<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="issue-statistic" class="right_col" role="main">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/view"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-issue-stat" name="view-issue-stat" value="Go" style="width:50px;"> <input type="button" id="export-issue-stat" name="export-issue-stat" value="Export to PDF" style="width:100px;"></div>
			</form>
		</div>
		<div id="graph">
			<div class="graph-title">ALL DEPARTMENT</div>
			<div id="total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">SECURITY</div>
			<div id="security-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="security-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="security-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">SAFETY</div>
			<div id="safety-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="safety-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="safety-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">PARKING &amp; TRAFFIC</div>
			<div id="parking-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="parking-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="parking-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">HOUSEKEEPING</div>
			<div id="hk-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="hk-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="hk-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">ENGINEERING</div>
			<div id="engineering-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="engineering-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="engineering-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
			<div class="graph-title">UTILITY</div>
			<div id="utility-total-issue" class="graph">Loading Total Issue Finding...</div>
			<div id="utility-type-issue" class="graph">Loading Total Issue Finding Per Type...</div>
			<div id="utility-open-close" class="graph">Loading Total Open & Close Issue Finding...</div>
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

	/***** ALL DEPARTMENT *****/

	/*** TOTAL ISSUE GRAPH ***/
	var totalIssue = new Array(['Issue', <?php echo $this->totalAllIssue['total']; ?>]);
	var totalIssueColors = ['#2D6B96'];
	var totalIssueChart = new JSChart('total-issue', 'bar');
	totalIssueChart.setDataArray(totalIssue);
	totalIssueChart.colorizeBars(totalIssueColors);
	totalIssueChart.setTitle('TOTAL ISSUE');
	totalIssueChart.setTitleColor('#8E8E8E');
	totalIssueChart.setAxisNameX('');
	totalIssueChart.setAxisNameY('');
	totalIssueChart.setAxisColor('#C4C4C4');
	totalIssueChart.setAxisNameFontSize(6);
	totalIssueChart.setAxisValuesFontSize(6);
	totalIssueChart.setAxisNameColor('#999');
	totalIssueChart.setAxisValuesColor('#7E7E7E');
	totalIssueChart.setBarValuesColor('#7E7E7E');
	totalIssueChart.setAxisPaddingTop(50);
	totalIssueChart.setAxisPaddingRight(40);
	totalIssueChart.setAxisPaddingLeft(40);
	totalIssueChart.setAxisPaddingBottom(40);
	totalIssueChart.setTextPaddingLeft(10);
	totalIssueChart.setTitleFontSize(8);
	totalIssueChart.setBarBorderWidth(1);
	totalIssueChart.setBarBorderColor('#C4C4C4');
	totalIssueChart.setBarSpacingRatio(40);
	totalIssueChart.setBarValuesFontSize(6);
	totalIssueChart.setGrid(false);
	totalIssueChart.setSize(125, 200);
	totalIssueChart.setBackgroundImage('chart_bg.jpg');
	totalIssueChart.draw();


	/*** ISSUE PER TYPE GRAPH ***/
	var issuePerType = new Array(['Incident Report', <?php echo $this->totalIncident['total']; ?>], ['Glitch', <?php echo $this->totalGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalLostFound['total']; ?>], ['Defect List', <?php echo $this->totalDefectList['total']; ?>]);
	var issuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var issuePerTypeChart = new JSChart('type-issue', 'bar');
	issuePerTypeChart.setDataArray(issuePerType);
	issuePerTypeChart.colorizeBars(issuePerTypeColors);
	issuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	issuePerTypeChart.setTitleColor('#8E8E8E');
	issuePerTypeChart.setAxisNameX('');
	issuePerTypeChart.setAxisNameY('');
	issuePerTypeChart.setAxisColor('#C4C4C4');
	issuePerTypeChart.setAxisNameFontSize(6);
	issuePerTypeChart.setAxisValuesFontSize(6);
	issuePerTypeChart.setAxisNameColor('#999');
	issuePerTypeChart.setAxisValuesColor('#7E7E7E');
	issuePerTypeChart.setBarValuesColor('#7E7E7E');
	issuePerTypeChart.setAxisPaddingTop(50);
	issuePerTypeChart.setAxisPaddingRight(40);
	issuePerTypeChart.setAxisPaddingLeft(40);
	issuePerTypeChart.setAxisPaddingBottom(40);
	issuePerTypeChart.setTextPaddingLeft(10);
	issuePerTypeChart.setTitleFontSize(8);
	issuePerTypeChart.setBarBorderWidth(1);
	issuePerTypeChart.setBarBorderColor('#C4C4C4');
	issuePerTypeChart.setBarSpacingRatio(40);
	issuePerTypeChart.setBarValuesFontSize(6);
	issuePerTypeChart.setGrid(false);
	issuePerTypeChart.setSize(320, 200);
	issuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	issuePerTypeChart.draw();


	/*** OPEN & CLOSE ISSUE PER CATEGORY ***/
	var openCloseIssue = new Array(['Incident Report', <?php echo $this->totalOpenIncident['total']; ?>, <?php echo $this->totalCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalOpenGlitch['total']; ?>, <?php echo $this->totalCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalOpenLostFound['total']; ?>, <?php echo $this->totalCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalOpenDefectList['total']; ?>,<?php echo $this->totalCloseDefectList['total']; ?>]);
	var openCloseIssueChart = new JSChart('open-close', 'bar');
	openCloseIssueChart.setDataArray(openCloseIssue);
	openCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	openCloseIssueChart.setTitleColor('#8E8E8E');
	openCloseIssueChart.setAxisNameX('');
	openCloseIssueChart.setAxisNameY('');
	openCloseIssueChart.setAxisNameFontSize(6);
	openCloseIssueChart.setAxisValuesFontSize(6);
	openCloseIssueChart.setAxisNameFontFamily("verdana");
	openCloseIssueChart.setAxisNameColor('#999');
	/*openCloseIssueChart.setAxisValuesAngle(15);*/
	openCloseIssueChart.setAxisValuesColor('#777');
	openCloseIssueChart.setAxisColor('#B5B5B5');
	openCloseIssueChart.setAxisWidth(1);
	openCloseIssueChart.setBarValuesColor('#2F6D99');
	openCloseIssueChart.setAxisPaddingTop(50);
	openCloseIssueChart.setAxisPaddingBottom(40);
	openCloseIssueChart.setAxisPaddingLeft(40);
	openCloseIssueChart.setTitleFontSize(8);
	openCloseIssueChart.setBarValuesFontSize(6);
	openCloseIssueChart.setBarColor('#2D6B96', 1);
	openCloseIssueChart.setBarColor('#9CCEF0', 2);
	openCloseIssueChart.setBarBorderWidth(0);
	openCloseIssueChart.setBarSpacingRatio(40);
	openCloseIssueChart.setBarOpacity(0.9);
	openCloseIssueChart.setFlagRadius(6);
	openCloseIssueChart.setLegendShow(true);
	openCloseIssueChart.setLegendPosition('bottom');
	openCloseIssueChart.setLegendPadding(10);
	openCloseIssueChart.setLegendFontSize(7);
	openCloseIssueChart.setLegendForBar(1, 'Opened');
	openCloseIssueChart.setLegendForBar(2, 'Closed');
	openCloseIssueChart.setSize(320, 200);
	openCloseIssueChart.setGridColor('#F7F7F7');
	openCloseIssueChart.draw();


	/***** SECURITY *****/

	/*** SECURITY TOTAL ISSUE GRAPH ***/
	var securityTotalIssue = new Array(['Issue', <?php echo $this->totalAllSecurityIssue['total']; ?>]);
	var securityTotalIssueColors = ['#2D6B96'];
	var securityTotalIssueChart = new JSChart('security-total-issue', 'bar');
	securityTotalIssueChart.setDataArray(securityTotalIssue);
	securityTotalIssueChart.colorizeBars(securityTotalIssueColors);
	securityTotalIssueChart.setTitle('TOTAL ISSUE');
	securityTotalIssueChart.setTitleColor('#8E8E8E');
	securityTotalIssueChart.setAxisNameX('');
	securityTotalIssueChart.setAxisNameY('');
	securityTotalIssueChart.setAxisColor('#C4C4C4');
	securityTotalIssueChart.setAxisNameFontSize(6);
	securityTotalIssueChart.setAxisValuesFontSize(6);
	securityTotalIssueChart.setAxisNameColor('#999');
	securityTotalIssueChart.setAxisValuesColor('#7E7E7E');
	securityTotalIssueChart.setBarValuesColor('#7E7E7E');
	securityTotalIssueChart.setAxisPaddingTop(50);
	securityTotalIssueChart.setAxisPaddingRight(40);
	securityTotalIssueChart.setAxisPaddingLeft(40);
	securityTotalIssueChart.setAxisPaddingBottom(40);
	securityTotalIssueChart.setTextPaddingLeft(10);
	securityTotalIssueChart.setTitleFontSize(8);
	securityTotalIssueChart.setBarBorderWidth(1);
	securityTotalIssueChart.setBarBorderColor('#C4C4C4');
	securityTotalIssueChart.setBarSpacingRatio(40);
	securityTotalIssueChart.setBarValuesFontSize(6);
	securityTotalIssueChart.setGrid(false);
	securityTotalIssueChart.setSize(125, 200);
	securityTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	securityTotalIssueChart.draw();


	/*** SECURITY ISSUE PER TYPE GRAPH ***/
	var securityIssuePerType = new Array(['Incident Report', <?php echo $this->totalSecurityIncident['total']; ?>], ['Glitch', <?php echo $this->totalSecurityGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalSecurityLostFound['total']; ?>], ['Defect List', <?php echo $this->totalSecurityDefectList['total']; ?>]);
	var securityIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var securityIssuePerTypeChart = new JSChart('security-type-issue', 'bar');
	securityIssuePerTypeChart.setDataArray(securityIssuePerType);
	securityIssuePerTypeChart.colorizeBars(securityIssuePerTypeColors);
	securityIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	securityIssuePerTypeChart.setTitleColor('#8E8E8E');
	securityIssuePerTypeChart.setAxisNameX('');
	securityIssuePerTypeChart.setAxisNameY('');
	securityIssuePerTypeChart.setAxisColor('#C4C4C4');
	securityIssuePerTypeChart.setAxisNameFontSize(6);
	securityIssuePerTypeChart.setAxisValuesFontSize(6);
	securityIssuePerTypeChart.setAxisNameColor('#999');
	securityIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	securityIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	securityIssuePerTypeChart.setAxisPaddingTop(50);
	securityIssuePerTypeChart.setAxisPaddingRight(40);
	securityIssuePerTypeChart.setAxisPaddingLeft(40);
	securityIssuePerTypeChart.setAxisPaddingBottom(40);
	securityIssuePerTypeChart.setTextPaddingLeft(10);
	securityIssuePerTypeChart.setTitleFontSize(8);
	securityIssuePerTypeChart.setBarBorderWidth(1);
	securityIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	securityIssuePerTypeChart.setBarSpacingRatio(40);
	securityIssuePerTypeChart.setBarValuesFontSize(6);
	securityIssuePerTypeChart.setGrid(false);
	securityIssuePerTypeChart.setSize(320, 200);
	securityIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	securityIssuePerTypeChart.draw();


	/*** SECURITY OPEN & CLOSE ISSUE PER CATEGORY ***/
	var securityOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalSecurityOpenIncident['total']; ?>, <?php echo $this->totalSecurityCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalSecurityOpenGlitch['total']; ?>, <?php echo $this->totalSecurityCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalSecurityOpenLostFound['total']; ?>, <?php echo $this->totalSecurityCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalSecurityOpenDefectList['total']; ?>,<?php echo $this->totalSecurityCloseDefectList['total']; ?>]);
	var securityOpenCloseIssueChart = new JSChart('security-open-close', 'bar');
	securityOpenCloseIssueChart.setDataArray(securityOpenCloseIssue);
	securityOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	securityOpenCloseIssueChart.setTitleColor('#8E8E8E');
	securityOpenCloseIssueChart.setAxisNameX('');
	securityOpenCloseIssueChart.setAxisNameY('');
	securityOpenCloseIssueChart.setAxisNameFontSize(6);
	securityOpenCloseIssueChart.setAxisValuesFontSize(6);
	securityOpenCloseIssueChart.setAxisNameColor('#999');
	/*securityOpenCloseIssueChart.setAxisValuesAngle(15);*/
	securityOpenCloseIssueChart.setAxisValuesColor('#777');
	securityOpenCloseIssueChart.setAxisColor('#B5B5B5');
	securityOpenCloseIssueChart.setAxisWidth(1);
	securityOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	securityOpenCloseIssueChart.setAxisPaddingTop(50);
	securityOpenCloseIssueChart.setAxisPaddingBottom(40);
	securityOpenCloseIssueChart.setAxisPaddingLeft(40);
	securityOpenCloseIssueChart.setTitleFontSize(8);
	securityOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	securityOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	securityOpenCloseIssueChart.setBarBorderWidth(0);
	securityOpenCloseIssueChart.setBarSpacingRatio(40);
	securityOpenCloseIssueChart.setBarOpacity(0.9);
	securityOpenCloseIssueChart.setBarValuesFontSize(6);
	securityOpenCloseIssueChart.setFlagRadius(6);
	securityOpenCloseIssueChart.setLegendShow(true);
	securityOpenCloseIssueChart.setLegendPosition('bottom');
	securityOpenCloseIssueChart.setLegendPadding(10);
	securityOpenCloseIssueChart.setLegendFontSize(7);
	securityOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	securityOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	securityOpenCloseIssueChart.setSize(320, 200);
	securityOpenCloseIssueChart.setGridColor('#F7F7F7');
	securityOpenCloseIssueChart.draw();

	/***** SAFETY *****/

	/*** SAFETY TOTAL ISSUE GRAPH ***/
	var safetyTotalIssue = new Array(['Issue', <?php echo $this->totalAllSafetyIssue['total']; ?>]);
	var safetyTotalIssueColors = ['#2D6B96'];
	var safetyTotalIssueChart = new JSChart('safety-total-issue', 'bar');
	safetyTotalIssueChart.setDataArray(safetyTotalIssue);
	safetyTotalIssueChart.colorizeBars(safetyTotalIssueColors);
	safetyTotalIssueChart.setTitle('TOTAL ISSUE');
	safetyTotalIssueChart.setTitleColor('#8E8E8E');
	safetyTotalIssueChart.setAxisNameX('');
	safetyTotalIssueChart.setAxisNameY('');
	safetyTotalIssueChart.setAxisColor('#C4C4C4');
	safetyTotalIssueChart.setAxisNameFontSize(6);
	safetyTotalIssueChart.setAxisValuesFontSize(6);
	safetyTotalIssueChart.setAxisNameColor('#999');
	safetyTotalIssueChart.setAxisValuesColor('#7E7E7E');
	safetyTotalIssueChart.setBarValuesColor('#7E7E7E');
	safetyTotalIssueChart.setAxisPaddingTop(50);
	safetyTotalIssueChart.setAxisPaddingRight(40);
	safetyTotalIssueChart.setAxisPaddingLeft(40);
	safetyTotalIssueChart.setAxisPaddingBottom(40);
	safetyTotalIssueChart.setTextPaddingLeft(10);
	safetyTotalIssueChart.setTitleFontSize(8);
	safetyTotalIssueChart.setBarBorderWidth(1);
	safetyTotalIssueChart.setBarBorderColor('#C4C4C4');
	safetyTotalIssueChart.setBarSpacingRatio(40);
	safetyTotalIssueChart.setBarValuesFontSize(6);
	safetyTotalIssueChart.setGrid(false);
	safetyTotalIssueChart.setSize(125, 200);
	safetyTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	safetyTotalIssueChart.draw();


	/*** SAFETY ISSUE PER TYPE GRAPH ***/
	var safetyIssuePerType = new Array(['Incident Report', <?php echo $this->totalSafetyIncident['total']; ?>], ['Glitch', <?php echo $this->totalSafetyGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalSafetyLostFound['total']; ?>], ['Defect List', <?php echo $this->totalSafetyDefectList['total']; ?>]);
	var safetyIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var safetyIssuePerTypeChart = new JSChart('safety-type-issue', 'bar');
	safetyIssuePerTypeChart.setDataArray(safetyIssuePerType);
	safetyIssuePerTypeChart.colorizeBars(safetyIssuePerTypeColors);
	safetyIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	safetyIssuePerTypeChart.setTitleColor('#8E8E8E');
	safetyIssuePerTypeChart.setAxisNameX('');
	safetyIssuePerTypeChart.setAxisNameY('');
	safetyIssuePerTypeChart.setAxisColor('#C4C4C4');
	safetyIssuePerTypeChart.setAxisNameFontSize(6);
	safetyIssuePerTypeChart.setAxisValuesFontSize(6);
	safetyIssuePerTypeChart.setAxisNameColor('#999');
	safetyIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	safetyIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	safetyIssuePerTypeChart.setAxisPaddingTop(50);
	safetyIssuePerTypeChart.setAxisPaddingRight(40);
	safetyIssuePerTypeChart.setAxisPaddingLeft(40);
	safetyIssuePerTypeChart.setAxisPaddingBottom(40);
	safetyIssuePerTypeChart.setTextPaddingLeft(10);
	safetyIssuePerTypeChart.setTitleFontSize(8);
	safetyIssuePerTypeChart.setBarBorderWidth(1);
	safetyIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	safetyIssuePerTypeChart.setBarSpacingRatio(40);
	safetyIssuePerTypeChart.setBarValuesFontSize(6);
	safetyIssuePerTypeChart.setGrid(false);
	safetyIssuePerTypeChart.setSize(320, 200);
	safetyIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	safetyIssuePerTypeChart.draw();


	/*** SAFETY OPEN & CLOSE ISSUE PER CATEGORY ***/
	var safetyOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalSafetyOpenIncident['total']; ?>, <?php echo $this->totalSafetyCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalSafetyOpenGlitch['total']; ?>, <?php echo $this->totalSafetyCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalSafetyOpenLostFound['total']; ?>, <?php echo $this->totalSafetyCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalSafetyOpenDefectList['total']; ?>,<?php echo $this->totalSafetyCloseDefectList['total']; ?>]);
	var safetyOpenCloseIssueChart = new JSChart('safety-open-close', 'bar');
	safetyOpenCloseIssueChart.setDataArray(safetyOpenCloseIssue);
	safetyOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	safetyOpenCloseIssueChart.setTitleColor('#8E8E8E');
	safetyOpenCloseIssueChart.setAxisNameX('');
	safetyOpenCloseIssueChart.setAxisNameY('');
	safetyOpenCloseIssueChart.setAxisNameFontSize(6);
	safetyOpenCloseIssueChart.setAxisValuesFontSize(6);
	safetyOpenCloseIssueChart.setAxisNameColor('#999');
	/*safetyOpenCloseIssueChart.setAxisValuesAngle(15);*/
	safetyOpenCloseIssueChart.setAxisValuesColor('#777');
	safetyOpenCloseIssueChart.setAxisColor('#B5B5B5');
	safetyOpenCloseIssueChart.setAxisWidth(1);
	safetyOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	safetyOpenCloseIssueChart.setAxisPaddingTop(50);
	safetyOpenCloseIssueChart.setAxisPaddingBottom(40);
	safetyOpenCloseIssueChart.setAxisPaddingLeft(40);
	safetyOpenCloseIssueChart.setTitleFontSize(8);
	safetyOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	safetyOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	safetyOpenCloseIssueChart.setBarBorderWidth(0);
	safetyOpenCloseIssueChart.setBarSpacingRatio(40);
	safetyOpenCloseIssueChart.setBarOpacity(0.9);
	safetyOpenCloseIssueChart.setBarValuesFontSize(6);
	safetyOpenCloseIssueChart.setFlagRadius(6);
	safetyOpenCloseIssueChart.setLegendShow(true);
	safetyOpenCloseIssueChart.setLegendPosition('bottom');
	safetyOpenCloseIssueChart.setLegendPadding(10);
	safetyOpenCloseIssueChart.setLegendFontSize(7);
	safetyOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	safetyOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	safetyOpenCloseIssueChart.setSize(320, 200);
	safetyOpenCloseIssueChart.setGridColor('#F7F7F7');
	safetyOpenCloseIssueChart.draw();


	/***** PARKING & TRAFFIC *****/

	/*** PARKING & TRAFFIC TOTAL ISSUE GRAPH ***/
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
	var parkingIssuePerType = new Array(['Incident Report', <?php echo $this->totalParkingIncident['total']; ?>], ['Glitch', <?php echo $this->totalParkingGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalParkingLostFound['total']; ?>], ['Defect List', <?php echo $this->totalParkingDefectList['total']; ?>]);
	var parkingIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
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
	parkingIssuePerTypeChart.setSize(320, 200);
	parkingIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	parkingIssuePerTypeChart.draw();


	/*** PARKING & TRAFFIC OPEN & CLOSE ISSUE PER CATEGORY ***/
	var parkingOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalParkingOpenIncident['total']; ?>, <?php echo $this->totalParkingCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalParkingOpenGlitch['total']; ?>, <?php echo $this->totalParkingCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalParkingOpenLostFound['total']; ?>, <?php echo $this->totalParkingCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalParkingOpenDefectList['total']; ?>,<?php echo $this->totalParkingCloseDefectList['total']; ?>]);
	var parkingOpenCloseIssueChart = new JSChart('parking-open-close', 'bar');
	parkingOpenCloseIssueChart.setDataArray(parkingOpenCloseIssue);
	parkingOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	parkingOpenCloseIssueChart.setTitleColor('#8E8E8E');
	parkingOpenCloseIssueChart.setAxisNameX('');
	parkingOpenCloseIssueChart.setAxisNameY('');
	parkingOpenCloseIssueChart.setAxisNameFontSize(6);
	parkingOpenCloseIssueChart.setAxisValuesFontSize(6);
	parkingOpenCloseIssueChart.setAxisNameColor('#999');
	/*parkingOpenCloseIssueChart.setAxisValuesAngle(15);*/
	parkingOpenCloseIssueChart.setAxisValuesColor('#777');
	parkingOpenCloseIssueChart.setAxisColor('#B5B5B5');
	parkingOpenCloseIssueChart.setAxisWidth(1);
	parkingOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	parkingOpenCloseIssueChart.setAxisPaddingTop(50);
	parkingOpenCloseIssueChart.setAxisPaddingBottom(40);
	parkingOpenCloseIssueChart.setAxisPaddingLeft(40);
	parkingOpenCloseIssueChart.setTitleFontSize(8);
	parkingOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	parkingOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	parkingOpenCloseIssueChart.setBarBorderWidth(0);
	parkingOpenCloseIssueChart.setBarSpacingRatio(40);
	parkingOpenCloseIssueChart.setBarOpacity(0.9);
	parkingOpenCloseIssueChart.setBarValuesFontSize(6);
	parkingOpenCloseIssueChart.setFlagRadius(6);
	parkingOpenCloseIssueChart.setLegendShow(true);
	parkingOpenCloseIssueChart.setLegendPosition('bottom');
	parkingOpenCloseIssueChart.setLegendPadding(10);
	parkingOpenCloseIssueChart.setLegendFontSize(7);
	parkingOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	parkingOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	parkingOpenCloseIssueChart.setSize(320, 200);
	parkingOpenCloseIssueChart.setGridColor('#F7F7F7');
	parkingOpenCloseIssueChart.draw();


	/***** HOUSEKEEPING *****/

	/*** HOUSEKEEPING TOTAL ISSUE GRAPH ***/
	var housekeepingTotalIssue = new Array(['Issue', <?php echo $this->totalAllHKIssue['total']; ?>]);
	var housekeepingTotalIssueColors = ['#2D6B96'];
	var housekeepingTotalIssueChart = new JSChart('hk-total-issue', 'bar');
	housekeepingTotalIssueChart.setDataArray(housekeepingTotalIssue);
	housekeepingTotalIssueChart.colorizeBars(housekeepingTotalIssueColors);
	housekeepingTotalIssueChart.setTitle('TOTAL ISSUE');
	housekeepingTotalIssueChart.setTitleColor('#8E8E8E');
	housekeepingTotalIssueChart.setAxisNameX('');
	housekeepingTotalIssueChart.setAxisNameY('');
	housekeepingTotalIssueChart.setAxisColor('#C4C4C4');
	housekeepingTotalIssueChart.setAxisNameFontSize(6);
	housekeepingTotalIssueChart.setAxisValuesFontSize(6);
	housekeepingTotalIssueChart.setAxisNameColor('#999');
	housekeepingTotalIssueChart.setAxisValuesColor('#7E7E7E');
	housekeepingTotalIssueChart.setBarValuesColor('#7E7E7E');
	housekeepingTotalIssueChart.setAxisPaddingTop(50);
	housekeepingTotalIssueChart.setAxisPaddingRight(40);
	housekeepingTotalIssueChart.setAxisPaddingLeft(40);
	housekeepingTotalIssueChart.setAxisPaddingBottom(40);
	housekeepingTotalIssueChart.setTextPaddingLeft(10);
	housekeepingTotalIssueChart.setTitleFontSize(8);
	housekeepingTotalIssueChart.setBarBorderWidth(1);
	housekeepingTotalIssueChart.setBarBorderColor('#C4C4C4');
	housekeepingTotalIssueChart.setBarSpacingRatio(40);
	housekeepingTotalIssueChart.setBarValuesFontSize(6);
	housekeepingTotalIssueChart.setGrid(false);
	housekeepingTotalIssueChart.setSize(125, 200);
	housekeepingTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	housekeepingTotalIssueChart.draw();


	/*** HOUSEKEEPING ISSUE PER TYPE GRAPH ***/
	var housekeepingIssuePerType = new Array(['Incident Report', <?php echo $this->totalHKIncident['total']; ?>], ['Glitch', <?php echo $this->totalHKGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalHKLostFound['total']; ?>], ['Defect List', <?php echo $this->totalHKDefectList['total']; ?>]);
	var housekeepingIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var housekeepingIssuePerTypeChart = new JSChart('hk-type-issue', 'bar');
	housekeepingIssuePerTypeChart.setDataArray(housekeepingIssuePerType);
	housekeepingIssuePerTypeChart.colorizeBars(housekeepingIssuePerTypeColors);
	housekeepingIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	housekeepingIssuePerTypeChart.setTitleColor('#8E8E8E');
	housekeepingIssuePerTypeChart.setAxisNameX('');
	housekeepingIssuePerTypeChart.setAxisNameY('');
	housekeepingIssuePerTypeChart.setAxisColor('#C4C4C4');
	housekeepingIssuePerTypeChart.setAxisNameFontSize(6);
	housekeepingIssuePerTypeChart.setAxisValuesFontSize(6);
	housekeepingIssuePerTypeChart.setAxisNameColor('#999');
	housekeepingIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	housekeepingIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	housekeepingIssuePerTypeChart.setAxisPaddingTop(50);
	housekeepingIssuePerTypeChart.setAxisPaddingRight(40);
	housekeepingIssuePerTypeChart.setAxisPaddingLeft(40);
	housekeepingIssuePerTypeChart.setAxisPaddingBottom(40);
	housekeepingIssuePerTypeChart.setTextPaddingLeft(10);
	housekeepingIssuePerTypeChart.setTitleFontSize(8);
	housekeepingIssuePerTypeChart.setBarBorderWidth(1);
	housekeepingIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	housekeepingIssuePerTypeChart.setBarSpacingRatio(40);
	housekeepingIssuePerTypeChart.setBarValuesFontSize(6);
	housekeepingIssuePerTypeChart.setGrid(false);
	housekeepingIssuePerTypeChart.setSize(320, 200);
	housekeepingIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	housekeepingIssuePerTypeChart.draw();


	/*** HOUSEKEEPING OPEN & CLOSE ISSUE PER CATEGORY ***/
	var housekeepingOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalHKOpenIncident['total']; ?>, <?php echo $this->totalHKCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalHKOpenGlitch['total']; ?>, <?php echo $this->totalHKCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalHKOpenLostFound['total']; ?>, <?php echo $this->totalHKCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalHKOpenDefectList['total']; ?>,<?php echo $this->totalHKCloseDefectList['total']; ?>]);
	var housekeepingOpenCloseIssueChart = new JSChart('hk-open-close', 'bar');
	housekeepingOpenCloseIssueChart.setDataArray(housekeepingOpenCloseIssue);
	housekeepingOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	housekeepingOpenCloseIssueChart.setTitleColor('#8E8E8E');
	housekeepingOpenCloseIssueChart.setAxisNameX('');
	housekeepingOpenCloseIssueChart.setAxisNameY('');
	housekeepingOpenCloseIssueChart.setAxisNameFontSize(6);
	housekeepingOpenCloseIssueChart.setAxisValuesFontSize(6);
	housekeepingOpenCloseIssueChart.setAxisNameColor('#999');
	/*housekeepingOpenCloseIssueChart.setAxisValuesAngle(15);*/
	housekeepingOpenCloseIssueChart.setAxisValuesColor('#777');
	housekeepingOpenCloseIssueChart.setAxisColor('#B5B5B5');
	housekeepingOpenCloseIssueChart.setAxisWidth(1);
	housekeepingOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	housekeepingOpenCloseIssueChart.setAxisPaddingTop(50);
	housekeepingOpenCloseIssueChart.setAxisPaddingBottom(40);
	housekeepingOpenCloseIssueChart.setAxisPaddingLeft(40);
	housekeepingOpenCloseIssueChart.setTitleFontSize(8);
	housekeepingOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	housekeepingOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	housekeepingOpenCloseIssueChart.setBarBorderWidth(0);
	housekeepingOpenCloseIssueChart.setBarSpacingRatio(40);
	housekeepingOpenCloseIssueChart.setBarOpacity(0.9);
	housekeepingOpenCloseIssueChart.setBarValuesFontSize(6);
	housekeepingOpenCloseIssueChart.setFlagRadius(6);
	housekeepingOpenCloseIssueChart.setLegendShow(true);
	housekeepingOpenCloseIssueChart.setLegendPosition('bottom');
	housekeepingOpenCloseIssueChart.setLegendPadding(10);
	housekeepingOpenCloseIssueChart.setLegendFontSize(7);
	housekeepingOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	housekeepingOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	housekeepingOpenCloseIssueChart.setSize(320, 200);
	housekeepingOpenCloseIssueChart.setGridColor('#F7F7F7');
	housekeepingOpenCloseIssueChart.draw();

	/***** ENGINEERING *****/

	/*** ENGINEERING TOTAL ISSUE GRAPH ***/
	var engineeringTotalIssue = new Array(['Issue', <?php echo $this->totalAllEngineeringIssue['total']; ?>]);
	var engineeringTotalIssueColors = ['#2D6B96'];
	var engineeringTotalIssueChart = new JSChart('engineering-total-issue', 'bar');
	engineeringTotalIssueChart.setDataArray(engineeringTotalIssue);
	engineeringTotalIssueChart.colorizeBars(engineeringTotalIssueColors);
	engineeringTotalIssueChart.setTitle('TOTAL ISSUE');
	engineeringTotalIssueChart.setTitleColor('#8E8E8E');
	engineeringTotalIssueChart.setAxisNameX('');
	engineeringTotalIssueChart.setAxisNameY('');
	engineeringTotalIssueChart.setAxisColor('#C4C4C4');
	engineeringTotalIssueChart.setAxisNameFontSize(6);
	engineeringTotalIssueChart.setAxisValuesFontSize(6);
	engineeringTotalIssueChart.setAxisNameColor('#999');
	engineeringTotalIssueChart.setAxisValuesColor('#7E7E7E');
	engineeringTotalIssueChart.setBarValuesColor('#7E7E7E');
	engineeringTotalIssueChart.setAxisPaddingTop(50);
	engineeringTotalIssueChart.setAxisPaddingRight(40);
	engineeringTotalIssueChart.setAxisPaddingLeft(40);
	engineeringTotalIssueChart.setAxisPaddingBottom(40);
	engineeringTotalIssueChart.setTextPaddingLeft(10);
	engineeringTotalIssueChart.setTitleFontSize(8);
	engineeringTotalIssueChart.setBarBorderWidth(1);
	engineeringTotalIssueChart.setBarBorderColor('#C4C4C4');
	engineeringTotalIssueChart.setBarSpacingRatio(40);
	engineeringTotalIssueChart.setBarValuesFontSize(6);
	engineeringTotalIssueChart.setGrid(false);
	engineeringTotalIssueChart.setSize(125, 200);
	engineeringTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	engineeringTotalIssueChart.draw();


	/*** ENGINEERING ISSUE PER TYPE GRAPH ***/
	var engineeringIssuePerType = new Array(['Incident Report', <?php echo $this->totalEngineeringIncident['total']; ?>], ['Glitch', <?php echo $this->totalEngineeringGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalEngineeringLostFound['total']; ?>], ['Defect List', <?php echo $this->totalEngineeringDefectList['total']; ?>]);
	var engineeringIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var engineeringIssuePerTypeChart = new JSChart('engineering-type-issue', 'bar');
	engineeringIssuePerTypeChart.setDataArray(engineeringIssuePerType);
	engineeringIssuePerTypeChart.colorizeBars(engineeringIssuePerTypeColors);
	engineeringIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	engineeringIssuePerTypeChart.setTitleColor('#8E8E8E');
	engineeringIssuePerTypeChart.setAxisNameX('');
	engineeringIssuePerTypeChart.setAxisNameY('');
	engineeringIssuePerTypeChart.setAxisColor('#C4C4C4');
	engineeringIssuePerTypeChart.setAxisNameFontSize(6);
	engineeringIssuePerTypeChart.setAxisValuesFontSize(6);
	engineeringIssuePerTypeChart.setAxisNameColor('#999');
	engineeringIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	engineeringIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	engineeringIssuePerTypeChart.setAxisPaddingTop(50);
	engineeringIssuePerTypeChart.setAxisPaddingRight(40);
	engineeringIssuePerTypeChart.setAxisPaddingLeft(40);
	engineeringIssuePerTypeChart.setAxisPaddingBottom(40);
	engineeringIssuePerTypeChart.setTextPaddingLeft(10);
	engineeringIssuePerTypeChart.setTitleFontSize(8);
	engineeringIssuePerTypeChart.setBarBorderWidth(1);
	engineeringIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	engineeringIssuePerTypeChart.setBarSpacingRatio(40);
	engineeringIssuePerTypeChart.setBarValuesFontSize(6);
	engineeringIssuePerTypeChart.setGrid(false);
	engineeringIssuePerTypeChart.setSize(320, 200);
	engineeringIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	engineeringIssuePerTypeChart.draw();


	/*** ENGINEERING OPEN & CLOSE ISSUE PER CATEGORY ***/
	var engineeringOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalEngineeringOpenIncident['total']; ?>, <?php echo $this->totalEngineeringCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalEngineeringOpenGlitch['total']; ?>, <?php echo $this->totalEngineeringCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalEngineeringOpenLostFound['total']; ?>, <?php echo $this->totalEngineeringCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalEngineeringOpenDefectList['total']; ?>,<?php echo $this->totalEngineeringCloseDefectList['total']; ?>]);
	var engineeringOpenCloseIssueChart = new JSChart('engineering-open-close', 'bar');
	engineeringOpenCloseIssueChart.setDataArray(engineeringOpenCloseIssue);
	engineeringOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	engineeringOpenCloseIssueChart.setTitleColor('#8E8E8E');
	engineeringOpenCloseIssueChart.setAxisNameX('');
	engineeringOpenCloseIssueChart.setAxisNameY('');
	engineeringOpenCloseIssueChart.setAxisNameFontSize(6);
	engineeringOpenCloseIssueChart.setAxisValuesFontSize(6);
	engineeringOpenCloseIssueChart.setAxisNameColor('#999');
	/*engineeringOpenCloseIssueChart.setAxisValuesAngle(15);*/
	engineeringOpenCloseIssueChart.setAxisValuesColor('#777');
	engineeringOpenCloseIssueChart.setAxisColor('#B5B5B5');
	engineeringOpenCloseIssueChart.setAxisWidth(1);
	engineeringOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	engineeringOpenCloseIssueChart.setAxisPaddingTop(50);
	engineeringOpenCloseIssueChart.setAxisPaddingBottom(40);
	engineeringOpenCloseIssueChart.setAxisPaddingLeft(40);
	engineeringOpenCloseIssueChart.setTitleFontSize(8);
	engineeringOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	engineeringOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	engineeringOpenCloseIssueChart.setBarBorderWidth(0);
	engineeringOpenCloseIssueChart.setBarSpacingRatio(40);
	engineeringOpenCloseIssueChart.setBarOpacity(0.9);
	engineeringOpenCloseIssueChart.setBarValuesFontSize(6);
	engineeringOpenCloseIssueChart.setFlagRadius(6);
	engineeringOpenCloseIssueChart.setLegendShow(true);
	engineeringOpenCloseIssueChart.setLegendPosition('bottom');
	engineeringOpenCloseIssueChart.setLegendPadding(10);
	engineeringOpenCloseIssueChart.setLegendFontSize(7);
	engineeringOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	engineeringOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	engineeringOpenCloseIssueChart.setSize(320, 200);
	engineeringOpenCloseIssueChart.setGridColor('#F7F7F7');
	engineeringOpenCloseIssueChart.draw();

	/***** UTILITY *****/

	/*** UTILITY TOTAL ISSUE GRAPH ***/
	var utilityTotalIssue = new Array(['Issue', <?php echo $this->totalAllUtilityIssue['total']; ?>]);
	var utilityTotalIssueColors = ['#2D6B96'];
	var utilityTotalIssueChart = new JSChart('utility-total-issue', 'bar');
	utilityTotalIssueChart.setDataArray(utilityTotalIssue);
	utilityTotalIssueChart.colorizeBars(utilityTotalIssueColors);
	utilityTotalIssueChart.setTitle('TOTAL ISSUE');
	utilityTotalIssueChart.setTitleColor('#8E8E8E');
	utilityTotalIssueChart.setAxisNameX('');
	utilityTotalIssueChart.setAxisNameY('');
	utilityTotalIssueChart.setAxisColor('#C4C4C4');
	utilityTotalIssueChart.setAxisNameFontSize(6);
	utilityTotalIssueChart.setAxisValuesFontSize(6);
	utilityTotalIssueChart.setAxisNameColor('#999');
	utilityTotalIssueChart.setAxisValuesColor('#7E7E7E');
	utilityTotalIssueChart.setBarValuesColor('#7E7E7E');
	utilityTotalIssueChart.setAxisPaddingTop(50);
	utilityTotalIssueChart.setAxisPaddingRight(40);
	utilityTotalIssueChart.setAxisPaddingLeft(40);
	utilityTotalIssueChart.setAxisPaddingBottom(40);
	utilityTotalIssueChart.setTextPaddingLeft(10);
	utilityTotalIssueChart.setTitleFontSize(8);
	utilityTotalIssueChart.setBarBorderWidth(1);
	utilityTotalIssueChart.setBarBorderColor('#C4C4C4');
	utilityTotalIssueChart.setBarSpacingRatio(40);
	utilityTotalIssueChart.setBarValuesFontSize(6);
	utilityTotalIssueChart.setGrid(false);
	utilityTotalIssueChart.setSize(125, 200);
	utilityTotalIssueChart.setBackgroundImage('chart_bg.jpg');
	utilityTotalIssueChart.draw();


	/*** UTILITY ISSUE PER TYPE GRAPH ***/
	var utilityIssuePerType = new Array(['Incident Report', <?php echo $this->totalUtilityIncident['total']; ?>], ['Glitch', <?php echo $this->totalUtilityGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalUtilityLostFound['total']; ?>], ['Defect List', <?php echo $this->totalUtilityDefectList['total']; ?>]);
	var utilityIssuePerTypeColors = ['#2D6B96', '#2D6B96', '#2D6B96', '#2D6B96'];
	var utilityIssuePerTypeChart = new JSChart('utility-type-issue', 'bar');
	utilityIssuePerTypeChart.setDataArray(utilityIssuePerType);
	utilityIssuePerTypeChart.colorizeBars(utilityIssuePerTypeColors);
	utilityIssuePerTypeChart.setTitle('TOTAL ISSUE PER TYPE');
	utilityIssuePerTypeChart.setTitleColor('#8E8E8E');
	utilityIssuePerTypeChart.setAxisNameX('');
	utilityIssuePerTypeChart.setAxisNameY('');
	utilityIssuePerTypeChart.setAxisColor('#C4C4C4');
	utilityIssuePerTypeChart.setAxisNameFontSize(6);
	utilityIssuePerTypeChart.setAxisValuesFontSize(6);
	utilityIssuePerTypeChart.setAxisNameColor('#999');
	utilityIssuePerTypeChart.setAxisValuesColor('#7E7E7E');
	utilityIssuePerTypeChart.setBarValuesColor('#7E7E7E');
	utilityIssuePerTypeChart.setAxisPaddingTop(50);
	utilityIssuePerTypeChart.setAxisPaddingRight(40);
	utilityIssuePerTypeChart.setAxisPaddingLeft(40);
	utilityIssuePerTypeChart.setAxisPaddingBottom(40);
	utilityIssuePerTypeChart.setTextPaddingLeft(10);
	utilityIssuePerTypeChart.setTitleFontSize(8);
	utilityIssuePerTypeChart.setBarBorderWidth(1);
	utilityIssuePerTypeChart.setBarBorderColor('#C4C4C4');
	utilityIssuePerTypeChart.setBarSpacingRatio(40);
	utilityIssuePerTypeChart.setBarValuesFontSize(6);
	utilityIssuePerTypeChart.setGrid(false);
	utilityIssuePerTypeChart.setSize(320, 200);
	utilityIssuePerTypeChart.setBackgroundImage('chart_bg.jpg');
	utilityIssuePerTypeChart.draw();


	/*** UTILITY OPEN & CLOSE ISSUE PER CATEGORY ***/
	var utilityOpenCloseIssue = new Array(['Incident Report', <?php echo $this->totalUtilityOpenIncident['total']; ?>, <?php echo $this->totalUtilityCloseIncident['total']; ?>], ['Glitch', <?php echo $this->totalUtilityOpenGlitch['total']; ?>, <?php echo $this->totalUtilityCloseGlitch['total']; ?>], ['Lost & Found', <?php echo $this->totalUtilityOpenLostFound['total']; ?>, <?php echo $this->totalUtilityCloseLostFound['total']; ?>], ['Defect List', <?php echo $this->totalUtilityOpenDefectList['total']; ?>,<?php echo $this->totalUtilityCloseDefectList['total']; ?>]);
	var utilityOpenCloseIssueChart = new JSChart('utility-open-close', 'bar');
	utilityOpenCloseIssueChart.setDataArray(utilityOpenCloseIssue);
	utilityOpenCloseIssueChart.setTitle('OPENED & CLOSED ISSUE');
	utilityOpenCloseIssueChart.setTitleColor('#8E8E8E');
	utilityOpenCloseIssueChart.setAxisNameX('');
	utilityOpenCloseIssueChart.setAxisNameY('');
	utilityOpenCloseIssueChart.setAxisNameFontSize(6);
	utilityOpenCloseIssueChart.setAxisValuesFontSize(6);
	utilityOpenCloseIssueChart.setAxisNameColor('#999');
	/*utilityOpenCloseIssueChart.setAxisValuesAngle(15);*/
	utilityOpenCloseIssueChart.setAxisValuesColor('#777');
	utilityOpenCloseIssueChart.setAxisColor('#B5B5B5');
	utilityOpenCloseIssueChart.setAxisWidth(1);
	utilityOpenCloseIssueChart.setBarValuesColor('#2F6D99');
	utilityOpenCloseIssueChart.setAxisPaddingTop(50);
	utilityOpenCloseIssueChart.setAxisPaddingBottom(40);
	utilityOpenCloseIssueChart.setAxisPaddingLeft(40);
	utilityOpenCloseIssueChart.setTitleFontSize(8);
	utilityOpenCloseIssueChart.setBarColor('#2D6B96', 1);
	utilityOpenCloseIssueChart.setBarColor('#9CCEF0', 2);
	utilityOpenCloseIssueChart.setBarBorderWidth(0);
	utilityOpenCloseIssueChart.setBarSpacingRatio(40);
	utilityOpenCloseIssueChart.setBarOpacity(0.9);
	utilityOpenCloseIssueChart.setBarValuesFontSize(6);
	utilityOpenCloseIssueChart.setFlagRadius(6);
	utilityOpenCloseIssueChart.setLegendShow(true);
	utilityOpenCloseIssueChart.setLegendPosition('bottom');
	utilityOpenCloseIssueChart.setLegendPadding(10);
	utilityOpenCloseIssueChart.setLegendFontSize(7);
	utilityOpenCloseIssueChart.setLegendForBar(1, 'Opened');
	utilityOpenCloseIssueChart.setLegendForBar(2, 'Closed');
	utilityOpenCloseIssueChart.setSize(320, 200);
	utilityOpenCloseIssueChart.setGridColor('#F7F7F7');
	utilityOpenCloseIssueChart.draw();

	$("#export-issue-stat").click(function() {
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
		var hk_total_issue = document.getElementById("JSChart_hk-total-issue");
		var hk_type_issue = document.getElementById("JSChart_hk-type-issue");
		var hk_open_close = document.getElementById("JSChart_hk-open-close");
		var eng_total_issue = document.getElementById("JSChart_engineering-total-issue");
		var eng_type_issue = document.getElementById("JSChart_engineering-type-issue");
		var eng_open_close = document.getElementById("JSChart_engineering-open-close");
		var uti_total_issue = document.getElementById("JSChart_utility-total-issue");
		var uti_type_issue = document.getElementById("JSChart_utility-type-issue");
		var uti_open_close = document.getElementById("JSChart_utility-open-close");

		$.ajax({
			method: 'POST',
			url: '/default/statistic/savegraph',
			data: {
				all_total_issue: total_issue.toDataURL("image/png"),
				all_type_issue: type_issue.toDataURL("image/png"),
				all_open_close: open_close.toDataURL("image/png"),
				sec_total_issue: sec_total_issue.toDataURL("image/png"),
				sec_type_issue: sec_type_issue.toDataURL("image/png"),
				sec_open_close: sec_open_close.toDataURL("image/png"),
				saf_total_issue: sec_total_issue.toDataURL("image/png"),
				saf_type_issue: sec_type_issue.toDataURL("image/png"),
				saf_open_close: sec_open_close.toDataURL("image/png"),
				park_total_issue: sec_total_issue.toDataURL("image/png"),
				park_type_issue: sec_type_issue.toDataURL("image/png"),
				park_open_close: sec_open_close.toDataURL("image/png"),
				hk_total_issue: sec_total_issue.toDataURL("image/png"),
				hk_type_issue: sec_type_issue.toDataURL("image/png"),
				hk_open_close: sec_open_close.toDataURL("image/png"),
				eng_total_issue: sec_total_issue.toDataURL("image/png"),
				eng_type_issue: sec_type_issue.toDataURL("image/png"),
				eng_open_close: sec_open_close.toDataURL("image/png"),
				uti_total_issue: sec_total_issue.toDataURL("image/png"),
				uti_type_issue: sec_type_issue.toDataURL("image/png"),
				uti_open_close: sec_open_close.toDataURL("image/png"),
			},
			success: function(data) {
				window.open("/default/statistic/exporttopdf/curdate/"+data, 'Issue Statistic'); 
			}
		});
		
			
	});
});	
</script>