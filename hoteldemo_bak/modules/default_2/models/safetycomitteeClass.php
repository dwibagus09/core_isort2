<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class safetycomitteeClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveSafetyComittee($params)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"created_date" => date("Y-m-d H:i:s"),
			"meeting_date" => $params["meeting_date"]." 00:00:00",
			"meeting_time" => $params["meeting_time"],
			"meeting_title" => $params["title"]
		);
		if(empty($params['safety_comittee_id']))
		{
			$safetyComitteeTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $safetyComitteeTable->getAdapter()->quoteInto('safety_comittee_id = ?', $params['safety_comittee_id']);
			$safetyComitteeTable->update($data, $where);
			return $params['safety_comittee_id'];
		}
	}

	function saveAttendance($params)
	{
		$safetyComitteeAttendanceTable = new safety_comittee_attendance(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"safety_comittee_id" => $params["safety_comittee_id"],
			"category_id" => $params["category_id"],
			"attendance_name" => $params["attendance_name"]
		);
		if(empty($params['attendance_id']))
		{
			$safetyComitteeAttendanceTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $safetyComitteeAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $params['attendance_id']);
			$safetyComitteeAttendanceTable->update($data, $where);
			return $params['attendance_id'];
		}
	}

	function getsafetyComitteeById($safety_comittee_id)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		$select = $safetyComitteeTable->select()->where('safety_comittee_id = ?', $safety_comittee_id);
		$safetyComittee = $safetyComitteeTable->getAdapter()->fetchRow($select);
		return $safetyComittee;
	}	

	function saveTopic($params)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"department_id" => $params["department_id"],
			"topic" => $params["topic"],
			"pic_name" => $params["pic"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_id']))
		{
			$safetyComitteeTopicTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $safetyComitteeTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
			$safetyComitteeTopicTable->update($data, $where);
			return $params['topic_id'];
		}
	}

	function getsafetyComitteeTopics($safety_comittee_id, $safety_comittee_date = "", $dept_ids = 0)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));

		$select = $safetyComitteeTopicTable->getAdapter()->select();
		$select->from(array("h"=>"safety_comittee_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		$select->joinLeft(array("t"=>"safety_comittee_topic_target"), "t.topic_id = h.topic_id", array("t.topic_target_id"));
		$select->joinLeft(array("s"=>"safety_comittee_topic_start"), "s.topic_id = h.topic_id", array("s.topic_start_id"));
		$select->joinLeft(array("f"=>"safety_comittee_topic_followup"), "f.topic_id = h.topic_id and f.safety_comittee_id = ".$safety_comittee_id, array("f.followup_id", "f.follow_up"));
		$select->joinLeft(array("hm"=>"safety_comittee"), "hm.safety_comittee_id = h.safety_comittee_id", array("hm.meeting_date"));
		$select->joinLeft(array("hmti"=>"safety_comittee_topic_images"), "hmti.safety_comittee_topic_id = h.topic_id", array("hmti.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		if(!empty($safety_comittee_date)) $select->where('date(hm.meeting_date) <= ?', $safety_comittee_date);
		else $select->where('h.safety_comittee_id = ?', $safety_comittee_id);
		$select->where('h.done is null or h.done = 0 or h.done_by_pic is null or h.done_by_pic = 0 or h.done_safety_comittee_id = '.$safety_comittee_id.' or h.done_safety_comittee_id_pic = '.$safety_comittee_id);
		if(!empty($dept_ids)) $select->where("h.department_id IN (".$dept_ids.")");
		$select->order("c.sort_order");
		$select->order("c.category_name");
		$select->group("h.topic_id");
		$safetyComitteeTopic = $safetyComitteeTopicTable->getAdapter()->fetchAll($select);
		return $safetyComitteeTopic;
	}	

	function getsafetyComitteeTopicById($topic_id)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));

		//$select = $safetyComitteeTopicTable->select()->where('topic_id = ?', $topic_id);
		//$safetyComitteeTopic = $safetyComitteeTopicTable->getAdapter()->fetchRow($select);
		
		$select = $safetyComitteeTopicTable->getAdapter()->select();
		$select->from(array("h"=>"safety_comittee_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		$select->where('topic_id = ?', $topic_id);
		$safetyComitteeTopic = $safetyComitteeTopicTable->getAdapter()->fetchRow($select);
		return $safetyComitteeTopic;
	}	

	function deleteTopic($topic_id)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));
		
		if ( is_numeric($topic_id) && $topic_id > 0 )
		{		
			$where = $safetyComitteeTopicTable->getAdapter()->quoteInto('topic_id = ?', $topic_id);
			$safetyComitteeTopicTable->delete($where);
		}
	}

	function getsafetyComitteeMom($params)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		$select = $safetyComitteeTable->select()->where('site_id = ?', $this->site_id)->order("meeting_date desc");
		if(!empty($params['start'])) $select->limit($params['start'], $params['pagesize']);
		$safetyComittee = $safetyComitteeTable->getAdapter()->fetchAll($select);
		return $safetyComittee;
	}	

	function getTotalSafetyComitteeMom() {
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		$select = "select count(*) as total from safety_comittee where site_id =".$this->site_id;
		$scMom = $safetyComitteeTable->getAdapter()->fetchRow($select);
		return $scMom;
	}

	function getAttendanceBysafetyComitteeId($safety_comittee_id)
	{
		$safetyComitteeAttendanceTable = new safety_comittee_attendance(array('db'=>'db'));
		$select = $safetyComitteeAttendanceTable->select()->where('safety_comittee_id = ?', $safety_comittee_id);
		$attendance = $safetyComitteeAttendanceTable->getAdapter()->fetchAll($select);
		return $attendance;
	}	

	function addTopicTargetDate($params)
	{
		$topicTargetTable = new safety_comittee_topic_target(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"target_date" => $params["target_date"],
			"added_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_target_id']))
		{
			$topicTargetTable->insert($data);
			return $this->db->lastInsertId();
		}
	}

	function addTopicStartDate($params)
	{
		$topicStartTable = new safety_comittee_topic_start(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"start_date" => $params["start_date"],
			"added_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_start_id']))
		{
			$topicStartTable->insert($data);
			return $this->db->lastInsertId();
		}
	}

	function getTopicTargetDate($topic_id)
	{
		$topicTargetTable = new safety_comittee_topic_target(array('db'=>'db'));

		$select = $topicTargetTable->select()->where('topic_id = ?', $topic_id);
		$targetDate = $topicTargetTable->getAdapter()->fetchAll($select);
		return $targetDate;
	}	

	function getTopicStartDate($topic_id)
	{
		$topicStartTable = new safety_comittee_topic_start(array('db'=>'db'));

		$select = $topicStartTable->select()->where('topic_id = ?', $topic_id);
		$startDate = $topicStartTable->getAdapter()->fetchAll($select);
		return $startDate;
	}	

	function updateFinishDate($params)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));
		
		$data = array(
			"finish_date" => $params["finish_date"],
			"done" => $params["done"],
			"done_safety_comittee_id" => $params["done_safety_comittee_id"]
		);
		$where = $safetyComitteeTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$safetyComitteeTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function saveFollowUp($params)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"follow_up" => $params["followup"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"added_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['followup_id']))
		{
			$topicFollowUpTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['added_date']);
			unset($data['user_id']);
			unset($data['site_id']);
			$where = $topicFollowUpTable->getAdapter()->quoteInto('followup_id = ?', $params['followup_id']);
			$topicFollowUpTable->update($data, $where);
			return $params['followup_id'];
		}
	}

	function checkIfFollowUpExist($topic_id, $safety_comittee_id)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('topic_id = ?', $topic_id)->where('safety_comittee_id = ?', $safety_comittee_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $followup['followup_id'];
	}	

	function getPrevsafetyComittee($safety_comittee_date)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		$select = $safetyComitteeTable->select();
		if(!empty($safety_comittee_date)) $select->where('date(meeting_date) < ?', $safety_comittee_date);
		$select->where('site_id = ?', $this->site_id);
		$select->order("meeting_date desc")->limit(1);
		$safetyComittee = $safetyComitteeTable->getAdapter()->fetchRow($select);
		return $safetyComittee;
	}	

	function getsafetyComitteeTopicFollowUp($safety_comittee_id)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('safety_comittee_id = ?', $safety_comittee_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $followup;
	}	

	function updateDoneByPic($params)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));
		
		$data = array(
			"done_by_pic" => $params["done_by_pic"],
			"done_safety_comittee_id_pic" => $params["done_safety_comittee_id_pic"]
		);
		$where = $safetyComitteeTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$safetyComitteeTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function approveMoM($params)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		
		$data = array(
			"approved" => 1,
			"approved_date" => date("Y-m-d H:i:s"),
			"approved_user_id" => $params['user_id']
		);
		$where = $safetyComitteeTable->getAdapter()->quoteInto('safety_comittee_id = ?', $params['safety_comittee_id']);
		$safetyComitteeTable->update($data, $where);
	}

	function getPrevsafetyComitteeFollowUp($safety_comittee_id, $safety_comittee_date, $dept_ids = 0)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"safety_comittee_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $safety_comittee_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_safety_comittee_id = '.$safety_comittee_id.' or t.done_safety_comittee_id_pic = '.$safety_comittee_id);
		if(!empty($dept_ids)) $select->where("t.department_id IN (".$dept_ids.")");
		$select->order("t.department_id");
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpByTopicId($topic_id)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));		
		$select->joinLeft(array("i"=>"safety_comittee_topic_followup_images"), "i.followup_id = f.followup_id", array("i.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function deleteAttendanceById($id)
	{
		$safetyComitteeAttendanceTable = new safety_comittee_attendance(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $safetyComitteeAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $id);
			$safetyComitteeAttendanceTable->delete($where);
		}
	}

	function deleteTopicStartDate($id)
	{
		$topicStartTable = new safety_comittee_topic_start(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicStartTable->getAdapter()->quoteInto('topic_start_id = ?', $id);
			$topicStartTable->delete($where);
		}
	}

	function deleteTopicTargetDate($id)
	{
		$topicTargetTable = new safety_comittee_topic_target(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicTargetTable->getAdapter()->quoteInto('topic_target_id = ?', $id);
			$topicTargetTable->delete($where);
		}
	}

	function getsafetyComitteeTopicsHistory($params)
	{
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));

		$select = $safetyComitteeTopicTable->getAdapter()->select();
		$select->from(array("h"=>"safety_comittee_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		$select->joinLeft(array("hm"=>"safety_comittee"), "hm.safety_comittee_id = h.safety_comittee_id", array("hm.meeting_date"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');
		if(!empty($params['category'])) $select->where('h.department_id = ?', $params['category']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$select->order("h.finish_date desc");
		$select->order("h.topic_id");
		$select->limit($params['pagesize'],$params['start']);
		$safetyComitteeTopic = $safetyComitteeTopicTable->getAdapter()->fetchAll($select);
		return $safetyComitteeTopic;
	}	

	function getTotalsafetyComitteeTopicsHistory($params) {
		$safetyComitteeTopicTable = new safety_comittee_topic(array('db'=>'db'));
		$select = $safetyComitteeTopicTable->getAdapter()->select();
		$select->from(array("h"=>"safety_comittee_topic"), array("count(*) as total"));
		$select->joinLeft(array("hm"=>"safety_comittee"), "hm.safety_comittee_id = h.safety_comittee_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');		
		if(!empty($params['category'])) $select->where('h.department_id = ?', $params['category']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$totalTopic = $safetyComitteeTopicTable->getAdapter()->fetchRow($select);
		return $totalTopic;
	}

	function getUnapprovedsafetyComittee($site_id, $safety_comittee_date)
	{
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));
		$select = $safetyComitteeTable->select();
		if(!empty($safety_comittee_date)) $select->where('date(meeting_date) < ?', $safety_comittee_date);
		$select->where('site_id = ?', $site_id);
		$select->where('approved is NULL or approved = 0');
		$safetyComittee = $safetyComitteeTable->getAdapter()->fetchAll($select);
		return $safetyComittee;
	}	

	function saveTopicImage($params)
	{
		$safetyComitteeTopicImageTable = new safety_comittee_topic_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"safety_comittee_topic_id" => $params["safety_comittee_topic_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"filename" => $params["filename"]
		);

		$safetyComitteeTopicImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getTopicImages($topic_id)
	{
		$safetyComitteeTopicImageTable = new safety_comittee_topic_images(array('db'=>'db'));
		$select = $safetyComitteeTopicImageTable->select()->where('safety_comittee_topic_id = ?', $topic_id);
		$images = $safetyComitteeTopicImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	
	
	function getTopicImageById($image_id)
	{
		$safetyComitteeTopicImageTable = new safety_comittee_topic_images(array('db'=>'db'));
		$select = $safetyComitteeTopicImageTable->select()->where('image_id = ?', $image_id);
		$images = $safetyComitteeTopicImageTable->getAdapter()->fetchRow($select);
		return $images;
	}	

	function deleteTopicImage($id)
	{
		$safetyComitteeTopicImageTable = new safety_comittee_topic_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $safetyComitteeTopicImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$safetyComitteeTopicImageTable->delete($where);
			return $id;
		}
	}

	function saveFollowUpImage($params)
	{
		$topicFollowUpImageTable = new safety_comittee_topic_followup_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"followup_id" => $params["followup_id"],
			"safety_comittee_topic_id" => $params["topic_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"filename" => $params["filename"]
		);

		$topicFollowUpImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getFollowUpImages($followup_id)
	{
		$topicFollowUpImageTable = new safety_comittee_topic_followup_images(array('db'=>'db'));
		$select = $topicFollowUpImageTable->select()->where('followup_id = ?', $followup_id);
		$images = $topicFollowUpImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteFollowUpImage($id)
	{
		$topicFollowUpImageTable = new safety_comittee_topic_followup_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicFollowUpImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$topicFollowUpImageTable->delete($where);
			return $id;
		}
	}
	
	function getFollowUpImageById($id)
	{
		$topicFollowUpImageTable = new safety_comittee_topic_followup_images(array('db'=>'db'));
		$select = $topicFollowUpImageTable->select()->where('image_id = ?', $id);
		$images = $topicFollowUpImageTable->getAdapter()->fetchRow($select);
		return $images;
	}

	function getCurrentFollowUp($topic_id, $safety_comittee_id)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_followup"), array("f.*"));
		$select->joinLeft(array("i"=>"safety_comittee_topic_followup_images"), "i.followup_id = f.followup_id", array("i.filename"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->where('f.safety_comittee_id = ?', $safety_comittee_id);
		$select->group("f.followup_id");
		$select->order("f.added_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpById($followup_id)
	{
		$topicFollowUpTable = new safety_comittee_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('followup_id = ?', $followup_id);
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $topicFollowUp;
	}	

	function getReportIds($params) {
		$safetyComitteeTable = new safety_comittee(array('db'=>'db'));

		$select = $safetyComitteeTable->select();
		$select->order('meeting_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$safetyComittee = $safetyComitteeTable->getAdapter()->fetchAll($select);
		return $safetyComittee;
	}

	function addComment($params)
	{
		$commentTable = new safety_comittee_comments(array('db'=>'db3'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"comment" => addslashes(str_replace("\n","<br>",$params["comment"])),
			"comment_date" => date("Y-m-d H:i:s"),
			"filename" => $params["filename"]
		);
		if(empty($params['comment_id']))
		{
			$commentTable->insert($data);
			return $this->db->lastInsertId();
		}
	}

	function getCommentsBysafetyComitteeId($meeting_id, $qty=0, $sort = "desc") {
		$commentsTable = new safety_comittee_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"safety_comittee_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.safety_comittee_id=?", $meeting_id);
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	
	
	/*** ACCIDENT REVIEW ***/
	
	function saveAccidentReview($params)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"accident_review" => $params["accident_review"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"added_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['accident_review_id']))
		{
			$topicAccidentReviewTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['added_date']);
			unset($data['user_id']);
			unset($data['site_id']);
			$where = $topicAccidentReviewTable->getAdapter()->quoteInto('accident_review_id = ?', $params['accident_review_id']);
			$topicAccidentReviewTable->update($data, $where);
			return $params['accident_review_id'];
		}
	}

	function checkIfAccidentReviewExist($topic_id, $safety_comittee_id)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->select()->where('topic_id = ?', $topic_id)->where('safety_comittee_id = ?', $safety_comittee_id);
		$accidentReview = $topicAccidentReviewTable->getAdapter()->fetchRow($select);
		return $accidentReview['accident_review_id'];
	}	

	function getsafetyComitteeTopicAccidentReview($safety_comittee_id)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->select()->where('safety_comittee_id = ?', $safety_comittee_id);
		$accidentReview = $topicAccidentReviewTable->getAdapter()->fetchAll($select);
		return $accidentReview;
	}	

	function getPrevsafetyComitteeAccidentReview($safety_comittee_id, $safety_comittee_date, $dept_ids = 0)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_accident_review"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"safety_comittee_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $safety_comittee_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_safety_comittee_id = '.$safety_comittee_id.' or t.done_safety_comittee_id_pic = '.$safety_comittee_id);
		if(!empty($dept_ids)) $select->where("t.department_id IN (".$dept_ids.")");
		$select->order("t.department_id");
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicAccidentReview = $topicAccidentReviewTable->getAdapter()->fetchAll($select);
		return $topicAccidentReview;
	}	
	
	function saveAccidentReviewImage($params)
	{
		$topicAccidentReviewImageTable = new safety_comittee_topic_accident_review_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"accident_review_id" => $params["accident_review_id"],
			"safety_comittee_topic_id" => $params["topic_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"filename" => $params["filename"]
		);

		$topicAccidentReviewImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}
	
	function getAccidentReviewImages($accident_review_id)
	{
		$topicAccidentReviewImageTable = new safety_comittee_topic_accident_review_images(array('db'=>'db'));
		$select = $topicAccidentReviewImageTable->select()->where('accident_review_id = ?', $accident_review_id);
		$images = $topicAccidentReviewImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteAccidentReviewImage($id)
	{
		$topicAccidentReviewImageTable = new safety_comittee_topic_accident_review_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicAccidentReviewImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$topicAccidentReviewImageTable->delete($where);
			return $id;
		}
	}
	
	function getAccidentReviewImageById($id)
	{
		$topicAccidentReviewImageTable = new safety_comittee_topic_accident_review_images(array('db'=>'db'));
		$select = $topicAccidentReviewImageTable->select()->where('image_id = ?', $id);
		$image = $topicAccidentReviewImageTable->getAdapter()->fetchRow($select);
		return $image;
	}	

    function getCurrentAccidentReview($topic_id, $safety_comittee_id)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_accident_review"), array("f.*"));
		$select->joinLeft(array("i"=>"safety_comittee_topic_accident_review_images"), "i.accident_review_id = f.accident_review_id", array("i.filename"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->where('f.safety_comittee_id = ?', $safety_comittee_id);
		$select->group("f.accident_review_id");
		$select->order("f.added_date desc");
		$topicAccidentReview = $topicAccidentReviewTable->getAdapter()->fetchAll($select);
		return $topicAccidentReview;
	}

	function getAccidentReviewByTopicId($topic_id)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_accident_review"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));
		$select->joinLeft(array("i"=>"safety_comittee_topic_accident_review_images"), "i.accident_review_id = f.accident_review_id", array("i.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->order("h.meeting_date desc");
		$topicAccidentReview = $topicAccidentReviewTable->getAdapter()->fetchAll($select);
		return $topicAccidentReview;
	}
	
	function getAccidentReviewById($accident_review_id)
	{
		$topicAccidentReviewTable = new safety_comittee_topic_accident_review(array('db'=>'db'));

		$select = $topicAccidentReviewTable->select()->where('accident_review_id = ?', $accident_review_id);
		$topicAccidentReview = $topicAccidentReviewTable->getAdapter()->fetchRow($select);
		return $topicAccidentReview;
	}
	
	/*** RECOMMENDATION ***/
	
	function saveRecommendation($params)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"recommendation" => $params["recommendation"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"added_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['recommendation_id']))
		{
			$topicRecommendationTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['added_date']);
			unset($data['user_id']);
			unset($data['site_id']);
			$where = $topicRecommendationTable->getAdapter()->quoteInto('recommendation_id = ?', $params['recommendation_id']);
			$topicRecommendationTable->update($data, $where);
			return $params['recommendation_id'];
		}
	}

	function checkIfRecommendationExist($topic_id, $safety_comittee_id)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->select()->where('topic_id = ?', $topic_id)->where('safety_comittee_id = ?', $safety_comittee_id);
		$recommendation = $topicRecommendationTable->getAdapter()->fetchRow($select);
		return $recommendation['recommendation_id'];
	}	

	function getsafetyComitteeTopicRecommendation($safety_comittee_id)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->select()->where('safety_comittee_id = ?', $safety_comittee_id);
		$recommendation = $topicRecommendationTable->getAdapter()->fetchAll($select);
		return $recommendation;
	}	

	function getPrevsafetyComitteeRecommendation($safety_comittee_id, $safety_comittee_date, $dept_ids = 0)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_recommendation"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"safety_comittee_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $safety_comittee_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_safety_comittee_id = '.$safety_comittee_id.' or t.done_safety_comittee_id_pic = '.$safety_comittee_id);
		if(!empty($dept_ids)) $select->where("t.department_id IN (".$dept_ids.")");
		$select->order("t.department_id");
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicRecommendation = $topicRecommendationTable->getAdapter()->fetchAll($select);
		return $topicRecommendation;
	}	
	
	function saveRecommendationImage($params)
	{
		$topicRecommendationImageTable = new safety_comittee_topic_recommendation_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"recommendation_id" => $params["recommendation_id"],
			"safety_comittee_topic_id" => $params["topic_id"],
			"safety_comittee_id" => $params["safety_comittee_id"],
			"filename" => $params["filename"]
		);
		$topicRecommendationImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}
	
	function getRecommendationImages($recommendation_id)
	{
		$topicRecommendationImageTable = new safety_comittee_topic_recommendation_images(array('db'=>'db'));
		$select = $topicRecommendationImageTable->select()->where('recommendation_id = ?', $recommendation_id);
		$images = $topicRecommendationImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteRecommendationImage($id)
	{
		$topicRecommendationImageTable = new safety_comittee_topic_recommendation_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicRecommendationImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$topicRecommendationImageTable->delete($where);
			return $id;
		}
	}
	
	function getRecommendationImageById($id)
	{
		$topicRecommendationImageTable = new safety_comittee_topic_recommendation_images(array('db'=>'db'));
		$select = $topicRecommendationImageTable->select()->where('image_id = ?', $id);
		$images = $topicRecommendationImageTable->getAdapter()->fetchRow($select);
		return $images;
	}	

    function getCurrentRecommendation($topic_id, $safety_comittee_id)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_recommendation"), array("f.*"));
		$select->joinLeft(array("i"=>"safety_comittee_topic_recommendation_images"), "i.recommendation_id = f.recommendation_id", array("i.filename"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->where('f.safety_comittee_id = ?', $safety_comittee_id);
		$select->group("f.recommendation_id");
		$select->order("f.added_date desc");
		$topicRecommendation = $topicRecommendationTable->getAdapter()->fetchAll($select);
		return $topicRecommendation;
	}

	function getRecommendationByTopicId($topic_id)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->getAdapter()->select();
		$select->from(array("f"=>"safety_comittee_topic_recommendation"), array("f.*"));
		$select->joinLeft(array("h"=>"safety_comittee"), "h.safety_comittee_id = f.safety_comittee_id", array("h.meeting_date"));
		$select->joinLeft(array("i"=>"safety_comittee_topic_recommendation_images"), "i.recommendation_id = f.recommendation_id", array("i.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->order("h.meeting_date desc");
		$topicRecommendation = $topicRecommendationTable->getAdapter()->fetchAll($select);
		return $topicRecommendation;
	}	
	
	function getRecommendationById($recommendation_id)
	{
		$topicRecommendationTable = new safety_comittee_topic_recommendation(array('db'=>'db'));

		$select = $topicRecommendationTable->select()->where('recommendation_id = ?', $recommendation_id);
		$topicRecommendation = $topicRecommendationTable->getAdapter()->fetchRow($select);
		return $topicRecommendation;
	}
	
}

?>