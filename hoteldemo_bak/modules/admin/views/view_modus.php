<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<style>
.mfp-container .mfp-content { width:500px!important; }
</style>

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#modus-form" id="add-modus" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Modus</span>
				</a>				
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="50">No</th>
								<th width="35%">Incident</th>
								<th>Modus</th>
								<th width="100">Sort Order</th>
								<th width="110">Action</th>
								<th width="20"></th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->modus)) { ?>		
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->modus as $m) {
						?>
							<tr>
								<td width="50"><?php echo $i; ?></td>
								<td width="35%"><?php echo $m['kejadian']; ?></td>
								<td><?php echo $m['modus']; ?></td>
								<td width="100"><?php echo $m['sort_order']; ?></td>
								<td width="110"><a href="#modus-form" class="edit-modus action-btn" data-id="<?php echo $m['modus_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-modus" data-id="<?php echo $m['modus_id']; ?>"><i class="fa fa-eraser" ></i></a>
									<a class="action-btn copy-modus" href="#copy-form" data-id="<?php echo $m['modus_id']; ?>" style="cursor:pointer;"><i class="fa fa-copy"></i></a><br/>
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

<!-- modus form -->
  <form action="" id="modus-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="modus_id" id="modus_id" />
	<input type="hidden" name="category_id" value="<?php echo $this->category_id; ?>" />
	<label for="kejadian_id">Incident</label><br/>
	<select name="kejadian_id" id="kejadian_id" class="form-control">
	<?php foreach($this->kejadian as $kejadian) { ?>
		<option value="<?php echo $kejadian['kejadian_id']; ?>"><?php echo $kejadian['kejadian']; ?></option>
	<?php } ?>
	</select>
	<label for="modus">Modus</label><br/>
	<input type="text" class="form-control" name="modus" id="modus" required>
	<label for="sort_order">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" required>
	<label for="link">Link to modus from other department</label> <i id="add_link" class="fa fa-plus-square "></i><br/>
	<table id="modus-linked-table" class="table table-striped responsive-table">
		<thead>
			<tr>
				<th>Department</th>
				<th>Incident</th>
				<th>Modus</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
		</tbody>
	</table><br/>
	<input type="submit" value="Save" class="btn" style="margin: 10px 180px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<input type="hidden" name="modus_id" id="modus_id" /><br/>
	<label for="name">Copy Modus to other sites</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="btn submit-btn" id="copy-modus-submit" name="copy-modus-submit" value="Copy">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery.mloading.js"></script>
<link href="/css/jquery.mloading.css" rel="stylesheet">
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category_id; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category_id; ?> li.modus").addClass("active");
	$("#menu-<?php echo $this->category_id; ?> .collapse").addClass("in");

	var selectedID;
	var ctr = 0;
	$('#add-modus').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#modus',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$('#modus-form')[0].reset();
				$("#modus_id").val("");
				$("#modus-linked-table > tbody"). empty();
			},
			close: function() {	
				$('#modus-form')[0].reset();
				$("#modus_id").val("");
				$("#modus-linked-table > tbody"). empty();
			}
		}
	});
	
	$('.edit-modus').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-modus').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#modus',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$('#modus-form')[0].reset();
				$("#modus-linked-table tbody tr").remove();
				$.ajax({
					url: "<?php echo $this->getByIdUrl; ?>",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#modus_id").val(resp.data.modus_id);
					$("#kejadian_id").val(resp.data.kejadian_id);
					$("#modus").val(resp.data.modus);
					$("#sort_order").val(resp.data.sort_order);
					$.each(resp.data.modus_linked, function (item, value) {
						row = '<tr id="modus_linked_tr'+value.linked_id+'">\
								<td>'+value.category_name+'</td>\
								<td>'+value.kejadian+'</td>\
								<td>'+value.modus+'</td>\
								<td><i class="fa fa-trash remove-link-db" style="margin:5px; cursor:pointer;" data-id="'+value.linked_id+'"></i></td>\
							</tr>';
						
						$( "#modus-linked-table").append(row);
						ctr++;
					});
					$('.remove-link-db').click(function() {
						var res = confirm("Are you sure you want to delete this link?");
						if(res == true)
						{
							var linked_id = this.dataset.id;
							$.ajax({
								url: "/admin/issuefinding/deletemoduslinked",
								data: { id : linked_id }
							}).done(function(response) { 
								$("#modus_linked_tr"+linked_id).remove();
							});
						}
					});
				});
				$("#err-msg").hide();
			},
			close: function() {	
				$('#modus-form')[0].reset();
				$("#modus_id").val("");
				$("#modus-linked-table > tbody"). empty();
			}
		}
	});
	
	$('#modus-form').on('submit', function(event){
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
	
	$('.delete-modus').click(function() {
		var res = confirm("Are you sure you want to delete this modus?");
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

	$('.copy-modus').magnificPopup({
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
				$("#modus_id").val("");
			}
		}
	});
	
	$(".copy-modus").click(function() {
			$("#modus_id").val(this.dataset.id);
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

	$("#add_link").click(function() {
		var row;
		var table_name;		
		ctr++;
		
		row = '<tr>\
				<td><select id="department-select'+ctr+'" name="department_id_link[]" class="form-control" required>\
					<option value="" disabled selected hidden>Department</option>\
						<?php if(!empty($this->departments)) { foreach($this->departments as $department) { ?>
						  <option value="<?php echo $department['category_id']; ?>"><?php echo $department['category_name']; ?></option>\
						 <?php } } ?>
					</select>\
				</td>\
				<td><select id="kejadian-select'+ctr+'" name="kejadian_id_link[]"  class="form-control" required>\
						<option value="" disabled selected hidden>Kejadian</option>\
					</select>\
				</td>\
				<td><select id="modus-select'+ctr+'" name="modus_id_link[]"  class="form-control" required>\
						<option value="" disabled selected hidden>Modus</option>\
					</select>\
				</td>\
				<td><i class="fa fa-trash remove-link" style="margin:5px; cursor:pointer;" onclick="$(this).closest(\'tr\').remove();"></i></td>\
			</tr>';
		
		$( "#modus-linked-table").append(row);

		$("#department-select"+ctr).change(function() {
			if($( this ).val() > 0)
			{			
				$('body').mLoading();
				$.ajax({
					url: "/admin/issuefinding/getkejadianbycategoryid",
					data: { category_id: $( this ).val()  }
				}).done(function(response) {
					$("#kejadian-select"+ctr).empty();
					var object = $.parseJSON(response);
					$("#kejadian-select"+ctr).append('<option value=""  disabled selected hidden>Select Kejadian</option>');
					$.each(object.data, function (item, value) {
						$("#kejadian-select"+ctr).append(new Option(value.kejadian, value.kejadian_id));
					});
					$("body").mLoading('hide');

					$("#kejadian-select"+ctr).change(function() {
						if($( this ).val() > 0)
						{			
							$('body').mLoading();
							$.ajax({
								url: "/admin/issuefinding/getmodusbykejadianid",
								data: { category_id: $("#department-select"+ctr).val(), kejadian_id: $("#kejadian-select"+ctr).val()  }
							}).done(function(response) {
								$("#modus-select"+ctr).empty();
								var object = $.parseJSON(response);
								$("#modus-select"+ctr).append('<option value=""  disabled selected hidden>Select Modus</option>');
								$.each(object.data, function (item, value) {
									$("#modus-select"+ctr).append(new Option(value.modus, value.modus_id));
								});
								$("body").mLoading('hide');
							});
						}
						else
						{
							$("#modus-select"+ctr).prop('required',false);
						}
					});

				});
			}
			else
			{
				$("#kejadian-select"+ctr).prop('required',false);
			}
		});
	});

	
});	
</script>