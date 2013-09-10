<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category   mage_devMail
 * @package    Table.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */

/**
 * Class Table.
 *
 * @category   mage_devMail
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      0.3.0
 */
class LeMike_DevMode_Block_Shell_Table
{
    public $_tableColumnHeading = 10;

    public $_tableColumnWidth = array();

    public $captionSet = array();

    public $footer = "";

    protected $_tableRowSet = array();


    function __construct($captions = array(), $data = array())
    {
        $this->captionSet = $captions;

        foreach ($data as $row)
        {
            $this->tableRowAdd($row);
        }
    }


    public function __toString()
    {
        return $this->_table();
    }


    public function tableRowAdd($row)
    {
        $this->_tableRowSet[] = $row;
        foreach ($row as $key => $cell)
        {
            $this->calcMaxWidth($key, $cell);
        }
    }


    public function calcMaxWidth($key, $message)
    {
        $this->_tableColumnWidth[$key] = max($this->_tableColumnWidth[$key], strlen($message));
    }


    protected function _table()
    {
        $out = $this->_tableCaptions()
               . PHP_EOL
               . $this->_tableBody()
               . PHP_EOL
               . $this->makeLegend()
               . PHP_EOL
               . $this->footer;

        return $out;
    }


    public function makeLegend($separator = ": ", $pad = STR_PAD_LEFT)
    {
        $legendWidth = 0;
        foreach ($this->captionSet as $value)
        {
            $legendWidth = max($legendWidth, strlen($value));
        }

        $out = '';
        foreach ($this->captionSet as $key => $null)
        {
            $out .= str_pad($this->captionSet[$key], $legendWidth, ' ', $pad)
                    . $separator . $this->legend[$key] . PHP_EOL;
        }

        return rtrim($out);
    }


    protected function _tableBody()
    {
        $out = '';

        foreach ($this->_tableRowSet as $content)
        {
            foreach ($this->captionSet as $key => $null)
            {
                $out .= str_pad($content[$key], $this->_tableColumnWidth[$key], ' ');
                $out .= ' | ';
            }
            $out .= PHP_EOL;
        }

        return $out;
    }


    protected function _tableCaptions()
    {
        $out = '';
        foreach ($this->captionSet as $key => $name)
        {
            $this->calcMaxWidth($key, $name);

            $out .= str_pad($name, $this->_tableColumnWidth[$key], ' ', STR_PAD_BOTH);
            $out .= ' | ';
        }

        $out .= PHP_EOL;

        foreach ($this->captionSet as $key => $name)
        {
            $out .= str_pad('', $this->_tableColumnWidth[$key], '-');
            $out .= '-+-';
        }

        return $out;
    }
}
