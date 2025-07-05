<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#equipment-form" id="add-equipment" class="add-btn" style="width:125px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Equipment</span>
				</a>
				<h3 class="page-title">MOD Equipments</h3>
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
								<td><?php echo $i; ?></td>
								<td><?php echo $equipment['equipment_name']; ?></td>
								<td><a href="#equipment-form" class="edit-equipment action-btn" data-id="<?php echo $equipment['mod_equipment_list_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-equipment" data-id="<?php echo $equipment['mod_equipment_list_id']; ?>"><i class="fa fa-trash" ></i></a>
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
	<input type="hidden" name="mod_equipment_list_id" id="mod_equipment_list_id" />
	<input type="hidden" name="equipment_type" id="equipment_type" value="<?php echo $this->type; ?>" />
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
					url: "/admin/equipment/getmodequipmentbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#mod_equipment_list_id").val(resp.data.mod_equipment_list_id);
					$("#equipment_name").val(resp.data.equipment_name);
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
			url: '/admin/equipment/addmodequipment',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/equipment/viewmodequipment";
			}
		});
	});
	
	$('.delete-equipment').click(function() {
		var res = confirm("Are you sure you want to delete this equipment?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/equipment/deletemodequipment",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/equipment/viewmodequipment";
			});
		}
		
	});
});	
</script>