			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Action Plan</h1>
			<?php if($this->showHousekeepingActionPlan == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/view/c/2">
				<div class="submenu-icon">
				    <img src="/images/sap.png" /><div class="submenu-icon-title">Housekeeping Action Plan</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showHousekeepingActionPlan == 1 && $this->showCQC == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/viewcqc/c/2">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Housekeeping CQC</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showHousekeepingKpi == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/kpi/viewmonthly/c/2">
				<div class="submenu-icon">
				    <img src="/images/monthly_kpi.png" /><div class="submenu-icon-title">Housekeeping Monthly KPI</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<br clear="all"/>
			<?php if($this->showSecurityActionPlan == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/view/c/1">
				<div class="submenu-icon">
				    <img src="/images/sap.png" /><div class="submenu-icon-title">Security, Safety, Parking Action Plan</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityActionPlan == 1 && $this->showCQC == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/viewcqc/c/1">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Security, Safety, Parking CQC</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityKpi == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/kpi/viewmonthly/c/1">
				<div class="submenu-icon">
				    <img src="/images/monthly_kpi.png" /><div class="submenu-icon-title">Security, Safety, Parking Monthly KPI</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<br clear="all"/>
			<?php if($this->showParkingActionPlan == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/view/c/5">
				<div class="submenu-icon">
				    <img src="/images/sap.png" /><div class="submenu-icon-title">Fit Out Action Plan</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showParkingActionPlan == 1 && $this->showCQC == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/viewcqc/c/5">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">Fit Out CQC</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showParkingKpi == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/kpi/viewmonthly/c/5">
				<div class="submenu-icon">
				    <img src="/images/monthly_kpi.png" /><div class="submenu-icon-title">Fit Out Monthly KPI</div>
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
	$("#action-plan-menu").addClass('active');
	$("#action-plan-menu .child_menu").show();
});	
</script>