<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#user-form" id="add-user" class="add-btn" style="float:right;">
				  <i class="fa fa-user-plus "></i>
				  <span class="link-title">Add User</span>
				</a>
				<h3 class="page-title">Users</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="25%">Username</th>
								<th>Name</th>
								<th width="25%">Role</th>
								<th width="90">Action</th>
							</tr>
						</thead>	
					</table>
					
					<?php if(!empty($this->users)) { ?>
					<div class="table_body">
						<table class="table table-striped responsive-table">
							<tbody>
							<?php
								foreach($this->users as $user) {
							?>
								<tr>
									<td width="25%"><?php echo $user['username']; ?></td>
									<td><?php echo $user['name']; ?></td>
									<td width="25%"><?php echo $user['role']; ?></td>
									<td width="90"><a href="#user-form" class="edit-user action-btn" data-id="<?php echo $user['user_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
										<a class="action-btn delete-user" data-id="<?php echo $user['user_id']; ?>"><i class="fa fa-eraser" ></i></a>
									</td>
								</tr>
							<?php } ?>
							</tbody>  
						</table>
					</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<!-- /.inner -->
	</div>
	<!-- /.outer -->
</div>
<!-- /#content -->

<!-- comment form -->
  <form action="" id="user-form" class="mfp-hide white-popup-block" >
	<h4>Add User</h4>
	<div id="err-msg"></div>
	<input type="hidden" name="user_id" id="user_id" />
	<label for="username">Username</label><br/>
	<input type="text" class="form-control" name="username" id="username" required>
	<label for="password">Password</label><br/>
	<input type="password" class="form-control" name="password" id="password">
	<label for="confirm_password">Confirm Password</label><br/>
	<input type="password" class="form-control" name="confirm_password" id="confirm_password">
	<label for="name">Name</label><br/>
	<input type="text" class="form-control" name="name" id="name" required>
	<label for="role">Role</label><br/>
	<select class="form-control" id="role" name="role[]" multiple>
	<?php if(!empty($this->role)) {
		foreach($this->role as $role) { ?>
		<option value="<?php echo $role['role_id']; ?>"><?php echo $role['role']; ?></option>
		<?php } } ?>
	</select>
	<div class="form-btn"><input type="submit" value="Save" class="btn btn-primary" style="width: 100px;"></div>
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-user').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#username',
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
		focus: '#username',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/user/getuserbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#user_id").val(resp.data.user_id);
					$("#username").val(resp.data.username);
					$("#name").val(resp.data.name);
					$("#role").val(resp.data.role_id.split(","));
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#user-form').on('submit', function(event){
		event.preventDefault(); 
		if($("#password")[0].value == $("#confirm_password")[0].value)
		{
			$.ajax({
				url: '/admin/user/adduser',
				type: 'POST',
				data: $(this).serialize(),
				success: function(id) {
					 location.href="/admin/user/view";
				}
			});
		}
		else
		{
			$("#err-msg").html("Password and confirm password should be the same");
			$("#err-msg").show();
		}
	});
	
	$('.delete-user').click(function() {
		var res = confirm("Are you sure you want to delete this user?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/user/deleteuser",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/user/view";
			});
		}
		
	});
});	
</script>