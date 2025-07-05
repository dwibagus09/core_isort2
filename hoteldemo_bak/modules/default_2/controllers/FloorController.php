<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class FloorController extends actionControllerBase
{

	function getfloorsbycityidsAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
		$floorClass = new floorClass();

		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		$city_ids = implode(",",$params['city_ids']);
		$sites = $siteClass->getSitesByCityId($city_ids);
		$site_id = "";
		if(!empty($sites))
		{
			foreach($sites as $site)
			{
				$site_id .= $site['site_id'].",";
			}
		}	
		$site_id = substr($site_id, 0, -1);
		$floors = $floorClass->getFloorByCategoryIdAndSites($params['category_id'], $site_id);	
		echo json_encode($floors);	
	}

	function getfloorsbysiteidsAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('floorClass', $this->modelDir);
		$floorClass = new floorClass();

		$site_ids = implode(",",$params['site_ids']);
		$floors = $floorClass->getFloorByCategoryIdAndSites($params['category_id'], $site_ids);	
		echo json_encode($floors);	
	}
}
?>