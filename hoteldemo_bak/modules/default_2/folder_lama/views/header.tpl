<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="/images/isort_new_logo.png" type="image/png" />

    <title>iSort CMMS</title>
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Oswald:wght@600&family=Roboto&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="/fontawesome5.15.3/css/all.css" rel="stylesheet">
    <!-- iCheck -->

    <!-- Custom Theme Style -->	
    <link href="/css/custom.css" rel="stylesheet">
	<link href="/css/styles.css?v=1" rel="stylesheet">
	
	<!-- jQuery -->
    <script src="/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/bootstrap/dist/js/bootstrap.min.js"></script>	
	<script src="/js/jquery.mloading.js"></script>
	<link href="/css/jquery.mloading.css" rel="stylesheet">
	
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<script type="text/javascript" src="/js/jquery-ui.min.js"></script>

	<script type="text/javascript">
		$(document).ready(function() {
			<?php if($this->isMobile == false) { ?>
				$("#sidebar-menu").height($( window ).height()-50);
				/*$(".row").height($( window ).height()-100);*/
			<?php } ?>
			
			$(".sites div").click(function() {
			    $( ".sites .top_submenu" ).toggle();
			    $( ".profile_menu .top_submenu" ).hide();
			});
			
			$(".profile_menu div").click(function() {
			    $( ".profile_menu .top_submenu" ).toggle();
			    $( ".sites .top_submenu" ).hide();
			});
			
			/*$(".row").css('min-height', $( window ).height()-120);*/
			
			$("#safety-comittee-menu").mouseover(function() {
                $('img', $("#safety-comittee-menu")).attr("src", "/images/safety_comittee_logo.png");
            });
            
            $( "#safety-comittee-menu" ).mouseleave(function() {
        	    $('img', $("#safety-comittee-menu")).attr("src", "/images/safety_comittee_logo_white.png");
            });
			
			if ($(window).width() < 770) {
			   $( "body" ).removeClass( "nav-md" );
			   $( "body" ).addClass( "nav-sm" );
			}
		});
	</script>
	<?php if(in_array(32, $this->ident['role_ids']) || in_array(33, $this->ident['role_ids'])) { ?>
		<style>
			#issue-finding-field {
				display:block;
			}
			#issue-form {
				width: 500px;
			}
		</style>
	<?php } ?>
	
  </head>

  <body class="nav-md" <?php if($this->hideScrollbar == 1) echo 'style="overflow:hidden";'; ?>>

      <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="scroll-view">

            <!-- menu profile quick info -->
            <?php /*<div class="profile clearfix">
              <div class="profile_info">
                <h2>Welcome <?php echo ucwords(strtolower($this->ident['name'])); ?>,</h2>
				<a href="/default/user/changepassword" style="color:#4db8ff"><i class="fas fa-lock"></i> Change Password</a>
				<?php if(!empty($this->sitesSelections) && count($this->sitesSelections) > 1) { ?>
				<br style="line-height:25px;"/>Sites: <?php echo $this->ident['site_name']; ?>
				<?php } ?>
              </div>
            </div> */ ?>
            <!-- /menu profile quick info -->

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="/default/index/index"><i class="fas fa-th"></i> Dashboard</a></li>
				  <?php if($this->showIssueFinding == 1) { ?>
					<?php if($this->showOpenedIssues == 1) { ?>
						<li><a href="/default/issue/listissues"><i class="fas fa-compass"></i> Opened Kaizen (<?php echo intval($this->totalAllIssues['total']); ?>)</a></li>
					<?php } ?>
					<?php if($this->showClosedIssues == 1) { ?>
						<li><a href="/default/issue/solvedissues"><i class="fas fa-check-circle"></i> Closed Kaizen</a></li>
					<?php } ?>
				  <?php } ?>
				  <?php /* if($this->showSecurity == 1 || $this->showChiefSecurity == 1) {  ?>
				  <li id="report-menu"><a href="/default/index/reportdashboard"><i class="fas fa-paste"></i> Resort Reports</a> 
				    <ul class="nav child_menu">
						<?php if($this->showAddSecurity == 1) { ?>
							<li><a href="/default/security/add">Add Supervisor Daily Report</a></li>
								<li class="edit-spv-sec" style="display:none;"><a href="#">Edit Supervisor Daily Report</a></li>
						<?php }
							if($this->showSecurity == 1) { ?>							
								<li><a href="/default/security/view">View Supervisor Daily Report</a></li>
						<?php }
							if($this->showChiefSecurity == 1){ ?>
								<li><a href="/default/security/viewchiefreport">View Chief Daily Report</a></li>							
								<li class="edit-chief-sec" style="display:none;"><a href="#">Edit Chief Daily Report</a></li>
						<?php } ?>
						</ul>
				  </li>
				  <?php } */  ?>
				  <?php if($this->showHousekeepingMonthlyAnalysis  == 1 || $this->showHousekeepingPivotChart == 1 || $this->showCorporateHousekeepingPivotChart == 1 || $this->showEngineeringMonthlyAnalysis  == 1 || $this->showEngineeringPivotChart == 1 || $this->showCorporateEngineeringPivotChart == 1 || $this->showBuildingServiceMonthlyAnalysis  == 1 || $this->showBuildingServicesPivotChart == 1 ||  $this->showCorporateBuildingServicePivotChart == 1 || $this->showGcMonthlyAnalysis == 1 || $this->showSecurityMonthlyAnalysis == 1 || $this->showSecurityPivotChart == 1 || $this->showCorporateSecurityPivotChart == 1 || $this->showParkingMonthlyAnalysis == 1 || $this->showParkingPivotChart == 1 || $this->showCorporateParkingPivotChart == 1 || $this->showTenantRelationMonthlyAnalysis == 1 || $this->showTenantRelationPivotChart == 1 || $this->showCorporateTenantRelationPivotChart == 1 || $this->showBuildingServiceMonthlyAnalysis == 1 || $this->showBuildingServicePivotChart == 1 || $this->showCorporateBuildingServicePivotChart == 1) {  ?>
				  <li id="business-intelligence-menu"><a href="/default/index/bidashboard"><i class="fas fa-chart-pie"></i> Business Intelligence</a> 
				    <ul class="nav child_menu">
						<?php if($this->addHousekeepingMonthlyAnalysis == 1 && $this->hideAddHousekeepingMonthlyAnalysis == 0) { ?>
							<li><a href="/default/bi/addmonthlyanalysis/c/2">Add Housekeeping Monthly Analytics</a></li>
						<?php }
							if($this->showHousekeepingMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/2">Housekeeping Monthly Analytics</a></li>
						<?php }
							if($this->showHousekeepingPivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/2">Housekeeping Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateHousekeepingPivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/2">Corporate Housekeeping Business Intelligence</a></li>
						<?php } ?>
						<?php if($this->addEngineeringMonthlyAnalysis == 1 && $this->hideAddEngineeringMonthlyAnalysis == 0) { ?>
								<li><a href="/default/bi/addmonthlyanalysis/c/6">Add Engineering Monthly Analytics</a></li>
						<?php }
							if($this->showEngineeringMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/6">Engineering Monthly Analytics</a></li>
						<?php }
							if($this->showEngineeringPivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/6">Engineering Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateEngineeringPivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/6">Corporate Engineering Business Intelligence</a></li>
						<?php } if($this->site_id == 1) {
							if($this->addGcMonthlyAnalysis == 1 && $this->hideAddGcMonthlyAnalysis == 0) { ?>
							<li><a href="/default/gc/addmonthlyanalysis">Add Guess Complain Monthly Analytics</a></li>
						<?php }
							if($this->showGcMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/gc/viewmonthlyanalysis">Guess Complain Monthly Analytics</a></li>
						<?php }
							if($this->showGcPivotChart == 1) { ?>							
								<li><a href="/default/pivot/gc">Guess Complain Business Intelligence</a></li>
						<?php } } 
						if($this->site_id == 2) {
							if($this->addSecurityMonthlyAnalysis == 1 && $this->hideAddSecurityMonthlyAnalysis == 0) { ?>
							<li><a href="/default/bi/addmonthlyanalysis/c/1">Add Security, Safety, Parking Monthly Analytics</a></li>
						<?php }
							if($this->showSecurityMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/1">Security, Safety, Parking Monthly Analytics</a></li>
						<?php }
							if($this->showSecurityPivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/1">Security, Safety, Parking Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateSecurityPivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/1">Corporate Security, Safety, Parking Business Intelligence</a></li>
						<?php }
						if($this->addParkingMonthlyAnalysis == 1 && $this->hideAddParkingMonthlyAnalysis == 0) { ?>
							<li><a href="/default/bi/addmonthlyanalysis/c/5">Add Fit Out Monthly Analytics</a></li>
						<?php }
							if($this->showParkingMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/5">Fit Out Monthly Analytics</a></li>
						<?php }
							if($this->showParkingPivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/5">Fit Out Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateParkingPivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/5">Corporate Fit Out Business Intelligence</a></li>
						<?php }
						if($this->addTenantRelationMonthlyAnalysis == 1 && $this->hideAddTenantRelationMonthlyAnalysis == 0) { ?>
							<li><a href="/default/bi/addmonthlyanalysis/c/11">Add Tenant Relation Monthly Analytics</a></li>
						<?php }
							if($this->showTenantRelationMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/11">Tenant Relation Monthly Analytics</a></li>
						<?php }
							if($this->showTenantRelationPivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/11">Tenant Relation Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateTenantRelationPivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/11">Corporate Tenant Relation Business Intelligence</a></li>
						<?php } } 
						if($this->addBuildingServiceMonthlyAnalysis == 1 && $this->hideAddBuildingServiceMonthlyAnalysis == 0) { ?>
							<li><a href="/default/bi/addmonthlyanalysis/c/10">Add Human Operations Monthly Analytics</a></li>
						<?php }
							if($this->showBuildingServiceMonthlyAnalysis == 1) { ?>							
								<li><a href="/default/bi/viewmonthlyanalysis/c/10">Human Operations Monthly Analytics</a></li>
						<?php }
							if($this->showBuildingServicePivotChart == 1) { ?>							
								<li><a href="/default/pivot/index/c/10">Human Operations Business Intelligence</a></li>
						<?php } 
							if($this->showCorporateBuildingServicePivotChart == 1 && count($this->sitesSelections) > 1) { ?>							
								<li><a href="/default/pivot/corporate/c/10">Corporate Human Operations Business Intelligence</a></li>
						<?php } ?>
						</ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showDigitalChecklist == 1) { ?>
					<li id="digital-checklist-menu"><a href="#"><i class="fas fa-tasks"></i> Digital Checklist</a>
						<ul class="nav child_menu">
						<?php if($this->showAddDigitalChecklist == 1) { ?>
							<li><a href="/default/checklist/add">Add Digital Checklist</a></li>
								<li class="edit-digital-checklist" style="display:none;"><a href="#">Edit Digital Checklist</a></li>
						<?php } ?>						
							<li><a href="/default/checklist/view">View Digital Checklist</a></li>
						</ul>
					</li>
				  <?php } ?>
				  <?php if($this->showCQC == 1 || $this->showHousekeepingActionPlan == 1 || $this->showHousekeepingKpi == 1 || $this->showEngineeringActionPlan == 1 || $this->showEngineeringKpi == 1) { ?>
				  <li id="action-plan-menu"><a href="/default/index/masterplandashboard"><i class="fas fa-calendar"></i> Action Plan</a> 
				    <ul class="nav child_menu">
						<?php if($this->showHousekeepingActionPlan == 1) { ?>
							<li><a href="/default/actionplan/view/c/2"> Housekeeping Action Plan</a></li>
						<?php } if($this->showHousekeepingActionPlan == 1 && $this->showCQC == 1) { ?>
							<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/2">Housekeeping CQC</a></li>
						<?php } ?>
						<?php if($this->showHousekeepingKpi == 1) { ?>
							<li><a href="/default/kpi/viewmonthly/c/2">Housekeeping Monthly KPI</a></li>
						<?php } ?>
						
						<?php if($this->showSecurityActionPlan == 1) { ?>
							<li><a href="/default/actionplan/view/c/1"> Security, Safety, Parking Action Plan</a></li>
						<?php } if($this->showSecurityActionPlan == 1 && $this->showCQC == 1) { ?>
							<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/1">Security, Safety, Parking CQC</a></li>
						<?php } ?>
						<?php if($this->showSecurityKpi == 1) { ?>
							<li><a href="/default/kpi/viewmonthly/c/1">Security, Safety, Parking Monthly KPI</a></li>
						<?php } ?>
						
						<?php if($this->showParkingActionPlan == 1) { ?>
							<li><a href="/default/actionplan/view/c/5"> Fit Out Action Plan</a></li>
						<?php } if($this->showParkingActionPlan == 1 && $this->showCQC == 1) { ?>
							<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/5">Fit Out CQC</a></li>
						<?php } ?>
						<?php if($this->showParkingKpi == 1) { ?>
							<li><a href="/default/kpi/viewmonthly/c/5">Fit Out Monthly KPI</a></li>
						<?php } ?>
						
					</ul>
				  </li>
				  <?php }  ?>
				<?php if($this->showEngineering == 1) { ?>
				<li id="engineering-menu"><a href="/default/engineering/dashboard"><i class="fas fa-calendar"></i> Engineering</a> 
				    <ul class="nav child_menu">
				        <?php if($this->showEngineeringActionPlan == 1) { ?>
						<li><a href="/default/actionplan/view/c/6"> Preventive Maintenance</a></li>
						<?php } if($this->showCQC == 1) { ?>
								<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/6">CQC</a></li>
						<?php } ?>
						<?php if($this->showEngineeringKpi == 1) { ?>
							<li><a href="/default/kpi/viewmonthly/c/6">Monthly KPI</a></li>
						<?php } ?>
						<?php /*if($this->viewWorkOrder == 1) { ?>
								<li class="view-wo"><a href="/default/workorder/view">Work Order</a></li>
						<?php } */ ?>
						</ul>
				  </li>  
				<?php } ?>
                <?php if($this->showOM == 1) { ?>
				  <li id="site-manager-menu"><a href="/default/sitemanager/dashboard"><i class="fas fa-tablet"></i> <?php if($this->teacher) echo "Principal"; else echo "Site Manager"; ?></a>
					 <ul class="nav child_menu">
					  <?php if($this->showHousekeepingActionPlanApproval == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist/cat/2"> Housekeeping Action Plan Reschedule Approval</a></li>
					  <?php } ?>
					  <?php if($this->showSecurityActionPlanApproval == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist/cat/1"> Security &amp; Safety Action Plan Reschedule Approval</a></li>
					  <?php } ?>
					  <?php if($this->showParkingActionPlanApproval == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist/cat/5"> Parking Action Plan Reschedule Approval</a></li>
					  <?php } ?>
					  <?php if($this->showPreventiveMaintenanceApproval == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist/cat/6"> Preventive Maintenance Reschedule Approval</a></li>
					  <?php } ?>
                    </ul>
				  </li>
				  <?php }  ?>
				  
                <?php if($this->showSafetyComittee == 1 || $this->showSafetyComitteeAdmin == 1) { ?>
					<li id="safety-comittee-menu"><a href="/default/safetycomittee/dashboard"><img class="nav-menu-icon" src="/images/safety_comittee_logo_white.png" /> Safety Committee</a>
					 <ul class="nav child_menu">
						<?php if($this->showAddSafetyComittee == 1) { ?><li><a href="/default/safetycomittee/add">Add Safety Committee</a></li><?php } ?>
						<li class="edit-sc" style="display:none;"><a href="#">Edit Safety Committee</a></li>
						<li><a href="/default/safetycomittee/view">View Safety Committee</a></li>
						<?php if($this->showHistorySafetyComittee == 1) { ?><li><a href="/default/safetycomittee/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>			  
				  
				<?php if($this->showHODMeeting == 1 || $this->showHODMeetingAdmin == 1) { ?>
					<li id="bod-meeting-menu"><a href="/default/hod/dashboard"><i class="fas fa-users"></i> Digital MOM</a>
					 <ul class="nav child_menu">
						<?php if($this->showAddHOD == 1) { ?><li><a href="/default/hod/add">Add Digital MOM</a></li><?php } ?>
						<li class="edit-hod" style="display:none;"><a href="#">Edit Digital MOM</a></li>
						<li><a href="/default/hod/view">View Digital MOM</a></li>
						<?php if($this->showHistoryHOD == 1) { ?><li><a href="/default/hod/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>
				<?php if($this->showStatistic == 1) { ?>
				  <li id="statistic-menu"><a href="/default/statistic/dashboard"><i class="fas fa-chart-bar"></i> Statistic</a>
					 <ul class="nav child_menu">
					 	<?php if($this->showStatistic == 1) { ?>
							<li><a href="/default/statistic/view">Kaizen Statistic</a></li>
							<li><a href="/default/statistic/site">User Statistic</a></li>
							<?php if(count($this->sitesSelections) > 1) { ?>
							<li><a href="/default/statistic/corporate">Corporate Statistic</a></li>
							<?php } ?>
							<?php /*<li><a href="/default/statistic/workorder">Work Order Statistic</a></li> */ ?>
						<?php } ?>
					</ul>
				  </li>
				  <?php }   ?>
				  
                </ul>
              </div>
            </div>
            <!-- /sidebar menu -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fas fa-bars"></i></a>
              </div>
                <div id="logo" style="background: url('/images/isort_logo_white.png') no-repeat; background-size: 28px;"></div>
		        <h1><span style="color:#9e824b;">i</span>Sort <span style="color:#9e824b;">H</span>otel</h1>
    			<div class="profile_menu">
    				<div>
    					Welcome <?php echo ucwords(strtolower($this->ident['name'])); ?>&nbsp;&nbsp;<i class="fas fa-chevron-down"></i>
    				</div>
    				<ul class="top_submenu">
    				    <li onclick="location.href='/default/user/changepassword'"><i class="fas fa-lock"></i>&nbsp;&nbsp;Change Password</li>
						<?php /*<li onclick="location.href='#'"><i class="fas fa-question-circle"></i>&nbsp;&nbsp;Helpdesk</li>*/ ?>
    				    <li onclick="location.href='/default/user/logout'"><i class="fas fa-sign-out-alt"></i>&nbsp;&nbsp;Logout</li>
    				</ul>
    			</div>
				<?php if(!empty($this->sitesSelections) && count($this->sitesSelections) > 1) { ?>
    			<div class="sites">
    				<div>
    					Sites: <?php echo $this->ident['site_name']; ?>&nbsp;&nbsp;<i class="fas fa-chevron-down"></i>
    				</div>
    				<ul class="top_submenu">
    				    <?php if($this->showSiteSelection == 1) {
    				        foreach($this->sitesSelections as $siteSelection) { ?>
    				            <li onclick="location.href='/default/user/setsiteid/id/<?php echo $siteSelection['site_id']; ?>'"><i class="fas fa-building"></i>&nbsp;&nbsp;<?php echo $siteSelection['site_name']; ?></li>
						<?php } } ?>
    				</ul>
    			</div>
				<?php } ?>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

		<!-- page content -->
        <div class="right_col" role="main">
			<?php /* if($this->showSiteSelection == 1) { ?>
				<div class="site-selection">
					Select Site &nbsp; &nbsp; <select id="site-menu" name="site_id" style="width:90%;">
					<?php foreach($this->sitesSelections as $siteSelection) { ?>
						<option value="<?php echo $siteSelection['site_id']; ?>" <?php if($this->ident['site_id'] == $siteSelection['site_id']) echo 'selected="selected"'; ?> ><?php echo $siteSelection['site_name']; ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } */ ?>
			<div id="content_wrapper">
