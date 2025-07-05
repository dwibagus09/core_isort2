<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class contentClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	
	function getRandomArticlesByArea($site_id,$count,$prioritylow=0,$priorityhigh=10,$photosonly=true)
	{
		//if ($count) $count=1;


		$sql = 'select article_id,article,cs.section_name,cs.section_id,headline,cap.priority, ca.photo_link_id, ca.pubdate, i.smugmug_id, i.smugmug_key, i.youtube_id, cg.content_gallery_type_id, i.image_class_id 
			from content_articles ca
			left join content_sections cs on cs.section_id=ca.section_id
			left join content_article_priority cap on ca.article_priority_id=cap.article_priority_id
			left join content_article_galleries cag on cag.content_articles_id = ca.article_id
			left join content_gallery cg on cg.content_gallery_id = cag.content_gallery_id
			left join content_gallery_images cgi on cgi.content_gallery_id = cg.content_gallery_id
			left join content_images ci on ci.content_images_id = cgi.content_images_id
			left join images i on i.source_id = ci.content_images_id
			where ca.site_id='.$site_id;
		
		if ($photosonly)
		{
			$sql .= ' and ca.exclude_from_slideshow=0 and photo_link_id is not null  ';
		}
		$sql .= ' and cap.priority between '.$prioritylow.' and '.$priorityhigh.' and ca.show_on_website = 1
			order by pubdate desc
			limit '.$count;

		//JR 9/26/2007
		//removed ,rand() from oder by clause in above sql statement
		//query takes 1200ms with rand(), 60ms without
		//if randomization is required suggest bumping the limit clause to count + n, doing an array_shuffle,
		//and then dropping the the last n elements off the end of the array. should be an order of
		//magnitude faster than letting mySQL handle it.
		
		$rs = $this->db->fetchAll($sql);

		$articles = array();
		foreach ($rs as $article) {
			$content = $article["article"];
			if(strpos($content, "</p>")) { }
			else {
				$temp = explode("\n", $content);
				$content = "";
				foreach ($temp as $para) {
					$content .= "<p>".$para."</p>";
				}
			}
			$article["article"] = $content;
			$articles[] = $article;
		}
		
		return $articles;
	}
	
	/*function makeImageFilesForArticle($article_id,$article_photo)
	{
		if (!$article_photo) return false;

		$imagesizes = array('small_image','medium_image','large_image','slide_image');
		//echo $article_photo;

		foreach($imagesizes as $imagesize)
		{

			$newimagefile = $this->config->paths->html . '/images/image_cache/'.$imagesize.'_'.$article_id.'.jpg';
			$origimagefile = $this->config->paths->html . '/images/article_photos/'.$article_photo;

			//See if this is portrait
			list($origwidth, $origheight, $type) = getimagesize($origimagefile);
			$portrait = ($origheight>$origwidth)?true:false;

			if (!file_exists($newimagefile))
			{
				$dims=array($this->getImageWidth($imagesize,$portrait),$this->getImageHeight($imagesize,$portrait));
				$this->resizeJPGImageFile($origimagefile,$newimagefile,$dims,($imagesize=='slide_image')?true:false);
			}
		}
	}
	
	//Image size fuctions, $imagetype are constants defined in contentIncludes.php
	function getImageWidth($imagesize,$portrait=false)
	{
		if ($portrait)
		{
			switch ($imagesize)
			{
				case small_image:
					return 145;
					break;
				case medium_image:
					return 330;
					break;
				case large_image:
					return 640;
					break;
				case slide_image:
					return 330;
					break;
				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 640;
		}
		else {
			switch ($imagesize)
			{
				case small_image:
					return 145;
					break;
				case medium_image:
					return 330;
					break;
				case large_image:
					return 640;
					break;
				case slide_image:
					return 330;
					break;
				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 640;
		}
	}
	
	function getImageHeight($imagesize,$portrait=false)
	{
		if ($portrait)
		{
			switch ($imagesize)
			{
				case small_image:
					return 0;
					break;
				case medium_image:
					return 200;
					break;
				case large_image:
					return 0;
					break;
				case slide_image:
					return 250;
					break;

				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 0;

		} else {
			switch ($imagesize)
			{
				case small_image:
					return 0;
					break;
				case medium_image:
					return 0;
					break;
				case large_image:
					return 0;
					break;
				case slide_image:
					return 250;
					break;

				default:
					echo '$imagetype constant not defined in function getImageWidth in contentClass';
			}
			return 0;
		}
	}
	
	function resizeJPGImageFile($origimagefile,$newimagefile,$dims,$center=false)
	{
		//Get the dimensions of the old file and new file
		list($origwidth, $origheight, $type) = getimagesize($origimagefile);
		if (!$center)
		{
			$newdims = $this->calcImageScale($origwidth, $origheight, $dims[0], $dims[1]);
		}
		else
		{
			$newdims = $dims;
		}
		//Get the image from the jpg file
		$origimage = imagecreatefromjpeg($origimagefile);

		//Resize it
		$newimage = $this->resizeJPGImage($origimage,$newdims,$center);

		//Save it as the new file
		imagejpeg($newimage, $newimagefile);

		//Delete the memory images
		imagedestroy($origimage);
		imagedestroy($newimage);

		return true;
	}
	
	function calcImageScale($origWidth, $origHeight, $maxWidth, $maxHeight, $stretch=FALSE)
	{
		if (!$maxWidth && $maxHeight)
		{
			// Width is unlimited, scale by width
			$newh = $maxHeight;

			if ($origHeight < $maxHeight && !$stretch) { $newh = $origHeight; }
			else { $newh = $maxHeight; }

			$neww = ($origWidth * $newh / $origHeight);
		}
		elseif (!$maxHeight && $maxWidth)
		{
			// Scale by height
			if ($origWidth < $maxWidth && !$stretch) { $neww = $origWidth; }
			else { $neww = $maxWidth; }

			$newh = ($origHeight * $neww / $origWidth);
		}
		elseif (!$maxWidth && !$maxHeight)
		{
			return array($origWidth,$origHeight);
		}
		else
		{
			if ($origWidth / $maxWidth > $origHeight / $maxHeight)
			{
				// Scale by height
				if ($origWidth < $maxWidth && !$stretch) { $neww = $origWidth; }
				else { $neww = $maxWidth; }

				$newh = ($origHeight * $neww / $origWidth);
			}
			elseif ($origWidth / $maxWidth <= $origHeight / $maxHeight)
			{
				// Scale by width

				if ($origHeight < $maxHeight && !$stretch) { $newh = $origHeight; }
				else { $newh = $maxHeight; }

				$neww = ($origWidth * $newh / $origHeight);
			}
		}
		return array(round($neww),round($newh));
	}
	
	function resizeJPGImage($origimage,$dims,$center=false)
	{
		$origwidth = imagesx($origimage);
		$origheight = imagesy($origimage);

		if ($center)
		{
			$newdims = $this->calcImageScale($origwidth,$origheight,0,$dims[1],true);
		}
		else
		{
			$newdims = $dims;
		}

		//list($origwidth, $origheight, $type) = getimagesize($origimage);
		$newimage = imagecreatetruecolor($dims[0], $dims[1]);

		//Color the background white
		$color = imagecolorallocate($newimage,255,255,255);
		imagefilledrectangle($newimage,0,0,$dims[0],$dims[1],$color);

		imagecopyresampled($newimage, $origimage, ($center)?($dims[0]-$newdims[0])/2:0, 0, 0, 0, $newdims[0], $newdims[1], $origwidth, $origheight);
		return $newimage;
	}
	
	function createParagraphArray($text)
	{
		$p = 0;
		$s = 0;
		$outstr = '';
		$paraarray[] = '';
		while (strlen($outstr)<9999999)
		{
			$p = strpos($text,"\n",$s);
			if ($p!==false)
			{
				//$para = substr($text,$s,($p-$s)+1);
				$para = substr($text,$s,($p-$s));
				$paraarray[] = $para;
				$s = $p + 1;
			} else break;
		}

		return $paraarray;
	}
	
	function buildByline($paras,&$start)
	{
		$output = '<P>';
		$start=0;
		foreach($paras as $para)
		{
			if (strlen($para)>50) break;
			$output .= $para.'<br>';
			$start = $start + 1;
		}
		$output .= '</P>';

		if (strlen($output)<10)
		{
			$output = '';
			$start = 0;
		}

		return $output;

	}*/
	
	//function setContentImage($siteid, $photo, $title, $caption, $keywords)
	function setContentImage($siteid, $img, $keywords)
	{
		
		$filename=basename($img['photo']);
		$now = date("Y-m-d H:i:s");
		//$filename=basename($photo);
		
		//See if the record already exists
		if($content_images_id=$this->getContentImageIdbySourceSystemId($siteid, $filename))
		{
			$content_imagesTable = new content_images(array('db' => 'db')); //use db object from registry
			
			$data = array(
				'title'				=> $img['title'],
				'caption'			=> $img['caption'],
				'alt_text'			=> $img['alt_text'],
				'keywords'			=> $keywords,
				'modify_date_time'	=> $now,
			);
			$where = array();
			$where[] = $content_imagesTable->getAdapter()->quoteInto("site_id=?", $siteid);
			$where[] = $content_imagesTable->getAdapter()->quoteInto("content_images_id=?", $content_images_id);
			$content_imagesTable->update($data, $where);
			
			return $content_images_id;
		}
		else
		{
			$content_imagesTable = new content_images(array('db' => 'db')); //use db object from registry
			
			$data = array(
				'site_id'			=> $siteid,
				'source_system_id'	=> $filename,
				'title'				=> $img['title'],
				'caption'			=> $img['caption'],
				'alt_text'			=> $img['alt_text'],
				//'title'			=> $title,
				//'caption'			=> $caption,
				//'alt_text'			=> $caption,
				'keywords'			=> $keywords,
				'create_date_time'	=> $now
			);
			
			$content_imagesTable->insert($data);
			$content_images_id = $this->db->lastInsertId();
			return $content_images_id;
		}
	}
	
	function getContentImageIdbySourceSystemId($siteid, $sourcesystemid)
	{
		if($sourcesystemid)
		{
			//get the most recent feature photo for the current section
			$sql = "select content_images_id from content_images
				where source_system_id='".$sourcesystemid."' and site_id=".$siteid;

			$rs = $this->db->fetchRow($sql);

			return $rs['content_images_id'];
		}
		return null;
	}
	
	function getImages($site_id, $gallery_id, $start=0, $limit=100)
	{
		/*$sql = "select cg.content_gallery_id, ci.source_system_id, ci.caption, ci.content_images_id
				from content_articles ca 
				join content_sections cs on cs.section_id=ca.section_id 
				join content_article_priority cap on ca.article_priority_id=cap.article_priority_id 
				join content_article_galleries cag on ca.article_id=cag.content_articles_id
				join content_gallery cg on cag.content_gallery_id=cg.content_gallery_id
				join content_gallery_images cgi on cg.content_gallery_id=cgi.content_gallery_id 
				join content_images ci on cgi.content_images_id =ci.content_images_id 
				where ca.site_id = ".$site_id." and ca.article_id = '".$article_id."'";*/
		if(in_array($this->siteid, array(13,14,15 )))
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS ci.*, i.smugmug_id, i.smugmug_key
					FROM `content_gallery` cg
					inner join content_gallery_images cgi on cg.content_gallery_id = cgi.content_gallery_id
					inner join content_images ci on ci.content_images_id = cgi.content_images_id
					inner join images i on i.source_id = ci.content_images_id
					WHERE cg.content_gallery_id = '".$gallery_id."'
					ORDER BY cg.create_date_time DESC LIMIT {$start}, {$limit}";
		}
		else
		{
			$sql = "SELECT SQL_CALC_FOUND_ROWS ci.*, i.smugmug_id, i.smugmug_key
					FROM `content_gallery` cg
					inner join content_gallery_images cgi on cg.content_gallery_id = cgi.content_gallery_id
					inner join content_images ci on ci.content_images_id = cgi.content_images_id
					inner join images i on i.source_id = ci.content_images_id
					WHERE (cg.site_id='".$site_id."') AND i.site_id = '".$site_id."' and cg.content_gallery_id = '".$gallery_id."'
					ORDER BY cg.create_date_time DESC LIMIT {$start}, {$limit}";
		}
		$result['data'] = $this->db->fetchAll($sql);
		
		/*$sql = "SELECT count(*)
				FROM `content_gallery` cg
				inner join content_gallery_images cgi on cg.content_gallery_id = cgi.content_gallery_id
				inner join content_images ci on ci.content_images_id = cgi.content_images_id
				inner join images i on i.source_id = ci.content_images_id
				WHERE (cg.site_id='".$site_id."') AND i.site_id = '".$site_id."' and cg.content_gallery_id = '".$gallery_id."'
				ORDER BY cg.create_date_time DESC";*/
		$sql = "SELECT FOUND_ROWS()";
		$result['total'] = $this->db->fetchOne($sql);

		return $result;
	}
	
	function getContentAreas($site_id)
	{
		$contentAreas = new content_areas(array('db'=>'db'));
		$select = $contentAreas->select();
		$select->where("site_id=?", $site_id);
		$rs = $contentAreas->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getContentImages($site_id)
	{
		$contentImages = new content_images(array('db'=>'db'));
		$select = $contentImages->select();
		$select->where("site_id=?", $site_id);
		$rs = $contentImages->getAdapter()->fetchAll($select);
		return $rs;
	}
	
	function getContentSectionArea($content_section_area_id)
	{
		$csa = new content_section_area(array('db'=>'db'));
		$select = $csa->select();
		$select->where("content_section_area_id=?", $content_section_area_id);
		$rs = $csa->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function getContentSectionAreaByCustomURL($custom_url) {
		$csa = new content_section_area(array('db'=>'db'));
		$select = $csa->select();
		$select->where("custom_url=?", $custom_url);
		$rs = $csa->getAdapter()->fetchRow($select);
		return $rs;
	}
	
	function getSectionIdsPerGroup($csa) {
		$csaTable = new content_section_area(array('db'=>'db'));
		$select = $csaTable->getAdapter()->select();
		$select->from(array('csa'=>'content_section_area'), array("GROUP_CONCAT(csa.section_id SEPARATOR ',') AS section_ids"));
		$select->joinLeft(array('csag'=>'content_section_area_group'), "csag.content_section_area_group_id=csa.content_section_area_group_id", array("csag.title"));
		$select->where("csa.area_id=?", $csa['area_id']);
		$select->where("csa.content_section_area_group_id=?", $csa['content_section_area_group_id']);		
		$result = $csaTable->getAdapter()->fetchRow($select);
		if($csa['custom_title'] == $result['title']) return $result['section_ids'];
		else return '';
	}
	
	function getImagesByArticleId($article_id)
	{
		$contentArticleGalleriesTable = new content_article_galleries(array('db' => 'db')); //use db object from registry
		$select = $contentArticleGalleriesTable->getAdapter()->select();
		$select->from(array("cag"=>"content_article_galleries"), array("cag.*"));
		$select->joinLeft(array("cgi"=>"content_gallery_images"), "cgi.content_gallery_id = cag.content_gallery_id", array("cgi.*"));
		$select->joinLeft(array("ci"=>"content_images"), "ci.content_images_id = cgi.content_images_id", array("ci.*"));
		$select->joinLeft(array("i"=>"images"), "i.source_id = ci.content_images_id", array("i.*"));
		$select->where('cag.content_articles_id = ?', $article_id);
		return $this->db->fetchAll($select);
	}
}

?>