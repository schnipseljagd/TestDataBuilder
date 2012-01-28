<?php

class TestDataBuilder_StubBuilder extends TestDataBuilder_CustomBuilder
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
     * @param string $className
     * @param PHPUnit_Framework_TestCase $testCase
     */
    public function __construct($className, PHPUnit_Framework_TestCase $testCase)
    {
        $this->className = $className;
        $this->testCase = $testCase;
    }

    /**
     * @param string $method
     * @param mixed $will
     * @return TestDataBuilder_StubBuilder
     */
    public function with($method, $will)
    {
        return parent::with($method, $will);
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function build()
    {
        $stub = $this->createStub();
        $this->loadMethodStubs($stub);
        return $stub;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStub()
    {
        return $this->testCase->getMock($this->className, array(), array(), '', false, false);
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $stub
     */
    private function loadMethodStubs($stub)
    {
        foreach ($this->fields as $field => $will) {
            if (!is_object($will) || !($will instanceof PHPUnit_Framework_MockObject_Stub)) {
                $will = $this->testCase->returnValue($this->buildIfValueIsABuilder($will));
            }

            $stub->expects($this->testCase->any())->method($field)->will($will);
        }
    }
}
