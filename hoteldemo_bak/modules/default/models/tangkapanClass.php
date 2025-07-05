<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class tangkapanClass extends defaultClass
{	
	function getHousekeepingHasilTangkapan() {
		$selTable = new housekeeping_tangkapan(array('db'=>'db'));
		$select = $selTable->select()->where('site_id = ?', $this->site_id);
		$tangkapan = $selTable->getAdapter()->fetchAll($select);
		return $tangkapan;
	}
	
	function getHousekeepingHasilTangkapanByReportId($id) {		
		$tangkapanTable = new housekeeping_tangkapan(array('db'=>'db'));
		
		$select = $tangkapanTable->getAdapter()->select();
		$select->from(array("ht"=>"housekeeping_tangkapan"), array("ht.*"));
		$select->joinLeft(array("hht"=>"housekeeping_hasil_tangkapan"), "ht.tangkapan_id=hht.tangkapan_id and hht.housekeeping_report_id = ".$id, array("hht.hasil_tangkapan_id","hht.shift1","hht.shift2","hht.shift3"));
		$select->where('ht.site_id = ?', $this->site_id);
		$select->order("ht.hewan_tangkapan");
		$tangkapan = $tangkapanTable->getAdapter()->fetchAll($select);
		return $tangkapan;
	}
	
	function addHousekeepingTangkapan($params) {
		$tangkapanTable = new housekeeping_hasil_tangkapan(array('db'=>'db'));
		
		$data = array(
			"housekeeping_report_id" => $params["housekeeping_report_id"],
			"tangkapan_id" => $params["tangkapan_id"],
			"shift1" => $params["hasil_tangkapan_shift1"],
			"shift2" => $params["hasil_tangkapan_shift2"],
			"shift3" => $params["hasil_tangkapan_shift3"]
		);
		$tangkapanTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function deleteTangkapanByHousekeepingReportId($housekeeping_report_id)
	{
		$tangkapanTable = new housekeeping_hasil_tangkapan(array('db'=>'db'));
		
		if ( is_numeric($housekeeping_report_id) && $housekeeping_report_id > 0 )
		{		
			$where = $tangkapanTable->getAdapter()->quoteInto('housekeeping_report_id = ?', $housekeeping_report_id);
			$tangkapanTable->delete($where);
		}
	}
	
}
?>