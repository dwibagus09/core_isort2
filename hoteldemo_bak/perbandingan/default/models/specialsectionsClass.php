<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class specialsectionsClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Returns the complete special sections details for a given site
	 *
	 * @param int $site_id
	 * @return array
	 */
	function getSpecialSections($site_id, $start, $limit, $order)
	{
		$specialSectionsTable = new special_sections(array('db' => 'db'));

		$select = $specialSectionsTable->select()
				->where('site_id = ?', $site_id)
				->where('active = ?', '1');
		/*$select = "SELECT ss.*, ca.headline FROM special_sections ss
				left join content_articles ca on ca.article_id = ss.article_id
				where ss.site_id = ".$site_id." and ss.active = '1' and ss.pubdate <= '".date('Y-m-d h:i:s')."'";
		*/
			
		$select = $select . " order by " . $order;
		
		$results['count'] = count($this->db->fetchAll($select));

		$select = $select . " limit ".$start.",".$limit;
	
		$results['data'] = $this->db->fetchAll($select);
		
		return $results;
	}

}
?>