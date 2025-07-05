<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class imageuploaderClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getImageUploaderByCustomPageId($custom_page_id)
	{
		$imageUploaderTable = new image_uploader(array('db'=>'db'));
		
		$select = $imageUploaderTable->select();
		$select->where("custom_page_id=?", $custom_page_id);
		$select->order("sort_order");
		
		$rs = $imageUploaderTable->getAdapter()->fetchAll($select);
		
		return $rs;
	}

}

?>