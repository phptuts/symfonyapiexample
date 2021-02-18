<?php


namespace App\Tests;


use ReflectionClass;

trait HelperTrait
{
    /**
     * A method for setting the ids on Entity Models
     *
     * @param $object
     * @param int $id
     */
    public function setId($object, int $id) {
        try {
            $reflection = new ReflectionClass($object);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($object, $id);

        } catch (\ReflectionException $e) {

        }
    }
}