<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentsectionareagroupClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getContentSectionAreaGroup($site_id, $area_id)
	{
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db'));
		$select = $contentsectionareagroupTable->select()
			->where('site_id = ?', $site_id)
			->where('area_id = ?', $area_id)
			->order('display_order');
		return $contentsectionareagroupTable->getAdapter()->fetchAll($select);
	}
	
	function addContentSectionAreaGroup($site_id, $params)
	{
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db'));
		
		$data = array(
			'site_id'					=> $site_id,
			'area_id'					=> $params['area_id'],
			'title'						=> $params['title'],
			'display_order'				=> $params['display_order']
		);
		$contentsectionareagroupTable->insert($data);
	}
	
	function getContentSectionAreaGroupById($content_section_area_group_id)
	{		
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db'));
		
		$select = $contentsectionareagroupTable->select()
			->where('content_section_area_group_id = ?', $content_section_area_group_id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function updateContentSectionAreaGroup($params)
	{		
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db'));
		
		$data = array(
			'area_id'				=> $params['area_id'],
			'title'					=> $params['title'],
			'display_order'			=> $params['display_order']
		);
		$where = $contentsectionareagroupTable->getAdapter()->quoteInto('content_section_area_group_id = ?', $params['content_section_area_group_id']);
		$contentsectionareagroupTable->update($data, $where);			
	}
	
	function deleteContentSectionAreaGroup($content_section_area_group_id)
	{
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db'));
		
		if ( is_numeric($content_section_area_group_id) && $content_section_area_group_id > 0 )
		{
			$where = $contentsectionareagroupTable->getAdapter()->quoteInto('content_section_area_group_id = ?', $content_section_area_group_id);
			$contentsectionareagroupTable->delete($where);
		}
	}
	
	function migrateContentSectionAreaGroup($site_id, $arrCSAGroup)
	{
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db_prod'));
		
		if ( $site_id > 0 )
		{
			$where = $contentsectionareagroupTable->getAdapter()->quoteInto('content_section_area_group_id = ?', $arrCSAGroup['content_section_area_group_id']);
			$contentsectionareagroupTable->delete($where);
			try {
					$contentsectionareagroupTable->insert($arrCSAGroup);
			}
			catch (Exception $ex) {
				
			}		
		}
		return true;
	}
	
	function deleteContentSectionAreaGroupProd($area_id)
	{
		$contentsectionareagroupTable = new content_section_area_group(array('db' => 'db_prod'));
		
		if ( is_numeric($area_id) && $area_id > 0 )
		{
		
			$where = $contentsectionareagroupTable->getAdapter()->quoteInto('area_id = ?', $area_id);
			$contentsectionareagroupTable->delete($where);
		}
	}
}
?>