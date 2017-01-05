<?php
namespace Ecodev\SpamChallenger\Service;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

use TYPO3\CMS\Core\SingletonInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A service returning a token for a form.
 */
class SpamChallenge implements SingletonInterface
{

    /**
     * Returns a class instance.
     *
     * @return SpamChallenge
     */
    static public function getInstance()
    {
        return GeneralUtility::makeInstance(SpamChallenge::class);
    }

    /**
     * Generate a form token given a component object
     *
     * @param object $contentElementIdentifier
     * @return string
     */
    public function generate($contentElementIdentifier)
    {
        return sprintf('formhandler_%s_%s',
            $contentElementIdentifier,
            $this->getTimeStamp()
        );
    }

    /**
     * Return current time stamp.
     *
     * @return int
     */
    protected function getTimeStamp()
    {
        return $GLOBALS['_SERVER']['REQUEST_TIME'];
    }

}