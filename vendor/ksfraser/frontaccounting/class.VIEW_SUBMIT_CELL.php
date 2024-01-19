<?php

require_once( 'class.VIEW_CELL.php' );

class VIEW_SUBMIT_CELL extends VIEW_CELL
{
//aspect='default'  name="RefreshInquiry"  id="RefreshInquiry" value="Search" title='Refresh Inquiry'
	protected $name;
	protected $id;
	protected $value;
	protected $title;
	protected $aspect;
	function __construct()
	{
		parent::__construct();
	}
	function __toString()	
	{
		submit_cells( 
				$this->get( "id" ),
				$this->get( "value" ),
				$this->get( "title" ),
				$this->get( "aspect" )
			);
	}
}
?>
