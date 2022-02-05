<?php

use  App\Http\Controllers\UserAuthController;

use  App\Http\Controllers\AdminAuthController;

use  App\Http\Controllers\ReactController;

use Illuminate\Support\Facades\Route;
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

// Route::view('/{path?}', 'app');

// Route::get('/{path?}', [
//     'uses' => 'ReactController@show',
//     'as' => 'react',
//     'where' => ['path' => 'burn|mint|new']
// ]);

// Route::get('/project/{path?}', function () {
//     return view('app');
// })->where('path', '.*');

// 
// // Route::get('/{url?}', function () {
//     return view('app');
// })->where('', 'list');

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/test', function(){
    return view('test');
});

// User

Route::get('/', [UserAuthController::class, 'index'])->name('user.home') ->middleware('auth:web');

Route::get('/login', [UserAuthController::class, 'login'])->name('user.login');

Route::post('/login', [UserAuthController::class, 'handleLogin'])->name('user.handleLogin');

Route::get('/logout', [UserAuthController::class, 'index'])->name('user.logout');

Route::get('email', 'SampleController@email_check');
