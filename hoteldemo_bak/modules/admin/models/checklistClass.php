<?php

require_once('adminClass.php');
require_once('dbClass.php');

class checklistClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getTemplates()
	{
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry
		
		$select = $templateTable->getAdapter()->select();
		$select->from(array("t"=>"checklist_templates"), array("t.*"));
		$select->joinLeft(array("c"=>"categories"), "c.category_id = t.category_id", array("c.category_name"));
		$select->where("t.site_id= ?", $this->site_id);
		
		return $this->db->fetchAll($select);
	}
	
	
	function addTemplate($params)
	{		
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'site_id'			=> $params['site_id'],
			'category_id'		=> $params['category_id'],
			'template_name'		=> $params['template_name'],
		);	
		if(empty($params['template_id']))
		{
			$templateTable->insert($data);
			$id = $templateTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $templateTable->getAdapter()->quoteInto('template_id = ?', $params['template_id']);
			$templateTable->update($data, $where);
			$id = $params['template_id'];
		}
		return $id;
	}
	
	function getTemplateById($id)
	{		
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry

		$select = $templateTable->select()
			->where('template_id = ?', $id);
			
		$rs = $templateTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function delete($id)
	{
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $templateTable->getAdapter()->quoteInto('template_id = ?', $id);
			$templateTable->delete($where);
		}
	}
	
	
	/*** ITEMS ***/
	
	function getItems($id)
	{
		$itemTable = new checklist_template_items(array('db' => 'db')); //use db object from registry
			
		$select = $itemTable->getAdapter()->select();
		$select->from(array("i"=>"checklist_template_items"), array("i.*"));
		$select->joinLeft(array("c"=>"checklist_categories"), "c.category_id = i.category_id", array("c.category_name"));
		$select->joinLeft(array("s"=>"checklist_subcategories"), "i.subcategory_id = s.subcategory_id", array("s.subcategory_name"));
		$select->where("i.site_id= ?", $this->site_id);
		$select->where("i.template_id= ?", $id);
		$select->order("i.category_id");
		$select->order("i.subcategory_id");
		$select->order("i.sort_order");
		
		return $this->db->fetchAll($select);
	}
	
	function addItem($params)
	{		
		$itemTable = new checklist_template_items(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'site_id'			=> $params['site_id'],
			'template_id'		=> $params['template_id'],
			'category_id'		=> $params['category_id'],
			'subcategory_id'	=> $params['subcategory_id'],
			'item_name'			=> $params['item_name'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['item_id']))
		{
			$itemTable->insert($data);
			$id = $itemTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $itemTable->getAdapter()->quoteInto('item_id = ?', $params['item_id']);
			$itemTable->update($data, $where);
			$id = $params['item_id'];
		}
		return $id;
	}
	
	function getItemById($id)
	{		
		$itemTable = new checklist_template_items(array('db' => 'db')); //use db object from registry

		$select = $itemTable->select()
			->where('item_id = ?', $id);
			
		$rs = $itemTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteItem($id)
	{
		$itemTable = new checklist_template_items(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $itemTable->getAdapter()->quoteInto('item_id = ?', $id);
			$itemTable->delete($where);
		}
	}
	
	/*** CATEGORIES ***/
	
	function getCategories()
	{
		$catTable = new checklist_categories(array('db' => 'db')); //use db object from registry
		
		$select = $catTable->select()->where('site_id = ?', $this->site_id)->order("sort_order");		
		return $this->db->fetchAll($select);
	}
	
	
	function addCategory($params)
	{		
		$catTable = new checklist_categories(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'site_id'			=> $params['site_id'],
			'category_name'		=> $params['category_name'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['category_id']))
		{
			$catTable->insert($data);
			$id = $catTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $catTable->getAdapter()->quoteInto('category_id = ?', $params['category_id']);
			$catTable->update($data, $where);
			$id = $params['category_id'];
		}
		return $id;
	}
	
	function getCategoryById($id)
	{		
		$catTable = new checklist_categories(array('db' => 'db')); //use db object from registry

		$select = $catTable->select()
			->where('category_id = ?', $id);
			
		$rs = $catTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function deleteCategory($id)
	{
		$catTable = new checklist_categories(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $catTable->getAdapter()->quoteInto('category_id = ?', $id);
			$catTable->delete($where);
		}
	}
	
	
	/*** SUBCATEGORIES ***/
	
	function getSubcategories()
	{
		$subcatTable = new checklist_subcategories(array('db' => 'db')); //use db object from registry
		
		$select = $subcatTable->getAdapter()->select();
		$select->from(array("s"=>"checklist_subcategories"), array("s.*"));
		$select->joinLeft(array("c"=>"checklist_categories"), "c.category_id = s.category_id", array("c.category_name"));
		$select->where("s.site_id= ?", $this->site_id);
		$select->order("category_id");
		$select->order("sort_order");
		
		return $this->db->fetchAll($select);
	}
	
	
	function addSubcategory($params)
	{		
		$subcatTable = new checklist_subcategories(array('db' => 'db')); //use db object from registry
		
		if(empty($params['site_id'])) $params['site_id']=$this->site_id;

		$data = array(
			'site_id'			=> $params['site_id'],
			'category_id'		=> $params['category_id'],
			'subcategory_name'	=> $params['subcategory_name'],
			'sort_order'		=> $params['sort_order'],
		);	
		if(empty($params['subcategory_id']))
		{
			$subcatTable->insert($data);
			$id = $subcatTable->getAdapter()->lastInsertId();
		}
		else
		{
			$where = $subcatTable->getAdapter()->quoteInto('subcategory_id = ?', $params['subcategory_id']);
			$subcatTable->update($data, $where);
			$id = $params['subcategory_id'];
		}
		return $id;
	}
	
	function getSubcategoryById($id)
	{		
		$subcatTable = new checklist_subcategories(array('db' => 'db')); //use db object from registry

		$select = $subcatTable->select()
			->where('subcategory_id = ?', $id);
			
		$rs = $subcatTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function getSubcategoryByCatId($cat_id)
	{		
		$subcatTable = new checklist_subcategories(array('db' => 'db')); //use db object from registry

		$select = $subcatTable->select()
			->where('category_id = ?', $cat_id);
		
		$rs = $subcatTable->getAdapter()->fetchAll($select);
		
		return $rs;	
	}
	
	function deleteSubcategory($id)
	{
		$subcatTable = new checklist_subcategories(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($id) && $id > 0 )
		{		
			$where = $subcatTable->getAdapter()->quoteInto('subcategory_id = ?', $id);
			$subcatTable->delete($where);
		}
	}
}
?>