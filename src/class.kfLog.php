<?php

//!< Dependant upon PEAR LOG

$path_to_root="../..";

require_once( 'class.origin.php' );
//@include_once ( 'Log/file.php' );
require_once( 'class.write_file.php' );
require_once( 'defines.inc.php' );


/*
  define( 'PEAR_LOG_EMERG', 0 );
        define( 'PEAR_LOG_ALERT', 1 );
        define( 'PEAR_LOG_CRIT', 2 );
        define( 'PEAR_LOG_ERR', 3 );
        define( 'PEAR_LOG_WARNING', 4 );
        define( 'PEAR_LOG_NOTICE', 5 );
        define( 'PEAR_LOG_INFO', 6 );
        define( 'PEAR_LOG_DEBUG', 7 );
*/

class kfLog extends origin
{
	var $logobject;
	var $objWriteFile;

	function __construct( $filename = __FILE__, $level = PEAR_LOG_DEBUG )
	{
		parent::__construct();
		$conf = array();
		$filename = basename( realpath( $filename ) );
		try
		{
			if( $ret = include_once( 'Log/file.php' ) )
			{
				$this->logobject = new Log_file( $filename . "_debug_log_pear.txt", "", $conf, $level );
			}
			else
			{
				$this->logobject = new stdClass();
			}
		} catch( Exception $e )
		{
				$this->logobject = new stdClass();
		}
		$this->objWriteFile = new write_file( ".", $filename . "_debug_log." . date( 'YmdHis' ) . ".txt" );
		return;	
	}
	function __destruct()
	{
		$this->objWriteFile->__destruct();
	}
	function Log( $msg, $level )
	{
		if( ! isset( $this->loglevel ) )
		{
			$this->set( 'loglevel', PEAR_LOG_CRIT );
			$this->tell_eventloop( $this, "SETTINGS_QUERY", "app_log_level" );
		}
		if( strcmp( $level, "WARN" ) == 0 )
		{
			unset( $level );
			//$level = PEAR_LOG_WARN;
		//	$this->logobject->log( $msg, PEAR_LOG_WARN );
		}
		if( $this->loglevel >= $level )
		{
			$this->objWriteFile->write_line( $level . "//" . $this->loglevel .  "::" . $msg );
		}
		$this->objWriteFile->write_line( $msg );
		return;	
	}
	function log_0( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_EMERG );
	}
	function log_1( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_ALERT );
	}
	function log_2( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_CRIT );
	}
	function log_3( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_ERR );
	}
	function log_4( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_WARNING );
	}
	function log_5( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_NOTICE );
	}
	function log_6( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_INFO );
	}
	function log_7( $obj, $msg )
	{
		$this->Log( $msg, PEAR_LOG_DEBUG );
	}
	function build_interested()
	{
		parent::build_interested();
		$this->interestedin['NOTIFY_LOG_DEBUG']['function'] = "log_7";
                $this->interestedin['NOTIFY_LOG_INFO']['function'] = "log_6";
                $this->interestedin['NOTIFY_LOG_NOTICE']['function'] = "log_5";
                $this->interestedin['NOTIFY_LOG_WARNING']['function'] = "log_4";
                $this->interestedin['NOTIFY_LOG_ERR']['function'] = "log_3";
                $this->interestedin['NOTIFY_LOG_CRIT']['function'] = "log_2";
                $this->interestedin['NOTIFY_LOG_ALERT']['function'] = "log_1";
                $this->interestedin['NOTIFY_LOG_EMERG']['function'] = "log_0";
		
	}
}
?>

