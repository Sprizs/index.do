<?php
error_reporting(0);
@session_start();
if (!$_SESSION['uid']) {
	header('Location: login.html');
}
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>域名管理 - 烧饼米表</title>
        <meta name="keywords" content="index">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="renderer" content="webkit">
        <meta http-equiv="Cache-Control" content="no-siteapp" />
        <link rel="icon" type="image/png" href="favicon-16x16.png">
        <meta name="apple-mobile-web-app-title" content="Amaze UI" />
        <script src="../assets/js/echarts.min.js"></script>
        <link rel="stylesheet" href="../assets/css/amazeui.min.css" />
        <link rel="stylesheet" href="../assets/css/amazeui.datatables.min.css" />
        <link rel="stylesheet" href="../assets/css/font-awesome.min.css" />
        <link href="https://cdn.bootcss.com/stacktable.js/1.0.3/stacktable.css" rel="stylesheet">
        <!--  <link rel="stylesheet/less" type="text/css" href="../assets/css/app.less"/>-->
        <!--  <script src="https://cdn.bootcss.com/less.js/2.7.2/less.min.js"></script>-->
        <link rel="stylesheet" href="../assets/css/app.css">
        <script src="js/Q.js"></script>
        <script src="js/echart.min.js"></script>
        <script src="../assets/js/jquery.min.js"></script>
        <script src="https://cdn.bootcss.com/stacktable.js/1.0.3/stacktable.js"></script>
    </head>

    <body data-type="index">
        <script src="../assets/js/theme.js"></script>
        <div class="am-g tpl-g">
            <!-- 头部 -->
            <header>
                <!-- logo -->
                <div class="tpl-header-logo">
                    <a href="javascript:;"><img src="../assets/img/logo.png" alt=""></a>
                </div>
                <!-- 右侧内容 -->
                <div class="tpl-header-fluid">
                    <!-- 侧边切换 -->
                    <div class="tpl-header-switch-button fa fa-bars"></div>
                    <!-- 其它功能-->
                    <div class="am-fr tpl-header-navbar">
                        <ul>
                            <!-- 退出 -->
                            <li><a href="../api/index.php/admin/logout"><span class="fa fa-power-off"></span>退出</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            <main>
                <!-- 风格切换 -->
                <div class="tpl-skiner">
                    <div class="tpl-skiner-toggle">
                        <span class="fa fa-gear"></span>
                    </div>
                    <div class="tpl-skiner-content">
                        <span class="skiner-color skiner-white" data-color="theme-white"></span>
                        <span class="skiner-color skiner-black" data-color="theme-black"></span>
                    </div>
                </div>
                <!-- 侧边导航栏 -->
                <div class="left-sidebar">
                    <!-- 菜单 -->
                    <ul class="sidebar-nav">
                        <li class="sidebar-nav-link" id="sidebar_listener">
                            <a href="#!dashboard" onclick="location.reload();" class="dashboard">
            <span class="fa fa-home"></span>仪表盘
          </a>
                            <a href="#!domain_list" class="domain_list">
            <span class="fa fa-list-ul"></span>域名列表
          </a>
                            <a href="#!add_domain" onclick="location.reload();" class="add_domain">
            <span class="fa fa-plus-circle"></span>新增域名
          </a>
                            <a href="#!admin_add" class="admin_add">
            <span class="fa fa-user-plus"></span>增加管理员
          </a>
                            <a href="#!admin_list" class="admin_list">
            <span class="fa fa-address-card"></span>管理员列表
          </a>
                            <a href="#!prize_list" class="prize_list">
            <span class="fa fa-money"></span>报价列表
          </a>
                            <a href="#!settings" class="settings">
            <span class="fa fa-cog"></span>网站设置
          </a>
                            <a href="#!classification" class="classification">
            <span class="fa fa-tachometer"></span>分类设置
          </a>
                            <a href="#!smtp" class="smtp">
            <span class="fa fa-envelope"></span>SMTP设置
          </a>
                            <a href="#!reset_password" class="reset_password">
            <span class="fa fa-lock"></span>修改密码
          </a>
                            <a class="logout" href="../api/index.php/admin/logout">
            <span class="fa fa-power-off"></span>退出登录
          </a>
                        </li>
                    </ul>
                </div>
                <!-- 内容区域 -->
                <div class="tpl-content-wrapper">
                    <div class="row-content">
                        <div class="widget" id="contents"></div>
                    </div>
                </div>
                <div class="content domain_list" style="display: none">
                    选择分类：
                    <div id="domain_type_list">
                        <a class="am-btn am-btn-success" href="#!domain_list">全部</a>
                    </div>
                    <br>
                    <table class="am-table am-table-compact am-table-striped tpl-table-black">
                        <thead>
                            <tr>
                                <th style="width: 10%">名字</th>
                                <th style="width: 10%">描述</th>
                                <th style="width: 6%">状态</th>
                                <th style="width: 9%">类型</th>
                                <th style="width: 5%">访问量</th>
                                <th style="width: 10%">注册时间</th>
                                <th style="width: 5%">过期时间</th>
                                <th style="width: 5%">剩余天数</th>
                                <th style="width: 22%">注册商</th>
                                <th style="width: 5%">权重</th>
                                <th style="width: 13%">操作</th>
                            </tr>
                        </thead>
                        <tbody id="table_domain_list">
                        </tbody>
                    </table>
                </div>
                <div class="content add_domain" style="display: none">
                    <form class="am-form tpl-form-line-form">
                        <fieldset>
                            <div class="am-form-group">
                                <h4>多域名导入</h4>
                                <textarea id="domain_list_import" placeholder="doma.in|sixsixsix|1|Moe|10" rows="9"></textarea>
                                <br>
                                <a class="am-btn am-btn-primary" id="multi_domain_import">导入</a>
                                <script type="text/javascript">
                                $('#multi_domain_import').on('click', function() {
                                    $.ajax({
                                        method: 'POST',
                                        url: '../api/index.php/admin/import',
                                        async: true,
                                        data: {
                                            content: $('#domain_list_import').val(),
                                        },
                                        success: function(data) {
                                            alert('[' + data['status'] + ']' + data['message']);
                                        },
                                    });
                                });
                                </script>
                                <hr>
                                <h4>单域名导入</h4>
                                <label class="form-label" for="input-1">域名</label>
                                <input type="text" id="domain_name" placeholder="baka.com" />
                                <br>
                                <label class="form-label">注册时间</label>
                                <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd'}">
                                    <input id="domain_register_time" type="text" class="am-form-field" placeholder="">
                                    <div class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button">
                                            <span class="am-icon-calendar"></span>
                                        </button>
                                    </div>
                                </div>
                                <br>
                                <label class="form-label">到期时间</label>
                                <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd'}">
                                    <input id="domain_expire_time" type="text" class="am-form-field" placeholder="">
                                    <div class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                    </div>
                                </div>
                                <br>
                                <label class="form-label">注册商</label>
                                <input type="text" id="domain_registrar" />
                                <br>
                                <label class="form-label">权重</label>
                                <input type="text" id="domain_rank" value="0" />
                                <br>
                                <script type="text/javascript">
                                $('#domain_name').on('input', function() {
                                    var regexp = new RegExp(
                                        '^(?=^.{3,255}$)[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+$');
                                    if (regexp.test($('#domain_name').val())) {
                                        $.getJSON('whois.php?domain=' + $('#domain_name').val(), function(data) {
                                            $('#domain_register_time').datepicker('setValue', data['register_date']);
                                            $('#domain_expire_time').datepicker('setValue', data['expire_date']);
                                            $('#domain_registrar').val(data['registrar_name']);
                                        });
                                    }

                                });
                                </script>
                                <label class="form-label" for="input-1">描述</label>
                                <textarea id="domain_description" placeholder="_(:з」∠)=" rows="3"></textarea>
                                <br>
                                <label class="form-label">状态</label>
                                <label class="form-radio">
                                    <input class="sell_type" type="radio" name="status" checked/>
                                    <i class="form-icon"></i> 正在出售
                                </label>
                                <label class="form-radio">
                                    <input class="sell_type" type="radio" name="status" />
                                    <i class="form-icon"></i> 不出售
                                </label>
                                <br>
                                <br>
                                <label class="form-label">类型</label>
                                <select id="type_selector">
                                </select>
                                <br>
                                <a class="am-btn am-btn-primary" onclick="post_new_domain();">添加</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="content admin_add" style="display: none">
                    <form class="am-form">
                        <div class="am-form-group">
                            <label class="form-label" for="input-1">管理员名字</label>
                            <input type="text" id="admin_name" placeholder="Satori" />
                            <br>
                            <label class="form-label" for="input-1">密码</label>
                            <input type="password" id="admin_password" placeholder="*******" rows="3" />
                            <br>
                            <a class="am-btn am-btn-primary" onclick="add_new_admin();">添加</a>
                        </div>
                    </form>
                </div>
                <div class="content reset_password" style="display: none">
                    <form class="am-form">
                        <div class="am-form-group">
                            <label class="form-label" for="input-1">新密码</label>
                            <input type="password" id="new_password" placeholder="*******" rows="3" />
                            <br>
                            <a class="am-btn am-btn-primary" onclick="set_new_password();">修改密码</a>
                        </div>
                    </form>
                </div>
                <div class="content admin_list" style="display: none">
                    <div class="form-group">
                        <table class="am-table am-table-compact am-table-striped tpl-table-black">
                            <thead>
                                <tr>
                                    <th>用户名</th>
                                    <th>添加IP</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tbody id="table_admin_list">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="content dashboard" style="display: none">
                    <div class="am-u-sm-12 am-u-lg-4">
                        <div class="widget">
                            <img class="img-welcome" src="https://ooo.0o0.ooo/2017/09/16/59bd178828334.png">
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-lg-4">
                        <div class="widget widget-primary">
                            <div class="widget-statistic-header">域名总数</div>
                            <div class="widget-statistic-body">
                                <span class="widget-statistic-value" id="domain_count"></span>
                                <span class="widget-statistic-description">个</span>
                                <div class="widget-statistic-icon am-icon-credit-card-alt"></div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-lg-4">
                        <div class="widget widget-purple">
                            <div class="widget-statistic-header">今日总域名流量</div>
                            <div class="widget-statistic-body">
                                <span class="widget-statistic-value" id="domain_bandwidth_count"></span>
                                <span class="widget-statistic-description">次访问</span>
                                <div class="widget-statistic-icon am-icon-support"></div>
                            </div>
                        </div>
                    </div>
                    <script type="text/javascript">
                    $.ajax({
                        url: '../api/index.php/admin/get_statistical_data',
                        success: function(datas) {
                            //datas = JSON.parse(datas);
                            var dataAxis = [];
                            var data = [];
                            datas.forEach(function(value, index) {
                                dataAxis[index] = value['domain'];
                                data[index] = value['count'];
                            });
                            var yMax = datas[0]['count'] * 2;
                            console.log(yMax);
                            var dataShadow = [];

                            for (var i = 0; i < data.length; i++) {
                                dataShadow.push(yMax);
                            }

                            option = {
                                grid: {
                                    left: 30,
                                    right: 10,
                                    top: 10,
                                    bottom: 10,
                                },
                                xAxis: {
                                    data: dataAxis,
                                    axisLabel: {
                                        inside: true,
                                        textStyle: {
                                            color: '#ccc',
                                        },
                                    },
                                    axisTick: {
                                        show: false,
                                    },
                                    axisLine: {
                                        show: false,
                                    },
                                    z: 10,
                                },
                                yAxis: {
                                    axisLine: {
                                        show: false,
                                    },
                                    axisTick: {
                                        show: false,
                                    },
                                    axisLabel: {
                                        textStyle: {
                                            color: '#999',
                                        },
                                    },
                                },
                                dataZoom: [{
                                    type: 'inside',
                                }],
                                series: [{ // For shadow
                                        type: 'bar',
                                        itemStyle: {
                                            normal: {
                                                color: 'transparent',
                                            },
                                        },
                                        barGap: '-100%',
                                        barCategoryGap: '40%',
                                        data: dataShadow,
                                        animation: false,
                                    },
                                    {
                                        type: 'bar',
                                        itemStyle: {
                                            normal: {
                                                color: '#74360e',
                                                //                       color: new echarts.graphic.LinearGradient(
                                                //                          0, 0, 0, 1, [
                                                //                            {offset: 0, color: '#83bff6'},
                                                //                            {offset: 0.5, color: '#188df0'},
                                                //                            {offset: 1, color: '#188df0'},
                                                //                          ]
                                                //                      ),
                                            },
                                            emphasis: {
                                                //                      color: new echarts.graphic.LinearGradient(
                                                //                          0, 0, 0, 1, [
                                                //                            {offset: 0, color: '#2378f7'},
                                                //                            {offset: 0.7, color: '#2378f7'},
                                                //                            {offset: 1, color: '#83bff6'},
                                                //                          ]
                                                //                      ),
                                            },
                                        },
                                        data: data,
                                    },
                                ],
                            };
                            var myChart = echarts.init(document.getElementById('tables_ovo'));
                            myChart.setOption(option);
                            // Enable data zoom when user click bar.
                            var zoomSize = 6;
                            myChart.on('click', function(params) {
                                //console.log(dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)]);
                                myChart.dispatchAction({
                                    type: 'dataZoom',
                                    startValue: dataAxis[Math.max(params.dataIndex - zoomSize / 2, 0)],
                                    endValue: dataAxis[Math.min(params.dataIndex + zoomSize / 2, data.length - 1)],
                                });
                            });
                        },
                    });
                    </script>
                    <div class="am-u-sm-12">
                        <div class="widget">
                            <div class="widget-head">
                                <div class="widget-title">域名总访问量Top5</div>
                            </div>
                            <div class="widget-body" id="tables_ovo"></div>
                        </div>
                    </div>
                </div>
                <div class="content edit" style="display: none">
                    <form class="am-form tpl-form-line-form">
                        <fieldset>
                            <div class="am-form-group">
                                <h4>编辑域名：<span id="domain_names"></span></h4>
                                <label class="form-label" for="input-1">域名</label>
                                <input type="text" id="edit_domain_name" placeholder="baka.com" />
                                <br>
                                <label class="form-label">注册时间</label>
                                <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd'}">
                                    <input id="edit_domain_register_time" type="text" class="am-form-field" placeholder="">
                                    <div class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                    </div>
                                </div>
                                <br>
                                <label class="form-label">到期时间</label>
                                <div class="am-input-group am-datepicker-date" data-am-datepicker="{format: 'yyyy-mm-dd'}">
                                    <input id="edit_domain_expire_time" type="text" class="am-form-field" placeholder="">
                                    <div class="am-input-group-btn am-datepicker-add-on">
                                        <button class="am-btn am-btn-default" type="button"><span class="am-icon-calendar"></span></button>
                                    </div>
                                </div>
                                <br>
                                <label class="form-label">注册商</label>
                                <input type="text" id="edit_domain_registrar" />
                                <br>
                                <label class="form-label">权重</label>
                                <input type="text" id="edit_domain_rank" value="0" />
                                <br>
                                <label class="form-label" for="input-1">描述</label>
                                <textarea id="edit_domain_description" placeholder="_(:з」∠)=" rows="3"></textarea>
                                <br>
                                <label class="form-label">状态</label>
                                <label class="form-radio">
                                    <input class="edit_sell_type" type="radio" name="status" />
                                    <i class="form-icon"></i> 正在出售
                                </label>
                                <label class="form-radio">
                                    <input class="edit_sell_type" type="radio" name="status" />
                                    <i class="form-icon"></i> 不出售
                                </label>
                                <br>
                                <br>
                                <label class="form-label">类型</label>
                                <select id="edit_type_selector">
                                </select>
                                <br>
                                <a class="am-btn am-btn-primary" onclick="save_edit_domain();">保存编辑</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="content prize_list" style="display: none">
                    <div class="form-group">
                        <table class="am-table am-table-compact am-table-striped tpl-table-black">
                            <thead>
                                <tr>
                                    <th>域名</th>
                                    <th>客户</th>
                                    <th>邮箱</th>
                                    <th>价格</th>
                                    <th>IP</th>
                                    <th>提交时间</th>
                                </tr>
                            </thead>
                            <tbody id="table_prize_list">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="content settings" style="display: none">
                    <form class="am-form tpl-form-line-form">
                        <fieldset>
                            <div class="am-form-group">
                                <h4>网站设置</h4>
                                <label class="form-label">Contact页面邮箱</label>
                                <input type="text" id="email" placeholder="admin@baka.com" />
                                <br>
                                <label class="form-label">网站标题</label>
                                <input type="text" id="site-title" placeholder="Premium Domain Names" />
                                <br>
                                <label class="form-label">网站名字</label>
                                <input type="text" id="site-name" placeholder="Index.do" />
                                <br>
                                <label class="form-label">网站底部链接</label>
                                <input type="text" id="site-footer-link" placeholder="https://index.do" />
                                <br>
                                <a class="am-btn am-btn-primary" onclick="save_settings();">保存编辑</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="content smtp" style="display: none">
                    <form class="am-form tpl-form-line-form">
                        <fieldset>
                            <div class="am-form-group">
                                <h4>SMTP 服务器设置</h4>
                                <label class="form-label">服务器地址</label>
                                <input type="text" id="smtp_server_address" placeholder="smtp.163.com" />
                                <br>
                                <label class="form-label">账号</label>
                                <input type="text" id="smtp_server_account" placeholder="undefined@163.com" />
                                <br>
                                <label class="form-label">密码</label>
                                <input type="password" id="smtp_server_password" placeholder="******" />
                                <br>
                                <label class="form-label">报价接收邮箱</label>
                                <input type="text" id="recv_prize_mail" placeholder="xxx@xxx.com">
                                <br>
                                <a class="am-btn am-btn-primary" onclick="save_smtp_settings();">保存设置</a>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="content classification" style="display: none">
                    <form class="am-form tpl-form-line-form">
                        <fieldset>
                            <div class="am-form-group">
                                <h4>增加分类</h4>
                                <label class="form-label">分类名字</label>
                                <input type="text" id="classification_name" placeholder="OwO" />
                                <br>
                                 <label class="form-label">分类权重</label>
                                <input type="text" id="classification_rank" placeholder="0" value="0" />
                                <br>
                                <a class="am-btn am-btn-primary" onclick="save_classification_settings();">增加设置</a>
                            </div>
                        </fieldset>
                    </form>
                    <hr>
                    <table>
                        <tbody>
                            <table class="am-table">
                                <thead>
                                    <tr>
                                        <th width="90%">分类名</th>
                                        <th width="10%">管理</th>
                                    </tr>
                                </thead>
                                <tbody id="table_type_list">
                                </tbody>
                            </table>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
        <script>
        function save_classification_settings() {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/add_classification',
                data: {
                    name: $('#classification_name').val(),
                    rank: $('#classification_rank').val()
                },
                async: true,
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }
        Q.reg('classification', function() {
            $('#contents').html($('.content.classification').html());
            $.getJSON('../api/index.php/domain/type_list?rand=' + Math.random(), function(data) {
                classification_data = data;
                data.forEach((value, index) => {
                    $('#table_type_list').append("<tr><td>" + value + "</td><td><div class='tpl-table-operation'><a href='javascript:;' onclick='delete_classification(" + index + ")' class=\'tpl-table-operation-del\'> <i class=\'am-icon-trash\'></i> 删除</a></div></td></tr>")
                })
            })
        })

        function delete_classification(id) {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/edit_classification',
                data: {
                    type: classification_data[id]
                },
                async: true,
                success: function(data) {
                    classification_data = data;
                    $('#table_type_list').html('');
                    data.forEach((value, index) => {
                        $('#table_type_list').append("<tr><td>" + value + "</td><td><div class='tpl-table-operation'><a href='javascript:;' onclick='delete_classification(" + index + ")' class=\'tpl-table-operation-del\'> <i class=\'am-icon-trash\'></i> 删除</a></div></td></tr>")
                    })
                },
            });
        }
        Q.reg('smtp', function() {
            $('#contents').html($('.content.smtp').html());
            $.getJSON('../api/index.php/smtp_server/get', function(data) {
                if (data['status'] == 200) {
                    $('#smtp_server_address').val(data['result']['server']);
                    $('#smtp_server_account').val(data['result']['account']);
                    $('#smtp_server_password').val(data['result']['password']);
                    $('#recv_prize_mail').val(data['result']['recv_prize_mail'])
                }
            })
        })

        function save_smtp_settings() {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/smtp_server/set',
                data: {
                    server: $('#smtp_server_address').val(),
                    account: $('#smtp_server_account').val(),
                    password: $('#smtp_server_password').val(),
                    recv_prize_mail: $('#recv_prize_mail').val()
                },
                async: true,
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }

        function save_settings() {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/settings',
                data: {
                    contact_email: $('#email').val(),
                    site_name: $('#site-name').val(),
                    site_title: $('#site-title').val(),
                    site_footer_link: $('#site-footer-link').val()
                },
                async: true,
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }

        function save_edit_domain() {
            if ($('.edit_sell_type')[0].checked) {
                var sell_type = 1;
            } else {
                var sell_type = 0;
            }
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/edit_domain',
                data: {
                    domain: $('#edit_domain_name').val(),
                    description: $('#edit_domain_description').val(),
                    register_time: $('#edit_domain_register_time').val(),
                    expire_time: $('#edit_domain_expire_time').val(),
                    registrar_name: $('#edit_domain_registrar').val(),
                    rank: $('#edit_domain_rank').val(),
                    type: $('#edit_type_selector').val(),
                    status: sell_type,
                },
                async: true,
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }
        Q.reg('settings', function() {
            $('#contents').html($('.content.settings').html());
            $.getJSON('settings.json' + "?rand=" + Math.random(), function(data) {
                $('#email').val(data['contact-email']);
                $('#site-name').val(data['site-name']);
                $('#site-title').val(data['site-title']);
                $('#site-footer-link').val(data['site-footer-link']);
            });

        })
        Q.reg('edit', function(cstr, id) {
            $('#contents').html($('.content.edit').html());
            $.ajax({
                url: '../api/index.php/admin/get_domain?id=' + id,
                async: true,
                success: function(data) {
                    $('#edit_domain_name').val(data['domain']);
                    $('#domain_names').text(data['domain']);
                    $('#edit_domain_register_time').datepicker('setValue', data['register_time']);
                    $('#edit_domain_expire_time').datepicker('setValue', data['expire_time']);
                    $('#edit_domain_registrar').val(data['registrar_name']);
                    $('#edit_domain_rank').val(data['rank']);
                    $('#edit_domain_description').val(data['description']);
                    if (data['status'] == 0) {
                        $('.edit_sell_type')[1].checked = true;
                    } else {
                        $('.edit_sell_type')[0].checked = true;
                    }
                    $('#edit_type_selector').find('option').each(function() {
                        if ($(this).text() == data['type']) {
                            $(this).attr('selected', 'selected');
                        }
                    })

                },
            });
            console.log(id);
        });
        // $(window).resize(function(){
        //     $('footer').css({ marginTop: window.innerHeight - $('footer').offset().top - 55 + "px" });
        // })
        // $('footer').css({ marginTop: window.innerHeight - $('footer').offset().top - 55 + "px" })
        $.getJSON('../api/index.php/domain/type_list?rand=' + Math.random(), function(result) {
            result.forEach(function(value, index) {
                $('#type_selector').
                append('<option value="' + value + '">' + value + '</option>');
                $('#edit_type_selector').append('<option value="' + value + '">' + value + '</option>');
            });
        });
        Q.reg('dashboard', function() {
            $('#contents').html($('.content.dashboard').html());
            $.ajax({
                url: '../api/index.php/domain/list',
                async: true,
                success: function(data) {
                    $('#domain_count').text(data.length);
                },
            });
            $.ajax({
                url: '../api/index.php/admin/get_statistical_data?type=bandwidth',
                async: true,
                success: function(data) {
                    $('#domain_bandwidth_count').text(data['count']);
                },
            });
        });
        $(document).ready(function() {
            $('#sidebar_listener').find('a').removeClass('active');

            $('#sidebar_listener').find('a').each(function(value) {
                if (window.location.hash.split('#!')[1] == $('#sidebar_listener').find('a')[value].className) {
                    $('#sidebar_listener').find('a')[value].className = window.location.hash.split('#!')[1] +
                        ' ' + 'active';
                }
            });
        });
        window.addEventListener('hashchange', function(event) {
            $('#sidebar_listener').find('a').removeClass('active');

            $('#sidebar_listener').find('a').each(function(value) {
                if (window.location.hash.split('#!')[1] == $('#sidebar_listener').find('a')[value].className) {
                    $('#sidebar_listener').find('a')[value].className = window.location.hash.split('#!')[1] +
                        ' ' + 'active';
                }
            });
        });
        Q.reg('reset_password', function() {
            $('#contents').html($('.content.reset_password').html());

        });
        Q.reg('prize_list', function() {
            $('#contents').html($('.content.prize_list').html());
            $.ajax({
                url: '../api/index.php/admin/get_prize_list',
                async: true,
                success: function(data) {
                    //data = JSON.parse(data);
                    data.forEach(function(value, index) {
                        $('#table_prize_list').
                        prepend('<tr><td>' + value['domain'] + '</td><td>' + value['username'] + '</td><td>' +
                            value['email'] + '</td><td>' + value['prize'] + '</td><td>' + value['ip'] +
                            '</td><td>' + value['time'] + '</td></tr>');
                    });
                    $('#contents > div > table').stacktable()
                },
            });
        });
        Q.reg('admin_list', function() {
            $('#contents').html($('.content.admin_list').html());
            $.ajax({
                url: '../api/index.php/admin/admin_list',
                async: true,
                success: function(data) {
                    //data = JSON.parse(data);
                    data.forEach(function(value, index) {
                        $('#table_admin_list').
                        append('<tr class=\'admin_list_' + value['id'] + '\'><td>' + value['username'] +
                            '</td><td>' +
                            value['regip'] +
                            '</td><td><div class=\'tpl-table-operation\'><a href=\'javascript:;\' onclick=\'delete_admin(' +
                            value['id'] +
                            ')\' class=\'tpl-table-operation-del\'> <i class=\'am-icon-trash\'></i> 删除</a></div></td></tr>');
                    });
                },
            });
        });

        function set_new_password() {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/reset_password',
                async: true,
                data: {
                    password: $('#new_password').val(),
                },
                success: function(data) {
                    if (data['status'] == 200) {
                        alert('[' + data['status'] + ']' + data['message']);
                    } else {
                        alert('[' + data['status'] + ']' + data['message']);
                    }
                },
            });
        }

        function delete_admin(id) {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/delete_admin',
                async: true,
                data: {
                    id: id,
                },
                success: function(data) {
                    var json = data;
                    if (json['status'] == 200) {
                        alert('[' + json['status'] + ']' + json['message']);
                        $('.admin_list_' + id).hide();
                    } else {
                        alert('[' + json['status'] + ']' + json['message']);
                    }
                },
            });
        }

        function add_new_admin() {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/add_admin',
                async: true,
                data: {
                    username: $('#admin_name').val(),
                    password: $('#admin_password').val(),
                },
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }

        Q.reg('admin_add', function() {
            $('#contents').html($('.content.admin_add').html());
        });
        Q.reg('domain_list', function(w, type) {
            if (!type) {
                type = ''
            }
            $('#contents').html($('.content.domain_list').html());
            $.getJSON('../api/index.php/domain/type_list?rand=' + Math.random(), function(data) {
                data.forEach((value, index) => {
                    $('#domain_type_list').append('<a class="am-btn am-btn-secondary" href="#!domain_list/type/' + value + '">' + value + '</a> ');
                });
            })

            $.ajax({
                url: '../api/index.php/domain/list?type=' + type,
                async: true,
                success: function(data) {
                    //data = JSON.parse(data);
                    data.forEach(function(value, index) {
                        $('#table_domain_list').append('<tr class=\'domain_list_' + value['id'] + '\'><td>' + value['domain'] +
                            '</td><td>' +
                            value['description'] + '</td><td align="center">' + (value['status'] == '1' ? '出售中' : '未出售') +
                            '</td><td align="center">' + (value['type']) + '</td><td align="center">' + value['view_count'] + '</td><td>' +
                            value['register_time'] + '</td><td align="center">' + value['expire_time'] +
                            '</td><td style=\'color:red\'>' + value['remaining'] + '</td><td>' +
                            value['registrar_name'] + '</td>' +
                            '<td>' + value['rank'] + '</td>' +
                            '<td><div class=\'tpl-table-operation\'><a href=\'#!edit/id/' +
                            value['id']

                            +
                            '\' class=\'tpl-table-operation-edit\'> <i class=\'am-icon-pencil\'></i> 编辑</a> <a href=\'javascript:;\' onclick=\'delete_domain(' +
                            value['id'] +
                            ')\' class=\'tpl-table-operation-del\'> <i class=\'am-icon-trash\'></i> 删除</a></div></td></tr>');
                    });
                    $('#contents > table').stacktable();
                },
            });
        });
        // $('.contents.domain_list').ready(function(){
        //   $('#contents > table').stacktable()
        // })
        Q.reg('add_domain', function() {
            $('#contents').html($('.content.add_domain').html());
        });

        function delete_domain(id) {
            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/delete_domain',
                data: {
                    id: id,
                },
                async: true,
                success: function(data) {
                    if (data['status'] == 200) {
                        alert('[' + data['status'] + ']' + data['message']);
                        $('.domain_list_' + id).hide();
                    } else {
                        alert('[' + data['status'] + ']' + data['message']);
                    }

                },
            });
        }

        function post_new_domain() {
            var domain = document.querySelector('#domain_name').value;
            var description = document.querySelector('#domain_description').value;
            if ($('.sell_type')[0].checked) {
                var sell_type = 1;
            } else {
                var sell_type = 0;
            }

            $.ajax({
                method: 'POST',
                url: '../api/index.php/admin/add_domain',
                data: {
                    domain: domain,
                    description: description,
                    register_time: $('#domain_register_time').val(),
                    expire_time: $('#domain_expire_time').val(),
                    registrar_name: $('#domain_registrar').val(),
                    type: $('#type_selector').val(),
                    rank: $('#domain_rank').val(),
                    status: sell_type,
                },
                async: true,
                success: function(data) {
                    alert('[' + data['status'] + ']' + data['message']);
                },
            });
        }

        Q.init({
            index: 'dashboard',
        });
        </script>
        <script src="../assets/js/amazeui.min.js"></script>
        <script src="../assets/js/amazeui.datatables.min.js"></script>
        <script src="../assets/js/dataTables.responsive.min.js"></script>
        <script src="../assets/js/app.js"></script>
    </body>

    </html>