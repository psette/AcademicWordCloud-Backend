<?php

include_once dirname(__FILE__) . '/../app/Http/Controllers/Server.php';
include_once dirname(__FILE__) . '/../app/Http/Controllers/ACMServer.php';

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

/*
 * Define endpoint for searching based on the artist
 *
 */

$app->get('/search/{term}', 'Server@search');
$app->get('/getProgress/', 'Server@getProgress');


?>
