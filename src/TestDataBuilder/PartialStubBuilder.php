<?php

class TestDataBuilder_PartialStubBuilder extends TestDataBuilder_StubBuilderForAbstractClass
{
    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStub()
    {
        if (empty($this->arguments)) {
            $callOriginalConstructor = false;
        } else {
            $callOriginalConstructor = true;
        }
        return $this->testCase->getMock(
            $this->className, array_keys($this->fields), $this->arguments, '', $callOriginalConstructor
        );
    }
}
