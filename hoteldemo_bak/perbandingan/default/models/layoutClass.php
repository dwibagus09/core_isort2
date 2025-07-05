<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class layoutClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getHomeLayout($site_id, $col)
	{		
		$home_layoutTable = new home_layout(array('db' => 'db'));

		$select = $home_layoutTable->select()
			->where('site_id = ?', $site_id)
			->where('column_no = ?', $col);
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}
	
	function getAreaLayout($site_id, $col)
	{		
		$area_layoutTable = new area_layout(array('db' => 'db'));

		$select = $area_layoutTable->select()
			->where('site_id = ?', $site_id)
			->where('column_no = ?', $col);
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}
}
?>