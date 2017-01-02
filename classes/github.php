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
     * @param json $data
     * @return mixed
     */
    public function receivePostedData(json $data)
    {
        return json_decode($data, true);
    }

    /**
     * Write to a log
     *
     * @param array $data
     */
    public function log(array $data)
    {

    }
}