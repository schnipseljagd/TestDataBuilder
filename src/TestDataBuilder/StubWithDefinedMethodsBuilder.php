<?php

class TestDataBuilder_StubWithDefinedMethodsBuilder extends TestDataBuilder_StubBuilder
{
    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStub()
    {
        return $this->testCase->getMock($this->className, array_keys($this->fields), array(), '', false, false);
    }
}
