<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentgalleryimagesClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function addContentGalleryImages($site_id, $params)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db')); //use db object from registry
		$params['sequence'] = intval($params['sequence']);
		$data = array(
			'site_id'				=> $site_id,
			'content_gallery_id'	=> $params['content_gallery_id'],
			'content_images_id'		=> $params['content_images_id'],
			'sequence'				=> $params['sequence']
		);
		
		$contentgalleryimagesTable->insert($data);
		
		$cgiId = $contentgalleryimagesTable->getAdapter()->lastInsertId();
		
		//$this->addLog($cgiId, "Add", "Gallery Image", array(), $data, "");
	}
	
	function updateSequence($content_gallery_images_id, $sequence)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db'));
		$sequence = intval($sequence);
		$data = array(
			'sequence'				=> $sequence
		);
		$where = $contentgalleryimagesTable->getAdapter()->quoteInto('content_gallery_images_id = ?', $content_gallery_images_id);
		$contentgalleryimagesTable->update($data, $where);	
	}
	
	function getContentGalleryImagesById($content_gallery_images_id)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db'));

		$select = $contentgalleryimagesTable->select()
			->where('content_gallery_images_id = ?', $content_gallery_images_id);
		
		return $this->db->fetchRow($select);
	}
	
	/**
	 * Delete content gallery images with the provided content_gallery_images_id
	 * 
	 * @param int $content_gallery_images_id
	 */
	function deleteContentGalleryImages($content_gallery_images_id)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($content_gallery_images_id) && $content_gallery_images_id > 0 )
		{
			$select = $contentgalleryimagesTable->select()->where("content_gallery_images_id=?", $content_gallery_images_id);
			$oldData = $contentgalleryimagesTable->getAdapter()->fetchRow($select);
			
			$where = $contentgalleryimagesTable->getAdapter()->quoteInto('content_gallery_images_id = ?', $content_gallery_images_id);
			$contentgalleryimagesTable->delete($where);
			
			/*if(!empty($oldData['content_gallery_images_id'])) 
				$this->addLog($content_gallery_images_id, "Delete", "Gallery Image", $oldData, array(), "");*/
		}
	}
	
	function getContentGalleryImages($content_gallery_id)
	{
		$content_gallery_id = intval($content_gallery_id);
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db'));

		$sql = "SELECT * FROM content_gallery_images WHERE content_gallery_id='{$content_gallery_id}'";
		if(!empty($this->site_group_id)) $sql .= " AND site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}') ";
		else $sql .= " AND site_id='{$this->site_id}'";
		
		return  $contentgalleryimagesTable->getAdapter()->fetchAll($sql);
	}
}
?>