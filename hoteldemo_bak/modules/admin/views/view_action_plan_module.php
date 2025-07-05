<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<div style="float:right;">
					<a href="#module-form" id="add-module" class="add-btn" style="float:right;">
					<i class="fa fa-plus "></i>
					<span class="link-title">Add Module</span>
					</a>
					<div class="year-field">
						Select Year:
						<select id="selectyear">
							<?php for($year = 2022; $year <= (date("Y")+1); $year++) { ?>
							<option value="<?php echo $year; ?>" <?php if($year == $this->selectedYear) echo "selected"; ?>><?php echo $year; ?></option>
							<?php } ?>
						</select>
					</div>
				</div>
				<h3 class="page-title"><?php echo $this->title; ?></h3>
				<div id="stripedTable" class="body collapse in">
					<table class="table table-striped responsive-table">
						<thead>
							<tr>
								<th width="70">Year</th>
								<th>Module</th>
								<th width="70">Order</th>
								<?php /*<th>Total Bobot</th>*/ ?>
								<th width="110">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->modules)) { ?>		
					<div class="table_body">
						<table class="table table-striped responsive-table">	
						<tbody>
						<?php
							$i = 1;
							foreach($this->modules as $module) {
						?>
							<tr>
								<td width="70"><?php echo $module['show_year']; ?></td>
								<td><?php echo $module['module_name']; ?></td>
								<td width="70"><?php echo $module['sort_order']; ?></td>
								<?php /*<td><?php echo $module['total_bobot']; ?>%</td>*/ ?>
								<td width="110"><a href="#module-form" class="edit-module action-btn" data-id="<?php echo $module['action_plan_module_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-module" data-id="<?php echo $module['action_plan_module_id']; ?>"><i class="fa fa-eraser" ></i></a>
									<a class="action-btn copy-module" href="#copy-form" data-id="<?php echo $module['action_plan_module_id']; ?>" style="cursor:pointer;"><i class="fa fa-copy" style="font-size:18px;" ></i></a><br/>
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

<!-- module form -->
  <form action="" id="module-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="action_plan_module_id" id="action_plan_module_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Year</label><br/>
	<select id="year" name="show_year" style="width: 100%;" class="form-control">
		<?php for($y=2020; $y<(date("Y")+2); $y++) { ?>
			<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
		<?php } ?>
	</select>
	<label for="name">module</label><br/>
	<textarea rows="3" style="width: 100%;" name="module_name" id="module_name"></textarea>
	<label for="name">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" style="width: 100%;">
	<?php /*<label for="name">Total Bobot</label><br/>
	<input type="text" name="total_bobot" id="total_bobot">% */ ?>
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 130px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Module to other sites</h4>
	<input type="hidden" name="action_plan_module_id" id="action_plan_module_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn btn btn-primary" id="copy-module-submit" name="copy-module-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category; ?> li.ap-module").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");
	
	var selectedID;
	$('#add-module').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#module',
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
	
	$('.edit-module').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-module').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#module',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/actionplan/getmodulebyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#action_plan_module_id").val(resp.action_plan_module_id);
					$("#module_name").val(resp.module_name);
					$("#year").val(resp.show_year);
					$("#sort_order").val(resp.sort_order);
					$("#total_bobot").val(resp.total_bobot);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#module-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/savemodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/actionplan/viewmodule/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			}
		});
	});
	
	$('.delete-module').click(function() {
		var res = confirm("Are you sure you want to delete this module?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/actionplan/deletemodulebyid",
				data: { id : this.dataset.id, c: '<?php echo $this->category_id; ?>' }
			}).done(function(response) { 
				location.href="/admin/actionplan/viewmodule/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-module').magnificPopup({
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
				$("#action_plan_module_id").val("");
			}
		}
	});
	
	$(".copy-module").click(function() {
			$("#action_plan_module_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/copymodule',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/actionplan/viewmodule/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			}
		});
	});

	$('#selectyear').change(function() {
		location.href="/admin/actionplan/viewmodule/c/<?php echo $this->category_id; ?>/y/"+$(this).val();
	});
});	
</script>