<?php

require_once('adminClass.php');
require_once('dbClass.php');

class contentgalleryClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getContentGallery($site_id, $params)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));

		//$select = $contentgalleryTable->select()
		//	->where('site_id = ?', $site_id);
		$select = $contentgalleryTable->getAdapter()->select();
		$select->from(array("cg"=>"content_gallery"), array("cg.*"));
		$select->joinLeft(array("cgt"=>"content_gallery_type"), "cgt.content_gallery_type_id = cg.content_gallery_type_id", array("cgt.content_gallery_type"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.site_id=cg.site_id AND cag.content_gallery_id=cg.content_gallery_id", array());
		$select->joinLeft(array("ca"=>"content_articles"), "ca.article_id=cag.content_articles_id", array("GROUP_CONCAT(DISTINCT CONCAT(cs.section_name, ': ', ca.headline) SEPARATOR '<br>') AS headline"));
		$select->joinLeft(array("cs"=>"content_sections"), "ca.section_id=cs.section_id", array());
		$select->joinLeft(array("cgs"=>"content_gallery_sites"), "cgs.content_gallery_id=cg.content_gallery_id", array("GROUP_CONCAT(DISTINCT cgs.site_id SEPARATOR ',') AS sites"));
		if(!empty($this->site_group_id)) {
			$select->joinLeft(array("s"=>"sites"), "s.site_id=cg.site_id", "s.site_group_id");
			if(!empty($params['startdatesearch']) && !empty($params['enddatesearch']))
				$select->where("cg.create_date_time BETWEEN '{$params['startdatesearch']} 00:00:00' AND '{$params['enddatesearch']} 23:59:59'", '');
			else if(!empty($params['startdatesearch']))
				$select->where('cg.create_date_time >= ?', $params['startdatesearch']);
			else if(!empty($params['enddatesearch']))
				$select->where('cg.create_date_time <= ?', $params['enddatesearch']);
			$select->where('s.site_group_id = ?', $this->site_group_id);
		}
		else {
			if(!empty($params['startdatesearch']) && !empty($params['enddatesearch']))
				$select->where("cg.create_date_time BETWEEN '{$params['startdatesearch']}' AND '{$params['enddatesearch']}'", '');
			else if(!empty($params['startdatesearch']))
				$select->where('cg.create_date_time >= ?', $params['startdatesearch']);
			else if(!empty($params['enddatesearch']))
				$select->where('cg.create_date_time <= ?', $params['enddatesearch']);
			$select->where('cg.site_id = ?', $site_id);
		}
		if(!empty($params['content_gallery_type_id']))
			$select->where('cg.content_gallery_type_id = ?', $params['content_gallery_type_id']);
		
		if(!empty($params['query']))
			$select->where('cg.content_gallery LIKE ?', '%'.$params['query'].'%');
		$select->group(array("cg.content_gallery_id"));
		$select->order('cg.content_gallery_id desc');
		$result = $this->db->fetchAll($select);
		
		$rs['total'] = count($result);
		
		if((!empty($params['start']) || $params['start'] == '0') && !empty($params['limit']))
			$select = $select . " limit ".$params['start'] . ", " . $params['limit']; 
		
		$rs['data'] = $this->db->fetchAll($select);
		
		return $rs;
	}
	
	function getContentGalleryType()
	{
		$contentgallerytypeTable = new content_gallery_type(array('db' => 'db'));

		$select = $contentgallerytypeTable->select();
		return $this->db->fetchAll($select);
	}
	
	function getContentGalleryById($content_gallery_id)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));

		$select = $contentgalleryTable->getAdapter()->select()->from(array("cg"=>"content_gallery"), "cg.*");
		$select->joinLeft(array("cgs"=>"content_gallery_sites"), "cgs.content_gallery_id=cg.content_gallery_id", array("GROUP_CONCAT(DISTINCT cgs.site_id SEPARATOR ',') AS sites"));
		$select->where('cg.content_gallery_id = ?', $content_gallery_id);
		$select->group(array("cg.content_gallery_id"));
			
		$rs = $this->db->fetchRow($select);
		return $rs;	
	}
	
	/**
	 * Inserts a new content gallery
	 * 
	 * @param array $params
	 */
	function addContentGallery($site_id, $params)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		
		$data = array(
			'site_id'						=> $site_id,
			'content_gallery_type_id'		=> $params['content_gallery_type_id'],
			'content_gallery'				=> $params['content_gallery'],
			'show_gallery_in_multimedia'	=> $params['show_gallery_in_multimedia'],
			"keywords"						=> $params['keywords'],
			"for_sale"						=> $params['for_sale'],
			"public"						=> (($params['public']=="true")?1:0),
			'create_date_time'				=> date('Y-m-d h:i:s')
		);
		if(!empty($params['smugmug_id'])) $data['smugmug_id'] = $params['smugmug_id'];
		if(!empty($params['smugmug_key'])) $data['smugmug_key'] = $params['smugmug_key'];
		if(strlen($params['smugmug_categoryid']) > 0) $data['smugmug_categoryid'] = intval($params['smugmug_categoryid']);
		
		$contentgalleryTable->insert($data);
		
		$contentGalleryId = $contentgalleryTable->getAdapter()->lastInsertId();
		
		$contentGallerySitesTable = new content_gallery_sites(array('db'=>'db'));
		$contentGallerySitesTable->delete($contentGallerySitesTable->getAdapter()->quoteInto("content_gallery_id=?", $contentGalleryId));
		$sites = explode(",", $params['sites']);
		if(is_array($sites)) foreach ($sites as $site) {
			$site = addslashes($site);
			$siteId = $contentGallerySitesTable->getAdapter()->fetchOne("SELECT site_id FROM sites WHERE `name`='{$site}'");
			if(empty($siteId)) continue;
			$contentGallerySitesTable->insert(array(
				"content_gallery_id"		=> $contentGalleryId,
				"site_id"					=> $siteId
			));
		}
		
		$contentGalleryKeywordTable = new content_gallery_keywords(array('db'=>'db'));
		$contentGalleryKeywordTable->insert(array(
			"content_gallery_id"		=> $contentGalleryId,
			"site_id"					=> $site_id,
			"title"						=> $params['content_gallery'],
		));
		
		$this->addLog($contentGalleryId, "Add", "Gallery", array(), $data, $params['content_gallery']);
		
		return $contentGalleryId;
	}
	
	function updateContentGallery($params)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		
		$select = $contentgalleryTable->select()->where("content_gallery_id=?", $params['content_gallery_id']);
		$oldData = $contentgalleryTable->getAdapter()->fetchRow($select);
		
		$data = array(
			'content_gallery_type_id'		=> $params['content_gallery_type_id'],
			'content_gallery'				=> $params['content_gallery'],
			'show_gallery_in_multimedia'	=> $params['show_gallery_in_multimedia'],
			"keywords"						=> $params['keywords'],
			"for_sale"						=> $params['for_sale'],
			"public"						=> (($params['public']=="true")?1:0),
			'modify_date_time'				=> date('Y-m-d H:i:s')
		);
		if(strlen($params['smugmug_categoryid']) > 0) $data['smugmug_categoryid'] = intval($params['smugmug_categoryid']);
		
		$where = $contentgalleryTable->getAdapter()->quoteInto('content_gallery_id = ?', $params['content_gallery_id']);
		
		$contentgalleryTable->update($data, $where);
		
		$this->addLog($params['content_gallery_id'], "Update", "Gallery", $oldData, $data, $data['content_gallery']);
		
		$contentGalleryKeywordTable = new content_gallery_keywords(array('db'=>'db'));
		$contentGalleryKeywordTable->update(array(
			"title"						=> $params['content_gallery'],
		), $contentGalleryKeywordTable->getAdapter()->quoteInto("content_gallery_id=?", $params['content_gallery_id']));
		
		$contentGallerySitesTable = new content_gallery_sites(array('db'=>'db'));
		$contentGallerySitesTable->delete($contentGallerySitesTable->getAdapter()->quoteInto("content_gallery_id=?", $params['content_gallery_id']));
		$sites = explode(",", $params['sites']);
		if(is_array($sites)) foreach ($sites as $site) {
			$site = addslashes($site);
			$siteId = $contentGallerySitesTable->getAdapter()->fetchOne("SELECT site_id FROM sites WHERE `name`='{$site}'");
			if(empty($siteId)) continue;
			$contentGallerySitesTable->insert(array(
				"content_gallery_id"		=> $params['content_gallery_id'],
				"site_id"					=> $siteId
			));
		}
		
	}
	
	function updateImageCount($content_gallery_id, $image_count)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		
		$data = array(
			'image_count'		=> $image_count
		);
		$where = $contentgalleryTable->getAdapter()->quoteInto('content_gallery_id = ?', $content_gallery_id);

		$contentgalleryTable->update($data, $where);
	}
	
	function updateThumbContentImage($content_gallery_id, $content_images_id)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		
		$data = array(
			'thumbnail_content_images_id'		=> $content_images_id
		);
		$where = $contentgalleryTable->getAdapter()->quoteInto('content_gallery_id = ?', $content_gallery_id);

		$contentgalleryTable->update($data, $where);
	}
	
	function updateArticleVideoFlag($content_gallery_id) {
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		$select = "SELECT * FROM content_article_galleries WHERE site_id='{$this->site_id}' AND content_gallery_id='{$content_gallery_id}'";
		$articles = $contentgalleryTable->getAdapter()->fetchAll($select);
		if(is_array($articles)) foreach($articles as $article) {
			$select = "
				SELECT cag.*
				FROM content_article_galleries cag
				LEFT JOIN content_gallery cg ON cag.content_gallery_id=cg.content_gallery_id
				LEFT JOIN content_gallery_images cgi ON cgi.content_gallery_id=cg.content_gallery_id
				LEFT JOIN images i ON i.source_id=cgi.content_images_id
				WHERE cag.site_id='{$this->site_id}' AND cag.content_articles_id='{$article['content_articles_id']}' AND i.image_class_id=1
			";
			$videos = $contentgalleryTable->getAdapter()->fetchAll($select);
			
			$hasVideo = 0;
			if(!empty($videos)) $hasVideo = 1;
			
			$contentgalleryTable->getAdapter()->query("UPDATE content_articles SET have_video='{$hasVideo}' WHERE article_id='{$article['content_articles_id']}'");
		}
	}
	
	function deleteContentGallery($content_gallery_id)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));
		
		if ( is_numeric($content_gallery_id) && $content_gallery_id > 0 )
		{
			$select = $contentgalleryTable->select()->where("content_gallery_id=?", $content_gallery_id);
			$oldData = $contentgalleryTable->getAdapter()->fetchRow($select);
		
			/*$where = $contentgalleryTable->getAdapter()->quoteInto('content_gallery_id = ?', $content_gallery_id);
			$contentgalleryTable->delete($where);*/
			$sql = "DELETE FROM content_gallery WHERE content_gallery_id='{$content_gallery_id}' ";
			if(!empty($this->site_group_id)) $sql .= " AND site_id IN (SELECT site_id FROM sites WHERE site_group_id='{$this->site_group_id}' )";
			else $sql .= " AND site_id={$this->site_id}";
			$contentgalleryTable->getAdapter()->query($sql);
			
			$sql = "DELETE FROM content_gallery_sites WHERE content_gallery_id='{$content_gallery_id}' ";
			$contentgalleryTable->getAdapter()->query($sql);
			
			if(!empty($oldData['content_gallery_id']))
				$this->addLog($content_gallery_id, "Delete", "Gallery", $oldData, array(), $oldData['content_gallery']);
		}
	}
	
	function getContentGalleryByArticleId($article_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry
		$select = $contentArticleGalleriesTable->getAdapter()->select();
		$select->from(array("cag"=>"content_article_galleries"), array("cag.*"));
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id");
		$select->where('cag.content_articles_id = ?', $article_id);
		$select->where('cg.content_gallery_id is not null');
		
		return $this->db->fetchAll($select);
	}
	
	/*function getContentGalleryWithImages($site_id, $params)
	{
		$contentgalleryTable = new content_gallery(array('db' => 'db'));

		//$select = $contentgalleryTable->select()
		//	->where('site_id = ?', $site_id);
		$select = $contentgalleryTable->getAdapter()->select();
		$select->from(array("cg"=>"content_gallery"), array("cg.content_gallery_id","cg.content_gallery"));
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cg.content_gallery_id");
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id"));
		$select->where('cg.site_id = ?', $site_id);
		if(!empty($params['query']))
			$select->where('cg.content_gallery LIKE ?', '%'.$params['query'].'%');
		$select->order('cg.content_gallery asc');
		$result = $this->db->fetchAll($select);
		$rs['total'] = count($result);
		
		if((!empty($params['start']) || $params['start'] == '0') && !empty($params['limit']))
			$select = $select . " limit ".$params['start'] . ", " . $params['limit']; 
		
		$rs['data'] = $this->db->fetchAll($select);
			
		return $rs;
	}*/

}
?>