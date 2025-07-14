<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
Route::get("/fifa/connectivity", function () { return response()->json(["connected" => true, "timestamp" => now()->toISOString(), "service" => "FIFA Connect ID v3.3", "status" => "Test mode - Ready for integration"]); });
Route::get("/fifa/test", function () { return response()->json(["message" => "FIFA Connect API test route working!", "timestamp" => now()->toISOString(), "status" => "success", "version" => "3.3"]); });
use App\Http\Controllers\FifaConnectController;
Route::prefix("fifa")->group(function () {
    Route::get("/connectivity", [FifaConnectController::class, "checkConnectivity"]);
    Route::post("/generate-id", [FifaConnectController::class, "generateId"]);
    Route::post("/validate-id", [FifaConnectController::class, "validateId"]);
    Route::post("/search-duplicates", [FifaConnectController::class, "searchDuplicates"]);
    Route::post("/sync-entity", [FifaConnectController::class, "syncEntity"]);
    Route::get("/entity/{fifa_connect_id}", [FifaConnectController::class, "getEntityData"]);
    Route::get("/entity/{fifa_connect_id}/history", [FifaConnectController::class, "getEntityHistory"]);
    Route::get("/statistics", [FifaConnectController::class, "getStatistics"]);
});
