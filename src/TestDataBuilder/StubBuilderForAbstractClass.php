<?php
 
class TestDataBuilder_StubBuilderForAbstractClass extends TestDataBuilder_AbstractStubBuilder
{
    /**
     * @var array
     */
    private $arguments = array();

    /**
     * @param array $arguments
     * @return StubBuilderForAbstractClass
     */
    public function withArguments(array $arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }

    /**
     * @param integer $index
     * @param mixed $argument
     * @return StubBuilderForAbstractClass
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
            $this->className, $this->arguments, '', true, false
        );
    }
}
