<?php

require_once( 'class.ksf_file.php' );

class ksf_file_csv extends ksf_file
{
	protected $size;
	protected $separator;
	protected $lines = array();	//!<array of arrays once run
	protected $linecount;
	protected $b_header;
	protected $b_skip_header;
	private $grabbed_header;
	protected $headerline;
	protected $enclosure;	//!<char
	protected $escapechar;	//!<char
	protected $fieldcount;	//!<int
	/**//******************************************
	* Setup the CSV reading class file
	*
	* @param string filename
	* @param int size of a line
	* @param char separator what character separates the CSV
	* @param bool is there a header
	* @param bool b_skip_header skip processing the header
	* @return none
	***********************************************/
	function __construct( $filename, $size, $separator, $b_header = false, $b_skip_header = false )
	{
		parent::__construct( $filename );
		$this->size = $size;
		$this->separator = $separator;
		$this->linecount = 0;
		$this->b_header = $b_header;
		$this->b_skip_header = $b_skip_header;
		$this->grabbed_header = false;
		$this->enclosure = null;
		$this->escapechar = null;
		$this->fieldcount = 0;
		$this->linecount = 0;
	}
	/**//**************************************************
	* Read a line from a CSV file
	*
	* @param none
	* @returns array the csv line split up.
	*******************************************************/
	/*@array@*/function readcsv_line()
	{
		if( !isset( $this->fp )  )
			throw new Exception( __CLASS__ . " required field not set: fp" );
		if( ! isset( $this->size )  )
			throw new Exception( __CLASS__ . " required field not set: size" );
		if( ! isset( $this->separator ) )
			throw new Exception( __CLASS__ . " required field not set: separator" );
		if( $this->b_header AND !$this->grabbed_header )
		{
			//fgetcsv( resource $stream, ?int $length = null, string $separator = ",", string $enclosure = "\"", string $escape = "\\"): array|false
			$this->headerline = fgetcsv( $this->fp, $this->size, $this->separator, $this->enclosure, $this->escapechar );
			$this->grabbed_header = true;
		}
		if( ! $this->b_header )
			$this->headerline = '';
		else
		{
		}
		$line = fgetcsv( $this->fp, $this->size, $this->separator );
		if( ! $this->fieldcount )
		{
			$this->set( "fieldcount", count( $line ) );
		}
		$this->linecount++;
		return $line;
	}
	function readcsv_entire()
	{
		if( ! isset( $this->fp ) )
			try {
				$this->open();
			} catch( Exception $e )
			{
				display_notification( $e->getMessage() );
				$this->lines = array();
				return;
			}
		while( $line = $this->readcsv_line() )
		{
			$this->lines[] = $line;
			$this->linecount++;
		}
	}
        /**//***************************************
        *
        *
        *
        *
        * @param
        * @returns
        *********************************************/
        function write_array_to_csv( $arr )
        {
                if( !isset( $this->fp ) )
		{
                        throw new Exception( "Fileponter not set", KSF_FILE_PTR_NOT_SET );
		}
		if( ! $this->bOpenedWrite )
		{
                        throw new Exception( "Fileponter was not opened for writing", KSF_FILE_READONLY );
		}
                fputcsv( $this->fp, $arr, $this->deliminater, $this->enclosure );
                //fputcsv( $this->fp, $arr, $this->deliminater, $this->enclosure, $this->escape_char );
        }


}

?>
