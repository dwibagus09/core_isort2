

			<?php if(!empty($this->msg)) echo '<div class="msg">'.$this->msg.'</div>'; ?>
			
			  <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
				  <div class="dashboard">						    
				  	<div class="dashboard-small">
				  	    <div class="dashboard-chart-title">Total Kaizen per Site</div>
        			    <canvas id="totalIssuesSite"></canvas>
        			</div>
					<div class="dashboard-xs">
						<div class="dashboard-chart-title">Top Kaizen Submitter</div>
						<div class="duration-dashboard">
							<span style="font-size:16px;"><?php echo $this->topKaizenSubmitter['name']; ?></span>
						</div>
						<br/><br/>
						<div class="dashboard-chart-title">Case Completion Duration</div>
						<div class="duration-dashboard">
							<span style="font-size:16px;"><?php echo $this->issuesAvgDuration[0]['duration'] . " days"; ?></span>
						</div>
						<br/><br/>
                                                <div class="dashboard-chart-title">Longest Closed Kaizen</div>
                                                <div class="duration-dashboard">
                                                        <span style="font-size:16px;"><?php echo $this->longestClosedKaizen . " days"; ?></span>
                                                </div>
					</div>
        			<div class="dashboard-small">
				  	    <div class="dashboard-chart-title">Total Kaizen per Department</div>
        			    <canvas id="totalIssuesDept"></canvas>
        			</div>
					
        			<?php /*<div class="dashboard-small">
				  	    <div class="dashboard-chart-title">Action Plan</div>
        			    <canvas id="actionPlan"></canvas>
        			</div>*/ ?>
        			<div class="dashboard-large">
				  	    <div class="dashboard-chart-title">Kaizen</div>
        			    <canvas id="totalOpenedIssues"></canvas>
        			</div>
        			<?php if(!empty($this->latestIssues)) { ?>
        			<div class="dashboard-large latest-kaizen">
        			    <div class="dashboard-chart-title">Latest Kaizen</div>
            			<div class="ticker">
                            <ul>
                                <?php foreach($this->latestIssues as $latestIssue) { 
                                        $title = $latestIssue['site_name']." - ".$latestIssue['issue_type'];
                                        if(!empty($latestIssue['kejadian']))  $title .= ": ".$latestIssue['kejadian'];
                                        if(!empty($latestIssue['modus']))  $title .= " - ".$latestIssue['modus'];
                                ?>
                                    <li onclick="location.href='/default/issue/listissues/s/<?php echo $latestIssue['site_id']; ?>/c/<?php echo $latestIssue['category_id']; ?>/id/<?php echo $latestIssue['issue_id']; ?>'"><a href="/default/issue/listissues/s/<?php echo $latestIssue['site_id']; ?>/c/<?php echo $latestIssue['category_id']; ?>/id/<?php echo $latestIssue['issue_id']; ?>"><img src="<?php echo $latestIssue['thumb_pic']; ?>" alt="Opened Image" /><strong><?php echo $title; ?></strong><p><?php echo $latestIssue['description']; ?></p><div class="latest-issue-date"><?php echo $latestIssue['issue_date_time']; ?></div></a></li>
                                <?php  } ?>
                            </ul>
                        </div>
                    </div>
                    <?php } ?>
				 </div> 	 
          </div>
          <br /> 
		 
        </div>
        <!-- /page content -->


<!--<script src="/js/amcharts4/core.js"></script>
<script src="/js/amcharts4/charts.js"></script>
<script src="/js/amcharts4/themes/animated.js"></script>-->
<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/plugin/chartjs-plugin-labels.js"></script>
<script type="text/javascript" src="/js/jquery_easy_ticker/dist/jquery.easy-ticker.min.js"></script>
<script type="text/javascript" src="/js/jquery.easing.min.js"></script>
<script type="text/javascript">
/*
am4core.ready(function() {

am4core.useTheme(am4themes_animated);

var chartData = {
  "<?php echo $this->totalIssues['total_issues']; ?>": [
      <?php foreach($this->totalIssuesSite as $totalIssuesSite) { ?>
        { "site": "<?php echo $totalIssuesSite['initial']; ?>", "Total Issue": <?php echo intval($totalIssuesSite['total_issues']); ?>},
      <?php } ?>
    ]
};

var chart = am4core.create("chartdiv", am4charts.PieChart);

chart.data = [
    <?php foreach($this->totalIssuesSite as $totalIssuesSite) { ?>
        { "site": "<?php echo $totalIssuesSite['initial']; ?>", "total": <?php echo intval($totalIssuesSite['total_issues']); ?>},
    <?php } ?>
];

chart.innerRadius = am4core.percent(50);
var label = chart.seriesContainer.createChild(am4core.Label);
label.text = "<?php echo $this->totalIssues['total_issues']; ?>";
label.horizontalCenter = "middle";
label.verticalCenter = "middle";
label.fontSize = 50;

var pieSeries = chart.series.push(new am4charts.PieSeries());
pieSeries.dataFields.value = "total";
pieSeries.dataFields.category = "site";
}); */

$(document).ready(function() {
    $('.ticker').easyTicker({
        direction: 'up'
    });
    
    var chartColor = ["#7e5b06", "#a07407", "#c19c40", "#d9b04a", "#B8860B", "#e1cb95", "#DDDDDD", "#CCCCCC", "#BBBBBB", "#AAAAAA"];
    
    /*** TOTAL ***

	var configTotal = {
		type: 'doughnut',
		data: {
			datasets: [{
				data: [
					<?php echo $this->totalIssues['total_issues']; ?>
				],
				backgroundColor: [
					chartColor[9],
				],
				label: 'Total Issues'
			}],
			labels: ['Total Issues']
		},
		options: {
			responsive: true,
			legend: {
				display: false,
			},			
			title: {
				display: true,
				text: 'TOTAL ISSUES'
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

	var totalChart = document.getElementById('totalAllIssues').getContext('2d');
	window.totalAllDoughnut = new Chart(totalChart, configTotal); */
    
    /*** TOTAL ISSUES PER SITES ***/
    <?php if(!empty($this->totalIssuesSite)) { ?>
    	var configTotal = {
    		type: 'doughnut',
    		data: {
    			datasets: [{
    				data: [
    				    <?php foreach($this->totalIssuesSite as $totalIssuesSite) {
    				        echo intval($totalIssuesSite['total_issues']).", ";
    				    } ?>
    				],
    				backgroundColor: [
    					chartColor[0], 
    					chartColor[1],
    					chartColor[2], 
    					chartColor[3], 
    					chartColor[4], 
    					chartColor[5], 					
    					chartColor[6]
    				],
    				label: 'Total Issues'
    			}],
    			labels: [
    			    <?php foreach($this->totalIssuesSite as $totalIssuesSite) {
				        echo '"'.$totalIssuesSite['initial'].'", ';
				    } ?>
    			]
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
    				display: false,
    				text: 'TOTAL ISSUES PER SITE'
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
    
    	var totalSiteChart = document.getElementById('totalIssuesSite').getContext('2d');
    	window.totalSiteDoughnut = new Chart(totalSiteChart, configTotal);
	<?php } ?>
	
	/*** TOTAL ISSUES ACCORDING TO DEPARTMENT ***/
	<?php if(!empty($this->totalIssuesDept)) { ?>
    	var configTotal = {
    		type: 'doughnut',
    		data: {
    			datasets: [{
    				data: [
    				    <?php foreach($this->totalIssuesDept as $totalIssuesDept) {
    				        echo intval($totalIssuesDept['total_issues']).", ";
    				    } ?>
    				],
    				backgroundColor: [
    					chartColor[0], 
    					chartColor[1],
    					chartColor[2], 
    					chartColor[3], 
    					chartColor[4], 
    					chartColor[5], 					
    					chartColor[6]
    				],
    				label: 'Total Issues Per Department'
    			}],
    			labels: [
    			    <?php foreach($this->totalIssuesDept as $totalIssuesDept) {
				        echo '"'.$totalIssuesDept['category_name'].'", ';
				    } ?>
    			]
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
    				display: false,
    				text: 'TOTAL ISSUES PER DEPARTMENT'
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
    
    	var totalDeptChart = document.getElementById('totalIssuesDept').getContext('2d');
    	window.totalDeptDoughnut = new Chart(totalDeptChart, configTotal);
	<?php } ?>
	
	/*** ACTION PLAN ***
	
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
					chartColor[0], 
    				chartColor[1],
					chartColor[2], 
					chartColor[3], 
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
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			}
		}
	};

	var ctx = document.getElementById('actionPlan').getContext('2d');
	window.doughnut = new Chart(ctx, config);*/
	
	/*** TOTAL OPENED ISSUES ***/

    <?php if(!empty($this->totalOpenedIssues)) { ?>
    	var openedIssuesLabel = new Array();
    	var openedIssuesData = new Array();
    	var closedIssuesData = new Array();
    	<?php $openedIssuesCtr = 0;
			$totalIssuesHighest = 0;
    	 	foreach($this->totalOpenedIssues as $oi) { 
    			echo 'openedIssuesLabel['.$openedIssuesCtr.'] = "'.$oi['initial'].'";';
    			echo 'openedIssuesData['.$openedIssuesCtr.'] = '.$oi['total_issues'].';';
    			$openedIssuesCtr++;
				if($oi['total_issues'] > $totalIssuesHighest) $totalIssuesHighest = $oi['total_issues'];
    	    } 
    	    $closedIssuesCtr = 0;
    	 	foreach($this->totalClosedIssues as $ci) { 
    			echo 'closedIssuesData['.$closedIssuesCtr.'] = '.$ci['total_issues'].';';
    			$closedIssuesCtr++;
				if($ci['total_issues'] > $totalIssuesHighest) $totalIssuesHighest = $ci['total_issues'];
    	    }

			$totalIssuesOff = $totalIssuesHighest % 5;
			if($totalIssuesOff == 0) $totalIssuesHighest = $totalIssuesHighest + 5;
			else $totalIssuesHighest = $totalIssuesHighest + (5 - $totalIssuesOff);
    	?>
    	
    	var color = Chart.helpers.color;
    	var openedIssuesChartData = {
    		labels: openedIssuesLabel,
    		datasets: [{
    		    type: 'bar',
    			label: 'Opened Kaizen',
    			backgroundColor: color(chartColor[7]).alpha(0.5).rgbString(),
    			borderColor: chartColor[7],
    			borderWidth: 1,
    			data: openedIssuesData
    		}, {
    		    type: 'bar',
    			label: 'Closed Kaizen',
				backgroundColor: color(chartColor[4]).alpha(0.5).rgbString(),
    			borderColor: chartColor[4],
    			borderWidth: 1,
    			data: closedIssuesData
    		}]
    	};
    
    	var openedIssuesChart = document.getElementById('totalOpenedIssues').getContext('2d');
    	window.openedIssuesBar = new Chart(openedIssuesChart, {
    		type: 'bar',
    		data: openedIssuesChartData,
    		options: {
    			responsive: true,
    			legend: {
    				display: true,
    			},
    			title: {
    				display: false,
    				text: 'OPENED KAIZEN'
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
    				}],
					yAxes: [{
						display: true,
						ticks: {
							beginAtZero: true,
							max: <?php echo $totalIssuesHighest; ?>,
							min: 0
						}
					}]
    			}
    		}
    	});
    <?php } ?>
    
    /*** TOTAL CLOSED ISSUES ***

    <?php if(!empty($this->totalClosedIssues)) { ?>
    	var closedIssuesLabel = new Array();
    	var closedIssuesData = new Array();
    	<?php $closedIssuesCtr = 0;
    	 	foreach($this->totalClosedIssues as $ci) { 
    			echo 'closedIssuesLabel['.$closedIssuesCtr.'] = "'.$ci['initial'].'";';
    			echo 'closedIssuesData['.$closedIssuesCtr.'] = '.$ci['total_issues'].';';
    			$closedIssuesCtr++;
    	    } ?>
    	
    	var color = Chart.helpers.color;
    	var closedIssuesChartData = {
    		labels: closedIssuesLabel,
    		datasets: [{
    			label: 'Closed Issues',
    			backgroundColor: color(chartColor[5]).alpha(0.5).rgbString(),
    			borderColor: chartColor[8],
    			borderWidth: 1,
    			data: closedIssuesData
    		}]
    	};
    
    	var closedIssuesChart = document.getElementById('totalClosedIssues').getContext('2d');
    	window.closedIssuesBar = new Chart(closedIssuesChart, {
    		type: 'bar',
    		data: closedIssuesChartData,
    		options: {
    			responsive: true,
    			legend: {
    				display: false,
    			},
    			title: {
    				display: true,
    				text: 'CLOSED ISSUES'
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
    <?php } ?> */
});	
</script>

