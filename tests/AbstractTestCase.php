<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use ReflectionClass;

abstract class AbstractTestCase extends TestCase
{
    protected function setEntityId(object $entity, int $value, string $idFiled = 'id')
    {
        $class = new ReflectionClass($entity);
        $property = $class->getProperty($idFiled);
        $property->setAccessible(true);
        $property->setValue($entity, $value);
        $property->setAccessible(false);
    }
}
