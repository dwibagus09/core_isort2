 <script type="text/javascript">
$(document).ready(function() {
	$("#issue-form")[0].reset();
	$("#other-info").hide();

	function filePreview(input) {
			if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.addEventListener('load', function() {
				$(".dashboard").hide();
				/*$("#discussion-field").hide();*/
				$("#other-info").show();
				$("<img />", {
					"src": reader.result,
									"class": "thumb-image"
							}).appendTo("#image-holder");
			});
					reader.readAsDataURL(input.files[0]);
			}
	}

	$( "#picture-issue" ).change(function() {
		$(".msg").hide();
		filePreview(this);
	});

	$( "#cancel-issue" ).click(function() {
		location.href="/default/index/index";
	});

	$('#issue-form').on('submit', function(event){
		$("body").mLoading();
	});

	/*$( "#location-next" ).click(function() {
		$("#location-field").hide();
		$("#discussion-field").show();
	});*/
});
</script>

			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
			<form id="issue-form" action=""  method="post" enctype="multipart/form-data">
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  <div class="dashboard">				  
				  	<?php if($this->showIssueFinding == 1) { ?>
						<div id="issue-finding-field" class="col-md-1 col-sm-3 col-xs-4">	
							<div class="menu-icon">
								<label for="picture-issue">
									<div class="icon-img"><img src="/images/newlogo_issue_finding.png" /></div>
									<div class="icon-title">See it?<br/>Say it!</div>
								</label>
								<input id="picture-issue" name="picture" type="file" accept="image/*" capture="capture" />		
							</div>
						</div>

						<?php if($this->showSiteSelection == 1) { ?>
							<div class="col-md-1 col-sm-3 col-xs-4">	
								<div class="menu-icon">
									<a href="/default/index/openedissuesdashboard">
										<div class="icon-img"><img src="/images/issue_icon.png" /></div>
										<div class="icon-title">Opened Issues</div>
									</a>		
								</div>
							</div>
						<?php } else { ?>
							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalSecIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalSecIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/1">
										<div class="icon-img"><img src="/images/security_opened_issues.png" /></div>
										<div class="icon-title">Security<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>		

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<?php if($this->totalSafIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalSafIssues['total']; ?></span><?php } ?>
									<a href="/default/issue/listissues/category/3">
										<div class="icon-img"><img src="/images/safety_opened_issues.png" /></div>
										<div class="icon-title">Safety<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>

							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalParkIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalParkIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/5">
										<div class="icon-img"><img src="/images/parking_opened_issues.png" /></div>
										<div class="icon-title">Parking &amp; Traffic<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>

							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalHKIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalHKIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/2">
										<div class="icon-img"><img src="/images/housekeeping_opened_issues.png" /></div>
										<div class="icon-title">Housekeeping<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalEngIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalEngIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/6">
										<div class="icon-img"><img src="/images/engineering_opened_issues.png" /></div>
										<div class="icon-title">Engineering<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalBSIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalBSIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/10">
										<div class="icon-img"><img src="/images/bs_opened_issues.png" /></div>
										<div class="icon-title">Building Service<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<?php if($this->totalTRIssues['total'] > 0) { ?><span class="notify-bubble"><?php echo $this->totalTRIssues['total']; ?></span><?php } ?>
								<div class="menu-icon">
									<a href="/default/issue/listissues/category/11">
										<div class="icon-img"><img src="/images/tr_opened_issues.png" /></div>
										<div class="icon-title">Tenant Relation<br/>Opened&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	
						<?php /*
							<div class="col-md-1 col-sm-3 col-xs-4">						
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/1">
										<div class="icon-img"><img src="/images/security_closed_issues.png" /></div>
										<div class="icon-title">Security<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>		

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/3">
										<div class="icon-img"><img src="/images/safety_closed_issues.png" /></div>
										<div class="icon-title">Safety<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/5">
										<div class="icon-img"><img src="/images/parking_closed_issues.png" /></div>
										<div class="icon-title">Parking &amp; Traffic<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/2">
										<div class="icon-img"><img src="/images/housekeeping_closed_issues.png" /></div>
										<div class="icon-title">Housekeeping<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/6">
										<div class="icon-img"><img src="/images/engineering_closed_issues.png" /></div>
										<div class="icon-title">Engineering<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/10">
										<div class="icon-img"><img src="/images/bs_closed_issues.png" /></div>
										<div class="icon-title">Building Service<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	

							<div class="col-md-1 col-sm-3 col-xs-4">
								<div class="menu-icon">
									<a href="/default/issue/solvedissues/category/11">
										<div class="icon-img"><img src="/images/tr_closed_issues.png" /></div>
										<div class="icon-title">Tenant Relation<br/>Closed&nbsp;Issues&nbsp;</div>
									</a>	
								</div>
							</div>	
						<?php */ } } ?>

					<?php if($this->showSiteSelection == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<div class="menu-icon">
								<a href="/default/index/reportdashboard">
									<div class="icon-img"><img src="/images/report_icon2.png" /></div>
									<div class="icon-title">Daily Report</div>
								</a>		
							</div>
						</div>
					<?php } else { ?>
						<?php if($this->showSecurity == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">
							<img id="security-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showSecurityStarNotif) { echo 'style="display:none;"'; } ?> />	
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									if($this->showChiefSecurity != 1) $sec_url = 'view'; 
									else $sec_url = 'viewchiefreport';
								}
								else {
									$sec_url = "viewchiefdetailreport/dt/".date("Y-m-d");
								} ?>
								<a href="/default/security/<?php echo $sec_url; ?>"  data-d="security">
									<div class="icon-img"><img id="security-icon" src="/images/security_report2.png" /></div>
									<div class="icon-title">Security Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showSafety == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="safety-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showSafetyStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$saf_url = 'viewreport';
								}
								else {
									if(empty($this->safetyReport['report_id'])) $saf_url = "viewreport";
									else $saf_url = "viewdetailreport/id/".$this->safetyReport['report_id'];
								} ?>
								<a href="/default/safety/<?php echo $saf_url; ?>"  data-d="safety">
									<div class="icon-img"><img id="security-icon" src="/images/safety_report2.png" /></div>
									<div class="icon-title">Safety Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showParkingTraffic == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="parking-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showParkingStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$park_url = 'viewreport';
								}
								else {
									if(empty($this->parkingReport['parking_report_id'])) $park_url = "viewreport";
									else $park_url = "viewdetailreport/id/".$this->parkingReport['parking_report_id'];
								} ?>
								<a href="/default/parking/<?php echo $park_url; ?>"  data-d="parking">
									<div class="icon-img"><img id="parking-icon" src="/images/parking_report2.png" /></div>
									<div class="icon-title">Parking &amp; Traffic Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showHousekeeping == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="hk-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showHousekeepingStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$hk_url = 'viewreport';
								}
								else {
									if(empty($this->housekeepingReport['housekeeping_report_id'])) $hk_url = "viewreport";
									else $hk_url = "viewdetailreport/id/".$this->housekeepingReport['housekeeping_report_id'];
								} ?>
								<a href="/default/housekeeping/<?php echo $hk_url; ?>"  data-d="hk">
									<div class="icon-img"><img id="housekeeping-icon" src="/images/housekeeping_report2.png" /></div>
									<div class="icon-title">Housekeeping Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showOM == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="om-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showOMStarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$om_url = 'viewreport';
								}
								else {
									if(empty($this->omReport['operation_mall_report_id'])) $om_url = "viewreport";
									else $om_url = "viewdetailreport/id/".$this->omReport['operation_mall_report_id'];
								} ?>
								<a href="/default/operational/<?php echo $om_url; ?>"  data-d="om" >
									<div class="icon-img"><img id="operational-mal-icon" src="/images/om_report2.png" /></div>
									<div class="icon-title">Operational Mall Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showMod == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<img id="mod-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showMODstarNotif) { echo 'style="display:none;"'; } ?> />
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$mod_url = 'viewreport';
								}
								else {
									if(empty($this->modReport['mod_report_id'])) $mod_url = "viewreport";
									else $mod_url = "viewdetailreport/id/".$this->modReport['mod_report_id'];
								} ?>
								<a href="/default/mod/<?php echo $mod_url; ?>"  data-d="mod">
									<div class="icon-img"><img id="mod-icon" src="/images/mod_report2.png" /></div>
									<div class="icon-title">Manager On Duty Report</div>	
								</a>
							</div>
						</div>	
						<?php } ?>
						<?php if($this->showBM == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">
							<img id="bm-star" class="unread-flag" src="/images/star2.png" <?php if(!$this->showBMStarNotif) { echo 'style="display:none;"'; } ?> />	
							<div class="menu-icon">
								<?php if($this->isMobile == true) {
									$bm_url = 'viewreport';
								}
								else {
									if(empty($this->bmReport['report_id'])) $bm_url = "viewreport";
									else $bm_url = "exporttopdf/id/".$this->bmReport['report_id'];
								} ?>
								<a href="/default/bm/<?php echo $bm_url; ?>" data-d="bm" <?php if($this->isMobile == false && !empty($this->bmReport['report_id'])) echo 'target="_blank"'; ?>>
									<div class="icon-img"><img id="bm-icon" src="/images/bm_report2.png" /></div>
									<div class="icon-title">Building Manager Report</div>	
								</a>
							</div>
						</div>	
					<?php } } ?>

					<?php if($this->showStatistic == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<div class="menu-icon">
								<a href="/default/index/statisticdashboard">
									<div class="icon-img"><img src="/images/graphic.png" /></div>
									<div class="icon-title">Statistic</div>
								</a>		
							</div>
						</div>
					<?php } ?>

					<?php if($this->showSecurityKpi == 1 || $this->showSafetyKpi == 1 || $this->showParkingKpi == 1) { ?>
						<div class="col-md-1 col-sm-3 col-xs-4">	
							<div class="menu-icon">
								<a href="/default/index/kpidashboard">
									<div class="icon-img"><img src="/images/speedometer2.png" /></div>
									<div class="icon-title">KPI</div>
								</a>		
							</div>
						</div>
					<?php } ?>
					<div class="clearfix"></div>
				  </div>
				  
				  <div id="other-info">
					<div id="image-holder"></div>
					<?php if(!empty($this->categories)) { ?>
					<div id="category-field">
						Category:<br/>
						<select id="category-select" name="category" required>
						<option value="" disabled selected hidden>Select Department</option>
						<?php foreach($this->categories as $category) { ?>
						  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
						 <?php } ?>
						</select><br/>
						<?php /*<input type="button" id="cancel-issue" name="cancel-issue" value="Cancel" /> <input type="button" id="category-next" name="category-next" value="Next" /> */ ?>
					</div>
					<?php } ?>
					<div id="type-field">
						Type:<br/>
						<select id="type-select" name="type_id" required>
							<option value="" disabled selected hidden>Select Issue Type</option>
						</select>
					</div>
					<div id="incident-field">
						Incident:<br/>
						<select id="incident-select" name="incident_id">
							<option value="" disabled selected hidden>Select Incident</option>
						</select>
					</div>
					<div id="modus-field">
						Modus:<br/>
						<select id="modus-select" name="modus_id">
							<option value="" disabled selected hidden>Select Modus</option>
						</select>
					</div>
					<div id="manpower-field">
						Man Power:<br/>
						<input id="manpower-text" name="manpower_id" type="text" autocomplete="off" required>
					</div>
					<div id="floor-field">
						Floor:<br/>
						<select id="floor-select" name="floor_id">
							<option value="" disabled selected hidden>Select Floor</option>
						</select>
					</div>
					<div id="tenant-public-field">
						<select id="tenant-public-select" name="tenant_public">
							<option value="" disabled selected hidden>Select Tenant/Public</option>
							<option value="0">Tenant</option>
							<option value="1">Public</option>
						</select>
					</div>
					<div id="location-field">
						Location Detail:<br/>
						<textarea rows="2" cols="50" id="location-txtarea" name="location" required></textarea><br/>
					</div>
					<div id="discussion-field">
						Discussion:<br/>
						<textarea rows="4" cols="50" id="discussion-txtarea" name="description" required></textarea><br/>
						<!--<input type="radio" name="sendwa" value="4" checked> Send Anonymous Notification<br>-->
						<!--<input type="radio" name="sendwa" value="1" checked> Send WhatsApp to Chief/Manager<br>-->
						<!--<input type="radio" name="sendwa" value="2"> Send WhatsApp to Group/Contact List<br>-->
						<!--<input type="radio" name="sendwa" value="3"> Do not send Notification<br/><br/>-->
					</div>	
					<div id="pelaku-tertangkap-field" style="display:none;">
						<input type="checkbox" name="pelaku_tertangkap" value="1"> Pelaku Tertangkap
					</div>
					<div id="button-field">
						<input type="button" id="cancel-issue" name="cancel-issue" value="Cancel" /> <input type="submit" id="issue-submit" name="issue-submit" value="Submit">
					</div>
					<br/><br/>
				  </div>
				</div>
			</form>
          </div>
          <br /> 
		  

        </div>
        <!-- /page content -->

<link rel="stylesheet" href="/css/jquery-ui.min.css">
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$(".menu-icon a").click(function() {
		$("#"+this.dataset.d+"-star").css( "display", "none" );
	});

	$("#incident-field").hide();
	$("#modus-field").hide();
	$("#manpower-field").hide();
	$("#floor-field").hide();
	$("#tenant-public-field").hide();
	$("#manpower-text").prop('required',false);

	$("#category-select").change(function() {
		var cat_id = $( this ).val();
		/*if(cat_id == 1 || cat_id == 2 || cat_id == 3 || cat_id == 5 || cat_id == 6)
		{*/
			$('body').mLoading();
			$("#floor-field").show();
			$.ajax({
				url: "/default/issue/getissuetypeandfloorbycatid",
				data: { category_id :  cat_id }
			}).done(function(response) {
				var object = $.parseJSON(response);

				$("#type-select").empty();
				$("#type-select").append('<option value=""  disabled selected hidden>Select Issue Type</option>');
				$.each(object.issue_type, function (item, value) {
					$("#type-select").append(new Option(value.issue_type, value.issue_type_id));
				});

				$("#floor-select").empty();
				$("#floor-select").append('<option value=""  disabled selected hidden>Select Floor</option>');
				$.each(object.floor, function (id, val) {
					$("#floor-select").append(new Option(val.floor, val.floor_id));
				});
				$("#floor-select").prop('required',true);
				$("#incident-field").hide();
				$("#modus-field").hide();
				$("#incident-select").prop('required',false);
				$("#modus-select").prop('required',false);
				$("body").mLoading('hide');
			});
		/*}
		else {			
			$('body').mLoading();
			$.ajax({
				url: "/default/issue/getissuetypebycatid",
				data: { category_id :  cat_id }
			}).done(function(response) {
				var object = $.parseJSON(response);

				$("#type-select").empty();
				$("#type-select").append('<option value=""  disabled selected hidden>Select Issue Type</option>');
				$.each(object, function (item, value) {
					$("#type-select").append(new Option(value.issue_type, value.issue_type_id));
				});
				$("body").mLoading('hide');
			});
			$("#floor-field").hide();
			$("#incident-field").hide();
			$("#modus-field").hide();
			$("#floor-select").prop('required',false);
			$("#incident-select").prop('required',false);
			$("#modus-select").prop('required',false);
		}*/
		$("#pelaku-tertangkap-field").hide();
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	var curIncidents = [];
	$("#type-select").change(function() {
		var cat_id = $("#category-select").val();
		if(/*(cat_id == 1 || cat_id == 2 || cat_id == 3 || cat_id == 5 || cat_id == 6) &&*/$( this ).val() > 0)
		{
			$('body').mLoading();
			$.ajax({
				url: "/default/issue/getincidentbyissuetypeid",
				data: { issue_type : $( this ).val(), category_id: cat_id  }
			}).done(function(response) {
				curIncidents = [];
				if(response == "[]")
				{
					$("#incident-field").hide();
					$("#modus-field").hide();
					$("#incident-select").prop('required',false);
					$("#modus-select").prop('required',false);
				} else {
					$("#incident-field").show();
					$("#incident-select").prop('required',true);
					$("#incident-select").empty();
					var object = $.parseJSON(response);
					$("#incident-select").append('<option value=""  disabled selected hidden>Select Incident</option>');
					$.each(object, function (item, value) {
						$("#incident-select").append(new Option(value.kejadian, value.kejadian_id));
						curIncidents[value.kejadian_id] = value.show_pelaku_checkbox;
					});
				}
				$("body").mLoading('hide');
			});
		}
		$("#pelaku-tertangkap-field").hide();
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	$("#incident-select").change(function() {
		if($( this ).val() > 0)
		{			
			$('body').mLoading();
			if(curIncidents[$( this ).val()] == "1")
			{
				$("#pelaku-tertangkap-field").show();
			}
			else
			{
				$("#pelaku-tertangkap-field").hide();
			}
			$("#modus-field").show();
			$("#modus-select").prop('required',true);
			$.ajax({
				url: "/default/issue/getmodusbykejadianid",
				data: { kejadian_id : $( this ).val(), category_id: $("#category-select").val()  }
			}).done(function(response) {
				$("#modus-select").empty();
				var object = $.parseJSON(response);
				$("#modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
				$.each(object, function (item, value) {
					$("#modus-select").append(new Option(value.modus, value.modus_id));
				});
				$("body").mLoading('hide');
			});
		}
		else
		{
			$("#modus-select").prop('required',false);
		}
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	$("#modus-select").change(function() {
		if(($("#category-select").val() == 1 &&  $("#incident-select").val() >= 50 && $("#incident-select").val() <= 57) || ($("#category-select").val() == 2 &&  ($("#incident-select").val() == 5 || ($("#incident-select").val() >= 13 && $("#incident-select").val() <= 19))) ||  ($("#category-select").val() == 3 &&  ($("#incident-select").val() == 112 || ($("#incident-select").val() >= 136 && $("#incident-select").val() <= 142))) || ($("#category-select").val() == 5 &&  $("#incident-select").val() >= 44 && $("#incident-select").val() <= 51))
		{
			var modusid = $( this ).val();
			var categoryid = $("#category-select").val();
			$("#manpower-field").show();
			$('#manpower-text').val('');
			$("#manpower-text").prop('required',true);
			$( "#manpower-text" ).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url: "/default/manpower/getmanpowerbykeyword",
						dataType: "json",
						data: {
							q: request.term, 
							m: modusid,
							c: categoryid
						},
						success: function( data ) {
							response( data );
						}
					});
				}
			});
		}
		else{
			$("#manpower-field").hide();			
			$('#manpower-text').val('');
			$("#manpower-text").prop('required',false);
		}
	});

	$("#floor-select").change(function() {
		if($( this ).val() > 0)
		{
			$("#tenant-public-field").show();
			$("#tenant-public-select").prop('required',true);
		}
		else
		{
			$("#tenant-public-select").prop('required',false);
		}
	});

	$('#issue-form').on('submit', function(event){
		event.preventDefault();
		$("body").mLoading();
		if($("#manpower-field").is(":visible") === true)
		{
			var c = $("#category-select").val();
			var name =  $("#manpower-text").val();
			var m =  $("#modus-select").val();
			$.ajax({
				url: "/default/manpower/getmanpowerbyname",
				dataType: "json",
				data: {
					name: name, 
					c: c,
					m: m
				},
				success: function( data ) {
					if(data === false)
					{
						$("body").mLoading('hide');
						alert("Data Man Power tidak terdapat di list, mohon di perbaiki");
					}
					else
					{
						$.ajax({
							url: '/default/issue/submitissue',
							type: 'POST',
							data: new FormData($('form')[0]),
							cache: false,
							contentType: false,
							processData: false,

							xhr: function () {
								var myXhr = $.ajaxSettings.xhr();
								if (myXhr.upload) {
									myXhr.upload.addEventListener('progress', function (e) {
									if (e.lengthComputable) {
										$('progress').attr({
										value: e.loaded,
										max: e.total
										});
									}
									}, false);
								}
								return myXhr;
							},
							success: function(response) {
								if(response === "1")
								{
									location.href="/default/issue/listissues";
								}
								else {
									location.href="/default/index/index/err/1";
								}
							}
						});
					}
				}
			});
		}
		else
		{
			$.ajax({
				url: '/default/issue/submitissue',
				type: 'POST',
				data: new FormData($('form')[0]),
				cache: false,
				contentType: false,
				processData: false,

				xhr: function () {
					var myXhr = $.ajaxSettings.xhr();
					if (myXhr.upload) {
						myXhr.upload.addEventListener('progress', function (e) {
						if (e.lengthComputable) {
							$('progress').attr({
							value: e.loaded,
							max: e.total
							});
						}
						}, false);
					}
					return myXhr;
				},
				success: function(response) {
					if(response === "1")
					{
						location.href="/default/issue/listissues";
					}
					else {
						location.href="/default/index/index/err/1";
					}
				}
			});
		}
	});
});	
</script>

