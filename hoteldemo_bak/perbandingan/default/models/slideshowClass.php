<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class slideshowClass extends defaultClass
{
	function getSlideshow($site_id, $limit = 6)
	{
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-5,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$slideshow = new slideshow(array('db'=>'db'));
		$select = $slideshow->getAdapter()->select();
		
		$select->from(array("s"=>"slideshow"), array("s.*"));
		$select->joinLeft(array("ca"=>"content_articles"), "ca.article_id = s.article_id", array("ca.*"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_type_id"));
		/*$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id");
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id"));
		*/
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id AND ci.site_id={$site_id}", array("ci.source_system_id", "ci.source_system_id AS image_name"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag", "i.image_class_id"));
		$select->where('s.site_id = ?', $site_id);
		
		$select->where("ca.pubdate BETWEEN '{$comparedate}' AND '{$now}'");		
		
		$select->where('ca.show_on_website = 1');
		/*$select->group('ca.article_id');*/
		$select->group(array("ca.source_system_id"));
		//$select->order('sort_order');
		$select->order('ca.slideshow_order');
		
		$select->limit($limit);
		return array();
		
		$rs = $slideshow->getAdapter()->fetchAll($select);
		return $rs;
	}	
	
	function getSlideshowByArea($site_id, $area_id, $limit = 6)
	{
		$slideshow = new slideshow(array('db'=>'db'));
		$select = $slideshow->getAdapter()->select();
		
		$select->from(array("s"=>"slideshow"), array("s.*"));
		$select->joinLeft(array("ca"=>"content_articles"), "ca.article_id = s.article_id", array("ca.*"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		/*$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_type_id"));
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id");
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id"));
		*/
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_type_id"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id AND ci.site_id={$site_id}", array("ci.source_system_id", "ci.source_system_id AS image_name"));
		
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag", "i.image_class_id"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.section_id = ca.section_id");
		$select->where('s.site_id = ?', $site_id);
		$select->where('ca.show_on_website = 1');
		$select->where('carea.area_id = ?', $area_id);
		/*$select->group('ca.article_id');*/
		$select->group(array("ca.source_system_id"));
		//$select->order('sort_order');
		$select->order('ca.slideshow2_order');
		$select->limit($limit);
		
		$rs = $slideshow->getAdapter()->fetchAll($select);
		return $rs;
	}
}
?>