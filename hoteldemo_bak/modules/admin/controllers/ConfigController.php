<?php
require_once('actionControllerBase.php');
class Admin_ConfigController extends actionControllerBase 
{
    
    /**
     * Action which provides the opportunity to view and modify a list of configs.
     *
     */
    function manageAction()
    {
    	$readOnly = FALSE;
    	if($this->ident["role"] != "super_admin") {
			Zend_Loader::LoadClass('userClass', $this->modelDir);
	    	$userClass = new userClass();
	    	$privilege = $userClass->getUserPrivilige(12, $this->ident["adminuserid"]);
	    	if($privilege & 2) $readOnly = TRUE;
		}
		$this->view->readOnly = $readOnly;
    	
    	$basePath 		= dirname(dirname(dirname(dirname(__FILE__))));
    	$basePath		= str_replace("\\", "/", $basePath);
    	
    	$configFile 	= $basePath."/sites/".$this->session->site['name']."/config.ini";
    	
		if(file_exists($configFile)) {
			$configs = new iniParser($configFile);
			$siteConfigs = $configs->getConfigArray();
			
			$prototypeFile 	= $basePath."/sites/_prototype/config.ini";
			$configs 		= new iniParser($prototypeFile);
			$prototypeConfigs = $configs->getConfigArray();
			
			$prototypeOptionsFile 	= $basePath."/sites/_prototype/config_options.ini";
			$configs 		= new iniParser($prototypeOptionsFile);
			$prototypeConfigOptions = $configs->getConfigArray();
			
			$prototypeTooltipFile 	= $basePath."/sites/_prototype/config_tooltip.ini";
			$configs 		= new iniParser($prototypeTooltipFile);
			$prototypeConfigTooltip = $configs->getConfigArray();
			
			$siteConfigs = $configs->iniMerge($prototypeConfigs, $siteConfigs);
			
			$customConfigs = array();
			foreach ($siteConfigs as $key=>$configItems) {
				$customConfig = array();
				$customConfig["name"] = $key;
				foreach ($configItems as $itemKey=>$itemVal) {
					$customConfig["items"][] = array(
						"name"	=> $itemKey,
						"value"	=> str_replace("&amp;", "&", $itemVal)
					);
				}
				$customConfigs[] = $customConfig;
			}
			
			$this->view->configs = $customConfigs;
			$this->view->configOptions = $prototypeConfigOptions;
			$this->view->configTooltip = $prototypeConfigTooltip;
		}
    	echo $this->view->render('header.php');
    	echo $this->view->render('config_manage.php');
        echo $this->view->render('footer.php');
    }
    
    /**
     * AJAX / JSON action which delivers a list of all configs for the current site from test.
     *
     */
    function getconfigsAction()
    {
    	$basePath 		= dirname(dirname(dirname(dirname(__FILE__))));
    	$basePath		= str_replace("\\", "/", $basePath);
    	
    	$configFile 	= $basePath."/sites/".$this->session->site['name']."/config.ini";
    	$configs = new iniParser($configFile);
    	$siteConfigs = $configs->getConfigArray();
    	
    	$prototypeFile 	= dirname($basePath)."/test/sites/_prototype/config.ini";
    	$configs 		= new iniParser($prototypeFile);
    	$prototypeConfigs = $configs->getConfigArray();
    	
    	$siteConfigs = $configs->iniMerge($prototypeConfigs, $siteConfigs);
    	
    	$customConfigs = array();
    	foreach ($siteConfigs as $key=>$configItems) {
    		foreach ($configItems as $itemKey=>$itemVal) {
    			$customConfigs[$key."[".$itemKey."]"] = str_replace("&amp;", "&", $itemVal);
    		}
    	}
    	
    	$response['data'] = $customConfigs;
    	$response['success'] = true;
    	echo json_encode($response);
    }
    
    /**
     * AJAX / JSON action which will modify config data and then return a list of all configs for the current site.
     *
     */
    function setconfigsAction()
    {
		$params = $this->_getAllParams();
		unset($params["controller"]);
		unset($params["action"]);
		unset($params["module"]);
		
		$basePath 		= dirname(dirname(dirname(dirname(__FILE__))));
    	$basePath		= str_replace("\\", "/", $basePath);
    	$configFile 	= $basePath."/sites/".$this->session->site['name']."/config.ini";
	
    	$configs = new iniParser();
		$configs->setConfigArray($params);
    	$result = $configs->save($configFile);
    	$response['success'] = $result;
    	if(!$result) $response["errorInfo"] = "Failed to write the config.ini file<br />Solution: Change file permission to be writable by \"apache\" user.";
    	echo json_encode($response);
    }
    
    /**
     * AJAX / JSON action which will save and  migrate config data for selected tab from test to prod
     *
     */
    function migrateconfigsAction()
    {
		$params = $this->_getAllParams();
		unset($params["controller"]);
		unset($params["action"]);
		unset($params["module"]);
		
		$basePath 		= dirname(dirname(dirname(dirname(dirname(__FILE__)))));
    	$basePath		= str_replace("\\", "/", $basePath);
    	$configTestFile 	= $basePath."/test/sites/".$this->session->site['name']."/config.ini";
		$configProdFile 	= $basePath."/prod/sites/".$this->session->site['name']."/config.ini";
		
		$configProd = parse_ini_file($configProdFile, true);
		//$configProd[$params['tab']] = $params[$params['tab']];
		foreach ($params[$params['tab']] as $itemKey=>$itemVal) {
			if($itemKey != 'rcaString')
			{
				$configProd[$params['tab']][$itemKey] = $params[$params['tab']][$itemKey];
			}
		}
    	
		unset($params["tab"]);
    	$configs = new iniParser();
    	$configs->setConfigArray($params);
    	$configs->save($configTestFile);
    	
		$configs->setConfigArray($configProd);
    	$result = $configs->save($configProdFile);
    	$response['success'] = $result;
    	if(!$result) $response["errorInfo"] = "Failed to write the config.ini file<br />Solution: Change file permission to be writable by \"apache\" user.";
    	echo json_encode($response);
    }
}

class iniParser {
	
	var $_iniFilename = '';
	var $_iniParsedArray = array();
	
	function iniParser( $filename= "" )
	{
		if(!empty($filename)) {
			$this->_iniFilename = $filename;
			if($this->_iniParsedArray = parse_ini_file( $filename, true ) ) {
				return true;
			} else {
				return false;
			} 
		}
	}
	
	function getConfigArray() {
		return $this->_iniParsedArray;
	}
	
	function setConfigArray($arr) {
		$this->_iniParsedArray = $arr;
	}
	
	function iniMerge ($config_ini, $custom_ini) {
		foreach ($custom_ini AS $k => $v) {
			if (is_array($v)) {
				$config_ini[$k] = $this->iniMerge($config_ini[$k], $custom_ini[$k]);
			} else {
				$config_ini[$k] = $v;
			}
		}
		return $config_ini;
	}
	
	function getSection( $key )
	{
		return $this->_iniParsedArray[$key];
	}
	
	function getValue( $section, $key )
	{
		if(!isset($this->_iniParsedArray[$section])) return false;
		return $this->_iniParsedArray[$section][$key];
	}
	
	function get( $section, $key=NULL )
	{
		if(is_null($key)) return $this->getSection($section);
		return $this->getValue($section, $key);
	}
	
	function setSection( $section, $array )
	{
		if(!is_array($array)) return false;
		return $this->_iniParsedArray[$section] = $array;
	}
	
	function setValue( $section, $key, $value )
	{
		if( $this->_iniParsedArray[$section][$key] = $value ) return true;
	}
	
	function set( $section, $key, $value=NULL )
	{
		if(is_array($key) && is_null($value)) return $this->setSection($section, $key);
		return $this->setValue($section, $key, $value);
	}
	
	function save( $filename = null )
	{
		if( $filename == null ) $filename = $this->_iniFilename;
		if( !file_exists($filename) || is_writeable( $filename ) ) {
			$SFfdescriptor = @fopen( $filename, "w" );
			if($SFfdescriptor) {
				foreach($this->_iniParsedArray as $section => $array){
					fwrite( $SFfdescriptor, "[" . $section . "]\n" );
					foreach( $array as $key => $value ) {
						if(is_numeric($value) && !in_array($key, array("frametypeid","aracct","defaultSICCode","web_sectionid"))) 
							fwrite( $SFfdescriptor, "$key = $value\n" );
						else
							fwrite( $SFfdescriptor, "$key = \"$value\"\n" );
					}
					fwrite( $SFfdescriptor, "\n" );
				}
				fclose( $SFfdescriptor );
				return true;
			}
			return false;
		} else {
			return false;
		}
	}
	
	
}
?>
