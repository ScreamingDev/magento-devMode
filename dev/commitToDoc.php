<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2014, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike_DevMode
 * @package   commitToDoc.php
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2014 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/LeMike_DevMode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/LeMike_DevMode
 * @since     $VERSION$
 */


$baseDir = $workingDirectory = getcwd();
if (isset($argv[1]))
{
    $baseDir = realpath($argv[1]);
}

if (!$baseDir)
{
    throw new Exception('Please provide a correct directory instead of "' . $argv[1] . '"');
}

chdir($baseDir);


$lastCommit = `git log HEAD^1..HEAD --no-merges --no-color --format=format:%h%n%b --relative`;

$commitBody = explode("\n", $lastCommit);

$shortHash = array_shift($commitBody);

foreach ($commitBody as $line)
{
    preg_match('/([\w_]*)\:\:([\w_]*)\s(.*)/', $line, $grepMatches, 0, PREG_PATTERN_ORDER);

    if (count($grepMatches) != 4)
    {
        continue;
    }

    $line = array_shift($grepMatches);
    $class = array_shift($grepMatches);
    $method = array_shift($grepMatches);
    $comment = array_shift($grepMatches);

    $assertions[$class][$method][] = $comment;
}

$changedFiles = `git log HEAD^1..HEAD --no-merges --no-color --name-only --format=format:'' --relative`;
$changedFiles = trim($changedFiles);
$changedFiles = explode("\n", $changedFiles);

foreach ($changedFiles as $singleFile)
{
    $contents = file_get_contents($singleFile);

    foreach ($assertions as $class => $assertMethods)
    {
        if (strpos($contents, $class) !== false)
        {
            break;
        }
    }

    foreach ($assertMethods as $method => $assertTexts)
    {
        foreach ($assertTexts as $singleText)
        {
            if (strpos($contents, $singleText) === false)
            {
                echo "$class::$method\n$singleText\n\n";
            }
        }
    }
}
