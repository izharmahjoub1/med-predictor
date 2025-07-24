#!/bin/bash

# Performance Management System Test Runner
# This script runs all tests for the sports performance management system

echo "🏃‍♂️ Running Performance Management System Tests"
echo "================================================"

# Set environment variables for testing
export APP_ENV=testing
export DB_CONNECTION=sqlite
export DB_DATABASE=:memory:

# Run unit tests
echo ""
echo "🧪 Running Unit Tests..."
echo "------------------------"
php artisan test --testsuite=Unit --verbose

# Run feature tests
echo ""
echo "🎯 Running Feature Tests..."
echo "---------------------------"
php artisan test --testsuite=Feature --verbose

# Run specific test categories
echo ""
echo "🔧 Running Service Tests..."
echo "---------------------------"
php artisan test tests/Unit/Services/ --verbose

echo ""
echo "🎨 Running Component Tests..."
echo "-----------------------------"
php artisan test tests/Unit/Components/ --verbose

echo ""
echo "🌐 Running API Tests..."
echo "----------------------"
php artisan test tests/Feature/Api/ --verbose

echo ""
echo "👁️ Running View Tests..."
echo "------------------------"
php artisan test tests/Feature/Views/ --verbose

echo ""
echo "🔄 Running Workflow Tests..."
echo "----------------------------"
php artisan test tests/Feature/PerformanceManagementWorkflowTest.php --verbose

# Generate test coverage report (if Xdebug is available)
if command -v phpdbg &> /dev/null; then
    echo ""
    echo "📊 Generating Coverage Report..."
    echo "-------------------------------"
    phpdbg -qrr vendor/bin/phpunit --coverage-html coverage-report
    echo "Coverage report generated in coverage-report/"
fi

echo ""
echo "✅ All tests completed!"
echo "======================" 