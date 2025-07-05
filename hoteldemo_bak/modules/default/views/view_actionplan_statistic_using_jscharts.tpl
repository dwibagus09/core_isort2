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
			<h4>Security</h4>
			<div id="ap-graph-sec" class="chart">Loading graph...</div>
			<div class="ap-outstanding-table">
				<h5>Outstanding Security - Action Plan <?php echo $this->year; ?></h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php if(!empty($this->outstandingListSec)) { $i = 1; foreach($this->outstandingListSec as $outstandingListSec) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListSec['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListSec['activity_name']; ?></td>
					</tr>
					<?php $i++; } } ?>
				</table>
			</div>
		</div>	

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<h4>Safety</h4>
			<div id="ap-graph-saf" class="chart">Loading graph...</div>
			<div class="ap-outstanding-table">
				<h5>Outstanding Safety - Action Plan <?php echo $this->year; ?></h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php if(!empty($this->outstandingListSaf)) { $i = 1; foreach($this->outstandingListSaf as $outstandingListSaf) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListSaf['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListSaf['activity_name']; ?></td>
					</tr>
					<?php $i++; } } ?>
				</table>
			</div>
		</div>	

		<div class="user-stat col-md-12 col-sm-12 col-xs-12">
			<h4>Parking &amp; Traffic</h4>
			<div id="ap-graph-park" class="chart">Loading graph...</div>
			<div class="ap-outstanding-table">
				<h5>Outstanding Parking &amp; Traffic - Action Plan <?php echo $this->year; ?></h5>
				<table>
					<tr>
						<th width="50">No</th>
						<th width="150">Date</th>
						<th>Planning Action</th>
					</tr>
					<?php if(!empty($this->outstandingListPark)) { $i = 1; foreach($this->outstandingListPark as $outstandingListPark) { ?>
					<tr>
						<td><?php echo $i; ?></td>
						<td><?php echo $outstandingListPark['formatted_schedule_date']; ?></td>
						<td><?php echo $outstandingListPark['activity_name']; ?></td>
					</tr>
					<?php $i++; } } ?>
				</table>
			</div>
		</div>	

		
		
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->

<script type="text/javascript" src="/js/JSCharts/sources/jscharts.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var myDataSec = new Array(['Outstanding', <?php echo intval($this->outstandingSec['total']); ?>], ['Reschedule', <?php echo intval($this->rescheduleSec['total']); ?>], ['Done', <?php echo intval($this->doneSec['total']); ?>], ['Upcoming', <?php echo intval($this->upcomingSec['total']); ?>]);
	var colorsSec = ['#fa6666', '#68e256', '#5668e2', '#ffc800'];
	var myChartSec = new JSChart('ap-graph-sec', 'pie');
	myChartSec.setDataArray(myDataSec);
	myChartSec.colorizePie(colorsSec);
	myChartSec.setTitle('');
	myChartSec.setTitleColor('#8E8E8E');
	myChartSec.setTitleFontSize(10);
	myChartSec.setTextPaddingTop(30);
	myChartSec.setSize(400, 200);
	myChartSec.setPiePosition(100, 100);
	myChartSec.setPieRadius(75);
	myChartSec.setPieUnitsColor('#555');
	myChartSec.setPieValuesFontSize(7);
	myChartSec.setBackgroundImage('chart_bg.jpg');
	myChartSec.setShowXValues(false);
	myChartSec.setPieValuesOffset(10);
	myChartSec.setPieValuesColor('#555');
	myChartSec.setLegend('#fa6666', 'Outstanding (<?php echo intval($this->outstandingSec['total']); ?>)');
	myChartSec.setLegend('#68e256', 'Reschedule (<?php echo intval($this->rescheduleSec['total']); ?>)');
	myChartSec.setLegend('#5668e2', 'Done (<?php echo intval($this->doneSec['total']); ?>)');
	myChartSec.setLegend('#ffc800', 'Upcoming Schedule (<?php echo intval($this->upcomingSec['total']); ?>)');
	myChartSec.setLegendPosition(200, 80);
	/*myChartSec.set3D(true);
	myChartSec.setPieAngle(45);*/
	myChartSec.setLegendShow(true);
	myChartSec.draw();

	var myDataSaf = new Array(['Outstanding', <?php echo intval($this->outstandingSaf['total']); ?>], ['Reschedule', <?php echo intval($this->rescheduleSaf['total']); ?>], ['Done', <?php echo intval($this->doneSaf['total']); ?>], ['Upcoming', <?php echo intval($this->upcomingSaf['total']); ?>]);
	var colorsSaf = ['#fa6666', '#68e256', '#5668e2', '#ffc800'];
	var myChartSaf = new JSChart('ap-graph-saf', 'pie');
	myChartSaf.setDataArray(myDataSaf);
	myChartSaf.colorizePie(colorsSaf);
	myChartSaf.setTitle('');
	myChartSaf.setTitleColor('#8E8E8E');
	myChartSaf.setTitleFontSize(10);
	myChartSaf.setTextPaddingTop(30);
	myChartSaf.setSize(400, 200);
	myChartSaf.setPiePosition(100, 100);
	myChartSaf.setPieRadius(75);
	myChartSaf.setPieUnitsColor('#555');
	myChartSaf.setPieValuesFontSize(7);
	myChartSaf.setBackgroundImage('chart_bg.jpg');
	myChartSaf.setShowXValues(false);
	myChartSaf.setPieValuesOffset(10);
	myChartSaf.setPieValuesColor('#555');
	myChartSaf.setLegend('#fa6666', 'Outstanding (<?php echo intval($this->outstandingSaf['total']); ?>)');
	myChartSaf.setLegend('#68e256', 'Reschedule (<?php echo intval($this->rescheduleSaf['total']); ?>)');
	myChartSaf.setLegend('#5668e2', 'Done (<?php echo intval($this->doneSaf['total']); ?>)');
	myChartSaf.setLegend('#ffc800', 'Upcoming Schedule (<?php echo intval($this->upcomingSaf['total']); ?>)');
	myChartSaf.setLegendPosition(200, 80);
	myChartSaf.setLegendShow(true);
	myChartSaf.draw();

	var myDataPark = new Array(['Outstanding', <?php echo intval($this->outstandingPark['total']); ?>], ['Reschedule', <?php echo intval($this->reschedulePark['total']); ?>], ['Done', <?php echo intval($this->donePark['total']); ?>], ['Upcoming', <?php echo intval($this->upcomingPark['total']); ?>]);
	var colorsPark = ['#fa6666', '#68e256', '#5668e2', '#ffc800'];
	var myChartPark = new JSChart('ap-graph-park', 'pie');
	myChartPark.setDataArray(myDataPark);
	myChartPark.colorizePie(colorsPark);
	myChartPark.setTitle('');
	myChartPark.setTitleColor('#8E8E8E');
	myChartPark.setTitleFontSize(10);
	myChartPark.setTextPaddingTop(30);
	myChartPark.setSize(400, 200);
	myChartPark.setPiePosition(100, 100);
	myChartPark.setPieRadius(75);
	myChartPark.setPieUnitsColor('#555');
	myChartPark.setPieValuesFontSize(7);
	myChartPark.setBackgroundImage('chart_bg.jpg');
	myChartPark.setShowXValues(false);
	myChartPark.setPieValuesOffset(10);
	myChartPark.setPieValuesColor('#555');
	myChartPark.setLegend('#fa6666', 'Outstanding (<?php echo intval($this->outstandingPark['total']); ?>)');
	myChartPark.setLegend('#68e256', 'Reschedule (<?php echo intval($this->reschedulePark['total']); ?>)');
	myChartPark.setLegend('#5668e2', 'Done (<?php echo intval($this->donePark['total']); ?>)');
	myChartPark.setLegend('#ffc800', 'Upcoming Schedule (<?php echo intval($this->upcomingPark['total']); ?>)');
	myChartPark.setLegendPosition(200, 80);
	myChartPark.setLegendShow(true);
	myChartPark.draw();
	
	$("#export-ap-stat").click(function() {
		$("body").mLoading();
		var ap_graph_sec = document.getElementById("JSChart_ap-graph-sec");
		var ap_graph_saf = document.getElementById("JSChart_ap-graph-saf");
		var ap_graph_park = document.getElementById("JSChart_ap-graph-park");
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveapgraph',
			data: {
				ap_graph_sec: ap_graph_sec.toDataURL("image/png"),
				ap_graph_saf: ap_graph_saf.toDataURL("image/png"),
				ap_graph_park: ap_graph_park.toDataURL("image/png")
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