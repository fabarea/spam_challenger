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
	 * @var boolean
	 */
	protected $isChallengePassed;

	/**
	 * The main method called by the controller
	 *
	 * @param array $errors Reference to the errors array to store the errors occurred
	 * @return boolean
	 */
	public function validate(&$errors) {
		$this->isChallengePassed = TRUE;

		$contentElementIdentifier = $this->cObj->data['uid'];
		$spamChallenge = SpamChallenge::getInstance()->generate($contentElementIdentifier);
		$challengeParts = explode('_', $spamChallenge);

		$sessionSpamChallenge = $this->getFrontendUser()->getKey('ses', Tx_SpamChallenger_PreProcessor_CreateSpamChallenge::SESSION_KEY);
		$sessionChallengeParts = explode('_', $sessionSpamChallenge);

		// $this->settings['langFiles'][] = 'Add new language file?'
		$timeLimit = $this->settings['timeLimitInSecond'] ? $this->settings['timeLimitInSecond'] : self::TIME_LIMIT_IN_SECOND;
		$submittingTime = $challengeParts[2] - $sessionChallengeParts[2];

		if (empty($sessionSpamChallenge)) {
			$this->setFailChallenge('[FAILED] cookie not set'); // Session was not set
		} elseif ($timeLimit > $submittingTime) {
			$this->setFailChallenge('[FAILED] you were too fast'); // User was too quick...
		} else {
			$this->utilityFuncs->debugMessage('[PASS] spam challenge is OK');
		}

		// It could be some honey-pot values to be checked.
		$honeyPostValues = $this->settings['honeyPotValues.'];
		if (!empty($honeyPostValues)) {
			foreach ($this->settings['honeyPotValues.'] as $fieldName => $value) {

				if (!isset($this->gp[$fieldName])) {
					$this->setFailChallenge(sprintf('[FAILED] honey pot field "%s" was not set. Forgotten field if your template?', $fieldName));
				} elseif ($this->gp[$fieldName] != $value) {
					$this->setFailChallenge(sprintf('[FAILED] honey pot field "%s" does not correspond to expected value "%s"', $fieldName, $value));
				} else {
					$this->setFailChallenge(sprintf('[PASS] honey pot field "%s" is OK', $fieldName, $value));
				}
			}
		}

		if (!$this->isChallengePassed) {
			$errors['spamchallenger'][] = 'challenge';
		}

		return $this->isChallengePassed;
	}

	/**
	 * Returns an instance of the current Frontend User.
	 *
	 * @param
	 * @return $void
	 */
	protected function setFailChallenge($message) {
		$this->isChallengePassed = FALSE;
		$this->utilityFuncs->debugMessage($message);
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