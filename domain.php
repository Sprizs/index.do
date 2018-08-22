<html>

<head>
    <title></title>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.css.network/css?family=Open+Sans:400,300,700,600,800" rel="stylesheet">
    <link href="https://cdn.css.net/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://cdn.css.net/libs/twitter-bootstrap/3.0.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="js/layer/layer.js"></script>
</head>

<body>
    <div class="theme-statefarm-v2 theme-white theme-imagery">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 ov-hidden text-center lcontact-heading">
                    <div class="initial-form">
                        <h1 class="domain_name"></h1>
                        <p class="subtitle">For Sale</p>
                    </div>
                    <hr class="top">
                </div>
                <div class="col-xs-12 lcontact-content">
                    <div class="initial-form">
                        <div class="clearfix">
                            <h2 class="col-xs-12 col-sm-8 text-center center-block no-pull">Complete this form to get a price quote on <strong class="domain_name"></strong>:</h2>
                        </div>
                        <div class="row">
                            <form id="domainquery" method="post">
                                <fieldset>
                                    <input type="hidden" id="domains" name="domain">
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="well">
                                            <div class="form-group form-group-full_name">
                                                <label class="control-label text-center fz-md" for="full_name">Full Name</label>
                                                <input class="form-control" type="text" name="full_name" id="full_name">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="well">
                                            <div class="form-group form-group-email">
                                                <label class="control-label text-center fz-md" for="email">Email Address</label>
                                                <input class="form-control" type="text" name="email" id="email">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4">
                                        <div class="well no-arrow">
                                            <div class="form-group form-group-price">
                                                <label class="control-label text-center fz-md" for="price">Expected Price (US Dollar)</label>
                                                <input class="form-control" type="number" name="price" id="prize">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12">
                                        <div class="row">
                                            <div class="col-xs-12 form-group-form">
                                                <div class="alert alert-warning text-center help-block"></div>
                                            </div>
                                            <div class="col-xs-12 col-xs-offset-0 col-sm-4 col-sm-offset-4" id="sub-a">
                                                <button class="btn btn-primary btn-lg btn-block" id="submitquery"><i class="fa fa-circle-o-notch fa-spin hide"></i>Get A Price Quote</button>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </form>
                        </div>
                    </div>
                    <div style="display:none;" id="showmessage" class="ov-hidden text-center ">
                        <p class="subtitle"></p>
                    </div>
                </div>
                <div class="col-xs-12">
                    <hr class="bottom">
                    <p class="copyright text-center fz-sm" id="copyrights">
                    </p>
                </div>
                <script type="text/javascript">
                $.getJSON('config.json', function(data) {
                    $('#copyrights').html(data['copyright'])
                })
                </script>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    //var domain_name = location.hash.split('#')[1];
    var domain_name = "<?php echo htmlspecialchars($_GET['name']); ?>"
    if (!domain_name) {
        window.location.href = '/';
    }
    $('#domains').val(domain_name);
    $.ajax({
        method: 'GET',
        url: "api/index.php/domain/info?name=" + domain_name,
        async: false,
        success: function(data) {
            if (data['status'] == '404') {
                window.location.href = '/';
            }
            if (data['result']['status'] == '0') {
                layer.msg('Not Sell');
            }
        }
    });
    document.title = 'Sales Inquiry ' + domain_name + ' | Cat Networks, Inc.'
    $('.domain_name').text(domain_name);
    $('#submitquery').on('click', function() {
        var full_name = $('#full_name').val();
        var email = $('#email').val();
        var price = $('#prize').val();

        var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

        if (!full_name) {
            layer.tips('Please enter your name.', $('#full_name'), { tips: [3, '#FF5722'] });
            $('#full_name').focus();
            return false;
        } else if (!email) {
            layer.tips('Please enter your email.', $('#email'), { tips: [3, '#FF5722'] });
            $('#email').focus();
            return false;
        } else if (!re.test(email)) {
            layer.tips('Please confirm your email.', $('#email'), { tips: [3, '#FF5722'] });
            $('#email').focus();
            return false;
        } else if (!price) {
            layer.tips('Please enter your expected price.', $('#price'), { tips: [3, '#FF5722'] });
            $('#price').focus();
            return false;
        } else {
            start_alert();
            return false;
        }
    })

    function start_alert() {
        layer.open({
            title: 'Validation information',
            shade: 0.3,
            type: 2,
            area: ['355px', '250px'],
            fix: false,
            maxmin: false,
            content: 'verify.php?' + $('#domainquery').serialize()
        });
    }
    </script>
</body>

</html>