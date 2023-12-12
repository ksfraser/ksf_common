<?php

/******************************************************************************************************
*
*	20100626 KF
*
*	Adding the ability to have modules to the application framework
*	The idea is that there will be a settings table "modules" for the modules
*	where the modules can be turned on and off. Will also allow certain modules
*	to be run between certain dates. If isactive is on (1) if the onstart/enddate are set
*	then today's date needs to be between them for the module to run.  If isactive is off(1)
*	then the module will run if todays date is between offstart/enddate allowing the module to
*	be inactive but to turn on between certain dates.  This would be useful for setting up
*	future sales, etc without having to activate the module.
*		module_name 	(varchar)	module name
*		isactive	(bool)		Is the module turned on
*		onstartdate	(date)		If the module is turned on, does it have a start date
*		onenddate	(date)		If the module is turned on, does it have a expiry date
*		offstartdate	(date)		If the module is turned off, does it have a start date
*		offenddate	(date)		If the module is turned off, does it have a expiry date
*
*	Modules will be loadable classes
*	Each module will have the same name in the file as the filename
*		i.e. my_module.php will contain class my_module.
*	If the module needs additional files specific to it, they will 
*	reside in the my_module directory within the modules directory.
*
*/

require_once( model/modules.class.php );
$modules = new modules();
$modules->where = "(isactive = '1' and onstartdate <= '$today' and onenddate >= '$today')";
$modules->where .= " OR (isactive = '1' and onstartdate = NULL and onenddate = NULL)";
$modules->where .= " OR (isactive = '0' and offstartdate <= '$today' and offenddate >= '$today')";
//Get list of modules to run
$modules->Select();
foreach( $modules->resultsarray as $row )
{
	//load the module
	$modname = $row['module_name'];
	require_once( modules/$modname.php );
}


?>
