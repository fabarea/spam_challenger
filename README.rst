Spam Challenger
================================

Spam Challenger is a TYPO3 CMS extension connected with `Formhandler`_ aiming to protect a form from spamming
using various technique but without the need of a captcha.

`Formhandler`_ is a flexible extension for handling form in TYPO3 CMS. It is a swiss army knife for all kinds of mailforms.

It will basically generate a challenge token stored in the Frontend Session. Upon submitting the challenge will be verified.
It may be less efficient than the Captcha technique but will still prevent most spams while keeping the form captcha-free.

* Was a User Session created?
* How fast is the User at submitting the form?
* Honey pot fields (todo)


.. _Formhandler: http://www.typo3-formhandler.com/

Configuration
===================

::

	# PRE-PROCESSORS
	preProcessors {
		1 {
			class = Tx_SpamChallenger_PreProcessor_CreateSpamChallenge
			config {

			}
		}
	}

	# VALIDATORS
	validators {
		2 {
			class = Tx_SpamChallenger_Validator_ValidateSpamChallenge
			config {
				# optional time limit in second - default 5 seconds
				timeLimitInSecond = 5
			}
		}
	}


Add in your language file::

	<label index="error_spamchallenger_challenge">Spam challenge did not pass. Hey! Who are you?</label>


Debug Spam Challenger
======================


When setting ``debug = 1`` at the formhandler level, debug messages will be outputted::

	plugin.Tx_Formhandler.settings.predef.foo {

		name = bar

		# Global flag for debugging formhandler
		debug = 1

	}

