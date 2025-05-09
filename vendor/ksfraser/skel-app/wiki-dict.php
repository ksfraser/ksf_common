<?php

//20091011 KF
//Functions to build dictionary files out of the codemeta descriptions
//Loading into the Wiki once this proc is run with something similar to:
//	cd wiki/maintenance
// 	for x in ../dict/*txt; 
//	do  
//		php importTextFile.php --user Kevin2 $x
//	done

	require_once('data/db.php');
        define ("SUCCESS", 1);
        define ("TRUE", 1);
        define ("FALSE", 0);

class dict
{
        var $tablename;
        var $query;
        var $result;
        var $db; //connection, not name
        var $dictdir;
	var $application;

	function __construct($application)
	{
	        $this->application = $application;
	        $this->dictdir = "wiki/dict";
	
		$this->sql_GetObjectQuery();
		$this->Query();
	
		$this->writefiles();
	        return SUCCESS;
	}
	function my_generator($application)
	{
	        return $this->__construct($application);
	}
	
	function sql_GetObjectQuery()
	{
		$this->query = "select * from codemeta.metadata_elements 
				where application='$this->application'";
	}
	function Query()
	{
	        $this->db = new my_db("localhost", "codemeta", "codemeta", "codemeta");
	        $this->db->SetQuery($this->query);
	        $this->db->Query();
	        $this->result = $this->db->result;
	        return SUCCESS;
	}
	function writefiles()
	{
		while( $row = $this->db->FetchRow() )
		{
			$col = $row['column_name'];
			$table = $row['table_name'];
			$filename = $this->dictdir . "/" . $this->application . "_" . $table . "_" . $col . ".txt";
			$fp = fopen( $filename, "w" );
			fwrite( $fp, "==Dictionary Entry==\n" );
			fwrite( $fp, "Application: " . $this->application . "\n\n" );
			fwrite( $fp, "Table Name: " . $table . "\n\n" );
			fwrite( $fp, "Name: " . $col . "\n\n" );
			fwrite( $fp, "Pretty Name: " . $row['pretty_name'] . "\n\n" );
			fwrite( $fp, "Type of Data: " . $row['abstract_data_type'] . "\n\n" );
			fwrite( $fp, "Stored in the database as: " . $row['db_data_type'] . "\n\n" );
			fwrite( $fp, "Explanation: " . $row['html_form_explanation'] . "\n\n" );
			fwrite( $fp, "Extra details: " . $row['help_text'] . "\n\n" );
			fwrite( $fp, "Default Value: " . $row['default_value'] . "\n\n" );
			fwrite( $fp, "Version of software: " . $row['definition_version'] . "\n\n" );
			fwrite( $fp, "This page was automatically generated by " . __FILE__ . " on " . date( 'Y-m-d' ) . "\n\n" );
			fwrite( $fp, "[[Category: Dictionary]]" );
			fwrite( $fp, "[[Category: " . $this->application . "]]" );
			fwrite( $fp, "[[Category: " . $table . "]]" );
			fwrite( $fp, "[[Category: " . $col . "]]" );
			fwrite( $fp, "\n" );
			fclose( $fp );
			
		}
	}
}


if (isset($argv[1]))
{
	$dict = new dict( $argv[1] );
}
else
{
	echo "Usage: $argv[0] application \n";
}

exit(0);




?>
