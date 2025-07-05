<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div class="right_col" role="main">
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

			<form class="form-horizontal form-label-left" action="/default/mod/savereport" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="mod_report_id" name="mod_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['mod_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->mod['report_date'])) echo $this->mod['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">EVENT <a class="add-event" href="#event-form"  style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th width="200">Nama Event</th>
						  <th width="150">Kondisi Event (Foto)</th>
						  <th width="200">Lantai</th>
						  <th>Status Event</th>
						  <th width="50"></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->events)) {
							foreach($this->events as $event) { ?>
							<tr>
								<td><?php echo $event['event_name']; ?></td>									
								<td align="center"><?php if(!empty($event['event_img'])) { ?><a class="image-popup-vertical-fit" href="/images/event/<?php echo $event['event_img']; ?>"><img src="/images/event/<?php echo $event['event_img']; ?>" class="thumb-img" /></a><?php } ?></td>
								<td><?php echo str_replace("<br>","&#13;",$event['event_location']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$event['event_status']); ?></td>
								<td><a class="add-event" href="#event-form" data-id="<?php echo $event['event_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-event" data-id="<?php echo $event['event_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				 </div>
			</div>
			
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
				  <td><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/8/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></td>
				  <td align="center" style="vertical-align:middle;"><a class="add-attachment" href="#attachment-form" data-id="<?php echo $attachment['attachment_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-attachment" data-id="<?php echo $attachment['attachment_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/mod/edit/id/<?php echo $this->mod['mod_report_id']; ?>'">Kembali ke halaman sebelumnya</button>
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

<!-- Event form -->
  <form action="/default/mod/addevent" id="event-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-event-title" class="popup-form-title"></div>
	<input type="hidden" id="event_id" name="event_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['mod_report_id']; ?>">
	<table id="add-event-table" class="popup-form-table">
		<tr>
			<td>Nama Event</td>
			<td><textarea id="event_name" name="event_name" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>File</td>
			<td align="center"><div id="event_image"></div><input type="file" name="event_image" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Lokasi</td>
			<td align="center"><textarea id="event_location" name="event_location" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Status</td>
			<td align="center"><textarea id="event_status" name="event_status" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>	
	<div class="add-btn"><input type="submit" id="add-event-submit" name="add-event-submit" value="Save"></div>
  </form>
<!-- End of Event form -->

<!-- Attachment form -->
  <form action="/default/mod/addattachment" id="attachment-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-attachment-title" class="popup-form-title"></div>
	<input type="hidden" id="attachment_id" name="attachment_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['mod_report_id']; ?>">
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
 <script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {		
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	/*$(".add-event").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><input type="hidden" name="event_id[]" class="form-control col-md-7 col-xs-12" required></td>	
			<td><textarea name="event_name[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>									
			<td align="center"><input type="file" name="event_image[]"></td>
			<td><textarea name="event_location[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><textarea name="event_status[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
			<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#event-table").append(row);
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
	});*/
	
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
				url: "/default/mod/getattachmentbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#attachment_id").val(obj.attachment_id);
				$("#report_id").val(obj.report_id);
				$("#attachment_description").val(obj.description);
				if(obj.filename != null)
				{
					$("#attachment_file").html('<a href="/attachment/mod/'+obj.filename+'" target="_blank" >'+obj.filename+'</a>');
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
			location.href="/default/mod/deleteattachmentbyid/id/"+this.dataset.id+"/om_report_id/<?php echo $this->mod['mod_report_id']; ?>";
		}
	});
	
	$('#attachment-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('.add-event').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#event-form')[0].reset();
				$("#event_img").html('');
			}
		}
	});
	
	$(".add-event").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$("#add-event-title").html("Edit Event");
			$.ajax({
				url: "/default/mod/geteventbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#event_id").val(obj.event_id);
				$("#event_name").val(obj.event_name);
				$("#event_location").val(obj.event_location);
				$("#event_status").val(obj.event_status);
				if(obj.event_img != null)
				{
					$("#event_image").html('<a href="/images/event/'+obj.event_img+'" target="_blank" ><img src="/images/event/'+obj.event_img+'" class="thumb-img"/></a>');
				}
			});	
		}
		else
		{
			$("#add-event-title").html("Add Event");
		}
	});
	
	$(".remove-event").click(function() {
		var res = confirm("Are you sure you want to delete this event?");
		if(res == true)
		{
			location.href="/default/mod/deleteeventbyid/id/"+this.dataset.id+"/mod_report_id/<?php echo $this->mod['mod_report_id']; ?>";
		}
	});
	
	$('#event-form').on('submit', function(event){
		$("body").mLoading();
	});
	
});	
</script>