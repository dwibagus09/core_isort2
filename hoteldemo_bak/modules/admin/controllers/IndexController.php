<?php
require_once('actionControllerBase.php');
class Admin_IndexController extends actionControllerBase 
{

    /**
     * The default action controller.
     *
     */
    function indexAction()
    {    	
    	set_time_limit(7200);
		
		Zend_Loader::LoadClass('siteClass', $this->modelDir);
    	$siteClass = new siteClass();
		
    	$this->view->sitesList = $siteList = $siteClass->getSites();
		if(count($siteList) == 1) $siteClass->setSite(1);
		
        echo $this->view->render('header.php');
        echo $this->view->render('index.php');
        echo $this->view->render('footer.php');
    }

    function backupdbAction()
    {    	
    	set_time_limit(0);
		
        $backup_file = "/home/emma/srt_backup_db/srt_" . date("Ymd") . '.gz';
        $command = "mysqldump -h localhost -u srt_user -psrtpakuwon2018$ smart_reporting_tool --skip-comments --skip-add-drop-table --skip-add-locks --skip-disable-keys --skip-set-charset | gzip > ".$backup_file;
        system($command);

        $backup_file2 = "/home/emma/srt_backup_db/srt_action_plan_" . date("Ymd") . '.gz';
        $command2 = "mysqldump -h 10.201.201.201 -u srt_user -psrtpakuwon2018$ smart_reporting_tool --skip-comments --skip-add-drop-table --skip-add-locks --skip-disable-keys --skip-set-charset -c | gzip > ".$backup_file2;
        system($command2);

        $backup_file3 = "/home/emma/srt_backup_db/srt_issues_" . date("Ymd") . '.gz';
        $command3 = "mysqldump -h 10.202.202.202 -u srt_user -psrtpakuwon2018$ smart_reporting_tool --skip-comments --skip-add-drop-table --skip-add-locks --skip-disable-keys --skip-set-charset | gzip > ".$backup_file3;
        system($command3);
    }

    function sendbackupdbAction()
    {    	
    	set_time_limit(0);
		
        require_once 'Zend/Mail.php';
        require_once 'Zend/Mime.php';
        $mail = new Zend_Mail();
        $mail->setBodyHtml('SRT Database Backup '.date("d M Y"));
        $mail->setFrom("srt@pakuwon.com");
        
        $mail->addTo("emmadarmawan@pakuwon.com");
    
        $mail->setSubject('SRT Database Backup '.date("d M Y"));

        /*$file1 = "/home/emma/srt_backup_db/srt_" . date("Ymd") . '.gz';
        /*$file2 = "/home/emma/srt_backup_db/srt_action_plan_" . date("Ymd") . '.sql';*/
        $file3 = "/home/emma/srt_backup_db/srt_issues_" . date("Ymd") . '.gz'; 
        
        /*$content = file_get_contents($file1);
        $at = $mail->createAttachment($content);
        $at->type        = Zend_Mime::TYPE_OCTETSTREAM;
        $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $at->encoding    = Zend_Mime::ENCODING_BASE64;
        $at->filename    = 'srt_' . date("Ymd") . '.gz';

        $content2 = file_get_contents($file2);
        $at2 = $mail->createAttachment($content2);
        $at2->type        = Zend_Mime::TYPE_OCTETSTREAM;
        $at2->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $at2->encoding    = Zend_Mime::ENCODING_BASE64;
        $at2->filename    = 'srt_action_plan_' . date("Ymd") . '.sql';*/


        $content3 = file_get_contents($file3);
        $at3 = $mail->createAttachment($content3);
        $at3->type        = Zend_Mime::TYPE_OCTETSTREAM;
        $at3->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
        $at3->encoding    = Zend_Mime::ENCODING_BASE64;
        $at3->filename    = 'srt_issues_' . date("Ymd") . '.gz';
        
        try {
            $mail->send();
            echo "success";
        }
        catch (Exception  $ex) {
            echo "failed=".$ex;
        }
        unset($mail);
    }

    function cleancacheallAction() {
        $this->cache->clean(Zend_Cache::CLEANING_MODE_ALL);
    }
         
}