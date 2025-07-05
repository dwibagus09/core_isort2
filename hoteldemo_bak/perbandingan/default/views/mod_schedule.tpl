<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle">MOD Schedule</h2>

		<div style="margin-bottom:10px;">
			<a class="add-schedule" href="#popup-form"><input type="button" value="Add Schedule" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="200">Date</th>
					<th width="200">Name</th>
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->schedules))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->schedules as $schedule) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $schedule['date']; ?></td>
					<td class="date-column"><?php echo $schedule['name']; ?></td>
				  <td class="action-column">
					<a class="add-schedule" href="#popup-form" data-id="<?php echo $schedule['schedule_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
					<i class="fa fa-trash remove-schedule" data-id="<?php echo $schedule['schedule_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
				  </td>
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
					
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- Add schedule form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="schedule_id" id="schedule_id" /><br/>
	<label for="name">Schedule Date</label><br/>
	<input type="text" id="schedule_date" name="schedule_date" class="form-control col-md-7 col-xs-12 datepicker" autocomplete="off"><br/><br/>
	<label for="name">MOD Name</label><br/>
	<select id="mod_user_id" name="mod_user_id" class="form-control col-md-7 col-xs-12">
		<?php if(!empty($this->modUsers)) {
				foreach($this->modUsers as $modUser)
		{ ?>
			<option value="<?php echo $modUser['user_id']; ?>"><?php echo $modUser['name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<input type="submit" class="submit-btn" id="add-schedule-submit" name="add-schedule-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var report_date;

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('.add-schedule').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#schedule_date',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#schedule_id").val("");
			}
		}
	});
	
	$(".add-schedule").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit MOD Schedule");
			$.ajax({
				url: "/default/mod/getschedulebyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#schedule_id").val(obj.schedule_id);
				$("#schedule_date").val(obj.schedule_date);
				$("#mod_user_id").val(obj.mod_user_id);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add MOD Schedule");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/mod/saveschedule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/mod/schedule";
			}
		});
	});
	
	$(".remove-schedule").click(function() {
		var res = confirm("Are you sure you want to delete this schedule?");
		if(res == true)
		{
			location.href="/default/mod/deleteschedulebyid/id/"+this.dataset.id;
		}
	});
	
});
</script>