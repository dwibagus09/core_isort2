<?php

require_once('adminClass.php');

class widgetClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getWidgets($siteId, $params, $useProductionDatabase=false) {
		if ( $useProductionDatabase == true ) {
			$widgetTable = new widgets(array('db' => 'db_prod')); //use db object from registry
		} else {
			$widgetTable = new widgets(array('db' => 'db')); //use db object from registry
		}
		
		$select = $widgetTable->select()->where("site_id=?", $siteId);
		if(!empty($params['ids']))
			$select = $select." and widget_id in(".$params['ids'].")";
			
		if(!empty($params["limit"])) $select = $select." limit ".$params["start"].",".$params["limit"];
		$widgets = $widgetTable->getAdapter()->fetchAll($select);
		foreach ($widgets as &$widget) {
			$widget["start_date"] = substr($widget["start_date"], 0, 10);
			$widget["end_date"] = substr($widget["end_date"], 0, 10);
		}
		return $widgets;
	}
	
	function getTotalWidgets($siteId, $params) {
		$widgetTable = new widgets(array('db'=>'db'));
		$select = $widgetTable->select()->where("site_id=?", $siteId);
		$widgets = $widgetTable->getAdapter()->fetchAll($select);
		return count($widgets);
	}
		
	function update($siteId, $data) {
		$widgetTable = new widgets(array('db'=>'db'));
		$where = array();
		$where[] = $widgetTable->getAdapter()->quoteInto("site_id=?", $siteId);
		$where[] = $widgetTable->getAdapter()->quoteInto("widget_id=?", $data["widget_id"]);
		$widgetTable->update($data, $where);
	}
	
	
	function getWidget($siteId, $widgetId) {
		$widgetTable = new widgets(array('db'=>'db'));
		$select = $widgetTable->select()->where("site_id=?", $siteId)->where("widget_id=?", $widgetId);
		$widget = $widgetTable->getAdapter()->fetchRow($select);
		$widget["start_date"] = substr($widget["start_date"], 0, 10);
		$widget["end_date"] = substr($widget["end_date"], 0, 10);
		return $widget;
	}
	
	function saveWidget($siteId, $params) {
		$widgetTable = new widgets(array('db'=>'db'));
		
		if(!empty($siteId)) {
			$data = array(
				"location_id" 		=> intval($params["location_id"]),
				"tag" 				=> $params["tag"],
				"widget_label" 		=> substr($params["widget_label"], 0, 300),
				"start_date" 		=> $params["start_date"],
				"end_date" 			=> $params["end_date"],
				"order_id" 			=> intval($params["order_id"]),
				"site_id"			=> $siteId
			);
			if(empty($params["widget_id"])) {
				$widgetTable->insert($data);
				$data["widget_id"] = $widgetTable->getAdapter()->lastInsertId();
				
				$this->addLog($data["widget_id"], "Add", "Widget", array(), $data, "");
				
			}
			else {
				$select = $widgetTable->select()->where("widget_id=?", $params['widget_id']);
				$oldData = $widgetTable->getAdapter()->fetchRow($select);
				
				$where[] = $widgetTable->getAdapter()->quoteInto("widget_id=?", $params["widget_id"]);
				$where[] = $widgetTable->getAdapter()->quoteInto("site_id=?", $siteId);
				$widgetTable->update($data, $where);
				$data["widget_id"] = $params["widget_id"];
				
				$this->addLog($params["widget_id"], "Update", "Widget", $oldData, $data, "");
			}
			return $data["widget_id"];
		}
	}
	
	function deleteWidget($siteId, $widgetId) {
		$widgetTable = new widgets(array('db'=>'db'));
		
		$select = $widgetTable->select()->where("widget_id=?", $widgetId);
		$oldData = $widgetTable->getAdapter()->fetchRow($select);
		
		$where = array();
		$where[] = $widgetTable->getAdapter()->quoteInto("widget_id=?", $widgetId);
		$where[] = $widgetTable->getAdapter()->quoteInto("site_id=?", $siteId);
		$widgetTable->delete($where);
		
		$this->addLog($widgetId, "Delete", "Widget", $oldData, array(), "");
	}
	
	function getWidgetsByLocation($siteId, $location_id) {
		$widgetTable = new widgets(array('db'=>'db'));
		$select = $widgetTable->select()
					->where("site_id=?", $siteId)
					->where("location_id=?", $location_id)
					->order("order_id");
					
		return $widgetTable->getAdapter()->fetchAll($select);
	}
	
	public function migrateWidget($site_id, $arrWidget, $params)
	{
		$widgetTable = new widgets(array('db'=>'db_prod'));
		
		$dataKeys = json_decode($params['data'], true);
		
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrWidget as $thisData ) {
				$arrIndexed[$thisData['widget_id']] = $thisData;
			}
			
			if(empty($params['donotremoveexisting'])) {
				// Delete all of the existing data from the production environment (very dangerous)
				$where = $widgetTable->getAdapter()->quoteInto('site_id = ?', $site_id);
				$widgetTable->delete($where);
			}
			foreach ($dataKeys as $dataToMigrate)
			{
				if(!empty($params['donotremoveexisting'])) {
					$where = $widgetTable->getAdapter()->quoteInto('widget_id = ?', $dataToMigrate['widget_id']);
					$widgetTable->delete($where);
				}
				$dataDetail = $arrIndexed[$dataToMigrate['widget_id']];
				$data = array();
				foreach ( $dataDetail as $dataIndex=>$dataValue )
				{
					$data[$dataIndex] = $dataValue;
				}
				$widgetTable->insert($data);
			}
		}
		return true;
	}
	
	public function migrateCopyWidget($site_id, $arrWidget, $params)
	{
		$widgetTable = new widgets(array('db'=>'db'));
		
		$dataKeys = json_decode($params['data'], true);
		if ( $site_id > 0 && is_array($dataKeys) )
		{
			// Reformat the array so that the primary key is the key in the array.
			$arrIndexed = array();
			foreach ( $arrWidget as $thisData ) {
				$arrIndexed[$thisData['widget_id']] = $thisData;
			}
			
			// Get the max value for the primary key field, so new entries can be safely added past it.
			$select = $widgetTable->select();
			$select->from($widgetTable, array('MAX(widget_id) AS max'));
			$arrMaxResult = $widgetTable->getAdapter()->fetchAll($select);
			
			$newkey = $arrMaxResult[0]['max'];

			if ( $newkey > 0 ) {
				foreach ($dataKeys as $dataToMigrate)
				{
					$dataDetail = $arrIndexed[$dataToMigrate['widget_id']];
					$data = array();
					foreach ( $dataDetail as $dataIndex=>$dataValue )
					{
						$data[$dataIndex] = $dataValue;
					}
					// Override the primary key for this entry
					$newkey++;
					$data['widget_id'] = $newkey;
						
					// Override the siteid value for this entry
					$data['site_id'] = $params['copySiteid'];
					$sitesTable = new sites(array('db' => 'db'));
			
					$select = $sitesTable->select()->where("site_id=?", $data['site_id']);
					$copySite = $sitesTable->getAdapter()->fetchRow($select);
					
					try {
						$widgetTable->insert($data);
					}
					catch (Exception $ex) {
						
					}
				}
			}
		}
		return true;
	}
}