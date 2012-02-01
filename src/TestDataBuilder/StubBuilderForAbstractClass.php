<?php
 
class TestDataBuilder_StubBuilderForAbstractClass extends TestDataBuilder_StubBuilder
{
    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @param array $arguments
     * @return TestDataBuilder_StubBuilderForAbstractClass
     */
    public function withConstructorArgs(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param integer $index
     * @param mixed $argument
     * @return TestDataBuilder_StubBuilderForAbstractClass
     */
    public function withArgument($index, $argument)
    {
        $this->arguments[(int) $index] = $argument;
        return $this;
    }

    /**
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    protected function createStub()
    {
        return $this->testCase->getMockForAbstractClass(
            $this->className, $this->arguments
        );
    }
}
