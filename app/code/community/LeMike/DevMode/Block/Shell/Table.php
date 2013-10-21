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


    /**
     * Generate table.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }


    /**
     * Calculate the maximum width for this key and store it.
     *
     * @param string $key     The column.
     * @param string $message The body.
     *
     * @return void
     */
    protected function _calcMaxWidth($key, $message)
    {
        $this->_tableColumnWidth[$key] = max($this->_tableColumnWidth[$key], strlen($message));
    }


    /**
     * Print the rendered table to the output.
     *
     * @return void
     */
    public function dispatch()
    {
        echo $this->__toString();
    }


    /**
     * Generate the legend.
     *
     * @param string $separator Infix between column name and description (default: colon).
     * @param int    $pad       Alignment of description.
     *
     * @return string
     */
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


    /**
     * Add a row to the table.
     *
     * @param array $data
     *
     * @return void
     */
    public function addRow($data)
    {
        if ($data instanceof Varien_Object)
        {
            $data = $data->getData();
        }

        $this->_tableRowSet[] = $data;
        foreach ($data as $key => $cell)
        {
            $this->_calcMaxWidth($key, $cell);
        }
    }


    /**
     * Add a row to the table.
     *
     * @param $row
     *
     * @return void
     */
    public function tableRowAdd($row)
    {
        $this->addRow($row);
    }


    /**
     * Generate table.
     *
     * @return string
     */
    public function render()
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


    /**
     * Generate and fetch the table body.
     *
     * @return string
     */
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


    /**
     * Generate and fetch the table heading.
     *
     * @return string
     */
    protected function _tableCaptions()
    {
        $out = '';
        foreach ($this->captionSet as $key => $name)
        {
            $this->_calcMaxWidth($key, $name);

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
