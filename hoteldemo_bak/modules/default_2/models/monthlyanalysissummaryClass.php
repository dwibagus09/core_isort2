<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class monthlyanalysissummaryClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	function saveMonthlyAnalysis($params, $category_id)
	{
		if($category_id == 1) $summaryTable = new security_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 2) $summaryTable = new housekeeping_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 3) $summaryTable = new safety_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 5) $summaryTable = new parking_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 6) $summaryTable = new engineering_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 11) $summaryTable = new tenant_relation_monthly_analysis_summary(array('db'=>'db'));

		if(empty($params['summary_id']))
		{
			$data = array(				
				"site_id" => $this->site_id,				
				"user_id" => $params["user_id"],	
				"kejadian_id" => $params["kejadian_id"],
				"analisa" => $params["analisa"],
				"tindakan" => $params["tindakan"],
				"save_date" => date("Y-m-d H:i:s"),
				"monthly_analysis_id" => $params["monthly_analysis_id"]
			);
			if($category_id == 3) $data['rekomendasi'] = $params["rekomendasi"];
			$summaryTable->insert($data);
		}
		else{
			$data = array(
				"analisa" => $params["analisa"],
				"tindakan" => $params["tindakan"]
			);
			if($category_id == 3) $data['rekomendasi'] = $params["rekomendasi"];
			$where = $summaryTable->getAdapter()->quoteInto('summary_id = ?', $params['summary_id']);
			$summaryTable->update($data, $where);
		}
	}

	function getMonthlyAnalysis($category_id, $params)
	{
		if($category_id == 1)
		{ 
			$summaryTable = new security_monthly_analysis_summary(array('db'=>'db'));
			$tableName = "security_monthly_analysis_summary";
		}
		elseif($category_id == 3) 
		{
			$summaryTable = new safety_monthly_analysis_summary(array('db'=>'db'));
			$tableName = "safety_monthly_analysis_summary";
		}
		elseif($category_id == 5) 
		{
			$summaryTable = new parking_monthly_analysis_summary(array('db'=>'db'));
			$tableName = "parking_monthly_analysis_summary";
		}

		$select = $summaryTable->getAdapter()->select();
		$select->from(array("ma"=>$tableName), array("ma.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = ma.user_id", array("u.name"));
		$select->where("ma.site_id=?", $this->site_id);
		$select->group("month(ma.save_date)");
		$select->limit($params['pagesize'],$params['start']);

		$rs = $summaryTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getTotalMonthlyAnalysis($category_id) {		
		if($category_id == 1) $summaryTable = new security_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 2) $summaryTable = new housekeeping_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 3) $summaryTable = new safety_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 5) $summaryTable = new parking_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 6) $summaryTable = new engineering_monthly_analysis_summary(array('db'=>'db'));
		elseif($category_id == 11) $summaryTable = new tenant_relation_monthly_analysis_summary(array('db'=>'db'));
		
		$select = $summaryTable->select()->where("site_id=?", $this->site_id)->group("month(save_date)");
		$ma = $summaryTable->getAdapter()->fetchAll($select);
		return count($ma);
	}
	
	function saveGcMonthlyAnalysis($params)
	{
		$summaryTable = new gc_monthly_analysis_summary(array('db'=>'db'));
		
		if(empty($params['summary_id']))
		{
			$data = array(				
				"site_id" => $this->site_id,				
				"user_id" => $params["user_id"],
				"category_id" => $params["category_id"],				
				"modus_id" => $params["modus_id"],
				"analisa" => $params["analisa"],
				"tindakan" => $params["tindakan"],
				"save_date" => date("Y-m-d H:i:s"),
				"monthly_analysis_id" => $params["monthly_analysis_id"]
			);
			
			$summaryTable->insert($data);
		}
		else{
			$data = array(
				"analisa" => $params["analisa"],
				"tindakan" => $params["tindakan"]
			);
			$where = $summaryTable->getAdapter()->quoteInto('summary_id = ?', $params['summary_id']);
			$summaryTable->update($data, $where);
		}
	}
}

?>