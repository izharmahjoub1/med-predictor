<?php

require_once 'vendor/autoload.php';

use App\Models\Player;
use Carbon\Carbon;

// Test avec un joueur existant
$player = Player::find(7);

if ($player) {
    echo "Joueur trouvé: {$player->first_name} {$player->last_name}\n";
    echo "Date de naissance brute: " . $player->date_of_birth . "\n";
    echo "Type de date_of_birth: " . gettype($player->date_of_birth) . "\n";
    
    if ($player->date_of_birth) {
        echo "Date de naissance est Carbon: " . ($player->date_of_birth instanceof Carbon ? 'OUI' : 'NON') . "\n";
        echo "Âge calculé avec diffInYears: " . $player->date_of_birth->diffInYears(now()) . "\n";
        echo "Type de l'âge: " . gettype($player->date_of_birth->diffInYears(now())) . "\n";
    } else {
        echo "Date de naissance est null\n";
    }
} else {
    echo "Joueur non trouvé\n";
}







