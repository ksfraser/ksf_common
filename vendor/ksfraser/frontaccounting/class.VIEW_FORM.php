<?php

require_once( 'class.origin.php' );

class VIEW_FORM extends origin
{
	protected $form_item_array;
	function __construct(  )
	{
		parent::__construct();
		$this->form_item_array = array();
	}
	function __toString()
	{
		$this->start_form();
		foreach( $this->form_item_array as $obj )
		{
			echo $obj;
		}
		$this->end_form();
	}
	function start_form()
	{
		start_form();
	}
	function end_form()
	{
		end_form();
	}
}
?>
