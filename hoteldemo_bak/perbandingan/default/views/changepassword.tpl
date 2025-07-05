<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <div class="x_title">
			<h2>Change Password</h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">

			<form class="form-horizontal form-label-left" action="/default/user/updatepassword" method="POST">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">Old Password</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="password" name="old_passwd" class="form-control col-md-7 col-xs-12" required>
					</div>
				</div>
				
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">New Password</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="password" name="new_passwd" class="form-control col-md-7 col-xs-12" required>
					</div>
				</div>
				
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">Confirm New Password</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input type="password" name="confirm_passwd" class="form-control col-md-7 col-xs-12" required>
					</div>
				</div>
		
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:200px;">Simpan</button>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->