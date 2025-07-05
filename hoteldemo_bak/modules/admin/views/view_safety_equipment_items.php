<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#equipment-form" id="add-equipment" class="add-btn" style="width:158px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Equipment Item</span>
				</a>
				<h3 class="page-title">Safety Equipment Items</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th rowspan="2">No</th>
								<th rowspan="2">Equipment</th>
								<th rowspan="2">Item</th>
								<th rowspan="2">Status</th>
								<th colspan="2" style="text-align:center">Status Pressure</th>
								<th rowspan="2">Action</th>
							</tr>
							<tr>
								<th>Cut In</th>
								<th>Cut Off</th>
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
								<td><?php echo $equipment['item_name']; ?></td>
								<td><?php echo $equipment['status']; ?></td>
								<td><?php echo $equipment['status_cut_in']; ?></td>
								<td><?php echo $equipment['status_cut_off']; ?></td>
								<td><a href="#equipment-form" class="edit-equipment action-btn" data-id="<?php echo $equipment['equipment_item_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-equipment" data-id="<?php echo $equipment['equipment_item_id']; ?>"><i class="fa fa-trash" ></i></a>
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
	<input type="hidden" name="equipment_item_id" id="equipment_item_id" />
	<label for="no">Equipment Name</label><br/>
	<select class="form-control" id="safety_equipment_list_id" name="safety_equipment_list_id">
	<?php if(!empty($this->safety_equipment_list)) {
		foreach($this->safety_equipment_list as $safety_equipment_list) { ?>
		<option value="<?php echo $safety_equipment_list['safety_equipment_list_id']; ?>"><?php echo $safety_equipment_list['no']." - ".$safety_equipment_list['equipment_name']; ?></option>
		<?php } } ?>
	</select>
	<label for="item_name">Equipment Item Name</label><br/>
	<input type="text" class="form-control" name="item_name" id="item_name">
	<label for="status">Status</label><br/>
	<input type="text" class="form-control" name="status" id="status">
	<label for="status_cut_in">Status Pressure - Cut In</label><br/>
	<input type="text" class="form-control" name="status_cut_in" id="status_cut_in">
	<label for="status_cut_off">Status Pressure - Cut Off</label><br/>
	<input type="text" class="form-control" name="status_cut_off" id="status_cut_off">
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
		focus: '#item_name',
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
		focus: '#item_name',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/equipment/getsafetyequipmentitembyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#equipment_item_id").val(resp.data.equipment_item_id);
					$("#safety_equipment_list_id").val(resp.data.safety_equipment_list_id);
					$("#item_name").val(resp.data.item_name);
					$("#status").val(resp.data.status);
					$("#status_cut_in").val(resp.data.status_cut_in);
					$("#status_cut_off").val(resp.data.status_cut_off);
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
			url: '/admin/equipment/addsafetyequipmentitem',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/equipment/viewsafetyequipmentitems";
			}
		});
	});
	
	$('.delete-equipment').click(function() {
		var res = confirm("Are you sure you want to delete this item?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/equipment/deletesafetyequipmentitem",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/equipment/viewsafetyequipmentitems";
			});
		}
		
	});
});	
</script>