<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class citiesClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}


	/*
	 * Gets an array of cities on site table
	 * @return array
	 */
	function getCities()
	{
		$citiesTable = new cities(array('db' => 'db'));
		$dbObj = $this->db;

		$select = $citiesTable->select();
		return $this->db->fetchAll($select);
	}

}

?>