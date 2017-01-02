<?php

namespace teamwork;


class github
{
    function __construct()
    {
    }

    /**
     * Parse the incoming data in to an array
     *
     * @param $data
     * @return array
     */
    public function receivePostedData($data)
    {
        if (isset($data) && $data != '') {
            return (array)json_decode($data, true);
        } else {
            return array();
        }
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
     * Pull out the commit information we need
     *
     * @param array $webHookData
     * @return array
     */
    public function parseGithubData(array $webHookData)
    {
        if (isset($webHookData['commits']) && !empty($webHookData['commits'])) {
            return $webHookData['commits'];
        } else {
            $this->log('github', array('error' => 'missing commits array from webhook data'));
            return array();
        }
    }
}