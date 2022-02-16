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
// $route['admin/(:any)'] = "admin/$1";
$route['members/(:any)'] = "admin/$1";
$route['members/projects/lists'] = "admin/projects/lists";
$route['members/projects/create'] = "admin/projects/create";
$route['members/projects/edit'] = "admin/projects/edit";
$route['members/projects/calendar'] = "admin/projects/calendar";
$route['members/calendar/lists'] = "admin/calendar/lists";
$route['members/projects/details/(:num)'] = "admin/projects/details/$1";
$route['members/projects/get_projects_list/(:num)'] = "admin/projects/get_projects_list/$1";
$route['members/home/get_tasks_list/(:num)'] = "admin/home/get_tasks_list/$1";
$route['members/invoices/invoice/(:num)'] = "admin/invoices/invoice/$1";
$route['members/leaves/get_leaves_list/(:num)'] = "admin/leaves/get_leaves_list/$1";
$route['members/payments/payment-modes'] = "admin/payments/payment-modes";
$route['members/estimates/view-estimate/(:num)'] = "admin/estimates/view-estimate/$1";
$route['members/invoices/view-invoice/(:num)'] = "admin/invoices/view-invoice/$1";
$route['members/estimates/edit-estimate/(:num)'] = "admin/estimates/edit-estimate/$1";
$route['members/invoices/edit-invoice/(:num)'] = "admin/invoices/edit-invoice/$1";
$route['members/users/edit-profile/(:num)'] = "admin/users/edit-profile/$1";
$route['members/estimates/estimate/(:num)'] = "admin/estimates/estimate/$1";
$route['members/settings/setting-detail'] = "admin/settings/setting-detail";
$route['members/chat/send_msg'] = "admin/chat/send_msg";
$route['members/tickets/manage-tickets'] = "admin/tickets/manage-tickets";
$route['members/tickets'] = "admin/tickets";
$route['members/tickets/send-message'] = "admin/tickets/send-message";
$route['members/tickets/get_ticket_messages'] = "admin/tickets/get_ticket_messages";
$route['clients/projects/details/(:num)'] = "admin/projects/details/$1";
$route['clients/projects/tasks/(:num)'] = "admin/projects/tasks/$1";
$route['members/projects/tasks/(:num)'] = "admin/projects/tasks/$1";
$route['members/announcements/details/(:num)'] = "admin/announcements/details/$1";
$route['members/notifications/details/(:num)'] = "admin/notifications/details/$1";
$route['members/announcements/create'] = "admin/announcements/create";
$route['members/announcements/edit'] = "admin/announcements/edit";
$route['members/users/detail/(:num)'] = "admin/users/detail/$1";
$route['members/tickets/get_ticket_by_id/(:num)'] = "admin/tickets/get_ticket_by_id/$1";
$route['members/tickets/get-ticket-messages'] = "admin/tickets/get-ticket-messages";
$route['clients/projects/lists'] = "admin/projects/lists";
$route['clients/projects/calendar'] = "admin/projects/calendar";
$route['clients/projects/calendar'] = "admin/projects/calendar";
$route['clients/projects/create'] = "admin/projects/create";
$route['clients/projects/edit'] = "admin/projects/edit";
$route['clients/users/detail/(:num)'] = "admin/users/detail/$1";
$route['clients/tickets/send-message'] = "admin/tickets/send-message";
$route['clients/tickets/get_ticket_by_id/(:num)'] = "admin/tickets/get_ticket_by_id/$1";
$route['clients/tickets/get_ticket_messages'] = "admin/tickets/get_ticket_messages";
$route['clients/tickets/get-ticket-messages'] = "admin/tickets/get-ticket-messages";
$route['members/projects/get_projects_list/(:num)'] = "admin/projects/get_projects_list/$1";

$route['members/(:any)/(:any)'] = "admin/$1";
$route['clients/(:any)/(:any)'] = "admin/$1";
$route['clients/(:any)/(:any)/(:any)'] = "admin/$1";
$route['clients/(:any)'] = "admin/$1";
$route['default_controller'] = 'home';
$route['projects/(:num)'] = 'projects';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;
