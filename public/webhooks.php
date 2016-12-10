<?php
require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../');

$teamwork = new teamwork\teamwork($_ENV['TEAMWORK_URL'], $_ENV['TEAMWORK_APIKEY']);

// read in Posted webhook data
$data = file_get_contents("php://input");

// parse webhook data in to an array
$webHookData = $teamwork->receivePostedData($data);

// call different events depending on the webhook
switch ($webHookData['event']) {
    case "TASK.CREATED":
        // Get the Task, Prepare a new Description, Update the Task
        $task = $teamwork->get('tasks', $webHookData['objectId']);
        $taskDescription = $teamwork->prepareTaskDescription($task);
        $response = $teamwork->put('tasks', $webHookData['objectId'], $taskDescription);
        echo $response;
        break;
    case "TASK.COMPLETED":
        $task = $teamwork->get('task', $webHookData['objectId']);
        break;
    default:
        echo 'No data posted';
        break;
}
