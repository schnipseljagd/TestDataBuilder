<?php

class TestDataBuilder_MockBuilder
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var PHPUnit_Framework_TestCase
     */
    protected $testCase;

    /**
     * @var TestDataBuilder_MockBuilder_Expectation[]
     */
    protected $expectations;

    /**
     * @param string $className
     * @param PHPUnit_Framework_TestCase $testCase
     */
    public function __construct($className, PHPUnit_Framework_TestCase $testCase)
    {
        $this->className = $className;
        $this->testCase = $testCase;
        $this->expectations = array();
    }

    public function expectsCall($method)
    {
        $this->expectations[] = new TestDataBuilder_MockBuilder_Expectation($method);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function build()
    {
        $mock = $this->testCase->getMock($this->className);
        foreach ($this->expectations as $expectation) {
            $mock->expects($this->testCase->once())->method($expectation->getMethod());
        }
        return $mock;
    }

    public function expectsCallAt($index, $method)
    {
        $this->expectations[] = new TestDataBuilder_MockBuilder_Expectation($method);
    }
}
