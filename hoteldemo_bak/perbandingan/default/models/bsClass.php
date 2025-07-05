<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class bsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveBs($params)
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"created_date" => date("Y-m-d H:i:s"),
			"meeting_date" => $params["meeting_date"]." 00:00:00",
			"meeting_time" => $params["meeting_time"],
			"meeting_title" => $params["title"]
		);
		if(empty($params['bs_meeting_id']))
		{
			$bsMeetingTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $bsMeetingTable->getAdapter()->quoteInto('bs_meeting_id = ?', $params['bs_meeting_id']);
			$bsMeetingTable->update($data, $where);
			return $params['bs_meeting_id'];
		}
	}

	function saveAttendance($params)
	{
		$bsMeetingAttendanceTable = new bs_meeting_attendance(array('db'=>'db'));
		
		$data = array(
			"site_id" => $params["site_id"],
			"bs_meeting_id" => $params["bs_meeting_id"],
			"category_id" => 10,
			"attendance_name" => $params["attendance_name"]
		);
		if(empty($params['attendance_id']))
		{
			$bsMeetingAttendanceTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $bsMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $params['attendance_id']);
			$bsMeetingAttendanceTable->update($data, $where);
			return $params['attendance_id'];
		}
	}

	function getBsMeetingById($bs_meeting_id)
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		$select = $bsMeetingTable->select()->where('bs_meeting_id = ?', $bs_meeting_id);
		$bsMeeting = $bsMeetingTable->getAdapter()->fetchRow($select);
		return $bsMeeting;
	}	

	function saveTopic($params)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"site_id" => $params['site_id'],
			"user_id" => $params["user_id"],
			"bs_meeting_id" => $params["bs_meeting_id"],
			"department_id" => 10,
			"topic" => $params["topic"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_id']))
		{
			$bsMeetingTopicTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $bsMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
			$bsMeetingTopicTable->update($data, $where);
			return $params['topic_id'];
		}
	}

	function getBsMeetingTopics($bs_meeting_id, $bs_meeting_date = "", $dept_ids = 0)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));

		$select = $bsMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"bs_meeting_topic"), array("h.*"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = h.site_id", array("si.site_id", "si.initial"));
		$select->joinLeft(array("t"=>"bs_meeting_topic_target"), "t.topic_id = h.topic_id", array("t.topic_target_id"));
		$select->joinLeft(array("s"=>"bs_meeting_topic_start"), "s.topic_id = h.topic_id", array("s.topic_start_id"));
		$select->joinLeft(array("f"=>"bs_meeting_topic_followup"), "f.topic_id = h.topic_id and f.bs_meeting_id = ".$bs_meeting_id, array("f.followup_id", "f.follow_up"));
		$select->joinLeft(array("hm"=>"bs_meeting"), "hm.bs_meeting_id = h.bs_meeting_id", array("hm.meeting_date"));
		$select->joinLeft(array("hmti"=>"bs_meeting_topic_images"), "hmti.bs_meeting_topic_id = h.topic_id", array("hmti.filename"));
		//$select->where('h.site_id = ?', $this->site_id);
		if(!empty($bs_meeting_date)) $select->where('date(hm.meeting_date) <= ?', $bs_meeting_date);
		else $select->where('h.bs_meeting_id = ?', $bs_meeting_id);
		$select->where('h.done is null or h.done = 0 or h.done_by_pic is null or h.done_by_pic = 0 or h.done_bs_meeting_id = '.$bs_meeting_id.' or h.done_bs_meeting_id_pic = '.$bs_meeting_id);
		if(!empty($dept_ids)) $select->where("h.department_id IN (".$dept_ids.")");
		$select->order("si.site_id");
		$select->group("h.topic_id");
		$bsMeetingTopic = $bsMeetingTopicTable->getAdapter()->fetchAll($select);
		return $bsMeetingTopic;
	}	

	function getBsMeetingTopicById($topic_id)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));

		$select = $bsMeetingTopicTable->select()->where('topic_id = ?', $topic_id);
		$bsMeetingTopic = $bsMeetingTopicTable->getAdapter()->fetchRow($select);
		return $bsMeetingTopic;
	}	

	function deleteTopic($topic_id)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));
		
		if ( is_numeric($topic_id) && $topic_id > 0 )
		{		
			$where = $bsMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $topic_id);
			$bsMeetingTopicTable->delete($where);
		}
	}

	function getBsMeetingMom()
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		$select = $bsMeetingTable->select()/*->where('site_id = ?', $this->site_id)*/->order("meeting_date desc");
		$bsMeeting = $bsMeetingTable->getAdapter()->fetchAll($select);
		return $bsMeeting;
	}	

	function getTotalBsMom() {
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		$select = "select count(*) as total from bs_meeting";
		$bsMom = $bsMeetingTable->getAdapter()->fetchRow($select);
		return $bsMom;
	}

	function getAttendanceByBsMeetingId($bs_meeting_id)
	{
		$bsMeetingAttendanceTable = new bs_meeting_attendance(array('db'=>'db'));
		$select = $bsMeetingAttendanceTable->select()->where('bs_meeting_id = ?', $bs_meeting_id);
		$attendance = $bsMeetingAttendanceTable->getAdapter()->fetchAll($select);
		return $attendance;
	}	

	function addTopicTargetDate($params)
	{
		$topicTargetTable = new bs_meeting_topic_target(array('db'=>'db'));
		
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
		$topicStartTable = new bs_meeting_topic_start(array('db'=>'db'));
		
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
		$topicTargetTable = new bs_meeting_topic_target(array('db'=>'db'));

		$select = $topicTargetTable->select()->where('topic_id = ?', $topic_id);
		$targetDate = $topicTargetTable->getAdapter()->fetchAll($select);
		return $targetDate;
	}	

	function getTopicStartDate($topic_id)
	{
		$topicStartTable = new bs_meeting_topic_start(array('db'=>'db'));

		$select = $topicStartTable->select()->where('topic_id = ?', $topic_id);
		$startDate = $topicStartTable->getAdapter()->fetchAll($select);
		return $startDate;
	}	

	function updateFinishDate($params)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"finish_date" => $params["finish_date"],
			"done" => $params["done"],
			"done_bs_meeting_id" => $params["done_bs_meeting_id"]
		);
		$where = $bsMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$bsMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function saveFollowUp($params)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"follow_up" => $params["followup"],
			"bs_meeting_id" => $params["bs_meeting_id"],
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

	function checkIfFollowUpExist($topic_id, $bs_meeting_id)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('topic_id = ?', $topic_id)->where('bs_meeting_id = ?', $bs_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $followup['followup_id'];
	}	

	function getPrevBsMeeting($bs_meeting_date)
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		$select = $bsMeetingTable->select();
		if(!empty($bs_meeting_date)) $select->where('date(meeting_date) < ?', $bs_meeting_date);
		$select->where('site_id = ?', $this->site_id);
		$select->order("meeting_date desc")->limit(1);
		$bsMeeting = $bsMeetingTable->getAdapter()->fetchRow($select);
		return $bsMeeting;
	}	

	function getBsMeetingTopicFollowUp($bs_meeting_id)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('bs_meeting_id = ?', $bs_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $followup;
	}	

	function updateDoneByPic($params)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"done_by_pic" => $params["done_by_pic"],
			"done_bs_meeting_id_pic" => $params["done_bs_meeting_id_pic"]
		);
		$where = $bsMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$bsMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function approveMoM($params)
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		
		$data = array(
			"approved" => 1,
			"approved_date" => date("Y-m-d H:i:s"),
			"approved_user_id" => $params['user_id']
		);
		$where = $bsMeetingTable->getAdapter()->quoteInto('bs_meeting_id = ?', $params['id']);
		$bsMeetingTable->update($data, $where);
	}

	function getPrevBsMeetingFollowUp($bs_meeting_id, $bs_meeting_date, $dept_ids = 0)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"bs_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"bs_meeting"), "h.bs_meeting_id = f.bs_meeting_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"bs_meeting_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $bs_meeting_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_bs_meeting_id = '.$bs_meeting_id.' or t.done_bs_meeting_id_pic = '.$bs_meeting_id);
		if(!empty($dept_ids)) $select->where("t.department_id IN (".$dept_ids.")");
		$select->order("t.department_id");
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpByTopicId($topic_id)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"bs_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"bs_meeting"), "h.bs_meeting_id = f.bs_meeting_id", array("h.meeting_date"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function deleteAttendanceById($id)
	{
		$bsMeetingAttendanceTable = new bs_meeting_attendance(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $bsMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $id);
			$bsMeetingAttendanceTable->delete($where);
		}
	}

	function deleteTopicStartDate($id)
	{
		$topicStartTable = new bs_meeting_topic_start(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicStartTable->getAdapter()->quoteInto('topic_start_id = ?', $id);
			$topicStartTable->delete($where);
		}
	}

	function deleteTopicTargetDate($id)
	{
		$topicTargetTable = new bs_meeting_topic_target(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicTargetTable->getAdapter()->quoteInto('topic_target_id = ?', $id);
			$topicTargetTable->delete($where);
		}
	}

	function getBsMeetingTopicsHistory($params)
	{
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));

		$select = $bsMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"bs_meeting_topic"), array("h.*"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = h.site_id", array("s.site_id", "s.initial"));
		$select->joinLeft(array("hm"=>"bs_meeting"), "hm.bs_meeting_id = h.bs_meeting_id", array("hm.meeting_date"));
		//$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');
		if(!empty($params['site'])) $select->where('h.site_id = ?', $params['site']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$select->order("h.finish_date desc");
		$select->order("h.topic_id");
		$select->limit($params['pagesize'],$params['start']);
		$bsMeetingTopic = $bsMeetingTopicTable->getAdapter()->fetchAll($select);
		return $bsMeetingTopic;
	}	

	function getTotalBsMeetingTopicsHistory($params) {
		$bsMeetingTopicTable = new bs_meeting_topic(array('db'=>'db'));
		$select = $bsMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"bs_meeting_topic"), array("count(*) as total"));
		$select->joinLeft(array("hm"=>"bs_meeting"), "hm.bs_meeting_id = h.bs_meeting_id", array());
		//$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');		
		if(!empty($params['site'])) $select->where('h.site_id = ?', $params['site']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$totalTopic = $bsMeetingTopicTable->getAdapter()->fetchRow($select);
		return $totalTopic;
	}

	function getUnapprovedBsMeeting($site_id, $bs_meeting_date)
	{
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));
		$select = $bsMeetingTable->select();
		if(!empty($bs_meeting_date)) $select->where('date(meeting_date) < ?', $bs_meeting_date);
		$select->where('site_id = ?', $site_id);
		$select->where('approved is NULL or approved = 0');
		$bsMeeting = $bsMeetingTable->getAdapter()->fetchAll($select);
		return $bsMeeting;
	}	

	function saveTopicImage($params)
	{
		$bsMeetingTopicImageTable = new bs_meeting_topic_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"bs_meeting_topic_id" => $params["bs_meeting_topic_id"],
			"bs_meeting_id" => $params["bs_meeting_id"],
			"filename" => $params["filename"]
		);

		$bsMeetingTopicImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getTopicImages($topic_id)
	{
		$bsMeetingTopicImageTable = new bs_meeting_topic_images(array('db'=>'db'));
		$select = $bsMeetingTopicImageTable->select()->where('bs_meeting_topic_id = ?', $topic_id);
		$images = $bsMeetingTopicImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteTopicImage($id)
	{
		$bsMeetingTopicImageTable = new bs_meeting_topic_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $bsMeetingTopicImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$bsMeetingTopicImageTable->delete($where);
		}
	}

	function saveFollowUpImage($params)
	{
		$topicFollowUpImageTable = new bs_meeting_topic_followup_images(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"followup_id" => $params["followup_id"],
			"bs_meeting_topic_id" => $params["topic_id"],
			"bs_meeting_id" => $params["bs_meeting_id"],
			"filename" => $params["filename"]
		);

		$topicFollowUpImageTable->insert($data);
		return $this->db->lastInsertId();
		
	}

	function getFollowUpImages($followup_id)
	{
		$topicFollowUpImageTable = new bs_meeting_topic_followup_images(array('db'=>'db'));
		$select = $topicFollowUpImageTable->select()->where('followup_id = ?', $followup_id);
		$images = $topicFollowUpImageTable->getAdapter()->fetchAll($select);
		return $images;
	}	

	function deleteFollowUpImage($id)
	{
		$topicFollowUpImageTable = new bs_meeting_topic_followup_images(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicFollowUpImageTable->getAdapter()->quoteInto('image_id = ?', $id);
			$topicFollowUpImageTable->delete($where);
		}
	}

	function getCurrentFollowUp($topic_id, $bs_meeting_id)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"bs_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("i"=>"bs_meeting_topic_followup_images"), "i.followup_id = f.followup_id", array("i.filename"));
		$select->where('f.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->where('f.bs_meeting_id = ?', $bs_meeting_id);
		$select->group("f.followup_id");
		$select->order("f.added_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpById($followup_id)
	{
		$topicFollowUpTable = new bs_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('followup_id = ?', $followup_id);
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $topicFollowUp;
	}	

	function getReportIds($params) {
		$bsMeetingTable = new bs_meeting(array('db'=>'db'));

		$select = $bsMeetingTable->select();
		$select->order('meeting_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$bs = $bsMeetingTable->getAdapter()->fetchAll($select);
		return $bs;
	}

	function addComment($params)
	{
		$commentTable = new bs_meeting_comments(array('db'=>'db3'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"bs_meeting_id" => $params["bs_meeting_id"],
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

	function getCommentsByBSMeetingId($meeting_id, $qty=0, $sort = "desc") {
		$commentsTable = new bs_meeting_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"bs_meeting_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.bs_meeting_id=?", $meeting_id);
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	

}

?>