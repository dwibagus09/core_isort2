<?php

require_once('adminClass.php');
require_once('dbClass.php');

class siteClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of Site Name according to site id
	 * Will return false if there is no site selected.

	 * @param int $site_id
	 * @return array
	 */
	function getSiteName($site_id)
	{
		$sitesTable = new sites(array('db' => 'db'));
		$dbObj = $this->db;

		$select = $sitesTable->select()
		->where('site_id = ?', $site_id);
		$row = $dbObj->fetchRow($select);
		if(is_array($row) && !empty($row))
			return $row;
		else
			return false;
	}
	
	/**
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
	
	/**
     * Will attempt to set the session data for the currently administered site.
     * Returns true on success.
     *
     * @param int $site_id
     * @return boolean
     */
	function setSite($site_id)
	{
		$siteTable = new sites(array('db' => 'db')); //use db object from registry
		
		$select = $siteTable->select();
		$select->where('site_id = ?', $site_id);
		$result = $this->db->fetchAll($select);
		if(count($result)==1) {
			$this->session->site['site_id']=$site_id;
			$this->session->site['name']=$result[0]['site_name'];
			return(true);
		}
		else {
			return(false);
		}

	}
	
	function getSite($siteId) {
		$siteTable = new sites(array('db'=>'db'));
		$select = $siteTable->select()->where("site_id=?", $siteId);
		return $siteTable->getAdapter()->fetchRow($select);
	}
	
	function addSite($params)
	{
		$siteTable = new sites(array('db'=>'db'));
		
		$data = array(
			'name'				=> $params['name'],
			'email'				=> $params['email'],
			'newspaper_name'	=> $params['newspaper_name']
		);
		
		$siteTable->insert($data);
	}
	
	function updateSite($params)
	{		
		$siteTable = new sites(array('db'=>'db'));
		
		$data = array(
			'name'				=> $params['name'],
			'email'				=> $params['email'],
			'newspaper_name'	=> $params['newspaper_name']
		);
		$where = $siteTable->getAdapter()->quoteInto('site_id = ?', $params['site_id']);
		
		$siteTable->update($data, $where);	
	}
	
	function deleteSite($site_id)
	{
		$siteTable = new sites(array('db'=>'db'));

		if ( is_numeric($site_id) && $site_id > 0 )
		{
			$where = $siteTable->getAdapter()->quoteInto('site_id = ?', $site_id);
			$siteTable->delete($where);
		}
	}

}