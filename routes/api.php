<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Route::get('/sanctum/csrf-cookie', function () {
//     return ['csrf_token' => csrf_token()];
// });

// Route::middleware('auth:api')->group(function(){});

Route::post('/register', [UserController::class, 'register']);

Route::post('/login', [UserController::class, 'login']);

Route::get('account/verify/{token}', [UserController::class, 'verifyAccount'])->name('user.verify');
Route::get('confirm/verify', [UserController::class, 'confirmVerify'])->name('confirm.verify');



// save and update route for online payment
Route::post('/payment', [UserController::class, 'savePayment']);

// categories
Route::get('/categories', [UserController::class, 'category']);
Route::get('/firstCategory', [UserController::class, 'firstCategory']);
Route::get('/secondCategory', [UserController::class, 'secondCategory']);
Route::get('/thirdCategory', [UserController::class, 'thirdCategory']);




// videos with associated category, rating, genre, parent control
Route::get('/allvideo', [UserController::class, 'allVideos']);

// videos by rating
Route::get('/allvideobyrating', [UserController::class, 'allVideosByRating']);

// videos by categories
Route::get('/allvideobycategory', [UserController::class, 'allVideosByCategory']);

// get thumbnail for carousel 
Route::get('/thumbnail', [UserController::class, 'BannerThumbnail']);

// fetch a single video by id
Route::get('/video/{id}', [UserController::class, 'playVideo']);

// fetch paymentPlans
Route::get('/paymentPlans', [UserController::class, 'paymentPlan']);

//active user plan
Route::get('/userActivePlan/{id}', [UserController::class, 'userActivePlan']);

// video likes and dislikes
Route::post('videolikes/likes', [UserController::class, 'VideoLikes']);
Route::post('videodislikes/dislikes', [UserController::class, 'VideoDislikes']);

// update user password
Route::post('update-password', [UserController::class, 'updatePassword']);

// forgot password
Route::post('forgot-password', [UserController::class, 'forgotPassword']);

Route::post('updateProfile', [UserController::class, 'updateProfile']);


Route::get('blog', [UserController::class, 'blogContent']);

Route::post('blog/comment', [UserController::class, 'blogComment']);



//ngrok http http://localhost:8000 -> REMEMBER FOR your api url to your appp
