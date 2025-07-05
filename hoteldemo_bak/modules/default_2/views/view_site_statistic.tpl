<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="user-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle"><?php echo $this->ident['initial']; ?> - Site User Statistic By Login</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/site"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-site-stat" name="view-user-stat" value="Go" style="width:50px;"  class="pivotbtn"> <input type="button" id="export-site-stat" name="export-site-stat" value="Export to PDF" style="width:100px;" class="pivotbtn"></div>
			</form>
		</div>

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Housekeeping</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th style="text-align:left;">Name</th>
					<th width="80">Total Login</th>
					<th width="135">Last Login</th>
				</tr>
				<?php if(!empty($this->usersHk)) { $i=1; foreach($this->usersHk as $uhk) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $uhk['name']; ?></td>
					<td align="center"><?php echo intval($uhk['total_login']); ?></td>
					<td align="center"><?php echo $uhk['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Engineering</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th style="text-align:left;">Name</th>
					<th width="80">Total Login</th>
					<th width="135">Last Login</th>
				</tr>
				<?php if(!empty($this->usersPt)) { $i=1; foreach($this->usersPt as $upt) { ?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $upt['name']; ?></td>
					<td align="center"><?php echo intval($upt['total_login']); ?></td>
					<td align="center"><?php echo $upt['last_login']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4><?php if($this->site_id == 1) { ?>Top Ten <?php } ?>User Statistic By Login</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th align="left" style="text-align:left;">Name</th>
					<th align="left" width="150" style="text-align:left;">Department</th>
					<th width="80">Total Login</th>
					<th width="145">Last Login</th>
					<th width="15"></th>
				</tr>
			</table>
			<div class="stat-overflow">
				<table>
					<?php if(!empty($this->users)) { $i=1; foreach($this->users as $user) { ?>
						<tr>
							<td width="35"><?php echo $i; ?></td>
							<td align="left" style="text-align:left;"><?php echo $user['name']; ?></td>
							<td align="left" width="150" style="text-align:left;"><?php echo $user['department']; ?></td>
							<td width="80" align="center"><?php echo intval($user['total_login']); ?></td>
							<td width="145" align="center"><?php echo $user['last_login']; ?></td>
						</tr>
					<?php $i++; } } ?>
				</table>
			</div>
		</div>	

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4><?php if($this->site_id == 1) { ?>Top Ten <?php } ?>User Statistic By Submitting Kaizen</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th style="text-align:left;">Name</th>
					<th width="150" style="text-align:left;">Department</th>
					<th width="80">Total Kaizen</th>
					<th width="15"></th>
				</tr>
			</table>
			<div class="stat-overflow">
			<table>				
				<?php if(!empty($this->userIssues)) { $j=1; foreach($this->userIssues as $user) { ?>
				<tr>
					<td width="35"><?php echo $j; ?></td>
					<td style="text-align:left;"><?php echo $user['name']; ?></td>
					<td style="text-align:left;" width="150"><?php echo $user['department']; ?></td>
					<td width="80" align="center"><?php echo $user['total_issues']; ?></td>
				</tr>
				<?php $j++; } } ?>
			</table>
			</div>
		</div>


		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4>Top Ten User Statistic By Comments</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th style="text-align:left;">Name</th>
					<th style="text-align:left;">Department</th>
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
		
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->


<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#export-site-stat").click(function() {
		if(window.innerWidth <= 800 && window.innerHeight <= 600) {
			location.href = '/default/statistic/exportsitestatistictopdf/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
		} else {
			window.open("/default/statistic/exportsitestatistictopdf/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
		}		
	});
});	
</script>