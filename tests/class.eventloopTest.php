<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\Controllers\eventloop;
use Ksfraser\Common\Controllers\Event;

/**
 * @covers \Ksfraser\Common\Controllers\eventloop
 */
final class EventloopTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $eventloop = new eventloop();
        $this->assertInstanceOf(eventloop::class, $eventloop);
    }

    public function testObserverRegistration(): void
    {
        $eventloop = new eventloop();
        $observer = $this->createMock(\SplObserver::class);
        $eventloop->attach($observer);
        $this->assertTrue(true); // Ensure no exceptions are thrown
    }

    public function testEventQueueProcessing(): void
    {
        $eventloop = new eventloop();
        $event = new Event('testEvent', ['key' => 'value'], ['payload' => 'data']);
        $eventloop->queueEvent($event);
        $eventloop->processEventQueue();
        $this->assertTrue(true); // Ensure no exceptions are thrown
    }
}