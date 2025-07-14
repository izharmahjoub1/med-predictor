<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('player_licenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->onDelete('cascade');
            $table->foreignId('club_id')->constrained()->onDelete('cascade');
            $table->string('fifa_connect_id')->nullable();
            $table->string('license_number')->unique()->nullable();
            $table->enum('license_type', ['professional', 'amateur', 'youth', 'international']);
            $table->enum('status', ['pending', 'active', 'suspended', 'expired', 'revoked'])->default('pending');
            $table->date('issue_date')->nullable();
            $table->date('expiry_date');
            $table->date('renewal_date')->nullable();
            $table->string('issuing_authority');
            $table->enum('license_category', ['A', 'B', 'C', 'D', 'E']);
            $table->string('registration_number')->nullable();
            $table->enum('transfer_status', ['registered', 'pending_transfer', 'transferred'])->default('registered');
            $table->enum('contract_type', ['permanent', 'loan', 'free_agent']);
            $table->date('contract_start_date');
            $table->date('contract_end_date');
            $table->decimal('wage_agreement', 15, 2);
            $table->text('bonus_structure')->nullable();
            $table->decimal('release_clause', 15, 2)->nullable();
            $table->boolean('medical_clearance')->default(false);
            $table->boolean('fitness_certificate')->default(false);
            $table->text('disciplinary_record')->nullable();
            $table->boolean('international_clearance')->default(false);
            $table->boolean('work_permit')->default(false);
            $table->string('visa_status')->nullable();
            $table->enum('documentation_status', ['pending', 'complete', 'incomplete'])->default('pending');
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['club_id', 'status']);
            $table->index(['player_id', 'status']);
            $table->index(['fifa_connect_id']);
            $table->index(['license_type']);
            $table->index(['expiry_date']);
            $table->index(['approval_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_licenses');
    }
};
