<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class trainingClass extends defaultClass
{	
	function getTrainingActivity() {
		$trainingActivityTable = new security_training_activity(array('db'=>'db'));
		$select = $trainingActivityTable->select()->where('site_id = ?', $this->site_id);
		$activity = $trainingActivityTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteTrainingByChiefSecurityId($chief_security_report_id)
	{
		$securityTrainingTable = new security_training(array('db'=>'db'));
		
		if ( is_numeric($chief_security_report_id) && $chief_security_report_id > 0 )
		{		
			$where = $securityTrainingTable->getAdapter()->quoteInto('chief_security_report_id = ?', $chief_security_report_id);
			$securityTrainingTable->delete($where);
		}
	}
	
	function addTraining($params) {
		$securityTrainingTable = new security_training(array('db'=>'db'));
		
		$data = array(
			"chief_security_report_id" => intval($params["chief_security_report_id"]),
			"training_type" => intval($params["training_type"]),
			"training_activity_id" => intval($params["training_activity"]),
			"description" => $params["description_training"]
		);
		$securityTrainingTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getSecurityTrainingByType($chief_security_report_id, $type_id) {
		$securityTrainingTable = new security_training(array('db'=>'db'));
		
		$select = $securityTrainingTable->getAdapter()->select();
		$select->from(array("st"=>"security_training"), array("st.*"));
		$select->joinLeft(array("sta"=>"security_training_activity"), "sta.training_activity_id = st.training_activity_id", array("sta.activity"));
		$select->where('st.chief_security_report_id = ?', $chief_security_report_id);
		$select->where('st.training_type = ?', $type_id);
		$activity = $securityTrainingTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function getSafetyTrainingActivity() {
		$trainingActivityTable = new safety_training_activity(array('db'=>'db'));
		$select = $trainingActivityTable->select();
		$activity = $trainingActivityTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteSafetyTrainingBySafetyReportId($safety_report_id)
	{
		$trainingTable = new safety_training(array('db'=>'db'));
		
		if ( is_numeric($safety_report_id) && $safety_report_id > 0 )
		{		
			$where = $trainingTable->getAdapter()->quoteInto('safety_report_id = ?', $safety_report_id);
			$trainingTable->delete($where);
		}
	}
	
	function addSafetyTraining($params) {
		$trainingTable = new safety_training(array('db'=>'db'));
		
		$data = array(
			"safety_report_id" => intval($params["safety_report_id"]),
			"training_type" => intval($params["training_type"]),
			"training_activity_id" => intval($params["training_activity"]),
			"description" => addslashes($params["description_training"]),
			"participant" => addslashes($params["participant_training"]),
			"remark" => addslashes($params["remark_training"]),
			"document" => addslashes($params["dokumen_training2"])
		);
		if(!empty($params["safety_training_id"])) $data['safety_training_id'] = $params["safety_training_id"];
		$trainingTable->insert($data);
		return $this->db->lastInsertId();
	}

	function updateTrainingDocument($id, $fieldname, $filename)
	{
		$trainingTable = new safety_training(array('db'=>'db'));
		
		$data = array(
			$fieldname => $filename
		);
		$where = $trainingTable->getAdapter()->quoteInto('safety_training_id = ?', $id);
		$trainingTable->update($data, $where);
	}
	
	function getSafetyTrainingByType($safety_report_id, $type_id) {
		$trainingTable = new safety_training(array('db'=>'db'));
		
		$select = $trainingTable->getAdapter()->select();
		$select->from(array("st"=>"safety_training"), array("st.*"));
		$select->joinLeft(array("sta"=>"safety_training_activity"), "sta.training_activity_id = st.training_activity_id", array("sta.activity"));
		$select->where('st.safety_report_id = ?', $safety_report_id);
		$select->where('st.training_type = ?', $type_id);
		$activity = $trainingTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteSafetyTrainingByReportId($safety_report_id)
	{
		$trainingTable = new safety_training(array('db'=>'db'));
		
		if ( is_numeric($safety_report_id) && $safety_report_id > 0 )
		{		
			$where = $trainingTable->getAdapter()->quoteInto('safety_report_id = ?', $safety_report_id);
			$trainingTable->delete($where);
		}
	}
	
	/*** PARKING TRAINING ***/
	
	function getParkingTrainingActivity() {
		$trainingActivityTable = new parking_training_activity(array('db'=>'db'));
		$select = $trainingActivityTable->select()->where('site_id = ?', $this->site_id);
		$activity = $trainingActivityTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteParkingTrainingByReportId($parking_report_id)
	{
		$trainingTable = new parking_training(array('db'=>'db'));
		
		if ( is_numeric($parking_report_id) && $parking_report_id > 0 )
		{		
			$where = $trainingTable->getAdapter()->quoteInto('parking_report_id = ?', $parking_report_id);
			$trainingTable->delete($where);
		}
	}
	
	function addParkingTraining($params) {
		$trainingTable = new parking_training(array('db'=>'db'));
		
		$data = array(
			"parking_report_id" => intval($params["parking_report_id"]),
			"training_type" => intval($params["training_type"]),
			"training_activity_id" => intval($params["training_activity"]),
			"description" => $params["description_training"]
		);
		$trainingTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getParkingTrainingByType($parking_report_id, $type_id) {
		$trainingTable = new parking_training(array('db'=>'db'));
		
		$select = $trainingTable->getAdapter()->select();
		$select->from(array("pt"=>"parking_training"), array("pt.*"));
		$select->joinLeft(array("pta"=>"parking_training_activity"), "pta.training_activity_id = pt.training_activity_id", array("pta.activity"));
		$select->where('pt.parking_report_id = ?', $parking_report_id);
		$select->where('pt.training_type = ?', $type_id);
		$activity = $trainingTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	/*** HOUSEKEEPING TRAINING ***/
	
	function getHousekeepingTraining($housekeeping_report_id) {
		$trainingTable = new housekeeping_training(array('db'=>'db'));
		$select = $trainingTable->select();
		$select->where('housekeeping_report_id = ?', $housekeeping_report_id);
		$activity = $trainingTable->getAdapter()->fetchAll($select);
		return $activity;
	}
	
	function deleteHousekeepingTrainingByReportId($housekeeping_report_id)
	{
		$trainingTable = new housekeeping_training(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_report_id) && $housekeeping_report_id > 0 )
		{		
			$where = $trainingTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_report_id);
			$trainingTable->delete($where);
		}
	}
	
	function addHousekeepingTraining($params) {
		$trainingTable = new housekeeping_training(array('db'=>'db'));
	
		$trainingTable->insert($params);
		return $this->db->lastInsertId();
	}
}
?>