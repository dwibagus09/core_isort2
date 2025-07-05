<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class checklistwatertankClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getTemplatesByDept($dept_ids)
	{
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry

		$select = $templateTable->select();
		$select->where('site_id = ?', $this->site_id);	
		if(!empty($dept_ids))
		{
			$select->where('category_id IN ('.$dept_ids.')');	
		}	
		
		$rs = $templateTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function getTemplatesById($id)
	{
		$templateTable = new checklist_templates(array('db' => 'db')); //use db object from registry

		$select = $templateTable->select();
		$select->where('site_id = ?', $this->site_id);	
		$select->where('template_id = ?', $id);		
		
		$rs = $templateTable->getAdapter()->fetchRow($select);
		return $rs;	
	}

	function registerwater3($data)
	{
		$checklistwaterTable = new checklist_watertank(array('db'=>'db'));
		$checklistwaterTable->insert($data);
		return;
		
	}

	function updatechecklist2($data,$checklistwater)
	{
		$checklistwaterTable = new checklist_watertank(array('db'=>'db'));
		$where = $checklistwaterTable->getAdapter()->quoteInto('checklist_watertank_id = ?', $checklistwater);
		$checklistwaterTable->update($data, $where);
		return;
		
	}

	function getdatechecklist($txtshift,$tanggal)
	{
		$checklistwaterTable = new checklist_watertank(array('db'=>'db'));
		$select = $checklistwaterTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		$select->where('c.shift = ?', $txtshift);
		$select->where('c.created = ?', $tanggal);
		$select->limit(1);
		return $checklistwaterTable->getAdapter()->fetchRow($select);
		
	}

	
	function saveChecklist($params)
	{
		$checklistTable = new checklist(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"template_id" => $params["template_id"],
			"room_no" => $params["room_no"],
			"user_id" => $params["user_id"],
			"submitted_date" => date("Y-m-d H:i:s")
		);
		if(empty($params['checklist_id']))
		{
			$checklistTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['submitted_date']);
			unset($data['user_id']);
			$where = $checklistTable->getAdapter()->quoteInto('checklist_id = ?', $params['checklist_id']);
			$checklistTable->update($data, $where);
			return $params['checklist_id'];
		}
	}
	
	function checkChecklist($params)
	{
		$checklistTable = new checklist(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist"), array("c.*"));
		$select->joinLeft(array("ci"=>"checklist_items"), "ci.checklist_id = c.checklist_id", array("ci.*"));
		$select->where('c.site_id = ?', $this->site_id);
		$select->where('c.room_no = ?', $params["room_no"]);
		$select->where('c.template_id = ?', $params["template_id"]);
		$select->group("c.checklist_id");
		$select->order("c.checklist_id desc");
		$select->limit(1);
		return $checklistTable->getAdapter()->fetchRow($select);
	}
	
	function getItemsByTemplateAndChecklist($template_id, $checklist_id)
	{
		$itemTable = new checklist_template_items(array('db' => 'db')); //use db object from registry
		
		$select = $itemTable->getAdapter()->select();
		$select->from(array("i"=>"checklist_template_items"), array("i.item_id as template_item_id_ori", "i.item_name as template_item_name"));
		$select->joinLeft(array("c"=>"checklist_categories"), "c.category_id = i.category_id", array("c.category_id", "c.category_name"));
		$select->joinLeft(array("s"=>"checklist_subcategories"), "s.subcategory_id = i.subcategory_id", array("s.subcategory_id", "s.subcategory_name"));
		$select->joinLeft(array("ci"=>"checklist_items"), "ci.template_item_id = i.item_id and ci.checklist_id = ".$checklist_id, array("ci.*"));
		$select->where('i.site_id = ?', $this->site_id);
		$select->where('i.template_id = ?', $template_id);
		$select->order("c.sort_order");
		$select->order("s.sort_order");
		$select->order("i.sort_order");
		$rs = $itemTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function getChecklistById($id)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));

		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		//$select->joinLeft(array("t"=>"checklist_templates"), "t.template_id = c.template_id", array("t.*"));	
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.fu", array("u.name"));			
		$select->where('c.checklist_watertank_id = ?', $id);
		
		$rs = $checklistTable->getAdapter()->fetchRow($select);
		return $rs;	
	}
	
	function getItemsByChecklistId($id)
	{
		$checklistItemsTable = new checklist_items(array('db'=>'db'));

		$select = $checklistItemsTable->getAdapter()->select();
		$select->from(array("ci"=>"checklist_items"), array("ci.*"));
		$select->joinLeft(array("i"=>"checklist_template_items"), "i.item_id = ci.template_item_id", array());
		$select->joinLeft(array("c"=>"checklist_categories"), "c.category_id = i.category_id", array("c.category_id", "c.category_name"));
		$select->joinLeft(array("s"=>"checklist_subcategories"), "s.subcategory_id = i.subcategory_id", array("s.subcategory_id", "s.subcategory_name"));
		$select->where('ci.site_id = ?', $this->site_id);
		$select->where('ci.checklist_id = ?', intval($id));
		$select->order("c.sort_order");
		$select->order("s.sort_order");
		$select->order("i.sort_order");
		//echo $select; exit();
		$rs = $checklistItemsTable->getAdapter()->fetchAll($select);
		return $rs;	
	}
	
	function saveChecklistItem($params)
	{
		$checklistItemsTable = new checklist_items(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"checklist_id" => $params["checklist_id"],
			"template_id" => $params["template_id"],
			"template_item_id" => $params["template_item_id"],
			"item_name" => $params['template_item_name'],
			"condition_".strtolower($params['position']) => $params["condition"],
			"save_date_".strtolower($params['position']) => date("Y-m-d H:i:s"),
		    "user_".strtolower($params['position']) => $params["user_id"],
			"last_updated" => date("Y-m-d H:i:s")
		);
		//echo "<pre>"; print_r($data); exit();
		//print_r($params);
		$select = $checklistItemsTable->select();
		$select->where('site_id = ?', $this->site_id);	
		$select->where('checklist_id = ?', intval($params["checklist_id"]));	
		$select->where('item_id = ?', intval($params["item_id"]));	
		$rs = $checklistItemsTable->getAdapter()->fetchRow($select);
		//print_r($rs); exit();
		if(empty($rs))
		{	
			$checklistItemsTable->insert($data);
			return $this->db->lastInsertId();
		}
		else
		{
			unset($data['template_item_id']);
			unset($data['item_name']);
			unset($data['template_id']);
			unset($data['checklist_id']);
			$where = $checklistItemsTable->getAdapter()->quoteInto('item_id = ?', $params['item_id']);
			$checklistItemsTable->update($data, $where);
			return $params['item_id'];
		}
	}
	
	function getChecklist($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		$select->join(array("t"=>"users"), "c.fu = t.user_id", array("t.name"));
		//$select->join(array("i"=>"checklist_items"), "c.checklist_watertank_id = i.checklist_id", array("i.save_date_staff", "i.save_date_spv", "i.save_date_staff2", "i.save_date_spv2", "i.save_date_staff3", "i.save_date_spv3", "i.last_updated"));
		//$select->where('c.site_id = ?', $this->site_id);
		//if(!empty($params['room_no'])) $select->where('c.room_no like "%'.$params['room_no'].'%"');
		//$select->order("c.room_no asc");
		//$select->order("i.last_updated desc");
		$select->order('c.created DESC');
		//$select->group("c.checklist_watertank_id");
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		$checklistList = $checklistTable->getAdapter()->fetchAll($select);
		return $checklistList;
	}

	function getChecklistdetail($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		$select->join(array("t"=>"users"), "c.fu = t.user_id", array("t.name"));
		//$select->join(array("i"=>"checklist_items"), "c.checklist_watertank_id = i.checklist_id", array("i.save_date_staff", "i.save_date_spv", "i.save_date_staff2", "i.save_date_spv2", "i.save_date_staff3", "i.save_date_spv3", "i.last_updated"));
		//$select->where('MONTH(created) = ?', $params['id']);
		//if(!empty($params['room_no'])) $select->where('c.room_no like "%'.$params['room_no'].'%"');
		//$select->order("c.room_no asc");
		//$select->order("i.last_updated desc");
		$select->order('c.created DESC');
		//$select->group("c.checklist_watertank_id");
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		$checklistList = $checklistTable->getAdapter()->fetchAll($select);
		return $checklistList;
	}
	
	function getChecklistbulan($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		$select->join(array("t"=>"users"), "c.fu = t.user_id", array("t.name"));
		$select->join(array("i"=>"checklist_items"), "c.checklist_watertank_id = i.checklist_id", array("i.save_date_staff", "i.save_date_spv", "i.save_date_staff2", "i.save_date_spv2", "i.save_date_staff3", "i.save_date_spv3", "i.last_updated"));
		$select->where('c.site_id = ?', $this->site_id);
		//if(!empty($params['room_no'])) $select->where('c.room_no like "%'.$params['room_no'].'%"');
		//$select->order("c.room_no asc");
		//$select->order("i.last_updated desc");
		//$select->columns([
		//	'tahun' => new \Zend\Db\Sql\Expression('YEAR(tanggal)'),
		//	'bulan' => new \Zend\Db\Sql\Expression('MONTH(c.created)'),
		//	'total' => new \Zend\Db\Sql\Expression('SUM(jumlah)'),
		//]);
		$select->group('Month(c.created)');
		
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		//$select->distinct();
		$checklistList = $checklistTable->getAdapter()->fetchAll($select);
		return $checklistList;
	}

	function getChecklistdetailbulan($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.*"));
		$select->join(array("t"=>"users"), "c.fu = t.user_id", array("t.name"));
		//$select->join(array("i"=>"checklist_items"), "c.checklist_watertank_id = i.checklist_id", array("i.save_date_staff", "i.save_date_spv", "i.save_date_staff2", "i.save_date_spv2", "i.save_date_staff3", "i.save_date_spv3", "i.last_updated"));
		$select->where('Month(c.created) = ?', $params['id']);
		$select->where('c.site_id = ?', $this->site_id);
		//if(!empty($params['room_no'])) $select->where('c.room_no like "%'.$params['room_no'].'%"');
		//$select->order("c.room_no asc");
		//$select->order("i.last_updated desc");
		//$select->order('c.created DESC');
		//$select->group("c.checklist_watertank_id");
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		$checklistList = $checklistTable->getAdapter()->fetchAll($select);
		return $checklistList;
	}

	function getChecklistdetailbulanexport($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$select = $checklistTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_watertank"), array("c.created","c.shift","c.temper_cool","c.temper_hot","c.vol_1","c.vol_2","c.site_id","c.ph","c.cl","c.sampit_utara","c.sampit_sel1","c.sampit_Sel2","c.sampit_sel3","c.sampit_kitchen","c.genzet_1","c.genzet_2","c.fuel_1","c.fuel_2","c.remarks"));
		$select->join(array("t"=>"users"), "c.fu = t.user_id", array("t.name"));
		//$select->join(array("i"=>"checklist_items"), "c.checklist_watertank_id = i.checklist_id", array("i.save_date_staff", "i.save_date_spv", "i.save_date_staff2", "i.save_date_spv2", "i.save_date_staff3", "i.save_date_spv3", "i.last_updated"));
		$select->where('Month(c.created) = ?', $params['id']);
		$select->where('c.site_id = ?', $this->site_id);
		//if(!empty($params['room_no'])) $select->where('c.room_no like "%'.$params['room_no'].'%"');
		//$select->order("c.room_no asc");
		//$select->order("i.last_updated desc");
		//$select->order('c.created DESC');
		//$select->group("c.checklist_watertank_id");
		if(!empty(!empty($params['pagesize']))) $select->limit($params['pagesize'],$params['start']);
		$checklistList = $checklistTable->getAdapter()->fetchAll($select);
		return $checklistList;
	}

	function getTotalChecklistbulan($params) {
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		$select = "select count(*) as total from checklist_watertank where Month(created) =".$params['id'];
		//$select .= ' and Month(created) like "%'.$params['id'].'%"';
		$checklistList = $checklistTable->getAdapter()->fetchRow($select);
		return $checklistList;
	}

	function getTotalChecklistbulan2($params) {
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		$select = "select count(*) as total from checklist_watertank where site_id =".$this->site_id;
		$select .= ' and Month(created) like "%'.$params['id'].'%"';
		//$select .= group('Month(created)');
		$checklistList = $checklistTable->getAdapter()->fetchRow($select);
		return $checklistList;
	}

	function getTotalChecklist($params) {
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		$select = "select count(*) as total from checklist_watertank where site_id =".$this->site_id;
		//if(!empty($params['room_no'])) $select .= ' and room_no like "%'.$params['room_no'].'%"';
		$checklistList = $checklistTable->getAdapter()->fetchRow($select);
		return $checklistList;
	}
	
	
	function addComment($params)
	{
		$commentTable = new checklist_comment_watertank(array('db'=>'db3'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"checklist_id" => $params["checklist_id"],
			"comment" => addslashes(str_replace("\n","<br>",$params["comment"])),
			"comment_date" => date("Y-m-d H:i:s"),
			"filename" => $params["filename"]
		);
		if(empty($params['comment_id']))
		{
			$commentTable->insert($data);
			return $this->db->lastInsertId();
		}
	}

	function updateChecklistWatertank($params)
	{
		$checklistTable = new checklist_watertank(array('db'=>'db'));
		
		$data = array(
			"site_id" => $this->site_id,
			"user_id" => $params["user_id"],
			"checklist_id" => $params["checklist_id"],
			"comment" => addslashes(str_replace("\n","<br>",$params["comment"])),
			"comment_date" => date("Y-m-d H:i:s"),
			"filename" => $params["filename"]
		);
		if(empty($params['comment_id']))
		{
			$commentTable->insert($data);
			return $this->db->lastInsertId();
		}
	}

	function getCommentsByChecklistId($meeting_id, $qty=0, $sort = "desc") {
		$commentsTable = new checklist_comment_watertank(array('db'=>'db3'));
		$select = $commentsTable->getAdapter()->select();
		$select->from(array("c"=>"checklist_comment_watertank"), array('c.*'));
		$select->joinLeft(array("u"=>"users"), "u.user_id = c.user_id", array("u.*"));
		$select->where("c.checklist_id=?", $meeting_id);
		$select->order("c.comment_id");
		if($qty > 0) $select->limit($qty);
		return $commentsTable->getAdapter()->fetchAll($select);
	}
	
}

?>