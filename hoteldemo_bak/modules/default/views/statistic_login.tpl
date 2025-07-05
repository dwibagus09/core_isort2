<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="issue-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="login-stat col-md-5 col-sm-6 col-xs-12" style="margin:20px auto; float:none;">
			<form class="form-signin" action="/default/statistic/dologin" method="POST">
			<div id="logo" style="width:50px; height:50px;">&nbsp;</div>
			<h2 class="form-signin-heading" style="text-align:center;">Smart Operational Reporting Tool</h2>
			<h5 style="text-align:center;">Please enter password to view the Statistic page</h5>
			<div id="login-form" style="text-align:center;">
				<input type="hidden" id="s" name="s" class="form-control" value="<?php echo $this->s; ?>">
				<input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
				<button class="btn btn-primary" type="submit" style="width: auto; font-size: 12px; padding: 4px 20px;">Submit</button>
			</div>
		</form>
		</div>	
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->