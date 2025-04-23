<?php
use PHPUnit\Framework\TestCase;

class fa_table_wrapperTest extends TestCase {
    public function testInsert() {
        $tableWrapper = new fa_table_wrapper();
        $this->assertNull($tableWrapper->insert());
    }

    public function testUpdate() {
        $tableWrapper = new fa_table_wrapper();
        $this->assertNull($tableWrapper->update());
    }
}