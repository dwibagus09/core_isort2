<?php

require_once('actionControllerBase.php');
class Admin_KpiController extends actionControllerBase
{	
	/*** RATING ***/
	function viewratingAction()
    {    	
    	Zend_Loader::LoadClass('ratingClass', $this->modelDir);
    	$ratingtable = new ratingClass();
		
		$this->view->rating = $ratingtable->getRating();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_rating.php');
        echo $this->view->render('footer.php');
    }
    
	function saveratingAction()
    {
		Zend_Loader::LoadClass('ratingClass', $this->modelDir);
    	$ratingtable = new ratingClass();
    	$params = $this->_getAllParams();
		$ratingtable->addRating($params);
    }
	
	function getratingbyidAction()
    {
    	Zend_Loader::LoadClass('ratingClass', $this->modelDir);
    	$ratingtable = new ratingClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $ratingtable->getRatingById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteratingAction()
    {
		Zend_Loader::LoadClass('ratingClass', $this->modelDir);
    	$ratingtable = new ratingClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$ratingtable->deleteRating($id);
		}
	}

	/*** KATEGORI CAPAIAN KINERJA ***/
	function viewachievementcategoryAction()
    {    	
    	Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategoryClass();
		
		$this->view->achievementCategory = $achievementCategoryTable->getAchievementCategory();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_achievement_category.php');
        echo $this->view->render('footer.php');
    }
    
	function saveachievementcategoryAction()
    {
		Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
		$achievementCategoryTable = new achievementcategoryClass();
		
    	$params = $this->_getAllParams();
		$achievementCategoryTable->addAchievementCategory($params);
    }
	
	function getachievementcategorybyidAction()
    {
    	Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategoryClass();
		    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $achievementCategoryTable->getAchievementCategoryById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteachievementcategoryAction()
    {
		Zend_Loader::LoadClass('achievementcategoryClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategoryClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$achievementCategoryTable->deleteAchievementCategory($id);
		}
	}

	/*** KETERANGAN HASIL CAPAIAN PER MODUL ***/
	function viewachievementcategorymoduleAction()
    {    	
		$params = $this->_getAllParams();

    	Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategorymoduleClass();
		$this->view->achievementCategory = $achievementCategoryTable->getAchievementModuleCategory($params['c']);

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$this->view->modules = $modules = $actionplanClass->getActionPlanModules($params['c']);

		$listModules = array();
		foreach($modules as $m)
		{
			$listModules[$m['action_plan_module_id']] = $m['show_year'] . ' - ' .$m['module_name'];
		}

		$this->view->listModules = $listModules;

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
		
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
		$this->view->category = $categoryClass->getCategoryById($params['c']);

        echo $this->view->render('header.php');
        echo $this->view->render('view_achievement_category_module.php');
        echo $this->view->render('footer.php');
    }
    
	function saveachievementcategorymoduleAction()
    {
		Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
		$achievementCategoryTable = new achievementcategorymoduleClass();
		
    	$params = $this->_getAllParams();
		$achievementCategoryTable->addAchievementModuleCategory($params);
    }
	
	function getachievementcategorymodulebyidAction()
    {
    	Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategorymoduleClass();
		    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $achievementCategoryTable->getAchievementCategoryModuleById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteachievementcategorymoduleAction()
    {
		Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategorymoduleClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$achievementCategoryTable->deleteAchievementCategoryModule($id);
		}
	}

	public function copyachievementcategorymoduleAction() {
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('achievementcategorymoduleClass', $this->modelDir);
    	$achievementCategoryTable = new achievementcategorymoduleClass();

		$category = $achievementCategoryTable->getAchievementCategoryModuleById($params['id']);

		Zend_Loader::LoadClass('actionplanClass', $this->modelDir);
		$actionplanClass = new actionplanClass();
		$module = $actionplanClass->getActionPlanModuleById($category['module_id']);

		foreach($params['site_id'] as $site_id) {
			$newTarget = $actionplanClass->getActionPlanModuleByModuleName($module['module_name'], $category['category_id'], $site_id);
			$category['module_id'] = $newTarget['action_plan_module_id'];	
			$category['site_id'] = $site_id;
			$achievementCategoryTable->copyAchievementCategoryModuleToOtherSite($category);
		}		
	}

	/*** USERS ***/
	function viewusersAction()
    {    	
		$params = $this->_getAllParams();

    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$usertable = new userClass();
		
		$this->view->kpiUsers = $usertable->getKPIUsers($params['c']);

		$this->view->users = $usertable->getUsersByCategory($params['c']);

		$position = array();
		$position[1] = "Chief";
		$position[2] = "Spv";
		$position[3] = "Staff";
		$position[4] = "Admin";
		$this->view->position = $position;
		
		$this->view->category_id = $params['c'];

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
		$categoryClass = new categoryClass();
		$this->view->category = $categoryClass->getCategoryById($params['c']);
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kpi_users.php');
        echo $this->view->render('footer.php');
    }
    
	function saveuserAction()
    {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$usertable = new userClass();
    	$params = $this->_getAllParams();
		$usertable->addKPIUser($params);
    }
	
	function getuserbyidAction()
    {
    	Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$usertable = new userClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $usertable->getKPIUserById($params['id']);

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
    	$usertable = new userClass();
    	
		$id =  $this->_request->getParam('id');
		if(!empty($id))
		{
			$usertable->deleteKPIUser($id);
		}
	}
}
?>
