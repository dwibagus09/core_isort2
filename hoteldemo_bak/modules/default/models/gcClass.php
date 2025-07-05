<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class gcClass extends defaultClass
{	
	function saveMonthlyAnalysis($params) {
		$monthlyAnalysisTable = new gc_monthly_analysis(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"save_date" => date("Y-m-d H:i:s")
		);

		$monthlyAnalysisTable->insert($data);
		return $this->db->lastInsertId();
	}

	function getMonthlyAnalysis() {
		$monthlyAnalysisTable = new gc_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->getAdapter()->select();
		$select->from(array("m"=>"gc_monthly_analysis"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.save_date desc');
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchAll($select);
		return $monthlyanalysis;
	}

	function getTotalMonthlyAnalysis() {
		$monthlyAnalysisTable = new gc_monthly_analysis(array('db'=>'db'));

		$select = "select count(*) as total from gc_monthly_analysis where site_id =".$this->site_id;
		$monthlyAnalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyAnalysis['total'];
	}

	function getMonthlyAnalysisByMonthYear($m, $y) {
		$monthlyAnalysisTable = new gc_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('month(save_date) = ?', $m)->where('year(save_date) = ?', $y);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}

	function getMonthlyAnalysisById($monthly_analysis_id) {
		$monthlyAnalysisTable = new gc_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('monthly_analysis_id = ?', $monthly_analysis_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}
}
?>