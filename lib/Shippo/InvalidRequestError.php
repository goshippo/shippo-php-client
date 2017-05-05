<?php

namespace Shippo;

class Shippo_InvalidRequestError extends Shippo_Error
{
    public $param;

    public function __construct($message, $param, $httpStatus = null, $httpBody = null, $jsonBody = null)
    {
        parent::__construct($message, $httpStatus, $httpBody, $jsonBody);
        $this->param = $param;
    }
}
