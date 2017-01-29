<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../');

$github = new teamwork\github();
$teamwork = new teamwork\teamwork($_ENV['TEAMWORK_URL'], $_ENV['TEAMWORK_APIKEY']);

// read in Posted webhook data
$data = file_get_contents("php://input");

// parse webhook data in to an array
$webHookData = $github->receivePostedData($data);

// log incoming data
$github->log('github', $webHookData);

// parse repository address
$repository = $github->parseRepositoryURL($webHookData);

// parse the data, only need the 'commits' section
$commits = $github->parseGithubData($webHookData);

// add comment(s) to Teamwork
$response = $teamwork->addCommitComment($repository, $commits);

// log response
$teamwork->log('github', $response);