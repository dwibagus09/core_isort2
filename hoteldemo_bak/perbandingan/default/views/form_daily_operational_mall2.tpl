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

			<form id="operational-page2-form" class="form-horizontal form-label-left" action="/default/operational/savereport" method="POST" onsubmit="$('body').mLoading();">
				<input type="hidden" id="operation_mall_report_id" name="operation_mall_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['operation_mall_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->operational['report_date'])) echo $this->operational['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">MARKETING &amp; PROMOTION <a class="add-marketing-promotion"href="#marketing-promotion-form"  style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></span>
				<div class="table-dv">			  
				 <table id="marketing-promotion-table" class="table">
					  <thead>
						<tr>
						  <th>Nama Event</th>
						  <th>Foto-foto</th>
						  <th>Lokasi</th>
						  <th>Kondisi Event</th>
						  <th>Periode Event</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->marketing_promotion)) {
							foreach($this->marketing_promotion as $marketing_promotion) { ?>
							<tr>
								<td><?php echo $marketing_promotion['event_name']; ?></td>									
								<td align="center"><?php if(!empty($marketing_promotion['event_img'])) { ?><img src="/images/event/<?php echo $marketing_promotion['event_img']; ?>" class="thumb-img" /><?php } ?></td>
								<td><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_location']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_condition']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_period']); ?></td>
								<td><a class="add-marketing-promotion" href="#marketing-promotion-form" data-id="<?php echo $marketing_promotion['event_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-marketing-promotion" data-id="<?php echo $marketing_promotion['event_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">SUMMARY WO/WR TENANT &amp; INTERNAL</span>	
				<div class="table-dv">
				 <table id="summary-wo-wr-tenant-internal" class="table">
					  <thead>
						<tr>
						  <th rowspan="2">Department</th>
						  <th rowspan="2">No. of Req. WO per today</th>
						  <th rowspan="2">Completed WO per today</th>
						  <th rowspan="2">No. of Outstanding WO per today</th>
						  <th colspan="2">Accumulate</th>
						</tr>
						<tr>
						  <th>Previous Outstanding</th>
						  <th>Total Outstanding</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
							<td>Engineering</td>
							<td><input type="text" name="engineering_no_of_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['engineering_no_of_req_wo']; ?>" required></td>
							<td><input type="text" name="engineering_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['engineering_completed_wo']; ?>" required></td>
							<td><input type="text" name="engineering_no_of_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['engineering_no_of_outstanding_wo']; ?>" required></td>
							<td><input type="text" name="engineering_previous_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['engineering_previous_outstanding']; ?>" required></td>
							<td><input type="text" name="engineering_next_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['engineering_next_outstanding']; ?>" required></td>
						</tr>
						<tr>
							<td>BS/Civil</td>
							<td><input type="text" name="bs_no_of_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bs_no_of_req_wo']; ?>" required></td>
							<td><input type="text" name="bs_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bs_completed_wo']; ?>" required></td>
							<td><input type="text" name="bs_no_of_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bs_no_of_outstanding_wo']; ?>" required></td>
							<td><input type="text" name="bs_previous_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bs_previous_outstanding']; ?>" required></td>
							<td><input type="text" name="bs_next_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bs_next_outstanding']; ?>" required></td>
						</tr>
						<tr>
							<td>Housekeeping</td>
							<td><input type="text" name="housekeeping_no_of_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['housekeeping_no_of_req_wo']; ?>" required></td>
							<td><input type="text" name="housekeeping_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['housekeeping_completed_wo']; ?>" required></td>
							<td><input type="text" name="housekeeping_no_of_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['housekeeping_no_of_outstanding_wo']; ?>" required></td>
							<td><input type="text" name="housekeeping_previous_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['housekeeping_previous_outstanding']; ?>" required></td>
							<td><input type="text" name="housekeeping_next_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['housekeeping_next_outstanding']; ?>" required></td>
						</tr>
						<tr>
							<td>Parking</td>
							<td><input type="text" name="parking_no_of_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['parking_no_of_req_wo']; ?>" required></td>
							<td><input type="text" name="parking_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['parking_completed_wo']; ?>" required></td>
							<td><input type="text" name="parking_no_of_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['parking_no_of_outstanding_wo']; ?>" required></td>
							<td><input type="text" name="parking_previous_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['parking_previous_outstanding']; ?>" required></td>
							<td><input type="text" name="parking_next_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['parking_next_outstanding']; ?>" required></td>
						</tr>
						<tr>
							<td>Others</td>
							<td><input type="text" name="other_no_of_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['other_no_of_req_wo']; ?>" required></td>
							<td><input type="text" name="other_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['other_completed_wo']; ?>" required></td>
							<td><input type="text" name="other_no_of_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['other_no_of_outstanding_wo']; ?>" required></td>
							<td><input type="text" name="other_previous_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['other_previous_outstanding']; ?>" required></td>
							<td><input type="text" name="other_next_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['other_next_outstanding']; ?>" required></td>
						</tr>
					</tbody>
				 </table>
				 </div>
			</div>
			<br/>
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px;">			
			  <span class="section">PERHITUNGAN HEAD COUNT &amp; CAR COUNT</span>	
				 <table id="head-car-count" class="table">
					<tr>
						<td width="150">A. Head Count</td>
						<td><input type="text" name="head_count" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['head_count']; ?>" required></td>
					</tr>
					<tr>
						<td>B. Total Car Count</td>
						<td><input type="text" name="total_car_count" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['total_car_count']; ?>" required></td>
					</tr>
					<tr>
						<td>1. Car Parking</td>
						<td><input type="text" name="car_parking2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['car_parking']; ?>" required disabled><input type="hidden" name="car_parking" value="<?php echo $this->operational['car_parking']; ?>" ></td>
					</tr>
					<tr>
						<td>2. Car Drop Off</td>
						<td><input type="text" name="car_drop_off2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['car_drop_off']; ?>" required disabled><input type="hidden" name="car_drop_off" value="<?php echo $this->operational['car_drop_off']; ?>" ></td>
					</tr>
					<tr>
						<td>3. Valet Parking</td>
						<td><input type="text" name="valet_parking2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['valet_parking']; ?>" required disabled><input type="hidden" name="valet_parking" value="<?php echo $this->operational['valet_parking']; ?>" ></td>
					</tr>
					<tr>
						<td>4. Box Vehicle</td>
						<td><input type="text" name="box_vehicle2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['box_vehicle']; ?>" required disabled><input type="hidden" name="box_vehicle" value="<?php echo $this->operational['box_vehicle']; ?>" ></td>
					</tr>
					<tr>
						<td>5. Taxi</td>
						<td><input type="text" name="taxi_bluebird2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['taxi_bluebird']; ?>" required disabled><input type="hidden" name="taxi_bluebird" value="<?php echo $this->operational['taxi_bluebird']; ?>" ></td>
					</tr>
					<tr>
						<td>C. Motorbike</td>
						<td><input type="text" name="motorbike2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['motorbike']; ?>" required disabled><input type="hidden" name="motorbike" value="<?php echo $this->operational['motorbike']; ?>" ></td>
					</tr>
					<tr>
						<td>D. Bus</td>
						<td><input type="text" name="bus" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['bus']; ?>" required></td>
					</tr>
				 </table>
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
				  <td><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/7/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></td>
				  <td align="center" style="vertical-align:middle;"><a class="add-attachment" href="#attachment-form" data-id="<?php echo $attachment['attachment_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-attachment" data-id="<?php echo $attachment['attachment_id']; ?>" style="font-size:20px; cursor:pointer;" ></i></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
		
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/operational/edit/id/<?php echo $this->operational['operation_mall_report_id']; ?>'">Kembali ke halaman sebelumnya</button> <button id="send" type="submit" class="btn btn-success" style="width:200px;">Simpan</button>
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

<!-- Marketing & Promotion form -->
  <form action="/default/operational/addevent" id="marketing-promotion-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-marketing-promotion-title" class="popup-form-title"></div>
	<input type="hidden" id="event_id" name="event_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['operation_mall_report_id']; ?>">
	<table id="add-marketing-promotion-table" class="popup-form-table">
		<tr>
			<td>Nama Event</td>
			<td><textarea id="event_name" name="event_name" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>File</td>
			<td align="center"><div id="event_img"></div><input type="file" name="event_img" accept="image/jpeg"></td>
		</tr>
		<tr>
			<td>Lokasi</td>
			<td align="center"><textarea id="event_location" name="event_location" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Kondisi Event</td>
			<td align="center"><textarea id="event_condition" name="event_condition" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td>Periode Event</td>
			<td align="center"><textarea id="event_period" name="event_period" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>	
	<div class="add-btn"><input type="submit" id="add-marketing-promotion-submit" name="add-marketing-promotion-submit" value="Save"></div>
  </form>
<!-- End of Marketing & Promotion form -->

<!-- Attachment form -->
  <form action="/default/operational/addattachment" id="attachment-form" class="mfp-hide white-popup-block"  method="POST" enctype="multipart/form-data">
	<div id="add-attachment-title" class="popup-form-title"></div>
	<input type="hidden" id="attachment_id" name="attachment_id" class="form-control col-md-7 col-xs-12">
	<input type="hidden" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->operational['operation_mall_report_id']; ?>">
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
	$(".edit-om").css("display", "block");
	$(".edit-om").addClass('current-page');
	$(".edit-om").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$.ajax({
		url: "/default/operational/savepdf",
		data: { id : '<?php echo $this->operational['operation_mall_report_id']; ?>' }
	}).done(function(response) {
	});	
	
	/*$(".add-marketing-promotion").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td><input type="hidden" name="event-id[]" class="form-control col-md-7 col-xs-12" required></td>	
				<td><textarea name="event_name[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>									
				<td align="center"><input type="file" name="event_img[]" accept="image/jpeg"></td>
				<td><textarea name="event_location[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
				<td align="center"><textarea name="event_condition[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
				<td align="center" style="vertical-align:middle;"><textarea name="event_period[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#marketing-promotion-table").append(row);
	});
	
	$("#add-attachment").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td><input type="hidden" name="attachment_id[]" class="form-control col-md-7 col-xs-12" required></td>									
				<td align="center"><input type="file" name="attachment_file[]" accept="image/jpeg"></td>
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
				url: "/default/operational/getattachmentbyid",
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
			location.href="/default/operational/deleteattachmentbyid/id/"+this.dataset.id+"/om_report_id/<?php echo $this->operational['operation_mall_report_id']; ?>";
		}
	});
	
	$('#attachment-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('.add-marketing-promotion').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#marketing-promotion-form')[0].reset();
				$("#event_img").html('');
			}
		}
	});
	
	$(".add-marketing-promotion").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$("#add-marketing-promotion-title").html("Edit Marketing & Promotion");
			$.ajax({
				url: "/default/operational/geteventbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#event_id").val(obj.event_id);
				$("#event_name").val(obj.event_name);
				$("#event_location").val(obj.event_location);
				$("#event_condition").val(obj.event_condition);
				$("#event_period").val(obj.event_period);
				if(obj.event_img != null)
				{
					$("#event_img").html('<a href="/images/event/'+obj.event_img+'" target="_blank" ><img src="/images/event/'+obj.event_img+'" class="thumb-img"/></a>');
				}
			});	
		}
		else
		{
			$("#add-marketing-promotion-title").html("Add Marketing & Promotion");
		}
	});
	
	$(".remove-marketing-promotion").click(function() {
		var res = confirm("Are you sure you want to delete this marketing & promotion?");
		if(res == true)
		{
			location.href="/default/operational/deleteeventbyid/id/"+this.dataset.id+"/om_report_id/<?php echo $this->operational['operation_mall_report_id']; ?>";
		}
	});
	
	$('#marketing-promotion-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	$('#operational-page2-form').on('submit', function(event){
		$.ajax({
			url: "/default/operational/savepdf",
			data: { id : '<?php echo $this->operational['operation_mall_report_id']; ?>' }
		}).done(function(response) {
		});	
		$("body").mLoading();
	});
});	
</script>