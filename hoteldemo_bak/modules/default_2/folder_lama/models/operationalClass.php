<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class operationalClass extends defaultClass
{	
	function saveReport($params) {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"engineering_no_of_req_wo" => $params["engineering_no_of_req_wo"],
			"engineering_completed_wo" => $params["engineering_completed_wo"],
			"engineering_no_of_outstanding_wo" => $params["engineering_no_of_outstanding_wo"],
			"engineering_previous_outstanding" => $params["engineering_previous_outstanding"],
			"engineering_next_outstanding" => $params["engineering_next_outstanding"],
			"bs_no_of_req_wo" => $params["bs_no_of_req_wo"],
			"bs_completed_wo" => $params["bs_completed_wo"],
			"bs_no_of_outstanding_wo" => $params["bs_no_of_outstanding_wo"],
			"bs_previous_outstanding" => $params["bs_previous_outstanding"],
			"bs_next_outstanding" => $params["bs_next_outstanding"],
			"housekeeping_no_of_req_wo" => $params["housekeeping_no_of_req_wo"],
			"housekeeping_completed_wo" => $params["housekeeping_completed_wo"],
			"housekeeping_no_of_outstanding_wo" => $params["housekeeping_no_of_outstanding_wo"],
			"housekeeping_previous_outstanding" => $params["housekeeping_previous_outstanding"],
			"housekeeping_next_outstanding" => $params["housekeeping_next_outstanding"],
			"parking_no_of_req_wo" => $params["parking_no_of_req_wo"],
			"parking_completed_wo" => $params["parking_completed_wo"],
			"parking_no_of_outstanding_wo" => $params["parking_no_of_outstanding_wo"],
			"parking_previous_outstanding" => $params["parking_previous_outstanding"],
			"parking_next_outstanding" => $params["parking_next_outstanding"],
			"other_no_of_req_wo" => $params["other_no_of_req_wo"],
			"other_completed_wo" => $params["other_completed_wo"],
			"other_no_of_outstanding_wo" => $params["other_no_of_outstanding_wo"],
			"other_previous_outstanding" => $params["other_previous_outstanding"],
			"other_next_outstanding" => $params["other_next_outstanding"],
			"head_count" => $params["head_count"],
			"total_car_count" => $params["total_car_count"],
			"car_parking" => $params["car_parking"],
			"car_drop_off" => $params["car_drop_off"],
			"valet_parking" => $params["valet_parking"],
			"box_vehicle" => $params["box_vehicle"],
			"taxi_bluebird" => $params["taxi_bluebird"],
			"motorbike" => $params["motorbike"],
			"bus" => $params["bus"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['operation_mall_report_id']))
		{
			$operationalReportTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $operationalReportTable->getAdapter()->quoteInto('operation_mall_report_id = ?', $params['operation_mall_report_id']);
			$operationalReportTable->update($data, $where);
			return $params['operation_mall_report_id'];
		}
	}
	
	function getReports($params) {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		$select = $operationalReportTable->getAdapter()->select();
		$select->from(array("o"=>"operational_mall_daily_report"), array("o.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = o.user_id", array("u.name"));
		$select->where('o.site_id = ?', $this->site_id);
		$select->order('o.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$operational = $operationalReportTable->getAdapter()->fetchAll($select);
		return $operational;
	}
	
	function getReportById($id) {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		
		$select = $operationalReportTable->getAdapter()->select();
		$select->from(array("s"=>"operational_mall_daily_report"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.site_fullname"));
		$select->where('s.operation_mall_report_id = ?', $id);
		$operational = $operationalReportTable->getAdapter()->fetchRow($select);

		return $operational;
	}
	
	function getEventById($event_id)
	{
		$eventTable = new operational_mall_events(array('db'=>'db'));
		$select = $eventTable->select()->where('event_id = ?', $event_id);
		$event = $eventTable->getAdapter()->fetchRow($select);
		return $event;
	}	
	
	function addEvent($params) {
		$eventTable = new operational_mall_events(array('db'=>'db'));
		$data = array(
			"operation_mall_report_id" => $params["operation_mall_report_id"],
			"event_name" => $params["event_name"],
			"event_location" => $params["event_location"],
			"event_condition" => $params["event_condition"],
			"event_period" => $params["event_period"]
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
	
	function deleteEventsByReportId($operation_mall_report_id)
	{
		$eventTable = new operational_mall_events(array('db'=>'db'));
		
		if ( is_numeric($operation_mall_report_id) && $operation_mall_report_id > 0 )
		{		
			$where = array();
			$where[] = $eventTable->getAdapter()->quoteInto('operation_mall_report_id = ?', $operation_mall_report_id);
			$eventTable->delete($where);
		}
	}
	
	function deleteEventById($event_id)
	{
		$eventTable = new operational_mall_events(array('db'=>'db'));
		
		if ( is_numeric($event_id) && $event_id > 0 )
		{		
			$where = array();
			$where[] = $eventTable->getAdapter()->quoteInto('event_id = ?', $event_id);
			$eventTable->delete($where);
		}
	}
	
	function updateEventFileName($id, $fieldname, $filename)
	{
		$eventTable = new operational_mall_events(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $eventTable->getAdapter()->quoteInto('event_id = ?', $id);
		$eventTable->update($data, $where);
	}
	
	function getEvents($operation_mall_report_id) {
		$eventTable = new operational_mall_events(array('db'=>'db'));
		$select = $eventTable->select()->where('operation_mall_report_id = ?', $operation_mall_report_id);
		$events = $eventTable->getAdapter()->fetchAll($select);
		return $events;
	}
	
	
	
	function getTotalReport() {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		$select = "select count(*) as total from operational_mall_daily_report where site_id =".$this->site_id;
		$omReports = $operationalReportTable->getAdapter()->fetchRow($select);
		return $omReports;
	}
	
	function getReportByDate($date) {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		
		$select = $operationalReportTable->select();
		$select->where('date(created_date) = ?', $date);
		$select->where('site_id = ?', $this->site_id);
		$omReports = $operationalReportTable->getAdapter()->fetchRow($select);
		
		return $omReports;
	}
	
	/*** ATTACHMENT ***/
	
	function getAttachmentById($attachment_id)
	{
		$attachmentTable = new operational_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addAttachment($params) {
		$attachmentTable = new operational_attachments(array('db'=>'db'));
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
		$attachmentTable = new operational_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new operational_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getAttachments($report_id) {
		$attachmentTable = new operational_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteAttachmentById($id)
	{
		$attachmentTable = new operational_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function getOMAllSitesByDate($date) {
		$operationalReportTable = new operational_mall_daily_report(array('db'=>'db'));
		
		$select = $operationalReportTable->getAdapter()->select();
		$select->from(array("o"=>"operational_mall_daily_report"), array("o.operation_mall_report_id", "o.site_id"));
		$select->where('date(o.created_date)  = ?', $date);
		$om = $operationalReportTable->getAdapter()->fetchAll($select);
		return $om;
	}

	function addReadOMReportLog($params) {
		$omReadReportLogTable = new om_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$omReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$omReadReportLogTable = new om_read_report_log(array('db'=>'db'));
		
		$select = $omReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $omReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}
}
?>