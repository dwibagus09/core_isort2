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
		  <form class="form-horizontal form-label-left" action="#">
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
			</form>
		<div class="col-md-12 col-sm-12 col-xs-12">
			<span class="section" style="clear:both; padding-top:20px;">PROGRESS REPORT</span>	

			<fieldset>
				<legend>Progress Report Shift 1&2  &nbsp;<a class="add-progress-report" href="#progress-report-form" data-shift="12" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
				<div class="table-dv">
				<table id="progress-report-shift12-table" class="table">
					  <thead>
						<tr>
						  <th rowspan="2">Area</th>
						  <th width="175" rowspan="2">Before</th>
						  <th width="175" rowspan="2">Progress</th>
						  <th width="175" rowspan="2">After</th>
						  <th rowspan="2">Status</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->progress_report_shift12)) {
							foreach($this->progress_report_shift12 as $progress_report_shift12) { ?>
							<tr>
								<td><?php echo str_replace("<br>","&#13;",$progress_report_shift12['area']); ?></td>									
								<td align="center"><?php if(!empty($progress_report_shift12['img_before'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_before']; ?>"><img src="<?php echo $progress_report_shift12['img_before']; ?>" class="thumb-img" /></a><?php } ?></td>									
								<td align="center"><?php if(!empty($progress_report_shift12['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_progress']; ?>"><img src="<?php echo $progress_report_shift12['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>									
								<td align="center"><?php if(!empty($progress_report_shift12['img_after'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_after']; ?>"><img src="<?php echo $progress_report_shift12['img_after']; ?>" class="thumb-img" /></a><?php } ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$progress_report_shift12['status']); ?></td>
								<td align="center" style="vertical-align:middle;"><a class="add-progress-report" href="#progress-report-form" data-shift="12" data-id="<?php echo $progress_report_shift12['progress_report_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-progress-report" data-id="<?php echo $progress_report_shift12['progress_report_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>	
						<?php } } ?>	
					</tbody>
				 </table>
				 </div>
			</fieldset>
			
			<fieldset>
				<legend>Progress Report Shift 3  &nbsp;<a class="add-progress-report" href="#progress-report-form" data-shift="3" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
				<div class="table-dv">
				<table id="progress-report-shift3-table" class="table">
					  <thead>
						<tr>
						  <th rowspan="2">Area</th>
						  <th width="175" rowspan="2">Before</th>
						  <th width="175" rowspan="2">Progress</th>
						  <th width="175" rowspan="2">After</th>
						  <th rowspan="2">Status</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->progress_report_shift3)) {
							foreach($this->progress_report_shift3 as $progress_report_shift3) { ?>
								<tr>
									<td><?php echo str_replace("<br>","&#13;",$progress_report_shift3['area']); ?></td>
									<td align="center"><?php if(!empty($progress_report_shift3['img_before'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_before']; ?>"><img src="<?php echo $progress_report_shift3['img_before']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td align="center"><?php if(!empty($progress_report_shift3['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_progress']; ?>"><img src="<?php echo $progress_report_shift3['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td align="center"><?php if(!empty($progress_report_shift3['img_after'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_after']; ?>"><img src="<?php echo $progress_report_shift3['img_after']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td><?php echo str_replace("<br>","&#13;",$progress_report_shift3['status']); ?></td>
									<td align="center" style="vertical-align:middle;"><a class="add-progress-report" href="#progress-report-form" data-shift="3" data-id="<?php echo $progress_report_shift3['progress_report_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-progress-report" data-id="<?php echo $progress_report_shift3['progress_report_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
								</tr>	
						<?php } } ?>		
					</tbody>
				 </table>
				 </div>
			</fieldset>
			
			<fieldset>
				<legend>Pest Control dan Informasi Lainnya  &nbsp;<a class="add-other-info" href="#other-info-form" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
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
									<td><?php echo str_replace("<br>","&#13;",$other_info['area']); ?></td>
									<td align="center"><?php if(!empty($other_info['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $other_info['img_progress']; ?>"><img src="<?php echo $other_info['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td><?php echo str_replace("<br>","&#13;",$other_info['status']); ?></td>
									<td align="center" style="vertical-align:middle;"><a class="add-other-info" href="#other-info-form" data-id="<?php echo $other_info['other_info_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-other-info" data-id="<?php echo $other_info['other_info_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
								</tr>	
						<?php } } ?>		
					</tbody>
				 </table>
				 </div>
			</fieldset>
			
			<span class="section">Attachment &nbsp;<a class="add-attachment" href="#attachment-form"><i id="add-attachment" class="fa fa-plus-square"></i></a></span>
			<table id="attachment-table" class="table">
			  <thead>
				<tr>
				  <th>Description</th>
				  <th width="30"></th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->attachment)) {
						foreach($this->attachment as $attachment) {
				?>
				<tr id="attachment<?php echo $attachment['attachment_id']; ?>">
				  <td><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/2/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></td>
				  <td align="center" style="vertical-align:middle;"><a class="add-attachment" href="#attachment-form" data-id="<?php echo $attachment['attachment_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-attachment" data-id="<?php echo $attachment['attachment_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
				
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/housekeeping/edit/id/<?php echo $this->housekeeping['housekeeping_report_id']; ?>'">Kembali ke halaman sebelumnya</button>  <button id="next" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/manpower/view/c/2'">Ke halaman berikutnya</button>
				</div>
			  </div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Progress Report form -->
  <form action="/default/housekeeping/addprogressreport" id="progress-report-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-progress-report-title"></div>
	<input type="hidden" id="progress_report_id" name="progress_report_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="housekeeping_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['housekeeping_report_id']; ?>">
	<input type="hidden" id="shift" name="shift" class="form-control col-md-7 col-xs-12">
	<table id="add-progress-report-table">
		<tr>
			<td>Area<td>
			<td><textarea id="area" name="area" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Before<td>
			<td align="center"><div id="img-before-report"></div><input type="file" name="img_before" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Progress<td>
			<td align="center"><div id="img-progress-report"></div><input type="file" name="img_progress" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>After<td>
			<td align="center"><div id="img-after-report"></div><input type="file" name="img_after" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Status<td>
			<td align="center"><textarea id="status" name="status" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>
	<div class="add-btn"><input type="submit" id="add-progress-report-submit" name="add-progress-report-submit" value="Save"></div>
  </form>
<!-- End of progress report form -->

<!-- Other Info form -->
  <form action="/default/housekeeping/addotherinfo" id="other-info-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-other-info-title"></div>
	<input type="hidden" id="other_info_id" name="other_info_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="housekeeping_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['housekeeping_report_id']; ?>">
	<table id="add-other-info-table">
		<tr>
			<td>Area<td>
			<td><textarea id="area_other" name="area" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Progress<td>
			<td align="center"><div id="img-progress-other"></div><input type="file" name="img_progress" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Status<td>
			<td align="center"><textarea id="status_other" name="status" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>
	<div class="add-btn"><input type="submit" id="add-other-info-submit" name="add-other-info-submit" value="Save"></div>
  </form>
<!-- End of other info form -->

<!-- Attachment form -->
  <form action="/default/housekeeping/addattachment" id="attachment-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-attachment-title"></div>
	<input type="hidden" id="attachment_id" name="attachment_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['housekeeping_report_id']; ?>">
	<table id="add-attachment-table">
		<tr>
			<td>File<td>
			<td align="center"><div id="attachment_file"></div><input type="file" name="attachment_file"></td>
		</tr>
		<tr>
			<td>Description<td>
			<td align="center"><textarea id="attachment_description" name="attachment_description" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>
	<div class="add-btn"><input type="submit" id="add-attachment-submit" name="add-attachment-submit" value="Save"></div>
  </form>
<!-- End of attachment form -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {	
	$(".edit-housekeeping").css("display", "block");
	$(".edit-housekeeping").addClass('current-page');
	$(".edit-housekeeping").addClass('current-page').parents('ul').slideDown().parent().addClass('active');

	$.ajax({
		url: "/default/housekeeping/savepdf",
		data: { id : '<?php echo $this->housekeeping['housekeeping_report_id']; ?>' }
	}).done(function(response) {
	});	
	
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
	$('.add-progress-report').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#progress-report-form')[0].reset();
				$("#img-before-report").html('');
				$("#img-progress-report").html('');
				$("#img-after-report").html('');
			}
		}
	});
	
	$(".add-progress-report").click(function() {
		var id = this.dataset.id;
		if(this.dataset.shift == '12')
		{
			if(id > 0)
			{
				$("#add-progress-report-title").html("Edit Progress Report Shift 1 & 2");
				$.ajax({
					url: "/default/housekeeping/getprogressreportbyid",
					data: { id : id }
				}).done(function(response) {
					var obj = jQuery.parseJSON(response);
					$("#progress_report_id").val(obj.progress_report_id);
					$("#housekeeping_report_id").val(obj.housekeeping_report_id);
					$("#shift").val(obj.shift);
					$("#area").val(obj.area);
					$("#status").val(obj.status);
					if(obj.img_before != null)
					{
						$("#img-before-report").html('<img src="'+obj.img_before+'" width="50" />');
					}
					if(obj.img_progress != null)
					{
						$("#img-progress-report").html('<img src="'+obj.img_progress+'" width="50" />');
					}
					if(obj.img_after != null)
					{
						$("#img-after-report").html('<img src="'+obj.img_after+'" width="50" />');
					}
				});	
			}
			else
			{
				$("#add-progress-report-title").html("Add Progress Report Shift 1 & 2");
				$("#shift").val(this.dataset.shift);
			}
		}
		else if(this.dataset.shift == '3')
		{
			if(id > 0)
			{
				$("#add-progress-report-title").html("Edit Progress Report Shift 3");
				$.ajax({
					url: "/default/housekeeping/getprogressreportbyid",
					data: { id : id }
				}).done(function(response) {
					var obj = jQuery.parseJSON(response);
					$("#progress_report_id").val(obj.progress_report_id);
					$("#housekeeping_report_id").val(obj.housekeeping_report_id);
					$("#shift").val(obj.shift);
					$("#area").val(obj.area);
					$("#status").val(obj.status);
					if(obj.img_before != null)
					{
						$("#img-before-report").html('<img src="'+obj.img_before+'" width="50" />');
					}
					if(obj.img_progress != null)
					{
						$("#img-progress-report").html('<img src="'+obj.img_progress+'" width="50" />');
					}
					if(obj.img_after != null)
					{
						$("#img-after-report").html('<img src="'+obj.img_after+'" width="50" />');
					}
				});	
			}
			else
			{
				$("#add-progress-report-title").html("Add Progress Report Shift 3");
				$("#shift").val(this.dataset.shift);
			}
		}
	});
	
	$(".remove-progress-report").click(function() {
		var res = confirm("Are you sure you want to delete this progress report?");
		if(res == true)
		{
			location.href="/default/housekeeping/deleteprogressreport/id/"+this.dataset.id+"/hk_report_id/<?php echo $this->housekeeping['housekeeping_report_id']; ?>";
		}
	});
	
	
	$('.add-other-info').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#other-info-form')[0].reset();
				$("#img-progress-other").html('');
			}
		}
	});
	
	$(".add-other-info").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$("#add-other-info-title").html("Edit Pest Control dan Informasi Lainnya");
			$.ajax({
				url: "/default/housekeeping/getotherinfobyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#other_info_id").val(obj.other_info_id);
				$("#housekeeping_report_id").val(obj.housekeeping_report_id);
				$("#area_other").val(obj.area);
				$("#status_other").val(obj.status);
				if(obj.img_progress != null)
				{
					$("#img-progress-other").html('<img src="'+obj.img_progress+'" width="50" />');
				}
			});	
		}
		else
		{
			$("#add-other-info-title").html("Add Pest Control dan Informasi Lainnya");
		}
	});
	
	$(".remove-other-info").click(function() {
		var res = confirm("Are you sure you want to delete this info?");
		if(res == true)
		{
			location.href="/default/housekeeping/deleteotherinfo/id/"+this.dataset.id+"/hk_report_id/<?php echo $this->housekeeping['housekeeping_report_id']; ?>";
		}
	});
	
	$('.add-attachment').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#attachment-form')[0].reset();
				$("#attachment_file").html('');
			}
		}
	});
	
	$(".add-attachment").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$("#add-attachment-title").html("Edit Attachment");
			$.ajax({
				url: "/default/housekeeping/getattachmentbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#attachment_id").val(obj.attachment_id);
				$("#report_id").val(obj.report_id);
				$("#attachment_description").val(obj.description);
				if(obj.filename != null)
				{
					$("#attachment_file").html('<a href="/attachment/operational/'+obj.filename+'" target="_blank" >'+obj.filename+'</a>');
				}
			});	
		}
		else
		{
			$("#add-attachment-title").html("Add Attachment");
		}
	});
	
	$(".remove-attachment").click(function() {
		var res = confirm("Are you sure you want to delete this attachment?");
		if(res == true)
		{
			location.href="/default/housekeeping/deleteattachmentbyid/id/"+this.dataset.id+"/hk_report_id/<?php echo $this->housekeeping['housekeeping_report_id']; ?>";
		}
	});
	
	$('#progress-report-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('#other-info-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('#attachment-form').on('submit', function(event){
		$("body").mLoading();
	});
	
});	
</script>