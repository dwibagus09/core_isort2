<link rel="stylesheet" href="/css/jquery-ui.min.css">

<div id="issue-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle">Kaizen Statistic</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/view"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-issue-stat" name="view-issue-stat" value="Go" style="width:50px;" class="form-btn"> <input type="button" id="export-issue-stat" name="export-issue-stat" value="Export to PDF" style="width:110px;" class="form-btn"></div>
			</form>
		</div>
		<div id="graph">
			<div class="graph-title">ALL DEPARTMENT</div>
			<div class="graph">
				<canvas id="total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="open-close" style="width:460px; height:225px;"></canvas>
			</div>

			<div class="graph-title">SECURITY</div>
			<div class="graph">
				<canvas id="security-total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="security-type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="security-open-close" style="width:460px; height:225px;"></canvas>
			</div>

			<div class="graph-title">SAFETY</div>
			<div class="graph">
				<canvas id="safety-total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="safety-type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="safety-open-close" style="width:460px; height:225px;"></canvas>
			</div>

			<div class="graph-title">PARKING &amp; TRAFFIC</div>
			<div class="graph">
				<canvas id="parking-total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="parking-type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="parking-open-close" style="width:460px; height:225px;"></canvas>
			</div>

			<div class="graph-title">HOUSEKEEPING</div>
			<div class="graph">
				<canvas id="hk-total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="hk-type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="hk-open-close" style="width:460px; height:225px;"></canvas>
			</div>
			
			<div class="graph-title">ENGINEERING</div>
			<div class="graph">
				<canvas id="engineering-total-issue" style="width:150px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="engineering-type-issue" style="width:460px; height:225px;"></canvas>
			</div>
			<div class="graph">
				<canvas id="engineering-open-close" style="width:460px; height:225px;"></canvas>
			</div>
		</div>
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->

<?php /*<script type="text/javascript" src="/js/JSCharts/sources/jscharts.js"></script> */ ?>
<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/plugin/chartjs-plugin-labels.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	/***** ALL DEPARTMENT *****/

	/*** TOTAL ISSUE GRAPH ***/

	var totalIssueLabel = new Array();
	var totalIssueData = new Array();
	totalIssueLabel[0] = "Total Kaizen";
	totalIssueData[0] = "310";
	
	var color = Chart.helpers.color;
	var totalIssueChartData = {
		labels: totalIssueLabel,
		datasets: [{
			label: 'TOTAL KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  totalIssueData
		}]
	};

	var totalIssueChart = document.getElementById('total-issue').getContext('2d');
	window.totalIssueBar = new Chart(totalIssueChart, {
		type: 'bar',
		data: totalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL KAIZEN',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** ISSUE PER TYPE GRAPH ***/

	var typeIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var typeIssueData = new Array(14, 11, 70, 165, 40, 10);
	
	var color = Chart.helpers.color;
	var typeIssueChartData = {
		labels: typeIssueLabel,
		datasets: [{
			label: 'TOTAL KAIZEN PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  typeIssueData  
		}]
	};

	var typeIssueChart = document.getElementById('type-issue').getContext('2d');
	window.typeIssueBar = new Chart(typeIssueChart, {
		type: 'bar',
		data: typeIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL KAIZEN PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** OPEN & CLOSE ISSUE PER CATEGORY ***/

	var openCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var openedIssueData = new Array(0, 0, 2, 1, 0, 0);
	var closedIssueData = new Array(14, 11, 68, 164, 40, 10);
	
	var color = Chart.helpers.color;
	var openCloseIssueChartData = {
		labels: openCloseIssueLabel,
		datasets: [{
			label: 'OPENED KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  openedIssueData
		},{
			label: 'CLOSED KAIZEN',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  closedIssueData
		}]
	};

	var openCloseIssueChart = document.getElementById('open-close').getContext('2d');
	window.openCloseIssueBar = new Chart(openCloseIssueChart, {
		type: 'bar',
		data: openCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED KAIZEN',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/***** SECURITY *****/

	/*** SECURITY TOTAL ISSUE GRAPH ***/

	var securityTotalIssueLabel = new Array();
	var securityTotalIssueData = new Array();
	securityTotalIssueLabel[0] = "Total Kaizen";
	securityTotalIssueData[0] = "54";
	
	var color = Chart.helpers.color;
	var securityTotalIssueChartData = {
		labels: securityTotalIssueLabel,
		datasets: [{
			label: 'TOTAL KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  securityTotalIssueData
		}]
	};

	var securityTotalIssueChart = document.getElementById('security-total-issue').getContext('2d');
	window.securityTotalIssueBar = new Chart(securityTotalIssueChart, {
		type: 'bar',
		data: securityTotalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL KAIZEN',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** SECURITY ISSUE PER TYPE GRAPH ***/

	var securityIssuePerTypeLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var securityIssuePerTypeData = new Array(1,10,35,8,0,0);
	
	var color = Chart.helpers.color;
	var securityIssuePerTypeChartData = {
		labels: securityIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL KAIZEN PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  securityIssuePerTypeData
		}]
	};

	var securityIssuePerTypeChart = document.getElementById('security-type-issue').getContext('2d');
	window.securityIssuePerTypeBar = new Chart(securityIssuePerTypeChart, {
		type: 'bar',
		data: securityIssuePerTypeChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL KAIZEN PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** SECURITY OPEN & CLOSE ISSUE PER CATEGORY ***/

	var securityOpenCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var securityOpenedIssueData = new Array(0,0,1,0,0,0);
	var securityClosedIssueData = new Array(1,10,34,8,0,0);
	
	var color = Chart.helpers.color;
	var securityOpenCloseIssueChartData = {
		labels: securityOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  securityOpenedIssueData
		},{
			label: 'CLOSED KAIZEN',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  securityClosedIssueData
		}]
	};

	var securityOpenCloseIssueChart = document.getElementById('security-open-close').getContext('2d');
	window.securityOpenCloseIssueBar = new Chart(securityOpenCloseIssueChart, {
		type: 'bar',
		data: securityOpenCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED KAIZEN',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/***** SAFETY *****/

	/*** SAFETY TOTAL ISSUE GRAPH ***/

	var safetyTotalIssueLabel = new Array();
	var safetyTotalIssueData = new Array();
	safetyTotalIssueLabel[0] = "Total Kaizen";
	safetyTotalIssueData[0] = "26";
	
	var color = Chart.helpers.color;
	var safetyTotalIssueChartData = {
		labels: safetyTotalIssueLabel,
		datasets: [{
			label: 'TOTAL ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  safetyTotalIssueData
		}]
	};

	var safetyTotalIssueChart = document.getElementById('safety-total-issue').getContext('2d');
	window.safetyTotalIssueBar = new Chart(safetyTotalIssueChart, {
		type: 'bar',
		data: safetyTotalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** SAFETY ISSUE PER TYPE GRAPH ***/

	var safetyIssuePerTypeLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var safetyIssuePerTypeData = new Array(5,3,2,1,4,1);
	
	var color = Chart.helpers.color;
	var safetyIssuePerTypeChartData = {
		labels: safetyIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL ISSUE PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  safetyIssuePerTypeData
		}]
	};

	var safetyIssuePerTypeChart = document.getElementById('safety-type-issue').getContext('2d');
	window.safetyIssuePerTypeBar = new Chart(safetyIssuePerTypeChart, {
		type: 'bar',
		data: safetyIssuePerTypeChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** SAFETY OPEN & CLOSE ISSUE PER CATEGORY ***/

	var safetyOpenCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var safetyOpenedIssueData = new Array(0,2,2,0,3,0);
	var safetyClosedIssueData = new Array(5,1,0,1,1,1);
	
	var color = Chart.helpers.color;
	var safetyOpenCloseIssueChartData = {
		labels: safetyOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  safetyOpenedIssueData
		},{
			label: 'CLOSED ISSUE',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  safetyClosedIssueData
		}]
	};

	var safetyOpenCloseIssueChart = document.getElementById('safety-open-close').getContext('2d');
	window.safetyOpenCloseIssueBar = new Chart(safetyOpenCloseIssueChart, {
		type: 'bar',
		data: safetyOpenCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/***** PARKING & TRAFFIC *****/

	/*** PARKING & TRAFFIC TOTAL ISSUE GRAPH ***/

	var parkingTotalIssueLabel = new Array();
	var parkingTotalIssueData = new Array();
	parkingTotalIssueLabel[0] = "Total Kaizen";
	parkingTotalIssueData[0] = "38";
	
	var color = Chart.helpers.color;
	var parkingTotalIssueChartData = {
		labels: parkingTotalIssueLabel,
		datasets: [{
			label: 'TOTAL ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  parkingTotalIssueData
		}]
	};

	var parkingTotalIssueChart = document.getElementById('parking-total-issue').getContext('2d');
	window.parkingTotalIssueBar = new Chart(parkingTotalIssueChart, {
		type: 'bar',
		data: parkingTotalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*	
	var parkingTotalIssue = new Array(['Issue', <?php echo $this->totalAllParkingIssue['total']; ?>]);
	var parkingTotalIssueColors = ['#2D6B96'];
	var parkingTotalIssueChart = new JSChart('parking-total-issue', 'bar');
	parkingTotalIssueChart.setDataArray(parkingTotalIssue);
	parkingTotalIssueChart.colorizeBars(parkingTotalIssueColors);
	parkingTotalIssueChart.setTitle('TOTAL ISSUE');
	parkingTotalIssueChart.setTitleColor('#8E8E8E');
	parkingTotalIssueChart.setAxisNameX('');
	parkingTotalIssueChart.setAxisNameY('');
	parkingTotalIssueChart.setAxisColor('#C4C4C4');
	parkingTotalIssueChart.setAxisNameFontSize(6);
	parkingTotalIssueChart.setAxisValuesFontSize(6);
	parkingTotalIssueChart.setAxisNameColor('#999');
	parkingTotalIssueChart.setAxisValuesColor('#7E7E7E');
	parkingTotalIssueChart.setBarValuesColor('#7E7E7E');
	parkingTotalIssueChart.setAxisPaddingTop(50);
	parkingTotalIssueChart.setAxisPaddingRight(40);
	parkingTotalIssueChart.setAxisPaddingLeft(40);
	parkingTotalIssueChart.setAxisPaddingBottom(40);
	parkingTotalIssueChart.setTextPaddingLeft(10);
	parkingTotalIssueChart.setTitleFontSize(8);
	parkingTotalIssueChart.setBarBorderWidth(1);
	parkingTotalIssueChart.setBarBorderColor('#C4C4C4');
	parkingTotalIssueChart.setBarSpacingRatio(40);
	parkingTotalIssueChart.setBarValuesFontSize(6);
	parkingTotalIssueChart.setGrid(false);
	parkingTotalIssueChart.setSize(125, 200);
	parkingTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	parkingTotalIssueChart.draw();


	/*** PARKING & TRAFFIC ISSUE PER TYPE GRAPH ***/

	var parkingIssuePerTypeLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var parkingIssuePerTypeData = new Array(3,0,32,1,1,1);
	
	var color = Chart.helpers.color;
	var parkingIssuePerTypeChartData = {
		labels: parkingIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL ISSUE PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  parkingIssuePerTypeData
		}]
	};

	var parkingIssuePerTypeChart = document.getElementById('parking-type-issue').getContext('2d');
	window.parkingIssuePerTypeBar = new Chart(parkingIssuePerTypeChart, {
		type: 'bar',
		data: parkingIssuePerTypeChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});

	/*	
	var parkingIssuePerType = new Array(['Incident Report', 0], ['Glitch', 0], ['Lost & Found', 1], ['Defect List', 0], ['Unsafe Condition', 1], ['Nearly Miss', 0]);
	var parkingIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var parkingIssuePerTypeChart = new JSChart('parking-type-issue', 'bar');
	parkingIssuePerTypeChart.setDataArray(parkingIssuePerType);
	parkingIssuePerTypeChart.colorizeBars(parkingIssuePerTypeColors);
	parkingIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	parkingIssuePerTypeChart.setTitleColor('#8E8E8E');
	parkingIssuePerTypeChart.setAxisNameX('');
	parkingIssuePerTypeChart.setAxisNameY('');
	parkingIssuePerTypeChart.setAxisColor('#C4C4C4');
	parkingIssuePerTypeChart.setAxisNameFontSize(6);
	parkingIssuePerTypeChart.setAxisValuesFontSize(6);
	parkingIssuePerTypeChart.setAxisNameColor('#999');
	parkingIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	parkingIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	parkingIssuePerTypeChart.setAxisPaddingTop(50);
	parkingIssuePerTypeChart.setAxisPaddingRight(40);
	parkingIssuePerTypeChart.setAxisPaddingLeft(40);
	parkingIssuePerTypeChart.setAxisPaddingBottom(40);
	parkingIssuePerTypeChart.setTextPaddingLeft(10);
	parkingIssuePerTypeChart.setTitleFontSize(8);
	parkingIssuePerTypeChart.setBarBorderWidth(1);
	parkingIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	parkingIssuePerTypeChart.setBarSpacingRatio(40);
	parkingIssuePerTypeChart.setBarValuesFontSize(6);
	parkingIssuePerTypeChart.setGrid(false);
	parkingIssuePerTypeChart.setSize(460, 200);
	parkingIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	parkingIssuePerTypeChart.draw();


	/*** PARKING & TRAFFIC OPEN & CLOSE ISSUE PER CATEGORY ***/

	var parkingOpenCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var parkingOpenedIssueData = new Array(0,0,1,0,1,0);
	var parkingClosedIssueData = new Array(3,0,31,1,0,1);
	
	var color = Chart.helpers.color;
	var parkingOpenCloseIssueChartData = {
		labels: parkingOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  parkingOpenedIssueData
		},{
			label: 'CLOSED ISSUE',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  parkingClosedIssueData
		}]
	};

	var parkingOpenCloseIssueChart = document.getElementById('parking-open-close').getContext('2d');
	window.parkingOpenCloseIssueBar = new Chart(parkingOpenCloseIssueChart, {
		type: 'bar',
		data: parkingOpenCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/***** HOUSEKEEPING *****/

	/*** HOUSEKEEPING TOTAL ISSUE GRAPH ***/

	var housekeepingTotalIssueLabel = new Array();
	var housekeepingTotalIssueData = new Array();
	housekeepingTotalIssueLabel[0] = "Total Kaizen";
	housekeepingTotalIssueData[0] = "85";
	
	var color = Chart.helpers.color;
	var housekeepingTotalIssueChartData = {
		labels: housekeepingTotalIssueLabel,
		datasets: [{
			label: 'TOTAL ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  housekeepingTotalIssueData
		}]
	};

	var housekeepingTotalIssueChart = document.getElementById('hk-total-issue').getContext('2d');
	window.housekeepingTotalIssueBar = new Chart(housekeepingTotalIssueChart, {
		type: 'bar',
		data: housekeepingTotalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/

	var housekeepingIssuePerTypeLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var housekeepingIssuePerTypeData = new Array(1,1,2,33,38,10);
	
	var color = Chart.helpers.color;
	var housekeepingIssuePerTypeChartData = {
		labels: housekeepingIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL ISSUE PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  housekeepingIssuePerTypeData
		}]
	};

	var housekeepingIssuePerTypeChart = document.getElementById('hk-type-issue').getContext('2d');
	window.housekeepingIssuePerTypeBar = new Chart(housekeepingIssuePerTypeChart, {
		type: 'bar',
		data: housekeepingIssuePerTypeChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** HOUSEKEEPING OPEN & CLOSE ISSUE PER CATEGORY ***/

	var housekeepingOpenCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var housekeepingOpenedIssueData = new Array(0,0,0,0,0,0);
	var housekeepingClosedIssueData = new Array(1,1,2,33,38,10);
	
	var color = Chart.helpers.color;
	var housekeepingOpenCloseIssueChartData = {
		labels: housekeepingOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  housekeepingOpenedIssueData
		},{
			label: 'CLOSED ISSUE',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  housekeepingClosedIssueData
		}]
	};

	var housekeepingOpenCloseIssueChart = document.getElementById('hk-open-close').getContext('2d');
	window.housekeepingOpenCloseIssueBar = new Chart(housekeepingOpenCloseIssueChart, {
		type: 'bar',
		data: housekeepingOpenCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	/***** ENGINEERING *****/

	/*** ENGINEERING TOTAL ISSUE GRAPH ***/

	var engineeringTotalIssueLabel = new Array();
	var engineeringTotalIssueData = new Array();
	engineeringTotalIssueLabel[0] = "Total Kaizen";
	engineeringTotalIssueData[0] = "72";
	
	var color = Chart.helpers.color;
	var engineeringTotalIssueChartData = {
		labels: engineeringTotalIssueLabel,
		datasets: [{
			label: 'TOTAL ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  engineeringTotalIssueData
		}]
	};

	var engineeringTotalIssueChart = document.getElementById('engineering-total-issue').getContext('2d');
	window.engineeringTotalIssueBar = new Chart(engineeringTotalIssueChart, {
		type: 'bar',
		data: engineeringTotalIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});



	/*** ENGINEERING ISSUE PER TYPE GRAPH ***/

	var engineeringIssuePerTypeLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var engineeringIssuePerTypeData = new Array(1,0,0,70,1,0);
	
	var color = Chart.helpers.color;
	var engineeringIssuePerTypeChartData = {
		labels: engineeringIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL ISSUE PER TYPE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  engineeringIssuePerTypeData
		}]
	};

	var engineeringIssuePerTypeChart = document.getElementById('engineering-type-issue').getContext('2d');
	window.engineeringIssuePerTypeBar = new Chart(engineeringIssuePerTypeChart, {
		type: 'bar',
		data: engineeringIssuePerTypeChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'TOTAL ISSUE PER TYPE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});



	/*** ENGINEERING OPEN & CLOSE ISSUE PER CATEGORY ***/

	var engineeringOpenCloseIssueLabel = new Array('Incident Report', 'Glitch', 'Lost & Found', 'Defect List', 'Unsafe Condition', 'Nearly Miss');
	var engineeringOpenedIssueData = new Array(0,0,0,1,0,0);
	var engineeringClosedIssueData = new Array(1,0,0,69,1,0);
	
	var color = Chart.helpers.color;
	var engineeringOpenCloseIssueChartData = {
		labels: engineeringOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED ISSUE',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  engineeringOpenedIssueData
		},{
			label: 'CLOSED ISSUE',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  engineeringClosedIssueData
		}]
	};

	var engineeringOpenCloseIssueChart = document.getElementById('engineering-open-close').getContext('2d');
	window.engineeringOpenCloseIssueBar = new Chart(engineeringOpenCloseIssueChart, {
		type: 'bar',
		data: engineeringOpenCloseIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'OPENED & CLOSED ISSUE',
				padding: 25
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
						fontSize: 9
					}
				}]
			}
		}
	});


	$("#export-issue-stat").click(function() {
		$("body").mLoading();
		var total_issue = document.getElementById("JSChart_total-issue");
		var type_issue = document.getElementById("JSChart_type-issue");
		var open_close = document.getElementById("JSChart_open-close");
		var sec_total_issue = document.getElementById("JSChart_security-total-issue");
		var sec_type_issue = document.getElementById("JSChart_security-type-issue");
		var sec_open_close = document.getElementById("JSChart_security-open-close");
		var saf_total_issue = document.getElementById("JSChart_safety-total-issue");
		var saf_type_issue = document.getElementById("JSChart_safety-type-issue");
		var saf_open_close = document.getElementById("JSChart_safety-open-close");
		var park_total_issue = document.getElementById("JSChart_parking-total-issue");
		var park_type_issue = document.getElementById("JSChart_parking-type-issue");
		var park_open_close = document.getElementById("JSChart_parking-open-close");
		var park_weekend = document.getElementById("JSChart_parking-weekend");
		var hk_total_issue = document.getElementById("JSChart_hk-total-issue");
		var hk_type_issue = document.getElementById("JSChart_hk-type-issue");
		var hk_open_close = document.getElementById("JSChart_hk-open-close");
		var eng_total_issue = document.getElementById("JSChart_engineering-total-issue");
		var eng_type_issue = document.getElementById("JSChart_engineering-type-issue");
		var eng_open_close = document.getElementById("JSChart_engineering-open-close");

		$.ajax({
			method: 'POST',
			url: '/default/statistic/savegraph',
			data: {
				all_total_issue: total_issue.toDataURL("image/png"),
				all_type_issue: type_issue.toDataURL("image/png"),
				all_open_close: open_close.toDataURL("image/png"),
				all_open_close: open_close.toDataURL("image/png"),
				sec_total_issue: sec_total_issue.toDataURL("image/png"),
				sec_type_issue: sec_type_issue.toDataURL("image/png"),
				sec_open_close: sec_open_close.toDataURL("image/png"),
				saf_total_issue: saf_total_issue.toDataURL("image/png"),
				saf_type_issue: saf_type_issue.toDataURL("image/png"),
				saf_open_close: saf_open_close.toDataURL("image/png"),
				park_total_issue: park_total_issue.toDataURL("image/png"),
				park_type_issue: park_type_issue.toDataURL("image/png"),
				park_open_close: park_open_close.toDataURL("image/png"),
				hk_total_issue: hk_total_issue.toDataURL("image/png"),
				hk_type_issue: hk_type_issue.toDataURL("image/png"),
				hk_open_close: hk_open_close.toDataURL("image/png"),
				eng_total_issue: eng_total_issue.toDataURL("image/png"),
				eng_type_issue: eng_type_issue.toDataURL("image/png"),
				eng_open_close: eng_open_close.toDataURL("image/png")
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exporttopdf/cd/'+data+'/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
				} else {
					window.open("/default/statistic/exporttopdf/cd/"+data+"/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
				}
				$("body").mLoading('hide');
			}
		});
		
			
	});
});	
</script>