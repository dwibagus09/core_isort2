<?php

require_once 'Zend/Db/Table.php';

class role extends Zend_Db_Table_Abstract
{
	protected $_name = 'role';
	protected $_primary = 'role_id';
}


class users extends Zend_Db_Table_Abstract
{
	protected $_name = 'users';
	protected $_primary = 'user_id';
}

class issues extends Zend_Db_Table_Abstract
{
	protected $_name = 'issues';
	protected $_primary = 'issue_id';
}

class categories extends Zend_Db_Table_Abstract
{
	protected $_name = 'categories';
	protected $_primary = 'category_id';
}

class comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'comments';
	protected $_primary = 'comment_id';
}

class security extends Zend_Db_Table_Abstract
{
	protected $_name = 'security';
	protected $_primary = 'security_id';
}

class security_defect_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_defect_list';
	protected $_primary = 'sdl_id';
}

class security_incident extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_incident';
	protected $_primary = 'incident_id';
}

class security_glitch extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_glitch';
	protected $_primary = 'glitch_id';
}

class security_lost_found extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_lost_found';
	protected $_primary = 'lost_found_id';
}

class issue_type extends Zend_Db_Table_Abstract
{
	protected $_name = 'issue_type';
	protected $_primary = 'issue_type_id';
}

class security_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_comments';
	protected $_primary = 'comment_id';
}

class security_training_activity extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_training_activity';
	protected $_primary = 'training_activity_id';
}

class chief_security_report extends Zend_Db_Table_Abstract
{
	protected $_name = 'chief_security_report';
	protected $_primary = 'chief_security_report_id';
}

class chief_security_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'chief_security_comments';
	protected $_primary = 'comment_id';
}

class security_equipment extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_equipment';
	protected $_primary = 'equipment_id';
}

class security_specific_report extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_specific_report';
	protected $_primary = 'specific_report_id';
}

class security_equipment_list extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_equipment_list';
	protected $_primary = 'security_equipment_list_id';
}

class sites extends Zend_Db_Table_Abstract
{
	protected $_name = 'sites';
	protected $_primary = 'site_id';
}

class shift extends Zend_Db_Table_Abstract
{
	protected $_name = 'shift';
	protected $_primary = 'shift_id';
}

class security_training extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_training';
	protected $_primary = 'security_training_id';
}

class spv_security_attachments extends Zend_Db_Table_Abstract
{
	protected $_name = 'spv_security_attachments';
	protected $_primary = 'attachment_id';
}

class chief_security_attachments extends Zend_Db_Table_Abstract
{
	protected $_name = 'chief_security_attachments';
	protected $_primary = 'attachment_id';
}

class issue_progress_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'issue_progress_images';
	protected $_primary = 'issue_progress_image_id';
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

class security_vendor extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_vendor';
	protected $_primary = 'vendor_id';
}

class user_log extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log';
	protected $_primary = 'log_id';
}

class security_read_report_log extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_read_report_log';
	protected $_primary = 'log_id';
}

class chief_security_read_report_log extends Zend_Db_Table_Abstract
{
	protected $_name = 'chief_security_read_report_log';
	protected $_primary = 'log_id';
}

class category_issue_type extends Zend_Db_Table_Abstract
{
	protected $_name = 'category_issue_type';
	protected $_primary = 'cit_id';
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

class monthly_kpi_total extends Zend_Db_Table_Abstract
{
	protected $_name = 'monthly_kpi_total';
	protected $_primary = 'id';
}

class action_plan_schedule_cqc extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_schedule_cqc';
	protected $_primary = 'cqc_id';
}

class action_plan_custom_rating extends Zend_Db_Table_Abstract
{
	protected $_name = 'action_plan_custom_rating';
	protected $_primary = 'id';
}

class security_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class safety_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class parking_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class housekeeping_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class engineering_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'engineering_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class building_service_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_service_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class building_service_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'building_service_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class tenant_relation_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'tenant_relation_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class tenant_relation_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'tenant_relation_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class gc_monthly_analysis extends Zend_Db_Table_Abstract
{
	protected $_name = 'gc_monthly_analysis';
	protected $_primary = 'monthly_analysis_id';
}

class gc_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'gc_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class cities extends Zend_Db_Table_Abstract
{
	protected $_name = 'cities';
	protected $_primary = 'city_id';
}


class user_log_2021 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2021';
	protected $_primary = 'log_id';
}

class user_log_2022 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2022';
	protected $_primary = 'log_id';
}

class user_log_2023 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2023';
	protected $_primary = 'log_id';
}

class user_log_2024 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2024';
	protected $_primary = 'log_id';
}

class user_log_2025 extends Zend_Db_Table_Abstract
{
	protected $_name = 'user_log_2025';
	protected $_primary = 'log_id';
}

class logs_1 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_1';
	protected $_primary = 'log_id';
}

class logs_2 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_2';
	protected $_primary = 'log_id';
}

class logs_3 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_3';
	protected $_primary = 'log_id';
}

class logs_4 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_4';
	protected $_primary = 'log_id';
}

class logs_5 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_5';
	protected $_primary = 'log_id';
}

class logs_6 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_6';
	protected $_primary = 'log_id';
}

class logs_7 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_7';
	protected $_primary = 'log_id';
}

class logs_8 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_8';
	protected $_primary = 'log_id';
}

class logs_9 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_9';
	protected $_primary = 'log_id';
}

class logs_10 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_10';
	protected $_primary = 'log_id';
}

class logs_11 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_11';
	protected $_primary = 'log_id';
}

class logs_12 extends Zend_Db_Table_Abstract
{
	protected $_name = 'logs_12';
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

class hod_meeting extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting';
	protected $_primary = 'hod_meeting_id';
}

class hod_meeting_attendance extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_attendance';
	protected $_primary = 'attendance_id';
}

class hod_meeting_topic extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic';
	protected $_primary = 'topic_id';
}

class hod_meeting_topic_start extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic_start';
	protected $_primary = 'topic_start_id';
}

class hod_meeting_topic_target extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic_target';
	protected $_primary = 'topic_target_id';
}

class hod_meeting_topic_followup extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic_followup';
	protected $_primary = 'followup_id';
}

class hod_meeting_topic_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic_images';
	protected $_primary = 'image_id';
}

class hod_meeting_topic_followup_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_topic_followup_images';
	protected $_primary = 'image_id';
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

class it_meeting extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting';
	protected $_primary = 'it_meeting_id';
}

class it_meeting_attendance extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_attendance';
	protected $_primary = 'attendance_id';
}

class it_meeting_topic extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_topic';
	protected $_primary = 'topic_id';
}

class it_meeting_topic_start extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_topic_start';
	protected $_primary = 'topic_start_id';
}

class it_meeting_topic_target extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_topic_target';
	protected $_primary = 'topic_target_id';
}

class it_meeting_topic_followup extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_topic_followup';
	protected $_primary = 'followup_id';
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

class safety_comittee extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee';
	protected $_primary = 'safety_comittee_id';
}

class safety_comittee_attendance extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_attendance';
	protected $_primary = 'attendance_id';
}

class safety_comittee_topic extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic';
	protected $_primary = 'topic_id';
}

class safety_comittee_topic_start extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_start';
	protected $_primary = 'topic_start_id';
}

class safety_comittee_topic_target extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_target';
	protected $_primary = 'topic_target_id';
}

class safety_comittee_topic_followup extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_followup';
	protected $_primary = 'followup_id';
}

class safety_comittee_topic_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_images';
	protected $_primary = 'image_id';
}

class safety_comittee_topic_followup_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_followup_images';
	protected $_primary = 'image_id';
}

class safety_comittee_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_comments';
	protected $_primary = 'comment_id';
}

class hod_meeting_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'hod_meeting_comments';
	protected $_primary = 'comment_id';
}

class it_meeting_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'it_meeting_comments';
	protected $_primary = 'comment_id';
}

class lost_found_options extends Zend_Db_Table_Abstract
{
	protected $_name = 'lost_found_options';
	protected $_primary = 'option_id';
}

class safety_comittee_topic_accident_review extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_accident_review';
	protected $_primary = 'accident_review_id';
}

class safety_comittee_topic_accident_review_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_accident_review_images';
	protected $_primary = 'image_id';
}

class safety_comittee_topic_recommendation extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_recommendation';
	protected $_primary = 'recommendation_id';
}

class safety_comittee_topic_recommendation_images extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_comittee_topic_recommendation_images';
	protected $_primary = 'image_id';
}

class work_order extends Zend_Db_Table_Abstract
{
	protected $_name = 'work_order';
	protected $_primary = 'wo_id';
}

class work_order_progress_attachment extends Zend_Db_Table_Abstract
{
	protected $_name = 'work_order_progress_attachment';
	protected $_primary = 'wo_id';
}

class work_order_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'work_order_comments';
	protected $_primary = 'comment_id';
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

class other_setting extends Zend_Db_Table_Abstract
{
	protected $_name = 'other_setting';
	protected $_primary = 'setting_id';
}

class security_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'security_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class safety_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class parking_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'parking_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class housekeeping_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'housekeeping_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class engineering_monthly_analysis_summary extends Zend_Db_Table_Abstract
{
	protected $_name = 'engineering_monthly_analysis_summary';
	protected $_primary = 'summary_id';
}

class manpower extends Zend_Db_Table_Abstract
{
	protected $_name = 'manpower';
	protected $_primary = 'manpower_id';
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

class false_alarm extends Zend_Db_Table_Abstract
{
	protected $_name = 'false_alarm';
	protected $_primary = 'false_alarm_id';
}

class fire_protection_tenant_equipment extends Zend_Db_Table_Abstract
{
	protected $_name = 'fire_protection_tenant_equipment';
	protected $_primary = 'perlengkapan_tenant_id';
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

class safety_specific_report extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_specific_report';
	protected $_primary = 'specific_report_id';
}

class safety_daily_report extends Zend_Db_Table_Abstract
{
	protected $_name = 'safety_daily_report';
	protected $_primary = 'report_id';
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

class kpi_c_section extends Zend_Db_Table_Abstract
{
	protected $_name = 'kpi_c_section';
	protected $_primary = 'id';
}

class rating extends Zend_Db_Table_Abstract
{
	protected $_name = 'rating';
	protected $_primary = 'id';
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

class checklist extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist';
	protected $_primary = 'checklist_id';
}

class checklist_items extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_items';
	protected $_primary = 'item_id';
}

class checklist_comments extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_comments';
	protected $_primary = 'comment_id';
}

class checklist_template_floor extends Zend_Db_Table_Abstract
{
	protected $_name = 'checklist_template_floor';
	protected $_primary = 'id';
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