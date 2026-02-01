<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'user';
$route['user-login'] = 'User/index';
$route['log'] = 'User/login';
$route['page'] = 'User/dashboard';
/*$route['check-In/(:any)'] = 'User/checkIn';*/
$route['add-depart'] = 'User/adddepartment';
$route['add-sub-depart'] = 'User/addsubdepartment';
$route['assign-depart'] = 'User/assigndepart';
$route['assign-sdepart'] = 'User/assignsubdepart';
$route['users'] = 'User/userslist';
$route['b-departs'] = 'User/showBusinessDeparts';
$route['tokens'] = 'User/businessTokens';
$route['counter-tokens'] = 'User/counterTokens';
$route['assi-counter'] = 'User/AssignCounter';
$route['c-pass'] = 'User/changePass';
$route['profile'] = 'User/userProfile';
$route['User/profile'] = 'User/profile';
$route['update-appointment'] = 'User/appointPage';
$route['assign-appointment'] = 'User/appointment';
$route['update-appodata'] = 'User/updateappointPage';
$route['view-appoinments'] = 'User/appoinments';
$route['view-request'] = 'User/request_data';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['new-qr'] = 'User/activeNewQR';
$route['qr'] = 'User/qrProfile';
$route['attendance'] = 'User/attendance';
$route['licence-qr'] = 'User/licence_qr';
$route['business-users'] = 'User/businessUsers';
/////////////
$route['employees'] = 'User/employees';
$route['addemployee'] = 'User/addemployee';
$route['dailyreport'] = 'User/daily_report2';
$route['monthly_report'] = 'User/monthlyreport2';
$route['employee_report'] = 'User/employee_report';
$route['leave'] = 'User/leave';
$route['leave_report'] = 'User/leave_report';
$route['Sleave'] = 'User/S_leave';
$route['manual_attendance'] = 'User/manual_attendance';
$route['attendance_rule'] = 'User/attendance_rule';
$route['open_leave'] = 'User/open_leave';
$route['salary-employees'] = 'User/salaryEmployees';
$route['staff'] = 'User/staff';
$route['add-staff'] = 'User/addFaculty';
$route['students'] = 'User/students';
$route['add-student'] = 'User/addStudent';
///////////////
$route['field_duty'] = 'User/field_duty';
$route['pending_att'] = 'User/pending_att';
$route['manager_roll'] = 'User/manager_roll';
$route['assign_shift'] = 'User/assign_shift';
$route['department_list'] = 'User/department_list';
$route['section_list'] = 'User/section_list';
$route['shifts'] = 'User/shifts';
$route['holidays_list'] = 'User/holidays_list';
$route['gps_report'] = 'User/gps_report';
$route['attendance_option'] = 'User/attendanceOptions';
$route['manual_report'] = 'User/manualReport';
$route['device_list'] = 'User/device_list';
$route['access_report'] = 'User/access_report';
$route['left_employee'] = 'User/left_employee';
/////
$route['generate_login'] = 'User/generate_login';
/////
$route['salary_head'] = 'User/salary_head';
$route['salary_report'] = 'User/salary_report';
$route['advance_report'] = 'User/advance_report';
$route['net_payable'] = 'User/net_payable';
$route['earnings'] = 'User/earnings';
$route['deduction'] = 'User/deduction';
$route['salary_rule'] = 'User/salary_rule';





$route['business_users'] = 'User/businessUsers';
$route['premium_business_users'] = 'User/premiumbusinessusers';
$route['active_business_users'] = 'User/activebusinessusers';
$route['inactive_business_users'] = 'User/inactivebusinessusers';
$route['active_users'] = 'User/activeusers';
$route['inactive_users'] = 'User/inactiveusers';
$route['licence_history'] = 'User/licence_history';
////

///
$route['staff-login'] = 'User/staff_login';
$route['page_staff'] = 'User/dashboard_staff';
$route['page_hostel'] = 'User/dashboard_hostel';



////
$route['student_list'] = 'User/student_list';
$route['school_student'] = 'User/school_student';

$route['hostel_detail'] = 'User/hostel_detail';
$route['hostel_daily_report'] = 'User/hostel_daily_report';
$route['hostel_monthly_report'] = 'User/hostel_monthly_report';
$route['complain_detail'] = 'User/complain_detail';
$route['students_checkin'] = 'User/student_checkin';
$route['get_user_from_device'] = 'User/getUserFromDevice';
$route['add_user_to_device'] = 'User/addUserToDevice';
$route['leave_history'] = 'User/leave_history';
