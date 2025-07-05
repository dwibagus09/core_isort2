<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form class="form-label-left" action="/default/it/save" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="it_meeting_id" name="it_meeting_id" type="hidden" value="<?php echo $this->itMeeting['it_meeting_id']; ?>">
			<div class="x_title">
				<h2><?php echo $this->title; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Site</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<?php echo $this->ident['site_fullname']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Judul</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="title" class="form-control col-md-7 col-xs-12" name="title" type="text" value="<?php echo $this->itMeeting['meeting_title']; ?>" required>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_date">Tanggal</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="meeting_date" class="form-control col-md-7 col-xs-12 datepicker" name="meeting_date" type="text" value="<?php echo $this->itMeeting['tanggal']; ?>" required autocomplete="off">
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_time">Jam</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="meeting_time" class="form-control col-md-7 col-xs-12" name="meeting_time" type="text" value="<?php echo $this->itMeeting['meeting_time']; ?>" required>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="attendance-table">Peserta Hadir <a id="add-attendance"><i class="fa fa-plus-square"></i></a></label>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<table id="attendance-table" class="table table-striped">
							<thead>
								<tr>
									<th>Name</th>	
									<th width="50"></th>		
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($this->attendance)) {
								foreach($this->attendance as $attendance)
								{ ?>
									<tr>
										<td><select id="user-select" name="attendance_user_id[]"  class="form-control col-md-7 col-xs-12" required>
												<option value="" disabled selected hidden>Select Attendance</option>
												<?php foreach($this->users as $user) { ?>
												<option value="<?php echo $user['user_id']; ?>" <?php if($attendance['user_id'] ==  $user['user_id']) echo "selected"; ?>><?php echo $user['name']; ?></option>
												<?php } ?>
											</select>
											<input type="hidden" name="attendance_id[]" value="<?php echo $attendance['attendance_id']; ?>" class="form-control col-md-7 col-xs-12">
										</td>
										<td><i class="fa fa-trash remove-attendance" data-id="<?php echo $attendance['attendance_id']; ?>" style="font-size:150%; cursor:pointer;"></i></td>
									</tr>
							<?php }
							} ?>
							</tbody>
						</table>
					</div>
				</div>
				<br/>
			
			<div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:250px;">Simpan & ke halaman berikutnya</button>
				</div>
			  </div>
		  </div>
		  </form>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$("#add-attendance").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td><select id="user-select" name="attendance_user_id[]"  class="form-control col-md-7 col-xs-12" required>
						<option value="" disabled selected hidden>Select Attendance</option>
						<?php foreach($this->users as $user) { ?>
						<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				<td><i class="fa fa-trash remove-attendance" style="font-size:150%; cursor:pointer;" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#attendance-table").append(row);
	});

	$(".remove-attendance").click(function() {
		var thisRow = $(this);
		if(this.dataset.id > 0)
		{
			var res = confirm("Are you sure you want to delete this attendance?");
			if(res == true)
			{
				$("body").mLoading();			
				$.ajax({
					url: "/default/it/deleteattendancebyid",
					data: { id : this.dataset.id }
				}).done(function(response) {					
					thisRow.closest('tr').remove();
					$("body").mLoading("hide");	
				});
			}
		}
	});
});	
</script>