<?php
require_once('actionControllerBase.php'); 
class ErrorController extends actionControllerBase
{	
	public function errorAction()
	{
		$e = $this->_getParam('error_handler');
	
		switch ($e->type)
		{
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
			case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
				// 404 error -- controller or action not found
				$this->logError($e);
				/*$this->getResponse()->setRedirect($this->config->paths->url . '/static/404.php', 303);
				$this->getResponse()->sendResponse();
				exit;*/

				/*$this->mailError($e);*/
			
				$this->renderTemplate('404.tpl');
				break;

			/*case Falcon_Content_Exception::EXCEPTION_INVALID_PARAMETERS:
				echo "invalid parameters"; exit();
				$this->logError($e);
				break;*/
			

			default:
				//Handle user defined exceptions
				switch($e->exception->getCode())
				{
					case Falcon_Content_Exception::EXCEPTION_INVALID_PARAMETERS:
						$this->logError($e);
						//$this->getResponse()->setRedirect($this->config->paths->url . '/static/error.php', 303);
						//$this->getResponse()->sendResponse();
						//$this->mailError($e);
						$this->renderTemplate('303.tpl');
						break;
                        
					default:

						$this->logError($e);
						$this->mailError($e);

						//$this->getResponse()->setRedirect($this->config->paths->url . '/static/error.php', 303);
						//$this->getResponse()->sendResponse();
						
						$this->renderTemplate('303.tpl');
						break;
				}
		}


	}

	private function logError($e)
	{	
		$exception = $e->exception;
		//Log the error
		$this->dbLogger->setEventItem('site_id',$this->siteid);
		$this->dbLogger->setEventItem('type',$e->type);
		$this->dbLogger->setEventItem('code',$exception->getCode());
		$this->dbLogger->setEventItem('location',"On line: " . $exception->getLine() . " in file " . $exception->getFile() . "\r\n");
		$this->dbLogger->setEventItem('trace',$e['exception']);
		$this->dbLogger->setEventItem('request_uri',$this->_request->getRequestURI());
		$this->dbLogger->setEventItem('remote_addr',$_SERVER['REMOTE_ADDR']);
		$this->dbLogger->setEventItem('referer',$_SERVER['HTTP_REFERER']);
		$this->dbLogger->info($exception->getMessage(),$exception->getCode());
	}

	private function mailError($e)
	{
		require_once 'Zend/Mail.php';
		//require_once 'Zend/Mail/Transport/Smtp.php';

		$exception = $e->exception;
		$msg = 'Type: '.$e->type . "\r\n\r\n";
		//$msg .= 'Module: '.$module . "\r\n\r\n";
		//$msg .= print_r($e);
		$msg .= $e['exception'] . "\r\n\r\n";
		$msg .= "Site : ".$this->siteName." - ".$this->config->general->url."\r\n";      
		$msg .= "Error Code: " . $exception->getCode() . "\r\n";
		$msg .= "Error Message: " . $exception->getMessage() . "\r\n";
		$msg .= "On line: " . $exception->getLine() . " in file " . $exception->getFile() . "\r\n";
		//$msg .= "SESSION ID: " . Zend_Session::getId() . PHP_EOL;
		$msg .= "REQUEST URI: " . $this->_request->getRequestURI() . PHP_EOL;
		$msg .= "REQUEST METHOD: " .$_SERVER['REQUEST_METHOD']. PHP_EOL;
		$msg .= "USER AGENT: " . $_SERVER['HTTP_USER_AGENT'] . PHP_EOL;
		$msg .= "REFERER: " . $_SERVER['HTTP_REFERER'] . PHP_EOL;
		$msg .= "REMOTE ADDRESS: " . $_SERVER['REMOTE_ADDR'] .PHP_EOL;
		//$msg .= "Session Dump: \r\n";
		//$msg .= print_r($_SESSION, true) . PHP_EOL;



		/*$config = array('auth' => 'login',
		'username' => 'mailer',
		'password' => 'Mail123');

		$transport = new Zend_Mail_Transport_Smtp('mx1.advpubtech.com', $config);*/

		$config = array('ssl' => 'ssl',
                'auth'          => 'login',
                'username' => 'AKIAI6LHTVUUHB4SSNWA',
                'password' => 'ApWkhDg1XUilj7oYRPNT3Bl3XXgcnU9nl3eFMVUARue5',
        );
        $transport = new Zend_Mail_Transport_Smtp('email-smtp.us-east-1.amazonaws.com', $config);
		
		$mail = new Zend_Mail();
		$mail->setBodyText($msg);
		$mail->setFrom('onlineads@apthosts.com', 'CMS Site Error');
        $mail->addTo('just4u209@gmail.com', 'Emma');
        $mail->addTo('jhoni_chen@yahoo.com', 'Jhoni Chen');
		$mail->setSubject('CMS Site Error');
		$mail->send($transport);
	}
}
?>
