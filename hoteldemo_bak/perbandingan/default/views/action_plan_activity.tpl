<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<a class="add-activity" href="#popup-form"><input type="button" value="Add Activity" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  	<th width="50">No</th>
				  	<th width="150">Module</th>
				  	<th width="150">Target</th>
				  	<th>Activity</th>
					<th width="150">Document As Approve</th>
					<th width="150">Remarks</th>
					<th width="80">Total Schedule</th>
				  <th width="80">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->activity))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->activity as $activity) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?></th>
				  <td class="date-column"><?php echo $activity['module_name']; ?></td>
				  <td class="date-column"><?php echo $activity['target_name']; ?></td>
				  <td class="date-column"><?php echo $activity['activity_name']; ?></td>
					<td class="date-column"><?php echo $activity['document_as_approve']; ?></td>
					<td class="date-column"><?php echo $activity['remarks']; ?></td>
					<td class="date-column"><?php echo $activity['total_schedule']; ?></td>
				  <td class="action-column">
					<a class="add-activity" href="#popup-form" data-id="<?php echo $activity['action_plan_activity_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
					<a class="copy-activity" href="#copy-form" data-id="<?php echo $activity['action_plan_activity_id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a><br/>
					<i class="fa fa-trash remove-event" data-id="<?php echo $activity['action_plan_activity_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
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

<!-- Add Activity form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="action_plan_activity_id" id="action_plan_activity_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Target</label><br/>
	<select id="action_plan_target_id" name="action_plan_target_id">
		<?php if(!empty($this->target)) { foreach($this->target as $target) { ?>
			<option value="<?php echo $target['action_plan_target_id']; ?>"><?php echo $target['target_name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<label for="name">Activity</label><br/>
	<textarea rows="3" cols="30" name="activity_name" id="activity_name"></textarea><br/><br/>
	<label for="name">Document As Approve</label><br/>
	<textarea rows="2" cols="30" name="document_as_approve" id="document_as_approve"></textarea><br/><br/>
	<label for="name">Remarks</label><br/>
	<textarea rows="3" cols="30" name="remarks" id="remarks"></textarea><br/><br/>
	<label for="name">Total Schedule</label><br/>
	<input type="text" name="total_schedule" id="total_schedule"><br/><br/>
	<label for="name">Sort Order</label><br/>
	<input type="text" name="sort_order" id="sort_order"><br/><br/>
	<input type="submit" class="submit-btn" id="add-activity-submit" name="add-activity-submit" value="Submit">
  </form>

	<!-- Copy activity form -->
  <form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title">Copy Activity to Other Site</h2>
	<input type="hidden" name="action_plan_activity_id" id="action_plan_activity_id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-activity-submit" name="copy-activity-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var report_date;
	
	$('.add-activity').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#activity_name',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_activity_id").val("");
			}
		}
	});
	
	$(".add-activity").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit Activity");
			$.ajax({
				url: "/default/actionplan/getactivitybyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#action_plan_activity_id").val(obj.action_plan_activity_id);
				$("#action_plan_target_id").val(obj.action_plan_target_id);
				$("#activity_name").val(obj.activity_name);
				$("#sort_order").val(obj.sort_order);
				$("#document_as_approve").val(obj.document_as_approve);
				$("#total_schedule").val(obj.total_schedule);
				$("#remarks").val(obj.remarks);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Activity");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/saveactivity',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/activity/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$(".remove-event").click(function() {
		var res = confirm("Are you sure you want to delete this activity?");
		if(res == true)
		{
			location.href="/default/actionplan/deleteactivitybyid/id/"+this.dataset.id+"/c/<?php echo $this->category_id; ?>";
		}
	});

	/*** COPY Form ***/

	$('.copy-activity').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#site_id',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#copy-form')[0].reset();
				$("#action_plan_activity_id").val("");
			}
		}
	});
	
	$(".copy-activity").click(function() {
			$("#action_plan_activity_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/copyactivity',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/activity/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
});
</script>