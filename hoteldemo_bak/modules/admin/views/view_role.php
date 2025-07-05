<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="/admin/user/view" class="add-btn">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Role</span>
				</a>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>Role</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->role)) { ?>											
						<tbody>
						<?php
							foreach($this->role as $role) {
						?>
							<tr>
								<td><?php echo $role['role']; ?></td>
								<td></td>
							</tr>
						<?php } ?>
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