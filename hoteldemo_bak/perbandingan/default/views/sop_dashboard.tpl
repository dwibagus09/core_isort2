
			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  	<div class="dashboard">
						<h1 class="page-title">SOP &amp; IK</h1>
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="/default/sop/security#anc">
							<div class="submenu-icon">
								<i class="fas fa-book" style="font-size:70px; width:auto;"></i><div class="submenu-icon-title">Security</div>
							</div>
							</a>
						</div>			
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="/default/sop/safety#anc">
							<div class="submenu-icon">
								<i class="fas fa-book" style="font-size:70px; width:auto;"></i><div class="submenu-icon-title">Safety</div>
							</div>
							</a>
						</div>			
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="/default/sop/parking#anc">
							<div class="submenu-icon">
								<i class="fas fa-book" style="font-size:70px; width:auto;"></i><div class="submenu-icon-title">Parking</div>
							</div>
							</a>
						</div>			
						<div class="col-md-2 col-sm-3 col-xs-6">
							<a href="/default/sop/housekeeping#anc">
							<div class="submenu-icon">
								<i class="fas fa-book" style="font-size:70px; width:auto;"></i><div class="submenu-icon-title">Housekeeping</div>
							</div>
							</a>
						</div>			
						<div class="clearfix"></div>
					</div>
          		</div>
			</div>
        </div>
        <!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
	$("#sop-menu").addClass('active');
	$("#sop-menu .child_menu").show();
});	
</script>