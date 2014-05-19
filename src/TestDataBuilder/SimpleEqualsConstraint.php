<?php

class TestDataBuilder_SimpleEqualsConstraint extends PHPUnit_Framework_Constraint
{

    private $expected;

    public function __construct($expected)
    {
        $this->expected = $expected;
        parent::__construct();
    }

    protected function matches($other)
    {
        return $this->expected == $other;
    }

    /**
     * Returns a string representation of the object.
     *
     * @return string
     */
    public function toString()
    {
        return sprintf(
            'equals (==) %s',
            $this->exporter->export($this->expected)
        );
    }
}
