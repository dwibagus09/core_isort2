<?php
require_once('actionControllerBase.php');
class ReportController extends actionControllerBase
{
	function createreportfromissueAction()
	{
		set_time_limit(7200);
		$params = $this->_getAllParams();
		$issueClass = $this->loadModel('issue');
		$issue_ids = json_decode($params['ids'], true);
		$i = $g = $l = 0;
		$security = $security['incident'] = $security['glitch'] = $security['lost_found'] = array();
		foreach($issue_ids as $id)
		{
			$issue = $issueClass->getIssueById($id);
			if($issue['issue_type_id'] == '1')
			{
				$security['incident'][$i] = $issue;
				$i++;
			}
			if($issue['issue_type_id'] == '2')
			{
				$security['glitch'][$g] = $issue;
				$g++;
			}
			if($issue['issue_type_id'] == '3')
			{
				$security['lost_found'][$l] = $issue;
				$l++;
			}
		}
		
		$this->view->security = $security;
		$this->renderTemplate('add_daily_security.tpl');  
	}
	
}
?>
