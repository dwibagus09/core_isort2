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

			<form class="form-horizontal form-label-left" action="/default/mod/savereport2" method="POST" enctype="multipart/form-data" onsubmit="$('body').mLoading();">
				<input type="hidden" id="mod_report_id" name="mod_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['mod_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->mod['report_date'])) echo $this->mod['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px;">			
			  <span class="section">Jumlah Kendaraan Masuk</span>	
				 <table id="head-car-count" class="table">
					<tr>
						<th width="50%">Jenis Kendaraan</th>
						<th width="50%">Jumlah</th>
					</tr>
					<tr>
						<td>Car Count Parking</td>
						<td><input type="text" name="car_parking2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['car_parking']; ?>" required disabled><input type="hidden" name="car_parking" value="<?php echo $this->mod['car_parking']; ?>" ></td>
					</tr>
					<tr>
						<td>Car Count Drop Off</td>
						<td><input type="text" name="car_drop_off2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['car_drop_off']; ?>" required disabled><input type="hidden" name="car_drop_off" value="<?php echo $this->mod['car_drop_off']; ?>" ></td>
					</tr>
					<tr>
						<td>Box Vehicle</td>
						<td><input type="text" name="box_vehicle2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['box_vehicle']; ?>" required disabled><input type="hidden" name="box_vehicle" value="<?php echo $this->mod['box_vehicle']; ?>" ></td>
					</tr>
					<tr>
						<td>Motorbike</td>
						<td><input type="text" name="motorbike2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['motorbike']; ?>" required disabled><input type="hidden" name="motorbike" value="<?php echo $this->mod['motorbike']; ?>" ></td>
					</tr>
					<tr>
						<td>Bus</td>
						<td><input type="text" name="bus" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['bus']; ?>" required></td>
					</tr>
					<tr>
						<td>Valet Service</td>
						<td><input type="text" name="valet_parking2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['valet_parking']; ?>" required disabled><input type="hidden" name="valet_parking" value="<?php echo $this->mod['valet_parking']; ?>" ></td>
					</tr>
					
					<tr>
						<td>Taxi Bluebird</td>
						<td><input type="text" name="taxi_bluebird2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['taxi_bluebird']; ?>" required disabled><input type="hidden" name="taxi_bluebird" value="<?php echo $this->mod['taxi_bluebird']; ?>" ></td>
					</tr>
					<tr>
						<td>Taxi Non Blue bird</td>
						<td><input type="text" name="taxi_non_bluebird" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['taxi_non_bluebird']; ?>" required></td>
					</tr>					
				 </table>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">FASILITAS/PERALATAN</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th width="200">Nama Fasilitas/Peralatan</th>
						  <th width="150">Kondisi (Foto bila ada)</th>
						  <th width="150">Lantai/Area</th>
						  <th>Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->equipments)) {
							foreach($this->equipments as $equipment) { ?>
							<tr>
								<td><input type="hidden" name="equipment_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $equipment['equipment_id']; ?>" required><input type="hidden" name="equipment_list_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $equipment['mod_equipment_list_id']; ?>" required><?php echo $equipment['equipment_name']; ?></td>									
								<td align="center"><?php if(!empty($equipment['image'])) { ?><a class="image-popup-vertical-fit" href="/images/equipment/<?php echo $equipment['image']; ?>"><img src="/images/equipment/<?php echo $equipment['image']; ?>" class="thumb-img" /></a><?php } ?><input type="file" name="equipment_img[]" accept="image/jpeg"></td>
								<td><textarea name="equipment_area[]" class="form-control col-md-7 col-xs-12 equipment-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$equipment['area']); ?></textarea></td>
								<td align="center"><textarea name="equipment_keterangan[]" class="form-control col-md-7 col-xs-12 equipment-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$equipment['keterangan']); ?></textarea></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				 </div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">INCIDENT</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th class="id-hidden"></th>
						  <th>Nama Insiden</th>
						  <th width="150">Kondisi (Foto)</th>
						  <th width="150">Lantai/Area</th>
						  <th width="150">Status</th>
						  <th width="200">Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->incident)) {
							foreach($this->incident as $incident) { 
								if(empty($incident['status'])) $incident['status'] = $incident['comment'];

								if($incident['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($incident['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
						?>
							<tr>
								<td><input type="hidden" name="issue_id_incident[]" class="form-control col-md-7 col-xs-12" value="<?php echo $incident['issue_id']; ?>" required></td>	
								<td><textarea name="description_incident[]" class="form-control col-md-7 col-xs-12 incident-txtarea" style="height:50px;" disabled><?php echo $incident['description']; ?></textarea></td>									
								<td align="center">
									<?php if(!empty($incident['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$incident['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$incident['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($incident['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$incident['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$incident['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><textarea name="location_incident[]" class="form-control col-md-7 col-xs-12 incident-txtarea" style="height:50px;" disabled><?php echo str_replace("<br>","&#13;",$incident['location']); ?></textarea></td>
								<td align="center"><textarea name="status_incident[]" class="form-control col-md-7 col-xs-12 incident-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$incident['status']); ?></textarea></td>
								<td><textarea name="keterangan_incident[]" class="form-control col-md-7 col-xs-12 incident-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$incident['keterangan']); ?></textarea></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">LOST & FOUND</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th class="id-hidden"></th>
						  <th>Kejadian</th>
						  <th width="150">Informasi Pelapor (Foto)</th>
						  <th width="150">Lantai/Area</th>
						  <th width="150">Status</th>
						  <th width="200">Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->lostFound)) {
							foreach($this->lostFound as $lostFound) { 
								if(empty($lostFound['status'])) $lostFound['status'] = $lostFound['comment'];	

								if($lostFound['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($lostFound['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td><input type="hidden" name="issue_id_lost_found[]" class="form-control col-md-7 col-xs-12" value="<?php echo $lostFound['issue_id']; ?>" required></td>	
								<td><textarea name="description[]" class="form-control col-md-7 col-xs-12 lost-found-txtarea" style="height:50px;" disabled><?php echo $lostFound['description']; ?></textarea></td>									
								<td align="center">
									<?php if(!empty($lostFound['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$lostFound['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$lostFound['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($lostFound['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$lostFound['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$lostFound['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><textarea name="location_lost_found[]" class="form-control col-md-7 col-xs-12 lost-found-txtarea" style="height:50px;" disabled><?php echo str_replace("<br>","&#13;",$lostFound['location']); ?></textarea></td>
								<td align="center"><textarea name="status_lost_found[]" class="form-control col-md-7 col-xs-12 lost-found-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$lostFound['status']); ?></textarea></td>
								<td><textarea name="keterangan_lost_found[]" class="form-control col-md-7 col-xs-12 lost-found-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$lostFound['keterangan']); ?></textarea></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">GLITCH</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th class="id-hidden"></th>
						  <th>Pelanggaran</th>
						  <th>Foto Pelanggaran</th>
						  <th>Lantai/Area</th>
						  <th>Tindakan Perbaikan</th>
						  <th>Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->glitch)) {
							foreach($this->glitch as $glitch) { 
								if(empty($glitch['status'])) $glitch['status'] = $glitch['comment'];	

								if($glitch['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($glitch['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td><input type="hidden" name="issue_id_glitch[]" class="form-control col-md-7 col-xs-12" value="<?php echo $glitch['issue_id']; ?>" required></td>	
								<td><textarea name="area_glitch[]" class="form-control col-md-7 col-xs-12 glitch-txtarea" style="height:50px;" disabled><?php echo $glitch['description']; ?></textarea></td>									
								<td align="center">
									<?php if(!empty($glitch['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$glitch['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$glitch['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($glitch['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$glitch['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$glitch['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><textarea name="location_glitch[]" class="form-control col-md-7 col-xs-12 glitchtxtarea" style="height:50px;" disabled><?php echo str_replace("<br>","&#13;",$glitch['location']); ?></textarea></td>
								<td align="center"><textarea name="status_glitch[]" class="form-control col-md-7 col-xs-12 glitch-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$glitch['status']); ?></textarea></td>
								<td><textarea name="keterangan_glitch[]" class="form-control col-md-7 col-xs-12 glitch-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$glitch['keterangan']); ?></textarea></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">WAY SYSTEM</span>	
				<?php if(!empty($this->mod['way_system_img'])) { ?><a class="image-popup-vertical-fit" href="/images/way_system/<?php echo $this->mod['way_system_img']; ?>"><img src="/images/way_system/<?php echo $this->mod['way_system_img']; ?>" class="thumb-img" /></a><?php } ?><input type="file" name="way_system_img" accept="image/jpeg">
			</div>
			
			  <div class="col-md-12 col-xs-12" style="padding-top:20px;">
				  <fieldset>
					<legend>SG</legend>
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th>Info</th>
							  <th>Jumlah</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Absent</td>
								<td><input type="text" name="sg_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['sg_absent']; ?>" required></td>
							</tr>
							<tr>
								<td>Subtitute</td>
								<td><input type="text" name="sg_subtitute" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['sg_subtitute']; ?>" required></td>
							</tr>
							<tr>
								<td>Subtitute (No Beacon)</td>
								<td><input type="text" name="sg_subtitute_no_beacon" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['sg_subtitute_no_beacon']; ?>" required></td>
							</tr>
							<tr>
								<td>Negligence</td>
								<td><input type="text" name="sg_negligence" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['sg_negligence']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>HK</legend>
					<table id="cleaning-table" class="table">
						  <thead>
							<tr>
							  <th>Info</th>
							  <th>Jumlah</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Absent</td>
								<td><input type="text" name="hk_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['hk_absent']; ?>" required></td>
							</tr>
							<tr>
								<td>Subtitute</td>
								<td><input type="text" name="hk_subtitute" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['hk_subtitute']; ?>" required></td>
							</tr>
							<tr>
								<td>Subtitute (No Beacon)</td>
								<td><input type="text" name="hk_subtitute_no_beacon" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['hk_subtitute_no_beacon']; ?>" required></td>
							</tr>
							<tr>
								<td>Negligence</td>
								<td><input type="text" name="hk_negligence" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['hk_negligence']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>	
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/mod/edit/id/<?php echo $this->mod['mod_report_id']; ?>'">Kembali ke halaman sebelumnya</button> <button id="send" type="submit" class="btn btn-success" style="width:200px;">Halaman Berikutnya</button>
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
	$(".edit-mod").css("display", "block");
	$(".edit-mod").addClass('current-page');
	$(".edit-mod").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$.ajax({
		url: "/default/mod/savepdf",
		data: { id : '<?php echo $this->mod['mod_report_id']; ?>' }
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