<?php

require_once( 'class.VIEW_CELL.php' );


class VIEW_AMOUNT_CELL extends VIEW_CELL
{
	function __construct( $f1 = "" )
	{
		parent::__construct();
		$this->set( "f1", $f1 );
	}
	function __toString()	
	{
		amount_cells( 
				$this->get( "f1" ),
			);
	}
}

