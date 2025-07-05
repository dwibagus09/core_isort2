<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">				
				<h3 class="page-title">Jenis Peralatan Proteksi Gedung
					<a href="#equipment-form" id="add-equipment" class="add-btn" style="width:155px; float:right;">
					<i class="fa fa-plus-square "></i>
					<span class="link-title">Add Equipment Type</span>
					</a>
				</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>Tipe</th>
								<th>Jenis Peralatan</th>
								<th>Nama Kolom</th>
								<th>Urutan</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->equipmentType)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->equipmentType as $equipmentType) {
						?>
							<tr>
								<td><?php echo $equipmentType['type']; ?></td>
								<td><?php echo $equipmentType['equipment_name']; ?></td>
								<td><?php echo $equipmentType['column_name']; ?></td>
								<td><?php echo $equipmentType['sort_order']; ?></td>
								<td><a href="#equipment-form" class="edit-equipment action-btn" data-id="<?php echo $equipmentType['equipment_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-equipment" data-id="<?php echo $equipmentType['equipment_id']; ?>"><i class="fa fa-trash" ></i></a>
									<a class="copy-equipment" href="#copy-form" data-id="<?php echo $equipmentType['equipment_id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a>
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

<!-- add equipment type form -->
  <form action="" id="equipment-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="equipment_id" id="equipment_id" />
	<label for="equipment_type">Tipe</label><br/>
	<select id="equipment_type" name="type">
		<option value="Aktif">Aktif</option>
		<option value="Pasif">Pasif</option>
	</select><br/>
	<label for="equipment_name">Jenis Peralatan</label><br/>
	<input type="text" class="form-control" name="equipment_name" id="equipment_name" required>
	<label for="column_name">Nama Kolom</label><br/>
	<input type="text" class="form-control" name="column_name" id="column_name" required>
	<label for="sort_order">Urutan</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

  <!-- Copy equipment type form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Jenis Peralatan Proteksi Gedung ke site lain</h4>
	<input type="hidden" name="equipment_id" id="equipment_id" />
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
					url: "/admin/equipment/getbuildingprotectionequipmenttypebyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#equipment_type").val(resp.data.type);
					$("#equipment_id").val(resp.data.equipment_id);
					$("#equipment_name").val(resp.data.equipment_name);
					$("#column_name").val(resp.data.column_name);
					$("#sort_order").val(resp.data.sort_order);
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
			url: '/admin/equipment/addbuildingprotectionequipmenttype',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/equipment/viewbuildingprotectionequipmenttype";
			}
		});
	});
	
	$('.delete-equipment').click(function() {
		var res = confirm("Are you sure you want to delete this equipment?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/equipment/deletebuildingprotectionequipmenttype",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/equipment/viewbuildingprotectionequipmenttype";
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
				$("#equipment_id").val("");
			}
		}
	});
	
	$(".copy-equipment").click(function() {
			$("#equipment_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/equipment/copybuildingprotectionequipmenttype',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/equipment/viewbuildingprotectionequipmenttype";
			}
		});
	});
});	
</script>