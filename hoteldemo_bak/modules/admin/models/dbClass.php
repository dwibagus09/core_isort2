<?php

require_once 'Zend/Db/Table.php';


class users extends Zend_Db_Table_Abstract
{
	protected $_name = 'users';
	protected $_primary = 'user_id';
}

class role extends Zend_Db_Table_Abstract
{
	protected $_name = 'role';
	protected $_primary = 'role_id';
}

class security_equipment_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_equipment_list';
	protected $_primary = 'security_equipment_list_id';
}

class other_setting extends Zend_Db_Table_Abstract
{
	protected $_name = 'other_setting';
	protected $_primary = 'setting_id';
}

class sites extends Zend_Db_Table_Abstract
{
	protected $_name = 'sites';
	protected $_primary = 'site_id';
}

class security_training_activity extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_training_activity';
	protected $_primary = 'training_activity_id';
}

class safety_training_activity extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_training_activity';
	protected $_primary = 'training_activity_id';
}

class safety_equipment_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_equipment_list';
	protected $_primary = 'safety_equipment_list_id';
}

class safety_equipment_list_items extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_equipment_list_items';
	protected $_primary = 'equipment_item_id';
}

class parking_equipment_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_equipment_list';
	protected $_primary = 'parking_equipment_list_id';
}

class parking_training_activity extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_training_activity';
	protected $_primary = 'training_activity_id';
}

class housekeeping_tangkapan extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_tangkapan';
	protected $_primary = 'tangkapan_id';
}

class mod_staff_condition extends Zend_Db_Table_Abstract
{
	protected $_name = 'mod_staff_condition';
	protected $_primary = 'staff_condition_id';
}

class mod_equipment_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'mod_equipment_list';
	protected $_primary = 'mod_equipment_list_id';
}

class security_vendor extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_vendor';
	protected $_primary = 'vendor_id';
}

class security_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_kejadian';
	protected $_primary = 'kejadian_id';
}

class security_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_modus';
	protected $_primary = 'modus_id';
}

class security_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_floor';
	protected $_primary = 'floor_id';
}

class security_lokasi_umum extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_lokasi_umum';
	protected $_primary = 'lokasi_umum_id';
}

class issue_type extends Zend_Db_Table_Abstract
{
	protected $_name = 'issue_type';
	protected $_primary = 'issue_type_id';
}

class category_issue_type extends Zend_Db_Table_Abstract
{
	protected $_name = 'category_issue_type';
	protected $_primary = 'cit_id';
}

class categories extends Zend_Db_Table_Abstract
{
	protected $_name = 'categories';
	protected $_primary = 'category_id';
}

class safety_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_kejadian';
	protected $_primary = 'kejadian_id';
}

class safety_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_modus';
	protected $_primary = 'modus_id';
}

class safety_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_floor';
	protected $_primary = 'floor_id';
}

class safety_lokasi_umum extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_lokasi_umum';
	protected $_primary = 'lokasi_umum_id';
}

class parking_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_kejadian';
	protected $_primary = 'kejadian_id';
}

class parking_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_modus';
	protected $_primary = 'modus_id';
}

class parking_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_floor';
	protected $_primary = 'floor_id';
}

class parking_lokasi_umum extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_lokasi_umum';
	protected $_primary = 'lokasi_umum_id';
}

class rating extends Zend_Db_Table_Abstract
{
	protected $_name = 'rating';
	protected $_primary = 'id';
}

class action_plan_module extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_module';
	protected $_primary = 'action_plan_module_id';
}

class action_plan_target extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_target';
	protected $_primary = 'action_plan_target_id';
}

class action_plan_activity extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_activity';
	protected $_primary = 'action_plan_activity_id';
}

class action_plan_reminder_email extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_reminder_email';
	protected $_primary = 'email_id';
}

class action_plan_schedule extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_schedule';
	protected $_primary = 'schedule_id';
}

class action_plan_schedule_attachment extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_schedule_attachment';
	protected $_primary = 'attachment_id';
}

class action_plan_reschedule extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_reschedule';
	protected $_primary = 'reschedule_id';
}


class achievement_category extends Zend_Db_Table_Abstract
{
	protected $_name = 'achievement_category';
	protected $_primary = 'id';
}

class achievement_category_module extends Zend_Db_Table_Abstract
{
	protected $_name = 'achievement_category_module';
	protected $_primary = 'id';
}

class kpi_users extends Zend_Db_Table_Abstract
{
	protected $_name = 'kpi_users';
	protected $_primary = 'kpi_user_id';
}

class building_protection_equipment extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_protection_equipment';
	protected $_primary = 'equipment_id';
}

class building_protection_equipment_item extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_protection_equipment_item';
	protected $_primary = 'equipment_item_id';
}

class building_protection_equipment_item_detail extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_protection_equipment_item_detail';
	protected $_primary = 'item_detail_id';
}

class fire_accident_equipment extends Zend_Db_Table_Abstract
{
	protected $_name = 'fire_accident_equipment';
	protected $_primary = 'equipment_id';
}

class fire_accident_equipment_detail extends Zend_Db_Table_Abstract
{
	protected $_name = 'fire_accident_equipment_detail';
	protected $_primary = 'equipment_detail_id';
}

class user_log_2020 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2020';
	protected $_primary = 'log_id';
}

class logs_2020 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_2020';
	protected $_primary = 'log_id';
}

class engineering_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'engineering_kejadian';
	protected $_primary = 'kejadian_id';
}

class engineering_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'engineering_modus';
	protected $_primary = 'modus_id';
}

class engineering_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'engineering_floor';
	protected $_primary = 'floor_id';
}

class housekeeping_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_kejadian';
	protected $_primary = 'kejadian_id';
}

class housekeeping_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_modus';
	protected $_primary = 'modus_id';
}

class housekeeping_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_floor';
	protected $_primary = 'floor_id';
}

class modus_linked extends Zend_Db_Table_Abstract
{
	protected $_name = 'modus_linked';
	protected $_primary = 'linked_id';
}

class building_service_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_service_kejadian';
	protected $_primary = 'kejadian_id';
}

class building_service_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_service_modus';
	protected $_primary = 'modus_id';
}

class building_service_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_service_floor';
	protected $_primary = 'floor_id';
}

class tenant_relation_kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'tenant_relation_kejadian';
	protected $_primary = 'kejadian_id';
}

class tenant_relation_modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'tenant_relation_modus';
	protected $_primary = 'modus_id';
}

class tenant_relation_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'tenant_relation_floor';
	protected $_primary = 'floor_id';
}

class area extends Zend_Db_Table_Abstract
{
	protected $_name = 'area';
	protected $_primary = 'area_id';
}

class checklist_templates extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_templates';
	protected $_primary = 'template_id';
}

class checklist_template_items extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_template_items';
	protected $_primary = 'item_id';
}

class checklist_categories extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_categories';
	protected $_primary = 'category_id';
}

class checklist_subcategories extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_subcategories';
	protected $_primary = 'subcategory_id';
}

class kejadian extends Zend_Db_Table_Abstract
{
	protected $_name = 'kejadian';
	protected $_primary = 'kejadian_id';
}

class modus extends Zend_Db_Table_Abstract
{
	protected $_name = 'modus';
	protected $_primary = 'modus_id';
}

class location extends Zend_Db_Table_Abstract
{
	protected $_name = 'location';
	protected $_primary = 'location_id';
}


?>