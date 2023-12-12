<?php

/* ************************************************************************
*
*	This file is to be used to morph sql create statements into the
*	metadata for the metadata tables to autogen classes.
*
*	first, mysqldump the db
*	Second, run the dump through awk to get rid of anything but create table statements
*	Next read in this data to this script
*	This script will then write into the metadata tables
*	Run the generator
*	Extend the classes as required
*/

/*	mysqldump DATABASENAME | awk '/^CREATE TABLE/, /;$/' > create.tables.sql
*	php create2metadata.php
*	Then import the resulting sql-out.sql.
*/

function BuildQuery($tablename, $columns)
{
	//$columns is an array
	//$columns[$colname]['attribute'] = $value;
	//Want to build a series of insert statements
	$insertquery = "insert into codemeta.metadata_elements";
	$elementkey = "table_name, ";
	$elementvalue = "'$tablename', ";
	$query = "";
	foreach ($columns as $colname => $colval)
	{
		////echo "**************$tablename**********$colname***********\n";
		foreach( $colval as $key => $value )
		{
			////echo "Key ::$key:: Value ::$value::\n";
			$elementkey .= "$key, ";
			$elementvalue .= "'$value', ";
		}
		$elementkey = rtrim( $elementkey, ", ");
		$elementvalue = rtrim( $elementvalue, ", ");
		$query .= "$insertquery ($elementkey) values ($elementvalue);";
	}
	//echo "Query: ::$delquery\n$delquery2\n$insertquery2\n$query::\n";
	return "$query\n";
}


$filename = "create.tables.sql";
$paren = array( "(", ")" );
$parenreplace = array( " ", " " );
$optparam = array ("unsigned", "zerofill", "NOT NULL", "auto_increment");
$optparamreplace = array ( " ", " ", " ", " " );
$quotes = array( "`", "'" );
$quotereplace = array ( "", "" );
		
$columns = array();
$fp = fopen( $filename, 'r' );
$done = 0;
while ($line = fgets($fp))
{
	//echo "Line::$line\n";
	//Parse the line
	if (0 == strncasecmp( $line, ")", 1))
	{
		//echo "End of table create statement\n";
		//break
		$done = 1;
	} 
	else if (0 == strncasecmp( $line, "CREATE", 6))
	{
		//echo "*************\n";
		//echo "CREATE LINE $line\n";
		$UPPER = strtoupper($line);
		$STRIPPED = str_replace( "CREATE TABLE", " ", $UPPER);
		$TRIMMED = ltrim( $STRIPPED );
		$TRIMMED2 = str_replace( $paren, $parenreplace, $TRIMMED );
		$TRIMMED2 =ltrim( rtrim( str_replace( $quotes, $quotereplace, $TRIMMED2 ) ) );
		$databasename = strtolower( $TRIMMED2 );
		echo "Upper: $UPPER\nStripped: $STRIPPED\nTrimmed: $TRIMMED\nTrimmed2: ::$TRIMMED2::\n DB: ::$databasename::\n";
		//echo "Table Named::$databasename::\n";
		$delquery = "delete from codemeta.metadata_object_types where table_name='$databasename';\n";
		$delquery2 = "delete from codemeta.metadata_elements where table_name='$databasename';\n";
		$insertquery2 = "insert into codemeta.metadata_object_types values ('$databasename', '$databasename', '$databasename');\n";
		echo "Delquery: ::$delquery::\n";
		$fp2 = fopen( "sql-out.sql", "a" );
		fputs( $fp2, $delquery );
		fputs( $fp2, $delquery2 );
		fputs( $fp2, $insertquery2 );
		fclose( $fp2 );

	} 
	else if (0 == strncasecmp( trim($line), "KEY", 6))
	{
		//Don't want this
	}
	else if (0 == strncasecmp( trim($line), "PRIMARY", 6))
	{
		//echo "Primary Key::$line::\n";
		$STRIPPED = str_replace( "PRIMARY KEY", " ", $line );
		$STRIPPED2 = str_replace( $paren, $parenreplace, $STRIPPED );
		$STRIPPED2 = str_replace( $quotes, $quotereplace, $STRIPPED2 );
		$TRIMMED = trim( $STRIPPED2 );
		//echo "Stripped: $STRIPPED\nStripped2: $STRIPPED2\nTrimmed: $TRIMMED\n";
		//Primary Key is listed on this line
		$prikeys = $TRIMMED;
		//This will fail for multiple pri key instances.
		echo "Primary Key pretok::$TRIMMED::\n";
		$query = "";
		if (stristr( $TRIMMED, "," ))
		{
		  	$token = strtok( $TRIMMED, "," );
		  	echo "Primary Key tok::$token::\n";
			$query .= "update codemeta.metadata_elements set prikey='Y' where column_name = '$token' and table_name = '$databasename';\n";
		  	while ( $token != false )
		  	{
				$token = strtok( "," );
				$query .= "update codemeta.metadata_elements set prikey='Y' where column_name = '$token' and table_name = '$databasename';\n";
		  		echo "Primary Key while::$token::\n";
		  	} 
	  	}
		else
		{
			$query .= "update codemeta.metadata_elements set prikey='Y' where column_name = '$TRIMMED' and table_name = '$databasename';\n";
		}
		//update the columns
		$fp2 = fopen( "sql-out.sql", "a" );
		fputs( $fp2, $query );
		fclose( $fp2 );	
	}
	else 
	{
		//echo "Column Line\n";
		//Line is a column line
		$colname = str_replace( $quotes, $quotereplace, strtok( $line, " ") );
		$columns[$colname]['column_name'] = $colname;
		$columns[$colname]['extra_sql'] = " ";
		//echo "Column Name ::$colname::\n";
		//Remove column name from line
		$line = str_replace( $colname, " ", $line);
		if (stristr( $line, "NOT NULL" ) != FALSE)
		{
			//Attribute was found
			//echo "Not Null\n";
			$columns[$colname]['field_null'] = "YES";
		}
		else
		{
			//Attribute wasn't found
			$columns[$colname]['field_null'] = "NO";
		}
		if (stristr( $line, "zerofill" ) != FALSE)
		{
			//Attribute was found
			//echo "Zero Fill\n";
			$columns[$colname]['c_zerofill'] = "Y";
		}
		
		if (stristr( $line, "unsigned" ) != FALSE)
		{
			//Attribute was found
			//echo "Unsigned\n";
			$columns[$colname]['c_unsigned'] = "Y";
		}
		if (stristr( $line, "auto_increment" ) != FALSE)
		{
			//Attribute was found
			//echo "AutoInc\n";
			$columns[$colname]['c_auto_increment'] = "Y";
		}

		//Next token is the type
		//strtok resets the string on subsequent calls if srcstring named
		$typetoken = strtok( " " );
		$typetoken2 = str_replace( $paren, $parenreplace, $typetoken );
		$type = strtok( $typetoken2, " ");
		$size = strtok( " ");
		//echo "TT ::$typetoken:: TT2 ::$typetoken2:: Type: ::$type:: Size ::$size::\n";
		$columns[$colname]['db_data_type'] = $type;
		$columns[$colname]['abstract_data_type'] = $type;
		$columns[$colname]['html_form_type'] = $type;
		$columns[$colname]['c_size'] = $size;
		
		//Next set of tokens are optional, and covered above
		
		$line = str_replace( $optparam, $optparamreplace, $line);
		//Already have the type/size
		$line = str_replace( $typetoken, " ", $line );
		//By this point, should be mostly space, and "default"
		$line = ltrim( $line, " " );
		//echo "Looking for Default: ::$line::\n";
		if (0 == strncasecmp($line, "default", 6))
		{
			$line = str_replace( "default", "", $line );
			$line = str_replace( "\n", "", $line );
			//echo "Default: ::$line::\n";
			$line = str_replace( "'", "", $line );
			//echo "Default: ::$line::\n";
			$line = str_replace( ",", "", $line );
			//echo "Default: ::$line::\n";
			$columns[$colname]['default_value'] = $line;
		}
	} //else strncasecmp )
	if ($columns != NULL) 
	{
		$query = BuildQuery($databasename, $columns);
		$columns = NULL;
		$fp2 = fopen( "sql-out.sql", "a" );
		fputs( $fp2, $query );
		fclose( $fp2 );
	}
} //While
fclose ($fp);


?>
			
			
				
		
