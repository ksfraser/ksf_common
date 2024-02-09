<?php

require_once( 'class.rest_file_download.php' );
/**//******************************************
* This class used to be image_url.  Download an image.
*
************************************************/
class rest_image_url extends rest_file_download 
{
	protected $imageurl;
	function __construct( $imageurl = "" ) 
	{ 
                parent::__construct();
		$this->imageurl = $imageurl;
		if( strlen( $imageurl ) > 5 )
	 		$this->download_url( $this, $imageurl );
		else
	 		$this->download_url( $this, "" );
                $this->set( 'endpoint',  '' );
                $this->set( 'key', "" );
		$this->set( 'queryval', "" );
	}
	function build_interestedin()
	{
		//We don't want to reset our filename etc.
	}
}

/**//******************************************
* This is a temproary move.  RENAMED.
*
************************************************/
class image_url extends rest_image_url
{
	function __construct( $imageurl = "" )
	{
		parent::__construct( $imageurl );
		throw new Exception( "This function has been renamed.  You've called the old name!", KSF_CLASS_RENAMED_DEPREC );
	}
}
