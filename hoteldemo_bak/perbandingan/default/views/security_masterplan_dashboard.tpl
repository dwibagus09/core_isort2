			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Action Plan</h1>
			<?php if($this->showSecurityActionPlan == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/view/c/1">
				<div class="submenu-icon">
				    <img src="/images/sap.png" /><div class="submenu-icon-title">Action Plan</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCQC == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/actionplan/viewcqc/c/1">
				<div class="submenu-icon">
				    <img src="/images/sap_audit.png" /><div class="submenu-icon-title">CQC</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityKpi == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/kpi/viewmonthly/c/1">
				<div class="submenu-icon">
				    <img src="/images/monthly_kpi.png" /><div class="submenu-icon-title">Monthly KPI</div>
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