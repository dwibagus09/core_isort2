<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		  <h1 class="pagetitle"><?php if($this->category_id == 6) echo "Preventive Maintenance"; else echo "Action Plan"; ?> Reschedule Approval List</h1>
		  <div class="table-auto-scroll">
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="50">No</th>
				  <?php /*<th width="100">Department</th>*/ ?>
				  <th width="200">Module</th>
				  <th width="200">Target</th>
				  <th>Activity</th>
				  <th>Original Date</th>
				  <th>Reschedule Date</th>
				  <th>Reason</th>
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->rescheduleList))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->rescheduleList as $list) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $i; ?></th>
				  <?php /*<td class="date-column"><?php echo $list['category_name']; ?></th> */ ?>
				  <td class="date-column"><?php echo $list['module_name']; ?></td>
				  <td class="date-column"><?php echo $list['target_name']; ?></td>
				  <td class="date-column"><?php echo $list['activity_name']; ?></td>
				  <td class="date-column"><?php echo $list['original_date']; ?></td>
				  <td class="date-column"><?php echo $list['reschedule_date']; ?></td>
				  <td class="date-column"><?php echo $list['remark']; ?></td>
				  <td class="action-column">
					<i class="fa fa-thumbs-up approve-reschedule" data-id="<?php echo $list['reschedule_id']; ?>" style="font-size:20px; cursor:pointer;" ></i><br/>
					<i class="fa fa-times reject-reschedule" data-id="<?php echo $list['reschedule_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
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
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {	
	$(".approve-reschedule").click(function() {
		var res = confirm("Are you sure you want to approve this?");
		if(res == true)
		{
			location.href="/default/actionplan/approvereschedule/id/"+this.dataset.id;
		}
	});
	
	$(".reject-reschedule").click(function() {
		var res = confirm("Are you sure you want to reject this?");
		if(res == true)
		{
			location.href="/default/actionplan/rejectreschedule/id/"+this.dataset.id;
		}
	});
});
</script>