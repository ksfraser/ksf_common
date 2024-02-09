<?php

require_once( 'class.fa_origin.php' );
//require_once( 'class.origin.php' );
//require_once( 'defines.inc.php' );

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
        protected $filesize;    //!<int
        protected $filepath;    //!<string full path
        protected $filecontents;        //!<binary file contents.
/**
        protected $deliminater; //!< fputcsv control value
        protected $enclosure;   //!< fputcsv control value
        protected $escape_char; //!< fputcsv control value
  **/ 
	protected $bOpenedWrite;	//!<bool opened for writing.
        /**//*****************************************************
        * Construct the File handling class
        *
        * @param string filename
        * @param string (optional)path
        * @return none sets internal variables.
        ***********************************************************/
        function __construct( $filename = "file.txt", $path = null )
	{
		parent::__construct();
		$this->filename = $filename;
		$this->bOpenedWrite = false;
		if(  defined( 'company_path' ) )
		{
			$this->path = company_path() . '/images';
		}
		else
		{
		}
                if( null !== $path )
                {
                        $this->path = $path;
                }
                else
                {
                        $path = dirname();
                }
		$this->bDeleteFile = false;

                if( strlen( $this->path ) > 1 )
                        $this->filepath = $this->path . '/' . $this->filename;
                else
                        $this->filepath = $this->filename;
                $this->filesize = filesize( $this->filepath );
	}
	function __destruct()
	{
		if( isset( $this->fp ) )
			$this->close();
		if( $this->bDeleteFile )
		{
			if( file_exists( $this->filename ) )
			{
				$this->unlink();
			}
		}
	}
	/**//***********************************************
	* Delete (unlink) a file
	*
	*	Will delete symlink on Linux
	*	On Windows to delte a symlink to a directory rmdir must be used
	*
	* @param string filename (optional) deletes ->filename
	* @return bool Did we succeed
	******************************/
	function unlink( $filename = null )
	{
		if( null !== $filename )
		{
			return unlink( $filename );
		}
		else
		{
			return unlink( $this->filename );
		}
	}
	/**//*********************************************
	* Alias to unlink
	*
	* @param string filename (optional) deletes ->filename
	* @return bool Did we succeed
	******************************/
	function delete( $filename = null )
	{
		return $this->unlink( $filename );
	}
        /**//*****************************************************
        * Open the file
        *
        * @param none uses internal variables.
        * @return none sets internal variables.
        ***********************************************************/
	function open()
	{
		$this->validateVariables();
		if( strlen( $this->path ) > 1 )
			$this->fp = fopen( $this->path . '/' . $this->filename, 'r' );
		else
			$this->fp = fopen( $this->filename, 'r' );
		if( !isset( $this->fp ) )
                        throw new Exception( "Unable to set Fileponter when trying to open ". $this->filename, KSF_FILE_OPEN_FAILED );
	}
	function open_for_write()
	{
		$this->validateVariables();
		$this->fp = fopen( $this->path . '/' . $this->filename, 'w' );
		if( !isset( $this->fp ) )
			throw new Exception( "Unable to set Fileponter when trying to open ". $this->filename, KSF_FILE_OPEN_FAILED );	
		$this->bOpenedWrite = true;
	}
	function close()
	{
		if( !isset( $this->fp ) )
			throw new Exception( "Trying to close a Fileponter that isn't set", KSF_FILE_PTR_NOT_SET );
		fflush( $this->fp );
		fclose( $this->fp );
		$this->fp = null;
	}
	/**//***************************************
	*
	*
	*
	*
	* @param string
	* @returns none
	*********************************************/
        function write_chunk( $line )
        {
                if( !isset( $this->fp ) )
                        throw new Exception( "Fileponter not set", KSF_FILE_PTR_NOT_SET );
                fwrite( $this->fp, $line );
                fflush( $this->fp );
        }
	/**//***************************************
	*
	*
	*
	*
	* @param string
	* @returns none
	*********************************************/
        function write_line( $line )
        {
                if( !isset( $this->fp ) )
                        throw new Exception( "Fileponter not set", KSF_FILE_PTR_NOT_SET );
                fwrite( $this->fp, $line . "\r\n" );
                fflush( $this->fp );
        }
	/**//***************************************
	*
	*
	*
	*
	* @param array
	* @returns Exception
	*********************************************/
        function write_array_to_csv( $arr )
        {
		throw new Exception( "You are using the wrong class.  Use ksf_file_csv", KSF_FCN_REFACTORED   );
        }

	/**//***************************************
	*
	*
	*
	*
	* @param none
	* @returns bool
	*********************************************/
	/*@bool@*/function make_path()
	{
		$this->validateVariables();
		if( !$this->pathExists() )
			mkdir( $this->path );
		//Did we succeed?
		return $this->pathExists();
	}
	/**//***************************************
	*
	*
	*
	*
	* @param none
	* @returns bool
	*********************************************/
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
		if( strlen( $this->path ) > 1 )
			$file = $this->path . '/' . $this->filename;
		else
			$file = $this->filename;
		$exists = file_exists( $file );
		if ( !is_file( $file ) || !is_readable( $file ) ) {
           		return false;
        	}
		return $exists;
	}
	function validateVariables()
	{
                if( !isset( $this->path ) )
                        throw new Exception( "Path variable not set", KSF_FIELD_NOT_SET );
                if( !isset( $this->filename )  )
                        throw new Exception( "filename variable not set", KSF_FIELD_NOT_SET );
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
	/**//*********************************************************************
	* Read the entire file using fread
	*
	* @param none
	* @returns stream file contents
	*************************************************************************/
	function fread()
	{
                if( ! isset( $this->fp ) )
                {
                        throw new Exception( "File Pointer not set, can't read", KSF_FILED_NOT_SET );
                }
                if( ! isset( $this->filesize ) )
                {
                        throw new Exception( "File Size not set", KSF_FILED_NOT_SET );
                }
                $this->filecontents = fread( $this->fp, $this->filesize );
                return $this->filecontents;
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
/*
        fwrite() - Binary-safe file write
        fsockopen() - Open Internet or Unix domain socket connection
        popen() - Opens process file pointer
        fgets() - Gets line from file pointer
        fgetss() - Gets line from file pointer and strip HTML tags
        fscanf() - Parses input from a file according to a format
        file() - Reads entire file into an array
        fpassthru() - Output all remaining data on a file pointer
        fseek() - Seeks on a file pointer
        ftell() - Returns the current position of the file read/write pointer
        rewind() - Rewind the position of a file pointer
        unpack() - Unpack data from binary string
        readfile() - Outputs a file
        file_put_contents() - Write data to a file
        stream_get_contents() - Reads remainder of a stream into a string
        stream_context_create() - Creates a stream context

basename — Returns trailing name component of path
chgrp — Changes file group
chmod — Changes file mode
chown — Changes file owner
clearstatcache — Clears file status cache
copy — Copies file
delete — See unlink or unset
dirname — Returns a parent directory's path
disk_free_space — Returns available space on filesystem or disk partition
disk_total_space — Returns the total size of a filesystem or disk partition
diskfreespace — Alias of disk_free_space
fclose — Closes an open file pointer
fdatasync — Synchronizes data (but not meta-data) to the file
feof — Tests for end-of-file on a file pointer
fflush — Flushes the output to a file
fgetc — Gets character from file pointer
fgetcsv — Gets line from file pointer and parse for CSV fields
fgets — Gets line from file pointer
fgetss — Gets line from file pointer and strip HTML tags
file_exists — Checks whether a file or directory exists
file_get_contents — Reads entire file into a string
file_put_contents — Write data to a file
file — Reads entire file into an array
fileatime — Gets last access time of file
filectime — Gets inode change time of file
filegroup — Gets file group
fileinode — Gets file inode
filemtime — Gets file modification time
fileowner — Gets file owner
fileperms — Gets file permissions
filesize — Gets file size
filetype — Gets file type
flock — Portable advisory file locking
fnmatch — Match filename against a pattern
fopen — Opens file or URL
fpassthru — Output all remaining data on a file pointer
fputcsv — Format line as CSV and write to file pointer
fputs — Alias of fwrite
fread — Binary-safe file read
fscanf — Parses input from a file according to a format
fseek — Seeks on a file pointer
fstat — Gets information about a file using an open file pointer
fsync — Synchronizes changes to the file (including meta-data)
ftell — Returns the current position of the file read/write pointer
ftruncate — Truncates a file to a given length
fwrite — Binary-safe file write
glob — Find pathnames matching a pattern
is_dir — Tells whether the filename is a directory
is_executable — Tells whether the filename is executable
is_file — Tells whether the filename is a regular file
is_link — Tells whether the filename is a symbolic link
is_readable — Tells whether a file exists and is readable
is_uploaded_file — Tells whether the file was uploaded via HTTP POST
is_writable — Tells whether the filename is writable
is_writeable — Alias of is_writable
lchgrp — Changes group ownership of symlink
lchown — Changes user ownership of symlink
link — Create a hard link
linkinfo — Gets information about a link
lstat — Gives information about a file or symbolic link
mkdir — Makes directory
move_uploaded_file — Moves an uploaded file to a new location
parse_ini_file — Parse a configuration file
parse_ini_string — Parse a configuration string
pathinfo — Returns information about a file path
pclose — Closes process file pointer
popen — Opens process file pointer
readfile — Outputs a file
readlink — Returns the target of a symbolic link
realpath_cache_get — Get realpath cache entries
realpath_cache_size — Get realpath cache size
realpath — Returns canonicalized absolute pathname
rename — Renames a file or directory
rewind — Rewind the position of a file pointer
rmdir — Removes directory
set_file_buffer — Alias of stream_set_write_buffer
stat — Gives information about a file
symlink — Creates a symbolic link
tempnam — Create file with unique file name
tmpfile — Creates a temporary file
touch — Sets access and modification time of file
umask — Changes the current umask
unlink — Deletes a file
*/

}
?>
