<?php

	//https://www.googleapis.com/books/v1/volumes?q=isbn:9780441018697&key=AIzaSyCsvXz4TDq6ShUV_oJ4aOVlJerrvP6lZkI
 //$baseurl = 'https://www.googleapis.com'; 
 //$endpoint = '/books/v1/volumes';
 //$endpoint = '/books/v1/volumes?key=AIzaSyCsvXz4TDq6ShUV_oJ4aOVlJerrvP6lZkI&q=isbn:';
 //$isbn = '9780441018697';
 //$queryval = $isbn;

require_once( 'class.origin.php' );

class rest_interface extends origin {
	protected $baseurl;
	protected $endpoint;
	protected $queryval;
	protected $key;
	protected $header;
	protected $curl;
	protected $response_raw;
	protected $response_decoded;
	protected $url;

	function __construct( $baseurl, $endpoint, $queryval, $key )
	{
		$this->set( 'baseurl', $baseurl );
		$this->set( 'endpoint', $endpoint );
		$this->set( 'queryval', $queryval );
		$this->set( 'key', $key );
		parent::__construct();
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
	}
	function __destruct()
	{
		if( isset( $this->curl ) )
			curl_close( $this->curl );
	}
	function set_header()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
 		$this->header = array();  
	}
	function build_url()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		$this->url = $this->baseurl . '/' . $this->endpoint . '/' . $this->queryval; 
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', 'URL ' . $this->url );
	}
	function init_curl()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		$this->curl = curl_init();
 		curl_setopt($this->curl,CURLOPT_URL,$this->url);  
 		curl_setopt($this->curl,CURLOPT_HTTPHEADER,$this->header);  
 		curl_setopt($this->curl,CURLOPT_RETURNTRANSFER, true);  
	}
	function curl_query()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		$this->response_raw = curl_exec( $this->curl );
	}
	function decode_json()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		if( isset( $this->response_raw ) )
		{
			$this->response_decoded = json_decode( $this->response_raw, true );
			return TRUE;
		}
		else
			return FALSE;
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
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
        }
	function run()
	{
		$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
		$this->build_url();
		$this->set_header();//Google vice isbn
		$this->init_curl();
		$this->curl_query();
		if( $this->decode_json() )
		{
			$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
			return $this->response_decoded;
		}
		else
		{
			$this->tell_eventloop( $this, 'NOTIFY_LOG_DEBUG', get_class( $this ) . "::" . __FUNCTION__ . "::" . __LINE__ );
			return $this->response_raw;
			//return NULL;
		}
	}
}

		
