<?php

require_once('adminClass.php');
require_once('dbClass.php');

class moduslinkedClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	
	function addModusLinked($params)
	{		
		$modusLinkedTable = new modus_linked(array('db' => 'db')); //use db object from registry

		$data = array(
			'site_id'			=> $this->site_id,
			'category_id'		=> $params['category_id'],
			'kejadian_id'		=> $params['kejadian_id'],
			'modus_id'			=> $params['modus_id'],
			'category_id2'		=> $params['category_id2'],
			'kejadian_id2'		=> $params['kejadian_id2'],
			'modus_id2'			=> $params['modus_id2']
		);	

		if(empty($params['id']))
		{
			$modusLinkedTable->insert($data);
		}
		else
		{
			$where = $modusLinkedTable->getAdapter()->quoteInto('linked_id = ?', $params['id']);
			$modusLinkedTable->update($data, $where);
		}
		
	}

	function getLinkedByModusId($id, $cat_id, $kej_id)
	{		
		$modusLinkedTable = new modus_linked(array('db' => 'db')); //use db object from registry

		$select = $modusLinkedTable->select()
			->where('modus_id = ?', $id)
			->where('category_id = ?', $cat_id)
			->where('kejadian_id = ?', $kej_id);
			
		$rs = $modusLinkedTable->getAdapter()->fetchAll($select);
		return $rs;	
	}

	function deleteModusLinked($id)
	{
		$modusLinkedTable = new modus_linked(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $modusLinkedTable->getAdapter()->quoteInto('linked_id = ?', $id);
			$modusLinkedTable->delete($where);
		}
	}

}
?>