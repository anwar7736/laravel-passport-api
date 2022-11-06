<?php

//Request header for api access
// header('Access-Control-Allow-Origin: *');
// header('Access-Control-Allow-Methods: *');
// header('Access-Control-Allow-Headers: *');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Backend
use App\Http\Controllers\Backend\LoginController;
use App\Http\Controllers\Backend\PasswordController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\ProductController;


//Frontend
use App\Http\Controllers\Frontend\UserAuthController;
use App\Http\Controllers\Frontend\OrderController;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1/')->group(function(){
    //Backend
    Route::prefix('admin/')->group(function(){
        //Authenticate and unauthenticate both user
        //login
        Route::post('login', [LoginController::class, 'login']);
        //forgotten password
        Route::post('email-verification', [PasswordController::class, 'EmailVerify']);
        Route::post('reset-password', [PasswordController::class, 'ResetPassword']);
       
        Route::get('login', function(){
            return 'Unauthenticate action.';
        })->name('login');
        Route::resource('products', ProductController::class)->except(['create', 'edit']);

        //Only for authenticate user
        Route::middleware('auth:api')->group(function(){
            Route::get('logout', [LoginController::class, 'logout']);
            Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
            

            
        });
    }); 

    //Frontend
    Route::controller(UserAuthController::class)->group(function(){
        Route::post('user-login', 'UserLogin');
        Route::post('user-register', 'UserRegister');
    });

     //Only for authenticate user
     Route::middleware('auth:api')->group(function(){
        Route::get('user-logout', [UserAuthController::class, 'UserLogout']);
        Route::post('checkout',   [OrderController::class, 'checkout']);
        Route::get('order_details/{id}',   [OrderController::class, 'order_details']);
    });

});
