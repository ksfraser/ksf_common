<?php
use PHPUnit\Framework\TestCase;

class fa_stock_movesTest extends TestCase {
    public function testInsert() {
        $stockMoves = new fa_stock_moves();
        $this->assertTrue($stockMoves->insert());
    }

    public function testUpdate() {
        $stockMoves = new fa_stock_moves();
        $this->assertTrue($stockMoves->update());
    }
}