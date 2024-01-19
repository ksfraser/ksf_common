<?php

require_once( 'class.origin.php' );

class VIEW_TABLE_TD extends origin
{
	protected $td_item;
	function __construct( $value = "" )
	{
		parent::__construct();
		$this->set( "td_item", $value );
	}
	function __toString()
	{
		$this->start_td();
		echo $this->td_item;
		$this->end_td();
	}
	function start_td()
	{
		echo "<td>";
	}
	function end_td()
	{
		echo "</td>";
	}
}
?>
