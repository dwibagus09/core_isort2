<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class securityClass extends defaultClass
{
	function addSecurity($params, $saveFromChief=0) {
		$securityTable = new security(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"shift" => $params["shift"],
			"supervisor" => $params["supervisor-inhouse"],
			"staff_posko" => $params["staff-posko-inhouse"],
			"staff_cctv" => $params["staff-cctv-inhouse"],
			"safety" => $params["safety-inhouse"],
			"chief_spd" => $params["chief-spd"],
			"panwas_spd" => $params["panwas-spd"],
			"danton_spd" => $params["danton-spd"],
			"jumlah_spd" => $params["jumlah-spd"],
			"chief_army" => $params["chief-army"],
			"panwas_army" => $params["panwas-army"],
			"danton_army" => $params["danton-army"],
			"jumlah_army" => $params["jumlah-army"],
			"briefing" => addslashes($params["briefing"]),
			"created_date" => date("Y-m-d H:i:s"),
			"user_id" => $params["user_id"]
		);
		if($saveFromChief=='1')
		{
			unset($data['chief_spd']);
			unset($data['panwas_spd']);
			unset($data['danton_spd']);
			unset($data['jumlah_spd']);
			unset($data['chief_army']);
			unset($data['panwas_army']);
			unset($data['danton_army']);
			unset($data['jumlah_army']);
			$data['briefing2'] = $params["briefing2"];
			$data['briefing3'] = $params["briefing3"];
			$data['created_date'] = $params['report_date']." ".date("H:i:s");
			$data['chief_security_report_id'] = $params["chief_security_report_id"];
		}
		$securityTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function updateSecurity($params, $saveFromChief=0) {
		$securityTable = new security(array('db'=>'db'));
		
		$data = array(
			"shift" => $params["shift"],
			"supervisor" => $params["supervisor-inhouse"],
			"staff_posko" => $params["staff-posko-inhouse"],
			"staff_cctv" => $params["staff-cctv-inhouse"],
			"safety" => $params["safety-inhouse"],
			"chief_spd" => $params["chief-spd"],
			"panwas_spd" => $params["panwas-spd"],
			"danton_spd" => $params["danton-spd"],
			"jumlah_spd" => $params["jumlah-spd"],
			"chief_army" => $params["chief-army"],
			"panwas_army" => $params["panwas-army"],
			"danton_army" => $params["danton-army"],
			"jumlah_army" => $params["jumlah-army"],
			"briefing" => addslashes($params["briefing"]),			
			"briefing2" => addslashes($params["briefing2"]),
			"briefing3" => addslashes($params["briefing3"])/*,
			"user_id" => $params["user_id"],
			"chief_security_report_id" => $params["chief_security_report_id"]*/
		);

		if(!empty($params['created_by'])) $data['user_id'] = $params['created_by'];
		
		if($params['role_id'] == '2')
		{
			unset($data['briefing2']);
			unset($data['briefing3']);
		}
		
		if($saveFromChief=='1')
		{
			unset($data['chief_spd']);
			unset($data['panwas_spd']);
			unset($data['danton_spd']);
			unset($data['jumlah_spd']);
			unset($data['chief_army']);
			unset($data['panwas_army']);
			unset($data['danton_army']);
			unset($data['jumlah_army']);
			unset($data['user_id']);
			$data['briefing2'] = $params["briefing2"];
			$data['briefing3'] = $params["briefing3"];
		}
		if(!empty($params["chief_security_report_id"])) $data['chief_security_report_id'] = $params["chief_security_report_id"];

		$where = $securityTable->getAdapter()->quoteInto('security_id = ?', $params['security_id']);
		
		$securityTable->update($data, $where);
		return $params['security_id'];
	}
	
	function addDefectList($params) {
		$security_defect_listTable = new security_defect_list(array('db'=>'db'));
		
		$data = array(
			"security_id" => $params["security_id"],
			"area" => $params["area"],
			"detail" => $params["detail"],
			"follow_up" => $params["follow_up"],
			"issue_id" => $params["issue_id"]
		);
		
		if(empty($params["sdl_id"])) 
		{
			$security_defect_listTable->insert($data);
		    return $this->db->lastInsertId();
		}
		else {
			$where = $security_defect_listTable->getAdapter()->quoteInto('sdl_id = ?', $params["sdl_id"]);
			$security_defect_listTable->update($data, $where);
			return $params["sdl_id"];
		}
	}
	
	function addIncident($params) {
		$security_incidentTable = new security_incident(array('db'=>'db'));
		
		$data = array(
			"security_id" => $params["security_id"],
			"issue_id" => $params["issue_id"],
			"status" => $params["status"]
		);
		
		if(empty($params["incident_id"])) 
		{
			$security_incidentTable->insert($data);
		    return $this->db->lastInsertId();
		}
		else {
			$where = $security_incidentTable->getAdapter()->quoteInto('incident_id = ?', $params["incident_id"]);
			$security_incidentTable->update($data, $where);
			return $params["incident_id"];
		}
	}
	
	function addGlitch($params) {
		$security_glitchTable = new security_glitch(array('db'=>'db'));
		
		$data = array(
			"security_id" => $params["security_id"],
			"issue_id" => $params["issue_id"],
			"status" => $params["status"]
		);
		
		if(empty($params["glitch_id"])) 
		{
			$security_glitchTable->insert($data);
		    return $this->db->lastInsertId();
		}
		else {
			$where = $security_glitchTable->getAdapter()->quoteInto('glitch_id = ?', $params["glitch_id"]);
			$security_glitchTable->update($data, $where);
			return $params["glitch_id"];
		}
		
		
	}
	
	function addLostFound($params) {
		$security_lost_foundTable = new security_lost_found(array('db'=>'db'));
		
		$data = array(
			"security_id" => $params["security_id"],
			"issue_id" => $params["issue_id"],
			"status" => $params["status"]
		);
		
		if(empty($params["lost_found_id"])) 
		{
			$security_lost_foundTable->insert($data);
		    return $this->db->lastInsertId();
		}
		else {
			$where = $security_lost_foundTable->getAdapter()->quoteInto('lost_found_id = ?', $params["lost_found_id"]);
			$security_lost_foundTable->update($data, $where);
			return $params["lost_found_id"];
		}
	}
	
	function updateSecurityIssue($params) {
		if($params['issue_type_id'] == 4) // defect list
		{
		    $security_issue_table = new security_defect_list(array('db'=>'db'));
		    $id_name = "sdl_id";
		}
		if($params['issue_type_id'] == 2) // glitch
		{
		    $security_issue_table = new security_glitch(array('db'=>'db'));
		    $id_name = "glitch_id";
		}
		if($params['issue_type_id'] == 3) // lost & found
		{
		    $security_issue_table = new security_lost_found(array('db'=>'db'));
		    $id_name = "lost_found_id";
		}
		if($params['issue_type_id'] == 1) // incident report
		{
		    $security_issue_table = new security_incident(array('db'=>'db'));
		    $id_name = "incident_id";
		}
		
		if($params['issue_type_id'] == 4)
		{
		    $data = array(
    			"issue_id" => $params["issue_id"],
    			"follow_up" => $params["status"]
    		);
		}
		else
		{
    		$data = array(
    			"issue_id" => $params["issue_id"],
    			"status" => $params["status"]
    		);
		}
		
		if(empty($params["security_issue_id"])) 
		{
			$security_issue_table->insert($data);
		    return $this->db->lastInsertId();
		}
		else {
			$where = $security_issue_table->getAdapter()->quoteInto($id_name.' = ?', $params["security_issue_id"]);
			$security_issue_table->update($data, $where);
		}
	}
	
	function getSecurityReports($params) {
		$securityTable = new security(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select->from(array("s"=>"security"), array("s.*"));
		$select->joinLeft(array("sh"=>"shift"), "sh.shift_id = s.shift", array("sh.shift_name"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->where('s.site_id = ?', $this->site_id);
		$select->order('s.created_date desc');
		$select->limit($params['pagesize'],$params['start']);
		$security = $securityTable->getAdapter()->fetchAll($select);
		return $security;
	}
	
	function getSecurityReportById($id) {
		$securityTable= new security(array('db'=>'db'));
		
		//$securityTable = new security_glitch(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select->from(array("s"=>"security"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->joinLeft(array("si"=>"sites"), "si.site_id = s.site_id", array("si.site_fullname"));
		$select->where('s.security_id = ?', $id);
		$security = $securityTable->getAdapter()->fetchRow($select);
		return $security;
	}
	
	function getSecurityReportByChiefId($id) {
		$securityTable= new security(array('db'=>'db'));
		
		$select = $securityTable->getAdapter()->select();
		$select = $securityTable->select()->where("chief_security_report_id = ?", $id);
		$security = $securityTable->getAdapter()->fetchAll($select);
		return $security;
	}
	
	function getDefectListBySecurityId($id) {
		$security_defect_listTable = new security_defect_list(array('db'=>'db'));
		$select = $security_defect_listTable->select()->where("security_id='".$id."'")->order("sdl_id desc");
		$defect_list = $security_defect_listTable->getAdapter()->fetchAll($select);
		return $defect_list;
	}
	
	function getIncidentBySecurityId($id) {
		$security_incidentTable = new security_incident(array('db'=>'db'));
		$select = $security_incidentTable->getAdapter()->select();
		$select->from(array("si"=>"security_incident"), array("si.*"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = si.issue_id", array("i.*"));
		$select->where('si.security_id = ?', $id);
		$select->order('incident_id desc');
		$incident = $security_incidentTable->getAdapter()->fetchAll($select);
		return $incident;
	}
	
	function getGlitchBySecurityId($id) {
		$security_glitchTable = new security_glitch(array('db'=>'db'));
		$select = $security_glitchTable->getAdapter()->select();
		$select->from(array("sg"=>"security_glitch"), array("sg.*"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = sg.issue_id", array("i.*"));
		$select->where('sg.security_id = ?', $id);
		$select->order('glitch_id desc');
		$glitch = $security_glitchTable->getAdapter()->fetchAll($select);
		return $glitch;
	}
	
	function getLostFoundBySecurityId($id) {
		$security_lost_foundTable = new security_lost_found(array('db'=>'db'));
		$select = $security_lost_foundTable->getAdapter()->select();
		$select->from(array("slf"=>"security_lost_found"), array("slf.*"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = slf.issue_id", array("i.*"));
		$select->where('slf.security_id = ?', $id);
		$select->order('lost_found_id desc');
		$lost_found = $security_lost_foundTable->getAdapter()->fetchAll($select);
		return $lost_found;
	}
	
	function deleteDefectListBySecurityId($security_id)
	{
		$security_defect_listTable = new security_defect_list(array('db'=>'db'));
		
		if ( is_numeric($security_id) && $security_id > 0 )
		{		
			$where = $security_defect_listTable->getAdapter()->quoteInto('security_id = ?', $security_id);
			$security_defect_listTable->delete($where);
		}
	}
	
	function deleteIncidentBySecurityId($security_id)
	{
		$security_incidentTable = new security_incident(array('db'=>'db'));
		
		if ( is_numeric($security_id) && $security_id > 0 )
		{		
			$where = $security_incidentTable->getAdapter()->quoteInto('security_id = ?', $security_id);
			$security_incidentTable->delete($where);
		}
	}
	
	function deleteGlitchBySecurityId($security_id)
	{
		$security_glitchTable = new security_glitch(array('db'=>'db'));
		
		if ( is_numeric($security_id) && $security_id > 0 )
		{		
			$where = $security_glitchTable->getAdapter()->quoteInto('security_id = ?', $security_id);
			$security_glitchTable->delete($where);
		}
	}
	
	function deleteLostFoundBySecurityId($security_id)
	{
		$security_lost_foundTable = new security_lost_found(array('db'=>'db'));
		
		if ( is_numeric($security_id) && $security_id > 0 )
		{		
			$where = $security_lost_foundTable->getAdapter()->quoteInto('security_id = ?', $security_id);
			$security_lost_foundTable->delete($where);
		}
	}
	
	function deleteSecurityById($id)
	{
		$securityTable = new security(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $securityTable->getAdapter()->quoteInto('security_id = ?', $id);
			$securityTable->delete($where);
		}
	}
	
	function saveChiefReport($params) {
		$chiefTable = new chief_security_report(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"chief_spd" => $params["chief_spd"],
			"chief_army" => $params["chief_army"],
			"panwas_spd" => $params["panwas_spd"],
			"panwas_army" => $params["panwas_army"],
			"danton_pagi_spd" => $params["danton_pagi_spd"],
			"danton_pagi_army" => $params["danton_pagi_army"],
			"kekuatan_spd" => $params["kekuatan_spd"],
			"kekuatan_army" => $params["kekuatan_army"],
			"created_date" => date("Y-m-d H:i:s"),
			"user_id" => $params["user_id"]
		);
		if(empty($params['chief_security_report_id']))
		{
			$chiefTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['created_date']);
			unset($data['user_id']);
			$where = $chiefTable->getAdapter()->quoteInto('chief_security_report_id = ?', $params['chief_security_report_id']);
			$chiefTable->update($data, $where);
			return $params['chief_security_report_id'];
		}
	}

	function saveChiefReport2($params) {
		$chiefTable = new chief_security_report(array('db'=>'db'));
		
		$data = array(
			"sosialisasi_sop_a" => $params["sosialisasi_sop_a"],
			"sosialisasi_sop_b" => $params["sosialisasi_sop_b"],
			"sosialisasi_sop_c" => $params["sosialisasi_sop_c"]
		);

		$where = $chiefTable->getAdapter()->quoteInto('chief_security_report_id = ?', $params['chief_security_report_id']);
		$chiefTable->update($data, $where);
		return $params['chief_security_report_id'];
	}
	
	function getChiefSecurityReports($params) {
		$securityTable = new security(array('db'=>'db'));
		//$select = $securityTable->select()->where('site_id = ?', $this->site_id)->group('date(created_date)')->order('security_id desc')->limit($params['pagesize'],$params['start']);
		$select = $securityTable->getAdapter()->select();
		$select->from(array("s"=>"security"), array("s.*"));
		$select->joinLeft(array("c"=>"chief_security_report"), "c.chief_security_report_id = s.chief_security_report_id", array("c.user_id as chief_user"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.name"));
		$select->where('s.site_id = ?', $this->site_id);
		$select->group('date(s.created_date)');
		$select->order('s.security_id desc');
		$select->limit($params['pagesize'],$params['start']);
		$security = $securityTable->getAdapter()->fetchAll($select);
		/*$securityTable = new security(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select->from(array("sr"=>"security"), array("sr.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = cr.user_id", array("u.name"));
		$select->order('cr.chief_security_report_id desc');
		$select->limit($params['pagesize'],$params['start']);
		$security = $securityTable->getAdapter()->fetchAll($select);*/
		return $security;
	}
	
	function getChiefSecurityReportById($id) {
		$chiefTable = new chief_security_report(array('db'=>'db'));
		
		$select = $chiefTable->getAdapter()->select();
		$select->from(array("cs"=>"chief_security_report"), array("cs.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = cs.user_id", array("u.name"));
		$select->where('cs.chief_security_report_id = ?', $id);
		$security = $chiefTable->getAdapter()->fetchRow($select);
		return $security;
	}
	
	function addSpecificReport($params) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$data = array(
			"chief_security_report_id" => intval($params["chief_security_report_id"]),
			"issue_type" => intval($params["issue_type"]),
			"time" => $params["time"],
			"detail" => $params["detail"],
			"status" => $params["status"],
			"created_date" => date("Y-m-d H:i:s"),
			"security_id" =>  intval($params["security_id"]),
			"issue_id" => intval($params["issue_id"]),
			"area" => $params["area"],
			"site_id" => $this->site_id
		);
		if(empty($params["chief_security_report_id"])) {
			unset($data['chief_security_report_id']);
		}
		$specificReportTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteSpecificReportByChiefSecurityId($chief_security_report_id)
	{
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		if ( is_numeric($chief_security_report_id) && $chief_security_report_id > 0 )
		{		
			$where = $specificReportTable->getAdapter()->quoteInto('chief_security_report_id = ?', $chief_security_report_id);
			$specificReportTable->delete($where);
		}
	}
	
	function deleteSpecificReportBySecurityId($security_id)
	{
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		if ( is_numeric($security_id) && $security_id > 0 )
		{		
			$where = $specificReportTable->getAdapter()->quoteInto('security_id = ?', $security_id);
			$specificReportTable->delete($where);
		}
	}
	
	function getSpecificReportByChiefSecurityReport($chief_security_report_id) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->where('ssr.chief_security_report_id = ?', $chief_security_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getSpecificReportByIds($security_ids, $chief_security_report_id) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description"));
		$select->where('ssr.security_id in ('.$security_ids.')');
		if($chief_security_report_id > 0) $select->orwhere('ssr.chief_security_report_id = ?', $chief_security_report_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function deleteChiefSecurityReportById($id)
	{
		$chiefTable = new chief_security_report(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $chiefTable->getAdapter()->quoteInto('chief_security_report_id = ?', $id);
			$chiefTable->delete($where);
		}
	}
	
	function getSecurityReportByShift($date, $shift, $site_id) {
		$securityTable= new security(array('db'=>'db'));
		
		$select = $securityTable->getAdapter()->select();
		$select->from(array("s"=>"security"), array("s.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = s.user_id", array("u.name"));
		$select->where('s.shift = ?', $shift);
		$select->where('date(s.created_date) = ?', $date);
		$select->where('s.site_id = ?', $site_id);
		$security = $securityTable->getAdapter()->fetchRow($select);
		return $security;
	}
	
	function getGlitchByDate($date)
	{
		$securityTable= new security(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select = "select * from security_glitch sg left join security s  on sg.security_id = s.security_id where  date(created_date) = '".$date."'";
		$security_glitch = $securityTable->getAdapter()->fetchRow($select);
		return $security_glitch;
	}
	
	function getIncidentByDate($date)
	{
		$securityTable= new security(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select = "select * from security_incident sg left join security s  on sg.security_id = s.security_id where  date(created_date) = '".$date."'";
		$security_glitch = $securityTable->getAdapter()->fetchRow($select);
		return $security_glitch;
	}
	
	function getLostFound($date)
	{
		$securityTable= new security(array('db'=>'db'));
		$select = $securityTable->getAdapter()->select();
		$select = "select * from security_lost_found sg left join security s  on sg.security_id = s.security_id where  date(created_date) = '".$date."'";
		$security_glitch = $securityTable->getAdapter()->fetchRow($select);
		return $security_glitch;
	}
	
	function getDefectListByIds($ids) {
		$select = "select *, 4 as issue_type_id from security_defect_list where security_id in (".$ids.")";
		$issues = $this->db->fetchAll($select);
		return $issues;
	}
	
	function getSpecificReportBySecurityIdIssueType($security_id, $issue_type) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description"));
		$select->where('ssr.security_id = ?', $security_id);
		$select->where("ssr.issue_type = ?", $issue_type);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getTotalSpvReport($site_id) {
		$securityTable= new security(array('db'=>'db'));
		$select = "select count(*) as total from security where site_id = ".$site_id;
		$spvReports = $securityTable->getAdapter()->fetchRow($select);
		return $spvReports;
	}
	
	function getTotalChiefReport() {
		$chiefTable = new chief_security_report(array('db'=>'db'));
		$select = "select count(*) as total from chief_security_report where site_id = ".$this->site_id;
		$chiefReports = $chiefTable->getAdapter()->fetchRow($select);
		return $chiefReports;
	}
	
	function getSpecificReportByDate($date) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.issue_date","i.description","i.location","i.picture","i.solved_picture","i.solved_date"));
		$select->where('date(ssr.created_date) = ?', $date);
		$select->where('ssr.chief_security_report_id is not null');
		$select->where('site_id = ?', $this->site_id);
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getChiefSecurityReportByDate($date) {
		$chiefTable = new chief_security_report(array('db'=>'db'));
		
		$select = $chiefTable->getAdapter()->select();
		$select->from(array("cs"=>"chief_security_report"), array("cs.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = cs.user_id", array("u.name"));
		$select->where('date(cs.created_date) = ?', $date);
		$select->where('cs.site_id = ?', $this->site_id);
		$security = $chiefTable->getAdapter()->fetchRow($select);
		return $security;
	}
	
	function updateSpecificReportCompletionDate($id, $completion_date, $report_id, $fieldName) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$data = array(
			"completion_date" => $completion_date." 00:00:00",
			$fieldName => $report_id
		);

		$where = $specificReportTable->getAdapter()->quoteInto('specific_report_id = ?', $id);
		$specificReportTable->update($data, $where);
	}
	
	/* tampilin yg completion date-nya null atau yg completion datenya pas hari itu. 
	completion date field otomatis terisi dari solved date. 
	jika solved datenya terisi bukan tgl hari ini, itu berarti completion datenya masih kosong */
	function getUnsolvedIssuesByChiefSecurityReport($om_report_id, $report_date = "0000-00-00") { 
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id and i.site_id = ssr.site_id", array("i.issue_id", "i.solved_date", "i.picture", "i.solved_picture", "i.solved"));
		$select->where('ssr.site_id = ?', $this->site_id);
		$select->where('(date(i.solved_date) = "'.$report_date.'" AND (date(ssr.completion_date) = "0000-00-00" or ssr.completion_date is NULL or date(ssr.completion_date) = "'.$report_date.'")) OR ((i.solved_date is null OR date(i.solved_date) = "0000-00-00")  AND (date(ssr.completion_date) = "0000-00-00" or ssr.completion_date is NULL or date(ssr.completion_date) = "'.$report_date.'"))');
		$select->where('ssr.mod_report_id is null or ssr.mod_report_id = 0');
		if(!empty($om_report_id)) $select->orWhere('ssr.om_report_id = ?', $om_report_id);
		$select->order("ssr.completion_date desc");
		$select->order("ssr.specific_report_id desc");
		$select->limit("50");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	function getUnsolvedIssuesByChiefSecurityReport2($chief_security_report_id, $mod_report_id, $report_date = "0000-00-00") { 
		$specificReportTable = new security_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($mod_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('chief_security_report_id = ?', $chief_security_report_id);
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di security specific report table yang completion date-nya kosong atau yg completion-date-nya hari itu */
	function getOMUnsolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new security_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		if(!empty($om_report_id)) $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		else  $select->where('completion_date is null or date(completion_date) = "0000-00-00" or date(completion_date) = "'.$report_date.'"');
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('chief_security_report_id is not null or chief_security_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di security specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getOMSolvedSpecificReports($om_report_id, $report_date)
	{
		$specificReportTable = new security_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or om_report_id = '.$om_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('chief_security_report_id is not null or chief_security_report_id >0');
		$select->where('mod_report_id is null or mod_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}

	/* tampilin semua data yg ada di security specific report table yang completion-date-nya hari itu dan sesuai report id */
	function getMODSolvedSpecificReports($mod_report_id, $report_date)
	{
		$specificReportTable = new security_specific_report(array('db'=>'db'));

		$select = $specificReportTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->where('date(completion_date) = "'.$report_date.'" or mod_report_id = '.$mod_report_id);
		$select->where('issue_id = 0 or issue_id is null');
		$select->where('chief_security_report_id is not null or chief_security_report_id >0');
		$select->where('om_report_id is null or om_report_id=0');
		$select->order("completion_date desc");
		$select->order("specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function getSpecificReportByReport($fieldName, $report_id, $report_date) {
		$specificReportTable = new security_specific_report(array('db'=>'db'));
		
		$select = $specificReportTable->getAdapter()->select();
		$select->from(array("ssr"=>"security_specific_report"), array("ssr.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = ssr.issue_type", array("it.issue_type_id","it.issue_type as issue_type_name"));
		$select->joinLeft(array("i"=>"issues"), "i.issue_id = ssr.issue_id", array("i.solved_date", "i.picture", "i.solved_picture", "i.solved_picture", "i.solved"));
		$select->where('ssr.site_id = ?', $this->site_id);
		$select->where("ssr.".$fieldName." ='".$report_id."' or date(ssr.completion_date) = '".$report_date."' or date(i.solved_date) = '".$report_date."'");
		if($fieldName == "om_report_id") $select->where("ssr.mod_report_id is null or ssr.mod_report_id = 0");
		else $select->where("ssr.om_report_id is null or ssr.om_report_id = 0");
		$select->order("ssr.specific_report_id desc");
		$specific_reports = $specificReportTable->getAdapter()->fetchAll($select);
		return $specific_reports;
	}
	
	function addAttachment($data)
	{
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		
		$attachmentTable->insert($data);
	}
	
	/*** ATTACHMENT ***/
	
	function getSpvAttachmentById($attachment_id)
	{
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addSpvAttachment($params) {
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
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
	
	function deleteSpvAttachmentByReportId($report_id)
	{
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateSpvAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getSpvAttachments($report_id) {
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteSpvAttachmentById($id)
	{
		$attachmentTable = new spv_security_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
			return $id;
		}
	}
	
	
	function getChiefAttachmentById($attachment_id)
	{
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('attachment_id = ?', $attachment_id);
		$attachment = $attachmentTable->getAdapter()->fetchRow($select);
		return $attachment;
	}	
	
	function addChiefAttachment($params) {
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
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
	
	function deleteChiefAttachmentByReportId($report_id)
	{
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
		
		if ( is_numeric($report_id) && $report_id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('report_id = ?', $report_id);
			$attachmentTable->delete($where);
		}
	}
	
	function updateChiefAttachment($id, $fieldname, $filename)
	{
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
		$attachmentTable->update($data, $where);
	}
	
	function getChiefAttachments($report_id) {
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
		$select = $attachmentTable->select()->where('report_id = ?', $report_id);
		$attachments = $attachmentTable->getAdapter()->fetchAll($select);
		return $attachments;
	}
	
	function deleteChiefAttachmentById($id)
	{
		$attachmentTable = new chief_security_attachments(array('db'=>'db'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = array();
			$where[] = $attachmentTable->getAdapter()->quoteInto('attachment_id = ?', $id);
			$attachmentTable->delete($where);
		}
		return $id;
	}

	function getSecurityAllSitesByDate($date) {
		$securityTable= new security(array('db'=>'db'));
		
		$select = $securityTable->getAdapter()->select();
		$select->from(array("s"=>"security"), array("s.security_id", "s.site_id"));
		$select->where('date(s.created_date)  = ?', $date);
		$security = $securityTable->getAdapter()->fetchAll($select);
		return $security;
	}

	function getChiefSecurityAllSitesByDate($date) {
		$chiefSecurityTable= new chief_security_report(array('db'=>'db'));
		
		$select = $chiefSecurityTable->getAdapter()->select();
		$select->from(array("cs"=>"chief_security_report"), array("cs.chief_security_report_id", "cs.site_id"));
		$select->where('date(cs.created_date)  = ?', $date);
		$security = $chiefSecurityTable->getAdapter()->fetchAll($select);
		return $security;
	}

	function addReadSpvReportLog($params) {
		$spvReadReportLogTable = new security_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$spvReadReportLogTable->insert($data);		
	}

	function addReadChiefReportLog($params) {
		$chiefReadReportLogTable = new chief_security_read_report_log(array('db'=>'db'));
		
		$data = array(			
			"report_id" => $params["id"],
			"user_id" => $params["user_id"],
			"site_id" => $this->site_id,
			"read_datetime" => date("Y-m-d H:i:s")
		);
		$chiefReadReportLogTable->insert($data);		
	}

	function getReadReportLogByReportIdUser($report_id, $user_id) {
		$chiefReadReportLogTable = new chief_security_read_report_log(array('db'=>'db'));
		
		$select = $chiefReadReportLogTable->select()->where('report_id = ?', $report_id)->where('user_id = ?', $user_id);
		$report = $chiefReadReportLogTable->getAdapter()->fetchRow($select);
		return $report;	
	}

	function getSecurityReportByDateAndChief($report_date) {
		$securityTable= new security(array('db'=>'db'));
		
		$select = $securityTable->getAdapter()->select();
		$select = $securityTable->select()->where("date(created_date) = ?", $report_date)->where("chief_security_report_id > 0")->where('site_id = ?', $this->site_id);
		$security = $securityTable->getAdapter()->fetchRow($select);
		return $security;
	}

	function saveMonthlyAnalysis($params) {
		$monthlyAnalysisTable = new security_monthly_analysis(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"save_date" => date("Y-m-d H:i:s")
		);

		$monthlyAnalysisTable->insert($data);
		return $this->db->lastInsertId();
	}

	function getMonthlyAnalysis() {
		$monthlyAnalysisTable = new security_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->getAdapter()->select();
		$select->from(array("m"=>"security_monthly_analysis"), array("m.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = m.user_id", array("u.name"));
		$select->where('m.site_id = ?', $this->site_id);
		$select->order('monthly_analysis_id desc');
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchAll($select);
		return $monthlyanalysis;
	}

	function getTotalMonthlyAnalysis() {
		$monthlyAnalysisTable = new security_monthly_analysis(array('db'=>'db'));

		$select = "select count(*) as total from security_monthly_analysis where site_id =".$this->site_id;
		$monthlyAnalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyAnalysis['total'];
	}

	function getSecurityMonthlyAnalysisByMonthYear($m, $y) {
		$monthlyAnalysisTable = new security_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('month(save_date) = ?', $m)->where('year(save_date) = ?', $y);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}

	function geMonthlyAnalysisById($monthly_analysis_id) {
		$monthlyAnalysisTable = new security_monthly_analysis(array('db'=>'db'));
		$select = $monthlyAnalysisTable->select()->where('site_id = ?', $this->site_id)->where('monthly_analysis_id = ?', $monthly_analysis_id);
		$monthlyanalysis = $monthlyAnalysisTable->getAdapter()->fetchRow($select);
		return $monthlyanalysis;
	}
}
?>