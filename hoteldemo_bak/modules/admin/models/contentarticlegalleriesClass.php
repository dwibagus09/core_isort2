<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentarticlegalleriesClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	function setContentArticleGalleries($site_id, $params)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry
		
		if(empty($params['content_article_galleries_id']))
		{
			$data = array(
				'site_id'				=> $site_id,
				'content_articles_id'	=> $params['article_id'],
				'content_gallery_id'	=> $params['content_gallery_id']
			);
			
			$contentArticleGalleriesTable->insert($data);
		}
		else
		{
			$data = array(
				'content_gallery_id'	=> $params['content_gallery_id']
			);
			$where = $contentArticleGalleriesTable->getAdapter()->quoteInto('content_article_galleries_id = ?', $params['content_article_galleries_id']);
			$contentArticleGalleriesTable->update($data, $where);
		}
	}
	
	function deleteContentArticleGalleries($article_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry

		if ( is_numeric($article_id) && $article_id > 0 )
		{
			$where = $contentArticleGalleriesTable->getAdapter()->quoteInto('content_articles_id = ?', $article_id);
			$contentArticleGalleriesTable->delete($where);
		}
	}
	
	function getContentArticleGalleries($article_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry

		$select = $contentArticleGalleriesTable->select();
		$select->where('content_articles_id = ?', $article_id);
		return $this->db->fetchAll($select);
	}
	
	function getImages($article_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry
		$select = $contentArticleGalleriesTable->getAdapter()->select();
		$select->from(array("cag"=>"content_article_galleries"), array("cag.*"));
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id", array("cgi.*"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.*"));
		//$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.*"));
		$select->where('cag.content_articles_id = ?', $article_id);
		return $this->db->fetchAll($select);
	}
}
?>