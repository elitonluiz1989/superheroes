<?php

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

Route::get('/', 'SuperheroesController@index');

Route::prefix('superheroes')->group(function() {
    Route::get('/{id}', 'SuperheroesController@get')->where('id', '[0-9]+')->name('superheroes.get');
    Route::match(['post', 'put'], '/', 'SuperheroesController@save')->name('superheroes.save');
    Route::any('/delete', 'SuperheroesController@delete')->name('superheroes.delete');

    Route::prefix('images')->group(function() {
        Route::post('/upload', 'SuperheroesImagesController@upload')->name('superheroes.images.upload');
        Route::any('/delete', 'SuperheroesImagesController@delete')->name('superheroes.images.delete');
    });

    Route::get('/image/{image}', 'SuperheroesImagesController@image')->name('superheroes.image');
});
