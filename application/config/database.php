<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
	'dsn'	=> '',
	// 'hostname' => '192.168.0.207',//'localhost',
	// 'username' => 'tenon-attendance',//'mobility_common',//'pg-staging',
	// 'password' => 'AttenD^cjejUi)DA',//'e4ft!#E$6ZT3X!StgnTU',//'Jhhh^hdjkI9*H#r4G1',
	// 'database' => 'tenon_attendance',
	'hostname' => 'localhost',//'localhost',
	'username' => 'root',
	'password' => '',
	'database' => 'tenon_attendance',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
