<?php

class TestDataBuilder_StubBuilder extends TestDataBuilder_AbstractStubBuilder
{
    /**
     * @var boolean
     */
    protected $stubOnlyImplementationsOfDefinedMethods;

    /**
     * @param string $className
     * @param PHPUnit_Framework_TestCase $testCase
     * @param bool $stubOnlyImplementationsOfDefinedMethods
     */
    public function __construct(
        $className, PHPUnit_Framework_TestCase $testCase, $stubOnlyImplementationsOfDefinedMethods = false
    )
    {
        parent::__construct($className, $testCase);
        $this->stubOnlyImplementationsOfDefinedMethods = $stubOnlyImplementationsOfDefinedMethods;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStub()
    {
        return $this->testCase->getMock($this->className, $this->methodsToStub(), array(), '', false, false);
    }

    /**
     * @return array
     */
    protected function methodsToStub()
    {
        if ($this->stubOnlyImplementationsOfDefinedMethods) {
            return array_keys($this->fields);
        }
        return array();
    }
}
