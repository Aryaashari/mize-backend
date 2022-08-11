<?php

use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\PriorityController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SizeController;

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
    Route::get("/priority/{priority}", [PriorityController::class, "getDetailPriority"]);
    Route::post("/priority", [PriorityController::class, "createPriority"]);
    Route::put("/priority/{priority}", [PriorityController::class, "updatePriority"]);
    Route::delete("/priority/{priority}", [PriorityController::class, "deletePriority"]);

    // Group
    Route::get("/groups", [GroupController::class, "getGroup"]);
    Route::get("/groups/{group}", [GroupController::class, "getDetailGroup"]);
    Route::post("/groups", [GroupController::class, "createGroup"]);
    Route::put("/groups/{group}", [GroupController::class, "updateGroup"]);
    Route::delete("/groups/{group}", [GroupController::class, "deleteGroup"]);

});

// Auth
Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
