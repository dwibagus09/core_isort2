<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="isort.id">
    <meta name="author" content="iSort">
    <link rel="icon" href="/images/isort_new_logo.png">

    <title>iSort CMMS</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/login_styles.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="/js/html5shiv.min.js"></script>
      <script src="/js/respond.min.js"></script>
    <![endif]-->
	
	<style>
	.login-banner {
		width:60%; 
		float:left; 
		background:url('/images/login_img.jpg') no-repeat left bottom; 
		height: 100vh; 
		background-size: 150%;
	}
	
	.login-container {
		width:40%; 
		float:right; 
		border: none; 
		border-radius: 0px; 
		box-shadow: none; 
		background: none; background:url('/images/pattern_bg.jpg') no-repeat 10% 20%; 
		height: 100vh; 
		background-size: cover; 
		position: relative;
	}
	
	@media (max-width: 1025px) {
		.login-banner {
			background-size: cover;
		}
	}
	
	@media (max-width: 800px) {
		.login-banner { display: none; }
		.login-container { float: none; width: 100%; }
	}

	
	</style>

  </head>

  <body style="background:none; padding-top: 0px;">

	<div class="login-banner">
			
	</div>
    <div class="login-container">
		<div style="position: absolute; top: 45%; left: 50%;  transform: translate(-50%, -50%); width: 400px;">
			  <form class="form-signin" action="/default/user/login" method="POST">
				<div id="logo"><img src="/images/isort_new_logo.png" /><br/><span style="color: #9e824b;">I</span>SORT <span style="color: #9e824b;">H</span>OTEL</div>
				<div id="login-form">
					<input type="hidden" id="ruri" name="ruri" class="form-control" value="<?php echo $this->ruri; ?>">  
					<label for="inputEmail" class="sr-only">Username</label>
					<input type="username" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
					<label for="password" class="sr-only">Password</label>
					<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
					<div class="checkbox">
					  <label>
						<input type="checkbox" value="1" name="rememberme"> Remember me
					  </label>
					</div>
					<div class="signin-btn">
						<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
					</div>
				</div>
			  </form>
		  <?php /*<div style="width:350px; margin:0 auto; padding-top:50px;">
      <img width="350" src="/images/under_maintenance.png">
    </div> */ ?>
    
			<div class="copyright">
				Copyright &copy;<?php echo date("Y"); ?> <a href="http://isort.id">isort</a>. All Rights Reserved.
			</div>
		</div>
    </div> <!-- /container -->
    
    

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
