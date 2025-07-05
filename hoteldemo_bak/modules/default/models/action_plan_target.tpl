<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<!-- page content -->
<div class="right_col" role="main">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<a class="add-target" href="#popup-form"><input type="button" value="Add Target" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <th width="100">Module</th>
				  <th width="150">Target</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->target))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->target as $target) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?></th>
				  <td class="date-column"><?php echo $target['module_name']; ?></td>
				  <td class="date-column"><?php echo $target['target_name']; ?></td>
				  <td class="action-column">
					<a class="add-target" href="#popup-form" data-id="<?php echo $target['action_plan_target_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a><br/><i class="fa fa-trash remove-event" data-id="<?php echo $target['action_plan_target_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
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

<!-- Add Target form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="action_plan_target_id" id="action_plan_target_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Module</label><br/>
	<select id="action_plan_module_id" name="action_plan_module_id">
		<?php if(!empty($this->modules)) { foreach($this->modules as $module) { ?>
			<option value="<?php echo $module['action_plan_module_id']; ?>"><?php echo $module['module_name']; ?></option>
		<?php } } ?>
	</select><br/><br/>
	<label for="name">Target</label><br/>
	<textarea rows="3" cols="30" name="target_name" id="target_name"></textarea><br/><br/>
	<label for="name">Sort Order</label><br/>
	<input type="text" name="sort_order" id="sort_order"><br/><br/>
	<input type="submit" class="submit-btn" id="add-target-submit" name="add-target-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var report_date;
	
	$('.add-target').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_name',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_target_id").val(obj.action_plan_target_id);
			}
		}
	});
	
	$(".add-target").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit Target");
			$.ajax({
				url: "/default/actionplan/gettargetbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#action_plan_target_id").val(obj.action_plan_target_id);
				$("#action_plan_module_id").val(obj.action_plan_module_id);
				$("#target_name").val(obj.target_name);
				$("#sort_order").val(obj.sort_order);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Target");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/savetarget',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/target/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$(".remove-event").click(function() {
		var res = confirm("Are you sure you want to delete this event?");
		if(res == true)
		{
			location.href="/default/actionplan/deletetargetbyid/id/"+this.dataset.id+"/c/<?php echo $this->category_id; ?>";
		}
	});
	
});
</script>