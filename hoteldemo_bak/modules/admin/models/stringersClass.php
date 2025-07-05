<?php

require_once('adminClass.php');
require_once('dbClass.php');

class stringersClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of stringers on stringer_users table
	 * @return array
	 */
	function getStringers($site_id, $params = array(), $useProductionDatabase=false)
	{
		if ( $useProductionDatabase == true ) {
			$stringerTable = new stringer_users(array('db' => 'db_prod')); //use db object from registry
		} else {
			$stringerTable = new stringer_users(array('db' => 'db')); //use db object from registry
		}

		/*$select = $stringerTable->select()
			->where('site_id = ?', $site_id)->order(array("stringer_username"));*/
		$startDate = date("Y-m-")."01";
		$endDate = date("Y-m-t");
		$select = "
			SELECT su.*, 
				COALESCE(SUM(price_charged), 0) AS this_month_sales, 
				COALESCE(SUM(quantity), 0) AS this_month_qty, 
				COALESCE(SUM(profit), 0) AS this_month_profit
			FROM stringer_users su
			LEFT JOIN smugmug_sales ss ON ss.stringer_user_id=su.stringer_user_id AND ss.order_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59' AND ss.smugmug_type='Sale'
			WHERE su.site_id='{$site_id}' ";
		if(!empty($params['ids'])) $select .= " AND su.stringer_user_id IN ({$params['ids']}) ";
		$select .= "
			GROUP BY su.stringer_user_id
			ORDER BY su.stringer_username
		";
		
		return $stringerTable->getAdapter()->fetchAll($select);
	}
	
	/**
	 * Inserts a new stringer
	 * 
	 * @param array $params
	 */
	function addStringer($site_id, $params)
	{
		$stringerTable = new stringer_users(array('db' => 'db'));
		
		$data = array(
			'site_id'			=> $site_id,
			'stringer_username'	=> $params['stringer_username'],
			'stringer_password'	=> $params['stringer_password'],
			'stringer_fullname'	=> $params['stringer_fullname'],
			'created_date'		=> date("Y-m-d H:i:s"),
			'staff_code'		=> substr($params['staff_code'],0,4),
			'commission_percentage'	=> intval($params['commission_percentage']),
			'total_sales'			=> floatval($params['total_sales']),
			'total_qty'				=> intval($params['total_qty']),
			'original_sales'		=> floatval($params['original_sales']),
			'original_qty'			=> intval($params['original_qty']),
		);
		$stringerTable->insert($data);
		
		$sId = $stringerTable->getAdapter()->lastInsertId();
		$this->addLog($sId, "Add", "Stringer", array(), $data, $params['stringer_username']);
	}
	
	/**
	 * Gets an array of stringer available in the currently selected id.
	 *
	 * @param int $stringerId
	 * @return array
	 */
	function getStringer($stringerId)
	{		
		$stringerTable = new stringer_users(array('db' => 'db'));

		$select = $stringerTable->select()
			->where('stringer_user_id = ?', $stringerId)
			->where('site_id=?', $this->site_id);
		$stringer = $stringerTable->getAdapter()->fetchRow($select);
		return $stringer;
	}
	
	/**
	 * updating section by section_id
	 *
	 * @param int $params
	 */
	function updateStringer($params)
	{		
		$stringerTable = new stringer_users(array('db' => 'db'));
		
		$select = $stringerTable->select()->where("stringer_user_id=?", $params['stringer_user_id']);
		$oldData = $stringerTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'stringer_username'	=> $params['stringer_username'],
			'stringer_password'	=> $params['stringer_password'],
			'stringer_fullname'	=> $params['stringer_fullname'],
			'staff_code'		=> substr($params['staff_code'],0,4),
			'commission_percentage'	=> intval($params['commission_percentage']),
			'total_sales'			=> floatval($params['total_sales']),
			'total_qty'				=> intval($params['total_qty']),
			'original_sales'		=> floatval($params['original_sales']),
			'original_qty'			=> intval($params['original_qty']),
		);
		$where = array();
		$where[] = $stringerTable->getAdapter()->quoteInto('stringer_user_id = ?', $params['stringer_user_id']);
		$where[] = $stringerTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
		$stringerTable->update($data, $where);	
		
		$this->addLog($params['stringer_user_id'], "Update", "Stringer", $oldData, $data, $data['stringer_username']);
	}
	
	/**
	 * Delete user with the provided stringer_user_id
	 * 
	 * @param int $stringerId
	 */
	function deleteStringers($stringerId)
	{
		$stringerTable = new stringer_users(array('db' => 'db'));
		
		if ( is_numeric($stringerId) && $stringerId > 0 )
		{
			$select = $stringerTable->select()->where("stringer_user_id=?", $stringerId);
			$oldData = $stringerTable->getAdapter()->fetchRow($select);
		
			$where = array();
			$where[] = $stringerTable->getAdapter()->quoteInto('stringer_user_id = ?', $stringerId);
			$where[] = $stringerTable->getAdapter()->quoteInto('site_id = ?', $this->site_id);
			$stringerTable->delete($where);
			
			if(!empty($oldData['stringer_user_id']))
				$this->addLog($stringerId, "Delete", "Stringer", $oldData, array(), $oldData['stringer_username']);
		}
	}
	
	public function migrateStringers($site_id, $arrAdminUsers, $params)
	{		
		if (!empty($arrAdminUsers))
		{
			$stringerTable = new stringer_users(array('db' => 'db_prod'));
			if(empty($params['donotremoveexisting'])) {
				// Delete all of the existing data from the production environment (very dangerous)
				$where = $stringerTable->getAdapter()->quoteInto('site_id = ?', $site_id);
				$stringerTable->delete($where);			
			}
			foreach ($arrAdminUsers as $dataToMigrate)
			{
				if(!empty($params['donotremoveexisting'])) {
					$where = array();
					$where[] = $stringerTable->getAdapter()->quoteInto('site_id = ?', $site_id);
					$where[] = $stringerTable->getAdapter()->quoteInto('stringer_username = ?', $dataToMigrate['stringer_username']);
					$stringerTable->delete($where);
				}
				try {
					unset($dataToMigrate['stringer_user_id']);
					unset($dataToMigrate['this_month_sales']);
					unset($dataToMigrate['this_month_qty']);
					unset($dataToMigrate['this_month_profit']);
					$stringerTable->insert($dataToMigrate);
				}
				catch (Exception $ex) {
				}
			}
			return true;
		}		
	}
	
	public function migrateCopyStringers($site_id, $arrAdminUsers, $params)
	{
		$stringerTable = new stringer_users(array('db' => 'db'));
		
		if ( $site_id > 0 && !empty($arrAdminUsers))
		{
			$siteName = $adminusersTable->getAdapter()->fetchOne("SELECT `name` FROM sites WHERE site_id='{$params['copySiteid']}'");
			
			foreach ($arrAdminUsers as $dataToMigrate)
			{
				$dataToMigrate['site_id'] = $params['copySiteid'];
				unset($dataToMigrate['stringer_user_id']);
				unset($dataToMigrate['this_month_sales']);
				unset($dataToMigrate['this_month_qty']);
				unset($dataToMigrate['this_month_profit']);
				try {
					$stringerTable->insert($dataToMigrate);
				}
				catch (Exception $ex) {
				}
			}
		}
		return true;
	}
	
	function getAssignments($siteId, $params) {
		$params['start'] = intval($params['start']);
		$params['limit'] = intval($params['limit']);
		$siteId = intval($siteId);
		$assignmentTable = new stringer_galleries(array('db'=>'db'));
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS sg.stringer_gallery_id, sg.site_id, sg.content_gallery_id, sg.created_date, 
			cg.content_gallery_type_id, cg.keywords, cg.content_gallery, cg.show_gallery_in_multimedia, cg.for_sale,
			cg.smugmug_id, cg.smugmug_key, cg.smugmug_categoryid, cgt.content_gallery_type, GROUP_CONCAT(DISTINCT su.stringer_username ORDER BY su.stringer_username ASC SEPARATOR ', ') AS users,
			GROUP_CONCAT(DISTINCT cgs.site_id SEPARATOR ',') AS sites
		FROM stringer_galleries sg
		LEFT JOIN content_gallery cg ON cg.content_gallery_id=sg.content_gallery_id
		LEFT JOIN content_gallery_type cgt ON cgt.content_gallery_type_id=cg.content_gallery_type_id
		LEFT JOIN stringer_assignments sa ON sa.stringer_gallery_id=sg.stringer_gallery_id
		LEFT JOIN stringer_users su ON su.stringer_user_id=sa.stringer_user_id
		LEFT JOIN content_gallery_sites cgs ON cgs.content_gallery_id=cg.content_gallery_id
		WHERE sg.site_id='{$siteId}'";
		if(!empty($params['query']))
			$sql .= " and (cg.content_gallery LIKE '%".$params['query']."%' or su.stringer_username LIKE '%".$params['query']."%' )";		
		$sql .= " GROUP BY sg.stringer_gallery_id
		ORDER BY sg.created_date DESC
		";
		if(!empty($params['limit'])) $sql .= " LIMIT {$params['start']}, {$params['limit']}";
		$assignments['data'] = $assignmentTable->getAdapter()->fetchAll($sql);
		$assignments['total'] = $assignmentTable->getAdapter()->fetchOne("SELECT FOUND_ROWS()");
		return $assignments;
	}

	function getAssignment($assignmentId)
	{
		$assignmentId = intval($assignmentId);
		$assignmentTable = new stringer_galleries(array('db'=>'db'));
		$sql = "
		SELECT sg.stringer_gallery_id, sg.site_id, sg.content_gallery_id, sg.created_date, 
			cg.content_gallery_type_id, cg.keywords, cg.content_gallery, cg.content_gallery, cg.show_gallery_in_multimedia, cg.for_sale,
			cg.smugmug_id, cg.smugmug_key, cg.smugmug_categoryid, cgt.content_gallery_type, GROUP_CONCAT(DISTINCT su.stringer_username SEPARATOR ', ') AS users,
			GROUP_CONCAT(DISTINCT cgs.site_id SEPARATOR ',') AS sites
		FROM stringer_galleries sg
		LEFT JOIN content_gallery cg ON cg.content_gallery_id=sg.content_gallery_id
		LEFT JOIN content_gallery_type cgt ON cgt.content_gallery_type_id=cg.content_gallery_type_id
		LEFT JOIN stringer_assignments sa ON sa.stringer_gallery_id=sg.stringer_gallery_id
		LEFT JOIN stringer_users su ON su.stringer_user_id=sa.stringer_user_id
		LEFT JOIN content_gallery_sites cgs ON cgs.content_gallery_id=cg.content_gallery_id
		WHERE sg.stringer_gallery_id='{$assignmentId}' AND sg.site_id='{$this->site_id}'
		GROUP BY sg.stringer_gallery_id
		ORDER BY sg.created_date DESC
		";
		return $assignmentTable->getAdapter()->fetchRow($sql);
	}
	
	function getAssignmentUsers($stringerGalleryId) {
		$stringerGalleryId = intval($stringerGalleryId);
		$assignmentTable = new stringer_galleries(array('db'=>'db'));
		$sql = "
		SELECT SQL_CALC_FOUND_ROWS su.*, sa.stringer_gallery_id
		FROM stringer_assignments sa
		LEFT JOIN stringer_users su ON su.stringer_user_id=sa.stringer_user_id
		WHERE sa.stringer_gallery_id={$stringerGalleryId}
		ORDER BY su.stringer_username
		";
		$result['success'] = true;
		$result['data'] = $assignmentTable->getAdapter()->fetchAll($sql);
		$result['total'] = $assignmentTable->getAdapter()->fetchOne("SELECT FOUND_ROWS()");
		return $result;
	}
	
	function deleteAssignment($stringerGalleryId) {
		$assignmentUser = new stringer_assignments(array('db'=>'db'));
		$where = array();
		$where[] = $assignmentUser->getAdapter()->quoteInto("stringer_gallery_id=?", $stringerGalleryId);
		$where[] = $assignmentUser->getAdapter()->quoteInto("site_id=?", $this->site_id);
		$assignmentUser->delete($where);
		
		$assignmentTable = new stringer_galleries(array('db'=>'db'));
		
		$galleryTitle = $assignmentTable->getAdapter()->fetchOne("SELECT cg.content_gallery FROM stringer_galleries sg LEFT JOIN content_gallery cg ON cg.content_gallery_id=sg.content_gallery_id WHERE sg.stringer_gallery_id='{$stringerGalleryId}'");
		
		$where = array();
		$where[] = $assignmentTable->getAdapter()->quoteInto("stringer_gallery_id=?", $stringerGalleryId);
		$where[] = $assignmentTable->getAdapter()->quoteInto("site_id=?", $this->site_id);
		$assignmentTable->delete($where);
		
		$this->addLog($stringerGalleryId, "Delete", "Assignment", array(), array(), "Gallery: ".$galleryTitle);
	}
	
	function getStringersForDateRange($site_id, $startDate, $endDate)
	{
		$stringerTable = new stringer_users(array('db' => 'db')); //use db object from registry
		
		$select = "
			SELECT su.*, 
				COALESCE(SUM(quantity*price_charged), 0) AS total_sales, 
				COALESCE(SUM(quantity), 0) AS total_qty, 
				COALESCE(SUM(profit), 0) AS total_profit
			FROM stringer_users su
			LEFT JOIN smugmug_sales ss ON ss.stringer_user_id=su.stringer_user_id AND ss.order_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59' AND ss.smugmug_type='Sale'
			WHERE su.site_id='{$site_id}' 
			GROUP BY su.stringer_user_id
			ORDER BY su.stringer_username
		";
		
		return $stringerTable->getAdapter()->fetchAll($select);
	}
}
?>