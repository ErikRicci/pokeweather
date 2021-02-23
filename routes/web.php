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

    $cities = file_get_contents(base_path('public/cities.json'));
    $cities = json_decode($cities);
    $cities = $cities->cities;

    return view('welcome', [
        'cities' => $cities
    ]);
});
