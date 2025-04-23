<?php

require_once( 'class.origin.php' );
class VIEW_ROW extends origin
{
	protected $row_item_array;
	function __construct()
	{
		parent::__construct();
		$this->row_item_array = array();
	}
	function __toString()
	{
		$this->start_row();
		foreach( $this->row_item_array as $obj )
		{
			echo $obj;
		}
		$this->end_row();
	}
	function start_row()
	{
		start_row();
	}
	function end_row()
	{
		end_row();
	}
}
