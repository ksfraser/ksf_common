<?php
use PHPUnit\Framework\TestCase;

class fa_stock_masterTest extends TestCase {
    public function testInsert() {
        $stockMaster = new fa_stock_master();
        $this->assertTrue($stockMaster->insert());
    }

    public function testGetById() {
        $stockMaster = new fa_stock_master();
        $this->assertTrue($stockMaster->getById());
    }
}