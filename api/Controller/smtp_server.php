<?php
class smtp_server {
	public static function get() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$smtp_server = new Db('smtp');
		$server_config = $smtp_server->select('*')->eq('id', 1)->find();
		$configs = [
			'server' => $server_config->data['server'],
			'account' => $server_config->data['account'],
			'password' => $server_config->data['password'],
			'recv_prize_mail' => $server_config->data['recv_prize_mail'],
		];
		echo json_encode(['status' => 200, 'result' => $configs]);
	}
	public static function set() {
		if (!$_SESSION['uid']) {
			echo json_encode(['status' => 401, 'message' => '未登录']);
			return;
		}
		Utils::DB_Connect();
		$smtp_server = new Db('smtp');
		$smtp_server->id = 1;
		$smtp_server->server = $_POST['server'];
		$smtp_server->account = $_POST['account'];
		$smtp_server->password = $_POST['password'];
		$smtp_server->recv_prize_mail = $_POST['recv_prize_mail'];
		$smtp_server->update();
		echo json_encode(['status' => 200, 'message' => '更新成功']);
	}
}