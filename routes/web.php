<?php

include_once dirname(__FILE__) . '/../app/Http/Controllers/SearchController.php';


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

// $app->get('/foo{artists}', function () {
// $app->put('/foo/{artists}', function () {
//     return \App\Http\Controllers\SearchController::searchArtists();
// });

$app->get('/search/{artist}', 'SearchController@searchArtists');







?>