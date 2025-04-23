<?php

require_once( 'class.VIEW_CELL.php' );

class VIEW_DATE_CELL extends VIEW_CELL
{
	function __construct()
	{
		parent::__construct();
	}
	function __toString()	
	{
		date_cells( 
				_($this->get( "label" ) ),
				$this->get( "f1" ),
				$this->get( "f2" ),
				$this->get( "f3" ),
				$this->get( "f4" )
			);
	}
}
