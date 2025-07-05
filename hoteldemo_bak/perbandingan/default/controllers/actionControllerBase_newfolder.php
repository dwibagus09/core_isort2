<?php
require_once("Zend/Date.php");
require_once('exception.php');

define("BANNER_CACHE", 24*3600);
define("LOOK_AND_FEEL_CACHE", 0);
define("GALLERY_CACHE", 0);
define("ARTICLE_CACHE", 0);
define("GALLERY_WITH_VIEW_COUNT_CACHE", 30);

class actionControllerBase extends Zend_Controller_Action 
{
	public $config;		// global configuration
    public $db;			// databse object
    public $session;	// online ads session namespace.     
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

		if ( strpos(PHP_OS, "WIN") === false ) {
			require_once 'Zend/Cache.php';
			$frontendOptions = array('lifetime' => 3600, 'automatic_serialization' => true);
			$backendOptions = array('servers' => array(array('host' => 'localhost','port' => 11211, 'persistent' => true)));
			$this->cache = Zend_Cache::factory('Output', 'Memcached', $frontendOptions, $backendOptions);
		}
		
		if(strpos(" ".$_SERVER['SERVER_NAME'], "admin")) {
    		$this->_response->setRedirect("/admin");
    		$this->_response->sendResponse();
    		exit();
		}
		
		if($_SERVER['SERVER_NAME'] == "srt.quantum.net.id" || $_SERVER['SERVER_NAME'] == "srt2.quantum.net.id")
		{
			$this->_response->setRedirect($this->config->paths->url.$_SERVER['REQUEST_URI']);
    		$this->_response->sendResponse();
    		exit();
		}
		
    	
		$config = array('ssl' => 'tls','port' => 25); // Optional port number supplied
        $transport = new Zend_Mail_Transport_Smtp('smtp.quantum.net.id', $config);
        Zend_Mail::setDefaultTransport($transport);
        
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
		if(empty($this->ident) && $this->_request->getParam("action") != "login" && $this->_request->getParam("action") != "issueimage" && $this->_request->getParam('action') != "sendreminder" && $this->_request->getParam('action') != "sendweeklyreminderauto" && $this->_request->getParam('action') != "sendweeklyreviewauto" && $this->_request->getParam('action') != "deletegraph"  && $this->_request->getParam('action') != "cacheopenedissuesomments" && $this->_request->getParam('action') != "savesecuritypdf" && $this->_request->getParam('action') != "savesafetyparkinghkpdf" && $this->_request->getParam('action') != "saveommodpdf") {
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
			$showSecurity = $showAddSecurity = $showEditSecurity = $showAddChiefSecurity = $showEditChiefSecurity = $showChiefSecurity = $showSecurityActionPlan = 0;
			$showSafety = $showAddSafety = $showEditSafety = $showSafetyActionPlan = 0;
			$showParkingTraffic = $showAddParkingTraffic = $showEditParkingTraffic = $showParkingActionPlan = 0;
			$showHousekeeping = $showAddHousekeeping = $showEditHousekeeping = $showHousekeepingActionPlan = 0;
			$showOM = $showAddOM = $showEditOM = $showOMActionPlan = 0;
			$showMod = $showAddMod = $showMODSchedule = $showMODScheduleReport = 0;
			$showBM = $showAddBM = 0;
			$showActionPlanSetting = $showStatistic = $showReminderReview = $showSiteSelection = $showActionPlanStat =  0;

			if(in_array(1, $this->ident['role_ids'])) // Super User
			{
				$showSecurity = $showAddSecurity = $showEditSecurity = $showAddChiefSecurity = $showChiefSecurity = $showSafety = $showAddSafety = $showEditSafety = $showParkingTraffic = $showAddParkingTraffic = $showEditParkingTraffic = $showHousekeeping = $showAddHousekeeping = $showEditHousekeeping = $showOM = $showAddOM = $showEditOM = $showMod = $showAddMod = $showBM = $showAddBM = $showSecurityActionPlan = $showSafetyActionPlan = $showParkingActionPlan = $showHousekeepingActionPlan = $showOMActionPlan = $showActionPlanSetting = $showStatistic = $showSiteSelection = $showMODSchedule = $showMODScheduleReport =  $showActionPlanStat = 1;
			}
			if(in_array(2, $this->ident['role_ids'])) // Spv Security
			{
				$showSecurity = $showAddSecurity = $showEditSecurity = 1;
			}
			if(in_array(3, $this->ident['role_ids'])) // Chief Security
			{
				$showSecurity = $showChiefSecurity = $showAddChiefSecurity = $showSecurityActionPlan = 1;
			}
			if(in_array(4, $this->ident['role_ids'])) // OM
			{
				$showSecurity = $showChiefSecurity = $showSafety = $showParkingTraffic = $showHousekeeping = $showOM = $showAddOM = $showEditOM = $showMod = $showAddMod = $showSecurityActionPlan = $showSafetyActionPlan = $showParkingActionPlan = $showHousekeepingActionPlan = $showOMActionPlan = $showActionPlanStat = 1;
			}
			if(in_array(5, $this->ident['role_ids'])) // General Manager
			{
				$showSecurity = $showChiefSecurity = $showSafety = $showParkingTraffic = $showHousekeeping = $showOM = $showMod = $showBM = $showActionPlanStat = 1;
			}
			if(in_array(6, $this->ident['role_ids'])) // Director
			{
				$showSecurity = $showChiefSecurity = $showSafety = $showParkingTraffic = $showHousekeeping = $showOM = $showMod = $showBM = $showSecurityActionPlan = $showSafetyActionPlan = $showParkingActionPlan = $showHousekeepingActionPlan = $showStatistic = $showSiteSelection = $showActionPlanStat = 1;
			}
			if(in_array(7, $this->ident['role_ids'])) // Chief Safety
			{
				$showSafety = $showAddSafety = $showEditSafety = $showSafetyActionPlan = 1;
			}
			if(in_array(8, $this->ident['role_ids'])) // Chief Parking & Traffic
			{
				$showParkingTraffic = $showAddParkingTraffic = $showEditParkingTraffic = $showParkingActionPlan = 1;
			}
			if(in_array(9, $this->ident['role_ids'])) // Chief Housekeeping
			{
				$showHousekeeping = $showAddHousekeeping = $showEditHousekeeping = $showHousekeepingActionPlan = 1;
			}
			if(in_array(10, $this->ident['role_ids'])) // MOD
			{
				$showMod = $showAddMod = $showActionPlanStat = 1;
			}
			if(in_array(11, $this->ident['role_ids'])) // BM
			{
				$showBM = $showAddBM = 1;
			}
			if(in_array(13, $this->ident['role_ids'])) // TS Security
			{
				$showSecurity = $showChiefSecurity = $showSecurityActionPlan = $showSiteSelection = 1;
			}
			if(in_array(14, $this->ident['role_ids'])) // TS Safety
			{
				$showSafety = $showSafetyActionPlan = $showSiteSelection = 1;
			}
			if(in_array(15, $this->ident['role_ids'])) // TS Housekeeping
			{
				$showHousekeeping = $showHousekeepingActionPlan = $showSiteSelection = 1;
			}
			if(in_array(16, $this->ident['role_ids'])) // TS Parking & Traffic
			{
				$showParkingTraffic = $showParkingActionPlan = $showSiteSelection = 1;
			}
			if(in_array(17, $this->ident['role_ids'])) // MOD Schedule
			{
				$showMod = $showMODSchedule = $showMODScheduleReport = 1;
			}

			if($this->ident['user_id'] == 1 || $this->ident['user_id'] == 31) {
				$showReminderReview = 1;
			}

			// cek jadwal MOD
			if($showMod == 1) {
				Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
				$modscheduleClass = new modscheduleClass();
				$allowedMODUsers = $modscheduleClass->getMODScheduleByDate(date("Y-m-d"));
				$allowedUsers = array();
				$j=0;
				if(!empty($allowedMODUsers))
				{
					foreach($allowedMODUsers as $au)
					{
						$allowedUsers[$j] = $au['mod_user_id'];
						$j++;
					}
					if(in_array($this->ident['user_id'], $allowedUsers)) $showAddMod = 1;
					else  $showAddMod = 0;
				}
				else $showAddMod = 0;
			}
		
			$this->view->showSecurity = $showSecurity;
			$this->view->showAddSecurity = $showAddSecurity;
			$this->view->showEditSecurity = $showEditSecurity;
			$this->view->showChiefSecurity = $showChiefSecurity;
			$this->view->showAddChiefSecurity = $showAddChiefSecurity;
			$this->view->showSafety = $showSafety;
			$this->view->showAddSafety = $showAddSafety;
			$this->view->showEditSafety = $showEditSafety;
			$this->view->showParkingTraffic = $showParkingTraffic;
			$this->view->showAddParkingTraffic = $showAddParkingTraffic;
			$this->view->showEditParkingTraffic = $showEditParkingTraffic;
			$this->view->showHousekeeping = $showHousekeeping;
			$this->view->showAddHousekeeping = $showAddHousekeeping;
			$this->view->showEditHousekeeping = $showEditHousekeeping;
			$this->view->showOM = $showOM;
			$this->view->showAddOM = $showAddOM;
			$this->view->showEditOM = $showEditOM;
			$this->view->showMod = $showMod;
			$this->view->showAddMod = $showAddMod;
			$this->view->showBM = $showBM;
			$this->view->showAddBM = $showAddBM;
			$this->view->showSecurityActionPlan = $showSecurityActionPlan;
			$this->view->showSafetyActionPlan = $showSafetyActionPlan;
			$this->view->showParkingActionPlan = $showParkingActionPlan;
			$this->view->showHousekeepingActionPlan = $showHousekeepingActionPlan;
			$this->view->showOMActionPlan = $showOMActionPlan;
			$this->view->showActionPlanSetting = $this->showActionPlanSetting =  $showActionPlanSetting;
			$this->view->showStatistic = $showStatistic;
			$this->view->showReminderReview = $this->showReminderReview = $showReminderReview;
			$this->view->showSiteSelection = $this->showSiteSelection = $showSiteSelection;
			$this->view->showMODSchedule = $showMODSchedule;
			$this->view->showMODScheduleReport = $showMODScheduleReport;
			$this->view->showActionPlanStat = $showActionPlanStat;
		}
	
		
		
			
		/*** Check hide/show add report button ***/
		
		if(!empty($this->ident)) {
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			$reportSafety = $safetyClass->getSafetyReportByDate(date("Y-m-d"));
			if(!empty($reportSafety)) $this->view->hideAddSafety = 1;
			else $this->view->hideAddSafety = 0;
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();		
			$reportParking = $parkingClass->getReportByDate(date("Y-m-d"));
			if(!empty($reportParking)) $this->view->hideAddParking = 1;
			else $this->view->hideAddParking = 0;
			
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$reportHK = $housekeepingClass->getReportByDate(date("Y-m-d"));
			if(!empty($reportHK)) $this->view->hideAddHK = 1;
			else $this->view->hideAddHK = 0;
			
			Zend_Loader::LoadClass('operationalClass', $this->modelDir);
			$operationalClass = new operationalClass();
			$reportOM = $operationalClass->getReportByDate(date("Y-m-d"));
			if(!empty($reportOM)) $this->view->hideAddOM = 1;
			else $this->view->hideAddOM = 0;		
			
			Zend_Loader::LoadClass('modClass', $this->modelDir);
			$modClass = new modClass();
			$reportMOD = $modClass->getReportByDate(date("Y-m-d"));
			if(!empty($reportMOD)) $this->view->hideAddMOD = 1;
			else $this->view->hideAddMOD = 0;	
			
			Zend_Loader::LoadClass('bmClass', $this->modelDir);
			$bmClass = new bmClass();
			$reportBM = $bmClass->getReportByDate(date("Y-m-d"));
			if(count($reportBM) > 1) $this->view->hideAddBM = 1;
			else $this->view->hideAddBM = 0;	
		}
		/*** End check hide/show add report button ***/

		if($showMod == 1 && $this->site_id == 2)
		{
			Zend_Loader::LoadClass('modscheduleClass', $this->modelDir);
			$modscheduleClass = new modscheduleClass();
			$modsched = $modscheduleClass->getScheduleByDate(date("Y-m-d"), $this->ident['user_id']);
			if(!empty($modsched) || in_array(1, $this->ident['role_ids'])) $this->view->showAddMod = 1;
			else  $this->view->showAddMod = 0;
		}

		if(!empty($this->site_id))
		{
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$this->view->totalAllIssues = $issueClass->getTotalPendingIssues('0', $this->site_id, 0);
		}
		
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
	
	function copyr($source, $dest) {
    	// Simple copy for a file
    	if (is_file($source)) {
        	return copy($source, $dest);
    	}
    	// Make destination directory
    	if (!is_dir($dest)) {
        	mkdir($dest);
    	}
    	// If the source is a symlink
    	if (is_link($source)) {
        	$link_dest = readlink($source);
        	return symlink($link_dest, $dest);
    	}

    	// Loop through the folder
    	$dir = dir($source);
    	while (false !== ($entry = $dir->read())) {
        	// Skip pointers
        	if ($entry == '.' || $entry == '..') {
            	continue;
        	}
        	// Deep copy directories
        	if ($dest != "$source/$entry") {
	            $this->copyr("$source/$entry", "$dest/$entry");
    	    }
    	}

    	// Clean up
    	$dir->close();
    	return true;
	}
	
	function deleter($path) {
		if($handler = opendir($path)) {
			while($filename = readdir($handler))  {
				if ($filename != "." and $filename != "..")  {
					if(is_dir($path."/".$filename)) $this->deleter($path."/".$filename);
					else unlink($path."/".$filename);
				}
			}
			closedir($handler);
			rmdir($path);
		}
	}
    
    protected function randomID($length = 30) {
    	$password = "";
        $possible = "0123456789abcdfghjklmnpqrstvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $i = 0;

        while ($i < $length) {
            $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);
            if (!strstr($password, $char)) {
                $password .= $char;
                $i++;
            }
		}
        return $password;
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
		/*$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('article_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('banners_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('display_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('events_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('gallery_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('newsSections_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('polls_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('specialsections_cms_'.$this->siteid));
		$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('submenu_cms_'.$this->siteid));*/
		//$this->cache->clean(Zend_Cache::CLEANING_MODE_MATCHING_TAG,array('article_cms_'.$this->siteid, 'banners_cms_'.$this->siteid, 'display_cms_'.$this->siteid, 'events_cms_'.$this->siteid, 'gallery_cms_'.$this->siteid, 'newsSections_cms_'.$this->siteid, 'polls_cms_'.$this->siteid, 'specialsections_cms_'.$this->siteid, 'submenu_cms_'.$this->siteid));
		
		$this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		
		/*$content = $this->loadModel('content');
		$contentAreas = $content->getContentAreas($this->siteid);		
		foreach($contentAreas as $ca)
		{
			$this->cache->remove("area_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("articleslideshow_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("latestnews_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("topReadPassMonth_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("topReadPassWeek_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("topreadtoday_".$ca["area_id"]."_cms_".$this->siteid);
			$this->cache->remove("topReadYesterday_".$ca["area_id"]."_cms_".$this->siteid);
		}
		
		$section = $this->loadModel('section');
		$contentSections = $section->getContentSections($this->siteid);
		foreach($contentSections as $cs)
		{
			$this->cache->remove("articles_".$cs["section_id"]."_cms_".$this->siteid);
			$this->cache->remove("banners_".$cs["section_id"]."_cms_".$this->siteid);
		}
		
		$article = $this->loadModel('contentarticle');
		$contentArticle = $article->getArticlesBySite($this->siteid);
		foreach($contentArticle as $cart)
		{
			$this->cache->remove("article_".$cart["article_id"]."_cms_".$this->siteid);
			$this->cache->remove("articlephoto_".$cart["article_id"]."_cms_".$this->siteid);
			$this->cache->remove("article_".$cart["article_id"]."_comments_cms_".$this->siteid);
		}
		
		$this->cache->remove("articleslideshow_cms_".$this->siteid);
		$this->cache->remove("audios_cms_".$this->siteid);
		$this->cache->remove("banners_cms_".$this->siteid);
		$this->cache->remove("events_cms_".$this->siteid);
		$this->cache->remove("footerblock_cms_".$this->siteid);
		$this->cache->remove("gallery_cms_".$this->siteid);
		$this->cache->remove("latestnews_cms_".$this->siteid);
		$this->cache->remove("latestaudios_cms_".$this->siteid);
		$this->cache->remove("latestphotos_cms_".$this->siteid);
		$this->cache->remove("latestslideshows_cms_".$this->siteid);
		$this->cache->remove("latestvideos_cms_".$this->siteid);
		$this->cache->remove("lookandfeel_cms_".$this->siteid);
		$this->cache->remove("menu_cms_".$this->siteid);
		$this->cache->remove("mostviewedaudios_cms_".$this->siteid);
		$this->cache->remove("mostviewedphotos_cms_".$this->siteid);
		$this->cache->remove("mostviewedslideshows_cms_".$this->siteid);
		$this->cache->remove("mostviewedvideos_cms_".$this->siteid);	
		$this->cache->remove("newsSections_cms_".$this->siteid);
		$this->cache->remove("photos_cms_".$this->siteid);
		$this->cache->remove("polls_cms_".$this->siteid);
		$this->cache->remove("slideshows_cms_".$this->siteid);
		$this->cache->remove("specialsections_cms_".$this->siteid);
		$this->cache->remove("submenu_cms_".$this->siteid);
		$this->cache->remove("topReadPassMonth_cms_".$this->siteid);
		$this->cache->remove("topReadPassWeek_cms_".$this->siteid);
		$this->cache->remove("topreadtoday_cms_".$this->siteid);
		$this->cache->remove("topReadYesterday_cms_".$this->siteid);
		$this->cache->remove("videos_cms_".$this->siteid);	*/
		
	}

	public function exportomtopdf($id, $site_id = 0) {
		require_once('fpdf/mc_table.php');
		$params['id'] = $id;
		Zend_Loader::LoadClass('operationalClass', $this->modelDir);
		$operationalClass = new operationalClass();
		if(!empty($site_id))
		{
			$this->site_id = $site_id;
			Zend_Registry::set('site_id', $this->site_id);
			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			$curSite = $siteClass->getSiteById($site_id);
			$this->ident['site_fullname'] =  $curSite['site_fullname'];
		}

		if(!empty($params['id'])) 
		{
			$operational = $operationalClass->getReportById($params['id']);
			
			$datetime = explode(" ",$operational['created_date']);
		
			$filename = $this->config->paths->html.'/pdf_report/om/' . $this->site_id."_om_".$params['id'].".pdf";

			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$operational['report_date'] = date("l, j F Y", $r_date);	
			
			$report_date = $datetime[0];
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($datetime[0]);
			$operational['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
			$operational['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
			$operational['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
			$operational['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
			$operational['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
			$operational['motorbike'] = $parkingReport['inhouse_carcount_motor'];
		
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$utilitySpecificReport = $issueClass->getIssueByReportAndCatId('om_report_id', $params['id'], '10', $report_date);
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			//$securitySpecificReport = $securityClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
			$security_solved_specific_report = $securityClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$security_issues = $issueClass->getOMClosedIssues($report_date, 1);
			$securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);
			
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			//$safetySpecificReport = $safetyClass->getSpecificReportByReport('om_report_id', $params['id'], $report_date);
			$safety_solved_specific_report = $safetyClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$safety_issues = $issueClass->getOMClosedIssues($report_date, 3);
			$safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
			
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$progressreportTable = $this->loadModel('progressreport');
			$hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('om_report_id', $params['id']);
			$hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('om_report_id', $params['id']);
			$hk_issues = $issueClass->getOMClosedIssues($report_date, 2);
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('om_report_id', $params['id']);
			$parking_solved_specific_report = $parkingClass->getOMSolvedSpecificReports($params['id'], $report_date);
			$parking_issues = $issueClass->getOMClosedIssues($report_date, 5);
			$parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

			$marketing_promotion = $operationalClass->getEvents($params['id']);
			
			$attachment = $operationalClass->getAttachments($params['id']);
			
			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			//$pdf->SetMargins(10, 5, 5);
			$pdf->SetTitle($this->ident['initial']." - Daily Operational Mall Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Operational Mall Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(50,7,'Day / Date',1,0,'L');
			$pdf->Cell(138,7,$operational['report_date'],1,0,'L');
			$pdf->Ln(12);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'ISSUES');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'A. BUILDING SERVICE');
			$pdf->Ln();

			if(!empty($utilitySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($utilitySpecificReport as $usr) {
					$completion_date = explode(" ", $usr['solved_date']);
					$bs_completion_date = date("j M Y", strtotime($completion_date[0]));
					$x1 = $pdf->GetY();
					$pdf->Row(array("",$usr['location'], $usr['description'], $bs_completion_date."\n\n\n\n"));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);		
					$usr['picture'] = str_replace(".","_thumb.",$usr['picture']);				
					if(!empty($usr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$usr['picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$usr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$usr['picture'],13,$y, $w, $h);
					}
					$usr['solved_picture'] = str_replace(".","_thumb.",$usr['solved_picture']);	
					if(!empty($usr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$usr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$usr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$usr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Building Service Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'B. SAFETY');
			$pdf->Ln();

			if(!empty($safetySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($safetySpecificReport as $sasr) {
					if(!empty($sasr['completion_date']) && $sasr['completion_date']!="0000-00-00 00:00:00") $safety_comp_date = $sasr['completion_date'];
					else $safety_comp_date = $sasr['solved_date'];
					$saf_completion_date = explode(" ", $safety_comp_date);
					$safety_completion_date = date("j M Y", strtotime($saf_completion_date[0]));
					if(!empty($sasr['issue_id'])) $sasr['detail'] = $sasr['description'];

					$x1 = $pdf->GetY();
					$pdf->Row(array("",$sasr['location'], $sasr['detail'], $safety_completion_date."\n\n\n\n"));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);			
					$sasr['picture'] = str_replace(".","_thumb.",$sasr['picture']);		
					if(!empty($sasr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sasr['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sasr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sasr['picture'],13,$y, $w, $h);
					}
					$sasr['solved_picture'] = str_replace(".","_thumb.",$sasr['solved_picture']);		
					if(!empty($sasr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sasr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sasr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sasr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Safety Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'C. SECURITY');
			$pdf->Ln();

			if(!empty($securitySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($securitySpecificReport as $sesr) {
					if(!empty($sesr['completion_date']) && $sesr['completion_date']!="0000-00-00 00:00:00") $security_comp_date = $sesr['completion_date'];
					else $security_comp_date = $sesr['solved_date'];
					$sec_completion_date = explode(" ", $security_comp_date);
					$security_completion_date = date("j M Y", strtotime($sec_completion_date[0]));
					if(!empty($sesr['issue_id'])) $sesr['detail'] = $sesr['description'];

					$y1 = $pdf->GetY();
					$pdf->Row(array("",$sesr['location'], $sesr['detail'], $security_completion_date."\n\n\n\n"));

					$y2= $pdf->GetY();
					if($y2<$y1) $y = 11;
					else $y = $pdf->GetY()-($y2-$y1-1);		
					$sesr['picture'] = str_replace(".","_thumb.",$sesr['picture']);				
					if(!empty($sesr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sesr['picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sesr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sesr['picture'],13,$y, $w, $h);
					}
					$sesr['solved_picture'] = str_replace(".","_thumb.",$sesr['solved_picture']);	
					if(!empty($sesr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sesr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sesr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sesr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Security Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'D. HOUSEKEEPING');
			$pdf->Ln();

			if(!empty($hk_progress_report_shift) || !empty($hk_other_info) || !empty($hk_issues))
			{	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(60,7,'Foto',1,0,'C',true);
				$pdf->Cell(59,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(40,7,'Status',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(60, 59, 40, 30));	

				if(!empty($hk_progress_report_shift)) { 				
					foreach($hk_progress_report_shift as $hk_pr_shift) { 
						$hk_progress_report_shift_completion_date = explode(" ", $hk_pr_shift['completion_date']);
						$hk_progress_report_shift_comp_date = date("j M Y", strtotime($hk_progress_report_shift_completion_date[0]));
						
						$y1 = $pdf->GetY();
						$pdf->Row(array("",$hk_pr_shift['area'], $hk_pr_shift['status'], $hk_progress_report_shift_comp_date."\n\n\n\n"));
						$y2= $pdf->GetY();
						if($y2<$y1) $y = 11;
						else $y = $pdf->GetY()-($y2-$y1-1);		
						$hk_pr_shift['img_before'] = str_replace(".","_thumb.",$hk_pr_shift['img_before']);		
						if(!empty($hk_pr_shift['img_before'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_before']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_before'],12,$y, $w, $h);
						}
						if(!empty($hk_pr_shift['img_progress'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_progress']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_progress'],31,$y, $w, $h);
						}
						if(!empty($hk_pr_shift['img_after'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_after']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_after'],50,$y, $w, $h);
						}
					}
				}

				if(!empty($hk_other_info)) { 				
					foreach($hk_other_info as $hkotherinfo) { 
						$hk_other_info_completion_date = explode(" ", $hkotherinfo['completion_date']);
						$hk_other_info_comp_date = date("j M Y", strtotime($hk_other_info_completion_date[0]));
						
						$y1 = $pdf->GetY();
						$pdf->Row(array("",$hkotherinfo['area'], $hk_progress_report_shift['status'], $hk_other_info_comp_date."\n\n\n\n"));
						$y2= $pdf->GetY();
						if($y2<$y1) $y = 11;
						else $y = $pdf->GetY()-($y2-$y1-1);		
						$hkotherinfo['img_before'] = str_replace(".","_thumb.",$hkotherinfo['img_before']);		
						if(!empty($hkotherinfo['img_progress'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hkotherinfo['img_progress']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hkotherinfo['img_progress'],13,$y, $w, $h);
						}
					}
				}

				if(!empty($hk_issues))
				{
					foreach($hk_issues as $hk_issue) {
						$hk_comp_date = $hk_issue['solved_date'];
						$comp_date = explode(" ", $hk_comp_date);
						$hk_completion_date = date("j M Y", strtotime($comp_date[0]));
						$x1 = $pdf->GetY();
						$pdf->Row(array("",$hk_issue['location'], $hk_issue['description'], $hk_completion_date."\n\n\n\n"));

						$x2= $pdf->GetY();
						if($x2<$x1) $y = 11;
						else $y = $pdf->GetY()-($x2-$x1-1);		
						$hk_issue['picture'] = str_replace(".","_thumb.",$hk_issue['picture']);				
						if(!empty($hk_issue['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['picture'])) {
							list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['picture']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/issues/'.$hk_issue['picture'],13,$y, $w, $h);
						}
						$hk_issue['solved_picture'] = str_replace(".","_thumb.",$hk_issue['solved_picture']);	
						if(!empty($hk_issueusr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['solved_picture'])) {
							list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['solved_picture']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/issues/'.$hk_issue['solved_picture'],32,$y, $w, $h);
						}
					}					
					$pdf->Ln();
				}
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Housekeeping Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'E. PARKING & TRAFFIC');
			$pdf->Ln();

			if(!empty($parkingSpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(21,7,'Foto',1,0,'C',true);
				$pdf->Cell(20,7,'Time',1,0,'C',true);
				$pdf->Cell(26,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(95,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(21, 20, 26, 95, 30));	

				foreach($parkingSpecificReport as $psr) {
					if(!empty($psr['completion_date'])) $parking_comp_date = $psr['completion_date'];
					else $parking_comp_date = $psr['solved_date'];
					$park_completion_date = explode(" ", $parking_comp_date);
					$parking_completion_date = date("j M Y", strtotime($park_completion_date[0]));

					if(!empty($psr['issue_id'])) $psr['detail'] = $psr['description'];

					if($psr['issue_type_id'] != 4) $time= $psr['time'];
					if($psr['issue_type_id'] != 6) { 
						if($psr['issue_type_id'] < 4) $location= $psr['location']; 
						else $location= $psr['area'];
					}

					$x1 = $pdf->GetY();
					$img = "";
					if(!empty($psr['picture'])) $img .= "\n\n\n\n";
					if(!empty($psr['solved_picture'])) $img .= "\n\n\n\n";
					$pdf->Row(array($img,$time, $location, $psr['detail'], $parking_completion_date));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);			
					$psr['picture'] = str_replace(".","_thumb.",$psr['picture']);		
					if(!empty($psr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$psr['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$psr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$psr['picture'],11,$y, $w, $h);
					}
					$psr['solved_picture'] = str_replace(".","_thumb.",$psr['solved_picture']);		
					if(!empty($psr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$psr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$psr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$psr['solved_picture'],11,$y+19, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Parking & Traffic Issue');
				$pdf->Ln();
			}

			if(!empty($marketing_promotion)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'MARKETING & PROMOTION');
				$pdf->Ln(); 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(40,7,'Nama Event',1,0,'C',true);
				$pdf->Cell(30,7,'Foto-foto',1,0,'C',true);
				$pdf->Cell(30,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(53,7,'Kondisi Event',1,0,'C',true);
				$pdf->Cell(30,7,'Periode Event',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(40, 30, 30, 53, 30)); 	

				foreach($marketing_promotion as $mp) {
					$x1 = $pdf->GetY();
					$pdf->Row(array($mp['event_name'],"", $mp['event_location'], $mp['event_condition'], $mp['event_period']."\n\n\n\n"));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);			
					$mp['event_img'] = str_replace(".","_thumb.",$mp['event_img']);		
					if(!empty($mp['event_img']) && @getimagesize($this->config->paths->html.'/images/event/'.$mp['event_img'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/event/'.$mp['event_img']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/event/'.$mp['event_img'],53,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'SUMMARY WO/WR TENANT &amp; INTERNAL');
			$pdf->Ln(); 	
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(40,7,'','LRT',0,'C',true);
			$pdf->Cell(29,7,'No. of','LRT',0,'C',true);
			$pdf->Cell(30,7,'Completed','LRT',0,'C',true);
			$pdf->Cell(30,7,'No. of','LRT',0,'C',true);
			$pdf->Cell(60,7,'Accumulate','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(40,7,'Department','LR',0,'C',true);
			$pdf->Cell(29,7,'Req. WO','LR',0,'C',true);
			$pdf->Cell(30,7,'WO per','LR',0,'C',true);
			$pdf->Cell(30,7,'Outstanding','LR',0,'C',true);
			$pdf->Cell(30,7,'Previous','LRT',0,'C',true);
			$pdf->Cell(30,7,'Total','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(40,7,'','LRB',0,'C',true);
			$pdf->Cell(29,7,'per today','LRB',0,'C',true);
			$pdf->Cell(30,7,'today','LRB',0,'C',true);
			$pdf->Cell(30,7,'WO per today','LRB',0,'C',true);
			$pdf->Cell(30,7,'Outstanding','LRB',0,'C',true);
			$pdf->Cell(30,7,'Outstanding','LRB',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(40, 29, 30, 30, 30, 30));	
			$pdf->Row(array('Engineering',$operational['engineering_no_of_req_wo'], $operational['engineering_completed_wo'], $operational['engineering_no_of_outstanding_wo'], $operational['engineering_previous_outstanding'], $operational['engineering_next_outstanding']));
			$pdf->Row(array('BS/Civil',$operational['bs_no_of_req_wo'], $operational['bs_completed_wo'], $operational['bs_no_of_outstanding_wo'], $operational['bs_previous_outstanding'], $operational['bs_next_outstanding']));
			$pdf->Row(array('Housekeeping',$operational['housekeeping_no_of_req_wo'], $operational['housekeeping_completed_wo'], $operational['housekeeping_no_of_outstanding_wo'], $operational['housekeeping_previous_outstanding'], $operational['housekeeping_next_outstanding']));
			$pdf->Row(array('Parking',$operational['parking_no_of_req_wo'], $operational['parking_completed_wo'], $operational['parking_no_of_outstanding_wo'], $operational['parking_previous_outstanding'], $operational['parking_next_outstanding']));
			$pdf->Row(array('Others',$operational['other_no_of_req_wo'], $operational['other_completed_wo'], $operational['other_no_of_outstanding_wo'], $operational['other_previous_outstanding'], $operational['other_next_outstanding']));
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'PERHITUNGAN HEAD COUNT & CAR COUNT');
			$pdf->Ln(); 	
			$pdf->SetTextColor(0,0,0);
			$pdf->SetFont('');
			$pdf->Cell(40,7,'A. Head Count',1,0,'L',false);
			$pdf->Cell(45,7,'',1,0,'C',false);
			$pdf->Cell(30,7,$operational['head_count'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'B. Total Car Count',1,0,'L',false);
			$pdf->Cell(45,7,'',1,0,'C',false);
			$pdf->Cell(30,7,$operational['total_car_count'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'C',false);
			$pdf->Cell(45,7,'1. Car Parking',1,0,'L',false);
			$pdf->Cell(30,7,$operational['car_parking'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'C',false);
			$pdf->Cell(45,7,'2. Car Drop Off',1,0,'L',false);
			$pdf->Cell(30,7,$operational['car_drop_off'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'C',false);
			$pdf->Cell(45,7,'3. Valet Parking',1,0,'L',false);
			$pdf->Cell(30,7,$operational['valet_parking'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'C',false);
			$pdf->Cell(45,7,'4. Box Vehicle',1,0,'L',false);
			$pdf->Cell(30,7,$operational['box_vehicle'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'',1,0,'C',false);
			$pdf->Cell(45,7,'5. Taxi',1,0,'L',false);
			$pdf->Cell(30,7,$operational['taxi_bluebird'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'C. Motorbike',1,0,'L',false);
			$pdf->Cell(45,7,'',1,0,'L',false);
			$pdf->Cell(30,7,$operational['motorbike'],1,0,'C',false);
			$pdf->Ln();
			$pdf->Cell(40,7,'D. Bus',1,0,'L',false);
			$pdf->Cell(45,7,'',1,0,'L',false);
			$pdf->Cell(30,7,$operational['bus'],1,0,'C',false);
			$pdf->Ln();

			if(!empty($attachment))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'ATTACHMENTS');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				foreach($attachment as $att)
				{
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/7/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}

			$pdf->Output('F', $filename, false);
		}
	}

	public function exportmodtopdf($id, $site_id = 0) {
		require_once('fpdf/mc_table.php');

		$params['id'] = $id;
		if(!empty($site_id))
		{
			$this->site_id = $site_id;
			Zend_Registry::set('site_id', $this->site_id);
			Zend_Loader::LoadClass('siteClass', $this->modelDir);
			$siteClass = new siteClass();
			$curSite = $siteClass->getSiteById($site_id);
			$this->ident['site_fullname'] =  $curSite['site_fullname'];
		}

		Zend_Loader::LoadClass('modClass', $this->modelDir);
		$modClass = new modClass();
		
		if(!empty($params['id'])) 
		{
			$mod = $modClass->getReportById($params['id']);
			$datetime = explode(" ",$mod['created_date']);
			
			$filename = $this->config->paths->html.'/pdf_report/mod/' . $this->site_id."_mod_".$params['id'].".pdf";			
			$date = explode("-",$datetime[0]);

			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$mod['report_date'] = date("l, j F Y", $r_date);	

			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			$parkingReport = $parkingClass->getReportByDate($datetime[0]);
			$mod['car_parking'] = $parkingReport['inhouse_carcount_mobil'];
			$mod['car_drop_off'] = $parkingReport['inhouse_carcount_drop_off'];
			$mod['valet_parking'] = $parkingReport['inhouse_carcount_valet_reg'];
			$mod['box_vehicle'] = $parkingReport['inhouse_carcount_box'];
			$mod['taxi_bluebird'] = $parkingReport['inhouse_carcount_taxi'];
			$mod['motorbike'] = $parkingReport['inhouse_carcount_motor'];
			
			Zend_Loader::LoadClass('issueClass', $this->modelDir);
			$issueClass = new issueClass();
			$utilitySpecificReport = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '10', $datetime[0]);
			
			Zend_Loader::LoadClass('securityClass', $this->modelDir);
			$securityClass = new securityClass();
			//$securitySpecificReport = $securityClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
			$security_solved_specific_report = $securityClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$security_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '1', $datetime[0]);
			$securitySpecificReport = array_merge($security_solved_specific_report, $security_issues);

			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();
			//$safetySpecificReport = $safetyClass->getSpecificReportByReport('mod_report_id', $params['id'], $datetime[0]);
			$safety_solved_specific_report = $safetyClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$safety_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '3', $datetime[0]);
			$safetySpecificReport = array_merge($safety_solved_specific_report, $safety_issues);
			
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();		
			$progressreportTable = $this->loadModel('progressreport');
			$hk_progress_report_shift = $progressreportTable->getHousekeepingProgressReportByReport('mod_report_id', $params['id']);
			$hk_other_info = $progressreportTable->getHousekeepingOtherInfoByReport('mod_report_id', $params['id']);
			$hk_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '2', $datetime[0]);
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();
			//$parkingSpecificReport = $parkingClass->getSpecificReportByReport('mod_report_id', $params['id']);
			$parking_solved_specific_report = $parkingClass->getMODSolvedSpecificReports($params['id'], $datetime[0]);
			$parking_issues = $issueClass->getIssueByReportAndCatId('mod_report_id', $params['id'], '5', $datetime[0]);
			$parkingSpecificReport = array_merge($parking_solved_specific_report, $parking_issues);

			$staff_condition_operasional = $modClass->getStaffCondition($this->site_id, '0', $params['id']);
			$staff_condition_non_operasional = $modClass->getStaffCondition($this->site_id, '1', $params['id']);
			
			$events = $modClass->getEvents($params['id']);
			$mall_condition = $modClass->getMallConditions($params['id']);
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getModEquipments($params['id']);
			
			$issueTable = $this->loadModel('issue');
			$incident = $issueTable->getIssueByDateAndType($datetime[0], 1);
			$commentsTable = $this->loadModel('comments');
			foreach($incident as &$ir)
			{
				if($ir['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($ir['solved_date']);
					$ir['comment'] = $c['comment'];
				}
			}
			$lostFound = $issueTable->getIssueByDateAndType($datetime[0], 3);
			foreach($lostFound as &$lf)
			{
				if($lf['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($lf['solved_date']);
					$lf['comment'] = $c['comment'];
				}
			}
				
			$glitch = $issueTable->getIssueByDateAndType($datetime[0], 2);
			foreach($glitch as &$gl)
			{
				if($gl['solved'] == 1)
				{
					$c = $commentsTable->getCommentByCommentDate($gl['solved_date']);
					$gl['comment'] = $c['comment'];
				}
			}
			$attachment = $modClass->getAttachments($params['id']);

			$users = $modClass->getUsersByReport($params['id']);
			if(!empty($users))
			{
				if($datetime[0] < "2019-06-11")	$name = $name.", ";
				else $name = "";
				foreach($users as $u)
				{
					$name .= $u['name'].", ";
				}
				$name = substr($name,0,-2);
			}
			else $name = $mod['name'];			
			
			/*** END OF SPECIFIC REPORT ***/

			$pdf=new PDF_MC_Table();

			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Manager On Duty Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Manager On Duty Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$name);
			$pdf->ln(7);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(50,7,'Day / Date',1,0,'L');
			$pdf->Cell(138,7,$mod['report_date'],1,0,'L');
			$pdf->Ln(12);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'ISSUES');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'A. BUILDING SERVICE');
			$pdf->Ln();

			if(!empty($utilitySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($utilitySpecificReport as $usr) {
					$completion_date = explode(" ", $usr['solved_date']);
					$bs_completion_date = date("j M Y", strtotime($completion_date[0]));
					$x1 = $pdf->GetY();
					$pdf->Row(array("",$usr['location'], $usr['description'], $bs_completion_date."\n\n\n\n"));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);		
					$usr['picture'] = str_replace(".","_thumb.",$usr['picture']);				
					if(!empty($usr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$usr['picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$usr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$usr['picture'],13,$y, $w, $h);
					}
					$usr['solved_picture'] = str_replace(".","_thumb.",$usr['solved_picture']);	
					if(!empty($usr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$usr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$usr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$usr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Building Service Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'B. SAFETY');
			$pdf->Ln();

			if(!empty($safetySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($safetySpecificReport as $sasr) {
					if(!empty($sasr['completion_date']) && $sasr['completion_date']!="0000-00-00 00:00:00") $safety_comp_date = $sasr['completion_date'];
					else $safety_comp_date = $sasr['solved_date'];
					$saf_completion_date = explode(" ", $safety_comp_date);
					$safety_completion_date = date("j M Y", strtotime($saf_completion_date[0]));
					if(!empty($sasr['issue_id'])) $sasr['detail'] = $sasr['description'];

					$x1 = $pdf->GetY();
					$pdf->Row(array("",$sasr['location'], $sasr['detail'], $safety_completion_date."\n\n\n\n"));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);			
					$sasr['picture'] = str_replace(".","_thumb.",$sasr['picture']);		
					if(!empty($sasr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sasr['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sasr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sasr['picture'],13,$y, $w, $h);
					}
					$sasr['solved_picture'] = str_replace(".","_thumb.",$sasr['solved_picture']);		
					if(!empty($sasr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sasr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sasr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sasr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Safety Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'C. SECURITY');
			$pdf->Ln();

			if(!empty($securitySpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(35,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(81,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 35, 81, 30));	

				foreach($securitySpecificReport as $sesr) {
					if(!empty($sesr['completion_date']) && $sesr['completion_date']!="0000-00-00 00:00:00") $security_comp_date = $sesr['completion_date'];
					else $security_comp_date = $sesr['solved_date'];
					$sec_completion_date = explode(" ", $security_comp_date);
					$security_completion_date = date("j M Y", strtotime($sec_completion_date[0]));
					if(!empty($sesr['issue_id'])) $sesr['detail'] = $sesr['description'];

					$y1 = $pdf->GetY();
					$pdf->Row(array("",$sesr['location'], $sesr['detail'], $security_completion_date."\n\n\n\n"));

					$y2= $pdf->GetY();
					if($y2<$y1) $y = 11;
					else $y = $pdf->GetY()-($y2-$y1-1);		
					$sesr['picture'] = str_replace(".","_thumb.",$sesr['picture']);				
					if(!empty($sesr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sesr['picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sesr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sesr['picture'],13,$y, $w, $h);
					}
					$sesr['solved_picture'] = str_replace(".","_thumb.",$sesr['solved_picture']);	
					if(!empty($sesr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$sesr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$sesr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$sesr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Security Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'D. HOUSEKEEPING');
			$pdf->Ln();

			if(!empty($hk_progress_report_shift) || !empty($hk_other_info) || !empty($hk_issues))
			{	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(60,7,'Foto',1,0,'C',true);
				$pdf->Cell(59,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(40,7,'Status',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(60, 59, 40, 30));	

				if(!empty($hk_progress_report_shift)) { 				
					foreach($hk_progress_report_shift as $hk_pr_shift) { 
						$hk_progress_report_shift_completion_date = explode(" ", $hk_pr_shift['completion_date']);
						$hk_progress_report_shift_comp_date = date("j M Y", strtotime($hk_progress_report_shift_completion_date[0]));
						
						$y1 = $pdf->GetY();
						$pdf->Row(array("",$hk_pr_shift['area'], $hk_pr_shift['status'], $hk_progress_report_shift_comp_date."\n\n\n\n"));
						$y2= $pdf->GetY();
						if($y2<$y1) $y = 11;
						else $y = $pdf->GetY()-($y2-$y1-1);		
						$hk_pr_shift['img_before'] = str_replace(".","_thumb.",$hk_pr_shift['img_before']);		
						if(!empty($hk_pr_shift['img_before'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_before']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_before'],12,$y, $w, $h);
						}
						if(!empty($hk_pr_shift['img_progress'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_progress']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_progress'],31,$y, $w, $h);
						}
						if(!empty($hk_pr_shift['img_after'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hk_pr_shift['img_after']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hk_pr_shift['img_after'],50,$y, $w, $h);
						}
					}
				}

				if(!empty($hk_other_info)) { 				
					foreach($hk_other_info as $hkotherinfo) { 
						$hk_other_info_completion_date = explode(" ", $hkotherinfo['completion_date']);
						$hk_other_info_comp_date = date("j M Y", strtotime($hk_other_info_completion_date[0]));
						
						$y1 = $pdf->GetY();
						$pdf->Row(array("",$hkotherinfo['area'], $hk_progress_report_shift['status'], $hk_other_info_comp_date."\n\n\n\n"));
						$y2= $pdf->GetY();
						if($y2<$y1) $y = 11;
						else $y = $pdf->GetY()-($y2-$y1-1);		
						$hkotherinfo['img_before'] = str_replace(".","_thumb.",$hkotherinfo['img_before']);		
						if(!empty($hkotherinfo['img_progress'])) 
						{
							list($width, $height) = getimagesize($this->config->paths->html.'/images/progress_report/'.$hkotherinfo['img_progress']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/progress_report/'.$hkotherinfo['img_progress'],13,$y, $w, $h);
						}
					}
				}

				if(!empty($hk_issues))
				{
					foreach($hk_issues as $hk_issue) {
						$hk_comp_date = $hk_issue['solved_date'];
						$comp_date = explode(" ", $hk_comp_date);
						$hk_completion_date = date("j M Y", strtotime($comp_date[0]));
						$x1 = $pdf->GetY();
						$pdf->Row(array("",$hk_issue['location'], $hk_issue['description'], $hk_completion_date."\n\n\n\n"));

						$x2= $pdf->GetY();
						if($x2<$x1) $y = 11;
						else $y = $pdf->GetY()-($x2-$x1-1);		
						$hk_issue['picture'] = str_replace(".","_thumb.",$hk_issue['picture']);				
						if(!empty($hk_issue['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['picture'])) {
							list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['picture']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/issues/'.$hk_issue['picture'],13,$y, $w, $h);
						}
						$hk_issue['solved_picture'] = str_replace(".","_thumb.",$hk_issue['solved_picture']);	
						if(!empty($hk_issue['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['solved_picture'])) {
							list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$hk_issue['solved_picture']);
							if($width > $height)
							{
								$w = 18;
								$h = 0;
							}
							else {
								$w = 0;
								$h = 18;
							}
							$pdf->Image($this->config->general->url.'images/issues/'.$hk_issue['solved_picture'],32,$y, $w, $h);
						}
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Housekeeping Issue');
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'E. PARKING & TRAFFIC');
			$pdf->Ln();

			if(!empty($parkingSpecificReport)) { 	
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(43,7,'Foto',1,0,'C',true);
				$pdf->Cell(20,7,'Time',1,0,'C',true);
				$pdf->Cell(30,7,'Lokasi',1,0,'C',true);
				$pdf->Cell(66,7,'Deskripsi',1,0,'C',true);
				$pdf->Cell(30,7,'Completion Date',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(43, 20, 30, 66, 30));	

				foreach($parkingSpecificReport as $psr) {
					if(!empty($psr['completion_date'])) $parking_comp_date = $psr['completion_date'];
					else $parking_comp_date = $psr['solved_date'];
					$park_completion_date = explode(" ", $parking_comp_date);
					$parking_completion_date = date("j M Y", strtotime($park_completion_date[0]));

					if(!empty($psr['issue_id'])) $psr['detail'] = $psr['description'];

					if($psr['issue_type_id'] != 4) $time= $psr['time'];
					if($psr['issue_type_id'] != 6) { 
						if($psr['issue_type_id'] < 4) $location= $psr['location']; 
						else $location= $psr['area'];
					}

					$x1 = $pdf->GetY();
					$pdf->Row(array("\n\n\n\n", $psr['time'], $location, $psr['detail'], $parking_completion_date));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);			
					$psr['picture'] = str_replace(".","_thumb.",$psr['picture']);		
					if(!empty($psr['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$psr['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$psr['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$psr['picture'],13,$y, $w, $h);
					}
					$psr['solved_picture'] = str_replace(".","_thumb.",$psr['solved_picture']);		
					if(!empty($psr['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$psr['solved_picture'])) {
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$psr['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$psr['solved_picture'],32,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}
			else
			{
				$pdf->SetFont('');
				$pdf->Write(10,'No Parking & Traffic Issue');
				$pdf->Ln();
			}
			$pdf->Ln(5);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'JUMLAH PETUGAS');
			$pdf->Ln(); 	
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'A. INHOUSE');
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(30,7,'','LRT',0,'C',true);
			$pdf->Cell(100,7,'Jumlah','LRT',0,'C',true);
			$pdf->Cell(59,7,'','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(30,7,'Divisi','LR',0,'C',true);
			$pdf->Cell(20,7,'Shift 1','LRT',0,'C',true);
			$pdf->Cell(20,7,'Middle','LRT',0,'C',true);
			$pdf->Cell(20,7,'Shift 2','LRT',0,'C',true);
			$pdf->Cell(20,7,'Shift 3','LRT',0,'C',true);
			$pdf->Cell(20,7,'Absent','LRT',0,'C',true);
			$pdf->Cell(59,7,'Keterangan','LR',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(30, 20, 20, 20, 20, 20, 59));	
			$pdf->Row(array('Engineering', $mod['inhouse_engineering_shift1'],  $mod['inhouse_engineering_middle'],  $mod['inhouse_engineering_shift2'],  $mod['inhouse_engineering_shift3'],  $mod['inhouse_engineering_absent'],  $mod['inhouse_engineering_keterangan']));
			$pdf->Row(array('BS', $mod['inhouse_bs_shift1'],  $mod['inhouse_bs_middle'],  $mod['inhouse_bs_shift2'],  $mod['inhouse_bs_shift3'],  $mod['inhouse_bs_absent'],  $mod['inhouse_bs_keterangan']));
			$pdf->Row(array('Tenant Relation', $mod['inhouse_tr_shift1'],  $mod['inhouse_tr_middle'],  $mod['inhouse_tr_shift2'],  $mod['inhouse_tr_shift3'],  $mod['inhouse_tr_absent'],  $mod['inhouse_tr_keterangan']));
			$pdf->Row(array('Security', $mod['inhouse_security_shift1'],  $mod['inhouse_security_middle'],  $mod['inhouse_security_shift2'],  $mod['inhouse_security_shift3'],  $mod['inhouse_security_absent'],  $mod['inhouse_security_keterangan']));
			$pdf->Row(array('Safety', $mod['inhouse_safety_shift1'],  $mod['inhouse_safety_middle'],  $mod['inhouse_safety_shift2'],  $mod['inhouse_safety_shift3'],  $mod['inhouse_safety_absent'],  $mod['inhouse_safety_keterangan']));
			$pdf->Row(array('Parking', $mod['inhouse_parking_shift1'],  $mod['inhouse_parking_middle'],  $mod['inhouse_parking_shift2'],  $mod['inhouse_parking_shift3'],  $mod['inhouse_parking_absent'],  $mod['inhouse_parking_keterangan']));
			$pdf->Row(array('Housekeeping', $mod['inhouse_housekeeping_shift1'],  $mod['inhouse_housekeeping_middle'],  $mod['inhouse_housekeeping_shift2'],  $mod['inhouse_housekeeping_shift3'],  $mod['inhouse_housekeepingg_absent'],  $mod['inhouse_housekeeping_keterangan']));
			$pdf->Row(array('Customer Service', $mod['inhouse_reception_shift1'],  $mod['inhouse_reception_middle'],  $mod['inhouse_reception_shift2'],  $mod['inhouse_reception_shift3'],  $mod['inhouse_reception_absent'],  $mod['inhouse_reception_keterangan']));
			$pdf->Ln();

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'B. OUTSOURCE');
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(30,7,'','LRT',0,'C',true);
			$pdf->Cell(100,7,'Jumlah','LRT',0,'C',true);
			$pdf->Cell(59,7,'','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->Cell(30,7,'Divisi','LR',0,'C',true);
			$pdf->Cell(20,7,'Shift 1','LRT',0,'C',true);
			$pdf->Cell(20,7,'Middle','LRT',0,'C',true);
			$pdf->Cell(20,7,'Shift 2','LRT',0,'C',true);
			$pdf->Cell(20,7,'Shift 3','LRT',0,'C',true);
			$pdf->Cell(20,7,'Absent','LRT',0,'C',true);
			$pdf->Cell(59,7,'Keterangan','LR',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(30, 20, 20, 20, 20, 20, 59));	
			$pdf->Row(array('Security', $mod['outsource_security_safety_shift1'],  $mod['outsource_security_safety_middle'],  $mod['outsource_security_safety_shift2'],  $mod['outsource_security_safety_shift3'],  $mod['outsource_security_safety_absent'],  $mod['outsource_security_safety_keterangan']));
			$pdf->Row(array('Safety', $mod['outsource_safety_shift1'],  $mod['outsource_safety_middle'],  $mod['outsource_safety_shift2'],  $mod['outsource_safety_shift3'],  $mod['outsource_safety_absent'],  $mod['outsource_safety_keterangan']));
			$pdf->Row(array('Parking', $mod['outsource_parking_shift1'],  $mod['outsource_parking_middle'],  $mod['outsource_parking_shift2'],  $mod['outsource_parking_shift3'],  $mod['outsource_parking_absent'],  $mod['outsource_parking_keterangan']));
			$pdf->Row(array('Valet', $mod['outsource_valet_shift1'],  $mod['outsource_valet_middle'],  $mod['outsource_valet_shift2'],  $mod['outsource_valet_shift3'],  $mod['outsource_valet_absent'],  $mod['outsource_valet_keterangan']));
			$pdf->Row(array('Housekeeping', $mod['outsource_housekeeping_shift1'],  $mod['outsource_housekeeping_middle'],  $mod['outsource_housekeeping_shift2'],  $mod['outsource_housekeeping_shift3'],  $mod['outsource_housekeeping_absent'],  $mod['outsource_housekeeping_keterangan']));
			$pdf->Row(array('Pest Control', $mod['outsource_pest_control_shift1'],  $mod['outsource_pest_control_middle'],  $mod['outsource_pest_control_shift2'],  $mod['outsource_pest_control_shift3'],  $mod['outsource_pest_control_absent'],  $mod['outsource_pest_control_keterangan']));
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(30,7,'TOTAL',1,0,'C',true);
			$pdf->Cell(20,7,$mod['total_shift1'],1,0,'C',true);
			$pdf->Cell(20,7,$mod['total_middle'],1,0,'C',true);
			$pdf->Cell(20,7,$mod['total_shift2'],1,0,'C',true);
			$pdf->Cell(20,7,$mod['total_shift3'],1,0,'C',true);
			$pdf->Cell(20,7,$mod['total_absent'],1,0,'C',true);
			$pdf->Cell(59,7,$mod['total_keterangan'],1,0,'C',true);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetFont('Arial','B',11);
			$pdf->SetTextColor(0,0,0);
			$pdf->Write(10,'JUMLAH KENDARAAN MASUK');
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(100,7,'Jenis Kendaraan','LRT',0,'C',true);
			$pdf->Cell(89,7,'Jumlah','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(100, 89));	
			$pdf->Row(array("Car Count Parking",$mod['car_parking']));
			$pdf->Row(array("Car Count Drop Off",$mod['car_drop_off']));
			$pdf->Row(array("Box Vehicle",$mod['box_vehicle']));
			$pdf->Row(array("Motorbike",$mod['motorbike']));
			$pdf->Row(array("Bus",$mod['bus']));
			$pdf->Row(array("Valet Service",$mod['valet_parking']));
			$pdf->Row(array("Taxi Bluebird",$mod['taxi_bluebird']));
			$pdf->Row(array("Taxi Non Blue bird",$mod['taxi_non_bluebird']));
			$pdf->Ln();

			if(!empty($equipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'FASILITAS/PERALATAN');
				$pdf->Ln(); 
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(50,7,'Nama Fasilitas/Peralatan','LRT',0,'C',true);
				$pdf->Cell(50,7,'Kondisi (Foto bila ada)','LRT',0,'C',true);
				$pdf->Cell(39,7,'Lantai/Area','LRT',0,'C',true);
				$pdf->Cell(50,7,'Keterangan','LRT',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('','',10);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(50, 50, 39, 50));	
				foreach($equipments as $equipment) {
					if(!empty($equipment['image'])) $equipment_name = $equipment['equipment_name']."\n\n\n\n";
					else $equipment_name = $equipment['equipment_name'];

					$x1 = $pdf->GetY();
					$pdf->Row(array($equipment_name,"", htmlentities(stripslashes($equipment['area'])), $equipment['keterangan']));

					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);						
					$equipment['image'] = str_replace(".","_thumb.",$equipment['image']);		
					if(!empty($equipment['image']) && @getimagesize($this->config->paths->html.'/images/equipment/'.$equipment['image'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/equipment/'.$equipment['image']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/equipment/'.$equipment['image'],53,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}

			if(!empty($incident)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'INCIDENT');
				$pdf->Ln(); 
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(78,7,'Nama Insiden','LRT',0,'C',true);
				$pdf->Cell(23,7,'Kondisi (Foto)','LRT',0,'C',true);
				$pdf->Cell(28,7,'Lantai/Area','LRT',0,'C',true);
				$pdf->Cell(28,7,'Status','LRT',0,'C',true);
				$pdf->Cell(35,7,'Keterangan','LRT',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('','',10);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(78, 23, 28, 28, 35));	
				foreach($incident as $inc) {
					if(empty($inc['status'])) $inc['status'] = $inc['comment'];
					$x1 = $pdf->GetY();
					$pdf->Row(array($inc['description'],"", $inc['location'], $inc['status']."\n\n\n\n\n\n\n\n", $inc['keterangan']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);	
					$inc['picture'] = str_replace(".","_thumb.",$inc['picture']);		
					if(!empty($inc['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$inc['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$inc['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$inc['picture'],91,$y, $w, $h);
					}

					$inc['solved_picture'] = str_replace(".","_thumb.",$inc['solved_picture']);		
					if(!empty($inc['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$inc['solved_picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$inc['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$inc['solved_picture'],91,$y+20, $w, $h);
					}
				}
				$pdf->Ln();
			}

			if(!empty($lostFound)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'LOST & FOUND');
				$pdf->Ln(); 
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(48,7,'Kejadian','LRT',0,'C',true);
				$pdf->Cell(45,7,'Informasi Pelapor (Foto)','LRT',0,'C',true);
				$pdf->Cell(30,7,'Lantai/Area','LRT',0,'C',true);
				$pdf->Cell(30,7,'Status','LRT',0,'C',true);
				$pdf->Cell(35,7,'Keterangan','LRT',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('','',10);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(48, 45, 30, 30, 35));	
				foreach($lostFound as $lf) {
					if(empty($lf['status'])) $lf['status'] = $lf['comment'];
					$x1 = $pdf->GetY();
					$pdf->Row(array($lf['description'],"", $lf['location'], $lf['status']."\n\n\n\n", $lf['keterangan']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);	
					$lf['picture'] = str_replace(".","_thumb.",$lf['picture']);		
					if(!empty($lf['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$lf['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$lf['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$lf['picture'],59,$y, $w, $h);
					}

					$lf['solved_picture'] = str_replace(".","_thumb.",$lf['solved_picture']);		
					if(!empty($inc['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$lf['solved_picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$lf['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$lf['solved_picture'],78,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}

			

			if(!empty($glitch)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'GLITCH');
				$pdf->Ln(); 
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(42,7,'Pelanggaran','LRT',0,'C',true);
				$pdf->Cell(42,7,'Foto Pelanggaran','LRT',0,'C',true);
				$pdf->Cell(33,7,'Lantai/Area','LRT',0,'C',true);
				$pdf->Cell(37,7,'Tindakan Perbaikan','LRT',0,'C',true);
				$pdf->Cell(35,7,'Keterangan','LRT',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('','',10);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(42, 42, 33, 37, 35));	
				foreach($glitch as $g) {
					if(empty($g['status'])) $g['status'] = $g['comment'];
					$x1 = $pdf->GetY();
					$pdf->Row(array($g['description'],"", $g['location'], $g['status']."\n\n\n\n", $g['keterangan']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);	
					$g['picture'] = str_replace(".","_thumb.",$g['picture']);		
					if(!empty($g['picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$g['picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$g['picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$g['picture'],53,$y, $w, $h);
					}

					$g['solved_picture'] = str_replace(".","_thumb.",$g['solved_picture']);		
					if(!empty($g['solved_picture']) && @getimagesize($this->config->paths->html.'/images/issues/'.$g['solved_picture'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/issues/'.$g['solved_picture']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/issues/'.$g['solved_picture'],72,$y, $w, $h);
					}
				}
				$pdf->Ln();
			}

			if(!empty($mod['way_system_img']) && @getimagesize($this->config->paths->html.'/images/way_system/'.$mod['way_system_img'])) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'WAY SYSTEM');
				$pdf->Ln(); 
				if($width > $height)
				{
					$w = 18;
					$h = 0;
				}
				else {
					$w = 0;
					$h = 18;
				}
				$pdf->Image($this->config->general->url.'images/way_system/'.$mod['way_system_img'],$pdf->GetX(),$pdf->GetY(), $w, $h);
				$pdf->Ln(); 
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'SG');
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(100,7,'Info','LRT',0,'C',true);
			$pdf->Cell(89,7,'Jumlah','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(100, 89));	
			$pdf->Row(array("Absent",$mod['sg_absent']));
			$pdf->Row(array("Subtitute",$mod['sg_subtitute']));
			$pdf->Row(array("Subtitute (No Beacon)",$mod['sg_subtitute_no_beacon']));
			$pdf->Row(array("Negligence",$mod['sg_negligence']));
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'HK');
			$pdf->Ln(); 
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(100,7,'Info','LRT',0,'C',true);
			$pdf->Cell(89,7,'Jumlah','LRT',0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('','',10);
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(100, 89));	
			$pdf->Row(array("Absent",$mod['hk_absent']));
			$pdf->Row(array("Subtitute",$mod['hk_subtitute']));
			$pdf->Row(array("Subtitute (No Beacon)",$mod['hk_subtitute_no_beacon']));
			$pdf->Row(array("Negligence",$mod['hk_negligence']));
			$pdf->Ln();

			if(!empty($events)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'EVENT');
				$pdf->Ln(); 
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(56,7,'Nama Event','LRT',0,'C',true);
				$pdf->Cell(42,7,'Kondisi Event (Foto)','LRT',0,'C',true);
				$pdf->Cell(35,7,'Lantai','LRT',0,'C',true);
				$pdf->Cell(56,7,'Status Event','LRT',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('','',10);
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(56, 42, 35, 56));	
				foreach($events as $event) {
					$x1 = $pdf->GetY();
					$pdf->Row(array($event['event_name'],"\n\n\n\n\n", $event['event_location'], $event['event_status']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 11;
					else $y = $pdf->GetY()-($x2-$x1-1);	
					$event['event_img'] = str_replace(".","_thumb.",$event['event_img']);		
					if(!empty($event['event_img']) && @getimagesize($this->config->paths->html.'/images/event/'.$event['event_img'])) {						
						list($width, $height) = getimagesize($this->config->paths->html.'/images/event/'.$event['event_img']);
						if($width > $height)
						{
							$w = 18;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 18;
						}
						$pdf->Image($this->config->general->url.'images/event/'.$event['event_img'],69,$y, $w, $h);
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
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/8/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}

			$pdf->Output('F', $filename, false);
		}
	}

	public function exportspvsecuritytopdf($id, $site_id = 0) {
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
			
			$filename = $this->config->paths->html.'/pdf_report/security/' . $this->site_id."_spv_".$params['id'].".pdf";

			require_once('fpdf/mc_table.php');

			$datetime = explode(" ",$security['created_date']);
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$report_date = date("l, j F Y", $r_date);
			
			
			$defect_list = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '4');
			$incident = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '1');
			$glitch = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '2');
			$lost_found = $securityClass->getSpecificReportBySecurityIdIssueType($params['id'], '3');
			
			$attachment = $securityClass->getSpvAttachments($params['id']);

			Zend_Loader::LoadClass('vendorClass', $this->modelDir);
			$vendorClass = new vendorClass();
			$vendor = $vendorClass->getVendor($this->site_id);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Security Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Report (Security Report)');
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
			$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(33,7,'Area',1,0,'L',true);
				$pdf->Cell(93,7,'Details',1,0,'L',true);
				$pdf->Cell(63,7,'Follow up',1,0,'L',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(33,93,63));
				foreach($defect_list as $dl)
				{
					$pdf->Row(array($dl['area'],$dl['detail'],$dl['follow_up']));
				}			
				$pdf->Ln();
			}
			
			
			if(!empty($incident))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'INCIDENT REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(33,7,'Date & Time',1,0,'L',true);
				$pdf->Cell(93,7,'Description',1,0,'L',true);
				$pdf->Cell(63,7,'Status',1,0,'L',true);
				$pdf->Ln();
				$pdf->SetFont('');	
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(33,93,63));
				foreach($incident as $i)
				{
					$issue_date_time = explode(" ",$i['issue_date']);
					$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
					$pdf->Row(array($issue_datetime,$i['description'],$i['status']));
				}
				$pdf->Ln();
			}
			
				
			if(!empty($glitch))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'GLITCH');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(33,7,'Date & Time',1,0,'L',true);
				$pdf->Cell(93,7,'Description',1,0,'L',true);
				$pdf->Cell(63,7,'Status',1,0,'L',true);
				$pdf->Ln();
				$pdf->SetFont('');	
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(33,93,63));
				foreach($glitch as $g)
				{
					$issue_date_time = explode(" ",$g['issue_date']);
					$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
					$pdf->Row(array($issue_datetime,$g['description'],$g['status']));
				}
				$pdf->Ln();
			}
			
				
			if(!empty($lost_found))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'LOST & FOUND');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(33,7,'Date & Time',1,0,'L',true);
				$pdf->Cell(93,7,'Description',1,0,'L',true);
				$pdf->Cell(63,7,'Status',1,0,'L',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(33,93,63));
				foreach($lost_found as $lf)
				{
					$issue_date_time = explode(" ",$lf['issue_date']);
					$issue_datetime = date("j M Y", strtotime($issue_date_time[0]))." ".$issue_date_time[1];
					$pdf->Row(array($issue_datetime,$lf['description'],$lf['status']));
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
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}
			$pdf->Ln();
			
			$pdf->Output('F', $filename, false);
		}
	}

	public function exportchiefsecuritytopdf($id, $site_id = 0) {	
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
				
			$filename = $this->config->paths->html.'/pdf_report/security/' . $this->site_id."_chief_".$id.".pdf";
		
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
			$pdf->SetTitle($this->ident['initial']." - Chief Security Daily Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Chief Security Report');
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
			$pdf->SetFillColor(9,41,102);
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
			$pdf->SetFillColor(9,41,102);
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
				$pdf->SetFillColor(9,41,102);
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
					$pdf->SetFillColor(9,41,102);
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
					$pdf->SetFillColor(9,41,102);
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
				$pdf->Write(10,'SPECIFIC REPORT');
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

			$pdf->Output('F', $filename, false);
		}
	}

	public function exportsafetytopdf($id, $site_id = 0) {
		$params['id'] = $id;
		if(!empty($id))
		{
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
			
			Zend_Loader::LoadClass('safetyClass', $this->modelDir);
			$safetyClass = new safetyClass();

			$safety = $safetyClass->getReportById($params['id']);
			$datetime = explode(" ",$safety['created_date']);

			$filename = $this->config->paths->html.'/pdf_report/safety/' . $this->site_id."_safety_".$params['id'].".pdf";		
			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$safety['report_date'] = date("l, j F Y", $r_date);	
			$safety['yesterday_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2])-1, intval($date[0])));
			$safety['today_date'] = date("d", mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0])));
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments_ab = $equipmentTable->getSafetyEquipments('ab', $params['id']);
			$equipments_c1 = $equipmentTable->getSafetyEquipments('c1', $params['id']);
			$equipments_c2 = $equipmentTable->getSafetyEquipments('c2', $params['id']);	
			
			$trainingTable = $this->loadModel('training');
			$training_activity = $trainingTable->getSafetyTrainingActivity();
			
			if(!empty($params['id']))
			{
				$outsourceTraining = $trainingTable->getSafetyTrainingByType($params['id'],'1');
				$inHouseTraining = $trainingTable->getSafetyTrainingByType($params['id'],'2');
				if(count($outsourceTraining) > count($inHouseTraining)) $totalTraining = count($outsourceTraining);
				else $totalTraining = count($inHouseTraining);
			}
			
			/*** SPECIFIC REPORT ***/
			
			$specific_report = $safetyClass->getSpecificReportById($params['id']);
			foreach($specific_report as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			$specific_reports = $specific_report;
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			
			$attachment = $safetyClass->getAttachments($params['id']);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Daily Safety Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Safety Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(40,7,'Day / Date',1,0,'L');
			$pdf->Cell(148,7,$safety['report_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(40,7,'Reporting Date',1,0,'L');
			$pdf->Cell(98,7,$safety['yesterday_date'],1,0,'C');
			$pdf->Cell(50,7,$safety['today_date'],1,0,'C');
			$pdf->Ln();
			$pdf->Cell(40,7,'Reporting Time',1,0,'L');
			$pdf->Cell(49,7,$setting['safety_afternoon_reporting_time'],1,0,'C');
			$pdf->Cell(49,7,$setting['safety_night_reporting_time'],1,0,'C');
			$pdf->Cell(50,7,$setting['safety_morning_reporting_time'],1,0,'C');
			$pdf->Ln(12);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(63,7,$setting['safety_afternoon_reporting_time'],1,0,'C',true);
			$pdf->Cell(63,7,$setting['safety_night_reporting_time'],1,0,'C',true);
			$pdf->Cell(63,7,$setting['safety_morning_reporting_time'],1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(63,63,63));			
			$pdf->Row(array($safety['man_power_afternoon'],$safety['man_power_night'],$safety['man_power_morning']));
			$pdf->Ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'PERLENGKAPAN');
			$pdf->Ln();
			if(!empty($equipments_ab)) {
				
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'No','LTR',0,'C',true);
				$pdf->Cell(38,7,'Equipment Name','LTR',0,'C',true);
				$pdf->Cell(38,7,'Item','LTR',0,'C',true);
				$pdf->Cell(31,7,'Status Normal',1,0,'C',true);
				$pdf->Cell(31,7,'Shift 3 23:00',1,0,'C',true);
				$pdf->Cell(31,7,'Shift 1 07:00','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(20, 38, 38, 31, 31, 31));	
				$i = 0;
				foreach($equipments_ab as $equipmentab) {
					$pdf->Row(array($equipmentab['no'],$equipmentab['equipment_name'],$equipmentab['item_name'], $equipmentab['status'], $equipmentab['shift2'], $equipmentab['shift3']));
					$i++; 
				} 
				$pdf->Ln();
			}

			if(!empty($equipments_c1)) {
				
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(15,7,'','LTR',0,'C',true);
				$pdf->Cell(32,7,'','LTR',0,'C',true);
				$pdf->Cell(30,7,'','LTR',0,'C',true);
				$pdf->Cell(56,7,"Status Pressure",'LTR',0,'C',true);
				$pdf->Cell(60,7,"Actual Pressure",'LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(15,7,'No','LR',0,'C',true);
				$pdf->Cell(32,7,'Equipment Name','LR',0,'C',true);
				$pdf->Cell(30,7,'Item','LR',0,'C',true);
				$pdf->Cell(56,7,"(bar or PSI or Kgf / cm2)",'LRB',0,'C',true);
				$pdf->Cell(60,7,"(bar or PSI or Kgf / cm2)",'LRB',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(15,7,'','LRB',0,'C',true);
				$pdf->Cell(32,7,'','LRB',0,'C',true);
				$pdf->Cell(30,7,'','LRB',0,'C',true);
				$pdf->Cell(28,7,'Cut In','LTR',0,'C',true);
				$pdf->Cell(28,7,'Cut Off','LTR',0,'C',true);
				$pdf->Cell(30,7,"Shift 3 23:00",1,0,'C',true);
				$pdf->Cell(30,7,"Shift 1 07:00",'LTR',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(15, 32, 30, 28, 28, 30, 30));	
				$i = 0;
				foreach($equipments_c1 as $equipmentc1) {
					if(empty($equipmentc1['status_pressure_cut_in'])) $equipmentc1['status_pressure_cut_in'] = $equipmentc1['status_cut_in'];
					if(empty($equipmentc1['status_pressure_cut_off'])) $equipmentc1['status_pressure_cut_off'] = $equipmentc1['status_cut_off'];
					$pdf->Row(array($equipmentc1['no'],$equipmentc1['equipment_name'],$equipmentc1['item_name'], $equipmentc1['status_pressure_cut_in'], $equipmentc1['status_pressure_cut_off'], $equipmentc1['shift2'], $equipmentc1['shift3']));
					$i++; 
				} 
				$pdf->Ln();
			}

			if(!empty($equipments_c2)) {
				
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(20,7,'No','LTR',0,'C',true);
				$pdf->Cell(72,7,'Tank Condition','LTR',0,'C',true);
				$pdf->Cell(32,7,'Status Normal',1,0,'C',true);
				$pdf->Cell(32,7,'Shift 3 23:00',1,0,'C',true);
				$pdf->Cell(32,7,'Shift 1 07:00','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(20, 72, 32, 32, 32));	
				$i = 0;
				foreach($equipments_c2 as $equipmentc2) {
					$pdf->Row(array($equipmentc2['no'],$equipmentc2['item_name'],$equipmentc2['status'], $equipmentc2['shift2'], $equipmentc2['shift3']));
					$i++; 
				} 
				$pdf->Ln();
			}
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($safety['briefing1']))
			{
				$briefing1 = explode("<br>", $safety['briefing1']);
				$i = 0;
				foreach($briefing1 as $b)
				{
					if(trim($briefing1[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}


			if(!empty($safety['briefing2']))
			{
				$briefing2 = explode("<br>", $safety['briefing2']);
				$i = 0;
				foreach($briefing2 as $b)
				{
					if(trim($briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}

			if(!empty($safety['briefing3']))
			{
				$briefing3 = explode("<br>", $safety['briefing3']);
				$i = 0;
				foreach($briefing3 as $b)
				{
					if(trim($briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}
			
		
			if(!empty($outsourceTraining) || !empty($inHouseTraining))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->ln();

				if(!empty($outsourceTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(9,41,102);
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
					$pdf->SetFillColor(9,41,102);
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

			if(!empty($safety['sop1']) || !empty($safety['sop2']) || !empty($safety['sop3']))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SOSIALISASI SOP');
				$pdf->ln();
				$pdf->SetFont('Arial','',10);
				if(!empty($safety['sop1']))
				{
					$pdf->Write(10,$safety['sop1']);
					$pdf->Ln();
				}
				if(!empty($safety['sop2']))
				{
					$pdf->Write(10,$safety['sop2']);
					$pdf->Ln();
				}
				if(!empty($safety['sop3']))
				{
					$pdf->Write(10,$safety['sop3']);
					$pdf->Ln();
				}
				$pdf->Ln(5);
			}

			if(!empty($specific_reports))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SPECIFIC REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(94,95));
				foreach($specific_reports as $specific_report)
				{
					if($specific_report['issue_type_id'] < 4)
					{
						$specific_report['detail'] = $specific_report['description'];
					}
					
					$issue = strtoupper($specific_report['issue_type_name'])."\nDetail : ".$specific_report['detail'];
					$status = "Status :\n".$specific_report['status'];
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
					//$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/3/f/'.$att['filename']);
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/3/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}

			$pdf->Output('F', $filename, false);
		}
	}

	public function exportparkingtopdf($id, $site_id = 0) {	
		$params['id'] = $id;
		if(!empty($id))
		{
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
			
			Zend_Loader::LoadClass('parkingClass', $this->modelDir);
			$parkingClass = new parkingClass();

			$parking = $parkingClass->getReportById($params['id']);
			$datetime = explode(" ",$parking['created_date']);

			$filename = $this->config->paths->html.'/pdf_report/parking/' . $this->site_id."_parkingtraffic_".$params['id'].".pdf";

			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$parking['report_date'] = date("l, j F Y", $r_date);	
			
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$equipmentTable = $this->loadModel('equipment');
			$equipments = $equipmentTable->getParkingEquipments('1', $params['id']);
			$parkingEquipments = $equipmentTable->getParkingEquipments('2', $params['id']);
			
			$trainingTable = $this->loadModel('training');
			$training_activity = $trainingTable->getParkingTrainingActivity();
			
			if(!empty($params['id']))
			{
				$outsourceTraining = $trainingTable->getParkingTrainingByType($params['id'],'1');
				$inHouseTraining = $trainingTable->getParkingTrainingByType($params['id'],'2');
				if(count($outsourceTraining) > count($inHouseTraining)) $totalTraining = count($outsourceTraining);
				else $totalTraining = count($inHouseTraining);
			}
		
			/*** SPECIFIC REPORT ***/
			
			$specific_report = $parkingClass->getSpecificReportById($params['id']);
			foreach($specific_report as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			$specific_reports = $specific_report;
			foreach($specific_reports as &$sr)
			{
				if(!empty($sr['issue_id']))
				{
					$sr['detail'] = $sr['description'];
					$datetime = explode(" ",$sr['issue_date']);
					$sr['time'] = $datetime[1];
				}
			}
			
			$attachment = $parkingClass->getAttachments($params['id']);

			if($this->site_id == 3) $vendor1 = "CP";
			else $vendor1 = "SPI";

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Daily Parking Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Parking Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(50,7,'Day / Date',1,0,'L');
			$pdf->Cell(138,7,$parking['report_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(50,7,'Time',1,0,'L');
			$pdf->Cell(138,7,$setting['parking_traffic_reporting_time'],1,0,'C');
			$pdf->Ln(12);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(9,41,102);
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
			$pdf->Row(array("SUPERVISOR",$parking['inhouse_spv_malam'],$parking['inhouse_spv_pagi'],$parking['inhouse_spv_siang']));
			$pdf->Row(array("ADMIN",$parking['inhouse_admin_malam'],$parking['inhouse_admin_pagi'],$parking['inhouse_admin_siang']));
			$pdf->Row(array("KEKUATAN",$parking['inhouse_kekuatan_malam'],$parking['inhouse_kekuatan_pagi'],$parking['inhouse_kekuatan_siang']));
			$pdf->SetWidths(array(45, 144));
			$car_count="Mobil : ".$parking['inhouse_carcount_mobil']."\nMotor : ".$parking['inhouse_carcount_motor']."\nBox : ".$parking['inhouse_carcount_box']."\nValet Reg : ".$parking['inhouse_carcount_valet_reg']."\nSelf Valet : ".$parking['inhouse_carcount_self_valet']."\nDrop Off : ".$parking['inhouse_carcount_drop_off']."\nTaxi : ".$parking['inhouse_carcount_taxi']."\nTotal : ".$parking['inhouse_carcount_total'];	
			$pdf->Row(array("CAR COUNT",$car_count));
			$pdf->Ln();

			$pdf->SetFont('Arial','',10);
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(189,7,'VENDOR',1,0,'C',true);
			$pdf->Ln();
			$pdf->Cell(45,7,'',1,0,'L',true);
			$pdf->Cell(72,7,$vendor1,1,0,'C',true);
			$pdf->Cell(72,7,'Valet',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 72, 72));			
			$pdf->Row(array("CPM/ACPM",$parking['vendor_cpm_acpm_spi'],$parking['vendor_cpm_acpm_valet']));
			$pdf->Row(array("PENGAWAS",$parking['vendor_pengawas_spi'],$parking['vendor_pengawas_valet']));
			$pdf->Row(array("ADMIN",$parking['vendor_admin_spi'],$parking['vendor_admin_valet']));
			$pdf->SetWidths(array(45, 144));
			$kekuatan=$vendor1." Pagi : ".$parking['vendor_kekuatan_spi_pagi']."\n".$vendor1." Siang : ".$parking['vendor_kekuatan_spi_siang']."\n".$vendor1." Malam : ".$parking['vendor_kekuatan_spi_malam']."\nValet Pagi : ".$parking['vendor_kekuatan_valet_pagi']."\nValet Siang : ".$parking['vendor_kekuatan_valet_siang']."\nValet Malam : ".$parking['vendor_kekuatan_valet_malam']."\nTaxi Pagi : ".$parking['vendor_kekuatan_taxi_pagi']."\nTaxi Siang : ".$parking['vendor_kekuatan_taxi_siang']."\nTaxi Malam : ".$parking['vendor_kekuatan_taxi_malam'];	
			$pdf->Row(array("KEKUATAN",$kekuatan));
			$pdf->Ln();

			if(!empty($equipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'PERLENGKAPAN');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(60,7,'Nama Perlengkapan','LTR',0,'C',true);
				$pdf->Cell(25,7,'Jumlah','LTR',0,'C',true);
				$pdf->Cell(50,7,'Kondisi',1,0,'C',true);
				$pdf->Cell(54,7,'Keterangan','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(60,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'Ok',1,0,'C',true);
				$pdf->Cell(25,7,'Tidak Ok',1,0,'C',true);
				$pdf->Cell(54,7,'','LRB',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(60, 25, 25, 25, 54));	
				$i = 0;
				foreach($equipments as $equipment) {
					$pdf->Row(array($equipment['equipment_name'],$equipment['total_equipment'], str_replace("√",'v',$equipment['ok_condition']), $equipment['bad_condition'], $equipment['description']));
					$i++; 
				} 
				$pdf->Ln();
			}

			if(!empty($parkingEquipments)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'PERLENGKAPAN PARKIR');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(60,7,'Nama Perlengkapan','LTR',0,'C',true);
				$pdf->Cell(25,7,'Jumlah','LTR',0,'C',true);
				$pdf->Cell(50,7,'Kondisi',1,0,'C',true);
				$pdf->Cell(54,7,'Keterangan','LTR',0,'C',true);
				$pdf->Ln();
				$pdf->Cell(60,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'','LRB',0,'C',true);
				$pdf->Cell(25,7,'Ok',1,0,'C',true);
				$pdf->Cell(25,7,'Tidak Ok',1,0,'C',true);
				$pdf->Cell(54,7,'','LRB',0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(60, 25, 25, 25, 54));	
				$i = 0;
				foreach($parkingEquipments as $equipment) {
					$pdf->Row(array($equipment['equipment_name'],$equipment['total_equipment'], str_replace("√",'v',$equipment['ok_condition']), $equipment['bad_condition'], $equipment['description']));
					$i++; 
				} 
				$pdf->Ln();
			}
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'BRIEFING');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($parking['briefing1']))
			{
				$briefing1 = explode("<br>", $parking['briefing1']);
				$i = 0;
				foreach($briefing1 as $b)
				{
					if(trim($briefing1[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}


			if(!empty($parking['briefing2']))
			{
				$briefing2 = explode("<br>", $parking['briefing2']);
				$i = 0;
				foreach($briefing2 as $b)
				{
					if(trim($briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}

			if(!empty($parking['briefing3']))
			{
				$briefing3 = explode("<br>", $parking['briefing3']);
				$i = 0;
				foreach($briefing3 as $b)
				{
					if(trim($briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}
			
		
			if(!empty($outsourceTraining) || !empty($inHouseTraining))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->ln();

				if(!empty($outsourceTraining))
				{				
					$pdf->SetFont('Arial','',10);
					$pdf->SetFillColor(9,41,102);
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
					$pdf->SetFillColor(9,41,102);
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

			if(!empty($parking['sop1']) || !empty($parking['sop2']) || !empty($parking['sop3']))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SOSIALISASI SOP');
				$pdf->ln();
				$pdf->SetFont('Arial','',10);
				if(!empty($parking['sop1']))
				{
					$pdf->Write(10,$parking['sop1']);
					$pdf->Ln();
				}
				if(!empty($parking['sop2']))
				{
					$pdf->Write(10,$parking['sop2']);
					$pdf->Ln();
				}
				if(!empty($parking['sop3']))
				{
					$pdf->Write(10,$parking['sop3']);
					$pdf->Ln();
				}
				$pdf->Ln(5);
			}

			if(!empty($specific_reports))
			{
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'SPECIFIC REPORT');
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(135,54));
				foreach($specific_reports as $specific_report)
				{
					if($specific_report['issue_type_id'] < 4)
					{
						$specific_report['detail'] = $specific_report['description'];
					}
					$issue = $specific_report['issue_type_name']."\nDetail : ".$specific_report['detail'];
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
					//$pdf->Cell(100,7,"- ".$att['description'],0,1, 'L', false, $this->baseUrl.'/default/attachment/openattachment/c/5/f/'.$att['filename']);
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/5/f/'.$att['filename']);
					$pdf->Ln(8);
				}
			}

			$pdf->Output('F', $filename, false);
		}
	}
	
	public function exporthousekeepingtopdf($id, $site_id = 0) {	
		$params['id'] = $id;
		if(!empty($id))
		{
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
			Zend_Loader::LoadClass('housekeepingClass', $this->modelDir);
			$housekeepingClass = new housekeepingClass();
		
			$housekeeping = $housekeepingClass->getReportById($params['id']);
			
			$datetime = explode(" ",$housekeeping['created_date']);

			$filename = $this->config->paths->html.'/pdf_report/housekeeping/' . $this->site_id."_hk_".$params['id'].".pdf";

			$date = explode("-",$datetime[0]);
			$r_date = mktime(0, 0, 0, intval($date[1]), intval($date[2]), intval($date[0]));
			$housekeeping['report_date'] = date("l, j F Y", $r_date);	
			$housekeeping = $housekeeping;
		
			$settingTable = $this->loadModel('setting');
			$setting = $settingTable->getOtherSetting();
			
			$tangkapanTable = $this->loadModel('tangkapan');
			$hasilTangkapan = $tangkapanTable->getHousekeepingHasilTangkapanByReportId($params['id']);
			
			$workTargetTable = $this->loadModel('worktarget');
			$work_target = $workTargetTable->getHousekeepingWorkTarget($params['id']);
			
			$trainingTable = $this->loadModel('training');
			$training = $trainingTable->getHousekeepingTraining($params['id']);
	
			$progressreportTable = $this->loadModel('progressreport');
			$progress_report_shift12 = $progressreportTable->getHousekeepingProgressReport($params['id'], '12');
			$progress_report_shift3 = $progressreportTable->getHousekeepingProgressReport($params['id'], '3');
			$other_info = $progressreportTable->getHousekeepingOtherInfo($params['id']);
			
			$attachment = $housekeepingClass->getAttachments($params['id']);

			$pdf=new PDF_MC_Table();
			$pdf->AddPage();
			$pdf->SetTitle($this->ident['initial']." - Daily Housekeeping Report");
			$pdf->SetFont('Arial','B',15);
			$pdf->Write(10,'Daily Housekeeping Report');
			$pdf->ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(5,$this->ident['site_fullname']);
			$pdf->ln();

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'DAY / DATE');
			$pdf->Ln();
			$pdf->SetFont('Arial','',10);
			$pdf->Cell(50,7,'Day / Date',1,0,'L');
			$pdf->Cell(138,7,$housekeeping['report_date'],1,0,'L');
			$pdf->Ln();
			$pdf->Cell(50,7,'Time',1,0,'L');
			$pdf->Cell(138,7,$setting['housekeeping_reporting_time'],1,0,'C');
			$pdf->Ln(12);

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'MAN POWER');
			$pdf->Ln();
			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'A. In House');
			$pdf->Ln();
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(45,7,'Description',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("Chief Housekeeping",$housekeeping['inhouse_chief_housekeeping_shift1'],$housekeeping['inhouse_chief_housekeeping_shift2'],$housekeeping['inhouse_chief_housekeeping_shift3']));
			$pdf->Row(array("Supervisor",$housekeeping['inhouse_supervisor_shift1'],$housekeeping['inhouse_supervisor_shift2'],$housekeeping['inhouse_supervisor_shift3']));
			$pdf->Row(array("Staff",$housekeeping['inhouse_staff_shift1'],$housekeeping['inhouse_staff_shift2'],$housekeeping['inhouse_staff_shift3']));
			$pdf->Row(array("Administrasi",$housekeeping['inhouse_admin_shift1'],$housekeeping['inhouse_admin_shift2'],$housekeeping['inhouse_admin_shift3']));
			$pdf->Ln();

			$pdf->SetFont('Arial','B',10);
			$pdf->Write(10,'B. Outsourcing');
			$pdf->Ln();
			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(45,7,'Cleaning Area',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("Chief Housekeeping",$housekeeping['outsource_chief_housekeeping_shift1'],$housekeeping['outsource_chief_housekeeping_shift2'],$housekeeping['outsource_chief_housekeeping_shift3']));
			$pdf->Row(array("Supervisor",$housekeeping['outsource_supervisor_shift1'],$housekeeping['outsource_supervisor_shift2'],$housekeeping['outsource_supervisor_shift3']));
			$pdf->Row(array("Leader",$housekeeping['outsource_leader_shift1'],$housekeeping['outsource_leader_shift2'],$housekeeping['outsource_leader_shift3']));
			$pdf->Row(array("Crew",$housekeeping['outsource_crew_shift1'],$housekeeping['outsource_crew_shift2'],$housekeeping['outsource_crew_shift3']));
			$pdf->Row(array("Toilet Crew",$housekeeping['outsource_toilet_crew_shift1'],$housekeeping['outsource_toilet_crew_shift2'],$housekeeping['outsource_toilet_crew_shift3']));
			$pdf->Row(array("Gondola",$housekeeping['outsource_gondola_shift1'],$housekeeping['outsource_gondola_shift2'],$housekeeping['outsource_gondola_shift3']));
			$pdf->Row(array("Admin",$housekeeping['outsource_admin_shift1'],$housekeeping['outsource_admin_shift2'],$housekeeping['outsource_admin_shift3']));
			$pdf->Row(array("Total",$housekeeping['outsource_total_shift1'],$housekeeping['outsource_total_shift2'],$housekeeping['outsource_total_shift3']));
			$pdf->Ln();

			$pdf->SetFillColor(9,41,102);
			$pdf->SetTextColor(255,255,255);
			$pdf->SetFont('','B');
			$pdf->Cell(45,7,'Pest Control',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
			$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
			$pdf->Ln();
			$pdf->SetFont('');
			$pdf->SetTextColor(0,0,0);
			$pdf->SetWidths(array(45, 48, 48, 48));			
			$pdf->Row(array("Koordinator",$housekeeping['pest_control_koordinator_shift1'],$housekeeping['pest_control_koordinator_shift2'],$housekeeping['pest_control_koordinator_shift3']));
			$pdf->Row(array("Leader",$housekeeping['pest_control_leader_shift1'],$housekeeping['pest_control_leader_shift2'],$housekeeping['pest_control_leader_shift3']));
			$pdf->Row(array("Crew",$housekeeping['pest_control_crew_shift1'],$housekeeping['pest_control_crew_shift2'],$housekeeping['pest_control_crew_shift3']));
			$pdf->Ln();

			if(!empty($work_target)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TARGET PEKERJAAN');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(45,7,'Target Perkerjaan',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(45, 48, 48, 48));	
				foreach($work_target as $wt) {		
					$pdf->Row(array($wt['work_target'],$wt['shift1'],$wt['shift2'],$wt['shift3']));
				}
				$pdf->Ln();
			}

			if(!empty($hasilTangkapan)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'HASIL TANGKAPAN');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(45,7,'Hasil Tangkapan',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(45, 48, 48, 48));	
				foreach($hasilTangkapan as $ht) {		
					$pdf->Row(array($ht['hewan_tangkapan'],$ht['shift1'],$ht['shift2'],$ht['shift3']));
				}
				$pdf->Ln();
			}

			if(!empty($training)) {
				$pdf->SetFont('Arial','B',11);
				$pdf->Write(10,'TRAINING');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(45,7,'Training',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 1',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 2',1,0,'C',true);
				$pdf->Cell(48,7,'Shift 3',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('');
				$pdf->SetTextColor(0,0,0);
				$pdf->SetWidths(array(45, 48, 48, 48));	
				foreach($training as $t) {		
					$pdf->Row(array($t['training_name'],$t['shift1'],$t['shift2'],$t['shift3']));
				}
				$pdf->Ln();
			}
			
			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'LAPORAN KEJADIAN');
			$pdf->ln();
			$pdf->SetFont('Arial','',10);
			if(!empty($housekeeping['briefing1']))
			{
				$briefing1 = explode("<br>", $housekeeping['briefing1']);
				$i = 0;
				foreach($briefing1 as $b)
				{
					if(trim($briefing1[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}


			if(!empty($housekeeping['briefing2']))
			{
				$briefing2 = explode("<br>", $housekeeping['briefing2']);
				$i = 0;
				foreach($briefing2 as $b)
				{
					if(trim($briefing2[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}

			if(!empty($housekeeping['briefing3']))
			{
				$briefing3 = explode("<br>", $housekeeping['briefing3']);
				$i = 0;
				foreach($briefing3 as $b)
				{
					if(trim($briefing3[$i-1]) == "" && trim($b) == "") ;
					else
					{
						$pdf->Write(5,$b);
						$pdf->Ln();
					}
					$i++;
				}
				$pdf->Line($pdf->getX(),$pdf->getY(), 189, $pdf->getY());
				$pdf->Ln();
			}

			$pdf->SetFont('Arial','B',11);
			$pdf->Write(10,'PROGRESS REPORT');
			$pdf->Ln();

			if($params['id'] == 1390) $progressReportDir = $this->config->paths->html.'/images/progress_report2/';
			else $progressReportDir = $this->config->paths->html.'/images/progress_report/';

			if($params['id'] == 1390) $progressReportUrl = $this->config->general->url.'/images/progress_report2/';
			else $progressReportUrl = $this->config->general->url.'/images/progress_report/';

			if(!empty($progress_report_shift12))
			{
				$pdf->SetFont('Arial','B',10);
				$pdf->Write(10,'Progress Report Shift 1&2');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(52,7,'Area',1,0,'C',true);
				$pdf->Cell(28,7,'Before',1,0,'C',true);
				$pdf->Cell(28,7,'Progress',1,0,'C',true);
				$pdf->Cell(28,7,'After',1,0,'C',true);
				$pdf->Cell(53,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(52,28,28,28,53));
				foreach($progress_report_shift12 as $pr)
				{
					$x1 = $pdf->GetY();
					$pdf->Row(array($pr['area']."\n\n\n\n\n","", "", "", $pr['status']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);
					if(!empty($pr['img_before'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_before']))) {
							$pr['img_before'] = str_replace(".","_thumb.",$pr['img_before']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_before']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_before'],65,$y, $w,$h);
					}
					if(!empty($pr['img_progress'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_progress']))) {
							$pr['img_progress'] = str_replace(".","_thumb.",$pr['img_progress']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_progress']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_progress'],94,$y, $w,$h);
					}
					if(!empty($pr['img_after'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_after']))) {
							$pr['img_after'] = str_replace(".","_thumb.",$pr['img_after']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_after']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_after'],122,$y, $w,$h);
					}
				}
				$pdf->Ln();
			}

			if(!empty($progress_report_shift3))
			{
				$pdf->SetFont('Arial','B',10);
				$pdf->Write(10,'Progress Report Shift 3');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(52,7,'Area',1,0,'C',true);
				$pdf->Cell(28,7,'Before',1,0,'C',true);
				$pdf->Cell(28,7,'Progress',1,0,'C',true);
				$pdf->Cell(28,7,'After',1,0,'C',true);
				$pdf->Cell(53,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(52,28,28,28,53));
				foreach($progress_report_shift3 as $pr)
				{
					$x1 = $pdf->GetY();
					$pdf->Row(array($pr['area']."\n\n\n\n\n","", "", "", $pr['status']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);
					if(!empty($pr['img_before'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_before']))) {
							$pr['img_before'] = str_replace(".","_thumb.",$pr['img_before']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_before']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_before'],65,$y, $w,$h);
					}
					if(!empty($pr['img_progress'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_progress']))) {
							$pr['img_progress'] = str_replace(".","_thumb.",$pr['img_progress']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_progress']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_progress'],94,$y, $w,$h);
					}
					if(!empty($pr['img_after'])) {
						if (file_exists($progressReportDir.str_replace(".","_thumb.",$pr['img_after']))) {
							$pr['img_after'] = str_replace(".","_thumb.",$pr['img_after']);
						}
						list($width, $height) = getimagesize($progressReportDir.$pr['img_after']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$pr['img_after'],122,$y, $w,$h);
					}
				}
				$pdf->Ln();
			}

			if(!empty($other_info))
			{
				$pdf->SetFont('Arial','B',10);
				$pdf->Write(10,'Pest Control dan Informasi Lainnya');
				$pdf->Ln();
				$pdf->SetFillColor(9,41,102);
				$pdf->SetTextColor(255,255,255);
				$pdf->SetFont('','B');
				$pdf->Cell(79,7,'Area',1,0,'C',true);
				$pdf->Cell(30,7,'Progress',1,0,'C',true);
				$pdf->Cell(80,7,'Status',1,0,'C',true);
				$pdf->Ln();
				$pdf->SetFont('Arial','',10);
				$pdf->SetTextColor(0,0,0);	
				$pdf->SetWidths(array(79,30,80));
				foreach($other_info as $oi)
				{
					$x1 = $pdf->GetY();
					$pdf->Row(array($oi['area']."\n\n\n\n\n","", $oi['status']));
					$x2= $pdf->GetY();
					if($x2<$x1) $y = 12;
					else $y = $pdf->GetY()-($x2-$x1-2);					
					$oi['img_progress'] = str_replace(".","_thumb.",$oi['img_progress']);
					if(!empty($oi['img_progress'])) {
						list($width, $height) = getimagesize($progressReportDir.$oi['img_progress']);
						if($width > $height)
						{
							$w = 20;
							$h = 0;
						}
						else {
							$w = 0;
							$h = 20;
						}
						$pdf->Image($progressReportUrl.$oi['img_progress'],94,$y, $w, $h);
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
					$pdf->Link(10, $pdf->getY(), 189, 5, $this->baseUrl.'/default/attachment/openattachment/c/2/f/'.$att['filename']);
					$pdf->write(5,"- ".$att['description']);					
					$pdf->Ln(8);
				}
			}

			$pdf->Output('F', $filename, false);
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
