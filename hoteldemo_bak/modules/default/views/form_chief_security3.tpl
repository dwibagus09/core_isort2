<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<?php 
if(empty($this->security['report_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->security['report_date']);
	$cur_date = $cur_report_date[0];
}
?>

  <div class="">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2><?php echo $this->title; ?></h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">

			<form class="form-horizontal form-label-left" action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="chief_security_report_id" name="chief_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_security_report_id']; ?>">
				<input type="hidden" id="morning_security_report_id" name="morning_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['morning']['security_id']; ?>">
				<input type="hidden" id="afternoon_security_report_id" name="afternoon_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['afternoon']['security_id']; ?>">
				<input type="hidden" id="night_security_report_id" name="night_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['night']['security_id']; ?>">
				<input type="hidden" name="report_date" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['report_date']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->security['created_date'])) echo $this->security['created_date']; ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['chief_security_reporting_time']; ?>
				</div>
			  </div>
			
			<span class="section">Attachment &nbsp;<a class="add-attachment" href="#attachment-form"><i id="add-attachment" class="fa fa-plus-square"></i></a></span>
			<table id="attachment-table" class="table">
			  <thead>
				<tr>
				  <th width="200">Filename</th>
				  <th>Description</th>
				  <th width="30"></th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->attachmentSpv)) {
						foreach($this->attachmentSpv as $attachmentSpv) {
				?>
				<tr id="attachmentSpv<?php echo $attachmentSpv['attachment_id']; ?>">
				  <td><?php echo '<a href="/attachment/security/'.substr($attachmentSpv['upload_date'], 0, 4)."/".$attachmentSpv['filename'].'" target="_blank" class="attachment-file">'.$attachmentSpv['filename'].'</a>'; ?></td>
				  <td><?php echo $attachmentSpv['description']; ?></td>
				  <td align="center" style="vertical-align:middle;"></td>
				</tr>
				<?php } 
				} ?>
				<?php if(!empty($this->attachment)) {
						foreach($this->attachment as $attachment) {
				?>
				<tr id="attachment<?php echo $attachment['attachment_id']; ?>">
				  <td><?php echo '<a href="/attachment/security/'.substr($attachment['upload_date'], 0, 4)."/".$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['filename'].'</a>'; ?></td>
				  <td><?php echo $attachment['description']; ?></td>
				  <td align="center" style="vertical-align:middle;"><a class="add-attachment" href="#attachment-form" data-id="<?php echo $attachment['attachment_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-attachment" data-id="<?php echo $attachment['attachment_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
			</table>
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
					<button id="previous" type="button" class="form-btn" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/security/chiefpage2/id/<?php echo $this->security['chief_security_report_id']; ?>'">Back To Previous Page</button> 
					<?php /*<button id="next" type="button" class="form-btn" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/manpower/view/c/1'">Next Page</button> */ ?>
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

<!-- Attachment form -->
  <form action="/default/security/addchiefattachment" id="attachment-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-attachment-title" class="popup-form-title"></div>
	<input type="hidden" id="attachment_id" name="attachment_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_security_report_id']; ?>">
	<table id="add-attachment-table" class="popup-form-table">
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
	$(".edit-chief-sec").css("display", "block");
	$(".edit-chief-sec").addClass('current-page');
	$(".edit-chief-sec").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	
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
				url: "/default/security/getchiefattachmentbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#attachment_id").val(obj.attachment_id);
				$("#report_id").val(obj.report_id);
				$("#attachment_description").val(obj.description);
				if(obj.filename != null)
				{
					$("#attachment_file").html('<a href="/attachment/security/'+obj.filename+'" target="_blank" >'+obj.filename+'</a>');
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
			location.href="/default/security/deletechiefattachmentbyid/id/"+this.dataset.id+"/report_id/<?php echo $this->security['chief_security_report_id']; ?>";
		}
	});
	
	$('#attachment-form').on('submit', function(event){
		$("body").mLoading();
	});
});	
</script>