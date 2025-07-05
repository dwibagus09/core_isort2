<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class itClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function saveIt($params)
	{
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"created_date" => date("Y-m-d H:i:s"),
			"meeting_date" => $params["meeting_date"]." 00:00:00",
			"meeting_time" => $params["meeting_time"],
			"meeting_title" => $params["title"]
		);
		if(empty($params['it_meeting_id']))
		{
			$itMeetingTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $itMeetingTable->getAdapter()->quoteInto('it_meeting_id = ?', $params['it_meeting_id']);
			$itMeetingTable->update($data, $where);
			return $params['it_meeting_id'];
		}
	}

	function saveAttendance($params)
	{
		$itMeetingAttendanceTable = new it_meeting_attendance(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"it_meeting_id" => $params["it_meeting_id"],
			"user_id" => $params["user_id"]
		);
		if(empty($params['attendance_id']))
		{
			$itMeetingAttendanceTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			$where = $itMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $params['attendance_id']);
			$itMeetingAttendanceTable->update($data, $where);
			return $params['attendance_id'];
		}
	}

	function getItMeetingById($it_meeting_id)
	{
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		$select = $itMeetingTable->select()->where('it_meeting_id = ?', $it_meeting_id);
		$itMeeting = $itMeetingTable->getAdapter()->fetchRow($select);
		return $itMeeting;
	}	

	function saveTopic($params)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"it_meeting_id" => $params["it_meeting_id"],
			"pic_id" => $params["pic_id"],
			"topic" => $params["topic"],
			"created_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['topic_id']))
		{
			$itMeetingTopicTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $itMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
			$itMeetingTopicTable->update($data, $where);
			return $params['topic_id'];
		}
	}

	function getItMeetingTopics($it_meeting_id, $it_meeting_date = "", $pic_id = 0)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));

		$select = $itMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"it_meeting_topic"), array("h.*"));
		$select->joinLeft(array("t"=>"it_meeting_topic_target"), "t.topic_id = h.topic_id", array("t.topic_target_id"));
		$select->joinLeft(array("s"=>"it_meeting_topic_start"), "s.topic_id = h.topic_id", array("s.topic_start_id"));
		$select->joinLeft(array("f"=>"it_meeting_topic_followup"), "f.topic_id = h.topic_id and f.it_meeting_id = ".$it_meeting_id, array("f.followup_id", "f.follow_up"));
		$select->joinLeft(array("hm"=>"it_meeting"), "hm.it_meeting_id = h.it_meeting_id", array("hm.meeting_date"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = h.pic_id", array("u.name"));
		$select->where('h.site_id = ?', $this->site_id);
		if(!empty($it_meeting_date)) $select->where('date(hm.meeting_date) <= ?', $it_meeting_date);
		else $select->where('h.it_meeting_id = ?', $it_meeting_id);
		$select->where('h.done is null or h.done = 0 or h.done_by_pic is null or h.done_by_pic = 0 or h.done_it_meeting_id = '.$it_meeting_id.' or h.done_it_meeting_id_pic = '.$it_meeting_id);
		if(!empty($pic_id)) $select->where("h.pic_id = ?", $pic_id);
		$select->order("u.name");
		$select->group("h.topic_id");
		$itMeetingTopic = $itMeetingTopicTable->getAdapter()->fetchAll($select);
		return $itMeetingTopic;
	}	

	function getItMeetingTopicById($topic_id)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));

		$select = $itMeetingTopicTable->select()->where('topic_id = ?', $topic_id);
		$itMeetingTopic = $itMeetingTopicTable->getAdapter()->fetchRow($select);
		return $itMeetingTopic;
	}	

	function deleteTopic($topic_id)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));
		
		if ( is_numeric($topic_id) && $topic_id > 0 )
		{		
			$where = $itMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $topic_id);
			$itMeetingTopicTable->delete($where);
		}
	}

	function getItMeetingMom()
	{
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		$select = $itMeetingTable->select()->where('site_id = ?', $this->site_id)->order("meeting_date desc");
		$itMeeting = $itMeetingTable->getAdapter()->fetchAll($select);
		return $itMeeting;
	}	

	function getTotalItMom() {
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		$select = "select count(*) as total from it_meeting where site_id =".$this->site_id;
		$itMom = $itMeetingTable->getAdapter()->fetchRow($select);
		return $itMom;
	}

	function getAttendanceByItMeetingId($it_meeting_id)
	{
		$itMeetingAttendanceTable = new it_meeting_attendance(array('db'=>'db'));
		$select = $itMeetingAttendanceTable->getAdapter()->select();
		$select->from(array("a"=>"it_meeting_attendance"), array("a.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = a.user_id", array("u.name"));
		$select->where('a.it_meeting_id = ?', $it_meeting_id);
		$attendance = $itMeetingAttendanceTable->getAdapter()->fetchAll($select);
		return $attendance;
	}	

	function addTopicTargetDate($params)
	{
		$topicTargetTable = new it_meeting_topic_target(array('db'=>'db'));
		
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
		$topicStartTable = new it_meeting_topic_start(array('db'=>'db'));
		
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
		$topicTargetTable = new it_meeting_topic_target(array('db'=>'db'));

		$select = $topicTargetTable->select()->where('topic_id = ?', $topic_id);
		$targetDate = $topicTargetTable->getAdapter()->fetchAll($select);
		return $targetDate;
	}	

	function getTopicStartDate($topic_id)
	{
		$topicStartTable = new it_meeting_topic_start(array('db'=>'db'));

		$select = $topicStartTable->select()->where('topic_id = ?', $topic_id);
		$startDate = $topicStartTable->getAdapter()->fetchAll($select);
		return $startDate;
	}	

	function updateFinishDate($params)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"finish_date" => $params["finish_date"],
			"done" => $params["done"],
			"done_it_meeting_id" => $params["done_it_meeting_id"]
		);
		$where = $itMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$itMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function saveFollowUp($params)
	{
		$topicFollowUpTable = new it_meeting_topic_followup(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"topic_id" => $params["topic_id"],
			"follow_up" => $params["followup"],
			"it_meeting_id" => $params["it_meeting_id"],
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
			return $params['topic_id'];
		}
	}

	function checkIfFollowUpExist($topic_id, $it_meeting_id)
	{
		$topicFollowUpTable = new it_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('topic_id = ?', $topic_id)->where('it_meeting_id = ?', $it_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchRow($select);
		return $followup['followup_id'];
	}	

	function getPrevItMeeting($it_meeting_date)
	{
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		$select = $itMeetingTable->select();
		if(!empty($it_meeting_date)) $select->where('date(meeting_date) < ?', $it_meeting_date);
		$select->where('site_id = ?', $this->site_id);
		$select->order("meeting_date desc")->limit(1);
		$itMeeting = $itMeetingTable->getAdapter()->fetchRow($select);
		return $itMeeting;
	}	

	function getItMeetingTopicFollowUp($it_meeting_id)
	{
		$topicFollowUpTable = new it_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->select()->where('it_meeting_id = ?', $it_meeting_id);
		$followup = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $followup;
	}	

	function updateDoneByPic($params)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));
		
		$data = array(
			"done_by_pic" => $params["done_by_pic"],
			"done_it_meeting_id_pic" => $params["done_it_meeting_id_pic"]
		);
		$where = $itMeetingTopicTable->getAdapter()->quoteInto('topic_id = ?', $params['topic_id']);
		$itMeetingTopicTable->update($data, $where);
		return $params['topic_id'];
	}

	function approveMoM($params)
	{
		$itMeetingTable = new it_meeting(array('db'=>'db'));
		
		$data = array(
			"approved" => 1,
			"approved_date" => date("Y-m-d H:i:s"),
			"approved_user_id" => $params['user_id']
		);
		$where = $itMeetingTable->getAdapter()->quoteInto('it_meeting_id = ?', $params['id']);
		$itMeetingTable->update($data, $where);
	}

	function getPrevItMeetingFollowUp($it_meeting_id, $it_meeting_date, $pic_id = 0)
	{
		$topicFollowUpTable = new it_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"it_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"it_meeting"), "h.it_meeting_id = f.it_meeting_id", array("h.meeting_date"));
		$select->joinLeft(array("t"=>"it_meeting_topic"), "t.topic_id = f.topic_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('date(h.meeting_date) <= ?', $it_meeting_date);
		$select->where('t.done is null or t.done = 0 or t.done_by_pic is null or t.done_by_pic = 0 or t.done_it_meeting_id = '.$it_meeting_id.' or t.done_it_meeting_id_pic = '.$it_meeting_id);
		if(!empty($pic_id)) $select->where("t.pic_id = ?", $pic_id);
		$select->order("t.topic_id");
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function getFollowUpByTopicId($topic_id)
	{
		$topicFollowUpTable = new it_meeting_topic_followup(array('db'=>'db'));

		$select = $topicFollowUpTable->getAdapter()->select();
		$select->from(array("f"=>"it_meeting_topic_followup"), array("f.*"));
		$select->joinLeft(array("h"=>"it_meeting"), "h.it_meeting_id = f.it_meeting_id", array("h.meeting_date"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('f.topic_id = ?', $topic_id);
		$select->order("h.meeting_date desc");
		$topicFollowUp = $topicFollowUpTable->getAdapter()->fetchAll($select);
		return $topicFollowUp;
	}	

	function deleteAttendanceById($id)
	{
		$itMeetingAttendanceTable = new it_meeting_attendance(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $itMeetingAttendanceTable->getAdapter()->quoteInto('attendance_id = ?', $id);
			$itMeetingAttendanceTable->delete($where);
		}
	}

	function deleteTopicStartDate($id)
	{
		$topicStartTable = new it_meeting_topic_start(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicStartTable->getAdapter()->quoteInto('topic_start_id = ?', $id);
			$topicStartTable->delete($where);
		}
	}

	function deleteTopicTargetDate($id)
	{
		$topicTargetTable = new it_meeting_topic_target(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $topicTargetTable->getAdapter()->quoteInto('topic_target_id = ?', $id);
			$topicTargetTable->delete($where);
		}
	}

	function getItMeetingTopicsHistory($params)
	{
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));

		$select = $itMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"it_meeting_topic"), array("h.*"));
		$select->joinLeft(array("hm"=>"it_meeting"), "hm.it_meeting_id = h.it_meeting_id", array("hm.meeting_date"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = h.pic_id", array("u.name"));
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');
		if(!empty($params['pic_id'])) $select->where('h.pic_id = ?', $params['pic_id']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$select->order("h.finish_date desc");
		$select->order("h.topic_id");
		$select->limit($params['pagesize'],$params['start']);
		$itMeetingTopic = $itMeetingTopicTable->getAdapter()->fetchAll($select);
		return $itMeetingTopic;
	}	

	function getTotalItMeetingTopicsHistory($params) {
		$itMeetingTopicTable = new it_meeting_topic(array('db'=>'db'));
		$select = $itMeetingTopicTable->getAdapter()->select();
		$select->from(array("h"=>"it_meeting_topic"), array("count(*) as total"));
		$select->joinLeft(array("hm"=>"it_meeting"), "hm.it_meeting_id = h.it_meeting_id", array());
		$select->where('h.site_id = ?', $this->site_id);
		$select->where('h.done = ?', '1');
		$select->where('h.done_by_pic = ?', '1');
		$select->where('hm.approved = ?', '1');		
		if(!empty($params['pic_id'])) $select->where('h.pic_id = ?', $params['pic_id']);
		if(!empty($params['project_name'])) $select->where('h.topic like ?', "%".$params['project_name']."%");
		$totalTopic = $itMeetingTopicTable->getAdapter()->fetchRow($select);
		return $totalTopic;
	}

	function getReportIds($params) {
		$itMeetingTable = new it_meeting(array('db'=>'db'));

		$select = $itMeetingTable->select();
		$select->order('meeting_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$it = $itMeetingTable->getAdapter()->fetchAll($select);
		return $it;
	}

	function addComment($params)
	{
		$commentTable = new it_meeting_comments(array('db'=>'db3'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"it_meeting_id" => $params["it_meeting_id"],
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

	function getCommentsByITMeetingId($meeting_id, $qty=0, $sort = "desc") {
		$commentsTable = new it_meeting_comments(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"it_meeting_comments"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.it_meeting_id=?", $meeting_id);
		$select->order("c.comment_id ".$sort);
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
}

?>