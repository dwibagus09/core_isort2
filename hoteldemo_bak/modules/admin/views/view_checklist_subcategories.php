<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#subcategory-form" id="add-subcategory" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Subcategory</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th>Category Name</th>
								<th>Subcategory Name</th>
								<th width="30%" style="text-align:center;">Sort Order</th>
								<th width="90">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->subcategories)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->subcategories as $subcategory) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td><?php echo $subcategory['category_name']; ?></td>
								<td><?php echo $subcategory['subcategory_name']; ?></td>
								<td width="30%" style="text-align:center;"><?php echo $subcategory['sort_order']; ?></td>
								<td width="90"><a href="#subcategory-form" class="edit-subcategory action-btn" data-id="<?php echo $subcategory['subcategory_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-subcategory" data-id="<?php echo $subcategory['subcategory_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- subcategory form -->
  <form action="" id="subcategory-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<h4 id="subcategory-form-title">Add Subcategory</h4>
	<input type="hidden" name="subcategory_id" id="subcategory_id" />
	<label for="category_id">Categories</label><br/>
	<select name="category_id" id="category_id" class="form-control">
	<?php foreach($this->categories as $category) { ?>
		<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
	<?php } ?>
	</select>
	<label for="subcategory_name">Subcategory Name</label><br/>
	<input type="text" class="form-control" name="subcategory_name" id="subcategory_name" required>
	<label for="sort_order">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy subcategory form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Subcategory to other sites</h4>
	<input type="hidden" name="subcategory_id" id="subcategory_id" /><br/>
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
	$("#menu-digital-checklist li.checklist-subcat").addClass("active");
	$("#menu-digital-checklist .collapse").addClass("in");

	var selectedID;
	$('#add-subcategory').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#subcategory_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#subcategory_id").val("");
				$("#sort_order").val("");
				$("#subcategory_name").val("");
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-subcategory').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-subcategory').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#category_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/checklist/getsubcategorybyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#subcategory-form-title").html("Edit Subcategory");
					$("#subcategory_id").val(resp.data.subcategory_id);
					$("#category_id").val(resp.data.category_id);
					$("#subcategory_name").val(resp.data.subcategory_name);
					$("#sort_order").val(resp.data.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#subcategory-form').on('submit', function(event){
		event.preventDefault(); 
		var curId = $("#category_id").val();
		$.ajax({
			url: '/admin/checklist/addsubcategory',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				location.href="/admin/checklist/subcategories";
			}
		});
	});
	
	$('.delete-subcategory').click(function() {
		var res = confirm("Are you sure you want to delete this subcategory?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/checklist/deletesubcategory",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/checklist/subcategories";
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
				$("#subcategory_id").val("");
			}
		}
	});
	
	$(".copy-checklist").click(function() {
			$("#subcategory_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/checklist/copysubcategory',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/checklist/subcategories";
			}
		});
	});
});	
</script>