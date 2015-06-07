<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Request;

class BitbucketController extends BaseController
{
    /**
     * Receive POSTed commit data from Bitbucket
     *
     * @param Request $request
     * @return string
     */
    public function receiveBitbucketPost(Request $request)
    {
        $commitData = $request->input('payload');

        $commitDataArray = json_decode($commitData, true);

        $api_key_to_use = '';
        $person_id_to_use = '';

        $commentsArray = $this->parseBitbucketCommit($commitDataArray);

        foreach ($commentsArray as $commitCreatedTime => $commentsForTeamwork) {

            preg_match_all('(\\[.*?\\])', $commentsForTeamwork, $taskIDs);

            $taskIDsArray = current($taskIDs);

            if (is_array($taskIDsArray) && !empty($taskIDsArray)) {

                foreach ($taskIDsArray as $taskID) {

                    $taskID = strtolower(preg_replace('/\s+/', '', trim($taskID))); // trim all whitespace, just in case

                    if ($taskID != '') {

                        $reassignForTesting = (stristr($taskID, 'finish') == true || stristr($taskID, 'finished')) ? true : false;

                        $taskID = str_replace(
                            array('[', ']', 'finished', 'finish'),
                            array('', '', '', ''),
                            $taskID
                        );

                        // Retrieve Task details
                        $taskJson = $this->curl(env('TEAMWORK_CUSTOM_URL') . '/tasks/' . $taskID . '.json', 'GET', env('TEAMWORK_API_KEY'));
                        $taskDetailsArray = json_decode($taskJson, true);

                        // look for Time taken and enable the Time section of Teamwork
                        if (stristr($taskID, ":") == true) {
                            $timeTaken = end(explode(":", $taskID)); // get time taken
                            $taskID = str_replace(":$timeTaken", "", $taskID); // clean task id
                            $projectID = $taskDetailsArray['project-id'];

                            $enableTimeArray = array(
                                'project' => array(
                                    'use-time' => 1,
                                )
                            );

                            // enable the 'Time' section in Teamwork in case it is not enabled
                            $this->curl(env('TEAMWORK_CUSTOM_URL') .'/projects/' . $projectID . '.json', 'PUT', env('TEAMWORK_API_KEY'), $enableTimeArray);
                        }

                        // Clean up comment first
                        $commentsForTeamwork = str_replace(
                            array('[', ']', 'finished', 'finish', $taskID),
                            array('', '', '', '', ''),
                            strtolower($commentsForTeamwork)
                        );

                        $commentArray = array(
                            'comment' => array(
                                'body' => $commentsForTeamwork,
                                'isprivate' => false,
                                ''
                            )
                        );

                        // Add a comment to the task mentioned in the commit message
                        $this->curl(env('TEAMWORK_CUSTOM_URL') .'/tasks/' . $taskID . '/comments.json', 'POST', env('TEAMWORK_API_KEY'), $commentArray);

                        // todo: If development time was sent too, create a Time sheet entry for this task

                        // todo: re-assign the task and notify that its ready for testing

                    }
                }
            }
        }

        return '';
    }

    /**
     * Given an array of data from Bitbucket, pull out the information we need
     *
     * @param array $full_commit_data
     * @return string
     */
    private function parseBitbucketCommit($full_commit_data)
    {

        $commit_changes = $full_commit_data['commits'];
        $comments_array = array();
        $commit_url = $full_commit_data['canon_url'] . $full_commit_data['repository']['absolute_url'] . 'commits/';

        /**
         * Loop through the commits to get the message, branch and files changed
         */
        foreach ($commit_changes as $commit) {

            $commit_time = strtotime($commit['utctimestamp']);

            $comments = $commit['message'] . "\r\n\r\n";

            $files_changed_array = $commit['files'];

            $comments .= "**files changed**\r\n";

            foreach ($files_changed_array as $file_change) {
                $comments .= $file_change['type'] . ' ' . $file_change['file'] . "\r\n";
            }

            $comments .= "\r\n**branch**\r\n";
            $comments .= $commit['branch'] . " \r\n";

            $comments .= "\r\n**View the commit on Bitbucket**\r\n";
            $comments .= $commit_url . $commit['raw_node'] . "\r\n";

            $comments_array[$commit_time] = $comments;

        }

        return $comments_array;
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
