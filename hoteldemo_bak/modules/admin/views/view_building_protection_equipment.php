<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#equipment-form" id="add-equipment" class="add-btn" style="width:130px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Equipment</span>
				</a>
				<h3 class="page-title">Peralatan Proteksi Gedung</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>Jenis Peralatan</th>
								<th>Nama Peralatan</th>
								<th>Urutan</th>
								<th>Enable</th>
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
								<td><?php echo $equipment['equipment_name']; ?></td>
								<td><?php echo $equipment['item_name']; ?></td>
								<td><?php echo $equipment['sort_order']; ?></td>
								<td><?php if($equipment['enable'] == '1') echo "&#x2714";  ?></td>
								<td><a href="#equipment-form" class="edit-equipment action-btn" data-id="<?php echo $equipment['equipment_item_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-equipment" data-id="<?php echo $equipment['equipment_item_id']; ?>"><i class="fa fa-trash" ></i></a>
									<a class="copy-equipment" href="#copy-form" data-id="<?php echo $equipment['equipment_item_id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a>
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
	<label for="equipment_id">Tipe</label><br/>
	<select id="equipment_id" name="equipment_id">
		<?php if(!empty($this->equipmentType)) {
			foreach($this->equipmentType as $equipmentType) { ?>
				<option value="<?php echo $equipmentType['equipment_id']; ?>"><?php echo $equipmentType['equipment_name']; ?></option>
		<?php } } ?>
	</select><br/>
	<label for="item_name">Nama Peralatan</label><br/>
	<input type="text" class="form-control" name="item_name" id="item_name" required>
	<label for="sort_order">Urutan</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required><br/>
	<input type="checkbox" id="enable" name="enable" value="1"> Enable
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

   <!-- Copy equipment form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Peralatan Proteksi Gedung ke site lain</h4>
	<input type="hidden" name="equipment_item_id" id="equipment_item_id" />
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-equipment-submit" name="copy-equipment-submit" value="Submit">
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
				$("#equipment_id").val("");
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
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/equipment/getbuildingprotectionequipmentbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#equipment_item_id").val(resp.data.equipment_item_id);
					$("#equipment_id").val(resp.data.equipment_id);
					$("#item_name").val(resp.data.item_name);
					$("#sort_order").val(resp.data.sort_order);
					if(resp.data.enable == '1')	$( "#enable" ).prop( "checked", true );
					else $( "#enable" ).prop( "checked", false );
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
			url: '/admin/equipment/addbuildingprotectionequipment',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/equipment/viewbuildingprotectionequipment";
			}
		});
	});
	
	$('.delete-equipment').click(function() {
		var res = confirm("Are you sure you want to delete this equipment?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/equipment/deletebuildingprotectionequipment",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/equipment/viewbuildingprotectionequipment";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-equipment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#site_id',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#copy-form')[0].reset();
				$("#equipment_item_id").val("");
			}
		}
	});
	
	$(".copy-equipment").click(function() {
			$("#equipment_item_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/equipment/copybuildingprotectionequipment',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/equipment/viewbuildingprotectionequipment";
			}
		});
	});
});	
</script>