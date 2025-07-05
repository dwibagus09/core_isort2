<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle">MOD Schedule Report <?php echo $this->selectedMonthYear; ?></h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/mod/schedulereport"  method="post">
				<div class="statistic-filter-field">Month : <select name="month" style="padding: 2px;">
						<option value="01" <?php if($this->selectedMonth == "01") echo "selected"; ?>>January</option>
						<option value="02" <?php if($this->selectedMonth == "02") echo "selected"; ?>>February</option>
						<option value="03" <?php if($this->selectedMonth == "03") echo "selected"; ?>>March</option>
						<option value="04" <?php if($this->selectedMonth == "04") echo "selected"; ?>>April</option>
						<option value="05" <?php if($this->selectedMonth == "05") echo "selected"; ?>>May</option>
						<option value="06" <?php if($this->selectedMonth == "06") echo "selected"; ?>>June</option>
						<option value="07" <?php if($this->selectedMonth == "07") echo "selected"; ?>>July</option>
						<option value="08" <?php if($this->selectedMonth == "08") echo "selected"; ?>>August</option>
						<option value="09" <?php if($this->selectedMonth == "09") echo "selected"; ?>>September</option>
						<option value="10" <?php if($this->selectedMonth == "10") echo "selected"; ?>>October</option>
						<option value="11" <?php if($this->selectedMonth == "11") echo "selected"; ?>>November</option>
						<option value="12" <?php if($this->selectedMonth == "12") echo "selected"; ?>>December</option>
					</select>
				</div>
				<div class="statistic-filter-field">Year :	<select name="year" style="padding: 2px;">
				<?php for($i=2019; $i<=date("Y"); $i++) { ?>
						<option value="<?php echo $i; ?>" <?php if($this->selectedYear == $i) echo "selected"; ?>><?php echo $i; ?></option>
				<?php } ?>
					</select></div>
				<div class="statistic-filter-field"><input type="submit" id="view-site-stat" name="view-user-stat" value="Go" style="width:50px; margin-top:-1px;"> <a target="_blank" href="/default/mod/exportscheduletopdf/m/<?php echo $this->selectedMonth; ?>/y/<?php echo $this->selectedYear; ?>"><img id="export-mod-schedule" src="/images/newlogo_pdf.png" style="cursor:pointer; margin-top:-5px; margin-left:5px;" width="24"></a></div>
			</form>
		</div>
		<br/>
		  <table class="table table-striped" id="mod-schedule-table">
			  <thead>
				<tr>
				  <th>Name</th>
					<?php if(!empty($this->dateList)) { foreach($this->dateList as $date) {
							echo '<th width="50">'.$date.'</th>';
					} } ?>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->modUsers))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->modUsers as $modUsers) { 
				?>
				<tr>
				  <td style="text-align:left;"><?php echo $modUsers['name']; ?></td>
					<?php if(!empty($this->dateList)) {  foreach($this->dateList as $date) {
							echo '<td style="color:red">'.$modUsers[$date].'</td>';
					} }	?>
				</tr>
				<?php
						$i++;
					}
				?>				
			  </tbody>
			<?php
				}
			?>
			</table>
					
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">

</script>