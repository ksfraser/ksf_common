<?php

require_once('class.table_interface.php');

$path_to_root = "../..";

/**
 * Class FaOrderToDelivery
 * Handles operations related to orders and delivery delays in FA.
 */
class FaOrderToDelivery extends table_interface
{
//protected $id;	
    protected $stock_id;
    protected $supplier;
    protected $days;
    public $errors = [];
    public $warnings = [];

    /**
     * Constructor
     * @param mixed $caller Optional caller object.
     */
    public function __construct($caller = null)
    {
        parent::__construct($caller);
    }

    /**
     * Fetches items with their order-to-delivery details.
     */
    private function getItemsOrderToDelivery()
    {
        $this->fields_array = ['d.item_code AS stockId', 's.supp_name AS supplier', 'ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days'];
        $this->from_array = [TB_PREF . 'purch_order_details d', TB_PREF . 'purch_orders o', TB_PREF . 'suppliers s'];
        $this->where_array = ['o.order_no = d.order_no', 'o.supplier_id = s.supplier_id'];
        $this->orderby_array = ['d.item_code', 's.supp_name'];
    }

    /**
     * Fetches suppliers with their order-to-delivery details.
     */
    private function getSuppliersOrderToDelivery()
    {
        $this->fields_array = ['d.order_no AS orderNumber', 's.supp_name AS supplier', 'ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days'];
        $this->from_array = [TB_PREF . 'purch_order_details d', TB_PREF . 'purch_orders o', TB_PREF . 'suppliers s'];
        $this->where_array = ['o.order_no = d.order_no', 'o.supplier_id = s.supplier_id'];
        $this->orderby_array = ['d.item_code', 's.supp_name'];
        $this->groupby_array = ['d.order_no'];
    }

    /**
     * Fetches detailed order-to-delivery information.
     */
    private function getOrdersToDeliveryDetails()
    {
        $this->fields_array = [
            'd.order_no AS orderNumber',
            's.supp_name AS supplier',
            'ABS(DATEDIFF(d.delivery_date, o.ord_date)) AS days',
            'o.ord_date AS orderDate',
            'd.delivery_date AS deliveryDate',
            'd.item_code AS stockId',
            'd.quantity_ordered AS quantityOrdered',
            'd.quantity_received AS quantityReceived'
        ];
        $this->from_array = [TB_PREF . 'purch_order_details d', TB_PREF . 'purch_orders o', TB_PREF . 'suppliers s'];
        $this->where_array = ['o.order_no = d.order_no', 'o.supplier_id = s.supplier_id'];
        $this->orderby_array = ['d.item_code', 's.supp_name'];
        $this->groupby_array = ['d.order_no'];
    }

    /**
     * Retrieves delay information based on the specified type and key.
     *
     * @param string $type The type of delay (items, suppliers, orders, etc.).
     * @param mixed $key The key to filter by (e.g., item code, supplier name, or order number).
     * @return mixed|null The result of the query or null if invalid type.
     */
    public function getDelay($type, $key = null)
    {
        switch ($type) {
            case 'items':
                $this->getItemsOrderToDelivery();
                break;
            case 'suppliers':
                $this->getSuppliersOrderToDelivery();
                break;
            case 'orders':
                $this->getOrdersToDeliveryDetails();
                break;
            case 'item':
                $this->getItemsOrderToDelivery();
                $this->where_array[] = "d.item_code = '$key'";
                break;
            case 'supplier':
                $this->getSuppliersOrderToDelivery();
                $this->where_array[] = "s.supp_name = '$key'";
                break;
            case 'order':
                $this->getOrdersToDeliveryDetails();
                $this->where_array[] = "d.order_no = '$key'";
                break;
            default:
                return null;
        }
        $this->buildSelectQuery();
        return $this->query_result;
    }
}
