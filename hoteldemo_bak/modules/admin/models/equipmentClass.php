 <?php

require_once('adminClass.php');
require_once('dbClass.php');

class equipmentClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getSecurityEquipments()
	{
		$equipmentTable = new security_equipment_list(array('db' => 'db')); //use db object from registry

		//$select = $equipmentTable->select()->order("equipment_name")->where('site_id = ?', $this->site_id);

		$select = $equipmentTable->getAdapter()->select();
		$select->from(array("e"=>"security_equipment_list"), array('e.*'));
		$select->joinLeft(array("v"=>"security_vendor"), "v.vendor_id=e.vendor_id and v.site_id = e.site_id");
		$select->where('e.site_id = ?', $this->site_id);
		$select->order(array("e.equipment_name"));
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSecurityEquipment($params)
	{		
		$equipmentTable = new security_equipment_list(array('db' => 'db')); //use db object from registryv
		
		$data = array(
			'equipment_name'	=> $params['equipment_name'],
			'vendor_id'			=> $params['vendor'],
			'site_id'			=> $this->site_id
		);
			
		if(empty($params['security_equipment_list_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('security_equipment_list_id = ?', $params['security_equipment_list_id']);
			$equipmentTable->update($data, $where);
		}
		
	}
	
	function getSecurityEquipmentById($id)
	{		
		$equipmentTable = new security_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('security_equipment_list_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSecurityEquipment($id)
	{
		$equipmentTable = new security_equipment_list(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('security_equipment_list_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}
	
	function getSafetyEquipments()
	{
		$equipmentTable = new safety_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()->order("equipment_name")->where('site_id = ?', $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	function addSafetyEquipment($params)
	{		
		$equipmentTable = new safety_equipment_list(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'no'				=> $params['no'],
			'equipment_name'	=> $params['equipment_name'],
			'site_id'			=> $this->site_id
		);
			
		if(empty($params['safety_equipment_list_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('safety_equipment_list_id = ?', $params['safety_equipment_list_id']);
			$equipmentTable->update($data, $where);
		}
		
	}
	
	function getSafetyEquipmentById($id)
	{		
		$equipmentTable = new safety_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('safety_equipment_list_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSafetyEquipment($id)
	{
		$equipmentTable = new safety_equipment_list(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('safety_equipment_list_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}
	
	function getSafetyEquipmentItems()
	{
		$equipmentTable = new safety_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->getAdapter()->select();
		$select->from(array("i"=>"safety_equipment_list_items"), array('i.*'));
		$select->joinLeft(array("e"=>"safety_equipment_list"), "e.safety_equipment_list_id=i.safety_equipment_list_id and e.site_id = i.site_id", array("e.*"));
		$select->where('i.site_id = ?', $this->site_id);
		$select->order(array("e.no"));
		
		return $this->db->fetchAll($select);
	}
	
	function addSafetyEquipmentItem($params)
	{		
		$equipmentTable = new safety_equipment_list_items(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'safety_equipment_list_id'	=> $params['safety_equipment_list_id'],
			'item_name'					=> $params['item_name'],
			'status'					=> $params['status'],
			'status_cut_in'				=> $params['status_cut_in'],
			'status_cut_off'			=> $params['status_cut_off'],
			'sort_order'				=> '0',
			'site_id'					=> $this->site_id
		);
			
		if(empty($params['equipment_item_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_item_id = ?', $params['equipment_item_id']);
			$equipmentTable->update($data, $where);
		}
		
	}
	
	function getSafetyEquipmentItemById($id)
	{		
		$equipmentTable = new safety_equipment_list_items(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_item_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteSafetyEquipmentItem($id)
	{
		$equipmentTable = new safety_equipment_list_items(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_item_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}
	
	/*** PARKING EQUIPMENT ***/
	
	function getParkingEquipments($type)
	{
		$equipmentTable = new parking_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()->where('equipment_type = ?', $type)->where('site_id = ?', $this->site_id)->order("equipment_name");
		
		return $this->db->fetchAll($select);
	}
	
	
	function addParkingEquipment($params)
	{		
		$equipmentTable = new parking_equipment_list(array('db' => 'db')); //use db object from registryv
		
		$data = array(
			'equipment_name'	=> $params['equipment_name'],
			'equipment_type'	=> $params['equipment_type'],
			'site_id'			=> $this->site_id
		);
			
		if(empty($params['parking_equipment_list_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('parking_equipment_list_id = ?', $params['parking_equipment_list_id']);
			$equipmentTable->update($data, $where);
		}
		
	}
	
	function getParkingEquipmentById($id)
	{		
		$equipmentTable = new parking_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('parking_equipment_list_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteParkingEquipment($id)
	{
		$equipmentTable = new parking_equipment_list(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('parking_equipment_list_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}
	
	/*** MOD EQUIPMENT ***/
	
	function getModEquipments($site_id)
	{
		$equipmentTable = new mod_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()->where('site_id = ?', $site_id)->order("equipment_name");
		
		return $this->db->fetchAll($select);
	}
	
	
	function addModEquipment($params)
	{		
		$equipmentTable = new mod_equipment_list(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'equipment_name'	=> $params['equipment_name'],
			'site_id'			=> $params['site_id']
		);
			
		if(empty($params['mod_equipment_list_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('mod_equipment_list_id = ?', $params['mod_equipment_list_id']);
			$equipmentTable->update($data, $where);
		}
		
	}
	
	function getModEquipmentById($id)
	{		
		$equipmentTable = new mod_equipment_list(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('mod_equipment_list_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteModEquipment($id)
	{
		$equipmentTable = new mod_equipment_list(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('mod_equipment_list_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}

	/*** Jenis Peralatan Proteksi Gedung ***/

	function getBuildingProtectionEquipmentType()
	{		
		$equipmentTable = new building_protection_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('site_id = ?', $this->site_id)
			->order('type')
			->order('sort_order');
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}

	function addBuildingProtectionEquipmentType($params)
	{		
		$equipmentTable = new building_protection_equipment(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $params['site_id'],
			'type'				=> $params['type'],
			'equipment_name'	=> $params['equipment_name'],
			'column_name'		=> $params['column_name'],
			'sort_order'		=> $params['sort_order']
		);
			
		if(empty($params['equipment_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_id = ?', $params['equipment_id']);
			$equipmentTable->update($data, $where);
		}
	}

	function getBuildingProtectionEquipmentTypeById($id)
	{		
		$equipmentTable = new building_protection_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteBuildingProtectionEquipmentType($id)
	{
		$equipmentTable = new building_protection_equipment(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}

	function getBuildingProtectionEquipmentTypeByName($name, $site_id)
	{		
		$equipmentTable = new building_protection_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_name = ?', $name)
			->where('site_id = ?', $site_id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}

	/*** Peralatan Proteksi Gedung ***/

	function getBuildingProtectionEquipment()
	{		
		$equipmentTable = new building_protection_equipment_item(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->getAdapter()->select();
		$select->from(array("i"=>"building_protection_equipment_item"), array('i.*'));
		$select->joinLeft(array("bpe"=>"building_protection_equipment"), "bpe.equipment_id=i.equipment_id",array('bpe.equipment_name', 'bpe.sort_order as bpe_sort_order'));
		$select->where('i.site_id = ?', $this->site_id);
		$select->order("bpe.type");
		$select->order("bpe.sort_order");
		$select->order("i.sort_order");
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}

	function addBuildingProtectionEquipment($params)
	{		
		$equipmentTable = new building_protection_equipment_item(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $params['site_id'],
			'equipment_id'		=> $params['equipment_id'],
			'sort_order'		=> $params['sort_order'],
			'item_name'			=> $params['item_name'],
			'enable'			=> $params['enable']
		);
			
		if(empty($params['equipment_item_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_item_id = ?', $params['equipment_item_id']);
			$equipmentTable->update($data, $where);
		}
	}

	function getBuildingProtectionEquipmentById($id)
	{		
		$equipmentTable = new building_protection_equipment_item(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_item_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteBuildingProtectionEquipment($id)
	{
		$equipmentTable = new building_protection_equipment_item(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_item_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}

	/*** PERLENGKAPAN PENANGGULANGAN KEBAKARAN DAN KECELAKAAN GEDUNG ***/

	function getFireAccidentEquipment()
	{		
		$equipmentTable = new fire_accident_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('site_id = ?', $this->site_id)
			->order('sort_order');
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}

	function addFireAccidentEquipment($params)
	{		
		$equipmentTable = new fire_accident_equipment(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $params['site_id'],
			'equipment_name'	=> $params['equipment_name'],
			'sort_order'		=> $params['sort_order'],			
			'enable'			=> $params['enable']
		);
			
		if(empty($params['equipment_id']))
		{
			$equipmentTable->insert($data);
		}
		else
		{
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_id = ?', $params['equipment_id']);
			$equipmentTable->update($data, $where);
		}
	}

	function getFireAccidentEquipmentById($id)
	{		
		$equipmentTable = new fire_accident_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteFireAccidentEquipment($id)
	{
		$equipmentTable = new fire_accident_equipment(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $equipmentTable->getAdapter()->quoteInto('equipment_id = ?', $id);
			$equipmentTable->delete($where);
		}
	}
	
	function getFireAccidentEquipmentByName($name, $site_id)
	{		
		$equipmentTable = new fire_accident_equipment(array('db' => 'db')); //use db object from registry

		$select = $equipmentTable->select()
			->where('equipment_name = ?', $name)
			->where('site_id = ?', $site_id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
}
?>