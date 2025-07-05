<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class incidentClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getIncidentByIssueTypeId($issue_type_id, $category_id)
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select()->where("issue_type=?", $issue_type_id)->where("site_id=?", $this->site_id);		
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIncidentByCategoryId($category_id)
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select()->where("site_id=?", $this->site_id);		
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIncidentByCategoryIdAndSites($category_id, $site_ids = "")
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select();
		if(!empty($site_ids)) $select->where('site_id IN ('.$site_ids.')');		
		$select->group('kejadian');	
		$select->order('sort_order');		
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIncidentById($kejadian_id, $category_id)
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select()->where("kejadian_id=?", $kejadian_id);		
		$rs = $incidentTable->getAdapter()->fetchRow($select);
		return $rs;
	}

	function getIncidentPerMonth($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		if($category_id == 1) {
			$incidentTable = new security_kejadian(array('db'=>'db'));
			$table_name = "security_kejadian";
		}
		elseif($category_id == 2)
		{
			$incidentTable = new housekeeping_kejadian(array('db'=>'db'));
			$table_name = "housekeeping_kejadian";
		}
		elseif($category_id == 3)
		{
			$incidentTable = new safety_kejadian(array('db'=>'db'));
			$table_name = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$incidentTable = new parking_kejadian(array('db'=>'db'));
			$table_name = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$incidentTable = new engineering_kejadian(array('db'=>'db'));
			$table_name = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$incidentTable = new building_service_kejadian(array('db'=>'db'));
			$table_name = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$incidentTable = new tenant_relation_kejadian(array('db'=>'db'));
			$table_name = "tenant_relation_kejadian";
		}

		$select = $incidentTable->getAdapter()->select();
		$select->from(array("k"=>$table_name), array("count(*) as total"));
		$select->joinLeft(array("i"=>"issues"), "k.issue_type = i.issue_type_id and k.site_id = i.site_id and i.kejadian_id = k.kejadian_id", array("month(i.issue_date) as mo"));
		//$select->where("k.site_id= ?", $this->site_id);
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("k.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
		if($tenant_umum != "") $select->where("i.area_id IN (".$tenant_umum.")");
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
		$select->group("month(issue_date)");	
		if($category_id == 5)
		{
			//echo $select; exit();
		}
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getTotalEachIncidents($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		if($category_id == 1) {
			$incidentTable = new security_kejadian(array('db'=>'db'));
			$table_name = "security_kejadian";
		}
		elseif($category_id == 2)
		{
			$incidentTable = new housekeeping_kejadian(array('db'=>'db'));
			$table_name = "housekeeping_kejadian";
		}
		elseif($category_id == 3)
		{
			$incidentTable = new safety_kejadian(array('db'=>'db'));
			$table_name = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$incidentTable = new parking_kejadian(array('db'=>'db'));
			$table_name = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$incidentTable = new engineering_kejadian(array('db'=>'db'));
			$table_name = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$incidentTable = new building_service_kejadian(array('db'=>'db'));
			$table_name = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$incidentTable = new tenant_relation_kejadian(array('db'=>'db'));
			$table_name = "tenant_relation_kejadian";
		}

		$select = $incidentTable->getAdapter()->select();
		$select->from(array("k"=>$table_name), array("count(*) as total", "k.kejadian_id", "k.kejadian"));
		$select->joinLeft(array("i"=>"issues"), "k.issue_type = i.issue_type_id and k.site_id = i.site_id and i.kejadian_id = k.kejadian_id", array());
		//$select->where("k.site_id= ?", $this->site_id);
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("k.site_id IN (".$site_ids.")");
		if(!empty($months)) $select->where("month(issue_date) IN (".$months.")");
		if(!empty($days)) $select->where("dayofweek(issue_date) IN (".$days.")");
		if(!empty($floors)) $select->where("floor_id IN (".$floors.")");
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
		//$select->group("k.kejadian_id");	
		$select->group("k.kejadian");	
		//echo $select; exit();
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIncidentPerDay($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		if($category_id == 1) {
			$incidentTable = new security_kejadian(array('db'=>'db'));
			$table_name = "security_kejadian";
		}
		elseif($category_id == 2)
		{
			$incidentTable = new housekeeping_kejadian(array('db'=>'db'));
			$table_name = "housekeeping_kejadian";
		}
		elseif($category_id == 3)
		{
			$incidentTable = new safety_kejadian(array('db'=>'db'));
			$table_name = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$incidentTable = new parking_kejadian(array('db'=>'db'));
			$table_name = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$incidentTable = new engineering_kejadian(array('db'=>'db'));
			$table_name = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$incidentTable = new building_service_kejadian(array('db'=>'db'));
			$table_name = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$incidentTable = new tenant_relation_kejadian(array('db'=>'db'));
			$table_name = "tenant_relation_kejadian";
		}

		$select = $incidentTable->getAdapter()->select();
		$select->from(array("i"=>"issues"), array("count(*) as total", "dayofweek(i.issue_date) as d"));
		//$select->joinLeft(array("k"=>$table_name), "k.issue_type = i.issue_type_id and k.site_id = i.site_id and i.kejadian_id = k.kejadian_id", array("count(*) as total"));
		//$select->where("k.site_id= ?", $this->site_id);
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("i.category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("i.site_id IN (".$site_ids.")");
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
		$select->group("dayofweek(i.issue_date)");	
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIncidentIdByIncidentNameAndSites($category_id, $incident_name, $site_ids)
	{
		if($category_id == 1) {
			$incidentTable = new security_kejadian(array('db'=>'db'));
			$table_name = "security_kejadian";
		}
		elseif($category_id == 2)
		{
			$incidentTable = new housekeeping_kejadian(array('db'=>'db'));
			$table_name = "housekeeping_kejadian";
		}
		elseif($category_id == 3)
		{
			$incidentTable = new safety_kejadian(array('db'=>'db'));
			$table_name = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$incidentTable = new parking_kejadian(array('db'=>'db'));
			$table_name = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$incidentTable = new engineering_kejadian(array('db'=>'db'));
			$table_name = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$incidentTable = new building_service_kejadian(array('db'=>'db'));
			$table_name = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$incidentTable = new tenant_relation_kejadian(array('db'=>'db'));
			$table_name = "tenant_relation_kejadian";
		}

		$select = $incidentTable->getAdapter()->select();
		$select->from(array("k"=>$table_name), array("k.kejadian_id"));
		$select->where('k.kejadian = ?', $incident_name);
		if(!empty($site_ids)) $select->where('k.site_id IN ('.$site_ids.')');	
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getKejadianByIssueTypeIds($issue_type_ids, $category_id)
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select()->where("issue_type IN (".$issue_type_ids.")")->where("site_id=?", $this->site_id);		
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getIncidentByCategoryIssueTypesSites($category_id, $issue_type_ids, $sites)
	{
		if($category_id == 1) $incidentTable = new security_kejadian(array('db'=>'db'));
		elseif($category_id == 2) $incidentTable = new housekeeping_kejadian(array('db'=>'db'));
		elseif($category_id == 3) $incidentTable = new safety_kejadian(array('db'=>'db'));
		elseif($category_id == 5) $incidentTable = new parking_kejadian(array('db'=>'db'));
		elseif($category_id == 6) $incidentTable = new engineering_kejadian(array('db'=>'db'));
		elseif($category_id == 10) $incidentTable = new building_service_kejadian(array('db'=>'db'));
		elseif($category_id == 11) $incidentTable = new tenant_relation_kejadian(array('db'=>'db'));

		$select = $incidentTable->select()->where("issue_type IN (".$issue_type_ids.")");
		if(!empty($sites)) $select->where("site_id IN (".$sites.")");
		$select->group("kejadian");	
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getIncidentIdByNameTypeSites($category_id, $incident_name, $issue_type, $site_ids)
	{
		if($category_id == 1) {
			$incidentTable = new security_kejadian(array('db'=>'db'));
			$table_name = "security_kejadian";
		}
		elseif($category_id == 2)
		{
			$incidentTable = new housekeeping_kejadian(array('db'=>'db'));
			$table_name = "housekeeping_kejadian";
		}
		elseif($category_id == 3)
		{
			$incidentTable = new safety_kejadian(array('db'=>'db'));
			$table_name = "safety_kejadian";
		}
		elseif($category_id == 5) 
		{
			$incidentTable = new parking_kejadian(array('db'=>'db'));
			$table_name = "parking_kejadian";
		}
		elseif($category_id == 6) 
		{
			$incidentTable = new engineering_kejadian(array('db'=>'db'));
			$table_name = "engineering_kejadian";
		}
		elseif($category_id == 10) 
		{
			$incidentTable = new building_service_kejadian(array('db'=>'db'));
			$table_name = "building_service_kejadian";
		}
		elseif($category_id == 11) 
		{
			$incidentTable = new tenant_relation_kejadian(array('db'=>'db'));
			$table_name = "tenant_relation_kejadian";
		}

		$select = $incidentTable->getAdapter()->select();
		$select->from(array("k"=>$table_name), array("k.kejadian_id"));
		$select->where('k.kejadian = ?', $incident_name);
		$select->where('k.issue_type = ?', $issue_type);
		if(!empty($site_ids)) $select->where('k.site_id IN ('.$site_ids.')');	
		
		$rs = $incidentTable->getAdapter()->fetchAll($select);
		return $rs;
	}
}

?>