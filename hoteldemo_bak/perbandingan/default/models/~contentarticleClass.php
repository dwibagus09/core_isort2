<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class contentArticleClass extends defaultClass
{
	function getLatestNews($siteid, $limit = 5)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-1,date("d")-$this->config->general->dayreduction,date("Y")));
		
		/*$sql = "select article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			from content_articles ca
			left join content_sections cs on cs.section_id=ca.section_id
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			left join images i on i.source_id = ci.content_images_id
			where ca.site_id = ".$siteid." and pubdate BETWEEN '{$comparedate}' AND '{$now}' and ca.show_on_website = '1'
			group by ca.source_system_id
			order by ca.pubdate DESC, article_id desc limit {$limit}";*/
		$sql = "select article_id,cs.section_name,cs.section_id,headline,ca.article_priority_id, cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			from content_articles ca
			left join content_sections cs on cs.section_id=ca.section_id
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			left join images i on i.source_id = ci.content_images_id
			where ca.site_id = ".$siteid." and pubdate BETWEEN '{$comparedate}' AND '{$now}' and ca.show_on_website = '1'
			group by ca.source_system_id
			order by ca.pubdate DESC, article_id desc limit {$limit}";
		
		$result = $this->db->fetchAll($sql);
		
		//for($i = 0; $i < count($result); $i++) {
		foreach ($result as &$r) {
			/*$content = $r["article"];
			$content = str_replace("\n\n","\n", $content);
			$content = str_replace("\r\r","\n", $content);
			$content = str_replace("\n\r\n\r","\n", $content);
			$content = str_replace("\r\n\r\n","\n", $content);
			$strPosClosingPara = strpos($content, "</p>");
			if(!empty($strPosClosingPara)) {
				$tempContent = "";
				$temp = explode("</p>", $content);
				$para = 0;
				for($j = 0; $j < count($temp) && $para < 3; $j++) {
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
			}*/
		
			$content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $r["article"]);
			$content = strip_tags($content, '<p><br>');
			$content = str_replace('<p>',' ',$content);
			$content = str_replace('</p>',' ',$content);
			$content = str_replace('<br>',' ',$content);
			$content = str_replace('<br/>',' ',$content);
			$content = trim($content);
			
			//$headline_cnt = str_word_count($r["headline"], 1);
			$headline_cnt = strlen($r["headline"]);
			if(!empty($r["image_name"]))
			{
				//$content = implode(' ', array_slice(explode(' ', $content), 0, 80-count($headline_cnt)));
				$content = substr($content, 0, 80-$headline_cnt);
			}
			else
			{
				//$content = implode(' ', array_slice(explode(' ', $content), 0, 120-count($headline_cnt)));
				$content = substr($content, 0, 120-$headline_cnt);
			}			
				
			$r["first_para"] = $content.'...';
			
			if(empty($r['image_name'])) {
				preg_match_all("|<img[^>]+>|U", $r["article"], $matches);
				if(is_array($matches[0])) foreach ($matches[0] as $img) {
					$imageStr = $img;
					$strPos = strpos($imageStr, 'src=');
					$strPos += 5;
					$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
					if(file_exists($this->config->paths->html.$src)) {
						list($width, $height) = getimagesize($this->config->paths->html.$src);
						if($width > 100 && $height > 100) {
							$r['image_name'] = str_replace("/images/article_photos/", "",$src);
							break;
						}
					}
				}
			}
		}

		return $result;
	}
	
	function getNewsTickers($siteid, $hours, $limit = 6)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H")-$hours, date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND ca.section_id <> '{$this->config->cms->bible->category_id}'
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id DESC 
			LIMIT {$limit}";
		
		$result = $this->db->fetchAll($sql);

		return $result;
	}

	function getInActions($siteid, $sectionIds, $limit = 5) {
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$getInActionsArr = array();
		if(is_array($sectionIds)) foreach ($sectionIds as $sectionId) {
			$sectionId = intval($sectionId);
			
			$section  = array();
			$section = $this->db->fetchRow("SELECT cs.*, ha.category_name 
				FROM content_sections cs 
				LEFT JOIN homepage_area ha ON ha.section_id=cs.section_id AND SUBSTRING(ha.area_name, 1, 13)='get_in_action'
				WHERE cs.section_id={$sectionId} AND cs.site_id={$siteid}");
			
			$sql = "select article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
				ca.pubdate, ca.article_priority_id
				from content_articles ca
				left join content_sections cs on cs.section_id=ca.section_id
				left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
				left join content_article_galleries cag on cag.content_articles_id = ca.article_id
				left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
				left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
				left join content_images ci on ci.content_images_id = cgi.content_images_id
				left join images i on i.source_id = ci.content_images_id
				where ca.site_id = ".$siteid." and (pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id={$sectionId} )  and ca.show_on_website = '1'
				group by article_id order by ca.pubdate DESC, article_id desc limit {$limit}";
			
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
				
				/*if(empty($r['image_name'])) {
					preg_match_all("|<img[^>]+>|U", $r["article"], $matches);
					if(is_array($matches[0])) foreach ($matches[0] as $img) {
						$imageStr = $img;
						$strPos = strpos($imageStr, 'src=');
						$strPos += 5;
						$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
						if(file_exists($this->config->paths->html.$src)) {
							list($width, $height) = getimagesize($this->config->paths->html.$src);
							if($width > 100 && $height > 100) {
								$r['image_name'] = str_replace("/images/article_photos/", "",$src);
								break;
							}
						}
					}
				}*/
			}
			
			$section['articles'] = $result;
			$getInActionsArr[] = $section;
		}

		return $getInActionsArr;
	}
	
	function getFeaturedArticles($siteid, $sectionId, $limit = 5) {
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sectionId = intval($sectionId);
		
		$section  = array();
		$section = $this->db->fetchRow("SELECT * FROM content_sections WHERE section_id={$sectionId} AND site_id={$siteid}");
		
		$sql = "select article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			from content_articles ca
			left join content_sections cs on cs.section_id=ca.section_id
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			left join images i on i.source_id = ci.content_images_id
			where ca.site_id = ".$siteid." and (pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id={$sectionId} )  and ca.show_on_website = '1'
			group by ca.source_system_id
			order by ca.pubdate DESC, article_id desc limit {$limit}";
		
		$result = $this->db->fetchAll($sql);
		
		//for($i = 0; $i < count($result); $i++) {
		foreach ($result as &$r) {
			/*$content = $r["article"];
			$content = str_replace("\n\n","\n", $content);
			$content = str_replace("\r\r","\n", $content);
			$content = str_replace("\n\r\n\r","\n", $content);
			$content = str_replace("\r\n\r\n","\n", $content);
			$strPosClosingPara = strpos($content, "</p>");
			if(!empty($strPosClosingPara)) {
				$tempContent = "";
				$temp = explode("</p>", $content);
				$para = 0;
				for($j = 0; $j < count($temp) && $para < 2; $j++) {
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
			}*/
			/*$temp = explode("</p>", $r["article"]);
			$content="";
			foreach ($temp as $t)
			{
				$content .= "<p>".strip_tags($t)."</p>";
			}*/
			/*$content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $r["article"]);
			$content = strip_tags($content, '<p><br>');
			$content = str_replace('<p>',' ',$content);
			$content = str_replace('</p>',' ',$content);
			$content = str_replace('<br>',' ',$content);
			$content = str_replace('<br/>',' ',$content);*/
			$content = str_ireplace('</p>','</p> ',$r["article"]);
			$content = str_ireplace(array('<br>','<br/>','<br />'),' ',$content);
			$content = strip_tags($content, '<b><i><u><strong><em><strike>');
			$content = trim($content);
			
			//$headline_cnt = str_word_count($r["headline"], 1);
			$headline_cnt = strlen($r["headline"]);
			if(!empty($r["image_name"]))
			{
				//$content = implode(' ', array_slice(explode(' ', $content), 0, 80-count($headline_cnt)));
				$content = strip_tags($content);
				$content = substr($content, 0, 80-$headline_cnt);
			}
			else
			{
				//$content = implode(' ', array_slice(explode(' ', $content), 0, 120-count($headline_cnt)));
				$content = strip_tags($content);
				$content = substr($content, 0, 120-$headline_cnt);
			}
				
			$r["first_para"] = $content.'...';
			
			if(empty($r['image_name'])) {
				preg_match_all("|<img[^>]+>|U", $r["article"], $matches);
				if(is_array($matches[0])) foreach ($matches[0] as $img) {
					$imageStr = $img;
					$strPos = strpos($imageStr, 'src=');
					$strPos += 5;
					$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
					if(file_exists($this->config->paths->html.$src)) {
						list($width, $height) = getimagesize($this->config->paths->html.$src);
						if($width > 100 && $height > 100) {
							$r['image_name'] = str_replace("/images/article_photos/", "",$src);
							break;
						}
					}
				}
			}
		}
		
		$section['articles'] = $result;
		
		return $section;
	}
	
	function getNewsBySection($siteid, $section_id)
	{
		$sql = "select article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.pubdate, ca.article_priority_id
			from content_articles ca
			join content_sections cs on cs.section_id=ca.section_id
			join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			where ca.site_id = ".$siteid." and cs.section_id = '".$section_id."' and ca.show_on_website = '1' 
			order by cap.priority desc limit 8";

		$result = $this->db->fetchAll($sql);

		return $result;
	}
	
	function getPhotoGalleries($siteid, $section)
	{
		$sql = "select article_id,cs.section_name,cs.section_id,headline,byline,article,cap.priority, ci.source_system_id, ci.caption, ca.article_priority_id
				from content_articles ca 
				join content_sections cs on cs.section_id=ca.section_id 
				join content_article_priority cap on ca.article_priority_id=cap.article_priority_id 
				join content_article_galleries cag on ca.article_id=cag.content_articles_id
				join content_gallery cg on cag.content_gallery_id=cg.content_gallery_id
				join content_images ci on cg.thumbnail_content_images_id=ci.content_images_id
				where ca.site_id = ".$siteid." and cs.section_name = '".$section."' and ca.show_on_website = '1' 
				group by article_id order by cap.priority desc limit 10";

		$result = $this->db->fetchAll($sql);

		return $result;
	}

	function getArticle($siteid, $content_article_id) {
		
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$select = $contentArticleTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name","enable_rating","enable_comment"));
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = cs.section_id", array("csa.content_section_area_id","csa.section_id","csa.group_name", "csa.template as section_template", "csa.custom_title as section_custom_title", "csa.custom_url as section_custom_url"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.area_id = csa.area_id", array("carea.area_id","carea.area_name","carea.custom_url as area_custom_url","carea.template_name as area_template_name","carea.custom_title as area_custom_title","carea.section_id as area_section_id"));
		$select->joinLeft(array("carea2"=>"content_areas"), "carea2.section_id = cs.section_id", array("carea2.area_id as menu_area_id","carea2.area_name as menu_area_name","carea2.custom_url as menu_custom_url","carea2.template_name as menu_template_name","carea2.custom_title as menu_custom_title","carea2.section_id as menu_section_id"));
		$select->joinLeft(array("cap"=>"content_article_priority"), "cap.article_priority_id=ca.article_priority_id", "priority");
		$select->where('ca.article_id = ?', $content_article_id)
				->where('ca.site_id = ?', $siteid)
				->where('ca.show_on_website = "1"');
		$article = $this->db->fetchRow($select);
		
		$content = trim($article["article"]);
		$content = str_replace("<br/>","<br/><br/>", $content);
		$content = str_replace("\n\n","\n", $content);
		$content = str_replace("\r\r","\n", $content);
		$content = str_replace("\n\r\n\r","\n", $content);
		$content = str_replace("\r\n\r\n","\n", $content);
		if(strpos($content, "</p>")) { }
		else {
			$temp = explode("\n", $content);
			$content = "";
			foreach ($temp as $para) {
				$content .= "<p>".$para."</p>";
			}
		}
		
		$article["article"] = $content;
		
		$pubDate = date_parse($article["pubdate"]);
		$article["pubdate"] = date("l, j F Y H:i", mktime($pubDate["hour"], $pubDate["minute"], $pubDate["second"], $pubDate["month"], $pubDate["day"], $pubDate["year"]));
		$pubDate = date_parse($article["modify_date_time"]);
		$article["modify_date_time"] = date("l, j F Y H:i", mktime($pubDate["hour"], $pubDate["minute"], $pubDate["second"], $pubDate["month"], $pubDate["day"], $pubDate["year"]));
		
		return $article;
	}
	
	function setContentArticles($siteid, $params, $images = array(), $videos = array())
	{
		$content_articlesTable = new content_articles(array('db' => 'db')); //use db object from registry
		
		if(empty($params['photo_link_id']))
			$params['photo_link_id'] = null;
		
		$searchtext = addslashes(stripslashes($params['title'].' '.$params['headline'].' '.$params['headline'].' '.strrev($params['headline']).' '.$params['byline'].' '.$params['byline'].' '.$params['keyword'].' '.$params['keyword'].' '.strrev($params['keyword']).' '.strip_tags($params['article'])));
		//Check if the article is already in the DB
		$articleid=$this->getArticleIdbySourceSystemId($params['source_system_id'], $siteid, $params['section_id']);
		
		if (!empty($articleid))
		{ 
			$data = array(
			'site_id'			=> $siteid,
			'source_system_id'	=> $params['source_system_id'],
			'section_id'		=> $params['section_id'],
			'title'				=> $params['title'],
			'headline'			=> $params['headline'],
			'subhead'      		=> $params['subhead'],
			'byline'      		=> $params['byline'],
			'article'			=> $params['article'],
			'pubdate'			=> $params['pubdate'],
			'create_date_time'	=> date('Y-m-d H:i:s'),
			'photo_link_id'		=> $params['photo_link_id'],
			'article_priority_id' => $params['articlepriorityid'],
			'keyword' 			=> $params['keyword'],
			"searchtext"		=> $searchtext,
			);
			
			$where = $content_articlesTable->getAdapter()->quoteInto('article_id = ?', $articleid);
			$content_articlesTable->update($data, $where);
			
			$contentArticleKeywordsTable = new content_article_keywords(array('db' => 'db'));
			$contentArticleKeywordsTable->update(array(
				"title"			=> $data['headline'],
				"byline"		=> $data['byline'],
				"keyword"		=> $data['keyword'],
				"search_text"	=> $data['searchtext'],
			), $contentArticleKeywordsTable->getAdapter()->quoteInto("article_id=?", $articleid));
						
			return $articleid;
		}
		else { 
			$data = array(
			'site_id'			=> $siteid,
			'source_system_id'	=> $params['source_system_id'],
			'section_id'		=> $params['section_id'],
			'title'				=> $params['title'],
			'headline'			=> $params['headline'],
			'subhead'      		=> $params['subhead'],
			'byline'      		=> $params['byline'],
			'article'			=> $params['article'],
			'pubdate'			=> $params['pubdate'],
			'create_date_time'	=> date('Y-m-d H:i:s'),
			'photo_link_id'		=> $params['photo_link_id'],
			'article_priority_id'	=> $params['articlepriorityid'],
			'keyword' 			=> $params['keyword'],
			"searchtext"		=> $searchtext,
			);
			
			if($siteid==11) {
				$data['exclude_from_2nd_slideshow'] = 0;
				$data['show_on_sectionfront'] = 1;
			}
			else if($siteid==7) {
				$data['exclude_from_slideshow'] = 0;
			}
			
			$content_articlesTable->insert($data);
			$articleid = $this->db->lastInsertId();
			
			if($siteid==11 && empty($images) && empty($videos)) {
				$contentSectionArea = $content_articlesTable->getAdapter()->fetchRow("SELECT * FROM content_section_area WHERE section_id='{$params['section_id']}' AND site_id='{$this->siteid}'");
				if(!empty($contentSectionArea['content_section_area_id'])) {
					$content_articlesTable->getAdapter()->query("INSERT INTO right_text_sliders(site_id, article_id, content_area_id, content_section_area_id, order_id) VALUE ('{$this->siteid}', '{$articleid}', '{$contentSectionArea['area_id']}', '{$contentSectionArea['content_section_area_id']}', 0)");
					
					$row = $content_articlesTable->getAdapter()->fetchRow("SELECT * FROM right_text_sliders WHERE article_id='{$articleid}' AND content_area_id='{$contentSectionArea['area_id']}' AND content_section_area_id=0");
					if(empty($row['right_text_slider_id']) && !empty($contentSectionArea['area_id'])) {
						$content_articlesTable->getAdapter()->query("INSERT INTO right_text_sliders(site_id, article_id, content_area_id, content_section_area_id, order_id) VALUE ('{$this->siteid}', '{$articleid}', '{$contentSectionArea['area_id']}', '0', 0)");
					}
				}
			}
			
			$contentArticleKeywordsTable = new content_article_keywords(array('db' => 'db'));
			$contentArticleKeywordsTable->insert(array(
				"article_id"	=> $articleid,
				"site_id"		=> $siteid,
				"title"			=> $data['headline'],
				"byline"		=> $data['byline'],
				"keyword"		=> $data['keyword'],
				"search_text"	=> $data['searchtext'],
			));
			
			return $articleid;
		}
	}
	
	
	function getArticleIdbySourceSystemId($sourcesystemid, $site_id, $sectionId = 0)
	{
		if($sourcesystemid)
		{
			//get the most recent feature photo for the current section
			$sql = "select article_id from content_articles
				where source_system_id=".$sourcesystemid." and site_id=".$site_id;
			
			if(!empty($sectionId)) $sql .= " AND section_id='{$sectionId}' ";

			$rs = $this->db->fetchAll($sql);

			//If only one then return it
			if (count($rs)==1)
			{
				return $rs[0]['article_id'];
			}
			else {
				return 0;
			}
		}
		return 0;
	}
	

	function insertContentArticleGallery($site_id, $content_articles_id,$content_gallery_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry

		//Check for duplicate
		$select = $contentArticleGalleriesTable->select()
				->where('site_id = ?', $site_id)
				->where('content_gallery_id = ?', $content_gallery_id)
				->where('content_articles_id = ?', $content_articles_id);
						
		$result = $select->query()->fetchAll();
		
		if (count($result))
		{
			return $result[0]['content_article_galleries_id'];
		}
		else {
			$data = array(
				'content_articles_id'=>$content_articles_id,
				'content_gallery_id'=>$content_gallery_id,
				'site_id'=>$site_id
			);
			
			$contentArticleGalleriesTable->insert($data);
			return $this->db->lastInsertId();	//Return the id of the inserted record
		}

	}
	
	function getArticlesByArea($areaId, $start = 0,$limit = 5, $prioritylow=0, $priorityhigh=10, $photoOnly = false) {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "
			SELECT ca.*, cs.section_name
			FROM content_areas cas
			LEFT JOIN content_section_area csa ON csa.area_id=cas.area_id
			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id AND cs.section_name NOT IN ('Obituaries')
			LEFT JOIN content_articles ca ON ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			WHERE cas.site_id={$this->siteid} AND cas.area_id={$areaId} ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$start}, {$limit}
		";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			/*$content = $r["article"];
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
			$r["first_para"] = $content;*/
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($r['article'], 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($r['article'], 22)."&hellip;";
		}
		
		return $result;
	}
	
	function getRating($articleId) {
		$ratingTable = new rating(array('db'=>'db'));
		$select = $ratingTable->select()->where("source_id=?", $articleId);
		$rating = $ratingTable->getAdapter()->fetchRow($select);
		$rating["rating"] = number_format($rating["rating"], 2, '.', ',');
		return $rating;
	}
	
	function updateRating($articleId, $rate) {
		$ratingTable = new rating(array('db'=>'db'));
		$select = $ratingTable->select()->where("source_id=?", $articleId);
		$rating = $ratingTable->getAdapter()->fetchRow($select);
		if(empty($rating["rating_id"])) {
			$ratingTable->insert(array("rating_source_id"=>1,"source_id"=>$articleId,"rating"=>$rate,"count"=>1));
		}
		else {
			$cnt = $rating["count"]+1;
			$dbrate = ($rating["count"]*$rating["rating"] + $rate) / $cnt;
			$where = $ratingTable->getAdapter()->quoteInto("rating_id=?", $rating["rating_id"]);
			$ratingTable->update(array("rating"=>$dbrate,"count"=>$cnt), $where);
		}
	}
	
	/*function getComments($articleId) {
		$commentTable = new comments(array('db'=>'db'));
		$select = $commentTable->getAdapter()->select();
		$select->from(array("c"=>"comments"), "c.*");
		$select->joinLeft(array("u"=>"users"),"u.userid=c.userid", "CONCAT(u.firstname,' ',u.lastname) as fullname");
		$select->where("source_id=?", $articleId);
		$select->where("comment_approval_id=2");
		return $commentTable->getAdapter()->fetchAll($select);
	}
	
	function addComment($params, $site_id) {
		$commentTable = new comments(array('db'=>'db'));
		$commentTable->insert(array(
			"site_id"			=> $site_id,
			"comment_source_id"	=> 1,
			"source_id"			=> $params["article_id"],
			"userid"			=> $params["userid"],
			"comment_name"		=> $params["comment_name"],
			"comment"			=> $params["comment"],
			"comment_time"		=> date("Y-m-d H:i:s"),
			"comment_approval_id"=>1,
			"remote_address"	=> $_SERVER['REMOTE_ADDR']
		));
		self::updateStatistic($params["article_id"], 1);
	}*/
	
	function updateStatistic($articleId, $typeId) {
		$articleStatistic = new content_article_statistics(array('db'=>'db'));
		$select = $articleStatistic->select()->where("article_id=?", $articleId)->where("trans_date=?", date("Y-m-d H").":00:00")->where("type_id=?", $typeId);
		$statistic = $articleStatistic->getAdapter()->fetchRow($select);
		if(!empty($statistic["statistic_id"])) {
			$cnt = $statistic["cnt"]+1;
			$where = $articleStatistic->getAdapter()->quoteInto("statistic_id=?", $statistic["statistic_id"]);
			$articleStatistic->update(array("cnt"=>$cnt), $where);
		}
		else {
			$articleStatistic->insert(array(
				"site_id"	=> $this->siteid,
				"trans_date"=> date("Y-m-d H").":00:00",
				"article_id"=> $articleId,
				"type_id"	=> $typeId,
				"cnt"		=> 1
			));
		}
	}
	
	function getAreaLatestNews($siteid, $areaId, $prioritylow=0, $priorityhigh=10, $start = 0, $limit = 5) {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		// old select statement ca.*, cs.section_name
		/*$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			FROM content_areas cas
			LEFT JOIN content_section_area csa ON csa.area_id=cas.area_id
			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id AND cs.section_name NOT IN ('Obituaries')
			LEFT JOIN content_articles ca ON ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE cas.site_id={$this->siteid} AND cas.area_id={$areaId} ";*/
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_areas cas
			LEFT JOIN content_section_area csa ON csa.area_id=cas.area_id
			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id AND cs.section_name NOT IN ('Obituaries')
			LEFT JOIN content_articles ca ON ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE cas.site_id={$this->siteid} AND cas.area_id={$areaId} ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$start}, {$limit}
		";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			/*$content = $r["article"];
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
			$r["first_para"] = $content;*/
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($r['article'], 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($r['article'], 22)."&hellip;";
		}
		
		return $result;
	}

	
	function getArticlesBySection($sectionId, $start = 0,$limit = 5, $prioritylow=0, $priorityhigh=10, $photoOnly = false) {
		$limit = intval($limit);
		if(empty($limit)) $limit = 10;
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")-2));
		
		$sql = "
			SELECT SQL_CALC_FOUND_ROWS ca.*, cs.section_name, cgi.content_images_id
			FROM content_articles ca 
			LEFT JOIN content_sections cs ON";
		$sql .= " ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			WHERE ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id={$sectionId} AND ca.site_id={$this->siteid} AND cs.section_name NOT IN ('Obituaries') ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= " and ca.show_on_website = '1' GROUP BY ca.article_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$start}, {$limit}
		";
		
		$result["articles"] = $this->db->fetchAll($sql);
		
		for($i = 0; $i < count($result["articles"]); $i++) {
			$content = $result["articles"][$i]["article"];
			$content = str_replace("\n\n","\n", $content);
			$content = str_replace("\r\r","\n", $content);
			$content = str_replace("\n\r\n\r","\n", $content);
			$content = str_replace("\r\n\r\n","\n", $content);
			$strPosClosingPara = strpos($content, "</p>");
			if(!empty($strPosClosingPara)) {
				$tempContent = "";
				$temp = explode("</p>", $content);
				$para = 0;
				for($j = 0; $j < count($temp) && $para < 2; $j++) {
					$tempPara = trim(strip_tags($temp[$j]));
					if(strlen($tempPara)>20) {
						$para++;
						$tempContent .= $tempPara."<br/><br/>";
					}
				}
				if(strlen($tempContent) > 200)
					$content = substr(trim(strip_tags($content)), 0, 197)."...";
				else
					$content = $tempContent;
			}
			else {
				$temp = explode("\n", $content);
				$content = strip_tags($temp[0])."\n\n".strip_tags($temp[1]);
			}
			$result["articles"][$i]["first_para"] = $content;
			//$result["articles"][$i]["first_para"] = implode(' ', array_slice(explode(' ', trim(strip_tags($content))), 0, 100));
		}
		
		$sql = "SELECT FOUND_ROWS()";
		$result["count"] = $this->db->fetchOne($sql);
		$result["start"] = $start;
		$result["limit"] = $limit;
		
		return $result;
	}
	
	function getTopReadStories($siteid, $limit, $startdate, $enddate=false)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$sql = "select ca.article_id,ca.pubdate,cs.section_name,cs.section_id,headline,ca.views, ci.source_system_id, i.smugmug_id, i.smugmug_key,
			ca.article_priority_id
			from content_articles ca 
			join content_sections cs on cs.section_id=ca.section_id 
			join content_article_priority cap on ca.article_priority_id=cap.article_priority_id 
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			left join images i on i.source_id = cgi.content_images_id
			where ca.site_id = '".$siteid."'";
			
		if(!$enddate)
		{
			$sql = $sql . " and ca.pubdate = '".$startdate."'";
		}
		else
		{
			$sql = $sql . " and ca.pubdate >= '".$startdate." 00:00:00' and ca.pubdate <= '".$enddate." 23:59:59'";
		}
		
		$sql = $sql . " and ca.show_on_website = '1' order by ca.views desc limit ".$limit;
		$result = $this->db->fetchAll($sql);

		return $result;
	}
	
	function getTopReadStoriesByArea($siteid, $area_id, $limit, $startdate, $enddate=false)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$sql = "select article_id, csa.section_id,headline,ca.views, csa.area_id, ca.article_priority_id
			from content_articles ca 
			join content_section_area csa on csa.section_id=ca.section_id 
			join content_article_priority cap on ca.article_priority_id=cap.article_priority_id 
			where ca.site_id = ".$siteid." and csa.area_id=".$area_id;
			
		if(!$enddate)
		{
			$sql = $sql . " and pubdate = '".$startdate."'";
		}
		else
		{
			$sql = $sql . " and pubdate >= '".$startdate."' and pubdate <= '".$enddate."'";
		}
		
		$sql = $sql . " and ca.show_on_website = '1' group by article_id order by ca.views desc limit ".$limit;

		$result = $this->db->fetchAll($sql);

		return $result;
	}
	
	function getTopReadStoriesBySection($siteid, $section_id, $limit, $startdate, $enddate=false)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$sql = "select ca.article_id,ca.pubdate,cs.section_name,cs.section_id,headline,ca.views, ci.source_system_id, i.smugmug_id, i.smugmug_key,
				ca.article_priority_id
			from content_articles ca 
			join content_sections cs on cs.section_id=ca.section_id 
			join content_article_priority cap on ca.article_priority_id=cap.article_priority_id 
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			left join images i on i.source_id = cgi.content_images_id
			where ca.site_id = '".$siteid."' and cs.section_id = '".$section_id."'";
			
		if(!$enddate)
		{
			$sql = $sql . " and ca.pubdate = '".$startdate."'";
		}
		else
		{
			$sql = $sql . " and ca.pubdate >= '".$startdate." 00:00:00' and ca.pubdate <= '".$enddate." 23:59:59'";
		}
		
		$sql = $sql . " and ca.show_on_website = '1' order by ca.views desc limit ".$limit;
		$result = $this->db->fetchAll($sql);

		return $result;
	}
	
	
	function getArchive($site_id, $params)
	{
		/*if the keywords only contain 2 letters or less, just ignore it, won't provide any good search result*/
		if(strlen($params['keyword']) < 3) {
			$rs['data'] = array();
			$rs['total'] = 0;			
			$rs['params'] = $params;
			return $rs;
		}
		
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$keyword = "";
		if(!empty($params['keyword']) && $params['keyword'] != "Search articles ...") {
			$keyword = stripslashes($params['keyword']);
			if(!strpos($params['keyword'],' ')) $keyword .= '* '.strrev($params['keyword']).'*';
			$keyword = addslashes(stripslashes($keyword));
		}
		
		//$select = $contentArticleTable->select();
		$select = $contentArticleTable->getAdapter()->select();
		$select->from(array("cak"=>"content_article_keywords"), array("MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('".$keyword."') AS score"));
		$select->joinLeft(array("ca"=>"content_articles"), "ca.article_id=cak.article_id", array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name","enable_rating","enable_comment"));
		$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_type_id"));
		/*$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id");
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id as image_name"));*/
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cg.thumbnail_content_images_id", array("ci.source_system_id as image_name"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id","i.smugmug_key","i.youtube_id","i.image_class_id", "i.video_tag"));
		$select->where("cak.site_id=?", $site_id);
		
		if(!empty($params['keyword']) && $params['keyword'] != "Search articles ...")
		{
			//$select->where("headline LIKE '%".$params['keyword']."%' OR article LIKE '%".$params['keyword']."%' OR MATCH(byline) AGAINST('".$params['keyword']."' IN BOOLEAN MODE)");
			//$select->where("MATCH(ca.article, ca.headline, ca.byline) AGAINST('".$params['keyword']."' IN BOOLEAN MODE)");
			//$select->where("MATCH(ca.headline, ca.keyword, ca.searchtext) AGAINST('".$keyword."' IN BOOLEAN MODE)");
			$select->where("MATCH(cak.title, cak.byline, cak.keyword, cak.search_text) AGAINST('".$keyword."' IN BOOLEAN MODE)");
		}
		
		if($params['newsSection'] > 0)
			$select->where("ca.section_id=?", $params['newsSection']);
			
		$startdate = date_parse($params['startdate']);
		if(!empty($params['startdate']) && empty($startdate['errors']))
		{
			$select->where("ca.pubdate>=?", $startdate['year']."-".str_pad($startdate['month'], 2, '00', STR_PAD_LEFT)."-".str_pad($startdate['day'], 2, '00', STR_PAD_LEFT));
		}
		$enddate = date_parse($params['enddate']);
		if(!empty($params['enddate']) && empty($enddate['errors']))
		{
			$select->where("ca.pubdate<=?", $enddate['year']."-".str_pad($enddate['month'], 2, '00', STR_PAD_LEFT)."-".str_pad($enddate['day'], 2, '00', STR_PAD_LEFT));
		}
		
		$select->where("ca.show_on_website='1'");
		//$select->group("ca.article_id");
		$select->group("ca.source_system_id");
		//$select->order(array("MATCH(ca.article, ca.headline, ca.byline) AGAINST('".$params['keyword']."') DESC", "ca.pubdate DESC"));
		$select->order(array("score DESC", "ca.pubdate DESC", "ca.article_id DESC"));
		//$select = $select . " order by MATCH(ca.article, ca.headline, ca.byline) AGAINST('".$params['keyword']."') DESC, ca.pubdate DESC"; 
		$select->limit(5, intval($params['start']));
		
		$sql = $select->__toString();
		$sql = str_replace("SELECT ", "SELECT SQL_CALC_FOUND_ROWS ", $sql);
		
		$rs['data'] = $contentArticleTable->getAdapter()->fetchAll($sql);
		$rs['total'] = $contentArticleTable->getAdapter()->fetchOne("SELECT FOUND_ROWS()");
		
		/*$result = $contentArticleTable->getAdapter()->fetchAll($select);
		$rs['total'] = count($result);
		
		$limit = $select . ' limit ' . intval($params['start']) . ',5';
		
		$rs['data'] = $contentArticleTable->getAdapter()->fetchAll($limit);*/
		
		$rs['params'] = $params;
		
		return $rs;
	}
	
	function updateArticleView($article_id){
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$select = $contentArticleTable->select()
					->where("article_id=?", $article_id);
					
		$curview = $contentArticleTable->getAdapter()->fetchRow($select);
		$views = $curview["views"] + 1;
		
		$where = $contentArticleTable->getAdapter()->quoteInto("article_id=?", $article_id);
		$contentArticleTable->update(array("views"=>$views), $where);
	}
	
	function sendArticle($params, $emailSender)
	{
		//Create the message and link for the Email
		$this->view->message = $this->view->messagetext.PHP_EOL.PHP_EOL;
		$link = $this->config->general->url.'/article/view/article_id/'.$params["article_id"].'/headline/'.urlencode(trim(stripslashes($params["headline"]))).'/section/'.urlencode(stripslashes($params["section_name"]));
		
		$mailoutput = "This story was sent to you by ".$params['name'].".<br/><br/><br/>".trim(strip_tags($params['message'])).PHP_EOL.PHP_EOL."<br/><br/><br><a href='".$link."' target='_blank' >".$link."</a>";
		
		$params['headline'] = str_replace("â€˜","'",$params['headline']);
		$params['headline'] = str_replace("â€™","'",$params['headline']);
		
		$title = $params['headline'].' - Article at '. $this->config->general->domain;

		$sender['email'] = $emailSender;
		$sender['name'] = $params['name'];
		
		/*require_once("Mailer.php");
        $mailer = new Mailer();
        $mailer->sendMail($title, $mailoutput, $params['recipient'], $params['recipient'], $sender);*/
		
		$config = array('ssl' => 'ssl',
                'auth'          => 'login',
                'username' => 'AKIAI6LHTVUUHB4SSNWA',
                'password' => 'ApWkhDg1XUilj7oYRPNT3Bl3XXgcnU9nl3eFMVUARue5',
        );
        $transport = new Zend_Mail_Transport_Smtp('email-smtp.us-east-1.amazonaws.com', $config);
		
		require_once 'Zend/Mail.php';
		$mail = new Zend_Mail();
		$mail->setBodyHtml($mailoutput);
		$emailSender = "onlineads@apthosts.com";
		$mail->setFrom($emailSender, $params['name']);
		$mail->addTo($params['recipient'], '');
		$mail->setSubject($title);
		$mail->send($transport);
	}
	
	function getArticlesBySite($site_id)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$select = $contentArticleTable->select()
					->where("site_id=?", $site_id)
					->where("show_on_website='1'");
		$rs = $contentArticleTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function updateArticleField($article_id, $field, $value)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		
		$data = array(
			$field		=> $value
		);
		$where = $contentArticlesTable->getAdapter()->quoteInto('article_id = ?', $article_id);

		$contentArticlesTable->update($data, $where);
	}
	
	function getArticleByPriority($site_id, $priority_id, $timer=0)
	{
		if($priority_id)
		{
			if($timer > 0)
				$startDate = date("Y-m-d H:i:s",mktime(date("H"), date("i")-$timer, date("s"), date("m")  , date("d"), date("Y")));
			else
				$startDate = date("Y-m-d H:i:s",mktime(date("H"), date("i"), date("s"), date("m")  , date("d")-1, date("Y")));
				
			$endDate = date("Y-m-d H:i:s");
			
			$sql = "select ca.*, cs.section_name from content_articles ca
				JOIN content_sections cs on cs.section_id=ca.section_id
				where ca.pubdate BETWEEN '{$startDate}' AND '{$endDate}' AND 
				ca.article_priority_id=".$priority_id." and ca.site_id='".$site_id."' and show_on_website='1' 
				GROUP BY ca.headline
				LIMIT 50
			";
			
			return $this->db->fetchAll($sql);
		}
	}
	
	function getSlideshow($site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		//$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		//$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id", array("cgi.content_gallery_id"));
		//$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id"));
		//$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_id"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id", array("ci.source_system_id"));		
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag"));
		$select->where('ca.site_id = ?', $site_id);
		$select->where('ca.exclude_from_slideshow = 0');
		//$select->where('cg.content_gallery_id is not null');
		$select->where('ca.show_on_website = 1');
		$select->where('ci.content_images_id IS NOT NULL');
		$select->group(array("ca.source_system_id"));
		$select->order('ca.pubdate desc');
		$select->limit(6);
		
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getSlideshowByArea($site_id, $area_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		//$select->joinLeft(array("cag"=>"content_article_galleries"), "cag.content_articles_id = ca.article_id");
		//$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id", array("cgi.content_gallery_id"));
		//$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.source_system_id"));
		//$select->joinLeft(array("cg"=>"content_gallery"), "cg.content_gallery_id = cag.content_gallery_id", array("cg.content_gallery_id"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id", array("ci.source_system_id"));	
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag"));
		$select->joinLeft(array("carea"=>"content_areas"), "carea.section_id = ca.section_id");
		$select->where('ca.site_id = ?', $site_id);
		$select->where('ca.exclude_from_slideshow = 0');
		//$select->where('cg.content_gallery_id is not null');
		$select->where('ca.show_on_website = 1');
		$select->where('ci.content_images_id IS NOT NULL');
		$select->where('carea.area_id = ?', $area_id);
		$select->group(array("ca.source_system_id"));
		$select->order('ca.pubdate desc');
		$select->limit(5);
		
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getSectionFrontSlideshow($site_id, $area_id, $section_id = 0)
	{
		$area_id = intval($area_id);
		$section_id = intval($section_id);
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow2_content_images_id", array("ci.source_system_id"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag"));
		if(!empty($area_id))
			$select->joinLeft(array("csa"=>"content_section_area"), "csa.section_id = ca.section_id");
		$select->where('ca.site_id = ?', $site_id);
		$select->where('ca.exclude_from_2nd_slideshow = 0');
		$select->where('ca.show_on_website = 1');
		$select->where('ci.content_images_id IS NOT NULL');
		if(!empty($area_id))
			$select->where('csa.area_id = ?', $area_id);
		if(!empty($section_id))
			$select->where('ca.section_id= ?', $section_id);
		$select->group(array("ca.source_system_id"));
		$select->order('ca.pubdate desc');
		$select->limit(6);
		
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getSlideshowBySection($site_id, $section_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id", array("ci.source_system_id"));		
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key", "i.video_tag"));
		$select->where('ca.site_id = ?', $site_id);
		$select->where('ca.exclude_from_slideshow = 0');
		$select->where('ca.show_on_website = 1');
		$select->where('ci.content_images_id IS NOT NULL');
		$select->where('cs.section_id = ?', $section_id);
		$select->group(array("ca.source_system_id"));
		$select->order('ca.pubdate desc');
		$select->limit(5);
		
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getTrendingArticles($site_id, $limit, $section_id = 0)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		/*$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("cas"=>"content_article_statistics"), array("cas.*"));
		$select->joinLeft(array("ca"=>"content_articles"), "cas.article_id=ca.article_id", array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id", array("ci.source_system_id"));	
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key"));
		$select->where('ca.site_id = ?', $site_id);
		$select->where('trans_date BETWEEN "'.date("Y-m-d H:00:00", mktime(date("H")-24,0,0,date("m"),date("d"),date("Y"))).'" AND "'.date("Y-m-d H:00:00").'"');
		$select->where('type_id=0');
		$select->where('ca.show_on_website = 1');
		if(!empty($section_id))
			$select->where('cs.section_id = ?', $section_id);
		$select->group(array("cas.article_id"));
		$select->order('ca.pubdate desc');
		$select->limit($limit);*/
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		//let's start with 3 days
		$comparedate = date("Y-m-d 00:00:00", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-3-$this->config->general->dayreduction,date("Y")));
		$section_id = intval($section_id);
		/*$select = "SELECT cas.article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, 
				i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
				ca.pubdate, SUM(cnt) AS total_stats
			FROM content_article_statistics cas
			LEFT JOIN content_articles ca ON ca.article_id=cas.article_id
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE cas.site_id = '".$site_id."' and cas.trans_date BETWEEN '{$comparedate}' AND '{$now}' ".((empty($section_id))?"":" AND ca.section_id='{$section_id}'")." AND ca.show_on_website = '1'
			GROUP BY ca.source_system_id
			ORDER BY total_stats DESC, ca.pubdate DESC
			LIMIT {$limit}";*/
		$select = "SELECT cas.article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, 
				i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
				ca.pubdate, SUM(cnt) AS total_stats, ca.article_priority_id
			FROM content_article_statistics cas
			LEFT JOIN content_articles ca ON ca.article_id=cas.article_id
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE cas.site_id = '".$site_id."' and cas.trans_date BETWEEN '{$comparedate}' AND '{$now}' ".((empty($section_id))?"":" AND ca.section_id='{$section_id}'")." AND ca.show_on_website = '1'
			GROUP BY ca.source_system_id
			ORDER BY total_stats DESC, ca.pubdate DESC
			LIMIT {$limit}";
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function get2ndSlideshow($site_id)
	{
		$contentArticlesTable = new content_articles(array('db' => 'db'));
		$select = $contentArticlesTable->getAdapter()->select();
		$select->from(array("ca"=>"content_articles"), array("ca.*"));
		$select->joinLeft(array("cs"=>"content_sections"), "cs.section_id=ca.section_id", array("section_name"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id =ca.slideshow_content_images_id", array("ci.source_system_id"));		
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.smugmug_id", "i.smugmug_key"));
		$select->where('ca.site_id = ?', $site_id);
		$select->where('ca.exclude_from_2nd_slideshow = 0');
		$select->where('ca.show_on_website = 1');
		$select->where('ci.content_images_id IS NOT NULL');
		$select->group(array("ca.source_system_id"));
		$select->order('ca.pubdate desc');
		//$select->limit(5);
		$rs = $contentArticlesTable->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getLatestNewsBySection($siteid, $section_id, $prioritylow=0, $priorityhigh=10, $start = 0, $limit = 5) {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		//old select statement ca.*, cs.section_name
		/*$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			FROM content_articles ca
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id={$this->siteid} AND ca.section_id={$section_id} AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";*/
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id={$this->siteid} AND ca.section_id={$section_id} AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$start}, {$limit}
		";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			/*$content = $r["article"];
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
			$r["first_para"] = $content;*/
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($r['article'], 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($r['article'], 22)."&hellip;";
		}
		
		return $result;
	}
	
	function getPreviewContent($content, $wordCount = 10) {
		$content = str_replace("</li>", " </li>", $content);
		$content = str_replace("</p>", " </p>", $content);
		$content = str_ireplace("<br", " <br", $content);
		$content = strip_tags($content, '<b><i><u><strong><em>');
		$content = trim($content);
		$content = str_replace("&nbsp;", " ", $content);
		$temp = explode(" ", $content);
		$preview = "";
		for($i = 0; $i < $wordCount && $i < count($temp); $i++) $preview .= $temp[$i].' ';
		return trim($preview);
	}
	
	function getLatestNewsBySectionIds($siteid, $sectionIds = "", $prioritylow=0, $priorityhigh=10, $start = 0, $limit = 5) {
		if(empty($sectionIds)) return array();
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		//old select statement ca.*, cs.section_name
		/*$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			FROM content_articles ca
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id={$this->siteid} AND ca.section_id IN ({$sectionIds}) AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";*/
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id={$this->siteid} AND ca.section_id IN ({$sectionIds}) AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";
		if(!($prioritylow==0 && $priorityhigh==10)) {
			$sql .= "
				AND cap.priority BETWEEN {$prioritylow} AND {$priorityhigh} 
			";
		}
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$start}, {$limit}
		";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			$content = $r["article"];
			/*$content = str_replace("\n\n","\n", $content);
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
			}*/
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($content, 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($content, 22)."&hellip;";
		}
		
		return $result;
	}
	
	function getSectionFrontArticles($siteId, $areaId = 0, $sectionId = 0, $limit = 5, $photoOnly = false) {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		//old select statement ca.*, cs.section_name
		/*$sql = "SELECT rts.article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, cgi.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate
			FROM right_text_sliders rts
			LEFT JOIN content_articles ca ON rts.article_id=ca.article_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi on cgi.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE rts.site_id={$this->siteid} ";*/
		$sql = "SELECT rts.article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM right_text_sliders rts
			LEFT JOIN content_articles ca ON rts.article_id=ca.article_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE rts.site_id={$this->siteid} ";
		
		/*if(!empty($areaId)) $sql .= " AND ca.section_id IN (SELECT section_id FROM content_section_area WHERE area_id='{$areaId}') ";
		else if(!empty($sectionId)) $sql .= " AND ca.section_id='{$sectionId}'";*/
		if(!empty($areaId)) $sql .= " AND rts.content_area_id='{$areaId}' ";
		if(!empty($sectionId)) $sql .= " AND rts.content_section_area_id='{$sectionId}' ";
		
		$sql .= " AND ca.show_on_sectionfront=1 ";
		
		$sql .= " AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";			
		
		if($photoOnly) $sql .= " AND ca.photo_link_id IS NOT NULL ";
		
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$limit}
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
					if(strlen($tempPara)>30) {
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
	
	function getHomeLatestNews($siteid, $photoOnlyArticles = 3, $otherLatest = 6)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-1,date("d")-$this->config->general->dayreduction,date("Y")));
		
		/*$sql = "SELECT article_id, ca.source_system_id, cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND cg.thumbnail_content_images_id IS NOT NULL AND i.image_class_id=2
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id desc 
			LIMIT {$photoOnlyArticles}";*/
		$sql = "SELECT article_id, ca.source_system_id, cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND cg.thumbnail_content_images_id IS NOT NULL AND ca.article_id NOT IN (
				SELECT article_id
				FROM content_articles ca
				LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
				LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
				LEFT JOIN content_gallery_images cgi ON cgi.content_gallery_id=cg.content_gallery_id
				LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
				LEFT JOIN images i on i.source_id = ci.content_images_id
				WHERE ca.site_id = '{$siteid}' AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				GROUP BY ca.source_system_id, i.image_class_id
				HAVING i.image_class_id=1	
			)
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id desc 
			LIMIT {$photoOnlyArticles}";
		
		$articles = $this->db->fetchAll($sql);
		
		$articleIds = "'',";
		if(is_array($articles)) foreach ($articles as &$r) {
			$articleIds .= "'".$r['source_system_id']."',";
			/*$content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $r["article"]);
			$content = strip_tags($content, '<p><br>');
			$content = str_replace('<p>',' ',$content);
			$content = str_replace('</p>',' ',$content);
			$content = str_replace('<br>',' ',$content);
			$content = str_replace('<br/>',' ',$content);
			$content = trim($content);
			
			//$headline_cnt = str_word_count($r["headline"], 1);
			$headline_cnt = strlen($r["headline"]);
			if(!empty($r["image_name"]))
			{
				$content = substr($content, 0, 80-$headline_cnt);
			}
			else
			{
				$content = substr($content, 0, 120-$headline_cnt);
			}			
				
			$r["first_para"] = $content.'...';*/
			
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($r['article'], 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($r['article'], 22)."&hellip;";
			
			if(empty($r['image_name'])) {
				preg_match_all("|<img[^>]+>|U", $r["article"], $matches);
				if(is_array($matches[0])) foreach ($matches[0] as $img) {
					$imageStr = $img;
					$strPos = strpos($imageStr, 'src=');
					$strPos += 5;
					$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
					if(file_exists($this->config->paths->html.$src)) {
						list($width, $height) = getimagesize($this->config->paths->html.$src);
						if($width > 100 && $height > 100) {
							$r['image_name'] = str_replace("/images/article_photos/", "",$src);
							break;
						}
					}
				}
			}
		}
		$articleIds = trim($articleIds, ',');
		/*$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND ca.source_system_id NOT IN ($articleIds) AND i.image_class_id=2
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id desc 
			LIMIT {$otherLatest}";*/
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND ca.source_system_id NOT IN ($articleIds) AND ca.article_id NOT IN (
				SELECT article_id
				FROM content_articles ca
				LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
				LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
				LEFT JOIN content_gallery_images cgi ON cgi.content_gallery_id=cg.content_gallery_id
				LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
				LEFT JOIN images i on i.source_id = ci.content_images_id
				WHERE ca.site_id = '{$siteid}' AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				GROUP BY ca.source_system_id, i.image_class_id
				HAVING i.image_class_id=1	
			)
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id desc 
			LIMIT {$otherLatest}";
		
		$articles_others = $this->db->fetchAll($sql);
		
		$result = array_merge($articles, $articles_others);
		
		return $result;
	}
	
	function getHomeLatestVideos($siteid, $limit = 9)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-1,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_article_galleries cag on cag.content_articles_id = ca.article_id
			LEFT JOIN content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			LEFT JOIN content_gallery_images cgi ON cgi.content_gallery_id=cg.content_gallery_id
			LEFT JOIN content_images ci on ci.content_images_id = cgi.content_images_id
			LEFT JOIN images i on i.source_id = ci.content_images_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
			GROUP BY ca.source_system_id, i.image_class_id
			HAVING i.image_class_id=1
			ORDER BY ca.pubdate DESC, article_id desc 
			LIMIT {$limit}";
		
		$articles = $this->db->fetchAll($sql);
		
		if(is_array($articles)) foreach ($articles as &$r) {
			/*$content = preg_replace('/<p[^>]*><\\/p[^>]*>/', '', $r["article"]);
			$content = strip_tags($content, '<p><br>');
			$content = str_replace('<p>',' ',$content);
			$content = str_replace('</p>',' ',$content);
			$content = str_replace('<br>',' ',$content);
			$content = str_replace('<br/>',' ',$content);
			$content = trim($content);
			
			//$headline_cnt = str_word_count($r["headline"], 1);
			$headline_cnt = strlen($r["headline"]);
			if(!empty($r["image_name"]))
			{
				$content = substr($content, 0, 80-$headline_cnt);
			}
			else
			{
				$content = substr($content, 0, 120-$headline_cnt);
			}			
				
			$r["first_para"] = $content.'...';*/
			
			if(empty($r['image_name']))
				$r["first_para"] = $this->getPreviewContent($r['article'], 40)."&hellip;";
			else 
				$r["first_para"] = $this->getPreviewContent($r['article'], 22)."&hellip;";
			
		}
		
		return $articles;
	}
	
	function getBibleVerses($siteid, $limit = 1) {
		if(empty($limit)) $limit = 1;
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-6,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, 
			ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			LEFT JOIN content_sections cs ON cs.section_id=ca.section_id
			WHERE ca.site_id='{$siteid}' AND ca.section_id = '{$this->config->cms->bible->category_id}' AND ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' ";
		
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT {$limit}
		";
		$result = $this->db->fetchAll($sql);
		
		return $result;
	}
	
	function getArticlesByAreaInHours($areaId, $sectionId, $hoursToRetrieve) {
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H")-$hoursToRetrieve, date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "
			SELECT ca.*, cs.section_name
			FROM content_areas cas
			LEFT JOIN content_section_area csa ON csa.area_id=cas.area_id
			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id AND cs.section_name NOT IN ('Obituaries')
			LEFT JOIN content_articles ca ON ca.pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.section_id=cs.section_id
			LEFT JOIN content_article_priority cap ON ca.article_priority_id=cap.article_priority_id
			WHERE cas.site_id={$this->siteid} ";
		if(!empty($areaId)) $sql .= " AND cas.area_id='{$areaId}' ";
		if(!empty($sectionId)) $sql .= " AND ca.section_id='{$sectionId}' ";
		$sql .= " and ca.show_on_website = '1' 
			GROUP BY source_system_id
			ORDER BY ca.pubdate DESC, ca.article_id DESC
			LIMIT 100
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
	
	function getArticlesInHours($hoursToRetrieve) {
		return $this->getArticlesByAreaInHours(0, 0, $hoursToRetrieve);
	}
	
	function updateSlideshow2Image($articleId, $contentImageId) {
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$select = $contentArticleTable->select()->where("article_id=?", $articleId);
		$article = $contentArticleTable->getAdapter()->fetchRow($select);
		if(empty($article['slideshow2_content_images_id']) && !empty($article['article_id']) && !empty($contentImageId)) {
			$where = array();
			$where[] = $contentArticleTable->getAdapter()->quoteInto("article_id=?", $articleId);
			$where[] = $contentArticleTable->getAdapter()->quoteInto("site_id=?", $this->siteid);
			$contentArticleTable->update(array("slideshow2_content_images_id"=>$contentImageId), $where);
		}
	}
	
	function updateSlideshowImage($articleId, $contentImageId) {
		$contentArticleTable = new content_articles(array('db' => 'db'));
		$select = $contentArticleTable->select()->where("article_id=?", $articleId);
		$article = $contentArticleTable->getAdapter()->fetchRow($select);
		if(empty($article['slideshow_content_images_id']) && !empty($article['article_id']) && !empty($contentImageId)) {
			$where = array();
			$where[] = $contentArticleTable->getAdapter()->quoteInto("article_id=?", $articleId);
			$where[] = $contentArticleTable->getAdapter()->quoteInto("site_id=?", $this->siteid);
			$contentArticleTable->update(array("slideshow_content_images_id"=>$contentImageId), $where);
		}
	}
	
	function getNonAPLatestNews($siteid, $limit = 5)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m")-1,date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "select article_id, ca.source_system_id, cs.section_name,cs.section_id,headline,ca.article_priority_id, cap.priority, ca.article, 
			ci.content_images_id, ci.source_system_id as image_name, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id, i.video_tag,
			ca.pubdate, ci.title AS image_title, ci.caption AS image_caption
			from content_articles ca
			left join content_sections cs on cs.section_id=ca.section_id
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			left join content_images ci on ci.content_images_id = cg.thumbnail_content_images_id
			left join images i on i.source_id = ci.content_images_id
			where ca.site_id = ".$siteid." and pubdate BETWEEN '{$comparedate}' AND '{$now}' and ca.show_on_website = '1'
				AND ca.byline NOT LIKE '%Associated Press%' AND ca.searchtext NOT LIKE '%(AP)%'
			group by ca.source_system_id
			order by ca.pubdate DESC, article_id desc limit {$limit}";
		
		$result = $this->db->fetchAll($sql);
		
		foreach ($result as &$r) {
			$content = $this->getParagraphPreview($r['article'], 1);
			
			$r["first_para"] = $content;
			
			if(empty($r['image_name'])) {
				preg_match_all("|<img[^>]+>|U", $r["article"], $matches);
				if(is_array($matches[0])) foreach ($matches[0] as $img) {
					$imageStr = $img;
					$strPos = strpos($imageStr, 'src=');
					$strPos += 5;
					$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
					if(file_exists($this->config->paths->html.$src)) {
						list($width, $height) = getimagesize($this->config->paths->html.$src);
						if($width > 100 && $height > 100) {
							$r['image_name'] = str_replace("/images/article_photos/", "",$src);
							break;
						}
					}
				}
			}
		}

		return $result;
	}
	
	 function getParagraphPreview($str, $totalParagraph = 2) {
		preg_match_all("|<p(.*)</p>|U", $str, $matches);
		$returns = "";
		$stripFirstChar = false;
		if(strpos($str, "<br>")) {
			$temp = explode("<br>", $str);
		}
		else if(strpos($str, "<br/>")) {
			$temp = explode("<br/>", $str);
		}
		else if(strpos($str, "<br />")) {
			$temp = explode("<br />", $str);
		}
		else if(!empty($matches[1]) && count($matches[1]) > 0) {
			$temp = $matches[1];
			$stripFirstChar = true;
		}
		
		for($i = 0, $j = 0; $j < $totalParagraph && $i < count($temp); $i++) {
			$content = strip_tags($temp[$i]);
			$content = str_replace('&nbsp;', ' ', $content);
			$content = trim($content);
			if($stripFirstChar) $content = substr($content, 1);
			if(!empty($content) && strlen($content) > 10) {
				$returns .= "<p>".$content."</p>";
				$j++;
			}
		} 
		if(empty($returns)) $returns = $str;
		return $returns;
	}
	
	function getBreakingNews($siteid, $section_id, $hours)
	{
		$contentArticleTable = new content_articles(array('db' => 'db'));
		
		$now = date("Y-m-d H:i:s", mktime(date("H"), date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		$comparedate = date("Y-m-d H:i:s", mktime(date("H")-$hours, date("i"),date("s"),date("m"),date("d")-$this->config->general->dayreduction,date("Y")));
		
		$sql = "SELECT article_id,cs.section_name,cs.section_id,headline,cap.priority, ca.article, ca.pubdate, ca.article_priority_id
			FROM content_articles ca
			LEFT JOIN content_sections cs on cs.section_id=ca.section_id
			LEFT JOIN content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			WHERE ca.site_id = ".$siteid." AND pubdate BETWEEN '{$comparedate}' AND '{$now}' AND ca.show_on_website = '1'
				AND ca.section_id in ({$section_id})
			GROUP BY ca.source_system_id
			ORDER BY ca.pubdate DESC, article_id DESC
			LIMIT 1";
		
		$result = $this->db->fetchAll($sql);

		return $result;
	}
}
?>