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
    public function log(string $name, array $array)
    {
        if (is_array($array) && !empty($array)) {

            $timestamp = date("Y_m_d_G_i_s");

            $fp = fopen(__DIR__ . '/../logs/' . $name . '.log', 'a');

            fwrite($fp, $timestamp . ' ' . json_encode($array) . PHP_EOL);

            fclose($fp);
        }
    }
}