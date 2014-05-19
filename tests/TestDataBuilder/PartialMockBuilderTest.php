<?php

/**
 * @covers TestDataBuilder_PartialMockBuilder
 * @covers TestDataBuilder_PartialMockBuilderForAbstractClass
 */
class TestDataBuilder_PartialMockBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestDataBuilder_PartialMockBuilder
     */
    private $builder;

    public function setUp()
    {
        $this->builder = new TestDataBuilder_PartialMockBuilder('TestClassForPartialMockObjectBuilder', $this);
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAnOriginalConstructor()
    {
        $this->assertThat($this->builder->build()->constructorStatus, $this->equalTo('original_constructor'));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithADisabledConstructor()
    {
        $this->builder->withDisabledConstructor();
        $this->assertThat($this->builder->build()->constructorStatus, $this->isNull());
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAnOriginalCloneMethod()
    {
        $clonedObj = clone $this->builder->build();
        $this->assertThat($clonedObj->cloneStatus, $this->equalTo('original_clone'));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithADisabledCloneMethod()
    {
        $this->builder->withDisabledClone();
        $clonedObj = clone $this->builder->build();
        $this->assertThat($clonedObj->cloneStatus, $this->isNull());
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithConstructorArgsSet()
    {
        $this->builder->withConstructorArgs(array('test 1', 'test 2'));
        $mock = $this->builder->build();
        $this->assertThat($mock->bla . $mock->blub, $this->equalTo('test 1test 2'));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithConstructorArg1Set()
    {
        $this->builder->withConstructorArg(1, 'test 1');
        $this->builder->withConstructorArg(0, 'test 2');
        $mock = $this->builder->build();
        $this->assertThat($mock->bla . $mock->blub, $this->equalTo('test 1test 2'));
    }

    /**
     * @test
     */
    public function itShouldAlwaysBuildAMockWithOriginalConstructorGivenConstructorArgs()
    {
        $this->builder->withDisabledConstructor();
        $this->builder->withConstructorArgs(array('test 1'));
        $mock = $this->builder->build();
        $this->assertThat($mock->bla, $this->equalTo('test 1'));
        
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithExpectationOnStubbedMethod()
    {
       $this->builder->expectsCall('doSomeThing')->will('test value');
       $mock = $this->builder->build();
       $this->assertThat($mock->doSomeThing(), $this->equalTo('test value'));
    }

    /**
     * @test
     */
    public function itShouldBuildAnObjectWithPartiallyMockedMethods()
    {
       $this->builder->expectsCall('doSomeThing')->will('test value');
       $mock = $this->builder->build();
       $this->assertThat($mock->doSomeThingOther(), $this->equalTo('a value'));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithPartiallyMockedMethodsFromAbstractClass()
    {
        $builder = new TestDataBuilder_PartialMockBuilderForAbstractClass('AbstractTestClassForPartialMockObjectBuilder', $this);
        $mock = $builder->build();
        $this->assertThat($mock->doSomeThing(), $this->equalTo('a value'));
    }

    /**
     * @test
     */
    public function itShouldBuildMockObject()
    {
        $this->builder->withDisabledConstructor();
        $this->builder->withDisabledClone();
        $this->builder->withConstructorArgs(array('test'));
        $this->builder->withConstructorArg(0, 'test');
        $this->builder->expectsCall('doSomeThing')->with($this->equalTo('test'));
        $this->builder->expectsCallAt(1, 'doSomeThing');
        $this->builder->expectsAtLeastOneCall('doSomeThing');
        $this->builder->expectsNoCall('doSomeThing');
        $this->builder->expectsExactNumberOfCalls(2, 'doSomeThing');
        $this->builder->with('doSomeThing', $this->returnValue('test'));
        $this->assertThat($this->builder->build(), $this->isInstanceOf('PHPUnit_Framework_MockObject_MockObject'));
    }

    /**
     * @param $originalClassName
     * @param array $methods
     * @param array $arguments
     * @param string $mockClassName
     * @param bool $callOriginalConstructor
     * @param bool $callOriginalClone
     * @param bool $callAutoload
     * @return PHPUnit_Framework_MockObject_MockObject
     */
    public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE, $cloneArguments = TRUE, $callOriginalMethods = FALSE)
    {
        $generator = new PHPUnit_Framework_MockObject_Generator();
        $mockObject = $generator->getMock(
          $originalClassName,
          $methods,
          $arguments,
          $mockClassName,
          $callOriginalConstructor,
          $callOriginalClone,
          $callAutoload,
          $cloneArguments,
          $callOriginalMethods
        );
        return $mockObject;
    }

    /**
     * @param PHPUnit_Framework_MockObject_MockObject $mock
     * @param string $expectedMessage
     */
    private function assertThatMockRaisesError($mock, $expectedMessage)
    {
        try {
            $mock->__phpunit_verify();
            $this->fail('where is my error?');
        } catch (Exception $e) {
            $this->assertThat($e->getMessage(), $this->equalTo($expectedMessage));
        }
    }
}

class TestClassForPartialMockObjectBuilder
{
    public $bla;
    public $blub;
    public $constructorStatus;
    public $cloneStatus;

    public function __construct($bla = null, $blub = null)
    {
        $this->bla = $bla;
        $this->blub = $blub;
        $this->constructorStatus = 'original_constructor';
    }

    public function __clone()
    {
        $this->cloneStatus = 'original_clone';
    }

    public function doSomeThing()
    {

    }

    public function doSomeThingOther()
    {
        return 'a value';
    }
}

abstract class AbstractTestClassForPartialMockObjectBuilder
{
    public function doSomeThing()
    {
        return 'a value';
    }

    abstract function doSomeThingOther();
}
