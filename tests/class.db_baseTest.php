<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\Core\db_base;

/**
 * @covers \Ksfraser\Common\Core\db_base
 */
final class DbBaseTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $dbBase = new db_base();
        $this->assertInstanceOf(db_base::class, $dbBase);
    }

    public function testSetAndGetVar(): void
    {
        $dbBase = new db_base();
        $dbBase->set_var('testKey', 'testValue');
        $this->assertSame('testValue', $dbBase->get_var('testKey'));
    }

    public function testDatabaseConnection(): void
    {
        $dbBase = new db_base();
        $this->assertTrue($dbBase->connect()); // Assuming connect() returns true on success
    }
}