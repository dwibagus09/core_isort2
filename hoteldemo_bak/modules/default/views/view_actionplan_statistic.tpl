<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="ap-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle"><?php echo $this->ident['initial']; ?> - Action Plan Statistic <?php echo $this->year; ?></h2>
		<?php /*<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/actionplan"  method="post">
				<div class="statistic-filter-field">Year : 
					<select name="year" style="width: 100px; padding: 2.8px;">
						<?php for($i=2019; $i <= date("Y"); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php if($i == $this->year) echo "selected"; ?>><?php echo $i; ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="statistic-filter-field"><input type="submit" id="view-ap-stat" name="view-ap-stat" value="Go" style="width:50px;"> <input type="button" id="export-ap-stat" name="export-ap-stat" value="Export to PDF" style="width:100px;"></div>
			</form>
		</div>*/ ?>
		<div style="float:right">
			<img id="export-ap-stat" src="/images/newlogo_pdf.png" width="24" style="float: left; cursor:pointer;" />
		</div>

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<div class="action-plan-statistic-title">Security - Action Plan <?php echo $this->year; ?></div>
			<div class="ap-chart">
				<canvas id="security-chart-area"></canvas>
			</div>
			<?php if(!empty($this->outstandingListSec)) {  ?>
			<div class="ap-outstanding-table">
				<h5>Outstanding List</h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php $i = 1; foreach($this->outstandingListSec as $outstandingListSec) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListSec['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListSec['activity_name']; ?></td>
					</tr>
					<?php $i++; } ?>
				</table>
			</div>
			<?php } ?>
		</div>

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<div class="action-plan-statistic-title">Safety - Action Plan <?php echo $this->year; ?></div>
			<div class="ap-chart">
				<canvas id="safety-chart-area"></canvas>
			</div>
			<?php if(!empty($this->outstandingListSaf)) {  ?>
			<div class="ap-outstanding-table">
				<h5>Outstanding List</h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php $i = 1; foreach($this->outstandingListSaf as $outstandingListSaf) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListSaf['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListSaf['activity_name']; ?></td>
					</tr>
					<?php $i++; } ?>
				</table>
			</div>
			<?php } ?>
		</div>	

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<div class="action-plan-statistic-title">Parking &amp; Traffic - Action Plan <?php echo $this->year; ?></div>
			<div class="ap-chart">
				<canvas id="parking-chart-area"></canvas>
			</div>
			<?php if(!empty($this->outstandingListPark)) { ?>
			<div class="ap-outstanding-table">
				<h5>Outstanding List</h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php $i = 1; foreach($this->outstandingListPark as $outstandingListPark) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListPark['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListPark['activity_name']; ?></td>
					</tr>
					<?php $i++; } ?>
				</table>
			</div>
			<?php } ?>
		</div>	

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<div class="action-plan-statistic-title">Housekeeping - Action Plan <?php echo $this->year; ?></div>
			<div class="ap-chart">
				<canvas id="housekeeping-chart-area"></canvas>
			</div>
			<?php if(!empty($this->outstandingListHk)) { ?>
			<div class="ap-outstanding-table">
				<h5>Outstanding List</h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php $i = 1; foreach($this->outstandingListHk as $outstandingListHk) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListHk['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListHk['activity_name']; ?></td>
					</tr>
					<?php $i++; } ?>
				</table>
			</div>
			<?php } ?>
		</div>	

		
		
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->

<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var securityConfig = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo intval($this->outstandingSec['total']); ?>,
					<?php echo intval($this->rescheduleSec['total']); ?>,
					<?php echo intval($this->doneSec['total']); ?>,
					<?php echo intval($this->upcomingSec['total']); ?>
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
				'Outstanding (<?php echo intval($this->outstandingSec['total']); ?>)',
				'Reschedule (<?php echo intval($this->rescheduleSec['total']); ?>)',
				'Done (<?php echo intval($this->doneSec['total']); ?>)',
				'Upcoming Schedule (<?php echo intval($this->upcomingSec['total']); ?>)'
			]
		},
		options: {
			responsive: true,
			legend: {
				position: 'right',
			},
			title: {
				display: false,
				text: 'Security Action Plan <?php echo $this->year; ?>'
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

	var ctx = document.getElementById('security-chart-area').getContext('2d');
	window.securityDoughnut = new Chart(ctx, securityConfig);

	var safetyConfig = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo intval($this->outstandingSaf['total']); ?>,
					<?php echo intval($this->rescheduleSaf['total']); ?>,
					<?php echo intval($this->doneSaf['total']); ?>,
					<?php echo intval($this->upcomingSaf['total']); ?>
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
				'Outstanding (<?php echo intval($this->outstandingSaf['total']); ?>)',
				'Reschedule (<?php echo intval($this->rescheduleSaf['total']); ?>)',
				'Done (<?php echo intval($this->doneSaf['total']); ?>)',
				'Upcoming Schedule (<?php echo intval($this->upcomingSaf['total']); ?>)'
			]
		},
		options: {
			responsive: true,
			legend: {
				position: 'right',
			},
			title: {
				display: false,
				text: 'Safety Action Plan <?php echo $this->year; ?>'
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

	var ctx = document.getElementById('safety-chart-area').getContext('2d');
	window.securityDoughnut = new Chart(ctx, safetyConfig);

	var parkingConfig = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo intval($this->outstandingPark['total']); ?>,
					<?php echo intval($this->reschedulePark['total']); ?>,
					<?php echo intval($this->donePark['total']); ?>,
					<?php echo intval($this->upcomingPark['total']); ?>
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
				'Outstanding (<?php echo intval($this->outstandingPark['total']); ?>)',
				'Reschedule (<?php echo intval($this->reschedulePark['total']); ?>)',
				'Done (<?php echo intval($this->donePark['total']); ?>)',
				'Upcoming Schedule (<?php echo intval($this->upcomingPark['total']); ?>)'
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

	var ctx = document.getElementById('parking-chart-area').getContext('2d');
	window.parkingDoughnut = new Chart(ctx, parkingConfig);

	var housekeepingConfig = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo intval($this->outstandingHk['total']); ?>,
					<?php echo intval($this->rescheduleHk['total']); ?>,
					<?php echo intval($this->doneHk['total']); ?>,
					<?php echo intval($this->upcomingHk['total']); ?>
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
				'Outstanding (<?php echo intval($this->outstandingHk['total']); ?>)',
				'Reschedule (<?php echo intval($this->rescheduleHk['total']); ?>)',
				'Done (<?php echo intval($this->doneHk['total']); ?>)',
				'Upcoming Schedule (<?php echo intval($this->upcomingHk['total']); ?>)'
			]
		},
		options: {
			responsive: true,
			legend: {
				position: 'right',
			},
			title: {
				display: false,
				text: 'Housekeeping Action Plan <?php echo $this->year; ?>'
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

	var ctx = document.getElementById('housekeeping-chart-area').getContext('2d');
	window.housekeepingDoughnut = new Chart(ctx, housekeepingConfig);

		
	$("#export-ap-stat").click(function() {
		$("body").mLoading();
		var ap_graph_sec = document.getElementById("security-chart-area");
		var ap_graph_saf = document.getElementById("safety-chart-area");
		var ap_graph_park = document.getElementById("parking-chart-area");
		var ap_graph_hk = document.getElementById("housekeeping-chart-area");
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveapgraph',
			data: {
				ap_graph_sec: ap_graph_sec.toDataURL("image/png"),
				ap_graph_saf: ap_graph_saf.toDataURL("image/png"),
				ap_graph_park: ap_graph_park.toDataURL("image/png"),
				ap_graph_hk: ap_graph_hk.toDataURL("image/png")
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exportapstatistictopdf/cd/'+data+'/y/<?php echo $this->year; ?>';
				} else {
					window.open("/default/statistic/exportapstatistictopdf/cd/"+data+"/y/<?php echo $this->year; ?>");
				}	
				$("body").mLoading('hide');	
			}
		});

	});
});	
</script>