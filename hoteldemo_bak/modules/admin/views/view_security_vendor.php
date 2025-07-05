<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#vendor-form" id="add-vendor" class="add-btn" style="width:100px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Vendor</span>
				</a>
				<h3 class="page-title">Security Vendor</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Vendor</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->vendorList)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->vendorList as $vendor) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $vendor['vendor_name']; ?></td>
								<td><a href="#vendor-form" class="edit-vendor action-btn" data-id="<?php echo $vendor['vendor_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-vendor" data-id="<?php echo $vendor['vendor_id']; ?>"><i class="fa fa-trash" ></i></a>
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

<!-- vendor form -->
  <form action="" id="vendor-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="vendor_id" id="vendor_id" />
	<label for="vendor_name">Vendor Name</label><br/>
	<input type="text" class="form-control" name="vendor_name" id="vendor_name" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-vendor').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#vendor_name',
		callbacks: {
			open: function() {
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-vendor').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-vendor').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#vendor_name',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/vendor/getsecurityvendorbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					console.log(resp.data);
					$("#vendor_id").val(resp.data.vendor_id);
					$("#vendor_name").val(resp.data.vendor_name);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#vendor-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/vendor/addsecurityvendor',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/vendor/viewsecurityvendor";
			}
		});
	});
	
	$('.delete-vendor').click(function() {
		var res = confirm("Are you sure you want to delete this vendor?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/vendor/deletesecurityvendor",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/vendor/viewsecurityvendor";
			});
		}
		
	});
});	
</script>