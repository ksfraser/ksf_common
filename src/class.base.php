<?php

namespace Ksfraser\Common;

error_reporting( E_ALL );
ini_set("display_errors", 1);
require_once( __DIR__ . '/../../includes/db/connect_db.inc' );
require_once( __DIR__ . '/../../includes/errors.inc' );
//20170106
require_once( __DIR__ . '/class.origin.php' );
//!20170106

//class base 
//20170106
/***********************************************************//**
 *Adds file writing onto ORIGIN class.  Could use a better class name
 *
 * Inherits:
 * 	fcn LogMsg
 * 	fcn LogError
 *	fcn fields2data
 *	fcn var2data
 *	fcn get_var
 *	fcn set_var
 *
 * ***************************************************************/
class base extends origin
{
	var $username;
	var $password;
	var $errmsg;
	var $debug;
	var $json_decode_as_array = FALSE;

	const HASH_ALGORITHM = 'SHA256';

	function __construct( /*array*/ $args = array() )
	{
		$this->parse_args( $args );
	}
	function __destruct()
	{
	}
	
	/**********************************************************************
	 * Options is an array of options so needs to be handled recursively
	 *
	 * *******************************************************************/
	function parse_args( /*array*/$args )
	{
		foreach( $args as $key=>$value )
		{
			if( $key = "options" )
			{
				$this->parse_args( $value );
			}
			else
			{
				$this->$key = $value;
			}
		}
	}
	function open_write_file( $filename )
	{
		return fopen( $filename, 'w' );
	}
	function write_line( $fp, $line )
	{
		fwrite( $fp, $line . "\n" );
	}
	function file_finish( $fp )
	{
		fflush( $fp );
		fclose( $fp );
	}
}

class BaseEventLoop {
    protected $eventloop;

    public function attach_eventloop($create_if_not_exist = true) {
        if (!isset($this->eventloop)) {
            global $eventloop;
            if (isset($eventloop) && is_object($eventloop) && get_class($eventloop) === "eventloop") {
                $this->eventloop = $eventloop;
                return true;
            } elseif ($create_if_not_exist) {
                return $this->create_eventloop();
            } else {
                return false;
            }
        }
        return true;
    }

    public function create_eventloop() {
        require_once('class.eventloop.php');
        global $eventloop;
        if (!isset($eventloop) || get_class($eventloop) !== "eventloop") {
            $eventloop = new eventloop();
        }
        $this->eventloop = $eventloop;
        return true;
    }

    public function tell_eventloop($caller, $event, $msg) {
        if ($this->attach_eventloop()) {
            $this->eventloop->ObserverNotify($caller, $event, $msg);
        }
    }
}

?>
