<?php

class Request
{
    public function getQueryParam($name)
    {
        return $this->queryParam[$name];
    }

    public function getPaymentData()
    {
        return [];
    }

    public  function setQueryParams($queryParams)
    {
        $this->queryParams = $queryParams;
    }
}
