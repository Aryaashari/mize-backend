<?php

use App\Http\Controllers\Api\UserController;
use App\Models\User;
use Illuminate\Http\Request;
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

});

// Auth
Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
