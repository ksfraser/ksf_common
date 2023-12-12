<?php 

class Log {
	var $fp;

	function __construct( $logdir = ".", $logname = "logfile.txt")
	{
		$fp = fopen( $logdir . "/" . $logname, "w" );
		fwrite( $fp, "-----------------------------\n" );
		fwrite( $fp, "Started: " . date() );
		$this->fp = $fp;
		return;
	}
	function Log( $string )
	{
		fwrite( $this->fp, $string );
		fwrite( $this->fp, "\n" );
		flush( $fp );
	}
}

/* ************* 
*
*	Classes will come from the app itself
*
************* */
//include_once( '../model/' . $$classes );
/*
class QuoteService { 
  private $quotes = array("ibm" => 98.42);   

  function getQuote($symbol) { 
    if (isset($this->quotes[$symbol])) { 
      return $this->quotes[$symbol]; 
    } else { 
      throw new SoapFault("Server","Unknown Symbol '$symbol'."); 
    } 
  } 
} 
*/

$log = new Log();
$log->Log( "soapserver started" );

$server = new SoapServer( $path . "/wsdl/services.wsdl"); 
$log->Log( "Created server" );
$server->setClass("QuoteService"); 
$log->Log( "Added class quoteservice" );
if( $_SERVER["REQUEST_METHOD"] == "POST")
{
    $server->handle();
}
else
{
        foreach( $server->getFunctions() as $func )
        {
                $log->Log( "Func: $func" );
        }
    $server->handle();
}

?> 
