<?php

namespace Ksfraser\Common\Core;

use Ksfraser\Common\Core\origin;

/**
 * Class base
 * Extends the origin class to add file writing capabilities.
 *
 * @package Ksfraser\Common\Core
 */
class base extends origin
{
    /**
     * @var string|null Username for authentication.
     */
    var $username;

    /**
     * @var string|null Password for authentication.
     */
    var $password;

    /**
     * @var string|null Error message.
     */
    var $errmsg;

    /**
     * @var bool Debug mode flag.
     */
    var $debug;

    /**
     * @var bool Whether to decode JSON as an array.
     */
    var $json_decode_as_array = FALSE;

    const HASH_ALGORITHM = 'SHA256';

    /**
     * Constructor to initialize the base class.
     *
     * @param array $args Optional arguments to initialize properties.
     */
    function __construct(/*array*/ $args = array())
    {
        $this->parse_args($args);
    }

    /**
     * Destructor for cleanup.
     */
    function __destruct()
    {
    }

    /**
     * Parse arguments and set properties.
     *
     * @param array $args Key-value pairs of properties to set.
     */
    function parse_args(/*array*/ $args)
    {
        foreach ($args as $key => $value) {
            if ($key = "options") {
                $this->parse_args($value);
            } else {
                $this->$key = $value;
            }
        }
    }

    /**
     * Open a file for writing.
     *
     * @param string $filename The name of the file to open.
     * @return resource The file pointer resource.
     */
    function open_write_file($filename)
    {
        return fopen($filename, 'w');
    }

    /**
     * Write a line to a file.
     *
     * @param resource $fp The file pointer resource.
     * @param string $line The line to write.
     */
    function write_line($fp, $line)
    {
        fwrite($fp, $line . "\n");
    }

    /**
     * Finalize and close a file.
     *
     * @param resource $fp The file pointer resource.
     */
    function file_finish($fp)
    {
        fflush($fp);
        fclose($fp);
    }
}