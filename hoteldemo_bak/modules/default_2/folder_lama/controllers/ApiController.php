<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class ApiController extends actionControllerBase
{

	public function loginAction()
	{	
		//$json = file_get_contents('php://input');
		$dataJson = $this->_request->getRawBody();

		$data = json_decode($dataJson, true);
		
		$username = $data['username'];
		$password = $data['password'];
		
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
			if(!empty($ruri) && ($pos !== false || $pos2 !== false)) $url = $ruri;
			else $url = "";

			$userlogTable = $this->loadModel('userlog');
			$userlogTable->insertUserLog($ident);

			echo json_encode("ok");
		}
		else
		{
			echo json_encode($result[1]);
		}	 
	}
	
	public function logoutAction()
	{		
		$redirect = $this->_request->getParam('ref');
		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::expireSessionCookie();
		//Zend_Session::destroy(TRUE,TRUE);
		
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