<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<a class="add-email" href="#popup-form"><input type="button" value="Add Email" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <th width="250">Email Address</th>
					<th width="250">CC</th>
					<th width="250">Reminder</th>
					<th width="250">Review</th>
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->email))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->email as $email) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?></th>
				  <td class="date-column"><?php echo $email['email']; ?></td>
					<td class="date-column"><?php if($email['cc'] == '1') echo "&#x2714"; ?></td>
					<td class="date-column"><?php if($email['reminder'] == '1') echo "&#x2714"; ?></td>
					<td class="date-column"><?php if($email['review'] == '1') echo "&#x2714"; ?></td>
				  <td class="action-column">
					<a class="add-email" href="#popup-form" data-id="<?php echo $email['email_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a> <i class="fa fa-trash remove-event" data-id="<?php echo $email['email_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
				  </td>
				</tr>
				<?php
						$i++;
					}
				?>				
			  </tbody>
			<?php
				}
			?>
			</table>
					
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- Add email form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="email_id" id="email_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Email</label><br/>
	<input type="text" name="email" id="email" style="width:100%;"><br/><br/>
	<input type="checkbox" id="cc" name="cc" value="1"> CC<br/>
	<input type="checkbox" id="reminder" name="reminder" value="1"> Reminder<br/>
	<input type="checkbox" id="review" name="review" value="1"> Review<br/><br/>
	<input type="submit" class="submit-btn" id="add-email-submit" name="add-email-submit" value="Save">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var report_date;
	
	$('.add-email').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#email_name',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_email_id").val("");
			}
		}
	});
	
	$(".add-email").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit email");
			$.ajax({
				url: "/default/actionplan/getemailbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#email_id").val(obj.email_id);
				$("#email").val(obj.email);
				if(obj.cc == "1") $("#cc").prop('checked', true);
				if(obj.reminder == "1") $("#reminder").prop('checked', true);
				if(obj.review == "1") $("#review").prop('checked', true);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add email");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/saveemail',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/reminder/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$(".remove-event").click(function() {
		var res = confirm("Are you sure you want to delete this email?");
		if(res == true)
		{
			location.href="/default/actionplan/deleteemailbyid/id/"+this.dataset.id+"/c/<?php echo $this->category_id; ?>";
		}
	});
	
});
</script>