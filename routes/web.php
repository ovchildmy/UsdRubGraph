<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataProcessing;

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

Route::get('/', [DataProcessing::class, 'index']);
Route::get('/update_date', [DataProcessing::class, 'ajax_get_data'])->name('update_date');  // ajax-запрос на подгрузку данных
