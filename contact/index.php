<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Contact / </title>
    <meta content="Buy premium domain names on index.do!" name="description">
    <meta content="width=device-width, initial-scale=1" name="viewport">
    <link href="https://cdnjs.cat.net/ajax/libs/bootswatch/3.3.7/cyborg/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cat.net/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="https://fonts.cat.net/css?family=Saira+Semi+Condensed" rel="stylesheet">
    <style type="text/css">
    body {
        font-family: 'Saira Semi Condensed', sans-serif;
    }

    table a:not(.btn),
    .table a:not(.btn) {
        color: #FF0066;
        text-decoration: none;
    }

    table a:hover,
    .table a:hover {
        color: #FFFFFF;
        text-decoration: none;
        background: #CC3366;
    }

    a {
        color: #FF0066;
    }

    a:hover {
        color: #FFFFFF;
        text-decoration: none;
        background: #CC3366;
    }
    </style>
</head>

<body>
    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button aria-controls="navbar" aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar" data-toggle="collapse" type="button"><span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button> <a id="location_href" class="navbar-brand"></a>
            </div>
            <div class="navbar-collapse collapse" id="navbar">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="/index.html">Home</a>
                    </li>
                    <li>
                        <a href="/contact">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="page-header">
            <h1>Contact Methods</h1>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">
                Sales Representative
            </div>
            <div class="panel-body">
                <p>Please send an email to <a id="send_email"></a></p>
            </div>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p class="text-muted">Copyright &#9400; 2017 <a href="https://index.do/" id="site_name_footer">Index do</a>. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdnjs.cat.net/ajax/libs/jquery/3.2.1/jquery.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    $.getJSON('../admin/settings.json', function(data) {
        document.title = 'Contact / ' + data['site-title'];
        $('#location_href').html(data['site-name']);
        $('#site_name_footer').html(data['site-name']);
        $('#send_email')[0].href = "mailto:" + data['contact-email'];
        $('#send_email').html(data['contact-email'])

        $('#site_name_footer')[0].href = data['site-footer-link'];
    })
    </script>
    <script src="https://cdnjs.cat.net/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js" type="text/javascript"></script>
</body>

</html>