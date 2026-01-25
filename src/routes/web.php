<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StripeWebhookController;
use App\Http\Controllers\VerifyEmailController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email/verify', [VerifyEmailController::class, 'index'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'verifyEmail'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerifyEmailController::class, 'resendVerificationEmail'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'destroy']);

Route::get('/', [ItemController::class, 'index']);
Route::get('/item/{item_id}', [ItemController::class, 'showDetail']);

Route::middleware('auth', 'verified')->prefix('sell')->group(function () {
    Route::get('/', [ItemController::class, 'showSellForm']);
    Route::post('/', [ItemController::class, 'sellItem']);
});


Route::middleware('auth', 'verified')->prefix('mypage')->group(function () {
    Route::get('/', [MypageController::class, 'showMypage']);
    Route::get('/profile', [MypageController::class, 'showProfile']);
    Route::post('/profile', [MypageController::class, 'updateProfile']);
    Route::get('/profile/item/{item_id}', [MypageController::class, 'redirectItem']);
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/chat-room/{chat_id}', [ChatController::class, 'showChatRoom']);
    Route::post('/chat-room/{chat_id}', [ChatController::class, 'sendMessage']);
    Route::patch('/chat-room/{message_id}', [ChatController::class, 'updateMessage']);
    Route::delete('/chat-room/{message_id}', [ChatController::class, 'deleteMessage']);
});

Route::post('/review', [ChatController::class, 'submitReview'])->middleware('auth', 'verified');
Route::post('/item/{item_id}/comment', [CommentController::class, 'makeComment'])->middleware('auth', 'verified');
Route::post('/item/{item_id}/like', [LikeController::class, 'likeItem'])->middleware('auth', 'verified');

Route::middleware('auth', 'verified')->prefix('purchase')->group(function () {
    Route::get('/{item_id}', [PurchaseController::class, 'showOrder']);
    Route::post('/{item_id}', [PurchaseController::class, 'completeOrder']);
    Route::get('/address/{item_id}', [PurchaseController::class, 'showShippingAddress']);
    Route::post('/address/{item_id}', [PurchaseController::class, 'updateShippingAddress']);
});

// Route::post('/stripe/webhook', [StripeWebhookController::class, 'handle']);
