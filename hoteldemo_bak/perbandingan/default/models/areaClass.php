<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class areaClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getArea()
	{
		$areaTable = new area(array('db'=>'db'));
		
		$select = $areaTable->select()->where("site_id=?", $this->site_id)->order("sort_order");
		$rs = $areaTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getAreaById($id)
	{
		$areaTable = new area(array('db'=>'db'));
		
		$select = $areaTable->select()->where("area_id=?", $id);		
		$rs = $areaTable->getAdapter()->fetchRow($select);
		return $rs;
	}
}

?>