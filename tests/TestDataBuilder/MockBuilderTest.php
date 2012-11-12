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
        $this->builder = new TestDataBuilder_MockBuilder('TestClassForMockObjectBuilder', $this);
    }

    /**
     * @test
     */
    public function itShouldReturnACopyOfTheMockBuilder()
    {
        $this->assertThat(
            $this->builder->but(), 
            $this->logicalAnd(
                $this->logicalNot($this->identicalTo($this->builder)),
                $this->equalTo($this->builder)
            )
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAMethodCall()
    {
        $this->builder->expectsCall('doSomeThing');
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 1 time(s).\nMethod was expected to be called 1 times, actually called 0 times.";
        $this->assertThatMockRaisesError(
            $this->builder->build(), 
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAMethodCallWithDefinedParameters()
    {
        $this->builder->expectsCall('doSomeThing')
            ->with($this->equalTo(34), $this->isInstanceOf('stdClass'));
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 1 time(s).\nParameter 1 for invocation TestClassForMockObjectBuilder::doSomeThing(34, null) does not match expected value.\nFailed asserting that null is an instance of class \"stdClass\".";
        $mock = $this->builder->build();

        try {
            $mock->doSomeThing(34, null);
        } catch (Exception $e) {
            // ignore failing expectation here
        }
        $this->assertThatMockRaisesError(
            $mock,
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsNothing()
    {
        $this->builder->expectsNoCall('doSomeThing');
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 0 time(s).\nMethod was expected to be called 0 times, actually called 1 times.";
        $mock = $this->builder->build();
        try {
            $mock->doSomeThing();
        } catch (Exception $e) {
            // ignore failing expectation here
        }
        $this->assertThatMockRaisesError(
            $mock,
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAMethodCallAtAnIndex()
    {
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked at sequence index 0.\nThe expected invocation at index 0 was never reached.";
        $this->builder->expectsCallAt(0, 'doSomeThing');
        $this->assertThatMockRaisesError(
            $this->builder->build(), 
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsAtLeastOneCall()
    {
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked at least once.\nExpected invocation at least once but it never occured.";
        $this->builder->expectsAtLeastOneCall('doSomeThing');
        $this->assertThatMockRaisesError(
            $this->builder->build(), 
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsExactNumberOfCalls()
    {
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 2 time(s).\nMethod was expected to be called 2 times, actually called 1 times.";
        $this->builder->expectsExactNumberOfCalls(2, 'doSomeThing');
        $mock = $this->builder->build();
        $mock->doSomeThing();
        $this->assertThatMockRaisesError(
            $mock,
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWhichExpectsACallOnAStubbedMethod()
    {
        $this->builder->with('doSomeThing', 'a value');
        $this->builder->expectsCall('doSomeThing');
        $expectedMessage = "Expectation failed for method name is equal to <string:doSomeThing> when invoked 1 time(s).\nMethod was expected to be called 1 times, actually called 0 times.";
        $this->assertThatMockRaisesError(
            $this->builder->build(), 
            $expectedMessage
        );
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAMethodStub()
    {
        $this->builder->with('doSomeThing', 'a value');
        $this->builder->expectsCall('doSomeThing')->with('test')->will('another value');
        $mock = $this->builder->build();
        $this->assertThat($mock->doSomeThing('test'), $this->equalTo('another value'));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAMethodStubWhichWasOverriden()
    {
        $this->builder->with('doSomeThing', 'a value');
        $this->builder->with('doSomeThing', 'another value');
        $this->builder->expectsCall('doSomeThing')->with('test');
        $mock = $this->builder->build();
        $this->assertThat($mock->doSomeThing('test'), $this->equalTo('another value'));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function itShouldBuildAMockWithAnotherMethodStub()
    {
        $this->builder->with('doSomeThing', $this->throwException(new RuntimeException()));
        $this->builder->expectsCall('doSomeThing');
        $mock = $this->builder->build();
        $mock->doSomeThing();
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAStubWhichReturnsAnotherBuilder()
    {
        $this->builder->with('doSomeThing', $this->anArray(array('a value')));
        $mock = $this->builder->build();
        $this->assertThat($mock->doSomeThing(), $this->equalTo(array('a value')));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAnotherStubWhichReturnsAnotherBuilder()
    {
        $this->builder->expectsCall('doSomeThing')->will($this->anArray(array('a value')));
        $mock = $this->builder->build();
        $this->assertThat($mock->doSomeThing(), $this->equalTo(array('a value')));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockWithAnExpectationWhichHasAnBuilderAsExpectedArgument()
    {
        $this->builder->expectsCall('doSomeThing')->with($this->anArray(array('a value')))->will($this->returnCallback(function ($arg1) {return $arg1;}));
        $mock = $this->builder->build();
        $this->assertThat($mock->doSomeThing(array('a value')), $this->equalTo(array('a value')));
    }

    /**
     * @test
     */
    public function itShouldBuildAMockObject()
    {
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
    public function getMock($originalClassName, $methods = array(), array $arguments = array(), $mockClassName = '', $callOriginalConstructor = TRUE, $callOriginalClone = TRUE, $callAutoload = TRUE, $cloneArguments = TRUE)
    {
        $mockObject = PHPUnit_Framework_MockObject_Generator::getMock(
          $originalClassName,
          $methods,
          $arguments,
          $mockClassName,
          $callOriginalConstructor,
          $callOriginalClone,
          $callAutoload,
          $cloneArguments
        );
        return $mockObject;
    }

    private function anArray(array $data)
    {
        return new TestDataBuilder_ArrayBuilder($data);
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

class TestClassForMockObjectBuilder
{
    public function doSomeThing()
    {

    }
}
