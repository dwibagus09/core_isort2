<?php

require_once('adminClass.php');
require_once('dbClass.php');

class sectionfrontlookandfeelClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function addLookandFeel($site_id, $params)
	{
		$sfLookandFeelTable = new sectionfront_lookandfeel(array('db' => 'db'));
		
		$data = array(
			'site_id'					=> $site_id,
			'content_section_area_id'	=> $params['content_section_area_id'],
			'section_id'				=> $params['section_id'], 
			'logo_url'					=> $params['logo_url'],
			'title_bg_color'			=> $params['title_bg_color'],
			'title_font_color'			=> $params['title_font_color'],
			'line_color'				=> $params['line_color']
		);
		$sfLookandFeelTable->insert($data);
		
		$csaId = $sfLookandFeelTable->getAdapter()->lastInsertId();
		
		$this->addLog($csaId, "Add", "Submenu", array(), $data, $params['section_id']."-".$params['custom_title']);
		return $csaId;
	}
	
	function update($siteId, $data) {
		$sfLookandFeelTable = new sectionfront_lookandfeel(array('db' => 'db'));
		$where = array();
		$where[] = $sfLookandFeelTable->getAdapter()->quoteInto("site_id=?", $siteId);
		$where[] = $sfLookandFeelTable->getAdapter()->quoteInto("sectionfront_lookandfeel_id=?", $data["sectionfront_lookandfeel_id"]);
		$sfLookandFeelTable->update($data, $where);
	}
	
	function getLookandFeelById($sectionfront_lookandfeel_id)
	{
		$sfLookandFeelTable = new sectionfront_lookandfeel(array('db' => 'db'));
		$select = $sfLookandFeelTable->select()
		->where('sectionfront_lookandfeel_id = ?', $sectionfront_lookandfeel_id);
		$rs = $this->db->fetchRow($select);

		return $rs;
	}
	
	function getLookandFeelByContentSectionAreaId($content_section_area_id)
	{
		$sfLookandFeelTable = new sectionfront_lookandfeel(array('db' => 'db'));
		$select = $sfLookandFeelTable->select()
		->where('content_section_area_id = ?', $content_section_area_id);
		$rs = $this->db->fetchRow($select);

		return $rs;
	}
}
?>