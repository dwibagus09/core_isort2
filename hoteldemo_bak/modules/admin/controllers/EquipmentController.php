<?php

require_once('actionControllerBase.php');
class Admin_EquipmentController extends actionControllerBase
{	
	function viewsecurityequipmentAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
		$this->view->equipment = $equipment->getSecurityEquipments();
		
		Zend_Loader::LoadClass('vendorClass', $this->modelDir);
    	$vendor = new vendorClass();
		$this->view->vendor = $vendor->getSecurityVendor();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_security_equipments.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecurityequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	$params = $this->_getAllParams();
    	$equipment->addSecurityEquipment($params);
    }
	
	function getsecurityequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getSecurityEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecurityequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteSecurityEquipment($id);
		}
    }
	
	function viewsafetyequipmentAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
    	$this->view->equipment = $equipment->getSafetyEquipments();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_safety_equipments.php');
        echo $this->view->render('footer.php');
    }
	
	function addsafetyequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	$params = $this->_getAllParams();
    	$equipment->addSafetyEquipment($params);
    }
	
	function getsafetyequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getSafetyEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetyequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteSafetyEquipment($id);
		}
    }
	
	function viewsafetyequipmentitemsAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
		$this->view->safety_equipment_list = $equipment->getSafetyEquipments();
		
    	$this->view->equipment = $equipment->getSafetyEquipmentItems();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_safety_equipment_items.php');
        echo $this->view->render('footer.php');
    }
	
	function addsafetyequipmentitemAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	$params = $this->_getAllParams();
    	$equipment->addSafetyEquipmentItem($params);
    }
	
	function getsafetyequipmentitembyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getSafetyEquipmentItemById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetyequipmentitemAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteSafetyEquipmentItem($id);
		}
    }
	
	
	/*** PARKING EQUIPMENT ***/
	
	function viewparkingequipment1Action()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
    	$this->view->equipment = $equipment->getParkingEquipments('1');
		$this->view->type = '1';
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_parking_equipments.php');
        echo $this->view->render('footer.php');
    }
	
	function viewparkingequipment2Action()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
    	$this->view->equipment = $equipment->getParkingEquipments('2');
		$this->view->type = '2';
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_parking_equipments.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkingequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	$params = $this->_getAllParams();
    	$equipment->addParkingEquipment($params);
    }
	
	function getparkingequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getParkingEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkingequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteParkingEquipment($id);
		}
    }
	
	/*** MOD EQUIPMENT ***/
	
	function viewmodequipmentAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
    	$this->view->equipment = $equipment->getModEquipments($this->site_id);
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_mod_equipments.php');
        echo $this->view->render('footer.php');
    }
    
	function addmodequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	$params = $this->_getAllParams();
		$params['site_id'] = $this->site_id;
    	$equipment->addModEquipment($params);
    }
	
	function getmodequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getModEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletemodequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteModEquipment($id);
		}
	}
	
	/*** Jenis Peralatan Proteksi Gedung ***/

	function viewbuildingprotectionequipmenttypeAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
		$this->view->equipmentType = $equipment->getBuildingProtectionEquipmentType();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_building_protection_equipment_type.php');
        echo $this->view->render('footer.php');
    }
	
	function addbuildingprotectionequipmenttypeAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		$params = $this->_getAllParams();
		$params['site_id'] = $this->site_id;
    	$equipment->addBuildingProtectionEquipmentType($params);
    }
	
	function getbuildingprotectionequipmenttypebyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getBuildingProtectionEquipmentTypeById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletebuildingprotectionequipmenttypeAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteBuildingProtectionEquipmentType($id);
		}
	}

	public function copybuildingprotectionequipmenttypeAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();

		$type = $equipment->getBuildingProtectionEquipmentTypeById($params['equipment_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		
		$msg = "";
		foreach($params['site_id'] as $site_id) {
			$existingType = $equipment->getBuildingProtectionEquipmentTypeByName($type['equipment_name'], $site_id);
			$site = $siteClass->getSite($site_id);
			
			if(empty($existingType))
			{
				$type['site_id'] = $site_id;
				$type['equipment_id'] = "";
				$equipment->addBuildingProtectionEquipmentType($type);
				$msg = $msg . "File has been copied to ".$site['initial']."\n";
			}
			else
			{
				$msg = $msg . "File cannot be copied to ".$site['initial']." because it's already exist\n";
			}
		}
		echo $msg; 
	}
	
	/*** Peralatan Proteksi Gedung ***/

	function viewbuildingprotectionequipmentAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
		$this->view->equipmentType = $equipment->getBuildingProtectionEquipmentType();		
		$this->view->equipment = $equipment->getBuildingProtectionEquipment();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_building_protection_equipment.php');
        echo $this->view->render('footer.php');
    }
	
	function addbuildingprotectionequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		$params = $this->_getAllParams();
		$params['site_id'] = $this->site_id;
    	$equipment->addBuildingProtectionEquipment($params);
    }
	
	function getbuildingprotectionequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getBuildingProtectionEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletebuildingprotectionequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteBuildingProtectionEquipment($id);
		}
	}
	
	public function copybuildingprotectionequipmentAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();

		$equip = $equipment->getBuildingProtectionEquipmentById($params['equipment_item_id']);
		$type = $equipment->getBuildingProtectionEquipmentTypeById($equip['equipment_id']);
		foreach($params['site_id'] as $site_id) {
			$type2 = $equipment->getBuildingProtectionEquipmentTypeByName($type['equipment_name'], $site_id);
			$equip['site_id'] = $site_id;
			$equip['equipment_item_id'] = "";
			$equip['equipment_id'] = $type2['equipment_id'];
			$equipment->addBuildingProtectionEquipment($equip);
		}
	}

	/*** PERLENGKAPAN PENANGGULANGAN KEBAKARAN DAN KECELAKAAN GEDUNG ***/

	function viewfireaccidentequipmentAction()
    {    	
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		
		$this->view->equipment = $equipment->getFireAccidentEquipment();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		$this->view->sites = $siteClass->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_fire_accident_equipment.php');
        echo $this->view->render('footer.php');
    }
	
	function addfireaccidentequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
		$params = $this->_getAllParams();
		$params['site_id'] = $this->site_id;
    	$equipment->addFireAccidentEquipment($params);
    }
	
	function getfireaccidentequipmentbyidAction()
    {
    	Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $equipment->getFireAccidentEquipmentById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletefireaccidentequipmentAction()
    {
		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$equipment->deleteFireAccidentEquipment($id);
		}
	}

	public function copyfireaccidentequipmentAction() {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('equipmentClass', $this->modelDir);
    	$equipment = new equipmentClass();

		$type = $equipment->getFireAccidentEquipmentById($params['equipment_id']);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();
		
		$msg = "";
		foreach($params['site_id'] as $site_id) {
			$existingType = $equipment->getFireAccidentEquipmentByName($type['equipment_name'], $site_id);
			$site = $siteClass->getSite($site_id);
		
		if(empty($existingType))
		{
			$type['site_id'] = $site_id;
			$type['equipment_id'] = "";
			$equipment->addFireAccidentEquipment($type);
			$msg = $msg . "File has been copied to ".$site['initial']."\n";
		}
		else
			{
				$msg = $msg . "File cannot be copied to ".$site['initial']." because it's already exist\n";
			}
		}
		echo $msg; 
	}
}
?>
