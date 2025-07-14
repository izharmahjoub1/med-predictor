<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerLicense extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'club_id',
        'fifa_connect_id',
        'license_number',
        'license_type', // professional, amateur, youth, international
        'status', // pending, active, suspended, expired, revoked
        'issue_date',
        'expiry_date',
        'renewal_date',
        'issuing_authority',
        'license_category', // A, B, C, D, E
        'registration_number',
        'transfer_status', // registered, pending_transfer, transferred
        'contract_type', // permanent, loan, free_agent
        'contract_start_date',
        'contract_end_date',
        'wage_agreement',
        'bonus_structure',
        'release_clause',
        'medical_clearance',
        'fitness_certificate',
        'disciplinary_record',
        'international_clearance',
        'work_permit',
        'visa_status',
        'documentation_status',
        'approval_status',
        'approved_by',
        'approved_at',
        'rejection_reason',
        'notes',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'renewal_date' => 'date',
        'contract_start_date' => 'date',
        'contract_end_date' => 'date',
        'approved_at' => 'datetime',
        'wage_agreement' => 'decimal:2',
        'release_clause' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('license_type', $type);
    }

    public function scopeByClub($query, $clubId)
    {
        return $query->where('club_id', $clubId);
    }

    public function scopeExpiringSoon($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                    ->where('status', 'active');
    }

    // Methods
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->expiry_date > now();
    }

    public function isExpired(): bool
    {
        return $this->expiry_date <= now();
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    public function daysUntilExpiry(): int
    {
        return now()->diffInDays($this->expiry_date, false);
    }

    public function requiresRenewal(): bool
    {
        return $this->daysUntilExpiry() <= 30 && $this->status === 'active';
    }

    public function canTransfer(): bool
    {
        return $this->status === 'active' && 
               $this->transfer_status !== 'pending_transfer' &&
               $this->contract_end_date > now();
    }

    public function getLicenseStatusColor(): string
    {
        return match($this->status) {
            'active' => 'green',
            'pending' => 'yellow',
            'suspended' => 'red',
            'expired' => 'gray',
            'revoked' => 'red',
            default => 'gray'
        };
    }

    public function getLicenseStatusText(): string
    {
        return match($this->status) {
            'active' => 'Active',
            'pending' => 'Pending Approval',
            'suspended' => 'Suspended',
            'expired' => 'Expired',
            'revoked' => 'Revoked',
            default => 'Unknown'
        };
    }

    public function generateLicenseNumber(): string
    {
        $prefix = strtoupper(substr($this->club->country, 0, 3));
        $year = date('Y');
        $sequence = str_pad($this->id, 6, '0', STR_PAD_LEFT);
        
        return "{$prefix}-{$year}-{$sequence}";
    }

    public function validateLicense(): array
    {
        $errors = [];

        // Check if player has FIFA Connect ID
        if (!$this->fifa_connect_id) {
            $errors[] = 'Player must have a FIFA Connect ID';
        }

        // Check if license is not expired
        if ($this->isExpired()) {
            $errors[] = 'License has expired';
        }

        // Check if all required documents are provided
        if (!$this->medical_clearance) {
            $errors[] = 'Medical clearance is required';
        }

        if (!$this->fitness_certificate) {
            $errors[] = 'Fitness certificate is required';
        }

        // Check contract validity
        if ($this->contract_end_date && $this->contract_end_date <= now()) {
            $errors[] = 'Contract has expired';
        }

        return $errors;
    }

    public function approve($approvedBy): bool
    {
        $errors = $this->validateLicense();
        
        if (!empty($errors)) {
            return false;
        }

        $this->update([
            'status' => 'active',
            'approval_status' => 'approved',
            'approved_by' => $approvedBy,
            'approved_at' => now(),
            'issue_date' => now(),
            'license_number' => $this->generateLicenseNumber()
        ]);

        return true;
    }

    public function reject($reason, $rejectedBy): bool
    {
        $this->update([
            'status' => 'revoked',
            'approval_status' => 'rejected',
            'rejection_reason' => $reason,
            'approved_by' => $rejectedBy,
            'approved_at' => now()
        ]);

        return true;
    }

    public function suspend($reason): bool
    {
        $this->update([
            'status' => 'suspended',
            'notes' => $this->notes . "\nSuspended: " . $reason . " (" . now()->format('Y-m-d H:i:s') . ")"
        ]);

        return true;
    }

    public function renew($newExpiryDate): bool
    {
        $this->update([
            'expiry_date' => $newExpiryDate,
            'renewal_date' => now(),
            'status' => 'active'
        ]);

        return true;
    }

    public function transfer($newClubId): bool
    {
        if (!$this->canTransfer()) {
            return false;
        }

        $this->update([
            'club_id' => $newClubId,
            'transfer_status' => 'transferred',
            'notes' => $this->notes . "\nTransferred to club ID: {$newClubId} (" . now()->format('Y-m-d H:i:s') . ")"
        ]);

        return true;
    }
} 