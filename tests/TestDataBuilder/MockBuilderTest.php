<?php

/**
 * @covers TestDataBuilder_MockBuilder
 */
class TestDataBuilder_MockBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestDataBuilder_MockBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new TestDataBuilder_MockBuilder('TestClass', $this);
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAMethodCall()
    {
        $this->builder->expectsCall('doSomeThing');
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 1 time(s).\nMethod was expected to be called 1 times, actually called 0 times.";
        $this->assertThatMockRaisesError($expectedMessage);
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsNothing()
    {
        $expectedMessage = "";
        $this->assertThatMockRaisesError($expectedMessage);
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAMethodCallAtAnIndex()
    {
        $expectedMessage = "";
        $this->builder->expectsCallAt(1, 'doSomeThing');
        $this->assertThatMockRaisesError($expectedMessage);
    }

    private function assertThatMockRaisesError($expectedMessage)
    {
        try {
            $this->builder->build()->__phpunit_verify();
        } catch (Exception $e) {
            $this->assertThat($e->getMessage(), $this->equalTo($expectedMessage));
        }
    }

    public function itShouldBuildAMockObject()
    {
        $builder = new TestDataBuilder_MockBuilder('TestClass', $this);
        $builder->expectsCall('doSomeThing')->with($this->equalTo('test'));
        $builder->expectsCallAt(1, 'doSomeThing');
        $builder->expectsAtLeastOneCall('doSomeThing');
        $builder->expectsNoCalls('doSomething');
        $builder->expectsCalls(2, 'doSomething');
        $builder->with('doSomeThing', $this->returnValue('test'));
        $this->assertThat($builder->build(), $this->isInstanceOf('PHPUnit_Framework_MockObject_MockObject'));
    }
    
    public function itShouldBuildAPartialMockObject()
    {
        $builder = new TestDataBuilder_PartialMockBuilder('TestClass', $this);
        $builder->withDisabledConstructor();
        $builder->withDisabledClone();
        $builder->withConstructorArgs(array('test'));
        $builder->withConstructorArg(0, 'test');
        $builder->expectsCall('doSomeThing')->with($this->equalTo('test'));
        $builder->expectsCallAt(1, 'doSomeThing');
        $builder->expectsAtLeastOneCall('doSomeThing');
        $builder->expectsNoCalls('doSomething');
        $builder->expectsCalls(2, 'doSomething');
        $builder->with('doSomeThing', $this->returnValue('test'));
        $this->assertThat($builder->build(), $this->isInstanceOf('PHPUnit_Framework_MockObject_MockObject'));
    }

    /**
     * @param $originalClassName
     * @param array $methods
     * @param array $arguments
     * @param string $mockClassName
     * @param bool $callOriginalConstructor
     * @param bool $callOriginalClone
     * @param bool $callAutoload
     * @return object
     */
    public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE)
    {
        $mockObject = PHPUnit_Framework_MockObject_Generator::getMock(
          $originalClassName,
          $methods,
          $arguments,
          $mockClassName,
          $callOriginalConstructor,
          $callOriginalClone,
          $callAutoload
        );
        return $mockObject;
    }


}

class TestClass
{
    public function doSomething()
    {

    }
}