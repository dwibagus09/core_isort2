<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<a href="#email-form" id="add-email" class="add-btn" style="float:right;">
				  <i class="fa fa-plus "></i>
				  <span class="link-title">Add Email</span>
				</a>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th>Email Address</th>
								<th width="15%" style="text-align:center">CC</th>
								<th width="15%" style="text-align:center">Reminder</th>
								<th width="15%" style="text-align:center">Review</th>
								<th width="150" style="text-align:center">Action</th>
							</tr>
						</thead>
					</table>
					<?php if(!empty($this->email)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">
						<tbody>
						<?php
							$i = 1;
							foreach($this->email as $email) {
						?>
							<tr>
								<td><?php echo $email['email']; ?></td>
								<td width="15%"  align="center"><?php if($email['cc'] == '1') echo "&#x2714"; ?></td>
								<td width="15%"  align="center"><?php if($email['reminder'] == '1') echo "&#x2714"; ?></td>
								<td width="15%"  align="center"><?php if($email['review'] == '1') echo "&#x2714"; ?></td>
								<td width="150"  align="center"><a href="#email-form" class="edit-email action-btn" data-id="<?php echo $email['email_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-email" data-id="<?php echo $email['email_id']; ?>"><i class="fa fa-eraser" ></i></a><br/>
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

<!-- email form -->
  <form action="" id="email-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="email_id" id="email_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Email</label><br/>
	<input type="text" name="email" id="email" style="width:100%;" class="form-control"><br/><br/>
	<input type="checkbox" id="cc" name="cc" value="1"> CC<br/>
	<input type="checkbox" id="reminder" name="reminder" value="1"> Reminder<br/>
	<input type="checkbox" id="review" name="review" value="1"> Review<br/><br/>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 80px; width: 100px;">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category; ?> li.ap-reminder-email").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");
	
	var selectedID;
	$('#add-email').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#email',
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
	
	$('.edit-email').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-email').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#email',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/actionplan/getemailbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#email_id").val(resp.email_id);
					$("#email").val(resp.email);
					if(resp.cc == "1") $("#cc").prop('checked', true);
					if(resp.reminder == "1") $("#reminder").prop('checked', true);
					if(resp.review == "1") $("#review").prop('checked', true);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#email-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/saveemail',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/actionplan/reminder/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$('.delete-email').click(function() {
		var res = confirm("Are you sure you want to delete this email?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/actionplan/deleteemailbyid",
				data: { id : this.dataset.id, c: '<?php echo $this->category_id; ?>' }
			}).done(function(response) { 
				location.href="/admin/actionplan/reminder/c/<?php echo $this->category_id; ?>";
			});
		}
		
	});

});	
</script>