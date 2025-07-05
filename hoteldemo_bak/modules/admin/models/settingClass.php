<?php

require_once('adminClass.php');
require_once('dbClass.php');

class settingClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	

	function getOtherSetting()
	{
		$otherSettingTable = new other_setting(array('db' => 'db')); //use db object from registry

		$select = $otherSettingTable->select()->where('site_id = ?', $this->site_id);
		return $this->db->fetchRow($select);
	}
	
	
	function saveOtherSetting($params)
	{		
		$otherSettingTable = new other_setting(array('db' => 'db')); //use db object from registry
		
		/*$data = array(
			'site_id'	=> $this->site_id,
			'chief_security_reporting_time'	=> $params['chief_security_reporting_time']
		);*/
		$data = $params;
		$data['site_id'] = $this->site_id;
		unset($data['module']);
		unset($data['controller']);
		unset($data['action']);
		unset($data['setting_id']);
		unset($data['act']);
		if(empty($params['setting_id']))
		{
			$otherSettingTable->insert($data);
		}
		else
		{
			$where = $otherSettingTable->getAdapter()->quoteInto('setting_id = ?', $params['setting_id']);
			$otherSettingTable->update($data, $where);
		}
		
	}
	
	function getOtherSettingById($id)
	{		
		$otherSettingTable = new other_setting(array('db' => 'db')); //use db object from registry

		$select = $otherSettingTable->select()
			->where('setting_id = ?', $id);
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
}
?>