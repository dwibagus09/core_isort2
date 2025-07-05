<?php

require_once('actionControllerBase.php');
class Admin_TrainingController extends actionControllerBase
{	
	function viewsecuritytrainingactivityAction()
    {    	
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
		
    	$this->view->activities = $training->getSecurityTrainingActivity();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_security_training_activity.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecuritytrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	$params = $this->_getAllParams();
    	$training->addSecurityTrainingActivity($params);
    }
	
	function getsecuritytrainingactivitybyidAction()
    {
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $training->getSecurityTrainingActivityById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecuritytrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$training->deleteSecurityTrainingActivity($id);
		}
    }
	
	/*** SAFETY TRAINING ***/
	
	function viewsafetytrainingactivityAction()
    {    	
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
		
    	$this->view->activities = $training->getSafetyTrainingActivity();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_safety_training_activity.php');
        echo $this->view->render('footer.php');
    }
    
	function addsafetytrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	$params = $this->_getAllParams();
    	$training->addSafetyTrainingActivity($params);
    }
	
	function getsafetytrainingactivitybyidAction()
    {
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $training->getSafetyTrainingActivityById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetytrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$training->deleteSafetyTrainingActivity($id);
		}
    }
	
	/*** PARKING TRAINING ***/
	
	function viewparkingtrainingactivityAction()
    {    	
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
		
    	$this->view->activities = $training->getParkingTrainingActivity();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_parking_training_activity.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkingtrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	$params = $this->_getAllParams();
    	$training->addParkingTrainingActivity($params);
    }
	
	function getparkingtrainingactivitybyidAction()
    {
    	Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $training->getParkingTrainingActivityById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkingtrainingactivityAction()
    {
		Zend_Loader::LoadClass('trainingClass', $this->modelDir);
    	$training = new trainingClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$training->deleteParkingTrainingActivity($id);
		}
    }
}
?>
