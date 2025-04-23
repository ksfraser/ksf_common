<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\Core\base;

/**
 * @covers \Ksfraser\Common\Core\base
 */
final class BaseTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $base = new base();
        $this->assertInstanceOf(base::class, $base);
    }

    public function testParseArgs(): void
    {
        $base = new base(['username' => 'testuser', 'password' => 'testpass']);
        $this->assertSame('testuser', $base->username);
        $this->assertSame('testpass', $base->password);
    }

    public function testOpenWriteFile(): void
    {
        $base = new base();
        $file = $base->open_write_file('testfile.txt');
        $this->assertIsResource($file);
        fclose($file);
        unlink('testfile.txt');
    }
}