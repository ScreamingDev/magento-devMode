<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class Table.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Block\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Block_Shell_Table
{
    /** @var array Width of each column. */
    public $_tableColumnWidth = array();

    /** @var array Captions for each column. */
    public $captionSet = array();

    /** @var string Footer of the table. */
    public $footer = "";

    /** @var array Data shown in the table body. */
    protected $_tableRowSet = array();

    /** @var array Legend for headings shown below the body. */
    public $legend = array();


    /**
     * Create a new table.
     *
     * The table will only show and accept the defined headings.
     *
     * @param array $captions Headings for each column.
     * @param array $data     Rows for the table.
     */
    public function __construct($captions = array(), $data = array(), $legend = array())
    {
        $this->captionSet = $captions;

        foreach ($data as $row)
        {
            $this->tableRowAdd($row);
        }

        $this->legend = $legend;
    }


    public function __toString()
    {
        return $this->_table();
    }


    public function calcMaxWidth($key, $message)
    {
        $this->_tableColumnWidth[$key] = max($this->_tableColumnWidth[$key], strlen($message));
    }


    public function dispatch()
    {
        echo $this->__toString();
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
            if (!$this->legend[$key])
            {
                continue;
            }

            $out .= str_pad($this->captionSet[$key], $legendWidth, ' ', $pad)
                    . $separator . $this->legend[$key] . PHP_EOL;
        }

        return rtrim($out);
    }


    public function tableRowAdd($row)
    {
        if ($row instanceof Varien_Object)
        {
            $row = $row->getData();
        }

        $this->_tableRowSet[] = $row;
        foreach ($row as $key => $cell)
        {
            $this->calcMaxWidth($key, $cell);
        }
    }


    protected function _table()
    {
        $out = $this->_tableCaptions()
               . PHP_EOL
               . $this->_tableBody();

        $legend = $this->makeLegend();
        if ($legend != '')
        {
            $out .= PHP_EOL . $legend;
        }

        if ($this->footer != '')
        {
            $out .= PHP_EOL . $this->footer;
        }

        return $out;
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
