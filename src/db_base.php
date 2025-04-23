<?php

//20170608 There is something in here screwing with FAs display_notification Exception handler

require_once( 'defines.inc.php' );	//defines path_to_faroot
//global $path_to_faroot;

/*
$path_to_faroot = __DIR__ . '/../..';
require_once( $path_to_faroot . '/includes/db/connect_db.inc' ); //db_query, ...
require_once( $path_to_faroot . '/includes/errors.inc' ); //check_db_error, ...
*/

require_once( 'class.origin.php' );
/***************************************************************//**
 *
 * Inherits:
   	function __construct( $loglevel = PEAR_LOG_DEBUG )
        / *@NULL@* /function set_var( $var, $value )
        function get_var( $var )
        / *@array@* /function var2data()
        / *@array@* /function fields2data( $fieldlist )
        / *@NULL@* /function LogError( $message, $level = PEAR_LOG_ERR )
        / *@NULL@* /function LogMsg( $message, $level = PEAR_LOG_INFO )
 * 
 * Provides:
 	function __construct( $host, $user, $pass, $database, $prefs_tablename )
        function connect_db()
        / *bool* / function is_installed()
        function set_prefix()
        function create_prefs_tablename()
        function mysql_query( $sql, $errmsg = NULL )
        function set_pref( $pref, $value )
        / *string* / function get_pref( $pref )
        function loadprefs()
        function updateprefs()
        function create_table( $table_array, $field_array )
 * 
 *
 * ******************************************************************/
/**
 * Class db_base
 * Provides database connection and management functionality.
 *
 * @package Ksfraser\Common\Core
 */
class db_base extends origin
{
    /**
     * @var string|null Database host.
     */
    var $host;

    /**
     * @var string|null Database user.
     */
    var $user;

    /**
     * @var string|null Database password.
     */
    var $pass;

    /**
     * @var string|null Database name.
     */
    var $database;

    /**
     * @var string|null Current action being performed.
     */
    var $action;

    /**
     * @var string|null Database host (alternative).
     */
    var $dbHost;

    /**
     * @var string|null Database user (alternative).
     */
    var $dbUser;

    /**
     * @var string|null Database password (alternative).
     */
    var $dbPassword;

    /**
     * @var string|null Database name (alternative).
     */
    var $dbName;

    /**
     * @var resource|null Database connection resource.
     */
    var $db_connection;

    /**
     * @var string|null Preferences table name.
     */
    var $prefs_tablename;

    /**
     * @var string|null Company prefix for table names.
     */
    var $company_prefix;

    /**
     * @var array|null Data fetched from the database.
     */
    var $data;

    /**
     * @var string|null SQL query string.
     */
    var $sql;

    /**
     * @var string|null Error message for SQL queries.
     */
    var $sqlerrmsg;

    /**
     * Constructor to initialize the database connection.
     *
     * @param string $host Database host.
     * @param string $user Database user.
     * @param string $pass Database password.
     * @param string $database Database name.
     * @param string $prefs_tablename Preferences table name.
     */
    function __construct($host, $user, $pass, $database, $prefs_tablename)
    {
        parent::__construct();
        try {
            $this->set_var("dbHost", $host);
            $this->set_var("dbUser", $user);
            $this->set_var("dbPassword", $pass);
            $this->set_var("dbName", $database);
        } catch (Exception $e) {
        }
        $this->set_var("prefs_tablename", $prefs_tablename);
        try {
            $this->set_var("host", $host);
            $this->set_var("user", $user);
            $this->set_var("pass", $pass);
            $this->set_var("database", $database);
        } catch (Exception $e) {
        }

        $this->set_prefix();
        $this->connect_db();
    }

    /**
     * Connect to the database.
     *
     * @return bool True if connection is successful, false otherwise.
     */
    function connect_db()
    {
        if (!$this->db_connection) {
            return FALSE;
        } else {
            mysql_select_db($this->dbName, $this->db_connection);
            return TRUE;
        }
    }

    /**
     * Check if the database is installed.
     *
     * @return bool True if installed, false otherwise.
     * @throws Exception If dependencies are not met.
     */
    function is_installed()
    {
        global $db_connections;
        if (!isset($_SESSION["wa_current_user"])) {
            throw new Exception("is_installed dependencies failed. Are we in CLI mode?", KSF_FIELD_NOT_SET);
        }

        $cur_prefix = $db_connections[$_SESSION["wa_current_user"]->cur_con]['tbpref'];

        $sql = "SHOW TABLES LIKE '%" . $cur_prefix . $this->prefs_tablename . "%'";
        $result = db_query($sql, __FILE__ . " could not show tables in is_installed: " . $sql);

        $num = db_num_rows($result);
        return $num > 0;
    }

    /**
     * Set the company prefix for table names.
     */
    function set_prefix()
    {
        if (!isset($this->company_prefix)) {
            if (strlen(TB_PREF) == 2) {
                $this->set_var('company_prefix', TB_PREF);
            } else {
                global $db_connections;
                $this->set_var('company_prefix', $db_connections[$_SESSION["wa_current_user"]->cur_con]['tbpref']);
            }
        }
    }

    /**
     * Create the preferences table.
     */
    function create_prefs_tablename()
    {
        $sql = "DROP TABLE IF EXISTS " . $this->company_prefix . $this->prefs_tablename;
        db_query($sql, "Error dropping table");

        $sql = "CREATE TABLE `" . $this->company_prefix . $this->prefs_tablename . "` (
            `name` char(32) NOT NULL default "",
            `value` varchar(100) NOT NULL default "",
            PRIMARY KEY  (`name`))
            ENGINE=MyISAM";
        db_query($sql, "Error creating table");
        $this->set_pref('lastcid', 0);
        $this->set_pref('lastoid', 0);
    }

    /**
     * Execute an SQL query.
     *
     * @param string|null $sql The SQL query string.
     * @param string|null $errmsg Error message for the query.
     * @return array|null The fetched data.
     * @throws Exception If the SQL query is missing.
     */
    function mysql_query($sql = null, $errmsg = NULL)
    {
        if (null === $sql) {
            $sql = $this->sql;
        }
        if (null === $errmsg) {
            $errmsg = $this->sqlerrmsg;
        }
        if (null === $sql) {
            throw new Exception("Can't do an SQL query without the SQL statement");
        }
        $result = db_query($sql, $errmsg);
        $this->data = db_fetch($result);
        return $this->data;
    }

    /**
     * Set a preference value.
     *
     * @param string $pref The preference name.
     * @param mixed $value The preference value.
     */
    function set_pref($pref, $value)
    {
        if (!isset($this->company_prefix) || !isset($this->prefs_tablename)) {
            return null;
        }
        $sql = "REPLACE " . $this->company_prefix . $this->prefs_tablename . " (name, value) VALUES (" . db_escape($pref) . ", " . db_escape($value) . ")";
        db_query($sql, "can't update " . $pref);
    }

    /**
     * Get a preference value.
     *
     * @param string $pref The preference name.
     * @return string|null The preference value.
     */
    function get_pref($pref)
    {
        if (!isset($this->company_prefix) || !isset($this->prefs_tablename)) {
            return null;
        }
        $pref = db_escape($pref);

        $sql = "SELECT * FROM " . $this->company_prefix . $this->prefs_tablename . " WHERE name = $pref";
        $result = db_query($sql, "could not get pref " . $pref);

        if (!db_num_rows($result)) {
            return null;
        }
        $row = db_fetch_row($result);
        return $row[1];
    }

    /**
     * Load preferences from the database.
     */
    function loadprefs()
    {
        foreach ($this->config_values as $row) {
            if (isset($row['integration_module']) && strlen($row['integration_module']) > 3) {
                $this->set_var($row['pref_name'], $this->get_pref($row['pref_name']));
            } else {
                $this->set_var($row['pref_name'], $this->get_pref($row['pref_name']));
            }
        }
    }

    /**
     * Update preferences in the database.
     */
    function updateprefs()
    {
        foreach ($this->config_values as $row) {
            if (isset($_POST[$row['pref_name']])) {
                $this->set_var($row['pref_name'], $_POST[$row['pref_name']]);
                if (isset($row['integration_module']) && strlen($row['integration_module']) > 3) {
                    $this->set_pref($row['pref_name'], $_POST[$row['pref_name']]);
                } else {
                    $this->set_pref($row['pref_name'], $_POST[$row['pref_name']]);
                }
            }
        }
    }

    /**
     * Create a database table.
     *
     * @param array $table_array Table configuration.
     * @param array $field_array Field configuration.
     * @return bool True if successful, false otherwise.
     */
    function create_table($table_array, $field_array)
    {
        if (!isset($table_array) || !isset($field_array)) {
            return FALSE;
        }
        $sql = "CREATE TABLE IF NOT EXISTS `" . $table_array['tablename'] . "` (\n";
        $fieldcount = 0;
        foreach ($field_array as $row) {
            $sql .= "`" . $row['name'] . "` " . $row['type'];
            if (isset($row['null'])) {
                $sql .= " " . $row['null'];
            }
            if (isset($row['auto_increment'])) {
                $sql .= " AUTO_INCREMENT";
            }
            if (isset($row['default'])) {
                $sql .= " DEFAULT " . $row['default'];
            }
            $sql .= ",";
            $fieldcount++;
        }
        if (isset($table_array['primarykey'])) {
            $sql .= " Primary KEY (`" . $table_array['primarykey'] . "`)";
        } else {
            $sql .= " Primary KEY (`" . $field_array[0]['name'] . "`)";
        }
        if (isset($table_array['index'])) {
            foreach ($table_array['index'] as $index) {
                $sql .= ", INDEX " . $index['name'] . "( " . $index['columns'] . " )";
            }
        }
        $sql .= " )";
        if (isset($table_array['engine'])) {
            $sql .= " ENGINE=" . $table_array['engine'] . "";
        } else {
            $sql .= " ENGINE=MyISAM";
        }
        if (isset($table_array['charset'])) {
            $sql .= " DEFAULT CHARSET=" . $table_array['charset'] . ";";
        } else {
            $sql .= " DEFAULT CHARSET=utf8;";
        }
        db_query($sql, "Couldn't create table " . $table_array['tablename']);
    }
}
?>
