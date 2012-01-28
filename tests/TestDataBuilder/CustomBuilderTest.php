<?php

/**
 * @covers TestDataBuilder_CustomBuilder
 */
class TestDataBuilder_CustomBuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function itShouldHaveFields()
    {
        $testDataBuilder = $this->createTestDataBuilder();
        $testDataBuilder->with('test', 'test value')->with('test2', 'test value 2');

        $this->assertThat(
            $testDataBuilder,
            $this->attributeEqualTo('fields', array('test' => 'test value', 'test2' => 'test value 2'))
        );
    }

    /**
     * @test
     */
    public function itShouldReturnCloneOfItself()
    {
        $testDataBuilder = $this->createTestDataBuilder();

        $this->assertThat(
            $testDataBuilder->but(),
            $this->logicalAnd(
                $this->logicalNot($this->identicalTo($testDataBuilder)),
                $this->equalTo($testDataBuilder)
            )
        );
    }

    /**
     * @return AbstractTestDataBuilder
     */
    private function createTestDataBuilder()
    {
        $testDataBuilder = $this->getMockBuilder('TestDataBuilder_CustomBuilder')->getMockForAbstractClass();
        return $testDataBuilder;
    }
}
