<?php

namespace App\Services;

use App\Models\User;
use App\Models\Club;
use App\Models\Association;
use App\Models\Player;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class FifaConnectService
{
    protected $baseUrl;
    protected $apiKey;
    protected $timeout;

    public function __construct()
    {
        $this->baseUrl = config('services.fifa.base_url', 'https://api.fifa.com/v1');
        $this->apiKey = config('services.fifa.api_key', 'demo-key');
        $this->timeout = config('services.fifa.timeout', 30);
    }

    /**
     * Generate a unique FIFA Connect ID for a user
     */
    public function generateConnectId(User $user): string
    {
        $prefix = $this->getRolePrefix($user->role);
        $entityCode = $this->getEntityCode($user);
        $timestamp = time();
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        
        return "FIFA-{$prefix}-{$entityCode}-{$timestamp}-{$random}";
    }

    /**
     * Sync user data with FIFA Connect
     */
    public function syncUser(User $user): bool
    {
        try {
            $payload = $this->prepareUserPayload($user);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/users/sync', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update user with FIFA Connect response data
                $user->update([
                    'fifa_connect_id' => $data['fifa_connect_id'] ?? $user->fifa_connect_id,
                    'fifa_sync_status' => 'synced',
                    'fifa_sync_date' => now(),
                ]);

                Log::info('User synced with FIFA Connect', [
                    'user_id' => $user->id,
                    'fifa_connect_id' => $user->fifa_connect_id,
                    'response' => $data
                ]);

                return true;
            } else {
                Log::error('FIFA Connect sync failed', [
                    'user_id' => $user->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                $user->update([
                    'fifa_sync_status' => 'failed',
                    'fifa_sync_date' => now(),
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('FIFA Connect sync exception', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            $user->update([
                'fifa_sync_status' => 'failed',
                'fifa_sync_date' => now(),
            ]);

            return false;
        }
    }

    /**
     * Get FIFA Connect status for a user
     */
    public function getUserStatus(string $fifaConnectId): ?array
    {
        try {
            $cacheKey = "fifa_user_status_{$fifaConnectId}";
            
            return Cache::remember($cacheKey, 300, function () use ($fifaConnectId) {
                $response = Http::timeout($this->timeout)
                    ->withHeaders([
                        'Authorization' => 'Bearer ' . $this->apiKey,
                        'Accept' => 'application/json',
                    ])
                    ->get($this->baseUrl . '/users/' . $fifaConnectId);

                if ($response->successful()) {
                    return $response->json();
                }

                return null;
            });
        } catch (\Exception $e) {
            Log::error('Failed to get FIFA Connect user status', [
                'fifa_connect_id' => $fifaConnectId,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * Validate FIFA Connect ID format
     */
    public function validateConnectId(string $fifaConnectId): bool
    {
        $pattern = '/^FIFA-[A-Z]{2,4}-[A-Z0-9]{3,8}-\d{10}-[A-Z0-9]{6}$/';
        return preg_match($pattern, $fifaConnectId) === 1;
    }

    /**
     * Get FIFA Connect connectivity status
     */
    public function getConnectivityStatus(): array
    {
        try {
            $response = Http::timeout(10)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/health');

            return [
                'status' => $response->successful() ? 'connected' : 'disconnected',
                'response_time' => $response->handlerStats()['total_time'] ?? null,
                'last_check' => now(),
                'details' => $response->successful() ? $response->json() : null,
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'last_check' => now(),
            ];
        }
    }

    /**
     * Get role prefix for FIFA Connect ID
     */
    private function getRolePrefix(string $role): string
    {
        $prefixes = [
            'system_admin' => 'SA',
            'association_admin' => 'AA',
            'association_registrar' => 'AR',
            'association_medical' => 'AM',
            'club_admin' => 'CA',
            'club_manager' => 'CM',
            'club_medical' => 'CMED',
            'club_staff' => 'CS',
        ];

        return $prefixes[$role] ?? 'USR';
    }

    /**
     * Get entity code for FIFA Connect ID
     */
    private function getEntityCode(User $user): string
    {
        if ($user->entity_type === 'club' && $user->club) {
            return strtoupper(substr($user->club->name, 0, 3));
        }

        if ($user->entity_type === 'association' && $user->association) {
            return strtoupper(substr($user->association->name, 0, 3));
        }

        return 'SYS';
    }

    /**
     * Prepare user payload for FIFA Connect sync
     */
    private function prepareUserPayload(User $user): array
    {
        $payload = [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'fifa_connect_id' => $user->fifa_connect_id,
            'permissions' => $user->permissions,
            'created_at' => $user->created_at->toISOString(),
        ];

        // Add entity information
        if ($user->entity_type === 'club' && $user->club) {
            $payload['entity'] = [
                'type' => 'club',
                'id' => $user->club->id,
                'name' => $user->club->name,
                'fifa_id' => $user->club->fifa_id,
            ];
        } elseif ($user->entity_type === 'association' && $user->association) {
            $payload['entity'] = [
                'type' => 'association',
                'id' => $user->association->id,
                'name' => $user->association->name,
                'fifa_id' => $user->association->fifa_id,
            ];
        }

        return $payload;
    }

    /**
     * Generate a unique FIFA Connect ID for a player
     */
    public function generatePlayerId(): \App\Models\FifaConnectId
    {
        $timestamp = time();
        $random = strtoupper(substr(md5(uniqid()), 0, 6));
        $fifaId = "PLR-{$timestamp}-{$random}";
        
        return \App\Models\FifaConnectId::create([
            'fifa_id' => $fifaId,
            'entity_type' => 'player',
            'status' => 'active'
        ]);
    }

    /**
     * Sync player data with FIFA Connect
     */
    public function syncPlayer(\App\Models\Player $player): bool
    {
        try {
            $payload = $this->preparePlayerPayload($player);
            
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($this->baseUrl . '/players/sync', $payload);

            if ($response->successful()) {
                $data = $response->json();
                
                // Update player with FIFA Connect response data
                $player->update([
                    'fifa_version' => $data['fifa_version'] ?? null,
                    'last_updated' => now(),
                ]);

                // Update FIFA Connect ID if provided
                if (isset($data['fifa_connect_id']) && $player->fifaConnectId) {
                    $player->fifaConnectId->update([
                        'fifa_id' => $data['fifa_connect_id'],
                        'status' => 'active'
                    ]);
                }

                Log::info('Player synced with FIFA Connect', [
                    'player_id' => $player->id,
                    'fifa_connect_id' => $player->fifaConnectId?->fifa_id,
                    'response' => $data
                ]);

                return true;
            } else {
                Log::error('FIFA Connect player sync failed', [
                    'player_id' => $player->id,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return false;
            }
        } catch (\Exception $e) {
            Log::error('FIFA Connect player sync exception', [
                'player_id' => $player->id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Prepare player payload for FIFA Connect sync
     */
    private function preparePlayerPayload(\App\Models\Player $player): array
    {
        $payload = [
            'player_id' => $player->id,
            'first_name' => $player->first_name,
            'last_name' => $player->last_name,
            'date_of_birth' => $player->date_of_birth->toISOString(),
            'nationality' => $player->nationality,
            'position' => $player->position,
            'jersey_number' => $player->jersey_number,
            'height' => $player->height,
            'weight' => $player->weight,
            'email' => $player->email,
            'phone' => $player->phone,
            'fifa_connect_id' => $player->fifaConnectId?->fifa_id,
            'created_at' => $player->created_at->toISOString(),
        ];

        // Add club information
        if ($player->club) {
            $payload['club'] = [
                'id' => $player->club->id,
                'name' => $player->club->name,
                'fifa_id' => $player->club->fifa_id,
            ];
        }

        return $payload;
    }

    /**
     * Bulk sync multiple players
     */
    public function bulkSyncPlayers(array $playerIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $players = Player::whereIn('id', $playerIds)->get();

        foreach ($players as $player) {
            try {
                if ($this->syncPlayer($player)) {
                    $results['success']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Failed to sync player {$player->id}: {$player->first_name} {$player->last_name}";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Exception syncing player {$player->id}: " . $e->getMessage();
            }
        }

        return $results;
    }

    /**
     * Bulk sync multiple users
     */
    public function bulkSyncUsers(array $userIds): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $users = User::whereIn('id', $userIds)->get();

        foreach ($users as $user) {
            if ($this->syncUser($user)) {
                $results['success']++;
            } else {
                $results['failed']++;
                $results['errors'][] = "Failed to sync user {$user->email}";
            }
        }

        return $results;
    }

    /**
     * Get FIFA Connect API statistics
     */
    public function getApiStats(): array
    {
        try {
            $response = Http::timeout($this->timeout)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Accept' => 'application/json',
                ])
                ->get($this->baseUrl . '/stats');

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Failed to get FIFA Connect API stats', [
                'error' => $e->getMessage()
            ]);

            return [];
        }
    }
} 