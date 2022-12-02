<?php

namespace App\Tests\Service\ExceptionHandler;

use App\Service\ExceptionHandler\ExceptionMappingResolver;
use App\Tests\AbstractTestCase;

class ExceptionMappingResolverTest extends AbstractTestCase
{
    public function testThrowExceptionOnEmptyCode(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        /* @noinspection PhpParamsInspection */
        new ExceptionMappingResolver(['someClass' => ['hidden' => true]]);
    }

    public function testResolveToNullWhenNotFound(): void
    {
        $resolver = new ExceptionMappingResolver([]);

        $this->assertNull($resolver->resolve(\InvalidArgumentException::class));
    }

    public function testResolvesClassItself(): void
    {
        /** @noinspection PhpParamsInspection */
        $resolver = new ExceptionMappingResolver([\InvalidArgumentException::class => ['code' => 400]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(400, $mapping->getCode());
        $this->assertTrue($mapping->isHidden());
        $this->assertFalse($mapping->isLoggable());
    }

    public function testResolvesSubClass(): void
    {
        /** @noinspection PhpParamsInspection */
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertEquals(500, $mapping->getCode());
        $this->assertTrue($mapping->isHidden());
        $this->assertFalse($mapping->isLoggable());
    }

    public function testResolvesHidden(): void
    {
        /** @noinspection PhpParamsInspection */
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500, 'hidden' => false]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertFalse($mapping->isHidden());
    }

    public function testResolvesLoggable(): void
    {
        /** @noinspection PhpParamsInspection */
        $resolver = new ExceptionMappingResolver([\LogicException::class => ['code' => 500, 'loggable' => true]]);
        $mapping = $resolver->resolve(\InvalidArgumentException::class);

        $this->assertTrue($mapping->isLoggable());
    }


}
