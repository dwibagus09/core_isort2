<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#training-form" id="add-training" class="add-btn" style="width:110px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Activity</span>
				</a>
				<h3 class="page-title">Parking &amp; Traffic Taining</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Activity</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->activities)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->activities as $training) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $training['activity']; ?></td>
								<td><a href="#training-form" class="edit-training action-btn" data-id="<?php echo $training['training_activity_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-training" data-id="<?php echo $training['training_activity_id']; ?>"><i class="fa fa-trash" ></i></a>
								</td>
							</tr>
						<?php $i++; } ?>
						</tbody>  
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
		<!-- /.inner -->
	</div>
	<!-- /.outer -->
</div>
<!-- /#content -->

<!-- comment form -->
  <form action="" id="training-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="training_activity_id" id="training_activity_id" />
	<label for="activity_name">Activity Name</label><br/>
	<input type="text" class="form-control" name="activity" id="activity_name" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-training').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#training_name',
		callbacks: {
			open: function() {
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-training').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-training').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#activity_name',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/training/getparkingtrainingactivitybyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#training_activity_id").val(resp.data.training_activity_id);
					$("#activity_name").val(resp.data.activity);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#training-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/training/addparkingtrainingactivity',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/training/viewparkingtrainingactivity";
			}
		});
	});
	
	$('.delete-training').click(function() {
		var res = confirm("Are you sure you want to delete this training activity?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/training/deleteparkingtrainingactivity",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/training/viewparkingtrainingactivity";
			});
		}
		
	});
});	
</script>