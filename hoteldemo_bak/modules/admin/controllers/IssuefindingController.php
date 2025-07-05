<?php

require_once('actionControllerBase.php');
class Admin_IssuefindingController extends actionControllerBase
{	
	/*** SECURITY KEJADIAN / INCINDENT ***/
	function viewsecuritykejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getSecurityKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('1');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Security &amp; Safety Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewsecuritykejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/getsecuritykejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addsecuritykejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletesecuritykejadian";
		$this->view->copyUrl = "/admin/issuefinding/copysecuritykejadian";
		
		$this->view->category = "security";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecuritykejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addSecurityKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_1_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
    }
	
	function getsecuritykejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getSecurityKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecuritykejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteSecurityKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
		}
	}
	
	function copysecuritykejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getSecurityKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addSecurityKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$site_id."_1_");
		}
	}
	
	/*** SECURITY MODUS ***/

	function viewsecuritymodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getSecurityModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getSecurityKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Security &amp; Safety Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewsecuritymodus";
		$this->view->getByIdUrl = "/admin/issuefinding/getsecuritymodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addsecuritymodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletesecuritymodus";
		$this->view->copyUrl = "/admin/issuefinding/copysecuritymodus";
		
		$this->view->category = "security";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecuritymodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
		$params = $this->_getAllParams();
		
		$modus_id = $modustable->addSecurityModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 1;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_1_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
    }
	
	function getsecuritymodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getSecurityModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 1, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecuritymodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteSecurityModus($id);
			$this->cache->remove("modus_".$this->site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
		}
	}
	
	function copysecuritymodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getSecurityModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getSecurityKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getSecurityKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addSecurityModus($modus);

			$this->cache->remove("modus_".$site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$site_id."_1_");
		}
	}
	
	/*** SECURITY LANTAI / FLOOR ***/
	function viewsecurityfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getSecurityFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();
		
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Security &amp; Safety Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewsecurityfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getsecurityfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addsecurityfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletesecurityfloor";
		$this->view->copyUrl = "/admin/issuefinding/copysecurityfloor";
		
		$this->view->category = "security";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecurityfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addSecurityFloor($params);
    }
	
	function getsecurityfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getSecurityFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecurityfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteSecurityFloor($id);
		}
	}
	
	function copysecurityfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getSecurityFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addSecurityFloor($floor);
		}
	}

	/*** SECURITY GENERAL LOCATION ***/

	function viewsecuritygenerallocationAction()
    {    	
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
		$this->view->generalLocation = $lokasiumumtable->getSecurityLokasiUmum();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getSecurityFloor();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_general_location.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecuritygenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
    	$params = $this->_getAllParams();
    	$lokasiumumtable->addSecurityLokasiUmum($params);
    }
	
	function getsecuritygenerallocationbyidAction()
    {
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $lokasiumumtable->getSecurityLokasiUmumById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecuritygenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$lokasiumumtable->deleteSecurityLokasiUmum($id);
		}
	}
	
	function copysecuritygenerallocationAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();

		foreach($params['site_id'] as $site_id) {
			$lokasiUmum = $lokasiumumtable->getSecurityLokasiUmumById($params['lokasi_umum_id']);
			$lokasiUmum['site_id'] = $site_id;

			$rs = $floortable->getSecurityFloorById($lokasiUmum['lantai_id']);
			$new_floor = $floortable->getSecurityFloorByName($rs['floor'], $site_id);

			$lokasiUmum['lokasi_umum_id'] = "";
			$lokasiUmum['lantai_id'] = $new_floor['floor_id'];
			$lokasiumumtable->addSecurityLokasiUmum($lokasiUmum);
		}
	}

	/*** SAFETY KEJADIAN / INCINDENT ***/
	function viewsafetykejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getSafetyKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('3');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Safety Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewsafetykejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/getsafetykejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addsafetykejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletesafetykejadian";
		$this->view->copyUrl = "/admin/issuefinding/copysafetykejadian";
		
		$this->view->category = "safety";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addsafetykejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addSafetyKejadian($params);
		
		$this->cache->remove("modus_".$this->site_id."_3_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_3_");
    }
	
	function getsafetykejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getSafetyKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetykejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteSafetyKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_3_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_3_");
		}
	}
	
	function copysafetykejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getSafetyKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addSafetyKejadian($kejadian);

			$this->cache->remove("modus_".$site_id."_3_");
			$this->cache->remove("total_modus_per_month_".$site_id."_3_");
		}
	}
	
	/*** SAFETY MODUS ***/

	function viewsafetymodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getSafetyModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getSafetyKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Safety Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewsafetymodus";
		$this->view->getByIdUrl = "/admin/issuefinding/getsafetymodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addsafetymodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletesafetymodus";
		$this->view->copyUrl = "/admin/issuefinding/copysafetymodus";
		
		$this->view->category = "safety";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addsafetymodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addSafetyModus($params);
		
		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 3;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_3_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_3_");
    }
	
	function getsafetymodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getSafetyModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 3, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetymodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteSafetyModus($id);
		}
	}
	
	function copysafetymodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getSafetyModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getSafetyKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getSafetyKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addSafetyModus($modus);
		}
	}
	
	/*** SAFETY LANTAI / FLOOR ***/
	function viewsafetyfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getSafetyFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Safety Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewsafetyfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getsafetyfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addsafetyfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletesafetyfloor";
		$this->view->copyUrl = "/admin/issuefinding/copysafetyfloor";
		
		$this->view->category = "safety";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addsafetyfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addSafetyFloor($params);
    }
	
	function getsafetyfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getSafetyFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetyfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteSafetyFloor($id);
		}
	}
	
	function copysafetyfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getSafetyFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addSafetyFloor($floor);
		}
	}

	/*** SAFETY GENERAL LOCATION ***/

	function viewsafetygenerallocationAction()
    {    	
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
		$this->view->generalLocation = $lokasiumumtable->getSafetyLokasiUmum();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getSafetyFloor();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_general_location.php');
        echo $this->view->render('footer.php');
    }
    
	function addsafetygenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
    	$params = $this->_getAllParams();
    	$lokasiumumtable->addSafetyLokasiUmum($params);
    }
	
	function getsafetygenerallocationbyidAction()
    {
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $lokasiumumtable->getSafetyLokasiUmumById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesafetygenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$lokasiumumtable->deleteSafetyLokasiUmum($id);
		}
	}
	
	function copysafetygenerallocationAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();

		foreach($params['site_id'] as $site_id) {
			$lokasiUmum = $lokasiumumtable->getSafetyLokasiUmumById($params['lokasi_umum_id']);
			$lokasiUmum['site_id'] = $site_id;

			$rs = $floortable->getSafetyFloorById($lokasiUmum['lantai_id']);
			$new_floor = $floortable->getSafetyFloorByName($rs['floor'], $site_id);

			$lokasiUmum['lokasi_umum_id'] = "";
			$lokasiUmum['lantai_id'] = $new_floor['floor_id'];
			$lokasiumumtable->addSafeityLokasiUmum($lokasiUmum);
		}
	}

	/*** PARKING & TRAFFIC KEJADIAN / INCINDENT ***/
	function viewparkingkejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getParkingKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('5');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Parking Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewparkingkejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/getparkingkejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addparkingkejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deleteparkingkejadian";
		$this->view->copyUrl = "/admin/issuefinding/copyparkingkejadian";
		
		$this->view->category = "parking";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkingkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addParkingKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_5_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_5_");
    }
	
	function getparkingkejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getParkingKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkingkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteParkingKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_5_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_5_");
		}
	}
	
	function copyparkingkejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getParkingKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addParkingKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_5_");
			$this->cache->remove("total_modus_per_month_".$site_id."_5_");
		}
	}
	
	/*** PARKING & TRAFFIC MODUS ***/

	function viewparkingmodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getParkingModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getParkingKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Parking Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewparkingmodus";
		$this->view->getByIdUrl = "/admin/issuefinding/getparkingmodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addparkingmodus";
		$this->view->deleteUrl = "/admin/issuefinding/deleteparkingmodus";
		$this->view->copyUrl = "/admin/issuefinding/copyparkingmodus";
		
		$this->view->category = "parking";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkingmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addParkingModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 5;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_5_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_5_");
    }
	
	function getparkingmodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getParkingModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 5, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkingmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteParkingModus($id);
			$this->cache->remove("modus_".$this->site_id."_5_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_5_");
		}
	}
	
	function copyparkingmodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getParkingModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getParkingKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getParkingKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addParkingModus($modus);
			$this->cache->remove("modus_".$site_id."_5_");
			$this->cache->remove("total_modus_per_month_".$site_id."_5_");
		}
	}
	
	/*** PARKING & TRAFFIC LANTAI / FLOOR ***/
	function viewparkingfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getParkingFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Parking Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewparkingfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getparkingfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addparkingfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deleteparkingfloor";
		$this->view->copyUrl = "/admin/issuefinding/copyparkingfloor";
		
		$this->view->category = "parking";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkingfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addParkingFloor($params);
    }
	
	function getparkingfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getParkingFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkingfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteParkingFloor($id);
		}
	}
	
	function copyparkingfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getParkingFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addParkingFloor($floor);
		}
	}

	/*** PARKING & TRAFFIC GENERAL LOCATION ***/

	function viewparkinggenerallocationAction()
    {    	
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
		$this->view->generalLocation = $lokasiumumtable->getParkingLokasiUmum();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getParkingFloor();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_general_location.php');
        echo $this->view->render('footer.php');
    }
    
	function addparkinggenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
		
    	$params = $this->_getAllParams();
    	$lokasiumumtable->addParkingLokasiUmum($params);
    }
	
	function getparkinggenerallocationbyidAction()
    {
    	Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $lokasiumumtable->getParkingLokasiUmumById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteparkinggenerallocationAction()
    {
		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$lokasiumumtable->deleteParkingLokasiUmum($id);
		}
	}
	
	function copyparkinggenerallocationAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('lokasiumumClass', $this->modelDir);
    	$lokasiumumtable = new lokasiumumClass();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();

		foreach($params['site_id'] as $site_id) {
			$lokasiUmum = $lokasiumumtable->getParkingLokasiUmumById($params['lokasi_umum_id']);
			$lokasiUmum['site_id'] = $site_id;

			$rs = $floortable->getParkingFloorById($lokasiUmum['lantai_id']);
			$new_floor = $floortable->getParkingFloorByName($rs['floor'], $site_id);

			$lokasiUmum['lokasi_umum_id'] = "";
			$lokasiUmum['lantai_id'] = $new_floor['floor_id'];
			$lokasiumumtable->addParkingLokasiUmum($lokasiUmum);
		}
	}

	/*** ENGINEERING KEJADIAN / INCINDENT ***/
	function viewengineeringkejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getEngineeringKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('6');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Engineering Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewengineeringkejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/getengineeringkejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addengineeringkejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deleteengineeringkejadian";
		$this->view->copyUrl = "/admin/issuefinding/copyengineeringkejadian";
				
		$this->view->category = "engineering";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addengineeringkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addEngineeringKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_6_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_6_");
    }
	
	function getengineeringkejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getEngineeringKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteengineeringkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteEngineeringKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_6_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_6_");
		}
	}
	
	function copyengineeringkejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getEngineeringKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addEngineeringKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_6_");
			$this->cache->remove("total_modus_per_month_".$site_id."_6_");
		}
	}
	
	/*** ENGINEERING MODUS ***/

	function viewengineeringmodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getEngineeringModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getEngineeringKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Engineering Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewengineeringmodus";
		$this->view->getByIdUrl = "/admin/issuefinding/getengineeringmodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addengineeringmodus";
		$this->view->deleteUrl = "/admin/issuefinding/deleteengineeringmodus";
		$this->view->copyUrl = "/admin/issuefinding/copyengineeringmodus";
		
		$this->view->category = "engineering";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addengineeringmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addEngineeringModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 6;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_6_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_6_");
    }
	
	function getengineeringmodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getEngineeringModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 6, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteengineeringmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteEngineeringModus($id);
			$this->cache->remove("modus_".$this->site_id."_6_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_6_");
		}
	}
	
	function copyengineeringmodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getEngineeringModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getEngineeringKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getEngineeringKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addEngineeringModus($modus);

			$this->cache->remove("modus_".$site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$site_id."_1_");
		}
	}
	
	/*** ENGINEERING LANTAI / FLOOR ***/
	function viewengineeringfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getEngineeringFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Engineering Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewengineeringfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getengineeringfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addengineeringfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deleteengineeringfloor";
		$this->view->copyUrl = "/admin/issuefinding/copyengineeringfloor";
		
		$this->view->category = "engineering";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addengineeringfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addEngineeringFloor($params);
    }
	
	function getengineeringfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getEngineeringFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteengineeringfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteEngineeringFloor($id);
		}
	}
	
	function copyengineeringfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getEngineeringFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addEngineeringFloor($floor);
		}
	}

	/*** HOUSEKEEPING KEJADIAN / INCINDENT ***/
	function viewhousekeepingkejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getHousekeepingKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('2');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Housekeeping Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewhousekeepingkejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/gethousekeepingkejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addhousekeepingkejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletehousekeepingkejadian";
		$this->view->copyUrl = "/admin/issuefinding/copyhousekeepingkejadian";
		
		$this->view->category = "housekeeping";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addhousekeepingkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addHousekeepingKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_2_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_2_");
    }
	
	function gethousekeepingkejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getHousekeepingKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletehousekeepingkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteHousekeepingKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_2_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_2_");
		}
	}
	
	function copyhousekeepingkejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getHousekeepingKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addHousekeepingKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_2_");
			$this->cache->remove("total_modus_per_month_".$site_id."_2_");
		}
	}
	
	/*** HOUSEKEEPING MODUS ***/

	function viewhousekeepingmodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getHousekeepingModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getHousekeepingKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Housekeeping Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewhousekeepingmodus";
		$this->view->getByIdUrl = "/admin/issuefinding/gethousekeepingmodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addhousekeepingmodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletehousekeepingmodus";
		$this->view->copyUrl = "/admin/issuefinding/copyhousekeepingmodus";
		
		$this->view->category = "housekeeping";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addhousekeepingmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addHousekeepingModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 2;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_2_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_2_");
    }
	
	function gethousekeepingmodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getHousekeepingModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 2, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletehousekeepingmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteHousekeepingModus($id);
			$this->cache->remove("modus_".$this->site_id."_2_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_2_");
		}
	}
	
	function copyhousekeepingmodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getHousekeepingModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getHousekeepingKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getHousekeepingKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addHousekeepingModus($modus);

			$this->cache->remove("modus_".$site_id."_2_");
			$this->cache->remove("total_modus_per_month_".$site_id."_2_");
		}
	}

	/*** HOUSEKEEPING LANTAI / FLOOR ***/
	function viewhousekeepingfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getHousekeepingFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Housekeeping Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewhousekeepingfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/gethousekeepingfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addhousekeepingfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletehousekeepingfloor";
		$this->view->copyUrl = "/admin/issuefinding/copyhousekeepingfloor";
		
		$this->view->category = "housekeeping";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addhousekeepingfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addHousekeepingFloor($params);
    }
	
	function gethousekeepingfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getHousekeepingFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletehousekeepingfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteHousekeepingFloor($id);
		}
	}
	
	function copyhousekeepingfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getHousekeepingFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addHousekeepingFloor($floor);
		}
	}

	function getkejadianbycategoryidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
		$response['data'] = array();
		switch($params['category_id'])
		{
			case 1 : $rs = $kejadiantable->getSecurityKejadian(); break;
			case 2 : $rs = $kejadiantable->getHousekeepingKejadian(); break;
			case 3 : $rs = $kejadiantable->getSafetyKejadian(); break;
			case 5 : $rs = $kejadiantable->getParkingKejadian(); break;
			case 6 : $rs = $kejadiantable->getEngineeringKejadian(); break;
			case 10 : $rs = $kejadiantable->getBuildingServiceKejadian(); break;
			case 11 : $rs = $kejadiantable->getTenantRelationKejadian(); break;
		}

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
	} 
	
	function getmodusbykejadianidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
		$response['data'] = array();
		switch($params['category_id'])
		{
			case 1 : $rs = $modustable->getSecurityModusByKejadianId($params['kejadian_id']); break;
			case 2 : $rs = $modustable->getHousekeepingModusByKejadianId($params['kejadian_id']); break;
			case 3 : $rs = $modustable->getSafetyModusByKejadianId($params['kejadian_id']); break;
			case 5 : $rs = $modustable->getParkingModusByKejadianId($params['kejadian_id']); break;
			case 6 : $rs = $modustable->getEngineeringModusByKejadianId($params['kejadian_id']); break;
			case 10 : $rs = $modustable->getBuildingServiceModusByKejadianId($params['kejadian_id']); break;
			case 11 : $rs = $modustable->getTenantRelationModusByKejadianId($params['kejadian_id']); break;
		}

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
	} 
	
	function deletemoduslinkedAction()
    {
		Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
    	$moduslinkedtable = new moduslinkedClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$moduslinkedtable->deleteModusLinked($id);
		}
	}


	/*** BUILDING SERVICE KEJADIAN / INCINDENT ***/
	function viewbuildingservicekejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getBuildingServiceKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('10');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Human Operations Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewbuildingservicekejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/getbuildingservicekejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addbuildingservicekejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletebuildingservicekejadian";
		$this->view->copyUrl = "/admin/issuefinding/copybuildingservicekejadian";
		
		$this->view->category = "buildingservice";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addbuildingservicekejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addBuildingServiceKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_10_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_10_");
    }
	
	function getbuildingservicekejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getBuildingServiceKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletebuildingservicekejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteBuildingServiceKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_10_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_10_");
		}
	}
	
	function copybuildingservicekejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getBuildingServiceKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addBuildingServiceKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_10_");
			$this->cache->remove("total_modus_per_month_".$site_id."_10_");
		}
	}
	
	/*** BUILDING SERVICE MODUS ***/

	function viewbuildingservicemodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getBuildingServiceModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getBuildingServiceKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Human Operations Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewbuildingservicemodus";
		$this->view->getByIdUrl = "/admin/issuefinding/getbuildingservicemodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addbuildingservicemodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletebuildingservicemodus";
		$this->view->copyUrl = "/admin/issuefinding/copybuildingservicemodus";
		
		$this->view->category = "buildingservice";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addbuildingservicemodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addBuildingserviceModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 10;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_10_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_10_");
    }
	
	function getbuildingservicemodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getBuildingServiceModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 10, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletebuildingservicemodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteBuildingServiceModus($id);
			$this->cache->remove("modus_".$this->site_id."_10_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_10_");
		}
	}
	
	function copybuildingservicemodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getBuildingServiceModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getBuildingServiceKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getBuildingServiceKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addBuildingServiceModus($modus);

			$this->cache->remove("modus_".$site_id."_10_");
			$this->cache->remove("total_modus_per_month_".$site_id."_10_");
		}
	}

	/*** BUILDING SERVICE LANTAI / FLOOR ***/
	function viewbuildingservicefloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getBuildingServiceFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Human Operations Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewbuildingservicefloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getbuildingservicefloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addbuildingservicefloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletebuildingservicefloor";
		$this->view->copyUrl = "/admin/issuefinding/copybuildingservicefloor";
		
		$this->view->category = "buildingservice";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addbuildingservicefloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addBuildingServiceFloor($params);
    }
	
	function getbuildingservicefloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getBuildingServiceFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletebuildingservicefloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteBuildingServiceFloor($id);
		}
	}
	
	function copybuildingservicefloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getBuildingServiceFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addBuildingServiceFloor($floor);
		}
	}

	/*** TENANT RELATION KEJADIAN / INCINDENT ***/
	function viewtenantrelationkejadianAction()
    {    	
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getTenantRelationKejadian();

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType('1');

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Tenant Relation Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewtenantrelationkejadian";
		$this->view->getByIdUrl = "/admin/issuefinding/gettenantrelationkejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addtenantrelationkejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletetenantrelationkejadian";
		$this->view->copyUrl = "/admin/issuefinding/copytenantrelationkejadian";
		
		$this->view->category = "tenantrelation";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addtenantrelationkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$kejadiantable->addTenantRelationKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_11_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_11_");
    }
	
	function gettenantrelationkejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getTenantRelationKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletetenantrelationkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteTenantRelationKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_11_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_11_");
		}
	}
	
	function copytenantrelationkejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getTenantRelationKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addTenantRelationKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_2_");
			$this->cache->remove("total_modus_per_month_".$site_id."_11_");
		}
	}
	
	/*** TENANT RELATION MODUS ***/

	function viewtenantrelationmodusAction()
    {    	
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getTenantRelationModus();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getTenantRelationKejadian();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		$this->view->title = "Tenant Relation Modus";

		$this->view->viewUrl = "/admin/issuefinding/viewtenantrelationmodus";
		$this->view->getByIdUrl = "/admin/issuefinding/gettenantrelationmodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addtenantrelationmodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletetenantrelationmodus";
		$this->view->copyUrl = "/admin/issuefinding/copytenantrelationmodus";
		
		$this->view->category = "tenantrelation";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addtenantrelationmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
    	$params = $this->_getAllParams();
		$modus_id = $modustable->addTenantRelationModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 11;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_11_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_11_");
    }
	
	function gettenantrelationmodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getTenantRelationModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 11, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletetenantrelationmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteTenantRelationModus($id);
			$this->cache->remove("modus_".$this->site_id."_11_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_11_");
		}
	}
	
	function copytenantrelationmodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getTenantRelationModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getTenantRelationKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getTenantRelationKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addTenantRelationModus($modus);

			$this->cache->remove("modus_".$site_id."_11_");
			$this->cache->remove("total_modus_per_month_".$site_id."_11_");
		}
	}

	/*** TENANT RELATION LANTAI / FLOOR ***/
	function viewtenantrelationfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getTenantRelationFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();

    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Tenant Relation Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewtenantrelationfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/gettenantrelationfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addtenantrelationfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletetenantrelationfloor";
		$this->view->copyUrl = "/admin/issuefinding/copytenantrelationfloor";
		
		$this->view->category = "tenantrelation";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addtenantrelationfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addTenantRelationFloor($params);
    }
	
	function gettenantrelationfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getTenantRelationFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletetenantrelationfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteTenantRelationFloor($id);
		}
	}
	
	function copytenantrelationfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getTenantRelationFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addTenantRelationFloor($floor);
		}
	}
	
	/*** FRONT OFFICE / GUEST COMPLAIN ***/
	
	/*** GLOBAL KEJADIAN ***/
	
	function viewkejadianAction()
    {    	
		$params = $this->_getAllParams();
		
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getKejadianByCatId($params['cat_id']);

		Zend_Loader::LoadClass('issueClass', $this->modelDir);
    	$issueTable = new issueClass();
		$this->view->issue_type = $issue_type = $issueTable->getIssueType($params['cat_id']);

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();
		
		switch($params['cat_id'])
		{
			case 16: $category_name = "Guest Complain";
		}

		$this->view->title = $category_name." Incident";

		$this->view->viewUrl = "/admin/issuefinding/viewkejadian/cat_id/".$params['cat_id'];
		$this->view->getByIdUrl = "/admin/issuefinding/getkejadianbyid";
		$this->view->addUrl = "/admin/issuefinding/addkejadian";
		$this->view->deleteUrl = "/admin/issuefinding/deletekejadian";
		$this->view->copyUrl = "/admin/issuefinding/copykejadian";
		
		$this->view->category = strtolower($category_name);
		$this->view->category_id = $params['cat_id'];
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_kejadian.php');
        echo $this->view->render('footer.php');
    }
    
	function addkejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		$params = $this->_getAllParams();
		if($params['show_pelaku_checkbox'] == "on") $params['show_pelaku_checkbox'] = '1';
		else $params['show_pelaku_checkbox'] = '0';
		$params['site_id'] = $this->site_id;
		$kejadiantable->addKejadian($params);
		$this->cache->remove("modus_".$this->site_id."_".$params['category_id']."_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_".$params['category_id']."_");
    }
	
	function getkejadianbyidAction()
    {
    	Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $kejadiantable->getKejadianById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletekejadianAction()
    {
		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$kejadiantable->deleteKejadian($id);
			$this->cache->remove("modus_".$this->site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
		}
	}
	
	function copykejadianAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();
		

		foreach($params['site_id'] as $site_id) {
			$kejadian = $kejadiantable->getKejadianById($params['kejadian_id']);
			$kejadian['site_id'] = $site_id;
			$kejadian['kejadian_id'] = "";
			$kejadian['issue_type_id'] = $kejadian['issue_type'];
			$kejadiantable->addKejadian($kejadian);
			$this->cache->remove("modus_".$site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$site_id."_1_");
		}
	}
	
	/*** GLOBAL MODUS ***/

	function viewmodusAction()
    {    	
		$params = $this->_getAllParams();
		
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
		
		$this->view->modus = $modustable->getModusByCatId($params['cat_id']);

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
    	$kejadiantable = new kejadianClass();
		
		$this->view->kejadian = $kejadiantable->getKejadianByCatId($params['cat_id']);

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryTable = new categoryClass();
		$this->view->departments = $categoryTable->getCategories();

		switch($params['cat_id'])
		{
			case 16: $category_name = "Guest Complain";
		}	
		
		$this->view->title = $category_name." Modus";
		$this->view->category_id = $params['cat_id'];

		$this->view->viewUrl = "/admin/issuefinding/viewmodus/cat_id/".$params['cat_id'];
		$this->view->getByIdUrl = "/admin/issuefinding/getmodusbyid";
		$this->view->addUrl = "/admin/issuefinding/addmodus";
		$this->view->deleteUrl = "/admin/issuefinding/deletemodus";
		$this->view->copyUrl = "/admin/issuefinding/copymodus";
		
		$this->view->category = "security";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_modus.php');
        echo $this->view->render('footer.php');
    }
    
	function addmodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();
		
		$params = $this->_getAllParams();
		$modus_id = $modustable->addModus($params);

		if(!empty($params['department_id_link']))
		{
			Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
			$moduslinkedtable = new moduslinkedClass();
			$i = 0;
			foreach($params['department_id_link'] as $department_id_link)
			{
				$data['category_id'] = 1;
				$data['kejadian_id'] = $params['kejadian_id'];
				$data['modus_id'] = $modus_id;
				$data['category_id2'] = $department_id_link;
				$data['kejadian_id2'] = $params['kejadian_id_link'][$i];
				$data['modus_id2'] = $params['modus_id_link'][$i];
				$moduslinkedtable->addModusLinked($data);
				$i++;
			}
		}

		$this->cache->remove("modus_".$this->site_id."_1_");
		$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
    }
	
	function getmodusbyidAction()
    {
    	Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $modustable->getModusById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
				Zend_Loader::LoadClass('moduslinkedClass', $this->modelDir);
				$moduslinkedtable = new moduslinkedClass();	
				$modus_linked = $moduslinkedtable->getLinkedByModusId($params['id'], 1, $rs['kejadian_id']);
				if(!empty($modus_linked))
				{
					foreach($modus_linked as &$link)
					{
						switch($link['category_id2'])
						{
							case 1 : $link['category_name'] = "Security"; 
									 $res = $modustable->getSecurityModusById($link['modus_id2']); break;
							case 2 : $link['category_name'] = "Housekeeping"; 
									 $res = $modustable->getHousekeepingModusById($link['modus_id2']); break;
							case 3 : $link['category_name'] = "Safety"; 
									 $res = $modustable->getSafetyModusById($link['modus_id2']); break;
							case 5 : $link['category_name'] = "Parking & Traffic"; 
									 $res = $modustable->getParkingModusById($link['modus_id2']); break;
							case 6 : $link['category_name'] = "Engineering"; 
									 $res = $modustable->getEngineeringModusById($link['modus_id2']); break;
							case 10 : $link['category_name'] = "Building Service"; 
									 $res = $modustable->getBuildingServiceModusById($link['modus_id2']); break;
							case 11 : $link['category_name'] = "Tenant Relation"; 
									  $res = $modustable->getTenantRelationModusById($link['modus_id2']); break;
						}
						$link['kejadian'] = $res['kejadian'];
						$link['modus'] = $res['modus'];
					}
				}
				$rs['modus_linked'] = $modus_linked;
	    		$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletemodusAction()
    {
		Zend_Loader::LoadClass('modusClass', $this->modelDir);
    	$modustable = new modusClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$modustable->deleteModus($id);
			$this->cache->remove("modus_".$this->site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$this->site_id."_1_");
		}
	}
	
	function copymodusAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('modusClass', $this->modelDir);
		$modustable = new modusClass();

		Zend_Loader::LoadClass('kejadianClass', $this->modelDir);
		$kejadiantable = new kejadianClass();

		foreach($params['site_id'] as $site_id) {
			$modus = $modustable->getModusById($params['modus_id']);
			$modus['site_id'] = $site_id;

			$rs = $kejadiantable->getKejadianById($modus['kejadian_id']);
			$new_kejadian = $kejadiantable->getKejadianByName($rs['kejadian'], $site_id);

			$modus['modus_id'] = "";
			$modus['kejadian_id'] = $new_kejadian['kejadian_id'];
			$modustable->addModus($modus);

			$this->cache->remove("modus_".$site_id."_1_");
			$this->cache->remove("total_modus_per_month_".$site_id."_1_");
		}
	}
	
	/*** SECURITY LANTAI / FLOOR ***/
	/*function viewsecurityfloorAction()
    {    	
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		
		$this->view->floor = $floortable->getSecurityFloor();
		
		Zend_Loader::LoadClass('areaClass', $this->modelDir);
    	$areatable = new areaClass();
		
		$this->view->area = $areatable->getArea();
		
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteTable = new siteClass();
		$this->view->sites = $siteTable->getSites();

		$this->view->title = "Security &amp; Safety Floor";

		$this->view->viewUrl = "/admin/issuefinding/viewsecurityfloor";
		$this->view->getByIdUrl = "/admin/issuefinding/getsecurityfloorbyid";
		$this->view->addUrl = "/admin/issuefinding/addsecurityfloor";
		$this->view->deleteUrl = "/admin/issuefinding/deletesecurityfloor";
		$this->view->copyUrl = "/admin/issuefinding/copysecurityfloor";
		
		$this->view->category = "security";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_floor.php');
        echo $this->view->render('footer.php');
    }
    
	function addsecurityfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	$params = $this->_getAllParams();
    	$floortable->addSecurityFloor($params);
    }
	
	function getsecurityfloorbyidAction()
    {
    	Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $floortable->getSecurityFloorById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesecurityfloorAction()
    {
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$floortable->deleteSecurityFloor($id);
		}
	}
	
	function copysecurityfloorAction()
    {
		$params = $this->_getAllParams();

		Zend_Loader::LoadClass('floorClass', $this->modelDir);
    	$floortable = new floorClass();
		

		foreach($params['site_id'] as $site_id) {
			$floor = $floortable->getSecurityFloorById($params['floor_id']);
			$floor['site_id'] = $site_id;
			$floor['floor_id'] = "";
			$floortable->addSecurityFloor($floor);
		}
	}*/
	
}
?>
