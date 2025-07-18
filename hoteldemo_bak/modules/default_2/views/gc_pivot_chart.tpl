<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="pivot-chart">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle"><?php echo $this->category_name; ?> Business Intelligence</h2>
		
		<div class="pivot-filter">
			<form id="pivot-filter-form" action="/default/pivot/gc"  method="post">
				<div class="filter-keyword">
				<i class="fa fa-filter" ></i>
				<?php if(!empty($this->keywords)) {
					foreach($this->keywords as $keyword) { echo '<div class="filter-key">'.$keyword.'</div>'; } }?>
				</div>
				<i id="expand-filter" class="fa fa-sliders-h" ></i>
				<div class="pivotbtn" id="exportpdf"><img id="exporttopdf" src="/images/newlogo_pdf.png" style="float: none; cursor:pointer;" width="20"> Export to PDF</div>
				<div class="pivotbtn" id="reset-chart">Reset Filter</div>
				<div id="filter-fields">
					<input type="hidden" name="c" value="<?php echo $this->cat_id; ?>" />
					<div class="pivot-filter-field">
						<div class="field-title">Year</div>
						<div class="field-value">
							<select id="year" name="year"><?php for($i = 2022; $i<=date("Y");$i++) { ?><option <?php if($this->year == $i) echo "selected"; ?> value="<?php echo $i; ?>"><?php echo $i; ?></option><?php } ?></select>
						</div>
						<div class="field-title">Month </div>
						<div class="field-value">
							<select name="month[]" id="month" multiple>
								<option value="1" <?php if($this->month[1] == 1) echo "selected"; ?>>January</option>
								<option value="2" <?php if($this->month[2] == 1) echo "selected"; ?>>February</option>
								<option value="3" <?php if($this->month[3] == 1) echo "selected"; ?>>March</option>
								<option value="4" <?php if($this->month[4] == 1) echo "selected"; ?>>April</option>
								<option value="5" <?php if($this->month[5] == 1) echo "selected"; ?>>May</option>
								<option value="6" <?php if($this->month[6] == 1) echo "selected"; ?>>June</option>
								<option value="7" <?php if($this->month[7] == 1) echo "selected"; ?>>July</option>
								<option value="8" <?php if($this->month[8] == 1) echo "selected"; ?>>August</option>
								<option value="9" <?php if($this->month[9] == 1) echo "selected"; ?>>September</option>
								<option value="10" <?php if($this->month[10] == 1) echo "selected"; ?>>October</option>
								<option value="11" <?php if($this->month[11] == 1) echo "selected"; ?>>November</option>
								<option value="12" <?php if($this->month[12] == 1) echo "selected"; ?>>December</option>
							</select>
						</div>
					</div>
					<div class="pivot-filter-field">
						<div class="field-title">Day</div>
						<div class="field-value">
							<select name="day[]" id="day" multiple>					
								<option value="1" <?php if($this->day[1] == 1) echo "selected"; ?>>Sunday</option>
								<option value="2" <?php if($this->day[2] == 1) echo "selected"; ?>>Monday</option>
								<option value="3" <?php if($this->day[3] == 1) echo "selected"; ?>>Tuesday</option>
								<option value="4" <?php if($this->day[4] == 1) echo "selected"; ?>>Wednesday</option>
								<option value="5" <?php if($this->day[5] == 1) echo "selected"; ?>>Thursday</option>
								<option value="6" <?php if($this->day[6] == 1) echo "selected"; ?>>Friday</option>
								<option value="7" <?php if($this->day[7] == 1) echo "selected"; ?>>Saturday</option>
							</select>
						</div>
						<div class="field-title">Time Period</div>
						<div class="field-value">	
							<select name="time_period[]" id="time_period" multiple>					
								<option value="1" <?php if($this->period[1] == 1) echo "selected"; ?>>09:01 - 12:00</option>
								<option value="2" <?php if($this->period[2] == 1) echo "selected"; ?>>12:01 - 16:00</option>
								<option value="3" <?php if($this->period[3] == 1) echo "selected"; ?>>16:01 - 19:00</option>
								<option value="4" <?php if($this->period[4] == 1) echo "selected"; ?>>19:01 - 23:00</option>
								<option value="5" <?php if($this->period[5] == 1) echo "selected"; ?>>23:01 - 09:00</option>
							</select>
						</div>
					</div>
					<div class="pivot-filter-field">
						<?php if(!empty($this->area)) { ?>
						<div class="field-title">
							Area
						</div>
						<div class="field-value">
							<select name="tenant_umum[]" id="tenant_umum" multiple>	
								<?php foreach($this->area as $area) { ?>		
									<option value="<?php echo $area['area_id']; ?>" <?php if($this->tenant_umum[$area['area_id']] == '1') echo "selected"; ?>><?php echo $area['area_name']; ?></option>
								<?php }?>
							</select>
						</div>						
						<?php } ?>
						<div class="field-title">
							Location
						</div>
						<div class="field-value">
							<select name="floor[]" id="floor" multiple>			
								<?php foreach($this->floor as $floor) { ?>			
									<option value="<?php echo $floor['floor_id']; ?>" <?php if($this->floors[$floor['floor_id']] == 1) echo "selected"; ?>><?php echo $floor['floor']; ?></option>
								<?php }?>
							</select>
						</div>
					</div>
					<div class="pivot-filter-field">
						<div class="field-title">
							Department
						</div>
						<div class="field-value">
							<select name="category[]" id="category" multiple>			
								<option value="2" <?php if($this->categories[2] == 1) echo "selected"; ?>>Housekeeping</option>
								<option value="6" <?php if($this->categories[6] == 1) echo "selected"; ?>>Engineering</option>
							</select>
						</div>
						<div id="pivot-modus-field">
							<div class="field-title">
								Modus
							</div>
							<div class="field-value">
								<select name="modus[]" id="modus" multiple style="min-width:100px;">					
									<?php if(!empty($this->modus)) { foreach($this->modus as $modus) { ?>			
										<option value="<?php echo $modus['modus_id']; ?>" <?php if($this->mods[$modus['modus_id']] == 1) echo "selected"; ?>><?php echo addslashes($modus['modus']); ?></option>
									<?php } } ?>
								</select>
							</div>
						</div>
					</div>
					<div>
						<input type="submit" id="apply-filter-btn" name="apply-filter" value="Apply Filter" class="pivotbtn">
					</div>
				</div>
			</form>
		</div>
		
		<div id="pivot-chart-container">
			<div id="total-all-chart" class="pivot-chart-small">
				<canvas id="totalAllChart"></canvas>
			</div>
			
			<div id="total-each-category" class="pivot-chart-small">
				<canvas id="totalEachCategory"></canvas>
			</div>
			
			<div id="total-each-period" class="pivot-chart-small">
				<canvas id="totalEachPeriod"></canvas>
			</div>
			
			<div id="total-day-chart" class="pivot-chart-large">
				<canvas id="totalDayChart"></canvas>
			</div>
			
			<div id="total-monthly-chart" class="pivot-chart-large">
				<canvas id="totalMonthlyChart"></canvas>
			</div>		
			
			<div id="total-each-area" class="pivot-chart-large">
				<canvas id="totalEachArea"></canvas>
			</div>
					
			<div id="total-each-location" class="pivot-chart-large">
				<canvas id="totalEachLocation"></canvas>
			</div>
			
			<div id="total-each-incident" class="pivot-chart-large">
				<canvas id="totalEachIncident"></canvas>
			</div>
			<div id="total-each-modus" class="pivot-chart-large">
				<canvas id="totalEachModus"></canvas>
			</div>
		</div>
		<?php if(!empty($this->detailIssues)) { ?>
			<div id="detail-issue-summary">
				<?php /*
				<div class="paging">
					<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Issues </div>
					<div class="paging-section">
						<?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
						<?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
						<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
						<?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
						<?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
					</div>
				</div>
				*/ ?>
				<table class="table table-striped">
					<thead>
						<tr>
							<th width="150">Kejadian</th>
							<th><?php if($this->cat_id == 3) echo "Data hasil Investigasi"; else echo "Analisa"; ?></th>
							<th><?php if($this->cat_id == 3) echo "Langkah Antisipatif"; else echo "Rencana &amp; Tindakan"; ?></th>
							<?php if($this->cat_id == 3) { ?><th>Rekomendasi</th><?php } ?>
						</tr>
					</thead>
					<tbody>
						<?php $i = 0; foreach($this->detailIssues as $detailIssue) { 
							$issuedate = explode(" ", $detailIssue['issue_date']);
							$issue_date = date("j M Y", strtotime($issuedate[0]))." ".$issuedate[1];
						?>
						<tr <?php if($i%2 == 1) echo 'style="background-color:#eee;"'; ?>>
							<td><?php echo $detailIssue['kejadian']; ?></td>
							<td align="center"><?php echo $detailIssue['analisa']; ?></td>
							<td align="center"><?php echo $detailIssue['tindakan']; ?></td>
							<?php if($this->cat_id == 3) { ?><td><?php echo $detailIssue['rekomendasi']; ?></td><?php } ?>
						</tr>
						<?php $i++; } ?>
					</tbody>
				</table
				<?php /*
				<div class="paging">
					<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Issues </div>
					<div class="paging-section">
						<?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
						<?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
						<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
						<?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
						<?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
					</div>
				</div>
				*/ ?>
			</div>
		<?php } ?>
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->

<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/plugin/chartjs-plugin-labels.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#business-intelligence-menu").addClass('active');
	$("#business-intelligence-menu .child_menu").show();

	$( "#filter-fields" ).hide();
	$( "#expand-filter" ).click(function() {
	  $( "#filter-fields" ).toggle( "slow", function() {
	  });
	});
	
	$('#tenant_umum').on('change', function() {
		$.ajax({
			url: '/default/issue/getlocationbyareaid',
			data: { area_id : $(this).val(),
					cat_id : '<?php echo $this->cat_id; ?>'
			}
		}).done(function(response) {
			var resp = $.parseJSON(response);
			$("#floor").empty();
			$.each(resp, function (item, value) {
				$("#floor").append(new Option(value.floor, value.floor_id));
			});

		});
	});

	$("#reset-chart").click(function() {
		location.href = '/default/pivot/gc';
	});	

	$("#exportpdf").click(function() {
		$("body").mLoading();
		var totalAllChart = document.getElementById("totalAllChart");
		var totalDayChart = document.getElementById("totalDayChart");
		var totalEachCategory = document.getElementById("totalEachCategory");
		var totalEachIncident = document.getElementById("totalEachIncident");
		var totalEachPeriod = document.getElementById("totalEachPeriod");
		var totalMonthlyChart = document.getElementById("totalMonthlyChart");
		var totalEachArea = document.getElementById("totalEachArea");
		var totalEachLocation = document.getElementById("totalEachLocation");
		var totalEachModus = document.getElementById("totalEachModus");

		var mo = [];
		$('#month option:selected').each(function () {
			if ($(this).val()) {
				mo.push($(this).val());
			}
		});

		var day = [];
		$('#day option:selected').each(function () {
			if ($(this).val()) {
				day.push($(this).val());
			}
		});

		var floor = [];
		$('#floor option:selected').each(function () {
			if ($(this).val()) {
				floor.push($(this).val());
			}
		});

		var tenant_umum = [];
		$('#tenant_umum option:selected').each(function () {
			if ($(this).val()) {
				tenant_umum.push($(this).val());
			}
		});
		
		var issuetypes = [];
		$('#issue_type option:selected').each(function () {
			if ($(this).val()) {
				issuetypes.push($(this).val());
			}
		});

		var kejadian = [];
		$('#kejadian option:selected').each(function () {
			if ($(this).val()) {
				kejadian.push($(this).val());
			}
		});

		var modus = [];
		$('#modus option:selected').each(function () {
			if ($(this).val()) {
				modus.push($(this).val());
			}
		});

		var time_period = [];
		$('#time_period option:selected').each(function () {
			if ($(this).val()) {
				time_period.push($(this).val());
			}
		});

		$.ajax({
			method: 'POST',
			url: '/default/pivot/savepivotgraph',
			data: {
				totalAllChart: totalAllChart.toDataURL("image/png"),
				totalDayChart: totalDayChart.toDataURL("image/png"),
				totalEachCategory: totalEachCategory.toDataURL("image/png"),
				totalEachIncident: totalEachIncident.toDataURL("image/png"),
				totalEachPeriod: totalEachPeriod.toDataURL("image/png"),
				totalMonthlyChart: totalMonthlyChart.toDataURL("image/png"),
				totalEachArea: totalEachArea.toDataURL("image/png"),
				totalEachLocation: totalEachLocation.toDataURL("image/png"),
				totalEachModus: totalEachModus.toDataURL("image/png"),
				prefix: '<?php echo $this->ident['initial']."_".$this->cat_id; ?>',
				year : $('#year option:selected').val(),
				month : mo,
				day : day,
				floor : floor,
				tenant_umum : tenant_umum,
				issuetypes : issuetypes,
				kejadian : kejadian,
				modus : modus,
				time_period : time_period
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/pivot/exportgcpivottopdf/cd/'+data+'/pf/<?php echo $this->ident['initial']."_".$this->cat_id; ?>';
				} else {
					window.open("/default/pivot/exportgcpivottopdf/cd/"+data+"/pf/<?php echo $this->ident['initial']."_".$this->cat_id; ?>");
				}	
				$("body").mLoading('hide');	
			}
		});
	});	

	$( ".paging-button").click(function() {
        $("body").mLoading();
		var mo = [];
		$('#month option:selected').each(function () {
			if ($(this).val()) {
				mo.push($(this).val());
			}
		});

		var day = [];
		$('#day option:selected').each(function () {
			if ($(this).val()) {
				day.push($(this).val());
			}
		});

		var floor = [];
		$('#floor option:selected').each(function () {
			if ($(this).val()) {
				floor.push($(this).val());
			}
		});

		var tenant_umum = [];
		$('#tenant_umum option:selected').each(function () {
			if ($(this).val()) {
				tenant_umum.push($(this).val());
			}
		});

		var kejadian = [];
		$('#kejadian option:selected').each(function () {
			if ($(this).val()) {
				kejadian.push($(this).val());
			}
		});

		var modus = [];
		$('#modus option:selected').each(function () {
			if ($(this).val()) {
				modus.push($(this).val());
			}
		});

		var time_period = [];
		$('#time_period option:selected').each(function () {
			if ($(this).val()) {
				time_period.push($(this).val());
			}
		});

		$.ajax({
			url: this.dataset.href,
			data: { c : '<?php echo $this->cat_id; ?>',
					year : $('#year option:selected').val(),
					month : mo,
					day : day,
					floor : floor,
					tenant_umum : tenant_umum,
					kejadian : kejadian,
					modus : modus,
					time_period : time_period
			}
		}).done(function(response) {
			$( "#detail-issue-summary").html(response);
            $("body").mLoading('hide');
		});
	});

	var chartColor = ["#7e5b06", "#a1a2a6", "#a07407", "#D7D7D7", "#c19c40", "#BBBBBB", "#d9b04a", "#777777", "#B8860B", "#666666", "#e1cb95", "#888888"];

	/*** MONTH ***/

	<?php $totalAll = 0; 
	if(!empty($this->totalMonthly)) { ?>
		var mo = new Array();
		var dt = new Array();
	<?php $m = 0;		
		$tmonthhighestotal = 0;
	 	foreach($this->totalMonthly as $tm) { 
			switch($tm['mo'])
			{
				case 1: $mo = 'mo['.$m.'] = "January";'; break;
				case 2: $mo = 'mo['.$m.'] = "February";'; break;
				case 3: $mo = 'mo['.$m.'] = "March";'; break;
				case 4: $mo = 'mo['.$m.'] = "April";'; break;
				case 5: $mo = 'mo['.$m.'] = "May";'; break;
				case 6: $mo = 'mo['.$m.'] = "June";'; break;
				case 7: $mo = 'mo['.$m.'] = "July";'; break;
				case 8: $mo = 'mo['.$m.'] = "August";'; break;
				case 9: $mo = 'mo['.$m.'] = "September";'; break;
				case 10: $mo = 'mo['.$m.'] = "October";'; break;
				case 11: $mo = 'mo['.$m.'] = "November";'; break;
				case 12: $mo = 'mo['.$m.'] = "December";'; break;
			}
			echo $mo;
			echo 'dt['.$m.'] = '.$tm['total'].';';
			$m++;
			$totalAll += $tm['total'];
			if($tm['total'] > $tmonthhighestotal) $tmonthhighestotal = $tm['total'];
			
	 	}
		$tmonthoffset = $tmonthhighestotal % 5;
		if($tmonthoffset == 0) $tmonthoffset = 5;
		else $tmonthoffset = 5 - $tmonthoffset ;
		$tmonthhighestotal = $tmonthhighestotal + $tmonthoffset;
		
	?>
	var configMonth = {
		type: 'line',
		data: {
			labels: mo,
			datasets: [{
				label: '',
				backgroundColor: "#7e5b06",
				borderColor: "#7e5b06",
				data: dt,
				fill: false,
			}]
		},
		options: {
			legend: {
				display: false,
			},
			responsive: true,
			title: {
				display: true,
				text: 'MONTH'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Month'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: ''
					},
					ticks: {
						beginAtZero: true,
						max: <?php echo $tmonthhighestotal; ?>,
						min: 0
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
	};

	var monthlyChart = document.getElementById('totalMonthlyChart').getContext('2d');
	window.totalMonthlyLine = new Chart(monthlyChart, configMonth);
	<?php } else  {
			echo "$('#total-monthly-chart').hide();";
		}
	?>
	
	/*** DAY ***/	
	<?php if($this->totalDay['1'] > 0 || $this->totalDay['2'] > 0 || $this->totalDay['3'] > 0 || $this->totalDay['4'] > 0 || $this->totalDay['5'] > 0 || $this->totalDay['6'] > 0 || $this->totalDay['7'] > 0) { 
		$tdayhighestotal = max($this->totalDay);
		$tdayoffset = $tdayhighestotal % 5;
		if($tdayoffset == 0) $tdayoffset = 5;
		else $tdayoffset = 5 - $tdayoffset ;
		$tdayhighestotal = $tdayhighestotal + $tdayoffset;
		
	?>
	var configDay = {
		type: 'line',
		data: {
			labels: [
				'Sunday',
				'Monday',
				'Tuesday',
				'Wednesday',
				'Thursday',
				'Friday',
				'Saturday'
			],
			datasets: [{
				label: '',
				backgroundColor: "#a1a2a6",
				borderColor: "#a1a2a6",
				data: [
					<?php echo intval($this->totalDay['1']); ?>,
					<?php echo intval($this->totalDay['2']); ?>,
					<?php echo intval($this->totalDay['3']); ?>,
					<?php echo intval($this->totalDay['4']); ?>,
					<?php echo intval($this->totalDay['5']); ?>,
					<?php echo intval($this->totalDay['6']); ?>,
					<?php echo intval($this->totalDay['7']); ?>
				],
				fill: false,
			}]
		},
		options: {
			legend: {
				display: false,
			},
			responsive: true,
			title: {
				display: true,
				text: 'DAY'
			},
			tooltips: {
				mode: 'index',
				intersect: false,
			},
			hover: {
				mode: 'nearest',
				intersect: true
			},
			scales: {
				xAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: 'Month'
					}
				}],
				yAxes: [{
					display: true,
					scaleLabel: {
						display: false,
						labelString: ''
					},
					ticks: {
						beginAtZero: true,
						max: <?php echo $tdayhighestotal; ?>,
						min: 0
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
	};

	var dayChart = document.getElementById('totalDayChart').getContext('2d');
	window.dayBar = new Chart(dayChart, configDay);
	<?php }  else  {
		echo "$('#total-day-chart').hide();";
	} ?>
	

	/*** TOTAL ***/

	<?php if(!empty($totalAll)) { ?>
	var configTotal = {
		type: 'pie',
		data: {
			datasets: [{
				data: [
					<?php echo $totalAll; ?>
				],
				backgroundColor: [
					"#9e824b"
				],
				label: 'Total'
			}],
			labels: ['Total']
		},
		options: {
			responsive: true,
			legend: {
				display: false,
			},			
			title: {
				display: true,
				text: 'TOTAL'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			}
		}
	};

	var totalChart = document.getElementById('totalAllChart').getContext('2d');
	window.totalAllDoughnut = new Chart(totalChart, configTotal);
	<?php } else {
		echo "$('#total-all-chart').hide();";
	} ?>

	
	
	/*** TIME PERIOD ***/
	
	<?php if($this->totalEachPeriod[0]['total'] > 0 || $this->totalEachPeriod[1]['total'] > 0 || $this->totalEachPeriod[2]['total'] > 0 || $this->totalEachPeriod[3]['total'] > 0 || $this->totalEachPeriod[4]['total'] > 0) { ?>
		var periodLabel = new Array();
		var periodData = new Array();
		<?php $periodCtr = 0;
	 	foreach($this->totalEachPeriod as $tperiod) { 
			echo 'periodLabel['.$periodCtr.'] = "'.$tperiod['time'].'";';
			echo 'periodData['.$periodCtr.'] = '.$tperiod['total'].';';
			$periodCtr++;
	} ?>
	
	var periodChartData = {
		labels: periodLabel,
		datasets: [{
			label: 'Modus',
			backgroundColor: "#9e824b",
			borderColor: "#9e824b",
			borderWidth: 1,
			data: periodData
		}]
	};

	var periodChart = document.getElementById('totalEachPeriod').getContext('2d');
	window.periodChartBar = new Chart(periodChart, {
		type: 'bar',
		data: periodChartData,
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
				text: 'TIME PERIOD'
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 10
					}
				}],
				yAxes: [{
					ticks: {
						beginAtZero: true,
						fontSize: 10,
						min: 0
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
	<?php }  else  {
		echo "$('#total-each-period').hide();";
	} ?>
	
	/*** AREA ***/
	
	<?php if(!empty($this->totalEachArea)) { ?>
		var areaLabel = new Array();
		var areaData = new Array();
		<?php $areaCtr = 0;
		$tareahighestotal = 0;
	 	foreach($this->totalEachArea as $tarea) { 
			echo 'areaLabel['.$areaCtr.'] = "'.$tarea['area_name'].'";';
			echo 'areaData['.$areaCtr.'] = '.$tarea['total'].';';
			if($tarea['total'] > $tareahighestotal) $tareahighestotal = $tarea['total'];
			$areaCtr++;
	 	}
		
		$tareaoffset = $tareahighestotal % 5;
		if($tareaoffset == 0) $tareaoffset = 5;
		else $tareaoffset = 5 - $tareaoffset ;
		$tareahighestotal = $tareahighestotal + $tareaoffset; ?>
	
	var color = Chart.helpers.color;
	var areaChartData = {
		labels: areaLabel,
		datasets: [{
			label: 'Area',
			backgroundColor: color(chartColor[7]).alpha(0.5).rgbString(),
			borderColor: chartColor[7],
			borderWidth: 1,
			data: areaData
		}]
	};

	var areaChart = document.getElementById('totalEachArea').getContext('2d');
	window.areaBar = new Chart(areaChart, {
		type: 'bar',
		data: areaChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'AREA'
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9,
						beginAtZero: true
					}
				}],
				yAxes: [{
					ticks: {
						beginAtZero: true,
						max: <?php echo $tareahighestotal; ?>,
						min: 0
					}
				}]
			}
		}
	});
	
	<?php }  else  {
		echo "$('#total-each-area').hide();";
	}
	?>

	/*** LOCATION ***/
	
	<?php if(!empty($this->totalEachLocation)) { ?>
		var locationLabel = new Array();
		var locationData = new Array();
		<?php $locationCtr = 0;
		$tlocationhighestotal = 0;
	 	foreach($this->totalEachLocation as $tlocation) { 
			echo 'locationLabel['.$locationCtr.'] = "'.addslashes($tlocation['floor']).'";';
			echo 'locationData['.$locationCtr.'] = '.$tlocation['total'].';';
			if($tlocation['total'] > $tlocationhighestotal) $tlocationhighestotal = $tlocation['total'];
			$locationCtr++;
	 	}
		
		$tlocationoffset = $tlocationhighestotal % 5;
		if($tlocationoffset == 0) $tlocationoffset = 5;
		else $tlocationoffset = 5 - $tlocationoffset ;
		$tlocationhighestotal = $tlocationhighestotal + $tlocationoffset; ?>
	
	var color = Chart.helpers.color;
	var locationChartData = {
		labels: locationLabel,
		datasets: [{
			label: 'Location',
			backgroundColor: color(chartColor[7]).alpha(0.5).rgbString(),
			borderColor: chartColor[7],
			borderWidth: 1,
			data: locationData
		}]
	};

	var locationChart = document.getElementById('totalEachLocation').getContext('2d');
	window.locationBar = new Chart(locationChart, {
		type: 'bar',
		data: locationChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'LOCATION'
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9,
						beginAtZero: true
					}
				}],
				yAxes: [{
					ticks: {
						beginAtZero: true,
						max: <?php echo $tlocationhighestotal; ?>,
						min: 0
					}
				}]
			}
		}
	});
	
	<?php }  else  {
		echo "$('#total-each-location').hide();";
	}
	?>

	/*** CATEGORIES ***/

	<?php if(!empty($this->totalEachCategory)) { ?>
		var issueCategoryName = new Array();
		var issueCategoryValue = new Array();
		var issueCategoryColor = new Array();

		issueCategoryName[0] = "Housekeeping";
		issueCategoryValue[0] = "<?php echo $this->totalEachCategory[2]; ?>";
		issueCategoryColor[0] = chartColor[0];
		issueCategoryName[1] = "Engineering";
		issueCategoryValue[1] = "<?php echo $this->totalEachCategory[6]; ?>";
		issueCategoryColor[1] = chartColor[1];

	var issueCategoryConfig = {
		type: 'pie',
		data: {
			datasets: [{
				data: issueCategoryValue,
				backgroundColor: issueCategoryColor,
				label: ''
			}],
			labels: issueCategoryName
		},
		options: {
			responsive: true,
			legend: {
				position: 'right',
				labels: {
					fontSize: 9
				},
			},			
			title: {
				display: true,
				text: 'Department'
			},
			animation: {
				animateScale: true,
				animateRotate: true
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000',
					position: 'outside'
				}
			}
		}
	};

	var categoryChart = document.getElementById('totalEachCategory').getContext('2d');
	window.categoryDoughnut = new Chart(categoryChart, issueCategoryConfig);
<?php }  else  {
		echo "$('#total-each-category').hide();";
	} ?>

	/*** INCIDENT ***/

	<?php if(!empty($this->totalEachIncidents)) { ?>
		var incidentName = new Array();
		var incidentValue = new Array();
		var incidentColor = new Array();

		<?php 
			$inc = 0;
			$totalAll = 0;
			foreach($this->totalEachIncidents as $ti) { 
				echo 'incidentName['.$inc.'] = "'.addslashes($ti['kejadian']).'"; ';
				echo 'incidentValue['.$inc.'] = '.$ti['total'].'; ';
				echo 'incidentColor['.$inc.'] = chartColor['.$inc.']; ';
				$inc++;
			} ?>

		var incidentConfig = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: incidentValue,
					backgroundColor: incidentColor,
					label: ''
				}],
				labels: incidentName
			},
			options: {
				responsive: true,
				legend: {
					position: 'right',
					labels: {
						fontSize: 9
					},
				},			
				title: {
					display: true,
					text: 'INCIDENT'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				plugins: {
					labels: {
						render: 'value',
						fontColor: '#000',
						position: 'outside'
					}
				}
			}
		};

		var incidentChart = document.getElementById('totalEachIncident').getContext('2d');
		window.incidentDoughnut = new Chart(incidentChart, incidentConfig);
	<?php }  else  {
		echo "$('#total-each-incident').hide();";
	} ?>

	/*** MODUS ***/
	
	var legendPosition = 'right';
	var maintainAspectRatio = true;
	if($( window ).width() <= 500) {
		legendPosition = 'bottom';
		maintainAspectRatio = false;
	}
	
	<?php if(!empty($this->totalEachModus)) { ?>
	var modusName = new Array();
	var modusValue = new Array();
	var modusColor = new Array();
	
	<?php 
		$mod = 0;
		$totalAll = 0;
	 	foreach($this->totalEachModus as $tm) { 
			echo 'modusName['.$mod.'] = "'.addslashes($tm['modus']).'"; ';
			echo 'modusValue['.$mod.'] = '.$tm['total'].'; ';
			echo 'modusColor['.$mod.'] = chartColor['.$mod.']; ';
			$mod++;
	 	}
	?>

		var modusConfig = {
			type: 'doughnut',
			data: {
				datasets: [{
					data: modusValue,
					backgroundColor: modusColor,
					label: ''
				}],
				labels: modusName
			},
			options: {
				maintainAspectRatio: maintainAspectRatio,
				responsive: true,
				legend: {
					position: legendPosition,
					labels: {
						fontSize: 9
					},
				},			
				title: {
					display: true,
					text: 'MODUS'
				},
				animation: {
					animateScale: true,
					animateRotate: true
				},
				plugins: {
					labels: {
						render: 'value',
						fontColor: '#000',
						position: 'outside'
					}
				}
			}
		};

		var modusChart = document.getElementById('totalEachModus').getContext('2d');
		window.modusDoughnut = new Chart(modusChart, modusConfig);
	<?php }  else  {
		echo "$('#total-each-modus').hide();";
	} ?>
});	
</script>
