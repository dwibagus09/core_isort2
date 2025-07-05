			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
  <div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
	  <div class="dashboard">
	      <h1 class="page-title">Business Intelligence</h1>
			
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addHousekeepingMonthlyAnalysis == 1 && $this->hideAddHousekeepingMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/2">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Housekeeping Monthly Analytics</div>
					</div>
					</a>		
				<?php } ?>
			</div>
			<?php if($this->showHousekeepingMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/2">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Housekeeping Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showHousekeepingPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/2">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Housekeeping Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateHousekeepingPivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/2">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Housekeeping Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addEngineeringMonthlyAnalysis == 1 && $this->hideAddEngineeringMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/6">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Engineering Monthly Analytics</div>
					</div>
					</a>	
				<?php } ?>
			</div>	
			<?php if($this->showEngineeringMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/6">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Engineering Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showEngineeringPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/6">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Engineering Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateEngineeringPivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/6">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Engineering Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->site_id == 2) { ?>
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addSecurityMonthlyAnalysis == 1 && $this->hideAddSecurityMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/1">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Security, Safety, Parking Monthly Analytics</div>
					</div>
					</a>	
				<?php } ?>
			</div>	
			<?php if($this->showSecurityMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/1">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Security, Safety, Parking Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showSecurityPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/1">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Security, Safety, Parking Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateSecurityPivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/1">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Security, Safety, Parking Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addParkingMonthlyAnalysis == 1 && $this->hideAddParkingMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/5">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Fit Out Monthly Analytics</div>
					</div>
					</a>	
				<?php } ?>
			</div>	
			<?php if($this->showParkingMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/5">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Fit Out Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showParkingPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/5">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Fit Out Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateParkingPivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/5">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Fit Out Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addTenantRelationMonthlyAnalysis == 1 && $this->hideAddTenantRelationMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/11">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Tenant Relation Monthly Analytics</div>
					</div>
					</a>	
				<?php } ?>
			</div>	
			<?php if($this->showTenantRelationMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/11">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Tenant Relation Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showTenantRelationPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/11">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Tenant Relation Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateTenantRelationPivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/11">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Tenant Relation Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			
			
			
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addBuildingServiceMonthlyAnalysis == 1 && $this->hideAddBuildingServiceMonthlyAnalysis == 0) { ?>			   
					<a href="/default/bi/addmonthlyanalysis/c/10">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Human Operations Monthly Analytics</div>
					</div>
					</a>	
				<?php } ?>
			</div>	
			<?php if($this->showBuildingServiceMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/bi/viewmonthlyanalysis/c/10">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Human Operations Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showBuildingServicePivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/index/c/10">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Human Operations Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showCorporateBuildingServicePivotChart == 1 && count($this->sitesSelections) > 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/corporate/c/10">
				<div class="submenu-icon">
				    <img src="/images/corp_analytics.png" /><div class="submenu-icon-title">Corporate Human Operations Business Intelligence</div>
				</div>
				</a>
			</div>
			<?php } } ?>
			
			<br clear="all"/>
			<div class="col-md-2 col-sm-3 col-xs-6">
				<?php if($this->addGcMonthlyAnalysis == 1 && $this->hideAddGcMonthlyAnalysis == 0) { ?>			   
					<a href="/default/gc/addmonthlyanalysis">
					<div class="submenu-icon">
						<img src="/images/chief_report.png" /><div class="submenu-icon-title">Add Guess Complain Monthly Analytics</div>
					</div>
					</a>		
				<?php } ?>
			</div>
			<?php if($this->showGcMonthlyAnalysis == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/gc/viewmonthlyanalysis">
				<div class="submenu-icon">
				    <img src="/images/monthly_analytics.png" /><div class="submenu-icon-title">Guess Complain Monthly Analytics</div>
				</div>
				</a>
			</div>
			<?php } ?>
			<?php if($this->showGcPivotChart == 1) { ?>
			<div class="col-md-2 col-sm-3 col-xs-6">
	            <a href="/default/pivot/gc">
				<div class="submenu-icon">
				    <img src="/images/site_analytics.png" /><div class="submenu-icon-title">Guess Complain Business Intelligence</div>
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