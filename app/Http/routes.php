<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->welcome();
});

/*
 * Point TASK.CREATED to this Route
 * The data looks like: event=TASK%2ECREATED&objectId=3488853&accountId=81120&userId=43244
 */
$app->post('receive_teamwork_task', 'App\Http\Controllers\TeamworkController@receiveTeamworkWebhook');