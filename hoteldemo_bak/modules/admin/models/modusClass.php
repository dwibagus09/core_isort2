<?php

require_once('adminClass.php');
require_once('dbClass.php');

class modusClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	/*** SECURITY ***/

	function getSecurityModus()
	{
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"security_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"security_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityModus($params)
	{		
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getSecurityModusById($id)
	{		
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"security_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"security_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getSecurityModusByKejadianId($id)
	{		
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteSecurityModus($id)
	{
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getSecurityModusByKejModId($id)
	{		
		$modusTable = new security_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"security_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"security_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** SAFETY ***/
	
	function getSafetyModus()
	{
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"safety_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"safety_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSafetyModus($params)
	{		
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getSafetyModusById($id)
	{		
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"safety_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"safety_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getSafetyModusByKejadianId($id)
	{		
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteSafetyModus($id)
	{
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getSafetyModusByKejModId($id)
	{		
		$modusTable = new safety_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"safety_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"safety_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** PARKING & TRAFFIC ***/
	
	function getParkingModus()
	{
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"parking_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"parking_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addParkingModus($params)
	{		
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getParkingModusById($id)
	{		
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"parking_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"parking_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getParkingModusByKejadianId($id)
	{		
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteParkingModus($id)
	{
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getParkingModusByKejModId($id)
	{		
		$modusTable = new parking_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"parking_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"parking_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** ENGINEERING ***/

	function getEngineeringModus()
	{
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"engineering_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"engineering_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addEngineeringModus($params)
	{		
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getEngineeringModusById($id)
	{		
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"engineering_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"engineering_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getEngineeringModusByKejadianId($id)
	{		
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteEngineeringModus($id)
	{
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getEngineeringModusByKejModId($id)
	{		
		$modusTable = new engineering_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"parking_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"parking_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** HOUSEKEEPING ***/

	function getHousekeepingModus()
	{
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"housekeeping_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"housekeeping_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addHousekeepingModus($params)
	{		
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getHousekeepingModusById($id)
	{		
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"housekeeping_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"housekeeping_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getHousekeepingModusByKejadianId($id)
	{		
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteHousekeepingModus($id)
	{
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getHousekeepingModusByKejModId($id)
	{		
		$modusTable = new housekeeping_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"housekeeping_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"housekeeping_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** BUILDING SERVICE ***/

	function getBuildingServiceModus()
	{
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"building_service_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"building_service_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addBuildingServiceModus($params)
	{		
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getBuildingServiceModusById($id)
	{		
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"building_service_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"building_service_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getBuildingServiceModusByKejadianId($id)
	{		
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteBuildingServiceModus($id)
	{
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getBuildingServiceModusByKejModId($id)
	{		
		$modusTable = new building_service_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"building_service_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"building_service_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	/*** TENANT RELATION ***/

	function getTenantRelationModus()
	{
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"tenant_relation_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addTenantRelationModus($params)
	{		
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getTenantRelationModusById($id)
	{		
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"tenant_relation_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getTenantRelationModusByKejadianId($id)
	{		
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteTenantRelationModus($id)
	{
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getTenantRelationModusByKejModId($id)
	{		
		$modusTable = new tenant_relation_modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"tenant_relation_modus"), array("m.*"));
		$select->joinLeft(array("k"=>"tenant_relation_kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	/*** GLOBAL ***/

	function getModusByCatId($cat_id)
	{
		$modusTable = new modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"modus"), array("m.*"));
		$select->joinLeft(array("k"=>"kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->where('m.category_id = ?', $cat_id);
		$select->order('m.kejadian_id');
		$select->order('m.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addModus($params)
	{		
		$modusTable = new modus(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'kejadian_id'		=> $params['kejadian_id'],
			'modus'				=> $params['modus'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'category_id'		=> $params['category_id']
		);	
		if(empty($params['modus_id']))
		{
			$modusTable->insert($data);
			$params['modus_id'] = $modusTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $params['modus_id']);
			$modusTable->update($data, $where);
		}
		return $params['modus_id'];
	}
	
	function getModusById($id)
	{		
		$modusTable = new modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"modus"), array("m.*"));
		$select->joinLeft(array("k"=>"tkejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.modus_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function getModusByKejadianId($id)
	{		
		$modusTable = new modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function deleteModus($id)
	{
		$modusTable = new modus(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusTable->getAdapter()->quoteInto('modus_id = ?', $id);
			$modusTable->delete($where);
		}
	}

	function getModusByKejModId($id)
	{		
		$modusTable = new modus(array('db' => 'db')); //use db object from registry

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>"modus"), array("m.*"));
		$select->joinLeft(array("k"=>"kejadian"), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.sort_order');
			
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
}
?>