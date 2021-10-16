<?php

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

Route::get('/', function () {
    return view('welcome');
});
Route::get('/smallWorldTestHome', function () {
    return view('smallWorldHome');
});

Route::post('loadDataFromExcel','\App\Http\Controllers\readCsv@loadDataFromExcel');
Route::post('launchScoreRankingOperation','\App\Http\Controllers\readCsv@calculateTotalRanking');
