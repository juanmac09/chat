<?php

use App\Http\Controllers\Group\AdvancedGroupController;
use App\Http\Controllers\Group\GroupArchiveController;
use App\Http\Controllers\Group\GroupManagementController;
use App\Http\Controllers\Message\MessageController;
use App\Http\Controllers\Reports\GroupReportController;
use App\Http\Controllers\Reports\UserReportController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');



Route::middleware('jwt')->group(function (){

    // Group management routes
    Route::post('group/create',[GroupManagementController::class,'create_group']);
    Route::get('group/get',[GroupManagementController::class,'get_groups']);
    Route::put('group/update',[GroupManagementController::class,'update_group']);
    Route::put('group/delete',[GroupManagementController::class,'delete_group']);

    // Group advanced routes
    Route::post('group/add-participants',[AdvancedGroupController::class,'addParticipants']);
    Route::post('group/remove-participants',[AdvancedGroupController::class,'removeParticipants']);
    Route::get('group/get-groups-for-user',[AdvancedGroupController::class,'getGroupsForUser']);
    Route::get('group/get-participants-for-group',[AdvancedGroupController::class,'getParticipantsForGroup']);
    Route::get('group/get-group-for-id',[AdvancedGroupController::class,'getGroupForId']);

    // Archive group routes
    Route::get('group/archive/get-archive-general',[GroupArchiveController::class,'getGroupsFiledGeneral']);
    Route::get('group/archive/get-archive-specific',[GroupArchiveController::class,'getArchivedGroupsForAUser']);
    
    // Message routes
    Route::post('message/send',[MessageController::class,'sendMessage']);
    Route::get('message/get',[MessageController::class,'getMessages']);
    Route::get('message/get-history',[MessageController::class,'getMessageHistory']);
    Route::post('message/markAsRead',[MessageController::class,'markAsRead']);
    Route::post('message/markAllMessagesAsRead',[MessageController::class,'markAllMessagesAsRead']);
    Route::get('message/countMessagesNotRead',[MessageController::class,'countMessagesNotRead']);
    Route::get('message/countMessagesNotReadOfGroup',[MessageController::class,'countMessagesNotReadOfGroup']);

    // User routes
    Route::get('user/get-users',[UserController::class,'getUsersWithLastMessage']);
    Route::get('user/get-user',[UserController::class,'getUser']);
    Route::get('user/get-user-jwt',[UserController::class,'getUserDataWithJwt']);

    // Report routes
    // User
    Route::get('report/users/inactive-general',[UserReportController::class,'getGeneralInactiveUsers']);
    Route::get('report/users/inactive-specific',[UserReportController::class,'getSpecificInactiveUsers']);
    Route::get('report/users/active-general',[UserReportController::class,'getGeneralActiveUsers']);
    Route::get('report/users/active-specific',[UserReportController::class,'getSpecificActiveUsers']);
    // Group 
    Route::get('report/groups/inactive',[GroupReportController::class,'getInactiveGroups']);
    Route::get('report/groups/membership',[GroupReportController::class,'getNumberOfUsersPerGroup']);
});
Route::get('/user/token', [UserController::class, 'generateToken']);



