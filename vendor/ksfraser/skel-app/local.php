<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
header("location: index.php");
exit;
}

$BASEURL="/BASEURL/";
$pathlen = strlen( __FILE__ );
$namelen = strlen( basename( __FILE__ ) );
$docrootlen = strlen( $_SERVER['DOCUMENT_ROOT'] );
//echo "Pathlen: $pathlen :: Namelen: $namelen :: DocrootLen: $docrootlen::\n";
$path = __FILE__;
$appdir = substr( $path, $docrootlen, $pathlen - $namelen - $docrootlen -1 );
/*
echo "\n" . $path . "<br />\n" . $appdir . "<br />\n";
echo $pathlen;
echo "<br />\n";
echo $namelen;
echo "<br />\n";
echo $docrootlen;
echo "<br />\n";
*/

if ( $_SERVER['DOCUMENT_ROOT'] == ( dirname( __FILE__ ) . "/" ) )
{
        //Called from a virtual server with its own doc root
        //echo "NO SUBSTRING<br />";
        $appdir = ".";
        $cssdir = ".";
}
else
{
        $appdir = substr( $path, $docrootlen, $pathlen - $namelen - $docrootlen - 1 );
        $cssdir = substr( $path, $docrootlen, $pathlen - $namelen - $docrootlen - 1 );
        $cssdir = ".";
}


$railroadnumber = 1;

define( WHOAMI, "01" );
define( CENTRAL, "00" );

define ('SUCCESS', 1);
define('NOSMARTY', 1);
define( 'FAILURE', "2" );
//Shortest normal barcode is 7 characters long
define( 'MINBARCODELEN', 7 ); 
$viewdir = "view/";
$datadir = "data/";
$classdir = "model/";
define ( 'APPDIR', dirname( __FILE__ ) );
define ( 'BASEDIR', dirname( __FILE__ ) );
define ( 'BINDIR', APPDIR . "/bin" );
define ( 'CSSDIR', APPDIR . "/css" );
define ( 'VIEWDIR', APPDIR . "/view" );
define ( 'DATADIR', APPDIR . "/data" );
define ( 'CLASSDIR', APPDIR . "/model" );
define ( 'MODELDIR', APPDIR . "/model" );
define ( 'REPORTDIR', APPDIR . "/reports" );
define ( 'SCRIPTDIR', APPDIR . "/scripts" );
define ( 'CONFIGDIR', APPDIR . "/etc" );
define ( 'LOGDIR', APPDIR . "/logs" );
define ( 'JOBSDIR', APPDIR . "/jobs" );
define ( 'JSDIR', APPDIR . "/js" );
define ( 'MODULEDIR', APPDIR . "/modules" );
define ( 'JSDIR', APPDIR . "/js" );
define ( 'MSGDIR', APPDIR . "/msqgueue" );
define ( 'RPCDIR', APPDIR . "/rpc" );
define ( 'WSDLDIR', APPDIR . "/wsdl" );
define ( 'WIKIDIR', APPDIR . "/wiki" );
define ( 'SQLDIR', APPDIR . "/sql" );
//echo "<br />" . MODELDIR . "<br />";
require_once( $datadir . 'db.php');

	function Local_DB()
	{
                $db = new my_db( 'localhost', 'codemeta', 'codemeta', 'codemeta');
		return $db;
	}

	function menufromls($listingkey = ".")
	{
		$menutable = '<div class="mtable"><table border=2>';
		if ($dh = opendir("."))
		{
//		echo "in dir\n";
			while (($file = readdir($dh)) !== false)
			{
//			echo "read dir, searching for $listingkey, in filename $file\n";
				if (stristr($file, $listingkey) != false )
				{
				"File $file\n";
					$menutable .= '<tr><td><a href="' . $file . '">' . $file . '</a></td></tr>';
				}
				else
				{
			//		echo "$file did not match $listingkey\n";
				}
			}
		}
		$menutable .= '</table></div>';
		return $menutable;
	}

	function CSS()
	{
		return;
	}

function Local_Menu()
{
//	echo "LOCALMENU CSS<br />";
	$menucss = CSS();
	$menu = NULL;
//	echo "LOCALMENU<br />";
	//$menuls = menufromls(".list.php");
	//$menuls .= menufromls(".insert.php");
	//$menuls .= menufromls(".update.php");
	//$menuls .= menufromls(".delete.php");
	//$menuls .= menufromls(".search.php");
//	echo "LOCALMENU ATOMIC<br />";
	require_once( 'view/atomic.php' );
	//$menu = new atomic($menuls);
	//echo "LOCALMENU POST ATOMIC<br />";
	return $menu;
}

function Local_Init()
{
	error_reporting(E_ALL);
//	display_errors(OFF);
//	log_errors(ON);
	error_log('syslog');
}
	
	
?>
