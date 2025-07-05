			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Site Manager</h1>
			<?php /* if($this->showSecurityActionPlanApproval == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/reschedulelist/cat/1">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Action Plan Reschedule</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSafetyActionPlanApproval == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/reschedulelist/cat/3">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Action Plan Reschedule</div>
				</div>
				</a>
			</div>
			<?php } */ ?>
			<?php if($this->showHousekeepingActionPlanApproval == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/reschedulelist/cat/2">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Action Plan Reschedule</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showPreventiveMaintenanceApproval == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/reschedulelist/cat/6">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Preventive Maintenance Reschedule</div>
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
	$("#site-manager-menu").addClass('active');
	$("#site-manager-menu .child_menu").show();
});	
</script>