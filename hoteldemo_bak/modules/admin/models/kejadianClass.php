<?php

require_once('adminClass.php');
require_once('dbClass.php');

class kejadianClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	/*** SECURITY KEJADIAN ***/

	function getSecurityKejadian()
	{
		$kejadianTable = new security_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"security_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);		
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityKejadian($params)
	{		
		$kejadianTable = new security_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getSecurityKejadianById($id)
	{		
		$kejadianTable = new security_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityKejadian($id)
	{
		$kejadianTable = new security_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getSecurityKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new security_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** SAFETY KEJADIAN ***/

	function getSafetyKejadian()
	{
		$kejadianTable = new safety_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"safety_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		return $this->db->fetchAll($select);
	}
	
	
	function addSafetyKejadian($params)
	{		
		$kejadianTable = new safety_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getSafetyKejadianById($id)
	{		
		$kejadianTable = new safety_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteSafetyKejadian($id)
	{
		$kejadianTable = new safety_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getSafetyKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new safety_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** PARKING & TRAFFIC KEJADIAN ***/

	function getParkingKejadian()
	{
		$kejadianTable = new parking_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"parking_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addParkingKejadian($params)
	{		
		$kejadianTable = new parking_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getParkingKejadianById($id)
	{		
		$kejadianTable = new parking_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteParkingKejadian($id)
	{
		$kejadianTable = new parking_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getParkingKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new parking_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** ENGINEERING KEJADIAN ***/

	function getEngineeringKejadian()
	{
		$kejadianTable = new engineering_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"engineering_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addEngineeringKejadian($params)
	{		
		$kejadianTable = new engineering_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getEngineeringKejadianById($id)
	{		
		$kejadianTable = new engineering_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteEngineeringKejadian($id)
	{
		$kejadianTable = new engineering_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getEngineeringKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new engineering_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** HOUSEKEEPING KEJADIAN ***/

	function getHousekeepingKejadian()
	{
		$kejadianTable = new housekeeping_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"housekeeping_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addHousekeepingKejadian($params)
	{		
		$kejadianTable = new housekeeping_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getHousekeepingKejadianById($id)
	{		
		$kejadianTable = new housekeeping_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteHousekeepingKejadian($id)
	{
		$kejadianTable = new housekeeping_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getHousekeepingKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new housekeeping_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** BUILDING SERVICE KEJADIAN ***/

	function getBuildingServiceKejadian()
	{
		$kejadianTable = new building_service_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"building_service_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addBuildingServiceKejadian($params)
	{		
		$kejadianTable = new building_service_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getBuildingServiceKejadianById($id)
	{		
		$kejadianTable = new building_service_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteBuildingServiceKejadian($id)
	{
		$kejadianTable = new building_service_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getBuildingServiceKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new building_service_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	/*** TENANT RELATION KEJADIAN ***/

	function getTenantRelationKejadian()
	{
		$kejadianTable = new tenant_relation_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"tenant_relation_kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		
		return $this->db->fetchAll($select);
	}
	
	
	function addTenantRelationKejadian($params)
	{		
		$kejadianTable = new tenant_relation_kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox']
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getTenantRelationKejadianById($id)
	{		
		$kejadianTable = new tenant_relation_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteTenantRelationKejadian($id)
	{
		$kejadianTable = new tenant_relation_kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}

	function getTenantRelationKejadianByName($kejadian, $site_id)
	{		
		$kejadianTable = new tenant_relation_kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()->where('kejadian = ?', $kejadian)->where('site_id = ?', $site_id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	
	function getKejadianByCatId($cat_id)
	{		
		$kejadianTable = new kejadian(array('db' => 'db')); 
		$select = $kejadianTable->getAdapter()->select();
		$select->from(array("k"=>"kejadian"), array("k.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = k.issue_type", array("it.issue_type as issue_type_name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->where('k.category_id = ?', $cat_id);
		$select->order('k.issue_type');
		$select->order('k.sort_order');
		return $this->db->fetchAll($select);
	}
	
	function addKejadian($params)
	{		
		$kejadianTable = new kejadian(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'issue_type'		=> $params['issue_type_id'],
			'kejadian'			=> $params['kejadian'],
			'site_id'			=> $params['site_id'],
			'sort_order'		=> $params['sort_order'],
			'show_pelaku_checkbox'	=> $params['show_pelaku_checkbox'],
			'category_id'			=> $params['category_id'],
		);	
		if(empty($params['kejadian_id']))
		{
			$kejadianTable->insert($data);
		}
		else
		{
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $params['kejadian_id']);
			$kejadianTable->update($data, $where);
		}
		
	}
	
	function getKejadianById($id)
	{		
		$kejadianTable = new kejadian(array('db' => 'db')); //use db object from registry

		$select = $kejadianTable->select()
			->where('kejadian_id = ?', $id);
			
		$rs = $kejadianTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteKejadian($id)
	{
		$kejadianTable = new kejadian(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $kejadianTable->getAdapter()->quoteInto('kejadian_id = ?', $id);
			$kejadianTable->delete($where);
		}
	}
	
}
?>