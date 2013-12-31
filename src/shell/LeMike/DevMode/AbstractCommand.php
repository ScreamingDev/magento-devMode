<?php
/**
 * Class Parameters
 *
 * Parses parameters given by CLI.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 */

/**
 * Parser for Parameters.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Shell\DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 */
class LeMike_DevMode_Parameter
{
    const ARGUMENTS = 'arguments';

    const COMMANDS = 'commands';

    const FLAGS = 'flags';

    const OPTIONS = 'options';

    /** @var string Prefix that indicates a flag */
    public static $flagPrefix = '-';

    /** @var string Infix between associations in options (e.g. --number=8) */
    public static $optionInfix = '=';

    /** @var string Prefix that indicates an option */
    public static $optionPrefix = '--';

    /** @var array Array with given parameter */
    protected $_parameter;


    /**
     * Create new LeMike_DevMode_Parameter Object by giving a parameter array.
     *
     * Provide an array like $argv that will be parsed.
     *
     * @param array   $parameterArray Set of options, commands, etc.
     * @param boolean $preserveFirst  Do not delete the first item (default: false - first will be deleted).
     */
    public function __construct($parameterArray = array(), $preserveFirst = false)
    {
        $this->_parameter = $this->parseToArray($parameterArray, $preserveFirst);
    }


    /**
     * Get a new instance by parsing environment.
     *
     * This will parse $GLOBALS['argv'] and return a new object.
     *
     * @return static
     */
    public static function createFromGlobals()
    {
        return new static($GLOBALS['argv'], false);
    }


    /**
     * Parses Parameters and assigns them to an array.
     *
     * Supports:
     * -e
     * -e <value>
     * --long-param
     * --long-param=<value>
     * --long-param <value>
     * <value>
     *
     * @param array $parameterArray Set of options, commands, etc.
     * @param bool  $preserveFirst  Do not delete the first item (default: false - first will be deleted)
     *
     * @return array
     */
    public static function parseToArray($parameterArray, $preserveFirst = false)
    {
        // default data
        $parsedToArray = array(
            static::COMMANDS  => array(),
            static::OPTIONS   => array(),
            static::FLAGS     => array(),
            static::ARGUMENTS => array(),
        );

        $endOfOptionsFlag = false;

        if (!$preserveFirst)
        {
            // do not preserve first: kill the script name itself
            array_shift($parameterArray);
        }

        while ($arg = array_shift($parameterArray))
        {
            /** @var string $arg Iterate over each parameter */

            if ($endOfOptionsFlag)
            { // end of options reached: only arguments after that
                $parsedToArray[static::ARGUMENTS][] = $arg;
                continue;
            }

            if (self::$optionPrefix === substr($arg, 0, 2))
            { // prefix '--': it is some kind of option

                // end of options flag?
                if (!isset ($arg[3]))
                { // it is the end of options flag: flag it
                    $endOfOptionsFlag = true;
                    continue;
                }

                $value   = "";
                $command = substr($arg, 2);

                // is it the syntax '--option=argument'?
                if (strpos($command, self::$optionInfix))
                { // it is a pair: resolve it
                    list($command, $value) = explode(self::$optionInfix, $command, 2);
                } // or is the option not followed by another option but by arguments?
                else
                { // followed by arguments: parse em
                    while (!empty($parameterArray) &&
                           strpos($parameterArray[0], self::$flagPrefix) !== 0)
                    {
                        $value .= array_shift($parameterArray) . ' ';
                    }
                    $value = rtrim($value, ' ');
                }

                $parsedToArray[static::OPTIONS][$command] = !empty($value) ? $value : true;
                continue;
            }

            // Is it a bunch of flags?
            if (substr($arg, 0, 1) === self::$flagPrefix)
            { // found prefix '-' : parse flags
                $end = strlen($arg);
                for ($i = 1; $i < $end; $i++)
                {
                    $parsedToArray[static::FLAGS][] = $arg[$i];
                }
                continue;
            }

            // neither option, nor flag, nor argument
            $parsedToArray[static::COMMANDS][] = $arg;
            continue;
        }

        if (!count($parsedToArray[static::OPTIONS]) && !count($parsedToArray[static::FLAGS]))
        { // neither options nor flags given: reduce commands to simple arguments
            $parsedToArray[static::ARGUMENTS] = array_merge(
                $parsedToArray[static::COMMANDS],
                $parsedToArray[static::ARGUMENTS]
            );

            $parsedToArray[static::COMMANDS] = array();
        }

        return $parsedToArray;
    }


    /**
     * Get all arguments.
     *
     * @return mixed
     */
    public function getArguments()
    {
        return $this->_parameter[static::ARGUMENTS];
    }


    /**
     * Get all found commands
     *
     * @return mixed
     */
    public function getCommands()
    {
        return $this->_parameter[static::COMMANDS];
    }


    /**
     * Get all found flags
     *
     * @return mixed
     */
    public function getFlags()
    {
        return $this->_parameter[static::FLAGS];
    }


    /**
     * Get all or a single option.
     *
     * @param null|string $name Name which option to get (default: null - get all as array).
     *
     * @param null        $default
     *
     * @return mixed
     */
    public function getOption($name = null, $default = null)
    {
        if (null === $name)
        { // no specific: return all
            return $this->_parameter[static::OPTIONS];
        }

        if ($this->hasOption($name))
        { // specific is set: show it
            return $this->_parameter[static::OPTIONS][$name];
        }

        // not found: show default (which is null by default) ... default
        return $default;
    }


    /**
     * Check if a argument is present.
     *
     * @param string $name Identifier of an argument
     *
     * @return boolean
     */
    public function hasArgument($name)
    {
        return in_array($name, $this->getArguments());
    }


    /**
     * Check if a command is present.
     *
     * @param string $name Identifier of a command
     *
     * @return boolean
     */
    public function hasCommand($name)
    {
        return in_array($name, $this->getCommands());
    }


    /**
     * Check if a flag is present.
     *
     * @param string $name Identifier of a flag
     *
     * @return boolean
     */
    public function hasFlag($name)
    {
        return in_array($name, $this->getFlags());
    }


    /**
     * Check if this parameter has an option set.
     *
     * Options are set via CLI using "--".
     * E.g.
     *
     * foo --bar --baz=qux
     *
     * @param string $name The identifier or option to check.
     *
     * @return bool
     */
    public function hasOption($name)
    {
        return isset($this->_parameter[static::OPTIONS][$name]);
    }


    /**
     * Receive all params as assoc array (category => list).
     *
     * @return array
     */
    public function toArray()
    {
        return $this->_parameter;
    }


    /**
     * Remove an argument.
     *
     * @param string $name Identifier of an argument
     *
     * @return boolean
     */
    public function unsetArgument($name = null)
    {
        if (null == $name)
        { // no specific: truncate arguments
            $this->_parameter[static::ARGUMENTS] = array();
        }

        // locate argument
        $pos = array_search($name, $this->getArguments(), true);

        unset($this->_parameter[static::ARGUMENTS][$pos]);

        return (!isset($this->_parameter[static::ARGUMENTS][$pos]));
    }


    /**
     * Remove a command.
     *
     * @param string $name Identifier of a command to remove
     *
     * @return boolean
     */
    public function unsetCommand($name = null)
    {
        if (null == $name)
        { // no specific: truncate commands
            $this->_parameter[static::COMMANDS] = array();

            return true;
        }

        // locate command
        $pos = array_search($name, $this->getCommands(), true);

        if ($pos !== false)
        { // found: remove command
            unset($this->_parameter[static::COMMANDS][$pos]);
        }

        return (!isset($this->_parameter[static::COMMANDS][$pos]));
    }


    /**
     * Remove a flag.
     *
     * @param string $name Identifier of a flag
     *
     * @return boolean
     */
    public function unsetFlag($name = null)
    {
        if (null == $name)
        { // no specific: return all
            $this->_parameter[static::FLAGS] = array();
        }

        $pos = array_search($name, $this->getArguments(), true); // locate flag

        unset($this->_parameter[static::FLAGS][$pos]);

        return (!isset($this->_parameter[static::FLAGS][$pos]));
    }


    /**
     * Remove an option.
     *
     * @param string $name Identifier of an option
     *
     * @return boolean
     */
    public function unsetOption($name = null)
    {
        if (null == $name)
        { // no specific: truncate options
            $this->_parameter[static::OPTIONS] = array();
        }

        if ($this->getOption($name) !== null)
        { // specific found: unset
            unset($this->_parameter[static::OPTIONS][$name]);
        }

        return (!$this->hasOption($name));
    }
}

/**
 * Class AbstractCommand.
 *
 * @category   php-application-toolkit
 * @package    Pat\Environment\System\Cli
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/php-application-toolkit/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/php-application-toolkit
 * @since      0.1.0
 */
abstract class AbstractCommand
{

    /** @var LeMike_DevMode_Parameter to a specific command */
    protected $_parameter;


    /**
     * Create a new command.
     *
     * This class is especially designed for use with CLI.
     *
     * @param array $parameter Arguments for this command.
     *
     * @throws Exception
     */
    public function __construct($parameter = array())
    {
        if ($parameter instanceof LeMike_DevMode_Parameter)
        {
            $this->_parameter = $parameter;
        }
        elseif (is_array($parameter))
        {
            $this->_parameter = new LeMike_DevMode_Parameter($parameter, true);
        }
        else
        {
            throw new Exception("Unsupported type " . gettype($parameter));
        }
    }


    /**
     * Run this command.
     *
     * @return void
     */
    public function __invoke()
    {
        $this->execute();
    }


    /**
     * Run this command.
     *
     * @return mixed
     */
    abstract public function execute();


    /**
     * Get the current parameter.
     *
     * The parameter influence the behaviour of this command.
     *
     * @return LeMike_DevMode_Parameter
     */
    public function getParameter()
    {
        return $this->_parameter;
    }


    /**
     * Parse how to use a method.
     *
     * Will take the doc-comments and turn them into raw text without stars etc.
     *
     * @param string $method Method name within the current object.
     *
     * @return string The documentation.
     */
    public function getUsage($method)
    {
        $method = ltrim(substr($method, strpos($method, '::')), ':');

        $docComment = $this->_getDocCommentByMethod($method);
        $firstDot   = strpos($docComment, '.') + 1;
        $endNode    = strpos($docComment, '* @');
        if (!$endNode)
        {
            $endNode = strrpos($docComment, '*/');
        }
        $docComment = substr($docComment, $firstDot, $endNode - $firstDot);
        $docComment = preg_replace('@[\n\r]?\s*\*\s@', "\n", $docComment);
        $docComment = trim($docComment);

        return $docComment;
    }


    /**
     * Get doc-comment in the current object for a method.
     *
     * @param $method
     *
     * @return string
     */
    protected function _getDocCommentByMethod($method)
    {
        $methodReflection = new ReflectionMethod(get_class($this), $method);
        $docComment       = $methodReflection->getDocComment();

        return $docComment;
    }
}

/**
 * Class DelegateCommand.
 *
 * @category  LeMike_Devmode
 * @package   LeMike_Devmode\Shell
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/${PROJECT_NAME}/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/${PROJECT_NAME}
 * @since     0.4.0
 */
abstract class DelegateCommand extends AbstractCommand
{
    /** @var bool Whether mage is loaded (true) or not (default: false). */
    public static $_mageLoaded = false;


    /**
     * Delegate this command.
     *
     * @return void
     */
    public function __invoke()
    {
        $this->delegate();
    }


    /**
     * Match a string against a filter.
     *
     * @param        $string
     * @param string $filter
     *
     * @return int
     */
    public function _filterMatch($string, $filter = '.*')
    {
        $filter = str_replace('@', '\@', $filter);

        return preg_match('@' . $filter . '@is', $string);
    }


    /**
     * Load Magento.
     *
     * @return void
     */
    public function _loadMagento()
    {
        if (!self::$_mageLoaded)
        {
            $load = new DevMode_Load_Magento();
            $load->run();
            self::$_mageLoaded = true;
        }
    }


    /**
     * Run this command.
     *
     * @throws Exception
     * @return mixed
     */
    public function delegate()
    {
        $arguments = $this->getParameter()->getArguments();
        if (empty($arguments))
        {
            $arguments = $this->getParameter()->getCommands();
        }

        $theArgument = array_shift($arguments);

        $classSegment  = ucfirst($theArgument);
        $thisClassName = get_class($this);

        $folder   = str_replace('_', '/', $thisClassName);
        $fileName = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $folder
                    . DIRECTORY_SEPARATOR . $classSegment . '.php';

        if (!file_exists($fileName) || !$classSegment)
        {
            $this->execute();
        }
        else
        {
            include_once $fileName;

            $className = get_class($this)
                         . '_' . $classSegment;

            if (!class_exists($className))
            {
                throw new Exception("class $className not found.");
            }

            $this->getParameter()->unsetArgument($theArgument);
            $this->getParameter()->unsetCommand($theArgument);

            /** @var DelegateCommand $instance */
            $instance = new $className($this->getParameter());
            $instance->delegate();
        }
    }


    /**
     * Run this command.
     *
     * Searches the action and calls it.
     *
     * @return mixed|void
     */
    public function execute()
    {
        echo PHP_EOL;

        $current = current($this->getParameter()->getArguments());
        if (!$current)
        {
            $current = current($this->getParameter()->getCommands());
        }

        if (!$current)
        {
            $current = 'index';
        }
        $action = $current . 'Action';

        if (method_exists($this, $action))
        {
            $this->getParameter()->unsetArgument($current);
            $this->getParameter()->unsetCommand($current);

            if ($this->getParameter()->hasOption('help')
                || current($this->getParameter()->getArguments()) == 'help'
            )
            {
                echo $this->getMethodHelp($action);
            }
            else
            {
                $this->$action();
            }
        }
        else
        {
            $this->helpAction();
        }

        echo PHP_EOL;
    }


    /**
     * Show help for a method.
     *
     * @param $actionName
     *
     * @return string
     */
    public function getMethodHelp($actionName)
    {
        if (false !== ($colon = strpos($actionName, '::')))
        { // it's class::method: split
            $actionName = substr($actionName, $colon + 2);
        }

        $reflectObject = new ReflectionObject($this);

        $method = $reflectObject->getMethod($actionName);

        if (!$method)
        {
            return 'Unknown method "' . $actionName . '"!';
        }

        $docComment = $method->getDocComment();

        $docComment = preg_replace('/[^\.]*\.[\*\s]*/is', '', $docComment, 1);
        $docComment = preg_replace('/\n[\s]*\*\s/is', "\n", $docComment);
        $docComment = preg_replace('/\*\s@[\w].*/is', "\n", $docComment, 1);
        $docComment = preg_replace('/\n[\s]*\*\s(\w)(.*)/s', "\n\n\$1\$2", $docComment, 1);
        $docComment = trim($docComment);

        if (substr($docComment, 0, 1) == '@')
        { // doc tag at beginning: this has no detailed help
            return "Sorry! No help available.";
        }

        return $docComment;
    }


    /**
     * Get all sub-commands.
     *
     * Parses the directory for other classes and returns them.
     *
     * @return array [MODULE_NAME => SHORT_DOCUMENTATION]
     */
    public function getSubModules()
    {
        $thisClassName = get_class($this);
        $baseFolder    = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        $baseFolder .= str_replace('_', '/', $thisClassName) . DIRECTORY_SEPARATOR;

        $modules = array();
        foreach (glob($baseFolder . '*.php') as $classFile)
        {
            if (strpos($classFile, '/Abstract') !== false)
            { // abstract file: skip this one
                continue;
            }

            $subModuleName      = basename($classFile, '.php');
            $subModuleClassName = $thisClassName . '_' . $subModuleName;

            include_once $classFile;
            $reflectClass            = new ReflectionClass($subModuleClassName);
            $docComment              =
                $this->_filterDocCommentHeader($reflectClass->getDocComment());
            $modules[$subModuleName] = $docComment;
        }

        return $modules;
    }


    /**
     * Shows this help.
     *
     * @return void
     */
    public function helpAction()
    {
        echo "Usage: ";
        $className = get_class($this);
        $methodSet = get_class_methods($className);
        echo
            str_replace(
                'devmode ',
                'php devmode.php ',
                strtolower(str_replace('_', ' ', $className))
            ) . " {module|action}" . PHP_EOL;
        echo PHP_EOL;

        $moduleSet = $this->getSubModules();
        $prefix    = '    ';

        if (count($moduleSet) > 0)
        {
            echo "Modules: " . PHP_EOL;
            foreach ($moduleSet as $moduleName => $docComment)
            {
                echo $prefix . strtolower($moduleName) . ' - ' . $docComment;
                echo PHP_EOL;
            }
        }

        echo "Actions: " . PHP_EOL;
        foreach ($methodSet as $method)
        {
            $action = substr($method, 0, strpos($method, 'Action'));
            if ($action != '' && $action != 'index')
            {
                echo "    " . $action;

                $docComment = $this->_getDocCommentByMethod($method);
                $docComment = ltrim($docComment, "/*\n\r ");
                $docComment = substr($docComment, 0, strpos($docComment, "\n"));
                if ($docComment)
                {
                    echo ' - ' . $docComment;
                }

                echo PHP_EOL;
            }
        }
    }


    /**
     * Get the short header of a doc-comment.
     *
     * @param string $docComment Complete doc-comment of a class.
     *
     * @return string Only the header / first sentence.
     */
    protected function _filterDocCommentHeader($docComment)
    {
        $_header = ltrim($docComment, "/*\n\r ");
        $_header = substr($_header, 0, strpos($_header, '.') + 1);

        return $_header;
    }
}
