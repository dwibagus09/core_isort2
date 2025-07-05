<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle"><?php echo $this->category['category_name']; ?> Man Power</h2>

		<div style="margin-bottom:10px;">
			<a class="add-man-power" href="#popup-form"><input type="button" value="Add Man Power" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  	<th>Inhouse/Outsource</th>
					<th>Name</th>
					<th>Position</th>
					<th>Vendor</th>
				 	<th width="50">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->manPower))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->manPower as $manPower) { 
				?>
				<tr <?php if($manPower['active'] != "1") echo 'style="background-color:#ffd6d6;"'; ?>>
				  <td class="date-column"><?php echo ($manPower['inhouse_outsource']) ? 'Outsource' : 'In House'; ?></td>
					<td class="date-column"><?php echo $manPower['name']; ?></td>
					<td class="date-column"><?php echo $manPower['position']; ?></td>
					<td class="date-column"><?php echo $manPower['vendor']; ?></td>
				  	<td class="action-column">
						<a class="add-man-power" href="#popup-form" data-id="<?php echo $manPower['manpower_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
						<?php if($this->allowDeleteManPower) { ?><i class="fa fa-trash remove-manpower" data-id="<?php echo $manPower['manpower_id']; ?>" style="font-size:20px; cursor:pointer;" ></i><?php } ?>
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

<!-- Add man power form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="manpower_id" id="manpower_id" /><input type="hidden" name="c" id="c" value="<?php echo $this->category['category_id']; ?>" /><br/>
	<label for="name">Inhouse/Outsource</label><br/>
	<select id="inhouse_outsource" name="inhouse_outsource" class="form-control col-md-7 col-xs-12">
		<option value="0">Inhouse</option>
		<option value="1">Outsource</option>
	</select><br/><br/>
	<label for="name">Name</label><br/>
	<input type="text" id="name" name="name" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="year_of_birth">Year of Birth (YYYY)</label><br/>
	<input type="text" id="year_of_birth" name="year_of_birth" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="join_year">Join Year (YYYY)</label><br/>
	<input type="text" id="join_year" name="join_year" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="position">Position</label><br/>
	<input type="text" id="position" name="position" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="vendor">Vendor</label><br/>
	<input type="text" id="vendor" name="vendor" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="year_start_exp">Year Start Experience</label><br/>
	<input type="text" id="year_start_exp" name="year_start_exp" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<label for="certificate_no">Certificate No</label><br/>
	<input type="text" id="certificate_no" name="certificate_no" class="form-control col-md-7 col-xs-12" autocomplete="off"><br/><br/>
	<input type="checkbox" id="active" name="active" value="1"> Active<br/><br/>
	<input type="submit" class="submit-btn" id="add-man-power-submit" name="add-man-power-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var report_date;

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('.add-man-power').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#schedule_date',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#manpower_id").val("");
			}
		}
	});
	
	$(".add-man-power").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit Man Power");
			$.ajax({
				url: "/default/manpower/getmanpowerbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#manpower_id").val(obj.manpower_id);
				$("#inhouse_outsource").val(obj.inhouse_outsource);
				$("#name").val(obj.name);
				$("#year_of_birth").val(obj.year_of_birth);
				$("#join_year").val(obj.join_year);
				$("#position").val(obj.position);
				$("#vendor").val(obj.vendor);
				$("#year_start_exp").val(obj.year_start_exp);
				$("#certificate_no").val(obj.certificate_no);
				if(obj.active == '1')
				{
					$('#active').prop('checked', true);
				}
				else
				{
					$('#active').prop('checked', false);
				}
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Man Power");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/manpower/savemanpower',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/manpower/view/c/<?php echo $this->category['category_id']; ?>";
			}
		});
	});
	
	$(".remove-manpower").click(function() {
		var res = confirm("Are you sure you want to delete this man power?");
		if(res == true)
		{
			location.href="/default/manpower/deletemanpowerbyid/id/"+this.dataset.id+"/c/<?php echo $this->category['category_id']; ?>";
		}
	});
	
});
</script>