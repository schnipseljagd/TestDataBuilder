<?php

class TestDataBuilder_MockBuilder extends TestDataBuilder_StubBuilder
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

    /**
     * @return TestDataBuilder_MockBuilder
     */
    public function but()
    {
        return parent::but();
    }

    /**
     * @param string $method
     * @param mixed $will
     * @return TestDataBuilder_MockBuilder
     */
    public function with($method, $will)
    {
        return parent::with($method, $will);
    }

    public function expectsCall($method)
    {
        return $this->expects($method, $this->testCase->once());
    }

    public function expectsNoCall($method)
    {
        return $this->expects($method, $this->testCase->never());
    }

    public function expectsCallAt($index, $method)
    {
        return $this->expects($method, $this->testCase->at($index));
    }

    public function expectsAtLeastOneCall($method)
    {
        return $this->expects($method, $this->testCase->atLeastOnce());
    }

    public function expectsExactNumberOfCalls($number, $method)
    {
        return $this->expects($method, $this->testCase->exactly($number));
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function build()
    {
        $mock = $this->testCase->getMock($this->className);
        $this->loadMethodStubs($mock);
        $this->loadMethodMocks($mock);
        return $mock;
    }

    private function loadMethodMocks($mock)
    {
        foreach ($this->expectations as $expectation) {
            $invocationMocker = $mock->expects($expectation->getInvocationMatcher());
            $invocationMocker = $invocationMocker->method($expectation->getMethod());
            $invocationMockerReflection = new ReflectionMethod('PHPUnit_Framework_MockObject_Builder_InvocationMocker', 'with');
            $invocationMockerReflection->invokeArgs($invocationMocker, $expectation->getArguments());
        }
    }

    private function expects($method, $invocationMatcher)
    {
        $expectation = new TestDataBuilder_MockBuilder_Expectation(
            $method,
            $invocationMatcher
        );
        $this->expectations[] = $expectation;
        return $expectation;
    }
}
