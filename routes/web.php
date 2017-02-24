<?php

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

$app->get('foo', function () {
    return 'Hello World';
});

$app->get('/', function () {
    $response = use_json_value("Kanye      WEst");;
    return $response;
});



