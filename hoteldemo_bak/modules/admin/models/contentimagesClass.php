<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentimagesClass extends adminClass
{
		function __construct()
	{
		parent::__construct();
	}
	
	function getImagesByGalleryId($site_id, $content_gallery_id)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db'));
		
		$select = $contentgalleryimagesTable->getAdapter()->select();
		$select->from(array("cgi"=>"content_gallery_images"), array("cgi.site_id","cgi.content_gallery_images_id","cgi.content_images_id", "cgi.sequence"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id", "ci.title", "ci.caption", "ci.keywords", "ci.credits", "ci.views", "ci.modify_date_time", "ci.create_date_time"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.image_id", "i.image", "i.smugmug_id", "i.smugmug_key", "i.youtube_id", "i.video_tag"));
		$select->joinLeft(array("imgsrc"=>"image_source"), "imgsrc.image_source_id = i.image_source_id", array("imgsrc.image_source"));
		$select->joinLeft(array("it"=>"image_type"), "it.image_type_id = i.image_type_id", array("it.file_type"));
		$select->joinLeft(array("ic"=>"image_class"), "ic.image_class_id = i.image_class_id and i.site_id = ci.site_id", array("ic.image_class", "ic.image_class_id"));
		if(!empty($this->site_group_id)) $select->joinLeft(array("s"=>"sites"), "s.site_id=cgi.site_id", "s.site_group_id");
		$select->where('cgi.content_gallery_id = ?', $content_gallery_id);
		
		if(!empty($this->site_group_id)) 
			$select->where('s.site_group_id = ?', $this->site_group_id);
		else
			$select->where('i.site_id = ?', $site_id);
		$select->order('cgi.sequence');
		
		$result = $this->db->fetchAll($select);
			
		return $result;
	}
	
	function addContentImages($site_id, $params)
	{
		$contentimagesTable = new content_images(array('db' => 'db'));

		$data = array(
			'site_id'						=> $site_id,
			'source_system_id'				=> $params['source_system_id'],
			'title'							=> $params['title'],
			'caption'						=> $params['caption'],
			'keywords'						=> $params['keywords'],
			'credits'						=> $params['credits'],
			'create_date_time'				=> date('Y-m-d H:i:s')
		);
		$contentimagesTable->insert($data);
		$ciId = $contentimagesTable->getAdapter()->lastInsertId();
		
		$this->addLog($ciId, "Add", "Gallery Image", array(), $data, $params['source_system_id']);
		
		return $ciId;
	}
	
	function getImageById($content_images_id)
	{
		$contentgalleryimagesTable = new content_gallery_images(array('db' => 'db'));
		
		$select = $contentgalleryimagesTable->getAdapter()->select();
		$select->from(array("cgi"=>"content_gallery_images"), array("cgi.site_id","cgi.content_gallery_images_id","cgi.content_images_id", "cgi.sequence"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id", "ci.title", "ci.caption", "ci.keywords", "ci.credits", "ci.views", "ci.modify_date_time", "ci.create_date_time"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.image_id", "i.image", "i.smugmug_id", "i.smugmug_key", "i.video_tag"));
		$select->joinLeft(array("imgsrc"=>"image_source"), "imgsrc.image_source_id = i.image_source_id", array("imgsrc.image_source"));
		$select->joinLeft(array("it"=>"image_type"), "it.image_type_id = i.image_type_id", array("it.file_type"));
		$select->joinLeft(array("ic"=>"image_class"), "ic.image_class_id = i.image_class_id", array("ic.image_class"));
		$select->where('cgi.content_images_id = ?', $content_images_id);
		$result = $this->db->fetchRow($select);
			
		return $result;
	}
	
	function getImage($content_images_id)
	{
		$contentimagesTable = new content_images(array('db' => 'db'));
		
		$select = $contentimagesTable->getAdapter()->select();
		$select->from(array("ci"=>"content_images"), array("ci.*"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.image_id", "i.image", "i.smugmug_id", "i.smugmug_key", "i.youtube_id", "i.image_class_id", "i.video_tag"));
		$select->where("ci.content_images_id=?", $content_images_id);
		
		$row = $this->db->fetchRow($select);
		
		return $row;
	}
	
	function updateContentImages($params)
	{
		$contentimagesTable = new content_images(array('db' => 'db'));

		$select = $contentimagesTable->select()->where("content_images_id=?", $params['content_images_id']);
		$oldData = $contentimagesTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'title'							=> $params['title'],
			'caption'						=> $params['caption'],
			'keywords'						=> $params['keywords'],
			'credits'						=> $params['credits']
		);
		$where = $contentimagesTable->getAdapter()->quoteInto('content_images_id = ?', $params['content_images_id']);
		$contentimagesTable->update($data, $where);	
		
		$this->addLog($params['content_images_id'], "Update", "Gallery Image", $oldData, $data, $data['title']);
	}
	
	function updateSourceSystemId($content_images_id, $source_system_id)
	{
		$contentimagesTable = new content_images(array('db' => 'db'));

		$data = array(
			'source_system_id'				=> $source_system_id
		);
		$where = $contentimagesTable->getAdapter()->quoteInto('content_images_id = ?', $content_images_id);
		$contentimagesTable->update($data, $where);	
	}
	
	/**
	 * Delete content images with the provided content_images_id
	 * 
	 * @param int $content_images_id
	 */
	function deleteContentImages($content_images_id)
	{
		$contentimagesTable = new content_images(array('db' => 'db')); //use db object from registry
		
		if ( is_numeric($content_images_id) && $content_images_id > 0 )
		{
			$select = $contentimagesTable->select()->where("content_images_id=?", $content_images_id);
			$oldData = $contentimagesTable->getAdapter()->fetchRow($select);
		
			$where = $contentimagesTable->getAdapter()->quoteInto('content_images_id = ?', $content_images_id);
			$contentimagesTable->delete($where);
			
			if(!empty($oldData['content_images_id']))
				$this->addLog($content_images_id, "Delete", "Gallery Image", $oldData, array(), $oldData['source_system_id']);
		}
	}
	
	/*function getImagesByArticleId($article_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.site_id","ca.article_id"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery"));
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id", array("cgi.site_id","cgi.content_gallery_images_id","cgi.content_images_id", "cgi.sequence"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id", "ci.title", "ci.caption", "ci.keywords", "ci.credits", "ci.views", "ci.modify_date_time", "ci.create_date_time"));
		$select->where('ca.article_id = ?', $article_id);
		$result = $this->db->fetchAll($select);
			
		return $result;
	}*/
}
?>