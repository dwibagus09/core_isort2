<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<?php /*<a href="#equipment-form" id="add-equipment" class="add-btn" style="width:150px;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Equipment</span>
				</a> */ ?>
				<h3 class="page-title">Safety Equipments</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Equipment</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->equipment)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->equipment as $equipment) {
						?>
							<tr>
								<td><?php echo $equipment['no']; ?></td>
								<td><?php echo $equipment['equipment_name']; ?></td>
								<td><a href="#equipment-form" class="edit-equipment action-btn" data-id="<?php echo $equipment['safety_equipment_list_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-equipment" data-id="<?php echo $equipment['safety_equipment_list_id']; ?>"><i class="fa fa-trash" ></i></a>
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
  <form action="" id="equipment-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="safety_equipment_list_id" id="safety_equipment_list_id" />
	<label for="equipment_name">No</label><br/>
	<input type="text" class="form-control" name="no" id="no" required>
	<label for="equipment_name">Equipment Name</label><br/>
	<input type="text" class="form-control" name="equipment_name" id="equipment_name" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-equipment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#equipment_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-equipment').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-equipment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#equipment_name',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/equipment/getsafetyequipmentbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#safety_equipment_list_id").val(resp.data.safety_equipment_list_id);
					$("#no").val(resp.data.no);
					$("#equipment_name").val(resp.data.equipment_name);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#equipment-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/equipment/addsafetyequipment',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/equipment/viewsafetyequipment";
			}
		});
	});
	
	$('.delete-equipment').click(function() {
		var res = confirm("Are you sure you want to delete this equipment?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/equipment/deletesafetyequipment",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/equipment/viewsafetyequipment";
			});
		}
		
	});
});	
</script>