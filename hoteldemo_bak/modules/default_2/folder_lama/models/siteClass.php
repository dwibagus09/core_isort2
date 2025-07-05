<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class siteClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Returns the complete details for a given site
	 *
	 * @param int $site_id
	 * @return array
	 */
	function getSiteById($site_id)
	{
		$sitesTable = new sites(array('db' => 'db'));

		$select = $sitesTable->select()
				->where('site_id = ?', $site_id);
		return $this->db->fetchRow($select);
	}
	
	/*
	 * Gets an array of sites on site table
	 * @return array
	 */
	function getSites()
	{
		$sitesTable = new sites(array('db' => 'db'));
		$dbObj = $this->db;

		$select = $sitesTable->select();
		return $this->db->fetchAll($select);
	}
	
	function setSite($siteid)
	{
		$sitesTable = new sites(array('db' => 'db')); //use db object from registry
		
		$select = $sitesTable->select()->where('site_id = ?', $siteid);
		$result = $this->db->fetchRow($select);
		if(!empty($result)) {
			$this->session->curUser['site_id']=$result['site_id'];
			$this->session->curUser['site_name']=$result['site_name'];
			$this->session->curUser['site_fullname']=$result['site_fullname'];
			$this->session->curUser['initial']=$result['initial'];
			return(true);
		}
		else {
			return(false);
		}

	}

	/**
	 * Gets an array of sites on site table according to the city id(s)
	 *
	 * @param int $city_ids
	 * @return array
	 */
	function getSitesByCityId($city_ids)
	{
		$sitesTable = new sites(array('db' => 'db'));

		$select = $sitesTable->select()
				->where('city_id IN ('.$city_ids.')');
		return $this->db->fetchAll($select);
	}

}

?>