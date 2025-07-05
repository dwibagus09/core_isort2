<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class shiftClass extends defaultClass
{
	function getShift() {
		$shiftTable = new shift(array('db'=>'db'));
		$select = $shiftTable->select();
		$shift = $shiftTable->getAdapter()->fetchAll($select);
		return $shift;
	}
	
	
}
?>