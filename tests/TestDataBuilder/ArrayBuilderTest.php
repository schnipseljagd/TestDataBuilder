<?php

/**
 * @covers TestDataBuilder_ArrayBuilder
 */
class TestDataBuilder_ArrayBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldReturnDefinedFields()
    {
        $array = new TestDataBuilder_ArrayBuilder();
        $array->with('test', 'test value');
        $array['test2'] = 'test value 2';

        $this->assertThat(
            $array->build(), $this->equalTo(array('test' => 'test value', 'test2' => 'test value 2'))
        );
    }

    /**
     * @test
     */
    public function itShouldReturnIfOffsetExists()
    {
        $array = new TestDataBuilder_ArrayBuilder();
        $array['test'] = 'test value';

        $this->assertThat(isset($array['test']), $this->isTrue());
    }

    /**
     * @test
     */
    public function itShouldReturnIfOffsetNotExists()
    {
        $array = new TestDataBuilder_ArrayBuilder();

        $this->assertThat(isset($array['test']), $this->isFalse());
    }

    /**
     * @test
     */
    public function itShouldReturnDefinedOffset()
    {
        $array = new TestDataBuilder_ArrayBuilder();
        $array['anyOffset'] = 'any value';

        $this->assertThat($array['anyOffset'], $this->equalTo('any value'));
    }

    /**
     * @test
     */
    public function itShouldBuildOtherBuilders()
    {
        $otherBuilder = $this->getMockBuilder('TestDataBuilder_Builder')->getMockForAbstractClass();
        $otherBuilder->expects($this->once())->method('build');

        $array = new TestDataBuilder_ArrayBuilder();
        $array['anyOffset'] = $otherBuilder;
        $array->build();
    }
}
