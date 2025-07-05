<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class lookandfeelClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Returns the complete look and feel details for a given site
	 *
	 * @param int $site_id
	 * @return array
	 */
	function getLookandfeel($site_id)
	{
		$lookandfeelTable = new lookandfeel(array('db' => 'db'));

		$select = $lookandfeelTable->select()
				->where('site_id = ?', $site_id);
		return $this->db->fetchRow($select);
	}

}

?>