<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form id="checklist-form" class="form-label-left" action="/default/checklist/savechecklistitems" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="checklist_id" name="checklist_id" type="hidden" value="<?php echo $this->checklist['checklist_id']; ?>">
			<div class="x_title">
				<h2 class="page-title"><?php echo $this->title; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no">Room Number</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->checklist['room_no']; ?>
					</div>
				</div>
				<br/>
				<?php if(!empty($this->items)) { 
					$category_id = 0;
					$subcategory_id = 0;
					$i=1;
				?>
				<div class="item form-group">
				<table id="checklist-items">
					<tr>
						<th colspan="2" style="text-align:left;">Date</th>
						<th colspan="2"><?php echo $this->date1; ?></th>
						<th colspan="2"><?php echo $this->date2; ?></th>
						<th colspan="2"><?php echo $this->date3; ?></th>						
						<th rowspan="2">HOD</th>
					</tr>
					<tr>
						<th style="text-align:left" colspan="2">Checked By</th>
						<th>Staff<br/><?php if(!empty($this->user_staff)) echo "(".$this->user_staff.")"; ?></th>
						<th>Spv<br/><?php if(!empty($this->user_spv)) echo "(".$this->user_spv.")"; ?></th>
						<th>Staff<br/><?php if(!empty($this->user_staff2)) echo "(".$this->user_staff2.")"; ?></th>
						<th>Spv<br/><?php if(!empty($this->user_spv2)) echo "(".$this->user_spv2.")"; ?></th>
						<th>Staff<br/><?php if(!empty($this->user_staff3)) echo "(".$this->user_staff3.")"; ?></th>
						<th>Spv<br/><?php if(!empty($this->user_spv3)) echo "(".$this->user_spv3.")"; ?></th>
					</tr>
					<?php foreach($this->items as $item) {
						if($item['category_id'] != $category_id)
						{
					?>
						<tr class="checklist-categories">
							<td colspan="9"><?php echo $item['category_name']; ?></td>
						</tr>
					<?php $category_id = $item['category_id']; } ?>
					<?php if($item['subcategory_id'] != $subcategory_id)
						{
					?>
						<tr class="checklist-subcategories">
							<td colspan="9"><?php echo $item['subcategory_name']; ?></td>
						</tr>
					<?php $subcategory_id = $item['subcategory_id']; } ?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" name="item_id[<?php echo $item['item_id']; ?>]" value="<?php echo $item['checklist_item_id']; ?>"></td>
							<td><?php echo $item['item_name']; ?><input type="hidden" name="item_name[<?php echo $item['item_id']; ?>]" value="<?php echo $item['item_name']; ?>"></td>
							<td align="center"><?php if($item['condition_staff']== 1) echo "&#10004;"; else if($item['condition_staff']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv']== 1) echo "&#10004;"; else if($item['condition_spv']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_staff2']== 1) echo "&#10004;"; else if($item['condition_staff2']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv2']== 1) echo "&#10004;"; else if($item['condition_spv2']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_staff3']== 1) echo "&#10004;"; else if($item['condition_staff3']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv3']== 1) echo "&#10004;"; else if($item['condition_spv3']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if(!empty($item['hod_image_update'])) { ?><a class="image-popup-vertical-fit" href="<?php echo str_replace(".","_large.",$item['hod_image_update']); ?>"><img src="<?php echo str_replace(".","_thumb.",$item['hod_image_update']); ?>" height="80" /><?php } ?></a></td>
						</tr>
					<?php $i++; } ?>
				</table>
				</div>
				<?php } ?>
			
		  </form>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#digital-checklist-menu").addClass("active");
	$("#digital-checklist-menu .child_menu").show();
	
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
});	
</script>