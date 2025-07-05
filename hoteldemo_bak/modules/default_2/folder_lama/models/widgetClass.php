<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class widgetClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getWidgets($siteId, $locationId=0) {
		$widgetTable = new widgets(array('db'=>'db'));
		
		$now = date("Y-m-d");
		
		$select = $widgetTable->select();
		$select->where("site_id=?", $siteId);		
		$select->where("'{$now}' BETWEEN start_date AND end_date");
		$select->where("location_id=".$locationId);
		$select->order(array("order_id"));
		$widgets = $widgetTable->getAdapter()->fetchAll($select);
		
		return $widgets;
	}
}
?>