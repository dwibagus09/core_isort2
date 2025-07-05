<?php

require_once('Zend/Auth/Adapter/Interface.php');
require_once('dbClass.php');

class Falcon_Auth_Adapter implements Zend_Auth_Adapter_Interface
{
	/**
    * siteid
    *
    * @var string
    */   
	protected $_siteid;

	/**
    * Username
    *
    * @var string
    */   
	protected $_username;

	/**
    * Password 
    *
    * @var string
    */
	protected $_password;

	/**
    * Sets adapter options
    * @param  mixed $username
    * @param  mixed $password
    * @return void
    */
	public function __construct($username = null, $password = null)
	{
		$this->config = Zend_Registry::get("config");
		$options = array('username', 'password');
		foreach ($options as $option)
		{
			if (null !== $$option)
			{
				$methodName = 'set' . ucfirst($option);
				$this->$methodName($$option);
			}
		}
	}

	/**
    * Returns the username option value or null if it has not yet been set
    *
    * @return string|null
    */
	public function getUsername()
	{
		return $this->_username;
	}

	/**
    * Sets the username option value
    *
    * @param  mixed $username
    * @return Falcon_Auth_Adapter. Provides a fluent interface
    */
	public function setUsername($username)
	{
		$this->_username = (string) $username;
		return $this;
	}

	/**
    * Returns the password option value or null if it has not yet been set
    *
    * @return string|null
    */
	public function getPassword()
	{
		return $this->_password;
	}

	/**
    * Sets the password option value
    *
    * @param  mixed $password
    * @return Falcon_Auth_Adpapter. Provides a fluent interface
    */
	public function setPassword($password)
	{
		$this->_password = (string) $password;
		return $this;
	}

	/**
    * Handles the authentication request
    *
    * 
    * @return Zend_Auth_Result object
    */ 
	public function authenticate()
	{
		$optionsRequired = array('username', 'password');
		
		foreach ($optionsRequired as $optionRequired)
		{
			if (null === $this->{"_$optionRequired"})
			{
				require_once 'Zend/Auth/Adapter/Exception.php';
				throw new Zend_Auth_Adapter_Exception("Option '$optionRequired' must be set before authentication.");
			}
		}

		$db = Zend_Registry::get('db');
		$usersTable = new users(array('db' => 'db')); //use db object from registry

		$select = $db->select();
		/*
		$select = $usersTable->select();
		$select->where('username = ?', $this->_username);
		$select->where('password = ?', md5($this->_password));*/
		
		$select->from(array("u"=>"users"), array("u.*"))
			->joinLeft(array("s"=>"sites"), "s.site_id=u.site_id", array("s.*"))
			->where("u.username=?", $this->_username)
			->where("u.password=?", md5($this->_password));
		$result = $db->fetchAll($select);
		
		if (!empty($result)/* && $result[0]['user_id'] == 1*/) {
			list($key, $val) = each($result);
			$user = $val;
			$user['role_ids'] = explode(",",$user['role_id']);
			
			//unset($user["password"]);
			$id['isValid'] = true;
			$id['identity'] = $user;
			$id['messages'] = array('Authentication Successful.');
		}
		else
		{
			$id['isValid'] = false;
			$id['identity'] = array ("userid"=>"guest");
			$id['messages'] = array('Invalid Username or Password');
		}
		return new Zend_Auth_Result($id['isValid'], $id['identity'], $id['messages']);
	}
}
