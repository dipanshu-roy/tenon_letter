<?php
defined('BASEPATH') OR exit('No direct script access allowed');
	
$route['default_controller'] = 'app';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['recovery-password'] = 'app/recovery_password';
$route['change-password/(:any)'] = 'app/change_password/$1';

/*   Admin   */
$route['admin-dashboard'] = 'admin/admin_dashboard';

$route['user/profile'] = 'admin/profile';

$route['create-roles'] = 'admin/create_roles';
$route['create-roles/(:num)'] = 'admin/create_roles';
$route['create-roles/map-user-role/(:num)'] = 'admin/map_user_role/$1';

$route['system-users'] = 'admin/system_users';
$route['system-users/(:num)'] = 'admin/system_users/$1';

$route['map-user-role/(:num)'] = 'admin/map_user_role/$1';

$route['create-officer-signature'] = 'admin/create_officer_signature';
$route['create-officer-signature/(:num)'] = 'admin/create_officer_signature/$1';

$route['map-officer-letter'] = 'admin/map_officer_letter';
$route['map-officer-letter/(:num)'] = 'admin/map_officer_letter/$1';

$route['absenteeism-letter-setting'] = 'admin/absenteeism_letter_setting';
$route['absenteeism-letter-setting/(:num)'] = 'admin/absenteeism_letter_setting/$1';


$route['approval-to-termination'] = 'admin/approval_to_termination';
$route['upload-cards'] = 'admin/upload_cards';
$route['letter-reports-with-filters'] = 'admin/letter_reports_with_filters';
$route['issue-warning-letters'] = 'admin/issue_warning_letters';
$route['issue-warning-letters/(:num)'] = 'admin/issue_warning_letters/$1';
$route['employee-exit'] = 'admin/employee_exit';
$route['employee-exit/(:num)'] = 'admin/employee_exit/$1';

$route['header-footer'] = 'admin/header_footer';
$route['header-footer/(:num)'] = 'admin/header_footer/$1';
$route['pending-letter-reports'] = 'admin/pending_letter_reports';

$route['other-letters'] = 'admin/otherLetters';
$route['other-letters/(:num)'] = 'admin/otherLetters/$i';
$route['advance-salary-report'] = 'admin/advanceSalaryReport';
$route['advance-salary-withdrawal'] = 'admin/advanceSalaryWithdrawal';
$route['advance-salary-apilog'] = 'admin/advanceSalaryApiLog';


$route['create-letter'] = 'admin/create_letter';
$route['create-letter/(:num)'] = 'admin/create_letter/$1';
$route['create-templates'] = 'admin/create_templates';
$route['create-templates/letter-template-list'] = 'admin/letter_template_list';
$route['create-templates/letter-template-edit/(:num)'] = 'admin/letter_template_edit/$1';
$route['create-notification'] = 'admin/create_notification';
$route['notification-history'] = 'admin/notification_history';


$route['apointment-letter-preview/(:num)'] = 'admin/preview/$1';
$route['letter-preview/(:num)/(:num)/(:num)'] = 'admin/letter_preview/$1/$1/$1';




/*   User */
$route['apointment-letter/(:any)'] = 'Pages/letters/$1';
$route['apointment-letter/(:any)/(:num)/(:num)'] = 'Pages/letters/$1/$1/$1';
$route['showletter/(:any)/(:any)'] = 'ShowLetters/letters/$1/$1';

$route['logout'] = 'app/logout';


/*****************for Refine Notification route*****************/
$route['create-refine-notification'] = 'Admin/createRefineNotification';
$route['refyne-delivery-reports'] = 'Admin/refyneDeliveryReports';
$route['save-acknowledgement'] = 'RefineNotification/save_acknowledgement';
$route['push-notification-stats'] = 'RefineNotification/push_notification_stats';
$route['push-notification'] = 'RefineNotification/push_notification';
$route['push-notification/delivery-acknowledgement'] = 'RefineNotification/delivery_acknowledgement';
/*****************for Refine Notification route*****************/


/**********************Start Create short link route***************************************/

$route['chkltr'] = 'Pages/chekLetter';


/**********************End Create short link route***************************************/


/*****************for Refine advance salary route*****************/
//for write files
$route['employee-info'] = 'Refine/employee_info';
$route['employee-data'] = 'Refine/employee_data';
$route['employee-attendance'] = 'Refine/attendance';
//for read files
$route['employee-transactions'] = 'Refine/readEmployeeTransaction';
$route['employee-withdrawal-limit'] = 'Refine/readEmployeeWithdrawal';
/*****************for Refine advance salary route*****************/


/*****************for Refine Notification route*****************/
$route['push-notification-stats'] = 'RefineNotification/push_notification_stats';
/*****************for Refine Notification route*****************/


/*****************for Trignodev Notification route*****************/
$route['send-notification'] = 'RefineNotification/TrignodevEmployeesNotification';
/*****************for Trignodev Notification route*****************/
