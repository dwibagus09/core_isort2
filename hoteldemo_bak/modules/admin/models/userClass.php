<?php

require_once('adminClass.php');
require_once('dbClass.php');

class userClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getUsers()
	{
		$usersTable = new users(array('db' => 'db')); //use db object from registry

		/*$select = $usersTable->getAdapter()->select();
		$select->from(array("u"=>"users"), array('u.*'));
		$select->joinLeft(array("r"=>"role"), "r.role_id=u.role_id", array("r.role"));*/
		$select = $usersTable->select();
		$select->where('site_id = ?', $this->site_id);
		$select->order(array("username"));
		
		return $this->db->fetchAll($select);
	}
	
	function getUserRole()
	{
		$roleTable = new role(array('db' => 'db')); //use db object from registry

		$select = $roleTable->select()->order("role");
		
		return $this->db->fetchAll($select);
	}
	
	
	function addUser($params)
	{		
		$usersTable = new users(array('db' => 'db'));
		//$usersTable3 = new users(array('db' => 'db3'));
		
		$data = array(
			'username'	=> $params['username'],
			'password'	=> md5($params['password']),
			'name'		=> $params['name'],
			'role_id'	=> implode(",",$params['role']),
			'site_id'	=> $this->site_id
		);
			
		if(empty($params['user_id']))
		{
			$usersTable->insert($data);
			//$usersTable3->insert($data);
		}
		else
		{
			if(empty($params['password']))	unset($data['password']);
			$where = $usersTable->getAdapter()->quoteInto('user_id = ?', $params['user_id']);
			$usersTable->update($data, $where);

			/*$where = $usersTable3->getAdapter()->quoteInto('user_id = ?', $params['user_id']);
			$usersTable3->update($data, $where);*/
		}
		
	}
	
	function getUserById($id)
	{		
		$usersTable = new users(array('db' => 'db'));

		$select = $usersTable->select()
			->where('user_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	function deleteUser($id)
	{
		$usersTable = new users(array('db' => 'db'));
		//$usersTable3 = new users(array('db' => 'db3'));
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $usersTable->getAdapter()->quoteInto('user_id = ?', $id);
			$usersTable->delete($where);

			/*$where3 = $usersTable3->getAdapter()->quoteInto('user_id = ?', $id);
			$usersTable3->delete($where3);*/
		}
	}
	
	function getUserRoleById($id)
	{		
		$roleTable = new role(array('db' => 'db')); //use db object from registry

		$select = $roleTable->select()
			->where('role_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}

	function getKPIUsers($category_id)
	{
		$usersTable = new kpi_users(array('db' => 'db')); //use db object from registry

		$select = $usersTable->getAdapter()->select();
		$select->from(array("k"=>"kpi_users"), array('k.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id=k.user_id", array("u.name"));
		$select->where('k.category_id = ?', $category_id);
		$select->where('k.site_id = ?', $this->site_id);
		$select->order('k.position_id');
		return $this->db->fetchAll($select);
	}

	function getUsersByCategory($category_id)
	{
		$usersTable = new users(array('db' => 'db')); //use db object from registry

		$select = $usersTable->getAdapter()->select();
		$select->from(array("u"=>"users"), array('u.*'));
		$select->joinLeft(array("r"=>"role"), "r.role_id=u.role_id", array());
		$select->where('u.site_id = ?', $this->site_id);
		$role_query="";
		if($category_id == "1") $role_query = " OR (u.role_id like'%3%' and u.role_id not like'%13%') or u.role_id = '%2%'";
		if($category_id == "3") $role_query = " OR (u.role_id like'%7%' and u.role_id not like'%17%')";
		if($category_id == "5") $role_query = " OR (u.role_id like'%8%' and u.role_id not like'%18%')";
		$select->where('r.category_id = '.$category_id.' OR u.role_id is NULL'. $role_query);
		$select->order('u.name');
		return $this->db->fetchAll($select);
	}

	function addKPIUser($params)
	{		
		$usersTable = new kpi_users(array('db' => 'db')); //use db object from registry

		$data = array(
			'site_id'			=> $this->site_id,
			'category_id'		=> $params['category_id'],
			'user_id'			=> $params['user_id'],
			'position_id'		=> $params['position_id']
		);	

		if(empty($params['kpi_user_id']))
		{
			$usersTable->insert($data);
		}
		else
		{
			$where = $usersTable->getAdapter()->quoteInto('kpi_user_id = ?', $params['kpi_user_id']);
			$usersTable->update($data, $where);
		}
		
	}
	
	function getKPIUserById($id)
	{		
		$usersTable = new kpi_users(array('db' => 'db')); //use db object from registry

		$select = $usersTable->select()
			->where('kpi_user_id = ?', $id);
			
		$rs = $usersTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteKPIUser($id)
	{
		$usersTable = new kpi_users(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $usersTable->getAdapter()->quoteInto('kpi_user_id = ?', $id);
			$usersTable->delete($where);
		}
	}
}
?>