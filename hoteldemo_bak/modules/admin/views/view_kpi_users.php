<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#user-form" id="add-user" class="add-btn" style="width:90px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add User</span>
				</a>
				<h3 class="page-title"><?php echo $this->category['category_name']; ?> KPI Users</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Name</th>
								<th>Position</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->kpiUsers)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->kpiUsers as $u) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $u['name']; ?></td>
								<td><?php echo $this->position[$u['position_id']]; ?></td>
								<td><a href="#user-form" class="edit-user action-btn" data-id="<?php echo $u['kpi_user_id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-user" data-id="<?php echo $u['kpi_user_id']; ?>"><i class="fa fa-trash" ></i></a>
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

<!-- user form -->
  <form action="" id="user-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="kpi_user_id" id="kpi_user_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
	<label for="name">Name</label><br/>
	<select id="user_id" name="user_id" style="width:270px;">
		<?php if(!empty($this->users)) { foreach($this->users as $user) { ?>
			<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<label for="end">Position</label><br/>
	<select id="position_id" name="position_id" style="width:270px;">
		<option value="1">Chief</option>
		<option value="2">Spv</option>
		<option value="3">Staff</option>
		<option value="4">Admin</option>
	</select><br/><br/>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-user').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#user',
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
	
	$('.edit-user').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-user').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#user',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/kpi/getuserbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					/*console.log(resp.data);*/
					$("#kpi_user_id").val(resp.data.kpi_user_id);
					$("#user_id").val(resp.data.user_id);
					$("#position_id").val(resp.data.position_id);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#user-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/kpi/saveuser',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				location.href="/admin/kpi/viewusers/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$('.delete-user').click(function() {
		var res = confirm("Are you sure you want to delete this user?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/kpi/deleteuser",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/kpi/viewusers/c/<?php echo $this->category_id; ?>";
			});
		}
		
	});
});	
</script>