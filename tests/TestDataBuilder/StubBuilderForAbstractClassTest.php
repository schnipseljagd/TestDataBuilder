<?php
require_once dirname(__FILE__) . '/AbstractTestClass.php';
require_once dirname(__FILE__) . '/TestClass.php';

/**
 * @covers TestDataBuilder_StubBuilderForAbstractClass
 * @covers TestDataBuilder_StubBuilder
 */
class TestDataBuilder_StubBuilderForAbstractClassTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldStubDefinedClass()
    {
        $testCase = $this->getMockBuilder('PHPUnit_Framework_TestCase')->disableOriginalConstructor()->getMock();
        $testCase->expects($this->once())->method('getMockForAbstractClass')->with($this->equalTo('stdClass'));

        $builder = new TestDataBuilder_StubBuilderForAbstractClass('stdClass', $testCase);
        $builder->build();
    }

    /**
     * @test
     */
    public function itShouldReturnTheDefinedStub()
    {
        $testCase = $this->getMockBuilder('PHPUnit_Framework_TestCase')->disableOriginalConstructor()->getMock();
        $testCase->expects($this->any())->method('getMockForAbstractClass')->will($this->returnValue(new stdClass()));
        $builder = new TestDataBuilder_StubBuilderForAbstractClass('stdClass', $testCase);

        $this->assertThat($builder->build(), $this->isInstanceOf('stdClass'));
    }

    /**
     * @test
     */
    public function itShouldStubImplementationForAbstractMethods()
    {
        $builder = new TestDataBuilder_StubBuilderForAbstractClass('TestDataBuilder_AbstractTestClass', $this);
        $builder->withConstructorArgs(array(true));
        $stub = $builder->build();
        $value = $stub->getTestValue();
        $value2 = $stub->getTestValue2();

        $this->assertThat($value === 'blah blah' && $value2 === null, $this->isTrue());
    }

    /**
     * @test
     */
    public function itShouldAddNewImplementationForAbstractMethods()
    {
        $builder = new TestDataBuilder_StubBuilderForAbstractClass('TestDataBuilder_AbstractTestClass', $this);
        $builder->withConstructorArgs(array(true));
        $builder->with('getTestValue2', 'test return value');

        $this->assertThat($builder->build()->getTestValue2(), $this->equalTo('test return value'));
    }
}
