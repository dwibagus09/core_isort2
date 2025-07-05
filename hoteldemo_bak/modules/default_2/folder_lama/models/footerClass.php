<?php

require_once('defaultClass.php');
require_once('dbClass.php');

class footerClass extends defaultClass
{
	function __construct()
	{
		parent::__construct();
	}

	
	/**
	 * Returns the complete footer_block details for a given site
	 *
	 * @param int $site_id
	 * @return array
	 */
	function getFooter($site_id)
	{
		$footerblockTable = new footer_block(array('db' => 'db'));
		
		$select = $footerblockTable->getAdapter()->select();
		$select->from(array("fb"=>"footer_block"), array("fb.footer_block_id", "fb.site_id", "fb.title", "fb.width"));
		$select->joinLeft(array("fbc"=>"footer_block_content"), "fbc.footer_block_id = fb.footer_block_id", array("fbc.footer_block_content_id", "fbc.area_id", "fbc.content_section_area_id", "fbc.freetext", "fbc.custom_url", "fbc.custom_content"));
		$select->joinLeft(array("ca"=>"content_areas"), "ca.area_id = fbc.area_id", array("ca.area_name as ca_area_name", "ca.custom_title as ca_custom_title", "ca.section_id as ca_section_id", "ca.template_name", "ca.custom_url as ca_custom_url"));
		$select->joinLeft(array("area_cs"=>"content_sections"), "area_cs.section_id = ca.section_id", array("area_cs.section_name as area_section_name"));
		$select->joinLeft(array("csa"=>"content_section_area"), "csa.content_section_area_id = fbc.content_section_area_id", array("csa.content_section_area_id", "csa.section_id as csa_section_id", "csa.area_id as csa_area_id", "csa.custom_title as csa_custom_title", "csa.custom_url as csa_custom_url", "csa.template as csa_template", "csa.content_section_area_type_id"));
		$select->joinLeft(array("sa_ca"=>"content_areas"), "sa_ca.area_id = csa.area_id", array("sa_ca.area_name as sa_ca_area_name", "sa_ca.template_name as sa_ca_template_name"));
		$select->joinLeft(array("sa_cs"=>"content_sections"), "sa_cs.section_id = csa.section_id", array("sa_cs.section_name as sa_ca_section_name"));
		$select->where("fb.site_id=?", $site_id);
		$select->order("fbc.footer_block_content_id");

		$footer_block = $footerblockTable->getAdapter()->fetchAll($select);
		
		$i = 0;
		$j = -1;
		$k = 0;
		
		if(!empty($footer_block))
		{
			foreach ($footer_block as $fb)
			{
				if($footer_block[$i-1]['footer_block_id'] != $fb['footer_block_id'])
				{
					$j++;
					$footerblock[$j]['footer_block_id'] = $fb['footer_block_id'];
					$footerblock[$j]['title'] = $fb['title'];
					$footerblock[$j]['width'] = $fb['width'];
					$k = 0;
				}
				
				if($footer_block[$i-1]['footer_block_content_id'] != $fb['footer_block_content_id'])
				{
					$footerblock[$j]['content'][$k]['footer_block_content_id'] = $fb['footer_block_content_id'];
					$footerblock[$j]['content'][$k]['freetext'] = $fb['freetext'];
					$footerblock[$j]['content'][$k]['custom_url'] = $fb['custom_url'];
					$footerblock[$j]['content'][$k]['area_id'] = $fb['area_id'];
					$footerblock[$j]['content'][$k]['content_section_area_id'] = $fb['content_section_area_id'];
					$footerblock[$j]['content'][$k]['custom_content'] = $fb['custom_content'];
				}
				
				if(!empty($fb['area_id']))
				{
					$footerblock[$j]['content'][$k]['content']['area_id'] = $fb['area_id'];
					$footerblock[$j]['content'][$k]['content']['area_name'] = $fb['ca_area_name'];
					$footerblock[$j]['content'][$k]['content']['custom_title'] = $fb['ca_custom_title'];
					$footerblock[$j]['content'][$k]['content']['section_id'] = $fb['ca_section_id'];
					$footerblock[$j]['content'][$k]['content']['template_name'] = $fb['template_name'];
					$footerblock[$j]['content'][$k]['content']['custom_url'] = $fb['ca_custom_url'];
					$footerblock[$j]['content'][$k]['content']['section_name'] = $fb['area_section_name'];
				}
				elseif(!empty($fb['content_section_area_id']))
				{
					$footerblock[$j]['content'][$k]['content']['content_section_area_id'] = $fb['content_section_area_id'];
					$footerblock[$j]['content'][$k]['content']['section_id'] = $fb['csa_section_id'];
					$footerblock[$j]['content'][$k]['content']['area_id'] = $fb['csa_area_id'];
					$footerblock[$j]['content'][$k]['content']['custom_title'] = $fb['csa_custom_title'];
					$footerblock[$j]['content'][$k]['content']['custom_url'] = $fb['csa_custom_url'];
					$footerblock[$j]['content'][$k]['content']['template'] = $fb['csa_template'];
					$footerblock[$j]['content'][$k]['content']['content_section_area_type_id'] = $fb['content_section_area_type_id'];
					$footerblock[$j]['content'][$k]['content']['area_name'] = $fb['sa_ca_area_name'];
					$footerblock[$j]['content'][$k]['content']['template_name'] = $fb['sa_ca_template_name'];
					$footerblock[$j]['content'][$k]['content']['section_name'] = $fb['sa_ca_section_name'];
				}
				
				/*if($fb['footer_block_content_id'] == '65')
				{
					print_r($footerblock[$j]['content']);
					exit();
//				}*/
				
				$i++;
				$k++;
			}
		}
		/*$select = $footerblockTable->select()
				->where('site_id = ?', $site_id);
				
		$footerblock = $this->db->fetchAll($select);
		
		$footerblockcontentTable = new footer_block_content(array('db' => 'db'));
		
		for ($i = 0; $i < count($footerblock); $i++) {
			$select = $footerblockcontentTable->select();
			$select->where("footer_block_id=?", $footerblock[$i]["footer_block_id"]);
			
			$footer_block_content = $footerblockcontentTable->getAdapter()->fetchAll($select);
		
			foreach ($footer_block_content as &$fbc)
			{
				if(!empty($fbc['area_id']))
				{
					$sql = "select ca.*, cs.section_name from content_areas ca 
							left join content_sections cs on cs.section_id = ca.section_id
							where area_id=".$fbc['area_id'];
					$area = $this->db->fetchRow($sql);
					$fbc['content'] = $area;
				}
				elseif (!empty($fbc['content_section_area_id']))
				{
					$sql = "select csa.*, ca.area_name, cs.section_name from content_section_area csa
							left join content_areas ca on ca.area_id = csa.area_id
							left join content_sections cs on cs.section_id = csa.section_id
							where csa.content_section_area_id=".$fbc['content_section_area_id'];
					$csa = $this->db->fetchRow($sql);
					$fbc['content'] = $csa;
				}
			}			
			
			$footerblock[$i]["content"] = $footer_block_content;
		}*/
		
		return $footerblock;
	}
	
	function getFooterBlockContentById($footer_block_content_id)
	{
		$footerBlockContentTable = new footer_block_content(array('db'=>'db'));
		
		$select = $footerBlockContentTable->select();
		$select->where("footer_block_content_id=?", $footer_block_content_id);
		
		$rs = $footerBlockContentTable->getAdapter()->fetchRow($select);
		
		return $rs;
	}

}

?>