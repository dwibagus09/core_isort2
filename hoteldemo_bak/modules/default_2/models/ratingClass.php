<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class ratingClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getRating($bobot)
	{
		$ratingTable = new rating(array('db'=>'db'));
		$select = $ratingTable->select()->where("start_range<=?", $bobot)->where("end_range>=?", $bobot);		
		$rs = $ratingTable->getAdapter()->fetchRow($select);
		return $rs;
	}

}

?>