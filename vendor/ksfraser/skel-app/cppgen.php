<?php

	require_once('data/db.php');
	define ("SUCCESS", 1);
	define ("TRUE", 1);
	define ("FALSE", 0);

/* Homebuilt code generator.  Will go into MySQL db to get the description of the tables, will build classes to handle CRUD for those tables, will include SQL scripts to create tables, etc.

GUI generation could also use XML descriptions.
*/


/*
Want to build classes in file named TABLENAME.cpp

define TRUE;
define FALSE;
define SUCCESS;

class TABLENAME {
	var $fieldspec = array('firstcol', 'secondcol', ...);
	var $firstcol = array( value, dbtype, htmltype, ...); //Comment on datatype, size, etc.
	var $secondcol;
	...
	function __contsructor()
	{
		return;
	}
	function Setfirstcol($value)
	{
		$this->firstcol = $value;
		return SUCCESS;
	}
	function Getfirstcol()
	{
		return $this->firstcol;
	}
	function Validatefirstcol()
	{
		if (iscoltype($value))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	...
}

*/


class my_generator
{
	var $tablename;
	var $query;
	var $result;
	var $db; //connection, not name
	var $classdir;
	var $datadir;
	var $viewdir;
	var $controllerdir;
	var $controllername;

function __construct($table)
{
	$this->Settablename($table);
	$this->includefilename = $this->tablename . ".class.h"; 
	$this->classfilename = $this->tablename . ".class.cpp"; 
	echo "Table Name: $table";
	$this->classdir = "model";
	$this->datadir = "data";
	$this->viewdir = "view";
	$this->controllerdir = "../controller";
	$this->controllername = "controller.php";
	return SUCCESS;
}
function my_generator($table)
{
	return $this->__construct($table);
}

function sql_GetObjectQuery()
{
	return "select * from $this->tablename";
}

function fp_OpenClassFile()
{
	$filename = $this->classdir . "/" . $this->classfilename;
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open include File $filename";
		echo "Check that the dir exists";
		exit(0);
	}
	else
	{
		return $fp;
	}
}

function fp_OpenClassHeaderFile()
{
/*No longer want to write the include file as it is up to dev's
 * to use the file to extend the base classes
 */
	return NULL;
	$filename = $this->classdir . "/" . $this->includefilename;
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open header File $this->includefilename";
		echo "Check that the dir exists";
		exit(0);
	}
	else
	{
		return $fp;
	}
}

function fp_OpenXSLTFile()
{
	$filename = $this->viewdir . "/" . $this->tablename . ".xslt"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open xslt File $filename";
		echo "Check that the dir exists";
		exit(0);
	}
	else
	{
		return $fp;
	}
}

function b_CreateHeader()
{
/*Don't create include file*/
	return SUCCESS;
	$headerstring = "<?php \n";
	//$headerstring .= '#include"db.php" \n'; 
	//$headerstring .= '#include"page.php" \n'; 
//	$headerstring .= '#include"datatypes.h" \n' 
	$headerstring .= "?>";
	$fp = $this->fp_OpenClassHeaderFile();
	fwrite( $fp, $headerstring );
	fclose ($fp);
	return SUCCESS;
}

function b_CreateClassFile($queryresults, $colres, $colres2, $app)
{
	$funcstring = "";
	$colstring = "";
	$varstring = "";
	$funcstring2 = "";
	$ExtraSQL = "";
	
	$filestring = "//";
	$filestring .= "//This file was generated by calling php " . __FILE__  . " $app \n\n";

	$filestring .= "//this is a class file.  It is of no value by itself.\n";
	//Need to doublecheck cpp syntax for inheritance
	$filestring .= "class $this->tablename extends generictable \n{\n";
	$filestring .= "         private \$observers;\n";
	//Build Variables List
	$fieldstring = "         \t\$this->fieldlist = array('";
	$colstring .= "         \t\$this->querytablename = '" . $this->tablename . "';\n";
	$colstring .= "         \t\$this->classname = '" . $this->tablename . "';\n";
	$searchablestring = "         \t\$this->searchlist = array(";
	$varstring .=   "         var \$data; //data passed in by other calls\n";
	while ($res = $this->GetRow($colres)) //implicit !NULL
	{	
		$fieldstring .= $res['column_name'];
		if ($res['issearchable'] == 1)
		{
			$searchablestring .= "'" . $res['column_name'] . "', ";
		}
		$fieldstring .= "', '";
		$totalcount = count ($res);
		$arrcount = count( $res[0] );
		$colcount = $totalcount / $arrcount;
		$keys = $arrcount / 2;
		for ($j = 0; $j < $colcount; $j++)
		{
		  for ($i = 0; $i < $keys; $i++)
		  {
			  //Errors out about unsetting string offsets
			//unset( $res[$j]['$i'] );
		  }
	  	}
		foreach ($res as $key => $value)
		{
			if (FALSE == is_numeric($key))
			{
				$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['$key'] = '$value';\n";
			}
		}
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['postinsert'] = 'Post" . $res['column_name'] . "Insert';\n";
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['postupdate'] = 'Post" . $res['column_name'] . "Update';\n";
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['postdelete'] = 'Post" . $res['column_name'] . "Delete';\n";
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['preinsert'] = 'Pre" . $res['column_name'] . "Insert';\n";
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['preupdate'] = 'Pre" . $res['column_name'] . "Update';\n";
		$colstring .= "         \t\$this->fieldspec['" . $res['column_name'] . "']['predelete'] = 'Pre" . $res['column_name'] . "Delete';\n";
//If we decide to include the default value in the class
		//$varstring .=   "         var $" . $res['column_name'] . " = " . $res['default_value'] . ";\n\t\t /*" . $res['pretty_name'] . " */\n";
		$varstring .=   "         var $" . $res['column_name'] . ";\n\t\t /*" . $res['pretty_name'] . " */\n";
		$dbactions = array( "Insert", "Update", "Delete" );
		foreach ($dbactions as $value)
		{
			$funcstring2 .= "         function Pre" . $res['column_name'] . $value . "( \$data )\n";
			$funcstring2 .= "         {\n";
			$funcstring2 .= "         \$this->data = \$data;\n";
			$funcstring2 .= "                 if ( is_callable( Pre" . $res['column_name'] . $value . " ) )\n";
			$funcstring2 .= "	            return Pre" . $res['column_name'] . $value . "( \$this );\n";
			$funcstring2 .= "                 else return;\n";
			$funcstring2 .= "         }\n";
			$funcstring2 .= "         function Post" . $res['column_name'] . $value . "( \$data, \$lastinsert = 0 )\n";
			$funcstring2 .= "         {\n";
			$funcstring2 .= "         \$this->data = \$data;\n";
			$funcstring2 .= "                 if ( is_callable( Post" . $res['column_name'] . $value . " ) )\n";
			$funcstring2 .= "	            return Post" . $res['column_name'] . $value . "( \$this );\n";
			$funcstring2 .= "                 else return;\n";
			$funcstring2 .= "         }\n";
		}
//Don't need the set/get in PHP the way I am handling elsewhere
		$funcstring2 .= "         function Set" . $res['column_name'] . "(\$value)\n";
		$funcstring2 .= "         {\n";
		$funcstring2 .= "                 \$this->" . $res['column_name'] . " = \$value;\n";
		$funcstring2 .= "	          return SUCCESS;\n";
		$funcstring2 .= "         }\n";
		$funcstring2 .= "         function Get" . $res['column_name'] ."()\n";
		$funcstring2 .= "         {\n  ";
		$funcstring2 .= "                  return \$this->" . $res['column_name'] . ";\n";
		$funcstring2 .= "         }\n";
		/*
		$funcstring2 .= "         function Validate" . $res['column_name'] . "()\n";
		$funcstring2 .= "         {\n";
		$funcstring2 .= "                  if (" . iscoltype . "(\$value))\n";
		$funcstring2 .= "                  {\n";
		$funcstring2 .= "                           return TRUE;\n";
		$funcstring2 .= "                  }\n";	
		$funcstring2 .= "                  else\n";	
		$funcstring2 .= "                  {\n";
		$funcstring2 .= "                           return FALSE;\n";
		$funcstring2 .= "                  }\n";
		$funcstring2 .= "         }\n";
*/
	}
	$fieldstring = rtrim( $fieldstring, "', "); //remove the last comma as it is extra due to the above while loop.
	$searchablestring = rtrim( $searchablestring, ",");
	$fieldstring .= "');\n";
	$searchablestring .= ");\n";
	$funcstring .= "         function __construct()\n";
		$funcstring .= "         {\n";
		$funcstring .= $colstring;
		$funcstring .= $fieldstring;
		$funcstring .= $searchablestring;
		$funcstring .= $ExtraSQL;
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";
		$funcstring .= "         function $this->tablename()\n";
		$funcstring .= "         { //For older php which doesn't have constructor\n";
		$funcstring .= "              return \$this->__construct();\n";
		$funcstring .= "         }\n";
		$funcstring .= "         function Push()\n";
		$funcstring .= "         {\n";	
		$funcstring .= "	         \$_SESSION['$this->tablename'] = serialize(\$this);\n";
		$funcstring .= "	         return SUCCESS;\n";	
		$funcstring .= "         }\n";
		$funcstring .= "         function Pop()\n";
		$funcstring .= "         {\n";	
		$funcstring .= "                 //Can't do this in self - this is how to do it outside\n";
		$funcstring .= "	       //  \$this = unserialize(\$_SESSION['$this->tablename']);\n";
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";

		//OBSERVER PATTERN
		$funcstring .= "         function ObserverRegister( \$observer, \$event )\n";
		$funcstring .= "         {\n";	
		$funcstring .= "                 \$this->observers[\$event][] = \$observer;\n";
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";
		$funcstring .= "         function ObserverDeRegister( \$observer )\n";
		$funcstring .= "         {\n";	
		$funcstring .= "                 \$this->observers[] = array_diff( \$this->observers, array( \$observer) );\n";
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";
		$funcstring .= "         function ObserverNotify( \$event, \$msg )\n";
		$funcstring .= "         {\n";	
		$funcstring .= "                 if ( isset( \$this->observers[\$event] ) )\n";	
		$funcstring .= "                 \tforeach ( \$this->observers[\$event] as \$obs ) \n";
		$funcstring .= "                 \t{\n";
		$funcstring .= "                      \t\t\$obs->notify( \$event, \$msg );\n";
		$funcstring .= "                 \t}\n"; 
		$funcstring .= "                 /* '**' being used as 'ALL' */\n"; 
		$funcstring .= "                 if ( isset( \$this->observers['**'] ) )\n";	
		$funcstring .= "                 \tforeach ( \$this->observers['**'] as \$obs ) \n";
		$funcstring .= "                 \t{\n";
		$funcstring .= "                      \t\t\$obs->notify( \$event, \$msg );\n";
		$funcstring .= "                 \t}\n"; 
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";
		$funcstring .= "         function notify( \$object )\n";
		$funcstring .= "         {\n";	
		$funcstring .= "                 //Called when another object we are observing sends us a notification\n";
		$funcstring .= "	         return SUCCESS;\n";
		$funcstring .= "         }\n";
		
	//Set attributes of the columns into arrays
	$output = $filestring . $includestring . $varstring . $funcstring . $funcstring2;
	$output .= "} /* class $this->tablename */\n";
	$fp = $this->fp_OpenClassFile();
	fwrite( $fp, $output );
	fclose ($fp);

	return SUCCESS;
}

function b_CreateXSLTFile($queryresults, $colres, $colres2)
{	
	
	$topstring = ' <xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0" xmlns="HTTP://WWW.W3.ORG/1999/XHTML">';

	$matchstring = '<xsl:template match="/"> <xsl:apply-templates select="ROOT/*" /> </xsl:template>';

	$setstring = '<xsl:template match="RECORDSET"> <xsl:apply-templates/> </xsl:template>';
	$recordstring = '<xsl:template match="RECORD"> <xsl:apply-templates/> </xsl:template>';
	$rowstring = "";
	while ($res = $this->GetRow($colres)) //implicit !NULL
	{	
		$rowstring .= '<xsl:template match="' . $res['column_name'] . '"> <xsl:apply-templates/> </xsl:template>';
	}
	$endstring = '</xsl:stylesheet>';
	
	$output = $topstring . $matchstring . $setstring . $recordstring . $rowstring . $endstring;
	$fp = $this->fp_OpenXSLTFile();
	fwrite( $fp, $output );
	fclose ($fp);

	return SUCCESS;
}

function BuildRegister( $app )
{
        $this->query = "select table_name, column_name from codemeta.metadata_elements where foreign_table = '$this->tablename'";
        $this->db->SetQuery( $this->query );
        $this->db->Query();
        $out = "<?php\n";
        $out .= "function " . $this->tablename . "RegisterOthers()\n";
        $out .= "{\n";

        while( $row = mysql_fetch_array( $this->db->result ) )
        {
                echo "\nRow returned:\n";
                var_dump( $row );
                echo "\n";
                //create $classname.register.php
                $out .= "\tinclude_once( '" . $row['table_name'] . ".class.php' );\n";
                $out .= "\t\$observer = new " . $row['table_name'] . "();\n";
                $out .= "\t\$table->ObserverRegister( \$observer, 'CREATE' );\n";
                $out .= "\t\$table->ObserverRegister( \$observer, 'REPLACE' );\n";
                $out .= "\t\$table->ObserverRegister( \$observer, 'UPDATE' );\n";
                $out .= "\t\$table->ObserverRegister( \$observer, 'DELETE' );\n";
        }
        $out .= "}\n";
        $out .= "?>";
        $filename = $this->classdir . "/" .  $this->tablename . ".register.php";
        echo "\nOutfile = $filename\n";
        $fp = fopen( $filename, "w" );
        if ($fp == NULL)
        {
                //error - log it
                echo "Couldn't open register File $filename";
                echo "Check that the dir exists";
                exit(0);
        }
        echo "Register text: $out\n";
        fwrite( $fp, $out );
        fclose( $fp );
        return SUCCESS;
}


function Settablename($name)
{
	$this->tablename = $name;
	return SUCCESS;
}
function BuildQuery( $app )
{
	//$this->query = "select * from codemeta.metadata_elements where table_name = '$this->tablename'";
	//$this->query = "select * from codemeta.metadata_elements where table_name = '$this->tablename' order by form_sort_key";
	$this->query = "select * from codemeta.metadata_elements where table_name = '$this->tablename' and application='$app' order by form_sort_key";
	return SUCCESS;
}
function Query()
{
	$this->db = new my_db("localhost", "codemeta", "codemeta", "codemeta");
	$this->db->SetQuery($this->query);
	$this->db->Query();
	$this->result = $this->db->result;
	return SUCCESS;
}
function GetRow($res)
{
	$this->db->result = $res;
	return $this->db->FetchRow();
}

function CreateObjects($app)
{
	$this->BuildQuery( $app );
	$this->Query();
	$res = $this->result;
	$this->b_CreateClassFile($res, $res, $res, $app);
	//$this->BuildRegister( $app );
	//$this->b_CreateHeader();
	return SUCCESS;
}


function CreateXSLT( $app )
{
	$this->BuildQuery( $app );
	$this->Query();
	$res = $this->result;
	$this->b_CreateXSLTFile($res, $res, $res);
	return SUCCESS;
}


function b_CreateSQLFile($result)
{
	$prikeyar = array();
	$statement = "";
	//$statement .="---\n\r"; 
	//$statement .="--- Table struct for table $this->tablename\n\r"; 
	//$statement .="---\n\r"; 
	$statement .= "DROP TABLE IF EXISTS `$this->tablename`;\n\r";
	$statement .= "CREATE TABLE `$this->tablename` (\n\r";
	while ($row = $this->GetRow($result))
	{
		//Init values
		$column = $type = $size = $unsigned = $zerofill = $NULL = $autoinc = $default = $comment = $PRIKEY = "";
		$column = $row['column_name'];
		$type = $row['db_data_type'];
		$size = $row['c_size'];
		if ($row['c_unsigned'] == 'Y')
		{
			$unsigned = "unsigned";
		}
		if ($row['c_zerofill'] == 'Y')
		{
			$zerofill = "zerofill";
		}
		if (strncmp($row['field_null'], "Y", 1) == 0)
		{
			$NULL = "NULL";
		}
		else
		{
			$NULL = "NOT NULL";
		}	
		if ($row['c_auto_increment'] == 'Y')
		{
			$autoinc = "auto_increment";
		}
		if ($row['prikey'] == 'Y')
		{
			$prikeyar[] = $row['column_name'];
			var_dump($prikeyar);
		}
		$defval = $row['default_value'];
		$default = "default '$defval'";
		$commentval = $row['pretty_name'];
		$comment = "comment '$commentval'";
		//If Comment -> COMMENT ''
		//If default -> default ''
		//PRIKEY = (`key1`, `key2`)
		$statement .= "`$column` $type($size) $unsigned $zerofill $NULL $autoinc $default $comment,\n\r";
	}
	$statement = rtrim( rtrim( $statement ), ",");
	if (count($prikeyar) < 2)
	{
		$PRIKEY = "`" . $prikeyar[0] . "`";
	}
	else
	{
		$PRIKEY = "`" . $prikeyar[0] . "`";
		foreach ($prikeyar as $key=> $value)
		{
			$PRIKEY .= ", '" . $value . "'";
		}
	}
	$statement .= ", PRIMARY KEY ($PRIKEY)";
	$statement .= ") ENGINE=InnoDB;\n";
	$fp = fopen( "sql/" . $this->tablename . ".sql", "w");
	if ($fp == NULL)
	{
		echo "couldn't open $this->tablename .sql.  Check dir exists";
		exit(0);
	}
	fputs( $fp, $statement );
	fclose( $fp );
}


function CreateTableSQL( $app )
{
	$this->BuildQuery( $app );
	$this->Query();
	$res = $this->result;
	$this->b_CreateSQLFile($res, $res, $res);
	$fp = fopen( 'tasks.default.sql', 'a' );
	$description = "$this->tablename";
	$filename = "";
	$tasktype = 'MENU';
	$parent = 'NULL';
	$insertstatementmenu = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.replace.php';
	$filename = $this->classdir . '/' . $this->tablename . '.insert.php';
	$description = "Insert into $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementinsert = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.replace.php';
	$description = "Replace into $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementreplace = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.update.php';
	$description = "Update $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementupdate = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.delete.php';
	$description = "Delete from $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementdelete = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.search.php';
	$description = "Search $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementsearch = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	$filename = $this->classdir . '/' . $this->tablename . '.list.php';
	$description = "Show $this->tablename";
	$tasktype = 'TASK';
	$parent = "$this->tablename";
	$insertstatementlist = "INSERT into tasks( tasktype, taskdescription, tasklink, taskparent) values ('$tasktype', '$description', '$filename', '$parent');\n";
	fwrite( $fp, $insertstatementmenu );
	fwrite( $fp, $insertstatementinsert );
	fwrite( $fp, $insertstatementreplace );
	fwrite( $fp, $insertstatementupdate );
	fwrite( $fp, $insertstatementdelete );
	fwrite( $fp, $insertstatementlist );
	fwrite( $fp, $insertstatementsearch );
	fwrite( $fp, "insert into roletask (idtasks) SELECT idtasks FROM tasks;" );
	fwrite( $fp, "update roletask set roles_id = 1;" );

	fclose( $fp );
	return SUCCESS;
}


function CreatePatterns()
{
	return;
	$prefix = $this->classdir . "/" . $this->tablename;
	//Create the files for list, update, delete, insert
	$filename =  $prefix . ".insert.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename' ;\n";
	$output .= '$mode = "insert";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

	$filename = $prefix . ".replace.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename' ;\n";
	$output .= '$mode = "replace";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	//$output .= "require_once( '" . $this->controllerdir . "/controller.php');\n";
	//$output .= "require_once( 'controller/controller.php');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

	$filename = $prefix . ".update.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename' ;\n";
	$output .= '$mode = "update";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	//$output .= "require_once( 'controller/controller.php');\n";
	//$output .= "require_once( '" . $this->controllerdir . "/controller.php');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

	$filename = $prefix . ".delete.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename' ;\n";
	$output .= '$mode = "delete";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	//$output .= "require_once( '" . $this->controllerdir . "/controller.php');\n";
	//$output .= "require_once( 'controller/controller.php');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

	$filename = $prefix . ".list.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename' ;\n";
	$output .= '$mode = "list";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	//$output .= "require_once( '" . $this->controllerdir . "/controller.php');\n";
	//$output .= "require_once('controller/controller.php');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

	$filename = $prefix . ".search.php"; 
	$fp = fopen( $filename, "w" );
	if ($fp == NULL)
	{
		//error - log it
		echo "Couldn't open $filename .  CHeck the dir exists";
		exit(0);
	}
	$output = "<?php\n";
	//$output .= "require_once('" . $this->classfilename . "');\n";
	//$output .= "\$table = new " . $this->tablename . "();\n";
	$output .= "\$thisclass = '$this->tablename';\n";
	$output .= '$mode = "search";' . "\n";
	$output .= "require_once( '" . $this->controllerdir . "/" . $this->controllername . "');\n";
	//$output .= "require_once( '" . $this->controllerdir . "/controller.php');\n";
	//$output .= "require_once('controller/controller.php');\n";
	$output .= "?>\n";
	fwrite( $fp, $output );
	fclose ($fp);

}

} /*class my_generator*/

$includefilename = ""; 
$classfilename = ""; 

$mydb = new my_db("localhost", "codemeta", "codemeta", "codemeta");
if (isset($argv[1]))
{
	$mydb->SetQuery("select distinct(table_name) from codemeta.metadata_elements where application='$argv[1]'");
}
else
{
	$mydb->SetQuery("select distinct(table_name) from codemeta.metadata_elements");
}
$result = $mydb->Query();
while ($res = $mydb->FetchRow())
{
	foreach ($res as $key => $value)
	{
		if (FALSE == is_numeric( $key ))
		{
			$gen = new my_generator($value);
			$gen->CreateObjects($argv[1]);
		//	$gen->CreateXSLT( $argv[1] );
		//	$gen->CreatePatterns();
		//	$gen->CreateTableSQL( $argv[1] );
		}

	}
}
exit(0);

?>
