<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class relatedarticlesClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	function getRelatedArticles($site_id, $article_id)
	{
		$raTable = new related_articles(array('db'=>'db'));
		
		$select = $raTable->select();
		$select->where("site_id=?", $site_id);
		$select->where("article_id=?", $article_id);

		$rs = $this->db->fetchAll($select);
		return $rs;
	}
	
}

?>