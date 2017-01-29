<?php
require __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class teamworkTest extends TestCase
{


    public function testReceivePostedData()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $response = $teamwork->receivePostedData('event=TASK%2ECREATED&objectId=1&accountId=2&userId=3');

        $this->assertArrayHasKey('event', $response);
        $this->assertArrayHasKey('objectId', $response);
        $this->assertArrayHasKey('accountId', $response);
        $this->assertArrayHasKey('userId', $response);
    }

    public function testPrepareTaskDescription()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $array = array(
            'todo-item' => array('id' => 12345)
        );

        $response = $teamwork->prepareTaskDescription(json_encode($array));

        $this->assertEquals('Include "[12345]" or "[Finish(ed) 12345]" to update this task when making a commit. Record time spent on the task by using: "[12345:30]"', $response);

    }

    public function testPrepareProjectTitle()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $array = array(
            'project' => array('id' => 12345, 'name' => 'my test project')
        );

        $response = $teamwork->prepareProjectTitle(json_encode($array));

        $this->assertEquals('[12345] my test project', $response);
    }

    public function testCleanTaskId()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $taskIdTestsArray = array('[Finished 12345]', '[finished 12345]', '[finish 12345]', '[ finish 12345 ] ', '[12345 finished]');

        foreach ($taskIdTestsArray as $taskId) {
            $response = $teamwork->cleanTaskId($taskId);
            $this->assertEquals('12345', $response);
        }
    }

    public function testCleanMessage()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $taskId = 12345;
        $messagesArray = array("[12345] updated a file", "updated a file [12345]", "[finished 12345] updated a file");

        foreach ($messagesArray as $message) {
            $response = $teamwork->cleanMessage($taskId, $message);
            $this->assertEquals('updated a file', $response);
        }

    }

    public function testFileChanges()
    {
        $teamwork = new teamwork\teamwork('test', 'test');

        $githubData = file_get_contents('./tests/samples/githubSeveralFiles.json');

        $githubdataArray = json_decode($githubData, true);

        $commit = $githubdataArray['commits'][0];

        $fileChanges = $teamwork->parseFileChanges($commit);

        $this->assertContains("**Added:**" . PHP_EOL . "sample.json" . PHP_EOL . "sample.txt" . PHP_EOL . "", $fileChanges);

        $this->assertContains("**Modified:**" . PHP_EOL . "README.md", $fileChanges);

    }

    public function testParseRepositoryURL()
    {
        $github = new \teamwork\github();

        $githubData = file_get_contents('./tests/samples/githubSeveralFiles.json');

        $githubdataArray = json_decode($githubData, true);

        $repositoryURL = $github->parseRepositoryURL($githubdataArray);

        $this->assertEquals("https://github.com/gordonmurray/repository-for-testing", $repositoryURL);

    }

    public function testParseCommitURL()
    {
        $github = new \teamwork\github();
        $teamwork = new teamwork\teamwork('test', 'test');

        $githubData = file_get_contents('./tests/samples/githubSeveralFiles.json');

        $githubdataArray = json_decode($githubData, true);

        $repository = $github->parseRepositoryURL($githubdataArray);

        $commit = $githubdataArray['commits'][0];

        $commitURL = $teamwork->parseCommitURL($repository, $commit);

        $this->assertEquals("https://github.com/gordonmurray/repository-for-testing/commit/4bb756aedac97e898846378cf7ec467f40a4277c", trim($commitURL));
        
    }
}