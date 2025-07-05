<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class modusClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getModusByKejadianId($kejadian_id, $category_id)
	{
		if($category_id == 1) $modusTable = new security_modus(array('db'=>'db'));
		elseif($category_id == 2) $modusTable = new housekeeping_modus(array('db'=>'db'));
		elseif($category_id == 3) $modusTable = new safety_modus(array('db'=>'db'));
		elseif($category_id == 5) $modusTable = new parking_modus(array('db'=>'db'));
		elseif($category_id == 6) $modusTable = new engineering_modus(array('db'=>'db'));
		elseif($category_id == 10) $modusTable = new building_service_modus(array('db'=>'db'));
		elseif($category_id == 11) $modusTable = new tenant_relation_modus(array('db'=>'db'));

		$select = $modusTable->select()->where("kejadian_id=?", $kejadian_id)->where("site_id=?", $this->site_id);		
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getModusByKejadianIds($kejadian_ids, $category_id)
	{
		if($category_id == 1) $modusTable = new security_modus(array('db'=>'db'));
		elseif($category_id == 2) $modusTable = new housekeeping_modus(array('db'=>'db'));
		elseif($category_id == 3) $modusTable = new safety_modus(array('db'=>'db'));
		elseif($category_id == 5) $modusTable = new parking_modus(array('db'=>'db'));
		elseif($category_id == 6) $modusTable = new engineering_modus(array('db'=>'db'));
		elseif($category_id == 10) $modusTable = new building_service_modus(array('db'=>'db'));
		elseif($category_id == 11) $modusTable = new tenant_relation_modus(array('db'=>'db'));

		$select = $modusTable->select()->where("kejadian_id IN (".$kejadian_ids.")")->where("site_id=?", $this->site_id);		
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	/*** Get modus by incidents name (can be multiple incidents) ***/
	function getModusByIncidentsAndSites($incidents, $category_id, $site_ids)
	{
		if($category_id == 1) $modusTable = new security_modus(array('db'=>'db'));
		elseif($category_id == 2) $modusTable = new housekeeping_modus(array('db'=>'db'));
		elseif($category_id == 3) $modusTable = new safety_modus(array('db'=>'db'));
		elseif($category_id == 5) $modusTable = new parking_modus(array('db'=>'db'));
		elseif($category_id == 6) $modusTable = new engineering_modus(array('db'=>'db'));
		elseif($category_id == 10) $modusTable = new building_service_modus(array('db'=>'db'));
		elseif($category_id == 11) $modusTable = new tenant_relation_modus(array('db'=>'db'));

		$select = $modusTable->select()->where("kejadian_id IN (".$incidents.")");
		if(!empty($site_ids)) $select->where("site_id IN (".$site_ids.")");		
		$select->group('modus');
		$select->order('sort_order');
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}


	function getModusById($modus_id, $category_id)
	{
		if($category_id == 1) $modusTable = new security_modus(array('db'=>'db'));
		elseif($category_id == 2) $modusTable = new housekeeping_modus(array('db'=>'db'));
		elseif($category_id == 3) $modusTable = new safety_modus(array('db'=>'db'));
		elseif($category_id == 5) $modusTable = new parking_modus(array('db'=>'db'));
		elseif($category_id == 6) $modusTable = new engineering_modus(array('db'=>'db'));
		elseif($category_id == 10) $modusTable = new building_service_modus(array('db'=>'db'));
		elseif($category_id == 11) $modusTable = new tenant_relation_modus(array('db'=>'db'));

		$select = $modusTable->select()->where("modus_id=?", $modus_id);		
		$rs = $modusTable->getAdapter()->fetchRow($select);
		return $rs;
	}

	function getModus($category_id, $issue_type_id = 0)
	{
		if($category_id == 1)
		{
			$modusTable = new security_modus(array('db'=>'db'));
			$tableName = "security_modus";
			$kejadianTable = "security_kejadian";
		} 
		elseif($category_id == 2) 
		{
			$modusTable = new housekeeping_modus(array('db'=>'db'));
			$tableName = "housekeeping_modus";
			$kejadianTable = "housekeeping_kejadian";
		}
		elseif($category_id == 3) 
		{
			$modusTable = new safety_modus(array('db'=>'db'));
			$tableName = "safety_modus";
			$kejadianTable = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$modusTable = new parking_modus(array('db'=>'db'));
			$tableName = "parking_modus";
			$kejadianTable = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$modusTable = new engineering_modus(array('db'=>'db'));
			$tableName = "engineering_modus";
			$kejadianTable = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$modusTable = new building_service_modus(array('db'=>'db'));
			$tableName = "building_service_modus";
			$kejadianTable = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$modusTable = new tenant_relation_modus(array('db'=>'db'));
			$tableName = "tenant_relation_modus";
			$kejadianTable = "tenant_relation_kejadian";
		}

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>$tableName), array("m.*"));
		$select->joinLeft(array("k"=>$kejadianTable), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->where("m.site_id=?", $this->site_id);
		if($issue_type_id > 0) $select->where("k.issue_type=?", $issue_type_id);
		$select->order("k.sort_order");
		$select->order("m.sort_order");
		$select->order("k.kejadian");
		$select->order("m.modus");
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getTotalEachModus($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		if($category_id == 1)
		{
			$modusTable = new security_modus(array('db'=>'db'));
			$tableName = "security_modus";
			$kejadianTable = "security_kejadian";
		} 
		elseif($category_id == 2) 
		{
			$modusTable = new housekeeping_modus(array('db'=>'db'));
			$tableName = "housekeeping_modus";
			$kejadianTable = "housekeeping_kejadian";
		}
		elseif($category_id == 3) 
		{
			$modusTable = new safety_modus(array('db'=>'db'));
			$tableName = "safety_modus";
			$kejadianTable = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$modusTable = new parking_modus(array('db'=>'db'));
			$tableName = "parking_modus";
			$kejadianTable = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$modusTable = new engineering_modus(array('db'=>'db'));
			$tableName = "engineering_modus";
			$kejadianTable = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$modusTable = new building_service_modus(array('db'=>'db'));
			$tableName = "building_service_modus";
			$kejadianTable = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$modusTable = new tenant_relation_modus(array('db'=>'db'));
			$tableName = "tenant_relation_modus";
			$kejadianTable = "tenant_relation_kejadian";
		}


		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>$tableName), array("m.modus", "count(*) as total"));
		$select->joinLeft(array("k"=>$kejadianTable), "k.kejadian_id = m.kejadian_id", array("k.kejadian"));
		$select->joinLeft(array("i"=>"issues"), "k.issue_type = i.issue_type_id and m.site_id = i.site_id and i.modus_id = m.modus_id", array());
		//$select->where("m.site_id=?", $this->site_id);
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		//$select->where('k.kejadian_id > ?', 0);
		if(!empty($site_ids)) $select->where("m.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id = ?", $tenant_umum);
		if(!empty($issuetypes)) $select->where("i.issue_type_id IN (".$issuetypes.")");
		if(!empty($incidents)) $select->where("i.kejadian_id IN (".$incidents.")");
		if(!empty($modus)) $select->where("i.modus_id IN (".$modus.")");
		if(!empty($period) && count($period) < 5)
		{
			$q = "";
			$j = 0;
			foreach($period as $p)
			{
				if($j > 0) $q .= ' OR ';
				switch($p)
				{
					case 1: $q .= '(time(i.issue_date) > "09:00" and time(i.issue_date) <= "12:00")'; break; 
					case 2: $q .= '(time(i.issue_date) > "12:00" and time(i.issue_date) <= "16:00")'; break;
					case 3: $q .= '(time(i.issue_date) > "16:00" and time(i.issue_date) <= "19:00")'; break;
					case 4: $q .= '(time(i.issue_date) > "19:00" and time(i.issue_date) <= "23:00")'; break;
					case 5: $q .= '(time(i.issue_date) > "23:00" or time(i.issue_date) <= "09:00")'; break;
				}
				$j++;
			}
			$select->where($q);
		}
		//$select->group("m.modus_id");	
		$select->group("m.modus");	
		$select->order("total desc");	
		$select->limit(20);	
		//echo $select; exit();
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getModusIdByModusNameAndSites($category_id, $modus_name, $site_ids, $kejadian_id = 0)
	{
		if($category_id == 1) {
			$modusTable = new security_modus(array('db'=>'db'));
			$table_name = "security_modus";
		}
		elseif($category_id == 2) 
		{
			$modusTable = new housekeeping_modus(array('db'=>'db'));
			$table_name = "housekeeping_modus";
		}
		elseif($category_id == 3)
		{
			$modusTable = new safety_modus(array('db'=>'db'));
			$table_name = "safety_modus";
		}
		elseif($category_id == 5) 
		{
			$modusTable = new parking_modus(array('db'=>'db'));
			$table_name = "parking_modus";
		}
		elseif($category_id == 6) 
		{
			$modusTable = new engineering_modus(array('db'=>'db'));
			$table_name = "engineering_modus";
		}
		elseif($category_id == 10) 
		{
			$modusTable = new building_service_modus(array('db'=>'db'));
			$table_name = "building_service_modus";
		}
		elseif($category_id == 11) 
		{
			$modusTable = new tenant_relation_modus(array('db'=>'db'));
			$table_name = "tenant_relation_modus";
		}

		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>$table_name), array("m.modus_id"));
		$select->where('m.modus = ?', $modus_name);
		if(!empty($site_ids)) $select->where('m.site_id IN ('.$site_ids.')');	
		if(!empty($kejadian_id)) $select->where('m.kejadian_id = ?', $kejadian_id);
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getModusLinked($params)
	{
		$modusLinkedTable = new modus_linked(array('db'=>'db'));
		$select = $modusLinkedTable->select()->where("category_id=?", $params['category'])->where("kejadian_id=?", $params['incident_id'])->where("modus_id=?", $params['modus_id']);		
		$rs = $modusLinkedTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getModusByIssueType($issue_type_id, $category_id)
	{
		switch($category_id) {
			case 1: $modusTable = new security_modus(array('db'=>'db'));
					$table_name = "security_modus";
					$kejadianTable = "security_kejadian";
					break;
			case 2: $modusTable = new housekeeping_modus(array('db'=>'db'));
					$table_name = "housekeeping_modus";
					$kejadianTable = "housekeeping_kejadian";
					break;
			case 3: $modusTable = new safety_modus(array('db'=>'db'));
					$table_name = "safety_modus";
					$kejadianTable = "safety_kejadian";
					break;
			case 5: $modusTable = new parking_modus(array('db'=>'db'));
					$table_name = "parking_modus";
					$kejadianTable = "parking_kejadian";
					break;
			case 6: $modusTable = new engineering_modus(array('db'=>'db'));
					$table_name = "engineering_modus";
					$kejadianTable = "engineering_kejadian";
					break;
			case 10: $modusTable = new building_service_modus(array('db'=>'db'));
					 $table_name = "building_service_modus";
					 $kejadianTable = "building_service_kejadian";
					 break;
			case 11: $modusTable = new tenant_relation_modus(array('db'=>'db'));
					 $table_name = "tenant_relation_modus";
					 $kejadianTable = "tenant_relation_kejadian";
					 break;
		}
		
		$select = $modusTable->getAdapter()->select();
		$select->from(array("m"=>$table_name), array("m.modus"));
		$select->joinLeft(array("k"=>$kejadianTable), "k.kejadian_id = m.kejadian_id", array());
		$select->where("m.site_id=?", $this->site_id);
		$select->where("k.issue_type=?", $issue_type_id);
		$select->order("m.sort_order");
		$select->order("m.modus");
		$rs = $modusTable->getAdapter()->fetchAll($select);
		return $rs;
	}
}

?>