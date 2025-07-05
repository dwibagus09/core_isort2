
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  <h2>Reschedule Statistic for <?php echo $this->selectedYear; ?></h2>
		<div id="filter-section">
			<form action="/default/actionplan/reschedulestatistic" METHOD="GET" id="filter-form" >
			<input type="hidden" name="c" value="<?php echo $this->category_id; ?>" />
			<table>
				<tr>
					<td>Year : </td>
					<td>
						<select id="year" name="year">
							<?php for($i=2018; $i <= date("Y"); $i++) { ?>
								<option value="<?php echo $i; ?>" <?php if($i == date("Y")) echo "selected"; ?>><?php echo $i; ?></option>
							<?php }  ?>
						</select>
					</td>
					<td>Filter By : </td>
					<td>
						<select id="filter" name="filter">
							<option value="ts" <?php if($this->filter == "ts") echo "selected"; ?> >Total Reschedule</option>
							<option value="u" <?php if($this->filter == "u") echo "selected"; ?> >User</option>								
						</select>
					</td>
					<td>
						<input type="submit" class="submit-btn" id="submit" name="submit" value="Filter" style="width:auto; margin-top:0px">
					</td>
				</tr>
			</table>
			</form>
		</div>
	  
		<div id="reschedule-statistic">
		<?php if($this->filter == "u") { ?>
			<table class="table table-striped">
					<thead>
					<tr>
						<th width="50">No</th>
						<th width="200">User</th>							
						<th width="100">Total Reschedule</th>
						<th width="350">Target - Activity - Reschedule Date</th>
					</tr>
					</thead>
					<?php
					if(!empty($this->rescheduleStatistic))
					{
				?>
					<tbody>
					<?php
						$i = 1;
						foreach($this->rescheduleStatistic as $stat) { 
					?>
					<tr>
						<td class="date-column"><?php echo $i; ?></th>
						<td class="date-column"><?php echo $stat['name']; ?></td>
						<td class="date-column"><?php echo $stat['total_reschedule']; ?></td>
						<td align="left"><?php echo $stat['activity_list']; ?></td>
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
		<?php } else { ?>
				<table class="table table-striped">
					<thead>
					<tr>
						<th width="50">No</th>
						<th width="250">Target</th>
						<th width="250">Activity</th>	
						<th width="100">Total Reschedule</th>
						<th width="100">Reschedule Date</th>
					</tr>
					</thead>
					<?php
					if(!empty($this->rescheduleStatistic))
					{
				?>
					<tbody>
					<?php
						$i = 1;
						foreach($this->rescheduleStatistic as $stat) { 
					?>
					<tr>
						<td class="date-column"><?php echo $i; ?></th>
						<td class="date-column"><?php echo $stat['target_name']; ?></td>
						<td class="date-column"><?php echo $stat['activity_name']; ?></td>
						<td class="date-column"><?php echo $stat['total_reschedule']; ?></td>
						<td class="date-column"><?php echo $stat['reschedule_dates']; ?></td>
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
		<?php } ?>
		</div>	
			<div class="ln_solid"></div>
			<div class="form-group">
		
			</div>
					
	  </div>
	</div>
</div>
<!-- /page content -->