<?php
/**
 * Test Simple Variables Blade
 */

echo "🔍 TEST SIMPLE VARIABLES BLADE\n";
echo "==============================\n\n";

// Test 1: Créer une vue simple
$vueSimple = '<!DOCTYPE html>
<html>
<head><title>{{ $player->first_name }}</title></head>
<body>
<h1>{{ $player->first_name }} {{ $player->last_name }}</h1>
<img src="{{ $player->photo_url }}" alt="Photo">
</body>
</html>';

file_put_contents('test-vue-simple.blade.php', $vueSimple);
echo "✅ Vue simple créée\n";

// Test 2: Créer un contrôleur de test
$controleurTest = '<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestBladeController extends Controller
{
    public function test()
    {
        $player = new \stdClass();
        $player->first_name = "Test";
        $player->last_name = "Blade";
        $player->photo_url = "https://example.com/test.jpg";
        
        return view("test-vue-simple", compact("player"));
    }
}';

file_put_contents('app/Http/Controllers/TestBladeController.php', $controleurTest);
echo "✅ Contrôleur de test créé\n";

// Test 3: Ajouter une route de test
$routeTest = '
// Test Blade simple
Route::get("/test-blade", [App\Http\Controllers\TestBladeController::class, "test"])->name("test.blade");
';

echo "📝 Ajoutez cette route dans routes/web.php :\n$routeTest\n";

echo "\n🎯 PROCHAINES ÉTAPES :\n";
echo "1. Ajouter la route dans routes/web.php\n";
echo "2. Tester http://localhost:8001/test-blade\n";
echo "3. Vérifier si les variables Blade sont traitées\n";

echo "\n✅ Test Blade créé !\n";

