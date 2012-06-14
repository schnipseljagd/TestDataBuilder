<?php

class TestDataBuilder_MockBuilder_Expectation
{
    /**
     * @var string
     */
    private $method;

    public function __construct($method)
    {
        $this->method = $method;
    }

    public function getMethod()
    {
        return $this->method;
    }
}
