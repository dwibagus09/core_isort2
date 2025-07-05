<!-- page content -->
<div class="right_col" role="main">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  <h2>Review <?php echo $this->category['category_name']; ?> Action Plan Activity <?php echo date("Y"); ?></h2>

	  
	  <form class="form-horizontal form-label-left" action="/default/actionplan/updatereview" method="POST">
			<input type="hidden" name="category_id" value="<?php echo $this->category_id; ?>" />
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left; width:120px;" for="name">Month
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php echo $this->month; ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left; width:120px;" for="reporting_time">Week (Date)
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->week." (".$this->startdate." - ".$this->enddate.")"; ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" style="text-align:left; width:120px;" for="reporting_time">Unit
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->sitename; ?>
				</div>
			  </div>

			<h4>Outstanding Action Plan <?php echo date("Y"); ?> Cumulated</h4>
	  
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <th width="100">Date</th>
				  <th width="200">Planning Action <?php echo date("Y"); ?></th>	
				  <th width="200">Document as approves</th>
				  <th width="200">Status</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->curYearReviewSchedule))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->curYearReviewSchedule as $curYearReviewSchedule) {
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?><input type="hidden" name="scheduleid[]" value="<?php echo $curYearReviewSchedule['schedule_id']; ?>" /></th>
				  <td class="date-column"><?php echo $curYearReviewSchedule['formatted_schedule_date']; ?></td>
				  <td class="date-column"><?php echo $curYearReviewSchedule['activity_name']; ?></td>
				  <td class="date-column"><textarea name="document[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$curYearReviewSchedule['document_as_approves']); ?></textarea></td>
				  <td class="date-column"><textarea name="remark[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$curYearReviewSchedule['remark']); ?></textarea></td>
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

			<h4>Review Previous Week</h4>

			<table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50" rowspan="2">No</th>
				  <th width="100" rowspan="2">Date</th>
				  <th width="200" rowspan="2">Planning Action <?php echo date("Y"); ?></th>	
				  <th width="200" rowspan="2">Document as approves</th>
					<th width="200" colspan="2">Status</th>
				  <th width="200" rowspan="2">Remark</th>
				</tr>
				<tr>
					<th width="100">Achieved</th>
					<th width="100">Miss</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->lastWeekReviewSchedule))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->lastWeekReviewSchedule as $lastWeekReviewSchedule) {
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?><input type="hidden" name="scheduleid2[]" value="<?php echo $lastWeekReviewSchedule['schedule_id']; ?>" /></th>
				  <td class="date-column"><?php echo $lastWeekReviewSchedule['formatted_schedule_date']; ?></td>
				  <td class="date-column"><?php echo $lastWeekReviewSchedule['activity_name']; ?></td>
				  <td align="left">
					<?php if($lastWeekReviewSchedule['status'] == 1) { 
						if(!empty($lastWeekReviewSchedule['documents']))
						{
							echo "<ul>";
							foreach($lastWeekReviewSchedule['documents'] as $document) {
									echo '<li><a href="'.$this->baseURL.'/actionplan/'.strtolower($this->category['category_name']).'/'.$document['filename'].'" target="_blank">'.$document['filename'].'</a></li>';
							}
							echo "</ul>";
						} ?>
						<input type="hidden" name="document2[]" value="#*#*#" />
					<?php } else { ?>
					<textarea name="document2[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$lastWeekReviewSchedule['document_as_approves']); ?></textarea>
					<?php } ?>
					</td>
				  <td align="center"><input type="radio" name="status_achieved<?php echo $i; ?>" value="1" <?php if($lastWeekReviewSchedule['status'] == 1) echo "checked"; ?>></td>
					<td align="center"><input type="radio" name="status_achieved<?php echo $i; ?>" value="<?php if($lastWeekReviewSchedule['status'] == 2) echo "2"; else echo "0"; ?> " <?php if($lastWeekReviewSchedule['status'] != 1) echo "checked"; ?>></td>
					<td class="date-column"><textarea name="remark2[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$lastWeekReviewSchedule['remark']); ?></textarea></td>
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
			
			<div class="ln_solid"></div>
			<div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="save" type="submit" class="btn btn-success" style="width:200px;">Simpan</button>
				  <button id="send" type="button" class="btn btn-success" style="width:200px;" onclick="javascript:location.href='/default/actionplan/sendweeklyreview/c/<?php echo $this->category_id; ?>'">Send</button>
				</div>
			</div>
		</form> 
					
	  </div>
	</div>
</div>
<!-- /page content -->