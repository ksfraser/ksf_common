<?php

require_once( 'class.origin.php' );

class VIEW_TABLE extends origin
{
	protected $table_header_array;
	protected $table_item_array;
	protected $table_width;		//!<integer percentage whole number
	protected $table_style;		//!<integer def TABLESTYLE
	function __construct()
	{
		parent::__construct();
		$this->table_item_array = array();
		$this->table_header_array = array();
		$this->set_var( "table_width", "70" );
		$this->set( "table_style", TABLESTYLE );
	}
	function __toString()
	{
		$this->start_table();
		foreach( $this->table_header_array as $obj )
		{
			echo $obj;
		}
		foreach( $this->table_item_array as $obj )
		{
			echo $obj;
		}
		$this->end_table();
	}
	function end_table()
	{
		end_table();
	}
	/**//***********************
	* Create a new Table
	*
	* Normally would do sanity check on variables but they are set in the constructor.
	*
	**************************/
	function start_table()
	{
		start_table( $this->get( "table_style" ), "width=" . $this->get( "table_width" ) . "%");
	}
	/**//***********************************************************
	* Setting with data validation
	*
	****************************************************************/
	function set( $var, $value = null, $enforce = true )
	{
		switch( $var )
		{
			case "style_width":
				if( is_integer( $value ) )
				{
					//good start
				}
				else
				{
					//try to convert data type
					$tmp = (int)$value;
					if( is_integer( $tmp ) )
					{
						if( $tmp <> $value )
						{
							return FALSE;
						}
						$value = $tmp;
					}
				}
			default:
				parent::set( $var, $value, $enforce);
				break;
		}
	}
}
?>
