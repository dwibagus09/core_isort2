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
				$(".row").height($( window ).height()-100);
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
			
			/*if ($(window).width() < 500) {
			   $( "body" ).removeClass( "nav-md" );
			   $( "body" ).addClass( "nav-sm" );
			}*/
		});
	</script>
  </head>

  <body class="nav-md" <?php if($this->hideScrollbar == 1) echo 'style="overflow:hidden";'; ?>>

      <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="scroll-view">

            <!-- menu profile quick info -->
            <?php /*<div class="profile clearfix">
              <div class="profile_info">
                <h2>Welcome <?php echo $this->ident['name']; ?>,</h2>
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
					<li><a href="/default/issue/listissues"><i class="fas fa-compass"></i> Opened Kaizen (<?php echo intval($this->totalAllIssues['total']); ?>)</a></li>
					<li><a href="/default/issue/solvedissues"><i class="fas fa-check-circle"></i> Closed Kaizen</a></li>
				  <?php } ?>
				  <?php if($this->showSecurity == 1 || $this->showSecurityKpi == 1) { ?>
				  <li id="business-intelligence-menu"><a href="/default/security/dashboard"><i class="fas fa-chart-pie"></i> Business Intelligence</a> 
				    <ul class="nav child_menu">
						<?php if($this->showAddSecurity == 1) { ?>
							<li><a href="/default/security/add">Add Supervisor <?php if($this->securityRole) echo "Security "; ?>Daily Report</a></li>
								<li class="edit-spv-sec" style="display:none;"><a href="#">Edit Supervisor <?php if($this->securityRole) echo "Security "; ?>Daily Report</a></li>
						<?php }
							if($this->showSecurity == 1) { ?>							
								<li><a href="/default/security/view">View Supervisor <?php if($this->securityRole) echo "Security "; ?>Daily Report</a></li>
						<?php }
							if($this->showChiefSecurity == 1){ ?>
								<li><a href="/default/security/viewchiefreport">View Chief <?php if($this->securityRole) echo "Security "; ?>Daily Report</a></li>							
								<li class="edit-chief-sec" style="display:none;"><a href="#">Edit Chief Daily Report</a></li>
						<?php } ?>
						<?php if($this->showSecurityMonthlyAnalysis == 1) { ?>
								<?php if($this->hideAddSecurityMonthlyAnalysis == 0 && $this->addSecurityMonthlyAnalysis == 1){ ?><li><a href="/default/security/addmonthlyanalysis">Add Monthly Analytics</a></li><?php } ?>						
								<li class="view-monthly-analysis"><a href="/default/security/viewmonthlyanalysis">Monthly Analytics</a></li>
						<?php } ?>
						<?php if($this->showSecurityPivotChart == 1) { ?>
							<li><a href="/default/pivot/index/c/1">Business Intelligence</a></li>
						<?php } ?>
						<?php if($this->showCorporateSecurityPivotChart == 1) { ?>
							<li><a href="/default/pivot/corporate/c/1">Corporate Business Intelligence </a></li>
						<?php } ?>
						</ul>
				  </li>
				  <li id="action-plan-menu"><a href="/default/security/masterplandashboard"><i class="fas fa-calendar-check"></i> Action Plan</a> 
				    <ul class="nav child_menu">
						<?php if($this->showSecurityActionPlan == 1) { ?>
							<li><a href="/default/actionplan/view/c/1"> Action Plan</a></li>
							<?php /*if($this->showActionPlanSetting == 1) { ?>
							<li><a href="/default/actionplan/module/c/1"> Action Plan Module</a></li>
							<li><a href="/default/actionplan/target/c/1"> Action Plan Target</a></li>
							<li><a href="/default/actionplan/activity/c/1"> Action Plan Activity</a></li>
							<li><a href="/default/actionplan/reminder/c/1"> Action Plan Reminder Email</a></li>
							<?php if($this->showReminderReview == 1) { ?>
								<?php */ /*<li><a href="/default/actionplan/upcoming/c/1"> Action Plan Weekly Reminder</a></li>*/ /* ?>
								<li><a href="/default/actionplan/review/c/1"> Action Plan Review</a></li>
							<?php } ?>
							<li><a href="/default/actionplan/reschedulestatistic/c/1"> Action Plan Resechedule Statistic</a></li>
						<?php } */ } ?>
						<?php if($this->showCQC == 1) { ?>
								<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/1">CQC</a></li>
						<?php } ?>
						<?php if($this->showSecurityKpi == 1) { ?>
							<?php /*<li><a href="/default/kpi/view/c/1">New KPI</a></li> */ ?>
							<li><a href="/default/kpi/viewmonthly/c/1">Monthly KPI</a></li>
						<?php } ?>
						</ul>
				  </li>
				  <?php }  ?>
				<?php if($this->showEngineering == 1) { ?>
				<li id="engineering-menu"><a href="/default/engineering/dashboard"><i class="fas fa-cogs"></i> Engineering</a> 
				    <ul class="nav child_menu">
				        <?php if($this->showEngineeringActionPlan == 1) { ?>
						<li><a href="/default/actionplan/view/c/6"> Preventive Maintenance</a></li>
						<?php } if($this->showCQC == 1) { ?>
								<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/6">CQC</a></li>
						<?php } ?>
						<?php if($this->showEngineeringKpi == 1) { ?>
							<li><a href="/default/kpi/viewmonthly/c/6">Monthly KPI</a></li>
						<?php } ?>
						<?php if($this->viewWorkOrder == 1) { ?>
								<li ><a href="/default/workorder/view">Work Order</a></li>
						<?php } ?>
						</ul>
				  </li>  
				<?php } ?>
                <?php if($this->showOM == 1) { ?>
				  <li id="site-manager-menu"><a href="/default/sitemanager/dashboard"><i class="fas fa-tablet"></i> <?php if($this->teacher) echo "Principal"; else echo "Site Manager"; ?></a>
					 <ul class="nav child_menu">
					  <?php if($this->showOMActionPlan == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist/cat/1"> Action Plan Reschedule Approval</a></li>
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
						<li class="edit-hod" style="display:none;"><a href="#">Edit Digital MOM</li>
						<li><a href="/default/hod/view">View Digital MOM</li>
						<?php if($this->showHistoryHOD == 1) { ?><li><a href="/default/hod/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>
				<?php if($this->showStatistic == 1) { ?>
				  <li id="statistic-menu"><a href="/default/statistic/dashboard"><i class="fas fa-chart-bar"></i> Statistic</a>
					 <ul class="nav child_menu">
					 	<?php if($this->showStatistic == 1) { ?>
							<li><a href="/default/statistic/view">Kaizen Statistic</a></li>
							<li><a href="/default/statistic/site">Site Statistic</a></li>
							<li><a href="/default/statistic/corporate">Corporate Statistic</a></li>
						<?php } ?>
					</ul>
				  </li>
				  <?php }   ?>
				  
				  <li id="covid19-menu"><a href="/sop/isort_covid_guidlines.pdf" target="_blank"><i class="fas fa-virus"></i> Covid-19 Guidelines</a></li>
				  
				  <li id="sop-menu"><a href="/default/sop/dashboard#anc"><i class="fas fa-book"></i> SOP &amp; IK</a>
					<ul class="nav child_menu">
						<li><a href="/default/sop/security#anc">Security</a></li>
						<li><a href="/default/sop/safety#anc">Safety</a></li>
						<li><a href="/default/sop/parking#anc">Parking &amp; Traffic</a></li>
						<li><a href="/default/sop/housekeeping#anc">Housekeeping</a></li>
					</ul>
				  </li>
				  
				  <li id="training-material-menu"><a href="/default/sop/trainingmaterialdashboard#anc"><i class="fas fa-book-reader"></i> Training Material</a>
					<ul class="nav child_menu">
						<li><a href="/default/sop/securitytrainingmaterial#anc">Security</a></li>
						<li><a href="/default/sop/safetytrainingmaterial#anc">Safety</a></li>
						<li><a href="/default/sop/parkingtrainingmaterial#anc">Parking &amp; Traffic</a></li>
						<li><a href="/default/sop/housekeepingtrainingmaterial#anc">Housekeeping</a></li>
					</ul>
				  </li>
				  
				 <?php /* <li><a href="/default/nfc/view"><i class="fa fa-wifi"></i> NFC</a></li> */ ?>
				 
                </ul>
              </div>
			  <div id="anc"></div>
            </div>
            <!-- /sidebar menu -->
          </div>
        </div>

        <!-- top navigation -->
        <div class="top_nav">
          <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
                <div id="logo"></div>
		        <h1><span style="color:#9e824b;">i</span>Sort</h1>
    			<div class="profile_menu">
    				<div>
    					Welcome <?php echo $this->ident['name']; ?>&nbsp;&nbsp;<i class="fa fa-chevron-down"></i>
    				</div>
    				<ul class="top_submenu">
    				    <li onclick="location.href='/default/user/changepassword'"><i class="fa fa-lock"></i>&nbsp;&nbsp;Change Password</li>
						<li onclick="location.href='#'"><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Helpdesk</li>
    				    <li onclick="location.href='/default/user/logout'"><i class="fa fa-sign-out-alt"></i>&nbsp;&nbsp;Logout</li>
    				</ul>
    			</div>
				<?php if(!empty($this->sitesSelections) && count($this->sitesSelections) > 1) { ?>
    			<div class="sites">
    				<div>
    					Sites: <?php echo $this->ident['site_name']; ?>&nbsp;&nbsp;<i class="fa fa-chevron-down"></i>
    				</div>
    				<ul class="top_submenu">
    				    <?php if($this->showSiteSelection == 1) {
    				        foreach($this->sitesSelections as $siteSelection) { ?>
    				            <li onclick="location.href='/default/user/setsiteid/id/<?php echo $siteSelection['site_id']; ?>'"><i class="fa fa-building"></i>&nbsp;&nbsp;<?php echo $siteSelection['site_name']; ?></li>
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