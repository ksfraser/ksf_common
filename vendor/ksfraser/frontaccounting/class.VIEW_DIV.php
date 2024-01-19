<?php

require_once( 'class.origin.php' );

class VIEW_DIV extends origin
{
	protected $name;
	protected $div_item_array;
	function __construct( $name = "" )
	{
		parent::__construct();
		$this->div_item_array = array();
		$this->set( "name", $name );
	}
	function __toString()
	{
		$this->start_div();
		foreach( $this->div_item_array as $obj )
		{
			echo $obj;
		}
		$this->end_div();
	}
	function start_div()
	{
		start_div( $this->get( "name" ) );
	}
	function end_div()
	{
		end_div();
	}
}
?>
