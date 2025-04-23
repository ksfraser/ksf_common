<?php
use PHPUnit\Framework\TestCase;

class fa_workcenterTest extends TestCase {
    public function testInsertWorkcenter() {
        $workcenter = new fa_workcenter();
        $workcenter->name = 'Test Workcenter';
        $this->assertTrue($workcenter->insert_workcenter());
    }

    public function testFetchWorkcenter() {
        $workcenter = new fa_workcenter();
        $this->assertTrue($workcenter->fetch("WHERE name = 'Test Workcenter'"));
    }
}