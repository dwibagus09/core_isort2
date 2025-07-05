<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<style>
.mfp-auto-cursor .mfp-content {
	width:500px!important;
}
</style>

<div id="content">
	<div class="outer">
		<div class="inner bg-light lter">
			<div class="col-lg-12">
				<div style="float:right;">
					<a href="#target-form" id="add-target" class="add-btn" style="float:right;">
						<i class="fa fa-plus "></i>
						<span class="link-title">Add Target</span>
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
								<th width="35%">Module</th>
								<th>Target</th>
								<th width="70">Order</th>
								<th width="110" align="center" width="150">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->target)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">
						<tbody>
						<?php
							$i = 1;
							foreach($this->target as $target) {
						?>
							<tr id="<?php echo $target['action_plan_target_id']; ?>" <?php if($this->id == $target['action_plan_target_id']) { ?>style="background-color:none;"<?php } ?> >
								<td width="70"><?php echo $target['show_year']; ?></td>
								<td width="35%"><?php echo $target['module_name']; ?></td>
								<td><?php echo $target['target_name']; ?></td>
								<td width="70"><?php echo $target['sort_order']; ?></td>
								<td width="110"><a href="#target-form" class="edit-target action-btn" data-id="<?php echo $target['action_plan_target_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-target" data-id="<?php echo $target['action_plan_target_id']; ?>"><i class="fa fa-eraser" ></i></a>
									<a class="action-btn copy-target" href="#copy-form" data-id="<?php echo $target['action_plan_target_id']; ?>" style="cursor:pointer;"><i class="fa fa-copy" style="font-size:18px;" ></i></a><br/>
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
  <form action="" id="target-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="action_plan_target_id" id="action_plan_target_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" /><br/>
	<label for="name">Module</label><br/>
	<select id="action_plan_module_id" name="action_plan_module_id" style="width:450px;" class="form-control">
		<?php if(!empty($this->modules)) { foreach($this->modules as $module) { ?>
			<option value="<?php echo $module['action_plan_module_id']; ?>"><?php echo $module['show_year']." - ".$module['module_name']; ?></option>
		<?php } } ?>
	</select>
	<label for="name">Target</label><br/>
	<textarea rows="3"  style="width: 100%;" name="target_name" id="target_name"></textarea>
	<label for="name">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" style="width: 100%;">
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 180px; width: 100px;">
  </form>

<!-- Copy target form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Target to other sites</h4>
	<input type="hidden" name="action_plan_target_id" id="action_plan_target_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
	<input type="hidden" name="year" id="year" value="<?php echo $this->selectedYear; ?>" />
	<br/>
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
	$("#menu-<?php echo $this->category; ?> li.ap-target").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");
	
	var selectedID;
	$('#add-target').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target',
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
	
	$('.edit-target').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-target').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/actionplan/gettargetbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#action_plan_target_id").val(resp.action_plan_target_id);
					$("#action_plan_module_id").val(resp.action_plan_module_id);
					$("#target_name").val(resp.target_name);
					$("#year").val(resp.show_year);
					$("#sort_order").val(resp.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#target-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/savetarget',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/actionplan/viewtarget/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>/id/"+id+"?rand=<?php echo rand(); ?>#"+id;
			}
		});
	});
	
	$('.delete-target').click(function() {
		var res = confirm("Are you sure you want to delete this target?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/actionplan/deletetargetbyid",
				data: { id : this.dataset.id, c: '<?php echo $this->category_id; ?>'  }
			}).done(function(response) { 
				location.href="/admin/actionplan/viewtarget/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-target').magnificPopup({
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
				$("#action_plan_target_id").val("");
			}
		}
	});
	
	$(".copy-target").click(function() {
			$("#action_plan_target_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/copytarget',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				alert(response);
				location.href="/admin/actionplan/viewtarget/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			}
		});
	});

	$('#selectyear').change(function() {
		location.href="/admin/actionplan/viewtarget/c/<?php echo $this->category_id; ?>/y/"+$(this).val();
	});
});	
</script>