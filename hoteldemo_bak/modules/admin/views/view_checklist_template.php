<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#template-form" id="add-template" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Template</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="30%">Department</th>
								<th>Template Name</th>
								<th width="110" style="text-align:center">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->templates)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->templates as $template) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td width="30%"><?php echo $template['category_name']; ?></td>
								<td><?php echo $template['template_name']; ?></td>
								<td width="110">
									<a href="/admin/checklist/viewitems/id/<?php echo $template['template_id']; ?>" class="action-btn"><i class="fa fa-list" ></i></a>
									<a href="#template-form" class="edit-template action-btn" data-id="<?php echo $template['template_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-template" data-id="<?php echo $template['template_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- template form -->
  <form action="" id="template-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<h4 id="template-form-title">Add Template</h4>
	<input type="hidden" name="template_id" id="template_id" />
	<label for="category_id">Department</label><br/>
	<select name="category_id" id="category_id" class="form-control">
	<?php foreach($this->categories as $category) { ?>
		<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
	<?php } ?>
	</select>
	<label for="template_name">Template Name</label><br/>
	<input type="text" class="form-control" name="template_name" id="template_name" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Template to other sites</h4>
	<input type="hidden" name="template_id" id="template_id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-template-submit" name="copy-template-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-digital-checklist .has-arrow").attr("aria-expanded", true);
	$("#menu-digital-checklist li.checklist-templates").addClass("active");
	$("#menu-digital-checklist .collapse").addClass("in");
	

	var selectedID;
	$('#add-template').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#template_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#template_id").val("");
				$("#category_id").val("");
				$("#template_name").val("");
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-template').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-template').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#template_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/checklist/gettemplatebyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#template-form-title").html("Edit Template");
					$("#template_id").val(resp.data.template_id);
					$("#category_id").val(resp.data.category_id);
					$("#template_name").val(resp.data.template_name);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#template-form').on('submit', function(event){
		event.preventDefault(); 
		var curId = $("#template_id").val();
		$.ajax({
			url: '/admin/checklist/addtemplate',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				console.log(id);
				if(curId > 0) { location.href="/admin/checklist/view"; }
				else { location.href="/admin/checklist/viewitems/id/"+id; }
			}
		});
	});
	
	$('.delete-template').click(function() {
		var res = confirm("Are you sure you want to delete this template?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/checklist/delete",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/checklist/view";
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
				$("#template_id").val("");
			}
		}
	});
	
	$(".copy-checklist").click(function() {
			$("#template_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/checklist/copy',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/checklist/view";
			}
		});
	});
});	
</script>