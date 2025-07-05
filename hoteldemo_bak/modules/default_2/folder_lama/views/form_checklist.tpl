<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form class="form-label-left" action="/default/checklist/savechecklist" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="checklist_id" name="checklist_id" type="hidden" value="<?php echo $this->checklistDetail['checklist_id']; ?>">
			<div class="x_title">
				<h2 class="page-title"><?php echo $this->title; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="template_id">Checklist</label>
					<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
						<select id="template_id" name="template_id" class="form-control" required>
							<?php if(!empty($this->templates)) { 
								foreach($this->templates as $template) {
							?>
							<option value="<?php echo $template['template_id']; ?>" <?php if($template['template_id'] == $this->checklistDetail['template_id']) echo "selected"; ?>><?php echo $template['template_name']; ?></option>
							<?php } } ?>		
						</select>
					</div>
				</div>
				<br/>
				<div class="item form-group" style="clear:both;">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no">Room Number</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<select id="room_no" name="room_no" class="form-control" required>
							<?php if(!empty($this->rooms)) { 
								foreach($this->rooms as $room) {
							?>
							<option value="<?php echo $room['floor']; ?>" <?php if($room['floor'] == $this->checklistDetail['room_no']) echo "selected"; ?>><?php echo $room['floor']; ?></option>
							<?php } } ?>		
						</select>
					</div>
				</div>
				<br/>
				
			
			<div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:250px;">Save &amp; go to the next page</button>
				</div>
			  </div>
		  </div>
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
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	<?php if($this->err == 1) { ?>
	alert("Checklist template is already exist, please use the existing one.");
	<?php } ?>
	
	<?php if($this->err == 2) { ?>
	alert("Room Number does not exist. Please type the correct room number.");
	<?php } ?>
	
	$( "#template_id" ).change(function() {
		$.ajax({
			url: "/checklist/getroomsbytemplateid/id/"+$(this).val(),
			success: function(response){
				var resp = jQuery.parseJSON(response);
				$('#room_no').empty();
				$.each(resp, function(key, val) {
					$('#room_no').append($("<option value='"+ val.floor +"'>"+val.floor+"</option>"));
				}); 	
			}
		});	
		
	});
	
});	
</script>