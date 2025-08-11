<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== Création des Tables Manquantes ===\n";

// Table appointments
if (!Schema::hasTable('appointments')) {
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->string('fifa_connect_id');
        $table->unsignedBigInteger('athlete_id')->nullable();
        $table->unsignedBigInteger('doctor_id')->nullable();
        $table->string('title');
        $table->text('description')->nullable();
        $table->datetime('appointment_date');
        $table->string('status')->default('scheduled');
        $table->string('type');
        $table->string('location')->nullable();
        $table->text('notes')->nullable();
        $table->json('metadata')->nullable();
        $table->timestamps();
    });
    echo "✅ Table appointments créée\n";
} else {
    echo "✅ Table appointments existe déjà\n";
}

// Table uploaded_documents
if (!Schema::hasTable('uploaded_documents')) {
    Schema::create('uploaded_documents', function (Blueprint $table) {
        $table->id();
        $table->string('fifa_connect_id');
        $table->unsignedBigInteger('athlete_id')->nullable();
        $table->unsignedBigInteger('uploaded_by');
        $table->string('filename');
        $table->string('original_filename');
        $table->string('file_path');
        $table->string('mime_type');
        $table->bigInteger('file_size');
        $table->string('document_type');
        $table->string('title');
        $table->text('description')->nullable();
        $table->json('ai_analysis')->nullable();
        $table->json('metadata')->nullable();
        $table->string('status')->default('pending');
        $table->timestamps();
    });
    echo "✅ Table uploaded_documents créée\n";
} else {
    echo "✅ Table uploaded_documents existe déjà\n";
}

echo "\n=== Test des Tables ===\n";
try {
    $appointmentCount = DB::table('appointments')->count();
    echo "✅ Table appointments: $appointmentCount enregistrements\n";
} catch (Exception $e) {
    echo "❌ Erreur table appointments: " . $e->getMessage() . "\n";
}

try {
    $documentCount = DB::table('uploaded_documents')->count();
    echo "✅ Table uploaded_documents: $documentCount enregistrements\n";
} catch (Exception $e) {
    echo "❌ Erreur table uploaded_documents: " . $e->getMessage() . "\n";
}

echo "\n=== Test du Secrétariat ===\n";
echo "🎯 Le secrétariat devrait maintenant fonctionner !\n";
echo "🔗 URL: http://localhost:8000/secretary/dashboard\n";
echo "👤 Utilisateur: secretary@test.com / password\n";
?> 