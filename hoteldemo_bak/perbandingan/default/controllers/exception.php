<?php

//Zend_Exception
require_once 'Zend/Exception.php';

class Falcon_Content_Exception extends Zend_Exception
{
	//protected $userCode = null;
	const EXCEPTION_INVALID_PARAMETERS='EXCEPTION_INVALID_PARAMETERS';

	public function __construct($message, $code = null)
	{
		$this->code = $code;
		parent::__construct($message);
	}

}
