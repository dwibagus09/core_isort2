<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class progressreportClass extends defaultClass
{		
	function addHousekeepingProgressReport($params) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		$data = array(
			"housekeeping_report_id" => $params["housekeeping_report_id"],
			"area" => $params["area"],
			"shift" => $params["shift"],
			"status" => $params["status"],
			"site_id" => $this->site_id,
			"upload_date" => date("Y-m-d H:i:s")
		);
		/*if(!empty($params['img_before'])) $data['img_before'] = $params['img_before'];
		if(!empty($params['img_progress'])) $data['img_progress'] = $params['img_progress'];
		if(!empty($params['img_after'])) $data['img_after'] = $params['img_after'];*/
		if(empty($params["progress_report_id"])) 
		{
			$progressReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $progressReportTable->getAdapter()->quoteInto('progress_report_id = ?', $params["progress_report_id"]);
			$progressReportTable->update($data, $where);
			return $params["progress_report_id"];
		}
	}
	
	function deleteHousekeepingProgressReportByReportId($housekeeping_report_id, $shift)
	{
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_report_id) && $housekeeping_report_id > 0 )
		{		
			$where = array();
			$where[] = $progressReportTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_report_id);
			$where[] = $progressReportTable->getAdapter()->quoteInto('shift = ?', $shift);
			$progressReportTable->delete($where);
		}
	}
	
	function deleteHousekeepingProgressReportById($progress_report_id)
	{
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		
		if ( is_numeric($progress_report_id) && $progress_report_id > 0 )
		{		
			$where = array();
			$where[] = $progressReportTable->getAdapter()->quoteInto('progress_report_id = ?', $progress_report_id);
			$progressReportTable->delete($where);
		}
	}
	
	function getHousekeepingProgressReport($housekeeping_report_id, $shift) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		$select = $progressReportTable->select()->where('housekeeping_report_id = ?', $housekeeping_report_id)->where('shift = ?', $shift);
		$progressReport = $progressReportTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function updateFileName($id, $fieldname, $filename)
	{
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $progressReportTable->getAdapter()->quoteInto('progress_report_id = ?', $id);
		$progressReportTable->update($data, $where);
	}
	
	function getHousekeepingProgressReportById($progress_report_id)
	{
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));
		$select = $progressReportTable->select()->where('progress_report_id = ?', $progress_report_id);
		$progressReport = $progressReportTable->getAdapter()->fetchRow($select);
		return $progressReport;
	}	
	
	function getHousekeepingOtherInfoById($other_info_id)
	{
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		$select = $otherInfoTable->select()->where('other_info_id = ?', $other_info_id);
		$otherInfo = $otherInfoTable->getAdapter()->fetchRow($select);
		return $otherInfo;
	}	
	
	function addHousekeepingOtherInfo($params) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		$data = array(
			"housekeeping_report_id" => $params["housekeeping_report_id"],
			"area" => $params["area"],
			"status" => $params["status"],
			"site_id" => $this->site_id,
			"upload_date" => date("Y-m-d H:i:s")
		);
		//if(!empty($params['img_progress'])) $data['img_progress'] = $params['img_progress'];
		if(empty($params["other_info_id"])) 
		{
			$otherInfoTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $otherInfoTable->getAdapter()->quoteInto('other_info_id = ?', $params["other_info_id"]);
			$otherInfoTable->update($data, $where);
			return $params["other_info_id"];
		}		
	}
	
	function deleteHousekeepingOtherInfoByReportId($housekeeping_report_id)
	{
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_report_id) && $housekeeping_report_id > 0 )
		{		
			$where = array();
			$where[] = $otherInfoTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_report_id);
			$otherInfoTable->delete($where);
		}
	}
	
	function updateHKOtherInfoFileName($id, $fieldname, $filename)
	{
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $otherInfoTable->getAdapter()->quoteInto('other_info_id = ?', $id);
		$otherInfoTable->update($data, $where);
	}
	
	function getHousekeepingOtherInfo($housekeeping_report_id) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		$select = $otherInfoTable->select()->where('housekeeping_report_id = ?', $housekeeping_report_id);
		$progressReport = $otherInfoTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function getUnsolvedHousekeepingProgressReport($om_report_id) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));

		$select = $progressReportTable->select()->where("site_id = ?", $this->site_id);
		$select->where('completion_date is null or date(completion_date) = "0000-00-00"');
		$select->where('date(upload_date) >= "'.date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")-3, date("Y"))).'"');
		if(!empty($om_report_id) && $om_report_id > 0) $select->orWhere('om_report_id = ?', $om_report_id);
		$progressReport = $progressReportTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function getUnsolvedHousekeepingOtherInfo($om_report_id) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));

		$select = $otherInfoTable->select()->where("site_id = ?", $this->site_id);
		$select->where('completion_date is null or date(completion_date) = "0000-00-00"');
		if(!empty($om_report_id) && $om_report_id > 0) $select->orWhere('om_report_id = ?', $om_report_id);
		$select->limit("50");
		$progressReport = $otherInfoTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}

	function getUnsolvedHousekeepingProgressReport2($mod_report_id) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));

		$select = $progressReportTable->select()->where("site_id = ?", $this->site_id);
		$select->where('completion_date is null or date(completion_date) = "0000-00-00"');
		if(!empty($mod_report_id) && $mod_report_id > 0) $select->orWhere('mod_report_id = ?', $mod_report_id);
		$select->limit("50");
		$progressReport = $progressReportTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function getUnsolvedHousekeepingOtherInfo2($mod_report_id) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));

		$select = $otherInfoTable->select()->where("site_id = ?", $this->site_id);
		$select->where('completion_date is null or date(completion_date) = "0000-00-00"');
		if(!empty($mod_report_id) && $mod_report_id > 0) $select->orWhere('mod_report_id = ?', $mod_report_id);
		$select->limit("50");
		$progressReport = $otherInfoTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function getHousekeepingProgressReportByReport($fieldName, $report_id) {
		$progressReportTable = new housekeeping_progress_report(array('db'=>'db'));

		$select = $progressReportTable->select()->where($fieldName." ='".$report_id."'");
		$progressReport = $progressReportTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function getHousekeepingOtherInfoByReport($fieldName, $report_id) {
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));

		$select = $otherInfoTable->select()->where($fieldName." ='".$report_id."'");
		$progressReport = $otherInfoTable->getAdapter()->fetchAll($select);
		return $progressReport;
	}
	
	function deleteHousekeepingOtherInfoById($other_info_id)
	{
		$otherInfoTable = new housekeeping_other_info(array('db'=>'db'));
		
		if ( is_numeric($other_info_id) && $other_info_id > 0 )
		{		
			$where = array();
			$where[] = $otherInfoTable->getAdapter()->quoteInto('other_info_id = ?', $other_info_id);
			$otherInfoTable->delete($where);
		}
	}
}
?>