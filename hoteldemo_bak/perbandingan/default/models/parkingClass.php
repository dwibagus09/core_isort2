<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class parkingClass extends defaultClass
{	
	function saveReport($params) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"inhouse_spv_malam" => $params["inhouse_spv_malam"],
			"inhouse_spv_pagi" => $params["inhouse_spv_pagi"],
			"inhouse_spv_siang" => $params["inhouse_spv_siang"],
			"inhouse_admin_malam" => $params["inhouse_admin_malam"],
			"inhouse_admin_pagi" => $params["inhouse_admin_pagi"],
			"inhouse_admin_siang" => $params["inhouse_admin_siang"],
			"inhouse_kekuatan_malam" => $params["inhouse_kekuatan_malam"],
			"inhouse_kekuatan_pagi" => $params["inhouse_kekuatan_pagi"],
			"inhouse_kekuatan_siang" => $params["inhouse_kekuatan_siang"],
			"inhouse_carcount_mobil" => intval($params["inhouse_carcount_mobil"]),
			"inhouse_carcount_motor" => intval($params["inhouse_carcount_motor"]),
			"inhouse_carcount_box" => intval($params["inhouse_carcount_box"]),
			"inhouse_carcount_valet_reg" => intval($params["inhouse_carcount_valet_reg"]),
			"inhouse_carcount_self_valet" => intval($params["inhouse_carcount_self_valet"]),
			"inhouse_carcount_drop_off" => intval($params["inhouse_carcount_drop_off"]),
			"inhouse_carcount_taxi" => intval($params["inhouse_carcount_taxi"]),
			"inhouse_carcount_taxionline" => intval($params["inhouse_carcount_taxionline"]),
			"inhouse_carcount_motoronline" => intval($params["inhouse_carcount_motoronline"]),
			"inhouse_carcount_total" => intval($params["inhouse_carcount_total"]),
			"vendor_cpm_acpm_spi" => $params["vendor_cpm_acpm_spi"],
			"vendor_cpm_acpm_valet" => $params["vendor_cpm_acpm_valet"],
			"vendor_pengawas_spi" => $params["vendor_pengawas_spi"],
			"vendor_pengawas_valet" => $params["vendor_pengawas_valet"],
			"vendor_admin_spi" => $params["vendor_admin_spi"],
			"vendor_admin_valet" => $params["vendor_admin_valet"],
			"vendor_kekuatan_spi_pagi" => $params["vendor_kekuatan_spi_pagi"],
			"vendor_kekuatan_spi_siang" => $params["vendor_kekuatan_spi_siang"],
			"vendor_kekuatan_spi_malam" => $params["vendor_kekuatan_spi_malam"],
			"vendor_kekuatan_valet_pagi" => $params["vendor_kekuatan_valet_pagi"],
			"vendor_kekuatan_valet_siang" => $params["vendor_kekuatan_valet_siang"],
			"vendor_kekuatan_valet_malam" => $params["vendor_kekuatan_valet_malam"],
			"vendor_kekuatan_taxi_pagi" => $params["vendor_kekuatan_taxi_pagi"],
			"vendor_kekuatan_taxi_siang" => $params["vendor_kekuatan_taxi_siang"],
			"vendor_kekuatan_taxi_malam" => $params["vendor_kekuatan_taxi_malam"],
			"vendor_kekuatan_taxionline_pagi" => $params["vendor_kekuatan_taxionline_pagi"],
			"vendor_kekuatan_taxionline_siang" => $params["vendor_kekuatan_taxionline_siang"],
			"vendor_kekuatan_taxionline_malam" => $params["vendor_kekuatan_taxionline_malam"],
			"briefing1" => $params["briefing1"],
			"briefing2" => $params["briefing2"],
			"briefing3" => $params["briefing3"],
			"sop1" => $params["sop1"],
			"sop2" => $params["sop2"],
			"sop3" => $params["sop3"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['parking_report_id']))
		{
			$parkingReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $parkingReportTable->getAdapter()->quoteInto('parking_report_id = ?', $params['parking_report_id']);
			$parkingReportTable->update($data, $where);
			return $params['parking_report_id'];
		}
	}
	
	function getReports($params) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		$select = $parkingReportTable->getAdapter()->select();
		$select->from(array("p"=>"parking_daily_report"), array("p.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = p.user_id", array("u.name"));
		$select->where('p.site_id = ?', $this->site_id);
		$select->order('p.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$parking = $parkingReportTable->getAdapter()->fetchAll($select);
		return $parking;
	}

	function getReportIds($params) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));

		$select = $parkingReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->order('created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$parking = $parkingReportTable->getAdapter()->fetchAll($select);
		return $parking;
	}
	
	function getReportById($id) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		
		$select = $parkingReportTable->getAdapter()->select();
		$select->from(array("s"=>"parking_daily_report"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.site_fullname"));
		$select->where('s.parking_report_id = ?', $id);
		$parking = $parkingReportTable->getAdapter()->fetchRow($select);

		return $parking;
	}
	
	function deleteReportById($id)
	{
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $parkingReportTable->getAdapter()->quoteInto('parking_report_id = ?', $id);
			$parkingReportTable->delete($where);
		}
	}
	
	
	function addSpecificReport($params) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$data = array(
			"parking_report_id" => intval($params["parking_report_id"]),
			"issue_type" => intval($params["issue_type"]),
			"time" => $params["time"],
			"detail" => $params["detail"],
			"status" => $params["status"],
			"created_date" => date("Y-m-d H:i:s"),
			"issue_id" => intval($params["issue_id"]),
			"area" => $params["area"],
			"site_id" => $this->site_id
		);
		$specificReportTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteSpecificReportByParkingId($parking_id)
	{
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		if ( is_numeric($parking_id) && $parking_id > 0 )
		{		
			$where = $specificReportTable->getAdapter()->quoteInto('parking_report_id = ?', $parking_id);
			$specificReportTable->delete($where);
		}
	}
	
	function getSpecificReportByParkingReport($parking_report_id) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"parking_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->where('ssr.parking_report_id = ?', $parking_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getSpecificReportById($parking_report_id) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"parking_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description", "i.picture", "i.solved", "i.solved_picture", "i.solved_date"));
		if($parking_report_id > 0) $select->orwhere('ssr.parking_report_id = ?', $parking_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getTotalReport() {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		$select = "select count(*) as total from parking_daily_report where site_id =".$this->site_id;
		$parkingReports = $parkingReportTable->getAdapter()->fetchRow($select);
		return $parkingReports;
	}
	
	function getReportByDate($date) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		
		$select = $parkingReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$parkingReports = $parkingReportTable->getAdapter()->fetchRow($select);
		
		return $parkingReports;
	}
	
	function getSpecificReportByDate($date) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"parking_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description","i.location","i.picture","i.solved_picture","i.solved_date"));
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function updateSpecificReportCompletionDate($id, $completion_date, $report_id, $fieldName) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$data = array(
			"completion_date" => $completion_date." 00:00:00",
			$fieldName => $report_id
		);

		$where = $specificReportTable->getAdapter()->quoteInto('specific_report_id = ?', $id);
		$specificReportTable->update($data, $where);
	}
	
	/* tampilin yg completion date-nya null atau yg completion datenya pas hari itu. 
	completion date field otomatis terisi dari solved date. */
	function getUnsolvedIssues($om_report_id, $report_date = "0000-00-00") {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("psr"=>"parking_specific_report"), array("psr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = psr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = psr.issue_id and i.site_id = psr.site_id", array("i.issue_id", "i.solved_date", "i.picture", "i.solved_picture", "i.solved"));
		$select->where('psr.site_id = ?', $this->site_id);
		$select->where('(date(i.solved_date) = "'.$report_date.'" AND (date(psr.completion_date) = "0000-00-00" or psr.completion_date is NULL or date(psr.completion_date) = "'.$report_date.'")) OR ((i.solved_date is null OR date(i.solved_date) = "0000-00-00")  AND (date(psr.completion_date) = "0000-00-00" or psr.completion_date is NULL or date(psr.completion_date) = "'.$report_date.'"))');
		$select->where('psr.mod_report_id is null or psr.mod_report_id = 0');
		if(!empty($om_report_id)) $select->where('psr.om_report_id = '.$om_report_id.' OR psr.om_report_id = 0 OR psr.om_report_id is null');
		$select->order("psr.completion_date desc");
		$select->order("psr.specific_report_id desc");
		$select->limit("50");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin yg completion date-nya null atau yg completion datenya pas hari itu. 
	completion date field otomatis terisi dari solved date. */
	function getUnsolvedIssues2($parking_report_id, $mod_report_id, $report_date = "0000-00-00") {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($mod_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('parking_report_id = ?', $parking_report_id);
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di parking&traffic specific report table yang completion date-nya kosong atau yg completion-date-nya hari itu */
	function getOMUnsolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new parking_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($om_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('parking_report_id is not null or parking_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di parking&traffic specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getOMSolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new parking_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('parking_report_id is not null or parking_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	/* tampilin semua data yg ada di parking&traffic specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getMODSolvedSpecificReports($mod_report_id, $report_date)
	{
		$specificReportTable = new parking_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('parking_report_id is not null or parking_report_id >0');
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	function getSpecificReportByReport($fieldName, $report_id) {
		$specificReportTable = new parking_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("psr"=>"parking_specific_report"), array("psr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = psr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = psr.issue_id", array("i.solved_date", "i.picture", "i.solved_picture"));
		$select->where('psr.site_id = ?', $this->site_id);
		$select->where("psr.".$fieldName." ='".$report_id."' or date(psr.completion_date) = '".$report_date."' or date(i.solved_date) = '".$report_date."'");
		if($fieldName == "om_report_id") $select->where("psr.mod_report_id is null or psr.mod_report_id = 0");
		else $select->where("psr.om_report_id is null or psr.om_report_id = 0");
		$select->order("psr.specific_report_id desc");

		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		$data = array(
			"report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"description" => $params["attachment_description"]
		);
		
		if(empty($params["attachment_id"])) 
		{
			$attachmentTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $params["attachment_id"]);
			$attachmentTable->update($data, $where);
			return $params["attachment_id"];
		}	
	}
	
	function deleteAttachmentByReportId($report_id)
	{
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new parking_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function getParkingAllSitesByDate($date) {
		$parkingReportTable = new parking_daily_report(array('db'=>'db'));
		
		$select = $parkingReportTable->getAdapter()->select();
		$select->from(array("p"=>"parking_daily_report"), array("p.parking_report_id", "p.site_id"));
		$select->where('date(p.created_date)  = ?', $date);
		$parking = $parkingReportTable->getAdapter()->fetchAll($select);
		return $parking;
	}

	function addReadParkingReportLog($params) {
		$parkingReadReportLogTable = new parking_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$parkingReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$parkingReadReportLogTable = new parking_read_report_log(array('db'=>'db'));
		
		$select = $parkingReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $parkingReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}

	function saveMonthlyAnalysis($params) {
		$monthlyAnalysisTable = new parking_monthly_analysis(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"save_date" => date("Y-m-d H:i:s")
		);

		$monthlyAnalysisTable->insert($data);
		return $this->db->lastInsertId();
	}

	function getMonthlyAnalysis() {
		$monthlyAnalysisTable = new parking_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->getAdapter()->select();
		$select->from(array("m"=>"parking_monthly_analysis"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchAll($select);
		return $monthlyanalysis;
	}

	function getTotalMonthlyAnalysis() {
		$monthlyAnalysisTable = new parking_monthly_analysis(array('db'=>'db'));

		$select = "select count(*) as total from parking_monthly_analysis where site_id =".$this->site_id;
		$monthlyAnalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyAnalysis['total'];
	}

	function getParkingMonthlyAnalysisByMonthYear($m, $y) {
		$monthlyAnalysisTable = new parking_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('month(save_date) = ?', $m)->where('year(save_date) = ?', $y);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}

	function geMonthlyAnalysisById($monthly_analysis_id) {
		$monthlyAnalysisTable = new parking_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('monthly_analysis_id = ?', $monthly_analysis_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}
}
?>