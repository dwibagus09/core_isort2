<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#floor-form" id="add-floor" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Floor</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="250">Area</th>
								<th>Floor</th>
								<th width="100">Sort Order</th>
								<th width="110">Action</th>
								<th width="20"></th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->floor)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->floor as $f) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td width="250"><?php echo $f['area_name']; ?></td>
								<td><?php echo $f['floor']; ?></td>
								<td width="100"><?php echo $f['sort_order']; ?></td>
								<td width="80"><a href="#floor-form" class="edit-floor action-btn" data-id="<?php echo $f['floor_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-floor" data-id="<?php echo $f['floor_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- floor form -->
  <form id="floor-form" class="mfp-hide white-popup-block" action="">
	<div id="err-msg"></div>
	<input type="hidden" name="floor_id" id="floor_id" />
	<label for="area_id">Area</label><br/>
	<select name="area_id" id="area_id" class="form-control">
	<?php foreach($this->area as $area) { ?>
		<option value="<?php echo $area['area_id']; ?>"><?php echo $area['area_name']; ?></option>
	<?php } ?>
	</select>
	<label for="floor">Floor</label><br/>
	<input type="text" class="form-control" name="floor" id="floor" required>
	<label for="sort_order">Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<div class="popup-btn"><input type="submit" value="Save" class="btn btn-primary" style="width: 100px;"></div>
  </form>

<!-- Copy floor form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Floor to other sites</h4>
	<input type="hidden" name="floor_id" id="floor_id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn btn btn-primary" id="copy-floor-submit" name="copy-floor-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category; ?> li.floor").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");

	var selectedID;
	$('#add-floor').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#floor',
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
	
	$('.edit-floor').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-floor').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#floor',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "<?php echo $this->getByIdUrl; ?>",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					console.log(resp.data);
					$("#floor_id").val(resp.data.floor_id);
					$("#floor").val(resp.data.floor);
					$("#area_id").val(resp.data.area);
					$("#sort_order").val(resp.data.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#floor-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '<?php echo $this->addUrl; ?>',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="<?php echo $this->viewUrl; ?>";
			}
		});
	});
	
	$('.delete-floor').click(function() {
		var res = confirm("Are you sure you want to delete this floor?");
		if(res == true)
		{
			$.ajax({
				url: "<?php echo $this->deleteUrl; ?>",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="<?php echo $this->viewUrl; ?>";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-floor').magnificPopup({
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
				$("#floor_id").val("");
			}
		}
	});
	
	$(".copy-floor").click(function() {
			$("#floor_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '<?php echo $this->copyUrl; ?>',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="<?php echo $this->viewUrl; ?>";
			}
		});
	});
});	
</script>