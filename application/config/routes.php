<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['divisi/i/(:any)'] = 'Divisi/index/$1';
$route['divisi/id/(:any)'] = 'Divisi/index_divisi_by_id/$1';

$route['pendidikan/'] = 'Pendidikan/';

// vacancy splitter
$route['vacancy/latest'] = 'Vacancy/index_limited/';
$route['vacancy/d/(:any)'] = 'Vacancy/index_limited/$1';
$route['vacancy/s'] = 'Vacancy/index_search_limited_vacancy';
$route['vacancy/s/(:any)'] = 'Vacancy/index_search_limited_vacancy/$1';
$route['vacancy/s-divisi/(:any)'] = 'Vacancy/index_sortby_divisi/$1';

// get vacancies by divisi
$route['vacancy/divisi/(:any)/(:any)'] = 'Vacancy/index_vacancy_divisi/$1/$2';

$route['vacancy/status'] = 'Vacancy/index_update_status/';

// get detail vacancy (by id)
$route['vacancy/id/(:any)'] = 'Vacancy/index_vacancy_detail/$1';

// get filtered vacancy on divisi
$route['vacancy/f/(:any)'] = 'Vacancy/index_filter_vacancy_in_divisi/$1';

// get for update vacancy
$route['vacancy/u/(:any)'] = 'Vacancy/updateVacancy/$1';


// FILTER ROUTE

// get filter jabatan
$route['filter/(:any)'] = 'Filter/index/$1';

// get point pendidikan
$route['edupoint/(:any)'] = 'Filter/getPendidikan/$1';

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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;