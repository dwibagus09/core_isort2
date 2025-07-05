<?php

require_once('actionControllerBase.php');
class Admin_TangkapanController extends actionControllerBase
{	
	function viewhousekeepinghasiltangkapanAction()
    {    	
    	Zend_Loader::LoadClass('tangkapanClass', $this->modelDir);
    	$tangkapan = new tangkapanClass();
		
    	$this->view->tangkapan = $tangkapan->getHousekeepingTangkapan();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_housekeeping_hasil_tangkapan.php');
        echo $this->view->render('footer.php');
    }
    
	function addhousekeepinghasiltangkapanAction()
    {
		Zend_Loader::LoadClass('tangkapanClass', $this->modelDir);
    	$tangkapan = new tangkapanClass();
    	$params = $this->_getAllParams();
    	$tangkapan->addHousekeepingTangkapan($params);
    }
	
	function gethousekeepinghasiltangkapanbyidAction()
    {
    	Zend_Loader::LoadClass('tangkapanClass', $this->modelDir);
    	$tangkapan = new tangkapanClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $tangkapan->getHousekeepingTangkapanById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletehousekeepinghasiltangkapanAction()
    {
		Zend_Loader::LoadClass('tangkapanClass', $this->modelDir);
    	$tangkapan = new tangkapanClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$tangkapan->deleteHousekeepingTangkapan($id);
		}
    }
	
	
}
?>
