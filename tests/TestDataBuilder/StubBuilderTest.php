<?php
require_once dirname(__FILE__) . '/AbstractTestClass.php';
require_once dirname(__FILE__) . '/TestClass.php';

/**
 * @covers TestDataBuilder_StubBuilder
 * @covers TestDataBuilder_AbstractStubBuilder
 */
class TestDataBuilder_StubBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldStubDefinedClass()
    {
        $testCase = $this->getMockBuilder('PHPUnit_Framework_TestCase')->disableOriginalConstructor()->getMock();
        $testCase->expects($this->once())->method('getMock')->with($this->equalTo('stdClass'));
        
        $builder = new TestDataBuilder_StubBuilder('stdClass', $testCase);
        $builder->build();
    }

    /**
     * @test
     */
    public function itShouldReturnTheDefinedStub()
    {
        $testCase = $this->getMockBuilder('PHPUnit_Framework_TestCase')->disableOriginalConstructor()->getMock();
        $testCase->expects($this->any())->method('getMock')->will($this->returnValue(new stdClass()));
        $builder = new TestDataBuilder_StubBuilder('stdClass', $testCase);

        $this->assertThat($builder->build(), $this->isInstanceOf('stdClass'));
    }

    /**
     * @test
     */
    public function itShouldStubImplementationForDefinedMethodsOnly()
    {
        $builder = new TestDataBuilder_StubBuilder('TestDataBuilder_TestClass', $this, true);
        $builder->with('getTestValue2', 'test return value');
        $stub = $builder->build();

        $this->assertThat(
            $stub->getTestValue() === 'blah blah' && $stub->getTestValue2() === 'test return value', $this->isTrue()
        );
    }

    /**
     * @test
     */
    public function itShouldStubImplementationForAllMethodsAsDefault()
    {
        $builder = new TestDataBuilder_StubBuilder('TestDataBuilder_TestClass', $this);
        $stub = $builder->build();
        $value = $stub->getTestValue();
        $value2 = $stub->getTestValue2();

        $this->assertThat($value === null && $value2 === null, $this->isTrue());
    }

    /**
     * @test
     */
    public function itShouldAddReturnValueImplementationForDefinedMethods()
    {
        $builder = new TestDataBuilder_StubBuilder('TestDataBuilder_TestClass', $this);
        $builder->with('getTestValue', 'test return value');

        $this->assertThat($builder->build()->getTestValue(), $this->equalTo('test return value'));
    }

    /**
     * @test
     * @expectedException Exception
     */
    public function itShouldAddThrowExceptionImplementationForDefinedMethods()
    {
        $builder = new TestDataBuilder_StubBuilder('TestDataBuilder_TestClass', $this);
        $builder->with('getTestValue', $this->throwException(new Exception()));

        $builder->build()->getTestValue();
    }
}
