<?php

use App\Http\Controllers\Group\AdvancedGroupController;
use App\Http\Controllers\Group\GroupManagementController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('auth:api')->group(function (){

    // Group management routes
    Route::post('group/create',[GroupManagementController::class,'create_group']);
    Route::get('group/get',[GroupManagementController::class,'get_groups']);
    Route::put('group/update',[GroupManagementController::class,'update_group']);
    Route::put('group/delete',[GroupManagementController::class,'delete_group']);

    // Group advanced routes
    Route::post('group/add-participants',[AdvancedGroupController::class,'addParticipants']);
    Route::post('group/remove-participants',[AdvancedGroupController::class,'removeParticipants']);
    Route::get('group/get-groups-for-user',[AdvancedGroupController::class,'getGroupsForUser']);

    // Message routes
    Route::post('message/send',[MessageController::class,'sendMessage']);
});
Route::get('/user/token', [UserController::class, 'generateToken']);



