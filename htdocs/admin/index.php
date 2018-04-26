<?php 
session_start();
session_destroy();
?>
<?php include 'inc/config.php'; ?>
<?php include 'inc/template_start.php'; ?>



<body  onLoad="document.getElementById('login-email').focus();">

<!-- Login Container -->
<div id="login-container">
    <!-- Login Header -->
    <h1 class="h2 text-light text-center push-top-bottom animation-pullDown">
        <i class="gi gi-keys text-light-op"></i> <strong>Chatbot Application</strong>
    </h1>
    <!-- END Login Header -->

    <!-- Login Block -->
    <div class="block animation-fadeInQuick">
        <!-- Login Title -->
        <div class="block-title">
            <div class="block-options pull-right">
                <a href="reminder-password.php" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Lupa Password?"><i class="fa fa-exclamation-circle"></i></a>
                <a href="../registration.php" class="btn btn-effect-ripple btn-primary" data-toggle="tooltip" data-placement="left" title="Pendaftaran"><i class="fa fa-plus"></i></a>
            </div>
            <h2>Silahkan Login</h2>        
        </div>
        <!-- END Login Title -->

        <!-- Login Form -->
		<ul class="nav nav-pills nav-justified">
			<li class="active"><a data-toggle="pill" href="#admin">Admin</a></li>
			<li><a data-toggle="pill" href="#kasir">Merchant</a></li>
		</ul>
		
		
        <!-- Login Form -->
		<div class="tab-content">
			<!-- Department -->
			<div id="admin" class="tab-pane fade in active">
				<br />
				<form id="form-login" method="post" class="form-horizontal">
					<div class="form-group">
						<label for="login-email" class="col-xs-12">Nama User</label>
						<div class="col-xs-12">
							<input type="text" id="login-email" name="login-email" class="form-control" placeholder="Nama User..">
						</div>
					</div>
					<div class="form-group">
						<label for="login-password" class="col-xs-12">Password</label>
						<div class="col-xs-12">
							<input type="password" id="login-password" name="login-password" class="form-control" placeholder="Your password..">
						</div>
					</div>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button class="btn btn-block btn-success" id = "btn-login" name = "btn-login">Log in</button>
						</div>
					</div>
					<div class="form-group" id="alert-msg1">
					</div>
				</form>
			</div>

			<!-- Kasir -->
			<div id="kasir" class="tab-pane fade">
				<br />
				<form id="kasir-login" method="post" class="form-horizontal">
					<div class="form-group">
						<label for="kasir-email" class="col-xs-12">Email Admin</label>
						<div class="col-xs-12">
							<input type="text" id="kasir-email" name="kasir-email" class="form-control" placeholder="Email Admin..">
						</div>
					</div>
					<div class="form-group">
						<label for="kasir-password" class="col-xs-12">Pasword Admin</label>
						<div class="col-xs-12">
							<input type="password" id="kasir-password" name="kasir-password" class="form-control" placeholder="Password Admin..">
						</div>
					</div>
					<div class="form-group form-actions">
						<div class="col-xs-12 text-right">
							<button class="btn btn-block btn-success" id = "btn-kasir" name = "btn-kasir">Log in</button>
						</div>
					</div>
					<div class="form-group" id="alert-msg2">
					</div>
					
				</form>
			</div>
    <!-- Footer -->
    <footer class="text-muted text-center animation-pullUp">
        <small><span id="year-copy"></span> &copy; <a href="http://www.zeus=ibe.com/" target="_blank"><?php echo $template['name'] . ' ' . $template['version']; ?></a></small>
    </footer>
    <!-- END Footer -->

			</div>
        <!-- END Login Form -->

    </div>
    <!-- END Login Block -->

</div>
<!-- END Login Container -->
</body>
<?php include 'inc/template_scripts.php'; ?>

	
<!-- Load and execute javascript code used only in this page -->
<script src="js/pages/readyLogin.js"></script>
<script>$(function(){ ReadyLogin.init(); });</script>

<?php include 'inc/template_end.php'; ?>