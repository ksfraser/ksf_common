<?php

namespace Ksfraser\Common;

/**
 * Class write_file
 * Provides functionality for writing to files, including chunks, lines, and CSV arrays.
 *
 * @package Ksfraser\Common
 */
class write_file
{
    /**
     * @var resource|null File pointer resource.
     */
    protected $fp;

    /**
     * @var string Name of the output file.
     */
    protected $filename;

    /**
     * @var string Temporary directory path.
     */
    protected $tmp_dir;

    /**
     * @var string Delimiter for CSV writing.
     */
    protected $deliminater;

    /**
     * @var string Enclosure for CSV writing.
     */
    protected $enclosure;

    /**
     * @var string Escape character for CSV writing.
     */
    protected $escape_char;

    /**
     * Constructor to initialize the write_file class.
     *
     * @param string $tmp_dir Temporary directory path.
     * @param string $filename Name of the output file.
     * @throws Exception If the file cannot be opened for writing.
     */
    function __construct($tmp_dir = "../../tmp/", $filename = "file.txt")
    {
        $this->tmp_dir = $tmp_dir;
        $this->filename = $filename;
        $this->fp = fopen($this->tmp_dir . "/" . $this->filename, 'w');
        if (!isset($this->fp)) {
            throw new Exception("Unable to set Filepointer when trying to open " . $this->tmp_dir . "/" . $this->filename . " for writing.");
        }
        $this->deliminater = ",";
        $this->enclosure = '"';
        $this->escape_char = "\\";
    }

    /**
     * Destructor to close the file pointer if it is set.
     */
    function __destruct()
    {
        if (isset($this->fp)) {
            $this->close();
        }
    }

    /**
     * Write a chunk of data to the file.
     *
     * @param string $line The data to write.
     * @throws Exception If the file pointer is not set.
     */
    function write_chunk($line)
    {
        if (!isset($this->fp)) {
            throw new Exception("Filepointer not set");
        }
        fwrite($this->fp, $line);
        fflush($this->fp);
    }

    /**
     * Write a line of data to the file.
     *
     * @param string $line The line to write.
     * @throws Exception If the file pointer is not set.
     */
    function write_line($line)
    {
        if (!isset($this->fp)) {
            throw new Exception("Filepointer not set");
        }
        fwrite($this->fp, $line . "\r\n");
        fflush($this->fp);
    }

    /**
     * Write an array of data to the file in CSV format.
     *
     * @param array $arr The array of data to write.
     * @throws Exception If the file pointer is not set.
     */
    function write_array_to_csv($arr)
    {
        if (!isset($this->fp)) {
            throw new Exception("Filepointer not set");
        }
        fputcsv($this->fp, $arr, $this->deliminater, $this->enclosure);
    }

    /**
     * Close the file pointer.
     *
     * @throws Exception If the file pointer is not set.
     */
    function close()
    {
        if (!isset($this->fp)) {
            throw new Exception("Trying to close a Filepointer that isn't set");
        }
        fflush($this->fp);
        fclose($this->fp);
        $this->fp = null;
    }
}

?>
