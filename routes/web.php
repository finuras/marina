<?php

use App\Http\Controllers\ProcessController;
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

Route::redirect('/', '/admin');


Route::get('/processes', [ProcessController::class, 'index'])->name('processes.index');
Route::get('/processes/mock', [ProcessController::class, 'mock'])->name('processes.mock');
Route::get('/processes/sse', [ProcessController::class, 'sse'])->name('processes.sse');
