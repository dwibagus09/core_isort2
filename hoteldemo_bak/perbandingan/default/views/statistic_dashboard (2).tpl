			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Business Intelligence</h1>
	      <?php if($this->showAddSecurity == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/add">
				<div class="submenu-icon">
				    <img src="/images/add_report.png" /><div class="submenu-icon-title">Add Supervisor <?php if($this->securityRole) echo "Security "; ?> Daily Report</div>
				</div>
				</a>
			</div>		
			<?php }
			if($this->showSecurity == 1) { ?>
	       <div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/view">
				<div class="submenu-icon">
				    <img src="/images/spv_report.png" /><div class="submenu-icon-title">View Supervisor <?php if($this->securityRole) echo "Security "; ?> Daily Report</div>
				</div>
				</a>
			</div>
			<?php }
			if($this->showChiefSecurity == 1){ ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/viewchiefreport">
				<div class="submenu-icon">
				    <img src="/images/chief_report.png" /><div class="submenu-icon-title">View Chief <?php if($this->securityRole) echo "Security "; ?> Daily Report</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/security/viewmonthlyanalysis">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/1">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateSecurityPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/1">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Business Intelligence</div>
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
	$("#business-intelligence-menu").addClass('active');
	$("#business-intelligence-menu .child_menu").show();
});	
</script>