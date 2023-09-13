<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GenreController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommentsController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Admin\ActivePlanController;
use App\Http\Controllers\Admin\VideoRatingController;
use App\Http\Controllers\Admin\PaymentPlansController;
use App\Http\Controllers\Admin\ParentControlController;

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

Route::get('/', function () {
    return view('auth.login');
});



Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Admin routes
Route::group(['middleware' => ['auth', 'isAdmin']], function(){

    Route::controller(AdminController::class)->group(function(){
        Route::get('/dashboard', 'dashboard')->name('dashboard');
    });

    // movie categories
    Route::controller(CategoryController::class)->group(function(){
        Route::get('/categories', 'index')->name('category.view');
        Route::get('/create/categories', 'create')->name('category.create');
        Route::post('/store/categories', 'store')->name('category.store');
        Route::get('/edit/{id}/categories', 'edit')->name('category.edit');
        Route::post('/update/{id}/categories', 'update')->name('category.update');
        Route::delete('/delete/{id}/categories', 'destroy')->name('category.delete');    
    });
  

    // movie subscription plan managemn routes 
    Route::controller(PaymentPlansController::class)->group(function(){
        Route::get('/payment-plans', 'index')->name('payment.plan');
        Route::get('/create/payment-plans', 'create')->name('payment.create');
        Route::post('/save/c', 'save')->name('payment.save');
        Route::get('/edit/{id}/payment-plans', 'edit')->name('payment.edit');
        Route::post('/update/{id}/payment-plans', 'update')->name('payment.update');
        Route::delete('/delete/{id}/payment-plans', 'delete')->name('payment.delete');
    });


    // active plans routes
    // Route::get('payment-plans/plan/{id}', [PaymentPlansController::class,'activePlan'])->name('payment.check.plan');
    Route::controller(ActivePlanController::class)->group(function(){
        Route::get('active/payment-plans/{id}', 'index')->name('active.user.plan');
        Route::get('active/plans/{activePlans:id}', 'activeUserSubscription')->name('user.subscribe.plan');

    });


 

    // manage registered members 
    Route::controller(UsersController::class)->group(function(){
        Route::get('/users', 'index')->name('users.all');
        Route::get('/user/details/{id}', 'details')->name('users.detail');
        Route::get('/user/suspend/{id}', 'suspend')->name('users.suspend');
        Route::get('/user/activate/{id}', 'activate')->name('users.activate');
        Route::get('/user/grant/{id}', 'MakeAdmin')->name('make.admin');
        Route::get('/user/revoke/{id}', 'RevokeAdmin')->name('admin.revoke');
        Route::delete('/delete/user/{id}', 'destroy')->name('user.destroy');
        Route::get('/subscription/select/{userId}/{subscriptionId}', 'select')->name('subscription.select');
        Route::post('/subscription/select/pay', 'paySelected')->name('subscription.select.pay');
    });


    //vidoe genre manager
    Route::controller(GenreController::class)->group(function(){
        Route::get('/genre', 'index')->name('genre');
        Route::post('/genre', 'create')->name('genre.create');
        Route::get('/genre/{id}', 'edit')->name('genre.edit');
        Route::post('/genre/{id}', 'update')->name('genre.update');
        Route::delete('/genre/{id}', 'destroy')->name('genre.destroy');

    });


    // video rating manager
    Route::controller(VideoRatingController::class)->group(function(){
        Route::get('/video-ratings', 'index')->name('ratings');
        Route::post('/video-ratings', 'store')->name('ratings.store');
        Route::get('/video-ratings/{id}', 'show')->name('ratings.show');
        Route::post('/video-ratings/{id}', 'update')->name('ratings.update');
        Route::delete('/video-ratings/{id}', 'destroy')->name('ratings.destroy');
    });

    // video parent control manager
    Route::controller(ParentControlController::class)->group(function(){
        Route::get('/parent-control', 'index')->name('parentcontrol');
        Route::post('/parent-control', 'store')->name('parentcontrol.store');
        Route::get('/parent-control/{id}', 'show')->name('parentcontrol.show');
        Route::post('/parent-control/{id}', 'update')->name('parentcontrol.update');
        Route::delete('/parent-control/{id}', 'destroy')->name('parentcontrol.destroy');
    });



    //manage video routes
    Route::controller(VideoController::class)->group(function(){
        Route::get('/videos', 'index')->name('videos') ;
        Route::get('/videos/create', 'create')->name('create.videos') ;
        Route::post('/videos/store', 'store')->name('store.videos') ;
        Route::get('/videos/show/{id}', 'show')->name('show.videos') ;
        Route::get('/videos/edit/{id}', 'edit')->name('edit.videos') ;
        Route::delete('/videos/delete/{id}', 'destroy')->name('destory.videos') ;
        Route::post('/videos/update/{id}', 'update')->name('update.videos') ;
        Route::get('/videos/draft/{id}', 'draft')->name('draft.videos') ;
        Route::get('/videos/activate/{id}', 'activate')->name('activate.videos') ;
    });

    Route::controller(BlogController::class)->group(function(){
        Route::get('blog', 'index')->name('blog');
        Route::get('blog/create', 'show')->name('blog.show');
        Route::post('blog/store', 'store')->name('blog.store');
        Route::get('blog/edit/{slug}', 'edit')->name('blog.edit');
        Route::post('blog/update/{slug}', 'update')->name('blog.update');
        Route::delete('blog/delete/{slug}', 'destroy')->name('blog.delete');
    });

    Route::controller(CommentsController::class)->group(function(){
        Route::get('comments', 'index')->name('comments');
        Route::get('comment/view/{id}', 'show')->name('comment.view');
        Route::post('comment/update', 'updateStatus')->name('comment.update.status');
        Route::post('comment/reply', 'submitCommentReply')->name('comment.reply.message');
    });


    // Route::class
});

// User payment managment  route
Route::controller(PaymentController::class)->group(function(){
    Route::get('/payments', 'index')->name('payments');
});
