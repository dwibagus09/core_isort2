<?php

/**
 * Zend_Session_SaveHandler_Interface
 */
require_once 'Zend/Session/SaveHandler/Interface.php';

class falconSessionSaveHandler implements Zend_Session_SaveHandler_Interface
{
    /**
     * @var object instance of Zend_Db_Adapter_Abstract
     */
    protected $_db;
    
    /**
     * @var integer maximum life time
     */
    protected $_lifeTime;
    
    /**
     * @var array table definitions
     */
    protected $_def = array(
        'table'   => 'sessions',
        'primary' => 'sessionId',
        'user'    => 'userId',
        'expire'  => 'expires',
        'data'    => 'data'
    );
    
    /**
     * Constructor to set db handler and table definitions
     */
    public function __construct(Zend_Db_Adapter_Abstract $db, $cfg, $tableDef = array())
    {
        // set db adapter
        $this->_db = $db;
        
        // set life time
        $this->_lifeTime = $cfg->session->gc_maxlifetime; 
        
        // set table definitions if passed
        foreach($tableDef as $key => $value)
        {
            if (isset($this->_def[$key]))
            {
                $this->_def[$key] = $value;
            }
        }
    }
    
    /**
     * Get db adapter
     */
    public function getDbAdapter()
    {
        return $this->_db;
    }
    
    /**
     * Get expire time
     */
    public function getExpireTime()
    {
        return $this->_lifeTime;
    }
    
    /**
     * Get table definitions
     */
    public function getDefinitions()
    {
        return $this->_def;
    }
    
    /**
     * Open Session - retrieve resources
     *
     * @param string $save_path
     * @param string $name
     */
    public function open($save_path, $name)
    {
        return true;
    }

    /**
     * Close Session - free resources
     *
     */
    public function close()
    {
        return true;
    }

    /**
     * Read session data
     *
     * @param string $id
     */
    public function read($id)
    {
        
             
        // build select to read session data for session id
        $select = $this->_db->select();
        $select->from($this->_def['table'], $this->_def['data']);
        $select->where($this->_def['primary'] . ' = ?', $id);
        $select->where($this->_def['expire'] . ' > ?', time());
        
        // read data
        $row = $this->_db->fetchOne($select);
        
        // return empty string if no active session found
        if (false === $row)
        {
            return '';
        }
        
        
        
        // return session data
        return $row;
    }

    /**
     * Write Session - commit data to resource
     *
     * @param string $id
     * @param mixed $data
     */
    public function write($id, $data)
    {
        
      
        // calculate new expire date
        $expire = time() + $this->_lifeTime;
        
        // build select to read if a dataset exists for session id 
        $select = $this->_db->select();
        $select->from($this->_def['table'], $this->_def['primary']);
        $select->where($this->_def['primary'] . ' = ?', $id);
        
        // read data
        $row = $this->_db->fetchRow($select);
        
        try
        {
            // if no dataset found, insert new dataset
            if (empty($row))
            {
                $row = array(
                    $this->_def['primary'] => $id,
                    $this->_def['user' ]   => intval($this->session->curUser['user_id']),
                    $this->_def['expire' ] => $expire,
                    $this->_def['data'   ] => $data
                );
                
                $rows_affected = $this->_db->insert($this->_def['table'], $row);
                
                if (1 == $rows_affected)
                {
                    // insert successful
                    return true;
                }
            }
            // otherweise update existing dataset
            else
            {
                $row = array(
                    $this->_def['expire'] => $expire,
                    $this->_def['data'  ] => $data
                );
                
                $where = $this->_db->quoteInto($this->_def['primary'] . ' = ?', $id);
                
                $rows_affected = $this->_db->update($this->_def['table'], $row, $where);
                
                if (1 == $rows_affected)
                {
                    // update successful
                    return true;
                }
            }
        }
        // catch any PDOException
        catch (Exception $e)
        {
        }
        
        // write not successful
        return false;
    }

    /**
     * Destroy Session - remove data from resource for
     * given session id
     *
     * @param string $id
     */
    public function destroy($id)
    {
        // build where clause to destroy session
        $where = $this->_db->quoteInto($this->_def['primary'] . ' = ?', $id);
        
        // destroy session
        $rows_affected = $this->_db->delete($this->_def['table'], $where);
        
        // return true if destroying was successful
        if (1 == $rows_affected)
        {
            return true;
        }
        
        // destroy not successful
        return false;
    }

    /**
     * Garbage Collection - remove old session data older
     * than $maxlifetime (in seconds)
     *
     * @param int $maxlifetime
     */
    public function gc($maxlifetime)
    {
        // build where clause to remove old sessions
        $where = $this->_db->quoteInto($this->_def['expire'] . ' < ?', time());
        
        // remove old session
        $rows_affected = $this->_db->delete($this->_def['table'], $where);
        
        // successful removal of datasets
        if (0 < $rows_affected)
        {
            return true;
        }
        
        // no datasets removed
        return false;
    }

}
?>