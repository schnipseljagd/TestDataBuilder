<?php

class TestDataBuilder_SimpleEqualsConstraintTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function itShouldActAsAPHPUnitConstraint()
    {
        $constraint = new TestDataBuilder_SimpleEqualsConstraint('something');

        $this->assertInstanceOf('PHPUnit_Framework_Constraint', $constraint);
    }

    /**
     * @test
     */
    public function itShouldEvaluateToTrueIfTheEvaluatedStringIsTheSameAsTheExpected()
    {
        $theString = 'the string';

        $constraint = new TestDataBuilder_SimpleEqualsConstraint($theString);

        $this->assertThat(
            $constraint->evaluate($theString, '', true),
            $this->isTrue()
        );
    }

    /**
     * @test
     */
    public function itShouldEvaluateToFalseIfTheEvaluatedStringIsNotTheSameAsTheExpected()
    {
        $constraint = new TestDataBuilder_SimpleEqualsConstraint('a string');

        $this->assertThat(
            $constraint->evaluate('another string', '', true),
            $this->isFalse()
        );
    }

}
