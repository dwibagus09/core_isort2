<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#category-form" id="add-category" class="add-btn" style="width:120px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Category</span>
				</a>
				<h3 class="page-title">Kategori Capaian Kinerja Kesimpulan</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
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
								<td><?php echo $ac['start_range']." - ". $ac['end_range']; ?></td>
								<td><?php echo $ac['description']; ?></td>
								<td><a href="#category-form" class="edit-category action-btn" data-id="<?php echo $ac['id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-category" data-id="<?php echo $ac['id']; ?>"><i class="fa fa-trash" ></i></a>
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
	<label for="start_range">Start Range</label><br/>
	<input type="text" class="form-control" name="start_range" id="start_range" required><br/>
	<label for="end">End Range</label><br/>
	<input type="text" class="form-control" name="end_range" id="end_range" required><br/>
	<label for="description">Description</label><br/>
	<input type="text" class="form-control" name="description" id="description" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Copy achievement category form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy category to other sites</h4>
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
					url: "/admin/kpi/getachievementcategorybyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#id").val(resp.data.id);
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
			url: '/admin/kpi/saveachievementcategory',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/kpi/viewachievementcategory";
			}
		});
	});
	
	$('.delete-category').click(function() {
		var res = confirm("Are you sure you want to delete this category?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/kpi/deleteachievementcategory",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/kpi/viewachievementcategory";
			});
		}
		
	});
});	
</script>