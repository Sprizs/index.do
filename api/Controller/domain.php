<?php
class domain {
	public static function prize() {
		Utils::DB_Connect();
		if (isset($_SESSION['checkcode']) && strtoupper($_POST['captcha']) == $_SESSION['checkcode']) {
			$domain = new Db('domain');
			$prize = new Db('prize');
			$domain_exists = $domain->select('id')->eq('domain', $_POST['domain'])->find();
			if (!$domain_exists) {
				echo json_encode(['status' => 404, 'message' => 'Domain Not Exists']);
				return;
			}
			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				echo json_encode(['status' => 403, 'message' => 'Email Address Not Valid']);
				return;
			}
			$rand_token = md5(uniqid());
			$content = "Please Click This Link To Confirm Your Domain Quotes：https://index.do/api/index.php/domain/confirm_prize?token=$rand_token";

			if (self::send_mail($_POST['email'], 'Conform Your Domain Quotes', $content, 'Conform Your Domain Quotes')) {
				$_SESSION['quotes_data'] = $_POST;
				$_SESSION['token'] = $rand_token;
				echo json_encode(['status' => 200, 'message' => 'A Domain Quotes Confirm Email Has been Send to Your Mailbox']);
			} else {
				echo json_encode(['status' => 400, 'message' => 'Email Server Error']);
			}
			unset($_SESSION['checkcode']);
			return;
		} else {
			echo json_encode(['status' => 403, 'message' => 'Wrong Checkcode']);
			unset($_SESSION['checkcode']);
			return;
		}
	}
	private static function parse_ip($ip) {
		if ($ip !== '127.0.0.1') {
			$result = json_decode(file_get_contents('https://api.ip.sb/geoip/' . $ip), true);
			return $result;
		} else {
			return ['country' => '本地网络'];
		}
	}
	public static function confirm_prize() {
		Utils::DB_Connect();
		if (isset($_SESSION['quotes_data']) && isset($_SESSION['token']) && $_SESSION['token'] == $_GET['token']) {
			$prize = new Db('prize');
			$smtp_server = new Db('smtp');
			$server_config = $smtp_server->select('recv_prize_mail')->eq('id', 1)->find();
			$recv_prize_mail = $server_config->data['recv_prize_mail'];
			$ip_info = self::parse_ip($_SERVER['REMOTE_ADDR']);
			$content = "Domain: " . $_SESSION['quotes_data']['domain'] . "<br>" .
			"Name: " . $_SESSION['quotes_data']['full_name'] . "<br>" .
			"Email: " . $_SESSION['quotes_data']['email'] . "<br>" .
			"Offer: " . intval($_SESSION['quotes_data']['price']) . "<br>" .
			"IP Address: " . $_SERVER['REMOTE_ADDR'] . "<br>" .
			"IP Info: " . @$ip_info['country'] . " " . @$ip_info['city'] . " " . @$ip_info['region'] . "<br>" .
			"Date: " . date('Y-m-d H:i:s');

			self::send_mail($recv_prize_mail, '[' . $_SESSION['quotes_data']['domain'] . ']Domain Prize Notifications', $content, 'Domain Prize Notifications');
			$prize->domain = $_SESSION['quotes_data']['domain'];
			$prize->prize = intval($_SESSION['quotes_data']['price']);
			$prize->email = $_SESSION['quotes_data']['email'];
			$prize->username = htmlspecialchars($_SESSION['quotes_data']['full_name']);
			$prize->time = time();
			$prize->ip = $_SERVER['REMOTE_ADDR'];
			$prize->insert();
			echo json_encode(['status' => 200, 'message' => 'Success']);
			unset($_SESSION['checkcode']);
			unset($_SESSION['token']);
			unset($_SESSION['quotes_data']);
		} else {
			echo json_encode(['status' => 400, 'message' => 'Token Wrong']);
		}
	}
	private static function send_mail($recv = '', $from_name = '', $content = '', $subject = '') {
		Utils::load_library('phpmailer');
		Utils::load_library('smtp');

		Utils::DB_Connect();
		$smtp_server = new Db('smtp');
		$server_config = $smtp_server->select('*')->eq('id', 1)->find();
		$configs = [
			'server' => $server_config->data['server'],
			'account' => $server_config->data['account'],
			'password' => $server_config->data['password'],
		];
		$ymail = $recv;

		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->Host = $configs['server'];
		$mail->Username = $configs['account'];
		$mail->Password = $configs['password'];
		$mail->From = $configs['account'];

		$mail->FromName = $from_name;
		//$rand_token = md5(uniqid());
		//$content = "Please Click This Link To Confirm Your Domain Quotes：https://index.do/api/domain/confirm_prize?token=$rand_token";
		$mail->AddAddress($ymail);

		$mail->Subject = $subject;
		date_default_timezone_set('Asia/Shanghai');
		$mail->CharSet = "UTF-8";
		$mail->MsgHTML($content);
		$mail->IsHTML(true);
		return $mail->Send();
		// if (true) {
		// 	echo json_encode(['status' => 400, 'message' => 'Email Server Error']);
		// } else {
		// 	echo json_encode(['status' => 200, 'message' => 'A Domain Quotes Confirm Email Has been Send to Your Mailbox']);
		// }
	}
	private function time_difference($begin_time, $end_time) {
		$datetime1 = date_create($begin_time);
		$datetime2 = date_create($end_time);
		$interval = date_diff($datetime1, $datetime2);
		return $interval;
	}
	public static function type_list() {
		Utils::DB_Connect();
		$type = new Db('type');
		$type = $type->select('text')->orderby('rank desc')->findAll();
		$type_list = [];
		foreach ($type as $key => $value) {
			$type_list[] = $value->data['text'];
		}
		echo json_encode($type_list);
	}
	public static function list() {
		Utils::DB_Connect();
		$domain = new Db('domain');
		if ($type = $_GET['type']) {
			$domain = $domain->select('*')->eq('type', $type)->orderby('timestamp desc')->findAll();
		} else {
			$domain = $domain->select('*')->orderby('timestamp desc')->findAll();
		}

		$domain_array = [];
		foreach ($domain as $key => $value) {
			$domain_array[$key]['id'] = intval($value->data['id']);
			$domain_array[$key]['domain'] = $value->data['domain'];
			$domain_array[$key]['description'] = $value->data['description'];
			$domain_array[$key]['status'] = intval($value->data['status']);
			$domain_array[$key]['type'] = $value->data['type'];
			$domain_array[$key]['rank'] = intval($value->data['rank']);
			$domain_array[$key]['image'] = $value->data['image'];
			if (isset($_SESSION['uid'])) {
				$domain_array[$key]['view_count'] = self::get_domain_view_count($value->data['domain']);
				$domain_array[$key]['register_time'] = $value->data['register_time'];
				$domain_array[$key]['expire_time'] = $value->data['expire_time'];
				if ($value->data['expire_time'] !== 'N/A') {
					$domain_array[$key]['remaining'] = self::time_difference($value->data['expire_time'], date('Y-m-d'))->days;
				} else {
					$domain_array[$key]['remaining'] = 'N/A';
				}

				$domain_array[$key]['registrar_name'] = $value->data['registrar_name'];
			}
			//$domain_array[$key]['timestamp'] = date('Y-m-d', $value->data['timestamp']);
			//$domain_array[] = $value->data;
		}
		$count = array();
		foreach ($domain_array as $counts) {
			$count[] = $counts['view_count'];
		}
		$result = array_multisort($count, SORT_DESC, $domain_array);
		echo json_encode($domain_array);
	}
	private static function get_domain_view_count($domain) {
		Utils::DB_Connect();
		$view = new Db('view');
		$view_count = $view->select('*')->eq('domain', $domain)->findAll();
		return count($view_count);
	}
	public static function info() {
		Utils::DB_Connect();
		$domain = new Db('domain');
		$view = new Db('view');
		$domain_query = $domain->select('*')->eq('domain', $_GET['name'])->find();
		if (!$domain_query) {
			echo json_encode(['status' => 404, 'message' => 'Domain Not Found']);
			return;
		} else {
			$view->domain = $_GET['name'];
			$view->timestamp = time();
			$view->insert();
			echo json_encode(['status' => 200, 'result' => $domain_query->data]);
			return;
		}
	}
	public static function search() {
		Utils::DB_Connect();
		$result = [];
		$domain = new Db('domain');
		$keyword = $_GET['name'];
		if (strlen($keyword) == 0) {
			echo json_encode(['status' => 404, 'message' => 'Domain Not Found']);
			return;
		}
		$return = [];
		$result = $domain->select('*')->like('domain', "%$keyword%")->findAll();
		foreach ($result as $key => $value) {
			$return[] = $value->data;
		}
		echo json_encode(['status' => 200, 'result' => $return]);
	}
}