<?php

require_once 'Zend/Mail.php';
require_once 'Zend/Mail/Transport/Smtp.php';
require_once 'Zend/Mail/Transport/Sendmail.php';
require_once 'Zend/Registry.php';
require_once 'Zend/Validate.php';

class Mailer
{
    /**
     * Directory path, where mail templates are located
     *
     * @var string
     */
    protected $templatesDir = '';

    /**
     * Constructor for the class, provide directory path where mail templates are saved
     *
     * @param string $templatesDir Directory path, where mail templates are located
     */
    public function __construct($templatesDir = 'languages/mailtemplates')
    {
    	$this->templatesDir = $templatesDir;
        $mailConfig = Zend_Registry::get('config')->mail;
        if ($mailConfig->smtp)
        {
        	$configs = $mailConfig->smtpconfig->toArray();
        	unset($configs["name"]);
            $transport = new Zend_Mail_Transport_Smtp($mailConfig->host, $configs);
            Zend_Mail::setDefaultTransport($transport);
        }
        else
        {
            //$transport = new Zend_Mail_Transport_Sendmail();
        }

        
    }


    public function sendMail($title, $content, $toEmailAddress, $toName, $site)
    {
        $mailer = new Zend_Mail();
        $mailer->addTo($toEmailAddress, htmlentities($toName, ENT_QUOTES));
        $mailer->setSubject($title);
        $mailer->setBodyHtml($content, 'utf8');
        $mailer->setFrom($site["email"], htmlentities($site["newspaper_name"], ENT_QUOTES));
        $mailer->send();
    }
    
    public function replaceVariables($content, $user, $site) {
    	$content = str_ireplace("[username]", htmlentities($user["username"], ENT_QUOTES), $content);
    	$content = str_ireplace("[password]",  htmlentities($user["password"], ENT_QUOTES), $content);
    	$content = str_ireplace("[first name]",  htmlentities($user["firstname"], ENT_QUOTES), $content);
    	$content = str_ireplace("[last name]",  htmlentities($user["lastname"], ENT_QUOTES), $content);
    	$content = str_ireplace("[name]",  htmlentities($user["firstname"]." ".$user["lastname"], ENT_QUOTES), $content);
    	$content = str_ireplace("[address]",  htmlentities($user["address"], ENT_QUOTES), $content);
    	$content = str_ireplace("[email]",  htmlentities($user["email"], ENT_QUOTES), $content);
    	$content = str_ireplace("[city]",  htmlentities($user["city"], ENT_QUOTES), $content);
    	$content = str_ireplace("[state]",  htmlentities($user["state"], ENT_QUOTES), $content);
    	$content = str_ireplace("[zip]",  htmlentities($user["zip"], ENT_QUOTES), $content);
    	$content = str_ireplace("[phone]",  "(".substr($user["phone"],0,3).")".substr($user["phone"],3,3)."-".substr($user["phone"], 6,4), $content);
    	$content = str_ireplace("[newspaper name]",  htmlentities($site["newspaper_name"], ENT_QUOTES), $content);
    	return $content;
    }
}