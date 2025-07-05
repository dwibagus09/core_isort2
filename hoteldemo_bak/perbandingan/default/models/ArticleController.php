<?php
require_once('actionControllerBase.php');

class ArticleController extends actionControllerBase
{
	
	public function indexAction() {
		$cac = $this->loadModel('contentarticle');
		$params = $this->_getAllParams();
    	$params["start"]  = intval($params["start"]);
    	
		if(!($articles = $this->cache->load($this->environment."csa_articles_index_".$params["start"]."_cms_".$this->siteid))) {
			$articles= $cac->getAllArticles($this->siteid, array('start'=>$params["start"], 'pagesize'=>$this->config->general->page_size));
			if( (empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1) && empty($params['start']) ) 
				$this->cache->save($articles, $this->environment."csa_articles_index_".$params["start"]."_cms_".$this->siteid, array("articles_cms_".$this->siteid), ARTICLE_CACHE);
		}
    	$this->view->articles = $articles;
	    $this->view->pagesize = $this->config->general->page_size;
	    
	    if($this->siteid==9)
	    	$this->view->title = "Stories";
	    else
	    	$this->view->title = "News";
	    	
	    $this->view->pagingData = $this->generatePagingData($this->baseUrl."/article/index", $articles["start"], $articles['limit'], 10, $articles["count"]);
	    $this->view->pageHelper = $this->view->render("paging.tpl");
			
	    $this->view->apVideo = $this->view->render("apvideo.tpl");
	    	
	    $contentView = $this->_request->getParam("contentview");
	    if($contentView=="mobile") {
	    	$output = $this->view->render("m_section.tpl");
	    	$output = $this->reformatQuery($output);
	    	echo $output;
	    } else
	    	$this->renderTemplate('section.tpl');
	}
	
	public function viewAction() {		
		$articleId = $this->_request->getParam("article_id");
		
		if(!is_numeric($articleId)) {
			$this->_response->setRedirect($this->config->general->url);
			$this->_response->sendResponse();
			exit();
		}
		
		$this->session->httpReferer = $_SERVER['REQUEST_URI'];
		
		$this->createCaptcha();
		
		$smugmugURL = "";
    	if(!empty($this->config->gallery->use_smugmug)) {
    		if(!empty($this->config->smugmug->url)) $smugmugURL = $this->config->smugmug->url;
    		else {
	    		$libPath = dirname(dirname(dirname(dirname(__FILE__))));
		    	$libPath = str_replace("\\", "/", $libPath);
		    	$libPath = rtrim($libPath, '/');
		    	$libPath .= '/lib';
		    	require_once($libPath."/phpSmug.php");
		    	$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
		    	try {			
					$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );
					$smugmugURL = $f->parsed_response['Login']['User']['URL'];
		    	} catch (Exception $ex) {}
    		}
    	}
		
		if(!is_numeric($articleId))
		{
			$this->render404();
			throw new Falcon_Content_Exception('Invalid parameter in viewAction',EXCEPTION_INVALID_PARAMETERS);
		}
		
		$contentArticle = $this->loadModel('contentarticle');
		if(!($article = $this->cache->load($this->environment."article_".$articleId."_cms_".$this->siteid))) {
			$article  = $contentArticle->getArticle($this->siteid, $articleId);
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($article, $this->environment."article_".$articleId."_cms_".$this->siteid, array("article_cms_".$this->siteid));
			
		}
		
		if(in_array($this->siteid, array(13, 14, 15))) {
			$this->view->articleMenus = $contentArticle->getMenuContainThisArticle($this->siteid, $article);
		}
		
		/*$article['article'] = mb_convert_encoding($article['article'], 'UTF-8', 'Windows-1252');*/
		$encoding = mb_detect_encoding($article['article']);
		if(empty($encoding)) header("Content-Type:text/html; charset=iso-8859-1");
		
		//$article['article']	= strip_tags($article['article'], "<p><a><img><b><i><u><strong><h1><h2><h3><h4><h5><hr><h6><ul><ol><li><font><br><sup><sub><strike><table><tr><td><th><div><object><param><embed><span><iframe><em>");
		
		//$articleText = preg_replace('#\s(id|class)="[^"]+"#', '', $article['article']);
		$articleText = $article['article'];
		$articleText = str_replace(array("\n", "\r"), array(' ', ' '), $articleText);
		$articleText = preg_replace('!<p>(\s)+<p>!is', '<apxh:p>', $articleText);
      	$articleText = preg_replace('!<\/p>(\s)+<\/p>!is', '</apxh:p>', $articleText);
      	$articleText = str_replace('<p></p>', '', $articleText);
		$articleText = str_replace("\t", '&nbsp;&nbsp;&nbsp;&nbsp;', $articleText);
		$article['article'] = $articleText;
		
		$in=array(
			/*'`((?:https?|ftp)://\S+[[:alnum:]]/?)`si',*/
			'` ((?:https?|ftp)://\S+[[:alnum:]]/?) `si',
			'`((?<!//)(?<!%2F%2F)(www\.\S+[[:alnum:]]/?))`si',
			'/(((\w+)|(\w+)\.(\w+))\@(\w+)\.(\w+)\.(\w+){2,3})\s/',
			'/((\w+)\@(\w+)\.(\w+)\.(\w+){2,3})\s/',
			'/(((\w+)|(\w+)\.(\w+))\@(\w+)\.(\w+){2,3})\s/',
			'/((\w+)\@(\w+)\.(\w+){2,3})\s/',
		);
		$out=array(
			' <a href="$1" target="_blank" rel="nofollow">$1</a> ',
			' <a href="http://$1" target="_blank" rel="nofollow">$1</a> ',
			' <a href="mailto:$1" target="_blank" rel="nofollow">$1</a> ',
			' <a href="mailto:$1" target="_blank" rel="nofollow">$1</a> ',
			' <a href="mailto:$1" target="_blank" rel="nofollow">$1</a> ',
			' <a href="mailto:$1" target="_blank" rel="nofollow">$1</a> ',
		);
		if(!strpos($article['article'], "<a")) {
			$article['article'] = str_replace("<", " <", $article['article']);
			$article['article'] = preg_replace($in, $out, $article['article']);
			//$article['article'] = str_replace(" <", "<", $article['article']);
		}
		
		$story = explode("</p>", $article['article']);	
		$i = 1;
		$art = '';
		$bannerClass = $this->loadModel("banner");
		
		if(!($rbanners = $this->cache->load($this->environment."article_banner_".$article['section_id']."_cms_".$this->siteid))) {
			$rbanners = $bannerClass->getArticleBanners($this->siteid, 0, $article['section_id']);
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($rbanners, $this->environment."article_banner_".$article['section_id']."_cms_".$this->siteid, array("article_cms_".$this->siteid), BANNER_CACHE);
		}
		
		$this->view->rbanners =  $rbanners;

		$firstParagraph = '';
		
		foreach ($story as $stor) { 
			$art .= $stor."</p>";
			
			if(empty($firstParagraph) || strlen($firstParagraph) < 60) {
				$temp = strip_tags($stor);
				$temp = str_replace('&nbsp;', ' ', $temp);
				$temp = trim($temp);
				$firstParagraph .= '<p>'.$temp.'</p>';
			}
			
			$banners = array();
			if(!empty($rbanners[$i])) $banners = $rbanners[$i];
			
			if(!empty($banners))
			{
				$banner = $banners;
				//foreach ($banners as $banner)
				{		
					/*if($this->siteid==11)			
						$art .= '<div style="float:left; margin-right:15px;clear:left; margin-bottom:12px;">';
					else 
						$art .= '<p style="float:left; margin-right:15px;clear:left;">';*/
					$art .= '<div class="within-article-text-banner" style="float:left; margin-right:15px;clear:left; margin-bottom:12px;">';
					if(!empty($banner["tag"]))	{
						$art .= str_replace("INSERT_RANDOM_NUMBER_HERE", rand(10000, 99999), $banner["tag"]);
					}
					else {
						if(!empty($banner["banner_url"])) { 
							$art .= '<a rel="nofollow" href="'.$banner["banner_url"].'" title="">';
						}
						
						$fileName = explode(".", $banner["banner_image"]);
						$ext = $fileName[count($fileName)-1];
						$ext = strtolower($ext);
						if($ext == "swf") {
							$art .= '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="250" id="FlashID" title="">
							  <param name="movie" value="/images/banners/'.$banner["banner_image"].'" />
							  <param name="quality" value="high" />
							  <param name="wmode" value="opaque" />
							  <param name="swfversion" value="58120.0.0.0" />
							  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
							  <param name="expressinstall" value="/common/js/flash/expressInstall.swf" />
							  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
							  <!--[if !IE]>-->
							  <object type="application/x-shockwave-flash" data="/images/banners/'.$banner["banner_image"].'" width="300" height="250">
							    <!--<![endif]-->
							    <param name="quality" value="high" />
							    <param name="wmode" value="opaque" />
							    <param name="swfversion" value="58120.0.0.0" />
							    <param name="expressinstall" value="/common/js/flash/expressInstall.swf" />
							    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
							    <div>
							      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
							      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
							    </div>
							    <!--[if !IE]>-->
							  </object>
							  <!--<![endif]-->
							</object>';
						} else {
							$art .= '<img border="0" src="/images/banners/'.$banner["banner_image"].'" alt="" />';
						}
						if(!empty($banner["banner_url"])) {
							$art .= '</a>';
						}
					}
					/*if($this->siteid==11)
						$art .= "</div>";
					else 
						$art .= "</p>";*/
					$art .= "</div>";
				}
			}
			$i++;
		}
		$article['article'] = $art;
		
		//$article['first_paragraph'] = $firstParagraph;
		$articleText = str_ireplace(array('</p>', '<br>', '<br />'), array('</p> ', '<br> ', '<br /> '), $article['article']);
		$article['first_paragraph'] = "<p>".substr(trim(strip_tags($articleText)), 0, 300)."&hellip;</p>";
		
		if(!empty($this->config->cms->paywall->enable)) {
			$pubdateTimestamp = strtotime($article['pubdate']);
			if(!empty($this->config->cms->paywall->days_to_hide_initial_free_articles) && $article['article_priority_id']==2) {
				if($pubdateTimestamp < (time()-($this->config->cms->paywall->days_to_hide_initial_free_articles*24*3600))) {
					$article['article_priority_id'] = 2;
				}
				else {
					$article['article_priority_id'] = 3;
				}
			}
		}
				
		$this->view->article = $article;
		
		if(empty($article["article_id"])) {
			$this->_response->setRedirect($this->config->general->url);
			$this->_response->sendResponse();
			exit();
		}
		
		if(!($articlePhotos = $this->cache->load($this->environment."articlephoto_".$articleId."_cms_".$this->siteid))) {
			$contentGallery = $this->loadModel('contentgallery');
			$articlePhotos  = $contentGallery->getArticlePhoto($articleId);
			
			if(!empty($this->config->gallery->use_smugmug) && is_array($articlePhotos) && !empty($articlePhotos)) {
				$libPath = dirname(dirname(dirname(dirname(__FILE__))));
		    	$libPath = str_replace("\\", "/", $libPath);
		    	$libPath = rtrim($libPath, '/');
		    	$libPath .= '/lib';
		    	require_once($libPath."/phpSmug.php");
		    	$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
		    	try {
		    		$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );	
		    	}
		    	catch (Exception $ex) { }
			}
			
			if(is_array($articlePhotos)) foreach($articlePhotos as $key=>$photo)
			{
				$articlePhotos[$key]['for_sale'] = 0;
				$articlePhotos[$key]['sale_url'] = '';
				if($photo['content_gallery_type_id'] == '1') // photo
				{
					if(!empty($this->config->gallery->use_smugmug) && !empty($photo['smugmug_id'])) {
						try {
							$smugmugImage = $f->images_getInfo('ImageID='.$photo['smugmug_id'],'ImageKey='.$photo['smugmug_key']);
							$articlePhotos[$key]['img_url'] = $articlePhotos[$key]['img_thumb'] = $smugmugImage['LargeURL'];
							$articlePhotos[$key]['album_url'] = $smugmugImage['Album']['URL'];
							if($smugmugImage['Album']['id'] != $this->config->gallery->smugmug_gallery_id) {
								$articlePhotos[$key]['for_sale'] = 1;
								$smugmugDomain = substr($smugmugImage['Album']['URL'], 0, strpos($smugmugImage['Album']['URL'], '/', 10));
								$smugmugDomain = trim($smugmugDomain, '/');
								$articlePhotos[$key]['sale_url'] = $smugmugDomain.'/buy/'.$smugmugImage['Album']['id'].'_'.$smugmugImage['Album']['Key'].'/'.$smugmugImage['id'].'_'.$smugmugImage['Key'];
							}
							if(empty($articlePhotos[$key]['sale_url']) && in_array($this->siteid, array(13, 14, 15))) {
								$image = getimagesize($smugmugImage['TinyURL'], &$info);
								
								$iptc = iptcparse($info['APP13']);
								$IPTCTitle = "";
								if(!empty($iptc['2#120'][0])) {
									$IPTCTitle = $iptc['2#120'][0];
									$IPTCTitle = strtoupper($IPTCTitle);
								}
								if(!strpos($IPTCTitle, "AP PHOTO") && !strpos($IPTCTitle, "ASSOCIATED PRESS")) {
									$articlePhotos[$key]['for_sale'] = 1;
									$smugmugDomain = substr($smugmugImage['Album']['URL'], 0, strpos($smugmugImage['Album']['URL'], '/', 10));
									$smugmugDomain = trim($smugmugDomain, '/');
									$articlePhotos[$key]['sale_url'] = $smugmugDomain.'/buy/'.$smugmugImage['Album']['id'].'_'.$smugmugImage['Album']['Key'].'/'.$smugmugImage['id'].'_'.$smugmugImage['Key'];
								}
							}
						} catch(Exception $ex) { }
					}
					else
					{							
						$articlePhotos[$key]['img_url'] = $this->articleImageUrl."/cached/".$photo['source_system_id'];
						$articlePhotos[$key]['img_thumb'] = $this->baseUrl."/image/getthumbbyid/im/".$photo["content_images_id"]."/width/300/height/250";
					}
				}
				elseif($photo['content_gallery_type_id'] == '2' && $photo['image_class_id']==1) // video
				{
					$this->view->use_youtube = $this->config->video->use_youtube;
					if(!empty($this->config->video->use_youtube) && $this->config->video->use_youtube == '1' && !empty($photo['youtube_id'])) {
						try {    						
	    					//$articlePhotos[$key]['video_url'] = "https://img.youtube.com/vi/".$photo['youtube_id']."/0.jpg";
	    					$articlePhotos[$key]['video'] = '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$photo['youtube_id'].'?rel=0&amp;wmode=transparent" frameborder="0" allowfullscreen allowtransparency="true"></iframe>';
	    					$articlePhotos[$key]['youtube_id']	= $photo['youtube_id'];
						} catch(Exception $ex) {}
					} else {
						$articlePhotos[$key]['video'] = $this->baseUrl.'/images/article_photos/'.$photo['source_system_id'];
					}
				}
			}
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($articlePhoto, $this->environment."articlephoto_".$articleId."_cms_".$this->siteid, array("article_cms_".$this->siteid), ARTICLE_CACHE);
		}
		$this->view->articlePhotos = $articlePhotos;
		$this->view->album_url = $articlePhotos[0]['album_url'];
		
		if($article["enable_rating"]==1) {
			$this->view->articleRate = $contentArticle->getRating($articleId);
			$this->view->alreadyVote = !(empty($_COOKIE["a_".$articleId]));
		}
		
		if(!($articleComments = $this->cache->load($this->environment."article_".$articleId."_comments_cms_".$this->siteid))) {
			$commentsClass = $this->loadModel('comments');
			$articleComments  = $commentsClass->getComments($articleId, '1');
			foreach ($articleComments as &$comment)
			{
				$comment_datetime = explode(" ", $comment['comment_time']);
				$comment_date = explode("-", $comment_datetime[0]);
				$comment['datetime'] = date("M j, Y", mktime(0, 0, 0, $comment_date[1], $comment_date[2], $comment_date[0]))." ".$comment_datetime[1];
				$comment['child'] = $commentsClass->getReplyComments($comment['comment_id']);
				if(!empty($comment['child']))
				{
					foreach ($comment['child'] as &$child)
					{
						$child_datetime = explode(" ", $child['comment_time']);
						$child_date = explode("-", $child_datetime[0]);
						$child['datetime'] = date("M j, Y", mktime(0, 0, 0, $child_date[1], $child_date[2], $child_date[0]))." ".$child_datetime[1];
					}
				}
			}
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($articleComments, $this->environment."article_".$articleId."_comments_cms_".$this->siteid, array("article_cms_".$this->siteid));
		}
		$this->view->articleComments = $articleComments;
		
		// 0 for article, 1 for comment, 2 for email
		$contentArticle->updateStatistic($articleId, 0);
		
		$contentArticle->updateArticleView($articleId);
		
		$this->view->title = stripslashes($article["headline"]);
		$this->view->keywords = $article["keyword"];
		
		if(!empty($article["content_section_area_id"]))
		{
			if(!empty($article["section_custom_title"]))
				$this->view->section_name = $article["section_custom_title"];
			else
				$this->view->section_name = $article["section_name"];
				
			if(!empty($article["section_custom_url"])) 
			{ 
				echo $article["section_custom_url"]; 
			} 
			else { 
				if ($article["section_id"] == 0 && $article["section_template"] > 0) 
				{ 
					$this->view->sectionURL = $this->baseUrl.'/article/template/id/'.$article["section_template"]; 
				} 
				elseif($article["section_id"] == 0  && ($article["section_template"] < 1 || empty($article["section_template"])) && !empty($article['content_section_area_id'])) { 
					$csa_title = preg_replace("/[^a-z 0-9\_]/i", "", $article["section_custom_title"]);
					$csa_title = str_replace("  ", " ", $csa_title);
					$csa_title = trim($csa_title);
					$csa_title = str_replace(" ", "-", $csa_title);
					$csa_title = substr($csa_title, 0, 190);
					$csa_title = trim($csa_title);
					$csa_title = strtolower($csa_title);
					$this->view->sectionURL = $this->baseUrl.'/index/custompage/menu/csa/id/'.$article["content_section_area_id"].'/title/'.$csa_title; 
				} 
				else if(!empty($article["content_section_area_id"]) && !empty($article["section_id"]) && !empty($article["section_name"]))
				{ 
					if($article['area_name']=='High Schools') {
						$this->view->sectionURL = $this->baseUrl.'/article/template/id/7/csa_id/'.$article["content_section_area_id"];
					}
					else {
						$this->view->sectionURL = $this->baseUrl.'/index/section/csa_id/'.$article["content_section_area_id"].'/section_id/'.$article["section_id"].'/section_name/'.urlencode(stripslashes($article["section_name"]));
					} 
				} 
			}	
		}
		$this->view->groupName = $article["group_name"];
		
		if(!empty($article['area_id']) || !empty($article['menu_area_id']))
		{
			if(!empty($article['menu_area_name']))
				$this->view->areaName = $article["menu_area_name"];
			else
				$this->view->areaName = $article["area_name"];
			
			if(!empty($article['menu_area_id']))
			{
				$custom_url = $article['menu_custom_url'];
				$template_name = $article['menu_template_name'];
				$area_id = $article['menu_area_id'];
				$area_name = $article['menu_area_name'];
				$custom_title = $article['menu_custom_title'];
				$section_id = $article['menu_section_id'];
			}
			else
			{
				$custom_url = $article['area_custom_url'];
				$template_name = $article['area_template_name'];
				$area_id = $article['area_id'];
				$area_name = $article['area_name'];
				$custom_title = $article['area_custom_title'];
				$section_id = $article['area_section_id'];
			}
			
			if(!empty($custom_url)) { 
				$this->view->areaURL = $custom_url; 
			} 
			elseif(!empty($template_name)) { 
				$this->view->areaURL = $this->baseUrl.'/index/area/area_id/'.$area_id.'/area_name/'.urlencode(stripslashes($area_name)); 
			} 
			elseif(!empty($section_id) && $section_id > 0) { 
				$this->view->areaURL = $this->baseUrl.'/index/areasection/area_id/'.$area_id.'/section_id/'.$section_id.'/section_name/'.urlencode(stripslashes($article["section_name"])); 
			} 
			else 
			{ 	
				$area_title = preg_replace("/[^a-z 0-9\_]/i", "", $custom_title);
				$area_title = str_replace("  ", " ", $area_title);
				$area_title = trim($area_title);
				$area_title = str_replace(" ", "-", $area_title);
				$area_title = substr($area_title, 0, 190);
				$area_title = trim($area_title);
				$area_title = strtolower($area_title);
				$this->view->areaURL = $this->baseUrl."/index/custompage/menu/ca/id/".$area_id."/title/".$area_title; 
			}
			
			$this->view->area_id = $article['area_id'];
		}
		
		$this->view->require_login_to_post_comment = $this->config->cms->require_login_to_post_comment;
		$this->view->use_facebook_comment = $this->config->cms->use_facebook_comment;
		$this->view->openGraphFb = '1';
		$uri = preg_replace("/[^a-z 0-9\_]/i", "", $article["headline"]);
		$uri = str_replace("  ", " ", $uri);
		$uri = trim($uri);
		$uri = str_replace(" ", "-", $uri);
		$uri = substr($uri, 0, 190);
		$uri = trim($uri);
		$uri = strtolower($uri);
		$this->view->curUrl = $this->baseUrl."/article/view/article_id/".$article["article_id"]."/headline/".$uri."/section/".urlencode(stripslashes($article["section_name"]));
		$this->view->imageUrl = $this->articleImageUrl."/cached/".$articlePhotos[0]["source_system_id"];
		$description = stripslashes(substr($article["article"], 0, 100));
		if(strlen($article["article"]) > 100) $description = $description . "...";
		$this->view->description = $description;

		if(!empty($this->session->formData["comment"])) {
			$this->view->comment_name = $this->session->formData['comment_name'];
			$this->view->comment = $this->session->formData['comment'];
//			$this->view->article["article_id"] = $this->session->formData['article_id'];
		}
		unset($this->session->formData);
		
		/*** Trending Stories ***/
    	/*if($this->siteid == '11')
    	{
    		$trendingStories = $contentArticle->getTrendingArticles($this->config->general->siteid, '12');
    		$trendingStories = $this->assignImageAndVideoForArticles($trendingStories, $smugmugURL);
    		$this->view->trendingStories = $trendingStories;
    		$this->view->trendingStoriesTpl = $this->view->render("trending_stories.tpl");
    	}
		*/
    	
    	$raClass = $this->loadModel('relatedarticles');
    	
		$relatedArticles  = $raClass->getRelatedArticles($this->siteid, $articleId);
		if(is_array($relatedArticles)) foreach ($relatedArticles as $key=>$relatedArticle) {
			/* /article/view/article_id/363191/headline/headline-here */
			if(substr($relatedArticle['related_article_custom_url'], 0, 24) == '/article/view/article_id') {
				$temp = explode("/", $relatedArticle['related_article_custom_url']);
				$relatedArticleId = $temp[4];
				if(is_numeric($relatedArticleId)) {
					$headline = $this->db->fetchOne("SELECT headline FROM content_articles WHERE article_id='{$relatedArticleId}'");
					$relatedArticles[$key]['related_headline'] = $headline;
					$relatedArticles[$key]['related_article_custom_url'] = preg_replace("!/article/view/article_id/([0-9]+)/headline/([^\"]+)/section/([^\"]+)!is", "/$3/$1/$2", $relatedArticle['related_article_custom_url']);
				}		
			}
		}
		
		/* if(!($relatedArticles = $this->cache->load($this->environment."related_article_".$articleId."_cms_".$this->siteid))) {
			$relatedArticles  = $raClass->getRelatedArticles($this->siteid, $articleId);
			if(is_array($relatedArticles)) foreach ($relatedArticles as $key=>$relatedArticle) {
				// /article/view/article_id/363191/headline/headline-here 
				if(substr($relatedArticle['related_article_custom_url'], 0, 24) == '/article/view/article_id') {
					$temp = explode("/", $relatedArticle['related_article_custom_url']);
					$relatedArticleId = $temp[4];
					if(is_numeric($relatedArticleId)) {
						$headline = $this->db->fetchOne("SELECT headline FROM content_articles WHERE article_id='{$relatedArticleId}'");
						$relatedArticles[$key]['related_headline'] = $headline;
					}		
				}
			}
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($relatedArticles, $this->environment."related_article_".$articleId."_cms_".$this->siteid, array("related_article_cms_".$this->siteid));
			
		} */
				
		$this->view->relatedArticles = $relatedArticles;
		
		if(!($articlePageWidget = $this->cache->load($this->environment."article_page_widget_cms_".$this->siteid))) {
			$widgetClass = $this->loadModel('widget');
			$articlePageWidget = $widgetClass->getWidgets($this->siteid, 5);
			
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($articlePageWidget, $this->environment."article_page_widget_cms_".$this->siteid, array("widget_cms_".$this->siteid), BANNER_CACHE);
		}
		$this->view->articlePageWidget = $articlePageWidget;
		
		$this->renderTemplate("article.tpl");
	}
	
	public function viewbysourcesystemidAction() {
		$sourceSystemId = intval($this->_request->getParam("source_system_id"));
		$contentArticle = $this->loadModel('contentarticle');
		if(!($article = $this->cache->load($this->environment."articlebysource_".$sourceSystemId."_cms_".$this->siteid))) {
			$article  = $contentArticle->getArticleBySourceSystemId($this->siteid, $sourceSystemId);
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($article, $this->environment."articlebysource_".$sourceSystemId."_cms_".$this->siteid, array("article_cms_".$this->siteid));
		}
		if(!empty($article['article_id'])) {
			$headline = preg_replace("/[^a-z 0-9\_]/i", "", $article['headline']);
			$headline = str_replace("  ", " ", $headline);
			$headline = trim($headline);
			$headline = str_replace(" ", "-", $headline);
			$headline = substr($headline, 0, 190);
			$headline = trim($headline);
			$headline = strtolower($headline);
			header("Location: /".$article['section_name']."/".$article['article_id']."/".$headline, true, 301);
			exit();
		}
	}
	
	public function voteAction() {
		$articleId = $this->_request->getParam("article_id");
		
		if(!is_numeric($articleId))
		{
			throw new Falcon_Content_Exception('Invalid parameter in voteAction',EXCEPTION_INVALID_PARAMETERS);
		}
		
		$rate = $this->_request->getParam("rate");
		$contentArticle = $this->loadModel('contentarticle');
		if(!empty($_COOKIE["a_".$articleId])) {
		}
		else {
			setcookie("a_".$articleId, $rate, time()+24*3600, "/");
			$contentArticle->updateRating($articleId, $rate);	
		}
		$rating = $contentArticle->getRating($articleId);
		echo json_encode($rating);		
		
	}
	
	public function postcommentAction() {
		$params = $this->_getAllParams();
		
		if($params['code'] != $this->session->captchaWord)
		{
			$this->view->errors = array("Verification code not valid");
			$this->view->comment_name = $params['comment_name'];
			$this->view->comment = $params['comment'];
			$this->view->article["article_id"] = $params['article_id'];
			//self::viewAction();
			$this->session->formData = $params;
			$referer = $_SERVER["HTTP_REFERER"];
			$temp = explode("?", $referer);
			$referer = $temp[0];
			if(!empty($params['parent_comment_id']))
				header("location:".$referer."?id=".$params['parent_comment_id']."&i=0&msg".$params['parent_comment_id']."=Verification code not valid.#rcomment".$params['parent_comment_id']);
			else
				header("location:".$referer."?msg=Verification code not valid.#postcomment");
		}
		else
		{
			if($this->config->cms->require_login_to_post_comment == '1')
			{
				$params['comment_name'] = $this->ident['username'];
				$params['userid'] = $this->ident['userid'];
			}
			else
				$params['userid'] = '0';
			
			$params["comment_source_id"] = '1';
			$params["source_id"] = $params['article_id'];
			if(empty($params["parent_comment_id"]))
				$params["parent_comment_id"] = '0';
			
			$comments = $this->loadModel('comments');
			$comments->addComment($params, $this->config->general->siteid);
			$contentArticle = $this->loadModel('contentarticle');
			$contentArticle->updateStatistic($params["article_id"], 1);
			//self::viewAction();
			/*$this->_response->setRedirect($_SERVER["HTTP_REFERER"]);
			$this->_response->sendResponse();*/
			$referer = $_SERVER["HTTP_REFERER"];
			$temp = explode("?", $referer);
			$referer = $temp[0];
			if(!empty($params['parent_comment_id']))
				header("location:".$referer."?id=".$params['parent_comment_id']."&i=1&msg".$params['parent_comment_id']."=Thank you for replying to this comment, your comment will be displayed on the site after it has been approved by our administrator.#rcomment".$params['parent_comment_id']);
			else
				header("location:".$referer."?msg=Thank you for posting comment, your comment will be displayed on the site after it has been approved by our administrator.#postcomment");
			exit();
		}
	}
	
	function showarticleAction()
    {
    	$params = $this->_getAllParams();
    	
    	if(!is_numeric($params['id']))
		{
			throw new Falcon_Content_Exception('Invalid parameter in showarticleAction',EXCEPTION_INVALID_PARAMETERS);
		}
    	
    	Zend_Loader::LoadClass('lookandfeelClass', $this->modelDir);
    	$lookandfeel = new lookandfeelClass();
		$this->view->lookandfeel=$lookandfeel->getLookandfeel($this->config->general->siteid);
    	
    	Zend_Loader::LoadClass('contentarticleClass', $this->modelDir);
		$cac = new contentArticleClass();
		
		$article = $cac->getArticle($this->config->general->siteid, $params['id']);
		
		$pubdate = explode(' ', $article['pubdate']);
		$pubdate_date = explode('-', $pubdate[0]);
		$pubdate_time = explode(':', $pubdate[1]);
		
		$article['pubdate'] = date('l, F j, Y g:i a',mktime($pubdate_time[0], $pubdate_time[1], $pubdate_time[2], $pubdate_date[1], $pubdate_date[2], $pubdate_date[0]));
		
		$modify_date_time = explode(' ', $article['modify_date_time']);
		$modify_date = explode('-', $modify_date_time[0]);
		$modify_time = explode(':', $modify_date_time[1]);
		
		$article['modify_date_time'] = date('g:i a, D M j, Y ',mktime($modify_time[0], $modify_time[1], $modify_time[2], $modify_date[1], $modify_date[2], $modify_date[0]));
		
		$article_text = explode('<br/>', $article['article']);
		$article['article'] = '';
		foreach ($article_text as $text)
		{
			$article['article'] .= '<p>'.$text.'</p>';
		}
		
		$this->view->article = $article;
		
		$this->view->photos = $cac->getPhotos($this->config->general->siteid, $article['article_id']);
		
		//print_r($this->view->photos); exit();
		
		$div_width = 0;
		
		foreach ($this->view->photos as $photos)
		{
			list($width, $height, $type, $attr) = getimagesize($this->config->paths->html."/images/article_photos/".$photos['source_system_id']);

			$h_div = $height / 250;
			$w = $width/$h_div;
			if($w > $div_width)
				$div_width = $w;
		}
		
		$this->view->div_width = $div_width;
		
		echo $this->view->render('header.tpl');
		echo $this->view->render('show_article.tpl');
		echo $this->view->render('footer.tpl');
    }
    
    /*** function showgallery sepertinya tidak dipakai ***/
    function showgalleryAction()
    {		
    	$params = $this->_getAllParams();
    	
    	if(!is_numeric($params['id']))
		{
			throw new Falcon_Content_Exception('Invalid parameter in showgalleryAction',EXCEPTION_INVALID_PARAMETERS);
		}
    	
    	Zend_Loader::LoadClass('lookandfeelClass', $this->modelDir);
    	$lookandfeel = new lookandfeelClass();
		$this->view->lookandfeel=$lookandfeel->getLookandfeel($this->config->general->siteid);
		
		Zend_Loader::LoadClass('contentarticleClass', $this->modelDir);
		$cac = new contentArticleClass();
		
		$article = $cac->getArticle($this->config->general->siteid, $params['id']);
		
		$pubdate = explode(' ', $article['pubdate']);
		$pubdate_date = explode('-', $pubdate[0]);
		$pubdate_time = explode(':', $pubdate[1]);
		
		$article['pubdate'] = date('l, F j, Y g:i a',mktime($pubdate_time[0], $pubdate_time[1], $pubdate_time[2], $pubdate_date[1], $pubdate_date[2], $pubdate_date[0]));
		
		$modify_date_time = explode(' ', $article['modify_date_time']);
		$modify_date = explode('-', $modify_date_time[0]);
		$modify_time = explode(':', $modify_date_time[1]);
		
		$article['modify_date_time'] = date('g:i a, D M j, Y ',mktime($modify_time[0], $modify_time[1], $modify_time[2], $modify_date[1], $modify_date[2], $modify_date[0]));
		
		$this->view->article = $article;
		
		$this->view->photos = $cac->getPhotos($this->config->general->siteid, $article['article_id']);
    	
		echo $this->view->render('header.tpl');
		echo $this->view->render('show_gallery.tpl');
		echo $this->view->render('footer.tpl');
    }
	
	function printarticleAction()
	{
		$articleId = $this->_request->getParam("articleid");
		
		if(empty($articleId))
		{
			throw new Falcon_Content_Exception('Invalid parameter in printarticleAction',EXCEPTION_INVALID_PARAMETERS);
		}
		
		$article_id = split("\.",$articleId);
		$contentArticle = $this->loadModel('contentarticle');
		
		if(!($article = $this->cache->load($this->environment."article_".$article_id[0]."_cms_".$this->siteid))) {
			$article  = $contentArticle->getArticle($this->siteid, $article_id[0]);
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($article, $this->environment."article_".$article_id[0]."_cms_".$this->siteid, array("article_cms_".$this->siteid));
		}
		$this->view->article = $article;
		
		if(!($articlePhotos = $this->cache->load($this->environment."articlephoto_".$article_id[0]."_cms_".$this->siteid))) {
			$contentGallery = $this->loadModel('contentgallery');
			$articlePhotos  = $contentGallery->getArticlePhoto($article_id[0]);
			if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				$this->cache->save($articlePhoto, $this->environment."articlephoto_".$article_id[0]."_cms_".$this->siteid, array("article_cms_".$this->siteid));
		}
		$this->view->articlePhotos = $articlePhotos;
		
		$this->view->require_login_to_post_comment = $this->config->cms->require_login_to_post_comment;
		$this->view->use_facebook_comment = $this->config->cms->use_facebook_comment;
		$this->view->openGraphFb = '1';
		$uri = preg_replace("/[^a-z 0-9\_]/i", "", $article["headline"]);
		$uri = str_replace("  ", " ", $uri);
		$uri = trim($uri);
		$uri = str_replace(" ", "-", $uri);
		$uri = substr($uri, 0, 190);
		$uri = trim($uri);
		$uri = strtolower($uri);
		$this->view->curUrl = $this->baseUrl."/article/view/article_id/".$article["article_id"]."/headline/".$uri."/section/".urlencode(stripslashes($article["section_name"]));
		
		$output = $this->view->render('print_article.tpl');
		$output = $this->reformatQuery($output);
		
		ob_start();
		echo $output;
		ob_end_flush();
	}

	function sendarticleAction()
	{
		$params = $this->_getAllParams();
		
		//Send the mail		
		/*$mail = new Zend_Mail();
		$mail->setBodyText($mailoutput, 'utf8');
		$mail->addTo($params['recipient'], $params['recipient']);
		switch ($this->site_id)
		{
			case 1:
				$mail->setFrom('noreply@mdjonline.com', 'Marietta Daily Journal');
				$mail->setSubject($this->view->headline.' - Article at MDJOnline.com');
				break;

			case 2:
				$mail->setFrom('noreply@cherokeetribune.com', 'Cherokee Tribune');
				$mail->setSubject($this->view->headline.' - Article at cherokeetribune.com');
				break;
				
			case 3:
				$mail->setFrom('noreply@variety.falconocp.com', 'Variety');
				$mail->setSubject($this->view->headline.' - Article at variety.falconocp.com');
				break;
		}
		$mail->send();*/
		
		
		$contentArticle = $this->loadModel('contentarticle');
		
		$contentArticle->sendArticle($params, $this->site["email"]);
		
		// 0 for article, 1 for comment, 2 for email
		$contentArticle->updateStatistic($params["article_id"], 2);
		
		$this->_response->setRedirect($_SERVER["HTTP_REFERER"]);
		$this->_response->sendResponse();
		exit();
	}
	
	function templateAction()
    {
    	$params = $this->_getAllParams();
    	
    	if (!is_numeric($params['id']) || $params['id'] < 1 || $params['id'] > 11)
		{
			throw new Falcon_Content_Exception('Invalid parameter in templateAction',EXCEPTION_INVALID_PARAMETERS);
		}
		
		$smugmugURL = "";
    	if(!empty($this->config->gallery->use_smugmug)) {
    		if(!empty($this->config->smugmug->url)) $smugmugURL = $this->config->smugmug->url;
    		else {
	    		$libPath = dirname(dirname(dirname(dirname(__FILE__))));
		    	$libPath = str_replace("\\", "/", $libPath);
		    	$libPath = rtrim($libPath, '/');
		    	$libPath .= '/lib';
		    	require_once($libPath."/phpSmug.php");
		    	$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
		    	try {			
					$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );
					$smugmugURL = $f->parsed_response['Login']['User']['URL'];
		    	} catch (Exception $ex) {}
    		}
    	}
		
    	switch ($params['id'])
    	{
    		case 1: if(in_array($this->siteid, array(9,13, 14, 15)))
    				{
    					Zend_Loader::LoadClass('contentarticleClass', $this->modelDir);
						$cac = new contentArticleClass();
						$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction, date("Y")));
    					$startmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d"), date("Y")));
	    				if(!($topReadPassMonth = $this->cache->load($this->environment."50topReadPassMonth_cms_".$this->siteid))) {
					    	$topReadPassMonth = $cac->getTopReadStories($this->config->general->siteid, 50, $startmonth, $today);
					    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
					    		$this->cache->save($topReadPassMonth, $this->environment."50topReadPassMonth_cms_".$this->siteid, array("article_cms_".$this->siteid));
				    	}
				    	$this->view->topreadstories = $topReadPassMonth;
				    	$this->view->title = "Most Read Articles";
    				}
    				else
    					$this->view->title = "Top Read Stories";
    				$this->renderTemplate("top_read_stories.tpl");
    				break;
    				
    		case 2:	if(empty($params['start']))
    					$params['start'] = 0;
    					
    				if(!($ssections = $this->cache->load($this->environment."specialsections_cms_".$this->siteid))) {
			    		$specialSections = $this->loadModel('specialsections');
			    		$ssections = $specialSections->getSpecialSections($this->config->general->siteid, $params['start'], 12, "title");
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($ssections, $this->environment."specialsections_cms_".$this->siteid, array("specialsections_cms_".$this->siteid));
			    	}
			    	
			    	$this->view->specialSections = $ssections['data'];	
			    	
    				$this->view->pageSize = 12;
			    	$this->view->pagingData = $this->generatePagingData($this->baseUrl."/article/template/id/2", $params["start"], 12, 10, $ssections["count"]);
			    	
    				$this->view->pageHelper = $this->view->render("paging.tpl");	
    				
    				$this->view->title = "Special Sections";
    				
    				$this->renderTemplate("special_sections.tpl");
    				break;
    				
    		case 3:	$section = $this->loadModel('section');
    				if(!($newsSections = $this->cache->load($this->environment."newsSections_cms_".$this->siteid))) {
			    		$newsSections = $section->getContentSections($this->config->general->siteid);
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($newsSections, $this->environment."newsSections_cms_".$this->siteid, array("newsSections_cms_".$this->siteid));
			    	}
			    	$this->view->newsSections = $newsSections;
	    		if(in_array($this->siteid, array(13, 14, 15))) $this->view->title = "RSS";
    			else $this->view->title = "RSS Feeds";
    				$this->renderTemplate("rss.tpl");
    				break;
    			
    		case 4:	if(empty($params['date']))
    				{
    					$filterdate = date("Y-m-d");
    					$date = date("l, F j, Y");
    				}
    				else
    				{
    					$filterdate = $params['date'];
    					$selDate = explode('-',$params['date']);
    					$date = date("l, F j, Y",mktime(0,0,0,$selDate[1],$selDate[2],$selDate[0]));
    				}
    				
    				$this->createCaptcha();
    				
    				$events = $this->loadModel('events');
    				
    				if(!($this->events = $this->cache->load($this->environment."events_cms_".str_replace("-","_",$params['date'])."_".$this->siteid))) {
			    		$this->events = $events->getEventsByDate($this->siteid, $filterdate);
			    		foreach ($this->events as &$event)
			    		{
			    			if(!empty($event['image']))
			    				$event['image'] = $this->config->paths->url . "images/events/".$event['image'];
			    		}
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($newsSections, $this->environment."events_cms_".$this->siteid, array("events_cms_".str_replace("-","_",$params['date'])."_".$this->siteid));
			    	}
			    	$this->view->events = $this->events;
    				
    				$fdate = explode('-',$filterdate);
    				$this->view->next = date("Y-m-d", mktime(0, 0, 0, $fdate[1], $fdate[2]+1, $fdate[0]));
    				$this->view->prev = date("Y-m-d", mktime(0, 0, 0, $fdate[1], $fdate[2]-1, $fdate[0]));
    				$this->view->date = $date;
					$this->view->allowAnonymousEventSubmission = ((!empty($this->config->cms->events->allow_submission_without_login))?true:false);
    				$this->view->title = "Event Calendar";
    				$this->renderTemplate("event_calendar.tpl");
    				break;
    		case 5: $csa_id = $this->_request->getParam("csa_id");
    				$content = $this->loadModel('content');
    				$csa = $content->getContentSectionArea($csa_id);
    				$this->view->section_id = $section_id = $csa['section_id'];
    				
    				$groupSectionIds = $content->getSectionIdsPerGroup($csa);
    				$temp = explode(',', $groupSectionIds);
    				
    				if(count($temp) > 1) $groupSectionIds = $temp;
    				else $groupSectionIds = array();
    				    				
    				$this->view->area_id = $csa['area_id'];
    				$this->view->content_section_area_id=$csa['content_section_area_id'];
    				$cac = $this->loadModel('contentarticle');
    				/*if(!($homeSlideshow = $this->cache->load($this->environment."articlehslideshow_".$section_id."_cms_".$this->siteid))) {
				    	$homeSlideshow = $cac->getSlideshowBySection($this->config->general->siteid, $section_id);
				    	if(is_array($homeSlideshow)) foreach ($homeSlideshow as $key=>$article) {
				    		if(!empty($this->config->gallery->use_smugmug)) {
								$homeSlideshow[$key]['image_name'] = $smugmugURL."/photos/i-".$article['smugmug_key']."/0/M/i-".$article['smugmug_key']."-M.jpg";
							}
							else
							{
								preg_match_all("|<img[^>]+>|U", $article["article"], $matches);
								if(is_array($matches[0])) foreach ($matches[0] as $img) {
									$imageStr = $img;
									$strPos = strpos($imageStr, 'src=');
									$strPos += 5;
									$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
									if(file_exists($this->config->paths->html.$src)) {
										list($width, $height) = getimagesize($this->config->paths->html.$src);
										if($width > 100 && $height > 100) {
											$homeSlideshow[$key]['image_name'] = $this->baseUrl."/images/article_photos/".$src;
											break;
										}
									}
								}
							}
				    	}
				    	
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($homeSlideshow, $this->environment."articlehslideshow_".$section_id."_cms_".$this->siteid, array("article_cms_".$this->siteid), GALLERY_CACHE);
			    	}*/
    				if(!($homeSlideshow = $this->cache->load($this->environment."articlehslideshow_section". ((!empty($groupSectionIds))?implode('_', $groupSectionIds):$section_id)."_cms_".$this->siteid))) {
			    		$homeSlideshow = $cac->getSectionFrontSlideshow($this->config->general->siteid, 0, ((!empty($groupSectionIds))?$groupSectionIds:$section_id), ((empty($this->config->cms->total_articles_in_slider) || !is_numeric($this->config->cms->total_articles_in_slider))?6:$this->config->cms->total_articles_in_slider));
				    	/* if(is_array($homeSlideshow)) foreach ($homeSlideshow as $key=>$article) {
				    		if(!empty($this->config->gallery->use_smugmug)) {
								$homeSlideshow[$key]['image_name'] = $smugmugURL."/photos/i-".$article['smugmug_key']."/0/M/i-".$article['smugmug_key']."-M.jpg";
							}
							else
							{
								preg_match_all("|<img[^>]+>|U", $article["article"], $matches);
								if(is_array($matches[0])) foreach ($matches[0] as $img) {
									$imageStr = $img;
									$strPos = strpos($imageStr, 'src=');
									$strPos += 5;
									$src = substr($imageStr, $strPos, strpos($imageStr, '"', $strPos)-$strPos);
									if(file_exists($this->config->paths->html.$src)) {
										list($width, $height) = getimagesize($this->config->paths->html.$src);
										if($width > 100 && $height > 100) {
											$homeSlideshow[$key]['image_name'] = $this->baseUrl."/images/article_photos/".$src;
											break;
										}
									}
								}
							}
				    	} */
				    	
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($homeSlideshow, $this->environment."articlehslideshow_section".$section_id."_cms_".$this->siteid, array("article_cms_".$this->siteid), GALLERY_CACHE);
			    	}
			    	$homeSlideshow = $this->assignImageAndVideoForArticles($homeSlideshow, $smugmugURL);
			    	$this->view->homeSlideshows = $homeSlideshow;
			    	$slideshowHomeTpl = $this->view->render("slideshow_home.tpl");
			    	$slideshowHomeTpl = preg_replace("!".$this->baseUrl."/article/view/article_id/([0-9]+)/headline/([^\"]+)/section/([^\"]+)!is", $this->baseUrl."/$3/$1/$2", $slideshowHomeTpl);
			    	$slideshowHomeTpl = preg_replace("!/article/view/article_id/([0-9]+)/headline/([^\"]+)/section/([^\"]+)!is", "/$3/$1/$2", $slideshowHomeTpl);
			    	$this->view->homeSlideshow = $slideshowHomeTpl;
			    	
			    	$params = $this->_getAllParams();
			    	$start = intval($params['start']);
			    	if(!($result = $this->cache->load($this->environment."rlatestnews_".((!empty($groupSectionIds))?implode('_', $groupSectionIds):$section_id)."_".$start."_cms_".$this->siteid))) {
			    		$result = $cac->getLatestNewsBySection($this->config->general->siteid, ((!empty($groupSectionIds))?$groupSectionIds:$section_id), 0, 9, $start, ((!empty($this->config->cms->total_articles_on_recent_news_area))?$this->config->cms->total_articles_on_recent_news_area:6), true);
						$result['articles'] = $this->assignImageAndVideoForArticles($result['articles'], $smugmugURL);
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($result, $this->environment."rlatestnews_".$section_id."_".$start."_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	}
			    	$latestNews = $result['articles'];
			    	$total = $result['count'];
			    	$this->view->latestNews = $latestNews;
			    	$this->view->pagingData = $this->generatePagingData($this->baseUrl."/article/template/id/5/csa_id/".$csa['content_section_area_id'], (int)$params["start"], ((!empty($this->config->cms->total_articles_on_recent_news_area))?$this->config->cms->total_articles_on_recent_news_area:6), 10, $total);
					$this->view->latestNewsPageHelper = $this->view->render("paging.tpl");
			    	
			    	if(in_array($this->siteid, array(10,11, 7, 12 ))) {
			    		if(!($sectionFrontArticles = $this->cache->load($this->environment."sectionfrontarticles{$csa['area_id']}_{$csa['content_section_area_id']}_cms_".$this->siteid))) {
							$limit = 5;
												
							$sectionFrontArticles = $cac->getSectionFrontArticles($this->config->general->siteid, $csa['area_id'], $csa['content_section_area_id'], ((!empty($this->config->cms->total_sectionfront_articles))?$this->config->cms->total_sectionfront_articles:$limit), false, $groupSectionIds);
							
							$sectionFrontArticles = $this->assignImageAndVideoForArticles($sectionFrontArticles, $smugmugURL);
							
				    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    			$this->cache->save($sectionFrontArticles, $this->environment."sectionfrontarticles{$section_id}_cms_".$this->siteid, array("article_cms_".$this->siteid), ARTICLE_CACHE);
				    	}
				    	$this->view->sectionFrontArticles = $sectionFrontArticles;
				    	$this->view->sectionFrontArticlesTpl = $this->view->render("uc-sectionfront-articles.tpl");
			    	}
	    	
			    	/*if(!($articlesInSections = $this->cache->load($this->environment."area_".$this->area["area_id"]."_cms_".$this->siteid))) {
				    	$sections = $section->getAreaSections($this->area["area_id"]);
				    	$articlesInSections = array();
				    	if(count($sections) > 0) {
				    		foreach ($sections as $sec) {
				    			$articles= $cac->getArticlesBySection($sec["section_id"], 0, 5);
				    			$articlesInSections[] = array(
				    				"area_name"=> $sec["section_name"],
				    				"articles" => $articles['articles']
				    			);
				    		}
				    	}
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($articlesInAreas, $this->environment."area_".$this->area["area_id"]."_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	}
			    	$this->view->articlesInAreas = $articlesInSections;*/
			    	
			    	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction, date("Y"))); 
			    	$yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction-1, date("Y")));
			    	$startweek = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction-7, date("Y")));
			    	$startmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d")-$this->config->general->dayreduction, date("Y")));
			    
			    	if(!in_array($this->siteid, array(10, 11, 7, 12 ))) {
				    	if(!($topReadToday = $this->cache->load($this->environment."topreadtoday_".$section_id."_cms_".$this->siteid))) {
					    	$this->view->topReadToday = $cac->getTopReadStoriesBySection($this->config->general->siteid, $section_id, 5, $today);
					    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
					    		$this->cache->save($topReadToday, $this->environment."topreadtoday_".$this->area["area_id"]."_cms_".$this->siteid, array("article_cms_".$this->siteid));
				    	}
				    	if(!($topReadYesterday = $this->cache->load($this->environment."topReadYesterday_".$section_id."_cms_".$this->siteid))) {
					    	$this->view->topReadYesterday = $cac->getTopReadStoriesBySection($this->config->general->siteid, $section_id, 5, $yesterday);
					    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
					    		$this->cache->save($topReadYesterday, $this->environment."topReadYesterday_".$section_id."_cms_".$this->siteid, array("article_cms_".$this->siteid));
				    	}
				    	if(!($topReadPassWeek = $this->cache->load($this->environment."topReadPassWeek_".$section_id."_cms_".$this->siteid))) {
					    	$this->view->topReadPassWeek = $cac->getTopReadStoriesBySection($this->config->general->siteid, $section_id, 5, $startweek, $today);
					    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
					    		$this->cache->save($topReadPassWeek, $this->environment."topReadPassWeek_".$section_id."_cms_".$this->siteid, array("article_cms_".$this->siteid));
				    	}
				    	if(!($topReadPassMonth = $this->cache->load($this->environment."topReadPassMonth_".$section_id."_cms_".$this->siteid))) {
					    	$this->view->topReadPassMonth = $cac->getTopReadStoriesBySection($this->config->general->siteid, $section_id, 5, $startmonth, $today);
					    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
					    		$this->cache->save($topReadPassMonth, $this->environment."topReadPassMonth_".$section_id."_cms_".$this->siteid, array("article_cms_".$this->siteid));
				    	}
			    	}
			    	
			    	$this->view->title = htmlentities(stripslashes($csa["custom_title"]), ENT_QUOTES);
			    	$this->view->keywords = htmlentities(stripslashes($csa["custom_title"]), ENT_QUOTES);
			    	$this->view->slideshows_section = $this->view->render("slideshows_section.tpl");
			    	$this->view->latest_news_section = $this->view->render("latest_news_section.tpl");
			    	$this->view->photo_galleries_section = $this->view->render("photo_galleries_section.tpl");
			    	$this->view->special_sections_section = $this->view->render("special_sections_section.tpl");
			    	$this->view->apVideo = $this->view->render("apvideo.tpl");
			    	$this->view->coming_up_events_section = $this->view->render("coming_up_events_section.tpl");
			    	$this->view->event_calendar_section = $this->view->render("event_calendar_section.tpl");
			    	$this->view->top_read_stories_section = $this->view->render("top_read_stories_section.tpl");
			    	$this->view->polls_section = $this->view->render("polls_section.tpl");
					
					if(in_array($this->siteid, array(7777,9))) {
						$this->view->facebookLike = $this->view->render("facebook_like.tpl");
							
						if(!empty($this->config->video->total_videos_on_homepage))
						{
							$this->view->latestYoutube = $this->db->fetchAll("SELECT i.youtube_id, cg.content_gallery
								FROM content_gallery cg
								LEFT JOIN content_gallery_images cgi ON cgi.`content_gallery_id`=cg.`content_gallery_id`
								LEFT JOIN images i ON i.source_id=cgi.`content_images_id`
								WHERE cg.site_id={$this->siteid} AND cg.content_gallery_type_id=2 AND youtube_id IS NOT NULL
								ORDER BY cg.content_gallery_id DESC, i.`image_id` DESC
								LIMIT {$this->config->video->total_videos_on_homepage}
							");
							$this->view->youtubeVideos = $this->view->render("youtube_videos.tpl");
						}
					}
					else {
						if(!empty($this->config->cms->marketplace_siteid))
				    	{
					    	$onlineads = $this->loadModel('onlineads');
					    	$marketplaceSections = $onlineads->getMarketplaceSection($this->config->cms->marketplace_siteid);
					    	foreach ($marketplaceSections as &$mktpSections) {
					    		$mktpSections['latestAds'] = $onlineads->get5LatestAds($this->config->cms->marketplace_siteid, $mktpSections['section_id']);
					    	}
					    	$this->view->marketplaceSections = $marketplaceSections;
					    	$this->view->marketplace_url = $this->config->cms->marketplace_url;
					    	$this->view->marketplace_section = $this->view->render("marketplace_section.tpl");
				    	}
					}
					
					/*** Trending Stories ***/
			    	/*if($this->siteid == '11')
			    	{
			    		$trendingStories = $cac->getTrendingArticles($this->config->general->siteid, '12');
						$trendingStories = $this->assignImageAndVideoForArticles($trendingStories, $smugmugURL);
			    		$this->view->trendingStories = $trendingStories;
			    		$this->view->trendingStoriesTpl = $this->view->render("trending_stories.tpl");
			    	}*/
					
					if(!($contest = $this->cache->load($this->environment."contest_cms_".$this->siteid))) {
			    		$contestClass = $this->loadModel('contest');
						$contest = $contestClass->getContest($this->siteid);
						
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($contest, $this->environment."contest_cms_".$this->siteid, array("contest_cms_".$this->siteid), BANNER_CACHE);
			    	}
			    	$this->view->contest = $contest;
			    	
			    	if(in_array($this->siteid, array(10,11, 7 ))) {
			    		if(!($bibleVerses = $this->cache->load($this->environment."bibleverse_".$this->siteid))) {
				    		$bibleVerses = $cac->getBibleVerses($this->config->general->siteid, $this->config->cms->bible->home_count_limit);
				    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    			$this->cache->save($bibleVerses, $this->environment."bibleverse_".$this->siteid, array("article_cms_".$this->siteid), BANNER_CACHE);
				    	}
				    	$this->view->bibleVerses = $bibleVerses;
			    	}
			    	
			    	if(in_array($this->siteid, array(10,11, 7, 12 ))) {
			    		$this->view->sidebar = $this->view->render("sidebar.tpl");
			    	}
			    	
			    	$this->view->area_id = $csa['area_id'];
			    	$this->view->content_section_area_group_id = $csa['content_section_area_group_id'];
			    	
			    	$sectionfrontlookandfeelClass = $this->loadModel('sectionfrontlookandfeel');
			    	$this->view->sectionFrontLookandFeel = $sectionfrontlookandfeelClass->getSectionFrontLookandFeelByCsaId($csa_id);
					
			    	if($this->siteid==12) {
			    		$author = $csa['custom_title'];
			    		$blogger = $this->db->fetchRow("SELECT * FROM bloggers WHERE custom_page_id=57 AND site_id='{$this->siteid}' AND category='Outdoor-{$author}'");
			    		$this->view->blogger = $blogger;
			    	}
			    	
					$this->renderTemplate('area.tpl');    	
    				break;
    		case 6:
    			$csaId = intval($params['csa_id']);
    			$sectionInfo = $this->db->fetchRow("
    			SELECT csa.section_id, cs.section_name
    			FROM content_section_area csa
    			LEFT JOIN content_sections cs ON cs.section_id=csa.section_id
    			WHERE csa.content_section_area_id='{$csaId}' AND csa.site_id='{$this->siteid}'
    			");
    			if(!empty($sectionInfo))
    				$_GET['section'] = $sectionInfo['section_name']."-".$sectionInfo['section_id'];
				$this->view->section_id = $sectionInfo['section_id'];
    			require_once("IndexController.php");
    			IndexController::sectionAction();
    			break;
    		case 7:	
    			$params = $this->_getAllParams();
		    	$params["start"]  = intval($params["start"]);
    	
    			$csa_id = $this->_request->getParam("csa_id");
    			$content = $this->loadModel('content');
    			$this->view->csa = $csa = $content->getContentSectionArea($csa_id);
    			
    			$this->view->section_id = $section_id = $csa['section_id'];
    			$this->view->area_id = $csa['area_id'];
    			$this->view->content_section_area_id=$csa['content_section_area_id'];
    			$this->view->content_section_area_group_id = $csa['content_section_area_group_id'];
    				
    			if(!($this->banners1 = $this->cache->load($this->environment."banners_".$section_id."_cms_".$this->siteid))) {
					$bannerClass = $this->loadModel("banner");
					$this->banners1 = $bannerClass->getBanners($this->siteid, $section_id);
					if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
						$this->cache->save($this->banners1, $this->environment."banners_".$this->section["section_id"]."_cms_".$this->siteid, array("banners_cms_".$this->siteid));
				}
				if(empty($this->banners1["top"])) $this->banners1["top"] = $this->banners["top"];
				if(empty($this->banners1["right"])) $this->banners1["right"] = $this->banners["right"];
				$this->view->banners = $this->banners1;
				$this->view->rightSideBanner = $this->view->render("rightSideBanner.tpl");
				$this->view->topBanner = $this->view->render("topBanner.tpl");
				
			    if(in_array($this->siteid, array(7,10,11, 12))) {
			    	$this->view->sidebar = $this->view->render("sidebar.tpl");
			    }
			    
			    //$csa['custom_title'] -- $csa['section_id']
			    
			    $this->view->title = $this->view->currentTeam = $csa['custom_title'];
			    
			    $endDate = date("Y-m-d H:i:s");
			    //$startDate = date("Y-m-d H:i:s", mktime(0, 0, 0, date("n"), date("j"), date("Y")-1));
			    $startDate = date("Y-m-d H:i:s", mktime(0, 0, 0, 7, 1, date("Y")-1));
			    $this->view->startDate = $startDate;
			    $this->view->endDate = $endDate;
			    $schoolName = addslashes(stripslashes($csa['custom_title']));
			    
				$this->view->games = $this->db->fetchAll("SELECT g.game_id, g.site_id, g.sport_id, g.game_date, hs.school_name AS home_team_name, vs.school_name AS visitor_team_name,s.sport_name,
					g.period, g.location, g.location_address, g.modified_date, g.modified_by, g.game_story_url, g.game_header, g.visitor_team_id, g.home_team_id,
					g.is_game_of_the_week, g.is_playoffs_game, g.home_team_score, g.visitor_team_score, g.game_type_order,
					hs.school_image AS home_team_image,
					vs.school_image AS visitor_team_image
				FROM games g 
				LEFT JOIN sports s ON s.sport_id=g.sport_id
				LEFT JOIN schools hs ON hs.school_id=g.home_team_id
				LEFT JOIN schools vs ON vs.school_id=g.visitor_team_id
			    WHERE g.site_id='{$this->siteid}' AND g.game_date >= '{$startDate}' AND (hs.school_name='{$schoolName}' || vs.school_name='{$schoolName}')
			    ORDER BY CASE WHEN MONTH(game_date)<7 THEN YEAR(game_date) ELSE YEAR(game_date)+1 END DESC, game_date"); //ORDER BY gc.order_id, g.sport_id, g.district, g.division, game_date //g.game_type_order,

		    	if(!empty($csa['gallery_keywords'])) {
		    		$slideshowImages = array();
		    		if(!($slideshowImages = $this->cache->load($this->environment."csaslideshow_".$csa["content_section_area_id"]."_cms_".$this->siteid))) {
						$cgc = $this->loadModel('contentgallery');
						
						$slideshowImages = $cgc->getRelatedGalleriesByKeywords($csa['gallery_keywords']);
						
						if(!empty($this->config->gallery->use_smugmug) && is_array($slideshowImages) && !empty($slideshowImages)) {
							$smugmugDomain = "";
							if(!empty($this->config->smugmug->url)) $smugmugDomain = $this->config->smugmug->url;
							else {
								$libPath = dirname(dirname(dirname(dirname(__FILE__))));
						    	$libPath = str_replace("\\", "/", $libPath);
						    	$libPath = rtrim($libPath, '/');
						    	$libPath .= '/lib';
						    	require_once($libPath."/phpSmug.php");
						    	$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
						    	try {
						    		$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );	
						    		$smugmugDomain = $f->parsed_response['Login']['User']['URL'];
						    	}
						    	catch (Exception $ex) { }
							}
					    }	
				    	foreach ($slideshowImages as $key=>$image) {
				    		if(!empty($this->config->gallery->use_smugmug) && $image['content_gallery_type_id']==1) {
				    			$slideshowImages[$key]['thumb_image_url'] = $smugmugDomain."/photos/i-".$image['image_smugmug_key']."/0/Th/i-".$image['image_smugmug_key']."-Th.jpg";
				    		}
				    		else if($image['content_gallery_type_id']==1) {
				    			$slideshowImages[$key]['thumb_image_url'] = $this->baseUrl."/images/article_photos/".$image["source_system_id"];
				    		}
				    		else if($image['content_gallery_type_id']==2) {
				    			$slideshowImages[$key]['thumb_image_url'] = "https://img.youtube.com/vi/".$image['youtube_id']."/0.jpg";
				    		}
				    	}
						
						if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
							$this->cache->save($slideshowImages, $this->environment."csaslideshow_".$csa["content_section_area_id"]."_cms_".$this->siteid, array("csaslideshow_".$this->siteid), GALLERY_WITH_VIEW_COUNT_CACHE);
					}
		    		
		    		$this->view->slideshowImages = $slideshowImages;   		    		
		    		
		    	}
		    	
		    	$section = $this->loadModel('section');
		    	$this->section = $section->getSection($section_id);
		    	$cac = $this->loadModel('contentarticle');
				if(empty($pagesize)) $pagesize = ((empty($this->config->general->page_size))?10:$this->config->general->page_size);
				
				if(!($articles = $this->cache->load($this->environment."sarticles_".$this->section["section_id"]."_".$params["start"]."_cms_".$this->siteid))) {
					$articles= $cac->getArticlesBySection($this->section["section_id"], $params["start"], $pagesize);
					if( (empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1) && empty($params['start']) ) //only store first page
						$this->cache->save($articles, $this->environment."sarticles_".$this->section["section_id"]."_".$params["start"]."_cms_".$this->siteid, array("articles_cms_".$this->siteid), ARTICLE_CACHE);
				}
				$articles['articles'] = $this->assignImageAndVideoForArticles($articles['articles'], $smugmugURL);
				$this->view->articles = $articles;
				$this->view->pagingData = $this->generatePagingData($this->baseUrl."/article/template/id/7/csa_id/{$params["csa_id"]}", $params["start"], $pagesize, 10, $articles["count"]);
				$this->view->pageHelper = $this->view->render("paging.tpl");
			    
    			$this->renderTemplate("score_page.tpl");
    			break;
    		case 8:
    			
    			$csa_id = 933;
    			$content = $this->loadModel('content');
    			$this->view->csa = $csa = $content->getContentSectionArea($csa_id);
    			$this->view->section_id = $section_id = $csa['section_id'];
    			$this->view->area_id = $csa['area_id'];
    			$this->view->content_section_area_id=$csa['content_section_area_id'];
    			$this->view->content_section_area_group_id = $csa['content_section_area_group_id'];
    			
    			$this->view->title = $csa['custom_title'];
    			
    			if(!($feed = $this->cache->load($this->environment."kytxfeed_cms_".$this->siteid))) {
	    			$rss = new DOMDocument();
				    $rss->load("http://etxnetwork.wp.icblivetv.com/feed/");
				    $feed = array();
				    foreach ($rss->getElementsByTagName('item') as $node) {
				        $item = array (
			                'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
			                'link' => $node->getElementsByTagName('link')->item(0)->nodeValue,
			                'pubDate' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
			                'description' => $node->getElementsByTagName('description')->item(0)->nodeValue,
			                'content' => $node->getElementsByTagName('encoded')->item(0)->nodeValue
			            );
				        array_push($feed, $item);
				    }		
					if( (empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)  )
						$this->cache->save($feed, $this->environment."kytxfeed_cms_".$this->siteid, array("kytx_cms_".$this->siteid), BANNER_CACHE);
				}
    			$this->view->feed = $feed[0];
    			
    			$videoList = "";
    			if(!($videoList = $this->cache->load($this->environment."kytxvideolist_cms_".$this->siteid))) {
	    			$kytxHomeContent = file_get_contents("http://etxnetwork.wp.icblivetv.com");
	    			preg_match('/<p><div class="videos-shortcode">.*?<\/div><\/p>/si',$kytxHomeContent, $matches);
	    			if(!empty($matches[0])) $videoList = $matches[0];
	    			$videoList = str_replace('<p><div class="videos-shortcode">', '<div class="videos-shortcode">', $videoList);
	    			$videoList = substr($videoList, 0, strlen($videoList)-4);
	    			
					if( (empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)  )
						$this->cache->save($videoList, $this->environment."kytxvideolist_cms_".$this->siteid, array("kytx_videolist_cms_".$this->siteid), BANNER_CACHE);
				}
    			$this->view->videoListString = $videoList;
    			
    			$this->view->sidebar = $this->view->render("sidebar.tpl");
    			
    			$this->renderTemplate("etfinalscore_kytx.tpl");
    			break;
    		case 9:
    			$params = $this->_getAllParams();
				if(in_array($this->siteid, array(13, 14, 15 ))) {
					$cac = $this->loadModel('contentarticle');
					
					if(!empty($this->config->cms->days_to_show_recent_videos)) $days_to_show_recent_videos = $this->config->cms->days_to_show_recent_videos;
					else $days_to_show_recent_videos = 0;
					
					if(!($listVideos = $this->cache->load($this->environment."listvideos".$this->siteid))) {
			    		$listVideos = $cac->getHomeLatestVideos($this->config->general->siteid, $this->config->cms->total_recent_videos, $days_to_show_recent_videos);
			    		$listVideos = $this->assignImageAndVideoForArticles($listVideos, $smugmugURL);
			    		if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    			$this->cache->save($listVideos, $this->environment."listvideos".$this->siteid, array("article_cms_".$this->siteid), BANNER_CACHE);
			    	}
					$this->view->title = "Video Gallery";
			    	$this->view->listVideos = $listVideos;
				}
				else
				{
					if(!empty($this->config->video->use_youtube)) {
						if(empty($params['start']))
							$start = 1;
						else
							$start = $params['start']+1;
							
						$xmlstring = file_get_contents("https://gdata.youtube.com/feeds/api/users/".$this->config->youtube->youtube_user_id."/uploads?start-index=".$start."&max-results=".$this->config->video->total_video_per_page_on_gallery); 
						$sxml = simplexml_load_string($xmlstring);
						$counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
						$total_vid = $counts->totalResults;
				
						$xml = json_decode(json_encode((array) $sxml), 1);
						$i = 0;
						foreach ($xml['entry'] as $vid)
						{
							$arr_id = explode('/',$vid['id']);
							$id = $arr_id[count($arr_id)-1];
							$listVideos[$i]['id'] = $id;
							$listVideos[$i]['thumb'] = "https://img.youtube.com/vi/".$id."/0.jpg";
							$listVideos[$i]['title'] = $vid['title'];
							if($id == $params['id'])
							{
								$showVid['id'] = $id;
								$showVid['title'] = $vid['title'];
							}
							$i++;
						}
						$this->view->listVideos = $listVideos;
						
						if(empty($showVid))
						{
							$showVid['id'] = $listVideos[0]['id'];
							$showVid['title'] = $listVideos[0]['title'];
						}
							
						$this->view->area_id = $areaId;
						$this->view->start = $start-1;	
						$this->view->pagesize = $this->config->video->total_video_per_page_on_gallery;
						
						$this->view->pagingData = $this->generatePagingData($this->baseUrl."/index/area/area_id/".$areaId."/area_name/".$this->area["area_name"]."/id/0", $start-1, $this->config->video->total_video_per_page_on_gallery, 10, $total_vid);
						$this->view->pageHelper = $this->view->render("paging.tpl");
				
						$this->area["area_name"] = $showVid['title'];	
						
						$this->view->showVid = $showVid;
						$this->view->useYoutube = 1;
					}
					else if(!empty($this->config->video->use_brightcove)) {
						$pageSize = $this->config->video->total_video_per_page_on_gallery;
						$pageSize = intval($pageSize);
						if(empty($pageSize)) $pageSize = 30;
						$token = $this->config->video->brightcove_api_token;
						$tag = $this->config->video->brightcove_tag;
						$this->view->origstart = $params['start'];
						if(empty($params['start']))
							$start = 1;
						else
							$start = $params['start']/$pageSize+1;
						$start--;
						$jsonData = file_get_contents("http://api.brightcove.com/services/library?command=search_videos&output=json&all=tag:{$tag}&token={$token}&sort_by=PUBLISH_DATE:DESC&exact=true&page_size={$pageSize}&page_number={$start}&get_item_count=true");
						$start++; 
						$jsonData = json_decode($jsonData);
						$videos = $jsonData->items;
						
						$total_vid = (int)$jsonData->total_count;
				
						$listVideos = array();
						foreach ($videos as $i=>$vid)
						{
							$id = (string) $vid->id;
							$title = (string) $vid->name; 
							$listVideos[$i]['id'] = $id;
							$listVideos[$i]['thumb'] = (string) $vid->thumbnailURL;
							$listVideos[$i]['title'] = $title;
							if($id == $params['id'])
							{
								$showVid['id'] = $id;
								$showVid['title'] = $title;
							}
						}
						$this->view->listVideos = $listVideos;
						
						if(empty($showVid))
						{
							$showVid['id'] = $listVideos[0]['id'];
							$showVid['title'] = $listVideos[0]['title'];
						}
						
						$this->view->area_id = $areaId;
						$this->view->start = $start-1;	
						$this->view->pagesize = $this->config->video->total_video_per_page_on_gallery;
						
						$this->view->pagingData = $this->generatePagingData($this->baseUrl."/index/area/area_id/".$areaId."/area_name/".$this->area["area_name"]."/id/0", (int) $params['start'], $pageSize, 10, $total_vid);
						$this->view->pageHelper = $this->view->render("paging.tpl");
				
						$this->area["area_name"] = $showVid['title'];	
						
						$this->view->showVid = $showVid;
						$this->view->useBrightCove = 1;
					}
				}
    			$this->renderTemplate('video_gallery.tpl');    	
    			break;
				
					
    		case 10: // displaying events using calendar format
    			$firstDateOfSelectedMonth = date("Y-m-")."01";
    			if(!empty($params['date'])) $firstDateOfSelectedMonth = date("Y-m-", strtotime($params['date']."-01"))."01";
    			
    			$this->createCaptcha();
    			
    			$timeStamp = strtotime($firstDateOfSelectedMonth);
    			
    			$eventClass = $this->loadModel('events');
    				
    			if(!($this->calendarEvents = $this->cache->load($this->environment.$timeStamp."_calendarevents_cms_".$this->siteid))) {
			    	$events = $eventClass->getEventsByMonth($this->siteid, $firstDateOfSelectedMonth);
			    	
			    	$this->calendarEvents = array();
			    	if(is_array($events)) foreach ($events as $event) {
			    		if(empty($this->calendarEvents[$event['event_date']])) $this->calendarEvents[$event['event_date']] = array();
			    		if(!empty($event['image'])) $event['image'] = $this->config->paths->url . "images/events/".$event['image']; 
			    		$this->calendarEvents[$event['event_date']][] = $event;
			    	}
			    	
			    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
			    		$this->cache->save($this->calendarEvents, $this->environment.$timeStamp."_calendarevents_cms_".$this->siteid, array("calendarevents_cms_".$this->siteid));
			    }
			    $this->view->events = $this->calendarEvents;
    				
    			$this->view->next = date("Y-m", strtotime($firstDateOfSelectedMonth) + 35 * 3600 * 24);
    			$this->view->prev = date("Y-m", strtotime($firstDateOfSelectedMonth) - 3600 * 24);
    			
    			$this->view->allowAnonymousEventSubmission = ((!empty($this->config->cms->events->allow_submission_without_login))?true:false);
    			
    			if(!empty($this->session->eventSubmissionMsg)) {
    				$this->view->eventSubmissionMsg = $this->session->eventSubmissionMsg;
    				unset($this->session->eventSubmissionMsg);
    			}
    			$this->view->firstDateOfMonth = $firstDateOfSelectedMonth;
    			$this->view->title = "Event Calendar For ".date("F Y", strtotime($firstDateOfSelectedMonth));
    			$this->view->displaySubmitEvent = $params['submit'];
    			$this->renderTemplate("event_calendar_v1.tpl");
    			break;
    			
			case 11:
    			$params = $this->_getAllParams();
				$this->view->title = "Multimedia";
				$cg = $this->loadModel('contentgallery');
		    	$albums = $cg->getContentGalleryThumbnails($this->siteid, '1',0,50);
				if($albums['count'] >0)
				{
					if(!empty($this->config->gallery->use_smugmug)) {
						if(!empty($this->config->smugmug->url)) $smugmugURL = $this->config->smugmug->url;
						else {
							$libPath = dirname(dirname(dirname(dirname(__FILE__))));
							$libPath = str_replace("\\", "/", $libPath);
							$libPath = rtrim($libPath, '/');
							$libPath .= '/lib';
							require_once($libPath."/phpSmug.php");
							$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
							try {			
								$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );
								$smugmugURL = $f->parsed_response['Login']['User']['URL'];
							} catch (Exception $ex) {}
						}
					}
					foreach($albums['data'] as &$data)
					{
						if(!empty($this->config->gallery->use_smugmug) && !empty($data['smugmug_key'])) {
							$data['image_url'] =$smugmugURL."/photos/i-".$data['smugmug_key']."/0/S/i-".$data['smugmug_key']."-Th.jpg";
							$data['medium_image_url'] =$smugmugURL."/photos/i-".$data['smugmug_key']."/0/M/i-".$data['smugmug_key']."-M.jpg";
						}
					}
				}
				$this->view->albums = $albums;
    			$this->renderTemplate('photo_album.tpl');    	
    			break;
    	}
    }
    
    function gettopreadstoriesAction()
    {
    	$params = $this->_getAllParams();
    	
    	if (!is_numeric($params['id']) || $params['id'] < 1 || $params['id'] > 4)
		{
			throw new Falcon_Content_Exception('Invalid parameter in gettopreadstoriesAction',EXCEPTION_INVALID_PARAMETERS);
		}
    		
    	$cac = $this->loadModel('contentarticle');
    	
    	$today = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction, date("Y")));
    
    	switch ($params['id'])
    	{
    		case 1: if(!($topReadToday = $this->cache->load($this->environment."topreadtoday_cms_".$this->siteid))) {
				    	$topReadToday = $cac->getTopReadStories($this->config->general->siteid, 50, $today);
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($topReadToday, $this->environment."topreadtoday_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	} 
			    	$this->view->topreadstories = $topReadToday;
			    	break;
			    
    		case 2: $yesterday = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction-1, date("Y")));
    				if(!($topReadYesterday = $this->cache->load($this->environment."topReadYesterday_cms_".$this->siteid))) {
				    	$topReadYesterday = $cac->getTopReadStories($this->config->general->siteid, 50, $yesterday);
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($topReadYesterday, $this->environment."topReadYesterday_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	}
			    	$this->view->topreadstories = $topReadYesterday;
			    	break;
			    	
    		case 3: $startweek = date("Y-m-d", mktime(0, 0, 0, date("m"), date("d")-$this->config->general->dayreduction-7, date("Y")));
    				if(!($topReadPassWeek = $this->cache->load($this->environment."topReadPassWeek_cms_".$this->siteid))) {
				    	$topReadPassWeek = $cac->getTopReadStories($this->config->general->siteid, 50, $startweek, $today);
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($topReadPassWeek, $this->environment."topReadPassWeek_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	}
    				$this->view->topreadstories = $topReadPassWeek;
    				break;
    				
    		case 4: $startmonth = date("Y-m-d", mktime(0, 0, 0, date("m")-1, date("d")-$this->config->general->dayreduction, date("Y")));
    				if(!($topReadPassMonth2 = $this->cache->load($this->environment."topReadPassMonth2_cms_".$this->siteid))) {
				    	$topReadPassMonth2 = $cac->getTopReadStories($this->config->general->siteid, 50, $startmonth, $today);
				    	if( empty($this->config->general->enable_cache) || $this->config->general->enable_cache == 1)
				    		$this->cache->save($topReadPassMonth2, $this->environment."topReadPassMonth2_cms_".$this->siteid, array("article_cms_".$this->siteid));
			    	}
			    	$this->view->topreadstories = $topReadPassMonth2;
    				break;
    	}
    	
    	//print_r($this->view->topreadstories); exit();
    	//echo $this->view->render("top_read_stories_tab.tpl");
    	$output = $this->view->render("top_read_stories_tab.tpl");
		$output = $this->reformatQuery($output);
		
		ob_start();
		echo $output;
		ob_end_flush();
    }

	public function submitstoryAction()
	{			
		if(!empty($this->session->formData)) {
			$this->view->formData = $this->session->formData;
			unset($this->session->formData);
		}
		if(empty($this->view->formData['state']) && $this->siteid==11) $this->view->formData['state'] = 'Texas';
		if(!empty($this->session->errors)) {
			$this->view->errors = $this->session->errors;
			unset($this->session->errors);
		}
		
		$this->createCaptcha();
		
		$this->view->title = "Tell us your story";
		$this->renderTemplate('submit-story.tpl');    	
	}
	
	function generateRandomPassword($length=10) {
		$pool = '23456789abcdefghjkmnpqrstuvwxyz';
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($pool, mt_rand(0, strlen($pool) -1), 1);
        }
        return $str;
	}
	
	public function getcaptchaAction() {
		$captchaCode = $this->generateRandomPassword(6);
		$captchaCode = strtoupper($captchaCode);
		$this->session->ssCaptchaCode = $captchaCode;
		$font_path = dirname(__FILE__)."/Karla.ttf";
		$use_font = ($font_path != '' && file_exists($font_path) AND function_exists('imagettftext')) ? TRUE : FALSE;
		$im = imagecreate(100, 40);
		$bg_color = imagecolorallocate($im, 230, 230, 230);
		$text_color = imagecolorallocate($im, 55, 55, 55);
		imagefilledrectangle($im, 0, 0, 100, 30, $bg_color);
        if ($use_font == FALSE) {
            imagestring($im, 15, 12, 10, $captchaCode, $text_color);
        }
        else {
        	for ($i = 0; $i < strlen($captchaCode); $i++) {
        		$rnd = rand(0, 10000);
        		$rnd = $rnd%15;
        		$randomColor = rand(50, 130);
        		$text_color = imagecolorallocate($im, $randomColor, $randomColor, $randomColor);
            	imagettftext($im, 15, 5, 10+$i*14, 20+$rnd, $text_color, $font_path, substr($captchaCode, $i, 1));
        	}
        }
        
        header("Content-type: image/png");
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
	    imagejpeg($im);
	    imagedestroy($im);
	}
	
	public function dosubmitstoryAction() {
		$data = $this->_request->getParams();
		if(is_array($data)) foreach ($data as $key=>$val) $data[$key] = trim($val);
		$this->session->formData = $data;
		$errors = array();
		if (strtolower($data['captcha']) != strtolower($this->session->captchaWord)) {
			$errors["captcha"] = "Please provide correct verification code";
		}
		if(empty($data['first_name'])) {
			$errors['first_name'] = "Please include first name";	
		}
		if(empty($data['last_name'])) {
			$errors['last_name'] = "Please include last name";	
		}
		if(empty($data['city'])) {
			$errors['city'] = "Please include city";	
		}
		if(empty($data['state'])) {
			$errors['state'] = "Please include state";	
		}
		if(empty($data['zip'])) {
			$errors['zip'] = "Please include ZIP";	
		}
		if(empty($data['email'])) {
			$errors['email'] = "Please include email";	
		}
		if(empty($data['phone'])) {
			$errors['phone'] = "Please include phone";	
		}
		if(empty($data['summary']) || strlen($data['summary']) < 10) {
			$errors['summary'] = "Please include Summary";	
		}
		if(!empty($_FILES['photo']['name'])) {
			$fileName=$_FILES["photo"]["name"];
			$fileName = urldecode($fileName);
			$temps = explode(".",$fileName);
			$ext = $temps[count($temps)-1];
			$pureFileName = str_ireplace(".{$ext}", "", $fileName);
			$ext = strtolower($ext);
			if (!in_array($ext, array("jpg", "jpeg", "gif", "png")) || substr($_FILES['photo']['type'], 0, 5) != 'image') {
				$errors['photo'] = "Only photo file (.jpeg, .jpg, .gif or .png) is allowed";
			}
		}
		if(!empty($errors)) $this->session->errors = $errors;
		else unset($this->session->errors);
		if(!empty($errors)) {
			header("Location: /article/submitstory");
			exit();
		}
		
		require_once 'Zend/Mail.php';
		require_once 'Zend/Mime.php';
		$mail = new Zend_Mail();
		$mail->setType(Zend_Mime::MULTIPART_MIXED);
		
		$imageId = "im-".time();
		if(!empty($_FILES['photo']['tmp_name'])) {			
			$at = $mail->createAttachment(@file_get_contents($_FILES['photo']['tmp_name']));
			$at->type        = $_FILES["photo"]["type"];
			$at->disposition = Zend_Mime::DISPOSITION_INLINE;
			$at->encoding    = Zend_Mime::ENCODING_BASE64;
			$at->filename    = $_FILES["photo"]["name"];
			$at->id			 = $imageId;
			$this->view->imageId = $imageId;
		}
		
		$data['ip'] = $_SERVER['REMOTE_ADDR'];
		$this->view->formData = $data;
		$bodyContent = $this->view->render("mail-tell-your-story.tpl");
		
		$mail->setBodyHtml($bodyContent);
		
		$mail->setFrom("onlineads@apthosts.com");
		
		if($this->siteid==11) $mail->addTo("news@tylerpaper.com"); //$mail->addTo("storysubmit@tylerpaper.com");
		else $mail->addTo("jhoni_chen@yahoo.com");
		$mail->addBcc("jhoni_chen@yahoo.com");
		
		$mail->setSubject('Tell us your story - '.$_SERVER['SERVER_NAME']);
		
		try {
			$mail->send();
		}
		catch (Exception  $ex) {
		}
		
		unset($this->session->formData);
		$this->session->errors['success'] = "Thank you for submitting your story";
		
		header("Location: /article/submitstory");
		exit();
		
	}
	
	function getarticlesbysectionAction() {
		$section_id = $this->_request->getParam('section_id');
		$sort = $this->_request->getParam('sort');
		$limit = $this->_request->getParam('limit');
		$title = $this->_request->getParam('title');
		$pub_date_within_hours = $this->_request->getParam('time');
		$limit = intval($limit);
		if(empty($limit)) $limit = 5;
		
		$smugmugURL = "";
		if(!empty($this->config->gallery->use_smugmug)) {
			if(!empty($this->config->smugmug->url)) $smugmugURL = $this->config->smugmug->url;
			else {
				$libPath = dirname(dirname(dirname(dirname(__FILE__))));
				$libPath = str_replace("\\", "/", $libPath);
				$libPath = rtrim($libPath, '/');
				$libPath .= '/lib';
				require_once($libPath."/phpSmug.php");
				$f = new phpSmug( "APIKey=".$this->config->smugmug->api_key, "AppName=".$this->config->smugmug->app_name );
				try {
					$f->login( "EmailAddress=".$this->config->smugmug->email, "Password=".$this->config->smugmug->password );
					$smugmugURL = $f->parsed_response['Login']['User']['URL'];
				} catch (Exception $ex) {}
			}
			$this->view->smugmugURL = $smugmugURL;
		}
		
		$cac = $this->loadModel('contentarticle');
		$articles = $cac->getArticlesBySectionCustomSort($section_id, $sort, $limit, false, $pub_date_within_hours);
		$articles = $this->assignImageAndVideoForArticles($articles, $smugmugURL);
		
		$this->view->articles = $articles;
		$this->view->title = $title;
		
		ob_start();
		echo $this->view->render('articles_by_section.php');
		$output = ob_get_contents();
		ob_end_clean();
		
		$output = $this->reformatQuery($output);
		
		echo $output;
	}
	
	/*
	 * This function will update article stats for all sites, so this is not site specific
	 *
	*/
	function updatearticlestatsAction() {
		$contentArticle = $this->loadModel('contentarticle');
		$maxStatId = $contentArticle->updateArticleStats();
		echo 'Processed at Max Stat Id: '.$maxStatId;
	}
}

?>