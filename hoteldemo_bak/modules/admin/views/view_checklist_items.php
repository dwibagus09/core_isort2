<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#item-form" id="add-item" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Item</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="25%">Category</th>
								<th width="25%">Subcategory</th>
								<th>Checklist Item</th>
								<th width="90">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->items)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->items as $item) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td width="25%"><?php echo $item['category_name']; ?></td>
								<td width="25%"><?php echo $item['subcategory_name']; ?></td>
								<td><?php echo $item['item_name']; ?></td>
								<td width="90"><a href="#item-form" class="edit-item action-btn" data-id="<?php echo $item['item_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-item" data-id="<?php echo $item['item_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- item form -->
  <form action="" id="item-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<h4 id="item-form-title">Add Item</h4>
	<input type="hidden" name="item_id" id="item_id" />
	<input type="hidden" name="template_id" id="template_id" value="<?php echo $this->template_id; ?>" />
	<label for="category_id">Category</label><br/>
	<select name="category_id" id="category_id" class="form-control">
	<?php foreach($this->categories as $category) { ?>
		<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
	<?php } ?>
	</select>
	<label for="subcategory_id">Sub Category</label><br/>
	<select name="subcategory_id" id="subcategory_id" class="form-control">
	<?php foreach($this->subcategories as $subcategory) { ?>
		<option value="<?php echo $subcategory['subcategory_id']; ?>"><?php echo $subcategory['subcategory_name']; ?></option>
	<?php } ?>
	</select>
	<label for="item_name">Item Name</label><br/>
	<input type="text" class="form-control" name="item_name" id="item_name" required>
	<label for="sort_order">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-digital-checklist .has-arrow").attr("aria-expanded", true);
	$("#menu-digital-checklist li.checklist-templates").addClass("active");
	$("#menu-digital-checklist .collapse").addClass("in");

	var selectedID;
	$('#add-item').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#item_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#item_id").val("");
				$("#category_id").val("");
				$("#subcategory_id").val("");
				$("#item_name").val("");
				$("#sort_order").val();
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-item').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-item').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#item_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/checklist/getitembyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#item-form-title").html("Edit item");
					$("#item_id").val(resp.data.item_id);
					$("#category_id").val(resp.data.category_id);
					$.ajax({
						url: "/admin/checklist/getsubcatbycatid",
						data: { category_id :  resp.data.category_id }
					}).done(function(response) {
						var object = $.parseJSON(response);
						console.log(object);
						$("#subcategory_id").empty();
						$("#subcategory_id").append('<option value="" >None</option>');
						$.each(object.data, function (item, value) {
							$("#subcategory_id").append(new Option(value.subcategory_name, value.subcategory_id));	
						});
						$("#subcategory_id").val(resp.data.subcategory_id);
					});					
					$("#item_name").val(resp.data.item_name);
					$("#sort_order").val(resp.data.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#item-form').on('submit', function(event){
		event.preventDefault(); 
		var curId = $("#item_id").val();
		$.ajax({
			url: '/admin/checklist/additem',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				location.href="/admin/checklist/viewitems/id/<?php echo $this->template_id; ?>";
			}
		});
	});
	
	$('.delete-item').click(function() {
		var res = confirm("Are you sure you want to delete this item?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/checklist/deleteitem",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/checklist/viewitems/id/<?php echo $this->template_id; ?>";
			});
		}
		
	});
	
	$("#category_id").change(function() {
		var cat_id = $( this ).val();
		$.ajax({
			url: "/admin/checklist/getsubcatbycatid",
			data: { category_id :  cat_id }
		}).done(function(response) {
			var object = $.parseJSON(response);
			console.log(object);
			$("#subcategory_id").empty();
			$("#subcategory_id").append('<option value="" >None</option>');
			$.each(object.data, function (item, value) {
				$("#subcategory_id").append(new Option(value.subcategory_name, value.subcategory_id));
			});
		});
	});


});	
</script>