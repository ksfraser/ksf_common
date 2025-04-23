<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\write_file;

/**
 * @covers \Ksfraser\Common\write_file
 */
final class WriteFileTest extends TestCase
{
    private $tmpDir = __DIR__ . '/tmp';
    private $testFile = 'testfile.txt';

    protected function setUp(): void
    {
        if (!is_dir($this->tmpDir)) {
            mkdir($this->tmpDir);
        }
    }

    protected function tearDown(): void
    {
        $filePath = $this->tmpDir . '/' . $this->testFile;
        if (file_exists($filePath)) {
            unlink($filePath);
        }
        if (is_dir($this->tmpDir)) {
            rmdir($this->tmpDir);
        }
    }

    public function testCanBeInstantiated(): void
    {
        $writeFile = new write_file($this->tmpDir, $this->testFile);
        $this->assertInstanceOf(write_file::class, $writeFile);
    }

    public function testWriteChunk(): void
    {
        $writeFile = new write_file($this->tmpDir, $this->testFile);
        $writeFile->write_chunk('Test chunk');
        $writeFile->close();

        $filePath = $this->tmpDir . '/' . $this->testFile;
        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, 'Test chunk');
    }

    public function testWriteLine(): void
    {
        $writeFile = new write_file($this->tmpDir, $this->testFile);
        $writeFile->write_line('Test line');
        $writeFile->close();

        $filePath = $this->tmpDir . '/' . $this->testFile;
        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, "Test line\r\n");
    }

    public function testWriteArrayToCsv(): void
    {
        $writeFile = new write_file($this->tmpDir, $this->testFile);
        $writeFile->write_array_to_csv(['col1', 'col2', 'col3']);
        $writeFile->close();

        $filePath = $this->tmpDir . '/' . $this->testFile;
        $this->assertFileExists($filePath);
        $this->assertStringEqualsFile($filePath, "col1,col2,col3\n");
    }
}