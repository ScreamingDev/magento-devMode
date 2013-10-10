<?php
/**
 * Contains class.
 *
 * PHP version 5
 *
 * Copyright (c) 2013, Mike Pretzlaw
 * All rights reserved.
 *
 * @category  LeMike/DevMode/Dev
 * @package   LeMike/DevMode
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode
 * @since
 */

function _assertPackage($doc)
{
    if (strpos($doc, '@package') === false)
    {
        $doc = preg_replace('/@category([^\n]*)/', "@category\$1\n * @package", $doc, 1);
    }

    return $doc;
}

/**
 * .
 *
 * @param $fileDoc
 * @param $tags
 * @param $padding
 *
 * @return mixed
 */
function _handleDocComment($fileDoc, $tags)
{
    $padding = 0;
    foreach ($tags as $key => $value)
    {
        $padding = max(strlen($key) + 1, $padding);
    }

    $newFileDoc = $fileDoc;
    foreach ($tags as $key => $value)
    {
        $line       = '@' . str_pad($key, $padding) . $value;
        $newFileDoc = preg_replace('/@' . preg_quote($key) . '[^\n]*/i', $line, $newFileDoc);
    }

    return $newFileDoc;
}

$tagSet = explode(PHP_EOL, trim(`git tag`));

$prev          = trim(`git rev-list --max-parents=0 HEAD`); // first commit
$versionToFile = array();
foreach ($tagSet as $version)
{
    echo "git diff --name-only $prev $version" . PHP_EOL;
    $files         = explode(PHP_EOL, trim(`git diff --name-only $prev $version`));
    $versionToFile = array_merge(
        array_combine(
            $files,
            array_fill(
                0,
                count($files),
                $version
            )
        ),
        $versionToFile
    );

    $prev = $version;
}

// iterate files
$recursiveIteratorIterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('..'));

foreach ($recursiveIteratorIterator as $fileInfo)
{
    /** @var SplFileInfo $fileInfo */
    if ($fileInfo->getExtension() != 'php')
    {
        continue;
    }

    $fileName = substr($fileInfo->getPathname(), 3);

    if (!isset($versionToFile[$fileName]))
    {
        echo "Fail on " . $fileName . PHP_EOL;
        continue;
    }

    $contents = file_get_contents($fileInfo->getPathname());

    $strPos = strpos($fileName, 'LeMike');

    if ($strPos !== false)
    {
        $category = str_replace('/', '\\', substr(dirname($fileName), $strPos));
    }
    else
    {
        $category =
            'LeMike\\DevMode\\' .
            str_replace(
                ' ',
                '\\',
                ucwords(str_replace(DIRECTORY_SEPARATOR, ' ', dirname($fileName)))
            );
    }

    if (!isset($versionToFile[$fileName]))
    {
        echo "Failed on " . $fileName . PHP_EOL;
    }

    $tags = array(
        'package'   => $category,
        'category'  => 'LeMike_DevMode',
        'author'    => 'Mike Pretzlaw <pretzlaw@gmail.com>',
        'copyright' => date('Y') . ' Mike Pretzlaw',
        'license'   => 'http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")',
        'link'      => 'http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub',
        'since'     => substr($versionToFile[$fileName], 1),
    );

    $fileDocPos = strpos($contents, "php\n/**");
    if ($fileDocPos !== false)
    {
        $fileDocPos += 4;
        $fileDocPosEnd = strpos($contents, "*/\n") - $fileDocPos + 2;
        $fileDoc       = substr($contents, $fileDocPos, $fileDocPosEnd);
        $newFileDoc    = _assertPackage($fileDoc);
        $newFileDoc    = _handleDocComment($newFileDoc, $tags);
        $contents      = str_replace($fileDoc, $newFileDoc, $contents);
    }

    $classDocPos = strpos($contents, "\n/**\n", max($fileDocPos, $fileDocPosEnd));
    if ($classDocPos !== false)
    {
        $classDocPos += 1;
        $classDocPosEnd = strpos($contents, "*/\n", $classDocPos) - $classDocPos + 2;
        $classDoc       = substr($contents, $classDocPos, $classDocPosEnd);
        $newClassDoc    = _assertPackage($classDoc);
        $newClassDoc    = _handleDocComment($newClassDoc, $tags);
        $contents       = str_replace($classDoc, $newClassDoc, $contents);
    }

    file_put_contents($fileInfo->getPathname(), $contents);
}
