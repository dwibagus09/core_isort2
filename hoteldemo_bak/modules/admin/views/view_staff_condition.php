<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#staff-condition-form" id="add-staff-condition" class="add-btn" style="width:160px;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Department</span>
				</a>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Department</th>
								<th>Type</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->staffcondition)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->staffcondition as $staffcondition) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $staffcondition['department']; ?></td>
								<td><?php if($staffcondition['type'] == 0) echo "Operasional"; else echo "Non-Operasional"; ?></td>
								<td><a href="#staff-condition-form" class="edit-staff-condition action-btn" data-id="<?php echo $staffcondition['staff_condition_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-staff-condition" data-id="<?php echo $staffcondition['staff_condition_id']; ?>"><i class="fa fa-trash" ></i></a>
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
  <form action="" id="staff-condition-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="staff_condition_id" id="staff_condition_id" />
	<label for="department">Department</label><br/>
	<input type="text" class="form-control" name="department" id="department" required><br/>
	<label for="type">Type</label><br/>
	<select id="type" name="type" class="form-control" required>
		<option value="0">Operasional</option>
		<option value="1">Non-Operasional</option>
	</select>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-staff-condition').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#department',
		callbacks: {
			open: function() {
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-staff-condition').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-staff-condition').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#department',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/mod/getstaffconditionbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#staff_condition_id").val(resp.data.staff_condition_id);
					$("#department").val(resp.data.department);
					$("#type").val(resp.data.type);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#staff-condition-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/mod/addstaffcondition',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/mod/viewstaffcondition";
			}
		});
	});
	
	$('.delete-staff-condition').click(function() {
		var res = confirm("Are you sure you want to delete this department?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/mod/deletestaffcondition",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/mod/viewstaffcondition";
			});
		}
		
	});
});	
</script>