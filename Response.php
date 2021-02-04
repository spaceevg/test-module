<?php

class Response
{
    private $code;

    private $description;

    private $payment = null;

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getBody()
    {
        return [];
    }

    public function getHeaders()
    {
        return [];
    }
}
