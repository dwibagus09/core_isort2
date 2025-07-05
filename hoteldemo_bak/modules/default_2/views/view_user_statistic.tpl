<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle">User Statistic</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/user"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-user-stat" name="view-user-stat" value="Go" style="width:50px;"> <input type="button" id="export-user-stat" name="export-user-stat" value="Export to PDF" style="width:100px;"></div>
			</form>
		</div>
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4><?php echo $this->ident['initial']; ?> - Top Ten User Statistic By Login</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="100">Total Login</th>
					<th width="145">Last Login</th>
				</tr>
				<?php if(!empty($this->users)) { $i=1; foreach($this->users as $user) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $user['name']; ?></td>
					<td><?php echo $user['department']; ?></td>
					<td align="center"><?php echo intval($user['total_login']); ?></td>
					<td align="center"><?php echo $user['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4><?php echo $this->ident['initial']; ?> - Top Ten User Statistic By Submitting Issue</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="100">Total Issues</th>
				</tr>
				<?php if(!empty($this->userIssues)) { $j=1; foreach($this->userIssues as $user) { ?>
				<tr>
					<td><?php echo $j; ?></td>
					<td><?php echo $user['name']; ?></td>
					<td><?php echo $user['department']; ?></td>
					<td align="center"><?php echo $user['total_issues']; ?></td>
				</tr>
				<?php $j++; } } ?>
			</table>
		</div>


		<div class="user-stat col-md-5 col-sm-5 col-xs-12" style="clear:both;">
			<h4><?php echo $this->ident['initial']; ?> - Top Ten User Statistic By Comments</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="110">Total Comments</th>
				</tr>
				<?php if(!empty($this->userComments)) { $k=1; foreach($this->userComments as  $key => $value) { if($k <= 10) { ?>
				<tr>
					<td><?php echo $k; ?></td>
					<td><?php echo $key; ?></td>
					<td><?php echo $this->userCommentsDept[$key]; ?></td>
					<td align="center"><?php echo intval($value); ?></td>
				</tr>
				<?php } $k++; } } ?>
			</table>
		</div>

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4>Top Ten Operational Managers Statistic By Login</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Site</th>
					<th width="110">Total Login</th>
				</tr>
				<?php if(!empty($this->om)) { $l=1; foreach($this->om as $o) { ?>
				<tr>
					<td><?php echo $l; ?></td>
					<td><?php echo $o['name']; ?></td>
					<td align="center"><?php echo $o['initial']; ?></td>
					<td align="center"><?php echo intval($o['total_login']); ?></td>
				</tr>
				<?php $l++; } } ?>
			</table>
		</div>

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
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
			<?php /*<div id="user-statistic-all-sites" class="graph">Loading User Statistic...</div>*/ ?>
			<div id="user-stat-login" class="graph">Loading User Statistic By Login...</div>
			<div id="user-stat-issues" class="graph">Loading User Statistic By Submitting Issues...</div>
			<div id="user-stat-comments" class="graph">Loading User Statistic By Comments...</div>
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
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#export-user-stat").click(function() {
		$("body").mLoading();
		var login = document.getElementById("JSChart_user-stat-login");
		var issue = document.getElementById("JSChart_user-stat-issues");
		var comment = document.getElementById("JSChart_user-stat-comments");
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveusergraph',
			data: {
				login: login.toDataURL("image/png"),
				issue: issue.toDataURL("image/png"),
				comment: comment.toDataURL("image/png")
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exportuserstatistictopdf/cd/'+data+'/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
				} else {
					window.open("/default/statistic/exportuserstatistictopdf/cd/"+data+"/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
				}
				$("body").mLoading('hide');
			}
		});
		
			
	});

	/*** Master User Statistic 
	var masterUserStat = new Array(['By Login', <?php echo $this->userStatisticSummary[0]['total_login']; ?>, <?php echo $this->userStatisticSummary[1]['total_login']; ?>, <?php echo $this->userStatisticSummary[2]['total_login']; ?>, 	<?php echo $this->userStatisticSummary[3]['total_login']; ?>, <?php echo $this->userStatisticSummary[4]['total_login']; ?>,	<?php echo $this->userStatisticSummary[5]['total_login']; ?>, <?php echo $this->userStatisticSummary[6]['total_login']; ?>,	<?php echo $this->userStatisticSummary[7]['total_login']; ?>], ['By Submitting Issue', <?php echo $this->userStatisticSummary[0]['total_issues']; ?>, <?php echo $this->userStatisticSummary[1]['total_issues']; ?>, <?php echo $this->userStatisticSummary[2]['total_issues']; ?>, <?php echo $this->userStatisticSummary[3]['total_issues']; ?>, <?php echo $this->userStatisticSummary[4]['total_issues']; ?>, <?php echo $this->userStatisticSummary[5]['total_issues']; ?>, <?php echo $this->userStatisticSummary[6]['total_issues']; ?>, <?php echo $this->userStatisticSummary[7]['total_issues']; ?>], ['By Comments', <?php echo $this->userStatisticSummary[0]['total_comments']; ?>, <?php echo $this->userStatisticSummary[1]['total_comments']; ?>, <?php echo $this->userStatisticSummary[2]['total_comments']; ?>, <?php echo $this->userStatisticSummary[3]['total_comments']; ?>, <?php echo $this->userStatisticSummary[4]['total_comments']; ?>, <?php echo $this->userStatisticSummary[5]['total_comments']; ?>, <?php echo $this->userStatisticSummary[6]['total_comments']; ?>, <?php echo $this->userStatisticSummary[7]['total_comments']; ?>]);
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
	masterUserStatChart.setLegendForBar(3, '<?php echo $this->userStatisticSummary[2]['initial']; ?>');
	masterUserStatChart.setLegendForBar(4, '<?php echo $this->userStatisticSummary[3]['initial']; ?>');
	masterUserStatChart.setLegendForBar(5, '<?php echo $this->userStatisticSummary[4]['initial']; ?>');
	masterUserStatChart.setLegendForBar(6, '<?php echo $this->userStatisticSummary[5]['initial']; ?>');
	masterUserStatChart.setLegendForBar(7, '<?php echo $this->userStatisticSummary[6]['initial']; ?>');
	masterUserStatChart.setLegendForBar(8, '<?php echo $this->userStatisticSummary[7]['initial']; ?>');
	masterUserStatChart.setSize(652, 307);
	masterUserStatChart.setGridColor('#F7F7F7');
	masterUserStatChart.draw(); ***/

	/*** USER STAT BY LOGIN ***/
	var userStatLogin = new Array(['<?php echo $this->userStatisticSummary[0]['initial']; ?>', <?php echo $this->userStatisticSummary[0]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[1]['initial']; ?>', <?php echo $this->userStatisticSummary[1]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[2]['initial']; ?>', <?php echo $this->userStatisticSummary[2]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[3]['initial']; ?>', <?php echo $this->userStatisticSummary[3]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[4]['initial']; ?>', <?php echo $this->userStatisticSummary[4]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[5]['initial']; ?>', <?php echo $this->userStatisticSummary[5]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[6]['initial']; ?>', <?php echo $this->userStatisticSummary[6]['total_login']; ?>], ['<?php echo $this->userStatisticSummary[7]['initial']; ?>', <?php echo $this->userStatisticSummary[7]['total_login']; ?>], );
	var userStatLoginColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var userStatLoginChart = new JSChart('user-stat-login', 'bar');
	userStatLoginChart.setDataArray(userStatLogin);
	userStatLoginChart.colorizeBars(userStatLoginColors);
	userStatLoginChart.setTitle('USER STATISTIC BY LOGIN');
	userStatLoginChart.setTitleColor('#8E8E8E');
	userStatLoginChart.setAxisNameX('');
	userStatLoginChart.setAxisNameY('');
	userStatLoginChart.setAxisColor('#C4C4C4');
	userStatLoginChart.setAxisNameFontSize(6);
	userStatLoginChart.setAxisValuesFontSize(6);
	userStatLoginChart.setAxisNameColor('#999');
	userStatLoginChart.setAxisValuesColor('#7E7E7E');
	userStatLoginChart.setBarValuesColor('#7E7E7E');
	userStatLoginChart.setAxisPaddingTop(50);
	userStatLoginChart.setAxisPaddingRight(40);
	userStatLoginChart.setAxisPaddingLeft(40);
	userStatLoginChart.setAxisPaddingBottom(40);
	userStatLoginChart.setTextPaddingLeft(10);
	userStatLoginChart.setTitleFontSize(8);
	userStatLoginChart.setBarBorderWidth(1);
	userStatLoginChart.setBarBorderColor('#C4C4C4');
	userStatLoginChart.setBarSpacingRatio(40);
	userStatLoginChart.setBarValuesFontSize(6);
	userStatLoginChart.setGrid(false);
	userStatLoginChart.setSize(350, 230);
	userStatLoginChart.setBackgroundImage('chart_bg.jpg');
	userStatLoginChart.draw();

	/*** USER STAT BY ISSUES ***/
	var userStatIssue = new Array(['<?php echo $this->userStatisticSummary[0]['initial']; ?>', <?php echo $this->userStatisticSummary[0]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[1]['initial']; ?>', <?php echo $this->userStatisticSummary[1]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[2]['initial']; ?>', <?php echo $this->userStatisticSummary[2]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[3]['initial']; ?>', <?php echo $this->userStatisticSummary[3]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[4]['initial']; ?>', <?php echo $this->userStatisticSummary[4]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[5]['initial']; ?>', <?php echo $this->userStatisticSummary[5]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[6]['initial']; ?>', <?php echo $this->userStatisticSummary[6]['total_issues']; ?>], ['<?php echo $this->userStatisticSummary[7]['initial']; ?>', <?php echo $this->userStatisticSummary[7]['total_issues']; ?>], );
	var userStatIssueColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var userStatIssueChart = new JSChart('user-stat-issues', 'bar');
	userStatIssueChart.setDataArray(userStatIssue);
	userStatIssueChart.colorizeBars(userStatIssueColors);
	userStatIssueChart.setTitle('USER STATISTIC BY SUBMITTING ISSUES');
	userStatIssueChart.setTitleColor('#8E8E8E');
	userStatIssueChart.setAxisNameX('');
	userStatIssueChart.setAxisNameY('');
	userStatIssueChart.setAxisColor('#C4C4C4');
	userStatIssueChart.setAxisNameFontSize(6);
	userStatIssueChart.setAxisValuesFontSize(6);
	userStatIssueChart.setAxisNameColor('#999');
	userStatIssueChart.setAxisValuesColor('#7E7E7E');
	userStatIssueChart.setBarValuesColor('#7E7E7E');
	userStatIssueChart.setAxisPaddingTop(50);
	userStatIssueChart.setAxisPaddingRight(40);
	userStatIssueChart.setAxisPaddingLeft(40);
	userStatIssueChart.setAxisPaddingBottom(40);
	userStatIssueChart.setTextPaddingLeft(10);
	userStatIssueChart.setTitleFontSize(8);
	userStatIssueChart.setBarBorderWidth(1);
	userStatIssueChart.setBarBorderColor('#C4C4C4');
	userStatIssueChart.setBarSpacingRatio(40);
	userStatIssueChart.setBarValuesFontSize(6);
	userStatIssueChart.setGrid(false);
	userStatIssueChart.setSize(350, 230);
	userStatIssueChart.setBackgroundImage('chart_bg.jpg');
	userStatIssueChart.draw();

	/*** USER STAT BY COMMENTS ***/
	var userStatComment = new Array(['<?php echo $this->userStatisticSummary[0]['initial']; ?>', <?php echo $this->userStatisticSummary[0]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[1]['initial']; ?>', <?php echo $this->userStatisticSummary[1]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[2]['initial']; ?>', <?php echo $this->userStatisticSummary[2]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[3]['initial']; ?>', <?php echo $this->userStatisticSummary[3]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[4]['initial']; ?>', <?php echo $this->userStatisticSummary[4]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[5]['initial']; ?>', <?php echo $this->userStatisticSummary[5]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[6]['initial']; ?>', <?php echo $this->userStatisticSummary[6]['total_comments']; ?>], ['<?php echo $this->userStatisticSummary[7]['initial']; ?>', <?php echo $this->userStatisticSummary[7]['total_comments']; ?>], );
	var userStatCommentColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var userStatCommentChart = new JSChart('user-stat-comments', 'bar');
	userStatCommentChart.setDataArray(userStatComment);
	userStatCommentChart.colorizeBars(userStatCommentColors);
	userStatCommentChart.setTitle('USER STATISTIC BY COMMENTS');
	userStatCommentChart.setTitleColor('#8E8E8E');
	userStatCommentChart.setAxisNameX('');
	userStatCommentChart.setAxisNameY('');
	userStatCommentChart.setAxisColor('#C4C4C4');
	userStatCommentChart.setAxisNameFontSize(6);
	userStatCommentChart.setAxisValuesFontSize(6);
	userStatCommentChart.setAxisNameColor('#999');
	userStatCommentChart.setAxisValuesColor('#7E7E7E');
	userStatCommentChart.setBarValuesColor('#7E7E7E');
	userStatCommentChart.setAxisPaddingTop(50);
	userStatCommentChart.setAxisPaddingRight(40);
	userStatCommentChart.setAxisPaddingLeft(40);
	userStatCommentChart.setAxisPaddingBottom(40);
	userStatCommentChart.setTextPaddingLeft(10);
	userStatCommentChart.setTitleFontSize(8);
	userStatCommentChart.setBarBorderWidth(1);
	userStatCommentChart.setBarBorderColor('#C4C4C4');
	userStatCommentChart.setBarSpacingRatio(40);
	userStatCommentChart.setBarValuesFontSize(6);
	userStatCommentChart.setGrid(false);
	userStatCommentChart.setSize(350, 230);
	userStatCommentChart.setBackgroundImage('chart_bg.jpg');
	userStatCommentChart.draw();
	
});	
</script>