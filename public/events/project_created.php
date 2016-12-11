<?php

// The actions to perform for PROJECT.CREATED

// Get the Task details
$project = $teamwork->get('projects', $webHookData['objectId']);

// prepare a new Project Title
$projectTitle = $teamwork->prepateProjectTitle($project);

$projectJson = json_encode(array('project'=>array('name'=>$projectTitle)));

// Update the Project
$response = $teamwork->put('projects', $webHookData['objectId'], $projectJson);

echo $response;