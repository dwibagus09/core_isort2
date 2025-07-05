<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#category-form" id="add-category" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Category</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th>Category Name</th>
								<th width="30%" style="text-align:center;">Sort Order</th>
								<th width="90">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->categories)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->categories as $category) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td><?php echo $category['category_name']; ?></td>
								<td width="30%" style="text-align:center;"><?php echo $category['sort_order']; ?></td>
								<td width="90"><a href="#category-form" class="edit-category action-btn" data-id="<?php echo $category['category_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-category" data-id="<?php echo $category['category_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
								</td>
							</tr>
						<?php $i++; } ?>
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

<!-- category form -->
  <form action="" id="category-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<h4 id="category-form-title">Add Category</h4>
	<input type="hidden" name="category_id" id="category_id" />
	<label for="category_name">Category Name</label><br/>
	<input type="text" class="form-control" name="category_name" id="category_name" required>
	<label for="sort_order">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Category to other sites</h4>
	<input type="hidden" name="category_id" id="category_id" /><br/>
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
	$("#menu-digital-checklist .has-arrow").attr("aria-expanded", true);
	$("#menu-digital-checklist li.checklist-cat").addClass("active");
	$("#menu-digital-checklist .collapse").addClass("in");

	var selectedID;
	$('#add-category').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#category_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#category_id").val("");
				$("#sort_order").val("");
				$("#category_name").val("");
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
		focus: '#category_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/checklist/getcategorybyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#category-form-title").html("Edit Category");
					$("#category_id").val(resp.data.category_id);
					$("#sort_order").val(resp.data.sort_order);
					$("#category_name").val(resp.data.category_name);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#category-form').on('submit', function(event){
		event.preventDefault(); 
		var curId = $("#category_id").val();
		$.ajax({
			url: '/admin/checklist/addcategory',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				location.href="/admin/checklist/categories";
			}
		});
	});
	
	$('.delete-category').click(function() {
		var res = confirm("Are you sure you want to delete this category?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/checklist/deletecategory",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/checklist/categories";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-checklist').magnificPopup({
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
				$("#category_id").val("");
			}
		}
	});
	
	$(".copy-checklist").click(function() {
			$("#category_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/checklist/copycategory',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/checklist/categories";
			}
		});
	});
});	
</script>