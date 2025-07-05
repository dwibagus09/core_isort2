<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div style="margin-bottom:10px;">
				<h2>Action Plan</h2>
				<a class="add-schedule" href="#popup-form"><input type="button" value="Add Schedule" style="width:100px;"></a>
				
				<div style="margin-top:10px;">
				<table id="action-plan-calendar-layout">
					<tr>
						<td>
							<table id="action-plan-activity">
								<tr>
									<th height="90">No</th>
									<th width="150">Target</th>
									<th width="300">Activity</th>
								</tr>
								<?php if(!empty($this->schedule)) { 
									foreach($this->schedule as $schedule){ 
								?>
									<tr>
										<td height="50" colspan="3"><h5><?php echo $schedule['module_name']; ?></h5></td>
									</tr>
									<?php 
										if(!empty($schedule['target'])) {
											$i=1; foreach($schedule['target'] as $target) { 
												if(!empty($target['activity'])) {
													$j = 0; foreach($target['activity'] as $activity) {
												?>
												<tr height="50">
													<?php if($j==0) { ?>
													<td rowspan="<?php echo count($target['activity']); ?>"><?php echo $i; ?></td>
													<td rowspan="<?php echo count($target['activity']); ?>"><?php echo $target['target_name']; ?></td>
													<?php } ?>
													<td><?php echo $activity['activity_name']. ' <span style="color:#'.$this->site1['action_plan_color'].'; font-weight:bold; line-height:16px;">(' . round($activity['percentage1'],2)."%)</span> ". ' <span style="color:#'.$this->site2['action_plan_color'].'; font-weight:bold; line-height:16px;">(' . round($activity['percentage2'],2)."%)</span> "; ?></td>
												</tr>
												<?php $j++; } 
												}		
												$i++;
											}
										}
									} 
								} ?>
							</table>
						</td>
						<td>
							<div id="calendar-layout">
								<?php if(!empty($this->calendar)) { ?> 
								<table id="action-plan-calendar">							
									<tr>
										<?php foreach($this->calendar as $calendar) { ?>
											<th height="30" colspan="<?php echo ($calendar['no_of_weeks']*2); ?>"><?php echo $calendar['month_name']; ?></th>
										<?php }?>
									</tr>
									<tr>
										<?php $j=0; foreach($this->calendar as $calendar) {
											for($w=1; $w<=$calendar['no_of_weeks'];$w++) { ?>
											<th height="30" colspan="2"><?php echo $w; ?></th>
										<?php $j++; }
											$col = $j;
										} ?>								
									</tr>
									<tr>
										<?php for($x=0;$x<$j; $x++)
										{ ?>
											<th height="30" style="background-color:#<?php echo $this->site1['action_plan_color']; ?>"><?php echo $this->site1['initial']; ?></th>
											<th style="background-color:#<?php echo $this->site2['action_plan_color']; ?>"><?php echo $this->site2['initial']; ?></th>
										<?php } ?>
									</tr>
									<?php if(!empty($this->schedule)) { 
										foreach($this->schedule as $schedule){ ?>
											<tr height="50">
												<td colspan="<?php echo ($col*2); ?>"></td>
											</tr>	
											<?php if(!empty($schedule['target'])) {
												$i=1; 
												foreach($schedule['target'] as $target) { 
													if(!empty($target['activity'])) {
														$j = 0; 
														foreach($target['activity'] as $activity) { ?>
															<tr height="50">
															<?php foreach($activity['month'] as $month) {
																foreach($month as $week) {
																?>
																		<td style="background-color:#<?php echo $this->site1['action_plan_color']; ?>"><a class="schedule-date" data-id="<?php echo $week['site1_schedule_id']; ?>" data-site-id="<?php echo $week['site1_site_id']; ?>" href="#schedule-form"><?php echo $week['site1']; ?></a></td>
																		<td style="background-color:#<?php echo $this->site2['action_plan_color']; ?>"><a class="schedule-date" data-id="<?php echo $week['site2_schedule_id']; ?>" data-site-id="<?php echo $week['site2_site_id']; ?>" href="#schedule-form"><?php echo $week['site2']; ?></a></td>
																<?php
																}
															} ?>
															</tr>	
														<?php }
													}
												}
											}
										}
									}
									?>
									
								</table>
								<?php } ?>
							</div>
						</td>
					</tr>
				</table>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->

<!-- Add Schedule form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Module</label><br/>
	<select id="action_plan_module_id" name="action_plan_module_id">
		<option value="">- Select Module -</option>
		<?php if(!empty($this->modules)) { foreach($this->modules as $module) { ?>
			<option value="<?php echo $module['action_plan_module_id']; ?>"><?php echo $module['module_name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<label for="name">Target</label><br/>
	<select id="action_plan_target_id" name="action_plan_target_id"></select><br/><br/>
	<label for="name">Activity</label><br/>
	<select id="action_plan_activity_id" name="action_plan_activity_id"></select><br/><br/>
	<label for="name">Date</label> <a class="add-date" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a><br/>
	<div id="list-date"><input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;"></div><br/>
	<input type="submit" class="submit-btn" id="add-target-submit" name="add-target-submit" value="Submit">
  </form>
  
<!-- Done / Reschedule Form -->
  <form action="/default/actionplan/updatestatusschedule" id="schedule-form" method="POST" class="mfp-hide white-popup-block" enctype="multipart/form-data">
	<h2 id="schedule-form-title"></h2>
	<div id="status-form">
		<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id2" />
		<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
		<input type="hidden" name="original_date" id="original_date" /><br/>
		<input type="radio" name="update_status_schedule" id="uploadattachment" value="done"> Upload Attachment<br>
		<input type="file" name="attachment" id="attachment" disabled><br/>
		<input type="radio" name="update_status_schedule" id="reschedule" value="reschedule"> Reschedule<br>
		<div id="list-date"><input type="text" name="schedule_date" id="schedule_date" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;" disabled></div><br/>
		<input type="submit" class="submit-btn" id="add-target-submit" name="add-target-submit" value="Submit">
	</div>
	<div id="schedule-info"></div>
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var report_date;
	
	$('.add-schedule').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_name',
		callbacks: {
			open: function() {
				$(".add-date").click(function() {
					var row;
					row = '<input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;">';
					$( "#list-date").append(row);
					$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
				});
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_target_id").val("");
			}
		}
	});
	
	$(".add-schedule").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit Schedule");
			$.ajax({
				url: "/default/actionplan/getschedulebyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#action_plan_target_id").val(obj.action_plan_target_id);
				$("#action_plan_module_id").val(obj.action_plan_module_id);
				$("#target_name").val(obj.target_name);
				$("#sort_order").val(obj.sort_order);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Schedule");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/saveschedule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/view/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$('#action_plan_module_id').on('change', function(event){
		$.ajax({
			url: '/default/actionplan/gettargetbymoduleid',
			data: { mid : $('#action_plan_module_id').val() },
			success: function(response) {
				var target = jQuery.parseJSON(response);
				$("#action_plan_target_id").empty();
				$("#action_plan_target_id").append($("<option></option>").val("").html("- Select Target -"));
				$.each(target, function (key,value) {
					$("#action_plan_target_id").append($("<option></option>").val(value.action_plan_target_id).html(value.target_name));
				});
			}
		});
	});
	
	$('#action_plan_target_id').on('change', function(event){
		$.ajax({
			url: '/default/actionplan/getactivitybytargetid',
			data: { tid : $('#action_plan_target_id').val() },
			success: function(response) {
				var target = jQuery.parseJSON(response);
				$("#action_plan_activity_id").empty();
				$("#action_plan_activity_id").append($("<option></option>").val("").html("- Select Activity -"));
				$.each(target, function (key,value) {
					$("#action_plan_activity_id").append($("<option></option>").val(value.action_plan_activity_id).html(value.activity_name));
				});
			}
		});
	});
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('.schedule-date').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_name',
		callbacks: {
			open: function() {
				$("#uploadattachment").click(function(event){
				   $('#attachment').prop("disabled", false);
				   $('#schedule_date').prop("disabled", true);
				});
				$("#reschedule").click(function(event){
				   $('#attachment').prop("disabled", true); 
				   $('#schedule_date').prop("disabled", false);
				});
			},
			close: function() {	
				$('#schedule-form')[0].reset();
				$("#action_plan_target_id").val("");
			}
		}
	});
	
	$(".schedule-date").click(function() {
		var id = this.dataset.id;
		var site_id = this.dataset.site-id;
		$( "#form-title" ).html("Edit Schedule");
		$.ajax({
			url: "/default/actionplan/getschedulebyid",
			data: { id : id, site_id:site_id }
		}).done(function(response) {
			console.log(response);
			var schedule = jQuery.parseJSON(response);			
			$( "#schedule-form-title" ).html(schedule.initial+" - "+schedule.activity_name+" - "+schedule.date );
			if(schedule.status == 1)
			{
				$("#status-form").hide();
				$("#schedule-info").show();
				$.ajax({
					url: "/default/actionplan/getattachmentbyscheduleid",
					data: { id : id, category_id : <?php echo $this->category_id; ?> }
				}).done(function(resp) {
					$( "#schedule-info" ).html(resp);
				});
			}
			else if(schedule.status == 2)
			{
				$("#status-form").hide();
				$("#schedule-info").show();
				$( "#schedule-info" ).html('<span style="color:red">Reschedule to '+schedule.reschedule+'. Need to be approved by OM.</span>');
			}
			else
			{
				$("#status-form").show();
				$("#schedule-info").hide();
				$("#action_plan_schedule_id2").val(schedule.schedule_id); 
				$("#original_date").val(schedule.schedule_date); 
			}
			
		});	
	});
	
	$('#schedule-form').on('submit', function(event){
		$("body").mLoading();
	});
	
});
</script>