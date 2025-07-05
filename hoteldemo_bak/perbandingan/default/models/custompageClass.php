<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class custompageClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getCustomPageById($custom_page_id)
	{
		$cpTable = new custom_page(array('db'=>'db'));
		
		$select = $cpTable->select();
		$select->where("custom_page_id=?", $custom_page_id);
		
		$rs = $cpTable->getAdapter()->fetchRow($select);
		
		return $rs;
	}

}

?>