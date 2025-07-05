<?php

require_once('adminClass.php');
require_once('dbClass.php');

class articlesClass extends adminClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Gets an array of articles available in the currently selected site.
	 *
	 * @param int $site_id
	 * @return array
	 */
	function getContentArticles($params, $site_id)
	{		
		$strWhere = "";
		$article = "";
		/*if(!empty($params['keyword']))
		{
			if($params['field'] == "Any field")
			{
				$strWhere = $strWhere . "1=0 or ";
				if(is_numeric($params['keyword']))
				{
					$strWhere = $strWhere . $this->search("article_id", $params['option'], $params['keyword']) . " or ";	
					//$strWhere = $strWhere . $this->search("site_id", $params['option'], $params['keyword']) . " or ";		
					$strWhere = $strWhere . $this->search("article_priority_id", $params['option'], $params['keyword']) . " or ";	
					$strWhere = $strWhere . $this->search("exclude_from_slideshow", $params['option'], $params['keyword']) . " or ";	
				}
				$strWhere = $strWhere . $this->search("section_name", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("source_system_id", $params['option'], $params['keyword']) . " or ";	
				$strWhere = $strWhere . $this->search("headline", $params['option'], $params['keyword']) . " or ";	
				$strWhere = $strWhere . $this->search("subhead", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("byline", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("source", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("shortstory", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("article", $params['option'], $params['keyword']) . " or ";
				$strWhere = $strWhere . $this->search("pubdate", $params['option'], $params['keyword']);
				$article = "article, ";
			}
			else
			{			
				if(in_array($params['field'], array("article_id","section_id","article_priority_id","site_id","exclude_from_slideshow")) && !is_numeric($params['keyword']))
				{
					$strWhere = $strWhere . "1=0";					
				}
				else
				{
					$strWhere = $strWhere . $this->search($params['field'], $params['option'], $params['keyword']);
					
					if($params['field'] == "article" )
						$article = "article, ";
				}
			}
			$strWhere = "(" . $strWhere . ") and ";
		}
			
		//$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry

		$total = "select count(`article_id`) as total From `content_articles` inner join `content_sections` on content_articles.section_id = content_sections.section_id where " . $strWhere . "content_articles.site_id = " . $site_id;
		
		$result = $this->db->fetchRow($total);
		$rs['total'] = $result['total'];	
			
		$limit = "select ca.site_id, article_id, section_name, source_system_id, title, headline, subhead, 
				  byline, source, shortstory, ".$article."pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id,
				  csa.group_name, csa.custom_title as submenu, carea.area_name, carea.custom_title as area_custom_title, ha.description
				  From content_articles ca
				  left join content_sections cs on ca.section_id = cs.section_id 
				  left join content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
				  left join content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
				  left join homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
				  where " . $strWhere . " ca.site_id = " . $site_id . 
				  " ORDER BY `pubdate` DESC,  ca.article_id DESC, `article_priority_id` DESC limit ".$params['start'].",".$params['limit']; 
*/
		$now = date("Y-m-d", mktime(0,0,0,date("m"),date("d"),date("Y")));
		$comparedate = date("Y-m-d", mktime(0,0,0,date("m")-8,date("d"),date("Y")));
		
		if(!empty($params['start_date'])) {
			$tempStartDate = date_parse($params['start_date']);
			$tempEndDate = date_parse($params['end_date']);
			if(empty($tempStartDate['errors']) && empty($tempEndDate['errors'])) {
				$now = date("Y-m-d", strtotime($params['end_date']));
				$comparedate = date("Y-m-d", strtotime($params['start_date']));
			}
			
		}
		
		$localPrintedFilter = '';
		if(!empty($params['used_for_printed_paper'])) {
			$localPrintedFilter .= ' AND ca.used_for_printed_paper='.intval($params['used_for_printed_paper']).' ';
		}
		if(!empty($params['is_local_content'])) {
			$localPrintedFilter .= ' AND ca.is_local_content='.intval($params['is_local_content']). ' ';
		}
		
		$params['keyword'] = addslashes(stripslashes($params['keyword']));
		$sql = "
			SELECT SQL_CALC_FOUND_ROWS ca.site_id, ca.article_id, section_name, source_system_id, title, headline, subhead, 
		  		byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id,
		  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description,
		  		ca.is_local_content, ca.used_for_printed_paper
			FROM content_articles ca
			LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
			LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
			LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
			LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
			LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
			WHERE ca.site_id='{$site_id}' {$localPrintedFilter}
			ORDER BY pubdate DESC, ca.article_id DESC
			LIMIT {$params['start']}, {$params['limit']}
		";

		if(!empty($params['keyword'])) {
			if($params['field'] == "all") {
				$sql = "
					SELECT SQL_CALC_FOUND_ROWS cak.site_id, cak.article_id, section_name, source_system_id, ca.title, ca.headline, subhead, 
				  		ca.byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id, show_on_sectionfront,
				  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description,
				  		 MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('{$params['keyword']}*') AS score
					FROM content_article_keywords cak
					LEFT JOIN content_articles ca ON ca.article_id=cak.article_id
					LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
					LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
					LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
					LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
					LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
					WHERE MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('{$params['keyword']}*' IN BOOLEAN MODE) AND cak.site_id='{$site_id}' {$localPrintedFilter} AND ca.article_id IS NOT NULL
					".((!empty($params['exclude_article_id']))?" AND ca.article_id <> '{$params['exclude_article_id']}' AND ca.pubdate BETWEEN '{$comparedate} 00:00:00' AND '{$now} 23:59:59' ":"")."
					ORDER BY score DESC, pubdate DESC
					LIMIT {$params['start']}, {$params['limit']}
				";
			}
			else if($params['field'] == "article_id") {
				$params['keyword'] = intval($params['keyword']);
				$sql = "
					SELECT SQL_CALC_FOUND_ROWS ca.site_id, ca.article_id, section_name, source_system_id, title, headline, subhead, 
				  		byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id, show_on_sectionfront,
				  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description
					FROM content_articles ca
					LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
					LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
					LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
					LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
					LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
					WHERE ca.article_id LIKE '{$params['keyword']}%' AND ca.site_id='{$site_id}' {$localPrintedFilter}
					ORDER BY pubdate DESC, ca.article_id DESC
					LIMIT {$params['start']}, {$params['limit']}
				";
			}
			else if($params['field'] == "section_name") {
				$sql = "
					SELECT SQL_CALC_FOUND_ROWS ca.site_id, ca.article_id, section_name, source_system_id, title, headline, subhead, 
				  		byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id, show_on_sectionfront,
				  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description
					FROM content_articles ca
					LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
					LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
					LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
					LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
					LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
					WHERE section_name LIKE '{$params['keyword']}%' AND ca.site_id='{$site_id}' {$localPrintedFilter}
					ORDER BY pubdate DESC, ca.article_id DESC
					LIMIT {$params['start']}, {$params['limit']}
				";
			}
			else if($params['field'] == "headline") {
				$sql = "
					SELECT SQL_CALC_FOUND_ROWS cak.site_id, cak.article_id, section_name, source_system_id, ca.title, ca.headline, subhead, 
				  		ca.byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id, show_on_sectionfront,
				  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description,
				  		 MATCH(cak.title) AGAINST('{$params['keyword']}*') AS score
					FROM content_article_keywords cak
					LEFT JOIN content_articles ca ON ca.article_id=cak.article_id
					LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
					LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
					LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
					LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
					LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
					WHERE MATCH(cak.title) AGAINST('{$params['keyword']}*' IN BOOLEAN MODE) AND cak.site_id='{$site_id}' {$localPrintedFilter} AND ca.article_id IS NOT NULL
					ORDER BY score DESC, pubdate DESC, cak.article_id DESC
					LIMIT {$params['start']}, {$params['limit']}
				";
			}
			else if($params['field'] == "pubdate") {
				$temp = date_parse($params['keyword']);
				
				if(empty($temp['error_count'])) {					
					$date = $temp['year']."-".str_pad($temp['month'],2,"00",STR_PAD_LEFT)."-".str_pad($temp['day'],2,"00",STR_PAD_LEFT);
					if(!empty($temp['hour']) && !empty($temp['minute']) && !empty($temp['second'])) $date .= " ".str_pad($temp['hour'],2,"00",STR_PAD_LEFT).":".str_pad($temp['minute'],2,"00",STR_PAD_LEFT).":".str_pad($temp['second'],2,"00",STR_PAD_LEFT);
					else if(!empty($temp['hour']) && !empty($temp['minute'])) $date .= " ".str_pad($temp['hour'],2,"00",STR_PAD_LEFT).":".str_pad($temp['minute'],2,"00",STR_PAD_LEFT).":00";
					else if(!empty($temp['hour'])) $date .= " ".str_pad($temp['hour'],2,"00",STR_PAD_LEFT).":00:00";
					$sql = "
						SELECT SQL_CALC_FOUND_ROWS ca.site_id, ca.article_id, section_name, source_system_id, title, headline, subhead, 
					  		byline, source, shortstory, pubdate, article_priority_id, exclude_from_slideshow, slideshow_content_images_id, show_on_sectionfront,
					  		csa.group_name, csa.custom_title as submenu, COALESCE(carea.area_name, ca1.area_name) AS area_name, carea.custom_title as area_custom_title, ha.description
						FROM content_articles ca
						LEFT JOIN content_sections cs on ca.section_id = cs.section_id 
						LEFT JOIN content_section_area csa on csa.section_id = cs.section_id and csa.site_id = ca.site_id
						LEFT JOIN content_areas carea on carea.area_id = csa.area_id and carea.site_id = ca.site_id
						LEFT JOIN content_areas ca1 ON ca1.section_id=ca.section_id
						LEFT JOIN homepage_area ha on ha.section_id = cs.section_id and ha.site_id = ca.site_id
						WHERE pubdate LIKE '{$date}%' AND ca.site_id='{$site_id}' {$localPrintedFilter}
						ORDER BY pubdate DESC, ca.article_id DESC
						LIMIT {$params['start']}, {$params['limit']}
					";
				}				
			}
		}
		$rs['data'] = $this->db->fetchAll($sql);
		$rs['total'] = $this->db->fetchOne("SELECT FOUND_ROWS()");
		return $rs;
	}

	
	function search($field, $option, $keyword)
	{
		$strWhere = "";
		if($option == "Empty")
		{	
			if(in_array($field, array("article","description")))
				$strWhere = $strWhere . "(`".$field."` is null or `".$field."`='')";
			else
				$strWhere = $strWhere . "`".$field."` is null";
		}
		else
		{
			if(in_array($field, array("article","description")))
			{
				$strField = "upper(`" . $field . "`)";
				// Contains
				if($option == "Contains")
					$strKeyword = "upper('%" . $keyword . "%')";
				// Starts with ...
				elseif($params['option'] == "Starts with ...")
					$strKeyword = "upper('" . $keyword . "%')";
				else
					$strKeyword = "upper('" . $keyword . "')";
			}
			else	
			{
				$strField = "`" . $field . "`";
				// Contains
				if($option == "Contains")
					$strKeyword = "'%" . $keyword . "%'";
				// Starts with ...
				elseif($option == "Starts with ...")
					$strKeyword = "'".$keyword . "%'";
				else
					$strKeyword = "'". $keyword ."'";
			}
			
			$strWhere = $strWhere . $strField;
				
			// Contains 					Starts with ...
			if($option == "Contains" || $option == "Starts with ...")
				$strWhere = $strWhere . " like ";
			// Equals
			if($option == "Equals")
				$strWhere = $strWhere . "=";
			// More than ...
			if($option == "More than ...")
				$strWhere = $strWhere . ">";
			// Less than ...
			if($option == "Less than ...")
				$strWhere = $strWhere . "<";
			// Equal or more than ...
			if($option == "Equal or more than ...")
				$strWhere = $strWhere . ">=";
			// Equal or less than ...
			if($option == "Equal or less than ...")
				$strWhere = $strWhere . "<=";
			// Empty
			if($option == "Empty")
				$strWhere = $strWhere . "=";
				
			$strWhere = $strWhere . $strKeyword;
		}
		return $strWhere;
	}
	
	/**
	 * Gets an array of article approval available in the currently selected site.
	 *
	 * @return array
	 */
	function getArticleApproval()
	{		
		
		$articleApprovalTable = new article_approval(array('db' => 'db')); //use db object from registry

		$select = $articleApprovalTable->select();
		$rs = $this->db->fetchAll($select);
			
		return $rs;
	}
	
	/**
	 * Gets an array of article available in the currently selected id.
	 *
	 * @param int $article_id
	 * @return array
	 */
	function getArticleById($article_id)
	{		
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry	

		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "ca.section_id = cs.section_id");
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = cs.section_id and csa.site_id = ca.site_id", array("csa.group_name","csa.custom_title as submenu"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.area_id = csa.area_id and carea.site_id = ca.site_id", array("carea.area_name","carea.custom_title as area_custom_title"));
		$select->joinLeft(array("ha"=>"homepage_area"), "ha.section_id = cs.section_id and ha.site_id = ca.site_id", array("ha.description"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_id"));
		/*$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id", array("cag.content_article_galleries_id"));
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_id","cg.content_gallery"));*/
		$select->where('article_id = ?', $article_id);
		return $contentArticlesTable->getAdapter()->fetchRow($select);	
	}
	
	/**
	 * updating article by article_id
	 *
	 * @param int $params
	 * @return array
	 */
	function updateArticle($params)
	{		
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$searchtext = addslashes(stripslashes($params['title'].' '.$params['headline'].' '.$params['headline'].' '.strrev($params['headline']).' '.$params['byline'].' '.$params['byline'].' '.strip_tags($params['article'])));
		
		$section = $this->db->fetchRow("SELECT * FROM content_sections WHERE section_id='{$params['section_id']}'");
		
		$data = array(
			'section_id'			=> $params['section_id'],
			'title'					=> $params['title'],
			'headline'				=> $params['headline'],
			'short_headline'		=> $params['short_headline'],
			'subhead'				=> $params['subhead'],
			'byline'				=> $params['byline'],
			'source'				=> $params['source'],
			'shortstory'			=> $params['shortstory'],
			'article'				=> $params['article'],
			'pubdate'				=> $params['pubdate'],
			'article_priority_id'	=> $params['article_priority_id'],
			'exclude_from_slideshow'=> $params['exclude_from_slideshow'],
			'show_on_sectionfront'=> $params['show_on_sectionfront'],
			'exclude_from_2nd_slideshow'=> $params['exclude_from_2nd_slideshow'],
			'show_on_website'		=> $params['show_on_website'],
			'searchtext'			=> $searchtext,
			'slideshow_content_images_id'	=> intval($params['slideshow_content_images_id']),
			'slideshow2_content_images_id'	=> intval($params['slideshow2_content_images_id']),
			'related_link'			=> $params['related_link'],
			'related_link_title'	=> $params['related_link_title'],
			'content_before_article'=> $params['content_before_article'],
			'is_local_content'		=> intval($params['is_local_content']),
			'used_for_printed_paper'=> intval($params['used_for_printed_paper']),
			'modify_date_time'		=> date('Y-m-d H:i:s'),
			'keyword'				=> $params['keyword'],
		);
		
		if(empty($data['exclude_from_slideshow']) && !stripos(' '.$data['keyword'], 'Featured')) $data['keyword'] .= ',Featured';
		else if(!empty($data['exclude_from_slideshow'])) $data['keyword'] = str_ireplace(array(',Featured', 'Featured,'), '', $data['keyword']);
		if(!empty($section['section_name']) && stripos(' '.$section['section_name'], 'breaking') && !stripos($data['keyword'], 'Breaking')) $data['keyword'] .= ',Breaking';
		else if(empty($section['section_name']) || !stripos(' '.$section['section_name'], 'breaking')) $data['keyword'] = str_ireplace(array(',Breaking', 'Breaking,'), '', $data['keyword']);
		$data['keyword'] = trim($data['keyword'], ',');
		
		$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $params['article_id']);
		
		$select = $contentArticlesTable->select()->where("article_id=?", $params['article_id']);
		$oldData = $contentArticlesTable->getAdapter()->fetchRow($select);
		
		$contentArticlesTable->update($data, $where);
		
		$this->addLog($params['article_id'], "Update", "Article", $oldData, $data, $params['headline']);
		
		$contentArticleKeywordsTable = new content_article_keywords(array('db' => 'db'));
		$contentArticleKeywordsTable->update(array(
			"title"			=> $data['headline'],
			"byline"		=> $data['byline'],
			"search_text"	=> $data['searchtext'],
		), $contentArticleKeywordsTable->getAdapter()->quoteInto("article_id=?", $params['article_id']));
		
		
	}
	
	/**
	 * Gets an array of content article priorities
	 * @return array
	 */
	function getArticlePriority()
	{
		/*$articlepriorityTable = new content_article_priority(array('db' => 'db'));
		$dbObj = $this->db;

		$select = $articlepriorityTable->select();
		return $this->db->fetchAll($select);
		*/
		return array(
			array(
				'article_priority_id'		=> 1,
				'description'				=> 'Members Only',
				'priority'					=> 1,
			),
			array(
				'article_priority_id'		=> 2,
				'description'				=> 'Initially Free',
				'priority'					=> 2,
			),
			array(
				'article_priority_id'		=> 3,
				'description'				=> 'Always Free',
				'priority'					=> 3,
			),
		);
	}
	
	/**
	 * Inserts a new article to content_article table
	 * 
	 * @param array $params
	 */
	function addArticle($params, $site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$searchtext = addslashes(stripslashes($params['title'].' '.$params['headline'].' '.$params['headline'].' '.strrev($params['headline']).' '.$params['byline'].' '.$params['byline'].' '.strip_tags($params['article'])));
		if(empty($params['related_link'])) $params['related_link'] = '';
		if(empty($params['related_link_title'])) $params['related_link_title'] = '';
		if(empty($params['article_priority_id'])) $params['article_priority_id'] = 3;
		
		$section = $this->db->fetchRow("SELECT * FROM content_sections WHERE section_id='{$params['section_id']}' AND site_id='{$site_id}'");
		
		$data = array(
			'site_id'				=> $site_id,
			'section_id'			=> $params['section_id'],
			'title'					=> $params['title'],
			'headline'				=> $params['headline'],
			'short_headline'		=> $params['short_headline'],
			'subhead'				=> $params['subhead'],
			'byline'				=> $params['byline'],
			'source'				=> $params['source'],
			'shortstory'			=> $params['shortstory'],
			'article'				=> $params['article'],
			'pubdate'				=> $params['pubdate'],
			'article_priority_id'	=> $params['article_priority_id'],
			'exclude_from_slideshow'=> $params['exclude_from_slideshow'],
			'show_on_sectionfront'  => intval($params['show_on_sectionfront']),
			'show_on_website'		=> $params['show_on_website'],
			'searchtext'			=> $searchtext,
			'related_link'			=> $params['related_link'],
			'related_link_title'	=> $params['related_link_title'],
			'content_before_article'=> $params['content_before_article'],
			'is_local_content'		=> intval($params['is_local_content']),
			'used_for_printed_paper'=> intval($params['used_for_printed_paper']),
			'keyword'				=> $params['keyword'],
			'source_system_id'		=> time().rand(10,99),
			'modify_date_time'		=> date('Y-m-d H:i:s'),
			'create_date_time'		=> date('Y-m-d H:i:s'),
		);
		
		if(empty($data['exclude_from_slideshow']) && !stripos(' '.$data['keyword'], 'Featured')) $data['keyword'] .= ',Featured';
		else if(!empty($data['exclude_from_slideshow'])) $data['keyword'] = str_ireplace(array(',Featured', 'Featured,'), '', $data['keyword']);
		if(!empty($section['section_name']) && stripos(' '.$section['section_name'], 'breaking') && !stripos($data['keyword'], 'Breaking')) $data['keyword'] .= ',Breaking';
		else if(empty($section['section_name']) || !stripos(' '.$section['section_name'], 'breaking')) $data['keyword'] = str_ireplace(array(',Breaking', 'Breaking,'), '', $data['keyword']);
		$data['keyword'] = trim($data['keyword'], ',');
		
		$contentArticlesTable->insert($data);
		
		$articleId = $this->db->lastInsertId();
		
		$this->addLog($articleId, "Add", "Article", array(), $data, $params['headline']);
		
		$contentArticleKeywordsTable = new content_article_keywords(array('db' => 'db'));
		$contentArticleKeywordsTable->insert(array(
			"article_id"	=> $articleId,
			"site_id"		=> $site_id,
			"title"			=> $data['headline'],
			"byline"		=> $data['byline'],
			"keyword"		=> '',
			"search_text"	=> $data['searchtext'],
		));
		
		return $articleId;
	}
	
	/**
	 * Delete articles with the provided article_id
	 * 
	 * @param int $article_id
	 */
	function deleteArticles($article_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		$contentArticleKeywordTable = new content_article_keywords(array('db'=>'db'));
		if ( is_numeric($article_id) && $article_id > 0 )
		{
			$select = $contentArticlesTable->select()->where("article_id=?", $article_id);
			$oldData = $contentArticlesTable->getAdapter()->fetchRow($select);
			
			if(!empty($oldData['article_id']))
				$this->addLog($oldData['article_id'], "Delete", "Article", $oldData, array(), $oldData['headline']);
			
			$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $article_id);
			$contentArticlesTable->delete($where);
			
			$where = $contentArticleKeywordTable->getAdapter()->quoteInto("article_id=?", $article_id);
			$contentArticleKeywordTable->delete($where);
		}
	}
	
	function getArticlesBySite($site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$select = $contentArticlesTable->select()
				  ->where("site_id=?", $site_id);
		$rs = $this->db->fetchAll($select);
		return $rs;
	}
	
	function addContentArticleGalleries($site_id, $content_articles_id, $content_gallery_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'site_id'				=> $site_id,
			'content_articles_id'	=> $content_articles_id,
			'content_gallery_id'	=> $content_gallery_id
		);
		
		$contentArticlesTable->insert($data);
	}
	
	function updatePhotoLinkId($article_id, $photo_link_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'photo_link_id'			=> $photo_link_id
		);
		$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $article_id);
		
		$contentArticlesTable->update($data, $where);
	}
	
	function getLatestNews($siteid, $dayreduction, $limit = 5)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-$dayreduction,date("Y")));
		$comparedate = date("Y-m-d", mktime(0,0,0,date("m")-1,date("d")-$dayreduction,date("Y")));
		
		$sql = "select article_id,headline,cap.priority, ca.article, ci.source_system_id as image_name  
			from content_articles ca
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			where ca.site_id = ".$siteid." and pubdate BETWEEN '{$comparedate}' AND '{$now}' 
			group by article_id order by article_id desc limit {$limit}";
		
		$result = $this->db->fetchAll($sql);
		
		//for($i = 0; $i < count($result); $i++) {
		foreach ($result as &$r) {
			$content = $r["article"];
			$content = str_replace("\n\n","\n", $content);
			$content = str_replace("\r\r","\n", $content);
			$content = str_replace("\n\r\n\r","\n", $content);
			$content = str_replace("\r\n\r\n","\n", $content);
			$strPosClosingPara = strpos($content, "</p>");
			if(!empty($strPosClosingPara)) {
				$tempContent = "";
				$temp = explode("</p>", $content);
				$para = 0;
				for($j = 0; $j < count($temp) && $para < 1; $j++) {
					$tempPara = trim(strip_tags($temp[$j]));
					if(strlen($tempPara)>20) {
						$para++;
						$tempContent .= $tempPara."<br/>";
					}
				}
				$content = $tempContent;
			}
			else {
				$temp = explode("\n", $content);
				$content = strip_tags($temp[0])."\n\n".strip_tags($temp[1]);
			}
			$r["first_para"] = $content;
		}
		return $result;
	}
	
	function getArticlesByArea($site_id, $areaId, $dayreduction, $start = 0,$limit = 5, $prioritylow=0, $priorityhigh=10, $photoOnly = false) {
		$now = date("Y-m-d", mktime(0,0,0,date("m"),date("d")-$dayreduction,date("Y")));
		$comparedate = date("Y-m-d", mktime(0,0,0,date("m")-1,date("d")-$dayreduction,date("Y")));
		
		$sql = "
			SELECT ca.*, cs.section_name
			FROM content_areas cas
			LEFT JOIN content_section_area csa ON csa.area_id=cas.area_id
			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id AND cs.section_name NOT IN ('Obituaries')
			LEFT JOIN content_articles ca ON ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			WHERE cas.site_id={$site_id} AND cas.area_id={$areaId} ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= "  
			GROUP BY source_system_id
			ORDER BY ca.pubdate DESC
			LIMIT {$start}, {$limit}
		";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			$content = $r["article"];
			$content = str_replace("\n\n","\n", $content);
			$content = str_replace("\r\r","\n", $content);
			$content = str_replace("\n\r\n\r","\n", $content);
			$content = str_replace("\r\n\r\n","\n", $content);
			$strPosClosingPara = strpos($content, "</p>");
			if(!empty($strPosClosingPara)) {
				$tempContent = "";
				$temp = explode("</p>", $content);
				$para = 0;
				for($j = 0; $j < count($temp) && $para < 1; $j++) {
					$tempPara = trim(strip_tags($temp[$j]));
					if(strlen($tempPara)>20) {
						$para++;
						$tempContent .= $tempPara."<br/>";
					}
				}
				$content = $tempContent;
			}
			else {
				$temp = explode("\n", $content);
				$content = strip_tags($temp[0])."\n\n".strip_tags($temp[1]);
			}
			$r["first_para"] = $content;
		}
		
		return $result;
	}
	
	function getTopReadStories($siteid, $limit, $startdate, $enddate=false)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$sql = "select article_id,cs.section_name,cs.section_id,headline,ca.views
			from content_articles ca
			join content_sections cs on cs.section_id=ca.section_id
			join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			where ca.site_id = ".$siteid;
			
		if(!$enddate)
		{
			$sql = $sql . " and pubdate = '".$startdate."'";
		}
		else
		{
			$sql = $sql . " and pubdate >= '".$startdate."' and pubdate <= '".$enddate."'";
		}
		
		$sql = $sql . " order by ca.views desc limit ".$limit;
		
		$result = $this->db->fetchAll($sql);

		return $result;
	}
	
	function getArticleWithImages($site_id, $params)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry	
		
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id");
		$select->where('ca.site_id = ?', $site_id);
		$select->where('cgi.content_images_id is not null');
		$select->where("ca.headline like '%".$params['query']."%'");
		$select->group('article_id');
		return $contentArticlesTable->getAdapter()->fetchAll($select);
	}
	
	function getSlideshow($site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));

		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array('ca.article_id', 'ca.headline', 'ca.pubdate', 'ca.exclude_from_slideshow', 'ca.slideshow_order'));
		$select->joinLeft(array("cs"=>"content_sections"), "ca.section_id = cs.section_id");
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = cs.section_id and csa.site_id = ca.site_id", array("csa.group_name","csa.custom_title as submenu"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.area_id = csa.area_id and carea.site_id = ca.site_id", array("carea.area_name","carea.custom_title as area_custom_title"));
		$select->joinLeft(array("ha"=>"homepage_area"), "ha.section_id = cs.section_id and ha.site_id = ca.site_id", array("ha.description"));
		$select->where('ca.site_id = ?', $site_id);
		$select->order('ca.slideshow_order');
		$select->order(array('carea.custom_title', 'COALESCE(csa.custom_title, csa.group_name)', 'ca.pubdate desc'));
		$select->where('ca.exclude_from_slideshow = 0');
		return $contentArticlesTable->getAdapter()->fetchAll($select);
	}
	
	function excludeFromSlideshow($article_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'exclude_from_slideshow'			=> '1'
		);
		$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $article_id);
		
		$this->addLog($article_id, "Exclude", "Article From Slideshow", array(), array(), "");
		
		$contentArticlesTable->update($data, $where);
	}
	
	function get2ndSlideshow($site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));

		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array('ca.article_id', 'ca.headline', 'ca.pubdate', 'ca.exclude_from_slideshow', 'ca.slideshow2_order'));
		$select->joinLeft(array("cs"=>"content_sections"), "ca.section_id = cs.section_id");
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = cs.section_id and csa.site_id = ca.site_id", array("csa.group_name","csa.custom_title as submenu"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.area_id = csa.area_id and carea.site_id = ca.site_id", array("carea.area_name","carea.custom_title as area_custom_title"));
		$select->joinLeft(array("ha"=>"homepage_area"), "ha.section_id = cs.section_id and ha.site_id = ca.site_id", array("ha.description"));
		$select->where('ca.site_id = ?', $site_id);
		$select->order('ca.slideshow2_order');
		$select->order(array('carea.custom_title', 'COALESCE(csa.custom_title, csa.group_name)', 'ca.pubdate desc'));
		$select->where('ca.exclude_from_2nd_slideshow = 0');
		return $contentArticlesTable->getAdapter()->fetchAll($select);
	}
	
	function excludeFrom2ndSlideshow($article_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		$data = array(
			'exclude_from_2nd_slideshow'			=> '1'
		);
		$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $article_id);
		
		$this->addLog($article_id, "Exclude", "Article From 2nd Slideshow", array(), array(), "");
		
		$contentArticlesTable->update($data, $where);
	}
	
	function excludeFromSectionFrontArticle($siteId, $articleId) {
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$data = array(
			'show_on_sectionfront'			=> 0
		);
		$where = array();
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $articleId);
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('site_id = ?', $siteId);
		
		$this->addLog($articleId, "Exclude", "Article From Section Front", array(), array(), "");
		
		$contentArticlesTable->update($data, $where);
	}
	
	function getSectionFrontArticles($siteId, $keywords = '') {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d"),date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-5,date("d"),date("Y")));
		
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array('ca.article_id', 'ca.headline', 'ca.pubdate', 'ca.exclude_from_slideshow'));
		$select->joinLeft(array("cs"=>"content_sections"), "ca.section_id = cs.section_id");
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = cs.section_id and csa.site_id = ca.site_id", array("csa.group_name","csa.custom_title as submenu"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.area_id = csa.area_id and carea.site_id = ca.site_id", array("carea.area_name","carea.custom_title as area_custom_title"));
		$select->joinLeft(array("ha"=>"homepage_area"), "ha.section_id = cs.section_id and ha.site_id = ca.site_id", array("ha.description"));
		$select->joinLeft(array("carea1"=>"content_areas"), "carea1.section_id = ca.section_id and carea1.site_id = ca.site_id", array("carea1.area_name AS area_name1","carea1.custom_title as area_custom_title1"));
		//$select->joinLeft(array("rts"=>"right_text_sliders"), "rts.article_id=ca.article_id AND rts.site_id=ca.site_id", array("GROUP_CONCAT(rts.order_id SEPARATOR ', ') AS order_ids"));
		if(!empty($keywords)) $select->joinLeft(array("cak"=>"content_article_keywords"), "cak.article_id=ca.article_id AND cak.site_id=ca.site_id", array("MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('{$keywords}') AS score"));
		$select->where('ca.site_id = ?', $siteId);
		
		$select->where("ca.pubdate > '{$comparedate}' ");
		
		$select->where('ca.show_on_sectionfront = 1');
		if(!empty($keywords)) $select->where("MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('".$keywords."' IN BOOLEAN MODE)");
		$select->group(array("ca.source_system_id"));
		//$select->order(array('carea.custom_title', 'COALESCE(csa.custom_title, csa.group_name)', 'ca.pubdate desc'));
		$select->order(array('ca.pubdate DESC'));
		return $contentArticlesTable->getAdapter()->fetchAll($select);
	}
	
	function updateSlideshowOrder($siteId, $params) {
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$data = array(
			'slideshow_order'			=> $params->slideshow_order
		);
		$where = array();
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $params->article_id);
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('site_id = ?', $siteId);
		
		$this->addLog($articleId, "Update", "Slideshow Articles Order From Section Front", array(), array(), "");
		
		$contentArticlesTable->update($data, $where);
	}
	
	function updateSlideshow2Order($siteId, $params) {
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$data = array(
			'slideshow2_order'			=> $params->slideshow2_order
		);
		$where = array();
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $params->article_id);
		$where[] = $contentArticlesTable->getAdapter()->quoteInto('site_id = ?', $siteId);
		
		$this->addLog($articleId, "Update", "2nd Slideshow Articles Order From Section Front", array(), array(), "");
		
		$contentArticlesTable->update($data, $where);
	}
}