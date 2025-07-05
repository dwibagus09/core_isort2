<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class actionplanClass extends defaultClass
{		
	/*** Action Plan Schedule ***/
	
	function saveActionPlanSchedule($params)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'schedule_id'				=> $params['schedule_id'],
			'user_id'					=> $params['user_id'],
			'action_plan_module_id'		=> $params['action_plan_module_id'],
			'action_plan_target_id'		=> $params['action_plan_target_id'],
			'action_plan_activity_id'	=> $params['action_plan_activity_id'],
			'schedule_date'				=> $params['schedule'],
			'week'						=> $params['week'],
			'month'						=> $params['month'],
			'created_date'				=> date("Y-m-d H:i:s")
		);
			
		if(empty($params['schedule_id']))
		{
			$data['original_schedule_date'] = $params['schedule'];
			$scheduleTable->insert($data);
		}
		else
		{
			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $params['schedule_id']);
			$scheduleTable->update($data, $where);
		}
	}

	function checkifscheduledateexist($activity_id, $schedule_date, $week, $month, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->select()->where('site_id = ?', $this->site_id)->where('action_plan_activity_id = ?', $activity_id)->where('schedule_date = "'.$schedule_date.'" or (week = "'.$week.'" and month = "'.$month.'" and year(schedule_date) = "'.$year.'")');
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}

	function totalSchedulesByActivity($activity_id)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(*) as total"));
		$select->where("s.action_plan_activity_id= ?", $activity_id);
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule['total'];
	}

	function getActivityTotalSchedule($activity_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.total_schedule"));
		$select->where("a.action_plan_activity_id= ?", $activity_id);
		$activity = $activityTable->getAdapter()->fetchRow($select);
		return $activity['total_schedule'];
	}
	
	function getActionPlanScheduleByActivityId($activity_id)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->select()->where('action_plan_activity_id = ?', $activity_id)->order("schedule_date");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	

	function getScheduleDateByMonthWeek($site_id, $month, $week, $activity_id, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		//$select = $scheduleTable->select()->where('site_id = ?', $site_id)->where('action_plan_activity_id = ?', $activity_id)->where('month = ?', $month)->where('week = ?', $week)->where('year(schedule_date) = ?', $year);
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id", "s.site_id", "s.status", "s.schedule_date"));
		$select->where("s.site_id= ?", $site_id);
		$select->where("s.action_plan_activity_id= ?", $activity_id);
		$select->where("s.month= ?", $month);
		$select->where("s.week= ?", $week);
		$select->where("year(s.schedule_date)= ?", $year);
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule;
	}

	function getScheduleDatesByCategoryId($site_id, $category_id, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		//$select = $scheduleTable->select()->where('site_id = ?', $site_id)->where('action_plan_activity_id = ?', $activity_id)->where('month = ?', $month)->where('week = ?', $week)->where('year(schedule_date) = ?', $year);
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id", "s.action_plan_activity_id", "s.site_id", "s.status", "s.schedule_date", "s.month", "s.week"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = s.action_plan_module_id", array());
		$select->where("s.site_id= ?", $site_id);
		$select->where("m.category_id= ?", $category_id);
		$select->where("year(s.schedule_date)= ?", $year);
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}

	function rangeWeek ($datestr) {
	   date_default_timezone_set (date_default_timezone_get());
	   $dt = strtotime ($datestr);
	   return array (
		 "start" => date ('N', $dt) == 1 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('last monday', $dt)),
		 "end" => date('N', $dt) == 7 ? date ('Y-m-d', $dt) : date ('Y-m-d', strtotime ('next sunday', $dt))
	   );
	}
	
	function getActionPlanScheduleById($schedule_id, $site_id) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.action_plan_activity_id", "a.activity_name", "a.total_schedule", "a.category_id"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.initial"));
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array("r.reschedule_date","r.approved_by_om"));
		$select->where("s.schedule_id= ?", $schedule_id);
		$select->order("r.reschedule_id desc");
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule;
	}
	
	
	function reschedule($params)
	{		
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'user_id'					=> $params['user_id'],
			'action_plan_schedule_id'	=> $params['action_plan_schedule_id'],
			'reschedule_date'			=> $params['schedule_date'],
			'original_date'				=> $params['original_date'],
			'remark'					=> $params['reason'],
			'created_date'				=> date("Y-m-d H:i:s")
		);
			
		if(empty($params['reschedule_id']))
		{
			$rescheduleTable->insert($data);
		}
	}
	
	function getActionPlanRescheduleList($cat_id)
	{
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array("r.*"));
		$select->joinLeft(array("s"=>"action_plan_schedule"), "s.schedule_id = r.action_plan_schedule_id", array());
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = s.action_plan_module_id", array("m.module_name"));
		//$select->joinLeft(array("c"=>"categories"), "c.category_id = a.category_id", array("c.category_name"));
		$select->where("r.approved_by_om is null or r.approved_by_om < 1");
		$select->where("r.site_id= ?", $this->site_id);
		$select->where("a.category_id= ?", $cat_id);
		$select->order('r.reschedule_date asc');
		$list = $rescheduleTable->getAdapter()->fetchAll($select);
		return $list;
	}
	
	function approveReschedule($id, $om_user)
	{		
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		
		$data = array(
			'approved_by_om'			=> '1',
			'approved_by_om_date'		=> date("Y-m-d H:i:s"),
			'om_user'					=> $om_user
		);
			
		$where = $rescheduleTable->getAdapter()->quoteInto('reschedule_id = ?', $id);
		$rescheduleTable->update($data, $where);
	}

	function deleteReschedule($id)
	{		
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $rescheduleTable->getAdapter()->quoteInto('reschedule_id = ?', $id);
			$rescheduleTable->delete($where);
		}
	}
	
	function getRescheduleById($id)
	{
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->select()->where('reschedule_id = ?', $id);
		$reschedule = $rescheduleTable->getAdapter()->fetchRow($select);
		return $reschedule;
	}

	function getRescheduleByScheduleId($id)
	{
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->select()->where('action_plan_schedule_id = ?', $id)->order('reschedule_id desc');
		$reschedule = $rescheduleTable->getAdapter()->fetchRow($select);
		return $reschedule;
	}
	
	function updateschedulestatus($schedule_id, $status)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
			
		if(!empty($schedule_id))
		{
			$data = array(
				'status'					=> $status
			);
			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $schedule_id);
			$scheduleTable->update($data, $where);
		}
	}

	function updateAllowAdditionalUpload($schedule_id, $allow_upload)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
			
		if(!empty($schedule_id))
		{
			$data = array(
				'allow_additional_upload'	=> intval($allow_upload)
			);
			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $schedule_id);
			$scheduleTable->update($data, $where);
		}
	}
	
	function updateDocumentRemark($schedule_id, $document, $remark, $updateStatus = 0, $status = 0)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
			
		if(!empty($schedule_id))
		{
			$data['remark'] = $remark;
			
			if($document != "#*#*#") $data['document_as_approves'] = $document;

			if($updateStatus == 1)
			{
				$data['status'] = $status;
			}

			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $schedule_id);
			$scheduleTable->update($data, $where);
		}
	}
	
	function updateScheduleDate($schedule_id, $date, $week, $month)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
			
		if(!empty($schedule_id))
		{
			$data = array(
				'schedule_date'				=> $date,
				'status'					=> '0',
				'week'						=> $week,
				'month'						=> $month
			);
			$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $schedule_id);
			$scheduleTable->update($data, $where);
		}
	}
	
	function addScheduleAttachment($params)
	{		
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'user_id'					=> $params['user_id'],
			'action_plan_schedule_id'	=> $params['action_plan_schedule_id'],
			'uploaded_date'				=> date("Y-m-d H:i:s"),
			'cqc'						=> intval($params['cqc']),
		);
		$attachmentTable->insert($data);
		return $this->db2->lastInsertId();
	}
	
	function updateScheduleAttachment($attachment_id,$fieldname, $filename)
	{		
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
			
		if(!empty($attachment_id))
		{
			$data = array(
				$fieldname					=> $filename
			);
			$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $attachment_id);
			$attachmentTable->update($data, $where);
		}
	}
	
	function getActionPlanAttachmentByScheduleId($schedule_id)
	{
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		$select = $attachmentTable->select()->where('action_plan_schedule_id = ?', $schedule_id)->where('cqc is null or cqc = 0');
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}	

	function getActionPlanAttachmentListByScheduleId($schedule_id)
	{
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		$select = $attachmentTable->select()->where('action_plan_schedule_id = ?', $schedule_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}	

	function deleteAttachmentById($id)
	{
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
	}

	function getActionPlanAttachmentById($attachment_id)
	{
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	

	
	function getTotalDoneSchedule($activity_id, $site_id, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->select()->where('action_plan_activity_id = ?', $activity_id)->where('site_id = ?', $site_id)->where('status = ?', '1')->where('year(schedule_date) = ?', $year);
		$done = $scheduleTable->getAdapter()->fetchAll($select);
		return count($done);
	}
	
	function getTotalSchedule($activity_id, $site_id, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->select()->where('action_plan_activity_id = ?', $activity_id)->where('site_id = ?', $site_id)->where('year(schedule_date) = ?', $year);
		$total = $scheduleTable->getAdapter()->fetchAll($select);
		return count($total);
	}
	
	function getScheduleByMonthYear($category_id, $month, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id","s.schedule_date", "s.status", "s.action_plan_module_id", "s.action_plan_target_id"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.action_plan_activity_id", "a.activity_name", "a.total_schedule"));
		/*$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));*/
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("month(s.schedule_date) = ?", $month);
		$select->where("year(s.schedule_date) = ?", $year);
		$schedules = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedules;
	}
	
	function getAllSchedule($category_id, $year)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_date"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.action_plan_activity_id", "a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.action_plan_target_id", "t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = s.action_plan_module_id", array("m.action_plan_module_id", "m.module_name"));
		$select->where("m.show_year= ?", $year);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("a.category_id= ?", $category_id);
		$select->order('m.module_name asc');
		$select->order('t.target_name asc');
		$select->order('a.activity_name asc');
		$select->order('s.schedule_date asc');
		$list = $scheduleTable->getAdapter()->fetchAll($select);
		return $list;
	}
	
	/*** Action Plan Module ***/
	
	function getActionPlanModules($category_id, $year = 0) {
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where("category_id= ?", $category_id);
		$select->where('site_id = ?', $this->site_id);
		if($year > 0) $select->where('show_year = ?', $year);
		$select->order('show_year asc');
		$select->order('sort_order asc');
		$modules = $moduleTable->getAdapter()->fetchAll($select);
		return $modules;
	}

	function saveActionPlanModule($params)
	{		
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'module_name'			=> $params['module_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'show_year'				=> $params['show_year']
		);
			
		if(empty($params['action_plan_module_id']))
		{
			$moduleTable->insert($data);
			return $moduleTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $moduleTable->getAdapter()->quoteInto('action_plan_module_id = ?', $params['action_plan_module_id']);
			$moduleTable->update($data, $where);
			return $params['action_plan_module_id'];
		}
	}
	
	function getActionPlanModuleById($module_id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where('action_plan_module_id = ?', $module_id);
		$target = $moduleTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanModuleById($id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $moduleTable->getAdapter()->quoteInto('action_plan_module_id = ?', $id);
			$moduleTable->delete($where);
		}
	}

	function copyActionPlanModuleToOtherSite($params)
	{		
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'module_name'			=> $params['module_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'show_year'				=> $params['show_year']
		);
			
		$moduleTable->insert($data);
	}

	function getActionPlanModuleByModuleName($module_name, $site_id)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where('module_name = ?', $module_name)->where('site_id = ?', $site_id);
		$module = $moduleTable->getAdapter()->fetchRow($select);
		return $module;
	}	

	/*** Action Plan Target ***/

	function getActionPlanTarget($category_id) {
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where("t.category_id= ?", $category_id);
		$select->where("t.site_id= ?", $this->site_id);
		$select->order('t.action_plan_module_id asc');
		$select->order('t.sort_order asc');
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function saveActionPlanTarget($params)
	{		
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'action_plan_module_id'	=> $params['action_plan_module_id'],
			'target_name'			=> $params['target_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id']
		);
			
		if(empty($params['action_plan_target_id']))
		{
			$targetTable->insert($data);
			return $targetTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $targetTable->getAdapter()->quoteInto('action_plan_target_id = ?', $params['action_plan_target_id']);
			$targetTable->update($data, $where);
			return $params['action_plan_target_id'];
		}
	}
	
	function getActionPlanTargetById($target_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->select()->where('action_plan_target_id = ?', $target_id);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanTargetById($id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $targetTable->getAdapter()->quoteInto('action_plan_target_id = ?', $id);
			$targetTable->delete($where);
		}
	}
	
	
	function getActionPlanTargetByModuleId($module_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->select()->where('action_plan_module_id = ?', $module_id)->where('site_id = ?', $this->site_id)->where('kpi_only is null or kpi_only = "0"')->order('sort_order asc');
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}	

	function copyActionPlanTargetToOtherSite($params)
	{		
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'action_plan_module_id'	=> $params['new_action_plan_module_id'],
			'target_name'			=> $params['target_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id']
		);
			
		$targetTable->insert($data);
	}

	function getActionPlanTargetByTargetName($target_name, $site_id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->select()->where('target_name = ?', $target_name)->where('site_id = ?', $site_id);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;
	}
	
	/*** Action Plan Activity ***/
	
	function getActionPlanActivity($category_id) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("a.site_id= ?", $this->site_id);
		$select->order('a.action_plan_target_id asc');
		$select->order('a.sort_order asc');
		$target = $activityTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function saveActionPlanActivity($params)
	{		
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'action_plan_target_id'	=> $params['action_plan_target_id'],
			'activity_name'			=> $params['activity_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'total_schedule'		=> $params['total_schedule'],
			'document_as_approve'	=> $params['document_as_approve'],
			'remarks'				=> $params['remarks']
		);
			
		if(empty($params['action_plan_activity_id']))
		{
			$activityTable->insert($data);
			return $activityTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $activityTable->getAdapter()->quoteInto('action_plan_activity_id = ?', $params['action_plan_activity_id']);
			$activityTable->update($data, $where);
			return $params['action_plan_activity_id'];
		}
	}
	
	function getActionPlanActivityById($activity_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->select()->where('action_plan_activity_id = ?', $activity_id);
		$target = $activityTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanActivityById($id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $activityTable->getAdapter()->quoteInto('action_plan_activity_id = ?', $id);
			$activityTable->delete($where);
		}
	}
	
	function getActionPlanActivityByTargetId($action_plan_target_id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->select()->where('action_plan_target_id = ?', $action_plan_target_id)->where('site_id = ?', $this->site_id)->order('sort_order asc');
		$activity = $activityTable->getAdapter()->fetchAll($select);
		return $activity;
	}	

	function copyActionPlanActivityToOtherSite($params)
	{		
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $params['site_id'],
			'action_plan_target_id'	=> $params['new_action_plan_target_id'],
			'activity_name'			=> $params['activity_name'],
			'sort_order'			=> $params['sort_order'],
			'category_id'			=> $params['category_id'],
			'total_schedule'		=> $params['total_schedule'],
			'document_as_approve'	=> $params['document_as_approve'],
			'remarks'				=> $params['remarks']
		);
			
		$activityTable->insert($data);
	}

	function getActionPlanActivityTargetModule($category_id, $year) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.action_plan_activity_id", "a.activity_name", "a.total_schedule"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.action_plan_target_id", "t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.action_plan_module_id", "m.module_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("a.site_id= ?", $this->site_id);
		$select->where("m.show_year= ?", $year);
		$select->order('m.sort_order asc');		
		$select->order('t.sort_order asc');
		$select->order('a.sort_order asc');
		$activities = $activityTable->getAdapter()->fetchAll($select);
		return $activities;
	}
	
	
	/*** Action Plan Reminder Email ***/
	
	function getActionPlanEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}

	function getActionPlanReminderEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->where("reminder= '1'")->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}

	function getActionPlanReviewEmail($category_id, $site_id) {
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where("category_id= ?", $category_id)->where("site_id= ?", $site_id)->where("review= '1'")->order('email asc');
		$email = $emailTable->getAdapter()->fetchAll($select);
		return $email;
	}
	
	function saveActionPlanEmail($params)
	{		
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'email'					=> $params['email'],
			'category_id'			=> $params['category_id'],
			'cc'					=> $params['cc'],
			'reminder'				=> $params['reminder'],
			'review'				=> $params['review']
		);
			
		if(empty($params['email_id']))
		{
			$emailTable->insert($data);
		}
		else
		{
			$where = $emailTable->getAdapter()->quoteInto('email_id = ?', $params['email_id']);
			$emailTable->update($data, $where);
		}
	}
	
	function getActionPlanEmailById($email_id)
	{
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		$select = $emailTable->select()->where('email_id = ?', $email_id);
		$target = $emailTable->getAdapter()->fetchRow($select);
		return $target;
	}	
	
	function deleteActionPlanEmailById($id)
	{
		$emailTable = new action_plan_reminder_email(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $emailTable->getAdapter()->quoteInto('email_id = ?', $id);
			$emailTable->delete($where);
		}
	}
	
	function getTomorrowSchedule($category_id, $site_id)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = s.action_plan_module_id", array("m.module_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("s.status is NULL or s.status = 0 or s.status = 2");
		$select->where("date(s.schedule_date)= ?", date("Y-m-d", mktime(0, 0, 0, date("m")  , date("d")+1, date("Y"))));
		$select->order("m.module_name asc");
		$select->order("t.target_name asc");
		$select->order("a.activity_name asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	
	
	function getActionPlanUpcomingSchedule($category_id, $startdate, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("date(s.schedule_date)>= ?", $startdate);
		$select->where("date(s.schedule_date)<= ?", $enddate);
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}
	
	function getActionPlanUpcomingSchedule2($site_id, $category_id, $startdate, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name", "a.document_as_approve as doc_act", "a.remarks as remarks_act"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.schedule_date)>= ?", $startdate);
		$select->where("date(s.schedule_date)<= ?", $enddate);
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}
	
	function getActionPlanCurrentYearReviewSchedule($category_id, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name", "a.document_as_approve as doc_act", "a.remarks as remarks_act"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("date(s.schedule_date)>= ?", date("Y-01-01", strtotime($enddate)));
		$select->where("date(s.schedule_date)< ?", $enddate);
		$select->where("s.status <> 1 OR s.status is NULL");
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	

	function getActionPlanCurrentYearReviewSchedule2($site_id, $category_id, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name", "a.document_as_approve as doc_act", "a.remarks as remarks_act"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.schedule_date)>= ?", date("Y-01-01", strtotime($enddate)));
		$select->where("date(s.schedule_date)< ?", $enddate);
		$select->where("s.status <> 1 OR s.status is NULL");
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	
	
	function getActionPlanLastWeekReviewSchedule($category_id, $startdate, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name", "a.document_as_approve as doc_act", "a.remarks as remarks_act"));
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array("r.original_date", "r.reschedule_date"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("((date(s.schedule_date)>= '".$startdate."') AND (date(s.schedule_date)<= '".$enddate."')) OR ((date(r.original_date)>= '".$startdate."') AND (date(r.original_date)<= '".$enddate."'))");
		$select->group("s.action_plan_activity_id");
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	

	function getActionPlanLastWeekReviewSchedule2($site_id, $category_id, $startdate, $enddate)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name", "a.document_as_approve as doc_act", "a.remarks as remarks_act"));
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array("r.original_date", "r.reschedule_date"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("((date(s.schedule_date)>= '".$startdate."') AND (date(s.schedule_date)<= '".$enddate."')) OR ((date(r.original_date)>= '".$startdate."') AND (date(r.original_date)<= '".$enddate."'))");
		$select->order("s.schedule_date asc");
		$schedule = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedule;
	}	

	function getRescheduleList($params) {
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array("r.*"));
		$select->joinLeft(array("s"=>"action_plan_schedule"), "s.schedule_id = r.action_plan_schedule_id", array());
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = s.action_plan_module_id", array("m.module_name"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = r.user_id", array("u.name"));
		$select->where("a.category_id= ?", $params['c']);
		$select->where("r.original_date= ?", $params['y']);
		if($params['s'] == 'activity')	$select->order('a.action_plan_activity_id asc');
		if($params['s'] == 'user')	$select->order('u.user_id asc');
		$rescheduleList = $rescheduleTable->getAdapter()->fetchAll($select);
		return $rescheduleList;
	}

	function getRescheduleStatistic($params) {
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array("r.*", "count(action_plan_schedule_id) as total_reschedule"));
		$select->joinLeft(array("s"=>"action_plan_schedule"), "s.schedule_id = r.action_plan_schedule_id", array());
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		if($params['filter'] == 'u') $select->joinLeft(array("u"=>"users"), "u.user_id = r.user_id", array("u.name"));
		$select->where("a.category_id= ?", $params['c']);
		$select->where("year(r.original_date)= ?", $params['year']);
		$select->where("r.site_id= ?", $this->site_id);
		if($params['filter'] == 'ts')	$select->group('r.action_plan_schedule_id');
		if($params['filter'] == 'u')	$select->group('r.user_id');
		$select->order('total_reschedule');
		//echo $select; exit();
		$rescheduleList = $rescheduleTable->getAdapter()->fetchAll($select);
		return $rescheduleList;
	}

	function getReschedulesByScheduleId($schedule_id)
	{
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->select()->where('action_plan_schedule_id = ?', $schedule_id);
		$rs = $rescheduleTable->getAdapter()->fetchAll($select);
		return $rs;
	}	

	function getReschedulesByUserId($user_id, $cat_id) {
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array("r.*"));
		$select->joinLeft(array("s"=>"action_plan_schedule"), "s.schedule_id = r.action_plan_schedule_id", array());
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = s.action_plan_target_id", array("t.target_name"));
		$select->where("r.user_id= ?", $user_id);
		$select->where("a.category_id= ?", $cat_id);
		$select->where("r.site_id= ?", $this->site_id);
		//echo $select; exit();
		$rescheduleList = $rescheduleTable->getAdapter()->fetchAll($select);
		return $rescheduleList;
	}

	function deleteScheduleById($id)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $id);
			$scheduleTable->delete($where);
		}
	}

	function getTotalDone($site_id, $cat_id) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.schedule_date) <= ?", date("Y")."-12-31");
		$select->where("date(s.schedule_date) >= ?", date("Y")."-01-01");
		$select->where("s.schedule_date = s.original_schedule_date");
		$select->where("s.status= ?", 1);
		$total = $scheduleTable->getAdapter()->fetchRow($select);
		return $total;
	}

	function getTotalOutstanding($site_id, $cat_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.schedule_date) < ?", date("Y-m-d"));
		$select->where("date(s.schedule_date) >= ?", $year."-01-01");
		$select->where("s.status <> 1 or s.status is null");
		$total = $scheduleTable->getAdapter()->fetchRow($select);
		return $total;
	}

	function getTotalReschedule($site_id, $cat_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));

		$select = "SELECT COUNT(*) as total
				FROM
				(
					SELECT DISTINCT schedule_id 
					FROM `action_plan_schedule` AS `s` 
					LEFT JOIN `action_plan_activity` AS `a` ON a.action_plan_activity_id = s.action_plan_activity_id 
					LEFT JOIN `action_plan_reschedule` AS `r` ON r.action_plan_schedule_id = s.schedule_id 
					WHERE (a.category_id= '".$cat_id."') AND (s.site_id= '".$site_id."') 
					AND (date(s.original_schedule_date) >= '".$year."-01-01') 
					AND (date(s.original_schedule_date) <= '".$year."-12-31') 
					AND (r.reschedule_id > 0)
				) as total_reschedule";
		$total = $scheduleTable->getAdapter()->fetchRow($select);
		return $total;
	}

	function getTotalUpcoming($site_id, $cat_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("s.status = '0' OR s.status is null or s.status = ''");
		$select->where("date(s.schedule_date) > ?", date("Y-m-d"));
		$select->where("date(s.schedule_date) <= ?", $year."-12-31");
		$select->where("s.schedule_date = s.original_schedule_date");
		$total = $scheduleTable->getAdapter()->fetchRow($select);
		return $total;
	}

	function getActionPlanForKPI($category_id, $show_year, $chief, $spv, $staff, $admin) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.action_plan_activity_id", "a.activity_name", "a.total_schedule", "a.document_as_approve", "a.chief", "a.spv", "a.staff", "a.admin", "a.chief_bobot as activity_chief_bobot", "a.spv_bobot as activity_spv_bobot", "a.staff_bobot as activity_staff_bobot", "a.admin_bobot as activity_admin_bobot"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.action_plan_target_id", "t.target_name", "t.chief_bobot", "t.spv_bobot", "t.staff_bobot", "t.admin_bobot", "t.use_activity_bobot", "t.sort_order as target_sort_order", "t.kpi_only"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.action_plan_module_id", "m.module_name"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("a.site_id= ?", $this->site_id);
		$select->where("m.show_year= ?", $show_year);
		$select->where("a.action_plan_activity_id > ?", 0);
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$select->order('m.sort_order asc');
		$select->order('t.sort_order asc');
		$select->order('a.sort_order asc');
		$ap = $activityTable->getAdapter()->fetchAll($select);
		return $ap;
	}

	function getTotalActivitiesByModule($module_id, $cat_id, $chief, $spv, $staff, $admin) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("count(a.action_plan_activity_id) as total_activities"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array());
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("a.site_id= ?", $this->site_id);
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$select->where("m.action_plan_module_id= ?", $module_id);
		$total = $activityTable->getAdapter()->fetchRow($select);
		return $total['total_activities'];
	}

	/*** HITUNG TOTAL RATING TIAP BULAN ***/
	function getTotalMonthlySchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("month(s.original_schedule_date) as month","count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.original_schedule_date)= ?", $year);
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$select->group("month(s.original_schedule_date)");
		return $scheduleTable->getAdapter()->fetchAll($select);
	}

	/*** HITUNG TOTAL RATING TIAP BULAN TIDAK TERMASUK YG DI RESCHEDULE DAN CQC YG BELUM DI APPROVE. UTK CQC YG SUDAH DI APPROVE, RATINGNYA TERGANTUNG BERAPA X DI REJECT */
	function getTotalDoneMonthlySchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		/*$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("month(s.original_schedule_date) as month","sum(rating) as total_rating"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->joinLeft(array("c"=>"action_plan_schedule_cqc"), "c.action_plan_schedule_id = s.schedule_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.schedule_date) = ?", $year);
		$select->where("status= ?", 1);
		//$select->where("month(s.schedule_date) = month(s.original_schedule_date)"); // Kalau reschedule di bulan yg sama, diitung achieve
		$select->where("s.schedule_date = s.original_schedule_date");
		$select->where("c.cqc_id is null OR (c.cqc_id is not null and cqc_approved = '1')");
		if(!empty($chief)) $select->where("a.chief = ?", '1');
		if(!empty($spv)) $select->where("a.spv = ?", '1');
		if(!empty($staff)) $select->where("a.staff = ?", '1');
		if(!empty($admin)) $select->where("a.admin = ?", '1');
		$select->group("month(s.original_schedule_date)");
		return $scheduleTable->getAdapter()->fetchAll($select);*/

		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$query = "SELECT month(s.original_schedule_date) AS `month`, sum(rating) as total_rating FROM `action_plan_schedule` AS `s`
				LEFT JOIN `action_plan_activity` AS `a` ON a.action_plan_activity_id = s.action_plan_activity_id 
				left join (select cqc_id, action_plan_schedule_id from `action_plan_schedule_cqc` c group by c.action_plan_schedule_id) c on c.action_plan_schedule_id = s.schedule_id
				WHERE (a.category_id= '".$cat_id."') AND (s.site_id= '".$this->site_id."') AND (year(s.original_schedule_date) = '".$year."') AND (status= 1) AND (s.schedule_date = s.original_schedule_date)";
		if(!empty($chief)) $query = $query . " AND (a.chief = '1')";
		if(!empty($spv)) $query = $query . " AND (a.spv = '1')";
		if(!empty($staff)) $query = $query .  " AND (a.staff = '1')";
		if(!empty($admin)) $query = $query . " AND (a.admin = '1')";
		$query = $query . " GROUP BY month(s.original_schedule_date)";
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	}

	/*** HITUNG TOTAL RATING BULAN INI. YG BELUM LEWAT TANGGAL HARI INI TIDAK DIHITUNG ***/
	function getCurrentMonthTotalSchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.original_schedule_date)= ?", $year);
		$select->where("month(s.original_schedule_date)= ?", date("n"));
		$select->where("date(s.original_schedule_date) <= ?", date("Y-m-d"));
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		return $scheduleTable->getAdapter()->fetchRow($select);
	}

	/*** HITUNG TOTAL RATING BULAN INI TIDAK TERMASUK YG DI RESCHEDULE DAN CQC YG BELUM DI APPROVE. UTK CQC YG SUDAH DI APPROVE, 
	 * RATINGNYA TERGANTUNG BERAPA X DI REJECT. YANG BELUM LEWAT TANGGAL HARI INI TIDAK DIHITUNG */
	function getCurrentMonthTotalDoneSchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$query = "SELECT sum(rating) as total_rating FROM `action_plan_schedule` AS `s`
				LEFT JOIN `action_plan_activity` AS `a` ON a.action_plan_activity_id = s.action_plan_activity_id 
				left join (select cqc_id, action_plan_schedule_id from `action_plan_schedule_cqc` c group by c.action_plan_schedule_id) c on c.action_plan_schedule_id = s.schedule_id
				WHERE (a.category_id= '".$cat_id."') AND (s.site_id= '".$this->site_id."') AND (year(s.original_schedule_date) = '".$year."') AND (status= 1) AND (s.schedule_date = s.original_schedule_date)";
		if(!empty($chief)) $query = $query . " AND (a.chief = '1')";
		if(!empty($spv)) $query = $query . " AND (a.spv = '1')";
		if(!empty($staff)) $query = $query .  " AND (a.staff = '1')";
		if(!empty($admin)) $query = $query . " AND (a.admin = '1')";
		$query = $query . " AND date(s.original_schedule_date) <= '".date("Y-m-d")."' AND (month(s.original_schedule_date) = '".date("n")."')";
		$rs = $scheduleTable->getAdapter()->fetchRow($query);
		return $rs;
	}

	function getMonthlyReschedulesForKPI($year, $month, $cat_id, $chief, $spv, $staff, $admin) {
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array());
		$select->joinLeft(array("s"=>"action_plan_schedule"), "s.schedule_id = r.action_plan_schedule_id", array("s.schedule_id"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("r.site_id= ?", $this->site_id);
		$select->where("month(r.original_date)= ?", $month);
		$select->where("year(r.original_date)= ?", $year);
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$select->order("r.action_plan_schedule_id");
		$select->group("r.action_plan_schedule_id");
		return $rescheduleTable->getAdapter()->fetchAll($select);
	}

	function getFinalRescheduleDate($id)
	{
		$rescheduleTable = new action_plan_reschedule(array('db'=>'db2'));
		$select = $rescheduleTable->getAdapter()->select();
		$select->from(array("r"=>"action_plan_reschedule"), array("count(*) as total_reschedule","max(reschedule_date) as final_date","min(original_date) as ori_date"));
		$select->where("r.action_plan_schedule_id= ?", $id);
		$reschedule = $rescheduleTable->getAdapter()->fetchRow($select);
		return $reschedule;
	}

	function getActionPlanModulesForKPI($category_id, $show_year) {
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->getAdapter()->select();
		$select->from(array("m"=>"action_plan_module"), array("m.module_name"));
		$select->where("m.category_id= ?", $category_id);
		$select->where("m.site_id= ?", $this->site_id);
		$select->where("m.show_year= ?", $show_year);
		$select->order('m.sort_order asc');
		$ap = $moduleTable->getAdapter()->fetchAll($select);
		return $ap;
	}

	function getTotalSchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.schedule_date)= ?", $year);
		if($year == date("Y") && date("n") < 13)
		{
			if(date("j") <= 15) $m = date("n")-1;
			else $m = date("n");
			$select->where("month(original_schedule_date) <= ?", $m);
		} 
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_schedule'];
	}

	function getTotalDoneSchedules($year, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.schedule_date) = ?", $year);
		$select->where("status= ?", 1);
		//$select->where("month(s.schedule_date) = month(s.original_schedule_date)"); // Kalau reschedule di bulan yg sama, diitung achieve
		$select->where("s.schedule_date = s.original_schedule_date");
		if($year == date("Y") && date("n") < 13)
		{
			if(date("j") <= 15) $m = date("n")-1;
			else $m = date("n");
			$select->where("month(original_schedule_date) <= ?", $m);
		} 
		if(!empty($chief)) $select->where("a.chief = ?", '1');
		if(!empty($spv)) $select->where("a.spv = ?", '1');
		if(!empty($staff)) $select->where("a.staff = ?", '1');
		if(!empty($admin)) $select->where("a.admin = ?", '1');
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_schedule'];
	}

	/* Menghitung total rating dari table action_plan_schedule, tidak termasuk yg sudah di reschedule. hasil CQC mengurangi nilai rating */
	function getTotalDoneScheduleForNewKPI($activity_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("sum(rating) as total_rating"));
		//$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->joinLeft(array("c"=>"action_plan_schedule_cqc"), "c.action_plan_schedule_id = s.schedule_id", array());
		$select->where("s.action_plan_activity_id= ?", $activity_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("year(s.schedule_date) = ?", $year);
		$select->where("status= ?", 1);
		$select->where("date(s.schedule_date) = date(s.original_schedule_date)");
		$select->where("c.cqc_id is null OR (c.cqc_id is not null and cqc_approved = '1')");
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_rating'];
	}

	/*** Action Plan CQC ***/
	
	function saveCQC($params)
	{		
		$cqcTable = new action_plan_schedule_cqc(array('db'=>'db2'));
		
		$data = array(
			'site_id'					=> $this->site_id,
			'category_id'				=> $params['category_id'],
			'action_plan_schedule_id'	=> $params['action_plan_schedule_id'],
			'user_id'					=> $params['user_id'],
			'remarks'					=> $params['cqc_remarks'],
			'submit_date'				=> date("Y-m-d H:i:s")
		);
			
		if(empty($params['cqc_id']))
		{
			$cqcTable->insert($data);
			return $cqcTable->getAdapter()->lastInsertId();
		}
	}

	function updateCQC($id, $filename)
	{		
		$cqcTable = new action_plan_schedule_cqc(array('db'=>'db2'));
		
		$data = array(
			'attachment'				=> $filename
		);			

		$where = $cqcTable->getAdapter()->quoteInto('cqc_id = ?', $id);
		$cqcTable->update($data, $where);
	}

	function getTotalCQCByScheduleId($schedule_id)
	{
		$cqcTable = new action_plan_schedule_cqc(array('db'=>'db2'));
		$select = $cqcTable->getAdapter()->select();
		$select->from(array("cqc"=>"action_plan_schedule_cqc"), array("count(*) as total"));
		$select->where("cqc.action_plan_schedule_id= ?", $schedule_id);
		$cqc = $cqcTable->getAdapter()->fetchRow($select);
		return $cqc['total'];
	}	

	function getRatingByScheduleId($schedule_id)
	{
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("cqc"=>"action_plan_schedule"), array("rating"));
		$select->where("cqc.action_plan_schedule_id= ?", $schedule_id);
		$schedule = $scheduleTable->getAdapter()->fetchRow($select);
		return $schedule['rating'];
	}	

	function updateScheduleRating($id, $rating)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		
		$data = array(
			'rating'				=> $rating
		);			

		$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $id);
		$scheduleTable->update($data, $where);
	}

	function getCQCByScheduleId($schedule_id)
	{
		$cqcTable = new action_plan_schedule_cqc(array('db'=>'db2'));
		$select = $cqcTable->select()->where('action_plan_schedule_id = ?', $schedule_id);
		$cqc = $cqcTable->getAdapter()->fetchAll($select);
		return $cqc;
	}	

	function getAttachmentByDateCQC($schedule_id, $startdate, $enddate)
	{
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		$select = $attachmentTable->select()->where('action_plan_schedule_id = ?', $schedule_id);
		if($startdate > 0) $select->where('uploaded_date > ?', $startdate);
		if($enddate > 0)$select->where('uploaded_date < ?', $enddate);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}	

	// Digunakan untuk hitung total schedule selama 6 bulan pertama atau 6 bulan kedua
	function getTotalSchedulesSelectedMonth($startdate, $enddate, $cat_id, $chief, $spv, $staff, $admin) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("date(original_schedule_date) >=  ?", $startdate);
		$select->where("date(original_schedule_date) <= ?", $enddate);
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_schedule'];
	}


	// Digunakan untuk hitung total schedule 6 bulan pertama dan 6 bulan ke 2. 
	// yg di reschedule tetap dpt score 3. Yang tidak disetujui oleh TS mengurangi score
	function getTotalApprovedSchedulesSelectedMonth($startdate, $enddate, $cat_id, $chief, $spv, $staff, $admin) {
		/*$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("count(s.schedule_id) as total_schedule"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->joinLeft(array("c"=>"action_plan_schedule_cqc"), "c.action_plan_schedule_id = s.schedule_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("date(original_schedule_date) >=  ?", $startdate);
		$select->where("date(original_schedule_date) <= ?", $enddate);
		//$select->where("s.schedule_date = s.original_schedule_date");
		$select->where("c.cqc_id is null");
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_schedule'];
		*/

		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("sum(rating) as total_rating"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array());
		$select->joinLeft(array("c"=>"action_plan_schedule_cqc"), "c.action_plan_schedule_id = s.schedule_id", array());
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("status= ?", 1);
		$select->where("date(original_schedule_date) >=  ?", $startdate);
		$select->where("date(original_schedule_date) <= ?", $enddate);
		$select->where("c.cqc_id is null OR (c.cqc_id is not null and cqc_approved = '1')");
		if(!empty($chief)) $select->where("a.chief= ?", '1');
		if(!empty($spv)) $select->where("a.spv= ?", '1');
		if(!empty($staff)) $select->where("a.staff= ?", '1');
		if(!empty($admin)) $select->where("a.admin= ?", '1');
		//echo $select; exit();
		$rs = $scheduleTable->getAdapter()->fetchRow($select);
		return $rs['total_rating'];
	}

	function getActionPlanForCQC($category_id, $year, $showDisapprove, $period = 1) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id","s.schedule_date","s.schedule_date","s.cqc_approved"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		if($showDisapprove == 1)
			$select->joinLeft(array("c"=>"action_plan_schedule_cqc"), "c.action_plan_schedule_id = s.schedule_id", array("cqc_id"));
		$select->where("a.category_id= ?", $category_id);
		$select->where("s.site_id= ?", $this->site_id);
		$select->where("s.status= ?", '1');
		if($showDisapprove == 1)
			$select->where("c.cqc_id > ?", 0);
		if($period == 1)
		{
			$select->where("month(s.schedule_date) < ?", "07");
		}
		else {
			$select->where("month(s.schedule_date) > ?", "06");
			$select->where("month(s.schedule_date) <= ?", "12");
		}
		$select->where("year(s.original_schedule_date) = ?", $year);
		$select->order("s.cqc_approved asc");
		$select->order("s.schedule_date asc");
		if($showDisapprove == 1) $select->group("s.schedule_id");
		$schedules = $scheduleTable->getAdapter()->fetchAll($select);
		return $schedules;
	}

	function approveCQC($params)
	{		
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		
		$data = array(
			'cqc_approved'				=> '1',
			'cqc_approved_by_user'		=> $params['user_id'],
			'cqc_approved_date'			=> date("Y-m-d H:i:s")
		);			

		$where = $scheduleTable->getAdapter()->quoteInto('schedule_id = ?', $params['id']);
		$scheduleTable->update($data, $where);
	}

	function getActionPlanCurrentYearReschedule($site_id, $cat_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id","s.original_schedule_date", "s.schedule_date"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array("r.original_date", "r.reschedule_date"));
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.original_schedule_date) >= ?", $year."-01-01");
		$select->where("date(s.original_schedule_date) <= ?", $year."-12-31");
		$select->where("r.reschedule_id > 0");
		$select->order("s.schedule_id");
		$select->order("r.reschedule_id");
		$rescheduleList = $scheduleTable->getAdapter()->fetchAll($select);
		return $rescheduleList;
	}

	function getActionPlanCurrentYearDone($site_id, $cat_id, $year) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$select = $scheduleTable->getAdapter()->select();
		$select->from(array("s"=>"action_plan_schedule"), array("s.schedule_id","s.original_schedule_date", "s.schedule_date"));
		$select->joinLeft(array("a"=>"action_plan_activity"), "a.action_plan_activity_id = s.action_plan_activity_id", array("a.activity_name"));
		$select->joinLeft(array("r"=>"action_plan_reschedule"), "r.action_plan_schedule_id = s.schedule_id", array("r.original_date", "r.reschedule_date"));
		$select->where("a.category_id= ?", $cat_id);
		$select->where("s.site_id= ?", $site_id);
		$select->where("date(s.original_schedule_date) >= ?", $year."-01-01");
		$select->where("date(s.original_schedule_date) <= ?", $year."-12-31");
		$select->where("s.status= ?", 1);
		$select->order("s.schedule_date");
		$rescheduleList = $scheduleTable->getAdapter()->fetchAll($select);
		return $rescheduleList;
	}
	
	function getschedulelistofemptyactivity() {
	    $scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$query = "select s.schedule_id from action_plan_schedule s left join action_plan_module m on m.action_plan_module_id = s.action_plan_module_id where m.action_plan_module_id is null";
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	}
	
	function gettargetlistofemptymodule() {
	    $targetTable = new action_plan_target(array('db'=>'db2'));
		$query = "SELECT * FROM `action_plan_target` t left join action_plan_module m on m.`action_plan_module_id` = t.`action_plan_module_id` where m.`action_plan_module_id` is null";
		$rs = $targetTable->getAdapter()->fetchAll($query);
		return $rs;
	}
	
	function deleteTargetById($id)
	{
		$targetTable = new action_plan_target(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $targetTable->getAdapter()->quoteInto('action_plan_target_id = ?', $id);
			$targetTable->delete($where);
		}
	}
	
	function getactivitylistofemptytarget() {
	    $activityTable = new action_plan_activity(array('db'=>'db2'));
		$query = "SELECT * FROM `action_plan_activity` a left join action_plan_target t on t.`action_plan_target_id` = a.`action_plan_target_id` where t.`action_plan_target_id` is null";
		$rs = $activityTable->getAdapter()->fetchAll($query);
		return $rs;
	}
	
	function deleteActivityById($id)
	{
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $activityTable->getAdapter()->quoteInto('action_plan_activity_id = ?', $id);
			$activityTable->delete($where);
		}
	}
	
	function getActionPlanModulesByYear($cat_id, $year = 0) {
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select();
		$select->where('category_id = ?', $cat_id);
		$select->where('site_id = ?', $this->site_id);
		if($year > 0) $select->where('show_year = ?', $year);
		$modules = $moduleTable->getAdapter()->fetchAll($select);
		return $modules;
	}
	
	function getActionPlanTargetByCatYear($cat_id, $year) {
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where('t.category_id = ?', $cat_id);
		$select->where("m.show_year= ?", $year);
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function getActionPlanTargetByYear($year = 0) {
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where("m.show_year= ?", $year);
		$target = $targetTable->getAdapter()->fetchAll($select);
		return $target;
	}
	
	function getActionPlanModuleByNameCatSiteYear($module_name, $cat_id, $site_id, $year)
	{
		$moduleTable = new action_plan_module(array('db'=>'db2'));
		$select = $moduleTable->select()->where('module_name = ?', str_replace(2020,2021,$module_name))->where('category_id = ?', $cat_id)->where('site_id = ?', $site_id)->where('show_year = ?', $year);
		$module = $moduleTable->getAdapter()->fetchRow($select);
		return $module;
	}	
	
	function getActionPlanActivityByYear($year) {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$select = $activityTable->getAdapter()->select();
		$select->from(array("a"=>"action_plan_activity"), array("a.*"));
		$select->joinLeft(array("t"=>"action_plan_target"), "t.action_plan_target_id = a.action_plan_target_id", array("t.target_name"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where("m.show_year= ?", $year);
		$target = $activityTable->getAdapter()->fetchAll($select);
		return $target;
	} 
	
	function getActionPlanTargetByNameCatSiteYear($name, $cat_id, $site_id, $year) {
		$targetTable = new action_plan_target(array('db'=>'db2'));
		$select = $targetTable->getAdapter()->select();
		$select->from(array("t"=>"action_plan_target"), array("t.*"));
		$select->joinLeft(array("m"=>"action_plan_module"), "m.action_plan_module_id = t.action_plan_module_id", array("m.module_name"));
		$select->where("t.target_name= ?", str_replace(2020,2021,$name));
		$select->where("t.category_id= ?", $cat_id);
		$select->where("t.site_id= ?", $site_id);
		$select->where("m.show_year= ?", $year);
		$target = $targetTable->getAdapter()->fetchRow($select);
		return $target;		
	}
	
	function getCqcEmail($category_id, $cc = "") {
		$emailTable = new action_plan_cqc_email(array('db'=>'db2'));
		$select = $emailTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('category_id = ?', $category_id);
		if($cc == 1) $select->where('cc = ?', "1");
		else {
			$select->where('cc = "0" or cc is null');
		
		}
		$emails = $emailTable->getAdapter()->fetchAll($select);
		
		return $emails;
	}

	function getActionPlanActivities() {
		$activityTable = new action_plan_activity(array('db'=>'db2'));
		$query = "select * from action_plan_activity where site_id = 2 and action_plan_target_id > 70 and action_plan_target_id < 270;";
		//$query = "select * from action_plan_activity where site_id = 2 and action_plan_target_id > 269;";
		$rs = $activityTable->getAdapter()->fetchAll($query);
		return $rs;
	} 
	
	function getActionPlanSchedule() {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$query = "select s.schedule_id, s.action_plan_module_id, s.action_plan_target_id, t.target_name, s.action_plan_activity_id, a.activity_name, s.user_id, s.schedule_date, s.created_date, s.week, s.month, s.status, s.original_schedule_date, s.rating, s.cqc_approved from action_plan_schedule s 
left join action_plan_target t on t.action_plan_target_id = s.action_plan_target_id
left join action_plan_activity a on a.action_plan_activity_id = s.action_plan_activity_id and a.action_plan_target_id = s.action_plan_target_id
where s.site_id = 2 limit 30000,5000";
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	} 
	
	function getActionPlanTargetLive($target_name, $module_id) {
		$targetTable = new action_plan_target(array('db'=>'db4'));
		$query = "select action_plan_target_id from action_plan_target where action_plan_module_id = ".$module_id." and target_name like '".trim(addslashes($target_name))."%'";
		if(empty($module_id)) { echo $query; exit(); } 
		$rs = $targetTable->getAdapter()->fetchRow($query);
		return $rs;
	} 
	
	function getActionPlanActivityLive($activity_name, $target_id) {
		$activityTable = new action_plan_activity(array('db'=>'db4'));
		$query = "select * from action_plan_activity where action_plan_target_id = ".$target_id." and activity_name like '%".trim(addslashes($activity_name))."%'";
		//if($target_id == 211) echo $query;
		$rs = $activityTable->getAdapter()->fetchRow($query);
		return $rs;
	} 
	
	function getNullActionPlanActivityLive() {
		$scheduleTable = new action_plan_schedule(array('db'=>'db4'));
		$query = "select s.schedule_id, s.action_plan_module_id, t.action_plan_target_id, t.target_name, a.action_plan_activity_id, a.activity_name, s.user_id, s.schedule_date, s.created_date, s.week, s.month, s.status, s.original_schedule_date, s.rating, s.cqc_approved from action_plan_schedule s left join action_plan_target t on t.action_plan_target_id = s.action_plan_target_id and s.site_id = t.site_id
				left join action_plan_activity a on a.action_plan_activity_id = s.action_plan_activity_id and s.site_id = a.site_id
				where t.action_plan_target_id is null or a.action_plan_activity_id is null and s.site_id = 2";
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	}
	
	function getActionPlanScheduleByDate($created_date, $ori_date) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db2'));
		$query = 'select s.schedule_id, s.action_plan_module_id, s.action_plan_activity_id, a.activity_name, s.action_plan_target_id, t.target_name from action_plan_schedule s left join action_plan_activity a on a.action_plan_activity_id = s.action_plan_activity_id left join action_plan_target t on t.action_plan_target_id = s.action_plan_target_id where s.created_date = "'.$created_date.'" and s.original_schedule_date = "'.$ori_date.'" and s.site_id = 2';
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	} 
	
	function getActionPlanScheduleAttachments() {
		$attachmentTable = new action_plan_schedule_attachment(array('db'=>'db2'));
		$query = 'select a.user_id, a.site_id, a.action_plan_schedule_id, a.filename, a.uploaded_date, a.description, a.cqc, s.schedule_date, s.created_date, s.original_schedule_date 
				from action_plan_schedule_attachment a
				left join action_plan_schedule s on s.schedule_id = a.action_plan_schedule_id 
				where s.action_plan_module_id = 14 and year(s.schedule_date) = "2023" and s.status = 1';
		$rs = $attachmentTable->getAdapter()->fetchAll($query);
		return $rs;
	} 
	
	function getActionPlanScheduleLiveByDate($schedule_date, $created_date, $ori_date, $module_id) {
		$scheduleTable = new action_plan_schedule(array('db'=>'db4'));
		$query = 'select schedule_id from action_plan_schedule where schedule_date = "'.$schedule_date.'" and created_date = "'.$created_date.'" and original_schedule_date = "'.$ori_date.'" and site_id = 2 and action_plan_module_id='.$module_id.' and status = 1';
		$rs = $scheduleTable->getAdapter()->fetchAll($query);
		return $rs;
	} 
}
?>