<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

  <div class="">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <div class="x_title">
			<h2><?php echo $this->title; ?></h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">

			<form class="form-horizontal form-label-left" action="/default/housekeeping/savereport3" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="housekeeping_report_id" name="housekeeping_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['housekeeping_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->housekeeping['report_date'])) echo $this->housekeeping['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['housekeeping_reporting_time']; ?>
				</div>
			  </div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">
				<span class="section" style="clear:both; padding-top:20px;">PROGRESS REPORT</span>								
				<fieldset>
					<legend>Pest Control dan Informasi Lainnya  &nbsp;<a id="add-other-info" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<div class="table-dv">
					<table id="other-info-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Area</th>
							  <th rowspan="2">Progress</th>
							  <th rowspan="2">Status</th>
							  <th></th>
							</tr>
						  </thead>
						  <tbody>
							<?php if(!empty($this->other_info)) {
								foreach($this->other_info as $other_info) { ?>
									<tr>
										<td><textarea name="other_info_area[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$other_info['area']); ?></textarea></td>
										<td align="center"><?php if(!empty($other_info['img_progress'])) { ?><a class="image-popup-vertical-fit" href="/images/progress_report/<?php echo $other_info['img_progress']; ?>"><img src="/images/progress_report/<?php echo $other_info['img_progress']; ?>" class="thumb-img" /></a><?php } ?><input type="file" name="other_info_progress[]" accept="image/jpeg"></td>
										<td><textarea name="other_info_status[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$other_info['status']); ?></textarea></td>
										<td align="center" style="vertical-align:middle;"><input type="hidden" name="other_info_id[]" value="<?php echo intval($other_info['other_info_id']); ?>"><i class="fa fa-trash remove-issue" onclick="$(this).closest('tr').remove();"></i></td>
									</tr>	
							<?php } } ?>		
						</tbody>
					 </table>
					 </div>
				</fieldset>
			</div>
			
			<span class="section">Attachment &nbsp;<a id="add-attachment" href="#attachment-table-form"><i id="add-attachment" class="fa fa-plus-square"></i></a></span>
			<table id="attachment-table" class="table">
			  <thead>
				<tr>
				  <th class="id-hidden"></th>
				  <th width="200">Filename</th>
				  <th>Description</th>
				  <th width="30"></th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->attachment)) {
						foreach($this->attachment as $attachment) {
				?>
				<tr id="attachment<?php echo $attachment['attachment_id']; ?>">
				  <td class="id-hidden"><input type="hidden" id="attachment_id" name="attachment_id[]" value="<?php echo $attachment['attachment_id']; ?>"></td>
				  <td><?php echo '<a href="/attachment/housekeeping/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['filename'].'</a>'; ?> <input type="file" name="attachment_file[]"></td>
				  <td><input type="text" name="attachment-description[]" class="form-control col-md-7 col-xs-12" value="<?php echo $attachment['description']; ?>" required /></td>
				  <td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest('tr').remove();"></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:200px;">Simpan</button>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {	
	$(".edit-housekeeping").css("display", "block");
	$(".edit-housekeeping").addClass('current-page');
	$(".edit-housekeeping").addClass('current-page').parents('ul').slideDown().parent().addClass('active');

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
	$("#add-other-info").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><textarea name="other_info_area[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><input type="file" name="other_info_progress[]" accept="image/jpeg"></td>
			<td><textarea name="other_info_status[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#other-info-table").append(row);
	});
	
	$("#add-attachment").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td><input type="hidden" name="attachment_id[]" class="form-control col-md-7 col-xs-12" required></td>									
				<td align="center"><input type="file" name="attachment_file[]"></td>
				<td><input type="text" name="attachment-description[]" class="form-control col-md-7 col-xs-12" required /></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#attachment-table").append(row);
	});
});	
</script>