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

Route::controller(UserController::class)->group(function(){

    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::get('account/verify/{token}', 'verifyAccount')->name('user.verify');
    Route::get('confirm/verify', 'confirmVerify')->name('confirm.verify');

    // save and update route for online payment
    Route::post('/payment', 'savePayment');

    // categories
    Route::get('/categories', 'category');
    Route::get('/firstCategory', 'firstCategory');
    Route::get('/secondCategory', 'secondCategory');
    Route::get('/thirdCategory', 'thirdCategory');

    // videos with associated category, rating, genre, parent control
    Route::get('/allvideo', 'allVideos');
    // videos by rating
    Route::get('/allvideobyrating', 'allVideosByRating');
    // videos by categories
    Route::get('/allvideobycategory', 'allVideosByCategory');


    // get thumbnail for carousel 
    Route::get('/thumbnail', 'BannerThumbnail');

    // fetch a single video by id
    Route::get('/video/{id}', 'playVideo');

    // fetch paymentPlans
    Route::get('/paymentPlans', 'paymentPlan');
    //active user plan
    Route::get('/userActivePlan/{id}', 'userActivePlan');

    // video likes and dislikes
    Route::post('videolikes/likes', 'VideoLikes');
    Route::post('videodislikes/dislikes', 'VideoDislikes');

    // update user password
    Route::post('update-password', 'updatePassword');
    // forgot password
    Route::post('forgot-password', 'forgotPassword');
    Route::post('updateProfile', 'updateProfile');


    // blog management api route
    Route::get('blog', 'blogContent');
    Route::post('blog/comment', 'blogComment');


});

//ngrok http http://localhost:8000 -> REMEMBER FOR your api url to your appp
