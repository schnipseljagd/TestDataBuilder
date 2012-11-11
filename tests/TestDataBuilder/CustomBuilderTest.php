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
            $testDataBuilder->build(),
            $this->equalTo(array('test' => 'test value', 'test2' => 'test value 2'))
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
     * @return TestDataBuilder_CustomBuilder
     */
    private function createTestDataBuilder()
    {
        $testDataBuilder = new TestCustomBuilder();
        return $testDataBuilder;
    }
}

class TestCustomBuilder extends TestDataBuilder_CustomBuilder
{
    public function build()
    {
        return $this->fields;
    }
}
