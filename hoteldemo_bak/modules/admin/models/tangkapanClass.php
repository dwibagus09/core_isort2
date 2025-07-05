<?php

require_once('adminClass.php');
require_once('dbClass.php');

class tangkapanClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	
	function getHousekeepingTangkapan()
	{
		$tangkapanTable = new housekeeping_tangkapan(array('db' => 'db')); //use db object from registry

		$select = $tangkapanTable->select()->where('site_id = ?', $this->site_id)->order("hewan_tangkapan");
		
		return $this->db->fetchAll($select);
	}
	
	function addHousekeepingTangkapan($params)
	{		
		$tangkapanTable = new housekeeping_tangkapan(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $this->site_id,
			'hewan_tangkapan'	=> $params['hewan_tangkapan']
		);
			
		if(empty($params['tangkapan_id']))
		{
			$tangkapanTable->insert($data);
		}
		else
		{
			$where = $tangkapanTable->getAdapter()->quoteInto('tangkapan_id = ?', $params['tangkapan_id']);
			$tangkapanTable->update($data, $where);
		}
		
	}
	
	function getHousekeepingTangkapanById($id)
	{		
		$tangkapanTable = new housekeeping_tangkapan(array('db' => 'db')); //use db object from registry

		$select = $tangkapanTable->select()
			->where('tangkapan_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteHousekeepingTangkapan($id)
	{
		$tangkapanTable = new housekeeping_tangkapan(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $tangkapanTable->getAdapter()->quoteInto('tangkapan_id = ?', $id);
			$tangkapanTable->delete($where);
		}
	}
	
	
}
?>