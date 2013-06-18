<?php

/**
 * MobileAPIValidation.class.php
 * Description:
 *
 */

class QuantifyMeFrameworkValidation
{
	private $_validate;
	private $_logger;
	private $_errorCode = '';
	private $_errors = array();
	private $_friendlyError = '';
	private $_errorCount = 0;

	public function __construct($logger)
	{
		//setup for log entries
		$this->_logger = $logger;

		$this->_validate = new FrameworkValidation();
	}

	public function validateAPICommon()
	{
		$_REQUEST['key'] = $this->_validate->sanitizeAPIKey($_REQUEST['key']);
		if (isset($_REQUEST['user'])) $_REQUEST['user'] = $this->_validate->sanitizeUUID($_REQUEST['user']);
		if (isset($_REQUEST['appVersion'])) $_REQUEST['appVersion'] = $this->_validate->sanitizeFloat($_REQUEST['appVersion']);
		if (isset($_REQUEST['device'])) $_REQUEST['device'] = $this->_validate->sanitizeTextWithSpace($_REQUEST['device']);
		if (isset($_REQUEST['machine'])) $_REQUEST['machine'] = $this->_validate->sanitizeMachineName($_REQUEST['machine']);
		if (isset($_REQUEST['osVersion'])) $_REQUEST['osVersion'] = $this->_validate->sanitizeFloat($_REQUEST['osVersion']);
		$this->validateKey(TRUE);
		$this->validateUser(FALSE);
		$this->validateAppVersion(FALSE);
		$this->validateDevice(FALSE);
		$this->validateMachine(FALSE);
		$this->validateOSVersion(FALSE);
	}

	public function validateCreateCategory()
	{
		if (isset($_REQUEST['name'])) $_REQUEST['name'] = $this->_validate->sanitizeAlphanumsWithSpaceAndPunctuation($_REQUEST['name']);
		$this->validateAlphanumsWithSpaceAndPunctuation(TRUE);
	}

	public function validateCreateAction()
	{
		if (isset($_REQUEST['name'])) $_REQUEST['name'] = $this->_validate->sanitizeAlphanumsWithSpaceAndPunctuation($_REQUEST['name']);
		if (isset($_REQUEST['category'])) $_REQUEST['category'] = $this->_validate->sanitizeUUID($_REQUEST['category']);
		$this->validateAlphanumsWithSpaceAndPunctuation(TRUE);
		$this->validateCategory(TRUE);
	}

	public function validateGetActions()
	{
		if (isset($_REQUEST['category'])) $_REQUEST['category'] = $this->_validate->sanitizeUUID($_REQUEST['category']);
		$this->validateCategory(TRUE);
	}

	public function validateCreateRecord()
	{
		if (isset($_REQUEST['category'])) $_REQUEST['category'] = $this->_validate->sanitizeUUID($_REQUEST['category']);
		if (isset($_REQUEST['action'])) $_REQUEST['action'] = $this->_validate->sanitizeUUID($_REQUEST['action']);
		if (isset($_REQUEST['detail'])) $_REQUEST['detail'] = $this->_validate->sanitizeSentence($_REQUEST['detail']);
		if (isset($_REQUEST['description'])) $_REQUEST['description'] = $this->_validate->sanitizeSentence($_REQUEST['description']);
		$this->validateUser(TRUE);
		$this->validateCategory(TRUE);
		$this->validateAction(TRUE);
		$this->validateDetail(FALSE);
		$this->validateDescription(FALSE);
	}

	private function validateKey($required) {
		if (isset($_REQUEST['key']) && !empty($_REQUEST['key'])) {
			$this->_logger->debug('Checking API Key: ' . $_REQUEST['key']);
			$returnError = $this->_validate->validateAPIKey($_REQUEST['key']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'API Key', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'API Key', '');
		}
	}

	private function validateUser($required) {
		if (isset($_REQUEST['user']) && !empty($_REQUEST['user'])) {
			$this->_logger->debug('Checking user: ' . $_REQUEST['user']);
			$returnError = $this->_validate->validateUUID($_REQUEST['user']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'user', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'user', '');
		}
	}

	private function validateAppVersion($required) {
		if (isset($_REQUEST['appVersion']) && !empty($_REQUEST['appVersion'])) {
			$this->_logger->debug('Checking appVersion: ' . $_REQUEST['appVersion']);
			$returnError = $this->_validate->validateVersionNumber($_REQUEST['appVersion']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'appVersion', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'appVersion', '');
		}
	}

	private function validateDevice($required) {
		if (isset($_REQUEST['device']) && !empty($_REQUEST['device'])) {
			$this->_logger->debug('Checking device: ' . $_REQUEST['device']);
			$returnError = $this->_validate->validateTextWithSpace($_REQUEST['device']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'device', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'device', '');
		}
	}

	private function validateMachine($required) {
		if (isset($_REQUEST['machine']) && !empty($_REQUEST['machine'])) {
			$this->_logger->debug('Checking machine: ' . $_REQUEST['machine']);
			$returnError = $this->_validate->validateMachineName($_REQUEST['machine']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'machine', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'machine', '');
		}
	}

	private function validateOSVersion($required) {
		if (isset($_REQUEST['osVersion']) && !empty($_REQUEST['osVersion'])) {
			$this->_logger->debug('Checking osVersion: ' . $_REQUEST['osVersion']);
			$returnError = $this->_validate->validateVersionNumber($_REQUEST['osVersion']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'osVersion', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'osVersion', '');
		}
	}

	private function validateAlphanumsWithSpaceAndPunctuation($required) {
		if (isset($_REQUEST['name']) && !empty($_REQUEST['name'])) {
			$this->_logger->debug('Checking name: ' . $_REQUEST['name']);
			$returnError = $this->_validate->validateAlphanumsWithSpaceAndPunctuation($_REQUEST['name']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'name', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'name', '');
		}
	}

	private function validateCategory($required) {
		if (isset($_REQUEST['category']) && !empty($_REQUEST['category'])) {
			$this->_logger->debug('Checking category: ' . $_REQUEST['category']);
			$returnError = $this->_validate->validateUUID($_REQUEST['category']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'category', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'category', '');
		}
	}

	private function validateAction($required) {
		if (isset($_REQUEST['action']) && !empty($_REQUEST['action'])) {
			$this->_logger->debug('Checking action: ' . $_REQUEST['action']);
			$returnError = $this->_validate->validateUUID($_REQUEST['action']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'action', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'action', '');
		}
	}

	private function validateDetail($required) {
		if (isset($_REQUEST['detail']) && !empty($_REQUEST['detail'])) {
			$this->_logger->debug('Checking detail: ' . $_REQUEST['detail']);
			$returnError = $this->_validate->validateSentence($_REQUEST['detail']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'detail', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'detail', '');
		}
	}

	private function validateDescription($required) {
		if (isset($_REQUEST['description']) && !empty($_REQUEST['description'])) {
			$this->_logger->debug('Checking description: ' . $_REQUEST['description']);
			$returnError = $this->_validate->validateSentence($_REQUEST['description']);
			if (!empty($returnError)) $this->reportVariableErrors('invalid', 'description', $returnError);
		} else if ($required === TRUE) {
			$this->reportVariableErrors('missing', 'description', '');
		}
	}

	private function reportVariableErrors($type, $variable, $returnError) {
		if ($type === 'invalid') {
			$this->_errorCode = 'invalidParameter';
			$this->_errors[] = $returnError . ' value for ' . $variable . ' (' . $_REQUEST[$variable] . ')';
			$this->_friendlyError = 'Invalid value or format for one of the submitted parameters.';
			$this->_errorCount++;
		} else if ($type === 'missing') {
			$this->_errorCode = 'missingParameter';
			$this->_errors[] = 'Required parameter ' . $variable . ' is missing from request.';
			$this->_friendlyError = 'A required parameter is missing from this request.';
			$this->_errorCount++;
		}
	}

	public function getErrorCode() {
		return $this->_errorCode;
	}

	public function getErrors() {
		return $this->_errors;
	}

	public function getFriendlyError() {
		return $this->_friendlyError;
	}

	public function getErrorCount() {
		return $this->_errorCount;
	}
}
