<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<a class="add-module" href="#popup-form"><input type="button" value="Add Module" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <th>Module</th>
					<th width="100">Year</th>
					<th width="50">Order</th>
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->modules))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->modules as $module) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?></th>
				  <td class="date-column"><?php echo $module['module_name']; ?></td>
					<td class="date-column"><?php echo $module['show_year']; ?></td>
					<td class="date-column"><?php echo $module['sort_order']; ?></td>
				  <td class="action-column">
					<a class="add-module" href="#popup-form" data-id="<?php echo $module['action_plan_module_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
					<a class="copy-module" href="#copy-form" data-id="<?php echo $module['action_plan_module_id']; ?>" style="cursor:pointer;"><i class="fa fa-clone" style="font-size:18px;" ></i></a><br/>
					<i class="fa fa-trash remove-event" data-id="<?php echo $module['action_plan_module_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
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

<!-- Add module form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="action_plan_module_id" id="action_plan_module_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">module</label><br/>
	<textarea rows="3" cols="30" name="module_name" id="module_name"></textarea><br/><br/>
	<label for="name">Year</label><br/>
	<select id="year" name="show_year">
		<?php for($y=2019; $y<(date("Y")+1); $y++) { ?>
			<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
		<?php } ?>
	</select><br/><br/>
	<label for="name">Sort Order</label><br/>
	<input type="text" name="sort_order" id="sort_order"><br/><br/>
	<input type="submit" class="submit-btn" id="add-module-submit" name="add-module-submit" value="Submit">
  </form>

	<!-- Copy module form -->
  <form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title">Copy Module to Other Site</h2>
	<input type="hidden" name="action_plan_module_id" id="action_plan_module_id" /><br/>
	<label for="name">Site</label><br/>
	<select id="site_id" name="site_id" style="width: 400px;">
		<?php foreach($this->sites as $site) { ?>
			<option value="<?php echo $site['site_id']; ?>"><?php echo $site['site_name']; ?></option>
		<?php } ?>
	</select><br/><br/>
	<input type="submit" class="submit-btn" id="copy-module-submit" name="copy-module-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var report_date;
	
	$('.add-module').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#module_name',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#action_plan_module_id").val("");
			}
		}
	});
	
	$(".add-module").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit module");
			$.ajax({
				url: "/default/actionplan/getmodulebyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#action_plan_module_id").val(obj.action_plan_module_id);
				$("#module_name").val(obj.module_name);
				$("#sort_order").val(obj.sort_order);
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Module");
		}
	});
	
	$('#popup-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/savemodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/module/c/<?php echo $this->category_id; ?>";
			}
		});
	});

	/*** COPY Form ***/

	$('.copy-module').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#site_id',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#copy-form')[0].reset();
				$("#action_plan_module_id").val("");
			}
		}
	});
	
	$(".copy-module").click(function() {
			$("#action_plan_module_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/actionplan/copymodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/default/actionplan/module/c/<?php echo $this->category_id; ?>";
			}
		});
	});
	
	$(".remove-event").click(function() {
		var res = confirm("Are you sure you want to delete this module?");
		if(res == true)
		{
			location.href="/default/actionplan/deletemodulebyid/id/"+this.dataset.id+"/c/<?php echo $this->category_id; ?>";
		}
	});
	
});
</script>