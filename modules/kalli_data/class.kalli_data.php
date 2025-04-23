<?php

require_once( '../class.origin.php' );
require_once('../../src/class.data_validate.php');

/*****************************************************//**
* A class for all other classes to dump their data into.
*
* Currently the classes I am re-writing use an array
* to store the data in.  Transitioning to using a class
* so that it can do data validity checks etc. 
*
*********************************************************/

class dataContainer extends origin
{
	protected $b_mandatory_set;	//!< bool has the mandatory fields been set
	protected $mandatory_fields_array;	//!< array the list of mandatory fields
	protected $dataType_array;	//!< array the data types of our fields for validity checking
	protected $validator;

	public function __construct($validator)
	{
		parent::__construct();
		$this->validator = $validator;
		$this->b_mandatory_set = false;
		$this->mandatory_fields_array = $this->mandatory_fields_array ?? [];
		$this->dataType_array = $this->dataType_array ?? [];
	}

	public function is_mandatory_set()
	{
		return $this->validator->areMandatoryFieldsSet($this->mandatory_fields_array, $this);
	}

	public function set($field, $value, $force = true, $validate = true)
	{
		if ($validate && isset($this->dataType_array[$field])) {
			$datatype = $this->dataType_array[$field];
			if (!$this->validator->validateType($value, $datatype)) {
				throw new Exception("Invalid data type for $field. Expected $datatype.", KSF_INVALID_TYPE);
			}
		}
		parent::set($field, $value, $force);
	}
}

class kalli_data extends dataContainer {
        protected $Title;
        protected $author;
        protected $publisher;
        protected $year;
        protected $releasedate;
        protected $keywords;
        protected $comments;
        protected $Summary;
        protected $chaptersURL;
        protected $azDetailPageURL;
        protected $isbn13;
        protected $isbn;
        protected $upc;
        protected $pages;
        protected $numberofdisks;
        protected $Length;
        protected $Media;
	protected $format;
	protected $rating; //MPAA rating
        protected $Image;
        protected $image;
        protected $coverimage;
        protected $imdbnumber;
        protected $ASIN;
        protected $GoogleDetailspage;
        protected $CoverImage;
	protected $fields_set;
	protected $etag;	//!< string etag from Google
	protected $copied_from_array; //!< array of class names that was the source
	protected $brand;
      	protected $model;
      	protected $color;
      	protected $size;
      	protected $dimension;
      	protected $weight;
      

	function __construct($validator)
	{
		parent::__construct($validator);
		$this->mandatory_fields_array = array( 'Title', 'upc' );
		$this->copied_from_array = array();
  		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
	}
    //            $this->ObserverNotify( 'NOTIFY_DETAILS_SET', $this, $this );
	function finish()
	{
  		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
	}
  	/***************************************************************//**
         *build_interestedin
         *
         *      This function builds the table of events that we
         *      want to react to and what handlers we are passing the
         *      data to so we can react.
         * ******************************************************************/
        function build_interestedin()
        {
                $this->interestedin["NOTIFY_DATA_FOR_COPY"]['function'] = "copy_from_source";
        }
	/**************************************************//**
	 * Copy fields from a source to our values
	 *
	 * This class is to act as an intermediary between
	 * data sources that we query, and various data
	 * stores that we are saving to.  This acts as a generic
	 * MITM data dump so that each source and target are
	 * decoupled.
	 *
	 * @param caller object that has the data
	 * @param data array of conversion matches
	* return null
	 * ****************************************************/
	function copy_from_source( $caller, $data )
	{
  		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		if( ! is_array( $data ) OR count( $data ) < 2 )
		{
  			$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ . " Data Source didn't pass in a valid conversion array.  Will try to build!" );
			if( method_exists( $caller, 'build_conversion_array' ) AND is_callable( array( $caller, 'build_conversion_array' ) ) )
			{
				$caller->build_conversion_array();
			}
			else
			{
  				$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ . " Data Source CAN'T build a valid conversion array.!" );
				return;
			}
		}
/* I think this was supposed to be inside the if above :(
		else
		{
			return;
		}
*/
		$fields = 0;
		$source = get_class( $caller );
		$merged = 0;
		foreach( $this->fields as $field )
		{
			try
			{
				if( ! is_array( $data[$field] ) )
				{
					$fd = $data[$field];
					$val = $caller->get( $fd );
				}
				else
				{
					$val = "";
					foreach( $data[$field] as $fd )
					{
						$val .= " - " . $caller->get( $fd );
					}
				}
				if( isset( $this->$field ) )
				{
					if( $this->merge( $field, $val, $source ) )
						$merged++;
				}
				else
				{
					$this->set( $field, $val );
				}
				$fields++;
			}
			catch( Exception $e )
			{
				//If caller isn't set, we can't copy it...
			}
		}
		$this->copied_from_array[] = array( 'source' => $source, 'fields' => $fields, 'merged' => $merged );
	}
	/**********************************************//*
	 * Set a field with HTML/PHP tags stripped
	 *
	 * Overrides and calls our parent's set
	 * @param field string the field to set
	 * @param value string the value to set
	 * @return null
	 * ************************************************/
	function set( $field, $value )
	{
		$val = strip_tags( $value );
		parent::set( $field, $val);
		return;
	}
	/******************************************************//**
	 * Merge data from different sources.
	 *
	 * TODO: make it so future merges can be detected and checked for duplicates
	 * 	As is now, the second time merge is called we can't find a duplicate
	 * 	string since we've concatenated two sources.
	 *
	 * @param fieldname string name of field to set/merge
	 * @param data string the data to put in field
	 * @param src string class name of source of data
	 * @return bool
	 * ********************************************************/
	function merge( $fieldname, $data, $src = null )
	{
		//need to check if fieldname has data already (hence the need to merge)
		//then compare data
		//then merge data if different
		$orig = $this->get( $fieldname );
		if( strcasecmp( $orig, $data ) == 0 )
		{
			//They are identical, do nothing
			return TRUE;
		}
		else
		{
			$_o = strip_tags( htmlspecialchars( htmlentities( $orig ) ) );
			$_d = strip_tags( htmlspecialchars( htmlentities( $data ) ) );
			if( strcasecmp( $_o, $_d ) == 0 )
			{
				//They are identical, do nothing
				return TRUE;
			}
			else
			{
				$this->set( $fieldname, $orig . " :: " . htmlspecialchars( htmlentities( $data ) ) );
				return TRUE;
			}
			return FALSE;
		}
	}
}

class kalli_data_wp_post extends kalli_data
{
	protected $wp_post_id;
}
