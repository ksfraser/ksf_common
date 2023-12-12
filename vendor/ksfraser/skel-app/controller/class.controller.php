<?php

$configArray = array();
class Controller extends generictable
{
	var $action;
	var $table;
//	private $observers;
//	function __construct()
	function Controller()
	{
		parent::__construct();
		//check Modules for config.*.php
		//Add the modules into array of notification observers
		//using $this->ObserverRegister( /* @class@ */$observer, /*NOTIFY_XX_XX*/ $event, /*@int@*/ $priority );
		//each config file sets variables in $configArray
		global $configArray;

		//echo "Constructor of class Controller<br />\n";

		/*
		 * Open each Module file config file
		 */
		require_once( 'local.php' ); //Need to get the MODULEDIR
	        $moduledir = MODULEDIR;
	        foreach (glob("{$moduledir}/config.*.php") as $filename)
	        {
			//echo "opening module config file " . $filename . "<br />\n";
	                include_once( $filename );
	        }
/*
 *        	//Set it up so that we don't have to require_once everything
 *        	function model_autoloader($class) {
 *        	        include( MODELDIR . '/' . $class . '.class.php' );
 *        	}
 *        	spl_autoload_register('model_autoloader');
 */
	
		/*
		 * Loop through the $configArray to set loading modules in right order
		 */
		//var_dump( $configArray );
		foreach( $configArray as $carray )
		{
			//var_dump( $carray );
			$modarray[$carray['loadpriority']][] = $carray;
		}

 		/* 
		 * locate Module class files to open 
		 */
		//var_dump( $modarray );
		foreach( $modarray as $priarray )
		{
			foreach( $priarray as $marray )
			{
		
				$res = include_once( MODULEDIR . "/" . $marray['loadFile'] );
				if( TRUE == $res )
				{
					$this->ObserverNotify( 'NOTIFY_LOG_INFO', "Module " . $marray['ModuleName'] . " being added" );
					$marray['objectName'] = new $marray['className'];
					if( isset( $marray['objectName']->observers ) )
					{
						foreach( $marray['objectName']->observers as $obs )
						{
							$this->observers[] = $obs;
						}
					}
				}
				else
				{
					echo "Attempt to open " . MODULEDIR . "/" . $marray['loadFile'] . " FAILED!<br />";
				}
			}
		}
		$this->ObserverNotify( 'NOTIFY_LOG_INFO', "Completed Adding Modules" );
		$this->ObserverNotify( 'NOTIFY_INIT_CONTROLLER_COMPLETE', "Completed Adding Modules" );
	}
	function dumpObservers()
	{
		if( isset( $this->observers ) )
		{
			foreach( $this->observers as $key=>$val )
			{
				echo "Observer Event: " . $key . " with value " . $val;
			}
/*
			foreach( $this->observers as $obs )
			{
				var_dump( $obs );
			}
*/
		}
	}
}
?>
