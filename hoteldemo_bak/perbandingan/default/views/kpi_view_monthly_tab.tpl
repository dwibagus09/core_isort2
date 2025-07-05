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
<div id="monthly-kpi-<?php echo $this->curTab; ?>" class="graph">Loading Monthly KPI..</div>


<!-- Magnific Popup core JS file -->
<script type="text/javascript" src="/js/JSCharts/sources/jscharts.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	/*** MONTHLY KPI GRAPHIC ***/
	var monthlyKPIDataChart<?php echo $this->curTab; ?> = new JSChart('monthly-kpi-<?php echo $this->curTab; ?>', 'line');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setDataArray([[1, <?php echo $this->lastYearMonthlyPercentage[1]; ?>],[2, <?php echo $this->lastYearMonthlyPercentage[2]; ?>],[3, <?php echo $this->lastYearMonthlyPercentage[3]; ?>],[4, <?php echo $this->lastYearMonthlyPercentage[4]; ?>],[5, <?php echo $this->lastYearMonthlyPercentage[5]; ?>],[6, <?php echo $this->lastYearMonthlyPercentage[6]; ?>],[7, <?php echo $this->lastYearMonthlyPercentage[7]; ?>],[8, <?php echo $this->lastYearMonthlyPercentage[8]; ?>],[9, <?php echo $this->lastYearMonthlyPercentage[9]; ?>],[10, <?php echo $this->lastYearMonthlyPercentage[10]; ?>],[11, <?php echo $this->lastYearMonthlyPercentage[11]; ?>],[12, <?php echo $this->lastYearMonthlyPercentage[12]; ?>]], 'blue');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setDataArray([[1, <?php echo $this->monthlyPercentage[1]; ?>],[2, <?php echo $this->monthlyPercentage[2]; ?>],[3, <?php echo $this->monthlyPercentage[3]; ?>],[4, <?php echo $this->monthlyPercentage[4]; ?>],[5, <?php echo $this->monthlyPercentage[5]; ?>],[6, <?php echo $this->monthlyPercentage[6]; ?>],[7, <?php echo $this->monthlyPercentage[7]; ?>],[8, <?php echo $this->monthlyPercentage[8]; ?>],[9, <?php echo $this->monthlyPercentage[9]; ?>],[10, <?php echo $this->monthlyPercentage[10]; ?>],[11, <?php echo $this->monthlyPercentage[11]; ?>],[12, <?php echo $this->monthlyPercentage[12]; ?>]], 'green');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setDataArray([[1, 0],[2, 0],[3, 0],[4, 0],[5, 0],[6, 0],[7, 0],[8, 0],[9, 0],[10, 0],[11, 0],[12, 0]], 'yellow');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTitle('<?php ucfirst($this->category['category_name']); ?> Monthly KPI for <?php echo (date("Y")-1)." - ".(date("Y")+1); ?>');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setSize(750, 300);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisNameX('Month');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisNameY('');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setIntervalStartY(1);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setIntervalEndY(200);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([1,'January']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([2,'February']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([3,'March']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([4,'April']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([5,'May']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([6,'June']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([7,'July']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([8,'August']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([9,'September']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([10,'October']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([11,'November']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLabelX([12,'December']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisValuesNumberX(12);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisValuesNumberY(5);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setShowXValues(false);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTitleColor('#454545');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisValuesColor('#454545');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLineColor("#7e5b06", 'blue');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLineColor("#a1a2a6", 'green');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLineColor("#e1cb95", 'yellow');
	/*monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([1, 'January <?php echo $this->monthlyPercentage[1]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([2, 'February <?php echo $this->monthlyPercentage[2]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([3, 'March <?php echo $this->monthlyPercentage[3]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([4, 'April <?php echo $this->monthlyPercentage[4]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([5, 'May <?php echo $this->monthlyPercentage[5]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([6, 'June <?php echo $this->monthlyPercentage[6]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([7, 'July <?php echo $this->monthlyPercentage[7]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([8, 'August <?php echo $this->monthlyPercentage[8]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([9, 'September <?php echo $this->monthlyPercentage[9]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([10, 'October <?php echo $this->monthlyPercentage[10]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([11, 'November <?php echo $this->monthlyPercentage[11]; ?>%']);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setTooltip([12, 'December <?php echo $this->monthlyPercentage[12]; ?>%']);*/
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setFlagColor('#9D16FC');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setFlagRadius(4);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setAxisPaddingRight(100);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLegendShow(true);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLegendPosition(680, 80);
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLegendForLine('blue', '<?php echo date("Y")-1; ?>');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLegendForLine('green', '<?php echo date("Y"); ?>');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.setLegendForLine('yellow', '<?php echo date("Y")+1; ?>');
	monthlyKPIDataChart<?php echo $this->curTab; ?>.draw();
});
</script>