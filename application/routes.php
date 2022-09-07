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
$route['approval-to-exit'] = 'admin/approval_to_exit';

$route['header-footer'] = 'admin/header_footer';
$route['header-footer/(:num)'] = 'admin/header_footer/$1';
$route['pending-letter-reports'] = 'admin/pending_letter_reports';

$route['other-letters'] = 'admin/otherLetters';
$route['advance-salary-report'] = 'admin/advanceSalaryReport';
$route['advance-salary-withdrawal'] = 'admin/advanceSalaryWithdrawal';
$route['advance-salary-apilog'] = 'admin/advanceSalaryApiLog';




$route['create-letter'] = 'admin/create_letter';
$route['create-letter/(:num)'] = 'admin/create_letter/$1';
$route['create-templates'] = 'admin/create_templates';
$route['create-templates/letter-template-list'] = 'admin/letter_template_list';
$route['create-templates/letter-template-edit/(:num)'] = 'admin/letter_template_edit/$1';


$route['apointment-letter-preview/(:num)'] = 'admin/preview/$1';




/*   User */
$route['apointment-letter/(:any)'] = 'Pages/letters/$1';

/******************************create letter pdf & download*****************************************************/
$route['apointment-letter-download/(:any)'] = 'Pages/lettersDownload/$1';
/*******************************create letter pdf & download*********************************************/



$route['logout'] = 'app/logout';


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



