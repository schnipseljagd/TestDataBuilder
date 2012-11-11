<?php

class TestDataBuilder_MockBuilder_Expectation
{
    private $method;
    private $invocationMatcher;
    private $arguments = array();
    private $stubbed = false;
    private $stub;

    public function __construct($method, $invocationMatcher)
    {
        $this->method = $method;
        $this->invocationMatcher = $invocationMatcher;
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
        return $this;
    }

    public function will($stub)
    {
        $this->stubbed = true;
        $this->stub = $stub;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getStub()
    {
        return $this->stub;
    }

    public function isStubbed()
    {
        return $this->stubbed;
    }
}
