<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // club_admin, club_manager, club_medical, association_admin, association_registrar, association_medical, system_admin
        'entity_type', // club, association (legacy field)
        'entity_id', // club_id or association_id (legacy field)
        'club_id', // direct club relationship
        'association_id', // direct association relationship
        'fifa_connect_id',
        'permissions',
        'status',
        'last_login_at',
        'login_count',
        'timezone',
        'language',
        'notifications_email',
        'notifications_sms',
        'profile_picture_url',
        'profile_picture_alt'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'last_login_at' => 'datetime',
            'login_count' => 'integer'
        ];
    }

    // Relationships
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'club_id');
    }

    public function association(): BelongsTo
    {
        return $this->belongsTo(Association::class, 'association_id');
    }

    // Legacy relationships for backward compatibility
    public function legacyClub(): BelongsTo
    {
        return $this->belongsTo(Club::class, 'entity_id');
    }

    public function legacyAssociation(): BelongsTo
    {
        return $this->belongsTo(Association::class, 'entity_id');
    }

    public function healthRecords(): HasMany
    {
        return $this->hasMany(HealthRecord::class);
    }

    // Role methods
    public function isClubAdmin(): bool
    {
        return $this->role === 'club_admin';
    }

    public function isClubManager(): bool
    {
        return $this->role === 'club_manager';
    }

    public function isClubMedical(): bool
    {
        return $this->role === 'club_medical';
    }

    public function isAssociationAdmin(): bool
    {
        return $this->role === 'association_admin';
    }

    public function isAssociationRegistrar(): bool
    {
        return $this->role === 'association_registrar';
    }

    public function isAssociationMedical(): bool
    {
        return $this->role === 'association_medical';
    }

    public function isSystemAdmin(): bool
    {
        return $this->role === 'system_admin';
    }

    public function isClubUser(): bool
    {
        return in_array($this->role, ['club_admin', 'club_manager', 'club_medical']);
    }

    public function isAssociationUser(): bool
    {
        return in_array($this->role, ['association_admin', 'association_registrar', 'association_medical']);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSystemAdmin()) {
            return true;
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function canAccessModule(string $module): bool
    {
        $modulePermissions = [
            'player_registration' => ['player_registration_access'],
            'competition_management' => ['competition_management_access'],
            'healthcare' => ['healthcare_access']
        ];

        if (!isset($modulePermissions[$module])) {
            return false;
        }

        foreach ($modulePermissions[$module] as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function getEntityName(): string
    {
        // Use new direct relationships first
        if ($this->club) {
            return $this->club->name;
        }

        if ($this->association) {
            return $this->association->name;
        }

        // Fallback to legacy relationships
        if ($this->entity_type === 'club' && $this->legacyClub) {
            return $this->legacyClub->name;
        }

        if ($this->entity_type === 'association' && $this->legacyAssociation) {
            return $this->legacyAssociation->name;
        }

        return 'System';
    }

    public function getEntityTypeDisplay(): string
    {
        return match($this->entity_type) {
            'club' => 'Club',
            'association' => 'Football Association',
            default => 'System'
        };
    }

    public function getRoleDisplay(): string
    {
        return match($this->role) {
            'club_admin' => 'Club Administrator',
            'club_manager' => 'Club Manager',
            'club_medical' => 'Club Medical Staff',
            'association_admin' => 'Association Administrator',
            'association_registrar' => 'Association Registrar',
            'association_medical' => 'Association Medical Director',
            'system_admin' => 'System Administrator',
            default => 'User'
        };
    }

    // Profile Picture Methods
    public function getProfilePictureUrl(): string
    {
        if ($this->profile_picture_url) {
            return $this->profile_picture_url;
        }
        
        // Return a default profile picture based on role or gender
        return asset('images/defaults/profile-picture.png');
    }

    public function getProfilePictureAlt(): string
    {
        if ($this->profile_picture_alt) {
            return $this->profile_picture_alt;
        }
        
        return $this->name . ' Profile Picture';
    }

    public function hasProfilePicture(): bool
    {
        return !empty($this->profile_picture_url);
    }

    public function getDisplayName(): string
    {
        return $this->name;
    }

    public function getInitials(): string
    {
        $names = explode(' ', $this->name);
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper(substr($name, 0, 1));
            }
        }
        
        return substr($initials, 0, 2);
    }
}
