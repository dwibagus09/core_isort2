<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class contactClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getContactByCustomPageId($custom_page_id)
	{
		$contactTable = new contact(array('db'=>'db'));
		
		$select = $contactTable->select();
		$select->where("custom_page_id=?", $custom_page_id);
		$select->order("contact_sort_order");
		
		$rs = $contactTable->getAdapter()->fetchAll($select);
		
		return $rs;
	}

}

?>