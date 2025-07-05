<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class achievementcategorymoduleClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAchievementCategoriesModule($category_id)
	{
		$categoryTable = new achievement_category_module(array('db'=>'db'));
		$select = $categoryTable->select()->where("site_id= ?", $this->site_id)->where("category_id= ?", $category_id)->order("module_id")->order("start_range desc");		
		$rs = $categoryTable->getAdapter()->fetchAll($select);
		return $rs;
	}

}

?>