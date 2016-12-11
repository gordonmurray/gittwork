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

    public function testprepareTaskDescription()
    {
        $teamwork = new teamwork\teamwork('test','test');

        $array = array(
            'todo-item'=>array('id'=>12345)
        );

        $response = $teamwork->prepareTaskDescription(json_encode($array));

        $this->assertEquals($response, 'Include "[12345]" or "[Finish(ed) 12345]" to update this task when making a commit. Record time spent on the task by using: "[12345:30]"');

    }

}