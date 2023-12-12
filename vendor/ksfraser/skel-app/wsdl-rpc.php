<?php


 /*
  *      WSDL file
<?xml version ='1.0' encoding ='UTF-8' ?>
   <definitions name='test'
       targetNamespace='http://192.168.1.15/test'
       xmlns:tns=' http://192.168.1.15/test '
       xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'
       xmlns:xsd='http://www.w3.org/2001/XMLSchema'
       xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'
       xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'
       xmlns='http://schemas.xmlsoap.org/wsdl/'>

   <message name='testRequest'>
       <part name='symbol' type='xsd:string'/>
   </message>
   <message name='testResponse'>
       <part name='Result' type='xsd:float'/>
   </message>

   <portType name='testPortType'>
      <operation name='test'>
          <input message='tns:testRequest'/>
          <output message='tns:testResponse'/>
      </operation>
   </portType>
   <binding name='testBinding' type='tns:testPortType'>
      <soap:binding style='rpc'
            transport='http://schemas.xmlsoap.org/soap/http'/>
      <operation name='test'>
      <soap:operation soapAction='urn:xmethods-delayed-quotes#test'/>
        <input>
            <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes'
                       encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
        </input>
        <output>
            <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes'
                 encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>
        </output>
      </operation>
    </binding>
       <service name='testService'>
           <port name='testPort' binding='tns:testBinding'>
              <soap:address location='http://192.168.1.15/pmtools/rpc/soaptest.php'/>
           </port>
       </service>
</definitions>
        */


function generateWSDLheader( $baseURL = "http://192.168.1.14/rpc", $appName = "test" )
{
	$header = "<?xml version ='1.0' encoding ='UTF-8' ?>\t <definitions name='" . $appName . "'\n\t targetNamespace='" . $baseURL . "/" . $appName . "' xmlns:tns='" . $baseURL . "/" . $appName . "'\n\t xmlns:soap='http://schemas.xmlsoap.org/wsdl/soap/'\n\t xmlns:xsd='http://www.w3.org/2001/XMLSchema'\n\t xmlns:soapenc='http://schemas.xmlsoap.org/soap/encoding/'\n\t xmlns:wsdl='http://schemas.xmlsoap.org/wsdl/'\n\t xmlns='http://schemas.xmlsoap.org/wsdl/'>\n\n
	";

	return $header;
}

function generateWSDLtrailer()
{
	$trailer = "</definitions>";
	return $trailer;
}

function generateWSDLmessage( $name, $inputArray, $outputArray )
{
	$starttag =  "<message name='" . $name . "'>\n\t";
	$endtag = "</message>";
	$inpart = "";
	$outpart = "";
	foreach( $inputArray as $symbol => $type )
	{
		$inpart = "<part name='request" . $symbol . "' type='xsd:" . $type . "'/>";
	}
	foreach( $outputArray as $symbol => $type )
	{
		$outpart = "<part name='response" . $symbol . "' type='xsd:" . $type . "'/>";
	}
	$message = $starttag . $inpart . $endtag . "\n\t" . $starttag . $outpart . $endtag . "\n";
	return $message;
}

function generateWSDLbinding( $name )
{
	$binding = "<binding name='" . $name . "Binding' type='tns:" . $name . "PortType'>\n\t
      			<soap:binding style='rpc' transport='http://schemas.xmlsoap.org/soap/http'/>\n\t\t <operation name='" . $name . "'>\n\t\t\t <soap:operation soapAction='urn:xmethods-delayed-quotes#" . $name . "'/>\n\t\t\t <input>\n\t\t\t\t <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes' encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>\n\t\t\t </input>\n\t\t\t <output>\n\t\t\t\t <soap:body use='encoded' namespace='urn:xmethods-delayed-quotes' encodingStyle='http://schemas.xmlsoap.org/soap/encoding/'/>\n\t\t\t </output>\n\t\t\t </operation>\n\t </binding>\n\t";

	return $binding;
}

function generateWSDLporttype( $name )
{
   $porttype = "<portType name='" . $name . "PortType'>\n\t <operation name='" . $name . "'>\n\t\t <input message='tns:Request" . $name . "'/>\n\t\t <output message='tns:Response" . $name . "'/>\n\t </operation>\n </portType>";
	return $porttype;
}

function generateWSDLservice( $name,  $baseURL = "http://192.168.1.14/rpc", $appName = "test"  )
{
       $service = "<service name='" . $name . "Service'>\n\t <port name='" . $name . "Port' binding='tns:" . $name . "Binding'>\n\t\t <soap:address location='" . $baseURL . "/" . $appName . "/rpc/" . $name . ".php'/>\n\t </port>\n </service>";
	return $service;
}

function generateWSDLcolumn ( $name, $baseURL, $appName, $inputArray, $outputArray )
{
	$message = generateWSDLmessage( $name, $inputArray, $outputArray );
	$binding = generateWSDLbinding( $name );
	$porttype = generateWSDLporttype( $name );
	$service = generateWSDLservice( $name, $baseURL, $appName );
	return $message . "\n" . $binding . "\n" . $porttype . "\n" . $service . "\n";
}


?>
