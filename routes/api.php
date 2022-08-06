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

});

// Auth
Route::post("/login", [UserController::class, "login"]);
Route::post("/register", [UserController::class, "register"]);
