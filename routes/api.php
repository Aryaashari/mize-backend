<?php

use App\Http\Controllers\Api\PriorityController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SizeController;
use App\Models\Priority;

Route::middleware('auth:sanctum')->group(function () {

    // User
    Route::get("/user", [UserController::class, "getUser"]);
    Route::put("/user", [UserController::class, "editUser"]);
    Route::post("/user/photo", [UserController::class, "editUserPhoto"]);

    // Size
    Route::get("/sizes", [SizeController::class, "getSizes"]);
    Route::post("/sizes", [SizeController::class, "createSize"]);
    Route::get("/sizes/{size}", [SizeController::class, "detailSize"]);
    Route::put("/sizes/{size}", [SizeController::class, "updateSize"]);
    Route::delete("/sizes/{size}", [SizeController::class, "deleteSize"]);

    // Priority
    Route::get("/priority", [PriorityController::class, "getAllPriority"]);
    Route::get("/priority/size/{size}", [PriorityController::class, "getDetailPriority"]);

});

// Auth
Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
