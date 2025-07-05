<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class hodClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveHod($params)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"created_date" => date("Y-m-d H:i:s"),
			"meeting_date" => $params["meeting_date"]." 00:00:00",
			"meeting_time" => $params["meeting_time"],
			"meeting_title" => $params["title"]
		);
		if(empty($params['hod_meeting_id']))
		{
			$hodMeetingTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $hodMeetingTable->getAdapter()->quoteInto('hod_meeting_id = ?', $params['hod_meeting_id']);
			$hodMeetingTable->update($data, $where);
			return $params['hod_meeting_id'];
		}
	}

	function saveAttendance($params)
	{
		$hodMeetingAttendanceTable = new hod_meeting_attendance(array('db'=>'db'));
		
		$data = array(
			"site_id" => $params['site_id'],
			"hod_meeting_id" => $params["hod_meeting_id"],
			"category_id" => $params["category_id"],
			"attendance_name" => $params["attendance_name"]
		);
		if(empty($params['attendance_id']))
		{
			$hodMeetingAttendanceTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $hodMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $params['attendance_id']);
			$hodMeetingAttendanceTable->update($data, $where);
			return $params['attendance_id'];
		}
	}

	function getHodMeetingById($hod_meeting_id)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		$select = $hodMeetingTable->select()->where('hod_meeting_id = ?', $hod_meeting_id);
		$hodMeeting = $hodMeetingTable->getAdapter()->fetchRow($select);
		return $hodMeeting;
	}	

	function saveTopic($params)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"site_id" => $params['site_id'],
			"user_id" => $params["user_id"],
			"hod_meeting_id" => $params["hod_meeting_id"],
			"department_id" => $params["department_id"],
			"topic" => $params["topic"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_id']))
		{
			$hodMeetingTopicTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $hodMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
			$hodMeetingTopicTable->update($data, $where);
			return $params['topic_id'];
		}
	}

	function getHodMeetingTopics($hod_meeting_id, $hod_meeting_date = "", $cat_ids = 0)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));

		$select = $hodMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"hod_meeting_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		//$select->joinLeft(array("si"=>"sites"), "si.site_id = h.site_id", array("si.site_name"));
		$select->joinLeft(array("t"=>"hod_meeting_topic_target"), "t.topic_id = h.topic_id", array("t.topic_target_id"));
		$select->joinLeft(array("s"=>"hod_meeting_topic_start"), "s.topic_id = h.topic_id", array("s.topic_start_id"));
		$select->joinLeft(array("f"=>"hod_meeting_topic_followup"), "f.topic_id = h.topic_id and f.hod_meeting_id = ".$hod_meeting_id, array("f.followup_id", "f.follow_up"));
		$select->joinLeft(array("hm"=>"hod_meeting"), "hm.hod_meeting_id = h.hod_meeting_id", array("hm.meeting_date"));
		$select->joinLeft(array("hmti"=>"hod_meeting_topic_images"), "hmti.hod_meeting_topic_id = h.topic_id", array("hmti.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		if(!empty($hod_meeting_date)) $select->where('date(hm.meeting_date) <= ?', $hod_meeting_date);
		else $select->where('h.hod_meeting_id = ?', $hod_meeting_id);
		$select->where('h.done is null or h.done = 0 or h.done_by_pic is null or h.done_by_pic = 0 or h.done_hod_meeting_id = '.$hod_meeting_id.' or h.done_hod_meeting_id_pic = '.$hod_meeting_id);
		if(!empty($cat_ids)) $select->where("h.department_id IN (".$cat_ids.")");
		$select->order("c.category_name");
		//$select->order("si.site_name");
		$select->group("h.topic_id");
		$hodMeetingTopic = $hodMeetingTopicTable->getAdapter()->fetchAll($select);
		return $hodMeetingTopic;
	}	

	function getHodMeetingTopicById($topic_id)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));

		//$select = $hodMeetingTopicTable->select()->where('topic_id = ?', $topic_id);
		$select = $hodMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"hod_meeting_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		$select->where('topic_id = ?', $topic_id);
		$hodMeetingTopic = $hodMeetingTopicTable->getAdapter()->fetchRow($select);
		return $hodMeetingTopic;
	}	

	function deleteTopic($topic_id)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));
		
		if ( is_numeric($topic_id) && $topic_id > 0 )
		{		
			$where = $hodMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $topic_id);
			$hodMeetingTopicTable->delete($where);
		}
	}

	function getHodMeetingMom($params)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		$select = $hodMeetingTable->select()->where('site_id = ?', $this->site_id)->order("meeting_date desc");
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		$hodMeeting = $hodMeetingTable->getAdapter()->fetchAll($select);
		return $hodMeeting;
	}	

	function getTotalHodMom() {
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		$select = "select count(*) as total from hod_meeting where site_id =".$this->site_id;
		$hodMom = $hodMeetingTable->getAdapter()->fetchRow($select);
		return $hodMom;
	}

	function getAttendanceByHodMeetingId($hod_meeting_id)
	{
		$hodMeetingAttendanceTable = new hod_meeting_attendance(array('db'=>'db'));
		$select = $hodMeetingAttendanceTable->select()->where('hod_meeting_id = ?', $hod_meeting_id);
		$attendance = $hodMeetingAttendanceTable->getAdapter()->fetchAll($select);
		return $attendance;
	}	

	function addTopicTargetDate($params)
	{
		$topicTargetTable = new hod_meeting_topic_target(array('db'=>'db'));
		
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
		$topicStartTable = new hod_meeting_topic_start(array('db'=>'db'));
		
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
		$topicTargetTable = new hod_meeting_topic_target(array('db'=>'db'));

		$select = $topicTargetTable->select()->where('topic_id = ?', $topic_id);
		$targetDate = $topicTargetTable->getAdapter()->fetchAll($select);
		return $targetDate;
	}	

	function getTopicStartDate($topic_id)
	{
		$topicStartTable = new hod_meeting_topic_start(array('db'=>'db'));

		$select = $topicStartTable->select()->where('topic_id = ?', $topic_id);
		$startDate = $topicStartTable->getAdapter()->fetchAll($select);
		return $startDate;
	}	

	function updateFinishDate($params)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"finish_date" => $params["finish_date"],
			"done" => $params["done"],
			"done_hod_meeting_id" => $params["done_hod_meeting_id"]
		);
		$where = $hodMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$hodMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function saveFollowUp($params)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"follow_up" => $params["followup"],
			"hod_meeting_id" => $params["hod_meeting_id"],
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

	function checkIfFollowUpExist($topic_id, $hod_meeting_id)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('topic_id = ?', $topic_id)->where('hod_meeting_id = ?', $hod_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $followup['followup_id'];
	}	

	function getPrevHodMeeting($hod_meeting_date)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		$select = $hodMeetingTable->select();
		if(!empty($hod_meeting_date)) $select->where('date(meeting_date) < ?', $hod_meeting_date);
		$select->where('site_id = ?', $this->site_id);
		$select->order("meeting_date desc")->limit(1);
		$hodMeeting = $hodMeetingTable->getAdapter()->fetchRow($select);
		return $hodMeeting;
	}	

	function getHodMeetingTopicFollowUp($hod_meeting_id)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('hod_meeting_id = ?', $hod_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $followup;
	}	

	function updateDoneByPic($params)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"done_by_pic" => $params["done_by_pic"],
			"done_hod_meeting_id_pic" => $params["done_hod_meeting_id_pic"]
		);
		$where = $hodMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$hodMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function approveMoM($params)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		
		$data = array(
			"approved" => 1,
			"approved_date" => date("Y-m-d H:i:s"),
			"approved_user_id" => $params['user_id']
		);
		$where = $hodMeetingTable->getAdapter()->quoteInto('hod_meeting_id = ?', $params['hod_meeting_id']);
		$hodMeetingTable->update($data, $where);
	}

	function getPrevHodMeetingFollowUp($hod_meeting_id, $hod_meeting_date, $dept_ids = 0)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"hod_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"hod_meeting"), "h.hod_meeting_id = f.hod_meeting_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"hod_meeting_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $hod_meeting_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_hod_meeting_id = '.$hod_meeting_id.' or t.done_hod_meeting_id_pic = '.$hod_meeting_id);
		if(!empty($dept_ids)) $select->where("t.department_id IN (".$dept_ids.")");
		$select->order("t.department_id");
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpByTopicId($topic_id)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"hod_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"hod_meeting"), "h.hod_meeting_id = f.hod_meeting_id", array("h.meeting_date"));
		$select->joinLeft(array("i"=>"hod_meeting_topic_followup_images"), "i.followup_id = f.followup_id", array("i.filename"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->group("f.followup_id");
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function deleteAttendanceById($id)
	{
		$hodMeetingAttendanceTable = new hod_meeting_attendance(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $hodMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $id);
			$hodMeetingAttendanceTable->delete($where);
		}
	}

	function deleteTopicStartDate($id)
	{
		$topicStartTable = new hod_meeting_topic_start(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicStartTable->getAdapter()->quoteInto('topic_start_id = ?', $id);
			$topicStartTable->delete($where);
		}
	}

	function deleteTopicTargetDate($id)
	{
		$topicTargetTable = new hod_meeting_topic_target(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicTargetTable->getAdapter()->quoteInto('topic_target_id = ?', $id);
			$topicTargetTable->delete($where);
		}
	}

	function getHodMeetingTopicsHistory($params)
	{
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));

		$select = $hodMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"hod_meeting_topic"), array("h.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = h.department_id", array("c.category_id", "c.category_name"));
		$select->joinLeft(array("hm"=>"hod_meeting"), "hm.hod_meeting_id = h.hod_meeting_id", array("hm.meeting_date"));		
		$select->joinLeft(array("hmti"=>"hod_meeting_topic_images"), "hmti.hod_meeting_topic_id = h.topic_id", array("hmti.filename"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = h.site_id", array("si.site_name"));
		//$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');
		if(!empty($params['site'])) $select->where('h.site_id = ?', $params['site']);
		if(!empty($params['category'])) $select->where('h.department_id = ?', $params['category']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$select->group("h.topic_id");
		$select->order("h.finish_date desc");
		$select->order("h.topic_id");
		$select->limit($params['pagesize'],$params['start']);
		$hodMeetingTopic = $hodMeetingTopicTable->getAdapter()->fetchAll($select);
		return $hodMeetingTopic;
	}	

	function getTotalHodMeetingTopicsHistory($params) {
		$hodMeetingTopicTable = new hod_meeting_topic(array('db'=>'db'));
		$select = $hodMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"hod_meeting_topic"), array("count(*) as total"));
		$select->joinLeft(array("hm"=>"hod_meeting"), "hm.hod_meeting_id = h.hod_meeting_id", array());
		/*$select->where('h.site_id = ?', $this->site_id);*/
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');		
		if(!empty($params['site'])) $select->where('h.site_id = ?', $params['site']);
		if(!empty($params['category'])) $select->where('h.department_id = ?', $params['category']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		/*$select->group("h.topic_id");*/
		$totalTopic = $hodMeetingTopicTable->getAdapter()->fetchRow($select);
		return $totalTopic;
	}

	function getUnapprovedHodMeeting($site_id, $hod_meeting_date)
	{
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));
		$select = $hodMeetingTable->select();
		if(!empty($hod_meeting_date)) $select->where('date(meeting_date) < ?', $hod_meeting_date);
		$select->where('site_id = ?', $site_id);
		$select->where('approved is NULL or approved = 0');
		$hodMeeting = $hodMeetingTable->getAdapter()->fetchAll($select);
		return $hodMeeting;
	}	

	function saveTopicImage($params)
	{
		$hodMeetingTopicImageTable = new hod_meeting_topic_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"hod_meeting_topic_id" => $params["hod_meeting_topic_id"],
			"hod_meeting_id" => $params["hod_meeting_id"],
			"filename" => $params["filename"]
		);

		$hodMeetingTopicImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getTopicImages($topic_id)
	{
		$hodMeetingTopicImageTable = new hod_meeting_topic_images(array('db'=>'db'));
		$select = $hodMeetingTopicImageTable->select()->where('hod_meeting_topic_id = ?', $topic_id);
		$images = $hodMeetingTopicImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	
	
	function getTopicImageById($image_id)
	{
		$safetyComitteeTopicImageTable = new hod_meeting_topic_images(array('db'=>'db'));
		$select = $safetyComitteeTopicImageTable->select()->where('image_id = ?', $image_id);
		$images = $safetyComitteeTopicImageTable->getAdapter()->fetchRow($select);
		return $images;
	}	

	function deleteTopicImage($id)
	{
		$hodMeetingTopicImageTable = new hod_meeting_topic_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $hodMeetingTopicImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$hodMeetingTopicImageTable->delete($where);
			return $id;
		}
	}

	function saveFollowUpImage($params)
	{
		$topicFollowUpImageTable = new hod_meeting_topic_followup_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"followup_id" => $params["followup_id"],
			"hod_meeting_topic_id" => $params["topic_id"],
			"hod_meeting_id" => $params["hod_meeting_id"],
			"filename" => $params["filename"]
		);

		$topicFollowUpImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getFollowUpImages($followup_id)
	{
		$topicFollowUpImageTable = new hod_meeting_topic_followup_images(array('db'=>'db'));
		$select = $topicFollowUpImageTable->select()->where('followup_id = ?', $followup_id);
		$images = $topicFollowUpImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteFollowUpImage($id)
	{
		$topicFollowUpImageTable = new hod_meeting_topic_followup_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicFollowUpImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$topicFollowUpImageTable->delete($where);
			return $id;
		}
	}
	
	function getFollowUpImageById($id)
	{
		$topicFollowUpImageTable = new hod_meeting_topic_followup_images(array('db'=>'db'));
		$select = $topicFollowUpImageTable->select()->where('image_id = ?', $id);
		$images = $topicFollowUpImageTable->getAdapter()->fetchRow($select);
		return $images;
	}

	function getCurrentFollowUp($topic_id, $hod_meeting_id)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"hod_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("i"=>"hod_meeting_topic_followup_images"), "i.followup_id = f.followup_id", array("i.filename"));
		$select->joinLeft(array("hm"=>"hod_meeting"), "hm.hod_meeting_id = f.hod_meeting_id", array("meeting_date"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->where('f.hod_meeting_id = ?', $hod_meeting_id);
		$select->group("f.followup_id");
		$select->order("f.added_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpById($followup_id)
	{
		$topicFollowUpTable = new hod_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('followup_id = ?', $followup_id);
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $topicFollowUp;
	}	

	function getReportIds($params) {
		$hodMeetingTable = new hod_meeting(array('db'=>'db'));

		$select = $hodMeetingTable->select();
		$select->order('meeting_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$hod = $hodMeetingTable->getAdapter()->fetchAll($select);
		return $hod;
	}

	function addComment($params)
	{
		$commentTable = new hod_meeting_comments(array('db'=>'db3'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"hod_meeting_id" => $params["hod_meeting_id"],
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

	function getCommentsByHODMeetingId($meeting_id, $qty=0, $sort = "desc") {
		$commentsTable = new hod_meeting_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"hod_meeting_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.hod_meeting_id=?", $meeting_id);
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	
	function getAttendanceByMeetingSiteId($hod_meeting_id, $category_id)
	{
		$hodMeetingAttendanceTable = new hod_meeting_attendance(array('db'=>'db'));
		$select = $hodMeetingAttendanceTable->select()->where('hod_meeting_id = ?', $hod_meeting_id)->where('category_id = ?', $category_id);
		$attendance = $hodMeetingAttendanceTable->getAdapter()->fetchAll($select);
		return $attendance;
	}	
}

?>