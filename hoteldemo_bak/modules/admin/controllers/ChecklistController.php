<?php

require_once('actionControllerBase.php');
class Admin_ChecklistController extends actionControllerBase
{		
	/*** CATEGORIES ***/
	
	function categoriesAction()
    {    	
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
		
		$this->view->categories = $checklisttable->getCategories();

		$this->view->title = "Digital Checklist Categories";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_checklist_categories.php');
        echo $this->view->render('footer.php');
    }
	
	function addcategoryAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	$params = $this->_getAllParams();
    	$id = $checklisttable->addCategory($params);
		echo $id;
    }
	
	function getcategorybyidAction()
    {
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $checklisttable->getCategoryById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletecategoryAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$checklisttable->deleteCategory($id);
		}
	}
	
	/*** SUBCATEGORIES ***/
	
	function subcategoriesAction()
    {    	
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
		
		$this->view->subcategories = $checklisttable->getSubcategories();
		
		$this->view->categories = $checklisttable->getCategories();

		$this->view->title = "Digital Checklist Subcategories";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_checklist_subcategories.php');
        echo $this->view->render('footer.php');
    }
	
	function addsubcategoryAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	$params = $this->_getAllParams();
    	$id = $checklisttable->addSubcategory($params);
		echo $id;
    }
	
	function getsubcategorybyidAction()
    {
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $checklisttable->getSubcategoryById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deletesubcategoryAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$checklisttable->deleteSubcategory($id);
		}
	}
	
	function getsubcatbycatidAction()
    {
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $checklisttable->getSubcategoryByCatId($params['category_id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 

	/*** TEMPLATES ***/

	function viewAction()
    {    	
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
		
		$this->view->templates = $checklisttable->getTemplates();

    	Zend_Loader::LoadClass('categoryClass', $this->modelDir);
    	$categoryClass = new categoryClass();
		$this->view->categories = $categoryClass->getCategories();

		$this->view->title = "Digital Checklist Templates";
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_checklist_template.php');
        echo $this->view->render('footer.php');
    }
	
	function addtemplateAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	$params = $this->_getAllParams();
    	$id = $checklisttable->addTemplate($params);
		echo $id;
    }
	
	function gettemplatebyidAction()
    {
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $checklisttable->getTemplateById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$checklisttable->delete($id);
		}
	}
	
	
	/*** CHECKLIST ITEMS ***/
	
	function viewitemsAction()
    {    	
		$params = $this->_getAllParams();
		
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
		
		$this->view->items = $checklisttable->getItems($params['id']);
		
		$this->view->categories = $checklisttable->getCategories();
		
		$template = $checklisttable->getTemplateById($params['id']);

		$this->view->title = $template['template_name'];
		
		$this->view->template_id = $params['id'];
    	
        echo $this->view->render('header.php');
        echo $this->view->render('view_checklist_items.php');
        echo $this->view->render('footer.php');
    }
	
	function additemAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	$params = $this->_getAllParams();
    	$id = $checklisttable->addItem($params);
		echo $id;
    }
	
	function getitembyidAction()
    {
    	Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
    	$params = $this->_getAllParams();
    	$response['success'] = true;
    	$response['data'] = array();
    	$rs = $checklisttable->getItemById($params['id']);

    	if ( is_array($rs) ) {    
    		if ( sizeof($rs) > 0 ) {
	    			$response['data'] = $rs;
    		}
   		}
    	echo json_encode($response);
    } 
	
	function deleteitemAction()
    {
		Zend_Loader::LoadClass('checklistClass', $this->modelDir);
    	$checklisttable = new checklistClass();
    	
		$id =  $this->_request->getParam('id');
		
		if(!empty($id))
		{
			$checklisttable->deleteItem($id);
		}
	}

}
?>
