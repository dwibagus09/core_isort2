<?php

class userClass extends defaultClass
{

	function __construct()
	{
		parent::__construct();
	}

	
	function updatePassword($user_id, $new_password) {
		$userTable = new users(array('db'=>'db'));
		$data = array(
			"password" => md5($new_password),
		);
		$where = array();
		$where[] = $userTable->getAdapter()->quoteInto("user_id=?", $user_id);
		$userTable->update($data, $where);
	}


	function getUsers($site_id) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->select();
		$select->where('role_id <> ?', 1);
		$select->where('role_id <> ?', 6);
		$select->where('role_id <> ?', 13);
		$select->where('role_id <> ?', 14);
		$select->where('role_id <> ?', 15);
		$select->where('role_id <> ?', 16);
		$select->where('role_id <> ?', 20);
		$select->where('role_id <> ?', 21);
		$select->where('role_id <> ?', 42);
		$select->where('site_id = ?', $site_id);		
		//$select->orWhere("role_id IN (13,14,15,16)");
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getDepartmentByRoleId() {
		$roleTable = new role(array('db'=>'db'));
		$select = $roleTable->getAdapter()->select();
		$select->from(array("r"=>"role"), array("r.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = r.category_id", array("c.category_name"));
		$role = $roleTable->getAdapter()->fetchAll($select);
		return $role;
	}

	function getUsersByRole($role_id) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->select();
		$select->where('site_id = ?', $this->site_id);		
		$select->where("role_id like '%".$role_id."%'");
		$select->order("name");
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getAllUsersByRole($role_id) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->select();	
		$select->where("role_id like '%".$role_id."%'");
		if($role_id == "25") 
		{
			//$select->orWhere("role_id = '1'");
			//$select->orWhere("role_id = '6'");
		}
		$select->order("name");
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getCategoriesByRoles($role_ids) {
		$roleTable = new role(array('db'=>'db'));
		$select = $roleTable->getAdapter()->select();
		$select->from(array("r"=>"role"), array("r.category_id"));
		$select->where("r.role_id IN (".$role_ids.")");
		$users = $roleTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getOMUsers() {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->getAdapter()->select();
		$select->from(array("u"=>"users"), array("u.*"));
		$select->joinLeft(array("s"=>"sites"), "s.site_id = u.site_id", array("s.*"));	
		$select->where("u.user_id <> 7");
		$select->where("(u.role_id = '4' or u.role_id like '4,%' or u.role_id like '%,4' or u.role_id like '%,4,%')");		
		$select->order("u.name");
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getRoleById($id)
	{
		$roleTable = new role(array('db'=>'db'));
		
		$select = $roleTable->select()->where("role_id=?", $id);		
		$rs = $roleTable->getAdapter()->fetchRow($select);
		return $rs;
	}

	function getKPIUsers($category_id) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->getAdapter()->select();
		$select->from(array("k"=>"kpi_users"), array("k.*"));
		$select->joinLeft(array("u"=>"users"), "u.user_id = k.user_id", array("u.name"));
		$select->where('k.site_id = ?', $this->site_id);
		$select->where('k.category_id = ?', $category_id);
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}

	function getTotalUserPerDepartment($cat_id, $site_id = 0) {
		$userTable = new users(array('db'=>'db'));
		
		$select = $userTable->getAdapter()->select();
		$select->from(array("u"=>"users"), array("count(*) as total"));
		if(!empty($site_id) && $site_id > 0) $select->where('u.site_id = ?', $site_id );
		switch($cat_id)
		{
			// security
			case 1: $select->where("u.role_id like '2,%' or u.role_id = '2' or u.role_id like '%,2' or u.role_id like '%,2,%' or u.role_id like '3,%' or u.role_id = '3' or u.role_id like '%,3' or u.role_id like '%,3,%'");
					break;
			// Housekeeping
			case 2: $select->where("u.role_id like '9,%' or u.role_id = '9' or u.role_id like '%,9' or u.role_id like '%,9,%'");
					break;

			// Safety
			case 3: $select->where("u.role_id like '7,%' or u.role_id = '7' or u.role_id like '%,7' or u.role_id like '%,7,%'");
					break;

			// Parking & Traffic
			case 5: $select->where("u.role_id like '8,%' or u.role_id = '8' or u.role_id like '%,8' or u.role_id like '%,8,%'");
					break;
		}
		$totalUsers =  $userTable->getAdapter()->fetchRow($select);
		return $totalUsers['total'];
	}

	function getUserById($user_id) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->select();
		$select->where('user_id = ?', $user_id);		
		$users = $userTable->getAdapter()->fetchRow($select);
		return $users;
	}
	
	function getUsersByIds($ids) {
		$userTable = new users(array('db'=>'db'));
		$select = $userTable->select();
		$select->where('user_id IN ('.$ids.')');	
		$users = $userTable->getAdapter()->fetchAll($select);
		return $users;
	}
}