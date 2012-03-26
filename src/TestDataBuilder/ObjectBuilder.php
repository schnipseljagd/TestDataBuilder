<?php
/**
 * easy way to build your objects
 */
class TestDataBuilder_ObjectBuilder extends TestDataBuilder_Builder
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $constructorArgs = array();

    /**
     * @var array
     */
    private $methodsToCall = array();

    /**
     * @var array
     */
    private $propertiesToSet = array();

    /**
     * @var string
     */
    private $factoryMethod;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param string $factoryMethod
     * @return TestDataBuilder_ObjectBuilder
     */
    public function useFactoryMethod($factoryMethod)
    {
        $this->factoryMethod = $factoryMethod;
        return $this;
    }

    /**
     * @param array $constructorArgs
     * @return TestDataBuilder_ObjectBuilder
     */
    public function with(array $constructorArgs)
    {
        $this->constructorArgs = $constructorArgs;
        return $this;
    }

    /**
     * @param integer $index
     * @param mixed $constructorArg
     * @return TestDataBuilder_ObjectBuilder
     */
    public function withArgument($index, $constructorArg)
    {
        $this->constructorArgs[$index] = $constructorArg;
        return $this;
    }

    /**
     * @param string $method
     * @return TestDataBuilder_ObjectBuilder
     */
    public function call($method)
    {
        $args = func_get_args();
        array_shift($args);
        $this->methodsToCall[$method] = $args;
        return $this;
    }

    /**
     * @param string $property
     * @param mixed $value
     * @return TestDataBuilder_ObjectBuilder
     */
    public function set($property, $value)
    {
        $this->propertiesToSet[$property] = $value;
        return $this;
    }

    /**
     * @return object
     */
    public function build()
    {
        $object = $this->buildObject();
        $this->setProperties($object);
        $this->callMethods($object);
        return $object;
    }

    /**
     * @return object
     */
    private function buildObject()
    {
        if ($this->factoryMethod !== null){
            return $this->buildObjectFromFactoryMethod();
        }
        return $this->buildObjectFromConstructor();
    }

    /**
     * @param object $object
     */
    private function setProperties($object)
    {
        foreach ($this->propertiesToSet as $property => $value) {
            $propertyReflection = new ReflectionProperty($this->class, $property);
            $propertyReflection->setValue($object, $this->buildIfValueIsABuilder($value));
        }
    }

    /**
     * @param object $object
     */
    private function callMethods($object)
    {
        foreach ($this->methodsToCall as $method => $args) {
            $methodReflection = new ReflectionMethod($this->class, $method);
            $methodReflection->invokeArgs($object, $this->buildIfValuesAreBuilder($args));
        }
    }

    /**
     * @return object
     */
    private function buildObjectFromConstructor()
    {
        $classReflection = new ReflectionClass($this->class);
        if (!$classReflection->getConstructor()) {
            $object = new $this->class;
            return $object;
        } else {
            $object = $classReflection->newInstanceArgs($this->builtConstructorArgs());
            return $object;
        }
    }

    /**
     * @return object
     */
    private function buildObjectFromFactoryMethod()
    {
        $methodReflection = new ReflectionMethod($this->class, $this->factoryMethod);
        return $methodReflection->invokeArgs(null, $this->builtConstructorArgs());
    }

    /**
     * @return array
     */
    private function builtConstructorArgs()
    {
        return $this->buildIfValuesAreBuilder($this->constructorArgs);
    }
}
