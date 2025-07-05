<?php
require_once('actionControllerBase.php');
class Admin_ImportController extends actionControllerBase 
{
	
	function importxmlAction()
    {    	
    	set_time_limit(7200);
    	
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(8, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
		
        echo $this->view->render('header.php');
        echo $this->view->render('import_xml.php');
        echo $this->view->render('footer.php');
    }
    
    public function uploadxmlAction() {
		set_time_limit(0);
		$editorialPath = $this->config->paths->sitepath."/".strtolower($this->session->site['name'])."/ftp/editorial/";
		$configFile = $this->config->paths->sitepath."/".strtolower($this->session->site['name'])."/config.ini";
		$configs = parse_ini_file( $configFile, true );
		$msg = "upload files failed";
		$rs = false;
		if(is_array($_FILES)) 
		{
			foreach ($_FILES as $key=>$val) {
				if($_FILES[$key]["type"]==  "text/xml" || $_FILES[$key]["type"]==  "image/tiff") {
					$fileName = $_FILES[$key]["name"];
					if(move_uploaded_file($_FILES[$key]["tmp_name"], $editorialPath.$fileName)) {
						$rs = true;
						$getType = explode('.', $fileName);
		    	
				    	if($getType[count($getType)-1] == 'xml')
						{
							$ret = $this->processXMLFile($configs["general"]["url"]."/xml/loadeditorialcontentbyfilename/file/".$fileName);
					
							$response = json_decode($ret, true);
					
							if($response['success'] == true)	
							{
								$msg = "upload files success";
								$this->cleanCache();
							}
							else
								$msg = $response['message'].' for '.$fileName;
						}
					}
					else
					{
						$rs = false;
						break;
					}
				}
			}
			echo $msg;
		}
	}
	
	function getxmlwaitingAction() {
		$editorialPath = $this->config->paths->sitepath."/".strtolower($this->session->site['name'])."/ftp/editorial/";
		$data = array();
		if ($handle = opendir($editorialPath)) {
			$i = 0;
		    while (false !== ($file = readdir($handle))) {
		    	$getType = explode('.', $file);
		    	
		    	//if($file != "." && $file != ".." && $file != "Thumbs.db" && $file != "done" && $file != "failed" && $file != "temp.txt" && $getType[count($getType)-1] == 'xml')
		    	if($getType[count($getType)-1] == 'xml')
				{
		        	$arrRows[$i]['filename'] = $file;
		        	$i++;
				}
		    }
		    closedir($handle);
		}
		if(!empty($arrRows))
			$data["data"] = $arrRows;
		else
			$data["data"] = "";
		echo json_encode($data);
	}
	
	function importxmlbyfilenameAction()
	{    	
		$params =  $this->_request->getParam('datas');
		$configFile = $this->config->paths->sitepath."/".strtolower($this->session->site['name'])."/config.ini";
		$configs = parse_ini_file( $configFile, true );

    	if(!empty($params))
    	{
	    	$params = explode(",", $params);
	    	$i = 0;
	    	$k = 0;
	    	$failedMsg = "";
	    	$msg = "";
	    	foreach ($params as $filename) {
	    		$ret = $this->processXMLFile($configs["general"]["url"]."/xml/loadeditorialcontentbyfilename/file/".$filename);
                
                $response = json_decode($ret, true);
				if($response['success'] == true)	
				{
					$successfulFiles[$i]['filename'] = $filename;
					$successfulFiles[$i]['section'] = $response['section'][0];
					$successfulFiles[$i]['headline'] = $response['headline'];
					$msg = $msg . "The XML for ".$filename." is complete and successful"."<br/>";
					$i++;					
				}
				else
				{
					$unsuccessfulFiles[$k]['filename'] = $filename;
					$unsuccessfulFiles[$k]['section'] = $response['section'][0];
					$unsuccessfulFiles[$k]['headline'] = $response['headline'];
					$failedMsg = $failedMsg."The XML for ".$filename." is not complete and was a failure. Reason:".$response['message']."<br/>";
					$reason[$k] = $response['message'];
					$k++;
				}
	    	}
	    	
	    	//if($response['success'] == true)
    		/*if($i == 1)
    			$msg = "1 File Successfully Imported.<br/>";
    		elseif($i > 1)
    			$msg = $i." Files Successfully Imported.<br/>";*/
    			
    		$text = "";
    		$hostName = $_SERVER['SERVER_NAME'];	
    		if($i > 0)
    		{		        
		        $text .=  "<h3>Successfully processed XML for ".$hostName."</h3>";
		        
		    	$text .= '<table border="1" cellpadding="0" cellspacing="0"><tr style="background-color:#eeeeee; font-weight:bold"><td align="center" style="padding:3px 5px;">No</td><td align="center" style="padding:3px 5px;">Filename</td><td align="center" style="padding:3px 5px;">Section</td><td align="center" style="padding:3px 5px;">Title</td></tr>';
		    	$j = 1;
		    	foreach ($successfulFiles as $successfulFile)
		    	{
		    		$text .= '<tr><td align="center" style="padding:3px 5px;">'.$j.'</td><td align="center" style="padding:3px 5px;">'.basename($successfulFile['filename']).'</td><td align="center" style="padding:3px 5px;">'.$successfulFile['section'].'</td><td align="center" style="padding:3px 5px;">'.htmlentities(stripslashes($successfulFile['headline']), ENT_QUOTES).'</td><td align="center" style="padding:3px 5px;">'.$reason[$l-1].'</td></tr>';
		    		$j++;
		    	}
		    	$text .= '</table>';    			
    		}
	    	
    		if($k > 0)
    		{
    			$text .=  "<h3>Unsuccessfully processed XML for ".$hostName."</h3>";
		        
		    	$text .= '<table border="1" cellpadding="0" cellspacing="0"><tr style="background-color:#eeeeee; font-weight:bold"><td align="center" style="padding:3px 5px;">No</td><td align="center" style="padding:3px 5px;">Filename</td><td align="center" style="padding:3px 5px;">Section</td><td align="center" style="padding:3px 5px;">Title</td><td align="center" style="padding:3px 5px;">Reason</td></tr>';
		    	$l = 1;
		    	foreach ($unsuccessfulFiles as $unsuccessfulFile)
		    	{
		    		$text .= '<tr><td align="center" style="padding:3px 5px;">'.$l.'</td><td align="center" style="padding:3px 5px;">'.basename($unsuccessfulFile['filename']).'</td><td align="center" style="padding:3px 5px;">'.$unsuccessfulFile['section'].'</td><td align="center" style="padding:3px 5px;">'.htmlentities(stripslashes($unsuccessfulFile['headline']), ENT_QUOTES).'</td><td align="center" style="padding:3px 5px;">'.$reason[$l-1].'</td></tr>';
		    		$l++;
		    	}
		    	$text .= '</table>';
    		}
    		
    		require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyHtml($text);
			$mail->setFrom('just4u209@gmail.com', 'APT CMS Application');
			$mail->addTo('just4u209@gmail.com', 'Emma');
			//$mail->addTo('diane.duren@advpubtech.com', 'Diane Duren');
			$mail->setSubject("XML Processing for ".$hostName);
			$mail->send();
    		
	    	echo substr($msg.$failedMsg,0,-5);
    	}		
	}
	
	function processXMLFile($filename)
	{
		$crl = curl_init();
        curl_setopt ($crl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt ($crl, CURLOPT_URL, $filename);
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