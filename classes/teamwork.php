<?php

namespace teamwork;

class teamwork
{
    public function __construct($teamworkURL, $apiKey)
    {
        $this->teamworkURL = $teamworkURL;
        $this->apiKey = $apiKey;
    }

    /**
     * Parse the incoming raw data in to an array
     *
     * @param $postedData
     * @return mixed
     */
    public function receivePostedData($postedData)
    {
        mb_parse_str($postedData, $result);

        return $result;
    }

    /**
     * Prepare the Task Description to use
     *
     * @param $task
     * @return string
     */
    public function prepareTaskDescription($task)
    {
        $task = json_decode($task, true);
        $taskId = $task['todo-item']['id'];

        $description = "Include \"[$taskId]\" or \"[Finish(ed) $taskId]\" to update this task when making a commit. Record time spent on the task by using: \"[$taskId:30]\"";

        return $description;
    }

    /**
     * Perform a Curl GET request
     *
     * @param $endpoint
     * @param $objectId
     * @return mixed
     */
    public function get($endpoint, $objectId)
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $this->teamworkURL . $endpoint . '/' . $objectId . '.json');
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_HTTPHEADER, array(
            "Authorization: BASIC " . base64_encode($this->apiKey . ":xxx"),
            "Content-type:application/json"
        ));

        $response = curl_exec($channel);

        curl_close($channel);

        return $response;
    }

    /**
     * Perform a Curl PUT request
     *
     * @param $endpoint
     * @param $objectId
     * @param $json
     * @return mixed
     */
    public function put($endpoint, $objectId, $json)
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $this->teamworkURL . $endpoint . '/' . $objectId . '.json');
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($channel, CURLOPT_POSTFIELDS, $json);
        curl_setopt($channel, CURLOPT_HTTPHEADER, array(
            "Authorization: BASIC " . base64_encode($this->apiKey . ":xxx"),
            "Content-type:application/json"
        ));

        $response = curl_exec($channel);

        curl_close($channel);

        return $response;
    }

    /**
     * Given the Project details, create a new project title: [id] name
     * @param $project
     * @return mixed
     */
    public function prepareProjectTitle($project)
    {
        $projectArray = json_decode($project, true);

        $projectId = $projectArray['project']['id'];

        $projectName = $projectArray['project']['name'];

        $newProjectName = "[$projectId] $projectName";

        return $newProjectName;
    }

    /**
     * Write to a log
     *
     * @param string $name
     * @param array $array
     */
    public function log($name, array $array)
    {
        if (is_array($array) && !empty($array)) {
            $timestamp = date("Y_m_d_G_i_s");

            $fp = fopen(__DIR__ . '/../logs/' . $name . '.log', 'a');

            fwrite($fp, $timestamp . ' ' . json_encode($array) . PHP_EOL);

            fclose($fp);
        }
    }

    /**
     * Add one or more Commits as Comments to a Task on Teamwork
     *
     * @param array $commits
     * @return array
     */
    public function addCommitComment(array $commits)
    {
        $responsesArray = array();

        // loop over the commits
        foreach ($commits as $commit) {
            $message = $commit['message'];

            preg_match_all('(\\[.*?\\])', $message, $teamworkTaskIds);

            $taskIdsArray = current($teamworkTaskIds);

            if (is_array($taskIdsArray) && !empty($taskIdsArray)) {
                foreach ($taskIdsArray as $taskID) {
                    $taskID = strtolower(preg_replace('/\s+/', '', trim($taskID))); // trim all whitespace, just in case

                    if ($taskID != '') {
                        $taskID = $this->cleanTaskId($taskID);

                        $message = $this->cleanMessage($taskID, $message);

                        $fileChanges = $this->parseFileChanges($commit);

                        $commentArray = array(
                            'comment' => array(
                                'body' => $message . PHP_EOL . $fileChanges,
                                'isprivate' => false,
                                ''
                            )
                        );

                        $responsesArray[] = $this->post('/tasks/' . $taskID . '/comments.json', $taskID, json_encode($commentArray));
                    }
                }
            } else {
                $responsesArray[] = 'No task ID found in this commit';
            }
        }

        return $responsesArray;
    }

    /**
     * Clean up the task ID, from something like [finished 3312] to just 3312
     *
     * @param string $taskID
     * @return int
     */
    public function cleanTaskId($taskID)
    {
        $taskID = strtolower($taskID);

        $taskID = str_replace(
            array('[', ']', 'finished', 'finish'),
            array('', '', '', ''),
            $taskID
        );

        return (int)$taskID;
    }

    /**
     * Clean up the commit message, remove any task Id
     *
     * @param int $taskId
     * @param string $message
     * @return string
     */
    public function cleanMessage($taskId, $message)
    {
        $messageCleaned = str_replace(
            array('[', ']', 'finished', 'finish', $taskId),
            array('', '', '', '', ''),
            strtolower($message)
        );

        return (string)trim($messageCleaned);
    }

    /**
     * Perform a Curl POST request
     *
     * @param $endpoint
     * @param $objectId
     * @param $json
     * @return mixed
     */
    public function post($endpoint, $objectId, $json)
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $this->teamworkURL . $endpoint . '/' . $objectId . '.json');
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($channel, CURLOPT_POSTFIELDS, $json);
        curl_setopt($channel, CURLOPT_HTTPHEADER, array(
            "Authorization: BASIC " . base64_encode($this->apiKey . ":xxx"),
            "Content-type:application/json"
        ));

        $response = curl_exec($channel);

        curl_close($channel);

        return $response;
    }

    /**
     * Given a commit, pull out the Added, Removed, modified parts
     *
     * @param $commit
     * @return string
     */
    public function parseFileChanges($commit)
    {
        $fileChanges = '';

        if (is_array($commit)) {

            if (isset($commit['added']) && !empty($commit['added'])) {
                $fileChanges .= PHP_EOL . 'Added: ';
                $fileChanges .= implode(", ", $commit['added']);
            }

            if (isset($commit['removed']) && !empty($commit['removed'])) {
                $fileChanges .= PHP_EOL . 'Removed: ';
                $fileChanges .= implode(", ", $commit['removed']);

            }

            if (isset($commit['modified']) && !empty($commit['modified'])) {
                $fileChanges .= PHP_EOL . 'Modified: ';
                $fileChanges .= implode(", ", $commit['modified']);
            }

        }

        return trim($fileChanges);
    }

}

