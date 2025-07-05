<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentsectionareasClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of content areas on content_areas table
	 * @return array
	 */
	function getContentSectionAreas($site_id, $area_id)
	{
		$sql = "select csa.content_section_area_id, csa.section_id, cs.section_name, csa.area_id, csa.custom_title, csa.custom_url, 
				csa.display_order as d_order, csa.enable_column, csag.title as group_name, csa.enable_submenu
				from content_section_area csa
				left join content_sections cs on cs.section_id = csa.section_id
				left join content_section_area_group csag on csag.content_section_area_group_id = csa.content_section_area_group_id
				where csa.site_id = ".$site_id." and csa.area_id = ".$area_id." ORDER BY csa.custom_title ASC";

		return $this->db->fetchAll($sql);
	}
	
	/**
	 * Inserts a new content area
	 * 
	 * @param array $params
	 */
	function addContentSectionArea($site_id, $params)
	{
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		$data = array(
			'site_id'				=> $site_id,
			'section_id'			=> $params['section_id'],
			'area_id'				=> $params['area_id'],
			'custom_title'			=> $params['custom_title'],
			'custom_url'			=> $params['custom_url'],
			'display_order'			=> $params['display_order'],
			'template'				=> $params['template'],
			'custom_page_content'	=> $params['custom_page_content'],
			'column_order'			=> $params['submenu_column_order'],
			'enable_column'			=> $params['submenu_enable_column'],
			'content_section_area_type_id'	=> $params['content_section_area_type_id'],
			'group_name'			=> $params['group_name'],
			'gallery_keywords'		=> $params['gallery_keywords'],
			'group_order'			=> intval($params['group_order']),
			'line_below_gallery'			=> $params['line_below_gallery'],
			'content_section_area_group_id'	=> intval($params['content_section_area_group_id']),
			'pdf_file'				=> $params['pdf_file'],
			'enable_submenu'		=> $params['enable_submenu']
		);
		$contentsectionareaTable->insert($data);
		
		$csaId = $contentsectionareaTable->getAdapter()->lastInsertId();
		
		$this->resortSubMenu($csaId, $params['area_id'], $params['display_order']);
		
		$this->addLog($csaId, "Add", "Submenu", array(), $data, $params['section_id']."-".$params['custom_title']);
		return $csaId;
	}
	
	/**
	 * Gets an array of content section area available in the currently selected id.
	 *
	 * @param int $content_section_area_id
	 * @return array
	 */
	function getContentSectionAreaById($content_section_area_id)
	{		
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		$select = $contentsectionareaTable->select()
			->where('content_section_area_id = ?', $content_section_area_id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	/**
	 * updating content section area by content_section_area_id
	 *
	 * @param int $params
	 */
	function updateContentSectionArea($params)
	{		
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		$select = $contentsectionareaTable->select()->where("content_section_area_id=?", $params['content_section_area_id']);
		$oldData = $contentsectionareaTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'section_id'			=> $params['section_id'],
			'custom_title'			=> $params['custom_title'],
			'custom_url'			=> $params['custom_url'],
			'display_order'			=> $params['display_order'],
			'template'				=> $params['template'],
			'custom_page_content'	=> $params['custom_page_content'],
			'column_order'			=> $params['submenu_column_order'],
			'enable_column'			=> $params['submenu_enable_column'],
			'content_section_area_type_id'	=> $params['content_section_area_type_id'],
			'group_name'			=> $params['group_name'],
			'gallery_keywords'		=> $params['gallery_keywords'],
			'group_order'			=> intval($params['group_order']),
			'modify_date_time'		=> date("Y-m-d H:i:s"),
			'content_section_area_group_id'	=> intval($params['content_section_area_group_id']),
			'enable_submenu'		=> $params['enable_submenu'],
			'line_below_gallery'	=> $params['line_below_gallery'],
		);
		$where = $contentsectionareaTable->getAdapter()->quoteInto('content_section_area_id = ?', $params['content_section_area_id']);
		$contentsectionareaTable->update($data, $where);	
		
		$select = $contentsectionareaTable->select()->where("site_id=?", $this->site_id)->where("content_section_area_id=?", $params['content_section_area_id']);
		$csa = $contentsectionareaTable->getAdapter()->fetchRow($select);
		
		$this->resortSubMenu($params['content_section_area_id'], $csa['area_id'], $params['display_order']);
		
		$this->addLog($params['content_section_area_id'], "Update", "Submenu", $oldData, $data, $params['section_id']."-".$params['custom_title']);
		return $params['content_section_area_id'];
	}
	
	function resortSubMenu($csaId, $curAreaId, $curOrderId) {
		$curOrderId = intval($curOrderId);
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		$select = $contentsectionareaTable->select()->where("site_id=?", $this->site_id)->where("area_id = ?", $curAreaId)->where("content_section_area_id<>?", $csaId)->where("display_order>=?", $curOrderId)->order(array("display_order"));
		$areas = $contentsectionareaTable->getAdapter()->fetchAll($select);
		if(!empty($areas) && !empty($areas[0]) && !empty($areas[0]['content_section_area_id']) && $areas[0]['display_order']==$curOrderId) {
			foreach ($areas as $area) {
				if($area['display_order']<=$curOrderId) {
					$where = array();
					$where[] = $contentsectionareaTable->getAdapter()->quoteInto("site_id=?", $this->site_id);
					$where[] = $contentsectionareaTable->getAdapter()->quoteInto("content_section_area_id=?", $area['content_section_area_id']);
					$contentsectionareaTable->update(array(
						"display_order"		=> ($curOrderId+1),
					), $where);
				}
				$curOrderId++;
			}
		}
	}
	
	/**
	 * Delete content section area with the provided content_section_area_id
	 * 
	 * @param int $content_section_area_id
	 */
	function deleteContentSectionArea($content_section_area_id)
	{
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		if ( is_numeric($content_section_area_id) && $content_section_area_id > 0 )
		{
			$select = $contentsectionareaTable->select()->where("content_section_area_id=?", $content_section_area_id);
			$oldData = $contentsectionareaTable->getAdapter()->fetchRow($select);
		
			$where = $contentsectionareaTable->getAdapter()->quoteInto('content_section_area_id = ?', $content_section_area_id);
			$contentsectionareaTable->delete($where);
			
			if(!empty($oldData['content_section_area_id']))
				$this->addLog($content_section_area_id, "Delete", "Submenu", $oldData, array(), $oldData['custom_title']);
		}
	}
	
	/**
	 * Delete content section area with the provided area_id
	 * 
	 * @param int $area_id
	 */
	function deleteContentSectionAreaByAreaId($area_id)
	{
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		if ( is_numeric($area_id) && $area_id > 0 )
		{
			$where = $contentsectionareaTable->getAdapter()->quoteInto('area_id = ?', $area_id);
			$contentsectionareaTable->delete($where);
		}
	}
	
	/**
	 * Gets an array of content section areas on content_section_areas table
	 * @return array
	 */
	function getContentSectionAreaList($site_id)
	{
		$sql = "Select csa.content_section_area_id,  csa.custom_title, cs.section_name
				from content_section_area csa
				left join content_sections cs on csa.section_id = cs.section_id
				where csa.site_id = ".$site_id." order by csa.custom_title";

		return $this->db->fetchAll($sql);
	}
	
	function getContentSectionAreaType()
	{		
		$contentsectionareatypeTable = new content_section_area_type(array('db' => 'db'));
		
		$select = $contentsectionareatypeTable->select();
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}
	
	function deleteContentSectionAreaBySiteId($site_id, $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$contentsectionareaTable = new content_section_area(array('db' => 'db_prod')); //use db object from registry
		} else {
			$contentsectionareaTable = new content_section_area(array('db' => 'db')); //use db object from registry
		}
		
		if ( is_numeric($site_id) && $site_id > 0 )
		{
			$where = $contentsectionareaTable->getAdapter()->quoteInto('site_id = ?', $site_id);
			$contentsectionareaTable->delete($where);
		}
	}
	
	function addContentSectionAreaProd($site_id, $params)
	{
		$contentsectionareaTable = new content_section_area(array('db' => 'db_prod'));
		
		$data = array(
			'site_id'				=> $site_id,
			'content_section_area_id' => $params['content_section_area_id'],
			'section_id'			=> $params['section_id'],
			'area_id'				=> $params['area_id'],
			'custom_title'			=> $params['custom_title'],
			'custom_url'			=> $params['custom_url'],
			'display_order'			=> $params['display_order'],
			'template'				=> $params['template'],
			'custom_page_content'	=> $params['custom_page_content'],
			'column_order'			=> $params['column_order'],
			'enable_column'			=> $params['enable_column'],
			'content_section_area_type_id'	=> $params['content_section_area_type_id'],
			'group_name'			=> $params['group_name'],
			'group_order'			=> intval($params['group_order']),
			'modify_date_time'		=> $params['modify_date_time'],
			'gallery_keywords'		=> $params['gallery_keywords'],
			'line_below_gallery'	=> $params['line_below_gallery'],
			'content_section_area_group_id'		=> intval($params['content_section_area_group_id']),
			'pdf_file'				=> $params['pdf_file'],
			'enable_submenu'		=> $params['enable_submenu']
		);
		$contentsectionareaTable->insert($data);
	}
	
	function deleteContentSectionAreaProd($area_id)
	{
		$contentsectionareaTable = new content_section_area(array('db' => 'db_prod'));
		
		if ( is_numeric($area_id) && $area_id > 0 )
		{
		
			$where = $contentsectionareaTable->getAdapter()->quoteInto('area_id = ?', $area_id);
			$contentsectionareaTable->delete($where);
		}
	}
	
	function getContentSectionAreaByAreaId($site_id, $area_id)
	{		
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		
		$select = $contentsectionareaTable->select()
			->where('site_id = ?', $site_id)
			->where('area_id = ?', $area_id);
			
		$rs = $this->db->fetchAll($select);
		return $rs;	
	}
	
	function update($data) {
		$contentsectionareaTable = new content_section_area(array('db' => 'db'));
		$where = array();
		$where[] = $contentsectionareaTable->getAdapter()->quoteInto("content_section_area_id=?", $data["content_section_area_id"]);
		$contentsectionareaTable->update($data, $where);
	}
}
?>