<?php

require_once('adminClass.php');

class imagesClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function insert_image($siteid, $image_source_id,$source_id,$filename,$image_type_id,$image_class_id, $externalData = array())
	{
		//Get the image from the file, we don't care what it is
		//$image = file_get_contents($filename);
		
		$imagesTable = new images(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'			=> $siteid,
			'image_source_id'	=> $image_source_id,
			'source_id'			=> $source_id,
			'image_type_id'		=> $image_type_id,
			'image_class_id'	=> $image_class_id,
			'image'      		=> '' //$image
		);
		if(!empty($externalData['id'])) $data['smugmug_id'] = $externalData['id'];
		if(!empty($externalData['Key'])) $data['smugmug_key'] = $externalData['Key'];
		if(!empty($externalData['youtube_id'])) $data['youtube_id'] = $externalData['youtube_id'];
		if(!empty($externalData['video_tag'])) $data['video_tag'] = $externalData['video_tag'];
		
		$imagesTable->insert($data);
		return $this->db->lastInsertId();
	}
	
	function getImageType($image_type)
	{
		$imageTypeTable = new image_type(array('db' => 'db')); //use db object from registry
		
		$select = $imageTypeTable->select()
			->where('image_type = ?', $image_type);
			
		return $this->db->fetchRow($select);
	}
	
	function update_image($image_id, $image_source_id,$source_id,$filename,$image_type_id,$image_class_id,$smugmug = array())
	{
		//$image = file_get_contents($filename);
		
		$imagesTable = new images(array('db' => 'db'));
		
		$data = array(
			'image_source_id'	=> $image_source_id,
			'source_id'			=> $source_id,
			'image_type_id'		=> $image_type_id,
			'image_class_id'	=> $image_class_id,
			'image'      		=> '' //$image
		);
		if(!empty($smugmug['id'])) $data['smugmug_id'] = $smugmug['id'];
		if(!empty($smugmug['Key'])) $data['smugmug_key'] = $smugmug['Key'];
		if(!empty($smugmug['video_tag'])) $data['video_tag'] = $smugmug['video_tag'];
		
		$where = $imagesTable->getAdapter()->quoteInto('image_id = ?', $image_id);
		$imagesTable->update($data, $where);	
	}
	
	/**
	 * Delete images with the provided source_id
	 * 
	 * @param int $source_id
	 */
	function deleteImages($source_id)
	{
		$imagesTable = new images(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($source_id) && $source_id > 0 )
		{
			$where = $imagesTable->getAdapter()->quoteInto('source_id = ?', $source_id);
			$imagesTable->delete($where);
		}
	}
	
	function getImageBySourceId($site_id, $source_id)
	{
		$imagesTable = new images(array('db' => 'db')); //use db object from registry
		
		$select = $imagesTable->select()
			->where('site_id = ?', $site_id)
			->where('source_id = ?', $source_id);
			
		return $this->db->fetchRow($select);
	}
	
}
?>