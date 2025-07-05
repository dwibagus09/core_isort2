<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#general-location-form" id="add-general-location" class="add-btn" style="width:160px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add General Location</span>
				</a>				
				<h3 class="page-title">Security General Location</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Floor</th>
								<th>General Location</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->generalLocation)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->generalLocation as $gl) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $gl['floor']; ?></td>
								<td><?php echo $gl['nama_lokasi']; ?></td>
								<td><a href="#general-location-form" class="edit-general-location action-btn" data-id="<?php echo $gl['lokasi_umum_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-general-location" data-id="<?php echo $gl['lokasi_umum_id']; ?>"><i class="fa fa-trash" ></i></a>
									<a class="copy-general-location" href="#copy-form" data-id="<?php echo $gl['lokasi_umum_id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a><br/>
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

<!-- general locaion form -->
  <form action="" id="general-location-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="lokasi_umum_id" id="lokasi_umum_id" />
	<label for="lantai_id">Floor</label><br/>
	<select name="lantai_id" id="lantai_id">
	<?php foreach($this->floor as $floor) { ?>
		<option value="<?php echo $floor['floor_id']; ?>"><?php echo $floor['floor']; ?></option>
	<?php } ?>
	</select><br/><br/>
	<label for="nama_lokasi">Location Name</label><br/>
	<input type="text" class="form-control" name="nama_lokasi" id="nama_lokasi" required><br/>
	<label for="sort_order">Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Copy General Locoation form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<input type="hidden" name="lokasi_umum_id" id="lokasi_umum_id" /><br/>
	<label for="name">Copy Modus to other sites</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="btn submit-btn" id="copy-general-location-submit" name="copy-general-location-submit" value="Copy">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-general-location').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#general-location',
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
	
	$('.edit-general-location').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-general-location').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#general-location',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/issuefinding/getsecuritygenerallocationbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#lokasi_umum_id").val(resp.data.lokasi_umum_id);
					$("#lantai_id").val(resp.data.lantai_id);
					$("#nama_lokasi").val(resp.data.nama_lokasi);
					$("#sort_order").val(resp.data.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#general-location-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/issuefinding/addsecuritygenerallocation',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/issuefinding/viewsecuritygenerallocation";
			}
		});
	});
	
	$('.delete-general-location').click(function() {
		var res = confirm("Are you sure you want to delete this general location?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/issuefinding/deletesecuritygenerallocation",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/issuefinding/viewsecuritygenerallocation";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-general-location').magnificPopup({
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
				$("#lokasi_umum_id").val("");
			}
		}
	});
	
	$(".copy-general-location").click(function() {
			$("#lokasi_umum_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/issuefinding/copysecuritygenerallocation',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/issuefinding/viewsecuritygenerallocation";
			}
		});
	});
});	
</script>