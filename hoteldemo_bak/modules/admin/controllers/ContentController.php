<?php
require_once('actionControllerBase.php');
class Admin_ContentController extends actionControllerBase 
{
    
    /*** CONTENT GALLERY ***/
    
    function contentgalleryAction()
    {   	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(9, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
		$configs = parse_ini_file( $configFile, true );
		
		if(empty($configs['gallery']['use_smugmug']))
			$this->view->imageURL = trim($configs["general"]["url"], '/'). "/images/article_photos/";
		else 
			$this->view->imageURL = '';
			
		if(!empty($configs['gallery']['use_smugmug'])) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));
	    	$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
	    	require($libPath."/phpSmug.php");
	    	$f = new phpSmug( "APIKey=".$configs['smugmug']['api_key'], "AppName=".$configs['smugmug']['app_name'] );
		 	$f->login( "EmailAddress=".$configs['smugmug']['email'], "Password=".$configs['smugmug']['password'] );
		 	$this->view->useSmugmug = 1;
		 	$categories = $f->categories_get();
		 	$this->view->smugmugCategories = $categories;
		}
		
		$siteGroupId = intval($this->site_group_id);
		$this->view->groupSites = $this->db->fetchAll("SELECT * FROM sites WHERE site_group_id='{$siteGroupId}'");
    			
        echo $this->view->render('header.php');
        echo $this->view->render('content_gallery.php');
        echo $this->view->render('footer.php');
    }
    
    function getcontentgalleryAction()
    {
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = false;
    	$data = $cgc->getContentGallery($this->site_id, $params);
    	$response['total'] = $data['total'];
    	$arrData = $data['data'];
    	 		
		if ( sizeof($arrData) > 0 ) {
			$response['success'] = true;  
			foreach ( $arrData as &$thisData ) {
				$thisData['show_gallery_in_multimedia'] = $thisData['show_gallery_in_multimedia'] == '1' ? 'Yes' : 'No';
			}   			
			$response['data'] = $arrData;
		}
		else {
   			$response['data'] = '';
   		}
	
    	echo json_encode($response);
    }
    
    function getcontentgallerywithimagesAction()
    {
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = false;
    	$data = $cgc->getContentGallery($this->site_id, $params);
    	$response['total'] = $data['total'];
    	$arrData = $data['data'];
    	 		
		if ( sizeof($arrData) > 0 ) {
			Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    		$cic = new contentimagesClass();
			
			$response['success'] = true; 
			$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
    		$site_configs = parse_ini_file( $configFile, true );
    		
    		if(!empty($site_configs['gallery']['use_smugmug'])) {
				$libPath = dirname(dirname(dirname(dirname(__FILE__))));
		    	$libPath = str_replace("\\", "/", $libPath);
		    	$libPath = rtrim($libPath, '/');
		    	$libPath .= '/lib';
		    	require($libPath."/phpSmug.php");
		    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
			 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );	
    		}
    		
    		if($site_configs['cms']['show_thumbnails_on_maintainer'] == '1')
    		{
				foreach ($arrData as &$data)  			
				{
					$images = $cic->getImagesByGalleryId($this->site_id, $data['content_gallery_id']);
					
					foreach($images as &$img)
					{
						if(!empty($site_configs['gallery']['use_smugmug']) && $img['image_class_id']==2) {
							try {
			    				$image = $f->images_getInfo('ImageID='.$img['smugmug_id'],'ImageKey='.$img['smugmug_key']);
			    				$img['source_system_id'] = $image['MediumURL'];
							} catch (Exception $ex) {}
		    			}
		    			else if(!empty($site_configs['video']['use_youtube']) && $img['image_class_id']==1) {
		    				$img['source_system_id'] = "https://img.youtube.com/vi/".$image['youtube_id']."/0.jpg";
		    			}
		    			else {
							$img['source_system_id'] = $site_configs['general']['url'].'/images/article_photos/'.$img['source_system_id'];
		    			}
		    			$img['title'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($img['title']));
		    			$img['caption'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($img['caption']));
		    			$img['keywords'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($img['keywords']));
					}
					
					$data['images'] = $images;
					
					$data['content_gallery'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($data['content_gallery']));
					$data['headline'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($data['headline']));
					$data['keywords'] = iconv("UTF-8","UTF-8//IGNORE", stripslashes($data['keywords']));
				}
    		}
			$response['data'] = $arrData;
		}
		else {
   			$response['data'] = '';
   		}
	
    	echo json_encode($response);
    }    
    
    function getcontentgallerytypeAction()
    {    		
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$response['data'] = $cgc->getContentGalleryType();
    	echo json_encode($response);
    }
    
    function getcontentgallerybyidAction()
    {
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $cgc->getContentGalleryById($params['content_gallery_id']);
		
    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    }
    
    /**
     * adding a new content gallery
     *
     */
	function addcontentgalleryAction()
    {
		Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$params = $this->_getAllParams();
    	
    	if(isset($params['show_gallery_in_multimedia']) && $params['show_gallery_in_multimedia'] == 'on')
    		$params['show_gallery_in_multimedia'] = 1;
    	else
    		$params['show_gallery_in_multimedia'] = 0;
    	
    	if(isset($params['for_sale']) && $params['for_sale'] == 'on')
    		$params['for_sale'] = 1;
    	else
    		$params['for_sale'] = 0;
    	
    	if(isset($params['public']) && $params['public'] == 'on')
    		$params['public'] = "true";	
    	else
    		$params['public'] = "false";
    	
    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		if(!empty($site_configs['gallery']['use_smugmug']) && $params['content_gallery_type_id']==1) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));
	    	$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
	    	require($libPath."/phpSmug.php");
	    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
		 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );
			try {
				$categoryId = intval($params['smugmug_categoryid']);
				$response = $f->albums_create("Title=".$params['content_gallery'], "Keywords=".$params['keywords'], "CategoryID=".$categoryId, "Protected=".((!empty($site_configs['smugmug']['album.protected']))?"true":"false"), "Watermarking=".((!empty($site_configs['smugmug']['album.watermarking']))?"true":"false"), "XLarges=".((!empty($site_configs['smugmug']['album.xlarges']))?"true":"false"), "Larges=".((!empty($site_configs['smugmug']['album.larges']))?"true":"false"), "Originals=".((!empty($site_configs['smugmug']['album.originals']))?"true":"false"), "X2Larges=".((!empty($site_configs['smugmug']['album.x2larges']))?"true":"false"), "X3Larges=".((!empty($site_configs['smugmug']['album.x3larges']))?"true":"false"), "WatermarkID=".((!empty($site_configs['smugmug']['album.watermarkid']))?$site_configs['smugmug']['album.watermarkid']:0), "Printable=".$params['for_sale'], "Public=".$params['public']);
				if(!empty($response['id'])) {
					$params['smugmug_id'] = $response['id'];
					$params['smugmug_key'] = $response['Key'];
				}
			} catch(Exception $ex) {}
		}
    		
    	$cgc->addContentGallery($this->site_id, $params);
    	//$this->cleanCache();
    	
    	$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
    	$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
    	$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
    }
    
    function updatecontentgalleryAction()
    {
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    	$cgc = new contentgalleryClass();
    	
    	$params = $this->_getAllParams();
    	
    	if(isset($params['show_gallery_in_multimedia']) && $params['show_gallery_in_multimedia'] == 'on')
    		$params['show_gallery_in_multimedia'] = 1;
    	else
    		$params['show_gallery_in_multimedia'] = 0;
    		
    	if(isset($params['for_sale']) && $params['for_sale'] == 'on')
    		$params['for_sale'] = 1;
    	else
    		$params['for_sale'] = 0;
    	
    	if(isset($params['public']) && $params['public'] == 'on')
    		$params['public'] = "true";	
    	else
    		$params['public'] = "false";
    	
    	$cgc->updateContentGallery($params);
    	
    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		if(!empty($site_configs['gallery']['use_smugmug']) && $params['content_gallery_type_id']==1) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));
	    	$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
	    	require($libPath."/phpSmug.php");
	    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
		 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );
			try {
				$categoryId = intval($params['smugmug_categoryid']);
				$response = $f->albums_changeSettings("AlbumID=".$params['smugmug_id'],"Title=".$params['content_gallery'], "Keywords=".$params['keywords'], "CategoryID=".$categoryId, "Protected=".((!empty($site_configs['smugmug']['album.protected']))?"true":"false"), "Watermarking=".((!empty($site_configs['smugmug']['album.watermarking']))?"true":"false"), "XLarges=".((!empty($site_configs['smugmug']['album.xlarges']))?"true":"false"), "Larges=".((!empty($site_configs['smugmug']['album.larges']))?"true":"false"), "Originals=".((!empty($site_configs['smugmug']['album.originals']))?"true":"false"), "X2Larges=".((!empty($site_configs['smugmug']['album.x2larges']))?"true":"false"), "X3Larges=".((!empty($site_configs['smugmug']['album.x3larges']))?"true":"false"), "Printable=".$params['for_sale'], "Public=".$params['public']);
			} catch(Exception $ex) {}
		}
    	
    	//$this->cleanCache();
    	$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
    	$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
    	$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
    }
    
    function getimagesAction()
    {
    	set_time_limit(3600);
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$cic = new contentimagesClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $cic->getImagesByGalleryId($this->site_id, $params['content_gallery_id']);
		
    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
    			$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
    			$site_configs = parse_ini_file( $configFile, true );
    			if(!empty($site_configs['gallery']['use_smugmug'])) {
    				$libPath = dirname(dirname(dirname(dirname(__FILE__))));
			    	$libPath = str_replace("\\", "/", $libPath);
			    	$libPath = rtrim($libPath, '/');
			    	$libPath .= '/lib';
			    	require($libPath."/phpSmug.php");
			    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
				 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );	
				 	$smugmugDomain = $f->parsed_response['Login']['User']['URL'];
    				foreach ($rs as $key=>$val) {
    					try {
    						if($val['image_class_id']==2) {
	    						//$image = $f->images_getInfo('ImageID='.$val['smugmug_id'],'ImageKey='.$val['smugmug_key']);
	    						//$rs[$key]['source_system_id'] = $image['MediumURL'];
	    						$rs[$key]['file_name'] = $rs[$key]['source_system_id'];
	    						$rs[$key]['source_system_id'] = $smugmugDomain."/photos/i-{$val['smugmug_key']}/0/M/i-{$val['smugmug_key']}-M.jpg";
    						}
    					} catch(Exception $ex) {}
    				}
    			}
    			if(!empty($site_configs['video']['use_youtube'])) {
    				foreach ($rs as $key=>$val) {
    					try {
    						if($val['image_class_id']==1) {
    							$rs[$key]['file_name'] = $rs[$key]['source_system_id'];
	    						$rs[$key]['source_system_id'] = "https://img.youtube.com/vi/".$val['youtube_id']."/0.jpg";
    						}
    					} catch(Exception $ex) {}
    				}
    			}
    			else if(!empty($site_configs['video']['use_brightcove'])) {
    				foreach ($rs as $key=>$val) { if($val['image_class_id']==2) continue; 
    					try {
							$sourceSystemId = $val['source_system_id'];
							if(!empty($sourceSystemId)) {
								$json = @file_get_contents("http://api.brightcove.com/services/library?command=find_video_by_id&video_id={$sourceSystemId}&video_fields=name,THUMBNAILURL,VIDEOSTILLURL&token=".urlencode("x95LXczyNI5-G9kX0cjsHM9edPFzaKFTE4PANJ7L2rQfuF-swGUxJg.."));
								$videoResp = json_decode($json);
								if(!empty($videoResp->videoStillURL)) {
									$rs[$key]['file_name'] = $rs[$key]['source_system_id'];
									$rs[$key]['source_system_id'] = $videoResp->videoStillURL;
								}
							}
						} catch(Exception $ex) {}
    				}
    			}
    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    }
    
    function addimageAction()
    {
		set_time_limit(7200);
    	$imageFiles = array("jpg", "jpeg", "png", );
    	$videoFiles = array("avi", "mpeg", "mov", "mpg", "mp4", "wmv", "ogg", "webm", "m1v", "m4v", "flv", "3gp");
    	$mp3Files = array("mp3");
    	$allAllowedFiles = array_merge($imageFiles, $videoFiles);
    	$allAllowedFiles = array_merge($allAllowedFiles, $mp3Files);
    	
    	$params = $this->_getAllParams();
    	
    	$filetype = explode(".", $params['filename']);
    	$filetype = strtolower($filetype[count($filetype)-1]);
    	
    	if(!empty($params['video_tag'])) {
    		$sourceSystemId = "";
			if(strpos(' '.$params['video_tag'], '<object')) {
				if(strpos($params['video_tag'], 'youtube')) {
					preg_match( '/value="([^"]*)"/i', $params['video_tag'], $array ) ;
					if(!empty($array[1])) $sourceSystemId = $array[1];
				}
				else if(strpos($params['video_tag'], 'brightcove')) {
					preg_match( '/id="([^"]*)"/i', $params['video_tag'], $array ) ;
					if(!empty($array[1])) $sourceSystemId = $array[1];
					$sourceSystemId = str_replace("myExperience", "", $sourceSystemId);
				}
				else {
					preg_match( '/&src=([^&]*)&/i', $params['video_tag'], $array ) ;
					if(!empty($array[1])) $sourceSystemId = $array[1];
					$sourceSystemId = str_ireplace(array('index.html?videoId='), array(''), $sourceSystemId);
				}
			}
			else if(strpos(' '.$params['video_tag'], 'data-video-id')) {
				preg_match( '/data-video-id="([^"]*)"/i', $params['video_tag'], $array ) ;
				if(!empty($array[1])) $sourceSystemId = $array[1];
			}
			else {
				preg_match( '/src="([^"]*)"/i', $params['video_tag'], $array ) ;
				if(!empty($array[1])) $sourceSystemId = $array[1];
				$sourceSystemId = str_ireplace(array('index.html?videoId='), array(''), $sourceSystemId);
			}
			if(empty($sourceSystemId)) exit();
			$params['filename'] = basename($sourceSystemId);
			
			$filetype = "flv";
    	}
    	
    	if(empty($params['filename']) || in_array($filetype, $allAllowedFiles)) {
	    	$params['source_system_id'] = $params['filename'];   	
	    	
	    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
	    	$cic = new contentimagesClass();    	
	    	$params['content_images_id'] = $cic->addContentImages($this->site_id, $params);
	    	
	    	Zend_Loader::LoadClass('contentgalleryimagesClass', $this->modelDir);
	    	$cgic = new contentgalleryimagesClass();
	    	$cgic->addContentGalleryImages($this->site_id, $params); 
	    	
	    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
			$site_configs = parse_ini_file( $configFile, true );
			$datafolder = $site_configs['paths']['html'] . "/images/article_photos/";
	    	
	    	Zend_Loader::LoadClass('imagesClass', $this->modelDir);
			$imagesClass = new imagesClass();
			//$thisfile = explode('.', $params['filename']);
			/*$imageType=$imagesClass->getImageType($filetype[count($filetype)-1]);
			if($imageType['image_type_id'] == 1)
				$imgClass = 2;
			elseif($imageType['image_type_id'] == 2)
				$imgClass = 1;
			elseif($imageType['image_type_id'] == 3)
				$imgClass = 4;*/
			
			if(in_array($filetype, $imageFiles)) $imgClass = 2;
			else if(in_array($filetype, $videoFiles)) $imgClass = 1;
			else if(in_array($filetype, $mp3Files)) $imgClass = 4;
			else $imgClass = 3;
			
			if(in_array($filetype, $imageFiles)) $imgType = 1;
			else if(in_array($filetype, $videoFiles)) $imgType = 2;
			else if(in_array($filetype, $mp3Files)) $imgType = 3;
			else $imgType = 1;

			$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
    		$site_configs = parse_ini_file( $configFile, true );
    		$externalData = array();
			if($imgClass == 2 && !empty($site_configs['gallery']['use_smugmug'])) {
				$externalData = $this->session->uplimage;
				unset($this->session->uplimage);
			}
			else if($imgClass == 1 && !empty($site_configs['video']['use_youtube'])) {
				$externalData['youtube_id'] =  $this->session->uplimage;
				unset($this->session->uplimage);
			}
    		$externalData['video_tag'] = $params['video_tag'];
			$image_id = $imagesClass->insert_image($this->site_id, '1', $params['content_images_id'], $datafolder.$params['source_system_id'], $imgType, $imgClass, $externalData);
			
			Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
	    	$cgc = new contentgalleryClass();
	    	$content_gallery = $cgc->getContentGalleryById($params['content_gallery_id']);
	    	$image_count = $content_gallery['image_count'] + 1;
	    	$cgc->updateImageCount($params['content_gallery_id'], $image_count);
	    	
	    	if(empty($content_gallery['thumbnail_content_images_id']))
	    	{	    		
	    		$cgc->updateThumbContentImage($params['content_gallery_id'], $params['content_images_id']);
	    	}
	    	else
	    	{
	    		/* $img = $imagesClass->getImageBySourceId($this->site_id, $content_gallery['thumbnail_content_images_id']);
	    		if(empty($img))
	    			$cgc->updateThumbContentImage($params['content_gallery_id'], $params['content_images_id']); */
	    		$contentImageId = $this->db->fetchOne("SELECT content_images_id FROM content_gallery_images WHERE content_gallery_id='{$params['content_gallery_id']}' ORDER BY sequence");
	    		$cgc->updateThumbContentImage($params['content_gallery_id'], $contentImageId);
	    	}
			
			$cgc->updateArticleVideoFlag($params['content_gallery_id']);
	    	
			$this->cleanCache();
			//$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
	    	//$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
	    	//$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
	    	//$contentGalleryId = intval($params['content_gallery_id']);
	    	//$this->cache->remove($this->environment."gimages_".$contentGalleryId."_".$this->site_id);
	    	//$articleGalleries = $this->db->fetchAll("SELECT content_articles_id FROM content_article_galleries WHERE content_gallery_id='{$contentGalleryId}'");
	    	//if(is_array($articleGalleries)) foreach ($articleGalleries as $cArtGal) $this->cache->remove($this->environment."articlephoto_".$cArtGal['content_articles_id']."_cms_".$this->site_id);
			
	    	$uniqueId = $this->_request->getParam("uniqueId");
	    	
	    	if(!empty($this->session->uploadsess[$uniqueId]) && $imgClass != 1) {
				$this->session->uploadsess[$uniqueId]['files'][] = array(
					"file_name"		=> $params['filename'],
					"file_url"		=> ((empty($externalData['youtube_id']))?"":$externalData['youtube_id']),
					"added_time"	=> date("Y-m-d H:i:s"),
					"status"		=> "success"
				);
				$this->_response->setRedirect("/admin/content/sendlog/uniqueId/".$uniqueId);
				$this->_response->sendResponse();
			}			
    	}
    }
    
    function updateimageAction()
    {
    	$params = $this->_getAllParams();
    	$imageFiles = array("jpg", "jpeg", "png", );
    	$videoFiles = array("avi", "mpeg", "mov", "mpg", "mp4", "wmv", "ogg", "webm", "m1v", "m4v", "flv", "3gp");
    	$mp3Files = array("mp3");
    	$allAllowedFiles = array_merge($imageFiles, $videoFiles);
    	$allAllowedFiles = array_merge($allAllowedFiles, $mp3Files);
    	
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$cic = new contentimagesClass();  
    	
    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
			
    	if(!empty($params['filename']))
    	{
			$datafolder = $site_configs['paths']['html'] . "/images/article_photos/";
		
    		Zend_Loader::LoadClass('imagesClass', $this->modelDir);
			$imagesClass = new imagesClass();
			$thisfile = explode('.', $params['filename']);
			$filetype = strtolower($thisfile[count($thisfile)-1]);
			/*$imageType=$imagesClass->getImageType($thisfile[count($thisfile)-1]);
			if($imageType['image_type_id'] == 1)
				$imageClass = 2;
			elseif($imageType['image_type_id'] == 2)
				$imageClass = 1;
			elseif($imageType['image_type_id'] == 3)
				$imageClass = 3;*/
			
			if(in_array($filetype, $imageFiles)) $imgClass = 2;
			else if(in_array($filetype, $videoFiles)) $imgClass = 1;
			else if(in_array($filetype, $mp3Files)) $imgClass = 4;
			else $imgClass = 3;
				
			$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
    		$site_configs = parse_ini_file( $configFile, true );
    		$externalData = array();
			if($imgClass==2 && !empty($site_configs['gallery']['use_smugmug'])) {
				$externalData = $this->session->uplimage;
				unset($this->session->uplimage);
			}
			else if($imgClass==1 && !empty($site_configs['video']['use_youtube'])) {
				$externalData['youtube_id'] = $this->session->uplimage;
				unset($this->session->uplimage);
			}
			
			$imagesClass->update_image($params['image_id'], '1', $params['content_images_id'], $datafolder.$params['filename'], $imageType['image_type_id'], $imageClass, $externalData);
			
			$cic->updateSourceSystemId($params['content_images_id'], $params['filename']);
    	}
    	else if(!empty($params['video_tag'])) {
    		Zend_Loader::LoadClass('imagesClass', $this->modelDir);
			$imagesClass = new imagesClass();
    		$imagesClass->update_image($params['image_id'], '1', $params['content_images_id'], '', 2, 1, array('video_tag'=>$params['video_tag']));
    	}
    	
    	Zend_Loader::LoadClass('contentgalleryimagesClass', $this->modelDir);
    	$cgic = new contentgalleryimagesClass();
    	$cgic->updateSequence($params['content_gallery_images_id'], $params['sequence']);
    	
    	/*update gallery thumbnail*/
    	Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
	    $cgc = new contentgalleryClass();
	    $contentImageId = $this->db->fetchOne("SELECT content_images_id FROM content_gallery_images WHERE content_gallery_id='{$params['content_gallery_id']}' ORDER BY sequence");
    	$cgc->updateThumbContentImage($params['content_gallery_id'], $contentImageId);
		
		$cgc->updateArticleVideoFlag($params['content_gallery_id']);
    	
    	$image = $this->db->fetchRow("SELECT * FROM images WHERE image_id='{$params['image_id']}'");
    	
    	$caption = $params['caption'];
    	$title = $params['title'];    	
    	$keywords = $params['keywords'];
    	
    	if($image['image_class_id'] == 1 && !empty($site_configs['video']['use_youtube'])) {
			try {
				Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
				$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
				$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
				              $username = $site_configs['youtube']['email'],
				              $password = $site_configs['youtube']['password'],
				              $service = 'youtube',
				              $client = null,
				              $source = $site_configs['youtube']['source'],
				              $loginToken = null,
				              $loginCaptcha = null,
				              $authenticationURL);
				$httpClient->setConfig(array( 'timeout' => 24*3600 )); 
				$developerKey = $site_configs['youtube']['youtube_api_key'];
				$applicationId = $site_configs['youtube']['source'];
				$clientId = $site_configs['youtube']['source'].' Upload';
				Zend_Loader::loadClass('Zend_Gdata_YouTube');
				$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
				$yt->setMajorProtocolVersion(2);
				Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
				
				$myVideoEntry = $yt->getVideoEntry($image['youtube_id']);
		    	
				$myVideoEntry->setVideoTitle(substr($title, 0, 100));
				$myVideoEntry->setVideoDescription($caption);
				$myVideoEntry->SetVideoTags($keywords);
				
				try {
					$putUrl = "https://gdata.youtube.com/feeds/api/users/default/uploads/".$image['youtube_id'];
					$yt->updateEntry($myVideoEntry, $putUrl, 'Zend_Gdata_YouTube_VideoEntry');
				} catch (Zend_Gdata_App_HttpException $httpException) {
					$msg = $httpException->getRawResponseBody();
				} catch (Zend_Gdata_App_Exception $e) {
				    $msg = $e->getMessage();
				} catch (Exception $ex) { $msg = $ex->getMessage(); }
				
			} catch (Exception $ex) {
				$msg = $ex->getMessage();
			}
		}
		else if($image['image_class_id']==2 && !empty($site_configs['gallery']['use_smugmug'])) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));    	
    		$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
			require($libPath."/phpSmug.php");
			try {
				$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
				$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );	
				$f->images_changeSettings("ImageID=".$image['smugmug_id'], "Caption=".$caption, "Keywords=".$keywords);
			} catch (Exception $ex) {
			}
		}
    	
    	$cic->updateContentImages($params);  
    	
    	$this->cleanCache();
    	//$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
    	//$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
    	//$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
    	//$contentGalleryId = intval($params['content_gallery_id']);
    	//$this->cache->remove($this->environment."gimages_".$contentGalleryId."_".$this->site_id);
    	//$articleGalleries = $this->db->fetchAll("SELECT content_articles_id FROM content_article_galleries WHERE content_gallery_id='{$contentGalleryId}'");
    	//if(is_array($articleGalleries)) foreach ($articleGalleries as $cArtGal) $this->cache->remove($this->environment."articlephoto_".$cArtGal['content_articles_id']."_cms_".$this->site_id);
    }
        
    function uploadimageAction() {
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$cic = new contentimagesClass();
    	
    	$caption = $this->_request->getParam("caption");
    	$title = $this->_request->getParam("title");
    	$keywords = $this->_request->getParam("keywords");
    	
    	$libPath = dirname(dirname(dirname(dirname(__FILE__))));    	
    	$libPath = str_replace("\\", "/", $libPath);
    	$libPath = rtrim($libPath, '/');
    	$libPath .= '/lib';
    	require($libPath."/phpSmug.php");
    	$imageFiles = array("jpg", "jpeg", "png", );
    	$videoFiles = array("avi", "mpeg", "mov", "mpg", "mp4", "wmv", "ogg", "webm", "m1v", "m4v", "flv", "3gp");
    	$allAllowedFiles = array_merge($imageFiles, $videoFiles);
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		$temp = explode(".", $file['name']);
    		$ext = $temp[count($temp)-1];
    		$ext = strtolower($ext);
    		if (in_array($ext, $allAllowedFiles) /*&& in_array($file["type"], array("image/jpeg","image/pjpeg"))*/ && (in_array($ext, $videoFiles) || (in_array($ext, $imageFiles) && $file["size"] <= $this->config->general->maxImageSize))){
				if ($file["error"] > 0) {
					/*$errorInfo = print_r($file, true);
					$errorInfo = str_replace(array("\n", "\r"), "", $errorInfo);
					$msg = "{success:false, errors: { reason: '".htmlentities($errorInfo, ENT_QUOTES)."' }}";*/
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {										
					$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
    				$site_configs = parse_ini_file( $configFile, true );
    				$datafolder = $site_configs['paths']['html'] . "/images/";
    				if(in_array($ext, $imageFiles))
		    		{
		    			$datafolder = $datafolder . "article_photos/";
		    			$file["name"]=preg_replace("/[^a-z \d\_\-.]/i", "", $file["name"]);
						$file["name"] = str_replace(" ", "_", $file["name"]);
			    		if (move_uploaded_file($file["tmp_name"], $datafolder.$file["name"])){
			    			if(!empty($site_configs['gallery']['use_smugmug']) && in_array($ext, $imageFiles)) {
			    				$contentGalleryTable = new content_gallery(array('db'=>'db'));
			    				$select = $contentGalleryTable->select()->where("content_gallery_id=?", $this->_request->getParam("content_gallery_id"));
    							$gallery = $contentGalleryTable->getAdapter()->fetchRow($select);
			    				$smugmugId = $gallery['smugmug_id'];
			    				$smugmugKey = $gallery['smugmug_key'];
			    				if(empty($smugmugId)) $smugmugId = $site_configs['gallery']['smugmug_gallery_id'];
			    				if(empty($smugmugKey)) $smugmugKey = $site_configs['gallery']['smugmug_gallery_key'];
			    				try {
				    				$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
				    				$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );	
				    				$image = $f->images_upload("AlbumID={$smugmugId}", "File=".$datafolder.$file["name"]);
				    				$this->session->uplimage = $image;
			    				} catch (Exception $ex) {
			    					$msg = "{success:false, errors: { reason: '". htmlentities($ex->getMessage(), ENT_QUOTES) ."' }}";
			    					exit();
			    				}
			    				@unlink($datafolder.$file["name"]);
			    			}
							$msg = "{success:true, filename: '".$file['name']."'}";
						}	
						else {
							$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";	
						}
		    		}
		    		else if(in_array($ext, $videoFiles))
		    		{
		    			$datafolder = $datafolder . "article_photos/";
		    			$file["name"]=preg_replace("/[^a-z \d\_\-.]/i", "", $file["name"]);
						$file["name"] = str_replace(" ", "_", $file["name"]);
			    		if (move_uploaded_file($file["tmp_name"],$datafolder.$file["name"])){
			    			if(!empty($site_configs['video']['use_youtube']) && in_array($ext, $videoFiles)) {
			    				$newFile = $datafolder.$file["name"];
			    				try {
				    				Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
									$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
									$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
									              $username = $site_configs['youtube']['email'],
									              $password = $site_configs['youtube']['password'],
									              $service = 'youtube',
									              $client = null,
									              $source = $site_configs['youtube']['source'],
									              $loginToken = null,
									              $loginCaptcha = null,
									              $authenticationURL);
									$httpClient->setConfig(array( 'timeout' => 24*3600 )); 
									$developerKey = $site_configs['youtube']['youtube_api_key'];
									$applicationId = $site_configs['youtube']['source'];
									$clientId = $site_configs['youtube']['source'].' Upload';
									Zend_Loader::loadClass('Zend_Gdata_YouTube');
									$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
									$yt->setMajorProtocolVersion(2);
									Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
									$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
									$filesource = $yt->newMediaFileSource($newFile);
									if(in_array($ext, array("avi","mpeg", "mpg", "m1v", "m4v")))
										$filesource->setContentType('video/mpeg');
									else if(in_array($ext, array("mp4")))
										$filesource->setContentType('video/mp4');
									else if(in_array($ext, array("ogg")))
										$filesource->setContentType('video/ogg');
									else if(in_array($ext, array("mov")))
										$filesource->setContentType('video/quicktime');
									else if(in_array($ext, array("webm")))
										$filesource->setContentType('video/webm');
									else if(in_array($ext, array("wmv")))
										$filesource->setContentType('video/x-ms-wmv');
									else if(in_array($ext, array("flv")))
										$filesource->setContentType('video/x-flv');
									else 
										$filesource->setContentType('video/mpeg');
									
									$filesource->setSlug($file["name"]);
									//$myVideoEntry->setVideoPrivate();
									$myVideoEntry->setMediaSource($filesource);
									$myVideoEntry->setVideoTitle(substr($title, 0, 100));
									$myVideoEntry->setVideoDescription($caption);
									$myVideoEntry->setVideoCategory('News');
									$myVideoEntry->SetVideoTags($keywords);
									$myVideoEntry->setVideoDeveloperTags(array('cms',));
									$yt->registerPackage('Zend_Gdata_Geo');
									$yt->registerPackage('Zend_Gdata_Geo_Extension');
									/*
									$geocode=$this->curlGet('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
									$output= json_decode($geocode);
									if(!empty($output->results[0]->geometry->location->lat) && !empty($output->results[0]->geometry->location->lng)) {
										$lat = $output->results[0]->geometry->location->lat;
										$long = $output->results[0]->geometry->location->lng;
										$where = $yt->newGeoRssWhere();
										$position = $yt->newGmlPos($lat.' '.$long);			
										$where->point = $yt->newGmlPoint($position);
										$myVideoEntry->setWhere($where);
									}
									*/
									Zend_Loader::loadClass('Zend_Gdata_App_Extension_Control');
									Zend_Loader::loadClass('Zend_Gdata_YouTube_Extension_State');
									$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
									$msg = $video = "";
									try {
										$newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
										$newEntry->setMajorProtocolVersion(2); 
										$videoId = $newEntry->getVideoId();
									} catch (Zend_Gdata_App_HttpException $httpException) {
										$msg = $httpException->getRawResponseBody();
									} catch (Zend_Gdata_App_Exception $e) {
									    $msg = $e->getMessage();
									} catch (Exception $ex) { $msg = $ex->getMessage(); }
									if(!empty($msg)) {
										$msg = "{success:false, errors: { reason: '". htmlentities($msg, ENT_QUOTES) ."' }}";
									}
									if(!empty($videoId)) {
										$videoId = addslashes(stripslashes($videoId));
										$this->session->uplimage = $videoId;
									}
			    				} catch (Exception $ex) {
			    					$msg = "{success:false, errors: { reason: '". htmlentities($ex->getMessage(), ENT_QUOTES) ."' }}";
			    				}
			    				@unlink($datafolder.$file["name"]);
			    			}
			    			if(empty($msg)) $msg = "{success:true, filename: '".$file['name']."'}";
						}	
						else {
							$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";	
						}
		    		}
					
				}
			}	
			else if(!in_array($ext, $imageFiles)){
				$msg = "{success:false, errors: { reason: 'Only jpg and png files can be uploaded.' }}";
			}
			else if(!in_array($ext, $videoFiles)){
				$msg = "{success:false, errors: { reason: 'Only avi, mpeg, mov, mpg, mp4, wmv, ogg, webm, m1v, m4v files can be uploaded.' }}";
			}
			else if(in_array($ext, $imageFiles) && $file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";								
			}
			else {
				$msg = "{success:false, errors: { reason: 'Unidentified error.' }}";
			}
    	}
    	echo $msg;    	
	}
	
	function getimageAction() {
		Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$contentimagesClass = new contentimagesClass();
    	$content_images_id = $this->_request->getParam("content_images_id");
    	$image = $contentimagesClass->getImage($content_images_id);
		
    	if(!empty($image["source_system_id"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
    		
			if($image['image_class_id']==2 && !empty($configs['gallery']['use_smugmug'])) {
				$libPath = dirname(dirname(dirname(dirname(__FILE__))));
		    	$libPath = str_replace("\\", "/", $libPath);
		    	$libPath = rtrim($libPath, '/');
		    	$libPath .= '/lib';
		    	require($libPath."/phpSmug.php");
		    	$f = new phpSmug( "APIKey=".$configs['smugmug']['api_key'], "AppName=".$configs['smugmug']['app_name'] );
			 	$f->login( "EmailAddress=".$configs['smugmug']['email'], "Password=".$configs['smugmug']['password'] );	
				try {
					$image = $f->images_getInfo('ImageID='.$image['smugmug_id'],'ImageKey='.$image['smugmug_key']);
					$data['fileName'] = basename($image['OriginalURL']);
					$data["filePath"] = $image['MediumURL'];
				} catch(Exception $ex) {}
			}
			else if($image['image_class_id']==1 && !empty($configs['video']['use_youtube'])) {
				$data['fileName'] = $image["source_system_id"];
				$data["filePath"] = "https://img.youtube.com/vi/".$image['youtube_id']."/0.jpg";
			}
			else {
	    		$data["fileName"] = $image["source_system_id"];
	    		$data["filePath"] = trim($configs["general"]["url"],'/'). "/images/article_photos/".$image["source_system_id"];
			}
    		echo '{"result":['. json_encode($data).']}';
    	}
	}
	
	function getimagebyidAction(){
		$params = $this->_getAllParams();

		$response['success'] = true;
    	$response['data'] = array();
			
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$cic = new contentimagesClass();
    	$rs = $cic->getImageById($params['content_images_id']);
    	
    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
	}
	
	function deleteimagesAction()
	{
		$params =  $this->_request->getParam('datas');
		
		Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
	    $cic = new contentimagesClass();
	    $contentImageTable = new content_images(array('db'=>'db'));
		$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		
		if(!empty($site_configs['gallery']['use_smugmug'])) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));
	    	$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
	    	require($libPath."/phpSmug.php");
	    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
		 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );
		}
		
		if(!empty($site_configs['video']['use_youtube'])) {
			try {
				Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
				$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
				$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
				              $username = $site_configs['youtube']['email'],
				              $password = $site_configs['youtube']['password'],
				              $service = 'youtube',
				              $client = null,
				              $source = $site_configs['youtube']['source'],
				              $loginToken = null,
				              $loginCaptcha = null,
				              $authenticationURL);
				//$httpClient->setConfig(array( 'timeout' => 24*3600 )); 
				$developerKey = $site_configs['youtube']['youtube_api_key'];
				$applicationId = $site_configs['youtube']['source'];
				$clientId = $site_configs['youtube']['source'].' Upload';
				Zend_Loader::loadClass('Zend_Gdata_YouTube');
				$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
				$yt->setMajorProtocolVersion(2);
				Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
			} catch (Exception $ex) {	
			}
		}
		

    	if(!empty($params))
    	{    		
	    	$params = explode(",", $params);
	    	foreach ($params as $content_gallery_images_id) {
	    		$image = $contentImageTable->getAdapter()->fetchRow("
	    			SELECT i.*
	    			FROM content_gallery_images cgi 
	    			LEFT JOIN content_images ci ON ci.content_images_id = cgi.content_images_id
	    			LEFT JOIN images i ON i.source_id = ci.content_images_id
	    			WHERE cgi.content_gallery_images_id='{$content_gallery_images_id}'
	    		");
	    		if(!empty($image['smugmug_id'])) {
	    			try {
	    				$f->images_delete("ImageID=".$image['smugmug_id']);
	    			}
	    			catch (Exception $ex) { echo $ex->getMessage(); }
	    		}
	    		else if(!empty($image['youtube_id'])) {
	    			try {
	    				$videoEntryToDelete = $yt->getVideoEntry($image['youtube_id'], null, true);
						$yt->delete($videoEntryToDelete);
	    			}
	    			catch (Exception $ex) {}
	    		}
	    		$res = $this->deleteimage($content_gallery_images_id);	    		
	    		if($res > 0)
	    			$content_gallery_id = $res;
	    	}
	    	
	    	if($content_gallery_id>0)
	    	{
	    		
	    		$content_images = $cic->getImagesByGalleryId($this->site_id, $content_gallery_id);
	    		Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
	    		$cgc = new contentgalleryClass();
	    		
	    		$cgc->updateThumbContentImage($content_gallery_id, $content_images[0]['content_images_id']);
				$cgc->updateArticleVideoFlag($content_gallery_id);
	    	}	   	
	    	
			$this->cleanCache();
	    	/*$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
	    	if(!empty($content_gallery_id)) {
		    	$contentGalleryId = $content_gallery_id;
		    	$this->cache->remove($this->environment."gimages_".$contentGalleryId."_".$this->site_id);
		    	$articleGalleries = $this->db->fetchAll("SELECT content_articles_id FROM content_article_galleries WHERE content_gallery_id='{$contentGalleryId}'");
		    	if(is_array($articleGalleries)) foreach ($articleGalleries as $cArtGal) $this->cache->remove($this->environment."articlephoto_".$cArtGal['content_articles_id']."_cms_".$this->site_id);
	    	}
			*/
	    	
	    	//$this->cleanCache();
    	}
	}
	
	function deletegalleryAction()
	{
		$params =  $this->_request->getParam('datas');
		
		$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		if(!empty($site_configs['gallery']['use_smugmug'])) {
			$libPath = dirname(dirname(dirname(dirname(__FILE__))));
	    	$libPath = str_replace("\\", "/", $libPath);
	    	$libPath = rtrim($libPath, '/');
	    	$libPath .= '/lib';
	    	require($libPath."/phpSmug.php");
	    	$f = new phpSmug( "APIKey=".$site_configs['smugmug']['api_key'], "AppName=".$site_configs['smugmug']['app_name'] );
		 	$f->login( "EmailAddress=".$site_configs['smugmug']['email'], "Password=".$site_configs['smugmug']['password'] );
		}
		
		if(!empty($params))
    	{
    		Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    		$cgc = new contentgalleryClass();
    		
    		Zend_Loader::LoadClass('contentgalleryimagesClass', $this->modelDir);
    		$cgic = new contentgalleryimagesClass();
    			
	    	$params = explode(",", $params);
	    	foreach ($params as $content_gallery_id) {
	    		
    			if(!empty($site_configs['gallery']['use_smugmug'])) {
    				$contentGalleryTable = new content_gallery(array('db'=>'db'));
    				$select = $contentGalleryTable->getAdapter()->select()->from(array("cg"=>"content_gallery"), array("cg.*"));
    				$select->joinLeft(array("s"=>"sites"), "s.site_id=cg.site_id", "s.site_group_id");
    				$select->where("cg.content_gallery_id=?", $content_gallery_id);
    				if(!empty($this->site_group_id)) $select->where("s.site_group_id=?", $this->site_group_id);
    				else $select->where("cg.site_id=?", $this->site_id);    				
    				$gallery = $contentGalleryTable->getAdapter()->fetchRow($select);
    				
    				if(!empty($gallery['smugmug_id'])) {
	    				try {
	    					if($gallery['content_gallery_type_id']==1) $response = $f->albums_delete("AlbumID=".$gallery['smugmug_id']);
	    				} catch (Exception $ex) {}
    				}
    			}
    			
    			$cgc->deleteContentGallery($content_gallery_id);
    			
	    		
    			$content_gallery_images = $cgic->getContentGalleryImages($content_gallery_id);
    			foreach ($content_gallery_images as $cgi) {
		    		$this->deleteimage($cgi['content_gallery_images_id']);
		    	}    			
	    	}
	    	//$this->cleanCache();
	    	$this->cache->remove($this->environment."gallery_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."articlehslideshow_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."articleslideshow_cms_".$this->site_id);
	    	if(!empty($content_gallery_id)) {
		    	$contentGalleryId = $content_gallery_id;
		    	$this->cache->remove($this->environment."gimages_".$contentGalleryId."_".$this->site_id);
		    	$articleGalleries = $this->db->fetchAll("SELECT content_articles_id FROM content_article_galleries WHERE content_gallery_id='{$contentGalleryId}'");
		    	if(is_array($articleGalleries)) foreach ($articleGalleries as $cArtGal) $this->cache->remove($this->environment."articlephoto_".$cArtGal['content_articles_id']."_cms_".$this->site_id);
	    	}
    	}
	}
	
	function deleteimage($content_gallery_images_id)
	{		
		Zend_Loader::LoadClass('contentgalleryimagesClass', $this->modelDir);
    	$cgic = new contentgalleryimagesClass();
    	
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	Zend_Loader::LoadClass('contentimagesClass', $this->modelDir);
    	$cic = new contentimagesClass();
    	
    	Zend_Loader::LoadClass('imagesClass', $this->modelDir);
		$ic = new imagesClass();
		
		Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
		$cgc = new contentgalleryClass();
		
		$content_gallery_images = $cgic->getContentGalleryImagesById($content_gallery_images_id);
		$cgic->deleteContentGalleryImages($content_gallery_images_id);
		
		$content_gallery = $cgc->getContentGalleryById($content_gallery_images['content_gallery_id']);
    	$image_count = $content_gallery['image_count'] - 1;
    	$cgc->updateImageCount($content_gallery_images['content_gallery_id'], $image_count);
    			
		$content_images = $cic->getImageById($content_gallery_images['content_images_id']);
		$cic->deleteContentImages($content_gallery_images['content_images_id']);
		$ic->deleteImages($content_gallery_images['content_images_id']);
		
		$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		$datafolder = $site_configs['paths']['html'] . "/images/article_photos/";
		if(!empty($content_images['source_system_id']) && file_exists($datafolder.$content_images['source_system_id']))
			@unlink($datafolder.$content_images['source_system_id']);
	
		if($content_gallery['thumbnail_content_images_id']==$content_gallery_images['content_images_id'])
			return $content_gallery['content_gallery_id'];
		else
			return '0';
	}
    
    
    /*** CONTENT AREAS ***/
    
    function contentareasAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(10, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('menubar.php');
        echo $this->view->render('footer.php');
    }
    
    /**
     * AJAX / JSON action which delivers a list of content areas
     *
     */
    function getcontentareasAction()
    {
    	Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$cac = new contentareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $cac->getContentAreas($this->site_id, $params);
    	if ( is_array($data) ) {
    		$response['success'] = true;    		
    		if ( sizeof($data) > 0 ) {
    			foreach ( $data as &$thisData ) {    				
					$thisData['is_special'] = $thisData['is_special'] == '1' ? 'Yes' : 'No';
					$thisData['enable_column'] = $thisData['enable_column'] == '1' ? 'Yes' : 'No';
				}   			
    			$response['data'] = $data;
    		}
   		}
    	echo json_encode($response);
    }
    
     /**
     * adding a new content area
     *
     */
	function addcontentareaAction()
    {
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$cac = new contentareasClass();
    	$params = $this->_getAllParams();
    	
    	if(isset($params['is_special']) && $params['is_special'] == 'on')
    		$params['is_special'] = 1;
    	else
    		$params['is_special'] = 0;
    		
    	if(isset($params['enable_column']) && $params['enable_column'] == 'on')
    		$params['enable_column'] = 1;
    	else
    		$params['enable_column'] = 0;
    		
    	if(empty($params['column_order']))
    		$params['column_order'] = '0';
    	
    	$cac->addContentArea($this->site_id, $params);
    	
    	//$this->cleanCache();
    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    }
    
    /**
     * 
     * Remove selected Content Areas
     *
     */
	function deletecontentareasAction()
    {
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$cac = new contentareasClass();
    	
    	Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $area_id) {
	    		$cac->deleteContentAreas($area_id);
	    		$csa->deleteContentSectionAreaByAreaId($area_id);
	    		
	    		$this->cache->remove($this->environment."area_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."articleslideshow_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."latestnews_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."area_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."topreadtoday_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."topReadYesterday_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."topReadPassWeek_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."topReadPassMonth_".$area_id."_cms_".$this->site_id);
	    		$this->cache->remove($this->environment."ca_".$area_id."_cms_".$this->site_id);
	    	}
	    	//$this->cleanCache();
	    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	}
    }
    
    /**
     * AJAX / JSON action which delivers a row of content area for the selected ID.
     *
     */
    function getcontentareabyidAction()
    {
    	Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$cac = new contentareasClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $cac->getContentAreaById($params['area_id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
    
    /**
     * AJAX / JSON action which will modify section
     */
    function setcontentareabyidAction()
    {
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$cac = new contentareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	if(isset($params['is_special']) && $params['is_special'] == 'on')
    		$params['is_special'] = 1;
    	else
    		$params['is_special'] = 0;
    		
    	if(isset($params['enable_column']) && $params['enable_column'] == 'on')
    		$params['enable_column'] = 1;
    	else
    		$params['enable_column'] = 0;
    		
    	if(empty($params['column_order']))
    		$params['column_order'] = '0';
    	
    	$cac->updateContentArea($params);
    	
    	$this->cache->remove($this->environment."area_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."articleslideshow_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."latestnews_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."area_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topreadtoday_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadYesterday_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassWeek_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassMonth_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."ca_".$params['area_id']."_cms_".$this->site_id);
    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	
    	//$this->cleanCache();
    }
    
    /**
     * AJAX / JSON action which delivers a list of content section areas
     *
     */
    function getcontentsectionareasAction()
    {
    	Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $csa->getContentSectionAreas($this->site_id, $params['area_id']);
    	
    	if ( is_array($data) ) {
    		$response['success'] = true;    
    		foreach ( $data as &$thisData ) {    				
				$thisData['submenu_enable_column'] = $thisData['enable_column'] == '1' ? 'Yes' : 'No';
			}   				
    		$response['data'] = $data;
   		}
    	echo json_encode($response);
    }
    
    /**
     * adding a new content section area
     *
     */
	function addcontentsectionareaAction()
    {
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	//$params['custom_page_content'] = $this->trimHtmlText($params['custom_page_content']);
    	
    	$js_tag = preg_match_all( '|<script.*?src=[\'"](.*?)[\'"].*?>|i', $params['custom_page_content'], $matches );
    	if ($js_tag === FALSE) 
    	{
    		$params['custom_page_content'] = $this->trimHtmlText($params['custom_page_content']);
    	}
    	else
    	{	    		    	
	    	$pos = strpos($matches[0][0], "www.dashrecipes.com");	 
	    	$pos2 = strpos($matches[0][0], "puzzles.kingdigital.com");   	
    		if($pos === FALSE && $pos2 === FALSE)
    			$params['custom_page_content'] = $this->trimHtmlText($params['custom_page_content']);
    	}
    	
    	if(isset($params['enable_submenu']) && $params['enable_submenu'] == 'on')
    		$params['enable_submenu'] = 1;
    	else
    		$params['enable_submenu'] = 0;
    	
    	if(isset($params['submenu_enable_column']) && $params['submenu_enable_column'] == 'on')
    		$params['submenu_enable_column'] = 1;
    	else
    		$params['submenu_enable_column'] = 0;
    		
    	if(empty($params['submenu_column_order']))
    		$params['submenu_column_order'] = '0';
    	
    	$content_section_area_id = $csa->addContentSectionArea($this->site_id, $params);
    	
    	if($params['template'] == 5)
    	{
    		Zend_Loader::LoadClass('sectionfrontlookandfeelClass', $this->modelDir);
    		$sfLookandFeelClass = new sectionfrontlookandfeelClass();
    		$params['content_section_area_id']=$content_section_area_id;
    		$sectionfront_lookandfeel_id = $sfLookandFeelClass->addLookandFeel($this->site_id, $params);
    	}
    	
    	$this->cache->remove($this->environment."area_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."articleslideshow_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."latestnews_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."area_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topreadtoday_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadYesterday_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassWeek_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassMonth_".$params['area_id']."_cms_".$this->site_id);
		$this->cache->remove($this->environment."ca_".$params['area_id']."_cms_".$this->site_id);
    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	//$this->cleanCache();
    	
    	$data["success"]= true;
    	$data["content_section_area_id"] = $content_section_area_id;
    	$data["sectionfront_lookandfeel_id"] = $sectionfront_lookandfeel_id;
    	echo json_encode($data);
    }
    
    /**
     * 
     * Remove selected Content Section Areas
     *
     */
	function deletecontentsectionareaAction()
    {
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	$areaId = 0;
	    	foreach ($params as $content_section_area_id) {
	    		if(empty($areaId)) $areaId = $this->db->fetchOne("SELECT area_id FROM content_section_area WHERE content_section_area_id='{$content_section_area_id}'");
	    		$csa->deleteContentSectionArea($content_section_area_id);
	    		$this->cache->remove($this->environment."csa_".$content_section_area_id."_cms_".$this->site_id);
	    	}
	    	//$this->cleanCache();
	    	
	    	$this->cache->remove($this->environment."area_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."articleslideshow_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."latestnews_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."area_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."topreadtoday_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."topReadYesterday_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."topReadPassWeek_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."topReadPassMonth_".$areaId."_cms_".$this->site_id);
			$this->cache->remove($this->environment."ca_".$areaId."_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	}
    }
    
    /**
     * AJAX / JSON action which delivers a row of content section area for the selected ID.
     *
     */
    function getcontentsectionareabyidAction()
    {
    	Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	
    	$rs = $csa->getContentSectionAreaById($params['content_section_area_id']);
    	
    	
		
    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
    			$rs['submenu_column_order'] = $rs['column_order'];
				$rs['submenu_enable_column'] = $rs['enable_column'];
				
				Zend_Loader::LoadClass('sectionfrontlookandfeelClass', $this->modelDir);
		    	$sfLookandFeelClass = new sectionfrontlookandfeelClass();
				$sectionFrontLookandFeel = $sfLookandFeelClass->getLookandFeelByContentSectionAreaId($params['content_section_area_id']);
				if(!empty($sectionFrontLookandFeel))
					$data = array_merge($rs, $sectionFrontLookandFeel);
				else
					$data = $rs;
	    		$response['data'] = $data;
    		}
   		}
    	echo json_encode($response);
    } 
    
    /**
     * AJAX / JSON action which will modify content section area
     */
    function setcontentsectionareabyidAction()
    {
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	if(empty($params['template']))
    	{
    		$params['template'] = 0;
    	}
    	
    	
    	if(isset($params['enable_submenu']) && $params['enable_submenu'] == 'on')
    		$params['enable_submenu'] = 1;
    	else
    		$params['enable_submenu'] = 0;
    	
    	if(isset($params['submenu_enable_column']) && $params['submenu_enable_column'] == 'on')
    		$params['submenu_enable_column'] = 1;
    	else
    		$params['submenu_enable_column'] = 0;
    		
    	if(empty($params['submenu_column_order']))
    		$params['submenu_column_order'] = '0';
    	
    	$js_tag = preg_match_all( '|<script.*?src=[\'"](.*?)[\'"].*?>|i', $params['custom_page_content'], $matches );
    	if ($js_tag === FALSE) 
    	{
    		$params['custom_page_content'] = $this->trimHtmlText($params['custom_page_content']);
    	}
    	else
    	{	    		    	
	    	$pos = strpos($matches[0][0], "www.dashrecipes.com");	    	
    		$pos2 = strpos($matches[0][0], "puzzles.kingdigital.com");   	
    		if($pos === FALSE && $pos2 === FALSE)
    			$params['custom_page_content'] = $this->trimHtmlText($params['custom_page_content']);
    	}
    	
    	$content_section_area_id = $csa->updateContentSectionArea($params);
    	
    	if($params['template'] == 5)
    	{
    		Zend_Loader::LoadClass('sectionfrontlookandfeelClass', $this->modelDir);
    		$sfLookandFeelClass = new sectionfrontlookandfeelClass();
    		$data['content_section_area_id'] = $params['content_section_area_id'];
    		$data['section_id'	]			 = $params['section_id'];
			$data['title_bg_color'	]		 = $params['title_bg_color'];
			$data['title_font_color']		 = $params['title_font_color'];
			$data['line_color']				 = $params['line_color'];
			$data['logo_url']				 = $params['logo_url'];
    		if(empty($params['sectionfront_lookandfeel_id']))
    		{
    			$sectionfront_lookandfeel_id = $sfLookandFeelClass->addLookandFeel($this->site_id, $data);
    		}
    		else {
    			$data['sectionfront_lookandfeel_id'] = $params['sectionfront_lookandfeel_id'];
    			$sfLookandFeelClass->update($this->site_id, $data);	
    			$sectionfront_lookandfeel_id = $params['sectionfront_lookandfeel_id'];
    		}
    		
    	}
    	
    	$areaId = $this->db->fetchOne("SELECT area_id FROM content_section_area WHERE content_section_area_id='{$content_section_area_id}'");
    	
    	//$this->cleanCache();
    	
    	$this->cache->remove($this->environment."csa_".$content_section_area_id."_cms_".$this->site_id);
    	$this->cache->remove($this->environment."area_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."articleslideshow_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."latestnews_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."area_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topreadtoday_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadYesterday_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassWeek_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."topReadPassMonth_".$areaId."_cms_".$this->site_id);
		$this->cache->remove($this->environment."ca_".$areaId."_cms_".$this->site_id);
    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."csaslideshow_".$content_section_area_id."_cms_".$this->site_id);
    	
    	$data["success"]= true;
    	$data["content_section_area_id"] = $content_section_area_id;
    	$data["sectionfront_lookandfeel_id"] = $sectionfront_lookandfeel_id;
    	echo json_encode($data);
    }
    
    function getcontentsectionareatypeAction()
    {
    	Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $csa->getContentSectionAreaType();
    	if ( is_array($data) ) {
    		$response['success'] = true;    		
    		if ( sizeof($data) > 0 ) {	
    			$response['data'] = $data;
    		}
   		}
    	echo json_encode($response);
    }
    
    function cleanCacheGalleryImage()
    {
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$areas = new contentareasClass();
		$contentAreas = $areas->getContentAreas($this->site_id, $params);		
		foreach($contentAreas as $ca)
		{
			$this->cache->remove("articleslideshow_".$ca["area_id"]."_cms_".$this->site_id);
		}
		
		Zend_Loader::LoadClass('articlesClass', $this->modelDir);
	    $article = new articlesClass();
		$contentArticle = $article->getArticlesBySite($this->site_id);
		foreach($contentArticle as $cart)
		{
			$this->cache->remove("article_".$cart["article_id"]."_cms_".$this->site_id);
			$this->cache->remove("articlephoto_".$cart["article_id"]."_cms_".$this->site_id);
		}
		
		$this->cache->remove("articleslideshow_cms_".$this->site_id);
		$this->cache->remove("audios_cms_".$this->site_id);
		$this->cache->remove("gallery_cms_".$this->site_id);
		$this->cache->remove("latestaudios_cms_".$this->site_id);
		$this->cache->remove("latestphotos_cms_".$this->site_id);
		$this->cache->remove("latestslideshows_cms_".$this->site_id);
		$this->cache->remove("latestvideos_cms_".$this->site_id);
		$this->cache->remove("mostviewedaudios_cms_".$this->site_id);
		$this->cache->remove("mostviewedphotos_cms_".$this->site_id);
		$this->cache->remove("mostviewedslideshows_cms_".$this->site_id);
		$this->cache->remove("mostviewedvideos_cms_".$this->site_id);
		$this->cache->remove("photos_cms_".$this->site_id);
		$this->cache->remove("slideshows_cms_".$this->site_id);
		$this->cache->remove("videos_cms_".$this->site_id);	
    }
    
    function cleanCacheMenu()
    {
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$areas = new contentareasClass();
		$contentAreas = $areas->getContentAreas($this->site_id, $params);		
		foreach($contentAreas as $ca)
		{
			$this->cache->remove("area_".$ca["area_id"]."_cms_".$this->site_id);
		}
		
		$this->cache->remove("menu_cms_".$this->site_id);
		$this->cache->remove("submenu_cms_".$this->site_id);
    }
    
    function migrateAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(17, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('menu_migrate.php');
        echo $this->view->render('footer.php');
    }
    
    function getcontentareasliveAction()
    {
    	Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$areas = new contentareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $areas->getContentAreas($this->site_id, $params, true);
    	if ( is_array($data) ) {
    		$response['success'] = true;    		
    		if ( sizeof($data) > 0 ) { 			
    			$response['data'] = $data;
    		}
   		}
    	echo json_encode($response);
    }
    
    function docontentareasmigrateAction()
    {
    	Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$areas = new contentareasClass();
    	
    	$params = $this->_getAllParams();
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "apt") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(17, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	
    	if (!$readOnly && $this->site_id > 0 ) {
    		$dataKeys = json_decode($params['data'], true);
			$ids="";
			foreach ($dataKeys as $id)
				$ids = $ids.$id['area_id'].",";
			$params['ids'] = substr($ids, 0, -1);
    		$arrContentAreas = $areas->getContentAreas($this->site_id, $params);
    		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    		$contentSectionAreaTable = new contentsectionareasClass();
    		Zend_Loader::LoadClass('contentsectionareagroupClass', $this->modelDir);
    		$csag = new contentsectionareagroupClass();
    		
    		if ( $params['logic'] == 'migrate' ) {
    			$retVal = $areas->migrateContentAreas($this->site_id, $arrContentAreas, $params); 
    			foreach ($dataKeys as $dk)
				{
					$contentSectionArea = $contentSectionAreaTable->getContentSectionAreaByAreaId($this->site_id, $dk['area_id']); 
					$contentSectionAreaTable->deleteContentSectionAreaProd($dk['area_id']);
					$csag->deleteContentSectionAreaGroupProd($dk['area_id']);
					foreach ( $contentSectionArea as $dt )
					{						
						$contentSectionAreaTable->addContentSectionAreaProd($this->site_id, $dt);
						if(!empty($dt['content_section_area_group_id']))
						{
							$arrCSAGroup = $csag->getContentSectionAreaGroupById($dt['content_section_area_group_id']);							
							$csag->migrateContentSectionAreaGroup($this->site_id, $arrCSAGroup);	
						}
						if(!empty($dt['pdf_file']))
						{
							$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/pdf/";
							$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/pdf/";
							if(!empty($dt['pdf_file']))
								copy($srcDir.$dt['pdf_file'], $destDir.$dt['pdf_file']);
						}
					}	
				}
				/*refresh fckeditor uploads photos*/			
				$devImagesPath = $this->config->paths->sitepath."/common/images/temp";
				$prodImagesPath = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/common/images/temp";
				if(is_dir($devImagesPath) && is_dir($prodImagesPath)) {
					$dir = dir($devImagesPath);
			    	while (false !== ($entry = $dir->read())) {
			        	// Skip pointers
			        	if ($entry == '.' || $entry == '..') {
			            	continue;
			        	}
			        	if(file_exists($prodImagesPath."/".$entry)) @unlink($prodImagesPath."/".$entry);
			        	@copy($devImagesPath."/".$entry, $prodImagesPath."/".$entry);
			    	}
			
			    	// Clean up
			    	$dir->close();
				}
    		}
    		elseif ( $params['logic'] == 'copy' ) {
    			$retVal = $areas->migrateCopyContentAreas($this->site_id, $arrContentAreas, $params);
    			
    			
    			Zend_Loader::LoadClass('siteClass', $this->modelDir);
    			$siteClass = new siteClass();
    			$copySite = $siteClass->getSite($params['copySiteid']);
    			foreach ($dataKeys as $dk)
				{
					$contentSectionArea = $contentSectionAreaTable->getContentSectionAreaByAreaId($this->site_id, $dk['area_id']); 
					foreach ( $contentSectionArea as $dt )
					{
						$dt['submenu_column_order'] = $dt['column_order'];
						$dt['submenu_enable_column'] = $dt['enable_column'];
						$contentSectionAreaTable->addContentSectionArea($params['copySiteid'], $dt);
						if(!empty($dt['content_section_area_group_id']))
						{
							$arrCSAGroup = $csag->getContentSectionAreaGroupById($dt['content_section_area_group_id']);
							$csag->addContentSectionAreaGroup($params['copySiteid'], $arrCSAGroup);	
						}
						if(!empty($dt['pdf_file']))
						{
							$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/pdf/";
							$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/pdf/";
							if(!empty($dt['pdf_file']))
								copy($srcDir.$dt['pdf_file'], $destDir.$dt['pdf_file']);
						}
					}	 	
				}   
    		}
    		
    		/*if ( $params['logic'] == 'migrate' ) {
	    		$arrContentAreas = $areas->getContentAreas($this->site_id);
    			$retVal = $areas->migrateContentAreas($this->site_id, $arrContentAreas, $params);    	
    			if($retVal)		
    			{
    				$csa->deleteContentSectionAreaBySiteId($this->site_id, true);
    				$dataKeys = json_decode($params['data'], true);
    				
    				foreach ($dataKeys as $dk)
					{
						//$contentSectionArea = $csa->getContentSectionAreas($this->site_id, $dk['area_id']);
						$contentSectionAreaTable = new content_section_area(array('db'=>'db'));
						$select = $contentSectionAreaTable->select()->where("site_id=?", $this->site_id)->where("area_id=?", $dk['area_id']);
						$contentSectionArea = $contentSectionAreaTable->getAdapter()->fetchAll($select);
						foreach ( $contentSectionArea as $dt )
						{
							$csa->addContentSectionAreaProd($this->site_id, $dt);
						}			
					}
    			}
    		}
    		elseif ( $params['logic'] == 'copy' ) {
    			$arrContentAreas = $areas->getContentAreas($this->site_id);
    			$retVal = $areas->migrateCopyContentAreas($this->site_id, $arrContentAreas, $params);
    		}*/
    	}
    	return self::getcontentareasliveAction();
    }
    
    function progressAction() {
		$id = $this->_request->getParam("UPLOAD_IDENTIFIER");
    	$info = uploadprogress_get_info($id);
    	echo json_encode($info);
    }
    
    function progressPhpAction() {
    	$id = $this->_request->getParam("UPLOAD_IDENTIFIER");
    	$info = uploadprogress_get_info($id);
    	if(!empty($info['filename']))
    		$info['success'] = true;
    	else 
    		$info['success'] = false;
    	echo json_encode($info);
    }
    
    function rrmdir($dir) {
    	if (is_dir($dir)) {
    		$objects = scandir($dir);
    		foreach ($objects as $object) {
    			if ($object != "." && $object != "..") {
    				if (filetype($dir . "/" . $object) == "dir") {
    					$this->rrmdir($dir . "/" . $object); 
    				} else {
    					unlink($dir . "/" . $object);
    				}
    			}
    		}
    		reset($objects);
    		rmdir($dir);
    	}
    }
    
    function createFileFromChunks($temp_dir, $fileName, $chunkSize, $totalSize, $params = array()) {
    
    	// count all the parts of this file
    	$total_files = 0;
    	foreach(scandir($temp_dir) as $file) {
	    	//if (stripos($file, $fileName) !== false) {
			if(!in_array($file, array(".", ".."))) {
		    	$total_files++;
    		}
	    }
		
    	// check that all the parts are present
	    //if ($total_files * $chunkSize >=  $totalSize) {
		if(intval($totalSize/$chunkSize) <= $total_files) {
	   
		    // create the final destination file 
    		if (($fp = @fopen($params['datafolder'].$fileName, 'w')) !== false) {
	    		for ($i=1; $i<=$total_files; $i++) {
		    		@fwrite($fp, file_get_contents($temp_dir.'/'.$fileName.'.part'.$i));
    			}
	    		@fclose($f);
	    		$this->uploadToYoutube($params, $params['datafolder'].$fileName);
		    } else {
    			return false;
	    	}
		
    		// rename the temporary directory (to avoid access from other 
    		// concurrent chunks uploads) and than delete it
    		if (@rename($temp_dir, $temp_dir.'_UNUSED')) {
    			$this->rrmdir($temp_dir.'_UNUSED');
    		} else {
    			$this->rrmdir($temp_dir);
    		}
    	}
   
    }
    
    function uploadToYoutube($params, $filetoupload) {
    	$site_configs = $params['config'];
    	$caption = $params['caption'];
    	$title = $params['title'];    	
    	$keywords = $params['keywords'];
    	$fileName = basename($filetoupload);
    	if(empty($title)) $title = $fileName;
    	$temp = explode(".", $fileName);
    	$ext = $temp[count($temp)-1];
    	$ext = strtolower($ext);
    	$videoFiles = array("avi", "mpeg", "mov", "mpg", "mp4", "wmv", "ogg", "webm", "m1v", "m4v", "flv", "3gp");
    	if(!empty($site_configs['video']['use_youtube']) && in_array($ext, $videoFiles)) {
			$newFile = $filetoupload;
			try {
				set_time_limit(24*3600);
				Zend_Loader::loadClass('Zend_Gdata_ClientLogin'); 
				$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
				$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
				              $username = $site_configs['youtube']['email'],
				              $password = $site_configs['youtube']['password'],
				              $service = 'youtube',
				              $client = null,
				              $source = $site_configs['youtube']['source'],
				              $loginToken = null,
				              $loginCaptcha = null,
				              $authenticationURL);
				$httpClient->setConfig(array( 'timeout' => 24*3600 )); 
				$developerKey = $site_configs['youtube']['youtube_api_key'];
				$applicationId = $site_configs['youtube']['source'];
				$clientId = $site_configs['youtube']['source'].' Upload';
				Zend_Loader::loadClass('Zend_Gdata_YouTube');
				$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
				$yt->setMajorProtocolVersion(2);
				Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
				$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
				$filesource = $yt->newMediaFileSource($newFile);
				if(in_array($ext, array("avi","mpeg", "mpg", "m1v", "m4v")))
					$filesource->setContentType('video/mpeg');
				else if(in_array($ext, array("mp4")))
					$filesource->setContentType('video/mp4');
				else if(in_array($ext, array("ogg")))
					$filesource->setContentType('video/ogg');
				else if(in_array($ext, array("mov")))
					$filesource->setContentType('video/quicktime');
				else if(in_array($ext, array("webm")))
					$filesource->setContentType('video/webm');
				else if(in_array($ext, array("wmv")))
					$filesource->setContentType('video/x-ms-wmv');
				else if(in_array($ext, array("flv")))
					$filesource->setContentType('video/x-flv');
				else 
					$filesource->setContentType('video/mpeg');
				
				$filesource->setSlug($fileName);
				//$myVideoEntry->setVideoPrivate();
				$myVideoEntry->setMediaSource($filesource);
				$myVideoEntry->setVideoTitle(substr($title, 0, 100));
				$myVideoEntry->setVideoDescription($caption);
				$myVideoEntry->setVideoCategory('News');
				$myVideoEntry->SetVideoTags($keywords);
				$myVideoEntry->setVideoDeveloperTags(array('cms',));
				$yt->registerPackage('Zend_Gdata_Geo');
				$yt->registerPackage('Zend_Gdata_Geo_Extension');
				
				Zend_Loader::loadClass('Zend_Gdata_App_Extension_Control');
				Zend_Loader::loadClass('Zend_Gdata_YouTube_Extension_State');
				$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
				$msg = $video = "";
				try {
					$newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
					$newEntry->setMajorProtocolVersion(2); 
					$videoId = $newEntry->getVideoId();
				} catch (Zend_Gdata_App_HttpException $httpException) {
					$msg = $httpException->getRawResponseBody();
				} catch (Zend_Gdata_App_Exception $e) {
				    $msg = $e->getMessage();
				} catch (Exception $ex) { $msg = $ex->getMessage(); }
				if(!empty($videoId)) {
					$videoId = addslashes(stripslashes($videoId));
					$this->session->uplimage = $videoId;
				}
			} catch (Exception $ex) { }
			@unlink($filetoupload);
    	}
    }
    
    function chunkuploadAction() {
    	if (!empty($_FILES)) foreach ($_FILES as $file) {
	    	$caption = $this->_request->getParam("caption");
	    	$title = $this->_request->getParam("title");
	    	$keywords = $this->_request->getParam("keywords");
	    	
	    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
			$site_configs = parse_ini_file( $configFile, true );
			$datafolder = $site_configs['paths']['html'] . "/images/";
			$datafolder = $datafolder . "article_photos/temp/";
	    	if(!is_dir($datafolder)) mkdir($datafolder);
	    	$temp_dir = $datafolder.$_POST['resumableIdentifier'];
			if(!is_dir($temp_dir)) mkdir($temp_dir, 0777, true);
	    	$dest_file = $temp_dir.'/'.$_POST['resumableFilename'].'.part'.$_POST['resumableChunkNumber'];
	    	// create the temporary directory
		
	    	// move the temporary file
		    if (!@move_uploaded_file($file['tmp_name'], $dest_file)) {
			    
	    	} else {
		    	// check if all the parts present, and create the final destination file
			    $this->createFileFromChunks($temp_dir, $_POST['resumableFilename'], 
			    	$_POST['resumableChunkSize'], $_POST['resumableTotalSize'], array(
			    		"caption"		=> $caption,
			    		"title"			=> $title,
			    		"keywords"		=> $keywords,
			    		"config"		=> $site_configs,
			    		"datafolder"	=> $datafolder,
			    	));
	    	}
    	}
    }
    
    function photosuploadAction() {
    	$contentGalleryId = intval($this->_request->getParam("content_gallery_id"));
    	
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteClass = new siteClass();
    	 
    	$site = $siteClass->getSite($this->site_id);
    	$baseDir= dirname(dirname(dirname(dirname(__FILE__))));
    	$baseDir = str_replace("\\", "/", $baseDir);
    	$baseDir = rtrim($baseDir, "/");
    	$sitename = $site['name'];
    	$baseDir .= "/sites/".$sitename;
    	$configOptions = parse_ini_file($baseDir."/config.ini", true);
    	
    	$libPath = dirname(dirname(dirname(dirname(__FILE__))));
    	$libPath = str_replace("\\", "/", $libPath);
    	$libPath = rtrim($libPath, '/');
    	$libPath .= '/lib';
    	require($libPath."/phpSmug.php");
    	 
    	$smugmugCategories = array();
    	try {
    		$f = new phpSmug( "APIKey=".$configOptions['smugmug']['api_key'], "AppName=".$configOptions['smugmug']['app_name']);
    		$f->login( "EmailAddress=".$configOptions['smugmug']['email'], "Password=".$configOptions['smugmug']['password']);
    		$tCategories = $f->categories_get();
    		if(is_array($tCategories)) foreach ($tCategories as $tCategory) $smugmugCategories[$tCategory['id']] = $tCategory['NiceName'];
    	} catch (Exception $ex) {
    	}
    	 
    	$imageFiles = array("jpg", "jpeg", "png", );
    	$videoFiles = array("avi", "mpeg", "mov", "mpg", "mp4", "wmv", "ogg", "webm", "m1v", "m4v", "flv");
    	$mp3Files = array("mp3");
    	//$allAllowedFiles = array_merge($imageFiles, $videoFiles);
    	$allAllowedFiles = $imageFiles;
    	$files = array();
    	
    	/**
    	 * POST vals
    	 * Array
(
    [content_gallery_id] => 89223
    [resumableChunkNumber] => 1
    [resumableChunkSize] => 52428800
    [resumableTotalSize] => 69233
    [resumableIdentifier] => 69233-tx-2016jpg
    [resumableFilename] => tx-2016.jpg
)
    	 */
    	
    	foreach ($_FILES as $key=>$file) {
    		set_time_limit(3600);
    		if(empty($file)) continue;
    		
    		$file['name'] = $_POST['resumableFilename'];
    	
    		$file1 = new stdClass();
    		$file1->name = $file['name'];
    		$file1->type = $file['type'];
    		$file1->size = intval($file['size']);
    		$fileName = $file['name'];
    		$temp = explode(".", $fileName);
    		$ext = $temp[count($temp)-1];
    		$ext = strtolower($ext);
    		if (in_array($ext, $allAllowedFiles)){
    			if ($file["error"] > 0) {
    				$msg = "{success:false, errors: { reason: 'File error.' }}";
    			}
    			else {
    				$datafolder = $configOptions['paths']['html'] . "/images/";
    				if(in_array($ext, $imageFiles))
    				{
    					$datafolder = $datafolder . "article_photos/";
    					$file['name'] = preg_replace("/[^a-z \d\_\-.]/i", "", $file['name']);
    					$file['name'] = str_replace(" ", "_", $file['name']);
    					    					
    					if (move_uploaded_file($file['tmp_name'], $datafolder.$file['name'])){
    						$files[] = $file1;
    						if(!empty($configOptions['gallery']['use_smugmug']) && in_array($ext, $imageFiles)) {
    							$contentGalleryTable = new content_gallery(array('db'=>'db'));
    							$select = $contentGalleryTable->select()->where("content_gallery_id=?", $contentGalleryId);
    							$gallery = $this->db->fetchRow("SELECT * FROM content_gallery WHERE content_gallery_id='{$contentGalleryId}'");
    							$smugmugId = $gallery['smugmug_id'];
    							$smugmugKey = $gallery['smugmug_key'];
    							if(empty($smugmugId)) $smugmugId = $configOptions['gallery']['smugmug_gallery_id'];
    							if(empty($smugmugKey)) $smugmugKey = $configOptions['gallery']['smugmug_gallery_key'];
    							 
    							$rotate = 0;
    							/*try to read image orientation stated on exif data*/
    							$exif = exif_read_data($datafolder.$file['name']);
    							if(!empty($exif['Orientation'])) {
    								$orientation = intval($exif['Orientation']);
    								switch ($orientation) {
    									case 1:
    										//do nothing
    										break;
    									case 2:
    										//horizontal flip
    										break;
    									case 3:
    										$rotate = 180;
    										break;
    									case 4:
    										//vertical flip
    										break;
    									case 5:
    										//vertical flip + rotate 270
    									case 6:
    										//only rotate 270
    									case 7:
    										//horizontal flip + rotate 270
    										$rotate = 270;
    										break;
    									case 8:
    										$rotate = 90;
    										break;
    								}
    							}
    							/*end of reading exif orientation*/
    							 
    							try {
    								if(!empty($gallery['smugmug_categoryid']) && !empty($smugmugCategories[$gallery['smugmug_categoryid']])) {
    									$image = $f->images_upload("AlbumID={$smugmugId}", "File=".$datafolder.$file['name'], "Keywords={$smugmugCategories[$gallery['smugmug_categoryid']]}");
    									$keywords = $smugmugCategories[$gallery['smugmug_categoryid']];
    								} else
    									$image = $f->images_upload("AlbumID={$smugmugId}", "File=".$datafolder.$file['name']);
    	
    								if(!empty($rotate)) {
    									$resp = $f->images_rotate("ImageID=".$image['id'], "Degrees=".$rotate);
    								}
    								$this->session->uplimage = $image;
    							} catch (Exception $ex) {
    								$msg = "{success:false, errors: { reason: '". htmlentities($ex->getMessage(), ENT_QUOTES) ."' }}";
    								echo $msg;
    								exit();
    							}
    							
    							$iinfo = getimagesize($datafolder.$file['name'], $info);
    							if(!empty($info['APP13'])) {
    								$iptc = iptcparse($info['APP13']);
    								if(!empty($iptc['2#120'][0])) {
    									$caption = $title = $iptc['2#120'][0];
    								}
    								if(!empty($iptc['2#080'][0])) {
    									$credits = $iptc['2#080'][0];
    								}
    								if(!empty($iptc['2#025']) && is_array($iptc['2#025'])) {
    									$keywords = implode(', ', $iptc['2#025']);
    								}
    							}
    							if(empty($title)) $title = $file['name'];
    							
    							@unlink($datafolder.$file['name']);
    						}
    						$msg = "{success:true, filename: '".$file['name']."'}";
    					}
    					else {
    						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";
    					}
    				}
    				else if(in_array($ext, $videoFiles))
    				{
    					$datafolder = $datafolder . "article_photos/";
    					$file['name'] = preg_replace("/[^a-z \d\_\-.]/i", "", $file['name']);
    					$file['name'] = str_replace(" ", "_", $file['name']);
    					if (move_uploaded_file($file['tmp_name'],$datafolder.$file['name'])){
    						if(!empty($configOptions['video']['use_youtube']) && in_array($ext, $videoFiles)) {
    							$files[] = $file1;
    							$newFile = $datafolder.$file['name'];
    							try {
    								Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
    								$authenticationURL= 'https://www.google.com/accounts/ClientLogin';
    								$httpClient = Zend_Gdata_ClientLogin::getHttpClient(
    										$username = $configOptions['youtube']['email'],
    										$password = $configOptions['youtube']['password'],
    										$service = 'youtube',
    										$client = null,
    										$source = $configOptions['youtube']['source'],
    										$loginToken = null,
    										$loginCaptcha = null,
    										$authenticationURL);
    								$httpClient->setConfig(array( 'timeout' => 24*3600 ));
    								$developerKey = $configOptions['youtube']['youtube_api_key'];
    								$applicationId = $configOptions['youtube']['source'];
    								$clientId = $configOptions['youtube']['source'].' Upload';
    								Zend_Loader::loadClass('Zend_Gdata_YouTube');
    								$yt = new Zend_Gdata_YouTube($httpClient, $applicationId, $clientId, $developerKey);
    								$yt->setMajorProtocolVersion(2);
    								Zend_Loader::loadClass('Zend_Gdata_YouTube_VideoEntry');
    								$myVideoEntry = new Zend_Gdata_YouTube_VideoEntry();
    								$filesource = $yt->newMediaFileSource($newFile);
    								if(in_array($ext, array("avi","mpeg", "mpg", "m1v", "m4v")))
    									$filesource->setContentType('video/mpeg');
    								else if(in_array($ext, array("mp4")))
    									$filesource->setContentType('video/mp4');
    								else if(in_array($ext, array("ogg")))
    									$filesource->setContentType('video/ogg');
    								else if(in_array($ext, array("mov")))
    									$filesource->setContentType('video/quicktime');
    								else if(in_array($ext, array("webm")))
    									$filesource->setContentType('video/webm');
    								else if(in_array($ext, array("wmv")))
    									$filesource->setContentType('video/x-ms-wmv');
    								else if(in_array($ext, array("flv")))
    									$filesource->setContentType('video/x-flv');
    								else
    									$filesource->setContentType('video/mpeg');
    									
    								$filesource->setSlug($file['name']);
    								//$myVideoEntry->setVideoPrivate();
    								$myVideoEntry->setMediaSource($filesource);
    								$myVideoEntry->setVideoTitle($title);
    								$myVideoEntry->setVideoDescription($caption);
    								$myVideoEntry->setVideoCategory('News');
    								$myVideoEntry->SetVideoTags($keywords);
    								$myVideoEntry->setVideoDeveloperTags(array('cms',));
    								$yt->registerPackage('Zend_Gdata_Geo');
    								$yt->registerPackage('Zend_Gdata_Geo_Extension');
    	
    								Zend_Loader::loadClass('Zend_Gdata_App_Extension_Control');
    								Zend_Loader::loadClass('Zend_Gdata_YouTube_Extension_State');
    								$uploadUrl = 'http://uploads.gdata.youtube.com/feeds/api/users/default/uploads';
    								$msg = $video = "";
    								try {
    									$newEntry = $yt->insertEntry($myVideoEntry, $uploadUrl, 'Zend_Gdata_YouTube_VideoEntry');
    									$newEntry->setMajorProtocolVersion(2);
    									$videoId = $newEntry->getVideoId();
    								} catch (Zend_Gdata_App_HttpException $httpException) {
    									$msg = $httpException->getRawResponseBody();
    								} catch (Zend_Gdata_App_Exception $e) {
    									$msg = $e->getMessage();
    								} catch (Exception $ex) { $msg = $ex->getMessage(); }
    								if(!empty($msg)) {
    									$msg = "{success:false, errors: { reason: '". htmlentities($msg, ENT_QUOTES) ."' }}";
    								}
    								if(!empty($videoId)) {
    									$videoId = addslashes(stripslashes($videoId));
    									$this->session->uplimage = $videoId;
    								}
    							} catch (Exception $ex) {
    								$msg = "{success:false, errors: { reason: '". htmlentities($ex->getMessage(), ENT_QUOTES) ."' }}";
    							}
    							@unlink($datafolder.$file['name']);
    						}
    						if(empty($msg)) $msg = "{success:true, filename: '".$file['name']."'}";
    					}
    					else {
    						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";
    					}
    				}
    				
    				$data = array(
    					'site_id'						=> $this->site_id,
    					'source_system_id'				=> $fileName,
    					'title'							=> $title,
    					'caption'						=> $caption,
    					'keywords'						=> $keywords,
    					'credits'						=> $credits,
    					'create_date_time'				=> date('Y-m-d H:i:s')
    				);
    				$this->db->insert('content_images', $data);
    				$contentImageId = $this->db->lastInsertId();
    	
    				$data = array(
    					'site_id'				=> $this->site_id,
    					'content_gallery_id'	=> $contentGalleryId,
    					'content_images_id'		=> $contentImageId,
    					'sequence'				=> $sequence
    				);
    				$this->db->insert('content_gallery_images', $data);
    					
    				if(in_array($ext, $imageFiles)) $imgClass = 2;
    				else if(in_array($ext, $videoFiles)) $imgClass = 1;
    				else if(in_array($ext, $mp3Files)) $imgClass = 4;
    				else $imgClass = 3;
    					
    				if(in_array($ext, $imageFiles)) $imgType = 1;
    				else if(in_array($ext, $videoFiles)) $imgType = 2;
    				else if(in_array($ext, $mp3Files)) $imgType = 3;
    				else $imgType = 1;
    					
    				$externalData = array();
    				if($imgClass == 2 && !empty($configOptions['gallery']['use_smugmug'])) {
    					$externalData = $this->session->uplimage;
    					unset($this->session->uplimage);
    				}
    				else if($imgClass == 1 && !empty($configOptions['video']['use_youtube'])) {
    					$externalData['youtube_id'] =  $this->session->uplimage;
    					unset($this->session->uplimage);
    				}
    					
    				$imagesTable = new images(array('db' => 'db')); //use db object from registry
    	
    				$data = array(
    						'site_id'			=> $this->site_id,
    						'image_source_id'	=> '1',
    						'source_id'			=> $contentImageId,
    						'image_type_id'		=> $imgType,
    						'image_class_id'	=> $imgClass,
    						'image'      		=> ''
    				);
    				if(!empty($externalData['id'])) $data['smugmug_id'] = $externalData['id'];
    				if(!empty($externalData['Key'])) $data['smugmug_key'] = $externalData['Key'];
    				if(!empty($externalData['youtube_id'])) $data['youtube_id'] = $externalData['youtube_id'];
    				
    				$this->db->insert('images', $data);
    				$imageId = $this->db->lastInsertId();
    					
    				$contentGallery = $this->db->fetchRow("SELECT * FROM content_gallery WHERE content_gallery_id='{$contentGalleryId}'");
    				
    				if(empty($contentGallery['thumbnail_content_images_id'])) {
    					$this->db->query("UPDATE content_gallery SET image_count=image_count+1, thumbnail_content_images_id='{$contentImageId}' WHERE content_gallery_id='{$contentGalleryId}'");
    				}
    				else {
    					$this->db->query("UPDATE content_gallery SET image_count=image_count+1 WHERE content_gallery_id='{$contentGalleryId}'");
    				}
    			}
    		}
    		else if(!in_array($ext, $imageFiles)){
    			$msg = "{success:false, errors: { reason: 'Only jpg and png files can be uploaded.' }}";
    		}
    		else {
    			$msg = "{success:false, errors: { reason: 'Unidentified error.' }}";
    		}
    	}
    	echo json_encode($files);
    	exit();
    }
    
    function getcontentsectionareagroupAction()
    {
    	Zend_Loader::LoadClass('contentsectionareagroupClass', $this->modelDir);
    	$csag = new contentsectionareagroupClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $csag->getContentSectionAreaGroup($this->site_id, $params['area_id']);
		$response['success'] = true;    					
		$response['data'] = $data;

    	echo json_encode($response);
    }
    
    function getcontentsectionareagrouplistAction()
    {
    	Zend_Loader::LoadClass('contentsectionareagroupClass', $this->modelDir);
    	$csag = new contentsectionareagroupClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $csag->getContentSectionAreaGroup($this->site_id, $params['area_id']);
    	$data = array_merge(array(array("content_section_area_group_id"=>0,"title"=>"No Group")),$data);
		$response['success'] = true;    					
		$response['data'] = $data;

    	echo json_encode($response);
    }
    
    function setcontentsectionareagroupAction()
    {
		Zend_Loader::LoadClass('contentsectionareagroupClass', $this->modelDir);
    	$csag = new contentsectionareagroupClass();
    	
    	$params = $this->_getAllParams();
    	
    	if(empty($params['content_section_area_group_id']))
    		$csag->addContentSectionAreaGroup($this->site_id, $params);
    	else
    		$csag->updateContentSectionAreaGroup($params);
    	
    	//$this->cleanCache();
    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    }
    
    function deletecontentsectionareagroupAction()
    {
		Zend_Loader::LoadClass('contentsectionareagroupClass', $this->modelDir);
    	$csag = new contentsectionareagroupClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $content_section_area_group_id) {
	    		$csag->deleteContentSectionAreaGroup($content_section_area_group_id);
	    	}
	    	//$this->cleanCache();
	    	$this->cache->remove($this->environment."newsSections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."specialsections_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."submenu_cms_".$this->site_id);
	    	$this->cache->remove($this->environment."menu_cms_".$this->site_id);
    	}
    }
    
    function uploadpdfAction() {
		$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	foreach ($_FILES as $file){
    		if($file["type"] == "application/pdf"){
				if ($file["error"] > 0) {
					$msg = "File error.";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/pdf";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
		    			
		    		$fileName = $params['id'].'.pdf';
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
		    			$data = array("content_section_area_id"=>$params["id"], "pdf_file"=>$fileName);
						$csa->update($data);
						$msg = "pdf file has been succesfully uploaded";
					}	
					else {
						$msg = "Fail to upload file.";						
					}
				}
			}	
			else {
				$msg = "Only pdf files only can be uploaded.";
			}
    	}
    	
    	echo $msg;
	}
	
	function getpdfAction() {
		Zend_Loader::LoadClass('contentsectionareasClass', $this->modelDir);
    	$csa = new contentsectionareasClass();
    	$id = $this->_request->getParam("id");
    	$content_section_area = $csa->getContentSectionAreaById($id);
    	if(!empty($content_section_area["pdf_file"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $content_section_area["pdf_file"];
    		$data["filePath"] = trim($configs["general"]["url"],'/'). "/index/openpdf/pdf_file/".$content_section_area["pdf_file"];
    		echo '{"result":['. json_encode($data).']}';
    	}
	}
	
	function logstartAction() {
		$uniqueId = $this->_request->getParam("uniqueId");
		if(empty($this->session->uploadsess[$uniqueId])) {
			if(empty($this->session->uploadsess)) $this->session->uploadsess = array();
			$this->session->uploadsess[$uniqueId] = array(
				"start_time"		=> date("Y-m-d H:i:s"),
				"title"				=> $this->_request->getParam("title"),
				"files"				=> array()
			);
		}
	}
	
	function fileuploadfailAction() {
		$uniqueId = $this->_request->getParam("uniqueId");
		if(!empty($this->session->uploadsess[$uniqueId])) {
			$this->session->uploadsess[$uniqueId]['files'][] = array(
				"file_name"		=> $fileName,
				"file_url"		=> "",
				"added_time"	=> date("Y-m-d H:i:s"),
				"status"		=> "fail"
			);	
		}
	}
	
	function sendlogAction() {
		$uniqueId = $this->_request->getParam("uniqueId");
		
		if(!empty($this->session->uploadsess[$uniqueId])) {
			$this->session->uploadsess[$uniqueId]['end_time'] = date("Y-m-d H:i:s");
			
			$hostName = $_SERVER['SERVER_NAME'];
	        
	        $text =  "<h1>Video Upload Report for ".$hostName."</h3>";
	        
	        $text .=  "<h2>
	        Title: ".$this->session->uploadsess[$uniqueId]['title']." <br />
	        Start Uploading on: ".$this->session->uploadsess[$uniqueId]['start_time']." <br />
	        Finished Uploading on: ".$this->session->uploadsess[$uniqueId]['end_time']." <br />
	        </h2>";
	        
	    	$text .= '<table border="1" cellpadding="0" cellspacing="0"><tr style="background-color:#eeeeee; font-weight:bold"><td align="center" style="padding:3px 5px;">No</td><td align="center" style="padding:3px 5px;">File name</td><td align="center" style="padding:3px 5px;">Added Time</td><td align="center" style="padding:3px 5px;">Result</td></tr>';
	    	if(is_array($this->session->uploadsess[$uniqueId]['files'])) foreach ($this->session->uploadsess[$uniqueId]['files'] as $i=>$file)
	    	{
	    		$text .= '<tr><td align="center" style="padding:3px 5px;">'.($i+1).'</td><td align="center" style="padding:3px 5px;">'.$file['file_name'].'</td><td style="padding:3px 5px;">'.$file['added_time'].'</td><td style="padding:3px 5px;">'.$file['status'].'</td></tr>';
	    	}
	    	$text .= '</table>';
	        
	    	require_once('notificationClass.php');
			$notify = new notification($this->site_id);
			
	    	$notify->sendNotification(9,$text,$_SERVER['SERVER_NAME']);
	    	
	    	unset($this->session->uploadsess[$uniqueId]);
	    	
		}
	}
	
	 /*** CUSTOM PAGE ***/
    
    function custompageAction()
    {   	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(50, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
		$configs = parse_ini_file( $configFile, true );		
		$this->view->contactURL = rtrim($configs["general"]["url"], '/'). "/images/contact/";
		$this->view->bloggerURL = rtrim($configs["general"]["url"], '/'). "/images/blogger/";
		$this->view->imageUploaderURL = rtrim($configs["general"]["url"], '/'). "/images/image_uploader/";
		$this->view->slideshowURL = rtrim($configs["general"]["url"], '/'). "/images/custom_slideshow/";
		$this->view->bioURL = rtrim($configs["general"]["url"], '/'). "/images/custom_bio/";
		
        echo $this->view->render('header.php');
        echo $this->view->render('custom_page.php');
        echo $this->view->render('footer.php');
    }
    
    function getcustompageAction()
    {
    	Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cp = new custompageClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = false;
    	$data = $cp->getCustomPage($params, $this->site_id);
    	$response['total'] = $data['total'];
    	$arrData = $data['data'];
    	 		
		if ( sizeof($arrData) > 0 ) {
			$response['success'] = true;  			
			$response['data'] = $arrData;
		}
		else {
   			$response['data'] = '';
   		}
	
    	echo json_encode($response);
    }
    
    function getcustompagebyidAction()
    {
    	Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cp = new custompageClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $cp->getCustomPageById($params['custom_page_id']);
		
    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    }
    
    function addcustompageAction()
    {
		Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cp = new custompageClass();
    	
    	$params = $this->_getAllParams();
    		
    	$custom_page_id = $cp->addCustomPage($this->site_id, $params);
    	
    	$this->cache->remove($this->environment."custompage_cms_".$this->site_id);
    	
    	$data["success"]= true;
    	$data["custom_page_id"] = $custom_page_id;
    	echo json_encode($data);
    }
    
    function updatecustompageAction()
    {
    	Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cp = new custompageClass();
    	
    	$params = $this->_getAllParams();
    	
    	$cp->updateCustomPage($params);
    	
		$this->cache->remove($this->environment."custompage_cms_".$this->site_id);
		
		$data["success"]= true;
    	$data["custom_page_id"] = $params["custom_page_id"];
    	echo json_encode($data);
    }
    
    function deletecustompageAction()
    {
		Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cp = new custompageClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $custom_page_id) {
	    		$cp->deleteCustomPage($custom_page_id);
	    	}
	    	//$this->cleanCache();
	    	$this->cache->remove($this->environment."custompage_cms_".$this->site_id);
    	}
    }
    
    function uploadcustompagebgimageAction() {
		$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cpClass = new custompageClass();
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		if (in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash")) && $file["size"] <= $this->config->general->maxImageSize){
				if ($file["error"] > 0) {
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/images/custom_page_bg";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
					
					$origFileName = $file["name"];
		    		$temp = explode(".", $origFileName);
		    		$ext = $temp[count($temp)-1];
		    		$ext = strtolower($ext);
		    			
		    		$fileName = $params['id'].'.'.$ext;
		    		
		    		$old = $cpClass->getCustomPageById($params["id"]);
					if(!empty($old['bg_image'])) {
						if(file_exists($datafolder."/".$old["bg_image"]))
							unlink($datafolder."/".$old["bg_image"]);
					}
		    		
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
						$cpClass->update($this->site_id, array("custom_page_id"=>$params["id"], "bg_image"=>$fileName));
						$msg = "{success:true,filePath:''}";
					}	
					else {
						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";						
					}
				}
			}	
			else if(!in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash"))){
				$msg = "{success:false, errors: { reason: 'Only jpg, gif, and png files only can be uploaded.' }}";
			}
			else if($file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";
				
			}
    	}
    	
    	echo $msg;
	}
	
	function getcustompagebgimageAction() {
		Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cpClass = new custompageClass();
    	$id = $this->_request->getParam("id");
    	$cp = $cpClass->getCustomPageById($id);
    	if(!empty($cp["bg_image"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $cp["bg_image"];
    		$data["filePath"] = rtrim($configs["general"]["url"],'/'). "/images/custom_page_bg/".$cp["bg_image"];
    		echo '{"result":['. json_encode($data).']}';
    	}
    	else {
    		$data["fileName"] = '';
    		$data["filePath"] = '';
    		echo '{"result":['. json_encode($data).']}';
    	}
	}
	
	function migratecustompageAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(52, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('custompage_migrate.php');
        echo $this->view->render('footer.php');
    }
    
    function getcustompageliveAction()
    {
    	if(!empty($this->site_id) || $this->site_id>0)
    	{
	    	Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    		$cpClass = new custompageClass();
	    	
	    	$params = $this->_getAllParams();
	    	
	    	set_time_limit(1800);
	    	
	    	$response['success'] = true;
	    	$response['data'] = array();
	    	$rs = $cpClass->getCustomPage($params, $this->site_id, true);
	 
	    	$arrRows = $rs['data'];
	    	if ( is_array($arrRows) ) {
	    		$response['success'] = true;
	    
	    		if ( sizeof($arrRows) > 0 ) {
	    			$response['data'] = $arrRows;
	    		}
	   		}
	    	echo json_encode($response);
    	}
    }
    
    function docustompagemigrateAction()
    {
    	Zend_Loader::LoadClass('custompageClass', $this->modelDir);
    	$cpClass = new custompageClass();
    	
    	$params = $this->_getAllParams();
    	$readOnly = FALSE;
    	if($this->ident["role"] != "apt") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(52, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
    	if (!$readOnly && $this->site_id > 0 ) {
    		$dataKeys = json_decode($params['data'], true);
			$ids="";
			foreach ($dataKeys as $id)
				$ids = $ids.$id['custom_page_id'].",";
			$params['ids'] = substr($ids, 0, -1);
    		$cp = $cpClass->getCustomPage($params, $this->site_id);
    		$arrCustomPages = $cp['data'];
	    		
    		if ( $params['logic'] == 'migrate' ) {
    			$retVal = $cpClass->migrateCustomPages($this->site_id, $arrCustomPages, $params);  
    			
    			Zend_Loader::LoadClass('contactClass', $this->modelDir);
    			$contactClass = new contactClass();
    			Zend_Loader::LoadClass('imageuploaderClass', $this->modelDir);
    			$imageuploaderClass = new imageuploaderClass();
    			Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    			$earlcampbellClass = new earlcampbellClass();
    			Zend_Loader::LoadClass('bloggerClass', $this->modelDir);
    			$bloggerClass = new bloggerClass();
    			foreach ($arrCustomPages as $custompage)
				{
					if($custompage['page_type'] == 0 || empty($custompage['page_type'])) //contact
					{
						$contactClass->deleteContactProd($custompage['custom_page_id']);
						$contact = $contactClass->getContactByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $contact as $c )
						{						
							$contactClass->addContactProd($this->site_id, $c);
							if(!empty($c['picture']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/contact/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/contact/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($c['picture']))
									copy($srcDir.$c['picture'], $destDir.$c['picture']);
							}
						}
					}
					elseif($custompage['page_type'] == 1) //image uploader
					{
						$imageuploaderClass->deleteImageUploaderProd($custompage['custom_page_id']);
						$ImageUploader = $imageuploaderClass->getImageUploaderByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$imageuploaderClass->addImageUploaderProd($this->site_id, $iu);
							if(!empty($iu['image']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/image_uploader/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/image_uploader/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['image']))
									copy($srcDir.$iu['image'], $destDir.$iu['image']);
							}
						}
					}
					elseif($custompage['page_type'] == 2) //earl campbell
					{
						$earlcampbellClass->deleteCustomSlideshowProd($custompage['custom_page_id']);
						$ImageUploader = $earlcampbellClass->getCustomSlideshowByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$earlcampbellClass->addCustomSlideshowProd($this->site_id, $iu);
							if(!empty($iu['image_name']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/custom_slideshow/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/custom_slideshow/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['image_name']))
									copy($srcDir.$iu['image_name'], $destDir.$iu['image_name']);
							}
						}
						
						$earlcampbellClass->deleteCustomBioSlideshowProd($custompage['custom_page_id']);
						$ImageUploader = $earlcampbellClass->getCustomBioSlideshowByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$earlcampbellClass->addCustomBioSlideshowProd($this->site_id, $iu);
							if(!empty($iu['image_name']) )
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/custom_bio/".$iu['custom_bio_id']."/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/custom_bio/";
								if(!is_dir($destDir)) mkdir($destDir);
								$destDir .= $iu['custom_bio_id']."/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['image_name']))
									copy($srcDir.$iu['image_name'], $destDir.$iu['image_name']);
							}
						}
						
						$earlcampbellClass->deleteCustomBioProd($custompage['custom_page_id']);
						$ImageUploader = $earlcampbellClass->getCustomBioByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$earlcampbellClass->addCustomBioProd($this->site_id, $iu);
							if(!empty($iu['student_photo']) || !empty($iu['school_logo']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/custom_bio/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/custom_bio/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['student_photo']))
									copy($srcDir.$iu['student_photo'], $destDir.$iu['student_photo']);
								if(!empty($iu['school_logo']))
									copy($srcDir.$iu['school_logo'], $destDir.$iu['school_logo']);
							}
						}
					}
					else if($custompage['page_type'] == 3) //blogger
					{
						$bloggerClass->deleteBloggerProd($custompage['custom_page_id']);
						$bloggers = $bloggerClass->getBloggerByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $bloggers as $c )
						{						
							$bloggerClass->addBloggerProd($this->site_id, $c);
							if(!empty($c['picture']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/blogger/";
								$destDir = str_replace('/dev/','/prod/', $this->config->paths->sitepath)."/".$this->session->site['name']."/html/images/blogger/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($c['picture']))
									copy($srcDir.$c['picture'], $destDir.$c['picture']);
							}
						}
					}
				}  			
    		}
    		elseif ( $params['logic'] == 'copy' ) {    			
    			$retVal = $cpClass->migrateCopyCustomPages($this->site_id, $arrCustomPages, $params);

    			Zend_Loader::LoadClass('siteClass', $this->modelDir);
    			$siteClass = new siteClass();
    			$copySite = $siteClass->getSite($params['copySiteid']);
    			Zend_Loader::LoadClass('contactClass', $this->modelDir);
    			$contactClass = new contactClass();
    			Zend_Loader::LoadClass('imageuploaderClass', $this->modelDir);
    			$imageuploaderClass = new imageuploaderClass();
    			Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    			$earlcampbellClass = new earlcampbellClass();
    			Zend_Loader::LoadClass('bloggerClass', $this->modelDir);
    			$bloggerClass = new bloggerClass();
    			foreach ($arrCustomPages as $custompage)
				{
					if($custompage['page_type'] == 0 || empty($custompage['page_type'])) //contact
					{
						$contact = $contactClass->getContactByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $contact as $c )
						{						
							$contactClass->addContact($params['copySiteid'], $c);
							if(!empty($c['picture']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/contact/";
								$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/images/contact/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($c['picture']))
									copy($srcDir.$c['picture'], $destDir.$c['picture']);
							}
						}
					}
					elseif($custompage['page_type'] == 1) //image uploader
					{
						$ImageUploader = $imageuploaderClass->getImageUploaderByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$imageuploaderClass->addImageUploader($params['copySiteid'], $iu);
							if(!empty($iu['image']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/image_uploader/";
								$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/images/image_uploader/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['image']))
									copy($srcDir.$iu['image'], $destDir.$iu['image']);
							}
						}
					} 
					elseif($custompage['page_type'] == 2) //earl campbell
					{
						$ImageUploader = $earlcampbellClass->getCustomSlideshowByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$earlcampbellClass->addCustomSlideshow($params['copySiteid'], $iu);
							if(!empty($iu['image_name']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/custom_slideshow/";
								$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/images/custom_slideshow/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['image_name']))
									copy($srcDir.$iu['image_name'], $destDir.$iu['image_name']);
							}
						}
						$ImageUploader = $earlcampbellClass->getCustomBioByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $ImageUploader as $iu )
						{						
							$earlcampbellClass->addCustomBio($params['copySiteid'], $iu);
							if(!empty($iu['student_photo']) || !empty($iu['school_logo']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/custom_bio/";
								$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/images/custom_bio/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($iu['student_photo']))
									copy($srcDir.$iu['student_photo'], $destDir.$iu['student_photo']);
								if(!empty($iu['school_logo']))
									copy($srcDir.$iu['school_logo'], $destDir.$iu['school_logo']);
							}
						}
					}
					elseif($custompage['page_type'] == 3) //bloggers
					{
						$bloggers = $bloggerClass->getBloggerByCustomPageId($this->site_id, $custompage['custom_page_id']); 
						foreach ( $bloggers as $c )
						{						
							$bloggerClass->addBlogger($params['copySiteid'], $c);
							if(!empty($c['picture']))
							{
								$srcDir = $this->config->paths->sitepath."/".$this->session->site['name']."/html/images/blogger/";
								$destDir = $this->config->paths->sitepath."/".$copySite['name']."/html/images/blogger/";
								if(!is_dir($destDir)) mkdir($destDir);
								if(!empty($c['picture']))
									copy($srcDir.$c['picture'], $destDir.$c['picture']);
							}
						}
					} 
				}     
    		}
    	}
    	return self::getcustompageliveAction();
    }
    
    
    /*Earl Campbell Awards Custom Slideshows*/
    
    function getcustomslideshowsAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	$params = $this->_getAllParams();
    	
    	/*if(empty($params["limit"])) $params["limit"] = 15;
    	if(empty($params["start"])) $params["start"] = 0;*/

    	$data = $earlcampbellClass->getSlideshows($this->site_id, $params);
    	
    	$response['data'] = $data;
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function addcustomslideshowAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	$slideshowID = $earlcampbellClass->addCustomSlideshow($this->site_id,$params);
    	
    	//$this->cleanCache();    	
    	
    	$data["success"]= true;
    	$data["custom_slideshow_id"] = $slideshowID;
    	echo json_encode($data);
    }
    
    function updatecustomslideshowAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	
    	$data = array(
    		'custom_slideshow_id'	=> $params['custom_slideshow_id'],
			'site_id'			=> $this->site_id,
			'slide_url'			=> $params['slide_url'],
			'slide_target'		=> $params['slide_target'],
			'custom_tag'		=> $params['custom_tag'],
			'order_id'			=> $params['order_id'],
			'enabled'			=> intval($params['enabled']),
			'modified_date'		=> date("Y-m-d H:i:s"),
			'modified_by'		=> $this->ident['adminuserid'],
		);
    	
    	$customSlideshowID = $earlcampbellClass->update($this->site_id, $data);
    	
    	//$this->cleanCache(); 
    	
    	$data["success"]= true;
    	$data["custom_slideshow_id"] = $customSlideshowID;
    	echo json_encode($data);
    }
    
    function uploadslideshowimageAction() {
    	$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		if (in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash")) && $file["size"] <= $this->config->general->maxImageSize){
				if ($file["error"] > 0) {
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/images/custom_slideshow";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
					
					$origFileName = $file["name"];
		    		$temp = explode(".", $origFileName);
		    		$ext = $temp[count($temp)-1];
		    		$ext = strtolower($ext);
		    			
		    		$fileName = $params['id'].'.'.$ext;
		    		
		    		$old = $earlcampbellClass->getSlideshowById($params["id"]);
					if(!empty($old['image_name'])) {
						if(file_exists($datafolder."/".$old["image_name"]))
							unlink($datafolder."/".$old["image_name"]);
					}
		    		
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
						$earlcampbellClass->update($this->site_id, array("custom_slideshow_id"=>$params["id"], "image_name"=>$fileName));
						$msg = "{success:true,filePath:''}";
					}	
					else {
						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";						
					}
				}
			}	
			else if(!in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash"))){
				$msg = "{success:false, errors: { reason: 'Only jpg, gif, and png files only can be uploaded.' }}";
			}
			else if($file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";
				
			}
    	}
    	
    	echo $msg;
    }
    
    function getcustomslideshowbyidAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
		$response['success'] = true;
    	$response['data'] = $earlcampbellClass->getSlideshowById($this->_request->getParam("custom_slideshow_id"));
    	echo json_encode($response);
    }
    
    function getslideshowimageAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$id = $this->_request->getParam("id");
    	$imageUploader = $earlcampbellClass->getSlideshowById($id);
    	if(!empty($imageUploader["image_name"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $imageUploader["image_name"];
    		$data["filePath"] = rtrim($configs["general"]["url"],'/'). "/images/custom_slideshow/".$imageUploader["image_name"];
    		echo '{"result":['. json_encode($data).']}';
    	}
    }
    
    function deletecustomslideshowAction() {
    	$ids = $this->_request->getParam("datas");
    	$ids = explode(",", $ids);
    	
		$datafolder = $this->config->paths->sitepath."/".$this->session->site["name"]."/html/images/custom_slideshow";
    	
    	if(!empty($ids) && is_array($ids)) {
    		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    		$earlcampbellClass = new earlcampbellClass();
    		foreach ($ids as $id) {
    			$slideshow = $earlcampbellClass->getSlideshowById($id);
    			if(!empty($slideshow['image_name'])) {
					if(file_exists($datafolder."/".$slideshow["image_name"]))
						@unlink($datafolder."/".$slideshow["image_name"]);
				}
    			$earlcampbellClass->deleteSlideshow($this->site_id, $id);
    		}
    		//$this->cleanCache();
    	}
    	$data["success"] = true;
    	echo json_encode($data);
    }
    
    function updatecustomslidesortorderAction() {
    	$datas = $this->_request->getParam("datas");
    	$records = explode(",", $datas);
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	if(is_array($records)) foreach ($records as $record) {
    		$data = explode("=", $record);
    		if(count($data) == 2) {
    			$id = intval($data[0]);
    			$sortOrder = intval($data[1]);
    			if(!empty($id) && !empty($sortOrder)) {
    				$earlcampbellClass->update($this->site_id, array("order_id"=>$sortOrder, "custom_slideshow_id"=>$id));
    			}
    		}
    	}
    }
    
    /*end of Earl Campbell Awards Custom Slideshows*/
    
    /*Earl Campbell Student Bios*/
    function getcustombiosAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	$params = $this->_getAllParams();
    	
    	/*if(empty($params["limit"])) $params["limit"] = 15;
    	if(empty($params["start"])) $params["start"] = 0;*/

    	$data = $earlcampbellClass->getBios($this->site_id, $params);
    	
    	$response['data'] = $data;
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function addcustombioAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	$bioID = $earlcampbellClass->addCustomBio($this->site_id,$params);
    	
    	//$this->cleanCache();    	
    	
    	$data["success"]= true;
    	$data["custom_bio_id"] = $bioID;
    	echo json_encode($data);
    }
    
    function updatecustombioAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	
    	$data = array(
    		'custom_bio_id'	=> $params['custom_bio_id'],
			'site_id'			=> $this->site_id,
			'student_name'		=> $params['student_name'],
			'student_year'		=> substr($params['student_year'], 0, 20),
			'school_name'		=> $params['school_name'],
			'student_status'	=> trim($params['student_status']),
			'student_position'	=> $params['student_position'],
			'order_id'			=> $params['order_id'],
			'student_bio'		=> $params['student_bio'],
			'modified_date'		=> date("Y-m-d H:i:s"),
			'modified_by'		=> $this->ident['adminuserid'],
		);
    	
    	$customBioID = $earlcampbellClass->updateBio($this->site_id, $data);
    	
    	//$this->cleanCache(); 
    	
    	$data["success"]= true;
    	$data["custom_bio_id"] = $customBioID;
    	echo json_encode($data);
    }
    
    function uploadbioimageAction() {
    	$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		if (in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash")) && $file["size"] <= $this->config->general->maxImageSize){
				if ($file["error"] > 0) {
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/images/custom_bio";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
					
					$origFileName = $file["name"];
		    		$temp = explode(".", $origFileName);
		    		$ext = $temp[count($temp)-1];
		    		$ext = strtolower($ext);
		    			
		    		$fileName = $params['id'].'-student.'.$ext;
		    		
		    		$old = $earlcampbellClass->getBioById($params["id"]);
					if(!empty($old['student_photo'])) {
						if(file_exists($datafolder."/".$old["student_photo"]))
							unlink($datafolder."/".$old["student_photo"]);
					}
		    		
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
						$earlcampbellClass->updateBio($this->site_id, array("custom_bio_id"=>$params["id"], "student_photo"=>$fileName));
						$msg = "{success:true,filePath:''}";
					}	
					else {
						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";						
					}
				}
			}	
			else if(!in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash"))){
				$msg = "{success:false, errors: { reason: 'Only jpg, gif, and png files only can be uploaded.' }}";
			}
			else if($file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";
				
			}
    	}
    	
    	echo $msg;
    }
    
    function uploadbiosimageAction() {
    	$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		if (in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash")) && $file["size"] <= $this->config->general->maxImageSize){
				if ($file["error"] > 0) {
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/images/custom_bio";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
					
					$origFileName = $file["name"];
		    		$temp = explode(".", $origFileName);
		    		$ext = $temp[count($temp)-1];
		    		$ext = strtolower($ext);
		    			
		    		$fileName = $params['id'].'-school.'.$ext;
		    		
		    		$old = $earlcampbellClass->getBioById($params["id"]);
					if(!empty($old['school_logo'])) {
						if(file_exists($datafolder."/".$old["school_logo"]))
							unlink($datafolder."/".$old["school_logo"]);
					}
		    		
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
						$earlcampbellClass->updateBio($this->site_id, array("custom_bio_id"=>$params["id"], "school_logo"=>$fileName));
						$msg = "{success:true,filePath:''}";
					}	
					else {
						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";						
					}
				}
			}	
			else if(!in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash"))){
				$msg = "{success:false, errors: { reason: 'Only jpg, gif, and png files only can be uploaded.' }}";
			}
			else if($file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";
				
			}
    	}
    	
    	echo $msg;
    }
    
    function getcustombiobyidAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
		$response['success'] = true;
    	$response['data'] = $earlcampbellClass->getBioById($this->_request->getParam("custom_bio_id"));
    	echo json_encode($response);
    }
    
    function getbioimageAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$id = $this->_request->getParam("id");
    	$imageUploader = $earlcampbellClass->getBioById($id);
    	if(!empty($imageUploader["student_photo"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $imageUploader["student_photo"];
    		$data["filePath"] = rtrim($configs["general"]["url"],'/'). "/images/custom_bio/".$imageUploader["student_photo"];
    		echo '{"result":['. json_encode($data).']}';
    	}
    }
    
    function getbiosimageAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$id = $this->_request->getParam("id");
    	$imageUploader = $earlcampbellClass->getBioById($id);
    	if(!empty($imageUploader["school_logo"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $imageUploader["school_logo"];
    		$data["filePath"] = rtrim($configs["general"]["url"],'/'). "/images/custom_bio/".$imageUploader["school_logo"];
    		echo '{"result":['. json_encode($data).']}';
    	}
    }
    
    function deletecustombioAction() {
    	$ids = $this->_request->getParam("datas");
    	$ids = explode(",", $ids);
    	
		$datafolder = $this->config->paths->sitepath."/".$this->session->site["name"]."/html/images/custom_bio";
    	
    	if(!empty($ids) && is_array($ids)) {
    		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    		$earlcampbellClass = new earlcampbellClass();
    		foreach ($ids as $id) {
    			$bio = $earlcampbellClass->getBioById($id);
    			if(!empty($bio['student_photo'])) {
					if(file_exists($datafolder."/".$bio["student_photo"]))
						@unlink($datafolder."/".$bio["student_photo"]);
				}
				if(!empty($bio['school_logo'])) {
					if(file_exists($datafolder."/".$bio["school_logo"]))
						@unlink($datafolder."/".$bio["school_logo"]);
				}
    			$earlcampbellClass->deleteBio($this->site_id, $id);
    		}
    		//$this->cleanCache();
    	}
    	$data["success"] = true;
    	echo json_encode($data);
    }
    
    function updatebiosortorderAction() {
    	$datas = $this->_request->getParam("datas");
    	$records = explode(",", $datas);
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	if(is_array($records)) foreach ($records as $record) {
    		$data = explode("=", $record);
    		if(count($data) == 2) {
    			$id = intval($data[0]);
    			$sortOrder = intval($data[1]);
    			if(!empty($id) && !empty($sortOrder)) {
    				$earlcampbellClass->updateBio($this->site_id, array("order_id"=>$sortOrder, "custom_bio_id"=>$id));
    			}
    		}
    	}
    }
    
    /*end of Earl Campbell Student Bios*/
    
    /*start of custom bio slideshows*/
    function getcustombioslideshowsAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	$params = $this->_getAllParams();
    	
    	/*if(empty($params["limit"])) $params["limit"] = 15;
    	if(empty($params["start"])) $params["start"] = 0;*/

    	$data = $earlcampbellClass->getBioSlideshows($this->site_id, $params);
    	
    	$response['data'] = $data;
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    function addcustombioslideshowAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	$slideshowID = $earlcampbellClass->addCustomBioSlideshow($this->site_id,$params);
    	
    	//$this->cleanCache();    	
    	
    	$data["success"]= true;
    	$data["custom_bio_slideshow_id"] = $slideshowID;
    	echo json_encode($data);
    }
    
    function updatecustombioslideshowAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$params = $this->_getAllParams();
    	
    	$data = array(
    		'custom_bio_slideshow_id'	=> $params['custom_bio_slideshow_id'],
			'site_id'			=> $this->site_id,
			'slide_url'			=> $params['slide_url'],
			'slide_target'		=> $params['slide_target'],
			'custom_tag'		=> $params['custom_tag'],
			'order_id'			=> $params['order_id'],
			'enabled'			=> intval($params['enabled']),
			'modified_date'		=> date("Y-m-d H:i:s"),
			'modified_by'		=> $this->ident['adminuserid'],
		);
    	
    	$customSlideshowID = $earlcampbellClass->updateBioSlideshow($this->site_id, $data);
    	
    	//$this->cleanCache(); 
    	
    	$data["success"]= true;
    	$data["custom_bio_slideshow_id"] = $customSlideshowID;
    	echo json_encode($data);
    }
    
    function uploadbioslideshowimageAction() {
    	$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass' , $this->modelDir);
		$site = new siteClass();
		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	
    	$customBioSlideshow = $earlcampbellClass->getBioSlideshowById($params['id']);
    	
    	foreach ($_FILES as $key=>$val){
    		$file = $_FILES[$key];
    		if (in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash")) && $file["size"] <= $this->config->general->maxImageSize){
				if ($file["error"] > 0) {
					$msg = "{success:false, errors: { reason: 'File error.' }}";
				}
				else {					
					$arr_site = $site->getSite($this->site_id);
					$name = $arr_site['name'];
					$baseDir = dirname(dirname(dirname(dirname(__FILE__))));
					$baseDir = str_replace("\\", "/", $baseDir);
					$baseDir = rtrim($baseDir, "/");
					$baseDir = $baseDir."/sites/{$name}/html/images/custom_bio";
					if(!is_dir($baseDir)) mkdir($baseDir);
					$baseDir .= "/".$customBioSlideshow['custom_bio_id'];
					if(!is_dir($baseDir)) mkdir($baseDir);
					$datafolder = $baseDir;
					
					$origFileName = $file["name"];
		    		$temp = explode(".", $origFileName);
		    		$ext = $temp[count($temp)-1];
		    		$ext = strtolower($ext);
		    			
		    		$fileName = $params['id'].'.'.$ext;
		    		
					if(!empty($customBioSlideshow['image_name'])) {
						if(file_exists($datafolder."/".$customBioSlideshow["image_name"]))
							unlink($datafolder."/".$customBioSlideshow["image_name"]);
					}
		    		
		    		if (move_uploaded_file($file["tmp_name"],$datafolder."/".$fileName)){
						$earlcampbellClass->updateBioSlideshow($this->site_id, array("custom_bio_slideshow_id"=>$params["id"], "image_name"=>$fileName));
						$msg = "{success:true,filePath:''}";
					}	
					else {
						$msg = "{success:false, errors: { reason: 'Fail to upload file.' }}";						
					}
				}
			}	
			else if(!in_array($file["type"], array("image/gif","image/jpeg","image/png","image/pjpeg","image/x-png","image/bmp","application/x-shockwave-flash"))){
				$msg = "{success:false, errors: { reason: 'Only jpg, gif, and png files only can be uploaded.' }}";
			}
			else if($file["size"] > $this->config->general->maxImageSize){
				$msg = "{success:false, errors: { reason: 'Image Size can not be greater than ".$this->config->general->maxImageSize." bytes' }}";
				
			}
    	}
    	
    	echo $msg;
    }
    
    function getcustombioslideshowbyidAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
		$response['success'] = true;
    	$response['data'] = $earlcampbellClass->getBioSlideshowById($this->_request->getParam("custom_bio_slideshow_id"));
    	echo json_encode($response);
    }
    
    function getbioslideshowimageAction() {
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	$id = $this->_request->getParam("id");
    	$imageUploader = $earlcampbellClass->getBioSlideshowById($id);
    	if(!empty($imageUploader["image_name"])) {
    		
    		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
			$configs = parse_ini_file( $configFile, true );
					
    		
    		$data["fileName"] = $imageUploader["image_name"];
    		$data["filePath"] = rtrim($configs["general"]["url"],'/'). "/images/custom_bio/".$id."/".$imageUploader["image_name"];
    		echo '{"result":['. json_encode($data).']}';
    	}
    }
    
    function deletecustombioslideshowAction() {
    	$ids = $this->_request->getParam("datas");
    	$ids = explode(",", $ids);
    	
		$datafolder = $this->config->paths->sitepath."/".$this->session->site["name"]."/html/images/custom_bio";
    	
    	if(!empty($ids) && is_array($ids)) {
    		Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    		$earlcampbellClass = new earlcampbellClass();
    		foreach ($ids as $id) {
    			$slideshow = $earlcampbellClass->getBioSlideshowById($id);
    			if(!empty($slideshow['image_name'])) {
					if(file_exists($datafolder."/".$slideshow['custom_bio_id']."/".$slideshow["image_name"]))
						@unlink($datafolder."/".$slideshow['custom_bio_id']."/".$slideshow["image_name"]);
				}
    			$earlcampbellClass->deleteBioSlideshow($this->site_id, $id);
    		}
    		//$this->cleanCache();
    	}
    	$data["success"] = true;
    	echo json_encode($data);
    }
    
    function updatecustombioslidesortorderAction() {
    	$datas = $this->_request->getParam("datas");
    	$records = explode(",", $datas);
    	Zend_Loader::LoadClass('earlcampbellClass', $this->modelDir);
    	$earlcampbellClass = new earlcampbellClass();
    	if(is_array($records)) foreach ($records as $record) {
    		$data = explode("=", $record);
    		if(count($data) == 2) {
    			$id = intval($data[0]);
    			$sortOrder = intval($data[1]);
    			if(!empty($id) && !empty($sortOrder)) {
    				$earlcampbellClass->updateBioSlideshow($this->site_id, array("order_id"=>$sortOrder, "custom_bio_slideshow_id"=>$id));
    			}
    		}
    	}
    }
    
    /*end of custom bio slideshow*/
    
}
?>
