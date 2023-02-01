<?php

use App\Http\Controllers\BackUpRouter;
use App\Http\Controllers\Path;
use App\Http\Controllers\Router;
use App\Http\Controllers\Main;
use App\Http\Controllers\UpdateRouter;
use App\Http\Controllers\UserRouter;
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

Route::get('/', [Main::class,'index'])->middleware('auth');
Route::get('/check_status_router_stream/{id}', [Main::class,'check_router_status_stream'])->middleware('auth');

Route::resource('router', Router::class)->middleware('auth');

Route::get('/update_router', [UpdateRouter::class,'index'])->middleware('auth');
Route::get('/check_update_router_stream/{id}', [UpdateRouter::class,'check_update_stream'])->middleware('auth');
Route::get('/update_router_stream/{id}', [UpdateRouter::class,'update_router_stream'])->middleware('auth');
Route::get('/update_router/{id}/update', [UpdateRouter::class,'update'])->middleware('auth');

Route::get('/user_router', [UserRouter::class,'index'])->middleware('auth');
Route::get('/user_router_update_password', [UserRouter::class,'update_password'])->middleware('auth');

Route::get('/path', [Path::class,'store'])->middleware('auth');



//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
