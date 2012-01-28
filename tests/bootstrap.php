<?php
if (!function_exists('testDataBuilderAutoLoad')) {
    function testDataBuilderAutoLoad($class)
    {
        if (strpos($class, 'TestDataBuilder_') === 0) {
            $file = str_replace('_', '/', $class) . '.php';
            if ($file) {
                require dirname(__FILE__) . '/../src/' . $file;
            }
        }
    }
    spl_autoload_register('testDataBuilderAutoLoad');
}
