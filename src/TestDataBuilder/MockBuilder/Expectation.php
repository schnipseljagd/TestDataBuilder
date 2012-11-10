<?php

class TestDataBuilder_MockBuilder_Expectation
{
    private $method;
    private $invocationMatcher;
    private $arguments;

    public function __construct($method, $invocationMatcher)
    {
        $this->method = $method;
        $this->invocationMatcher = $invocationMatcher;
        $this->arguments = array();
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getInvocationMatcher()
    {
        return $this->invocationMatcher;
    }

    public function with()
    {
        $this->arguments = func_get_args();
    }

    public function getArguments()
    {
        return $this->arguments;
    }
}
