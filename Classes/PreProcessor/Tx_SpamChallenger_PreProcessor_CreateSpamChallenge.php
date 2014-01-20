<?php
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
use TYPO3\CMS\SpamChallenger\Service\SpamChallenge;

/**
 * This finisher for user auto login after they register
 */
class Tx_SpamChallenger_PreProcessor_CreateSpamChallenge extends Tx_Formhandler_AbstractPreProcessor {

	const SESSION_KEY = 'formhandler_spam_challenger';

	/**
	 * The main method called by the controller
	 *
	 * @return array The probably modified GET/POST parameters
	 */
	public function process() {

		$contentElementIdentifier = $this->cObj->data['uid'];
		$spamChallenge = SpamChallenge::getInstance()->generate($contentElementIdentifier);
		$this->utilityFuncs->debugMessage(sprintf('Created a spam protection challenge "%s"', $spamChallenge));

		$this->getFrontendUser()->setKey('ses', Tx_SpamChallenger_PreProcessor_CreateSpamChallenge::SESSION_KEY, $spamChallenge);
		return $this->gp;
	}

	/**
	 * Returns an instance of the current Frontend User.
	 *
	 * @return \TYPO3\CMS\Frontend\Authentication\FrontendUserAuthentication
	 */
	protected function getFrontendUser() {
		return $GLOBALS['TSFE']->fe_user;
	}
}
?>