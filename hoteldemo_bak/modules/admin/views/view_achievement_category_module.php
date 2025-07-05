<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<style>
.mfp-auto-cursor .mfp-content {
	width:500px!important;
}
</style>

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#category-form" id="add-category" class="add-btn" style="width:120px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Category</span>
				</a>
				<h3 class="page-title"><?php echo $this->category['category_name']; ?> Kategori Capaian Kinerja Per Module</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Module Name</th>
								<th>Range</th>
								<th>Description</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->achievementCategory)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->achievementCategory as $ac) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $this->listModules[$ac['module_id']]; ?></td>
								<td width="100"><?php echo $ac['start_range']." - ". $ac['end_range']; ?></td>
								<td width="150"><?php echo $ac['description']; ?></td>
								<td width="100"><a href="#category-form" class="edit-category action-btn" data-id="<?php echo $ac['id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-category" data-id="<?php echo $ac['id']; ?>"><i class="fa fa-trash" ></i></a> 
									<a class="copy-category" href="#copy-form" data-id="<?php echo $ac['id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a>
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

<!-- achievement category form -->
  <form action="" id="category-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="id" id="id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Module</label><br/>
	<select id="action_plan_module_id" name="action_plan_module_id" style="width:450px;">
		<?php if(!empty($this->modules)) { foreach($this->modules as $module) { ?>
			<option value="<?php echo $module['action_plan_module_id']; ?>"><?php echo $module['show_year'].' - '.$module['module_name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<label for="start_range">Start Range</label><br/>
	<input type="text" class="form-control" name="start_range" id="start_range" required><br/>
	<label for="end">End Range</label><br/>
	<input type="text" class="form-control" name="end_range" id="end_range" required><br/>
	<label for="description">Description</label><br/>
	<input type="text" class="form-control" name="description" id="description" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 175px; width: 100px;">
  </form>

<!-- Copy achievement category form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy achievement category to other sites</h4>
	<input type="hidden" name="id" id="id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-category-submit" name="copy-category-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-category').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#start_range',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#id").val("");
				$('#category-form')[0].reset();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-category').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-category').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#start_range',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/kpi/getachievementcategorymodulebyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#id").val(resp.data.id);
					$("#action_plan_module_id").val(resp.data.module_id);
					$("#start_range").val(resp.data.start_range);
					$("#end_range").val(resp.data.end_range);
					$("#description").val(resp.data.description);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#category-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/kpi/saveachievementcategorymodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/kpi/viewachievementcategorymodule/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$('.delete-category').click(function() {
		var res = confirm("Are you sure you want to delete this category?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/kpi/deleteachievementcategorymodule",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/kpi/viewachievementcategorymodule/c/<?php echo $this->category_id; ?>";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-category').magnificPopup({
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
				$("#id").val("");
			}
		}
	});
	
	$(".copy-category").click(function() {
			$("#id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/kpi/copyachievementcategorymodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/kpi/viewachievementcategorymodule/c/<?php echo $this->category_id; ?>";
			}
		});
	});
});	
</script>