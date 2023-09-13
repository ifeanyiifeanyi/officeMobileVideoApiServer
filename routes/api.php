<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\API\CommentController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ManageBlogController;
use App\Http\Controllers\API\VideoManagerController;
use App\Http\Controllers\API\ManageUserSubscriptionController;

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

Route::controller(UserController::class)->group(function () {

    Route::post('/register', 'register');
    Route::post('/login', 'login');

    Route::get('account/verify/{token}', 'verifyAccount')->name('user.verify');
    Route::get('confirm/verify', 'confirmVerify')->name('confirm.verify');

    // update user password
    Route::post('update-password', 'updatePassword');
    // forgot password
    Route::post('forgot-password', 'forgotPassword');
    Route::post('updateProfile', 'updateProfile');
});

Route::controller(ManageUserSubscriptionController::class)->group(function () {
    //active user plan
    Route::get('/userActivePlan/{id}', 'userActivePlan');
    // fetch paymentPlans
    Route::get('/paymentPlans', 'paymentPlan');
    // save and update route for online payment
    Route::post('/payment', 'savePayment');
});

Route::controller(VideoManagerController::class)->group(function () {
    // videos with associated category, rating, genre, parent control
    Route::get('/allvideo', 'allVideos');
    // videos by rating
    Route::get('/allvideobyrating', 'allVideosByRating');
    // videos by categories
    Route::get('/allvideobycategory', 'allVideosByCategory');
    // fetch a single video by id
    Route::get('/video/{id}', 'playVideo');
    // get thumbnail for carousel 
    Route::get('/thumbnail', 'BannerThumbnail');
    // video likes and dislikes
    Route::post('videolikes/likes', 'VideoLikes');
    Route::post('videodislikes/dislikes', 'VideoDislikes');
});

Route::controller(CategoryController::class)->group(function () {
    // categories
    Route::get('/categories', 'category');
    Route::get('/firstCategory', 'firstCategory');
    Route::get('/secondCategory', 'secondCategory');
    Route::get('/thirdCategory', 'thirdCategory');

});

Route::controller(ManageBlogController::class)->group(function () {
    // blog management api route
    Route::get('blog', 'blogContent');
    Route::post('blog/comment', 'blogComment');

});

Route::controller(CommentController::class)->group(function () {
    Route::post('comments', 'getComments');
});

//ngrok http http://localhost:8000 -> REMEMBER FOR your api url to your appp
