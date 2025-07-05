<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class modscheduleClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addSchedule($params) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$scheduleTable->insert(array(
			"site_id"				=> $this->site_id,
			"schedule_date"			=> $params["schedule_date"]." 0000-00-00",
			"mod_user_id"			=> $params["mod_user_id"],
			"added_by"				=> $params["added_by"],
			"added_date"			=> date("Y-m-d H:i:s")
		));
	}
	
	function getSchedules() {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"mod_schedule"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.mod_user_id", array("u.name"));	
		$select->where('s.site_id = ?', $this->site_id);
		$select->order('s.schedule_date desc' );
		return $scheduleTable->getAdapter()->fetchAll($select);
	}

	function getScheduleById($id) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$select->where('schedule_id = ?', $id);	
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule;
	}

	function updateSchedule($params) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$data = array(
			"schedule_date"			=> $params["schedule_date"]." 0000-00-00",
			"mod_user_id"			=> $params["mod_user_id"]
		);
		$where = array();
		$where[] = $scheduleTable->getAdapter()->quoteInto("schedule_id=?", $params['schedule_id']);
		$scheduleTable->update($data, $where);
	}
	
	function deleteScheduleById($id)
	{
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $id);
			$scheduleTable->delete($where);
		}
	}

	function getScheduleByDate($date, $mod_user_id) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$select->where('schedule_date = ?', $date);	
		$select->where('mod_user_id = ?', $mod_user_id);
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule;
	}

	function getScheduleByMonth($month, $year) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$select->where('schedule_date >= ?', $year."-".$month."-01");
		$select->where('schedule_date <= ?', $year."-".$month."-31");	
		$select->where('site_id = ?', $this->site_id);
		$select->order('schedule_date');
		$select->group('schedule_date');
		$schedules = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedules;
	}

	function getScheduleByUser($month, $year, $user_id) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$select->where('schedule_date >= ?', $year."-".$month."-01");
		$select->where('schedule_date <= ?', $year."-".$month."-31");	
		$select->where('site_id = ?', $this->site_id);
		$select->where('mod_user_id = ?', $user_id);
		$select->order('schedule_date');
		$schedules = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedules;
	}
	
	function getMODScheduleByDate($date) {
		$scheduleTable = new mod_schedule(array('db'=>'db'));
		$select = $scheduleTable->select();
		$select->where('schedule_date = ?', $date);	
		$select->where('site_id = ?', $this->site_id);
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}
}
?>