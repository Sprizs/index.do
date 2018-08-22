<?php
/**
 * Created by PhpStorm.
 * User: youxianglimize
 * Date: 2017/8/8
 * Time: 下午10:17
 */

class MVC {
	public static $_action;
	public static $_method;

	public static $_error = [
		'action_not_found' => ['status' => 404, 'message' => 'Action Not Found'],
		'method_not_found' => ['status' => 404, 'message' => 'Method Not Found'],
	];

	private static $_pathinfo;

	public static function _init() {
		if (isset($_SERVER['PATH_INFO'])) {
			self::$_pathinfo = $_SERVER['PATH_INFO'];
		} else {
			if (isset($_GET['action']) && isset($_GET['method'])) {
				self::$_pathinfo = '/' . $_GET['action'] . '/' . $_GET['method'];
			} else {
				return false;
			}
		}
		if (!self::_path() || !class_exists(self::$_action)) {
			die(json_encode(self::$_error['action_not_found']));
		}

		if (!method_exists(self::$_action, self::$_method)) {
			die(json_encode(self::$_error['method_not_found']));
		}

		$action = new ReflectionClass(self::$_action);
		$action->getMethod(self::$_method)->invoke(new self::$_action);
	}

	private static function _path() {
		if (!self::$_pathinfo) {
			return false;
		}

		foreach (explode('/', self::$_pathinfo) as $key => $_path) {
			if ($key && $_path) {
				self::$_action ? self::$_method = $_path : self::$_action = $_path;
			}

		}
		return true;
	}

}