<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#area-form" id="add-area" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Area</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th>Area</th>
								<th width="100">Sort Order</th>
								<th width="90">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->area)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->area as $a) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td><?php echo $a['area_name']; ?></td>
								<td width="100"><?php echo $a['sort_order']; ?></td>
								<td width="90"><a href="#area-form" class="edit-area action-btn" data-id="<?php echo $a['area_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-area" data-id="<?php echo $a['area_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- area form -->
  <form action="" id="area-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="area_id" id="area_id" />
	<label for="area_name">Area</label><br/>
	<input type="text" class="form-control" name="area_name" id="area_name" required>
	<label for="sort_order">Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Area to other sites</h4>
	<input type="hidden" name="area_id" id="area_id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-area-submit" name="copy-area-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category; ?> li.area").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");

	var selectedID;
	$('#add-area').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#area_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$("#area_id").val("");
				$("#area_name").val("");
				$("#sort_order").val("");
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-area').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-area').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#area_name',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/area/getareabyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					console.log(resp.data);
					$("#area_id").val(resp.data.area_id);
					$("#area_name").val(resp.data.area_name);
					$("#sort_order").val(resp.data.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#area-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/area/addarea',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/area/view";
			}
		});
	});
	
	$('.delete-area').click(function() {
		var res = confirm("Are you sure you want to delete this area?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/area/delete",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/area/view";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-area').magnificPopup({
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
				$("#area_id").val("");
			}
		}
	});
	
	$(".copy-area").click(function() {
			$("#area_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/area/copy',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/area/view";
			}
		});
	});
});	
</script>