<?php

require_once('adminClass.php');
require_once('dbClass.php');

class ratingClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function getRating()
	{
		$ratingTable = new rating(array('db' => 'db')); //use db object from registry

		$select = $ratingTable->select();
		
		return $this->db->fetchAll($select);
	}
	
	
	function addRating($params)
	{		
		$ratingTable = new rating(array('db' => 'db')); //use db object from registry

		$data = array(
			'start_range'		=> $params['start_range'],
			'end_range'			=> $params['end_range'],
			'rating'			=> $params['rating']
		);	

		if(empty($params['id']))
		{
			$ratingTable->insert($data);
		}
		else
		{
			$where = $ratingTable->getAdapter()->quoteInto('id = ?', $params['id']);
			$ratingTable->update($data, $where);
		}
		
	}
	
	function getRatingById($id)
	{		
		$ratingTable = new rating(array('db' => 'db')); //use db object from registry

		$select = $ratingTable->select()
			->where('id = ?', $id);
			
		$rs = $ratingTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteRating($id)
	{
		$ratingTable = new rating(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $ratingTable->getAdapter()->quoteInto('id = ?', $id);
			$ratingTable->delete($where);
		}
	}

}
?>