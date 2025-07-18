<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/js/FullWidthTabs/css/component.css" />
<style>
.mytooltip {
  color: red !important;
  padding: 5px 20px;
  text-align: center;
  font: bold 14px ;
  text-decoration: none;
}
</style>

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle"><?php echo strtoupper($this->category['category_name']); ?> MONTHLY KPI - <?php echo $this->selectedYear; ?></h2>
			<div class="clearfix"></div>			
			<?php /*<a href="/default/safety/downloadsafetyreport/id/<?php echo $this->safety['report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a> */ ?>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div>
			<div id="tabs" class="tabs">
				<nav>
					<ul>
						<li id="section1" class="tab tab-current" data-id="1"><a href="#section-1"><span>Chief</span></a></li>
						<li id="section2" class="tab" data-id="2"><a href="#section-2"><span>Spv</span></a></li>
						<li id="section3" class="tab" data-id="3"><a href="#section-3"><span>Staff</span></a></li>
						<li id="section4" class="tab" data-id="4"><a href="#section-4"><span>Admin</span></a></li>
					</ul>
				</nav>
				<div id="kpi" class="content">
					<section id="section-1" class="content-current">
						<table class="kpi-monthly-table" style="margin-bottom:0px; float:left; margin-right:30px;">
							<thead>
								<tr>
									<th>MONTH</th>
									<th><?php echo date("Y")-1; ?></th>
									<th><?php echo date("Y"); ?></th>
									<th><?php echo date("Y")+1; ?></th>
								</tr>
								<tr>
									<td width="50%"><strong>January</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[1]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[1]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>February</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[2]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[2]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>March</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[3]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[3]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>April</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[4]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[4]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>May</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[5]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[5]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>June</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[6]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[6]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>July</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[7]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[7]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>August</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[8]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[8]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>September</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[9]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[9]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>October</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[10]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[10]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>November</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[11]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[11]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr>
									<td><strong>December</strong></td>
									<td><?php echo $this->lastYearMonthlyPercentage[12]; ?>%</td>
									<td><?php echo $this->monthlyPercentage[12]; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
								<tr style="background-color:#ffd03f; color:black; font-weight:bold;">
									<td>Grand Total</td>
									<td><?php echo $this->lastYearGrandTotal; ?>%</td>
									<td><?php echo $this->grandTotal; ?>%</td>
									<td><?php echo "0"; ?>%</td>
								</tr>
							</thead>
						</table>
						<div id="monthly-kpi" class="graph">Loading Monthly KPI..</div>
					</section>
					<section id="section-2">
						
					</section>
					<section id="section-3">
						
					</section>
					<section id="section-4">
						
					</section>
				</div><!-- /content -->
			</div><!-- /tabs -->
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script type="text/javascript" src="/js/JSCharts/sources/jscharts.js"></script>
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/FullWidthTabs/js/cbpFWTabs.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	new CBPFWTabs( document.getElementById( 'tabs' ) );

	$('.tab').click(function() {
		$("body").mLoading();
		var tab = this.dataset.id;
		$.ajax({
			async : true,
			url: "/default/kpi/viewmonthly",
			data: { c : <?php echo $this->category['category_id']; ?>,
					tab : tab,
					open_tab : '1'
			}
		}).done(function(response) {
			$( "#section"+tab ).removeClass( "tab-current" );
			$( "#section-"+tab ).removeClass( "content-current" );
			$( "#section-"+tab).html(response);
			$( "#section"+tab ).addClass( "tab-current" );
			$( "#section-"+tab ).addClass( "content-current" );
			$("body").mLoading('hide');
		});
	});

	/*** MONTHLY KPI GRAPHIC ***/
	var monthlyKPIDataChart = new JSChart('monthly-kpi', 'line');
	monthlyKPIDataChart.setDataArray([[1, <?php echo $this->lastYearMonthlyPercentage[1]; ?>],[2, <?php echo $this->lastYearMonthlyPercentage[2]; ?>],[3, <?php echo $this->lastYearMonthlyPercentage[3]; ?>],[4, <?php echo $this->lastYearMonthlyPercentage[4]; ?>],[5, <?php echo $this->lastYearMonthlyPercentage[5]; ?>],[6, <?php echo $this->lastYearMonthlyPercentage[6]; ?>],[7, <?php echo $this->lastYearMonthlyPercentage[7]; ?>],[8, <?php echo $this->lastYearMonthlyPercentage[8]; ?>],[9, <?php echo $this->lastYearMonthlyPercentage[9]; ?>],[10, <?php echo $this->lastYearMonthlyPercentage[10]; ?>],[11, <?php echo $this->lastYearMonthlyPercentage[11]; ?>],[12, <?php echo $this->lastYearMonthlyPercentage[12]; ?>]], 'blue');
	monthlyKPIDataChart.setDataArray([[1, <?php echo $this->monthlyPercentage[1]; ?>],[2, <?php echo $this->monthlyPercentage[2]; ?>],[3, <?php echo $this->monthlyPercentage[3]; ?>],[4, <?php echo $this->monthlyPercentage[4]; ?>],[5, <?php echo $this->monthlyPercentage[5]; ?>],[6, <?php echo $this->monthlyPercentage[6]; ?>],[7, <?php echo $this->monthlyPercentage[7]; ?>],[8, <?php echo $this->monthlyPercentage[8]; ?>],[9, <?php echo $this->monthlyPercentage[9]; ?>],[10, <?php echo $this->monthlyPercentage[10]; ?>],[11, <?php echo $this->monthlyPercentage[11]; ?>],[12, <?php echo $this->monthlyPercentage[12]; ?>]], 'green');
	monthlyKPIDataChart.setDataArray([[1, 0],[2, 0],[3, 0],[4, 0],[5, 0],[6, 0],[7, 0],[8, 0],[9, 0],[10, 0],[11, 0],[12, 0]], 'yellow');
	monthlyKPIDataChart.setTitle('<?php ucfirst($this->category['category_name']); ?> Monthly KPI for <?php echo (date("Y")-1)." - ".(date("Y")+1); ?>');
	monthlyKPIDataChart.setSize(750, 300);
	monthlyKPIDataChart.setAxisNameX('Month');
	monthlyKPIDataChart.setAxisNameY('');
	monthlyKPIDataChart.setAxisValuesNumberX(12);
	monthlyKPIDataChart.setAxisValuesNumberY(5);
	monthlyKPIDataChart.setIntervalStartY(1);
	monthlyKPIDataChart.setIntervalEndY(200);
	monthlyKPIDataChart.setLabelX([1,'January']);
	monthlyKPIDataChart.setLabelX([2,'February']);
	monthlyKPIDataChart.setLabelX([3,'March']);
	monthlyKPIDataChart.setLabelX([4,'April']);
	monthlyKPIDataChart.setLabelX([5,'May']);
	monthlyKPIDataChart.setLabelX([6,'June']);
	monthlyKPIDataChart.setLabelX([7,'July']);
	monthlyKPIDataChart.setLabelX([8,'August']);
	monthlyKPIDataChart.setLabelX([9,'September']);
	monthlyKPIDataChart.setLabelX([10,'October']);
	monthlyKPIDataChart.setLabelX([11,'November']);
	monthlyKPIDataChart.setLabelX([12,'December']);
	monthlyKPIDataChart.setShowXValues(false);
	monthlyKPIDataChart.setTitleColor('#454545');
	monthlyKPIDataChart.setAxisValuesColor('#454545');
	monthlyKPIDataChart.setLineColor("#7e5b06", 'blue');
	monthlyKPIDataChart.setLineColor('#A4D314', 'green');
	monthlyKPIDataChart.setLineColor('#f9d91b', 'yellow');
	/*monthlyKPIDataChart.setTooltip([1, 'January <?php echo $this->monthlyPercentage[1]; ?>%']);
	monthlyKPIDataChart.setTooltip([2, 'February <?php echo $this->monthlyPercentage[2]; ?>%']);
	monthlyKPIDataChart.setTooltip([3, 'March <?php echo $this->monthlyPercentage[3]; ?>%']);
	monthlyKPIDataChart.setTooltip([4, 'April <?php echo $this->monthlyPercentage[4]; ?>%']);
	monthlyKPIDataChart.setTooltip([5, 'May <?php echo $this->monthlyPercentage[5]; ?>%']);
	monthlyKPIDataChart.setTooltip([6, 'June <?php echo $this->monthlyPercentage[6]; ?>%']);
	monthlyKPIDataChart.setTooltip([7, 'July <?php echo $this->monthlyPercentage[7]; ?>%']);
	monthlyKPIDataChart.setTooltip([8, 'August <?php echo $this->monthlyPercentage[8]; ?>%']);
	monthlyKPIDataChart.setTooltip([9, 'September <?php echo $this->monthlyPercentage[9]; ?>%']);
	monthlyKPIDataChart.setTooltip([10, 'October <?php echo $this->monthlyPercentage[10]; ?>%']);
	monthlyKPIDataChart.setTooltip([11, 'November <?php echo $this->monthlyPercentage[11]; ?>%']);
	monthlyKPIDataChart.setTooltip([12, 'December <?php echo $this->monthlyPercentage[12]; ?>%']);*/
	monthlyKPIDataChart.setFlagColor('#9D16FC');
	monthlyKPIDataChart.setFlagRadius(4);
	monthlyKPIDataChart.setAxisPaddingRight(100);
	monthlyKPIDataChart.setLegendShow(true);
	monthlyKPIDataChart.setLegendPosition(680, 80);
	monthlyKPIDataChart.setLegendForLine('blue', '<?php echo date("Y")-1; ?>');
	monthlyKPIDataChart.setLegendForLine('green', '<?php echo date("Y"); ?>');
	monthlyKPIDataChart.setLegendForLine('yellow', '<?php echo date("Y")+1; ?>');
	monthlyKPIDataChart.draw();
});
</script>