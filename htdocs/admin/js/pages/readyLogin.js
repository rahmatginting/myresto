/*
 *  Document   : readyLogin.js
 *  Author     : pixelcave
 *  Description: Custom javascript code used in Login page
 */

var ReadyLogin = function() {

    return {
        init: function() {
            /*
             *  Jquery Validation, Check out more examples and documentation at https://github.com/jzaefferer/jquery-validation
             */

			$('#form-login').on('submit', function(donard){
				donard.preventDefault();
				var a = $(this).find('input[name="login-email"]').val();
				var b = $(this).find('input[name="login-password"]').val();

				if (a === '' && b ===''){
					$('#alert-msg1').html('<div class="alert alert-danger">All fields are required!</div>');
				}else{
					$.ajax({
						type: 'POST',
						url: 'new_login.php',
						data: {
							username: a,
							password: b
						},
						beforeSend:  function(){
							$('#alert-msg1').html('');
						}
					})
					.done(function(donard){
						if (donard == 0){
							$('#alert-msg1').html('<div class="alert alert-danger">Incorrect username or password!</div>');
							return;
						}else{
							$("#btn-login").html('<img src="loading.gif" /> &nbsp; Signing In ...');
							setTimeout(' window.location.href = "home.php"; ',2000);
							return;
						}
					});
				}
			});
			
			
			$('#kasir-login').on('submit', function(donard){
				donard.preventDefault();

				var a = $(this).find('input[name="kasir-email"]').val();
				var b = $(this).find('input[name="kasir-password"]').val();

				if (a === '' && b ===''){
					$('#alert-msg2').html('<div class="alert alert-danger">All fields are required!</div>');
				}else{
					$.ajax({
						type: 'POST',
						url: 'cashier/new_login.php',
						data: {
							username: a,
							password: b
						},
						beforeSend:  function(){
							$('#alert-msg2').html('');
						}
					})
					.done(function(donard){
						if (donard == 0){
							$('#alert-msg2').html('<div class="alert alert-danger">Incorrect username or password!</div>');
						}else{
							$("#btn").html('<img src="loading.gif" /> &nbsp; Signing In ...');
							//setTimeout(' window.location.href = "cashier/sales.php?id=cash&invoice=<?php echo $finalcode ?>"; ',2000);
							setTimeout(' window.location.href = "cashier/sales.php?id=cash&invoice=' + donard + '"',2000);
						}
					});
				}
			});
			
			
			
			
			
			
        }
    };
}();