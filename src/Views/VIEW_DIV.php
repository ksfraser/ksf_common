<?php

namespace Ksfraser\Common\Views;

use Ksfraser\Common\Core\origin;

/**
 * Class VIEW_DIV
 * Represents a div element with dynamic content.
 *
 * @package Ksfraser\Common\Views
 */
class VIEW_DIV extends origin
{
    /**
     * @var string|null The name of the div element.
     */
    protected $name;

    /**
     * @var array The array of items to be rendered inside the div.
     */
    protected $div_item_array;

    /**
     * Constructor to initialize the VIEW_DIV class.
     *
     * @param string|null $name The name of the div element.
     */
    function __construct($name = "")
    {
        parent::__construct();
        $this->div_item_array = array();
        $this->set("name", $name);
    }

    /**
     * Render the div element as a string.
     *
     * @return string The rendered div element.
     */
    function __toString()
    {
        $this->start_div();
        foreach ($this->div_item_array as $obj) {
            echo $obj;
        }
        $this->end_div();
    }

    /**
     * Start the div element.
     */
    function start_div()
    {
        if (function_exists('start_div')) {
            start_div($this->get("name"));
        } else {
            throw new Exception("Function 'start_div' is not defined.");
        }
    }

    /**
     * End the div element.
     */
    function end_div()
    {
        if (function_exists('end_div')) {
            end_div();
        } else {
            throw new Exception("Function 'end_div' is not defined.");
        }
    }
}