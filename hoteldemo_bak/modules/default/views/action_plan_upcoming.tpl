
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  <h2>Reminder for your Action Plan <?php echo date("Y"); ?></h2>

	  
	  <form class="form-horizontal form-label-left" action="/default/actionplan/updateupcoming" method="POST">
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
	  
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <th width="100">Date</th>
				  <th width="200">Action Plan <?php echo date("Y"); ?></th>	
				  <th width="200">Document as approves</th>
				  <th width="200">Remark</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->schedule))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->schedule as $schedule) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?><input type="hidden" name="scheduleid[]" value="<?php echo $schedule['schedule_id']; ?>" /></th>
				  <td class="date-column"><?php echo $schedule['formatted_schedule_date']; ?></td>
				  <td class="date-column"><?php echo $schedule['activity_name']; ?></td>
				  <td class="date-column"><textarea name="document[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$schedule['document_as_approves']); ?></textarea></td>
				  <td class="date-column"><textarea name="remark[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$schedule['remark']); ?></textarea></td>
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
				  <button id="send" type="button" class="btn btn-success" style="width:200px;" onclick="javascript:location.href='/default/actionplan/sendweeklyreminder/c/<?php echo $this->category_id; ?>'">Send</button>
				</div>
			</div>
		</form>
					
	  </div>
	</div>
</div>
<!-- /page content -->