<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class teamworkTest extends TestCase
{


    public function testreceivePostedData()
    {

        $teamwork = new teamwork\teamwork('test','test');

        $response = $teamwork->receivePostedData('event=TASK%2ECREATED&objectId=1&accountId=2&userId=3');

        $this->assertArrayHasKey('event', $response);
        $this->assertArrayHasKey('objectId', $response);
        $this->assertArrayHasKey('accountId', $response);
        $this->assertArrayHasKey('userId', $response);
    }

}