<?php

require_once('adminClass.php');
require_once('dbClass.php');

class categoryClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getCategoryById($id)
	{		
		$catTable = new categories(array('db' => 'db')); //use db object from registry

		$select = $catTable->select()
			->where('category_id = ?', $id);
			
		$rs = $catTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getCategories()
	{		
		$catTable = new categories(array('db' => 'db')); //use db object from registry

		$select = $catTable->select()
			->where('category_id IN (1,2,3,5,6,10,11)');
	
		$rs = $catTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	
}
?>