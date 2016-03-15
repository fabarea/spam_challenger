<?php
namespace Ecodev\SpamChallenger\PreProcessor;

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

use Ecodev\SpamChallenger\Service\SpamChallenge;
use Typoheads\Formhandler\PreProcessor\AbstractPreProcessor;

/**
 * This finisher for user auto login after they register
 */
class CreateSpamChallenge extends AbstractPreProcessor {

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

		$this->getFrontendUser()->setKey('ses', self::SESSION_KEY, $spamChallenge);
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