<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Competition\StoreCompetitionRequest;
use App\Http\Requests\Api\V1\Competition\UpdateCompetitionRequest;
use App\Http\Resources\Api\V1\CompetitionResource;
use App\Http\Resources\Api\V1\CompetitionCollection;
use App\Models\Competition;
use App\Models\Season;
use App\Models\Standing;
use App\Models\Team;
use App\Services\AuditTrailService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CompetitionController extends Controller
{
    use AuthorizesRequests;

    protected $auditTrailService;

    public function __construct(AuditTrailService $auditTrailService)
    {
        $this->auditTrailService = $auditTrailService;
    }

    /**
     * Display a listing of competitions
     */
    public function index(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Competition::class);

        $query = Competition::query()
            ->with(['association', 'seasons', 'teams'])
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->association_id, function ($query, $associationId) {
                $query->where('association_id', $associationId);
            })
            ->when($request->type, function ($query, $type) {
                $query->where('type', $type);
            })
            ->when($request->season, function ($query, $season) {
                $query->whereHas('seasons', function ($q) use ($season) {
                    $q->where('name', 'like', "%{$season}%");
                });
            });

        $competitions = $query->orderBy('name')->paginate($request->per_page ?? 15);

        return response()->json([
            'message' => 'Competitions retrieved successfully',
            'data' => new CompetitionCollection($competitions)
        ]);
    }

    /**
     * Store a newly created competition
     */
    public function store(StoreCompetitionRequest $request): JsonResponse
    {
        $this->authorize('create', Competition::class);

        $competition = Competition::create($request->validated());

        // Create audit trail
        $this->auditTrailService->log(
            'competition_created',
            'Competition created',
            $competition,
            $request->user()
        );

        return response()->json([
            'message' => 'Competition created successfully',
            'data' => new CompetitionResource($competition->load(['association', 'seasons']))
        ], 201);
    }

    /**
     * Display the specified competition
     */
    public function show(Competition $competition): JsonResponse
    {
        $this->authorize('view', $competition);

        return response()->json([
            'message' => 'Competition retrieved successfully',
            'data' => new CompetitionResource($competition->load([
                'association', 
                'seasons', 
                'teams', 
                'matches' => function ($query) {
                    $query->latest()->take(10);
                }
            ]))
        ]);
    }

    /**
     * Update the specified competition
     */
    public function update(UpdateCompetitionRequest $request, Competition $competition): JsonResponse
    {
        $this->authorize('update', $competition);

        $oldData = $competition->toArray();
        $competition->update($request->validated());

        // Create audit trail
        $this->auditTrailService->log(
            'competition_updated',
            'Competition updated',
            $competition,
            $request->user(),
            $oldData
        );

        return response()->json([
            'message' => 'Competition updated successfully',
            'data' => new CompetitionResource($competition->load(['association', 'seasons']))
        ]);
    }

    /**
     * Remove the specified competition
     */
    public function destroy(Competition $competition): JsonResponse
    {
        $this->authorize('delete', $competition);

        // Check if competition has active matches
        if ($competition->matches()->where('match_status', '!=', 'completed')->exists()) {
            return response()->json([
                'message' => 'Cannot delete competition with active matches'
            ], 422);
        }

        $competition->delete();

        // Create audit trail
        $this->auditTrailService->log(
            'competition_deleted',
            'Competition deleted',
            $competition,
            request()->user()
        );

        return response()->json([
            'message' => 'Competition deleted successfully'
        ]);
    }

    /**
     * Get competition standings
     */
    public function standings(Competition $competition, Request $request): JsonResponse
    {
        $this->authorize('view', $competition);

        $seasonId = $request->season_id ?? $competition->seasons()->latest()->first()?->id;

        if (!$seasonId) {
            return response()->json([
                'message' => 'No active season found for this competition'
            ], 404);
        }

        $standings = Standing::where('competition_id', $competition->id)
            ->where('season_id', $seasonId)
            ->with(['team'])
            ->orderBy('points', 'desc')
            ->orderBy('goal_difference', 'desc')
            ->orderBy('goals_for', 'desc')
            ->get();

        return response()->json([
            'message' => 'Competition standings retrieved successfully',
            'data' => [
                'competition' => new CompetitionResource($competition),
                'season_id' => $seasonId,
                'standings' => $standings->map(function ($standing, $index) {
                    return [
                        'position' => $index + 1,
                        'team' => $standing->team->name,
                        'team_id' => $standing->team_id,
                        'played' => $standing->played,
                        'won' => $standing->won,
                        'drawn' => $standing->drawn,
                        'lost' => $standing->lost,
                        'goals_for' => $standing->goals_for,
                        'goals_against' => $standing->goals_against,
                        'goal_difference' => $standing->goal_difference,
                        'points' => $standing->points
                    ];
                })
            ]
        ]);
    }

    /**
     * Get competition seasons
     */
    public function seasons(Competition $competition): JsonResponse
    {
        $this->authorize('view', $competition);

        $seasons = $competition->seasons()
            ->orderBy('start_date', 'desc')
            ->get();

        return response()->json([
            'message' => 'Competition seasons retrieved successfully',
            'data' => [
                'competition' => new CompetitionResource($competition),
                'seasons' => $seasons
            ]
        ]);
    }

    /**
     * Get competition teams
     */
    public function teams(Competition $competition): JsonResponse
    {
        $this->authorize('view', $competition);

        $teams = $competition->teams()
            ->with(['club', 'players'])
            ->orderBy('name')
            ->get();

        return response()->json([
            'message' => 'Competition teams retrieved successfully',
            'data' => [
                'competition' => new CompetitionResource($competition),
                'teams' => $teams
            ]
        ]);
    }

    /**
     * Get competition statistics
     */
    public function statistics(Competition $competition, Request $request): JsonResponse
    {
        $this->authorize('view', $competition);

        $seasonId = $request->season_id ?? $competition->seasons()->latest()->first()?->id;

        if (!$seasonId) {
            return response()->json([
                'message' => 'No active season found for this competition'
            ], 404);
        }

        $matches = $competition->matches()
            ->where('season_id', $seasonId)
            ->get();

        $statistics = [
            'total_matches' => $matches->count(),
            'completed_matches' => $matches->where('match_status', 'completed')->count(),
            'scheduled_matches' => $matches->where('match_status', 'scheduled')->count(),
            'total_teams' => $competition->teams()->count(),
            'total_goals' => $matches->sum(function ($match) {
                return ($match->home_score ?? 0) + ($match->away_score ?? 0);
            }),
            'average_goals_per_match' => $matches->where('match_status', 'completed')->count() > 0 
                ? round($matches->sum(function ($match) {
                    return ($match->home_score ?? 0) + ($match->away_score ?? 0);
                }) / $matches->where('match_status', 'completed')->count(), 2)
                : 0,
            'top_scorers' => $this->getTopScorers($competition, $seasonId),
            'most_assists' => $this->getMostAssists($competition, $seasonId),
            'clean_sheets' => $this->getCleanSheets($competition, $seasonId)
        ];

        return response()->json([
            'message' => 'Competition statistics retrieved successfully',
            'data' => [
                'competition' => new CompetitionResource($competition),
                'season_id' => $seasonId,
                'statistics' => $statistics
            ]
        ]);
    }

    /**
     * Add team to competition
     */
    public function addTeam(Request $request, Competition $competition): JsonResponse
    {
        $this->authorize('update', $competition);

        $request->validate([
            'team_id' => 'required|exists:teams,id',
            'season_id' => 'required|exists:seasons,id'
        ]);

        $team = Team::findOrFail($request->team_id);

        // Check if team already in competition
        if ($competition->teams()->where('team_id', $team->id)->exists()) {
            return response()->json([
                'message' => 'Team is already in this competition'
            ], 422);
        }

        $competition->teams()->attach($team->id, [
            'season_id' => $request->season_id,
            'joined_at' => now()
        ]);

        // Create audit trail
        $this->auditTrailService->log(
            'team_added_to_competition',
            "Team {$team->name} added to competition",
            $competition,
            $request->user()
        );

        return response()->json([
            'message' => 'Team added to competition successfully'
        ]);
    }

    /**
     * Remove team from competition
     */
    public function removeTeam(Request $request, Competition $competition): JsonResponse
    {
        $this->authorize('update', $competition);

        $request->validate([
            'team_id' => 'required|exists:teams,id'
        ]);

        $team = Team::findOrFail($request->team_id);

        // Check if team has active matches
        if ($competition->matches()
            ->where(function ($query) use ($team) {
                $query->where('home_team_id', $team->id)
                    ->orWhere('away_team_id', $team->id);
            })
            ->where('match_status', '!=', 'completed')
            ->exists()) {
            return response()->json([
                'message' => 'Cannot remove team with active matches'
            ], 422);
        }

        $competition->teams()->detach($team->id);

        // Create audit trail
        $this->auditTrailService->log(
            'team_removed_from_competition',
            "Team {$team->name} removed from competition",
            $competition,
            $request->user()
        );

        return response()->json([
            'message' => 'Team removed from competition successfully'
        ]);
    }

    /**
     * Get top scorers for competition
     */
    private function getTopScorers(Competition $competition, $seasonId): array
    {
        // This would typically query match events for goals
        // For now, return empty array as placeholder
        return [];
    }

    /**
     * Get most assists for competition
     */
    private function getMostAssists(Competition $competition, $seasonId): array
    {
        // This would typically query match events for assists
        // For now, return empty array as placeholder
        return [];
    }

    /**
     * Get clean sheets for competition
     */
    private function getCleanSheets(Competition $competition, $seasonId): array
    {
        // This would calculate clean sheets based on match results
        // For now, return empty array as placeholder
        return [];
    }
}
