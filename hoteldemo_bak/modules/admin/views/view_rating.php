<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#rating-form" id="add-rating" class="add-btn" style="width:110px; float:right;">
				  <i class="fa fa-plus-square "></i>
				  <span class="link-title">Add Rating</span>
				</a>
				<h3 class="page-title">Rating</h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>No</th>
								<th>Range</th>
								<th>Rating</th>
								<th>Action</th>
							</tr>
						</thead>	
						<?php if(!empty($this->rating)) { ?>											
						<tbody>
						<?php
							$i = 1;
							foreach($this->rating as $r) {
						?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $r['start_range']."% - ". $r['end_range']."%"; ?></td>
								<td><?php echo $r['rating']; ?></td>
								<td><a href="#rating-form" class="edit-rating action-btn" data-id="<?php echo $r['id']; ?>"><i class="fa fa-edit" ></i></a>
									<a class="action-btn delete-rating" data-id="<?php echo $r['id']; ?>"><i class="fa fa-trash" ></i></a>
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

<!-- rating form -->
  <form action="" id="rating-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="id" id="id" />
	<label for="start_range">Start Range</label><br/>
	<input type="text" class="form-control" name="start_range" id="start_range" required><br/>
	<label for="end">End Range</label><br/>
	<input type="text" class="form-control" name="end_range" id="end_range" required><br/>
	<label for="rating">Rating</label><br/>
	<input type="text" class="form-control" name="rating" id="rating" required>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Copy rating form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy rating to other sites</h4>
	<input type="hidden" name="id" id="id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-rating-submit" name="copy-rating-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	$('#add-rating').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#rating',
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
	
	$('.edit-rating').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-rating').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#rating',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/kpi/getratingbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					console.log(resp.data);
					$("#id").val(resp.data.id);
					$("#start_range").val(resp.data.start_range);
					$("#end_range").val(resp.data.end_range);
					$("#rating").val(resp.data.rating);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#rating-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/kpi/saverating',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/kpi/viewrating";
			}
		});
	});
	
	$('.delete-rating').click(function() {
		var res = confirm("Are you sure you want to delete this rating?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/kpi/deleterating",
				data: { id : this.dataset.id }
			}).done(function(response) { 
				location.href="/admin/kpi/viewrating";
			});
		}
		
	});
});	
</script>