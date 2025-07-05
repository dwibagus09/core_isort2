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
			
			<?php if($this->site_id == 2) { ?>
				<div class="graph-title">SECURITY, SAFETY, PARKING</div>
				<div class="graph">
					<canvas id="sec-total-issue" style="width:150px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="sec-type-issue" style="width:460px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="sec-open-close" style="width:460px; height:225px;"></canvas>
				</div>
				
				<div class="graph-title">FIT OUT</div>
				<div class="graph">
					<canvas id="park-total-issue" style="width:150px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="park-type-issue" style="width:460px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="park-open-close" style="width:460px; height:225px;"></canvas>
				</div>

				<div class="graph-title">TENANT RELATION</div>
				<div class="graph">
					<canvas id="tr-total-issue" style="width:150px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="tr-type-issue" style="width:460px; height:225px;"></canvas>
				</div>
				<div class="graph">
					<canvas id="tr-open-close" style="width:460px; height:225px;"></canvas>
				</div>
			<?php } ?>
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

	/*** TOTAL KAIZEN GRAPH ***/

	var totalIssueLabel = new Array();
	var totalIssueData = new Array();
	totalIssueLabel[0] = "Total Kaizen";
	totalIssueData[0] = "<?php echo intval($this->totalAllIssue['total']); ?>";
	
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
	
	
/*** KAIZEN PER TYPE GRAPH ***/

	<?php if($this->site_id == 1) { ?>
		var typeIssueLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var typeIssueData = new Array(<?php echo intval($this->totalGeneralChecklist['total']); ?>, <?php echo intval($this->totalRoomChecklist['total']); ?>, <?php echo intval($this->totalVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var typeIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var typeIssueData = new Array(<?php echo intval($this->totalGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngineeringChecklist['total']); ?>, <?php echo intval($this->totalSafety['total']); ?>);
	<?php } ?>
	
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

	/*** OPEN & CLOSE KAIZEN PER CATEGORY ***/
	
	
	<?php if($this->site_id == 1) { ?>
		var openCloseIssueLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var openedIssueData = new Array(<?php echo intval($this->totalOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalOpenRoomChecklist['total']); ?>, <?php echo intval($this->totalOpenVillaChecklist['total']); ?>);
		var closedIssueData = new Array(<?php echo intval($this->totalCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalCloseRoomChecklist['total']); ?>, <?php echo intval($this->totalCloseVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var openCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var openedIssueData = new Array(<?php echo intval($this->totalOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalOpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalOpenSafety['total']); ?>);
		var closedIssueData = new Array(<?php echo intval($this->totalCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalCloseSafety['total']); ?>);
	<?php } ?>
	
	var color = Chart.helpers.color;
	var openCloseIssueChartData = {
		labels: openCloseIssueLabel,
		datasets: [{
			label: 'OPENED KAIZEN',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  openedIssueData
		},{
			label: 'CLOSED KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
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


	/***** HOUSEKEEPING *****/

	/*** HOUSEKEEPING TOTAL KAIZEN GRAPH ***/

	var housekeepingTotalIssueLabel = new Array();
	var housekeepingTotalIssueData = new Array();
	housekeepingTotalIssueLabel[0] = "Total Kaizen";
	housekeepingTotalIssueData[0] = "<?php echo intval($this->totalAllHKIssue['total']); ?>";
	
	var color = Chart.helpers.color;
	var housekeepingTotalIssueChartData = {
		labels: housekeepingTotalIssueLabel,
		datasets: [{
			label: 'TOTAL KAIZEN',
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


	/*** HOUSEKEEPING KAIZEN PER TYPE GRAPH ***/
	
	<?php if($this->site_id == 1) { ?>
		var housekeepingIssuePerTypeLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var housekeepingIssuePerTypeData = new Array(<?php echo intval($this->totalHKGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKRoomChecklist['total']); ?>, <?php echo intval($this->totalHKVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var housekeepingIssuePerTypeLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var housekeepingIssuePerTypeData = new Array(<?php echo intval($this->totalHKGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKEngineeringChecklist['total']); ?>, <?php echo intval($this->totalHKSafety['total']); ?>);
	<?php } ?>	
	
	var color = Chart.helpers.color;
	var housekeepingIssuePerTypeChartData = {
		labels: housekeepingIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL KAIZEN PER TYPE',
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

	/*** HOUSEKEEPING OPEN & CLOSE KAIZEN PER CATEGORY ***/

	<?php if($this->site_id == 1) { ?>
		var housekeepingOpenCloseIssueLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var housekeepingOpenedIssueData = new Array(<?php echo intval($this->totalHKOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKOpenRoomChecklist['total']); ?>, <?php echo intval($this->totalHKOpenVillaChecklist['total']); ?>);
		var housekeepingClosedIssueData = new Array(<?php echo intval($this->totalHKCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKCloseRoomChecklist['total']); ?>, <?php echo intval($this->totalHKCloseVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var housekeepingOpenCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var housekeepingOpenedIssueData = new Array(<?php echo intval($this->totalHKOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKOpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalHKOpenSafety['total']); ?>);
		var housekeepingClosedIssueData = new Array(<?php echo intval($this->totalHKCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalHKCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalHKCloseSafety['total']); ?>);
	<?php } ?>	
	
	var color = Chart.helpers.color;
	var housekeepingOpenCloseIssueChartData = {
		labels: housekeepingOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED KAIZEN',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  housekeepingOpenedIssueData
		},{
			label: 'CLOSED KAIZEN',			
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
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


	/***** ENGINEERING *****/

	/*** ENGINEERING TOTAL KAIZEN GRAPH ***/

	var engineeringTotalIssueLabel = new Array();
	var engineeringTotalIssueData = new Array();
	engineeringTotalIssueLabel[0] = "Total Kaizen";
	engineeringTotalIssueData[0] = "<?php echo intval($this->totalAllEngineeringIssue['total']); ?>";
	
	var color = Chart.helpers.color;
	var engineeringTotalIssueChartData = {
		labels: engineeringTotalIssueLabel,
		datasets: [{
			label: 'TOTAL KAIZEN',
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



	/*** ENGINEERING KAIZEN PER TYPE GRAPH ***/

	<?php if($this->site_id == 1) { ?>
		var engineeringIssuePerTypeLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var engineeringIssuePerTypeData = new Array(<?php echo intval($this->totalEngineeringGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngineeringRoomChecklist['total']); ?>, <?php echo intval($this->totalEngineeringVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var engineeringIssuePerTypeLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var engineeringIssuePerTypeData = new Array(<?php echo intval($this->totalEngineeringGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngEngineeringChecklist['total']); ?>, <?php echo intval($this->totalEngSafety['total']); ?>);
	<?php } ?>	
	
	var color = Chart.helpers.color;
	var engineeringIssuePerTypeChartData = {
		labels: engineeringIssuePerTypeLabel,
		datasets: [{
			label: 'TOTAL KAIZEN PER TYPE',
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



	/*** ENGINEERING OPEN & CLOSE KAIZEN PER CATEGORY ***/
	
	<?php if($this->site_id == 1) { ?>
		var engineeringOpenCloseIssueLabel = new Array('General Checklist', 'Room Checklist', 'Villa Checklist');
		var engineeringOpenedIssueData = new Array(<?php echo intval($this->totalEngineeringOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngineeringOpenRoomChecklist['total']); ?>, <?php echo intval($this->totalEngineeringOpenVillaChecklist['total']); ?>);
		var engineeringClosedIssueData = new Array(<?php echo intval($this->totalEngineeringCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngineeringCloseRoomChecklist['total']); ?>, <?php echo intval($this->totalEngineeringCloseVillaChecklist['total']); ?>);
	<?php } else if($this->site_id == 2) { ?>
		var engineeringOpenCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var engineeringOpenedIssueData = new Array(<?php echo intval($this->totalEngineeringOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngOpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalEngOpenSafety['total']); ?>);
		var engineeringClosedIssueData = new Array(<?php echo intval($this->totalEngineeringCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalEngCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalEngCloseSafety['total']); ?>);
	<?php } ?>	

	
	
	var color = Chart.helpers.color;
	var engineeringOpenCloseIssueChartData = {
		labels: engineeringOpenCloseIssueLabel,
		datasets: [{
			label: 'OPENED KAIZEN',
			backgroundColor: '#a1a2a6',
			borderColor: '#a1a2a6',
			borderWidth: 1,
			data:  engineeringOpenedIssueData
		},{
			label: 'CLOSED KAIZEN',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
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
	
	
	<?php if($this->site_id == 2) { ?>
	
		/***** SECURITY *****/

		/*** SECURITY TOTAL KAIZEN GRAPH ***/

		var securityTotalIssueLabel = new Array();
		var securityTotalIssueData = new Array();
		securityTotalIssueLabel[0] = "Total Kaizen";
		securityTotalIssueData[0] = "<?php echo intval($this->totalAllSecurityIssue['total']); ?>";
		
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

		var securityTotalIssueChart = document.getElementById('sec-total-issue').getContext('2d');
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


		/*** SECURITY KAIZEN PER TYPE GRAPH ***/

		var securityIssuePerTypeLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var securityIssuePerTypeData = new Array(<?php echo intval($this->totalSecGeneralChecklist['total']); ?>, <?php echo intval($this->totalSecEngineeringChecklist['total']); ?>, <?php echo intval($this->totalSecSafety['total']); ?>);
		
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

		var securityIssuePerTypeChart = document.getElementById('sec-type-issue').getContext('2d');
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

		/*** SECURITY OPEN & CLOSE KAIZEN PER CATEGORY ***/

		var securityOpenCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var securityOpenedIssueData = new Array(<?php echo intval($this->totalSecOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalSecOpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalSecOpenSafety['total']); ?>);
		var securityClosedIssueData = new Array(<?php echo intval($this->totalSecCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalSecCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalSecCloseSafety['total']); ?>);
		
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

		var securityOpenCloseIssueChart = document.getElementById('sec-open-close').getContext('2d');
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
		
		/***** FIT OUT *****/

		/*** FIT OUT TOTAL KAIZEN GRAPH ***/

		var parkingTotalIssueLabel = new Array();
		var parkingTotalIssueData = new Array();
		parkingTotalIssueLabel[0] = "Total Kaizen";
		parkingTotalIssueData[0] = "<?php echo intval($this->totalAllParkingIssue['total']); ?>";
		
		var color = Chart.helpers.color;
		var parkingTotalIssueChartData = {
			labels: parkingTotalIssueLabel,
			datasets: [{
				label: 'TOTAL KAIZEN',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  parkingTotalIssueData
			}]
		};

		var parkingTotalIssueChart = document.getElementById('park-total-issue').getContext('2d');
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


		/*** FIT OUT KAIZEN PER TYPE GRAPH ***/

		var parkingIssuePerTypeLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var parkingIssuePerTypeData = new Array(<?php echo intval($this->totalParkGeneralChecklist['total']); ?>, <?php echo intval($this->totalParkEngineeringChecklist['total']); ?>, <?php echo intval($this->totalParkSafety['total']); ?>);
		
		var color = Chart.helpers.color;
		var parkingIssuePerTypeChartData = {
			labels: parkingIssuePerTypeLabel,
			datasets: [{
				label: 'TOTAL KAIZEN PER TYPE',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  parkingIssuePerTypeData
			}]
		};

		var parkingIssuePerTypeChart = document.getElementById('park-type-issue').getContext('2d');
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

		/*** FIT OUT OPEN & CLOSE KAIZEN PER CATEGORY ***/

		var parkingOpenCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var parkingOpenedIssueData = new Array(<?php echo intval($this->totalParkOpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalParkOpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalParkOpenSafety['total']); ?>);
		var parkingClosedIssueData = new Array(<?php echo intval($this->totalParkCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalParkCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalParkCloseSafety['total']); ?>);
		
		var color = Chart.helpers.color;
		var parkingOpenCloseIssueChartData = {
			labels: parkingOpenCloseIssueLabel,
			datasets: [{
				label: 'OPENED KAIZEN',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  parkingOpenedIssueData
			},{
				label: 'CLOSED KAIZEN',
				backgroundColor: '#a1a2a6',
				borderColor: '#a1a2a6',
				borderWidth: 1,
				data:  parkingClosedIssueData
			}]
		};

		var parkingOpenCloseIssueChart = document.getElementById('park-open-close').getContext('2d');
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
		
		/***** TENANT RELATION *****/

		/*** TENANT RELATION TOTAL KAIZEN GRAPH ***/

		var trTotalIssueLabel = new Array();
		var trTotalIssueData = new Array();
		trTotalIssueLabel[0] = "Total Kaizen";
		trTotalIssueData[0] = "<?php echo intval($this->totalAllTRIssue['total']); ?>";
		
		var color = Chart.helpers.color;
		var trTotalIssueChartData = {
			labels: trTotalIssueLabel,
			datasets: [{
				label: 'TOTAL KAIZEN',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  trTotalIssueData
			}]
		};

		var trTotalIssueChart = document.getElementById('tr-total-issue').getContext('2d');
		window.trTotalIssueBar = new Chart(trTotalIssueChart, {
			type: 'bar',
			data: trTotalIssueChartData,
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


		/*** TENANT RELATION KAIZEN PER TYPE GRAPH ***/

		var trIssuePerTypeLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var trIssuePerTypeData = new Array(<?php echo intval($this->totalTRGeneralChecklist['total']); ?>, <?php echo intval($this->totalTREngineeringChecklist['total']); ?>, <?php echo intval($this->totalTRSafety['total']); ?>);
		
		var color = Chart.helpers.color;
		var trIssuePerTypeChartData = {
			labels: trIssuePerTypeLabel,
			datasets: [{
				label: 'TOTAL KAIZEN PER TYPE',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  trIssuePerTypeData
			}]
		};

		var trIssuePerTypeChart = document.getElementById('tr-type-issue').getContext('2d');
		window.trIssuePerTypeBar = new Chart(trIssuePerTypeChart, {
			type: 'bar',
			data: trIssuePerTypeChartData,
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

		/*** TENANT RELATION OPEN & CLOSE KAIZEN PER CATEGORY ***/

		var trOpenCloseIssueLabel = new Array('General Checklist', 'Engineering Checklist', 'Safety');
		var trOpenedIssueData = new Array(<?php echo intval($this->totalTROpenGeneralChecklist['total']); ?>, <?php echo intval($this->totalTROpenEngineeringChecklist['total']); ?>, <?php echo intval($this->totalTROpenSafety['total']); ?>);
		var trClosedIssueData = new Array(<?php echo intval($this->totalTRCloseGeneralChecklist['total']); ?>, <?php echo intval($this->totalTRCloseEngineeringChecklist['total']); ?>, <?php echo intval($this->totalTRCloseSafety['total']); ?>);
		
		var color = Chart.helpers.color;
		var trOpenCloseIssueChartData = {
			labels: trOpenCloseIssueLabel,
			datasets: [{
				label: 'OPENED KAIZEN',
				backgroundColor: '#9e824b',
				borderColor: '#9e824b',
				borderWidth: 1,
				data:  trOpenedIssueData
			},{
				label: 'CLOSED KAIZEN',
				backgroundColor: '#a1a2a6',
				borderColor: '#a1a2a6',
				borderWidth: 1,
				data:  trClosedIssueData
			}]
		};

		var trOpenCloseIssueChart = document.getElementById('tr-open-close').getContext('2d');
		window.trOpenCloseIssueBar = new Chart(trOpenCloseIssueChart, {
			type: 'bar',
			data: trOpenCloseIssueChartData,
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
	<?php } ?>	



	$("#export-issue-stat").click(function() {
		$("body").mLoading();
		var total_issue = document.getElementById("total-issue");
		var type_issue = document.getElementById("type-issue");
		var open_close = document.getElementById("open-close");
		var hk_total_issue = document.getElementById("hk-total-issue");
		var hk_type_issue = document.getElementById("hk-type-issue");
		var hk_open_close = document.getElementById("hk-open-close");
		var eng_total_issue = document.getElementById("engineering-total-issue");
		var eng_type_issue = document.getElementById("engineering-type-issue");
		var eng_open_close = document.getElementById("engineering-open-close");	
		<?php if($this->site_id == 2) { ?>
		var sec_total_issue = document.getElementById("sec-total-issue");
		var sec_type_issue = document.getElementById("sec-type-issue");
		var sec_open_close = document.getElementById("sec-open-close");
		var park_total_issue = document.getElementById("park-total-issue");
		var park_type_issue = document.getElementById("park-type-issue");
		var park_open_close = document.getElementById("park-open-close");
		var tr_total_issue = document.getElementById("tr-total-issue");
		var tr_type_issue = document.getElementById("tr-type-issue");
		var tr_open_close = document.getElementById("tr-open-close");
		<?php } ?>

		$.ajax({
			method: 'POST',
			url: '/default/statistic/savegraph',
			data: {
				all_total_issue: total_issue.toDataURL("image/png"),
				all_type_issue: type_issue.toDataURL("image/png"),
				all_open_close: open_close.toDataURL("image/png"),
				all_open_close: open_close.toDataURL("image/png"),
				hk_total_issue: hk_total_issue.toDataURL("image/png"),
				hk_type_issue: hk_type_issue.toDataURL("image/png"),
				hk_open_close: hk_open_close.toDataURL("image/png"),
				eng_total_issue: eng_total_issue.toDataURL("image/png"),
				eng_type_issue: eng_type_issue.toDataURL("image/png"),
				eng_open_close: eng_open_close.toDataURL("image/png"),
				<?php if($this->site_id == 2) { ?>
				sec_total_issue: sec_total_issue.toDataURL("image/png"),
				sec_type_issue: sec_type_issue.toDataURL("image/png"),
				sec_open_close: sec_open_close.toDataURL("image/png"),
				park_total_issue: park_total_issue.toDataURL("image/png"),
				park_type_issue: park_type_issue.toDataURL("image/png"),
				park_open_close: park_open_close.toDataURL("image/png"),
				tr_total_issue: tr_total_issue.toDataURL("image/png"),
				tr_type_issue: tr_type_issue.toDataURL("image/png"),
				tr_open_close: tr_open_close.toDataURL("image/png"),
				<?php } ?>
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