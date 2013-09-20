<?php
/**
 * @since 0.2.0
 */

class Shell_MakeReveal
{
    protected $_args;

    protected $_logDepth = 0;

    public $header;

    public function __construct($args = null)
    {
        if ($args === null) $args = getopt("f:");

        $this->_args = $args;
    }


    public function __invoke()
    {
        chdir(__DIR__);

        $filename = $this->getArg('f');
        if (!file_exists($filename))
        {
            echo $filename . ' not a file.';
            return;
        }

        $content = file_get_contents($filename);

        $content = preg_replace('/LeMike_DevMode_Test_[^_\n]*_/', '', $content);

        $data = array();
        $target = null;
        foreach (explode(PHP_EOL, $content) as $line)
        {
            if (substr($line, 0, 1) == ' ')
            {
                $line = trim($line);
                $target[] = preg_replace('/\[[x\s]{1}\]/', '', $line);
            }
            elseif ($line = trim($line))
            {
                $target =& $data;
                foreach (explode('_', $line) as $segment)
                {
                    $target =& $target[$segment];
                }
            }
        }

        $out = $this->header . $this->makeOut($data);

        file_put_contents('../FEATURES.md', $out);
    }


    public function makeOut($data, $depth = 1)
    {
        $maxDepth = 3;

        $out = "";
        foreach ($data as $key => $line)
        {
            if (is_string($key) && is_array($line))
            { // strrrring
                $out .= "\n\n";
                if ($depth <= $maxDepth)
                {
                    $out .= str_repeat('#', $depth);
                }
                else
                {
                    $out .= str_repeat('  ', $depth - $maxDepth) . '-';
                }

                $out .= ' ' . $key . "\n" . $this->makeOut($line, ($depth+1));
            }
            elseif (!is_string($key))
            {
                $out .= str_repeat('  ', max(0, $depth - $maxDepth)) . '-';
                $out .= $line;
            }
            else
            {
                $out .= implode("\n", (array) $line);
            }

            $out .= "\n";
        }

        return $out;
    }


    public function getArg($short, $long = '')
    {
        if (isset($this->_args[$short]))
        {
            if ($this->_args[$short] === false) return true;

            return $this->_args[$short];
        }

        if ($long != '') {
            return $this->getArg($long);
        }

        return false;
    }


    /**
     * .
     *
     * @param $str
     * @return void
     */
    public function _logMessage($str)
    {
        $this->_logAdapter(str_repeat('  ', $this->_logDepth) . $str . PHP_EOL);
    }


    protected function _logAdapter($str)
    {
        echo $str;
    }


    private function _logVerbose($string)
    {
        $this->_logMessage($string);
    }
}

$cmd = new Shell_MakeReveal();

$cmd->header = <<<MARKDOWN
# Features

This list has been generated while testing the extension.
Please excuse the bad english.

MARKDOWN;

$cmd->__invoke();

echo PHP_EOL;
