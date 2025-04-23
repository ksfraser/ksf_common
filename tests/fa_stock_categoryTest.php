<?php
use PHPUnit\Framework\TestCase;

class fa_stock_categoryTest extends TestCase {
    public function testActiveSellableCategories() {
        $stockCategory = new fa_stock_category();
        $this->assertNotEmpty($stockCategory->active_sellable_categories());
    }

    public function testGetCategoryName() {
        $stockCategory = new fa_stock_category();
        $stockCategory->category_id = 1;
        $this->assertNotEmpty($stockCategory->get_category_name());
    }
}