<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class bmClass extends defaultClass
{	
	function saveReport($params) {
		$bmReportTable = new bm_daily_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"internal_engineering_req_wo" => $params["internal_engineering_req_wo"],
			"internal_engineering_completed_wo" => $params["internal_engineering_completed_wo"],
			"internal_engineering_outstanding_wo" => $params["internal_engineering_outstanding_wo"],
			"internal_engineering_prev_outstanding" => $params["internal_engineering_prev_outstanding"],
			"internal_engineering_total_outstanding" => $params["internal_engineering_total_outstanding"],
			"internal_bs_civil_req_wo" => $params["internal_bs_civil_req_wo"],
			"internal_bs_civil_completed_wo" => $params["internal_bs_civil_completed_wo"],
			"internal_bs_civil_outstanding_wo" => $params["internal_bs_civil_outstanding_wo"],
			"internal_bs_civil_prev_outstanding" => $params["internal_bs_civil_prev_outstanding"],
			"internal_bs_civil_total_outstanding" => $params["internal_bs_civil_total_outstanding"],
			"internal_housekeeping_req_wo" => $params["internal_housekeeping_req_wo"],
			"internal_housekeeping_completed_wo" => $params["internal_housekeeping_completed_wo"],
			"internal_housekeeping_outstanding_wo" => $params["internal_housekeeping_outstanding_wo"],
			"internal_housekeeping_prev_outstanding" => $params["internal_housekeeping_prev_outstanding"],
			"internal_housekeeping_total_outstanding" => $params["internal_housekeeping_total_outstanding"],
			"internal_parking_req_wo" => $params["internal_parking_req_wo"],
			"internal_parking_completed_wo" => $params["internal_parking_completed_wo"],
			"internal_parking_outstanding_wo" => $params["internal_parking_outstanding_wo"],
			"internal_parking_prev_outstanding" => $params["internal_parking_prev_outstanding"],
			"internal_parking_total_outstanding" => $params["internal_parking_total_outstanding"],
			"internal_other_req_wo" => $params["internal_other_req_wo"],
			"internal_other_completed_wo" => $params["internal_other_completed_wo"],
			"internal_other_outstanding_wo" => $params["internal_other_outstanding_wo"],
			"internal_other_prev_outstanding" => $params["internal_other_prev_outstanding"],
			"internal_other_total_outstanding" => $params["internal_other_total_outstanding"],
			"tenant_engineering_req_wo" => $params["tenant_engineering_req_wo"],
			"tenant_engineering_completed_wo" => $params["tenant_engineering_completed_wo"],
			"tenant_engineering_outstanding_wo" => $params["tenant_engineering_outstanding_wo"],
			"tenant_engineering_prev_outstanding" => $params["tenant_engineering_prev_outstanding"],
			"tenant_engineering_total_outstanding" => $params["tenant_engineering_total_outstanding"],
			"tenant_bs_civil_req_wo" => $params["tenant_bs_civil_req_wo"],
			"tenant_bs_civil_completed_wo" => $params["tenant_bs_civil_completed_wo"],
			"tenant_bs_civil_outstanding_wo" => $params["tenant_bs_civil_outstanding_wo"],
			"tenant_bs_civil_prev_outstanding" => $params["tenant_bs_civil_prev_outstanding"],
			"tenant_bs_civil_total_outstanding" => $params["tenant_bs_civil_total_outstanding"],
			"tenant_housekeeping_req_wo" => $params["tenant_housekeeping_req_wo"],
			"tenant_housekeeping_completed_wo" => $params["tenant_housekeeping_completed_wo"],
			"tenant_housekeeping_outstanding_wo" => $params["tenant_housekeeping_outstanding_wo"],
			"tenant_housekeeping_prev_outstanding" => $params["tenant_housekeeping_prev_outstanding"],
			"tenant_housekeeping_total_outstanding" => $params["tenant_housekeeping_total_outstanding"],
			"tenant_parking_req_wo" => $params["tenant_parking_req_wo"],
			"tenant_parking_completed_wo" => $params["tenant_parking_completed_wo"],
			"tenant_parking_outstanding_wo" => $params["tenant_parking_outstanding_wo"],
			"tenant_parking_prev_outstanding" => $params["tenant_parking_prev_outstanding"],
			"tenant_parking_total_outstanding" => $params["tenant_parking_total_outstanding"],
			"tenant_other_req_wo" => $params["tenant_other_req_wo"],
			"tenant_other_completed_wo" => $params["tenant_other_completed_wo"],
			"tenant_other_outstanding_wo" => $params["tenant_other_outstanding_wo"],
			"tenant_other_prev_outstanding" => $params["tenant_other_prev_outstanding"],
			"tenant_other_total_outstanding" => $params["tenant_other_total_outstanding"],
			"inhouse_engineering_shift1" => $params["inhouse_engineering_shift1"],
			"inhouse_engineering_middle" => $params["inhouse_engineering_middle"],
			"inhouse_engineering_shift2" => $params["inhouse_engineering_shift2"],
			"inhouse_engineering_shift3" => $params["inhouse_engineering_shift3"],
			"inhouse_engineering_off" => $params["inhouse_engineering_off"],
			"inhouse_engineering_absent" => $params["inhouse_engineering_absent"],
			"inhouse_engineering_keterangan" => $params["inhouse_engineering_keterangan"],
			"inhouse_bs_civil_shift1" => $params["inhouse_bs_civil_shift1"],
			"inhouse_bs_civil_middle" => $params["inhouse_bs_civil_middle"],
			"inhouse_bs_civil_shift2" => $params["inhouse_bs_civil_shift2"],
			"inhouse_bs_civil_shift3" => $params["inhouse_bs_civil_shift3"],
			"inhouse_bs_civil_off" => $params["inhouse_bs_civil_off"],
			"inhouse_bs_civil_absent" => $params["inhouse_bs_civil_absent"],
			"inhouse_bs_civil_keterangan" => $params["inhouse_bs_civil_keterangan"],
			"inhouse_housekeeping_shift1" => $params["inhouse_housekeeping_shift1"],
			"inhouse_housekeeping_middle" => $params["inhouse_housekeeping_middle"],
			"inhouse_housekeeping_shift2" => $params["inhouse_housekeeping_shift2"],
			"inhouse_housekeeping_shift3" => $params["inhouse_housekeeping_shift3"],
			"inhouse_housekeeping_off" => $params["inhouse_housekeeping_off"],
			"inhouse_housekeeping_absent" => $params["inhouse_housekeeping_absent"],
			"inhouse_housekeeping_keterangan" => $params["inhouse_housekeeping_keterangan"],
			"inhouse_parking_shift1" => $params["inhouse_parking_shift1"],
			"inhouse_parking_middle" => $params["inhouse_parking_middle"],
			"inhouse_parking_shift2" => $params["inhouse_parking_shift2"],
			"inhouse_parking_shift3" => $params["inhouse_parking_shift3"],
			"inhouse_parking_off" => $params["inhouse_parking_off"],
			"inhouse_parking_absent" => $params["inhouse_parking_absent"],
			"inhouse_parking_keterangan" => $params["inhouse_parking_keterangan"],
			"inhouse_other_shift1" => $params["inhouse_other_shift1"],
			"inhouse_other_middle" => $params["inhouse_other_middle"],
			"inhouse_other_shift2" => $params["inhouse_other_shift2"],
			"inhouse_other_shift3" => $params["inhouse_other_shift3"],
			"inhouse_other_off" => $params["inhouse_other_off"],
			"inhouse_other_absent" => $params["inhouse_other_absent"],
			"inhouse_other_keterangan" => $params["inhouse_other_keterangan"],
			"outsource_engineering_shift1" => $params["outsource_engineering_shift1"],
			"outsource_engineering_middle" => $params["outsource_engineering_middle"],
			"outsource_engineering_shift2" => $params["outsource_engineering_shift2"],
			"outsource_engineering_shift3" => $params["outsource_engineering_shift3"],
			"outsource_engineering_off" => $params["outsource_engineering_off"],
			"outsource_engineering_absent" => $params["outsource_engineering_absent"],
			"outsource_engineering_keterangan" => $params["outsource_engineering_keterangan"],
			"outsource_bs_civil_shift1" => $params["outsource_bs_civil_shift1"],
			"outsource_bs_civil_middle" => $params["outsource_bs_civil_middle"],
			"outsource_bs_civil_shift2" => $params["outsource_bs_civil_shift2"],
			"outsource_bs_civil_shift3" => $params["outsource_bs_civil_shift3"],
			"outsource_bs_civil_off" => $params["outsource_bs_civil_off"],
			"outsource_bs_civil_absent" => $params["outsource_bs_civil_absent"],
			"outsource_bs_civil_keterangan" => $params["outsource_bs_civil_keterangan"],
			"outsource_housekeeping_shift1" => $params["outsource_housekeeping_shift1"],
			"outsource_housekeeping_middle" => $params["outsource_housekeeping_middle"],
			"outsource_housekeeping_shift2" => $params["outsource_housekeeping_shift2"],
			"outsource_housekeeping_shift3" => $params["outsource_housekeeping_shift3"],
			"outsource_housekeeping_off" => $params["outsource_housekeeping_off"],
			"outsource_housekeeping_absent" => $params["outsource_housekeeping_absent"],
			"outsource_housekeeping_keterangan" => $params["outsource_housekeeping_keterangan"],
			"outsource_parking_shift1" => $params["outsource_parking_shift1"],
			"outsource_parking_middle" => $params["outsource_parking_middle"],
			"outsource_parking_shift2" => $params["outsource_parking_shift2"],
			"outsource_parking_shift3" => $params["outsource_parking_shift3"],
			"outsource_parking_off" => $params["outsource_parking_off"],
			"outsource_parking_absent" => $params["outsource_parking_absent"],
			"outsource_parking_keterangan" => $params["outsource_parking_keterangan"],
			"outsource_other_shift1" => $params["outsource_other_shift1"],
			"outsource_other_middle" => $params["outsource_other_middle"],
			"outsource_other_shift2" => $params["outsource_other_shift2"],
			"outsource_other_shift3" => $params["outsource_other_shift3"],
			"outsource_other_off" => $params["outsource_other_off"],
			"outsource_other_absent" => $params["outsource_other_absent"],
			"outsource_other_keterangan" => $params["outsource_other_keterangan"],
			"total_shift1" => $params["total_shift1"],
			"total_middle" => $params["total_middle"],
			"total_shift2" => $params["total_shift2"],
			"total_shift3" => $params["total_shift3"],
			"total_off" => $params["total_off"],
			"total_absent" => $params["total_absent"],
			"total_keterangan" => $params["total_keterangan"],
			"building" => $params["building"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['report_id']))
		{
			$bmReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $bmReportTable->getAdapter()->quoteInto('report_id = ?', $params['report_id']);
			$bmReportTable->update($data, $where);
			return $params['report_id'];
		}
	}
	
	function getReports($params) {
		$bmReportTable = new bm_daily_report(array('db'=>'db'));
		$select = $bmReportTable->getAdapter()->select();
		$select->from(array("b"=>"bm_daily_report"), array("b.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = b.user_id", array("u.name"));
		$select->where('b.site_id = ?', $this->site_id);
		$select->order('b.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$bm = $bmReportTable->getAdapter()->fetchAll($select);
		return $bm;
	}
	
	function getReportById($id) {
		$bmReportTable = new bm_daily_report(array('db'=>'db'));
		
		$select = $bmReportTable->getAdapter()->select();
		$select->from(array("bm"=>"bm_daily_report"), array("bm.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = bm.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = bm.site_id", array("si.site_fullname"));
		$select->where('bm.report_id = ?', $id);
		$bm = $bmReportTable->getAdapter()->fetchRow($select);

		return $bm;
	}
	
	function getReportByDate($date) {
		$bmReportTable = new bm_daily_report(array('db'=>'db'));
		
		$select = $bmReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$bm = $bmReportTable->getAdapter()->fetchAll($select);
		return $bm;
	}
	
	function getTotalReport() {
		$bmReportTable = new bm_daily_report(array('db'=>'db'));
		$select = "select count(*) as total from bm_daily_report where site_id =".$this->site_id;
		$bmReports = $bmReportTable->getAdapter()->fetchRow($select);
		return $bmReports;
	}
	
	/*** UTILITY ***/
	
	function getUtilityIssueById($issue_id)
	{
		$issueTable = new bm_utility_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addUtilityIssue($params) {
		$issueTable = new bm_utility_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteUtilityByReportId($bm_report_id)
	{
		$issueTable = new bm_utility_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateUtilityFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_utility_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getUtilityIssues($bm_report_id) {
		$issueTable = new bm_utility_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** SAFETY ***/
	
	function getSafetyIssueById($issue_id)
	{
		$issueTable = new bm_safety_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addSafetyIssue($params) {
		$issueTable = new bm_safety_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteSafetyByReportId($bm_report_id)
	{
		$issueTable = new bm_safety_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateSafetyFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_safety_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getSafetyIssues($bm_report_id) {
		$issueTable = new bm_safety_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** SECURITY ***/
	
	function getSecurityIssueById($issue_id)
	{
		$issueTable = new bm_security_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addSecurityIssue($params) {
		$issueTable = new bm_security_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteSecurityByReportId($bm_report_id)
	{
		$issueTable = new bm_security_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateSecurityFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_security_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getSecurityIssues($bm_report_id) {
		$issueTable = new bm_security_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** HOUSEKEEPING ***/
	
	function getHousekeepingIssueById($issue_id)
	{
		$issueTable = new bm_housekeeping_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addHousekeepingIssue($params) {
		$issueTable = new bm_housekeeping_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteHousekeepingByReportId($bm_report_id)
	{
		$issueTable = new bm_housekeeping_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateHousekeepingFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_housekeeping_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getHousekeepingIssues($bm_report_id) {
		$issueTable = new bm_housekeeping_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** PARKING & TRAFFIC ***/
	
	function getParkingIssueById($issue_id)
	{
		$issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addParkingIssue($params) {
		$issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"picture" => $params["picture"],
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteParkingByReportId($bm_report_id)
	{
		$issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateParkingFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getParkingIssues($bm_report_id) {
		$issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** RESIDENT RELATIONS ***/
	
	function getResidentIssueById($issue_id)
	{
		$issueTable = new bm_resident_relations_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addResidentIssue($params) {
		$issueTable = new bm_resident_relations_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteResidentByReportId($bm_report_id)
	{
		$issueTable = new bm_resident_relations_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateResidentFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_resident_relations_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getResidentIssues($bm_report_id) {
		$issueTable = new bm_resident_relations_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	/*** BUILDING SERVICES ***/
	
	function getBuildingServiceIssueById($issue_id)
	{
		$issueTable = new bm_building_service_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('issue_id = ?', $issue_id);
		$event = $issueTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addBuildingServiceIssue($params) {
		$issueTable = new bm_building_service_issue(array('db'=>'db'));
		$data = array(
			"bm_report_id" => $params["report_id"],
			"site_id" => $this->site_id,
			"location" => $params["location"],
			"description" => $params["description"],
			"status" => $params["status"],
			"completion_date" => $params["completion_date"]
		);
		if(empty($params["issue_id"])) 
		{
			$issueTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $params["issue_id"]);
			$issueTable->update($data, $where);
			return $params["issue_id"];
		}
	}
	
	function deleteBuildingServiceByReportId($bm_report_id)
	{
		$issueTable = new bm_building_service_issue(array('db'=>'db'));
		
		if ( is_numeric($bm_report_id) && $bm_report_id > 0 )
		{		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('bm_report_id = ?', $bm_report_id);
			$issueTable->delete($where);
		}
	}
	
	function updateBuildingServiceFileName($id, $fieldname, $filename)
	{
		$issueTable = new bm_building_service_issue(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $issueTable->getAdapter()->quoteInto('issue_id = ?', $id);
		$issueTable->update($data, $where);
	}
	
	function getBuildingServiceIssues($bm_report_id) {
		$issueTable = new bm_building_service_issue(array('db'=>'db'));
		$select = $issueTable->select()->where('bm_report_id = ?', $bm_report_id);
		$events = $issueTable->getAdapter()->fetchAll($select);
		return $events;
	}

	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new bm_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new bm_attachments(array('db'=>'db'));
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
		$attachmentTable = new bm_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new bm_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new bm_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new bm_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}
	
	function deleteIssueById($issue_id, $issue_type)
	{		
		if ( is_numeric($issue_id) && $issue_id > 0 )
		{
			if(strtolower($issue_type) == "utility") $issueTable = new bm_utility_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "safety") $issueTable = new bm_safety_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "security") $issueTable = new bm_security_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "housekeeping") $issueTable = new bm_housekeeping_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "parking_traffic") $issueTable = new bm_parking_traffic_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "resident_relations") $issueTable = new bm_resident_relations_issue(array('db'=>'db'));
			else if(strtolower($issue_type) == "building_service") $issueTable = new bm_building_service_issue(array('db'=>'db'));
		
			$where = array();
			$where[] = $issueTable->getAdapter()->quoteInto('issue_id = ?', $issue_id);
			$issueTable->delete($where);
		}
	}

	function addReadBMReportLog($params) {
		$bmReadReportLogTable = new bm_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$bmReadReportLogTable->insert($data);		
	}
}
?>