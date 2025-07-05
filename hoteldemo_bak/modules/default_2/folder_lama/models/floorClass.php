<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class floorClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getFloorByCategoryId($category_id)
	{
		if($category_id == 1) $floorTable = new security_floor(array('db'=>'db'));
		elseif($category_id == 2) $floorTable = new housekeeping_floor(array('db'=>'db'));
		elseif($category_id == 3) $floorTable = new safety_floor(array('db'=>'db'));
		elseif($category_id == 5) $floorTable = new parking_floor(array('db'=>'db'));
		elseif($category_id == 6) $floorTable = new engineering_floor(array('db'=>'db'));
		elseif($category_id == 10) $floorTable = new building_service_floor(array('db'=>'db'));
		elseif($category_id == 11) $floorTable = new tenant_relation_floor(array('db'=>'db'));

		$select = $floorTable->select()->where("site_id=?", $this->site_id);		
		$rs = $floorTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getFloorByCategoryIdAndSites($category_id, $site_ids = "")
	{
		if($category_id == 1) $floorTable = new security_floor(array('db'=>'db'));
		elseif($category_id == 2) $floorTable = new housekeeping_floor(array('db'=>'db'));
		elseif($category_id == 3) $floorTable = new safety_floor(array('db'=>'db'));
		elseif($category_id == 5) $floorTable = new parking_floor(array('db'=>'db'));
		elseif($category_id == 6) $floorTable = new engineering_floor(array('db'=>'db'));
		elseif($category_id == 10) $floorTable = new building_service_floor(array('db'=>'db'));
		elseif($category_id == 11) $floorTable = new tenant_relation_floor(array('db'=>'db'));

		$select = $floorTable->select();
		if(!empty($site_ids)) $select->where('site_id IN ('.$site_ids.')');	
		$select->group('floor');	
		$select->order('sort_order');	
		$rs = $floorTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getFloorById($floor_id, $category_id)
	{
		if($category_id == 1) $floorTable = new security_floor(array('db'=>'db'));
		elseif($category_id == 2) $floorTable = new housekeeping_floor(array('db'=>'db'));
		elseif($category_id == 3) $floorTable = new safety_floor(array('db'=>'db'));
		elseif($category_id == 5) $floorTable = new parking_floor(array('db'=>'db'));
		elseif($category_id == 6) $floorTable = new engineering_floor(array('db'=>'db'));
		elseif($category_id == 10) $floorTable = new building_service_floor(array('db'=>'db'));
		elseif($category_id == 11) $floorTable = new tenant_relation_floor(array('db'=>'db'));

		$select = $floorTable->select()->where("floor_id=?", $floor_id);		
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;
	}

	function getTotalEachModus($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		if($category_id == 1) {
			$floorTable = new security_floor(array('db'=>'db'));
			$table_name = "security_floor";
		}
		elseif($category_id == 2)
		{
			$floorTable = new housekeeping_floor(array('db'=>'db'));
			$table_name = "housekeeping_floor";
		}
		elseif($category_id == 3)
		{
			$floorTable = new safety_floor(array('db'=>'db'));
			$table_name = "safety_floor";
		}
		elseif($category_id == 5) 
		{
			$floorTable = new parking_floor(array('db'=>'db'));
			$table_name = "parking_floor";
		}
		elseif($category_id == 6) 
		{
			$floorTable = new engineering_floor(array('db'=>'db'));
			$table_name = "engineering_floor";
		}
		elseif($category_id == 10) 
		{
			$floorTable = new building_service_floor(array('db'=>'db'));
			$table_name = "building_service_floor";
		}
		elseif($category_id == 11) 
		{
			$floorTable = new tenant_relation_floor(array('db'=>'db'));
			$table_name = "tenant_relation_floor";
		}

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>$table_name), array("count(*) as total", "f.floor"));
		$select->joinLeft(array("i"=>"issues"), "f.site_id = i.site_id and i.floor_id = f.floor_id", array());
		/*if($category_id == 1) 
			$select->joinLeft(array("k"=>"security_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 2)
			$select->joinLeft(array("k"=>"housekeeping_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 3)
			$select->joinLeft(array("k"=>"safety_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 5) 
			$select->joinLeft(array("k"=>"parking_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 6) 
			$select->joinLeft(array("k"=>"engineering_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 10) 
			$select->joinLeft(array("k"=>"building_service_kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));
		elseif($category_id == 11) 
			$select->joinLeft(array("k"=>"tenant_relation__kejadian"), "i.kejadian_id = k.kejadian_id and k.issue_type = i.issue_type_id and k.site_id = i.site_id", array("k.kejadian"));*/

		//$select->where("f.site_id= ?", $this->site_id);
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		//$select->where("k.kejadian_id > ?", 0);
		if(!empty($site_ids)) $select->where("f.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(i.issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(i.issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("i.floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
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
		$select->group("f.floor");	
		$select->order("total desc");	
		$select->limit(20);
		$rs = $floorTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getFloorIdByFloorNameAndSites($category_id, $floor_name, $site_ids)
	{
		if($category_id == 1) {
			$floorTable = new security_floor(array('db'=>'db'));
			$table_name = "security_floor";
		}
		elseif($category_id == 2)
		{
			$floorTable = new housekeeping_floor(array('db'=>'db'));
			$table_name = "housekeeping_floor";
		}
		elseif($category_id == 3)
		{
			$floorTable = new safety_floor(array('db'=>'db'));
			$table_name = "safety_floor";
		}
		elseif($category_id == 5) 
		{
			$floorTable = new parking_floor(array('db'=>'db'));
			$table_name = "parking_floor";
		}
		elseif($category_id == 6) 
		{
			$floorTable = new engineering_floor(array('db'=>'db'));
			$table_name = "engineering_floor";
		}
		elseif($category_id == 10) 
		{
			$floorTable = new building_service_floor(array('db'=>'db'));
			$table_name = "building_service_floor";
		}
		elseif($category_id == 11) 
		{
			$floorTable = new tenant_relation_floor(array('db'=>'db'));
			$table_name = "tenant_relation_floor";
		}

		$select = $floorTable->getAdapter()->select();
		$select->from(array("f"=>$table_name), array("f.floor_id"));
		$select->where('f.floor = ?', $floor_name);
		if(!empty($site_ids)) $select->where('f.site_id IN ('.$site_ids.')');	
		$select->group('f.floor');		
		$rs = $floorTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getFloorByAreaId($area_id, $category_id, $site_id = 0)
	{
		if($category_id == 1) $floorTable = new security_floor(array('db'=>'db'));
		elseif($category_id == 2) $floorTable = new housekeeping_floor(array('db'=>'db'));
		elseif($category_id == 3) $floorTable = new safety_floor(array('db'=>'db'));
		elseif($category_id == 5) $floorTable = new parking_floor(array('db'=>'db'));
		elseif($category_id == 6) $floorTable = new engineering_floor(array('db'=>'db'));
		elseif($category_id == 10) $floorTable = new building_service_floor(array('db'=>'db'));
		elseif($category_id == 11) $floorTable = new tenant_relation_floor(array('db'=>'db'));

		$select = $floorTable->select();
		if(!empty($site_id)) $select->where("site_id=?", $site_id);
		$select->where("area IN (".$area_id.")")->group('floor')->order('sort_order')->order('floor');		
		$rs = $floorTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getFloorByName($floor_name, $category_id)
	{
		if($category_id == 1) $floorTable = new security_floor(array('db'=>'db'));
		elseif($category_id == 2) $floorTable = new housekeeping_floor(array('db'=>'db'));
		elseif($category_id == 3) $floorTable = new safety_floor(array('db'=>'db'));
		elseif($category_id == 5) $floorTable = new parking_floor(array('db'=>'db'));
		elseif($category_id == 6) $floorTable = new engineering_floor(array('db'=>'db'));
		elseif($category_id == 10) $floorTable = new building_service_floor(array('db'=>'db'));
		elseif($category_id == 11) $floorTable = new tenant_relation_floor(array('db'=>'db'));

		$select = $floorTable->select()->where("site_id=?", $this->site_id)->where("floor=?", $floor_name)->order('sort_order')->order('floor');		
		$rs = $floorTable->getAdapter()->fetchRow($select);
		return $rs;
	}

}

?>