<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class onlineadsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getMarketplaceSection($marketplaceSiteId) {
		/* dk 2017-05-17
	    $mktpAdsTable = new mktp_ads(array('db'=>'db_onlineads'));
		$select = $mktpAdsTable->getAdapter()->select();
		$select->from(array("ma"=>"mktp_ads"));
		$select->joinLeft(array("msc"=>"mktp_section_category"), "ma.category_id = msc.category_id");
		$select->joinLeft(array("ms"=>"mktp_sections"), "msc.section_id = ms.section_id", "ms.*");
		$select->where('ms.site_id = ?', $marketplaceSiteId);
		$select->group("ms.name");
		$sections = $mktpAdsTable->getAdapter()->fetchAll($select);
		return $sections;
		*/
	}
	
	function get5LatestAds($marketplaceSiteId, $section_id) {
		/* dk 2017-05-17
		$mktpAdsTable = new mktp_ads(array('db'=>'db_onlineads'));
		$select = $mktpAdsTable->getAdapter()->select();
		$select->from(array("ma"=>"mktp_ads"));
		$select->joinLeft(array("msc"=>"mktp_section_category"), "ma.category_id = msc.category_id");
		$select->joinLeft(array("ms"=>"mktp_sections"), "msc.section_id = ms.section_id", "ms.*");
		$select->where('ms.site_id = ?', $marketplaceSiteId);
		$select->where('ms.section_id = ?', $section_id);
		$select->order('ma.pub_for_date');
		$select->limit(5);
		$sections = $mktpAdsTable->getAdapter()->fetchAll($select);
		return $sections;
		*/
	}
	
}
?>