<?php
require_once("Zend/Date.php");
require_once('exception.php');

class actionControllerBase extends Zend_Controller_Action 
{
    public $config;		// global configuration
    public $db;			// databse object
    public $session;	// session namespace.     
    public $view;		// view object
    public $auth;		// authentication object
    public $dbLogger;	// log object
    public $modelDir;

    
    public $site_id;		// LEGACY storage of site data (should use the $site array instead)
    public $siteName;	// LEGACY storage of site data (should use the $site array instead)
  	public $perpage = 10;
  	public $areaName;
  	public $environment;
	public $isMobile;
	public $ident;

	public function init()
	{		
	    
		$this->startTime = microtime(true);
		// Retrieve objects needed globally from the registry.
		$this->db		= Zend_Registry::get('db');
		$this->db2		= Zend_Registry::get('db2');
		$this->db3		= Zend_Registry::get('db3');
		$this->auth		= Zend_Registry::get('auth');
		$this->dbLogger	= Zend_Registry::get('dbLogger');
		$this->config	= Zend_Registry::get('config');

		/*if ( strpos(PHP_OS, "WIN") === false ) {
			require_once 'Zend/Cache.php';
			$frontendOptions = array('lifetime' => 3600, 'automatic_serialization' => true);
			$backendOptions = array('servers' => array(array('host' => 'localhost','port' => 11211, 'persistent' => true)));
			$this->cache = Zend_Cache::factory('Output', 'Memcached', $frontendOptions, $backendOptions);
		}*/
		
		if(strpos(" ".$_SERVER['SERVER_NAME'], "admin")) {
    		$this->_response->setRedirect("/admin");
    		$this->_response->sendResponse();
    		exit();
		}
		
		/*if($_SERVER['SERVER_NAME'] == "srt.quantum.net.id" || $_SERVER['SERVER_NAME'] == "srt2.quantum.net.id")
		{
			$this->_response->setRedirect($this->config->paths->url.$_SERVER['REQUEST_URI']);
    		$this->_response->sendResponse();
    		exit();
		}*/
		
    	
		//$config = array('ssl' => 'tls','port' => 25); // Optional port number supplied
        //$transport = new Zend_Mail_Transport_Smtp('smtp.quantum.net.id', $config);
        //Zend_Mail::setDefaultTransport($transport);
        
		// Setup standard variables.
        if ($this->_request->getParam('module') == 'default') {
            $this->module = $this->config->modules->default;
        } else {
            $this->module = $this->_request->getParam('module');
        }

        
        // Determine request type.
		if ($this->_request->isXMLHttpRequest())
		{
			$this->requestType='ajax';
		}
		else
		{
			$this->requestType='page';
		}
        
		$this->modelDir = $this->config->general->modulePath . '/' . $this->module . '/models/';
		
		require_once 'Zend/Session/Namespace.php';
		$this->session = new Zend_Session_Namespace($this->config->session->name);
		Zend_Registry::set('session', $this->session);
				
		// The registry value for the view will be set later, but we must at least generate an empty variable so that the 
		// application can load the site configuration.  It will fail if this variable is not initialized.
		Zend_Registry::set('view', null);
		
    	 
		// Populate the view model.
		require_once('Zend/View.php');
		$this->view = new Zend_View();
		$defaultViewDir = $this->config->general->modulePath . '/'  . $this->module . '/views';
		
		$this->view->customViewDir = $this->customViewDir = $customViewDir	= $this->config->general->basePath . '/sites/default/views';
		$this->view->defaultViewDir = dirname(dirname(__FILE__))."/views";
		$this->view->addScriptPath($defaultViewDir);
		$this->view->addScriptPath($customViewDir);
		Zend_Registry::set('view', $this->view);		
        
		$this->view->cookiename = $this->config->session->name;
		$this->view->module     = $this->module;

        if(empty($this->ident) && empty($this->session->curUser))
        {
			$this->ident = $this->auth->getIdentity();
        }
        elseif(!empty($this->session->curUser))
        {
        	$this->ident = $this->session->curUser;
        }

		$this->view->ident = $this->ident;
		//expired session or bad login			
		if(empty($this->ident) && $this->_request->getParam('controller') != "api" && $this->_request->getParam("action") != "login" && $this->_request->getParam("action") != "issueimage" && $this->_request->getParam('action') != "sendreminder" && $this->_request->getParam('action') != "sendweeklyreminderauto" && $this->_request->getParam('action') != "sendweeklyreviewauto" && $this->_request->getParam('action') != "deletegraph"  && $this->_request->getParam('action') != "cacheopenedissuesomments" && $this->_request->getParam('action') != "savesecuritypdf" && $this->_request->getParam('action') != "savesafetyparkingpdf" && $this->_request->getParam('action') != "savehkpdf" && $this->_request->getParam('action') != "saveommodpdf" && $this->_request->getParam('action') != "sendapprovalreminder" && $this->_request->getParam('action') != "clearmonthlylogs") {
			
			$this->view->ruri = $_SERVER['REQUEST_URI'];
			echo $this->view->render('login.php');
			exit;
		}
		
		if(empty($this->session->curUser)) $this->session->curUser = $this->ident;
        $this->view->curUser = $this->session->curUser;
		$this->site_id = $this->session->curUser['site_id'];
		Zend_Registry::set('site_id', $this->site_id);
		
		/*if(!empty($this->site_id))
		{
			$siteTable = $this->loadModel('site');
			$this->view->site = $site = $siteTable->getSiteById($this->site_id);
		}*/
		$controller = $this->_request->getParam('controller');
		$action = $this->_request->getParam("action");
		$controllerAction = $controller.':'.$action;

		if(is_numeric($action) || substr($requestURI, 0, 8) == "/graphic") $this->render404();		
		
		if(isset($this->config->general->enable_cache) && empty($this->config->general->enable_cache))
			$this->cleancache();
			
		
		$controller = $this->_request->getParam('controller');
		$action = $this->_request->getParam('action');
		$conaction  = $controller.":".$action;

		if($controller != "statistic") $this->session->statPasswd = "";
		
		if(!empty($this->ident['role_ids']))
		{	
			$showIssueFinding = 0;	
			$showSecurity = $showAddSecurity = $showEditSecurity = $showAddChiefSecurity = $showEditChiefSecurity = $showChiefSecurity = $showSecurityActionPlan = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis =  0;
			$showOM = $showOMActionPlan = 0;
			$showActionPlanSetting = $showStatistic = $showReminderReview = $showSiteSelection = $showActionPlanStat = $showCQC = 0;
			$showSecurityKpi = $showSafetyKpi = $showParkingKpi = $showCQC = $allowApproveCQC = $allowUploadCQC = $allowFillChiefRating = 0;
			$showSecurityPivotChart = $showSafetyPivotChart = $showParkingPivotChart = $showCorporateSecurityPivotChart = $showCorporateSafetyPivotChart = $showCorporateParkingPivotChart = 0;
			$showHODMeeting = $showAddHOD = $showHODMeetingAdmin = $approveHODMeeting = $allowDeleteHODMeeting = $showHistoryHOD = 0;
			$showSafetyComittee = $showAddSafetyComittee = $showSafetyComitteeAdmin = $approveSafetyComittee = $allowDeleteSafetyComittee = $showHistorySafetyComittee = 0;
			$viewFeedbackInbox = 0;	
			$showEngineering = $showEngineeringActionPlan = $showEngineeringKpi = 0;
			$addWorkOrder = $viewWorkOrder = $showStartWO = $showProgressWO = $showFinishWO = $showApprovedWO = 0;
			
			if(in_array(1, $this->ident['role_ids'])) // Super User
			{
				$showIssueFinding = $showActionPlanSetting = $showActionPlanStat = $showStatistic = $showSiteSelection = 1;
				$showSecurity = $showAddSecurity = $showEditSecurity = $showAddChiefSecurity = $showChiefSecurity = $showSecurityActionPlan = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis = $showSecurityPivotChart = $showCorporateSecurityPivotChart = 1;
				$showOM = $showOMActionPlan = 1;
				$allowApproveCQC = $allowUploadCQC = $allowFillChiefRating = $showCQC = $allowUploadCQC = 1;
				/*$showHODMeeting = */$showAddHOD = $showHODMeetingAdmin = $allowDeleteHODMeeting = $showHistoryHOD = $showFitOutOnGoing = /*$approveHODMeeting = */1;
				/*$showSafetyComittee = */$showAddSafetyComittee = $showSafetyComitteeAdmin = $allowDeleteSafetyComittee = $showHistorySafetyComittee = /*$approveSafetyComittee = */1;
				$viewFeedbackInbox = 1;
				$showEngineering = $showEngineeringActionPlan = $showEngineeringKpi = 1;
				$addWorkOrder = $viewWorkOrder = $showStartWO = $showProgressWO = $showFinishWO = $showApprovedWO = 1;
			}
			if(in_array(37, $this->ident['role_ids'])) // Teacher
			{
				$showIssueFinding = $showActionPlanSetting = $showActionPlanStat = $showStatistic = $showSiteSelection = 1;
				$showSecurity = $showAddSecurity = $showEditSecurity = $showAddChiefSecurity = $showChiefSecurity = $showSecurityActionPlan = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis = $showSecurityPivotChart = $showCorporateSecurityPivotChart = $showSecurityKpi = 1;
				$showOM = $showOMActionPlan = 1;				
				$allowApproveCQC = $allowUploadCQC = $allowFillChiefRating = $showCQC = $allowUploadCQC = 1;
				/*$showHODMeeting = */$showAddHOD = $showHODMeetingAdmin = $allowDeleteHODMeeting = $showHistoryHOD = $showFitOutOnGoing = /*$approveHODMeeting = */1;
				/*$showSafetyComittee = */$showAddSafetyComittee = $showSafetyComitteeAdmin = $allowDeleteSafetyComittee = $showHistorySafetyComittee = /*$approveSafetyComittee = */1;
				$viewFeedbackInbox = 1;
				$this->view->teacher = $this->teacher = 1;
			}
			if(in_array(2, $this->ident['role_ids'])) // Spv Security
			{
				$showIssueFinding = $showSecurity = $showAddSecurity = $showEditSecurity = 1;
			}
			if(in_array(3, $this->ident['role_ids'])) // Chief Security
			{
				$showIssueFinding = $showSecurity = $showChiefSecurity = $showAddChiefSecurity = $showSecurityActionPlan = $showSecurityPivotChart = $showSecurityKpi /*= $showCQC = $allowUploadCQC*/ = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis =  $showHODMeeting = 1;
			}
			if(in_array(4, $this->ident['role_ids'])) // OM
			{
				$showIssueFinding = $showSecurity = $showChiefSecurity = $showSafety = $showParkingTraffic = $showHousekeeping = $showOM = $showAddOM = $showEditOM = $showMod = $showAddMod = $showSecurityActionPlan = $showSafetyActionPlan = $showParkingActionPlan = $showHousekeepingActionPlan = $showOMActionPlan = $showActionPlanStat = $showSecurityPivotChart = $showSafetyPivotChart = $showParkingPivotChart = $showHODMeeting = 1;
				$showEngineering = $showEngineeringActionPlan = $showEngineeringKpi = 1;
				$viewWorkOrder = $showApprovedWO = 1;
			}
			if(in_array(5, $this->ident['role_ids'])) // General Manager
			{
				$showIssueFinding = $showSecurity = $showChiefSecurity = $showSecurityPivotChart = $showHODMeeting = $approveHODMeeting = $showSafetyComittee = $approveSafetyComittee = 1;
			}
			if(in_array(6, $this->ident['role_ids'])) // Director
			{
				$showIssueFinding = $showSecurity = $showChiefSecurity = $showSafety = $showParkingTraffic = $showHousekeeping = $showOM = $showMod = /*$showBM = */$showSecurityActionPlan = $showSafetyActionPlan = $showParkingActionPlan = $showHousekeepingActionPlan = $showStatistic = $showSiteSelection = $showActionPlanStat = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis = $showCQC = $allowApproveCQC = $showSecurityKpi = $showSafetyKpi = $showParkingKpi =  $showSecurityPivotChart = $showSafetyPivotChart = $showParkingPivotChart =  $showCorporateSecurityPivotChart = $showCorporateSafetyPivotChart = $showCorporateParkingPivotChart = $showSafetyBoard = $uploadSafetyBoard = $showHODMeeting = $showFitOutOnGoing = $showAddHOD = $showAddITMeeting = $showITMeeting = $approveITMeeting = $showSafetyComittee = $showAddSafetyComittee = $approveSafetyComittee = 1;
			}
			if(in_array(13, $this->ident['role_ids'])) // TS Security
			{
				$showIssueFinding = $showSecurity = $showChiefSecurity = $showSecurityActionPlan = $showSiteSelection = /*$showSecurityKpi = */$allowApproveCQC =  $allowFillChiefRating = $showCQC = $addSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis = $showCorporateSecurityPivotChart = $showSecurityPivotChart = $showHODMeeting = $showHODMeetingAdmin = $showAddHOD = 1;
			}
			$showIssueFinding = 1;
			if(in_array(22, $this->ident['role_ids'])) // HRD
			{
				$showSiteSelection = $showSecurityKpi = $showSafetyKpi = $showParkingKpi = $showHODMeeting = 1;
				$showIssueFinding = 0;
			}
			if(in_array(23, $this->ident['role_ids'])) // Fit Out
			{
				$showHODMeeting = $showFitOutOnGoing = 1;
			}
			if(in_array(24, $this->ident['role_ids'])) // Leasing
			{
				$showHODMeeting = 1;
			}
			if(in_array(21, $this->ident['role_ids']) || in_array(31, $this->ident['role_ids'])) // TS Engineering
			{
				$showEngineering = $showEngineeringActionPlan = $showEngineeringKpi = 1;
				$addWorkOrder = $viewWorkOrder = $showStartWO = $showProgressWO = $showFinishWO = 1;
			}
			
			if($this->ident['user_id'] == 1 || $this->ident['user_id'] == 3) {
				$showReminderReview = 1;
				$showSecurityKpi = $showSafetyKpi = $showParkingKpi = 1;
				$this->view->allowUploadActionPlan = 1;
			}

			if($this->ident['user_id'] == 2)
			{
				$this->view->securityRole = $this->securityRole = 1;
			}
			
			$this->view->showIssueFinding = $this->showIssueFinding = $showIssueFinding;
			$this->view->showSecurity = $this->showSecurity = $showSecurity;
			$this->view->showAddSecurity = $this->showAddSecurity = $showAddSecurity;
			$this->view->showEditSecurity = $this->showEditSecurity = $showEditSecurity;
			$this->view->showChiefSecurity = $this->showChiefSecurity = $showChiefSecurity;
			$this->view->showAddChiefSecurity = $this->showAddChiefSecurity = $showAddChiefSecurity;
			$this->view->addSecurityMonthlyAnalysis = $this->addSecurityMonthlyAnalysis = $addSecurityMonthlyAnalysis;
			$this->view->showSecurityMonthlyAnalysis = $this->showSecurityMonthlyAnalysis = $showSecurityMonthlyAnalysis;
			$this->view->showOM = $this->showOM = $showOM;
			$this->view->showSecurityActionPlan = $this->showSecurityActionPlan = $showSecurityActionPlan;
			$this->view->showOMActionPlan = $this->showOMActionPlan = $showOMActionPlan;
			$this->view->showActionPlanSetting = $this->showActionPlanSetting =  $showActionPlanSetting;
			$this->view->showStatistic = $this->showStatistic = $showStatistic;
			$this->view->showSiteSelection = $this->showSiteSelection = $showSiteSelection;
			$this->view->showActionPlanStat = $this->showActionPlanStat = $showActionPlanStat;
			$this->view->showSecurityKpi = $this->showSecurityKpi = $showSecurityKpi;
			$this->view->showCQC = $this->showCQC = $showCQC;
			$this->view->allowApproveCQC = $this->allowApproveCQC = $allowApproveCQC;
			$this->view->allowFillChiefRating = $this->allowFillChiefRating = $allowFillChiefRating;
			$this->view->allowUploadCQC = $this->allowUploadCQC = $allowUploadCQC;
			$this->view->showSecurityPivotChart =$this->showSecurityPivotChart =  $showSecurityPivotChart;
			$this->view->showSafetyPivotChart =$this->showSafetyPivotChart =  $showSafetyPivotChart;
			$this->view->showParkingPivotChart =$this->showParkingPivotChart =  $showParkingPivotChart;
			$this->view->showCorporateSecurityPivotChart =$this->showCorporateSecurityPivotChart =  $showCorporateSecurityPivotChart;
			$this->view->showCorporateSafetyPivotChart =$this->showCorporateSafetyPivotChart =  $showCorporateSafetyPivotChart;
			$this->view->showCorporateParkingPivotChart =$this->showCorporateParkingPivotChart =  $showCorporateParkingPivotChart;
			$this->view->showHODMeetingAdmin = $this->showHODMeetingAdmin = $showHODMeetingAdmin;
			$this->view->showAddHOD = $this->showAddHOD = $showAddHOD;
			$this->view->showHODMeeting = $this->showHODMeeting = $showHODMeeting;
			$this->view->approveHODMeeting = $this->approveHODMeeting = $approveHODMeeting;
			$this->view->allowDeleteHODMeeting = $this->allowDeleteHODMeeting = $allowDeleteHODMeeting;
			$this->view->showHistoryHOD = $this->showHistoryHOD = $showHistoryHOD;		
			$this->view->showSafetyComitteeAdmin = $this->showSafetyComitteeAdmin = $showSafetyComitteeAdmin;
			$this->view->showAddSafetyComittee = $this->showAddSafetyComittee = $showAddSafetyComittee;
			$this->view->showSafetyComittee = $this->showSafetyComittee = $showSafetyComittee;
			$this->view->approveSafetyComittee = $this->approveSafetyComittee = $approveSafetyComittee;
			$this->view->allowDeleteSafetyComittee = $this->allowDeleteSafetyComittee = $allowDeleteSafetyComittee;
			$this->view->showHistorySafetyComittee = $this->showHistorySafetyComittee = $showHistorySafetyComittee;
			$this->view->viewFeedbackInbox = $this->viewFeedbackInbox = $viewFeedbackInbox;
			$this->view->showEngineering = $this->showEngineering = $showEngineering;
			$this->view->showEngineeringActionPlan = $this->showEngineeringActionPlan = $showEngineeringActionPlan;
			$this->view->showEngineeringKpi = $this->showEngineeringKpi = $showEngineeringKpi; 
			$this->view->addWorkOrder = $this->addWorkOrder = $addWorkOrder; 
			$this->view->viewWorkOrder = $this->viewWorkOrder = $viewWorkOrder; 
			$this->view->showStartWO = $this->showStartWO = $showStartWO;
			$this->view->showProgressWO = $this->showProgressWO = $showProgressWO;
			$this->view->showFinishWO = $this->showFinishWO = $showFinishWO; 
			$this->view->showApprovedWO = $this->showApprovedWO = $showApprovedWO;
		}

		/*** Check hide/show add report button ***/
		
		if(!empty($this->ident)) {

			if(date("j") <= 10 || in_array(1, $this->ident['role_ids'])) 
			{
				/*$lastMonth = date("n", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
				$lastMonthYear = date("Y", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));*/

				Zend_Loader::LoadClass('securityClass', $this->modelDir);
				$securityClass = new securityClass();
				$securityMonthlyAnalysis = $securityClass->getSecurityMonthlyAnalysisByMonthYear(date("m"), date("Y"));
				if(!empty($securityMonthlyAnalysis)) $this->view->hideAddSecurityMonthlyAnalysis = 1;
				else $this->view->hideAddSecurityMonthlyAnalysis = 0;

			}
			else {
				$this->view->hideAddSecurityMonthlyAnalysis = 1;
			}

		}
		/*** End check hide/show add report button ***/
        
		if(!empty($this->site_id))
		{
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$this->view->totalAllIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, 0);
		}

		if($this->showSiteSelection == 1)
		{
			$siteClass = $this->loadModel('site');
			$this->view->sitesSelections = $siteClass->getSites();
		}
		
		$category = $this->loadModel('category');
		$this->view->kaizenCategories = $category->getCategories(6);
		$issuetype = $this->loadModel('issuetype');
		$this->view->type = $issuetype->getIssueType('1','6');
		
		$this->view->baseUrl = $this->baseUrl = rtrim($this->config->general->url, '/');
		
		$useragent=$_SERVER['HTTP_USER_AGENT'];
		if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4))) $this->view->isMobile = $this->isMobile = true;
		else $this->view->isMobile = $this->isMobile = false;
		
		if(!empty($_GET['src']) && $_GET['src']=='android') {
			setcookie("isandroid", "1", time()+12*30*24*3600);
		}
		
		if(!empty($_COOKIE["isandroid"]) || (!empty($_GET['src']) && $_GET['src']=='android')) {
			$this->view->isMobile = true;
			$this->view->isAndroid = "1";
		}
	}
	
	function render404() {
		$basePath = dirname(dirname(__FILE__));
		$viewPath = str_replace("\\", "/", $basePath)."/views/";
		$cache404FileName = $viewPath."/temp404.html";
		if(!file_exists($cache404FileName) || (time() - @filemtime($cache404FileName) > 1800)) {
			$header = file_get_contents("http://".$_SERVER['SERVER_NAME']."/template/header");
			$footer = file_get_contents("http://".$_SERVER['SERVER_NAME']."/template/footer");
			$content = $header.$this->view->render("404.tpl").$footer;
			//$content = $this->reformatQuery($content);
			@file_put_contents($cache404FileName, $content);
		}
		else {
			$content = @file_get_contents($cache404FileName);
		}
		echo $content;exit();
	}
	
	
    function loadModel($modelName, $modelType='model')
    {    	
    	require_once($this->modelDir . '/defaultClass.php');
    	$customModelDir  = $this->config->general->basePath . '/sites/';
    	$customModelDir .= $this->siteName . '/' . $this->module . '/models';
      
    	$sfname = $modelName . 'Class.php'; //standard file name
		$sclsname = $modelName . 'Class'; //standard class name
		$cfname = $modelName . 'customClass.php'; //custom file name
		$cclsname = $modelName . 'customClass'; //custom class name
    	if (file_exists($customModelDir . '/' . $cfname)) {
    		//require base class for standard models
    		if ($modelType=='model') {
    			require_once($this->modelDir . $sfname);
    		} 
			ob_start();
    		Zend_Loader::LoadClass($cclsname, $customModelDir);
			ob_end_clean();
            return (new $cclsname());
    	} else {
			ob_start();
            Zend_Loader::LoadClass($sclsname, $this->modelDir);
			ob_end_clean();
    		return (new $sclsname());			
    	}
    } 
    
    
    protected function renderTemplate($template) {
    	$this->endTime = microtime(true);
		$this->view->renderTime = $this->endTime-$this->startTime;
    	$output = $this->view->render('header.tpl');
		$output .= $this->view->render($template);
		$output .= $this->view->render('footer.tpl');
		//$output = $this->reformatQuery($output);
		
		//$output = str_replace(array("\n","\r","  "), " ", $output);
        $output = preg_replace('/\s\s+/', ' ', $output);
		
		//ob_start();
		echo $output;
		//ob_end_flush();
		
    }
	

	public function cleancache() {		
		$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		
	}

	public function exportspvsecuritytopdf($id, $site_id = 0, $download = 0) {
		$params['id'] = $id;
		if(!empty($id))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			if(!empty($site_id))
			{
				$this->site_id = $site_id;
				Zend_Registry::set('site_id', $this->site_id);
				Zend_Loader::LoadClass('siteClass', $this->modelDir);
				$siteClass = new siteClass();
				$curSite = $siteClass->getSiteById($site_id);
				$this->ident['site_fullname'] =  $curSite['site_fullname'];
			}

			$security = $securityClass->getSecurityReportById($params['id']);
			
			$filename = $this->site_id."_spv_".$params['id'].".pdf";

			require_once('fpdf/mc_table.php');

			$datetime = explode(" ",$security['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$report_date = date("l, j F Y", $r_date);
			
			
			/*$defect_list = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
			$incident = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
			$glitch = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
			$lost_found = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');*/
			
			if($security['shift'] == 1)
    		{
    		    $startdate = $datetime[0]." 07:00:00";
    		    $enddate = $datetime[0]." 15:00:00";
    		}
    		if($security['shift'] == 2)
    		{
    		    $startdate = $datetime[0]." 15:00:00";
    		    $enddate = $datetime[0]." 23:00:00";
    		}
    		if($security['shift'] == 3)
    		{
    		    $startdate =  date("Y-m-d",mktime(0, 0, 0, $date[1], $date[2]-1, $date[0]))." 23:00:00";
    		    $enddate = $datetime[0]." 07:00:00";
    		}
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
	    	$issueClass = new issueClass();
			
			$defect_list = $issueClass->getIssuesByTypeShift(4, 1, $this->site_id, $startdate, $enddate);
		    
    		if(!empty($defect_list))
    		{
    		    foreach($defect_list as &$dl)
    		    {
    		        $issuedatetime = explode(" ", $dl['issue_date']);
    				$dl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($dl['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $dl['picture']);
    				$dl['large_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_large.".$pic[1];
    				$dl['thumb_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    				$dl['status'] = $dl['follow_up'];
    		    }
    		}
    		
    		
    		$incident = $issueClass->getIssuesByTypeShift(1, 1, $this->site_id, $startdate, $enddate);
    		if(!empty($incident))
    		{
    		    foreach($incident as &$inc)
    		    {
    		        $issuedatetime = explode(" ", $inc['issue_date']);
    				$inc['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($inc['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $inc['picture']);
    				$inc['large_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_large.".$pic[1];
    				$inc['thumb_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
    		
    		$glitch = $issueClass->getIssuesByTypeShift(2, 1, $this->site_id, $startdate, $enddate);
    		
    		if(!empty($glitch))
    		{
    		    foreach($glitch as &$gl)
    		    {
    		        $issuedatetime = explode(" ", $gl['issue_date']);
    				$gl['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($gl['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $gl['picture']);
    				$gl['large_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_large.".$pic[1];
    				$gl['thumb_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
    		
    		$lost_found = $issueClass->getIssuesByTypeShift(3, 1, $this->site_id, $startdate, $enddate);
    		if(!empty($lost_found))
    		{
    		    foreach($lost_found as &$lf)
    		    {
    		        $issuedatetime = explode(" ", $lf['issue_date']);
    				$lf['date_time'] = date("j M Y", strtotime($issuedatetime[0]))." ".$issuedatetime[1];
    				
    			    if($lf['issue_date'] > "2019-10-23 14:30:00")
        			{
        				$issuedate = explode("-",$issuedatetime[0]);
        				$imageURL = "/images/issues/".$issuedate[0]."/";
        			}
        			else
        				$imageURL = "/images/issues/";
        			
        			$pic = explode(".", $lf['picture']);
    				$lf['large_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_large.".$pic[1];
    				$lf['thumb_pic'] = $this->config->paths->html.$imageURL.$pic[0]."_thumb.".$pic[1];
    				
    		    }
    		}
			
			$attachment = $securityClass->getSpvAttachments($params['id']);

			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$vendor = $vendorClass->getVendor($this->site_id);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Security Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,'Security');
			$pdf->ln();
			$pdf->Write(5,$security['site_fullname']);
			$pdf->ln();
			$pdf->ln();

			if($security['shift'] == 1) $shift = "Pagi, 07:00 - 15:00 WIB";
			else if($security['shift'] == 2) $shift = "Siang, 15:00 - 23:00 WIB";
			else if($security['shift'] == 3) $shift = "Malam, 23:00 - 07:00 WIB";

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DATE / SHIFT');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(40,7,'Day / Date',1,0,'L');
			$pdf->Cell(50,7,$report_date,1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'Shift',1,0,'L');
			$pdf->Cell(50,7,$shift,1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'L');
			$pdf->Cell(50,7,$security['name'],1,0,'L');
			$pdf->Ln();
			$pdf->ln();
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(63,7,'In House',1,0,'L',true);
			$pdf->Cell(63,7,strtoupper($vendor[0]['vendor_name']),1,0,'L',true);
			$pdf->Cell(63,7,strtoupper($vendor[1]['vendor_name']),1,0,'L',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(63,63,63));
			$pdf->Row(array('Spv : '.$security['supervisor'],'Waka : '.$security['chief_spd'],'Chief : '.$security['chief_army']));
			$pdf->Row(array('Posko : '.$security['staff_posko'],'Panwas : '.$security['panwas_spd'],'Panwas : '.$security['panwas_army']));
			$pdf->Row(array('CCTV : '.$security['staff_cctv'],'Danton / Danru : '.$security['danton_spd'],'Danton / Danru : '.$security['danton_army']));
			$pdf->Row(array('Safety : '.$security['safety'],'Jumlah : '.$security['jumlah_spd'],'Jumlah : '.$security['jumlah_army']));
			$pdf->Ln();
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			$briefing = explode("<br>", $security['briefing']);
			$i = 0;
			foreach($briefing as $b)
			{
				if(trim($briefing[$i-1]) == "" && trim($b) == "") ;
				else
				{
					$pdf->Write(5,$b);
					$pdf->Ln();
				}
				$i++;
			}
			$pdf->Ln();
		
			if(!empty($defect_list))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'DEFECT LIST');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'Image',1,0,'C',true);
				$pdf->Cell(30,7,'Date & Time',1,0,'C',true);
				$pdf->Cell(30,7,'Location',1,0,'C',true);
				$pdf->Cell(55,7,'Description',1,0,'C',true);
				$pdf->Cell(55,7,'Follow Up',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(20,30,30,55,55));
				$pdf->SetAligns(array('C','C','C','C','C'));
				foreach($defect_list as $dl)
				{
				    $y = $pdf->GetY() + 1;
					$pdf->Row(array("\n\n\n\n",$dl['date_time'],$dl['location'],$dl['description'],$dl['status']));
					$y2 = $pdf->GetY() + 1;
					if($y2<$y) $y = $y2-20;
					if (file_exists($dl['thumb_pic'])) {
    					list($width, $height) = getimagesize($dl['thumb_pic']);
    					if($width > $height)
    					{
    						$w = 18;
    						$h = 0;
    					}
    					else {
    						$w = 0;
    						$h = 18;
    					}
    					
    					$pdf->Image($dl['thumb_pic'],11,$y, $w,$h);
					
				    }
				}			
				$pdf->Ln();
			}
			
			
			if(!empty($incident))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'INCIDENT REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'Image',1,0,'C',true);
				$pdf->Cell(30,7,'Date & Time',1,0,'C',true);
				$pdf->Cell(30,7,'Location',1,0,'C',true);
				$pdf->Cell(55,7,'Description',1,0,'C',true);
				$pdf->Cell(55,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');	
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(20,30,30,55,55));
				$pdf->SetAligns(array('C','C','C','C','C'));
				foreach($incident as $i)
				{
					$y = $pdf->GetY() + 1;
					$pdf->Row(array("\n\n\n\n",$i['date_time'],$i['location'],$i['description'],$i['status']));
					$y2 = $pdf->GetY() + 1;
					if($y2<$y) $y = $y2-20;
					if (file_exists($i['thumb_pic'])) {
    					list($width, $height) = getimagesize($i['thumb_pic']);
    					if($width > $height)
    					{
    						$w = 18;
    						$h = 0;
    					}
    					else {
    						$w = 0;
    						$h = 18;
    					}
    					
    					$pdf->Image($i['thumb_pic'],11,$y, $w,$h);
				    }
				}
				$pdf->Ln();
			}
			
				
			if(!empty($glitch))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'GLITCH');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'Image',1,0,'C',true);
				$pdf->Cell(30,7,'Date & Time',1,0,'C',true);
				$pdf->Cell(30,7,'Location',1,0,'C',true);
				$pdf->Cell(55,7,'Description',1,0,'C',true);
				$pdf->Cell(55,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');	
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(20,30,30,55,55));
				$pdf->SetAligns(array('C','C','C','C','C'));
				foreach($glitch as $g)
				{
					$y = $pdf->GetY() + 1;
					$pdf->Row(array("\n\n\n\n",$g['date_time'],$g['location'],$g['description'],$g['status']));
					$y2 = $pdf->GetY() + 1;
					if($y2<$y) $y = $y2-20;
					if (file_exists($g['thumb_pic'])) {
    					list($width, $height) = getimagesize($g['thumb_pic']);
    					if($width > $height)
    					{
    						$w = 18;
    						$h = 0;
    					}
    					else {
    						$w = 0;
    						$h = 18;
    					}
    					$pdf->Image($g['thumb_pic'],11,$y, $w,$h);
				    }
				}
				$pdf->Ln();
			}
			
				
			if(!empty($lost_found))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'LOST & FOUND');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'Image',1,0,'C',true);
				$pdf->Cell(30,7,'Date & Time',1,0,'C',true);
				$pdf->Cell(30,7,'Location',1,0,'C',true);
				$pdf->Cell(55,7,'Description',1,0,'C',true);
				$pdf->Cell(55,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(20,30,30,55,55));
				$pdf->SetAligns(array('C','C','C','C','C'));
				foreach($lost_found as $lf)
				{
					$y = $pdf->GetY() + 1;
					$pdf->Row(array("\n\n\n\n",$lf['date_time'],$lf['location'],$lf['description'],$lf['status']));
					$y2 = $pdf->GetY() + 1;
					if($y2<$y) $y = $y2-20;
					if (file_exists($lf['thumb_pic'])) {
    					list($width, $height) = getimagesize($lf['thumb_pic']);
    					if($width > $height)
    					{
    						$w = 18;
    						$h = 0;
    					}
    					else {
    						$w = 0;
    						$h = 18;
    					}
    					
    					$pdf->Image($lf['thumb_pic'],11,$y, $w,$h);
				    }
				}
				$pdf->Ln();
			}

			if(!empty($attachment))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'ATTACHMENTS');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				foreach($attachment as $att)
				{
					//$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/1/s/'.substr($att['upload_date'], 0, 4).'/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}
			$pdf->Ln();
			
			if($download == 1) $pdf->Output('D', $filename, false);
			else $pdf->Output('F', $filename, false);
		}
	}

	public function exportchiefsecuritytopdf($id, $site_id = 0, $download = 0) {	
		$params['id'] = $id;
		if(!empty($id))
		{
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();

			if(!empty($site_id))
			{
				$this->site_id = $site_id;
				Zend_Registry::set('site_id', $this->site_id);
				Zend_Loader::LoadClass('siteClass', $this->modelDir);
				$siteClass = new siteClass();
				$curSite = $siteClass->getSiteById($site_id);
				$this->ident['site_fullname'] =  $curSite['site_fullname'];
			}	

			require_once('fpdf/mc_table.php');

			$security = $securityClass->getChiefSecurityReportById($id);
			$created_date = explode(" ", $security['created_date']);
			$sec['morning'] = $securityClass->getSecurityReportByShift($created_date[0], '1', $this->site_id);
			$sec['afternoon'] = $securityClass->getSecurityReportByShift($created_date[0], '2', $this->site_id);
			$sec['night'] = $securityClass->getSecurityReportByShift($created_date[0], '3', $this->site_id);
			
			if($sec['morning']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['morning']['chief_security_report_id'];
			elseif($sec['afternoon']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['afternoon']['chief_security_report_id'];
			elseif($sec['night']['chief_security_report_id'] > 0) $sec['chief_security_report_id'] = $sec['night']['chief_security_report_id'];
				
			$filename = $this->site_id."_chief_".$id.".pdf";
		
			$security['morning'] = $sec['morning'];
			$security['afternoon'] = $sec['afternoon'];
			$security['night'] = $sec['night'];
			
			$date = explode("-",$created_date[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$security['created_date'] = date("l, j F Y", $r_date);
			$security['report_date'] = $created_date[0];
			
			if(empty($sec['chief_security_report_id'])) $tempId = '0';
			else $tempId = $sec['chief_security_report_id'];
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getPerlengkapanByChiefSecurityReport($tempId);
			
			$trainingTable = $this->loadModel('training');
			
			if(!empty($sec['chief_security_report_id']))
			{
				$outsourceTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'1');
				$inHouseTraining = $trainingTable->getSecurityTrainingByType($sec['chief_security_report_id'],'2');
				if(count($outsourceTraining) > count($inHouseTraining)) $totalTraining = count($outsourceTraining);
				else $totalTraining = count($inHouseTraining);
			}
			
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			/*** SPECIFIC REPORT ***/
			
			$security_ids = "";
			if(!empty($security['morning']['security_id'])) $security_ids .= $security['morning']['security_id'].",";
			if(!empty($security['afternoon']['security_id'])) $security_ids .= $security['afternoon']['security_id'].",";
			if(!empty($security['night']['security_id'])) $security_ids .= $security['night']['security_id'].",";
			$security_ids = substr($security_ids,0,-1);
			$issueTable = $this->loadModel('issue');
			
			$specific_reports = $securityClass->getSpecificReportByIds($security_ids, $tempId);
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			
			/*** END OF SPECIFIC REPORT ***/
			$attachment = $attachmentMorning = $attachmentAfternoon = $attachmentNight = array();
			if(!empty($sec['chief_security_report_id'])) $attachment = $securityClass->getChiefAttachments($sec['chief_security_report_id']);
					
			if(!empty($security['morning']['security_id'])) $attachmentMorning = $securityClass->getSpvAttachments($security['morning']['security_id']);
			if(!empty($security['afternoon']['security_id'])) $attachmentAfternoon = $securityClass->getSpvAttachments($security['afternoon']['security_id']);
			if(!empty($security['night']['security_id'])) $attachmentNight = $securityClass->getSpvAttachments($security['night']['security_id']);
			$attachment = array_merge($attachmentNight, $attachmentMorning, $attachmentAfternoon, $attachment);
			
			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$vendor = $vendorClass->getVendor($this->site_id);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Chief Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Chief Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(40,7,'Day / Date',1,0,'L');
			$pdf->Cell(50,7,$security['created_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'Reporting Time',1,0,'L');
			$pdf->Cell(50,7,$setting['chief_security_reporting_time'],1,0,'L');
			$pdf->Ln();
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(45,7,'',1,0,'L',true);
			$pdf->Cell(48,7,'Malam',1,0,'C',true);
			$pdf->Cell(48,7,'Pagi',1,0,'C',true);
			$pdf->Cell(48,7,'Siang',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("SUPERVISOR",$security['night']['supervisor'],$security['morning']['supervisor'],$security['afternoon']['supervisor']));
			$pdf->Row(array("STAFF POSKO",$security['night']['staff_posko'],$security['morning']['staff_posko'],$security['afternoon']['staff_posko']));
			$pdf->Row(array("STAFF CCTV",$security['night']['staff_cctv'],$security['morning']['staff_cctv'],$security['afternoon']['staff_cctv']));
			$pdf->Row(array("SAFETY",$security['night']['safety'],$security['morning']['safety'],$security['afternoon']['safety']));
			$pdf->Ln();
			
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(158,130,75);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'VENDOR',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(49,7,'',1,0,'L',true);
			$pdf->Cell(70,7,$vendor[0]['vendor_name'],1,0,'C',true);
			$pdf->Cell(70,7,$vendor[1]['vendor_name'],1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(49, 70, 70));			
			$pdf->Row(array("CHIEF / WAKA",$security['chief_spd'],$security['chief_army']));
			$pdf->Row(array("PANWAS",$security['panwas_spd'],$security['panwas_army']));
			$pdf->Row(array("DANTON / DANRU PAGI",$security['danton_pagi_spd'],$security['danton_pagi_army']));
			$pdf->Row(array("KEKUATAN",$security['kekuatan_spd'],$security['kekuatan_army']));
			$pdf->Ln();

			
			if(!empty($equipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'PERLENGKAPAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(158,130,75);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(35,7,'Nama','LTR',0,'C',true);
				$pdf->Cell(35,7,'Vendor','LTR',0,'C',true);
				$pdf->Cell(30,7,'Jumlah','LTR',0,'C',true);
				$pdf->Cell(50,7,'Kondisi',1,0,'C',true);
				$pdf->Cell(39,7,'Keterangan','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(35,7,'Perlengkapan','LRB',0,'C',true);
				$pdf->Cell(35,7,'','LRB',0,'C',true);
				$pdf->Cell(30,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'Ok',1,0,'C',true);
				$pdf->Cell(25,7,'Tidak Ok',1,0,'C',true);
				$pdf->Cell(39,7,'','LRB',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(35, 35, 30, 25, 25, 39));	
				$i = 0;
				foreach($equipments as $equipment) {
					$pdf->Row(array($equipment['equipment_name'],$equipment['vendor_name'],$equipment['total_equipment'], str_replace("√",'v',$equipment['ok_condition']), $equipment['bad_condition'], $equipment['description']));
					$i++; 
				} 
				$pdf->Ln();
			}		


			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Morning Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['morning']['briefing']))
			{
				$morning_briefing = explode("<br>", $security['morning']['briefing']);
				$i = 0;
				foreach($morning_briefing as $b)
				{
					if(trim($morning_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing2']))
			{
				$morning_briefing2 = explode("<br>", $security['morning']['briefing2']);
				$i = 0;
				foreach($morning_briefing2 as $b)
				{
					if(trim($morning_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['morning']['briefing3']))
			{
				$morning_briefing3 = explode("<br>", $security['morning']['briefing3']);
				$i = 0;
				foreach($morning_briefing3 as $b)
				{
					if(trim($morning_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Afternoon Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['afternoon']['briefing']))
			{
				$afternoon_briefing = explode("<br>", $security['afternoon']['briefing']);
				$i = 0;
				foreach($afternoon_briefing as $b)
				{
					if(trim($afternoon_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing2']))
			{
				$afternoon_briefing2 = explode("<br>", $security['afternoon']['briefing2']);
				$i = 0;
				foreach($afternoon_briefing2 as $b)
				{
					if(trim($afternoon_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['afternoon']['briefing3']))
			{
				$afternoon_briefing3 = explode("<br>", $security['afternoon']['briefing3']);
				$i = 0;
				foreach($afternoon_briefing3 as $b)
				{
					if(trim($afternoon_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'Night Briefing');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($security['night']['briefing']))
			{
				$night_briefing = explode("<br>", $security['night']['briefing']);
				$i = 0;
				foreach($night_briefing as $b)
				{
					if(trim($night_briefing[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing2']))
			{
				$night_briefing2 = explode("<br>", $security['night']['briefing2']);
				$i = 0;
				foreach($night_briefing2 as $b)
				{
					if(trim($night_briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			if(!empty($security['night']['briefing3']))
			{
				$night_briefing3 = explode("<br>", $security['night']['briefing3']);
				$i = 0;
				foreach($night_briefing3 as $b)
				{
					if(trim($night_briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Ln();
			}

			$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
		
			if(!empty($outsourceTraining) || !empty($inHouseTraining))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->ln();

				if(!empty($outsourceTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'OUTSOURCE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($outsourceTraining as $outsourceTrain) {	
						$pdf->Row(array($outsourceTrain['activity'],$outsourceTrain['description']));
					}
					$pdf->Ln();
				}

				if(!empty($inHouseTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(158,130,75);
					$pdf->SetTextColor(255,255,255);
					$pdf->SetFont('','B');
					$pdf->Cell(189,7,'IN HOUSE',1,0,'C',true);
					$pdf->Ln();
					$pdf->Cell(60,7,'Activity',1,0,'C',true);
					$pdf->Cell(129,7,'Description',1,0,'C',true);
					$pdf->Ln();
					$pdf->SetFont('');
					$pdf->SetTextColor(0,0,0);
					$pdf->SetWidths(array(60,129));		
					foreach($inHouseTraining as $inHouseTrain) {	
						$pdf->Row(array($inHouseTrain['activity'],$inHouseTrain['description']));
					}
					$pdf->Ln();
				}
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'SOSIALISASI SOP');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Write(10,$security['sosialisasi_sop_a']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_b']);
			$pdf->Ln();
			$pdf->Write(10,$security['sosialisasi_sop_c']);
			$pdf->Ln();

			if(!empty($specific_reports))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'KAIZEN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(94,95));
				foreach($specific_reports as $specific_report)
				{
					$timeField = "Time";
					if($specific_report['issue_type_id'] < 4)
					{
						$specific_report['detail'] = $specific_report['description'];
					}
					if($specific_report['issue_type_id'] == 4)
					{
						$specific_report['time'] =  $specific_report['area'];
						$specific_report['issue_type_name'] = "Defect List";
						$timeField = "Area";
					}
					$issue = $specific_report['issue_type_name']."\n".$timeField.' : '.$specific_report['time']."\nDetail : ".$specific_report['detail'];
					$pdf->Row(array($issue,"Status :\n".$specific_report['status']));
				}
				$pdf->Ln();
			}
			

			if(!empty($attachment))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'ATTACHMENTS');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				foreach($attachment as $att)
				{
					//$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
					$pdf->write(5,"- ".$att['description'], $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);					
					//$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}
			$pdf->Ln();

			if($download == 1) $pdf->Output('D', $filename, false);
			else $pdf->Output('F', $filename, false);
		}
	}
}

class iniParser {
	
	var $_iniFilename = '';
	var $_iniParsedArray = array();
	
	function iniParser( $filename= "" )
	{
		if(!empty($filename)) {
			$this->_iniFilename = $filename;
			if($this->_iniParsedArray = parse_ini_file( $filename, true ) ) {
				return true;
			} else {
				return false;
			} 
		}
	}
	
	function getConfigArray() {
		return $this->_iniParsedArray;
	}
	
	function setConfigArray($arr) {
		$this->_iniParsedArray = $arr;
	}
	
	function iniMerge ($config_ini, $custom_ini) {
		foreach ($custom_ini AS $k => $v) {
			if (is_array($v)) {
				$config_ini[$k] = $this->iniMerge($config_ini[$k], $custom_ini[$k]);
			} else {
				$config_ini[$k] = $v;
			}
		}
		return $config_ini;
	}
	
	function getSection( $key )
	{
		return $this->_iniParsedArray[$key];
	}
	
	function getValue( $section, $key )
	{
		if(!isset($this->_iniParsedArray[$section])) return false;
		return $this->_iniParsedArray[$section][$key];
	}
	
	function get( $section, $key=NULL )
	{
		if(is_null($key)) return $this->getSection($section);
		return $this->getValue($section, $key);
	}
	
	function setSection( $section, $array )
	{
		if(!is_array($array)) return false;
		return $this->_iniParsedArray[$section] = $array;
	}
	
	function setValue( $section, $key, $value )
	{
		if( $this->_iniParsedArray[$section][$key] = $value ) return true;
	}
	
	function set( $section, $key, $value=NULL )
	{
		if(is_array($key) && is_null($value)) return $this->setSection($section, $key);
		return $this->setValue($section, $key, $value);
	}
	
	function save( $filename = null )
	{
		if( $filename == null ) $filename = $this->_iniFilename;
		if( !file_exists($filename) || is_writeable( $filename ) ) {
			$SFfdescriptor = @fopen( $filename, "w" );
			if($SFfdescriptor) {
				foreach($this->_iniParsedArray as $section => $array){
					fwrite( $SFfdescriptor, "[" . $section . "]\n" );
					foreach( $array as $key => $value ) {
						if(is_numeric($value) && !in_array($key, array("frametypeid","aracct","defaultSICCode","web_sectionid"))) 
							fwrite( $SFfdescriptor, "$key = $value\n" );
						else
							fwrite( $SFfdescriptor, "$key = \"$value\"\n" );
					}
					fwrite( $SFfdescriptor, "\n" );
				}
				fclose( $SFfdescriptor );
				return true;
			}
			return false;
		} else {
			return false;
		}
	}
	
}

?>
