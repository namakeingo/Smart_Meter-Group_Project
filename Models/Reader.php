<?php
/**
 * Created by PhpStorm.
 * User: kumai
 * Date: 13/01/2019
 * Time: 16:48
 */

class Reader
{
    private $filename;
    private $lines;
    private $data;

    public function __construct($filename)
    {
        $this->filename = $filename;
        // an array of lines present in the text files
        $this->lines = file($this->filename);
        // array for storing the data of the lines in the file
        $this->data = [];
    }

    /**
     * @return mixed
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @return array|bool
     */
    public function getLines()
    {
        return $this->lines;
    }

    /**
     * @param string|$filename
     */
    public function setLines($filename)
    {
        // sets lines array to the new lines of the new file
        $this->lines = file($filename);
        // resets the data array
        $this->data = [];
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    public function readData($type) {
        foreach ($this->lines as $line) {
            if ($type == 'multiple') {
                // preg splits does what explode does but with a regular expression for the delimiter
                $line = preg_split("/[, | \n]/",$line);
                $this->data[] = [intval($line[0]),intval($line[1]), intval($line[2])];

            }
            elseif ($type == 'single') {
                $line = trim($line);
                $this->data[] = $line;
            }
        }
    }
}