<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form class="form-label-left" action="/default/safetycomittee/save" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="safety_comittee_id" name="safety_comittee_id" type="hidden" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>">
			<div class="x_title">
				<h2 class="pagetitle"><?php echo $this->title; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Site Name</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<?php echo $this->ident['site_fullname']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="title">Title</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="title" class="form-control col-md-7 col-xs-12" name="title" type="text" value="<?php echo $this->safetyComittee['meeting_title']; ?>" required>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_date">Date</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="meeting_date" class="form-control col-md-7 col-xs-12 datepicker" name="meeting_date" type="text" value="<?php echo $this->safetyComittee['tanggal']; ?>" required autocomplete="off">
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="meeting_time">Time</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<input id="meeting_time" class="form-control col-md-7 col-xs-12" name="meeting_time" type="text" value="<?php echo $this->safetyComittee['meeting_time']; ?>" required>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="attendance-table">Attendance <a id="add-attendance"><i class="fa fa-plus-square"></i></a></label>
					<div class="col-md-12 col-sm-12 col-xs-12">
						<table id="attendance-table" class="table table-striped">
							<thead>
								<tr>
									<th>Department</th>
									<th>Name</th>	
									<th width="50"></th>		
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($this->attendance)) {
								foreach($this->attendance as $attendance)
								{ ?>
									<tr>
										<td><select id="department-select" name="attendance_department_id[]"  class="form-control col-md-7 col-xs-12" required>
												<option value="" disabled selected hidden>Select Department</option>
												<?php foreach($this->categories as $category) { ?>
												<option value="<?php echo $category['category_id']; ?>" <?php if($attendance['category_id'] == $category['category_id']) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
												<?php } ?>
											</select>
										</td>
										<td><input type="hidden" name="attendance_id[]" value="<?php echo $attendance['attendance_id']; ?>" class="form-control col-md-7 col-xs-12"><input type="text" name="attendance_name[]" value="<?php echo $attendance['attendance_name']; ?>" class="form-control col-md-7 col-xs-12" autocomplete="off"></td>
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
				  <button id="send" type="submit" class="form-btn" style="width:250px;">Save &amp; go to next page</button>
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
				<td><select id="department-select" name="attendance_department_id[]"  class="form-control col-md-7 col-xs-12" required>
						<option value="" disabled selected hidden>Select Department</option>
						<?php foreach($this->categories as $category) { ?>
						  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
						 <?php } ?>
					</select>
				</td>
				<td><input type="hidden" name="attendance_id[]" value="" class="form-control col-md-7 col-xs-12"><input type="text" name="attendance_name[]" class="form-control col-md-7 col-xs-12" autocomplete="off"></td>
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
					url: "/default/safetycomittee/deleteattendancebyid",
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