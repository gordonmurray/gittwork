<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../');

$teamwork = new teamwork\teamwork($_ENV['TEAMWORK_URL'], $_ENV['TEAMWORK_APIKEY']);

// read in Posted webhook data
$data = file_get_contents("php://input");

// parse webhook data in to an array
$webHookData = $teamwork->receivePostedData($data);

// log incoming data
$teamwork->log('teamwork', $webHookData);

// if a webhook event is passed, handle it
if(isset($webHookData['event']) && $webHookData['event']!='') {

    $eventFile = strtolower(str_replace(".", "_", $webHookData['event']));

    // call a file related to the event name, for example TASK.CREATED will look for task_created.php
    if (file_exists('events/' . $eventFile . '.php')) {
        require_once('events/' . $eventFile . '.php');
    }

}