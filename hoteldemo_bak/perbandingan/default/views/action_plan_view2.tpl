<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link href='/js/fullcalendar_540/lib/main.css' rel='stylesheet' />

	<div class="row">
		<div class="col-md-12 col-sm-12 col-xs-12">
			<div style="margin-bottom:10px;">
				<h2 class="pagetitle"><?php if($this->category_id==6) echo $this->ident['initial']." - Preventive Maintenance "; else echo $this->ident['initial']." - Action Plan "; ?></h2>
				
				<div id="bar-graph">
					<div class="ap-chart">
						<canvas id="chart-area"></canvas>
					</div>
					<img id="exporttopdf" src="/images/newlogo_pdf.png" width="24" style="margin-top: -4px;" class="exportaptopdf" />
				</div>
				
				<div style="padding-top:10px; clear:both;">
					<div id='calendar'></div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->

<!-- View All Action Plan List -->
	<div id="view-all" class="mfp-hide white-popup-block">
		<div style="float:right; margin-top: 7px;">
			<select id="year_list" name="year_list">
				<option value="">- Select Year -</option>
				<?php for($i=2020; $i<=date("Y"); $i++) { ?>
					<option value="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php } ?>
			</select>
			<img id="exportaplisttopdf" src="/images/newlogo_pdf.png" width="24" class="exportaplisttopdf" style="margin-top:-5px; cursor:pointer;" />
		</div>
		<h2 id="form-title"><?php if($this->category_id==6) echo "Preventive Maintenance "; else echo "Action Plan "; ?> Schedule List</h2>
		<div id="action-plan-list-table"></div>
	</div>
  
<!-- Add Schedule form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" autocomplete="off">
		<div id="show_form">
			<h2 id="form-title">Add Schedule</h2>
			<input type="hidden" name="action_plan_schedule_id" id="action_plan_schedule_id" />
			<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
			<label for="name">Year</label><br/>
			<select id="year" name="year">
				<option value="">- Select Year -</option>
				<?php for($y = "2021"; $y <= date("Y")+1; $y++) {?>
					<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
				<?php } ?>
			</select><br/><br/>
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
			<label for="date" id="date-field">Schedule Date</label> <?php /* <a class="add-date" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a> */ ?><br/>
			<div id="list-date"></div><br/>
			<input type="submit" class="form-btn" id="add-schedule-submit" name="add-schedule-submit" value="Submit" style="display:none;">
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
				<td align="center"><textarea name="description[]" id="description" class="file-description1" required></textarea></td>
				<td align="center"><input type="file" name="attachment[]" id="attachment" class="attachment-uploader1" accept="application/pdf,image/jpeg" required></td>
			</tr>
		</table>
		</div>
		<input type="radio" name="update_status_schedule" id="reschedule" value="reschedule"> Reschedule<br>
		<div id="list-date"><input type="text" name="schedule_date" id="schedule_date" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;"  autocomplete="off" disabled required onkeydown="return false"></div>
		<textarea rows="1" cols="20" name="reason" id="reason" style="width:100%; height:50px;" required placeholder="Please write your reason for rescheduling"></textarea><br/>
		<?php if($this->showActionPlanSetting == 1) { ?><input type="radio" name="update_status_schedule" id="deleteschedule" value="delete"> Delete this schedule<br><?php } ?>
		<input type="submit" class="form-btn" id="update-status-submit" name="update-status-submit" value="Submit">
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
						<td><input type="file" name="attachment[]" id="attachment" class="attachment-uploader2" accept="application/pdf,image/jpeg"/></td>
					</tr>
				</table>
				<?php if($this->showActionPlanSetting == 1) { ?>
				<input type="checkbox" name="allow_upload" id="allow_upload" value="1"> Allow Chief to Upload Attachment<br>
				<?php } ?>
			</fieldset>
			<input type="submit" class="form-btn" id="upload" name="upload" value="Save" style="width: 100px; margin-left: 150px; margin-top: 10px;">
		</div>
		
	</div>
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src='/js/fullcalendar_540/lib/main.js'></script>
<script type="text/javascript">

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
	
	$("#exportaplisttopdf").click(function() {
		$("body").mLoading();
		var year = $("#year_list").val();
		if(window.innerWidth <= 800 && window.innerHeight <= 600) {
			location.href = '/default/actionplan/exportaplisttopdf/y/'+year+'/c/<?php echo $this->category_id; ?>';
		} else {
			window.open("/default/actionplan/exportaplisttopdf/y/"+year+"/c/<?php echo $this->category_id; ?>");
		}	
		$("body").mLoading('hide');	
	});

	var color = Chart.helpers.color;
	
	var apChartData = {
		labels: [
			'Upcoming Schedule',
			'Done',
			'Reschedule',
			'Outstanding'		
		],
		datasets: [{
			label: 'Modus',
			backgroundColor: color("#9e824b").alpha(0.5).rgbString(),
			borderColor: "#9e824b",
			borderWidth: 1,
			data:  [
				<?php echo intval($this->upcoming['total']); ?>,
				<?php echo intval($this->done['total']); ?>,
				<?php echo intval($this->reschedule['total']); ?>,
				<?php echo intval($this->outstanding['total']); ?>			
			],
		}]
	};

	var apChart = document.getElementById('chart-area').getContext('2d');
	window.apChartBar = new Chart(apChart, {
		type: 'horizontalBar',
		data: apChartData,
		options: {
			layout: {
				padding: {
					left: 0,
					right: 30,
					top: 0,
					bottom: 0
				}
			},
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: ''
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 10,
						min: 0
					}
				}],
				yAxes: [{
					ticks: {
						fontSize: 10
					}
				}]
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000',
					position: 'outside'
				}
			}
		}
	});

	$('.fc-addSchedule-button').magnificPopup({
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
	
	$(".fc-addSchedule-button").click(function() {
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
	
	$('#year').on('change', function(event){
		$.ajax({
			url: '/default/actionplan/getmodulebyyear',
			data: {c : <?php echo $this->category_id; ?>, y : $('#year').val() },
			success: function(response) {
				var module = jQuery.parseJSON(response);
				$("#action_plan_module_id").empty();
				$("#action_plan_module_id").append($("<option></option>").val("").html("- Select Module -"));
				$.each(module, function (key,value) {
					$("#action_plan_module_id").append($("<option></option>").val(value.action_plan_module_id).html(value.module_name));
				});
				$("#list-date").html("");
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

				if(schedule.addtldate == 365)
				{
					txt = txt + '<input type="radio" id="daily" name="scheduledate" value="daily"> <label for="daily">Daily</label><br>';
				}
				else if(schedule.addtldate > 0)
				{
					if(schedule.addtldate == 52)
					{
						txt = txt + '<input type="radio" id="weekly" name="scheduledate" value="weekly"> <label for="weekly">Weekly</label><br><div id="listday"></div>';
					}
					txt = txt + '<input type="radio" id="selDates" name="scheduledate" value="selDates"> <label for="selDates">Selected Dates</label><br><div id="listdate"></div>';
					
					/*for (i = 0; i < schedule.addtldate; i++) {
						txt = txt + '<input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;" autocomplete="off" required  onkeydown="return false"><br/>';
					}*/
				}
				$("#list-date").html(txt);
				
				$('#weekly').change(function() {
					$("#listdate").html("");
					var txt2 = "";
					txt2 = txt2 + '<select name="day" id="day">
					  <option value="7">Sunday</option>
					  <option value="1">Monday</option>
					  <option value="2">Tuesday</option>
					  <option value="3">Wednesday</option>
					  <option value="4">Thursday</option>
					  <option value="5">Friday</option>
					  <option value="6">Saturday</option>
					</select>';
					$("#listday").html(txt2);
				});
				
				$('#selDates').change(function() {
					$("#listday").html("");
					var txt2 = "";
					for (i = 0; i < schedule.addtldate; i++) {
						txt2 = txt2 + '<input type="text" name="schedule_date[]" class="form-control col-md-7 col-xs-12 datepicker" style="margin-bottom:5px;" autocomplete="off" required  onkeydown="return false"><br/>';
					}
					$("#listdate").html(txt2);
					$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date($('#year').val()+'-01-01'),  maxDate: new Date($('#year').val()+'-12-31') });
				});
				
				if(schedule.addtldate > 0)
				{
					$("#add-schedule-submit").show();
				}

				$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd', minDate: new Date($('#year').val()+'-01-01'),  maxDate: new Date($('#year').val()+'-12-31') });
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
							row = '<tr><td align="center"><textarea name="description[]" id="description" class="file-description"></textarea></td><td align="center"><input type="file" name="attachment[]" id="attachment" class="attachment-uploader" accept="application/pdf,image/jpeg" /></td></tr>';
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
});

document.addEventListener('DOMContentLoaded', function() {

/*function dayHeaderFormatUsingMoment(info) {
	console.log("format");
    return moment(info.date.marker).format("ddd, DD/MM/YYYY");
}*/

var calendarEl = document.getElementById('calendar');

var calendar = new FullCalendar.Calendar(calendarEl, {
  initialDate: '<?php echo Date("Y-m-d"); ?>',
  height: window.innerHeight - 130,
  editable: true,
  selectable: true,
  businessHours: true,
  firstDay: 1,
  customButtons: {
    addSchedule: {
      text: 'Add Schedule',
      click: function() {
        $.magnificPopup.open({
			items: {
				src: '#popup-form',
			},
			type: 'inline',
			closeOnBgClick: false,
		});
      }
    },
	viewAllList: {
      text: 'View All Schedules',
      click: function() {
		$.magnificPopup.open({
			items: {
				src: '#view-all',
			},
			type: 'inline',
			closeOnBgClick: false,
			callbacks: {
				open: function() {
					var curDate = calendar.getDate();
					$("#year_list").val(curDate.getFullYear());
					$.ajax({
						url: "/default/actionplan/getallactionplan/c/<?php echo $this->category_id; ?>/y/"+curDate.getFullYear(),
					}).done(function(response) { 						
						$(".mfp-content").addClass("w1000");
						$("#view-all #form-title").html("<?php if($this->category_id==6) echo "Preventive Maintenance "; else echo "Action Plan "; ?> Schedule List " + curDate.getFullYear());
						$("#action-plan-list-table").html(response);
					});
									
					$('#year_list').change(function() {
						var selYear = $(this).val();
						$.ajax({
							url: "/default/actionplan/getallactionplan/c/<?php echo $this->category_id; ?>/y/"+selYear,
						}).done(function(response) { 			
							$("#view-all #form-title").html("<?php if($this->category_id==6) echo "Preventive Maintenance "; else echo "Action Plan "; ?> Schedule List " + selYear);
							$("#action-plan-list-table").html(response);
						});
					});	
				},
				close: function() {	
				
				}
			}
		});
      }
    }
  },
  headerToolbar: {
        left: 'title addSchedule viewAllList today dayGridMonth,timeGridWeek,listMonth prev,next',
        center: '',
        right: ''
  },
  /*views: {
	timeGrid: {
      dayHeaderFormat: this.dayHeaderFormatUsingMoment,
    },
  },*/
  dayMaxEvents: true,
  events: [
  <?php foreach($this->schedules as $schedule) { 
  ?>
	{
	  title: '<?php echo $schedule['activity_name'].' (' . round($schedule['percentage'],2).'%) ('.$schedule['totalDone'].'/'.$schedule['total'].')'; ?>',
	  start: '<?php echo $schedule['date']; ?>',
	  url: '#schedule-form',
	  id: '<?php echo $schedule['schedule_id']; ?>',
	  <?php if($schedule['status'] == 1) echo "color: '#9e824b'"; else if($schedule['status'] == 2) echo "color: 'darkorange'"; else if(($schedule['date'] < date("Y-m-d")) && $schedule['status'] < 1) echo "color: 'red'";  ?>
	},
  <?php } ?>
  ],
	eventMouseEnter: function(event, el, jsEvent, view) {
		$(event.el).tooltip({
            title: event.event.title,
			container: "body"
        });
	},
	eventMouseout: function(event, jsEvent, view) {
		$('#'+event.id).remove();
	},
  eventClick: function(info) {
	var id = info.event.id;
	$( "#form-title" ).html("Edit Schedule");
	$.ajax({
		url: "/default/actionplan/getschedulebyid",
		data: { id : id, y: <?php echo $this->selectedYear; ?> }
	}).done(function(response) {
		var schedule = jQuery.parseJSON(response);			
		$( "#schedule-form-title" ).html(schedule.initial+" - "+schedule.activity_name+" ("+schedule.percentage+"%) ("+schedule.totalDone+"/"+schedule.total+") - "+schedule.date );
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
			$( "#schedule-info" ).html('<span style="color:red">Reschedule to '+schedule.reschedule+'. Need to be approved by Site Manager.</span>');
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
					row = '<tr><td align="center"><textarea name="description[]" id="description" class="file-description1"></textarea></td><td align="center"><input type="file" name="attachment[]" id="attachment" class="attachment-uploader1" accept="application/pdf,image/jpeg" /></td></tr>';
					$( "#uploader-table").append(row);
				});
		}			
	});	
	
	$.magnificPopup.open({
		items: {
			src: '#schedule-form',
		},
		type: 'inline',
		closeOnBgClick: false,
		callbacks: {
			open: function() {
				$("#uploadattachment").click(function(event){
				   $('.attachment-uploader1').prop("disabled", false);
				   $('.file-description1').prop("disabled", false);
				   $('#schedule_date').prop("disabled", true);
				   $('#reason').prop("disabled", true);
				   $(".add-attachment").click(function() {
					   	var row;
							row = '<tr><td align="center"><textarea name="description[]" id="description" class="file-description"></textarea></td><td align="center"><input type="file" name="attachment[]" id="attachment" class="attachment-uploader" accept="application/pdf,image/jpeg" /></td></tr>';
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
					row = '<tr><td align="center"><textarea name="description[]" id="description" class="file-description"></textarea></td><td align="center"><input type="file" name="attachment[]" id="attachment" class="attachment-uploader" accept="application/pdf,image/jpeg" /></td></tr>';
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
	}
});

calendar.render();

$(".fc-prev-button").click(function(event){
	var date = calendar.getDate();
	var m = date.getMonth()+1;
	var y = date.getFullYear();
	$.ajax({
		url: "/default/actionplan/getschedulebymonthyear",
		data: { m : m, y : y, c : <?php echo $this->category_id; ?> }
	}).done(function(response) {
		events = calendar.getEvents();
		if(m<10) m = "0"+m;
		events.forEach(event => {
			if(event.startStr >= y+"-"+m+"-01" && event.startStr <= y+"-"+m+"-31")
			{
				event.remove();
			}
		});
		var obj = jQuery.parseJSON(response);
		$.each( obj, function( key, value ) {
			calendar.addEvent(value);	
		});
			
	});		
});

$(".fc-next-button").click(function(event){
	var date = calendar.getDate();
	var m = date.getMonth()+1;
	var y = date.getFullYear();
	$.ajax({
		url: "/default/actionplan/getschedulebymonthyear",
		data: { m : m, y : y, c : <?php echo $this->category_id; ?> }
	}).done(function(response) { 
		events = calendar.getEvents();
		if(m<10) m = "0"+m;
		events.forEach(event => {
			if(event.startStr >= y+"-"+m+"-01" && event.startStr <= y+"-"+m+"-31")
			{
				event.remove();
			}
		});
		var obj = jQuery.parseJSON(response);
		$.each( obj, function( key, value ) {
			calendar.addEvent(value);	
		});	
	});	
});

});
</script>