<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pakuwon Smart Reporting Tool</title>
	
	<link rel="icon" href="/images/favicon.ico" type="image/ico" >

    <!-- Bootstrap -->
    <link href="/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="/nprogress/nprogress.css" rel="stylesheet">
    <!-- Animate.css -->
    <link href="/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
          <section class="login_content">
            <form action="/default/user/login" method="POST">
				<img src="/images/pakuwon_group.png" width="100%" />
              <h1>Smart Reporting Tool</h1>
			  <?php if(!empty($this->errormsg)) { echo '<span style="font-size:12px; color:red">'.$this->errormsg.'</span>'; } ?>
              <div>
                <input type="text" class="form-control" placeholder="Username" required="" name="username" />
              </div>
              <div>
                <input type="password" class="form-control" placeholder="Password" required="" name="password" />
              </div>
              <div>
                <input class="btn btn-default submit" type="submit" value="Log in" />
              </div>

              <div class="clearfix"></div>

              <div class="separator">

                <div class="clearfix"></div>
                <div>
                  <p>&copy;<?php echo date("Y"); ?> All Rights Reserved.<br/>Powered by <a href="http://emma.web.id">Emma</a></p>
                </div>
              </div>
            </form>
          </section>
        </div>
      </div>
    </div>
  </body>
</html>
