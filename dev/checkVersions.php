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
 * @package    checkVersions.php
 * @author     Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright  2013 Mike Pretzlaw
 * @license    http://github.com/sourcerer-mike/mage_devMail/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link       http://github.com/sourcerer-mike/mage_devMail
 * @since      $VERSION$
 */

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
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator('..'))
         as
         $fileInfo)
{
    /** @var SplFileInfo $fileInfo */
    if ($fileInfo->getExtension() != 'php')
    {
        continue;
    }

    $fileName = substr($fileInfo->getPathname(), 3);

    if (isset($versionToFile[$fileName]))
    {
        $version  = substr($versionToFile[$fileName], 1);
        $contents = file_get_contents($fileInfo->getPathname());

        preg_match_all('/\*\s*\@since\s*(\d{1,}\.\d{1,}\.\d{1,})/', $contents, $matches);

        if (!isset($matches[1]))
        {
            echo "Need $version in $fileName ." . PHP_EOL;
            continue;
        }

        foreach ($matches[1] as $writtenVersion)
        {
            if ($writtenVersion != $version)
            {
                echo "Need $version in $fileName ." . PHP_EOL;
                continue;
            }
        }
    }
}
