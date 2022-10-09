<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');


/*
|--------------------------------------------------------------------------
| General Options for all pages
|--------------------------------------------------------------------------
|
*/
$config['options'] = [
	'courts' => [
		'sp_crt_isb' => 'Supreme court - Islamabad registry ',
		'sp_crt_isb' => 'Supreme court - Karachi registry',
		
		'h_crt_khi' => 'Supreme court - Karachi',
		'h_crt_hyd' => 'Supreme court - Hyderabad',
		'h_crt_lark' => 'Supreme court - Larkana',
		'h_crt_sukk' => 'Supreme court - Sukkur',
	],
	'doc_types' => [ 
		'0' => 'Court',
		'1' => 'Respondent',
		'2' => 'Petitioner',
		'3' => 'Member',
	],
	'case_status' => [ //check
		'0' => 'Registered',
		'1' => 'Process',
		'2' => 'Completed',
	],
	'case_member_types' => [
		'1' => 'Petitioner',
		'2' => 'Respondent',
	],
];

/*
|--------------------------------------------------------------------------
| General Paths for files
|--------------------------------------------------------------------------
|
*/
// $config['litigationPath'] = 'upload/litigation/';