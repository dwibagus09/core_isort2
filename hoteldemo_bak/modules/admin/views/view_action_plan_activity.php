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
					<a href="#activity-form" id="add-activity" class="add-btn" style="float:right;">
					<i class="fa fa-plus "></i>
					<span class="link-title">Add Activity</span>
					</a>
					<div style="float: left; margin: 21px 10px;">
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
								<th width="50">Year</th>
								<th width="20%">Module</th>
								<th width="20%">Target</th>
								<th>Activity</th>
								<th align="center" width="130">Total Schedule</th>
								<th align="center" width="100">Sort Order</th>
								<th width="110">Action</th>
							</tr>
						</thead>	
					</table>
					<?php if(!empty($this->activity)) { ?>	
					<div class="table_body">
						<table class="table table-striped responsive-table">						
						<tbody>
						<?php
							$i = 1;
							foreach($this->activity as $activity) {
						?>
							<tr>
								<td width="50"><?php echo $activity['show_year']; ?></td>
								<td width="20%" class="date-column"><?php echo $activity['module_name']; ?></td>
								<td width="20%" class="date-column"><?php echo $activity['target_name']; ?></td>
								<td class="date-column"><?php echo $activity['activity_name']; ?></td>
								<td width="130" align="center" class="date-column"><?php echo $activity['total_schedule']; ?></td>
								<td width="100" align="center" class="date-column"><?php echo $activity['sort_order']; ?></td>
								<td width="110"><a href="#activity-form" class="edit-activity action-btn" data-id="<?php echo $activity['action_plan_activity_id']; ?>"><i class="fa fa-pencil-alt" ></i></a>
									<a class="action-btn delete-activity" data-id="<?php echo $activity['action_plan_activity_id']; ?>"><i class="fa fa-eraser" ></i></a>
									<a class="action-btn copy-activity" href="#copy-form" data-id="<?php echo $activity['action_plan_activity_id']; ?>" style="cursor:pointer;"><i class="fa fa-copy" style="font-size:18px;" ></i></a>
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

<!-- activity form -->
  <form action="" id="activity-form" class="mfp-hide white-popup-block" >
	<div id="err-msg"></div>
	<input type="hidden" name="action_plan_activity_id" id="action_plan_activity_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
	<input type="hidden" name="year" id="year" value="<?php echo $this->selectedYear; ?>" /><br/>
	<label for="name">Target</label><br/>
	<select id="action_plan_target_id" name="action_plan_target_id" style="width:450px;" class="form-control">
		<?php if(!empty($this->target)) { foreach($this->target as $target) { ?>
			<option value="<?php echo $target['action_plan_target_id']; ?>"><?php echo $target['target_name']; ?></option>
		<?php } } ?>
	</select>
	<label for="name">Activity</label><br/>
	<textarea rows="2" style="width: 100%;" name="activity_name" id="activity_name"></textarea>
	<label for="name">Total Schedule</label><br/>
	<input type="text" class="form-control" name="total_schedule" id="total_schedule" style="width: 100%;">
	<label for="name">Sort Order</label><br/>
	<input type="text" class="form-control" name="sort_order" id="sort_order" style="width: 100%;">
	<input type="submit" value="Save" class="btn btn-primary" style="margin: 10px 180px; width: 100px;">
  </form>

<!-- Copy activity form -->
<form action="" id="copy-form" class="mfp-hide white-popup-block" ><br/>
	<h4 id="form-title">Copy Activity to other sites</h4>
	<input type="hidden" name="action_plan_activity_id" id="action_plan_activity_id" />
	<input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
	<input type="hidden" name="year" id="year" value="<?php echo $this->selectedYear; ?>" /><br/>
	<label for="name">Select Site</label><br/>
		<?php foreach($this->sites as $site) { ?>
			<input type="checkbox" name="site_id[]" value="<?php echo $site['site_id']; ?>"> <?php echo $site['site_name']; ?><br>
		<?php } ?>
	<br/>
	<input type="submit" class="submit-btn btn btn-primary" id="copy-activity-submit" name="copy-activity-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {
	$("#menu-<?php echo $this->category; ?> .has-arrow").attr("aria-expanded", true);
	$("#menu-<?php echo $this->category; ?> li.ap-activity").addClass("active");
	$("#menu-<?php echo $this->category; ?> .collapse").addClass("in");

	var selectedID;
	$('#add-activity').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#activity',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#err-msg").hide();
				$('#activity-form')[0].reset();
				$("#action_plan_activity_id").val("");
			},
			close: function() {	
			
			}
		}
	});
	
	$('.edit-activity').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.edit-activity').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#activity',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = selectedID;
				$.ajax({
					url: "/admin/actionplan/getactivitybyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#action_plan_target_id").val(resp.action_plan_target_id);
					$("#action_plan_activity_id").val(resp.action_plan_activity_id);
					$("#module_name").val(resp.module_name);
					$("#activity_name").val(resp.activity_name);
					$("#total_schedule").val(resp.total_schedule);
					$("#sort_order").val(resp.sort_order);
				});
				$("#err-msg").hide();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#activity-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/saveactivity',
			type: 'POST',
			data: $(this).serialize(),
			success: function(id) {
				 location.href="/admin/actionplan/viewactivity/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			}
		});
	});
	
	$('.delete-activity').click(function() {
		var res = confirm("Are you sure you want to delete this activity?");
		if(res == true)
		{
			$.ajax({
				url: "/admin/actionplan/deleteactivitybyid",
				data: { id : this.dataset.id, c: '<?php echo $this->category_id; ?>', y: '<?php echo $this->selectedYear; ?>' }
			}).done(function(response) { 
				location.href="/admin/actionplan/viewactivity/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			});
		}
		
	});

	/*** COPY Form ***/

	$('.copy-activity').magnificPopup({
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
				$("#action_plan_activity_id").val("");
			}
		}
	});
	
	$(".copy-activity").click(function() {
			$("#action_plan_activity_id").val(this.dataset.id);
	});
	
	$('#copy-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/admin/actionplan/copyactivity',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				location.href="/admin/actionplan/viewactivity/c/<?php echo $this->category_id; ?>/y/<?php echo $this->selectedYear; ?>";
			}
		});
	});

	$('#selectyear').change(function() {
		location.href="/admin/actionplan/viewactivity/c/<?php echo $this->category_id; ?>/y/"+$(this).val();
	});
});	
</script>