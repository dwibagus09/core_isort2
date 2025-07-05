<?php

require_once('actionControllerBase.php');
require_once('Zend/Json.php');

class SiteController extends actionControllerBase
{

	function getsitesbycityidsAction()
	{
		$params = $this->_getAllParams();
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
		$siteClass = new siteClass();

		$city_ids = implode(",",$params['city_ids']);
		$sites = $siteClass->getSitesByCityId($city_ids);
		echo json_encode($sites);	
	}
}
?>