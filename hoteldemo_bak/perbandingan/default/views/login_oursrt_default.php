<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="Pakuwon Smart Reporting Tool">
    <meta name="author" content="Emma">
    <link rel="icon" href="/images/favicon.ico">

    <title>Our Smart Reporting Tool</title>

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
  </head>

  <body>

    <div class="container">

      <form class="form-signin" action="/default/user/login" method="POST">
        <div id="logo"><img src="/images/oursrtlogo.png" width="50" /></div><h2 class="form-signin-heading">Our SRT</h2>
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
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
		</div>
      </form>

    <?php /*<div style="width:350px; margin:0 auto; padding-top:50px;">
      <img width="350" src="/images/under_maintenance.png">
    </div> */ ?>

    </div> <!-- /container -->


    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
