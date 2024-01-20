<?php

require_once( 'class.fa_origin.php' );

/*
if( ! defined( 'company_path' ) )
{
	function company_path()
	{
		return "";
	}
}
*/

class ksf_file extends fa_origin
{
	protected $fp;	//!< @var handle File Pointer
	protected $filename;	//!< @var string name of output file
	protected $tmp_dir;	//!< @var string temporary directory name
	protected $path;	//!<DIR where are the images stored.  default company/X/images...
	protected $file_contents;	//!<binary data stream - file contents
	protected $bDeleteFile;	//!<bool should we delete the file once we are done with it.
	protected $linecount;	//!<int
	function __construct( $filename = "file.txt" )
	{
		parent::__construct();
		$this->filename = $filename;
		$this->path = company_path() . '/images';
		$this->bDeleteFile = false;
	}
	function __destruct()
	{
		if( isset( $this->fp ) )
			$this->close();
		if( $this->bDeleteFile )
		{
			if( file_exists( $this->filename ) )
			{
				unlink( $this->filename );
			}
		}
	}
	function open()
	{
		$this->validateVariables();
		if( strlen( $this->path ) > 1 )
			$this->fp = fopen( $this->path . '/' . $this->filename, 'r' );
		else
			$this->fp = fopen( $this->filename, 'r' );
		if( !isset( $this->fp ) )
			throw new Exception( "Unable to set Fileponter when trying to open ". $this->filename );	
	}
	function open_for_write()
	{
		$this->validateVariables();
		$this->fp = fopen( $this->path . '/' . $this->filename, 'w' );
		if( !isset( $this->fp ) )
			throw new Exception( "Unable to set Fileponter when trying to open ". $this->filename );	
	}
	function close()
	{
		if( !isset( $this->fp ) )
			throw new Exception( "Trying to close a Fileponter that isn't set" );
		fflush( $this->fp );
		fclose( $this->fp );
		$this->fp = null;
	}
	/*@bool@*/function make_path()
	{
		$this->validateVariables();
		if( !$this->pathExists() )
			mkdir( $this->path );
		//Did we succeed?
		return $this->pathExists();
	}
	/*@bool@*/function pathExists()
	{
		$this->validateVariables();	
		return is_dir( $this->path );
	}
	/***************************************************************
	 * Check for the existance of a file
	 *
	 * 
	 * @return bool
	 * *************************************************************/
	/*@bool@*/function fileExists()
	{
		$this->validateVariables();
		$exists = file_exists( $this->path . '/' . $this->filename );
		if ( !is_file($this->_sourceFile) || !is_readable($this->_sourceFile)) {
           		return false;
        	}
		return $exists;
	}
	function validateVariables()
	{
		if( !isset( $this->path ) )
			throw new Exception( "Path variable not set" );
		if( !isset( $this->filename )  )									throw new Exception( "filename variable not set" );
	}
	/**//***************************************************************
	* Use PHP functions to read the file contents entire.
	*
	* @param none uses internal variables
	* @returns none sets internal variables
	********************************************************************/
	function getFileContents()
	{
		if( ! isset( $this->filename ) )
		{
			throw new Exception( "Filename not set.  Can't read an unspecified file", KSF_FIELD_NOT_SET );
		}
		$this->file_contents = file_get_contents($this->filename);
	}
	/**//***************************************************************
	* Grab a filename from the webserver after an upload.
	*
	* @param int id which file (on multi upload) to grab.  Default 0
	* @returns none sets internal variables
	********************************************************************/
	function uploadFileName( $id = 0 )
	{
		if( ! isset( $_FILES ) )
		{
			throw new Exception( "Can't set a filename when one not passed in", KSF_VAR_NOT_SET );
		}
		$this->filename = $_FILES['files']['tmp_name'][$id];
	}
	/**//********************************************************************************
     	* Remove the BOM (Byte Order Mark) from the beginning of the import row if it exists
	*
	* This function came from SuiteCRM ImportFile
	*
	* @param none
     	* @return void
     	*/
    	private function setFpAfterBOM()
    	{
        	if($this->fp === FALSE)
            		return;
        	rewind($this->fp);
        	$bomCheck = fread($this->fp, 3);
        	if($bomCheck != pack("CCC",0xef,0xbb,0xbf)) {
            		rewind($this->fp);
        	}
    	}

	/**//*************************************************************************
     	* Determine the number of lines in this file.
     	*
     	* @return int
     	*/
    	function getNumberOfLinesInfile()
    	{
        	$lineCount = 0;
        	if ($this->fp )
        	{
            		rewind($this->_fp);
            		while( !feof($this->_fp) )
            		{
                		if( fgets($this->_fp) !== FALSE)
                    			$lineCount++;
            		}
            		//Reset the fp to after the bom if applicable.
            		$this->setFpAfterBOM();
        	}
		$this->linecount = $lineCount;
        	return $lineCount;
    	}

}
?>
