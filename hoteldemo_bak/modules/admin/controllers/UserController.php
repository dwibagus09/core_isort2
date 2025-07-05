<?php

require_once('actionControllerBase.php');
class Admin_UserController extends actionControllerBase
{
	function loginAction()
	{			
		$username = trim($this->getRequest()->getParam('username'));
		$password = trim($this->getRequest()->getParam('password'));
		
		if (empty($username) && empty($password))
		{
			$showform = TRUE;
			$msg = $this->getRequest()->getParam('msg');

			switch ($msg)
			{
				case 'logout':
					$this->view->message = 'Logout Successful';
					break;

				case 'exp':
					$this->view->message = 'Session Expired. Please Login To Continue.';
					break;
			}
		}
		else
		{
			// Set up the authentication adapter
			require_once($this->modelDir. 'adminAuthAdapter.php');
			$authAdapter = new adminAuth($username, $password);

			// Attempt authentication. falconAuthAdapter stores successful result in session.
			$result = $this->auth->authenticate($authAdapter);
			if (!$result->isValid()) //authentication failed
			{
				foreach ($result->getMessages() as $message)
				{
					$this->view->message .= "$message\n";
				}
				$showform = TRUE;
			}

			//redirect to main page
			$this->getResponse()->setRedirect($this->config->paths->url);
			$this->getResponse()->sendResponse();
			exit;
		}

	}
	
	function viewAction()
    {    	
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
		
    	$users = $user->getUsers();
		foreach($users as &$curUser)
		{
			$curUser['role'] = "";
			$userRole = explode(",",$curUser['role_id']);
			foreach($userRole as $usrRole)
			{
				$role = $user->getUserRoleById($usrRole);
				$curUser['role'] .= $role['role'].", ";
			}
			$curUser['role'] = substr($curUser['role'], 0, -2);
		}
		
		$this->view->users = $users;
		
		$this->view->role = $user->getUserRole();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_users.php');
        echo $this->view->render('footer.php');
    }
	
	function viewroleAction()
    {    	
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
		
    	$this->view->role = $user->getUserRole();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_role.php');
        echo $this->view->render('footer.php');
    }
    
	function adduserAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	$params = $this->_getAllParams();
    	$user->addUser($params);
    }
	
	function getuserbyidAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $user->getUserById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteuserAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$user->deleteuser($id);
		}
    }
	
	
	
	/* ############################################################## */
	
	
    /**
     * AJAX / JSON action which delivers a list of all users
     *
     */
    function getadminusersAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $user->getUsers($this->site_id, $params);
    	
    	echo json_encode($response);
    }
    
    function getadminuserbyusernameAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $user->getUsersByUsername($this->site_id, $params);
    	
    	echo json_encode($response);
    }
    
    /**
     * adding a new user
     *
     */
	function addadminuserAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	$params = $this->_getAllParams();

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(7, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
		if(!$readOnly) {
    		$user->addUser($this->site_id, $params);
		}
    }
    
    /**
     * AJAX / JSON action which delivers a row of user for the selected ID.
     *
     */
    function getadminuserbyidAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $user->getUserById($params['adminuserid']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
    
    /**
     * AJAX / JSON action which will modify user
     */
    function setadminuserbyidAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	$params = $this->_getAllParams();
    	
    	$user->updateUser($params);
    }
    
    /**
     * 
     * Remove selected Users
     *
     */
	
    
    function getadminusermodulesAction()
    {    	
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
		
    	$response['rows'] = $user->getUserModules($this->site_id, $this->getRequest()->getParam("adminuserid"));
    	for ($idx = 0; $idx < count($response["rows"]); $idx++) {
    		$response["rows"][$idx]["is_view"] = ($response["rows"][$idx]["privilege"] & 1)?true:false;
    		$response["rows"][$idx]["is_readonly"] = ($response["rows"][$idx]["privilege"] & 2)?true:false;
    	}
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function setadminusermodulesAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	$params = $this->_getAllParams();
    	
    	$ident = $this->auth->getIdentity();
		$this->view->role = $ident['role'];

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(7, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
    	if (!$readOnly) $user->saveUserModules($params);
    	
    	self::getadminusermodulesAction();
    }

	/**
     * Action which will validate user credentials and login a user.
     *
     */
	
	
	function indexAction()
    {    	
    	set_time_limit(7200);
    	
    	$ident = $this->auth->getIdentity();
		$this->view->role = $ident['role'];

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(7, $this->ident["userid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('users.php');
        echo $this->view->render('footer.php');
    }

	/**
	 * Action which will immediately destroy the user's session and log them out.
	 *
	 */
	function logoutAction()
	{

		Zend_Auth::getInstance()->clearIdentity();
		Zend_Session::expireSessionCookie();
		Zend_Session::destroy(TRUE,TRUE);
		$this->view->message = "Logout Successful";
		$params = $this->_getAllParams();
		$this->view->expired = $params['expired'];
		echo $this->view->render('login.php');
		exit;
	}
	
	function managenotificationAction() {
    	$ident = $this->auth->getIdentity();
		$this->view->role = $ident['role'];
		
		$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(15, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
		$this->view->readOnly = $readOnly;
		
    	echo $this->view->render('header.php');
    	echo $this->view->render('usernotification_manage.php');
        echo $this->view->render('footer.php');
    }
    
    function getusernotificationsAction() {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();

    	$response['data'] = $user->getUserNotifications($this->site_id);
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function getnotificationsAction() {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();

    	$response['data'] = $user->getNotifications();
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function getnotificationmethodAction() {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();

    	$response['data'] = $user->getNotificationMethod();
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function setusernotificationAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	$params = $this->_getAllParams();

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(7, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
		if(!$readOnly) {
			$users = $user->getUserNotificationById($params['user_notification_id']);
			if(empty($users))
    			$user->addUserNotification($params);
    		else
    			$user->updateUserNotification($params);
		}
    }
    
    function getusernotificationbyidAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $user->getUserNotificationById($params['user_notification_id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
    				$rs['adminuserid'] = $rs['userid'];
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
    
    function deleteusernotificationAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
		$params =  $this->_request->getParam('datas');
		
		$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(7, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
		if(!$readOnly) {
	    	if(!empty($params))
	    	{
		    	$params = explode(",", $params);
		    	foreach ($params as $user_notification_id) {
		    		$user->deleteUserNotification($user_notification_id);
		    	}
	    	}
		}
    }
    
    function logAction() {    	
    	$ident = $this->auth->getIdentity();
		$this->view->role = $ident['role'];

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(42, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('user_logs.php');
        echo $this->view->render('footer.php');
    }
    
    function getlogsAction() {
    	$ident = $this->auth->getIdentity();
		$this->view->role = $ident['role'];

    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(42, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
		$this->view->readOnly = $readOnly;
		
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$startDate = date("Y-m-d H:i:s", mktime(0,0,0,intval(date("m"))-6, date("d"), date("Y")));
    	$endDate = date("Y-m-d H:i:s");
    	
    	$params = $this->_getAllParams();
    	$params['start'] = intval($params['start']);
    	$params['limit'] = intval($params['limit']);
    	if(empty($params['limit'])) $params['limit'] = 16;
    	
    	$logsTable = new logs(array('db'=>'db'));
    	$logs = $logsTable->getAdapter()->fetchAll("
    	SELECT SQL_CALC_FOUND_ROWS l.log_id, l.user_id, au.adminusername, l.log_date, l.from_ip, l.description, l.site_id
    	FROM logs l
    	LEFT JOIN adminusers au ON au.adminuserid=l.user_id
    	WHERE l.log_date BETWEEN '{$startDate}' AND '{$endDate}' AND l.site_id='{$this->site_id}'
    	LIMIT {$params['start']}, {$params['limit']}
    	");
    	
    	$response['success'] = true;
    	$response['data'] = $logs;
    	$response['total'] = $logsTable->getAdapter()->fetchOne("SELECT FOUND_ROWS()");
    	echo json_encode($response);
    }
    
    function migrateadminusersAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(43, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('admin_users_migrate.php');
        echo $this->view->render('footer.php');
    }
    
    function getadminusersliveAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $user->getUsers($this->site_id, $params, true);
    	
    	echo json_encode($response);
    }
    
    function doadminusersmigrateAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	$readOnly = FALSE;
    	if($this->ident["role"] != "apt") {
	    	$privilege = $user->getUserPrivilige(43, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
    	if (!$readOnly && $this->site_id > 0 ) {
    		$dataKeys = json_decode($params['data'], true);
			$ids="";
			foreach ($dataKeys as $id)
				$ids = $ids.$id['adminuserid'].",";
			$params['ids'] = substr($ids, 0, -1);
    		$arrAdminUsers = $user->getUsers($this->site_id, $params);
	    	
    		foreach ($arrAdminUsers as &$adminUsers)	
    		{
    			$adminUsers['admin_user_module'] = $user->getUserModulesByUserId($adminUsers['adminuserid']);
    		}
    		
    		if ( $params['logic'] == 'migrate' ) {
    			$retVal = $user->migrateAdminUsers($this->site_id, $arrAdminUsers, $params);    			
    		}
    		elseif ( $params['logic'] == 'copy' ) {
    			$retVal = $user->migrateCopyAdminUsers($this->site_id, $arrAdminUsers, $params);
    		}
    	}
    	return self::getadminusersliveAction();
    }
    
    function migrateusernotificationAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(44, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('usernotification_migrate.php');
        echo $this->view->render('footer.php');
    }
    
    function getusernotificationliveAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $user->getUserNotifications($this->site_id, true);
    	
    	echo json_encode($response);
    }
    
    function dousernotificationmigrateAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
    	
    	$params = $this->_getAllParams();
    	$readOnly = FALSE;
    	if($this->ident["role"] != "apt") {
	    	$privilege = $user->getUserPrivilige(44, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
    	if (!$readOnly && $this->site_id > 0 ) {
    		$dataKeys = json_decode($params['data'], true);
			$ids="";
			foreach ($dataKeys as $id)
				$ids = $ids.$id['user_notification_id'].",";
			$ids = substr($ids, 0, -1);
    		$arrUserNotification = $user->getUserNotificationsForMigration($ids);
    		
    		if ( $params['logic'] == 'migrate' ) {
    			foreach ($arrUserNotification as $userNotif)
    			{
    				$dataAdminUser = $user->getUserById($userNotif['userid']);
    				$user->addUserProd($dataAdminUser);
    			}
    			$retVal = $user->migrateUserNotification($arrUserNotification, $params);    			
    		}
    		elseif ( $params['logic'] == 'copy' ) {
    			$retVal = $user->migrateCopyUserNotification($this->site_id, $arrUserNotification, $params);
    		}
    	}
    	return self::getusernotificationliveAction();
    }
}
?>
