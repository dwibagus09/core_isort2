<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class newsletterClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}
	
	protected function phplistrestapi_login() {
		$post_params = array();
		$post_params['login'] = 'aptrestapi';
		$post_params['password'] = 'R3StP@sSAp!';
		
		$result = $this->phplistrestapi('login', $post_params);
		
		return ($result->status == 'success');
	}
	
	protected function phplistrestapi($cmd, $post_params) {
		$post_params['cmd'] = $cmd;
		$post_params['secret'] = '6f9046b1f1b86b56d';
		$post_params = http_build_query($post_params);
		$url = 'http://list.falconocp.com/admin/?page=call&pi=restapi';
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL,            $url);
		curl_setopt($c, CURLOPT_HEADER,         0);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_POST,           1);
		curl_setopt($c, CURLOPT_POSTFIELDS,     $post_params);
		curl_setopt($c, CURLOPT_COOKIEFILE,     'phplistrestapi');
		curl_setopt($c, CURLOPT_COOKIEJAR,      'phplistrestapi');
		curl_setopt($c, CURLOPT_HTTPHEADER,     array('Connection: Keep-Alive', 'Keep-Alive: 60'));
		
		$result = curl_exec($c);
		curl_close($c);
		
		$result = json_decode($result);
		
		return $result;
	}
	
	function addSubscriber($site_id, $params)
	{
		$listClass = new newsletter_lists(array('db' => 'db'));
		
		$select = $listClass->select()->where("list_id=?", $params['list_id']);
		$list = $listClass->getAdapter()->fetchRow($select);
		
		$subscriberId = 0;
		$newsletterUserId = 0;
		$lists = array();
		if(!empty($list['phplist_list_id']) && $this->phplistrestapi_login()) {
			$response = $this->phplistrestapi('subscriberGetByEmail', array(
				'email'			=> $params['email'],
			));
			if(!empty($response->data->id)) $subscriberId = $response->data->id;
			
			if(empty($subscriberId)) {
				$response = $this->phplistrestapi('subscriberAdd', array(
					'email'			=> $params['email'],
					'confirmed'		=> 1,
					'htmlemail'		=> 1,
					'foreignkey'	=> '',
					'password'		=> '',
					'subscribepage'	=> '',
					'disabled'		=> 0, 
				));
				if(!empty($response->data->id)) $subscriberId = $response->data->id;
			}
			
			if(!empty($subscriberId)) {
				$response = $this->phplistrestapi('listSubscriberAdd', array(
					'list_id'		=> $list['phplist_list_id'],
					'subscriber_id'	=> $subscriberId,
				));
				$lists = $response->data;
				
				$usersClass = new newsletter_users(array('db' => 'db'));
				$select = $usersClass->select()->where("phplist_user_user_id=?", $subscriberId)->where("newsletter_list_id=?", $params['list_id'])->where("site_id=?", $site_id);
				$user = $usersClass->getAdapter()->fetchRow($select);				
				
				if(empty($user))
				{
					$data = array(
						'site_id'			=> $site_id,
						'newsletter_list_id'	=> $params['list_id'],
						'phplist_user_user_id'	=> $subscriberId,
						'firstname'			=> $params['firstname'],
						'lastname'			=> $params['lastname'],
						'confirmed'			=> '1',
						'sign_up_date_time'	=> date('Y-m-d H:i:s')
					);				
					$usersClass->insert($data);		
					$newsletterUserId = $usersClass->getAdapter()->lastInsertId();					
				}
			}
		}
		
		return $newsletterUserId;
	}
	
	function getListById($list_id)
	{	
		if(!empty($list_id) && $this->phplistrestapi_login()) {
			$phplistdata = $this->phplistrestapi('listGet', array('id'	=> $list_id));
			if(!empty($phplistdata->data)) {
				$list['name'] 			= $phplistdata->data->name;
				$list['description'] 	= $phplistdata->data->description;
				$list['listorder'] 		= $phplistdata->data->listorder;
				$list['active'] 		= $phplistdata->data->active;
			}
		}
		
		return $list;	
	}
	
	function deleteListSubscriber($site_id, $params)
	{	
		$lists = array();
		if(!empty($params['email']) && !empty($params['list_id']) && $this->phplistrestapi_login()) {
			$responseSubscriber = $this->phplistrestapi('subscriberGetByEmail', array(
				'email'			=> $params['email'],
			));
			$subscriber = $responseSubscriber->data;
			if(!empty($responseSubscriber->data->id)) $subscriberId = $subscriber->id;			

			if(!empty($subscriberId)) {
				/*$responseFnameUsrAttr = $this->phplistrestapi('subscriberGetUserAttribute', array(
					'attributeid' 		=> 3,
					'userid'			=> $subscriberId
				));
				if(!empty($responseFnameUsrAttr->data[0]->value)) $subscriber->firstname = $responseFnameUsrAttr->data[0]->value;
				
				$responseLnameUsrAttr = $this->phplistrestapi('subscriberGetUserAttribute', array(
					'attributeid' 		=> 4,
					'userid'			=> $subscriberId
				));
				if(!empty($responseLnameUsrAttr->data[0]->value)) $subscriber->lastname = $responseLnameUsrAttr->data[0]->value;
				*/
				$response = $this->phplistrestapi('listSubscriberDelete', array(
					'list_id'		=> $params['list_id'],
					'subscriber_id'	=> $subscriberId
				));
				$lists = $response->data;
			}
		}
		return $subscriber;
	}
	
	function unconfirmedListSubscriber($site_id, $params)
	{	
		$user = array();
		if(!empty($params['email']) && !empty($params['list_id']) && $this->phplistrestapi_login()) {
			$responseSubscriber = $this->phplistrestapi('subscriberGetByEmail', array(
				'email'			=> $params['email'],
			));
			$subscriber = $responseSubscriber->data;
			if(!empty($responseSubscriber->data->id)) $subscriberId = $responseSubscriber->data->id;
			
			$listClass = new newsletter_lists(array('db' => 'db'));		
			$select = $listClass->select()->where("phplist_list_id=?", $params['list_id'])->where("site_id=?", $site_id);
			$list = $listClass->getAdapter()->fetchRow($select);
			if(!empty($list['list_id']) && !empty($subscriberId))
			{
				$usersClass = new newsletter_users(array('db' => 'db'));		
				$select = $usersClass->select()->where("phplist_user_user_id=?", $subscriberId)->where("newsletter_list_id=?", $list['list_id'])->where("site_id=?", $site_id);
				$user = $usersClass->getAdapter()->fetchRow($select);
				$data = array(
					'confirmed'			=> '0',
					'opt_out_date_time' => date('Y-m-d H:i:s')
				);
				$where = array();
				$where[] = $usersClass->getAdapter()->quoteInto('newsletter_user_id = ?', $user['newsletter_user_id']);
				$usersClass->update($data, $where);
			}
		}
		return $user;
	}
}
?>