<?php

require_once('adminClass.php');
require_once('dbClass.php');

class vendorClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getSecurityVendor()
	{
		$vTable = new security_vendor(array('db' => 'db')); //use db object from registry

		$select = $vTable->select()->where('site_id = ?', $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityVendor($params)
	{		
		$vTable = new security_vendor(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'vendor_name'	=> $params['vendor_name'],
			'site_id'		=> $this->site_id
		);	
		if(empty($params['vendor_id']))
		{
			$vTable->insert($data);
		}
		else
		{
			$where = $vTable->getAdapter()->quoteInto('vendor_id = ?', $params['vendor_id']);
			$vTable->update($data, $where);
		}
		
	}
	
	function getSecurityVendorById($id)
	{		
		$vTable = new security_vendor(array('db' => 'db')); //use db object from registry

		$select = $vTable->select()
			->where('vendor_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityVendor($id)
	{
		$vTable = new security_vendor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $vTable->getAdapter()->quoteInto('vendor_id = ?', $id);
			$vTable->delete($where);
		}
	}
	
}
?>