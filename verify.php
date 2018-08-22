<?php
?>
<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css" />
<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<br>
<div style="float: left;left: 15%;position: relative;">
<h5>Please Input The Checkcode</h5>
	<img src="api/checkcode.php" onclick="this.src=this.src" style="margin-left: 10px;">
	<br>
    <input class="form-input" type="text" id="captcha" />
    <br>
    <button class="btn btn-primary" style="float: right" id="confirm">Success</button>
</div>
<script type="text/javascript">
	$('#confirm').on('click',function(){
		$.ajax({
		method:'POST',
        url: "api/index.php/domain/prize",
        async: true,
        data:{
        	captcha:$('#captcha').val(),
        	domain: '<?php echo $_GET['domain']; ?>',
        	full_name: '<?php echo $_GET['full_name']; ?>',
        	email: '<?php echo $_GET['email']; ?>',
        	price: <?php echo $_GET['price']; ?>
        },
        success: function(data) {
            if (data['status']==200) {
            	alert("["+data['status']+"]"+data['message']);
            }else{
            	alert("["+data['status']+"]"+data['message']);
            }
        }
   	 });
	})
</script>