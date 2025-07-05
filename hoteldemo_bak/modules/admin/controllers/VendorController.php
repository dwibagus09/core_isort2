<?php

require_once('actionControllerBase.php');
class Admin_VendorController extends actionControllerBase
{	
	function viewsecurityvendorAction()
    {    	
    	Zend_Loader::LoadClass('vendorClass', $this->modelDir);
    	$vendor = new vendorClass();
		
    	$this->view->vendorList = $vendor->getSecurityVendor();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_security_vendor.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecurityvendorAction()
    {
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
    	$vendor = new vendorClass();
    	$params = $this->_getAllParams();
    	$vendor->addSecurityVendor($params);
    }
	
	function getsecurityvendorbyidAction()
    {
    	Zend_Loader::LoadClass('vendorClass', $this->modelDir);
    	$vendor = new vendorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $vendor->getSecurityVendorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecurityvendorAction()
    {
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
    	$vendor = new vendorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$vendor->deleteSecurityVendor($id);
		}
    }
}
?>
