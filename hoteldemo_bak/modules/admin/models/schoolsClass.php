<?php

require_once('adminClass.php');
require_once('dbClass.php');

class schoolsClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of sections on s table
	 * @return array
	 */
	function getSchools($site_id, $params = array(), $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$schoolTable = new schools(array('db' => 'db_prod')); //use db object from registry
		} else {
			$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		}

		if(!empty($params['keyword'])) $params['keyword'] = addslashes(stripslashes($params['keyword']));
		$sql = "
			SELECT s.*, gc.class_name
			FROM schools s
			LEFT JOIN game_classes gc ON gc.game_class_id=s.game_class_id
			WHERE s.site_id='{$site_id}' ".((!empty($params['keyword']))?" AND (s.school_name LIKE '%{$params['keyword']}%' OR s.location LIKE '%{$params['keyword']}%' OR s.location_address LIKE '%{$params['keyword']}%') ":"");
		
		if(!empty($params['ids']))
			$sql = $sql." and s.school_id in(".$params['ids'].")";
		
		$sql = $sql." ORDER BY school_name";
		
		$schools = $schoolTable->getAdapter()->fetchAll($sql);
		
		return $schools;
	}
	
	function getSchoolsForThisSite($site_id, $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$schoolTable = new schools(array('db' => 'db_prod')); //use db object from registry
		} else {
			$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		}

		$schools = $schoolTable->getAdapter()->fetchAll("
			SELECT s.*, gc.class_name
			FROM schools s
			LEFT JOIN game_classes gc ON gc.game_class_id=s.game_class_id
			WHERE s.site_id='{$site_id}'
			ORDER BY school_name		
		");
		
		return $schools;
	}
	
	/**
	 * Inserts a new section
	 * 
	 * @param array $params
	 */
	function addSchool($site_id, $params)
	{
		$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $site_id,
			'school_name'		=> substr($params['school_name'], 0, 500),
			'location'			=> substr($params['location'], 0, 1000),
			'location_address'	=> substr($params['location_address'], 0, 1000),
			'game_class_id'		=> $params['game_class_id'],
			'school_image'		=> '',
			'division'			=> substr($params['division'], 0, 500),
			'district'			=> substr($params['district'], 0, 500),
			'region'			=> substr($params['region'], 0, 500),
			'modified_date'		=> date("Y-m-d H:i:s"),
			'modified_by'		=> $this->ident["adminuserid"],
		);
		$schoolTable->insert($data);
		
		$schoolId = $schoolTable->getAdapter()->lastInsertId();
		
		if(!empty($params['location']) && !empty($params['location_address'])) {
			$params['location'] = addslashes(stripslashes($params['location']));
			$location = $schoolTable->getAdapter()->fetchRow("SELECT * FROM game_locations WHERE site_id='{$site_id}' AND location='{$params['location']}'");
			if(empty($location)) {
				$schoolTable->getAdapter()->insert("game_locations", array(
					"site_id"	=> $site_id,
					"location"	=> stripslashes($params['location']),
					"address"	=> $params['location_address']
				));
			}
			else {
				$where = array();
				$where[] = $schoolTable->getAdapter()->quoteInto('site_id = ?', $site_id);
				$where[] = $schoolTable->getAdapter()->quoteInto('location = ?', $params['location']);
				$schoolTable->getAdapter()->update("game_locations", array(
					"address"	=> $params['location_address']
				), $where);
			}
		}
		
		$this->addLog($schoolId, "Add", "School", array(), $data, $data['school_name']);
		
		return $schoolId;
	}
	
	/**
	 * Gets an array of section available in the currently selected id.
	 *
	 * @param int $school_id
	 * @return array
	 */
	function getSchoolById($school_id)
	{		
		$schoolTable = new schools(array('db' => 'db'));

		$school = $schoolTable->getAdapter()->fetchRow("
			SELECT s.*, gc.class_name
			FROM schools s
			LEFT JOIN game_classes gc ON gc.game_class_id=s.game_class_id
			WHERE s.school_id='{$school_id}'		
		");
		
		return $school;	
	}
	
	/**
	 * updating section by school_id
	 *
	 * @param int $params
	 */
	function updateSchool($params)
	{		
		$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		
		$select = $schoolTable->select()->where("school_id=?", $params['school_id']);
		$oldData = $schoolTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'school_name'		=> substr($params['school_name'], 0, 500),
			'location'			=> substr($params['location'], 0, 1000),
			'location_address'	=> substr($params['location_address'], 0, 1000),
			'game_class_id'		=> $params['game_class_id'],
			'school_image'		=> $params['school_image'],
			'division'			=> substr($params['division'], 0, 500),
			'district'			=> substr($params['district'], 0, 500),
			'region'			=> substr($params['region'], 0, 500),
			'modified_date'		=> date("Y-m-d H:i:s"),
			'modified_by'		=> $this->ident["adminuserid"],
		);
		
		$where = array();
		$where[] = $schoolTable->getAdapter()->quoteInto('school_id = ?', $params['school_id']);
		$where[] = $schoolTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
		
		$schoolTable->update($data, $where);	
		
		if(!empty($params['location']) && !empty($params['location_address'])) {
			$params['location'] = addslashes(stripslashes($params['location']));
			$location = $schoolTable->getAdapter()->fetchRow("SELECT * FROM game_locations WHERE site_id='{$this->site_id}' AND location='{$params['location']}'");
			if(empty($location)) {
				$schoolTable->getAdapter()->insert("game_locations", array(
					"site_id"	=> $this->site_id,
					"location"	=> stripslashes($params['location']),
					"address"	=> $params['location_address']
				));
			}
			else {
				$where = array();
				$where[] = $schoolTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
				$where[] = $schoolTable->getAdapter()->quoteInto('location = ?', $params['location']);
				$schoolTable->getAdapter()->update("game_locations", array(
					"address"	=> $params['location_address']
				), $where);
			}
		}
		
		$this->addLog($params['school_id'], "Update", "School", $oldData, $data, $data['school_name']);
	}
	
	/**
	 * Delete section with the provided school_id
	 * 
	 * @param int $school_id
	 */
	function deleteSchools($school_id)
	{
		$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($school_id) && $school_id > 0 )
		{
			$select = $schoolTable->select()->where("school_id=?", $school_id);
			$oldData = $schoolTable->getAdapter()->fetchRow($select);
		
			$where = array();
			$where[] = $schoolTable->getAdapter()->quoteInto('school_id = ?', $school_id);
			$where[] = $schoolTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
			
			$schoolTable->delete($where);
			
			if(!empty($oldData['school_id']))
				$this->addLog($school_id, "Delete", "School", $oldData, array(), $oldData['school_name']);
		}
	}
	
	function checkSchoolExists($schoolName, $site_id) {
		$schoolName = addslashes(stripslashes($schoolName));
		$sql = "SELECT school_id, school_name FROM schools WHERE ";
		$sql .= " site_id=" . $site_id;
		$sql .= " AND school_name='{$schoolName}'";
		
		$school = $this->db->fetchRow($sql);
		if(!empty($school['school_id'])) return true;
		else return false;
	}
	
	function getSchoolsList($site_id)
	{
		$sql = "SELECT * FROM schools WHERE ";
		$sql .= " site_id=" . $site_id;
		$sql .= " ORDER BY school_name";
		
		return $this->db->fetchAll($sql);
	}
	
	public function migrateSchools($site_id, $arrSchools, $params)
	{
		$schoolTable = new schools(array('db' => 'db_prod')); //use db object from registry
		$dataKeys = json_decode($params['data'], true);
		
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrSchools as $thisData ) {
				$arrIndexed[$thisData['school_id']] = $thisData;
			}
			
			if(empty($params['donotremoveexisting'])) {
				// Delete all of the existing data from the production environment (very dangerous)
				$where = $schoolTable->getAdapter()->quoteInto('site_id = ?', $site_id);
				$schoolTable->delete($where);
			}
			
			foreach ($arrSchools as $data)
			{
				if(!empty($params['donotremoveexisting'])) {
					$where = array();
					$where[] = $schoolTable->getAdapter()->quoteInto('site_id = ?', $site_id);
					$where[] = $schoolTable->getAdapter()->quoteInto('school_id = ?', $data['school_id']);
					$schoolTable->delete($where);
				}
				
				unset($data['class_name']);
				$schoolTable->insert($data);
				
				$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/game";
				$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/game";
				if(!is_dir($destDir)) mkdir($destDir);
				if(!empty($data['school_image'])) 
					@copy($srcDir.'/'.$data['school_image'], $destDir.'/'.$data['school_image']);
			}
			
		}
		return true;
	}
	
	public function migrateCopySchools($site_id, $arrSchools, $params)
	{
		$schoolTable = new schools(array('db' => 'db')); //use db object from registry
		$dataKeys = json_decode($params['data'], true);
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrSchools as $thisData ) {
				$arrIndexed[$thisData['school_id']] = $thisData;
			}
			
			// Get the max value for the primary key field, so new entries can be safely added past it.
			$select = $schoolTable->select();
			$select->from($schoolTable, array('max(school_id) as max'));
			$arrMaxResult = $this->db->fetchAll($select);
			
			$newkey = $arrMaxResult[0]['max'];

			if ( $newkey > 0 ) {
				foreach ($dataKeys as $dataToMigrate)
				{
					$dataDetail = $arrIndexed[$dataToMigrate['school_id']];
					$data = array();
					foreach ( $dataDetail as $dataIndex=>$dataValue )
					{
						$data[$dataIndex] = $dataValue;
					}
					// Override the primary key for this entry
					$newkey++;
					$data['school_id'] = $newkey;
					
					$className = $schoolTable->getAdapter()->fetchOne("SELECT class_name FROM game_classes WHERE site_id='{$site_id}' AND game_class_id='{$data['game_class_id']}'");
					$classId = $schoolTable->getAdapter()->fetchOne("SELECT game_class_id FROM game_classes WHERE site_id='{$params['copySiteid']}' AND class_name='{$className}'");
					$data['game_class_id'] = intval($classId);
						
					// Override the siteid value for this entry
					$data['site_id'] = $params['copySiteid'];
					
					try {
						unset($data['class_name']);
						$schoolTable->insert($data);
					}
					catch (Exception $ex) {
						
					}
				}
			}
		}
		return true;
	}
	
	function update($siteId, $data) {
		$schoolTable = new schools(array('db' => 'db'));
		
		$where = array();
		$where[] = $schoolTable->getAdapter()->quoteInto("site_id=?", $siteId);
		$where[] = $schoolTable->getAdapter()->quoteInto("school_id=?", $data["school_id"]);
		$schoolTable->update($data, $where);
	}

}
?>