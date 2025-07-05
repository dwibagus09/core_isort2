<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class IncidentController extends actionControllerBase
{
	function getincidentsbycityidsAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();

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
		$incidents = $incidentClass->getIncidentByCategoryIdAndSites($params['category_id'], $site_id);	
		echo json_encode($incidents);	
	}

	function getincidentsbysiteidsAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('incidentClass', $this->modelDir);
		$incidentClass = new incidentClass();


		$site_ids = implode(",",$params['site_ids']);
	
		$incidents = $incidentClass->getIncidentByCategoryIdAndSites($params['category_id'], $site_ids);	
		echo json_encode($incidents);	
	}
}
?>