<?php
namespace TYPO3\CMS\SpamChallenger\Service;
/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Fabien Udriot <fabien.udriot@typo3.org>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * A service returning a token for a form.
 */
class SpamChallenge implements \TYPO3\CMS\Core\SingletonInterface {

	/**
	 * Returns a class instance.
	 *
	 * @return \TYPO3\CMS\SpamChallenger\Service\SpamChallenge
	 */
	static public function getInstance() {
		return GeneralUtility::makeInstance('TYPO3\CMS\SpamChallenger\Service\SpamChallenge');
	}

	/**
	 * Generate a form token given a component object
	  	 *
	 * @param object $contentElementIdentifier
	 * @return string
	 */
	public function generate($contentElementIdentifier) {
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
	protected function getTimeStamp() {
		return $GLOBALS['_SERVER']['REQUEST_TIME'];
	}

}
?>