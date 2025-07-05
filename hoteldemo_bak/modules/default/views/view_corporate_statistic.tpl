<link rel="stylesheet" href="/css/jquery-ui.min.css">

<div id="user-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle">Corporate User Statistic</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/corporate"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-corporate-stat" name="view-corporate-stat" value="Go" style="width:50px; margin-top:0px;" class="form-btn"> <input type="button" id="export-corporate-stat" name="export-corporate-stat" value="Export to PDF" style="width:110px; margin-top:0px;" class="form-btn"></div>
			</form>
		</div>
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Security</h4>
			<div class="total-people-corp-stat">Total Security: <?php echo $this->totalSecurity; ?> people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<?php if(!empty($this->usersSec)) { $i=1; foreach($this->usersSec as $use) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $use['name']; ?></td>
					<td align="center"><?php echo $use['initial']; ?></td>
					<td align="center"><?php echo intval($use['total_login']); ?></td>
					<td align="center"><?php echo $use['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Safety</h4>
			<div class="total-people-corp-stat">Total Safety: <?php echo $this->totalSafety; ?> people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<?php if(!empty($this->usersSaf)) { $i=1; foreach($this->usersSaf as $usa) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $usa['name']; ?></td>
					<td align="center"><?php echo $usa['initial']; ?></td>
					<td align="center"><?php echo intval($usa['total_login']); ?></td>
					<td align="center"><?php echo $usa['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Housekeeping</h4>
			<div class="total-people-corp-stat">Total Housekeeping: <?php echo $this->totalHousekeeping; ?> people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th> 
					<th width="155">Last Login</th>
				</tr>
				<?php if(!empty($this->usersHk)) { $i=1; foreach($this->usersHk as $uhk) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $uhk['name']; ?></td>
					<td align="center"><?php echo $uhk['initial']; ?></td>
					<td align="center"><?php echo intval($uhk['total_login']); ?></td>
					<td align="center"><?php echo $uhk['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Engineering</h4>
			<div class="total-people-corp-stat">Total Engineering: <?php echo $this->totalEngineering; ?> people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<?php if(!empty($this->usersEng)) { $i=1; foreach($this->usersEng as $ue) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $ue['name']; ?></td>
					<td align="center"><?php echo $ue['initial']; ?></td>
					<td align="center"><?php echo intval($ue['total_login']); ?></td>
					<td align="center"><?php echo $ue['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	
		
		<div class="user-stat col-md-5 col-sm-5 col-xs-12" style="min-height:auto;">
			<h4>Users Statistic for All Sites</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table style="margin-bottom:8px;">
				<tr>
					<th width="70" height="33">Sites</th>
					<th>By Login</th>
					<th>By Submitting Issues</th>
					<th>By Comments</th>
				</tr>
				<?php if(!empty($this->userStatisticSummary)) { $k=1; foreach($this->userStatisticSummary as  $userStatisticSummary) { ?>
				<tr>
					<th height="33"><?php echo $userStatisticSummary['initial']; ?></th>
					<td align="center"><?php echo $userStatisticSummary['total_login']; ?></td>
					<td align="center"><?php echo $userStatisticSummary['total_issues']; ?></td>
					<td align="center"><?php echo $userStatisticSummary['total_comments']; ?></td>
				</tr>
				<?php } } ?>
			</table>
		</div>	

		<div id="graph" style="clear:both;">
			<?php /*<div id="user-stat-login" class="graph">Loading User Statistic By Login...</div>
			<div id="user-stat-issues" class="graph">Loading User Statistic By Submitting Kaizen...</div>
			<div id="user-stat-comments" class="graph">Loading User Statistic By Comments...</div>
			<div id="security-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>
			<div id="safety-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>
			<div id="parking-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>*/ ?>

			<div class="corporate-user-stat">
				<canvas id="userStatLogin"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="userStatSubmitIssue"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="userStatComment"></canvas>
			</div>
		</div>
		
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
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#export-corporate-stat").click(function() {
		$("body").mLoading();
		var login = document.getElementById("userStatLogin");
		var issue = document.getElementById("userStatSubmitIssue");
		var comment = document.getElementById("userStatComment");
		/*var apreschedulesec = document.getElementById("securityReschedule");
		var prevmaintreschedule = document.getElementById("prevMaintReschedule");*/
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveusergraph',
			data: {
				login: login.toDataURL("image/png"),
				issue: issue.toDataURL("image/png"),
				comment: comment.toDataURL("image/png")/*,
				apreschedulesec: apreschedulesec.toDataURL("image/png"),
				prevmaintreschedule: prevmaintreschedule.toDataURL("image/png")*/
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exportcorporatestatistictopdf/cd/'+data+'/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
				} else {
					window.open("/default/statistic/exportcorporatestatistictopdf/cd/"+data+"/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
				}	
				$("body").mLoading('hide');	
			}
		});
	});	

	/*** Master User Statistic 
	var masterUserStat = new Array(['By Login', 236, 177, 137, 	98, 75], ['By Submitting Issue', 168, 109, 83, 66, 42], ['By Comments', 103, 92, 61, 47, 34]);
	var masterUserStatChart = new JSChart('user-statistic-all-sites', 'bar');
	masterUserStatChart.setDataArray(masterUserStat);
	masterUserStatChart.setTitle('User Statistic for All Sites');
	masterUserStatChart.setTitleColor('#8E8E8E');
	masterUserStatChart.setAxisNameX('');
	masterUserStatChart.setAxisNameY('');
	masterUserStatChart.setAxisNameFontSize(6);
	masterUserStatChart.setAxisValuesFontSize(6);
	masterUserStatChart.setAxisNameColor('#999');
	masterUserStatChart.setAxisValuesColor('#777');
	masterUserStatChart.setAxisColor('#B5B5B5');
	masterUserStatChart.setAxisWidth(1);
	masterUserStatChart.setBarValuesColor('#2F6D99');
	masterUserStatChart.setAxisPaddingTop(50);
	masterUserStatChart.setAxisPaddingBottom(40);
	masterUserStatChart.setAxisPaddingLeft(40);
	masterUserStatChart.setTitleFontSize(10);
	masterUserStatChart.setBarColor('#2D6B96', 1);
	masterUserStatChart.setBarColor('#04da18', 2);
	masterUserStatChart.setBarColor('#f1a81c', 3);
	masterUserStatChart.setBarColor('#9CCEF0', 4);
	masterUserStatChart.setBarColor('#03d1de', 5);
	masterUserStatChart.setBarColor('#c3ec1f', 6);
	masterUserStatChart.setBarColor('#bb0606', 7);
	masterUserStatChart.setBarColor('#a688bf', 8);
	masterUserStatChart.setBarBorderWidth(0);
	masterUserStatChart.setBarSpacingRatio(18);
	masterUserStatChart.setBarOpacity(0.9);
	masterUserStatChart.setBarValuesFontSize(6);
	masterUserStatChart.setFlagRadius(6);
	masterUserStatChart.setLegendShow(true);
	masterUserStatChart.setLegendPosition('bottom');
	masterUserStatChart.setLegendPadding(10);
	masterUserStatChart.setLegendFontSize(7);
	masterUserStatChart.setLegendForBar(1, '<?php echo $this->userStatisticSummary[0]['initial']; ?>');
	masterUserStatChart.setLegendForBar(2, '<?php echo $this->userStatisticSummary[1]['initial']; ?>');
	masterUserStatChart.setSize(652, 307);
	masterUserStatChart.setGridColor('#F7F7F7');
	masterUserStatChart.draw(); ***/

	/*** USER STAT BY LOGIN ***/
	var userStatLoginLabel = new Array();
	var userStatLoginData = new Array();
	<?php if(!empty($this->userStatisticSummary) && !empty($this->totalLoginStat)) {
		$i = 0;
	 	foreach($this->totalLoginStat as $key=>$val) { 
			echo 'userStatLoginLabel['.$i.'] = "'.$this->userStatisticSummary[$key]['initial'].'";';
			echo 'userStatLoginData['.$i.'] = '.$val.';';
			$i++;
	 	}
	} ?>
	
	var color = Chart.helpers.color;
	var userStatLoginChartData = {
		labels: userStatLoginLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatLoginData
		}]
	};

	var userStatLoginChart = document.getElementById('userStatLogin').getContext('2d');
	window.userStatLoginBar = new Chart(userStatLoginChart, {
		type: 'bar',
		data: userStatLoginChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY LOGIN',
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

	/*** USER STAT BY ISSUES ***/

	var userStatSubmitIssueLabel = new Array();
	var userStatSubmitIssueData = new Array();
	<?php if(!empty($this->userStatisticSummary) && !empty($this->userStatisticSummary)) {
		$i = 0;
	 	foreach($this->userStatisticSummary as $userStatSum) { 
			echo 'userStatSubmitIssueLabel['.$i.'] = "'.$userStatSum['initial'].'";';
			echo 'userStatSubmitIssueData['.$i.'] = '.intval($userStatSum['total_issues']).';';
			$i++;
	 	}
	} ?>
	
	var color = Chart.helpers.color;
	var userStatSubmitIssueChartData = {
		labels: userStatSubmitIssueLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatSubmitIssueData
		}]
	};

	var userStatSubmitIssueChart = document.getElementById('userStatSubmitIssue').getContext('2d');
	window.userStatSubmitIssueBar = new Chart(userStatSubmitIssueChart, {
		type: 'bar',
		data: userStatSubmitIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY SUBMITTING KAIZEN',
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
				}],
				yAxes: [{
					ticks: {
						fontSize: 9,
						min: 0
					}
				}]
			}
		}
	});

	/*** USER STAT BY COMMENTS ***/

	var userStatCommentsLabel = new Array();
	var userStatCommentsData = new Array();
	<?php if(!empty($this->userStatisticSummary) && !empty($this->totalCommentsStat)) {
		$i = 0;
	 	foreach($this->totalCommentsStat as $key=>$val) { 
			echo 'userStatCommentsLabel['.$i.'] = "'.$this->userStatisticSummary[$key]['initial'].'";';
			echo 'userStatCommentsData['.$i.'] = '.$val.';';
			$i++;
	 	}
	} ?>
	
	var color = Chart.helpers.color;
	var userStatCommentsChartData = {
		labels: userStatCommentsLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatCommentsData
		}]
	};

	var userStatCommentsChart = document.getElementById('userStatComment').getContext('2d');
	window.userStatCommentsBar = new Chart(userStatCommentsChart, {
		type: 'bar',
		data: userStatCommentsChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY COMMENTS',
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

});	
</script>