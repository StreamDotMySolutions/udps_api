<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


// Homepage
use App\Http\Controllers\Homepage\{
    BannerController,
    DeejayController,
    TopicController,
    ChoiceController,
    VoteController,
    FooterController,
    ChatController,
    ModuleController
};

// role = Guest
use App\Http\Controllers\{
    RegisterController,
    AuthController
};

// role = User
use App\Http\Controllers\User\{
    AccountController,
};

// role = Admin
use App\Http\Controllers\Admin\{
    StreamingController,
};


Route::get('/start-streaming', [StreamingController::class, 'startStreaming']);
Route::get('/stop-streaming/{pid}', [StreamingController::class, 'stopStreaming']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {

    $user = $request->user(); // Get the authenticated user
    
    // Retrieve the user's role using Spatie
    $role = $user->roles->pluck('name')->first();

    $user['role'] = $role;

    return response()->json([
        'message' => 'Logged user info',
        'user' => $user,
        'role' => $role,
    ]);

});

// homepage
Route::get('/homepage/modules', [ModuleController::class, 'show']);
Route::get('/homepage/banners', [BannerController::class, 'show']);
Route::get('/homepage/deejays', [DeejayController::class, 'show']);
Route::get('/homepage/topics', [TopicController::class, 'show']);
Route::get('/homepage/choices/{topicId}', [ChoiceController::class, 'index']);
Route::get('/homepage/choice/{choice}', [ChoiceController::class, 'show']);
Route::post('/homepage/vote', [VoteController::class, 'store']);
//Route::get('/homepage/deejay', [DeejayController::class, 'show']);

// chatroom
Route::get('/homepage/chats', [ChatController::class, 'index']);
Route::post('/homepage/chats', [ChatController::class, 'store']);

// footer
Route::get('/homepage/footers/address', [FooterController::class, 'address']);
Route::get('/homepage/footers/{hashtag}', [FooterController::class, 'hashtag']);
Route::get('/homepage/footers/{footer}/show', [FooterController::class, 'show']);

//Route::get('/homepage/footers/{footer}', [FooterController::class, 'show']);

// Account Management ( logged in users )
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/account', [AccountController::class, 'show']);
    Route::put('/account/update', [AccountController::class, 'update']);
    Route::put('/account/change_password', [AccountController::class, 'changePassword']);
});


// Auth 
Route::post('/frontend/register', [RegisterController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

