<?php

abstract class TestDataBuilder_Builder
{

    /**
     * @return AbstractTestDataBuilder
     */
    public function but()
    {
        return clone $this;
    }

    /**
     * @return object
     */
    abstract public function build();
}
