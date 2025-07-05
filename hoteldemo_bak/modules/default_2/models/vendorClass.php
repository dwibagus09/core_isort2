<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class vendorClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getVendor($siteId) {
		$vendorTable = new security_vendor(array('db'=>'db'));
		
		$now = date("Y-m-d");
		
		$select = $vendorTable->select();
		$select->where("site_id=?", $siteId);		
		$select->order(array("vendor_id"));
		$vendor = $vendorTable->getAdapter()->fetchAll($select);
		
		return $vendor;
	}
}
?>