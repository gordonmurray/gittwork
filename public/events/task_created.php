<?php

// The actions to perform for TASK.CREATED

// Get the Task details
$task = $teamwork->get('tasks', $webHookData['objectId']);

// prepare a Description
$taskDescription = $teamwork->prepareTaskDescription($task);

$taskJson = json_encode(array('todo-item' => array('description' => $taskDescription)));

// Update the Task
$response = $teamwork->put('tasks', $webHookData['objectId'], $taskJson);

// log response
$teamwork->log('teamwork', json_decode($response, true));