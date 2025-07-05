<?php

require_once('actionControllerBase.php');
class Admin_SettingController extends actionControllerBase
{	
	function viewchiefreportingtimeAction()
    {    	
    	Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
		
		$params = $this->_getAllParams();
		
		$setting = $settingClass->getOtherSetting();
		$setting['act'] = $params['action'];
    	$this->view->setting = $setting;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_chief_reporting_time.php');
        echo $this->view->render('footer.php');
    }
    
	function saveothersettingAction()
    {
		Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
    	$params = $this->_getAllParams();
    	$settingClass->saveOtherSetting($params);
		
		$this->getResponse()->setRedirect($this->config->paths->url."/admin/setting/".$params['act']);
		$this->getResponse()->sendResponse();
		exit;
    }
	
	function getothersettingbyidAction()
    {
    	Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getOtherSettingById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function viewsafetyreportingtimeAction()
    {    	
    	Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
		
		$params = $this->_getAllParams();
		
    	$setting = $settingClass->getOtherSetting();
		$setting['act'] = $params['action'];
    	$this->view->setting = $setting;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_safety_reporting_time.php');
        echo $this->view->render('footer.php');
    }

	function viewparkingreportingtimeAction()
    {    	
    	Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
		
		$params = $this->_getAllParams();
		
		$setting = $settingClass->getOtherSetting();
		$setting['act'] = $params['action'];
    	$this->view->setting = $setting;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_parking_reporting_time.php');
        echo $this->view->render('footer.php');
    }
	
	function viewhousekeepingreportingtimeAction()
    {    	
    	Zend_Loader::LoadClass('settingClass', $this->modelDir);
    	$settingClass = new settingClass();
		
		$params = $this->_getAllParams();
		
		$setting = $settingClass->getOtherSetting();
		$setting['act'] = $params['action'];
    	$this->view->setting = $setting;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_housekeeping_reporting_time.php');
        echo $this->view->render('footer.php');
    }
}
?>
