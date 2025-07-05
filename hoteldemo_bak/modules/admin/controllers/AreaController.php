<?php

require_once('actionControllerBase.php');
class Admin_AreaController extends actionControllerBase
{		
	function viewAction()
    {    	
    	Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Area";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_area.php');
        echo $this->view->render('footer.php');
    }
    
	function addareaAction()
    {
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
    	$params = $this->_getAllParams();
    	$areatable->add($params);
    }
	
	function getareabyidAction()
    {
    	Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $areatable->getAreaById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteAction()
    {
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$areatable->delete($id);
		}
	}
	
	function copyAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		

		foreach($params['site_id'] as $site_id) {
			$area = $areatable->getAreaById($params['area_id']);
			$area['site_id'] = $site_id;
			$area['area_id'] = "";
			$areatable->add($area);
		}
	}

}
?>
