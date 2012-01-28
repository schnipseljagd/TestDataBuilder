<?php

/**
 * @covers TestDataBuilder_ObjectBuilder
 */
class TestDataBuilder_ObjectBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldBuildObject()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('stdClass');

        $this->assertThat($objectBuilder->build(), $this->isInstanceOf('stdClass'));
    }

    /**
     * @test
     */
    public function itShouldBuildObjectWithConstructorArguments()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));

        $this->assertThat($objectBuilder->build()->getAnyParameter(), $this->equalTo('test value'));
    }

    /**
     * @test
     */
    public function itShouldBuildObjectWithMultipleConstructorArguments()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value', 'test value 2'));

        $this->assertThat($objectBuilder->build()->getAnySecondParameter(), $this->equalTo('test value 2'));
    }

    /**
     * @test
     */
    public function itShouldCallMethodsAfterObjectBuild()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->call('overrideParameters', 'overridden test value');

        $this->assertThat($objectBuilder->build()->getAnyParameter(), $this->equalTo('overridden test value'));
    }

    /**
     * @test
     */
    public function itShouldCallMethodsWithMultipleArgumentsAfterObjectBuild()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->call('overrideParameters', 'overridden test value', 'overridden test value 2');

        $this->assertThat($objectBuilder->build()->getAnySecondParameter(), $this->equalTo('overridden test value 2'));
    }

    /**
     * @test
     */
    public function itShouldCallMethodsWithoutArgumentsAfterObjectBuild()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->call('overrideParameters');

        $this->assertThat($objectBuilder->build()->getAnyParameter(), $this->isNull());
    }

    /**
     * @test
     */
    public function itShouldSetPropertiesAfterObjectBuild()
    {
        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->set('anyProperty', 'test value');
        $this->assertThat($objectBuilder->build()->anyProperty, $this->equalTo('test value'));
    }

    /**
     * @test
     */
    public function itShouldBuildOtherBuilderBeforeObjectConstruction()
    {
        $otherBuilder = $this->getMockBuilder('TestDataBuilder_Builder')->getMockForAbstractClass();
        $otherBuilder->expects($this->once())->method('build');

        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array($otherBuilder));
        $objectBuilder->build();
    }

    /**
     * @test
     */
    public function itShouldBuildOtherBuilderBeforeCallingMethods()
    {
        $otherBuilder = $this->getMockBuilder('TestDataBuilder_Builder')->getMockForAbstractClass();
        $otherBuilder->expects($this->once())->method('build');

        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->call('overrideParameters', $otherBuilder);
        $objectBuilder->build();
    }

    /**
     * @test
     */
    public function itShouldBuildOtherBuilderBeforeSettingProperties()
    {
        $otherBuilder = $this->getMockBuilder('TestDataBuilder_Builder')->getMockForAbstractClass();
        $otherBuilder->expects($this->once())->method('build');

        $objectBuilder = new TestDataBuilder_ObjectBuilder('test_Object');
        $objectBuilder->with(array('test value'));
        $objectBuilder->set('anyProperty', $otherBuilder);
        $objectBuilder->build();
    }
}

class test_Object
{
    public $anyProperty;
    private $anyParameter;
    private $anySecondParameter;

    public function __construct($anyParameter, $anySecondParameter = null)
    {
        $this->anyParameter = $anyParameter;
        $this->anySecondParameter = $anySecondParameter;
    }

    public function getAnyParameter()
    {
        return $this->anyParameter;
    }

    public function getAnySecondParameter()
    {
        return $this->anySecondParameter;
    }

    public function overrideParameters($anyParameter = null, $anySecondParameter = null)
    {
        $this->anyParameter = $anyParameter;
        $this->anySecondParameter = $anySecondParameter;
    }
}