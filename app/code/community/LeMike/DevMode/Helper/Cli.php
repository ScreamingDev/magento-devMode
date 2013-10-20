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
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */

/**
 * Class Config.
 *
 * @category  LeMike_DevMode
 * @package   LeMike\DevMode\Helper
 * @author    Mike Pretzlaw <pretzlaw@gmail.com>
 * @copyright 2013 Mike Pretzlaw
 * @license   http://github.com/sourcerer-mike/mage_devmode/blob/master/License.md BSD 3-Clause ("BSD New")
 * @link      http://github.com/sourcerer-mike/mage_devmode LeMike_DevMode on GitHub
 * @since     0.3.0
 */
class LeMike_DevMode_Helper_Cli extends LeMike_DevMode_Helper_Abstract
{
    /**
     * Prompt for user input.
     *
     * @param string $question  Prompt to show.
     * @param array  $answerSet Possible limited answers (clear if anything is possible).
     *
     * @return string
     */
    public function ask($question, $answerSet = array('y', 'n'))
    {
        do
        {
            echo $question;
            $answer = strtolower(trim(fgets(STDIN)));
        } while (!in_array($answer, $answerSet) && !empty($answerSet));

        return $answer;
    }
}
