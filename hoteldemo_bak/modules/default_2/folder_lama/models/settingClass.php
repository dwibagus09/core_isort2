<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class settingClass extends defaultClass
{	
	function getOtherSetting() {
		$otherSettingTable = new other_setting(array('db'=>'db'));
		$select = $otherSettingTable->select()->where('site_id='.$this->site_id);
		$setting = $otherSettingTable->getAdapter()->fetchRow($select);
		return $setting;
	}
}
?>