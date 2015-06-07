<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class TeamworkController extends BaseController
{

    /**
     * Receive POSTed data from a Teamwork Webhook
     *
     * @param Request $request
     * @return string
     */
    public function receiveTeamworkWebhook(Request $request)
    {
        $webhookData = $request->getContent();
        parse_str($webhookData, $webhookArray);
        $objectId = array_get($webhookArray, 'objectId');

        // Retrieve all tasks details
        $taskJson = $this->curl(env('TEAMWORK_CUSTOM_URL') . '/tasks/' . $objectId . '.json', 'GET', env('TEAMWORK_API_KEY'));
        $taskArray = json_decode($taskJson, true);

        // Append to the Task description
        $description = array_get($taskArray, 'todo-item.description');
        $newDescription = "Include \"[$objectId]\" or \"[Finish(ed) $objectId]\" to update this task when making a commit. Record time spent on the task by using: \"[$objectId:30]\"\r\n\r\n" . $description;

        $taskDataArray = array(
            'todo-item' => array('description' => $newDescription)
        );

        // Update the Task Description
        $taskJson = $this->curl(env('TEAMWORK_CUSTOM_URL') . '/tasks/' . $objectId . '.json', 'PUT', env('TEAMWORK_API_KEY'), $taskDataArray);

        return $taskJson;
    }

    /**
     * Perform a Curl request
     *
     * @param $endpoint
     * @param $method
     * @param $apiKey
     * @param array $postDataArray
     * @return mixed
     */
    private function curl($endpoint, $method, $apiKey, $postDataArray = array())
    {
        $channel = curl_init();
        curl_setopt($channel, CURLOPT_URL, $endpoint);
        curl_setopt($channel, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($channel, CURLOPT_CUSTOMREQUEST, $method);
        if (!empty($postDataArray)) {
            curl_setopt($channel, CURLOPT_POSTFIELDS, json_encode($postDataArray));
        }
        curl_setopt($channel, CURLOPT_HTTPHEADER, array(
            "Authorization: BASIC " . base64_encode($apiKey . ":xxx"),
            "Content-type:application/json"
        ));

        $response = curl_exec($channel);

        curl_close($channel);

        return $response;
    }
}
