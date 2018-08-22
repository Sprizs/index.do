<?php
if (!defined('IN_APP')) {
	http_response_code(403);
	die('Access Denied');
}
class Output {
	public static $status = [
		'status' => -1,
		'message' => 'undefined',
	];
	public static function json() {
		echo json_encode(self::$status);
		exit();
	}
}
class Utils {
	public static $_action;
	public static $_method;
	public static function load_library($name) {
		if (file_exists($path = 'Library/' . $name . '.php')) {
			require_once $path;
		} else {
			return false;
		}
	}
	public static function DB_Connect() {
		switch (Config['type']) {
		case 'MySQL':
			return ActiveRecord::setDb(new PDO("mysql:host=" . Config['database']['host'] . ";charset=" . Config['database']['charset'] . ";port=" . Config['database']['port'] . ";dbname=" . Config['database']['dbname'] . "", Config['database']['user'], Config['database']['pass']));
			break;
		case 'SQLite':
			return ActiveRecord::setDb(new PDO('sqlite:' . Config['database']['name']));
			break;
		case 'PostgreSQL':
			return ActiveRecord::setDb(new PDO("pgsql:host=" . Config['database']['host'] . ";port=" . Config['database']['port'] . ";dbname=" . Config['database']['dbname'] . "", Config['database']['user'], Config['database']['pass']));
			break;
		}
	}
	public static function GetURLRewriteArray($url_arr, $type = false) {
		$url = explode('/', $url_arr);
		unset($url[0]);
		if (!$type) {
			$url = array_values($url);
			$url_array = [];
			for ($i = 0; $i < count($url); $i++) {
				if ($i % 2 == 0) {
					$url_array[$url[$i]] = $url[$i + 1];
				}
			}
			return $url_array;
		} else {
			$url = array_values($url);
			return $url;
		}
	}

	public static function _load($type = '') {
		if ($type && is_dir($type)) {
			foreach (scandir($type) as $file) {
				if (strstr($file, '.php')) {
					require_once $type . '/' . $file;
				}

			}
		}
	}

}