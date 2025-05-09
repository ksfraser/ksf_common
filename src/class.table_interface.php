<?php

require_once( 'defines.inc.php' );
require_once( 'class.kfLog.php' );
require_once 'class.BaseDAO.php';

/**
 * Class table_interface
 * Provides basic SQL functions for database tables.
 */
class table_interface
{
	var $db_insert_id;	//!< int set by $this->insert_table();
	var $table_details;	//!< array definition details about the table
	var $properties_array;	//!< array
	var $fields_array;	//!< array
	var $caller;
	/***GENERICTABLE***/
	var $select_array;
	var $from_array;
	var $where_array;
	var $groupby_array;
	var $having_array;
	var $orderby_array;
	var $sort_dir;			//!< ASC or DESC
	var $limit;
	var $limit_startrow;		//!< int to build a LIMIT statement - which row to start at (multi-page)
	var $limit_numberrows;		//!< int to build a LIMIT statement - how many rows to return
	var $number_rows_affected;
	var $querytime;
	var $join_array;
	protected $select_clause;	//!< string SQL sub-string (clause)
	protected $from_clause;		//!< string SQL sub-string (clause)
	protected $where_clause;	//!< string SQL sub-string (clause)
					//	The WHERE array can be COLUMN => VALUE/Column (that it is equal to )
					//	--OR-- VALUE => array( OPERAND (ie <> Like) => VALUE/Column
	protected $groupby_clause;	//!< string SQL sub-string (clause)
	protected $having_clause;	//!< string SQL sub-string (clause)
	protected $orderby_clause;	//!< string SQL sub-string (clause)
	protected $limit_clause;	//!< string SQL sub-string (clause)
	protected $join_clause;
	protected $query_result;
	/***!GENERICTABLE***/
	protected $objLog;

	function __construct( $caller = null )
	{
		$this->db_insert_id = null;
		if( !isset( $this->table_details ) )
			$this->table_details = array();
		if( !isset( $this->properties_array ) )
			$this->properties_array = array();
		if( null !== $caller )
			$this->caller = $caller;
		$this->objLog = new kfLog();
		if( !isset( $this->fields_array ) )
                       $this->fields_array = array();


	}
        /**********************************************************//**
         * Log 
	 *
	 *	Inheriting classes can override to provide more complete
	 *	logging.  A TABLE orientated class shouldn't be sending
	 * 	to a screen; should be passing messages back through
	 *	returns or Exceptions.
         * ***********************************************************/
        function notify( $msg, $level = "ERROR" )
        {
		$this->Log( $msg, $level );
        }

	function Log( $msg, $level = PEAR_LOG_DEBUG )
	{
		if( isset( $this->objLog ) )
			$this->objLog->Log( $msg, $level );
	}
	/********************************************************//**
	 * Copied from origin.  Throws exceptions
	 *
	 * **********************************************************/
	function get( $field )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( isset( $this->$field ) )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return $this->$field;
		}
		else
			throw new Exception( __METHOD__ . "  Field " . $field . " not set.  Can't GET", KSF_FIELD_NOT_SET );
	}
	/*********************************************//**
	 * Set a variable.  Throws exceptions on sanity checks
	 *
	 * 
	 * @param field string Variable to be set
	 * @param value ... value for variable to be set
	 * @return bool Did we set the value
	 * **********************************************/
	/*@bool@*/function set( $field, $value = null )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//display_notification( __FILE__ . "::" . __CLASS__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );	
		if( !isset( $field )  )
			throw new Exception( "Fields not set", KSF_FIELD_NOT_SET );
		if( ! isset( $this->fields_array ) )
		{
			debug_print_backtrace();
			return FALSE;
		}
		foreach( $this->fields_array as $row )
		{
			if( $field == $row['name'] )
			{
				try
				{
					$this->validate( $value, $row['type'] );
					$this->$field = $value;
					$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
					return TRUE;
				}
				catch(InvalidArgumentException $e)
				{
					display_error( $e->getMessage() );
				}
				catch( Exception $e )
				{
					display_notification( $e->getMessage() );
				}
			}
			
		}
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		throw new Exception( "Variable <i><b>" . $field . "</b></i> to be set is not a member of the class", KSF_FIELD_NOT_CLASS_VAR );
	}
	/*******************************************************************************************//**
	 * Sanity check on passed in data versus table definition (data dictionary)
	 *
	 * @param mixed the field value
	 * @param mixed the definition we used to create the DB table
	 * @returns bool is it of reasonable values...
	 * **********************************************************************************************/
	/*@bool@*/function validate( $data_value, $data_type )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//display_notification( __FILE__ . "::" . __CLASS__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );
		if( strncasecmp( $data_type, "int(1)", 6 ) )
			$data_type = 'digit';
		if( strncasecmp( $data_type, "int", 3 ) )
			$data_type = 'int';
		if( strncasecmp( $data_type, "varc", 4 ) )
			$data_type = 'string';
		switch($data_type)
		{
			case 'bool':
			case 'boolean':
					if( $data_value === true ) return true;
					if( $data_value === false ) return true;
					if( $data_value == 0 ) return true;
					if( $data_value == 1 ) return true;
					throw new InvalidArgumentException("Expected Boolean.  Received " . $data_value);
					break;	//fall out of switch and return false
			case 'string':
			case 'digit':
				if( $data_value >= 0 AND $data_value <= 9 ) return true;
				break;	//fall out of switch and return false
			case 'int':
				if( is_int( $data_value ) ) 
				{
					$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
					return true;
				}
				else 
					throw new InvalidArgumentException("Expected INT.  Received " . $data_value);
				break;
			default:
				$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
				return true;	//data type not found
		}
		//throw new InvalidArgumentException();
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return false;
		/*
			BadFunctionCallException
			BadMethodCallException
			DomainException
			InvalidArgumentException
			LengthException
			LogicException
			OutOfBoundsException
			OutOfRangeException
			OverflowException
			RangeException
			RuntimeException
			UnderflowException
			UnexpectedValueException
		 * 
		 * */
	}
	/***************************************************************************************************************//**
	 * Select a row from the table.  Requires that the prikey is set.
	 *
	 * Doesn't consider foreign keys (recursive)
	 * Throws exceptions
	 * ***************************************************************************************************************/
	/*none*/function select_row( $set_caller = false )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( isset( $this->table_details['primarykey'] ) )
			$key = $this->table_details['primarykey'];
		else
			throw new Exception( "Primary Key not defined.  This function uses that field in the query", KSF_PRIKEY_NOT_DEFINED );
		if( ! isset( $this->$key ) )
		{
			var_dump( $this->table_details );
			throw new Exception( "Primary Key not set.  Required Field for this function", KSF_PRIKEY_NOT_SET );
		}
		$sql = "SELECT * from `" . $this->table_details['tablename'] . "` WHERE $key='" . $this->$key . "'";
		$res = db_query( $sql, "Couldn't select from " . $this->table_details['tablename'] );
		$row = db_fetch( $res );
		foreach( $this->fields_array as $def )
		{
			$name = $def['name'];
			if( isset( $row[$name] ) )
			{
				$this->$name = $row[$name];
				if( $set_caller AND isset( $this->caller ) )
				{
					try
					{
						$this->caller->set( $name, $this->$name );
					} catch( Exception $e )
					{
						//Caller may not have the same name for the variable.
						//Not going to stress over it.  They can query us for
						//the value...
						//HOWEVER if set_caller is true, they are expecting the value.
						throw $e;
					}
				}
			}
		}
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	
	/*******************************************************************************************************************//**
	 * Select from the table with defined values.  NOT setup to handle joins or subselects
	 *
	 * TODO: handle array for field list
	 *
	 * @param string list of fields to select "a,b,c as cost"
	 *			Eventually make it into an array....array( 'fieldname' => 'select as name' )
	 * @param array list of WHERE conditions array( 'field1' => 'equal to value' )
	 * @param array list of fields  array( 'field1', 'field2', ...)
	 * @param int limit
	 * @returns mysql_result
	*******************************************************************************************************************/
	/*@mysql_result@*/function select_table($fieldlist = "*", /*@array@*/$where = null, /*@array@*/$orderby = null, /*@int@*/$limit = null)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		$whereset = FALSE;
		$sql = "SELECT " . $fieldlist . " from `" . $this->table_details['tablename'] . "`";
		if( isset( $where ) )
		{
			foreach( $where as $key => $value )
			{
				//Allow a 1 dimensional array for WHERE
				if( strlen( $value ) < 1 )
				{
					if( isset( $this->$key ) )
					{
						$value = $this->$key;
					}
				}
				if( strlen( $key ) > 1 AND strlen( $value ) > 1 )
				{
					if( in_array( $key, $this->properties_array ) )
					{
						if( $whereset == FALSE )
						{
							$sql .= " WHERE ";
							$whereset = TRUE;
						}
						else
						{
							//each WHERE needs to be split by an AND
							$sql .= " AND ";
						}
						$sql .= $key .= "=" . db_encode( $value );
					}
				}
			}
		}
		if( isset( $orderby ) )
		{
			$orderset = FALSE;
			foreach( $orderby as $key )
			{
				if( strlen( $key ) > 1 )
				{
					if( in_array( $key, $this->properties_array ) )
					{
						if( $orderset == TRUE )
						{
							$sql .= ", ";
							
						}
						else
						{
							//First orderby doesn't need a comma
							$sql .= " ";
							$orderset = TRUE;
						}
						$sql .= $key;
					}
				}
			}
		}
		if( isset( $limit ) )
			$sql .= " LIMIT " . $limit;
		$res = db_query( $sql, "Couldn't select from " . $this->table_details['tablename'] );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $res;
	}
	/**//***************************************************************
	 * Run a query.  Sets query_results
	 *
	 *
	 * @param string Error Message if query fails
	 * @returns mysql_res object (result)
	 *******************************************************************/
	function query( $msg )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		$this->query_result = db_query( $this->sql, $msg );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $this->query_result;
	}

	/***************************************************************************************//**
	 * Delete a row in the table as long as the prikey has a value set
	 *
	 * Will eventually throw exceptions!
	 *
	 * *****************************************************************************************/
	function delete_table()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//display_notification( __FILE__ . "::" . __CLASS__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );
		$pri = $this->table_details['primarykey'];
		if( !isset( $this->$pri ) )
			throw new Exception( "Primary Key not set", PRIMARY_KEY_NOT_SET );
		$sql = "DELETE from " . $this->table_details['tablename'] . " WHERE " . $pri . " = '" . $this->$pri . "'";
		//var_dump( $sql );
		db_query( $sql, "Couldn't delete table " . $this->table_details['tablename'] . " for key " .  $pri );	
		//throw new Exception( $sql );	//Causes FA to display_error the msg.  Useful for debugging.
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	/***************************************************************************************//**
	 * Update a row in the table as long as the prikey has a value set
	 *
	 * Will eventually throw exceptions!
	 *
	 * *****************************************************************************************/
	function update_table()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//display_notification( __FILE__ . "::" . __CLASS__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );
		$pri = $this->table_details['primarykey'];
		if( !isset( $this->$pri ) )
			return;
			//throw new Exception( "Primary Key field is not set.  This function requires that key" );
		$sql = "UPDATE `" . $this->table_details['tablename'] . "` set" . "\n";
		$fieldcount = 0;
		foreach( $this->fields_array as $row )
		{
			if( $row['name'] != $this->table_details['primarykey'] )
			{
				if( isset( $this->$row['name'] ) )
				{
					if( $fieldcount > 0 )
						$sql .= ", ";
					$sql .= "`" . $row['name'] . "`=" . db_escape($this->$row['name']);
					$fieldcount++;
				}
			}
		}
	
		$sql .= " WHERE '" . $pri . "'='" . $this->$pri . "'";
		db_query( $sql, "Couldn't update table " . $this->table_details['tablename'] . " for key " .  $pri );	
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	/*****************************************************************************//**
	 *
	 *
	 * @returns bool did we find the key
	 * ******************************************************************************/
	/*@bool@*/function check_table_for_id()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( isset( $this->table_details['primarykey'] ) )
		{
			$prikey = $this->table_details['primarykey'];
		}
		//check to see if we have the id in a record
		$go = false;
		foreach( $this->fields_array as $row )
		{
			if( isset( $this->$row['name'] ) )
			{
				if( $this->$row['name'] == 'id' )
				{
					$go = true;
					$prikey = $this->$row['name'];
				}
			}
		}
		if( $go )
		{
			$sql = "select count('id') as count from $this->table_details['tablename']";
			$sql .= " WHERE " . $prikey . " = '" . $this->id . "'";
			$res = db_query( $sql, "Couldn't check for count in table " . $this->table_details['tablename'] . " with " .  $sql );	
			$count = db_fetch_assoc( $res );
			if( $count['count'] > 0 )
			{
				$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
				return TRUE;
			}
			else
			{
				$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
				return FALSE;
			}
		}
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return FALSE;
	}
	/*******************************************************************************************************//**
	 *
	 *
	 * This function uses mysql_real_escape_string which is depreciated in 5.5 and removed in php 7.
	 * @return int Index of last insert
	 * *********************************************************************************************************/
	/*int index of last insert*/
	/*@int@*/function insert_table()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//display_notification( __FILE__ . "::" . __CLASS__ . "::"  . __METHOD__ . ":" . __LINE__, "WARN" );
		global $db_connection;
		$sql = "INSERT IGNORE INTO `" . $this->table_details['tablename'] . "`" . "\n";
		$fieldcount = 0;
		$fields = "(";
		$values = "values(";
		foreach( $this->fields_array as $row )
		{
			if( isset( $this->$row['name'] ) )
			{
				if( $fieldcount > 0 )
				{
					$fields .= ", ";
					$values .= ", ";
				}
				$fields .= "`" . $row['name'] . "`";
				$values .=  db_escape($this->$row['name']);
				$fieldcount++;
			}
		}
		$fields .= ")";
		$values .= ")";
		$sql .= $fields . $values;
		//var_dump( $sql );
		if( $fieldcount > 0 )
			db_query( $sql, "Couldn't insert into table " . $this->table_details['tablename'] . " for " .  $sql );	
		else
			display_error( "No values set so couldn't insert" );
		$this->db_insert_id = db_insert_id();
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $this->db_insert_id;
	}
	function create_table()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( ! isset( $this->table_details['tablename'] ) )
		{
			if( method_exists( $this->define_table() ) )
				$this->define_table();
			else
				throw new Exception( "Table Definition not defined so can't create table", KSF_TABLE_NOT_DEFINED );
		}
		$sql = "CREATE TABLE IF NOT EXISTS `" . $this->table_details['tablename'] . "` (" . "\n";
		$fieldcount = 0;
		foreach( $this->fields_array as $row )
		{
			if( $fieldcount > 0 )
			{
				$sql .= ",";
			}
			$sql .= "`" . $row['name'] . "` " . $row['type'];
			if( isset( $row['null'] ) )
				$sql .= " " . $row['null'];
			if( isset( $row['auto_increment'] ) )
				$sql .= " AUTO_INCREMENT";
			if( isset( $row['default'] ) )
				$sql .= " DEFAULT " . $row['default'];
			$fieldcount++;
		}
		if( isset( $this->table_details['primarykey'] ) )
		{
			if( $fieldcount > 0 )
			{
				$sql .= ",";
			}
		
			$sql .= " Primary KEY (`" . $this->table_details['primarykey'] . "`)";
		}
		else
		{
			//$sql .= " Primary KEY (`" . $fields_array[0]['name'] . "`)";
		}
		if( isset( $this->table_details['index'] ) )
		{
			foreach( $this->table_details['index'] as $index )
			{
				if( $index['type'] == "unique")
				{
					$sql .= ", UNIQUE KEY `" . $index['keyname'] . "` ( " . $index['columns'] . " )";
				}
				else
					//$sql .= ", INDEX " . $index['keyname'] . "( " . $index['columns'] . " )";
					$sql .= ", UNIQUE KEY `" . $index['keyname'] . "` ( " . $index['columns'] . " )";
			}
		}
		$sql .= " )";
		if( isset( $this->table_details['engine'] ) )
		{
			$sql .= " ENGINE=" . $this->table_details['engine'] . "";
		}
		else
		{
			$sql .= " ENGINE=MyISAM";
		}
		if( isset( $this->table_details['charset'] ) )
		{
			$sql .= " DEFAULT CHARSET=" . $this->table_details['charset'] . ";";
		}
		else
		{
			$sql .= " DEFAULT CHARSET=utf8;";
		}
		//var_dump( $sql );
		display_notification( __FILE__ . " Creating table " . $this->table_details['tablename'] );
		db_query( $sql, "Couldn't create table " . $this->table_details['tablename'] );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $this->alter_table();
	}
	function alter_table()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		//Need a function for doing updates/upgrades between versions.
		//ASSUMPTION:
		//	create_table as been run, and if not exist may
		//	or may not have triggered.  Regardless we are
		//	going to ALTER table to ensure all INDEXES are
		//	created and all COLUMNS exist.
		//
		//	ALTER TABLE tablename
		//		ADD COLUMN (colname colspec, colname2 colspec2)
		$sql = "ALTER TABLE `" . $this->table_details['tablename'] . "`" . "\n";
		$fieldcount = 0;
		$col = "ADD COLUMN (";
		$endcol = ")";
		$col_data = "";
		foreach( $this->fields_array as $row )
		{
			if( $fieldcount > 0 )
			{
				$col_data .= ",";
			}
			$col_data .= "`" . $row['name'] . "` " . $row['type'];
			if( isset( $row['null'] ) )
				$col_data .= " " . $row['null'];
			if( isset( $row['auto_increment'] ) )
				$col_data .= " AUTO_INCREMENT";
			if( isset( $row['default'] ) )
				$col_data .= " DEFAULT " . $row['default'];
			$fieldcount++;
		}
		if( $fieldcount > 0 )
			$col .= $col_data . $endcol;
		//ASSUMING the primary key was generated with the table and no changes since.
	/*
		if( isset( $this->table_details['index'] ) )
		{
			foreach( $this->table_details['index'] as $index )
			{
				if( $index['type'] == "unique")
				{
					$sql .= ", UNIQUE KEY `" . $index['keyname'] . "` ( " . $index['columns'] . " )";
				}
				else
					//$sql .= ", INDEX " . $index['keyname'] . "( " . $index['columns'] . " )";
					$sql .= ", UNIQUE KEY `" . $index['keyname'] . "` ( " . $index['columns'] . " )";
			}
		}
		$sql .= " )";
	 */
		//ASSUMING no changes to the engine nor charset
		//var_dump( $sql );
		display_notification( __FILE__ . " Altering table " . $this->table_details['tablename'] );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return db_query( $sql, "Couldn't alter table " . $this->table_details['tablename'] );
	}
	/*****************************************************************************************//**
	 * Count the number of rows in the table.
	 *
	 * @returns int number of rows
	 * ******************************************************************************************/
	/*@int@*/function count_rows()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		$res = db_query( "select count(*) from " . $this->table_details['tablename'], "Couldn't count rows in " . $this->table_details['tablename'] );
		$count = db_fetch_row( $res );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $count[0];
	}
	/*****************************************************************************************//**
	 * Count the number of rows in the table filtered by criteria
	 *
	 * @params string where criteria w/o leading "where"
	 * @returns int number of rows
	 * ******************************************************************************************/
	/*@int@*/function count_filtered($where = null)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( !isset( $where ) )
			return $this->count_rows();
		$res = db_query( "select count(*) from " . $this->table_details['tablename'] . " where " . $where, "Couldn't count rows in " . $this->table_details['tablename'] );
		$count = db_fetch_row( $res );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return $count[0];
	}
	/*string*/function getPrimaryKey()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( isset( $this->table_details['primarykey'] ) )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return $this->table_details['primarykey'];
		}
		else
			throw new Exception( "Primary Key Not Set", KSF_PRIKEY_NOT_DEFINED );
	}
	/*none*/function getByPrimaryKey()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/*
		$fields = "*";	//comma separated list
		$prikey = $this->getPrimaryKey();
		$where = array( $prikey );
		$orderby = array();
		$limit = null;	//int
		return $this->select_table( $fields, $where, $orderby, $limit );
		*/
		$this->select_row();
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	/***GENERICTABLE***/
	/*****************************************//**
	 * Build the LIMIT clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildLimit()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( (strlen( $this->limit_startrow ) < 1) 
			OR ($this->limit_startrow < 0) 
		  )
		{
			$this->limit_startrow = 0;
		}
		if( strlen( $this->limit_numberrows ) > 0 )
		{
			//If no upper set, don't set a limit
			$this->limit = (int)$this->limit_startrow - 1 . "," . (int)$this->limit_numberrows;
		}
		$this->limit_clause = $this->limit;
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	/*****************************************//**
	 * Build the REPLACE STATEMENT
	 *
	 * repalce is an extension in mysql that does either an insert or a delete then insert.
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined replaces.
	 * @param NONE but uses internal variables
	 * *****************************************/
	function ReplaceQuery( $b_validate_in_table = false)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		global $db_connection;
		$sql = "REPLACE INTO `" . $this->table_details['tablename'] . "`" . "\n";
		$fieldcount = 0;
		$fields = "(";
		$values = "values(";
		foreach( $this->fields_array as $row )
		{
			if( isset( $this->$row['name'] ) )
			{
				if( $fieldcount > 0 )
				{
					$fields .= ", ";
					$values .= ", ";
				}
				$fields .= "`" . $row['name'] . "`";
				$values .=  db_escape($this->$row['name']);
				$fieldcount++;
			}
		}
		$fields .= ")";
		$values .= ")";
		$sql .= $fields . $values;
		if( $fieldcount > 0 )
			db_query( $sql, "Couldn't replace into table " . $this->table_details['tablename'] . " for " .  $sql );	
		else
			display_error( "No values set so couldn't insert" );
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
		return;
	}
	/*****************************************//**
	 * Build the SELECT clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildSelect( $b_validate_in_table = false)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->select_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		$fieldcount = 0;
		$sql = "";
		foreach( $this->select_array as $val )
		{
			if( $b_validate_in_table )
			{
				
				//if( $val != "*"  AND ! in_array( $tabledef, $val ) )
				//	throw new Exception( "Select variable not in table definition", KSF_FIELD_NOT_CLASS_VAR );
 		
			}
			if( 0 < $fieldcount )
				$sql .= ", ";
			$sql .= $val;
			$fieldcount++;
		}
		$this->select_clause = "SELECT " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the FROM clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildFrom()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );

		/**/
		if( null === $this->from_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		$fieldcount = 0;
		$sql = "";
		foreach( $this->from_array as $val )
		{
			if( 0 < $fieldcount )
				$sql .= ", ";
			$sql .= $val;
			$fieldcount++;
		}
		$this->from_clause = " FROM " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the WHERE clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildWhere( $b_validate_in_table = false)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->where_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		if( count( $this->where_array ) < 1 )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return;
		}
		$fieldcount = 0;
		$sql = "";
		if( isset( $this->where_array ) AND is_array( $this->where_array ) )
		{
			foreach( $this->where_array as $col => $val )
			{
				if( $b_validate_in_table )
				{
					
				//	if( $val != "*"  AND ! in_array( $tabledef, $val ) )
				//		throw new Exception( "Select variable not in table definition", KSF_FIELD_NOT_CLASS_VAR );
 				
				}
/*
				if( 0 < $fieldcount )
					$sql .= " and ";
				$sql .= "$col = '$val' ";
				$fieldcount++;
*/
				if( ! is_array( $val )
				{
					if( 0 < $fieldcount )
					{
						$sql .= " and ";
					}
					$sql .= "$col = '$val' ";
					$fieldcount++;
				}
				else
				{
					foreach( $val as $operand => $field )
					{
						if( 0 < $fieldcount )
						{
							$sql .= " and ";
						}
						$sql .= "$col $operand $field";
						$fieldcount++;
					}
				}
			}
		}
		$this->where_clause = " WHERE " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the ORDERBY clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildOrderBy( $b_validate_in_table = false)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->orderby_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		if( count( $this->orderby_array ) < 1 )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return;
		}
		$fieldcount = 0;
		$sql = "";
		if( isset( $this->orderby_array ) AND is_array( $this->orderby_array ))
		{
			foreach( $this->orderby_array as $col )
			{
				if( $b_validate_in_table )
				{
					
				//	if( $col != "*"  AND ! in_array( $tabledef, $col ) )
				//		throw new Exception( "Select variable not in table definition", KSF_FIELD_NOT_CLASS_VAR );
 				
				}
				if( 0 < $fieldcount )
					$sql .= ", ";
				$sql .= " '$col' ";
				$fieldcount++;
			}
		}
		$this->orderby_clause = " ORDER BY " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the GROUPBY clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildGroupBy( $b_validate_in_table = false)
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->groupby_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		if( count( $this->groupby_array ) < 1 )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return;
		}
		$fieldcount = 0;
		$sql = "";
		if( isset( $this->groupby_array ) AND is_array( $this->groupby_array ))
		{
			foreach( $this->groupby_array as $col )
			{
				if( $b_validate_in_table )
				{
					
				//	if( $col != "*"  AND ! in_array( $tabledef, $col ) )
				//		throw new Exception( "Select variable not in table definition", KSF_FIELD_NOT_CLASS_VAR );
 				
				}
				if( 0 < $fieldcount )
					$sql .= ", ";
				$sql .= " '$col' ";
				$fieldcount++;
			}
		}
		$this->groupby_clause = " GROUP BY " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the HAVING clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * HAVING was added because WHERE conditions can't be used with aggregate functions.
	 * e.g having count(orders) > 10
	 *
	 * @param bool should we check the table definition that all variables are in it.  Prevents joined selects.
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildHaving( )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->having_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		if( count( $this->having_array ) < 1 )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return;
		}
		$fieldcount = 0;
		$sql = "";
		if( isset( $this->having_array ) AND is_array( $this->having_array ))
		{
			foreach( $this->having_array as $col )
			{
				if( 0 < $fieldcount )
					$sql .= ", ";
				$sql .= " '$col' ";
				$fieldcount++;
			}
		}
		$this->having_clause = " HAVING " . $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	/*****************************************//**
	 * Build the JOIN clause
	 *
	 * adapted from legacy GENERICTABLE
	 *
	 * Employees INNER JOIN Orders on employees.id = orders.employee_id
	 * Expecting
	 * 	array[0] = array( 'table1' => tbname,
	 * 			'table2' => tbname,
	 * 			'field1 => fdname,
	 * 			'field2 => fdname,
	 * 			'type' => INNER_JOIN /...
	 * 			)
	 *
	 * @param NONE but uses internal variables
	 * @return NONE but sets limit
	 * *****************************************/
	function buildJoin() 
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		if( null === $this->join_array )
			throw new Exception( "Required Field not set", KSF_FIELD_NOT_SET );
		if( count( $this->join_array ) < 1 )
		{
			$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
			return;
		}
		$sql = "";
		$joincount = 0;
		$FIELDS = array( 'table1', 'type', 'table2', 'field1', 'field2' );
		if( isset( $this->join_array ) AND is_array( $this->join_array ))
		{
			foreach( $this->join_array as $arr )
			{
				if( 0 < $joincount )
					$sql .= ", ";
				foreach( $FIELDS as $col )
				{
					if( isset( $arr[$col] ) )
						$sql .= $arr[$col] . " ";
						if( $col == 'table2' )
							$sql .= "on ";
						else if( $col == 'field1' )
							$sql .= "= ";
					else
						throw new Exception( "Mandatory field $col not set" );
				}
				$joincount++;
			}
		}
		$this->join_clause = $sql;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}	
	function buildSelectQuery( $b_validate_in_table = false )
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		/**/
		try {
			$this->buildSelect($b_validate_in_table);
		} catch( Exception $e )
		{
			throw new Exception( "Can't select anything with invalid select criterea", $e->getCode() );
		}
		try {
			$this->buildFrom($b_validate_in_table);
		} catch( Exception $e )
		{
			throw new Exception( "Can't select anything with invalid FROM criterea", $e->getCode() );
		}
		try {
			$this->buildWhere($b_validate_in_table);
		} catch( Exception $e )
		{
						//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
			}
			else
				throw new Exception( "Can't select anything with invalid WHERE criterea", $e->getCode() );
		}
		try {
			$this->buildGroupby($b_validate_in_table);
		} catch( Exception $e )
		{
			//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
			}
			//Invalid Groupby might not result in the right data set returned but shouldn't hard fail
			//throw new Exception( "Can't select anything with invalid FROM criterea" );
		}
		try {
			$this->buildOrderBy($b_validate_in_table);
		} catch( Exception $e )
		{
			//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
				//echo "No Orderby set";
			}
			else
			{
				//Invalid Orderby might not result in the right data set returned but shouldn't hard fail
				throw new Exception( "Can't select anything with invalid ORDERBY criterea" );
			}
		}
		try {
			$this->buildHaving($b_validate_in_table);
		} catch( Exception $e )
		{
			//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
				//echo "No HAVING set";
			}
			else
			{
				//Invalid Having might not result in the right data set returned but shouldn't hard fail
				throw new Exception( "Can't select anything with invalid HAVING criterea" );
			}
		}
		try {
			$this->buildJoin($b_validate_in_table);
		} catch( Exception $e )
		{
			//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
			//	echo "No JOIN to be done!";
			}
			else
			{
			}
		}
		try {
			$this->buildLimit($b_validate_in_table);
		} catch( Exception $e )
		{
			//Is this a FIELD NOT SET error or something else?  Joins are not mandatory
			if( KSF_FIELD_NOT_SET == $e->getCode() )
			{
				//Not mandatory, continue
			//	echo "No LIMIT to set";
			}
			else
			{
			}
		}
		$this->sql = $this->select_clause 
			. $this->from_clause 
			. $this->where_clause
			. $this->groupby_clause
			. $this->having_clause
			. $this->orderby_clause
			. $this->limit_clause;
 		/**/
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
	}
	function clear_sql_vars()
	{
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		
		$this->select_array = null;
		$this->where_array = null;
		$this->from_array = null;
		$this->groupby_array = null;
		$this->having_array = null;
		$this->orderby_array = null;
		$this->sortdir = "ASC";
		$this->limit = "";
		$this->limit_startrows = null; 
		$this->limit_numberrows = null;
		$this->select_clause = "";
		$this->where_clause = "";
		$this->from_clause = "";
		$this->groupby_clause = "";
		$this->having_clause = "";
		$this->orderby_clause = "";
		$this->query_results = "";
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
 		
	}
	/***!GENERICTABLE***/
	function assoc2var( $assoc )
        {
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
                foreach( $this->fields_array as $field_spec )
                {
                        $field = $field_spec['name'];
                        if( isset( $assoc[$field] ) )
                                $this->set( $field, $assoc[$field] );
                }
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
        }
	function var2caller()
        {
		$this->notify( __METHOD__ . ":" . __LINE__ . " Entering " . __METHOD__, "WARN" );
		if( ! isset( $this->caller ) )
			throw new Exception( "Caller not set so can't pass values back", KSF_FIELD_NOT_SET );
                foreach( $this->fields_array as $field_spec )
                {
                        $field = $field_spec['name'];
                        if( isset( $this->$field ) )
                                $this->caller->set( $field, $this->$field );
                }
		$this->notify( __METHOD__ . ":" . __LINE__ . " Exiting " . __METHOD__, "WARN" );
        }


}

?>
