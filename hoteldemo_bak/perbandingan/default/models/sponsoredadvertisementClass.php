<?php

class sponsoredAdvertisementClass extends defaultClass
{

	function __construct()
	{
		parent::__construct();
	}

	function getCategories($site_id)
	{
		$saCategory = new sponsored_advertisement_category(array('db' => 'db'));

		$select = $saCategory->select()
			->where('site_id = ?', $site_id);
		
		return $this->db->fetchAll($select);
	}
	
	function add($userid, $params) {
		$saEmailList = new sponsored_advertisement_email_list(array('db'=>'db'));
		
		if(!empty($params))
		{
			foreach ($params as $sa) {
				$data = array(
					'sponsored_advertisement_category_id'	=> $sa,
					'user_id'								=> $userid
				);
				$saEmailList->insert($data);
			}
		}
	}
	
	function delete($userid) {
		$saEmailList = new sponsored_advertisement_email_list(array('db'=>'db'));
		
		$where = $saEmailList->getAdapter()->quoteInto('user_id = ?', $userid);
		$saEmailList->delete($where);
	}
	
	function checkCategoryUser($sponsored_advertisement_category_id, $userid)
	{
		$saEmailList = new sponsored_advertisement_email_list(array('db' => 'db'));
		
		$select = $saEmailList->getAdapter()->select();
		$select->from(array("sael"=>"sponsored_advertisement_email_list"), array("sael.*"));
		$select->joinLeft(array("u"=>"users"), "u.userid = sael.user_id", array("u.username", "u.firstname", "u.lastname", "u.email"));
		$select->where('sael.sponsored_advertisement_category_id = ?', $sponsored_advertisement_category_id);
		$select->where('u.userid = ?', $userid);
		
		$rs = $this->db->fetchAll($select);
		if(count($rs) > 0)		
			return 1;
		else
			return 0;
	}
}