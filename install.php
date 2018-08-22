<?php
error_reporting(0);
if (file_exists('install.lock')) {
	die('权限拒绝: 请先删除install.lock后再安装');
}
include 'api/ActiveRecord.php';
class Db extends ActiveRecord {
	public $table = '';
	public $primaryKey = '';
	function __construct($name, $key = 'id') {
		$this->table = $name;
		$this->primaryKey = $key;
	}
}
session_start();
$step = @$_GET['step'];
switch ($step) {
case 'check_db':
	try {
		ActiveRecord::setDb(new PDO("mysql:host=" . @$_POST['host'] . ";charset=UTF8MB4;port=" . intval(@$_POST['port']) . ";dbname=" . @$_POST['dbname'] . "", @$_POST['user'], @$_POST['pass']));
		ActiveRecord::execute("CREATE TABLE `domain` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain` text NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `register_time` text NOT NULL,
  `registrar_name` text NOT NULL,
  `expire_time` text NOT NULL,
  `status` int(4) NOT NULL DEFAULT 0,
  `rank` int(4) NOT NULL DEFAULT 0,
  `type` text NOT NULL,
  `timestamp` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
		ActiveRecord::execute("CREATE TABLE `prize` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain` text NOT NULL,
  `prize` int(20) NOT NULL,
  `email` text NOT NULL,
  `username` text NOT NULL,
  `ip` text NOT NULL,
  `time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
		ActiveRecord::execute("CREATE TABLE `smtp` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `server` text NOT NULL,
  `account` text NOT NULL,
  `password` text NOT NULL,
  `recv_prize_mail` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
		ActiveRecord::execute("CREATE TABLE `user` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `regtime` int(10) NOT NULL,
  `regip` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
		ActiveRecord::execute("CREATE TABLE `view` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `domain` text NOT NULL,
  `timestamp` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
		$smtp = new Db('smtp');
		$smtp->server = '请设置SMTP服务器';
		$smtp->account = '请设置SMTP账号';
		$smtp->password = '请设置SMTP密码';
		$smtp->recv_prize_mail = '请设置报价接收邮箱';
		$smtp->insert();

		$_SESSION['db']['host'] = $_POST['host'];
		$_SESSION['db']['port'] = $_POST['port'];
		$_SESSION['db']['dbname'] = $_POST['dbname'];
		$_SESSION['db']['user'] = $_POST['user'];
		$_SESSION['db']['pass'] = $_POST['pass'];

		$write_file = file_get_contents('api/config.php');
		$replace_file = str_replace([
			'default_config_host',
			'default_config_port',
			'default_config_user',
			'default_config_pass',
			'default_config_dbname',
		], [
			$_SESSION['db']['host'],
			$_SESSION['db']['port'],
			$_SESSION['db']['user'],
			$_SESSION['db']['pass'],
			$_SESSION['db']['dbname'],
		], $write_file);
		try {
			file_put_contents('api/config.php', $replace_file);
		} catch (Exception $e) {
			echo json_encode(['status' => 400, 'message' => $e->getMessage()]);
			die();
		}
		echo json_encode(['status' => 200, 'message' => '数据库导入成功']);
		die();
	} catch (Exception $e) {
		echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
		die();
	}
	break;
case 'add_admin':
	try {
		ActiveRecord::setDb(new PDO("mysql:host=" . $_SESSION['db']['host'] . ";charset=UTF8MB4;port=" . $_SESSION['db']['port'] . ";dbname=" . $_SESSION['db']['dbname'] . "", $_SESSION['db']['user'], $_SESSION['db']['pass']));

		$user = new Db('user');
		$user->username = $_POST['username'];
		$user->password = md5($_POST['password']);
		$user->regtime = time();
		$user->regip = $_SERVER['REMOTE_ADDR'];
		$user->insert();
		echo json_encode(['status' => 200, 'message' => '管理员创建成功']);
		file_put_contents('install.lock', 'owo');
		unset($_SESSION['db']);
		unset($_SESSION['uid']);
		die();
	} catch (Exception $e) {
		echo json_encode(['status' => 500, 'message' => $e->getMessage()]);
		die();
	}
	break;
}
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title>安装 - 烧饼米表</title>
        <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/4.0.0-beta/css/bootstrap.min.css">
        <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/popper.js/1.12.5/umd/popper.min.js"></script>
        <script src="https://cdn.bootcss.com/bootstrap/4.0.0-beta/js/bootstrap.min.js"></script>
        <style type="text/css">
        .container {
            max-width: 90%;
        }
        </style>
    </head>

    <body>
        <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
            <div class="container">
                <a class="navbar-brand" href="#">烧饼米表</a>
            </div>
        </nav>
        <div style="height: 50px"></div>
        <div class="container">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a id="step_1_nav" class="nav-link active" href="javascript:;">设置数据库</a>
                </li>
                <li class="nav-item">
                    <a id="step_2_nav" class="nav-link disabled" href="javascript:;">添加管理账号</a>
                </li>
                <li class="nav-item">
                    <a id="step_3_nav" class="nav-link disabled" href="javascript:;">完成</a>
                </li>
            </ul>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-sm-1">
                </div>
                <div class="col-sm-10">
                    <br>
                    <div id="message"></div>
                    <br>
                    <div id="content_step">
                        <h4>文件权限检测</h4>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>文件名字</th>
                                    <th>读取</th>
                                    <th>写入</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>api/</td>
                                   <td><?php if (is_readable('api/')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('api/')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                                <tr>
                                    <td>assets/</td>
                                    <td><?php if (is_readable('assets/')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('assets/')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                                <tr>
                                    <td>admin/</td>
                                    <td><?php if (is_readable('admin/')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('admin/')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                                <tr>
                                    <td>css/</td>
                                    <td><?php if (is_readable('css/')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('css/')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                                <tr>
                                    <td>js/</td>
                                    <td><?php if (is_readable('js/')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('js/')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                                <tr>
                                    <td>config.json</td>
                                    <td><?php if (is_readable('config.json')) {echo "可读";} else {echo "不可读";}?></td>
                                    <td><?php if (is_writable('config.json')) {echo "可写";} else {echo "不可写";}?></td>
                                </tr>
                            </tbody>
                        </table>
                        <hr>
                        <div class="form-group">
                            <label for="mysql_address">数据库地址:</label>
                            <input type="text" class="form-control" id="mysql_address" placeholder="127.0.0.1">
                        </div>
                        <div class="form-group">
                            <label for="mysql_port">数据库端口:</label>
                            <input type="text" class="form-control" id="mysql_port" placeholder="3306">
                        </div>
                        <div class="form-group">
                            <label for="mysql_username">数据库用户名:</label>
                            <input type="text" class="form-control" id="mysql_username" placeholder="root">
                        </div>
                        <div class="form-group">
                            <label for="mysql_password">数据库密码:</label>
                            <input type="text" class="form-control" id="mysql_password" placeholder="******">
                        </div>
                        <div class="form-group">
                            <label for="mysql_dbname">数据库表名:</label>
                            <input type="text" class="form-control" id="mysql_dbname" placeholder="sb_database">
                        </div>
                        <br>
                        <a href="javascript:;" id="step_to_add_admin" class="btn btn-success">下一步</a>
                    </div>
                </div>
                <div class="col-sm-1">
                </div>
            </div>
        </div>
        <div id="message_success" style="display: none">
            <div class="alert alert-success">
                <strong id="success_prefix"></strong><span id="success_message"></span>
            </div>
        </div>
        <div id="content_step_2" style="display: none;">
            <div class="form-group">
                <label for="admin_username">管理员账号:</label>
                <input type="text" class="form-control" id="admin_username" placeholder="admin">
            </div>
            <div class="form-group">
                <label for="admin_password">管理员密码:</label>
                <input type="text" class="form-control" id="admin_password" placeholder="******">
            </div>
            <br>
            <a href="javascript:;" onclick="step_to_success()" class="btn btn-success">下一步</a>
        </div>
        <div id="content_step_3" style="display: none;">
            <br>
            <a href="admin/index.php" target="_blank">点击进入米表后台</a>
        </div>
        <div id="message_error" style="display: none">
            <div class="alert alert-danger">
                <strong id="error_prefix"></strong><span id="error_message"></span>
            </div>
        </div>
        <script type="text/javascript">
        $('#step_to_add_admin').on('click', function() {
            $.ajax({
                type: "POST",
                url: 'install.php?step=check_db',
                dataType: 'json',
                data: {
                    host: $('#mysql_address').val(),
                    port: $('#mysql_port').val(),
                    dbname: $('#mysql_dbname').val(),
                    user: $('#mysql_username').val(),
                    pass: $('#mysql_password').val()
                },
                success: function(data) {
                    if (data['status'] == 200) {
                        $('#message').html($('#message_success').html())
                        $('#success_message').text(data['message']);
                        $('#success_prefix').text("成功：");
                        $('#content_step').html($('#content_step_2').html());
                        $('#step_1_nav').addClass('disabled');
                        $('#step_1_nav').removeClass('active')
                        $('#step_2_nav').addClass('active');
                    } else {
                        $('#message').html($('#message_error').html())
                        $('#error_message').text(data['message']);
                        $('#error_prefix').text("数据库导入失败：");
                    }
                    console.log(data)
                }
            });
        })

        function step_to_success() {
            $.ajax({
                type: "POST",
                url: 'install.php?step=add_admin',
                dataType: 'json',
                data: {
                    username: $('#admin_username').val(),
                    password: $('#admin_password').val()
                },
                success: function(data) {
                    if (data['status'] == 200) {
                        $('#message').html($('#message_success').html())
                        $('#success_message').text(data['message']);
                        $('#success_prefix').text("成功：");
                        $('#content_step').html($('#content_step_3').html());
                        $('#step_1_nav').addClass('disabled');
                        $('#step_1_nav').removeClass('active')
                        $('#step_2_nav').removeClass('active')
                        $('#step_2_nav').addClass('disabled');
                        $('#step_3_nav').removeClass('disabled')
                        $('#step_3_nav').addClass('active')
                    } else {
                        $('#message').html($('#message_error').html())
                        $('#error_message').text(data['message']);
                        $('#error_prefix').text("管理员添加失败：");
                    }
                    console.log(data)
                }
            });
        }
        </script>
    </body>

    </html>