<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class areaClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getArea()
	{
		$areaTable = new area(array('db'=>'db'));
		
		$select = $areaTable->select()->where("site_id=?", $this->site_id)->order("sort_order");
		$rs = $areaTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getAreaById($id)
	{
		$areaTable = new area(array('db'=>'db'));
		
		$select = $areaTable->select()->where("area_id=?", $id);		
		$rs = $areaTable->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function getAreaBySites($site_ids = "")
	{
		$areaTable = new area(array('db'=>'db'));
		$select = $areaTable->select();
		if(!empty($site_ids)) $select->where('site_id IN ('.$site_ids.')');	
		$select->group('area_name');	
		$select->order('sort_order');	
		$rs = $areaTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getTotalEachModus($category_id, $year, $months, $days, $period, $floors, $tenant_umum, $issuetypes, $incidents, $modus, $site_ids)
	{
		$areaTable = new area(array('db'=>'db'));
		$select = $areaTable->getAdapter()->select();
		$select->from(array("a"=>'area'), array("count(*) as total", "a.area_name"));
		$select->joinLeft(array("i"=>"issues"), "a.site_id = i.site_id and i.area_id = a.area_id", array());
		$select->where("year(i.issue_date)= ?", $year);
		$select->where("category_id= ?", $category_id);
		if(!empty($site_ids)) $select->where("a.site_id IN (".$site_ids.")");
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
		$select->group("a.area_id");	
		$select->order("total desc");	
		$rs = $areaTable->getAdapter()->fetchAll($select);
		return $rs;
	}
}

?>