<?php

class TestDataBuilder_PartialMockBuilderForAbstractClass extends TestDataBuilder_PartialMockBuilder
{
    protected function createMock()
    {
        return $this->testCase->getMockForAbstractClass(
            $this->className,
            $this->constructorArgs,
            '',
            $this->useOriginalConstructor(),
            $this->useOriginalClone()
        );
    }
}
