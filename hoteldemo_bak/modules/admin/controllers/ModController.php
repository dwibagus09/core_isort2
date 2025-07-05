<?php

require_once('actionControllerBase.php');
class Admin_ModController extends actionControllerBase
{	
	function viewstaffconditionAction()
    {    	
    	Zend_Loader::LoadClass('staffconditionClass', $this->modelDir);
    	$staffcondition = new staffconditionClass();
		
    	$this->view->staffcondition = $staffcondition->getStaffConditions();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_staff_condition.php');
        echo $this->view->render('footer.php');
    }
    
	function addstaffconditionAction()
    {
		Zend_Loader::LoadClass('staffconditionClass', $this->modelDir);
    	$staffcondition = new staffconditionClass();
    	$params = $this->_getAllParams();
		$params['site_id'] = $this->site_id;
    	$staffcondition->addStaffCondition($params);
    }
	
	function getstaffconditionbyidAction()
    {
    	Zend_Loader::LoadClass('staffconditionClass', $this->modelDir);
    	$staffcondition = new staffconditionClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $staffcondition->getStaffConditionById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletestaffconditionAction()
    {
		Zend_Loader::LoadClass('staffconditionClass', $this->modelDir);
    	$staffcondition = new staffconditionClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$staffcondition->deleteStaffCondition($id);
		}
    }
}
?>
