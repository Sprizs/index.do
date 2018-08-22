<?php
if (!defined('IN_APP')) {
	http_response_code(403);
	die('Access Denied');
}
@session_start();
define('Config', [
	'type' => 'MySQL', //Support: MySQL/SQLite/PostgreSQL
	'database' => [
		//'name' => 'satori.db',
		'charset' => 'UTF8MB4',
		'host' => '127.0.0.1',
		'port' => 3306,
		'user' => 'root',
		'pass' => 'satori',
		'dbname' => 'domain_list',
	],
]);
class Db extends ActiveRecord {
	public $table = '';
	public $primaryKey = '';
	function __construct($name, $key = 'id') {
		$this->table = $name;
		$this->primaryKey = $key;
	}
}