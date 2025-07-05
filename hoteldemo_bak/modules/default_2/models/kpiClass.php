<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class kpiClass extends defaultClass
{		
	function saveKPITotal($params)
	{		
		$kpiTable = new kpi_total(array('db'=>'db'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'category_id'			=> $params['category_id'],
			'total_chief'			=> $params['total_chief']
		);
			
		if(empty($params['id']))
		{
			$kpiTable->insert($data);
		}
		else
		{
			$where = $kpiTable->getAdapter()->quoteInto('id = ?', $params['id']);
			$kpiTable->update($data, $where);
		}
	}

	function getKPITotalByCatId($cat_id)
	{
		$kpiTable = new kpi_total(array('db'=>'db'));
		$select = $kpiTable->select()->where('category_id = ?', $cat_id)->where('site_id = ?', $this->site_id);
		$kpi = $kpiTable->getAdapter()->fetchRow($select);
		return $kpi;
	}	

	function saveMonthlyKPITotal($params)
	{		
		$kpiTable = new monthly_kpi_total(array('db'=>'db'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'category_id'			=> $params['category_id'],
			'total_chief'			=> $params['total_chief']
		);
			
		if(empty($params['id']))
		{
			$kpiTable->insert($data);
		}
		else
		{
			$where = $kpiTable->getAdapter()->quoteInto('id = ?', $params['id']);
			$kpiTable->update($data, $where);
		}
	}

	function getMonthlyKPITotalByCatId($cat_id)
	{
		$kpiTable = new monthly_kpi_total(array('db'=>'db'));
		$select = $kpiTable->select()->where('category_id = ?', $cat_id)->where('site_id = ?', $this->site_id);
		$kpi = $kpiTable->getAdapter()->fetchRow($select);
		return $kpi;
	}	

	function getCSection($cat_id, $tab, $year)
	{
		$kpiTable = new kpi_c_section(array('db'=>'db'));
		$select = $kpiTable->select()->where('category_id = ?', $cat_id)->where('site_id = ?', $this->site_id)->where('tab = ?', $tab)->where('year = ?', $year);
		$kpi = $kpiTable->getAdapter()->fetchRow($select);
		return $kpi;
	}	

	function saveCSection($params)
	{		
		$kpiTable = new kpi_c_section(array('db'=>'db'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'category_id'			=> $params['c'],
			'year'					=> $params['year'],
			'c13'					=> $params['c13'],
			'c21'					=> $params['c21'],
			'c22'					=> $params['c22'],
			'c23'					=> $params['c23'],
			'tab'					=> $params['datatab']
		);
			
		if(empty($params['id']))
		{
			$kpiTable->insert($data);
		}
		else
		{
			$where = $kpiTable->getAdapter()->quoteInto('id = ?', $params['id']);
			$kpiTable->update($data, $where);
		}
	}

	function getCustomRating($cat_id, $tab, $year, $action_plan_activity_id)
	{
		$apTable = new action_plan_custom_rating(array('db'=>'db2'));
		$select = $apTable->select()->where('category_id = ?', $cat_id)->where('site_id = ?', $this->site_id)->where('tab = ?', $tab)->where('year = ?', $year)->where('action_plan_activity_id = ?', $action_plan_activity_id);
		$kpi = $apTable->getAdapter()->fetchRow($select);
		return $kpi;
	}

	function saveCustomRating($params)
	{		
		$apTable = new action_plan_custom_rating(array('db'=>'db2'));
		
		$data = array(
			'site_id'				=> $this->site_id,
			'category_id'			=> $params['c'],
			'year'					=> $params['year'],
			'action_plan_activity_id' => $params['action_plan_activity_id'],
			'rating'				=> $params['rating'],
			'tab'					=> $params['datatab']
		);
			
		if(empty($params['custom_id']))
		{
			$apTable->insert($data);
		}
		else
		{
			$where = $apTable->getAdapter()->quoteInto('id = ?', $params['custom_id']);
			$apTable->update($data, $where);
		}
	}

	function checkIfUserIsChief($user_id, $cat_id)
	{
		$userTable = new kpi_users(array('db'=>'db'));
		$select = $userTable->select()->where('position_id = ?', '1')->where('site_id = ?', $this->site_id)->where('category_id = ?', $cat_id)->where('user_id = ?', $user_id);
		$user = $userTable->getAdapter()->fetchRow($select);
		return $user;
	}
}
?>