<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class manpowerClass extends defaultClass
{	
	function getManPower($params) {
		$manpowerTable = new manpower(array('db'=>'db'));
		
		$select = $manpowerTable->select()->where('site_id = ?', $this->site_id)->where('category_id = ?', $params['c'])->order('inhouse_outsource')->order('name');
		$manPower = $manpowerTable->getAdapter()->fetchAll($select);
		return $manPower;	
	}

	function saveManPower($params) {
		$manpowerTable = new manpower(array('db'=>'db'));
		$data = array(
			"site_id" => $this->site_id,
			"inhouse_outsource" => $params["inhouse_outsource"],
			"name" => $params["name"],
			"category_id" => $params["c"],
			"year_start_exp" => $params["year_start_exp"],
			"year_of_birth" => $params["year_of_birth"],
			"join_year" => $params["join_year"],
			"position" => $params["position"],
			"vendor" => $params["vendor"],
			"active" => $params["active"],
			"certificate_no" => $params["certificate_no"]
		);
		
		if(empty($params["manpower_id"])) 
		{
			$manpowerTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $manpowerTable->getAdapter()->quoteInto('manpower_id = ?', $params["manpower_id"]);
			$manpowerTable->update($data, $where);
			return $params["manpower_id"];
		}
	}

	function getManPowerById($id)
	{
		$manpowerTable = new manpower(array('db'=>'db'));
		$select = $manpowerTable->select()->where('manpower_id = ?', $id);
		$manpower = $manpowerTable->getAdapter()->fetchRow($select);
		return $manpower;
	}	

	function getManPowerByKeyword($keyword, $cat_id, $inhouse_outsource)
	{
		$manpowerTable = new manpower(array('db'=>'db'));

		$select = $manpowerTable->getAdapter()->select();
		$select->from(array("m"=>"manpower"), array("m.manpower_id", "m.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->where('m.category_id = ?', $cat_id);
		$select->where('m.inhouse_outsource = ?', $inhouse_outsource);
		$select->where('m.name like "%'.$keyword.'%"');
		$select->where('m.active = ?', '1');
		$manpower = $manpowerTable->getAdapter()->fetchAll($select);
		return $manpower;
	}	

	function getManPowerIdByName($name, $cat_id, $inhouse_outsource)
	{
		$manpowerTable = new manpower(array('db'=>'db'));

		$select = $manpowerTable->getAdapter()->select();
		$select->from(array("m"=>"manpower"), array("m.manpower_id"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->where('m.category_id = ?', $cat_id);
		$select->where('m.inhouse_outsource = ?', $inhouse_outsource);
		$select->where('m.name = ?', $name);
		$manpower = $manpowerTable->getAdapter()->fetchRow($select);
		return $manpower;
	}	

	function deleteManPowerById($id)
	{
		$manpowerTable = new manpower(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $manpowerTable->getAdapter()->quoteInto('manpower_id = ?', $id);
			$manpowerTable->delete($where);
		}
	}

	function insertManPower($params) {
		$manpowerTable = new manpower(array('db'=>'db'));
		$data = array(
			"site_id" => $params["site_id"],
			"inhouse_outsource" => $params["inhouse_outsource"],
			"name" => $params["name"],
			"category_id" => $params["c"],
			"year_start_exp" => $params["year_start_exp"],
			"year_of_birth" => $params["year_of_birth"],
			"join_year" => $params["join_year"],
			"position" => $params["position"],
			"vendor" => $params["vendor"],
			"active" => '1',
			"certificate_no" => $params["certificate_no"]
		);
		$manpowerTable->insert($data);
	}
}
?>