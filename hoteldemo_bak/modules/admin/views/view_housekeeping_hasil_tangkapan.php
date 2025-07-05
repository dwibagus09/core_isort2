<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#tangkapan-form" id="add-tangkapan" class="add-btn" style="width:180px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Tambah Hasil Tangkapan</span>
				</a>
				<h3 class="page-title">Housekeeping Hasil Tangkapan</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Hewan</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->tangkapan)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->tangkapan as $tangkapan) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $tangkapan['hewan_tangkapan']; ?></td>
								<td><a href="#tangkapan-form" class="edit-tangkapan action-btn" data-id="<?php echo $tangkapan['tangkapan_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-tangkapan" data-id="<?php echo $tangkapan['tangkapan_id']; ?>"><i class="fa fa-trash" ></i></a>
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
  <form action="" id="tangkapan-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="tangkapan_id" id="tangkapan_id" />
	<label for="hewan_tangkapan">Hewan Tangkapan</label><br/>
	<input type="text" class="form-control" name="hewan_tangkapan" id="hewan_tangkapan" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-tangkapan').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#hewan_tangkapan',
		callbacks: {
			open: function() {
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-tangkapan').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-tangkapan').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#hewan_tangkapan',
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/tangkapan/gethousekeepinghasiltangkapanbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#tangkapan_id").val(resp.data.tangkapan_id);
					$("#hewan_tangkapan").val(resp.data.hewan_tangkapan);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#tangkapan-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/tangkapan/addhousekeepinghasiltangkapan',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/tangkapan/viewhousekeepinghasiltangkapan";
			}
		});
	});
	
	$('.delete-tangkapan').click(function() {
		var res = confirm("Are you sure you want to delete this?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/tangkapan/deletehousekeepinghasiltangkapan",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/tangkapan/viewhousekeepinghasiltangkapan";
			});
		}
		
	});
});	
</script>