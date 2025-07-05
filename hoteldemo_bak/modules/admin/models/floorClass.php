<?php

require_once('adminClass.php');
require_once('dbClass.php');

class floorClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*** SECURITY ***/
	function getSecurityFloor()
	{
		$floorTable = new security_floor(array('db' => 'db')); //use db object from registry
		
		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"security_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityFloor($params)
	{		
		$floorTable = new security_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getSecurityFloorById($id)
	{		
		$floorTable = new security_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityFloor($id)
	{
		$floorTable = new security_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getSecurityFloorByName($floor, $site_id)
	{		
		$floorTable = new security_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** SAFETY ***/

	function getSafetyFloor()
	{
		$floorTable = new safety_floor(array('db' => 'db')); //use db object from registry
		
		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"safety_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSafetyFloor($params)
	{		
		$floorTable = new safety_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getSafetyFloorById($id)
	{		
		$floorTable = new safety_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteSafetyFloor($id)
	{
		$floorTable = new safety_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getSafetyFloorByName($floor, $site_id)
	{		
		$floorTable = new safety_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** PARKING & TRAFFIC ***/

	function getParkingFloor()
	{
		$floorTable = new parking_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"parking_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addParkingFloor($params)
	{		
		$floorTable = new parking_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getParkingFloorById($id)
	{		
		$floorTable = new parking_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteParkingFloor($id)
	{
		$floorTable = new parking_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getParkingFloorByName($floor, $site_id)
	{		
		$floorTable = new parking_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** ENGINEERING ***/
	function getEngineeringFloor()
	{
		$floorTable = new engineering_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"engineering_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addEngineeringFloor($params)
	{		
		$floorTable = new engineering_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getEngineeringFloorById($id)
	{		
		$floorTable = new engineering_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteEngineeringFloor($id)
	{
		$floorTable = new engineering_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getEngineeringFloorByName($floor, $site_id)
	{		
		$floorTable = new engineering_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** HOUSEKEEPING ***/
	function getHousekeepingFloor()
	{
		$floorTable = new housekeeping_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"housekeeping_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addHousekeepingFloor($params)
	{		
		$floorTable = new housekeeping_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getHousekeepingFloorById($id)
	{		
		$floorTable = new housekeeping_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteHousekeepingFloor($id)
	{
		$floorTable = new housekeeping_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getHousekeepingFloorByName($floor, $site_id)
	{		
		$floorTable = new housekeeping_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	/*** BUILDING SERVICE ***/
	function getBuildingServiceFloor()
	{
		$floorTable = new building_service_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"building_service_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addBuildingServiceFloor($params)
	{		
		$floorTable = new building_service_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getBuildingServiceFloorById($id)
	{		
		$floorTable = new building_service_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteBuildingServiceFloor($id)
	{
		$floorTable = new building_service_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getBuildingServiceFloorByName($floor, $site_id)
	{		
		$floorTable = new building_service_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** TENANT RELATION ***/
	function getTenantRelationFloor()
	{
		$floorTable = new tenant_relation_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>"tenant_relation_floor"), array("f.*"));
		$select->joinLeft(array("a"=>"area"), "a.area_id = f.area", array("a.area_name"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->order('a.area_name');
		$select->order('f.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addTenantRelationFloor($params)
	{		
		$floorTable = new tenant_relation_floor(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'area'				=> intval($params['area_id']),
			'floor'				=> $params['floor'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['floor_id']))
		{
			$floorTable->insert($data);
		}
		else
		{
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $params['floor_id']);
			$floorTable->update($data, $where);
		}
		
	}
	
	function getTenantRelationFloorById($id)
	{		
		$floorTable = new tenant_relation_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()
			->where('floor_id = ?', $id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteTenantRelationFloor($id)
	{
		$floorTable = new tenant_relation_floor(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $floorTable->getAdapter()->quoteInto('floor_id = ?', $id);
			$floorTable->delete($where);
		}
	}

	function getTenantRelationFloorByName($floor, $site_id)
	{		
		$floorTable = new tenant_relation_floor(array('db' => 'db')); //use db object from registry

		$select = $floorTable->select()->where('floor = ?', $floor)->where('site_id = ?', $site_id);
			
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
}
?>