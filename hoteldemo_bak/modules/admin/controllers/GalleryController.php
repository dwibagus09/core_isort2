<?php
require_once('actionControllerBase.php');
class Admin_GalleryController extends actionControllerBase 
{
	
	function userAction()
    {   
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(32, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
		$this->view->start_date = date("Y-m-")."01";
    	$this->view->end_date = date("Y-m-").date("t");
    	
        echo $this->view->render('header.php');
        echo $this->view->render('stringers.php');
        echo $this->view->render('footer.php');
    }
    
    function getstringersAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $stringerClass->getStringers($this->site_id);
    	if ( is_array($data) ) {
    		$response['success'] = true;
    		$response['data'] = $data;
   		}
    	echo json_encode($response);
    }
    
    function addstringerAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	
    	$stringerTable = new stringer_users(array('db'=>'db'));
    	$select = $stringerTable->select()->where("site_id=?", $this->site_id)->where("stringer_username=?", $params['stringer_username']);
    	$stringerId = $stringerTable->getAdapter()->fetchOne($select);
    	if(!empty($stringerId)) {
    		$data = array(
    			"success"	=> false,
    			"msg"		=> "Username has already been taken, please choose another username."
    		);
    	}
    	else {
    		$stringerClass->addStringer($this->site_id, $params);
    		$data = array(
    			"success"	=> true,
    		);
    	}
    	echo json_encode($data);
    }
    
    function deletestringersAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $stringerId) {
	    		$stringerClass->deleteStringers($stringerId);
	    	}
    	}
    }
    
    function getstringerbyidAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$stringer = $stringerClass->getStringer($params['stringer_user_id']);
		$response['data'] = $stringer;
    	echo json_encode($response);
    }
    
    function setstringerbyidAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	$params = $this->_getAllParams();
    	
    	$stringerTable = new stringer_users(array('db'=>'db'));
    	$select = $stringerTable->select()->where("site_id=?", $this->site_id)->where("stringer_username=?", $params['stringer_username'])->where('stringer_user_id<>?', $params['stringer_user_id']);
    	$stringerId = $stringerTable->getAdapter()->fetchOne($select);
    	if(!empty($stringerId)) {
    		$data = array(
    			"success"	=> false,
    			"msg"		=> "Username has already been taken, please choose another username."
    		);
    	}
    	else {
    		$stringerClass->updateStringer($params);
    		$data = array(
    			"success"	=> true,
    		);
    	}
    	echo json_encode($data);
    }
	
	function migrateuserAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(71, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
        echo $this->view->render('header.php');
        echo $this->view->render('stringers_migrate.php');
        echo $this->view->render('footer.php');
    }
    
    function getstringersliveAction()
    {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $stringerClass->getStringers($this->site_id, array(), true);
    	
    	echo json_encode($response);
    }
    
    function dostringersmigrateAction()
    {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
		Zend_Loader::LoadClass('userClass', $this->modelDir);
    	$user = new userClass();
		
    	$params = $this->_getAllParams();
    	$readOnly = FALSE;
    	if($this->ident["role"] != "apt") {
	    	$privilege = $user->getUserPrivilige(71, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
    	
    	if (!$readOnly && $this->site_id > 0 ) {
    		$dataKeys = json_decode($params['data'], true);
			$ids="";
			foreach ($dataKeys as $id)
				$ids = $ids.$id['stringer_user_id'].",";
			$params['ids'] = substr($ids, 0, -1);
    		$arrAdminUsers = $stringerClass->getStringers($this->site_id, $params);
	    	    		
    		if ( $params['logic'] == 'migrate' ) {
    			$retVal = $stringerClass->migrateStringers($this->site_id, $arrAdminUsers, $params);    			
    		}
    		elseif ( $params['logic'] == 'copy' ) {
    			$retVal = $stringerClass->migrateCopyStringers($this->site_id, $arrAdminUsers, $params);
    		}
    	}
    	return self::getstringersliveAction();
    }
    
    function assignmentAction() {
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(33, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
		Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
		$this->view->stringers = $stringerClass->getStringers($this->site_id);
    	
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
		 	$this->view->useSmugmug = 1;
		 	$categories = $f->categories_get();
		 	$this->view->smugmugCategories = $categories;
		}
		
		$siteGroupId = intval($this->site_group_id);
		$this->view->groupSites = $this->db->fetchAll("SELECT * FROM sites WHERE site_group_id='{$siteGroupId}'");
		
        echo $this->view->render('header.php');
        echo $this->view->render('assignments.php');
        echo $this->view->render('footer.php');
    }
    
    function getassignmentsAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$data = $stringerClass->getAssignments($this->site_id, $params);
    	if ( is_array($data) ) {
    		$response = $data;
    		$response['success'] = true;
   		}
    	echo json_encode($response);
    }

    function saveassignmentAction() {
    	$params = $this->_getAllParams();
    	if(isset($params['show_gallery_in_multimedia']) && $params['show_gallery_in_multimedia'] == 'on')
    		$params['show_gallery_in_multimedia'] = 1;
    	else
    		$params['show_gallery_in_multimedia'] = 0;
    	
    	if(isset($params['for_sale']) && $params['for_sale'] == 'on')
    		$params['for_sale'] = 1;
    	else
    		$params['for_sale'] = 0;
    	
    	$params['public'] = true;
    		
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(33, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		$assignmentId = 0;
		if(!$readOnly) {
			Zend_Loader::LoadClass('contentgalleryClass', $this->modelDir);
    		$cgc = new contentgalleryClass();
    	
			if(empty($params['stringer_gallery_id'])) {
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
						$response = $f->albums_create("Title=".$params['content_gallery'], "Keywords=".$params['keywords'], "CategoryID=".$categoryId, "Protected=".((!empty($site_configs['smugmug']['album.protected']))?"true":"false"), "Watermarking=".((!empty($site_configs['smugmug']['album.watermarking']))?"true":"false"), "XLarges=".((!empty($site_configs['smugmug']['album.xlarges']))?"true":"false"), "Larges=".((!empty($site_configs['smugmug']['album.larges']))?"true":"false"), "Originals=".((!empty($site_configs['smugmug']['album.originals']))?"true":"false"), "X2Larges=".((!empty($site_configs['smugmug']['album.x2larges']))?"true":"false"), "X3Larges=".((!empty($site_configs['smugmug']['album.x3larges']))?"true":"false"), "WatermarkID=".((!empty($site_configs['smugmug']['album.watermarkid']))?$site_configs['smugmug']['album.watermarkid']:0), "Printable=".$params['for_sale']);
						if(!empty($response['id'])) {
							$params['smugmug_id'] = $response['id'];
							$params['smugmug_key'] = $response['Key'];
						}
					} catch(Exception $ex) {}
				}
		    		
		    	$contentGalleryId = $cgc->addContentGallery($this->site_id, $params);
		    	
				$stringerGalleryTable = new stringer_galleries(array('db'=>'db'));
				$stringerGalleryTable->insert(array(
					"site_id"				=> $this->site_id,
					"content_gallery_id"	=> $contentGalleryId,
					"created_date"			=> date("Y-m-d H:i:s"),
				));
				$assignmentId = $stringerGalleryTable->getAdapter()->lastInsertId('stringer_galleries');
			}
			else {
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
						$response = $f->albums_changeSettings("AlbumID=".$params['smugmug_id'], "Title=".$params['content_gallery'], "Keywords=".$params['keywords'], "CategoryID=".$categoryId, "Protected=".((!empty($site_configs['smugmug']['album.protected']))?"true":"false"), "Watermarking=".((!empty($site_configs['smugmug']['album.watermarking']))?"true":"false"), "XLarges=".((!empty($site_configs['smugmug']['album.xlarges']))?"true":"false"), "Larges=".((!empty($site_configs['smugmug']['album.larges']))?"true":"false"), "Originals=".((!empty($site_configs['smugmug']['album.originals']))?"true":"false"), "X2Larges=".((!empty($site_configs['smugmug']['album.x2larges']))?"true":"false"), "X3Larges=".((!empty($site_configs['smugmug']['album.x3larges']))?"true":"false"), "Printable=".$params['for_sale']);
					} catch(Exception $ex) { }
				}
				
				$assignmentId = $params['stringer_gallery_id'];
				
				$stringerGalleryTable = new stringer_galleries(array('db'=>'db'));
				$stringerGalleryTable->getAdapter()->query("DELETE FROM stringer_assignments WHERE stringer_gallery_id='{$assignmentId}'");
			}
			
			$users = $params['ausers_list'];
			$users = json_decode($users);
			$stringerUserTable = new stringer_assignments(array('db'=>'db'));
			if(is_array($users)) foreach ($users as $user) {
				$stringerUserTable->insert(array(
					"site_id"		=> $this->site_id,
					"stringer_gallery_id"	=> $assignmentId,
					"stringer_user_id"		=> intval($user->stringer_user_id),
					"created_date"			=> date("Y-m-d H:i:s"),
				));
			}
		}
    }
    
    function getassignmentbyidAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$params = $this->_getAllParams();
    	$data['success'] = true;
    	$data['data'] = $stringerClass->getAssignment($params['stringer_gallery_id']);
    	
    	echo json_encode($data);
    }
    
    function getusersinassignmentAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	$data = $stringerClass->getAssignmentUsers($this->_request->getParam('stringer_gallery_id'));
    	echo json_encode($data);
    }
    
    function deleteassignmentsAction() {
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $assignmentId) {
	    		$stringerClass->deleteAssignment($assignmentId);
	    	}
    	}
    }
    
    function updatestringermonthlysalesAction() {
    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		
		$userAgent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36";
		
    	set_time_limit(0);
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login?goTo=https%3A%2F%2Fsecure.smugmug.com%2Flogin");
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_REFERER, 'http://www.smugmug.com/');
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		$tokenPosition = strpos($ret, '"formToken"');
		/*$tokenPosition += 19;*/
		$tokenPosition += 13;
		$nextAposPosition = strpos($ret, '"', $tokenPosition);
		$formToken = substr($ret, $tokenPosition, $nextAposPosition-$tokenPosition);
				
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_POST, 1);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login");
		curl_setopt ($crl, CURLOPT_POSTFIELDS, 'username='.$site_configs['smugmug']['email'].'&password='.$site_configs['smugmug']['password'].'&serviceType=2&linkAccounts=0&keeploggedin=0&formToken='.$formToken);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		//$downloadURL = "https://secure.smugmug.com/sales/history/?download&detailed=true&filtered=true&PaidStatus=Paid&DateRange=ThisMonth";
		$downloadURL = "https://secure.smugmug.com/sales/history/?download&detailed=true&filtered=true&PaidStatus=All&DateRange=ThisMonth";
		$crl = curl_init();
		$timeout = 1800;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_URL, $downloadURL);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
    	//$content = file_get_contents("http://temp.cai.com/sample.csv");
    	$content = $ret;
    	$lines = explode("\n", $content);
    	array_shift($lines);
    	
    	require_once($this->modelDir."/dbClass.php");
    	
    	$smugmugSalesTable = new smugmug_sales(array('db'=>'db'));
    	
    	$startDate = date("Y-m-")."01 00:00:00";
    	$endDate = date("Y-m-").date("t")." 23:59:59";
    	
    	$where = array();
    	$where[] = $smugmugSalesTable->getAdapter()->quoteInto("site_id=?", $this->site_id);
    	$where[] = $smugmugSalesTable->getAdapter()->quoteInto("order_date BETWEEN '{$startDate}' AND '{$endDate}'", "");
    	$smugmugSalesTable->delete($where);
    	
    	$stringerUserTable = new stringer_users(array('db'=>'db'));
    	$select = $stringerUserTable->select()->where("site_id=?", $this->site_id);
    	$astringers = $stringerUserTable->getAdapter()->fetchAll($select);
    	$stringers = array();
    	$codes = array();
    	foreach ($astringers as $stringer) {
    		$stringer['staff_code'] = strtoupper($stringer['staff_code']);
    		$stringers[$stringer['staff_code']] = $stringer;
    		$codes[] = $stringer['staff_code'];
    	}
    	if(is_array($lines)) foreach ($lines as $line) {
    		$columns = explode(",", $line);
    		if(count($columns)==23) {
    			if(strtolower($columns[10])=="sale") {
    				$columns[18] = trim($columns[18], '"');
    				$fileName = $columns[18];
    				$temp = explode("_", $fileName);
    				$code = strtoupper($temp[0]);
    				$stringerUserId = 0;
    				$commissionPercentage = 0;
    				if(in_array($code, $codes)) {
    					$stringerUserId = $stringers[$code]['stringer_user_id'];
    					$commissionPercentage = intval($stringers[$code]['commission_percentage']);
    				}
					$date = date_parse($columns[1]);
					$data = array(
						"stringer_user_id"		=> $stringerUserId,
						"site_id"				=> $this->site_id,
						"order_id"				=> $columns[0],
						"order_date"			=> date("Y-m-d H:i:s", mktime($date['hour'],$date['minute'],$date['second'],$date['month'],$date['day'],$date['year'])),
						"quantity"				=> $columns[2],
						"currency"				=> $columns[3],
						"base_price"			=> floatval($columns[4]),
						"price_charged"			=> floatval($columns[5]),
						"profit"				=> floatval($columns[6]),
						"charges"				=> floatval($columns[7]),
						"sales_tax"				=> floatval($columns[8]),
						"shipping_cost"			=> floatval($columns[9]),
						"smugmug_type"			=> $columns[10],
						"smugmug_name"			=> trim($columns[11], '"'),
						"payment_status"		=> $columns[12],
						"payment_info"			=> $columns[14],
						"payment_currency"		=> $columns[15],
						"payment_exchange_rate"	=> floatval($columns[16]),
						"file_name"				=> $fileName,
						"image_id"				=> $columns[19],
						"album_id"				=> $columns[20],
						"gallery_title"			=> trim($columns[22], '"'),
						"category_hierarchy"	=> trim($columns[21], '"'),
						"commission_percentage"	=> $commissionPercentage,
						"last_update_date"		=> date("Y-m-d H:i:s"),
						"last_update_by"		=> $this->ident['adminusername'],
					);
					if(!empty($columns[13])) {
						$date = date_parse($columns[13]);
						$data['payment_date'] = date("Y-m-d H:i:s", mktime($date['hour'],$date['minute'],$date['second'],$date['month'],$date['day'],$date['year']));
					}
					$smugmugSalesTable->insert($data);
				}
    		}
    	}
    	
    	foreach ($stringers as $stringer) {
    		$totalQty = $smugmugSalesTable->getAdapter()->fetchOne("
    			SELECT SUM(quantity) FROM smugmug_sales WHERE stringer_user_id='{$stringer['stringer_user_id']}' 
    			GROUP BY stringer_user_id
    		");
    		$totalQty = intval($totalQty);
    		$totalQty += intval($stringer['original_qty']);
    		$totalSales = $smugmugSalesTable->getAdapter()->fetchOne("
    			SELECT SUM(price_charged) FROM smugmug_sales WHERE stringer_user_id='{$stringer['stringer_user_id']}' 
    			GROUP BY stringer_user_id
    		");
    		$totalSales = floatval($totalSales);
    		$totalSales += floatval($stringer['original_sales']);
    		
    		$stringerUserTable->getAdapter()->query("
    			UPDATE stringer_users SET
    				total_sales={$totalSales},
    				total_qty={$totalQty}
    			WHERE stringer_user_id='{$stringer['stringer_user_id']}' AND site_id='{$this->site_id}'
    		");
    		
    	}
    	
    }
    
    function updatestringerlastmonthsalesAction() {
    	$configFile = $this->config->paths->sitepath."/".$this->session->site['name']."/config.ini";
		$site_configs = parse_ini_file( $configFile, true );
		
		$userAgent = "Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36";
		
		set_time_limit(0);
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login?goTo=https%3A%2F%2Fsecure.smugmug.com%2Flogin");
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_REFERER, 'http://www.smugmug.com/');
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		$tokenPosition = strpos($ret, '"formToken"');
		/*$tokenPosition += 19;*/
		$tokenPosition += 13;
		$nextAposPosition = strpos($ret, '"', $tokenPosition);
		$formToken = substr($ret, $tokenPosition, $nextAposPosition-$tokenPosition);
				
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_POST, 1);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login");
		curl_setopt ($crl, CURLOPT_POSTFIELDS, 'username='.$site_configs['smugmug']['email'].'&password='.$site_configs['smugmug']['password'].'&serviceType=2&linkAccounts=0&keeploggedin=0&formToken='.$formToken);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		/*
    	set_time_limit(0);
		$crl = curl_init();
		$timeout = 180;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login?goTo=https%3A%2F%2Fsecure.smugmug.com%2Flogin");
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_REFERER, 'http://www.smugmug.com/');
		//curl_setopt ($crl, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)");
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		$tokenPosition = strpos($ret, '"formToken"');
		//$tokenPosition += 19;
		$tokenPosition += 13;
		$nextAposPosition = strpos($ret, '"', $tokenPosition);
		$formToken = substr($ret, $tokenPosition, $nextAposPosition-$tokenPosition);
		
$logContent = $formToken ."\n\n";
		
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_POST, 1);
		//curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login.mg");
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/login");
		curl_setopt ($crl, CURLOPT_POSTFIELDS, 'username='.$site_configs['smugmug']['email'].'&password='.$site_configs['smugmug']['password'].'&serviceType=2&linkAccounts=0&keeploggedin=0&formToken='.$formToken);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
		$crl = curl_init();
		$timeout = 120;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_POST, TRUE);
		curl_setopt ($crl, CURLOPT_URL, "https://secure.smugmug.com/services/api/json/1.4.0/");
		curl_setopt ($crl, CURLOPT_POSTFIELDS, "Email=".$site_configs['smugmug']['email']."&Password=".$site_configs['smugmug']['password']."&OTPCode=&KeepLoggedIn=0&IsOAuth=0&method=rpc.user.login");
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_REFERER, 'https://secure.smugmug.com/login?goTo=https%3A%2F%2Ffocusinonme.smugmug.com%2F');
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIESESSION, TRUE);			
		curl_setopt ($crl, CURLOPT_HTTPHEADER, array('content-type: application/x-www-form-urlencoded; charset=UTF-8', ':authority:secure.smugmug.com', ':path:/services/api/json/1.4.0/', ':scheme:https', 'accept:application/json')); 
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
	
		$returnArr = json_decode($ret, true);
$logContent .= "json login result=". print_r($returnArr) ."\n\n";
*/
		
		//$downloadURL = "https://secure.smugmug.com/sales/history/?download&detailed=true&filtered=true&PaidStatus=Paid&DateRange=LastMonth";
		$downloadURL = "https://secure.smugmug.com/sales/history/?download&detailed=true&filtered=true&PaidStatus=All&DateRange=LastMonth";
		$crl = curl_init();
		$timeout = 1800;
		curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt ($crl, CURLOPT_URL, $downloadURL);
		curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
		curl_setopt ($crl, CURLOPT_USERAGENT, $userAgent);
		curl_setopt ($crl, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt ($crl, CURLOPT_COOKIEFILE, "cookiefile");
		curl_setopt ($crl, CURLOPT_COOKIEJAR, "cookiefile");
		$ret = curl_exec($crl);
		curl_close($crl);
		
$logContent .= "last month sales=". $ret ."\n\n";
				
    	//$content = file_get_contents("http://temp.cai.com/sample.csv");
    	$content = $ret;
    	$lines = explode("\n", $content);
		$totalColumn = 0;
		if(!empty($lines)) {
			$columns = explode(",", $lines[0]);
			$totalColumn = count($columns);
		}
    	array_shift($lines);
		
$logContent .= "lines=". print_r($lines, true) ."\n\n";

//@file_put_contents(dirname(__FILE__)."/logcontent.txt", $logContent);
    	
    	require_once($this->modelDir."/dbClass.php");
    	
    	$smugmugSalesTable = new smugmug_sales(array('db'=>'db'));
    	
    	$lastMonthTimestamp = mktime(0, 0, 0, date("m")-1, date("d"), date("Y"));
    	$startDate = date("Y-m-", $lastMonthTimestamp)."01 00:00:00";
    	$endDate = date("Y-m-", $lastMonthTimestamp).date("t", $lastMonthTimestamp)." 23:59:59";
    	
    	$where = array();
    	$where[] = $smugmugSalesTable->getAdapter()->quoteInto("site_id=?", $this->site_id);
    	$where[] = $smugmugSalesTable->getAdapter()->quoteInto("order_date BETWEEN '{$startDate}' AND '{$endDate}'", "");
    	$smugmugSalesTable->delete($where);
    	
    	$stringerUserTable = new stringer_users(array('db'=>'db'));
    	$select = $stringerUserTable->select()->where("site_id=?", $this->site_id);
    	$astringers = $stringerUserTable->getAdapter()->fetchAll($select);
    	$stringers = array();
    	$codes = array();
    	foreach ($astringers as $stringer) {
    		$stringer['staff_code'] = strtoupper($stringer['staff_code']);
    		$stringers[$stringer['staff_code']] = $stringer;
    		$codes[] = $stringer['staff_code'];
    	}
    	if(is_array($lines)) foreach ($lines as $line) {
    		//$columns = explode(",", $line);
			$columns = str_getcsv($line, ',');
    		if($totalColumn==23) {
    			if(strtolower($columns[10])=="sale") {
    				$columns[18] = trim($columns[18], '"');
					$fileName = $columns[18];
    				$temp = explode("_", $fileName);
    				$code = strtoupper($temp[0]);
    				$stringerUserId = 0;
    				$commissionPercentage = 0;
    				if(in_array($code, $codes)) {
    					$stringerUserId = $stringers[$code]['stringer_user_id'];
    					$commissionPercentage = intval($stringers[$code]['commission_percentage']);
    				}
					$date = date_parse($columns[1]);
					$data = array(
						"stringer_user_id"		=> $stringerUserId,
						"site_id"				=> $this->site_id,
						"order_id"				=> $columns[0],
						"order_date"			=> date("Y-m-d H:i:s", mktime($date['hour'],$date['minute'],$date['second'],$date['month'],$date['day'],$date['year'])),
						"quantity"				=> $columns[2],
						"currency"				=> $columns[3],
						"base_price"			=> floatval($columns[4]),
						"price_charged"			=> floatval($columns[5]),
						"profit"				=> floatval($columns[6]),
						"charges"				=> floatval($columns[7]),
						"sales_tax"				=> floatval($columns[8]),
						"shipping_cost"			=> floatval($columns[9]),
						"smugmug_type"			=> $columns[10],
						"smugmug_name"			=> trim($columns[11], '"'),
						"payment_status"		=> $columns[12],
						"payment_info"			=> $columns[14],
						"payment_currency"		=> $columns[15],
						"payment_exchange_rate"	=> floatval($columns[16]),
						"file_name"				=> $fileName,
						"image_id"				=> $columns[19],
						"album_id"				=> $columns[20],
						"gallery_title"			=> trim($columns[22], '"'),
						"category_hierarchy"	=> trim($columns[21], '"'),
						"commission_percentage"	=> $commissionPercentage,
						"last_update_date"		=> date("Y-m-d H:i:s"),
						"last_update_by"		=> $this->ident['adminusername'],
					);
					if(!empty($columns[13])) {
						$date = date_parse($columns[13]);
						$data['payment_date'] = date("Y-m-d H:i:s", mktime($date['hour'],$date['minute'],$date['second'],$date['month'],$date['day'],$date['year']));
					}
					$smugmugSalesTable->insert($data);
				}
    		}
    	}
    	
    	foreach ($stringers as $stringer) {
    		$totalQty = $smugmugSalesTable->getAdapter()->fetchOne("
    			SELECT SUM(quantity) FROM smugmug_sales WHERE stringer_user_id='{$stringer['stringer_user_id']}' 
    			GROUP BY stringer_user_id
    		");
    		$totalQty = intval($totalQty);
    		$totalQty += intval($stringer['original_qty']);
    		$totalSales = $smugmugSalesTable->getAdapter()->fetchOne("
    			SELECT SUM(price_charged) FROM smugmug_sales WHERE stringer_user_id='{$stringer['stringer_user_id']}' 
    			GROUP BY stringer_user_id
    		");
    		$totalSales = floatval($totalSales);
    		$totalSales += floatval($stringer['original_sales']);
    		
    		$stringerUserTable->getAdapter()->query("
    			UPDATE stringer_users SET
    				total_sales={$totalSales},
    				total_qty={$totalQty}
    			WHERE stringer_user_id='{$stringer['stringer_user_id']}' AND site_id='{$this->site_id}'
    		");
    		
    	}
    	
    }
    
    function getsalesAction() {
    	$startDate = $this->_request->getParam("start_date");
    	$endDate = $this->_request->getParam("end_date");
    	$startDate = substr($startDate,0,10);
    	$endDate = substr($endDate,0,10);
    	if(strlen($startDate) < 10) $startDate = date("Y-m-")."01";
    	if(strlen($endDate) < 10) $endDate = date("Y-m-").date("t");
    	$stringerUserId = $this->_request->getParam("stringer_user_id");
    	require_once($this->modelDir."/dbClass.php");
    	$smugmugSaleTable = new smugmug_sales(array('db'=>'db'));
    	$select = $smugmugSaleTable->select()->where("site_id=?", $this->site_id)->where("order_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'")->where("smugmug_type=?", "sale")->where("stringer_user_id=?", $stringerUserId);
    	$data['rows'] = $smugmugSaleTable->getAdapter()->fetchAll($select);
    	$data['totalCount'] = count($data['rows']);
    	echo json_encode($data);
    }
    
    function exportsalesAction() {
    	$startDate = $this->_request->getParam("start_date");
    	$endDate = $this->_request->getParam("end_date");
    	$startDate = substr($startDate,0,10);
    	$endDate = substr($endDate,0,10);
    	if(strlen($startDate) < 10) $startDate = date("Y-m-")."01";
    	if(strlen($endDate) < 10) $endDate = date("Y-m-").date("t");
    	$stringerUserId = $this->_request->getParam("stringer_user_id");
    	require_once($this->modelDir."/dbClass.php");
    	$smugmugSaleTable = new smugmug_sales(array('db'=>'db'));
    	
    	$this->view->sales = $sales = $smugmugSaleTable->getAdapter()->fetchAll("
    		SELECT ss.*, COALESCE(su.stringer_username, 'N/A') AS stringer_username, COALESCE(su.`stringer_fullname`, 'N/A') AS stringer_fullname, COALESCE(su.`staff_code`, 'N/A') AS staff_code
			FROM smugmug_sales ss
			LEFT JOIN stringer_users su ON su.`stringer_user_id`=ss.`stringer_user_id`
			WHERE ss.site_id={$this->site_id} AND ss.order_date BETWEEN '{$startDate} 00:00:00' AND '{$endDate} 23:59:59'
				AND ss.`smugmug_type`='Sale'
			ORDER BY su.`stringer_username`, ss.`order_date`
    	");
    	
    	/*
		$this->view->reportTimestamp = mktime();
    	$this->view->startDate = $startDate;
    	$this->view->endDate = $endDate;
		
		$this->_response->setHeader("Content-Type", "application/vnd.ms-excel; charset=UTF-8", true);
		$this->_response->setHeader("Content-Disposition", "inline; filename=smugmugsales-".date("ymd.His").".xls", true);
		$this->_response->setHeader("Expires", 0, true);
		$this->_response->setHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0", true);
		$this->_response->setHeader("Pragma", "public", true);
    	
    	echo $this->view->render("smugmug_sales.php");*/
		
		require_once 'PHPExcel-1.8/PHPExcel.php';
		
		$startDate = $this->_request->getParam("start_date");
    	$endDate = $this->_request->getParam("end_date");
		$startDate = substr($startDate,0,10);
    	$endDate = substr($endDate,0,10);
    	if(strlen($startDate) != 10) $startDate = date("Y-m-")."01";
    	if(strlen($endDate) != 10) $endDate = date("Y-m-").date("t");
		
    	$stringerUserId = $this->_request->getParam("stringer_user_id");
    	
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$reportTimestamp = mktime();
		
		$sd = explode('-',$startDate);
		if($sd[1]<10) $sd[1] = str_replace('0','',$sd[1]);
		if($sd[2]<10) $sd[2] = str_replace('0','',$sd[2]);
		$startDate = $sd[1]."/".$sd[2]."/".substr($sd[0],2);
		$ed = explode('-',$endDate);
		if($ed[1]<10) $ed[1] = str_replace('0','',$ed[1]);
		if($ed[2]<10) $ed[2] = str_replace('0','',$ed[2]);
		$endDate = $ed[1]."/".$ed[2]."/".substr($ed[0],2);
    	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("APT")
									 ->setLastModifiedBy("APT")
									 ->setTitle($this->session->site['name']." Smugmug Sales")
									 ->setSubject($this->session->site['name']." Smugmug Sales")
									 ->setDescription($this->session->site['name']." Smugmug Sales")
									 ->setKeywords($this->session->site['name']." Smugmug Sales")
									 ->setCategory("Smugmug Sales");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Site')
					->setCellValue('A2', 'Report Created Date')
					->setCellValue('A3', 'Start Date Period')
					->setCellValue('A4', 'End Date Period');
					
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME2);		
		$objPHPExcel->getActiveSheet()->getStyle('C3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_MDDYYYYSLASH);					
		$objPHPExcel->getActiveSheet()->getStyle('C4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_MDDYYYYSLASH);

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C1', $this->session->site['name'])
					->setCellValue('C2', date("m/d/y g:i A", $reportTimestamp))
					->setCellValue('C3', $startDate)
					->setCellValue('C4', $endDate);

		$objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'User ID')
			  ->setCellValue('B6', 'Username')
			  ->setCellValue('C6', 'Full Name')
			  ->setCellValue('D6', 'Order ID')
			  ->setCellValue('E6', 'Order Date')
			  ->setCellValue('F6', 'Quantity')
			  ->setCellValue('G6', 'Currency')
			  ->setCellValue('H6', 'Base Price')
			  ->setCellValue('I6', 'Price Charged')
			  ->setCellValue('J6', 'Profit')
			  ->setCellValue('K6', 'Charges')
			  ->setCellValue('L6', 'Tax')
			  ->setCellValue('M6', 'Shipping Cost')
			  ->setCellValue('N6', 'Smugmug Name')
			  ->setCellValue('O6', 'Payment Status')
			  ->setCellValue('P6', 'File Name')
			  ->setCellValue('Q6', 'Category')
			  ->setCellValue('R6', 'Gallery')
			  ->setCellValue('S6', '% Commission');
			  
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('F')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('G')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$BStyle = array(
		  'borders' => array(
			'bottom' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THICK
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->applyFromArray($BStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A6:S6')->getAlignment()->setWrapText(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(40);
		$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->freezePane('A7');
			  
		$objPHPExcel->getActiveSheet()->setAutoFilter('A6:S500');
		$autoFilter = $objPHPExcel->getActiveSheet()->getAutoFilter();
		/*$autoFilter->getColumn('A')
			->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_FILTER)
			->createRule()
			->setRule(
				PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
				''
			);
			*/
		if(is_array($sales)) 
		{
			$i = 7;
			foreach ($sales as $sale)
			{
				$date = date_parse($sale['order_date']); 
				$timeStamp = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);  
			
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, $sale['stringer_user_id'])
					->setCellValue('B'.$i, $sale['stringer_username'])
					->setCellValue('C'.$i, $sale['stringer_fullname'])
					->setCellValue('D'.$i, $sale['order_id'])
					->setCellValue('E'.$i, date("n/j/y g:i A",$timeStamp))
					->setCellValue('F'.$i, $sale['quantity'])
					->setCellValue('G'.$i, $sale['currency'])
					->setCellValue('H'.$i, $sale['base_price'])
					->setCellValue('I'.$i, ($sale['quantity']*$sale['price_charged']))
					->setCellValue('J'.$i, $sale['profit'])
					->setCellValue('K'.$i, $sale['charges'])
					->setCellValue('L'.$i, $sale['sales_tax'])
					->setCellValue('M'.$i, $sale['shipping_cost'])
					->setCellValue('N'.$i, $sale['smugmug_name'])
					->setCellValue('O'.$i, $sale['payment_status'])
					->setCellValue('P'.$i, $sale['file_name'])
					->setCellValue('Q'.$i, $sale['category_hierarchy'])
					->setCellValue('R'.$i, $sale['gallery_title'])
					->setCellValue('S'.$i, floatval($sale['commission_percentage']/100));
				$objPHPExcel->getActiveSheet()->getStyle('S'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i.":I".$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$i++;
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Sales Report '.date("Ymd-His", $reportTimestamp));

		
		

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="smugmugsales-'.date("ymd.His").'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: 0'); // Date in the past
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
    }
    
    /*function exportsummarysalesAction() {
    	$startDate = $this->_request->getParam("start_date");
    	$endDate = $this->_request->getParam("end_date");
    	$startDate = substr($startDate,0,10);
    	$endDate = substr($endDate,0,10);
    	if(strlen($startDate) != 10) $startDate = date("Y-m-")."01";
    	if(strlen($endDate) != 10) $endDate = date("Y-m-").date("t");
    	
    	$stringerUserId = $this->_request->getParam("stringer_user_id");
    	
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$this->view->reportTimestamp = mktime();
    	$this->view->startDate = $startDate;
    	$this->view->endDate = $endDate;
    	
    	$this->view->sales = $stringerClass->getStringersForDateRange($this->site_id, $startDate, $endDate);
    	
    	$this->_response->setHeader("Content-Type", "application/vnd.ms-excel; charset=UTF-8", true);
		$this->_response->setHeader("Content-Disposition", "inline; filename=smugmugsummarysales-".date("ymd.His").".xls", true);
		$this->_response->setHeader("Expires", 0, true);
		$this->_response->setHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0", true);
		$this->_response->setHeader("Pragma", "public", true);
    	
    	echo $this->view->render("smugmug_summary_sales.php");
    }*/
	
	function exportsummarysalesAction() {
		require_once 'PHPExcel-1.8/PHPExcel.php';
		
		$startDate = $this->_request->getParam("start_date");
    	$endDate = $this->_request->getParam("end_date");
		$startDate = substr($startDate,0,10);
    	$endDate = substr($endDate,0,10);
    	if(strlen($startDate) != 10) $startDate = date("Y-m-")."01";
    	if(strlen($endDate) != 10) $endDate = date("Y-m-").date("t");
		
    	$stringerUserId = $this->_request->getParam("stringer_user_id");
    	
    	Zend_Loader::LoadClass('stringersClass', $this->modelDir);
    	$stringerClass = new stringersClass();
    	
    	$reportTimestamp = mktime();
    	
    	$sales = $stringerClass->getStringersForDateRange($this->site_id, $startDate, $endDate);
		
		$sd = explode('-',$startDate);
		if($sd[1]<10) $sd[1] = str_replace('0','',$sd[1]);
		if($sd[2]<10) $sd[2] = str_replace('0','',$sd[2]);
		$startDate = $sd[1]."/".$sd[2]."/".substr($sd[0],2);
		$ed = explode('-',$endDate);
		if($ed[1]<10) $ed[1] = str_replace('0','',$ed[1]);
		if($ed[2]<10) $ed[2] = str_replace('0','',$ed[2]);
		$endDate = $ed[1]."/".$ed[2]."/".substr($ed[0],2);
    	
		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("APT")
									 ->setLastModifiedBy("APT")
									 ->setTitle($this->session->site['name']." Smugmug Summary Sales")
									 ->setSubject($this->session->site['name']." Smugmug Summary Sales")
									 ->setDescription($this->session->site['name']." Smugmug Summary Sales")
									 ->setKeywords($this->session->site['name']." Smugmug Summary Sales")
									 ->setCategory("Smugmug Summary Sales");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Site')
					->setCellValue('A2', 'Report Created Date')
					->setCellValue('A3', 'Start Date Period')
					->setCellValue('A4', 'End Date Period');
					
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME2);		
		$objPHPExcel->getActiveSheet()->getStyle('C3')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_MDDYYYYSLASH);					
		$objPHPExcel->getActiveSheet()->getStyle('C4')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_MDDYYYYSLASH);

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C1', $this->session->site['name'])
					->setCellValue('C2', date("m/d/y g:i A", $reportTimestamp))
					->setCellValue('C3', $startDate)
					->setCellValue('C4', $endDate);

		$objPHPExcel->getActiveSheet()->getStyle('A1:C4')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A6', 'Stringer ID')
			  ->setCellValue('B6', 'Username')
			  ->setCellValue('C6', 'Stringer/User Name')
			  ->setCellValue('D6', 'Smugmug Code')
			  ->setCellValue('E6', '% Commission')
			  ->setCellValue('F6', 'Total Qty')
			  ->setCellValue('G6', 'Total Sales')
			  ->setCellValue('H6', 'Total Profit');
			  
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$BStyle = array(
		  'borders' => array(
			'bottom' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THICK
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray($BStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setWrapText(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getRowDimension('6')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->freezePane('A7');
			  
		$objPHPExcel->getActiveSheet()->setAutoFilter('A6:H500');
		$autoFilter = $objPHPExcel->getActiveSheet()->getAutoFilter();
		$autoFilter->getColumn('A')
			->setFilterType(PHPExcel_Worksheet_AutoFilter_Column::AUTOFILTER_FILTERTYPE_FILTER)
			->createRule()
			->setRule(
				PHPExcel_Worksheet_AutoFilter_Column_Rule::AUTOFILTER_COLUMN_RULE_EQUAL,
				''
			);
			
		if(is_array($sales)) 
		{
			$i = 7;
			foreach ($sales as $sale)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, stripslashes($sale['stringer_user_id']))
					->setCellValue('B'.$i, stripslashes($sale['stringer_username']))
					->setCellValue('C'.$i, stripslashes($sale['stringer_fullname']))
					->setCellValue('D'.$i, stripslashes($sale['staff_code']))
					->setCellValue('E'.$i, floatval($sale['commission_percentage']/100))
					->setCellValue('F'.$i, intval($sale['total_qty']))
					->setCellValue('G'.$i, floatval($sale['total_sales']))
					->setCellValue('H'.$i, floatval($sale['total_profit']));
				$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
				$objPHPExcel->getActiveSheet()->getStyle('G'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$objPHPExcel->getActiveSheet()->getStyle('H'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$i++;
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Sales Report '.date("Ymd-His", $reportTimestamp));	
		

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="smugmugsummarysales-'.date("ymd.His").'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: 0'); // Date in the past
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}
    
    function exportstringersAction() {
    	require_once($this->modelDir."/dbClass.php");
    	$stringerUserTable = new stringer_users(array('db'=>'db'));
    	
    	$this->view->reportTimestamp = $reportTimestamp = mktime();
    	
    	$select = $stringerUserTable->select()->where("site_id=?", $this->site_id)->order(array("stringer_username"));
    	$this->view->users = $users = $stringerUserTable->getAdapter()->fetchAll($select);
    	
		require_once 'PHPExcel-1.8/PHPExcel.php';

		// Create new PHPExcel object
		$objPHPExcel = new PHPExcel();

		// Set document properties
		$objPHPExcel->getProperties()->setCreator("APT")
									 ->setLastModifiedBy("APT")
									 ->setTitle($this->session->site['name']." Stringers")
									 ->setSubject($this->session->site['name']." Stringers")
									 ->setDescription($this->session->site['name']." Stringers")
									 ->setKeywords($this->session->site['name']." Stringers")
									 ->setCategory("Stringers");


		// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A1', 'Site')
					->setCellValue('A2', 'Report Created Date');
					
		$objPHPExcel->getActiveSheet()->getStyle('C2')->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DATETIME2);		

		// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('C1', $this->session->site['name'])
					->setCellValue('C2', date("m/d/y g:i A", $reportTimestamp));

		$objPHPExcel->getActiveSheet()->getStyle('A1:C2')->getFont()->setBold(true);
		
		$objPHPExcel->getActiveSheet()->setCellValue('A4', 'User ID')
			  ->setCellValue('B4', 'Username')
			  ->setCellValue('C4', 'Full Name')
			  ->setCellValue('D4', 'Staff Code')
			  ->setCellValue('E4', 'Commission Percentage')
			  ->setCellValue('F4', 'Total Sales')
			  ->setCellValue('G4', 'Total Qty');
			  
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFont()->setBold(true);
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_BLUE);
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$BStyle = array(
		  'borders' => array(
			'bottom' => array(
			  'style' => PHPExcel_Style_Border::BORDER_THICK
			)
		  )
		);
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->applyFromArray($BStyle);
		$objPHPExcel->getActiveSheet()->getStyle('A4:G4')->getAlignment()->setWrapText(TRUE);
		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(30);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
		$objPHPExcel->getActiveSheet()->getRowDimension('4')->setRowHeight(30);
		$objPHPExcel->getActiveSheet()->freezePane('A5');
			  
		$objPHPExcel->getActiveSheet()->setAutoFilter('A4:G500');
		$autoFilter = $objPHPExcel->getActiveSheet()->getAutoFilter();
			
		if(is_array($users)) 
		{
			$i = 5;
			foreach ($users as $user)
			{
				$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('A'.$i, stripslashes($user['stringer_user_id']))
					->setCellValue('B'.$i, stripslashes($user['stringer_username']))
					->setCellValue('C'.$i, stripslashes($user['stringer_fullname']))
					->setCellValue('D'.$i, stripslashes($user['staff_code']))
					->setCellValue('E'.$i, floatval($user['commission_percentage']/100))
					->setCellValue('F'.$i, (floatval($user['total_sales'])+floatval($user['original_sales'])))
					->setCellValue('G'.$i, (intval($user['total_qty'])+intval($user['original_qty'])));
				$objPHPExcel->getActiveSheet()->getStyle('E'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_PERCENTAGE);
				$objPHPExcel->getActiveSheet()->getStyle('F'.$i)->getNumberFormat()->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
				$i++;
			}
		}
		
		// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Stringers '.date("Ymd-His", $reportTimestamp));	
		

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		// Redirect output to a client’s web browser (Excel5)
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="stringers-'.date("ymd.His").'.xls"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header ('Expires: 0'); // Date in the past
		header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header ('Pragma: public'); // HTTP/1.0

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
		
    	/*$this->_response->setHeader("Content-Type", "application/vnd.ms-excel; charset=UTF-8", true);
		$this->_response->setHeader("Content-Disposition", "inline; filename=stringers-".date("ymd.His").".xls", true);
		$this->_response->setHeader("Expires", 0, true);
		$this->_response->setHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0", true);
		$this->_response->setHeader("Pragma", "public", true);
    	
    	echo $this->view->render("xls_stringers.php");*/
    }
}
