<?php

class TestDataBuilder_PartialMockBuilder extends TestDataBuilder_MockBuilder
{
    protected $constructorArgs = array();
    private $disabledConstructor = false;
    private $disabledClone = false;

    public function withDisabledConstructor()
    {
        $this->disabledConstructor = true;
    }

    public function withDisabledClone()
    {
        $this->disabledClone = true;
    }

    public function withConstructorArgs(array $args)
    {
        $this->constructorArgs = $args;
    }

    public function withConstructorArg($index, $arg)
    {
        $this->constructorArgs[$index] = $arg;
    }

    protected function createMock()
    {
        return $this->testCase->getMock(
            $this->className,
            $this->mockedMethods(),
            $this->constructorArgs,
            '',
            $this->useOriginalConstructor(),
            $this->useOriginalClone()
        );
    }

    protected function useOriginalConstructor()
    {
        return !$this->disabledConstructor || count($this->constructorArgs) > 0;
    }

    protected function useOriginalClone()
    {
        return !$this->disabledClone;
    }

    private function mockedMethods()
    {
        $methods = array();
        foreach ($this->expectations as $expectation) {
            $methods [$expectation->getMethod()] = true;
        }
        return array_keys($methods);
    }
}
