<?php

require_once('adminClass.php');
require_once('dbClass.php');

class issueClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getIssueType($category_id)
	{
		$catIssueTypeTable = new category_issue_type(array('db' => 'db')); //use db object from registry

		$select = $catIssueTypeTable->getAdapter()->select();
		$select->from(array("cit"=>"category_issue_type"), array("cit.*"));
		$select->joinLeft(array("it"=>"issue_type"), "it.issue_type_id = cit.issue_type_id", array("it.issue_type as issue_type_name"));
		$select->where('cit.category_id = ?', $category_id);
		$select->where('cit.site_id = ?', $this->site_id);
		return $catIssueTypeTable->getAdapter()->fetchAll($select);
	}
	
}
?>