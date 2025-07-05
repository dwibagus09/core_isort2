<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class modClass extends defaultClass
{	
	function getStaffCondition($site_id, $type, $mod_report_id = 0) {
		$staffConditionTable = new mod_staff_condition(array('db'=>'db'));
		
		$select = $staffConditionTable->getAdapter()->select();
		$select->from(array("msc"=>"mod_staff_condition"), array("msc.*"));
		$select->joinLeft(array("msci"=>"mod_staff_condition_info"), "msci.staff_condition_id=msc.staff_condition_id and msci.mod_report_id = ".$mod_report_id, array("msci.inhouse","msci.outsource"));
		$select->where('msc.site_id = ?', $this->site_id);
		$select->where('msc.type = ?', $type);
		$select->order("msc.department");
		$staffCondition = $staffConditionTable->getAdapter()->fetchAll($select);
		return $staffCondition;
	}
	
	function getReportByDate($date) {
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$select = $modReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$modReport = $modReportTable->getAdapter()->fetchRow($select);
		
		return $modReport;
	}

	function saveReport($params) {
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"created_date" => date("Y-m-d H:i:s"),
			"inhouse_engineering_shift1" => $params["inhouse_engineering_shift1"],
			"inhouse_engineering_middle" => $params["inhouse_engineering_middle"],
			"inhouse_engineering_shift2" => $params["inhouse_engineering_shift2"],
			"inhouse_engineering_shift3" => $params["inhouse_engineering_shift3"],
			"inhouse_engineering_off" => $params["inhouse_engineering_off"],
			"inhouse_engineering_absent" => $params["inhouse_engineering_absent"],
			"inhouse_engineering_keterangan" => $params["inhouse_engineering_keterangan"],
			"inhouse_bs_shift1" => $params["inhouse_bs_shift1"],
			"inhouse_bs_middle" => $params["inhouse_bs_middle"],
			"inhouse_bs_shift2" => $params["inhouse_bs_shift2"],
			"inhouse_bs_shift3" => $params["inhouse_bs_shift3"],
			"inhouse_bs_off" => $params["inhouse_bs_off"],
			"inhouse_bs_absent" => $params["inhouse_bs_absent"],
			"inhouse_bs_keterangan" => $params["inhouse_bs_keterangan"],
			"inhouse_tr_shift1" => $params["inhouse_tr_shift1"],
			"inhouse_tr_middle" => $params["inhouse_tr_middle"],
			"inhouse_tr_shift2" => $params["inhouse_tr_shift2"],
			"inhouse_tr_shift3" => $params["inhouse_tr_shift3"],
			"inhouse_tr_off" => $params["inhouse_tr_off"],
			"inhouse_tr_absent" => $params["inhouse_tr_absent"],
			"inhouse_tr_keterangan" => $params["inhouse_tr_keterangan"],
			"inhouse_security_shift1" => $params["inhouse_security_shift1"],
			"inhouse_security_middle" => $params["inhouse_security_middle"],
			"inhouse_security_shift2" => $params["inhouse_security_shift2"],
			"inhouse_security_shift3" => $params["inhouse_security_shift3"],
			"inhouse_security_off" => $params["inhouse_security_off"],
			"inhouse_security_absent" => $params["inhouse_security_absent"],
			"inhouse_security_keterangan" => $params["inhouse_security_keterangan"],
			"inhouse_safety_shift1" => $params["inhouse_safety_shift1"],
			"inhouse_safety_middle" => $params["inhouse_safety_middle"],
			"inhouse_safety_shift2" => $params["inhouse_safety_shift2"],
			"inhouse_safety_shift3" => $params["inhouse_safety_shift3"],
			"inhouse_safety_off" => $params["inhouse_safety_off"],
			"inhouse_safety_absent" => $params["inhouse_safety_absent"],
			"inhouse_safety_keterangan" => $params["inhouse_safety_keterangan"],
			"inhouse_parking_shift1" => $params["inhouse_parking_shift1"],
			"inhouse_parking_middle" => $params["inhouse_parking_middle"],
			"inhouse_parking_shift2" => $params["inhouse_parking_shift2"],
			"inhouse_parking_shift3" => $params["inhouse_parking_shift3"],
			"inhouse_parking_off" => $params["inhouse_parking_off"],
			"inhouse_parking_absent" => $params["inhouse_parking_absent"],
			"inhouse_parking_keterangan" => $params["inhouse_parking_keterangan"],
			"inhouse_housekeeping_shift1" => $params["inhouse_housekeeping_shift1"],
			"inhouse_housekeeping_middle" => $params["inhouse_housekeeping_middle"],
			"inhouse_housekeeping_shift2" => $params["inhouse_housekeeping_shift2"],
			"inhouse_housekeeping_shift3" => $params["inhouse_housekeeping_shift3"],
			"inhouse_housekeeping_off" => $params["inhouse_housekeeping_off"],
			"inhouse_housekeeping_absent" => $params["inhouse_housekeeping_absent"],
			"inhouse_housekeeping_keterangan" => $params["inhouse_housekeeping_keterangan"],
			"inhouse_reception_shift1" => $params["inhouse_reception_shift1"],
			"inhouse_reception_middle" => $params["inhouse_reception_middle"],
			"inhouse_reception_shift2" => $params["inhouse_reception_shift2"],
			"inhouse_reception_shift3" => $params["inhouse_reception_shift3"],
			"inhouse_reception_off" => $params["inhouse_reception_off"],
			"inhouse_reception_absent" => $params["inhouse_reception_absent"],
			"inhouse_reception_keterangan" => $params["inhouse_reception_keterangan"],
			"outsource_security_safety_shift1" => $params["outsource_security_safety_shift1"],
			"outsource_security_safety_middle" => $params["outsource_security_safety_middle"],
			"outsource_security_safety_shift2" => $params["outsource_security_safety_shift2"],
			"outsource_security_safety_shift3" => $params["outsource_security_safety_shift3"],
			"outsource_security_safety_off" => $params["outsource_security_safety_off"],
			"outsource_security_safety_absent" => $params["outsource_security_safety_absent"],
			"outsource_security_safety_keterangan" => $params["outsource_security_safety_keterangan"],
			"outsource_safety_shift1" => $params["outsource_safety_shift1"],
			"outsource_safety_middle" => $params["outsource_safety_middle"],
			"outsource_safety_shift2" => $params["outsource_safety_shift2"],
			"outsource_safety_shift3" => $params["outsource_safety_shift3"],
			"outsource_safety_off" => $params["outsource_safety_off"],
			"outsource_safety_absent" => $params["outsource_safety_absent"],
			"outsource_safety_keterangan" => $params["outsource_safety_keterangan"],
			"outsource_parking_shift1" => $params["outsource_parking_shift1"],
			"outsource_parking_middle" => $params["outsource_parking_middle"],
			"outsource_parking_shift2" => $params["outsource_parking_shift2"],
			"outsource_parking_shift3" => $params["outsource_parking_shift3"],
			"outsource_parking_off" => $params["outsource_parking_off"],
			"outsource_parking_absent" => $params["outsource_parking_absent"],
			"outsource_parking_keterangan" => $params["outsource_parking_keterangan"],
			"outsource_valet_shift1" => $params["outsource_valet_shift1"],
			"outsource_valet_middle" => $params["outsource_valet_middle"],
			"outsource_valet_shift2" => $params["outsource_valet_shift2"],
			"outsource_valet_shift3" => $params["outsource_valet_shift3"],
			"outsource_valet_off" => $params["outsource_valet_off"],
			"outsource_valet_absent" => $params["outsource_valet_absent"],
			"outsource_valet_keterangan" => $params["outsource_valet_keterangan"],
			"outsource_housekeeping_shift1" => $params["outsource_housekeeping_shift1"],
			"outsource_housekeeping_middle" => $params["outsource_housekeeping_middle"],
			"outsource_housekeeping_shift2" => $params["outsource_housekeeping_shift2"],
			"outsource_housekeeping_shift3" => $params["outsource_housekeeping_shift3"],
			"outsource_housekeeping_off" => $params["outsource_housekeeping_off"],
			"outsource_housekeeping_absent" => $params["outsource_housekeeping_absent"],
			"outsource_housekeeping_keterangan" => $params["outsource_housekeeping_keterangan"],
			"outsource_pest_control_shift1" => $params["outsource_pest_control_shift1"],
			"outsource_pest_control_middle" => $params["outsource_pest_control_middle"],
			"outsource_pest_control_shift2" => $params["outsource_pest_control_shift2"],
			"outsource_pest_control_shift3" => $params["outsource_pest_control_shift3"],
			"outsource_pest_control_off" => $params["outsource_pest_control_off"],
			"outsource_pest_control_absent" => $params["outsource_pest_control_absent"],
			"outsource_pest_control_keterangan" => $params["outsource_pest_control_keterangan"],
			"total_shift1" => $params["total_shift1"],
			"total_middle" => $params["total_middle"],
			"total_shift2" => $params["total_shift2"],
			"total_shift3" => $params["total_shift3"],
			"total_off" => $params["total_off"],
			"total_absent" => $params["total_absent"],
			"total_keterangan" => $params["total_keterangan"]
		);
		if(empty($params['mod_report_id']))
		{
			$modReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $modReportTable->getAdapter()->quoteInto('mod_report_id = ?', $params['mod_report_id']);
			$modReportTable->update($data, $where);
			return $params['mod_report_id'];
		}
	}

	function updateReportPage2($params) {
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$data = array(
			"sg_absent" => $params["sg_absent"],
			"sg_subtitute" => $params["sg_subtitute"],
			"sg_subtitute_no_beacon" => $params["sg_subtitute_no_beacon"],
			"sg_negligence" => $params["sg_negligence"],
			"hk_absent" => $params["hk_absent"],
			"hk_subtitute" => $params["hk_subtitute"],
			"hk_subtitute_no_beacon" => $params["hk_subtitute_no_beacon"],
			"hk_negligence" => $params["hk_negligence"],
			"car_parking" => $params["car_parking"],
			"car_drop_off" => $params["car_drop_off"],
			"box_vehicle" => $params["box_vehicle"],
			"motorbike" => $params["motorbike"],
			"bus" => $params["bus"],
			"valet_parking" => $params["valet_parking"],
			"taxi_bluebird" => $params["taxi_bluebird"],
			"taxi_non_bluebird" => $params["taxi_non_bluebird"]
		);
		$where = $modReportTable->getAdapter()->quoteInto('mod_report_id = ?', $params['mod_report_id']);
		$modReportTable->update($data, $where);
		return $params['mod_report_id'];
	}
	
	function getReports($params) {
		$modReportTable = new mod_report(array('db'=>'db'));
		$select = $modReportTable->getAdapter()->select();
		$select->from(array("m"=>"mod_report"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('m.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$mod = $modReportTable->getAdapter()->fetchAll($select);
		return $mod;
	}
	
	function getTotalReport() {
		$modReportTable = new mod_report(array('db'=>'db'));
		$select = "select count(*) as total from mod_report where site_id =".$this->site_id;
		$modReport = $modReportTable->getAdapter()->fetchRow($select);
		return $modReport;
	}
	
	function getReportById($id) {
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$select = $modReportTable->getAdapter()->select();
		$select->from(array("m"=>"mod_report"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = m.site_id", array("si.site_fullname"));
		$select->where('m.mod_report_id = ?', $id);
		$mod = $modReportTable->getAdapter()->fetchRow($select);

		return $mod;
	}
	
	function deleteStaffConditionByModReportId($mod_report_id)
	{
		$staffConditionTable = new mod_staff_condition_info(array('db'=>'db'));
		
		if ( is_numeric($mod_report_id) && $mod_report_id > 0 )
		{		
			$where = $staffConditionTable->getAdapter()->quoteInto('mod_report_id = ?', $mod_report_id);
			$staffConditionTable->delete($where);
		}
	}
	
	function addStaffCondition($params) {
		$staffConditionTable = new mod_staff_condition_info(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"mod_report_id" => $params["mod_report_id"],
			"staff_condition_id" => $params["staff_condition_id"],
			"inhouse" => $params["inhouse"],
			"outsource" => $params["outsource"]
		);

		$staffConditionTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	/*** EVENT ***/
	
	function getEventById($event_id)
	{
		$eventTable = new mod_events(array('db'=>'db'));
		$select = $eventTable->select()->where('event_id = ?', $event_id);
		$event = $eventTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addEvent($params) {
		$eventTable = new mod_events(array('db'=>'db'));
		$data = array(
			"mod_report_id" => $params["mod_report_id"],
			"event_name" => $params["event_name"],
			"event_location" => $params["event_location"],
			"event_status" => $params["event_status"]
		);
		
		if(empty($params["event_id"])) 
		{
			$eventTable->insert($data);
			return $this->db->lastInsertId();
		}
		else {
			$where = $eventTable->getAdapter()->quoteInto('event_id = ?', $params["event_id"]);
			$eventTable->update($data, $where);
			return $params["event_id"];
		}
	}
	
	function deleteEventsByReportId($mod_report_id)
	{
		$eventTable = new mod_events(array('db'=>'db'));
		
		if ( is_numeric($mod_report_id) && $mod_report_id > 0 )
		{		
			$where = array();
			$where[] = $eventTable->getAdapter()->quoteInto('mod_report_id = ?', $mod_report_id);
			$eventTable->delete($where);
		}
	}
	
	function updateEventFileName($id, $fieldname, $filename)
	{
		$eventTable = new mod_events(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $eventTable->getAdapter()->quoteInto('event_id = ?', $id);
		$eventTable->update($data, $where);
	}
	
	function getEvents($mod_report_id) {
		$eventTable = new mod_events(array('db'=>'db'));
		$select = $eventTable->select()->where('mod_report_id = ?', $mod_report_id);
		$events = $eventTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	function deleteEventById($id)
	{
		$eventTable = new mod_events(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $eventTable->getAdapter()->quoteInto('event_id = ?', $id);
			$eventTable->delete($where);
		}
	}
	
	/*** MALL CONDITION ***/
	
	function getMallConditionById($mall_condition_id)
	{
		$mcTable = new mod_mall_condition(array('db'=>'db'));
		$select = $mcTable->select()->where('mall_condition_id = ?', $mall_condition_id);
		$mc = $mcTable->getAdapter()->fetchRow($select);
		return $mc;
	}	
	
	function addMallCondition($params) {
		$mcTable = new mod_mall_condition(array('db'=>'db'));
		$data = array(
			"mod_report_id" => $params["mod_report_id"],
			"area" => $params["area"],
			"condition_img" => $params["condition_img"],
			"condition_floor" => $params["condition_floor"],
			"status" => $params["status"]
		);
		if(!empty($params['condition_img'])) $data['condition_img'] = $params['condition_img'];
		$mcTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteMallConditionByReportId($mod_report_id)
	{
		$mcTable = new mod_mall_condition(array('db'=>'db'));
		
		if ( is_numeric($mod_report_id) && $mod_report_id > 0 )
		{		
			$where = array();
			$where[] = $mcTable->getAdapter()->quoteInto('mod_report_id = ?', $mod_report_id);
			$mcTable->delete($where);
		}
	}
	
	function updateMallConditionFileName($id, $fieldname, $filename)
	{
		$mcTable = new mod_mall_condition(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $mcTable->getAdapter()->quoteInto('mall_condition_id = ?', $id);
		$mcTable->update($data, $where);
	}
	
	function getMallConditions($mod_report_id) {
		$mcTable = new mod_mall_condition(array('db'=>'db'));
		$select = $mcTable->select()->where('mod_report_id = ?', $mod_report_id);
		$events = $mcTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	function updateWaySystemFileName($id, $fieldname, $filename)
	{
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $modReportTable->getAdapter()->quoteInto('mod_report_id = ?', $id);
		$modReportTable->update($data, $where);
	}
	
	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new mod_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new mod_attachments(array('db'=>'db'));
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
		$attachmentTable = new mod_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new mod_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new mod_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new mod_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function logMODReport($params) {
		$modReportLogTable = new mod_report_log(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"mod_report_id" => $params["mod_report_id"],
			"save_date" => date("Y-m-d H:i:s")
		);
		$modReportLogTable->insert($data);		
	}

	function getUsersByReport($mod_report_id) {
		$modReportLogTable = new mod_report_log(array('db'=>'db'));
		
		$select = $modReportLogTable->getAdapter()->select();
		$select->from(array("l"=>"mod_report_log"), array("l.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = l.user_id", array("u.name"));
		$select->where('l.mod_report_id = ?', $mod_report_id);
		$select->group("l.user_id");
		$users = $modReportLogTable->getAdapter()->fetchAll($select);
		return $users;		
	}

	function getMODAllSitesByDate($date) {
		$modReportTable = new mod_report(array('db'=>'db'));
		
		$select = $modReportTable->getAdapter()->select();
		$select->from(array("mod"=>"mod_report"), array("mod.mod_report_id", "mod.site_id"));
		$select->where('date(mod.created_date)  = ?', $date);
		$mod = $modReportTable->getAdapter()->fetchAll($select);
		return $mod;
	}

	function addReadMODReportLog($params) {
		$modReadReportLogTable = new mod_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$modReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$modReadReportLogTable = new mod_read_report_log(array('db'=>'db'));
		
		$select = $modReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $modReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}
}
?>