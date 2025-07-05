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
	<link rel="icon" href="/images/favicon.ico" type="image/ico" />

    <title>Our Smart Reporting Tool</title>

    <!-- Bootstrap -->
    <link href="/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="/animate.css/animate.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->	
    <link href="/css/custom.min.css" rel="stylesheet">
	<link href="/css/styles.css?v=1" rel="stylesheet">
	
	<!-- jQuery -->
    <script src="/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="/bootstrap/dist/js/bootstrap.min.js"></script>	
	<script src="/js/jquery.mloading.js"></script>
	<link href="/css/jquery.mloading.css" rel="stylesheet">

	<script type="text/javascript">
		$(document).ready(function() {
			<?php if($this->isMobile == false) { ?>
				$("#sidebar-menu").height($( window ).height()-160);
			<?php } ?>
		});
	</script>
  </head>

  <body class="nav-md" <?php if($this->hideScrollbar == 1) echo 'style="overflow:hidden";'; ?>>

      <div class="container body">
      <div class="main_container">
        <div class="col-md-3 left_col">
          <div class="left_col scroll-view">
            <div class="navbar nav_title" style="border: 0;">
              <?php /*<img id="logo" src="/images/oursrtlogo.png" width="80%" style="margin:5px;" / > */ ?>
			  <div id="logo"></div>
            </div>

            <div class="clearfix"></div>

            <!-- menu profile quick info -->
            <div class="profile clearfix">
              <div class="profile_info">
                <h2>Welcome <?php echo $this->ident['name']; ?>,</h2>
				<a href="/default/user/changepassword" style="color:#4db8ff"><i class="fa fa-lock"></i> Change Password</a>
				<br style="line-height:25px;"/>Sites: <?php echo $this->ident['site_name']; ?>
              </div>
            </div>
            <!-- /menu profile quick info -->

            <!-- sidebar menu -->
            <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
              <div class="menu_section">
                <ul class="nav side-menu">
                  <li><a href="/default/index/index"><i class="fa fa-home"></i> Home</a></li>
				  <?php if($this->showIssueFinding == 1) { ?>
					<li><a href="/default/issue/listissues"><i class="fa fa-list"></i> Opened Issues (<?php echo intval($this->totalAllIssues['total']); ?>)</a></li>
					<li><a href="/default/issue/solvedissues"><i class="fa fa-check-square"></i> Closed Issues</a></li>
				  <?php } ?>
				  <?php if($this->showSecurity == 1 || $this->showSecurityKpi == 1) { ?>
				  <li><a><i class="fa fa-shield"></i> Security</a>
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
								<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/1">View CQC</a></li>
						<?php } ?>
						<?php if($this->showSecurityMonthlyAnalysis == 1) { ?>
								<?php if($this->hideAddSecurityMonthlyAnalysis == 0 && $this->addSecurityMonthlyAnalysis == 1){ ?><li><a href="/default/security/addmonthlyanalysis">Add Monthly Analysis</a></li><?php } ?>						
								<li class="view-monthly-analysis"><a href="/default/security/viewmonthlyanalysis">View Monthly Analysis</a></li>
						<?php } ?>
						<?php if($this->showSecurityPivotChart == 1) { ?>
							<li><a href="/default/pivot/index/c/1">Pivot Chart</a></li>
						<?php } ?>
						<?php if($this->showCorporateSecurityPivotChart == 1) { ?>
							<li><a href="/default/pivot/corporate/c/1">Corporate Pivot Chart</a></li>
						<?php } ?>
						<?php if($this->showSecurityKpi == 1) { ?>
							<li><a href="/default/kpi/view/c/1">New KPI</a></li>
							<li><a href="/default/kpi/viewmonthly/c/1">Monthly KPI</a></li>
						<?php } ?>
						<?php if($this->showSecurityManPower == 1) { ?>
							<li><a href="/default/manpower/view/c/1">Man Power</a></li>
						<?php } ?>
						</ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showSafety == 1 || $this->showSafetyKpi == 1) { ?>
				  <li><a><i class="fa fa fa-exclamation-triangle"></i> Safety</a>
					 <ul class="nav child_menu">
						<?php if($this->hideAddSafety == 0 && $this->showSafety == 1) { ?>
							<li><a href="/default/safety/add">Add Daily Report</a></li>
						<?php }
							if($this->showSafety == 1) {
						 ?>
						<li class="edit-safety" style="display:none;"><a href="#">Edit Daily Report</a></li>
						<li><a href="/default/safety/viewreport">View Daily Report</a></li>
						<?php } if($this->showSafetyActionPlan == 1) { ?>
							<li><a href="/default/actionplan/view/c/3"> Action Plan</a></li>
						<?php /*if($this->showActionPlanSetting == 1) { ?>
							<li><a href="/default/actionplan/module/c/3"> Action Plan Module</a></li>
							<li><a href="/default/actionplan/target/c/3"> Action Plan Target</a></li>
							<li><a href="/default/actionplan/activity/c/3"> Action Plan Activity</a></li>
							<li><a href="/default/actionplan/reminder/c/3"> Action Plan Reminder Email</a></li>
							<?php if($this->showReminderReview == 1) { ?>
								<?php */ /*<li><a href="/default/actionplan/upcoming/c/3"> Action Plan Weekly Reminder</a></li>*/ /* ?>
								<li><a href="/default/actionplan/review/c/3"> Action Plan Review</a></li>
							<?php } ?>
							<li><a href="/default/actionplan/reschedulestatistic/c/3"> Action Plan Resechedule Statistic</a></li>
						<?php } */ } ?>
						<?php if($this->showCQC == 1) { ?>
							<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/3">View CQC</a></li>
					  <?php } ?>
					  <?php if($this->showSafetyMonthlyAnalysis == 1) { ?>
							<?php if($this->hideAddSafetyMonthlyAnalysis == 0 && $this->addSafetyMonthlyAnalysis == 1){ ?><li><a href="/default/safety/addmonthlyanalysis">Add Monthly Analysis</a></li><?php } ?>						
							<li class="view-monthly-analysis"><a href="/default/safety/viewmonthlyanalysis">View Monthly Analysis</a></li>
					  <?php } ?>
					  <?php if($this->showSafetyPivotChart == 1) { ?>
						  <li><a href="/default/pivot/index/c/3">Pivot Chart</a></li>
					  <?php } ?>
					  <?php if($this->showCorporateSafetyPivotChart == 1) { ?>
						  <li><a href="/default/pivot/corporate/c/3">Corporate Pivot Chart</a></li>
					  <?php } ?>
					  <?php if($this->showSafetyKpi == 1) { ?>
						  <li><a href="/default/kpi/view/c/3">New KPI</a></li>
						  <li><a href="/default/kpi/viewmonthly/c/3">Monthly KPI</a></li>
					  <?php } ?>
					    <?php if($this->showSafetyBoard == 1) { ?>
						  <li><a href="/default/safety/viewsafetyboard">Safety Board</a></li>
						  <?php if($this->uploadSafetyBoard == 1) { ?>
						 	<li><a href="/default/safety/viewsafetyboardimages">Upload Safety Board</a></li>
					  <?php } } ?>
					  <?php if($this->showSafetyManPower == 1) { ?>
						  <li><a href="/default/manpower/view/c/3">Man Power</a></li>
					  <?php } ?>
                    </ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showParkingTraffic == 1 || $this->showParkingKpi == 1) { ?>
				  <li><a><i class="fa fa-car"></i> Parking &amp; Traffic</a>
					 <ul class="nav child_menu">
						<?php if($this->hideAddParking == 0 && $this->showAddParkingTraffic == 1) { ?>
							<li><a href="/default/parking/add">Add Daily Report</a></li>
						<?php } 
						if($this->showParkingTraffic == 1) {	
						?>
						<li class="edit-parking" style="display:none;"><a href="#">Edit Daily Report</a></li>
						<li><a href="/default/parking/viewreport">View Daily Report</a></li>
						<?php } if($this->showParkingActionPlan == 1) { ?>
						<li><a href="/default/actionplan/view/c/5"> Action Plan</a></li>
						<?php /* if($this->showActionPlanSetting == 1) { ?>
						<li><a href="/default/actionplan/module/c/5"> Action Plan Module</a></li>
						<li><a href="/default/actionplan/target/c/5"> Action Plan Target</a></li>
						<li><a href="/default/actionplan/activity/c/5"> Action Plan Activity</a></li>
						<li><a href="/default/actionplan/reminder/c/5"> Action Plan Reminder Email</a></li>
						<?php if($this->showReminderReview == 1) { ?>
							< ?php */ /*<li><a href="/default/actionplan/upcoming/c/5"> Action Plan Weekly Reminder</a></li>*/ /* ?>
							<li><a href="/default/actionplan/review/c/5"> Action Plan Review</a></li>
						<?php } ?>
						<li><a href="/default/actionplan/reschedulestatistic/c/5"> Action Plan Resechedule Statistic</a></li>
					  <?php } */ } ?>
					  <?php if($this->showCQC == 1) { ?>
							<li class="view-cqc"><a href="/default/actionplan/viewcqc/c/5">View CQC</a></li>
					  <?php } ?>
					  <?php if($this->showParkingMonthlyAnalysis == 1) { ?>
							<?php if($this->hideAddParkingMonthlyAnalysis == 0 && $this->addParkingMonthlyAnalysis == 1){ ?><li><a href="/default/parking/addmonthlyanalysis">Add Monthly Analysis</a></li><?php } ?>						
							<li class="view-monthly-analysis"><a href="/default/parking/viewmonthlyanalysis">View Monthly Analysis</a></li>
					  <?php } ?>
					  <?php if($this->showParkingPivotChart == 1) { ?>
						  <li><a href="/default/pivot/index/c/5">Pivot Chart</a></li>
					  <?php } ?>
					  <?php if($this->showCorporateParkingPivotChart == 1) { ?>
						  <li><a href="/default/pivot/corporate/c/5">Corporate Pivot Chart</a></li>
					  <?php } ?>
					  <?php if($this->showParkingKpi == 1) { ?>
						  <li><a href="/default/kpi/view/c/5">New KPI</a></li>
						  <li><a href="/default/kpi/viewmonthly/c/5">Monthly KPI</a></li>
					  <?php } ?>
					  <?php if($this->showParkingManPower == 1) { ?>
						  <li><a href="/default/manpower/view/c/5">Man Power</a></li>
					  <?php } ?>
                    </ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showHousekeeping == 1) { ?>
				  <li><a><i class="fa fa-recycle"></i> Housekeeping</a>
					 <ul class="nav child_menu">
						<?php if($this->hideAddHK == 0 && $this->showAddHousekeeping == 1) { ?><li><a href="/default/housekeeping/add">Add Daily Report</a></li><?php } ?>
						<li class="edit-housekeeping" style="display:none;"><a href="#">Edit Daily Report</a></li>
						<li><a href="/default/housekeeping/viewreport">View Daily Report</a></li>
						<?php if($this->showHousekeepingActionPlan == 1) { ?>
						<li><a href="/default/actionplan/view/c/2"> Action Plan</a></li>
						<?php /*if($this->showActionPlanSetting == 1) { ?>
						<li><a href="/default/actionplan/module/c/2"> Action Plan Module</a></li>
						<li><a href="/default/actionplan/target/c/2"> Action Plan Target</a></li>
						<li><a href="/default/actionplan/activity/c/2"> Action Plan Activity</a></li>
						<li><a href="/default/actionplan/reminder/c/2"> Action Plan Reminder Email</a></li>
						<?php if($this->showReminderReview == 1) { */ ?>
							<?php /*<li><a href="/default/actionplan/upcoming/c/2"> Action Plan Weekly Reminder</a></li>*/ /* ?>
							<li><a href="/default/actionplan/review/c/2"> Action Plan Review</a></li>
						<?php } ?>
						<li><a href="/default/actionplan/reschedulestatistic/c/2"> Action Plan Resechedule Statistic</a></li>
						<?php } */ ?>
					  <?php } ?>
					  <?php if($this->showHousekeepingManPower == 1) { ?>
						  <li><a href="/default/manpower/view/c/2">Man Power</a></li>
					  <?php } ?>
                    </ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showOM == 1) { ?>
				  <li><a><i class="fa fa-tablet"></i> Operational Mall</a>
					 <ul class="nav child_menu">
						<?php if($this->hideAddOM == 0 && $this->showAddOM == 1) { ?><li><a href="/default/operational/add">Add Daily Report</a></li><?php } ?>
						<li class="edit-om" style="display:none;"><a href="#">Edit Daily Report</a></li>
						<li><a href="/default/operational/viewreport">View Daily Report</a></li>
						<?php if($this->showOMActionPlan == 1) { ?>
						<li><a href="/default/actionplan/reschedulelist"> Action Plan Reschedule</a></li>
					  <?php } ?>
                    </ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showMod == 1) { ?>
				  <li><a><i class="fa fa-tags"></i> Manager On Duty</a>
					 	<ul class="nav child_menu">
							<?php if($this->hideAddMOD == 0 && $this->showAddMod == 1) { ?><li><a href="/default/mod/add">Add Report</a></li><?php } ?>
							<li class="edit-mod" style="display:none;"><a href="#">Edit Report</a></li>
							<li><a href="/default/mod/viewreport">View Report</a></li>
							<?php if($this->showMODSchedule == 1) { ?><li><a href="/default/mod/schedule">MOD Schedule</a></li><?php } ?>
							<?php if($this->showMODScheduleReport == 1) { ?><li><a href="/default/mod/schedulereport">MOD Schedule Report</a></li><?php } ?>
            </ul>
				  </li>
				  <?php }  ?>
				  <?php if($this->showBM == 1) { ?>
				  <li><a><i class="fa fa-building"></i> Building Manager</a>
					 <ul class="nav child_menu">
						<?php if($this->hideAddBM == 0 && $this->showAddBM == 1) { ?><li><a href="/default/bm/add">Add Report</a></li><?php } ?>
						<li class="edit-bm" style="display:none;"><a href="#">Edit Report</a></li>
						<li><a href="/default/bm/viewreport">View Report</a></li>
                    </ul>
				  </li>
				  <?php }  ?>
				<?php if($this->showHODMeeting == 1 || $this->showHODMeetingAdmin == 1) { ?>
					<li><a><i class="fa fa-hourglass"></i> BOD Meeting</a>
					 <ul class="nav child_menu">
						<?php if($this->showAddHOD == 1) { ?><li><a href="/default/hod/add">Add BOD Meeting</a></li><?php } ?>
						<li class="edit-hod" style="display:none;"><a href="#">Edit BOD Meeting</a></li>
						<li><a href="/default/hod/view">View BOD Meeting</a></li>
						<?php if($this->showHistoryHOD == 1) { ?><li><a href="/default/hod/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>
				<?php if($this->showITMeeting == 1 || $this->showITMeetingAdmin == 1) { ?>
					<li><a><i class="fa fa-hourglass"></i> IT Meeting</a>
					 <ul class="nav child_menu">
						<?php if($this->showAddITMeeting == 1) { ?><li><a href="/default/it/add">Add IT Meeting</a></li><?php } ?>
						<li class="edit-it" style="display:none;"><a href="#">Edit IT Meeting</a></li>
						<li><a href="/default/it/view">View IT Meeting</a></li>
						<?php if($this->showHistoryITMeeting == 1) { ?><li><a href="/default/it/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>
				<?php if($this->showBSMeeting == 1 || $this->showBSMeetingAdmin == 1) { ?>
					<li><a><i class="fa fa-hourglass"></i> BS Meeting</a>
					 <ul class="nav child_menu">
						<?php if($this->showAddBSMeeting == 1) { ?><li><a href="/default/bs/add">Add BS Meeting</a></li><?php } ?>
						<li class="edit-bs" style="display:none;"><a href="#">Edit BS Meeting</a></li>
						<li><a href="/default/bs/view">View BS Meeting</a></li>
						<?php if($this->showHistoryBSMeeting == 1) { ?><li><a href="/default/bs/history">Project History</a></li><?php } ?>
                    </ul>
				  </li>
				<?php }  ?>
				<?php if($this->showStatistic == 1 || $this->showActionPlanStat == 1) { ?>
				  <li><a><i class="fa fa-bar-chart"></i> Statistic</a>
					 <ul class="nav child_menu">
					 	<?php if($this->showStatistic == 1) { ?>
							<li><a href="/default/statistic/view">Issue Statistic</a></li>
							<?php /*<li><a href="/default/statistic/user">User Statistic</a></li> */ ?>
							<li><a href="/default/statistic/site">Site Statistic</a></li>
							<li><a href="/default/statistic/corporate">Corporate Statistic</a></li>
						<?php } ?>
						<?php if($this->showActionPlanStat == 1) { ?>
							<li><a href="/default/statistic/actionplan">Action Plan Statistic</a></li>
						<?php } ?>
            </ul>
				  </li>
				  <?php }  ?>

                <?php /*
				  <li><a><i class="fa fa-envelope"></i> Feedback</a>
				  	<ul class="nav child_menu">
						<li><a href="/default/feedback/viewform">Send Feedback</a></li>
						<?php if($this->viewFeedbackInbox == 1) { ?><li><a href="/default/feedback/inbox">Feedback Inbox</a></li><?php } ?>
                    </ul>
				  </li>
				  */ ?>
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
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
              </div>
			<h1>Our SRT</h1>
			<div class="logout">
                  <a href="/default/user/logout"><img src="/images/newlogo_logoff.png" /></a>
				  <br/>
				  <a id="change-passwd-sm" href="/default/user/changepassword" style="color:#7cd202"><i class="fa fa-lock"></i></a>
				   <?php /* if($this->ident['user_id'] == 1 || $this->ident['user_id'] == 3) { ?><a href="<?php echo $_SERVER['REQUEST_URI']; ?>"><img src="/images/refresh.png" style="width: 20px;" /></a><?php } */ ?>
              </div>
			<div class="profile_info_mobile">
				<div>
					Welcome <?php echo $this->ident['name']; ?>,<br/>
					Sites: <?php echo $this->ident['site_name']; ?><br/>
				</div>
			</div>
            </nav>
          </div>
        </div>
        <!-- /top navigation -->

		<!-- page content -->
        <div class="right_col" role="main">
			<?php if($this->showSiteSelection == 1) { ?>
				<div class="site-selection">
					Select Site &nbsp; &nbsp; <select id="site-menu" name="site_id" style="width:90%;">
					<?php foreach($this->sitesSelections as $siteSelection) { ?>
						<option value="<?php echo $siteSelection['site_id']; ?>" <?php if($this->ident['site_id'] == $siteSelection['site_id']) echo 'selected="selected"'; ?> ><?php echo $siteSelection['site_name']; ?></option>
						<?php } ?>
					</select>
				</div>
			<?php } ?>