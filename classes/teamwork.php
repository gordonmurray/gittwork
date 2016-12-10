<?php

namespace teamwork;


class teamwork
{


    function __construct($teamworkURL, $apiKey)
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
        $task = json_decode($task, TRUE);
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
     * @param $data
     * @return mixed
     */
    public function put($endpoint, $objectId, $data)
    {
        $json = json_encode(array('todo-item' => array('description' => $data)));

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
}