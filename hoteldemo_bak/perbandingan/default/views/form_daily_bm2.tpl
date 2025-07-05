<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

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

			<form class="form-horizontal form-label-left" action="/default/bm/savereport" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="report_id" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['report_id']; ?>">
			  <span class="section">DATE / BUILDING</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->bm['report_date'])) echo $this->bm['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="building">Building
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select id="building" name="building" class="form-control" required <?php if(!empty($this->bm['building'])) { ?>disabled<?php } ?>>
						<?php if($this->building != '1') { ?><option value="1" <?php if($this->bm['building'] == '1') echo "selected"; ?>>Office Tower</option><?php } ?>
						<?php if($this->building != '2') { ?><option value="2" <?php if($this->bm['building'] == '2') echo "selected"; ?>>Kondominium</option><?php } ?>
					  </select>
					  <?php if(!empty($this->bm['building'])) { ?><input type="hidden" id="building" name="building" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['building']; ?>"><?php } ?>
				</div>
			  </div>
			  
			  <span class="section">ISSUE</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. Utility <a class="add-issue" href="#issue-form" data-issuetype="Utility" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="utility-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->utility)) { 
							foreach($this->utility as $utility) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="utility_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $utility['issue_id']; ?>">
									<?php if(!empty($utility['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$utility['picture'].'"><img src="/images/bm/'.str_replace("Utility.","Utility_thumb.",$utility['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$utility['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$utility['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$utility['status']); ?></td>
								<td><?php echo $utility['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $utility['issue_id']; ?>" data-issuetype="Utility" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $utility['issue_id']; ?>" data-issuetype="Utility" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>B. Safety <a class="add-issue" href="#issue-form" data-issuetype="Safety" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="safety-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->safety)) { 
							foreach($this->safety as $safety) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="safety_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $safety['issue_id']; ?>">
									<?php if(!empty($safety['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$safety['picture'].'"><img src="/images/bm/'.str_replace("Safety.","Safety_thumb.",$safety['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$safety['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$safety['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$safety['status']); ?></td>
								<td><?php echo $safety['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $safety['issue_id']; ?>" data-issuetype="Safety" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $safety['issue_id']; ?>" data-issuetype="Safety" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>C. Security <a class="add-issue" href="#issue-form" data-issuetype="Security" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="security-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->security)) { 
							foreach($this->security as $security) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="security_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $security['issue_id']; ?>">
									<?php if(!empty($security['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$security['picture'].'"><img src="/images/bm/'.str_replace("Security.","Security_thumb.",$security['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$security['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$security['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$security['status']); ?></td>
								<td><?php echo $security['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $security['issue_id']; ?>" data-issuetype="Security" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $security['issue_id']; ?>" data-issuetype="Security" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>D. Housekeeping <a class="add-issue" href="#issue-form" data-issuetype="Housekeeping" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="housekeeping-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->housekeeping)) { 
							foreach($this->housekeeping as $housekeeping) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="housekeeping_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $housekeeping['issue_id']; ?>">
									<?php if(!empty($housekeeping['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$housekeeping['picture'].'"><img src="/images/bm/'.str_replace("Housekeeping.","Housekeeping_thumb.",$housekeeping['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$housekeeping['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$housekeeping['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$housekeeping['status']); ?></td>
								<td><?php echo $housekeeping['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $housekeeping['issue_id']; ?>" data-issuetype="Housekeeping" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $housekeeping['issue_id']; ?>" data-issuetype="Housekeeping" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>E. Parking &amp; Traffic <a class="add-issue" href="#issue-form" data-issuetype="Parking_Traffic" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="parking-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->parking)) { 
							foreach($this->parking as $parking) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="parking_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $parking['issue_id']; ?>">
									<?php if(!empty($parking['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$parking['picture'].'"><img src="/images/bm/'.str_replace("Parking_Traffic.","Parking_Traffic_thumb.",$parking['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$parking['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$parking['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$parking['status']); ?></td>
								<td><?php echo $parking['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $parking['issue_id']; ?>" data-issuetype="Parking_Traffic" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $parking['issue_id']; ?>" data-issuetype="Parking_Traffic" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>F. Resident Relations <a class="add-issue" href="#issue-form" data-issuetype="Resident_Relations" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="resident-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->resident)) { 
							foreach($this->resident as $resident) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="resident_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $resident['issue_id']; ?>">
									<?php if(!empty($resident['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$resident['picture'].'"><img src="/images/bm/'.str_replace("Resident_Relations.","Resident_Relations_thumb.",$resident['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$resident['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$resident['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$resident['status']); ?></td>
								<td><?php echo $resident['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $resident['issue_id']; ?>" data-issuetype="Resident_Relations" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $resident['issue_id']; ?>" data-issuetype="Resident_Relations" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>G. Building Service <a class="add-issue" href="#issue-form" data-issuetype="Building_Service" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></legend>
					<table id="building-service-table" class="table">
						  <thead>
							<tr>
							  <th width="150">Foto</th>
							  <th width="150">Lokasi</th>
							  <th>Deskripsi</th>
							  <th width="150">Status</th>
							  <th width="100">Completion Date</th>
							  <th width="50"></th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->building_service)) { 
							foreach($this->building_service as $building_service) { 
							?>
							<tr>
								<td>
									<input type="hidden" name="building_service_issue_id" class="form-control col-md-7 col-xs-12" value="<?php echo $building_service['issue_id']; ?>">
									<?php if(!empty($building_service['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/bm/'.$building_service['picture'].'"><img src="/images/bm/'.str_replace("Building_Service.","Building_Service_thumb.",$building_service['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$building_service['location']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$building_service['description']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$building_service['status']); ?></td>
								<td><?php echo $building_service['completion_date']; ?></td>
								<td><a class="add-issue" href="#issue-form" data-id="<?php echo $building_service['issue_id']; ?>" data-issuetype="Building_Service" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-issue" data-id="<?php echo $building_service['issue_id']; ?>" data-issuetype="Building_Service" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
				</fieldset>
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
				  <td><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/9/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></td>
				  <td align="center" style="vertical-align:middle;"><a class="add-attachment" href="#attachment-form" data-id="<?php echo $attachment['attachment_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-attachment" data-id="<?php echo $attachment['attachment_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
		
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/bm/edit/id/<?php echo $this->bm['report_id']; ?>'">Kembali ke halaman sebelumnya</button>
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

<!-- Issue form -->
  <form action="/default/bm/addissue" id="issue-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-issue-title" class="popup-form-title"></div>
	<input type="hidden" id="issue_id" name="issue_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" id="issue_type" name="issue_type" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['report_id']; ?>">
	<table id="add-issue-table" class="popup-form-table">
		<tr>
			<td>Foto</td>
			<td align="center"><div id="issue_pic"></div><input type="file" name="pic" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Lokasi</td>
			<td><textarea id="location" name="location" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Deskripsi</td>
			<td align="center"><textarea id="description" name="description" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Status</td>
			<td align="center"><textarea id="status" name="status" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Completion Date</td>
			<td align="center"><input type="text" id="completion_date" name="completion_date" class="form-control col-md-7 col-xs-12 datepicker"></td>
		</tr>
	</table>	
	<div class="add-btn"><input type="submit" id="add-issue-submit" name="add-issue-submit" value="Save"></div>
  </form>
  
  
<!-- End of Issue form -->

<!-- Attachment form -->
  <form action="/default/bm/addattachment" id="attachment-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-attachment-title" class="popup-form-title"></div>
	<input type="hidden" id="attachment_id" name="attachment_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['report_id']; ?>">
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
	$(".edit-bm").css("display", "block");
	$(".edit-bm").addClass('current-page');
	$(".edit-bm").addClass('current-page').parents('ul').slideDown().parent().addClass('active');

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
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
				url: "/default/bm/getattachmentbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#attachment_id").val(obj.attachment_id);
				$("#report_id").val(obj.report_id);
				$("#attachment_description").val(obj.description);
				if(obj.filename != null)
				{
					$("#attachment_file").html('<a href="/attachment/bm/'+obj.filename+'" target="_blank" >'+obj.filename+'</a>');
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
			location.href="/default/bm/deleteattachmentbyid/id/"+this.dataset.id+"/bm_report_id/<?php echo $this->bm['report_id']; ?>";
		}
	});
	
	$('#attachment-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('.add-issue').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#issue-form')[0].reset();
				$("#event_img").html('');
			}
		}
	});
	
	$(".add-issue").click(function() {
		var id = this.dataset.id;
		var issuetype = this.dataset.issuetype;
		var issue_title="";
		if(issuetype == "Parking_Traffic") issue_title = "Parking & Traffic";
		else if(issuetype == "Resident_Relations") issue_title = "Resident Relations";
		else if(issuetype == "Building_Service") issue_title = "Building Service";
		else issue_title = issuetype;
		if(id > 0)
		{
			$("#add-issue-title").html("Edit "+issue_title+" Issue");
			$.ajax({
				url: "/default/bm/getissuebyid",
				data: { id : id, it:issuetype }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#issue_id").val(obj.issue_id);
				$("#issue_type").val(issuetype);
				$("#location").val(obj.location);
				$("#description").val(obj.description);
				$("#status").val(obj.status);
				$("#completion_date").val(obj.completion_date);
				if(obj.picture != null)
				{
					$("#issue_pic").html('<a href="/images/bm/'+obj.picture+'" target="_blank" ><img src="/images/bm/'+obj.picture+'" class="thumb-img"/></a>');
				}
			});	
		}
		else
		{			
			$("#add-issue-title").html("Add "+ issue_title +" Issue");			
			$("#issue_type").val(issuetype);
		}
	});
	
	$(".remove-issue").click(function() {
		var res = confirm("Are you sure you want to delete this issue?");
		if(res == true)
		{
			location.href="/default/bm/deleteissuebyid/id/"+this.dataset.id+"/it/"+this.dataset.issuetype+"/bm_report_id/<?php echo $this->bm['report_id']; ?>";
		}
	});
	
	$('#issue-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('#bm-page2-form').on('submit', function(event){
		$("body").mLoading();
	});
});	
</script>