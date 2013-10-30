<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $route['desc']; ?> | Login</title>
        <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">

		<link rel="stylesheet" href="<?php echo ABSURL; ?>static/css/src/flatly.min.css">
        <link href="<?php echo ABSURL; ?>static/css/style.css" rel="stylesheet">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <style>
        #errorMessage{
            min-height: 20px;
            font-size: 11px;
        }
        </style>
    </head>
    <body class="login-page">       
        <div class="login-panel">
            <div class="row-fluid panel">
                <div class="redworld-icon span12">
                    <h4><?php echo $route['desc']; ?></h4>
                    <div id="errorMessage"></div>
                </div>
            </div>
            <div class="row-fluid panel">
                <input type="text" maxlength="10" class="input-block-level" id="userinput" autocomplete="off" placeholder="username" autofocus="on" style="text-align:center;" />
            </div>
            <div class="row-fluid panel authcode">
                <input type="password" class="input-block-level" id="authinput" placeholder="password" autocomplete="off" style="text-align:center;" />
            </div>
        </div>
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
		<script src="<?php echo ABSURL; ?>static/js/bootstrap-tab.js"></script>
		<script src="<?php echo ABSURL; ?>static/js/bootstrap-dropdown.js"></script>
		<script src="<?php echo ABSURL; ?>static/js/bootstrap-typeahead.js"></script>

		<script src="<?php echo ABSURL; ?>static/js/baldrick.js"></script>
		<script src="<?php echo ABSURL; ?>static/js/main.js"></script>

        <script>
            
            var input = jQuery('#userinput');
            var auth = jQuery('#authinput');
            var mins;
            var secs;
            var rwid;
            
            jQuery(document).ready(function() {
                
              
                jQuery('.login-panel').fadeIn(1000);
               
                
                input.keypress(function(e){
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if(code == 13) {                
                        auth.focus();
                    }
                });
        

                auth.keypress(function(e){
                    var code = (e.keyCode ? e.keyCode : e.which);
                    if(code == 13) {                
                        input.blur();
                        jQuery('#errorMessage').empty();
                        jQuery.ajax({
                            type: 'POST',
                            dataType: 'json',
                            url: '<?php echo ABSURL;?>admin/login/',
                            data: {
                                user: input.val(),
                                pass: auth.val(),
                                process: 'auth-login'
                            },
                            error: function(response, v) {
                                console.log('error:');
                                console.log(response);
                                console.log(v);
                            },
                            success: function(response, v) {
                            	console.log(response);
                                if(response.data){
                                    var date = new Date();
                                    date.setTime(date.getTime()+(24*60*60*1000));                                
                                    document.cookie = "auth-node="+response.data.authcode+"; expires="+date.toGMTString()+"; path=/";
                                    document.cookie = response.data.authcode+"="+response.data.usernode+"; expires="+date.toGMTString()+"; path=/";
                                    jQuery('.login-panel').fadeOut(500, function(){
                                       document.location = "./";
                                    });                                
                                }
                                if(response.error){
                                    jQuery('#errorMessage').html(response.error);
                                }
                            }
                        });
                    }
                });
            })


            function countdown() {
                setTimeout('Decrement()', 1000);
            }
            function Decrement() {
                timer = jQuery("#timer");
                min = Math.floor(secs/60);
                sec = secs - Math.round(min * 60);
                if(sec < 10){
                    sec = '0'+sec;
                }
                timer.html(min+':'+sec);
                secs--;
                if(secs >= 0){
                    setTimeout('Decrement()', 1000);
                }else{
                    rwid = 0;
                    input.removeClass('success');
                    input.val('');
                    input.removeAttr('disabled');
                    auth.val('');
                    auth.slideUp(500);                    
                    input.focus();
                }
            }
        </script>
    </body>
</html>
