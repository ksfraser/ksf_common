<?php

require_once( 'defines.inc.php' );
require_once( 'class.origin.php' );
require_once 'class.BaseDAO.php';

/**
 * Class db_base
 * Provides database connection and management functionality.
 */
class db_base extends origin
{
    /**
     * @var string|null Preferences table name.
     */
    var $prefs_tablename;

    /**
     * @var BaseDAO Database helper instance.
     */
    private $baseDAO;

    /**
     * Constructor to initialize the database connection and preferences table.
     *
     * @param string $host Database host.
     * @param string $user Database user.
     * @param string $pass Database password.
     * @param string $database Database name.
     * @param string $prefs_tablename Preferences table name.
     */
public     function __construct($host, $user, $pass, $database, $prefs_tablename)
    {
        parent::__construct();
        $this->baseDAO = new BaseDAO($host, $user, $pass, $database);
        $this->prefs_tablename = $prefs_tablename;
    }

    /**
     * Create the preferences table.
     */
    function create_prefs_tablename()
    {
        $sql = "DROP TABLE IF EXISTS `" . $this->prefs_tablename . "`";
        $this->baseDAO->query($sql);

        $sql = "CREATE TABLE `" . $this->prefs_tablename . "` (
            `name` char(32) NOT NULL default '',
            `value` varchar(100) NOT NULL default '',
            PRIMARY KEY  (`name`))
            ENGINE=MyISAM";
        $this->baseDAO->query($sql);
        $this->set_pref('lastcid', 0);
        $this->set_pref('lastoid', 0);
    }

    /**
     * Set a preference value.
*
     * @param string $pref The preference name.
     * @param mixed $value The preference value.
     */
    function set_pref($pref, $value)
    {
        $sql = "REPLACE INTO `" . $this->prefs_tablename . "` (name, value) VALUES ('" . $this->baseDAO->escape($pref) . "', '" . $this->baseDAO->escape($value) . "')";
        $this->baseDAO->query($sql);
    }

    /**
     * Get a preference value.
*
     * @param string $pref The preference name.
     * @return string|null The preference value.
     */
    function get_pref($pref)
    {
        $sql = "SELECT * FROM `" . $this->prefs_tablename . "` WHERE name = '" . $this->baseDAO->escape($pref) . "'";
        $result = $this->baseDAO->query($sql);
        $row = $this->baseDAO->fetch_row($result);
        return $row['value'] ?? null;
    }

    /**
     * Escape a string for use in SQL queries.
     *
     * @param string $value The string to escape.
     * @return string The escaped string.
     */
    private function escape($value)
    {
        return mysqli_real_escape_string($this->db_connection, $value);
    }
}
?>
