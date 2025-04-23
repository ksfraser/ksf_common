<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\Controllers\controller_origin;

/**
 * @covers \Ksfraser\Common\Controllers\controller_origin
 */
final class ControllerOriginTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $controller = new controller_origin();
        $this->assertInstanceOf(controller_origin::class, $controller);
    }

    public function testDefaultMode(): void
    {
        $controller = new controller_origin();
        $this->assertSame('unknown', $controller->get_var('mode'));
    }

    public function testSetAndGetVar(): void
    {
        $controller = new controller_origin();
        $controller->set_var('testKey', 'testValue');
        $this->assertSame('testValue', $controller->get_var('testKey'));
    }
}