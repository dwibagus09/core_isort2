<?php

require_once('adminClass.php');
require_once('dbClass.php');

class lokasiumumClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getSecurityLokasiUmum()
	{
		$lokasiUmumTable = new security_lokasi_umum(array('db' => 'db')); //use db object from registry

		$select = $lokasiUmumTable->getAdapter()->select();
		$select->from(array("lu"=>"security_lokasi_umum"), array("lu.*"));
		$select->joinLeft(array("f"=>"security_floor"), "f.floor_id = lu.lantai_id", array("f.floor"));
		$select->where('lu.site_id = ?', $this->site_id);
		$select->order('lu.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityLokasiUmum($params)
	{		
		$lokasiUmumTable = new security_lokasi_umum(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'lantai_id'			=> $params['lantai_id'],
			'nama_lokasi'		=> $params['nama_lokasi'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['lokasi_umum_id']))
		{
			$lokasiUmumTable->insert($data);
		}
		else
		{
			$where = $lokasiUmumTable->getAdapter()->quoteInto('lokasi_umum_id = ?', $params['lokasi_umum_id']);
			$lokasiUmumTable->update($data, $where);
		}
		
	}
	
	function getSecurityLokasiUmumById($id)
	{		
		$lokasiUmumTable = new security_lokasi_umum(array('db' => 'db')); //use db object from registry

		$select = $lokasiUmumTable->select()
			->where('lokasi_umum_id = ?', $id);
			
		$rs = $lokasiUmumTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityLokasiUmum($id)
	{
		$lokasiUmumTable = new security_lokasi_umum(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $lokasiUmumTable->getAdapter()->quoteInto('lokasi_umum_id = ?', $id);
			$lokasiUmumTable->delete($where);
		}
	}
	
}
?>