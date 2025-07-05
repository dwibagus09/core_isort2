<?php
class actionControllerBase extends Zend_Controller_Action 
{

	public $config; //global configuration
    public $db; //databse object
    public $session; //session object
    public $view; //view object
    public $dbLogger;
    
    public $site_id;
    
    public $privileges = array();
    public $environment;
    
    public function init()
	{
		
		//populate variables with controller and action
		$ctlname = $this->_request->getParam('controller');
		$actname = $this->_request->getParam('action');
		
		//load objects instatiated in bootstrap file
		$this->db = Zend_Registry::get('db');
		$this->auth = Zend_Registry::get('auth');
		$this->dbLogger = Zend_Registry::get('dbLogger');
		$this->config = Zend_Registry::get('config');

		
		// require_once 'Zend/Cache.php';
        // $frontendOptions = array('lifetime' => 3600, 'automatic_serialization' => true);
        // $backendOptions = array('servers' => array(array('host' => 'localhost','port' => 11211, 'persistent' => true)));
		// $this->cache = Zend_Cache::factory('Output', 'Memcached', $frontendOptions, $backendOptions);
		
		//setup some other standard variables
		$this->modelDir =  $this->config->paths->application . '/models/'; 

		//setup the view
		require_once('Zend/View.php');
		$this->view = new Zend_View();
		$this->viewDir = $viewDir = $this->config->paths->application . '/views';
		$this->view->addScriptPath($viewDir);
		Zend_Registry::set('view', $this->view);
		//Check auth status and session expiration only if the user is not logging in/out.
		$ident = $this->auth->getIdentity();
			
		//expired session or bad login			
		if ($ident == FALSE && isset($_COOKIE[$this->config->session->name]) && $actname!='login' && $this->_request->getParam('action') != "backupdb" && $this->_request->getParam('action') != "sendbackupdb") {
			//TODO - really need to come up with the "right" way to test for session exipration
			echo $this->view->render('login.php');
			exit;
		}
		//new request
		elseif($ident == FALSE && !isset($_COOKIE[$this->config->session->name]) && $actname!='login' && $this->_request->getParam('action') != "backupdb" && $this->_request->getParam('action') != "sendbackupdb") {
			echo $this->view->render('login.php');
			exit;
		}
		//if we make it this far, we already have a session and we must be logged in
		
		//set view properties for identity and standard view variables
		$this->view->userid = $ident['admin_user_id'];
		$this->view->username = $ident['username'];
		$this->ident = $ident;
			
		require_once 'Zend/Session/Namespace.php';
		$this->session = new Zend_Session_Namespace('smugmugAdmin');
		Zend_Registry::set('session', $this->session);
		
		if(empty($this->session->site))
		{
			$this->session->site['name'] = '';
			$this->session->site['site_id'] = '';
		}
		
		if(empty($this->session->poll_id))
			$this->session->poll_id = '';
		

		if(isset($this->session->site)) {
			//echo "site_id".$this->session->site['site_id']; exit();
			$this->site_id = $this->view->site_id = $this->session->site['site_id'];
			$this->site_group_id = $this->view->site_group_id = $this->session->site['site_group_id'];
			$this->view->company = $this->session->site['name'];						
		}
		else {
			$this->site_id=0;
		}
		Zend_Registry::set('site_id', $this->site_id);
		Zend_Registry::set('site_group_id', $this->site_group_id);
		
		$curPath = dirname(__FILE__);
		$curPath = str_replace("\\", "/", $curPath);
		if(strpos($curPath, '/prod/')) $this->environment = 'live';
		else $this->environment = 'test';
		$this->view->environment = $this->environment;

		// get session expiry value from config
		$this->config = Zend_Registry::get('config');
		$this->view->session_timeout = $this->config->session_lifetime->expire;
	}
	
	function getAllowedMethods($userId) {
		Zend_Loader::LoadClass('userClass', $this->modelDir);
		$userClass = new userClass();
		$userModules = $userClass->getUserModules($this->site_id, $userId);
		/*controller : action name*/
		$allowedMethods = array("index:index", "user:login", "user:logout");
		$privileges = array();
		$privileges[1]["view"] = array("articles:index", "articles:getarticles", "articles:setarticle", "articles:getarticlebyid", "articles:setarticlebyid", "articles:getarticlepriority", "articles:addarticle", "articles:deletearticles", "sections:getcontentsections", "articles:getimagesbyarticleid","articles:clonearticles","articles:addrelatedarticle","articles:getrelatedarticles","articles:deleterelatedarticles","articles:getrelatedarticlebyid","articles:setrelatedarticlebyid", "articles:addrelatedarticles");
		$privileges[2]["view"] = array("comments:index", "comments:getcomments", "comments:setcomment", "comments:getcommentbyid", "comments:setcommentbyid", "comments:getcommentapproval", "comments:updatestatus");
		$privileges[3]["view"] = array("polls:index", "polls:pollanswers", "polls:getpolls", "polls:getpollbyid", "polls:setpollbyid", "polls:addpoll", "polls:deletepolls", "polls:getpollanswers", "polls:getpollanswerbyid", "polls:setpollanswerbyid", "polls:addpollanswer", "polls:deletepollanswers");
		$privileges[4]["view"] = array("sections:index", "sections:getcontentsections", "sections:addsection", "sections:getsectionbyid","sections:setsectionbyid", "sections:deletesections");
		$privileges[5]["view"] = array("specialsections:index", "specialsections:getspecialsections", "specialsections:getspecialsectionbyid", "specialsections:setspecialsection", "specialsections:uploadpdf", "specialsections:getpdf", "specialsections:deletespecialsections");
		$privileges[6]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[7]["view"] = array("user:admin", "user:getadminusers", "user:addadminuser", "user:getadminuserbyid", "user:setadminuserbyid", "user:deleteadminusers", "user:getadminusermodules", "user:setadminusermodules");
		$privileges[8]["view"] = array("import:importxml", "import:uploadxml", "import:getxmlwaiting", "import:importxmlbyfilename");
		$privileges[9]["view"] = array("content:contentgallery", "content:getcontentgallery", "content:getcontentgallerytype", "content:getcontentgallerybyid", "content:addcontentgallery", "content:updatecontentgallery", "content:getimages", "content:addimage", "content:updateimage", "content:uploadimage", "content:getimage", "content:getimagebyid", "content:deleteimages", "content:deletegallery", "content:uploadimage", "content:progressPhp", "content:progressphp", "content:progress.php", "content:getcontentgallerywithimages", "content:chunkupload", "content:photosupload");
		$privileges[10]["view"] = array("content:contentareas", "content:getcontentareas", "content:addcontentarea", "content:deletecontentareas", "content:getcontentareabyid", "content:setcontentareabyid", "content:getcontentsectionareas", "content:addcontentsectionarea", "content:deletecontentsectionarea", "content:getcontentsectionareabyid", "content:setcontentsectionareabyid", "sections:getcontentsectionslist",  "content:getcontentsectionareatype", "content:getcontentsectionareagroup", "content:getcontentsectionareagrouplist", "content:setcontentsectionareagroup", "content:deletecontentsectionareagroup", "content:uploadpdf", "content:getpdf", "sectionfront:uploadsectionfrontlogo", "sectionfront:getsectionfrontlogo" );
		$privileges[11]["view"] = array("footer:index", "footer:getfooterblock", "footer:addfooterblock", "footer:getfooterblockbyid", "footer:setfooterblockbyid", "footer:deletefooterblock", "footer:getfooterblockcontent", "footer:addfooterblockcontent", "footer:getfooterblockcontentbyid", "footer:setfooterblockcontentbyid", "footer:deletefooterblockcontent", "footer:getsubmenulist" );
		$privileges[12]["view"] = array("config:manage", "config:getconfigs", "config:setconfigs", "config:migrateconfigs" );
		$privileges[13]["view"] = array("sponsoredadvertisement:category", "sponsoredadvertisement:getcategories", "sponsoredadvertisement:addcategory", "sponsoredadvertisement:getcategorybyid", "sponsoredadvertisement:setcategorybyid", "sponsoredadvertisement:deletecategories", "sponsoredadvertisement:getemaillist" );
		$privileges[14]["view"] = array("sponsoredadvertisement:email", "sponsoredadvertisement:getemails", "sponsoredadvertisement:addemail", "sponsoredadvertisement:getemailbyid", "sponsoredadvertisement:updateemailbyid", "sponsoredadvertisement:deleteemails");
		$privileges[15]["view"] = array("user:managenotification", "user:getusernotifications", "user:getnotifications", "user:getnotificationmethod", "user:setusernotification", "user:getusernotificationbyid", "user:deleteusernotification");
		$privileges[16]["view"] = array("sections:getcontentsections", "sections:migrate", "sections:getcontentsectionslive", "sections:dosectionsmigrate");
		$privileges[17]["view"] = array("content:getcontentareas", "content:migrate", "content:getcontentareaslive", "content:docontentareasmigrate");
		$privileges[18]["view"] = array("polls:getpolls", "polls:migrate", "polls:getpollslive", "polls:dopollsmigrate");
		$privileges[19]["view"] = array("footer:getfooterblock", "footer:migrate", "footer:getfooterlive", "footer:dofootermigrate");
		$privileges[20]["view"] = array("specialsections:getspecialsections", "specialsections:migrate", "specialsections:getspecialsectionslive", "specialsections:dospecialsectionsmigrate");
		$privileges[21]["view"] = array("banner:manage", "banner:getsections", "banner:getbanners", "banner:getbannerbyid", "banner:setbannerbyid", "banner:delete", "banner:uploadimage", "banner:getimage", "banner:getexpandingimage", "banner:uploadexpandingimage");
		$privileges[22]["view"] = array("banner:getbanners", "banner:migrate", "banner:getbannerslive", "banner:dobannermigrate");
		$privileges[23]["view"] = array("lookandfeel:migrate", "lookandfeel:getlookandfeeltest", "lookandfeel:getlookandfeellive", "lookandfeel:dolookandfeelmigrate", "lookandfeel:dositesmigratepertab");
		$privileges[24]["view"] = array("sponsoredadvertisement:getcategories", "sponsoredadvertisement:migratecategory", "sponsoredadvertisement:getcategorieslive", "sponsoredadvertisement:dosponsoredadvertisementcategorymigrate");
		$privileges[25]["view"] = array("sponsoredadvertisement:getemails", "sponsoredadvertisement:migrateemail", "sponsoredadvertisement:getemailslive", "sponsoredadvertisement:dosponsoredadvertisementemailmigrate");
		$privileges[26]["view"] = array("layout:managehome", "layout:savehomelayout");
		$privileges[27]["view"] = array("layout:migratehome", "layout:gethomelayout", "layout:gethomelayoutlive", "layout:dohomelayoutmigrate");
		$privileges[28]["view"] = array("layout:resethome", "layout:managehome");
		$privileges[29]["view"] = array("layout:managearea", "layout:savearealayout");
		$privileges[30]["view"] = array("layout:migratearea", "layout:getarealayout", "layout:getarealayoutlive", "layout:doarealayoutmigrate");
		$privileges[31]["view"] = array("layout:resetarea", "layout:managearea");
		$privileges[32]["view"] = array("gallery:user", "gallery:getstringers", "gallery:addstringer", "gallery:deletestringers", "gallery:getstringerbyid", "gallery:setstringerbyid", "gallery:updatestringermonthlysales", "gallery:getsales", "gallery:exportsales", "gallery:exportsummarysales", "gallery:exportstringers",
			"gallery:updatestringerlastmonthsales",
		);
		$privileges[33]["view"] = array("gallery:assignment", "gallery:getassignments", "gallery:getstringers", "content:getcontentgallerytype", "gallery:saveassignment", "gallery:getassignmentbyid", "gallery:getusersinassignment", "gallery:deleteassignments");
		$privileges[34]["view"] = array("slideshow:index", "slideshow:excludefromslideshow", "slideshow:getslideshow", "slideshow:updateslideshoworder");
		$privileges[35]["view"] = array("site:cleancache");
		$privileges[36]["view"] = array("homepage:index","homepage:gethomepagearea","homepage:gethomepageareabyid","homepage:sethomepageareabyid","homepage:index");
		$privileges[37]["view"] = array("events:index","events:getevents","events:geteventbyid","events:addevent","events:deleteevents","events:seteventbyid","events:updatestatus");	
		$privileges[38]["view"] = array("site:manage");
		$privileges[39]["view"] = array("site:managesite");
		$privileges[40]["view"] = array("homepage:migrate","homepage:gethomepagearealive","homepage:dohomepageareamigrate","homepage:gethomepagearea");
		$privileges[41]["view"] = array("events:getevents","events:migrate","events:geteventslive","events:doeventsmigrate");	
		$privileges[42]["view"] = array("user:log","user:getlogs",);
		$privileges[43]["view"] = array("user:getadminusers","user:migrateadminusers","user:getadminuserslive","user:doadminusersmigrate");	
		$privileges[44]["view"] = array("user:getusernotifications","user:migrateusernotification","user:getusernotificationlive","user:dousernotificationmigrate");	
		$privileges[45]["view"] = array("widget:manage", "widget:getwidgets", "widget:getwidgetbyid", "widget:setwidgetbyid", "widget:delete");
		$privileges[46]["view"] = array("widget:getwidgets", "widget:migrate", "widget:getwidgetslive", "widget:dowidgetmigrate");
		$privileges[47]["view"] = array("slideshow:slideshow2", "slideshow:excludefrom2ndslideshow", "slideshow:get2ndslideshow", "slideshow:updateslideshow2order");
		$privileges[48]["view"] = array("contest:manage", "contest:getcontest", "contest:getcontestbyid", "contest:setcontestbyid", "contest:delete", "contest:uploadimage", "contest:getimage");
		$privileges[49]["view"] = array("contest:migrate", "contest:getcontest", "contest:getcontestlive", "contest:docontestmigrate");
		$privileges[50]["view"] = array("content:custompage", "content:getcustompage", "content:getcustompagebyid", "content:addcustompage", "content:updatecustompage", "content:deletecustompage", "content:uploadcustompagebgimage", "content:getcustompagebgimage", "contact:uploadimage", "contact:getimage", "contact:getcontacts", "contact:getcontactbyid", "contact:addcontact", "contact:updatecontact", "contact:deletecontact", "imageuploader:uploadimage", "imageuploader:getimage", "imageuploader:getimageuploader", "imageuploader:getimageuploaderbyid", "imageuploader:addimageuploader", "imageuploader:updateimageuploader", "imageuploader:deleteimageuploader", "content:getcustomslideshows", "content:addcustomslideshow", "content:updatecustomslideshow", "content:uploadslideshowimage", "content:getcustomslideshowbyid", "content:getslideshowimage", "content:deletecustomslideshow", "content:updatecustomslidesortorder", "content:getcustombios", "content:addcustombio", "content:updatecustombio", "content:uploadbioimage", "content:uploadbiosimage", "content:getcustombiobyid", "content:getbioimage", "content:getbiosimage", 
			"content:deletecustombio", "content:updatebiosortorder", "content:getcustombioslideshows", "content:addcustombioslideshow", "content:updatecustombioslideshow",
			"content:uploadbioslideshowimage", "content:getcustombioslideshowbyid", "content:getbioslideshowimage", "content:deletecustombioslideshow", "content:updatecustombioslidesortorder",
			"blogger:uploadimage", "blogger:getimage", "blogger:getbloggers", "blogger:getbloggerbyid", "blogger:addblogger", "blogger:updateblogger", "blogger:deleteblogger"
		);
		$privileges[51]["view"] = array("slideshow:sectionfrontarticles", "slideshow:excludearticlefromsectionfront", "slideshow:getsectionfrontarticles", "slideshow:getsectionfrontarticlesections", "articles:getarticlebyid", "slideshow:setsectionfrontarticles");
		$privileges[52]["view"] = array("content:migratecustompage", "content:getcustompage", "content:getcustompagelive", "content:docustompagemigrate");
		$privileges[53]["view"] = array("game:manage", "game:getgame", "game:getsports", "game:getgametypes", "game:updategamescores", "game:getgameteams", "game:getgamebyid", "game:setgamebyid", "game:delete", "game:uploadhtimage", "game:uploadvtimage", "game:gethtimage", "game:getvtimage",
			"game:getperiods", "game:getlocations", "game:getschools", );
		$privileges[54]["view"] = array("game:migrate", "game:getgame", "game:getgamelive", "game:dogamemigrate");
		$privileges[55]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[56]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[57]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[58]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[59]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[60]["view"] = array("lookandfeel:index", "lookandfeel:getlookandfeel", "lookandfeel:setlookandfeel", "lookandfeel:uploadlogo", "lookandfeel:getlogo", "sections:getcontentsectionslist");
		$privileges[61]["view"] = array("sports:index", "sports:getsports", "sports:addsport", "sports:getsportbyid","sports:setsportbyid", "sports:deletesports");
		$privileges[62]["view"] = array("sports:getsports", "sports:migrate", "sports:getsportslive", "sports:dosportsmigrate");
		$privileges[63]["view"] = array("gameclasses:index", "gameclasses:getgameclasses", "gameclasses:addgameclass", "gameclasses:getgameclassbyid","gameclasses:setgameclassbyid", "gameclasses:deletegameclasses");
		$privileges[64]["view"] = array("gameclasses:getgameclasses", "gameclasses:migrate", "gameclasses:getgameclasseslive", "gameclasses:dogameclassesmigrate");
		$privileges[65]["view"] = array("schools:index", "schools:getschools", "schools:addschools", "schools:getschoolbyid","schools:setschoolbyid", "schools:deleteschools", "schools:uploadschoolimage", "schools:getschoolimage", "schools:getclasses",);
		$privileges[66]["view"] = array("schools:getschools", "schools:migrate", "schools:getschoolslive", "schools:doschoolsmigrate");
		$privileges[67]["view"] = array("newsletter:list", "newsletter:getlists", "newsletter:addlist", "newsletter:deletelists", "newsletter:getlistbyid", "newsletter:setlistbyid", "newsletter:getlistsubscribers", "newsletter:addlistsubscribers", "newsletter:deletelistsubscriberbyid", "newsletter:setlistsubscriberbyid");
		$privileges[68]["view"] = array("newsletter:campaigns", "newsletter:getcampaigns", "newsletter:addcampaign", "newsletter:deletecampaigns", "newsletter:getcampaignbyid", "newsletter:setcampaignbyid", "newsletter:getmessages", "newsletter:addmessage", "newsletter:getmessagebyid", "newsletter:setmessagebyid");
		$privileges[69]["view"] = array("newsletter:pages", "newsletter:getpages", "newsletter:pageeditor", "newsletter:getarticleportlet", "newsletter:getpage", "newsletter:savepage", "newsletter:getnewscategories", "newsletter:deletepage", "newsletter:gettemplates", "newsletter:addpage");
		$privileges[70]["view"] = array("newsletter:migratepages", "newsletter:getpagestest", "newsletter:getpageslive", "newsletter:dopagesmigrate", "site:getsites");
		$privileges[71]["view"] = array("gallery:getstringers","gallery:migratestringers","gallery:getstringerslive","gallery:dostringersmigrate");	
		
		foreach ($userModules as $userModule) { 
			if(($userModule["privilege"] & 1) && is_array($privileges[$userModule["admin_module_id"]]["view"])) $allowedMethods = array_merge($allowedMethods, $privileges[$userModule["admin_module_id"]]["view"]);
		}
		
		return $allowedMethods;
	}
	
	function trimHtmlText($str) {
		$str = stripslashes($str);
		$str		= str_replace("\n", "", $str);
		$str		= str_replace("\r", "", $str);
		$str		= str_replace("'", "&#039;", $str);
		$str		= preg_replace("!<script.*?</script>!is", "", $str);
		$str		= preg_replace("!<style.*?</style>!is", "", $str);
		$str		= strip_tags($str, "<p><a><img><b><i><u><strong><h1><h2><h3><h4><h5><h6><ul><ol><li><font><br><sup><sub><strike><table><tr><td><th><div><span>"); 
		
		//$str		= preg_replace("/( style\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onmouseover\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onclick\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onmouseout\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onmousemove\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onmousedown\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onmouseup\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( ondblclick\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onkeypress\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onkeyup\=\"(.*?)\")/is", "", $str);
		$str		= preg_replace("/( onkeydown\=\"(.*?)\")/is", "", $str);
		//$str		= str_replace("javascript:", "", $str);
		return $str;
	}
	
	function cleanCache() {
		$configFile = $this->config->paths->sitepath."/".$this->session->site["name"]."/config.ini";
		$configs = parse_ini_file( $configFile, true );
		$crl = curl_init();
        curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($crl, CURLOPT_URL, $configs["general"]["url"]."/index/docleancache"); ///tag/".CACHE_NAME.$this->siteid.$this->session->site["sitename"]
        curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, 3600);
        curl_setopt ($crl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
        curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
        curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
        $ret = curl_exec($crl);
        curl_close($crl);
        return $ret;
	}
}

?>