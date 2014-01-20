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
class Tx_SpamChallenger_Validator_ValidateSpamChallenge extends Tx_Formhandler_AbstractValidator {

	const TIME_LIMIT_IN_SECOND = 5;

	/**
	 * The main method called by the controller
	 *
	 * @param array $errors Reference to the errors array to store the errors occurred
	 * @return boolean
	 */
	public function validate(&$errors) {
		$isChallengePassed = TRUE;

		$contentElementIdentifier = $this->cObj->data['uid'];
		$spamChallenge = SpamChallenge::getInstance()->generate($contentElementIdentifier);
		$challengeParts = explode('_', $spamChallenge);

		$sessionSpamChallenge = $this->getFrontendUser()->getKey('ses', Tx_SpamChallenger_PreProcessor_CreateSpamChallenge::SESSION_KEY);
		$sessionChallengeParts = explode('_', $sessionSpamChallenge);

		// $this->settings['langFiles'][] = 'Add new language file?'
		$timeLimit = $this->settings['timeLimitInSecond'] ? $this->settings['timeLimitInSecond'] : self::TIME_LIMIT_IN_SECOND;
		$submittingTime = $challengeParts[2] - $sessionChallengeParts[2];

		if (empty($sessionSpamChallenge)) {
			$isChallengePassed = FALSE; // Session was not set
			$errors['spamchallenger'][] = 'challenge';
			$this->utilityFuncs->debugMessage('[FAILED] spam challenge: cookie not set');
		} elseif ($timeLimit > $submittingTime) {
			$isChallengePassed = FALSE; // User was too quick
			$errors['spamchallenger'][] = 'challenge';
			$this->utilityFuncs->debugMessage('[FAILED] spam challenge: you were too fast');
		} else {
			$this->utilityFuncs->debugMessage(sprintf('[PASS] spam challenge with token "%s"', $spamChallenge));
		}

		return $isChallengePassed;
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