<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\Admin\User\UserController;
use \App\Http\Controllers\Admin\Game\GameController;

$role = "admin";
Route::middleware(["can:{$role}", "auth"])->prefix($role)->group(function () use ($role) {

    Route::get("/", [GameController::class, "index"])->name("{$role}-home");

    // **************************************************************
    // user
    // **************************************************************
    Route::post("/user/changepassword", [UserController::class, "changepassword"])->name("{$role}-user-changepassword");
    // Route::post("/user/overwritepassword/{user_id}", [UserController::class, "overwritepassword"])->name("{$role}-user-overwritepassword");
    // Route::get("/user/create", [UserController::class, "create"])->name("{$role}-user-create");
    // Route::post("/user/createstore", [UserController::class, "createstore"])->name("{$role}-user-createstore");
    // Route::post("/user/deletestore/{user_id}", [UserController::class, "deletestore"])->name("{$role}-user-deletestore");
    // Route::get("/user/update/{user_id}", [UserController::class, "update"])->name("{$role}-user-update");
    // Route::post("/user/updatestore/{user_id}", [UserController::class, "updatestore"])->name("{$role}-user-updatestore");
    // Route::get("/user/index", [UserController::class, "index"])->name("{$role}-user-index");

    // **************************************************************
    // game
    // **************************************************************
    Route::post("/game/createstore", [GameController::class, "createstore"])->name("{$role}-game-createstore");
    Route::post("/game/enterstore/{game_id}", [GameController::class, "enterstore"])->name("{$role}-game-enterstore");
    Route::post("/game/playstore/{game_id}", [GameController::class, "playstore"])->name("{$role}-game-playstore");
    Route::get("/game/play/{game_id}", [GameController::class, "play"])->name("{$role}-game-play");
    Route::get("/game/ready/{game_id}", [GameController::class, "ready"])->name("{$role}-game-ready");
    Route::post("/game/pullcard/{game_id}", [GameController::class, "pullcard"])->name("{$role}-game-pullcard");
    Route::post("/game/pass/{game_id}", [GameController::class, "pass"])->name("{$role}-game-pass");
    Route::post("/game/color/{game_id}/{color}", [GameController::class, "color"])->name("{$role}-game-color");
    Route::post("/game/putcard/{game_id}/{cardname}", [GameController::class, "putcard"])->name("{$role}-game-putcard");
    Route::post("/game/challenge/{game_id}/{value}", [GameController::class, "challenge"])->name("{$role}-game-challenge");
});
