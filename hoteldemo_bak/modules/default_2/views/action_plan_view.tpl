<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div style="margin-bottom:10px;">
				<h2 class="pagetitle"><?php if($this->category_id==6) echo $this->ident['initial']." - Preventive Maintenance ". $this->selectedYear; else echo $this->ident['initial']." - Action Plan ". $this->selectedYear; ?></h2>
				<a class="add-schedule" href="#popup-form"><input type="button" value="Add Schedule" style="width:110px;"></a>
				
				<div id="pie-graph">
					<img id="exporttopdf" src="/images/newlogo_pdf.png" width="24" style="float: left; cursor:pointer;" />
					<div class="ap-individual-chart">
						<canvas id="chart-area"></canvas>
					</div>
				</div>
				
				<div style="margin-top:10px;">
				<table id="action-plan-calendar-layout" width="1077">
					<tr>
						<td width="550">
							<a href="/default/actionplan/view/c/<?php echo $this->category_id; ?>/y/<?php echo ($this->selectedYear-1); ?>" class="year-paging">&laquo; <?php echo ($this->selectedYear-1); ?></a><br style="line-height:30px"/>
							<table class="action-plan-activity" width="550">
								<tr>
									<th width="40" height="60">No</th>
									<th width="160">Target</th>
									<th width="350">Activity</th>
								</tr>
							</table>
						</td>
						<td>
							<a href="/default/actionplan/view/c/<?php echo $this->category_id; ?>/y/<?php echo ($this->selectedYear+1); ?>" class="year-paging" style="float:right"><?php echo ($this->selectedYear+1); ?> &raquo;</a><br style="line-height:30px"/>	
							<div id="calendar-layout" width="510">
								<?php if(!empty($this->calendar)) { ?> 
									<table class="action-plan-calendar" width="<?php echo (30*$this->weekTotal); ?>">							
										<tr>
											<?php foreach($this->calendar as $calendar) { ?>
												<th height="30" width="30" colspan="<?php echo ($calendar['no_of_weeks']); ?>"><?php echo $calendar['month_name']; ?></th>
											<?php }?>
										</tr>
										<tr>
											<?php $j=0; foreach($this->calendar as $calendar) {
												for($w=1; $w<=$calendar['no_of_weeks'];$w++) { ?>
												<th height="30" width="30"><?php echo $w; ?></th>
											<?php $j++; }
												$col = $j;
											} ?>								
										</tr>
									</table>
								<?php } ?>
							</div>
						</td>
					</tr>
					<tr>
						<td>
							<div id="activity-list" style="height:550px; overflow:hidden;">
							<table class="action-plan-activity">
								<?php if(!empty($this->schedule)) { 
									$cur_module_id = 0;
									$cur_target_id = 0;
									$i = 0;
									foreach($this->schedule as $schedule){ 
										if($schedule['action_plan_module_id'] != $cur_module_id)
										{
											$cur_module_id = $schedule['action_plan_module_id'];
								?>
									<tr>
										<td width="550" height="65" colspan="3"><h5><?php echo $schedule['module_name']; ?></h5></td>
									</tr>
									<?php 
										}
										?>
										<tr height="60">
											<?php if($schedule['action_plan_target_id'] != $cur_target_id) { 
												$cur_target_id = $schedule['action_plan_target_id'];
												$i++; 
											?>
												<td width="40" rowspan="<?php echo $this->totalActivityPerTarget[$schedule['action_plan_target_id']]; ?>"><?php echo $i; ?></td>
												<td width="160" rowspan="<?php echo $this->totalActivityPerTarget[$schedule['action_plan_target_id']]; ?>"><?php echo $schedule['target_name']; ?></td>
											<?php } ?>
											<td width="350"><?php echo $schedule['activity_name']. ' <span style="color:#'.$this->site1['action_plan_color'].'; font-weight:bold; line-height:16px;">(' . round($schedule['percentage1'],2).'%) ('.$schedule['totalDone'].'/'.$schedule['total'].')</span> '; ?></td>
										</tr>
										<?php 
									} 
								} ?>
							</table>
							</div>
						</td>
						<td>							
							<?php if(!empty($this->calendar)) { ?>	
								<div id="cal-date" style="height:575px; overflow:auto; width:527px;">
								<table class="action-plan-calendar" width="<?php echo (30*$this->weekTotal); ?>">
									<?php if(!empty($this->schedule)) { 
										$cur_module_id = 0;
										$cur_target_id = 0;
										foreach($this->schedule as $schedule){ 
											if($schedule['action_plan_module_id'] != $cur_module_id)
											{
												$cur_module_id = $schedule['action_plan_module_id'];	
											?>
												<tr>
													<td width="30" height="65" colspan="<?php echo $col; ?>"></td>
												</tr>	
										<?php } ?>
											<tr height="60" width="30">
											<?php foreach($schedule['month'] as $month) {
												foreach($month as $week) {
												?>
														<td style="background-color:#8c6e45;" width="30"><a class="schedule-date" data-id="<?php echo $week['site1_schedule_id']; ?>" style="<?php if($week['status'] == 1) echo 'color:red; font-weight:bold;'; else if($week['status'] == 2) echo 'color:yellow;  font-weight:bold;'; ?>"  href="#schedule-form"><?php echo $week['site1']; ?></a></td>
												<?php
												}
											} ?>
											</tr>	
										<?php 
										}
									}
									?>									
								</table>
								</div>
							<?php } ?>
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
  <form action="" id="popup-form" class="mfp-hide white-popup-block" autocomplete="off">
		<div id="show_form">
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
			<label for="action_plan_target_id">Target</label><br/>
			<select id="action_plan_target_id" name="action_plan_target_id"></select><br/><br/>
			<label for="action_plan_activity_id">Activity</label><br/>
			<select id="action_plan_activity_id" name="action_plan_activity_id"></select><br/><br/>
			<label for="date" id="date-field">Date</label> <?php /* <a class="add-date" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a> */ ?><br/>
			<div id="list-date"></div><br/>
			<input type="submit" class="submit-btn" id="add-schedule-submit" name="add-schedule-submit" value="Submit" style="display:none;">
		</div>
  </form>
  
<!-- Done / Reschedule Form -->
  <form action="/default/actionplan/updatestatusschedule" id="schedule-form" method="POST" class="mfp-hide white-popup-block" enctype="multipart/form-data">
	<h2 id="schedule-form-title"></h2>
	<div id="status-form">
		<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id2" />
		<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
		<input type="hidden" name="original_date" id="original_date" /><br/>
		<div id="attachment-files">
		<input type="radio" name="update_status_schedule" id="uploadattachment" value="done" checked> Upload Attachment <a class="add-attachment" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a><br>
		<table id="uploader-table">
			<tr>
				<th>Description</th>
				<th>File</th>
			</tr>
			<tr>
				<td><textarea name="description[]" id="description" class="file-description1" required></textarea></td>
				<td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader1" accept="application/pdf,image/jpeg" required></td>
			</tr>
		</table>
		</div>
		<input type="radio" name="update_status_schedule" id="reschedule" value="reschedule"> Reschedule<br>
		<div id="list-date"><input type="text" name="schedule_date" id="schedule_date" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;"  autocomplete="off" disabled required onkeydown="return false"></div>
		<textarea rows="1" cols="20" name="reason" id="reason" style="width:100%; height:50px;" required placeholder="Please write your reason for rescheduling"></textarea><br/>
		<?php if($this->showActionPlanSetting == 1) { ?><input type="radio" name="update_status_schedule" id="deleteschedule" value="delete"> Delete this schedule<br><?php } ?>
		<input type="submit" class="submit-btn" id="update-status-submit" name="update-status-submit" value="Submit">
	</div>
	<div id="schedule-info"></div>
	<?php /*<div id="cqc_settings">
		<?php if($this->allowCQC == 1) { ?>
		<div id="cqc">
			<fieldset id="cqc-fieldset">
				<legend>CQC</legend>
				<input type="checkbox" id="cqc_checkbox" name="notapprove" value="1"> Tidak Setuju<br/>
				Remarks:<br/>
				<textarea rows="1" cols="30" name="cqc_remarks" id="cqc_remarks" style="width:100%; height:50px;" disabled></textarea><br/>
				<input type="file" name="cqc_attachment" id="cqc_attachment" accept="application/pdf,image/jpeg" style="width:170px; padding-top:5px;"  disabled />
			</fieldset>
		</div>
		<?php } */ ?>
		<div id="additional-uploader">
			<fieldset id="addtl-uploader-fieldset">
				<legend>Upload Attachment <a class="add-addtl-attachment" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
				<input type="hidden" name="additional_attachment" id="additional_attachment">
				<table id="addtl-uploader-table">
					<tr>
						<th>Description</th>
						<th>File</th>
					</tr>
					<tr>
						<td><textarea name="description[]" id="description" class="file-description2"></textarea></td>
						<td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader2" accept="application/pdf,image/jpeg" /></td>
					</tr>
				</table>
				<?php if($this->showActionPlanSetting == 1) { ?>
				<input type="checkbox" name="allow_upload" id="allow_upload" value="1"> Allow Chief to Upload Attachment<br>
				<?php } ?>
			</fieldset>
			<input type="submit" class="submit-btn" id="upload" name="upload" value="Save" style="width: 100px; margin-left: 150px; margin-top: 10px;">
		</div>
		
	</div>
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var report_date;

	<?php if($this->isMobile == false) { ?>
		$("#cal-date").height($( window ).height()-235);
		$("#activity-list").height($( window ).height()-241);
		$("#cal-date").width($( window ).width()-780);
		$("#calendar-layout").width($( window ).width()-798);
	<?php } ?>

	$("#exporttopdf").click(function() {
		$("body").mLoading();
		var pie = document.getElementById("chart-area");
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveactionplangraph',
			data: {
				pie: pie.toDataURL("image/png"),
				prefix: '<?php echo $this->ident['initial']."_".$this->category_id; ?>'
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exportapindividualtopdf/cd/'+data+'/y/<?php echo $this->selectedYear; ?>/pf/<?php echo $this->ident['initial']."_".$this->category_id; ?>';
				} else {
					window.open("/default/statistic/exportapindividualtopdf/cd/"+data+"/y/<?php echo $this->selectedYear; ?>/pf/<?php echo $this->ident['initial']."_".$this->category_id; ?>");
				}	
				$("body").mLoading('hide');	
			}
		});
	});	

	var config = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo intval($this->outstanding['total']); ?>,
					<?php echo intval($this->reschedule['total']); ?>,
					<?php echo intval($this->done['total']); ?>,
					<?php echo intval($this->upcoming['total']); ?>
				],
				backgroundColor: [
					window.chartColors.red,
					window.chartColors.yellow,
					window.chartColors.green,
					window.chartColors.blue,
				],
				label: 'Dataset 1'
			}],
			labels: [
				'Outstanding (<?php echo intval($this->outstanding['total']); ?>)',
				'Reschedule (<?php echo intval($this->reschedule['total']); ?>)',
				'Done (<?php echo intval($this->done['total']); ?>)',
				'Upcoming Schedule (<?php echo intval($this->upcoming['total']); ?>)'
			]
		},
		options: {
			responsive: true,
			legend: {
				position: 'right',
			},
			title: {
				display: false,
				text: 'Parking Action Plan <?php echo $this->year; ?>'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			}
		}
	};

	var ctx = document.getElementById('chart-area').getContext('2d');
	window.doughnut = new Chart(ctx, config);

	$('.add-schedule').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				/*$(".add-date").click(function() {
					var row;
					row = '<input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;" autocomplete="off" onkeydown="return false">';
					$( "#list-date").append(row);
					$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date(<?php echo $this->selectedYear; ?>, 1, 1),  maxDate: 'new Date(<?php echo $this->selectedYear; ?>, 31, 12)' });
				});*/
				$("#list-date").html("");
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_target_id").val("");
			}
		}
	});
	
	$(".add-schedule").click(function() {
		$("#date-field").hide();
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
				$("body").mLoading('hide');
				$("#show_form").html(response);
				$(".mfp-close").click(function() {
					$("body").mLoading();
					location.href="/default/actionplan/view/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
				});
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
				$("#list-date").html("");
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
				$("#list-date").html("");
			}
		});
	});

	$('#action_plan_activity_id').on('change', function(event){
		$.ajax({
			url: '/default/actionplan/getscheduleforthisactivity',
			data: { activity_id : $('#action_plan_activity_id').val(), year : '<?php echo $this->selectedYear; ?>' },
			success: function(response) {
				var schedule = jQuery.parseJSON(response);
				$("#date-field").show();
				var txt = "";
				$.each(schedule.scheduleList, function( index, value ) {
					txt = txt + value + "<br/>";
				});

				for (i = 0; i < schedule.addtldate; i++) {
					txt = txt + '<input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;" autocomplete="off" required  onkeydown="return false"><br/>';
				}
				$("#list-date").html(txt);
				
				if(schedule.addtldate > 0)
				{
					$("#add-schedule-submit").show();
				}

				$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date('<?php echo $this->selectedYear; ?>-01-01'),  maxDate: new Date('<?php echo $this->selectedYear; ?>-12-31') });
			}
		});
	});
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date('<?php echo $this->selectedYear; ?>-01-01'),  maxDate: new Date('<?php echo $this->selectedYear; ?>-12-31') });
	
	$('.schedule-date').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_name',		
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#uploadattachment").click(function(event){
				   $('.attachment-uploader1').prop("disabled", false);
				   $('.file-description1').prop("disabled", false);
				   $('#schedule_date').prop("disabled", true);
				   $('#reason').prop("disabled", true);
				   $(".add-attachment").click(function() {
					   	var row;
							row = '<tr><td><textarea name="description[]" id="description" class="file-description"></textarea></td><td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader" accept="application/pdf,image/jpeg" /></td></tr>';
							$( "#uploader-table").append(row);
						});
				});
				$("#reschedule").click(function(event){
				   $('.attachment-uploader1').prop("disabled", true); 
				   $('.file-description1').prop("disabled", true);
				   $('#schedule_date').prop("disabled", false);
				   $('#reason').prop("disabled", false);
				   $(".add-attachment").off('click');
				});
				$(".add-addtl-attachment").click(function() {
					var row;
					row = '<tr><td><textarea name="description[]" id="description" class="file-description"></textarea></td><td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader" accept="application/pdf,image/jpeg" /></td></tr>';
					$( "#addtl-uploader-table").append(row);
				});
				$("#deleteschedule").click(function(event){
				   $('.attachment-uploader1').prop("disabled", true); 
				   $('.file-description1').prop("disabled", true);
				   $('#schedule_date').prop("disabled", true);
				   $('#reason').prop("disabled", true);
				   $(".add-attachment").off('click');
				});

			},
			close: function() {	
				$('#schedule-form')[0].reset();
				$("#action_plan_target_id").val("");
			}
		}
	});

	function showListAttachment(id) {
			$.ajax({
				url: "/default/actionplan/getattachmentbyscheduleid",
				data: { id : id, category_id : <?php echo $this->category_id; ?>}
			}).done(function(resp) {
				$( "#schedule-info" ).html(resp);
				$(".delete-ap-att").click(function() {
					var res = confirm("Are you sure you want to delete this file?");
					if(res == true)
					{
						$.ajax({
							url: "/default/actionplan/deleteattachmentbyid",
							data: { id : this.dataset.id, category_id : <?php echo $this->category_id; ?>, filename:this.dataset.filename  }
						}).done(function(resp) {
								showListAttachment(id);
						});
					}
				});
			});
	}
	
	$(".schedule-date").click(function() {
		var id = this.dataset.id;
		$( "#form-title" ).html("Edit Schedule");
		$.ajax({
			url: "/default/actionplan/getschedulebyid",
			data: { id : id }
		}).done(function(response) {
			var schedule = jQuery.parseJSON(response);			
			$( "#schedule-form-title" ).html(schedule.initial+" - "+schedule.activity_name+" - "+schedule.date );
			var superAdmin = 1;
			if(schedule.schedule_date >= '<?php echo date("Y-m-d")." 00:00:00"; ?>' || superAdmin == '<?php echo $this->allowUploadActionPlan; ?>')
			{
				$("#attachment-files").show();				
				$('#description').prop("disabled", false);
				$('#attachment').prop("disabled", false);
				$('#reschedule').prop('checked',false);
				$('#schedule_date').prop("disabled", true);
				$('#reason').prop("disabled", true);
			}
			else {
				$("#attachment-files").hide();
				$('#reschedule').prop('checked',true);
				$('#schedule_date').prop("disabled", false);
				$('#reason').prop("disabled", false);
				$('#description').prop("disabled", true);
				$('#attachment').prop("disabled", true);
			}
			if(schedule.status == 1)
			{
				$("#uploadattachment").prop("checked", false);
				$('.attachment-uploader1').prop("disabled", true); 
				$('.file-description1').prop("disabled", true);
				$('#schedule_date').prop("disabled", true);
				$('#reason').prop("disabled", true);
				$("#status-form").hide();
				$("#schedule-info").show();
				$("#additional_attachment").val("1"); 
				$("#action_plan_schedule_id2").val(schedule.schedule_id); 
				if(schedule.allow_additional_upload == "1") 
				{
					$('#allow_upload').prop('checked',true);
					$("#additional-uploader").show();
				}
				else {
					$("#additional-uploader").hide();
				}
				<?php if($this->showActionPlanSetting == 1) { ?>
					$("#additional-uploader").show();
				<?php } ?>
				showListAttachment(id);
			}
			else if(schedule.status == 2)
			{
				$("#status-form").hide();
				$("#schedule-info").show();
				$("#additional-uploader").hide();
				/*<?php if($this->allowCQC == 1) { ?>
					$("#cqc").hide();
				<?php } ?>
				$("#cqc_settings").hide();*/
				$( "#schedule-info" ).html('<span style="color:red">Reschedule to '+schedule.reschedule+'. Need to be approved by OM.</span>');
			}
			else
			{
				$("#status-form").show();
				$("#schedule-info").hide();
				$("#additional-uploader").hide();
				/*<?php if($this->allowCQC == 1) { ?>
					$("#cqc").hide();
				<?php } ?>
				$("#cqc_settings").hide();*/
				$("#action_plan_schedule_id2").val(schedule.schedule_id); 
				$("#original_date").val(schedule.schedule_date); 
				/*$('.attachment-uploader2').prop("disabled", true);
				$('.file-description2').prop("disabled", true);*/
				$(".add-attachment").click(function() {
					   	var row;
						row = '<tr><td><textarea name="description[]" id="description" class="file-description1"></textarea></td><td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader1" accept="application/pdf,image/jpeg" /></td></tr>';
						$( "#uploader-table").append(row);
					});
			}			
		});	
	});
	

	$('#schedule-form').on('submit', function(event){	
		if($("input[name='update_status_schedule']:checked"). val() == "delete")
		{
			event.preventDefault(); 
			var res = confirm("Are you sure you want to delete this schedule?");
			if(res == true)
			{							
				$("body").mLoading();
				$.ajax({
					url: '/default/actionplan/deletestatusschedule',
					type: 'POST',
					data: $(this).serialize(),
					success: function(response) {
						location.href="/default/actionplan/view/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
					}
				});
			}
		}
		else
		{		
			$("body").mLoading();
		}		
	});
	
	
	$('#cal-date').on('scroll', function () {     
      $('#calendar-layout')[0].scrollLeft = this.scrollLeft;
	  $('#activity-list')[0].scrollTop = this.scrollTop;
	});

	/*$("#cqc_checkbox").click(function() {
		if ($('#cqc_checkbox').is(":checked"))
		{
			$( "#cqc_remarks" ).prop( "disabled", false );
			$( "#cqc_attachment" ).prop( "disabled", false );	
			$( "#cqc_save_btn" ).prop( "disabled", false );			
		}		
		else {
			$( "#cqc_remarks" ).prop( "disabled", true );
			$( "#cqc_attachment" ).prop( "disabled", true );
			$( "#cqc_save_btn" ).prop( "disabled", true );			
		}
	});*/

});
</script>