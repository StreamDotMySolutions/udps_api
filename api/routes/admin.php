<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\{
    UserController,
    BannerController,
    DeejayController,
    TopicController,
    ChoiceController,
    FooterController,
    ChatController,
    ModuleController,
    RestreamController,
};

// Protect all admin routes with the 'admin' role
Route::group(['middleware' => ['auth:sanctum','role:admin']], function () {

    // User Management 
    // GET /api/admin/users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'delete']);
    Route::get('/roles', [UserController::class, 'roles']);

    // Banner Management
    // GET /api/admin/banners
    Route::get('/banners', [BannerController::class, 'index']);
    Route::get('/banners/{banner}', [BannerController::class, 'show']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::put('/banners/{banner}', [BannerController::class, 'update']);
    Route::post('/banners', [BannerController::class, 'store']);
    Route::delete('/banners/{banner}', [BannerController::class, 'delete']);
    Route::get('/banners/ordering/{banner}', [BannerController::class, 'ordering']);

    // Deejay Management
    // GET /api/admin/deejays
    Route::get('/deejays', [DeejayController::class, 'index']);
    Route::get('/deejays/{deejay}', [DeejayController::class, 'show']);
    Route::post('/deejays', [DeejayController::class, 'store']);
    Route::put('/deejays/{deejay}', [DeejayController::class, 'update']);
    Route::post('/deejays', [DeejayController::class, 'store']);
    Route::delete('/deejays/{deejay}', [DeejayController::class, 'delete']);
    Route::get('/deejays/ordering/{deejay}', [DeejayController::class, 'ordering']);

    // Topic Management | poll
    // GET /api/admin/topics
    Route::get('/topics', [TopicController::class, 'index']);
    Route::get('/topics/{topic}', [TopicController::class, 'show']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::put('/topics/{topic}', [TopicController::class, 'update']);
    Route::post('/topics', [TopicController::class, 'store']);
    Route::delete('/topics/{topic}', [TopicController::class, 'delete']);
    Route::get('/topics/ordering/{topic}', [TopicController::class, 'ordering']);

    // Choice Management | poll
    // GET /api/admin/choices
    Route::get('/choices/{topicId}', [ChoiceController::class, 'index']);
    Route::get('/choices/{choice}/show', [ChoiceController::class, 'show']);
    Route::post('/choices', [ChoiceController::class, 'store']);
    Route::put('/choices/{choice}/update', [ChoiceController::class, 'update']);
    Route::post('/choices', [ChoiceController::class, 'store']);
    Route::delete('/choices/{choice}/delete', [ChoiceController::class, 'delete']);
    Route::get('/choices/ordering/{choice}', [ChoiceController::class, 'ordering']);

    // Footer Management | poll
    // GET /api/admin/footers
    Route::get('/footers', [FooterController::class, 'index']);
    Route::get('/footers/{footer}', [FooterController::class, 'show']);
    Route::post('/footers', [FooterController::class, 'store']);
    Route::put('/footers/{footer}', [FooterController::class, 'update']);
    Route::post('/footers', [FooterController::class, 'store']);
    Route::delete('/footers/{footer}', [FooterController::class, 'delete']);
    Route::get('/footers/ordering/{footer}', [FooterController::class, 'ordering']);

    // Chatroom management
    // GET /api/admin/chats
    Route::get('/chats', [ChatController::class, 'index']);
    Route::post('/chats', [ChatController::class, 'store']);
    Route::delete('/chats/{chat}', [ChatController::class, 'delete']);

    // Module management
    // GET /api/admin/modules
    Route::get('/modules', [ModuleController::class, 'index']);
    Route::get('/modules/ordering/{module}', [ModuleController::class, 'ordering']);
    Route::get('/modules/activation/{module}/{is_active}', [ModuleController::class, 'activation']);

    // Restream Management
    // GET /api/admin/restreams
    Route::get('/restreams', [RestreamController::class, 'index']);
    Route::get('/restreams/{restream}', [RestreamController::class, 'show']);
    Route::post('/restreams', [RestreamController::class, 'store']);
    Route::put('/restreams/{restream}', [RestreamController::class, 'update']);
    Route::post('/restreams', [RestreamController::class, 'store']);
    Route::delete('/restreams/{restream}', [RestreamController::class, 'delete']);
    Route::get('/restreams/ordering/{restream}', [RestreamController::class, 'ordering']);
    Route::get('/restreams/start-streaming/{restream}', [RestreamController::class, 'startStreaming']);
    Route::get('/restreams/stop-streaming/{restream}', [RestreamController::class, 'stopStreaming']);

});

