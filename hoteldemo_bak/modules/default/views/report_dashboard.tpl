			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Resort Report</h1>
	      <?php if($this->showAddSecurity == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/add">
				<div class="submenu-icon">
				    <img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Supervisor Daily Report</div>
				</div>
				</a>
			</div>		
			<?php }
			if($this->showSecurity == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/view">
				<div class="submenu-icon">
				    <img src="/images/spv_report.png" /><div class="submenu-icon-title">View Supervisor Daily Report</div>
				</div>
				</a>
			</div>
			<?php }
			if($this->showChiefSecurity == 1){ ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/viewchiefreport">
				<div class="submenu-icon">
				    <img src="/images/chief_report.png" /><div class="submenu-icon-title">View Chief Daily Report</div>
				</div>
				</a>
			</div>
			<?php } ?>
	  </div> 	
    </div>
</div>
<!-- /page content -->

<script type="text/javascript">
$(document).ready(function() {
	$("#report-menu").addClass('active');
	$("#report-menu .child_menu").show();
});	
</script>