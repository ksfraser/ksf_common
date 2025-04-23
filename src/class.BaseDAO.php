<?php

namespace Ksfraser\Common\Core;

/**
 * Class BaseDAO
 * Provides shared functionality for database access objects.
 */
class BaseDAO
{
    /**
     * @var string|null Database host.
     */
    protected $host;

    /**
     * @var string|null Database user.
     */
    protected $user;

    /**
     * @var string|null Database password.
     */
    protected $pass;

    /**
     * @var string|null Database name.
     */
    protected $database;

    /**
     * @var resource|null Database connection resource.
     */
    protected $db_connection;

    /**
     * Constructor to initialize the database connection.
     *
     * @param string $host Database host.
     * @param string $user Database user.
     * @param string $pass Database password.
     * @param string $database Database name.
     */
    public function __construct($host, $user, $pass, $database)
    {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->database = $database;
        $this->connect_db();
    }

    /**
     * Connect to the database.
     *
     * @return bool True if connection is successful, false otherwise.
     */
    protected function connect_db()
    {
        $this->db_connection = mysqli_connect($this->host, $this->user, $this->pass, $this->database);
        if (!$this->db_connection) {
            throw new \Exception("Database connection failed: " . mysqli_connect_error());
        }
        return true;
    }

    /**
     * Execute an SQL query.
     *
     * @param string $sql The SQL query string.
     * @return mixed The query result.
     */
    public function query($sql)
    {
        $result = mysqli_query($this->db_connection, $sql);
        if (!$result) {
            throw new \Exception("Query failed: " . mysqli_error($this->db_connection));
        }
        return $result;
    }

    /**
     * Fetch a single row from the result set.
     *
     * @param mixed $result The query result.
     * @return array|null The fetched row or null if no rows are left.
     */
    public function fetch_row($result)
    {
        return mysqli_fetch_assoc($result);
    }

    /**
     * Close the database connection.
     */
    public function close_connection()
    {
        if ($this->db_connection) {
            mysqli_close($this->db_connection);
        }
    }
}
