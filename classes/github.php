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
     * @return mixed
     */
    public function receivePostedData($data)
    {
        return json_decode($data, true);
    }
}