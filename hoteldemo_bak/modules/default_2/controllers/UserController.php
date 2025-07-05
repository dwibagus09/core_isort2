<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class UserController extends actionControllerBase
{

	public function loginAction()
	{
		$username = $this->_request->getParam('username');
		$password = $this->_request->getParam('password');
		$shift = $this->_request->getParam('shift');
		$ruri = $this->_request->getParam('ruri');
		
		if($this->_request->getParam('rememberme') == 1)
		{
		    // make the session stays for 3 days
			Zend_Session::rememberMe(3*86400);
		}

		// Set up the authentication adapter
		require_once($this->modelDir . '/falconAuthAdapter.php');
		$authAdapter = new Falcon_Auth_Adapter($username, $password);
		
		// Attempt authentication. falconAuthAdapter stores successful result in session.
		$result = $this->auth->authenticate($authAdapter);
	    
		if ( !$result->isValid() )
		{
			// Authentication failed
			foreach ($result->getMessages() as $message)
			{
				$message = "$message\n";
			}
			$result = array(FALSE, $message);
		}
		else
		{
			$result = array(TRUE, $result);
		}

		
		$logsTable = $this->loadModel('logs');

		if ( $result[0] == TRUE )
		{
			$ident = $result[1]->getIdentity();
			$ident['shift'] = $shift;
			if(!empty($this->session->httpReferer)) {
				$url = $this->session->httpReferer;
				unset($this->session->httpReferer);
			}			

			$pos = strpos($ruri, "listissues");
			$pos2 = strpos($ruri, "solvedissues");
			$pos3 = strpos($ruri, "/hod/viewdetail");
			$pos4 = strpos($ruri, "viewchiefdetailreport");
			$pos5 = strpos($ruri, "viewdetailreport");
			if(!empty($ruri) && ($pos !== false || $pos2 !== false || $pos3 !== false || $pos4 !== false || $pos5 !== false)) $url = $ruri;
			else $url = "";

			$userlogTable = $this->loadModel('userlog');
			$userlogTable->insertUserLog($ident);

			$logData['user_id'] = intval($ident['user_id']);
			$logData['action'] = "Login";
			$logData['data'] = "Login successful";
			$logsTable->insertLogs($logData);

			$this->_response->setRedirect($this->baseUrl.$url);
			$this->_response->sendResponse();
			exit();
		}
		else
		{
			$this->view->error = $result[1];

			$logData['user_id'] = intval($ident['user_id']);
			$logData['action'] = "Login";
			$logData['data'] = "Login failed for username:".$username." reason: ".$result[1];
			$logsTable->insertLogs($logData);
			
			$this->view->ruri = $ruri;

			echo $this->view->render('login.php');
			exit;
		}		  	
	}
	
	public function logoutAction()
	{		
		$redirect = $this->_request->getParam('ref');
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::expireSessionCookie();
		Zend_Session::destroy(TRUE,TRUE);
		
		$this->getResponse()->setRedirect($this->config->general->url);
		$this->getResponse()->sendResponse();
		exit;
	}
	
	public function changepasswordAction()
	{		
		$this->view->message = $this->_request->getParam('msg');
		$this->renderTemplate('changepassword.tpl');
	}
	
	public function updatepasswordAction()
	{		
		$params = $this->_getAllParams();
		
		if($params['new_passwd'] != $params['confirm_passwd'])
		{
			$this->getResponse()->setRedirect($this->config->general->url."/default/user/changepassword/msg/".urlencode("New Password and Confirm Password should be the same"));
		}
		elseif(md5($params['old_passwd']) != $this->ident['password'])
		{
			$this->getResponse()->setRedirect($this->config->general->url."/default/user/changepassword/msg/".urlencode("Old Password does not match."));
		}
		else
		{
			Zend_Loader::LoadClass('userClass', $this->modelDir);
			$userClass = new userClass();
			$userClass->updatePassword($this->ident['user_id'], $params['new_passwd']);
			$this->getResponse()->setRedirect($this->config->general->url."/default/user/changepassword/msg/".urlencode("Password successfully changed."));
		}
		
		
		$this->getResponse()->sendResponse();
		exit;
	}
	
	function setsiteidAction()
	{
		$params = $this->_getAllParams();
		
		if(!empty($params['id']))
		{
			$siteTable = $this->loadModel('site');
			$siteTable->setSite($params['id']);
			$this->ident['site_id'] = $params['id'];
		}
		// Redirect to main page
		$this->getResponse()->setRedirect($this->config->paths->url);
		$this->getResponse()->sendResponse();
		exit;

	}
}
?>
