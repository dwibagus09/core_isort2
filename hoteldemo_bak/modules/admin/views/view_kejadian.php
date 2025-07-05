<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#kejadian-form" id="add-kejadian" class="add-btn" style="width:150px; text-align: center; float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Incident</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="30%">Issue Type</th>
								<th>Incident</th>
								<th width="100">Sort Order</th>
								<th width="110">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->kejadian)) { ?>		
					<div class="table_body">
						<table class="table table-striped responsive-table">						
						<tbody>
						<?php
							$i = 1;
							foreach($this->kejadian as $k) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td width="30%"><?php echo $k['issue_type_name']; ?></td>
								<td><?php echo $k['kejadian']; ?></td>
								<td width="100"><?php echo $k['sort_order']; ?></td>
								<td width="110"><a href="#kejadian-form" class="edit-kejadian action-btn" data-id="<?php echo $k['kejadian_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-kejadian" data-id="<?php echo $k['kejadian_id']; ?>"><i class="fa fa-eraser" ></i></a>
									<a class="action-btn copy-kejadian" href="#copy-form" data-id="<?php echo $k['kejadian_id']; ?>" style="cursor:pointer;"><i class="fa fa-copy"></i></a>
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

<!-- kejadian form -->
  <form action="" id="kejadian-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="kejadian_id" id="kejadian_id" />
	<input type="hidden" name="category_id" value="<?php echo $this->category_id; ?>" />
	<label for="issue_type">Issue Type</label><br/>
	<select name="issue_type_id" id="issue_type_id" class="form-control">
	<?php foreach($this->issue_type as $issueType) { ?>
		<option value="<?php echo $issueType['issue_type_id']; ?>"><?php echo $issueType['issue_type_name']; ?></option>
	<?php } ?>
	</select>
	<label for="kejadian">Incident</label><br/>
	<input type="text" class="form-control" name="kejadian" id="kejadian" required>
	<label for="sort_order">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required><br/>
	<input type="checkbox" name="show_pelaku_checkbox" id="show_pelaku_checkbox"> Show "Perpetrator Caught" checkbox in Kaizen
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Incident to other sites</h4>
	<input type="hidden" name="kejadian_id" id="kejadian_id" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn" id="copy-kejadian-submit" name="copy-kejadian-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category_id; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category_id; ?> li.incident").addClass("active");
	$("#menu-<?php echo $this->category_id; ?> .collapse").addClass("in");

	var selectedID;
	$('#add-kejadian').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#kejadian',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$('#kejadian-form')[0].reset();
				$("#kejadian_id").val("");
			},
			close: function() {	
				$('#kejadian-form')[0].reset();
				$("#kejadian_id").val("");
			}	
		}
	});
	
	$('.edit-kejadian').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-kejadian').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#kejadian',
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
					$("#kejadian_id").val(resp.data.kejadian_id);
					$("#issue_type_id").val(resp.data.issue_type);
					$("#kejadian").val(resp.data.kejadian);
					$("#sort_order").val(resp.data.sort_order);
					if(resp.data.show_pelaku_checkbox == '1')
					{
						$('#show_pelaku_checkbox').prop('checked', true);
					}
					else
					{
						$('#show_pelaku_checkbox').prop('checked', false);
					}
				});
				$("#err-msg").hide();
			},
			close: function() {	
				$('#kejadian-form')[0].reset();
				$("#kejadian_id").val("");
			}
		}
	});
	
	$('#kejadian-form').on('submit', function(event){
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
	
	$('.delete-kejadian').click(function() {
		var res = confirm("Are you sure you want to delete this incident?");
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

	$('.copy-kejadian').magnificPopup({
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
				$("#kejadian_id").val("");
			}
		}
	});
	
	$(".copy-kejadian").click(function() {
			$("#kejadian_id").val(this.dataset.id);
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