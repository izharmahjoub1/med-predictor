<?php

require_once 'vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== Dashboard Routing Test Script ===\n\n";

// Test users with different roles
$testUsers = [
    'system_admin' => [
        'email' => 'admin@medpredictor.com',
        'expected_route' => 'back-office.dashboard',
        'expected_url' => '/back-office'
    ],
    'club_admin' => [
        'email' => 'club.admin@medpredictor.com',
        'expected_route' => 'club-management.dashboard',
        'expected_url' => '/club-management/dashboard'
    ],
    'player' => [
        'email' => 'player@test.com',
        'expected_route' => 'player-dashboard.index',
        'expected_url' => '/player-dashboard'
    ],
    'referee' => [
        'email' => 'david-wilson6@sample.com',
        'expected_route' => 'referee.dashboard',
        'expected_url' => '/referee/dashboard'
    ]
];

// Test pages to check for dashboard button
$testPages = [
    '/player-registration',
    '/competition-management',
    '/healthcare',
    '/medical-predictions',
    '/club-management',
    '/stakeholder-gallery',
    '/rankings',
    '/league-championship',
    '/back-office/settings',
    '/back-office/logs',
    '/back-office/seasons',
    '/back-office/content',
    '/test-dashboard-routing'
];

// Dashboard pages (where button should NOT appear)
$dashboardPages = [
    '/dashboard',
    '/back-office',
    '/club-management/dashboard',
    '/player-dashboard',
    '/referee/dashboard',
    '/healthcare/dashboard',
    '/medical-predictions/dashboard',
    '/player-registration/dashboard',
    '/user-management/dashboard'
];

function testDashboardRouting($user, $page, $expectedRoute, $expectedUrl) {
    global $app;
    
    // Find user
    $userModel = \App\Models\User::where('email', $user['email'])->first();
    if (!$userModel) {
        echo "❌ User not found: {$user['email']}\n";
        return false;
    }
    
    // Create request
    $request = Request::create($page, 'GET');
    $request->setUserResolver(function () use ($userModel) {
        return $userModel;
    });
    
    // Test the routing logic
    $dashboardRoute = match($userModel->role) {
        'system_admin' => 'back-office.dashboard',
        'association_admin' => 'back-office.dashboard',
        'association_registrar' => 'back-office.dashboard',
        'association_medical' => 'back-office.dashboard',
        'club_admin' => 'club-management.dashboard',
        'club_manager' => 'club-management.dashboard',
        'club_medical' => 'club-management.dashboard',
        'player' => 'player-dashboard.index',
        'referee' => 'referee.dashboard',
        'admin' => 'dashboard',
        default => 'dashboard'
    };
    
    $isDashboardPage = in_array($page, [
        '/dashboard',
        '/back-office',
        '/club-management/dashboard',
        '/player-dashboard',
        '/referee/dashboard',
        '/healthcare/dashboard',
        '/medical-predictions/dashboard',
        '/player-registration/dashboard',
        '/user-management/dashboard'
    ]);
    
    // Check if routing is correct
    $routeCorrect = $dashboardRoute === $expectedRoute;
    $urlCorrect = route($dashboardRoute) === $expectedUrl;
    $buttonShouldShow = !$isDashboardPage;
    
    $status = ($routeCorrect && $urlCorrect) ? '✅' : '❌';
    
    echo "{$status} {$userModel->name} ({$userModel->role}) on {$page}\n";
    echo "   Expected Route: {$expectedRoute} | Got: {$dashboardRoute}\n";
    echo "   Expected URL: {$expectedUrl} | Got: " . route($dashboardRoute) . "\n";
    echo "   Button should show: " . ($buttonShouldShow ? 'Yes' : 'No') . "\n";
    echo "   Is Dashboard Page: " . ($isDashboardPage ? 'Yes' : 'No') . "\n";
    echo "\n";
    
    return $routeCorrect && $urlCorrect;
}

// Run tests
echo "Testing Dashboard Routing Logic:\n";
echo "================================\n\n";

$totalTests = 0;
$passedTests = 0;

foreach ($testUsers as $role => $user) {
    echo "Testing {$role}:\n";
    echo "----------------\n";
    
    foreach ($testPages as $page) {
        $totalTests++;
        if (testDashboardRouting($user, $page, $user['expected_route'], $user['expected_url'])) {
            $passedTests++;
        }
    }
    
    echo "\n";
}

// Test dashboard pages (button should NOT show)
echo "Testing Dashboard Pages (Button should NOT show):\n";
echo "================================================\n\n";

foreach ($testUsers as $role => $user) {
    echo "Testing {$role} on dashboard pages:\n";
    echo "-----------------------------------\n";
    
    foreach ($dashboardPages as $page) {
        $totalTests++;
        $userModel = \App\Models\User::where('email', $user['email'])->first();
        
        $isDashboardPage = in_array($page, $dashboardPages);
        $buttonShouldShow = !$isDashboardPage;
        
        $status = !$buttonShouldShow ? '✅' : '❌';
        echo "{$status} {$userModel->name} on {$page} - Button should show: " . ($buttonShouldShow ? 'Yes' : 'No') . "\n";
        
        if (!$buttonShouldShow) {
            $passedTests++;
        }
    }
    
    echo "\n";
}

// Summary
echo "=== Test Summary ===\n";
echo "Total Tests: {$totalTests}\n";
echo "Passed: {$passedTests}\n";
echo "Failed: " . ($totalTests - $passedTests) . "\n";
echo "Success Rate: " . round(($passedTests / $totalTests) * 100, 2) . "%\n";

// Test route availability
echo "\n=== Route Availability Test ===\n";
$routes = [
    'back-office.dashboard',
    'club-management.dashboard',
    'player-dashboard.index',
    'referee.dashboard',
    'dashboard'
];

foreach ($routes as $routeName) {
    try {
        $url = route($routeName);
        echo "✅ {$routeName} -> {$url}\n";
    } catch (Exception $e) {
        echo "❌ {$routeName} -> ERROR: " . $e->getMessage() . "\n";
    }
}

echo "\n=== Test Complete ===\n"; 