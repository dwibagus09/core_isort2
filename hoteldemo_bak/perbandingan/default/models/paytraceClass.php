<?php
class PaytraceClass  {
    protected $validateURL = "https://paytrace.com/api/validate.pay";
    protected $defaultURL = "https://paytrace.com/api/default.pay";
	protected $db;
	protected $config;
	protected $postString = '';
	protected $siteid;
	
	function __construct() {
		$this->config = Zend_Registry::get('config');
        $this->siteid = $this->config->general->siteid;
        $params = explode(';', $this->config->online_payment->merchant_data);
        $merchantData = array();
        if(is_array($params)) foreach ($params as $param) {
        	$param = trim($param);
        	$temp = explode('=', $param);
        	if(count($temp) == 2) {
        		$temp[0] = trim($temp[0]);
        		$merchantData[$temp[0]] = trim($temp[1]);
        	}
        }
		$this->postString='UN~'.$merchantData['UN'];	
		$this->postString.='|PSWD~'.$merchantData['PSWD'];
		$this->profileId = $merchantData['UN'];
		$this->profileKey = $merchantData['PSWD'];
	}
	
	function getAuthID($params) {
		$this->postString .= "|TERMS~Y|TRANXTYPE~Sale|ORDERID~{$params['order_id']}|AMOUNT~{$params['amount']}|RETURNURL~".urlencode($params['silence_url'])."|APPROVEURL~".urlencode($params['return_url'])."|DECLINEURL~".urlencode($params['failure_url']);
		$response = $this->curlRequest(true);
		$responseFields = explode("|",$response);
		$responseValues = array();
        foreach($responseFields as $field)
        {
            $nameValue = explode("~",$field);
            $responseValues[strtoupper($nameValue[0])] = $nameValue[1];
        }
        return $responseValues;
	}
	
	function curlRequest($useValidate = false) {
		$header = array("MIME-Version: 1.0","Content-type: application/x-www-form-urlencoded","Contenttransfer-encoding: text");
		$this->postString = "PARMLIST=".$this->postString;
		
		if($useValidate)
			$url = $this->validateURL;
		else
			$url = $this->defaultURL;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		/*curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		//Depending on your PHP Host, you may need to specify their proxy server
		//curl_setopt ($ch, CURLOPT_PROXY, "http://proxyaddress:port");*/
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postString);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
		// grab URL and pass it to the browser
		$response = curl_exec($ch);
		// close curl resource, and free up system resources
		
		if($response === false) {
			require_once 'Zend/Mail.php';
			$mail = new Zend_Mail();
			$mail->setBodyText("Urgent, Paytrace CURL error: ".curl_error($ch)."\nURL: ".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
			$mail->setFrom("onlineads@apthosts.com");
			
			$mail->addTo("jhoni_chen@yahoo.com");
			$mail->addTo("diane.duren@advpubtech.com");
			
			$subject = "IMPORTANT, Paytrace Payment CURL Error, user is not able to submit payment";
			
			$mail->setSubject($subject);
			try {
				$mail->send();
			} catch ( Exception $e ) {
				;
			}
		}
		
		curl_close($ch);
		/*
		$curdir = dirname(__FILE__);
		$env = "prod";
		if(strpos($curdir, '/test/')) $env = "test"; 
		$uniqFile = "mkt".time().".txt";
		$cmd = "wget -q '{$url}?{$this->postString}' -O /var/www/onlineads_3.1.5/{$env}/sites/{$this->config->general->siteName}/{$uniqFile}";
		exec($cmd);
		$response = @file_get_contents("/var/www/onlineads_3.1.5/{$env}/sites/{$this->config->general->siteName}/{$uniqFile}");
		@unlink("/var/www/onlineads_3.1.5/{$env}/sites/{$this->config->general->siteName}/{$uniqFile}");
		*/
       	return $response;
	}
	
	function emailReceipt($transactionId, $email) {
		$this->postString='UN~'.$this->profileId;	
		$this->postString.='|PSWD~'.$this->profileKey;
		$this->postString .= "|TERMS~Y|METHOD~EmailReceipt";
		$this->postString .= "|TRANXID~".$transactionId;
		$this->postString.='|EMAIL~'.$email;
       	$response = $this->curlRequest();
        return TRUE;
	}
	
	function voidTransaction($transactionId) {
		$this->postString='UN~'.$this->profileId;	
		$this->postString.='|PSWD~'.$this->profileKey;
		$this->postString .= "|TERMS~Y|METHOD~ProcessTranx|TRANXTYPE~Void";
		$this->postString .= "|TRANXID~".$transactionId;
       	$response = $this->curlRequest();
        return TRUE;
	}
	
	function exportTransaction($transactionId) {
		$this->postString='UN~'.$this->profileId;	
		$this->postString.='|PSWD~'.$this->profileKey;
		$this->postString .= "|TERMS~Y|METHOD~ExportTranx";
		$this->postString .= "|TRANXID~".$transactionId;
       	$response = $this->curlRequest();
       	$temp = explode("~", $response);
       	if(count($temp) > 1) $response = $temp[1];
        $transactions = explode("|",$response);
        $responseFields = explode("+", $transactions[0]);
		$responseValues = array();
        foreach($responseFields as $field)
        {
            $nameValue = explode("=",$field);
            $responseValues[strtoupper($nameValue[0])] = $nameValue[1];
        }
        return $responseValues;
	}
}
?>