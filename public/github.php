<?php

require __DIR__ . '/../vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__ . '/../');

// read in Posted webhook data
$data = file_get_contents("php://input");

// parse webhook data in to an array
$webHookData = $github->receivePostedData($data);
