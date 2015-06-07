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
 * Point a Webhook called TASK.CREATED in Teamwork to this Route
 * This will update a Task Description to include the Task ID so a developer will know what to use in their commit message
 * The data looks like: event=TASK%2ECREATED&objectId=3488853&accountId=81120&userId=43244
 */
$app->post('receive_teamwork_task', 'App\Http\Controllers\TeamworkController@receiveTeamworkWebhook');


/*
 * Point a POST request to this Route in Bitbucket
 * This will add the details from a Commit as a Comment to a Teamwork Task
 */
$app->post('receive_bitbucket_commit', 'App\Http\Controllers\BitbucketController@receiveBitbucketPost');