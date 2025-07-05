<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class issuetypeClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getIssueType($category_id, $limit = 0)
	{
		$issueTypeTable = new issue_type(array('db'=>'db'));
		
		$select = $issueTypeTable->select();
		$select->where("category_id=?", $category_id);		
		$select->orWhere("category_id=?", '0');	
		if(!empty($limit)) $select->limit($limit);
		$rs = $issueTypeTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getIssueTypeById($id)
	{
		$issueTypeTable = new issue_type(array('db'=>'db'));
		
		$select = $issueTypeTable->select()->where("issue_type_id=?", $id);		
		$rs = $issueTypeTable->getAdapter()->fetchRow($select);
		return $rs;
	}

	function getIssueTypeByIds($ids)
	{
		$issueTypeTable = new issue_type(array('db'=>'db'));
		
		$select = $issueTypeTable->select()->where("issue_type_id in (".$ids.")");		
		$rs = $issueTypeTable->getAdapter()->fetchAll($select);
		return $rs;
	}

	function getIssueTypeByCategoryId($id, $incl = "")
	{
		$categoryIssueTypeTable = new category_issue_type(array('db'=>'db'));

		$select = $categoryIssueTypeTable->getAdapter()->select();
		$select->from(array("cit"=>"category_issue_type"), array("cit.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = cit.issue_type_id", array("it.*"));
		$select->where("cit.category_id=?", $id);
		$select->where("cit.site_id=?", $this->site_id);
		if(!empty($incl))
		{
			$select->where("cit.issue_type_id IN (".$incl.")");
		}
		$select->order('cit.order_id');
		$issue_type = $categoryIssueTypeTable->getAdapter()->fetchAll($select);
		return $issue_type;
	}

	function getLostFoundOptions()
	{
		$lfoTable= new lost_found_options(array('db'=>'db'));

		$select = $lfoTable->select();
		$options = $lfoTable->getAdapter()->fetchAll($select);
		return $options;
	}

	function getLostFoundOptionsById($id)
	{
		$lfoTable= new lost_found_options(array('db'=>'db'));

		$select = $lfoTable->select();
		$select->where("option_id=?", $id);
		$option = $lfoTable->getAdapter()->fetchRow($select);
		return $option;
	}
}

?>