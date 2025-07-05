<?php
require_once 'Zend/Auth/Adapter/Interface.php'; 

class adminAuth implements Zend_Auth_Adapter_Interface
{
	
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
        $this->_password = (string) md5($password);
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
        $sql =  "select * from admin_users ";
        $sql .= "where username='$this->_username' and password='$this->_password'";
        $result = $db->fetchAll($sql);          
        
        if (count($result)==1)
        {
            list($key, $val) = each($result);
            $result['isValid'] = true;
            $result['identity'] = array ("admin_user_id"=>$val['admin_user_id'],"username"=>$val['username']);
            $result['messages'] = array('Authentication Successful.');
        }
        else
        {
            $result['isValid'] = false;  
            $result['identity'] = array ("userid"=>"guest");
            $result['messages'] = array('Invalid Username or Password');  
        }
        
        return new Zend_Auth_Result($result['isValid'], $result['identity'], $result['messages']);
    }
}
  
?>
