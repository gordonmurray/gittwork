<?php

// The actions to perform for TASK.CREATED

// Get the Task details
$task = $teamwork->get('tasks', $webHookData['objectId']);

// prepare a Description
$taskDescription = $teamwork->prepareTaskDescription($task);

// Update the Task
$response = $teamwork->put('tasks', $webHookData['objectId'], $taskDescription);

echo $response;