<?php
require_once('actionControllerBase.php');
class Admin_SiteController extends actionControllerBase 
{

	/**
	 * Will set the valid of the site id that the user is administrating.
	 *
	 */
	function setsiteidAction()
	{
        Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass();
		$res =  $site->setSite($this->_request->getParam('site_id'));
		if ( $res == true )
		{
			$this->site_id = $this->session->site['site_id'];
			// Redirect to main page
			Zend_Registry::set('site_id', $this->site_id);
			$this->getResponse()->setRedirect($this->config->paths->url);
			$this->getResponse()->sendResponse();
			exit;
		}
		else
		{
			// Raise an exception here
			echo "ERROR: Could not set siteid.";
		}

	}
	
	function manageAction()
	{	
		set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(39, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
			
    	echo $this->view->render('header.php');
    	echo $this->view->render('setup_manage.php');
        echo $this->view->render('footer.php');
	}
	
	function managesiteAction()
	{		
		set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(38, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
    	echo $this->view->render('header.php');
    	echo $this->view->render('site_manage.php');
        echo $this->view->render('footer.php');
	}

    /**
     * AJAX / JSON action which delivers a list of all sites
     *
     */
    function getsitesAction()
    {
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = $site->getSites();
    	
    	echo json_encode($response);
    }
    
    
	
  	function removexmlfilesAction() {
    	set_time_limit(1800);
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteModel = new siteClass();
    	$sites = $siteModel->getSites();
    	foreach ($sites as $site) {
	    	$xmlDonePath = $this->config->paths->sitepath."/".strtolower($site["name"])."/ftp/editorial/done";
	    	$xmlFailedPath = $this->config->paths->sitepath."/".strtolower($site["name"])."/ftp/editorial/failed";
	    	$configFile = $this->config->paths->sitepath."/".strtolower($site["name"])."/config.ini";
	    	if(file_exists($configFile)) {
				$siteConfigs = parse_ini_file( $configFile, true );	
				if(!empty($siteConfigs['cms']['remove_xml_files_per_day']) && $siteConfigs['cms']['remove_xml_files_per_day'] > 0)
					$day = $siteConfigs['cms']['remove_xml_files_per_day'];
				else 
					$day = 31;	
				
				if(is_dir($xmlDonePath)) {
			    	if($handler = opendir($xmlDonePath)) {
						while($filename = readdir($handler))  {
							$getType = explode('.', $filename);
							if($getType[count($getType)-1] == 'xml')
							{
								$curDate = date("Ymd", mktime(0,0,0, date("m"), date("d")-$day, date("Y")));
								
								if($curDate > date("Ymd", filemtime($xmlDonePath."/".$filename))) {
									@unlink($xmlDonePath."/".$filename);
								}
							}
						}
						closedir($handler);
					}
		    	}	
		    	
		    	if(is_dir($xmlFailedPath)) {
			    	if($handler = opendir($xmlFailedPath)) {
						while($filename = readdir($handler))  {
							$getType = explode('.', $filename);
							if($getType[count($getType)-1] == 'xml')
							{
								$curDate = date("Ymd", mktime(0,0,0, date("m"), date("d")-$day, date("Y")));
								
								if($curDate > date("Ymd", filemtime($xmlFailedPath."/".$filename))) {
									@unlink($xmlFailedPath."/".$filename);
								}
							}
						}
						closedir($handler);
					}
		    	}		
	    	}
	    	sleep(2);
    	}
    }
    
    function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755))
    {
        $result=false;
       
        if (is_file($source)) {
			if(basename($source) != "Thumbs.db")
			{
	            if ($dest[strlen($dest)-1]=='/') {
	                if (!file_exists($dest)) {
	                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true);
	                }
	                $__dest=$dest."/".basename($source);
	            } else {
	                $__dest=$dest;
	            }
	            $result=copy($source, $__dest);
	            chmod($__dest,$options['filePermission']);
			}
           $result=true;
        } elseif(is_dir($source)) {
            if ($dest[strlen($dest)-1]=='/') {
                if ($source[strlen($source)-1]=='/') {
                    //Copy only contents
                } else {
                    //Change parent itself and its contents
                    $dest=$dest.basename($source);
                    @mkdir($dest);
                    chmod($dest,$options['filePermission']);
                }
            } else {
                if ($source[strlen($source)-1]=='/') {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                } else {
                    //Copy parent directory with new name and all its content
                    @mkdir($dest,$options['folderPermission']);
                    chmod($dest,$options['filePermission']);
                }
            }

            $dirHandle=opendir($source);
            while($file=readdir($dirHandle))
            {
                if($file!="." && $file!="..")
                {
                     if(!is_dir($source."/".$file)) {
                        $__dest=$dest."/".$file;
                    } else {
                        $__dest=$dest."/".$file;
                    }
                    //echo "$source/$file ||| $__dest<br />";
                    $result=$this->smartCopy($source."/".$file, $__dest, $options);
                }
            }
            closedir($dirHandle);
            $result=true;
        } else {
            $result=false;
        }
        return $result;
    } 
     
    function setupsiteAction()
	{
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass();
    	
    	$cursite = $site->getSite($this->site_id);
    	
    	// create site folder
    	mkdir($this->config->paths->sitepath."/".$cursite['name']);
    	
    	$prototype_path = $this->config->paths->sitepath.'/_prototype';
    	$sitepath = $this->config->paths->sitepath."/".$cursite['name'];
    	
    	//copying ftp folder
    	if ($this->smartCopy($prototype_path.'/ftp', $sitepath.'/ftp') == false) {
		    $response['success'] = false;
	    	$response["errorInfo"] = "Can not copy ftp folder<br />Solution: Change folder permission to be writable by \"apache\" user.";
	    	echo json_encode($response);
		}
    	
    	//copying html file
    	if ($this->smartCopy($prototype_path.'/html', $sitepath.'/html') == false) {
		    $response['success'] = false;
	    	$response["errorInfo"] = "Can not copy html file<br />Solution: Change file permission to be writable by \"apache\" user.";
	    	echo json_encode($response);
		}
		
		// copying config.ini file
		if ($this->smartCopy($prototype_path.'/config.ini', $sitepath.'/config.ini') == false) {
		    $response['success'] = false;
	    	$response["errorInfo"] = "Can not copy config.ini file<br />Solution: Change file permission to be writable by \"apache\" user.";
	    	echo json_encode($response);
		}
		
		//copy menu
		Zend_Loader::LoadClass('contentareasClass', $this->modelDir);
    	$contentareas= new contentareasClass();
    	$menu = $contentareas->getContentAreas('0');
    	foreach ($menu as $m)
    	{
    		$contentareas->addContentArea($this->site_id, $m);
    	}    	
		
		$response['success'] = true;
    	echo json_encode($response);
    	
	}
	
	function addsiteAction()
    {    	
    	$params = $this->_getAllParams();
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass();
		$res =  $site->addSite($params);
		//$this->cleanCache();
    }
    
    function getsitebyidAction()
    {
    	Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass();
    	
    	$params = $this->_getAllParams();
    	
    	$response['success'] = true;
    	$response['data'] = array();
    	$response['data'] = $site->getSite($params['site_id']);
   		
    	echo json_encode($response);
    } 
    
    function setsitebyidAction()
    {
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass(); 
    	
    	$params = $this->_getAllParams();   	
    	
    	$site->updateSite($params);
    	//$this->cleanCache();
    } 
    
    function deletesitesAction()
    {
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$site = new siteClass(); 
    	
		$params =  $this->_request->getParam('datas');

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	foreach ($params as $site_id) {
	    		$site->deleteSite($site_id);
	    	}
	    	//$this->cleanCache();
    	}
    }
    
    function cleancacheAction() {
		$this->view->ret = $this->cleanCache();
        echo $this->view->render('header.php');
        echo $this->view->render('cleancache.php');
        echo $this->view->render('footer.php');
	}
}
?>
