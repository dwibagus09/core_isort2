<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class achievementcategoryClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getAchievementCategories()
	{
		$categoryTable = new achievement_category(array('db'=>'db'));
		$select = $categoryTable->select()->order("start_range desc");		
		$rs = $categoryTable->getAdapter()->fetchAll($select);
		return $rs;
	}

}

?>