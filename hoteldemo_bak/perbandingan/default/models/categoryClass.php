<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class categoryClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getCategories($maxCatId = 0)
	{
		$categoriesTable = new categories(array('db'=>'db'));
		
		$select = $categoriesTable->select()->where("category_id<>?", 4)->where("category_id<?", 7)->orWhere("category_id>?", 9);
		if($maxCatId > 0) 	
		{
		    $select->where("category_id<?", 12);
		    $select->limit($maxCatId);
		}
		else $select->order("category_name");
		$rs = $categoriesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getCategoryById($id)
	{
		$categoriesTable = new categories(array('db'=>'db'));
		
		$select = $categoriesTable->select()->where("category_id=?", $id);		
		$rs = $categoriesTable->getAdapter()->fetchRow($select);
		return $rs;
	}
}

?>