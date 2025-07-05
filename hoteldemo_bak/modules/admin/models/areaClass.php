<?php

require_once('adminClass.php');
require_once('dbClass.php');

class areaClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getArea()
	{
		$areaTable = new area(array('db' => 'db')); //use db object from registry

		$select = $areaTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->order('sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function add($params)
	{		
		$areaTable = new area(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area_name'			=> $params['area_name'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['area_id']))
		{
			$areaTable->insert($data);
		}
		else
		{
			$where = $areaTable->getAdapter()->quoteInto('area_id = ?', $params['area_id']);
			$areaTable->update($data, $where);
		}
		
	}
	
	function getAreaById($id)
	{		
		$areaTable = new area(array('db' => 'db')); //use db object from registry

		$select = $areaTable->select()
			->where('area_id = ?', $id);
			
		$rs = $areaTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function delete($id)
	{
		$areaTable = new area(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $areaTable->getAdapter()->quoteInto('area_id = ?', $id);
			$areaTable->delete($where);
		}
	}

	function getAreaByName($area_name, $site_id)
	{		
		$areaTable = new area(array('db' => 'db')); //use db object from registry

		$select = $areaTable->select()->where('area_name = ?', $area_name)->where('site_id = ?', $site_id);
			
		$rs = $areaTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
}
?>