<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Ksfraser\Common\Views\VIEW_DIV;

/**
 * @covers \Ksfraser\Common\Views\VIEW_DIV
 */
final class VIEW_DIVTest extends TestCase
{
    public function testCanBeInstantiated(): void
    {
        $viewDiv = new VIEW_DIV('testDiv');
        $this->assertInstanceOf(VIEW_DIV::class, $viewDiv);
    }

    public function testStartAndEndDiv(): void
    {
        $viewDiv = new VIEW_DIV('testDiv');
        ob_start();
        $viewDiv->start_div();
        $viewDiv->end_div();
        $output = ob_get_clean();
        $this->assertStringContainsString('<div', $output);
        $this->assertStringContainsString('</div>', $output);
    }

    public function testAddAndRenderItems(): void
    {
        $viewDiv = new VIEW_DIV('testDiv');
        $viewDiv->div_item_array[] = 'Item1';
        $viewDiv->div_item_array[] = 'Item2';
        ob_start();
        echo $viewDiv;
        $output = ob_get_clean();
        $this->assertStringContainsString('Item1', $output);
        $this->assertStringContainsString('Item2', $output);
    }
}