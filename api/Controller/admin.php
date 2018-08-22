<?php
class admin {
	public static function edit_classification() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$type = new Db('type');
		$types = $type->select('*')->eq('text', $_POST['type'])->find();
		$types->delete();
		unset($type);
		$type = new Db('type');
		$type_list = $type->select('text')->orderby('rank desc')->findAll();
		$type_lists = [];
		foreach ($type_list as $key => $value) {
			$type_lists[] = $value->data['text'];
		}
		echo json_encode($type_lists);
	}
	public static function add_classification() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		// $type = json_decode(file_get_contents('../admin/type.json'), true);
		// $type[] = $_POST['name'];
		// file_put_contents('../admin/type.json', json_encode($type));
		Utils::DB_Connect();
		$type = new Db('type');
		$type->text = $_POST['name'];
		$type->rank = $_POST['rank'];
		$type->insert();
		echo json_encode(['status' => 200, 'message' => '添加成功']);
	}

	public static function settings() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		$contact_email = $_POST['contact_email'];
		$site_name = $_POST['site_name'];
		$site_title = $_POST['site_title'];
		$site_footer_link = $_POST['site_footer_link'];
		//var_dump(file_get_contents('../admin/settings.json'));
		file_put_contents('../admin/settings.json', json_encode([
			'contact-email' => $contact_email,
			'site-name' => $site_name,
			'site-title' => $site_title,
			'site-footer-link' => $site_footer_link,
		]));

		echo json_encode(['status' => 200, 'message' => '更新成功']);
	}
	public static function get_statistical_data() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();

		$domain = new Db('domain');

		if (@$_GET['type'] == 'bandwidth') {
			$view = new Db('view');
			//print_r(date('Y'));
			//print_r(mktime(0, 0, 0, date('d'), date('m'), date('Y')));
			$domain_list = $view->select('*')->where('timestamp between ' . (time() - 86400) . ' AND ' . time())->findAll();
			//$domain_bandwidth = 0;
			// foreach ($domain_list as $key => $value) {
			// 	$domain_bandwidth += self::get_domain_view_count($value->data['domain']);
			// }
			echo json_encode(['count' => count($domain_list)]);
		} else {
			$domain_list = $domain->select('domain')->findAll();
			$domain_list_count = [];
			foreach ($domain_list as $key => $value) {
				$domain_list_count[] = ['domain' => $value->data['domain'], 'count' => self::get_domain_view_count($value->data['domain'])];
			}
			$count = array();
			foreach ($domain_list_count as $counts) {
				$count[] = $counts['count'];
			}
			$result = array_multisort($count, SORT_DESC, $domain_list_count);
			echo json_encode(array_slice($domain_list_count, 0, 5));
		}
	}
	private static function get_domain_view_count($domain) {
		Utils::DB_Connect();
		$view = new Db('view');
		$view_count = $view->select('*')->eq('domain', $domain)->findAll();
		return count($view_count);
	}
	public static function get_domain() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$domain = new Db('domain');
		$domain = $domain->select('*')->eq('id', $_GET['id'])->find();
		echo json_encode($domain->data);
	}
	public static function import() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$domain = new Db('domain');
		$f = $_POST['content'];
		if (strlen($f) < 2) {
			echo json_encode(['status' => 400, 'message' => '导入失败：数据为空']);
			return;
		}
		$file = explode(PHP_EOL, $f);
		$array = [];
		foreach ($file as $key => $value) {
			$array[] = explode('|', $value);
		}
		$fail_count = 0;
		foreach ($array as $key => $value) {
			if (!self::domain_exists($domain->domain = $value[0])) {
				$domain->domain = $value[0];
				$domain->description = $value[1];
				$domain->status = $value[2];
				$domain->type = $value[3];
				$domain->rank = $value[4];
				$domain->timestamp = time();
				$domain->insert();
			} else {
				$fail_count = $fail_count + 1;
			}
		}
		echo json_encode(['status' => 200, 'message' => '导入成功,总计:' . count($array) . "条记录,失败" . $fail_count . "条"]);
	}
	private static function domain_exists($domain_name) {
		Utils::DB_Connect();
		$domain = new Db('domain');
		$domain_exists = $domain->select('id')->eq('domain', $domain_name)->find();
		if (!$domain_exists) {
			return false;
		} else {
			return true;
		}
	}
	public static function status() {
		if (isset($_SESSION['uid'])) {
			echo json_encode(['status' => 200]);
		} else {
			echo json_encode(['status' => 401]);
		}
	}
	public static function logout() {
		unset($_SESSION['uid']);
		header('Location: /');
	}
	public static function get_prize_list() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$prize = new Db('prize');
		$prize_list = $prize->select('*')->where('time between ' . (time() - 864000) . ' and ' . time())->orderby('time asc')->findAll();
		$prize_list_array = [];
		foreach ($prize_list as $key => $value) {
			$prize_list_array[] = $value->data;
		}
		foreach ($prize_list_array as $key => $value) {
			$prize_list_array[$key]['time'] = date('Y-m-d H:i:s', $value['time']);
		}
		echo json_encode($prize_list_array);
	}
	public static function delete_admin() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$user = new Db('user');
		$user_exists = $user->select('id')->eq('id', $_POST['id'])->find();
		if (!$user_exists) {
			echo json_encode(['status' => 404, 'message' => '用户不存在']);
			return;
		} else {
			$user_exists->delete();
			echo json_encode(['status' => 200, 'message' => '删除成功']);
		}
	}
	public static function reset_password() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$user = new Db('user');
		$userdata = $user->select('*')->eq('id', $_SESSION['uid'])->find();
		$userdata->password = md5($_POST['password']);
		$userdata->update();
		echo json_encode(['status' => 200, 'message' => '修改成功']);
	}
	public static function admin_list() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$user = new Db('user');
		$user_list = $user->select('id,username,regip')->findAll();
		$users_list = [];
		foreach ($user_list as $key => $value) {
			$users_list[] = $value->data;
		}
		echo json_encode($users_list);
	}
	public static function add_admin() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$user = new Db('user');
		$user->username = $_POST['username'];
		$user->password = md5($_POST['password']);
		$user->regtime = time();
		$user->regip = $_SERVER['REMOTE_ADDR'];
		$user->insert();
		echo json_encode(['status' => 200, 'message' => '添加成功']);
	}
	public static function login() {
		if (isset($_SESSION['uid'])) {
			echo json_encode(['status' => 403, 'message' => '重复登录']);
			return;
		}
		Utils::DB_Connect();
		$password = $_POST['password'];
		if (!$password) {
			echo json_encode(['status' => 400, 'message' => '密码错误']);
			return;
		}
		$user = new Db('user');
		$user_exists = $user->select('*')->eq('username', $_POST['username'])->find();
		if (!$user_exists) {
			echo json_encode(['status' => 404, 'message' => '用户不存在']);
			return;
		}
		$user_data = $user_exists->data;
		if ($user_data['password'] == md5($password)) {
			echo json_encode(['status' => 200, 'message' => '登录成功']);
			$_SESSION['uid'] = rand(1000, 5000);
			return;
		} else {
			echo json_encode(['status' => 400, 'message' => '密码错误']);
			return;
		}
	}
	public static function edit_domain() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => 'UnAuthorized']);
			return;
		}
		Utils::DB_Connect();
		$domain = new Db('domain');

		$new_domain = $_POST['domain'];
		$description = $_POST['description'];
		$type = $_POST['type'];
		$status = intval($_POST['status']);

		$domain_exists = $domain->select('*')->eq('domain', $new_domain)->find();
		if (!$domain_exists) {
			echo json_encode(['status' => 403, 'message' => 'Domain Not Exists']);
			return;
		} else {
			$domain->domain = $new_domain;
			$domain->description = $description;
			$domain->status = $status;
			$domain->register_time = $_POST['register_time'];
			$domain->expire_time = $_POST['expire_time'];
			$domain->registrar_name = $_POST['registrar_name'];
			$domain->type = $type;
			$domain->rank = $_POST['rank'];
			$domain->timestamp = time();
			$domain->update();
			echo json_encode(['status' => 200, 'message' => 'Domain Edit Success']);
		}
	}
	public static function add_domain() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => 'UnAuthorized']);
			return;
		}
		Utils::DB_Connect();
		$domain = new Db('domain');

		$new_domain = $_POST['domain'];
		$description = $_POST['description'];
		$type = $_POST['type'];
		$status = intval($_POST['status']);

		$domain_exists = $domain->select('*')->eq('domain', $new_domain)->find();
		if ($domain_exists) {
			echo json_encode(['status' => 403, 'message' => 'Domain Exists']);
			return;
		} else {
			$domain->domain = $new_domain;
			$domain->description = $description;
			$domain->status = $status;
			$domain->register_time = $_POST['register_time'];
			$domain->expire_time = $_POST['expire_time'];
			$domain->registrar_name = $_POST['registrar_name'];
			$domain->type = $type;
			$domain->rank = intval($_POST['rank']);
			$domain->timestamp = time();
			$domain->insert();
			echo json_encode(['status' => 200, 'message' => 'Domain Added']);
		}
	}
	public static function delete_domain() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => 'UnAuthorized']);
			return;
		}
		Utils::DB_Connect();
		$domain_id = $_POST['id'];
		$domain = new Db('domain');
		$domain_exists = $domain->select('*')->eq('id', $domain_id)->find();
		if (!$domain_exists) {
			echo json_encode(['status' => 403, 'message' => 'Domain Not Exists']);
			return;
		} else {
			$domain_exists->delete();
			echo json_encode(['status' => 200, 'message' => 'Delete Success']);
		}
	}
}