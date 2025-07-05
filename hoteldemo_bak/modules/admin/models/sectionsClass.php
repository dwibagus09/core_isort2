<?php

require_once('adminClass.php');
require_once('dbClass.php');

class sectionsClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of sections on content_sections table
	 * @return array
	 */
	function getContentSections($site_id, $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$contentsectionsTable = new content_sections(array('db' => 'db_prod')); //use db object from registry
		} else {
			$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		}

		$select = $contentsectionsTable->select();
		/*if(!empty($this->site_group_id)) $select->where("site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}')", "");
		else */$select->where('site_id = ?', $site_id);
		
		$select->order("section_name");
		
		return $contentsectionsTable->getAdapter()->fetchAll($select);
	}
	
	function getSectionsForThisSite($site_id, $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$contentsectionsTable = new content_sections(array('db' => 'db_prod')); //use db object from registry
		} else {
			$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		}

		$select = $contentsectionsTable->select();
		$select->where('site_id = ?', $site_id);
		
		$select->order("section_name");
		
		return $contentsectionsTable->getAdapter()->fetchAll($select);
	}
	
	/**
	 * Inserts a new section
	 * 
	 * @param array $params
	 */
	function addSection($site_id, $params)
	{
		$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $site_id,
			'section_name'		=> $params['section_name'],
			'days_to_show'		=> $params['days_to_show'],
			'enable_rating'		=> $params['enable_rating'],
			'enable_comment'	=> $params['enable_comment']
		);
		$contentsectionsTable->insert($data);
		
		$sectionId = $contentsectionsTable->getAdapter()->lastInsertId();
		
		$this->addLog($sectionId, "Add", "Category", array(), $data, $data['section_name']);
	}
	
	/**
	 * Gets an array of section available in the currently selected id.
	 *
	 * @param int $section_id
	 * @return array
	 */
	function getSectionById($section_id)
	{		
		$contentsectionsTable = new content_sections(array('db' => 'db'));

		$select = $contentsectionsTable->select()
			->where('section_id = ?', $section_id);
			
		/*if(!empty($this->site_group_id)) $select->where("site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}')", "");
		else */$select->where('site_id = ?', $this->site_id);
		
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	/**
	 * updating section by section_id
	 *
	 * @param int $params
	 */
	function updateSection($params)
	{		
		$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		
		$select = $contentsectionsTable->select()->where("section_id=?", $params['section_id']);
		$oldData = $contentsectionsTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'section_name'		=> $params['section_name'],
			'days_to_show'		=> $params['days_to_show'],
			'enable_rating'		=> $params['enable_rating'],
			'enable_comment'	=> $params['enable_comment']
		);
		
		$where = array();
		$where[] = $contentsectionsTable->getAdapter()->quoteInto('section_id = ?', $params['section_id']);
		/*if(!empty($this->site_group_id)) $where[] = $contentsectionsTable->getAdapter()->quoteInto("site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}')", "");
		else */$where[] = $contentsectionsTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
		
		$contentsectionsTable->update($data, $where);	
		
		$this->addLog($params['section_id'], "Update", "Category", $oldData, $data, $data['section_name']);
	}
	
	/**
	 * Delete section with the provided section_id
	 * 
	 * @param int $section_id
	 */
	function deleteSections($section_id)
	{
		$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($section_id) && $section_id > 0 )
		{
			$select = $contentsectionsTable->select()->where("section_id=?", $section_id);
			$oldData = $contentsectionsTable->getAdapter()->fetchRow($select);
		
			$where = array();
			$where[] = $contentsectionsTable->getAdapter()->quoteInto('section_id = ?', $section_id);
			/*if(!empty($this->site_group_id)) $where[] = $contentsectionsTable->getAdapter()->quoteInto("site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}')", "");
			else */$where[] = $contentsectionsTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
			
			$contentsectionsTable->delete($where);
			
			if(!empty($oldData['section_id']))
				$this->addLog($section_id, "Delete", "Category", $oldData, array(), $oldData['section_name']);
		}
	}
	
	function checkCategoryExists($categoryName, $site_id) {
		$categoryName = addslashes(stripslashes($categoryName));
		$sql = "select section_id, section_name from content_sections where ";
		/*if(!empty($this->site_group_id)) $sql .= " site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}') ";
		else */$sql .= " site_id=" . $site_id;
		$sql .= " AND section_name='{$categoryName}'";
		
		$category = $this->db->fetchRow($sql);
		if(!empty($category['section_id'])) return true;
		else return false;
	}
	
	function getContentSectionsList($site_id)
	{
		$sql = "select section_id, section_name from content_sections where ";
		/*if(!empty($this->site_group_id)) $sql .= " site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}') ";
		else */$sql .= " site_id=" . $site_id;
		$sql .= " or section_id=0 order by section_name";
		
		return $this->db->fetchAll($sql);
	}
	
	public function migrateSections($site_id, $arrSections, $params)
	{
		$contentsectionsTable = new content_sections(array('db' => 'db_prod')); //use db object from registry
		$dataKeys = json_decode($params['data'], true);
		
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrSections as $thisData ) {
				$arrIndexed[$thisData['section_id']] = $thisData;
			}
			
			// Delete all of the existing data from the production environment (very dangerous)
			if(empty($params['donotremoveexisting'])) {
				$where = $contentsectionsTable->getAdapter()->quoteInto('site_id = ?', $site_id);
				$contentsectionsTable->delete($where);
			}
			
			foreach ($arrSections as $data)
			{
				if(!empty($params['donotremoveexisting'])) {
					$where = array();
					$where[] = $contentsectionsTable->getAdapter()->quoteInto('site_id = ?', $site_id);
					$where[] = $contentsectionsTable->getAdapter()->quoteInto('section_id = ?', $data['section_id']);
					$contentsectionsTable->delete($where);
				}
				$contentsectionsTable->insert($data);
			}
			
			/*foreach ($dataKeys as $dataToMigrate)
			{
				$dataDetail = $arrIndexed[$dataToMigrate['section_id']];
				
				$data = array();
				foreach ( $dataDetail as $dataIndex=>$dataValue )
				{
						$data[$dataIndex] = $dataValue;
				}
				print_r($data);
				$contentsectionsTable->insert($data);
			}
			exit();*/
		}
		return true;
	}
	
	public function migrateCopySections($site_id, $arrSections, $params)
	{
		$contentsectionsTable = new content_sections(array('db' => 'db')); //use db object from registry
		$dataKeys = json_decode($params['data'], true);
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrSections as $thisData ) {
				$arrIndexed[$thisData['section_id']] = $thisData;
			}
			
			// Get the max value for the primary key field, so new entries can be safely added past it.
			$select = $contentsectionsTable->select();
			$select->from($contentsectionsTable, array('max(section_id) as max'));
			$arrMaxResult = $this->db->fetchAll($select);
			
			$newkey = $arrMaxResult[0]['max'];

			if ( $newkey > 0 ) {
				foreach ($dataKeys as $dataToMigrate)
				{
					$dataDetail = $arrIndexed[$dataToMigrate['section_id']];
					$data = array();
					foreach ( $dataDetail as $dataIndex=>$dataValue )
					{
						$data[$dataIndex] = $dataValue;
					}
					// Override the primary key for this entry
					$newkey++;
					$data['section_id'] = $newkey;
						
					// Override the siteid value for this entry
					$data['site_id'] = $params['copySiteid'];
					
					try {
						$contentsectionsTable->insert($data);
					}
					catch (Exception $ex) {
						
					}
				}
			}
		}
		return true;
	}

}
?>