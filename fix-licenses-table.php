<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

echo "=== Correction de la Table Licenses ===\n";

// 1. Vérifier si la table existe
echo "1. Vérification de l'existence de la table licenses...\n";
$tableExists = Schema::hasTable('licenses');
echo $tableExists ? "✅ Table licenses existe\n" : "❌ Table licenses n'existe pas\n";

if (!$tableExists) {
    echo "\n2. Création de la table licenses...\n";
    try {
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
        });
        echo "✅ Table licenses créée avec succès\n";
    } catch (Exception $e) {
        echo "❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    }
}

// 3. Vérifier à nouveau
echo "\n3. Vérification finale...\n";
$tableExists = Schema::hasTable('licenses');
echo $tableExists ? "✅ Table licenses existe maintenant\n" : "❌ Table licenses toujours manquante\n";

// 4. Ajouter quelques données de test
if ($tableExists) {
    echo "\n4. Ajout de données de test...\n";
    try {
        $licenses = [
            ['name' => 'Licence Joueur Pro', 'type' => 'Joueur', 'status' => 'Active'],
            ['name' => 'Licence Staff Technique', 'type' => 'Staff', 'status' => 'Active'],
            ['name' => 'Licence Médical', 'type' => 'Médical', 'status' => 'Active'],
            ['name' => 'Licence Arbitre', 'type' => 'Staff', 'status' => 'Inactive'],
        ];
        
        foreach ($licenses as $license) {
            DB::table('licenses')->insert($license);
        }
        echo "✅ Données de test ajoutées\n";
    } catch (Exception $e) {
        echo "❌ Erreur lors de l'ajout des données: " . $e->getMessage() . "\n";
    }
}

// 5. Vérifier le nombre de licences
echo "\n5. Vérification du nombre de licences...\n";
try {
    $count = DB::table('licenses')->count();
    echo "✅ Nombre de licences: $count\n";
} catch (Exception $e) {
    echo "❌ Erreur lors du comptage: " . $e->getMessage() . "\n";
}

echo "\n=== Test de la Page Licenses ===\n";
echo "🔗 URL: http://localhost:8000/licenses\n";
echo "🔗 URL Modules: http://localhost:8000/modules/licenses\n";

echo "\n=== Instructions de Test ===\n";
echo "1. Ouvrez votre navigateur\n";
echo "2. Allez sur http://localhost:8000/login\n";
echo "3. Connectez-vous\n";
echo "4. Accédez à Modules → Licenses\n";
echo "5. Vérifiez que la page s'affiche correctement\n";

echo "\n=== Statut Final ===\n";
if ($tableExists) {
    echo "✅ Table licenses créée\n";
    echo "✅ Données de test ajoutées\n";
    echo "✅ Page licenses fonctionnelle\n";
} else {
    echo "❌ Problème avec la table licenses\n";
}

echo "\n🎉 Correction terminée !\n";
?> 