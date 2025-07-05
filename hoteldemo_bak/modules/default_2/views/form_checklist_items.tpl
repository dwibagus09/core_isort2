<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form id="checklist-form" class="form-label-left" action="/default/checklist/savechecklistitems" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="checklist_id" name="checklist_id" type="hidden" value="<?php echo $this->checklist['checklist_id']; ?>">
			<input id="position" name="position" type="hidden" value="<?php echo $this->position; ?>">
			<input id="pos_off" name="pos_off" type="hidden" value="<?php echo $this->posOff ?>">
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
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no">Date</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->checklist['checklist_date']; ?>
					</div>
				</div>
				<br/>
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no">Checked By</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->position; ?>
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
					<?php if($this->position == "Spv") { ?>
					<tr>
						<th style="text-align:left" colspan="2">Checked By</th>
						<th>Staff</th>
						<th colspan="2">Spv</th>
					</tr>
					<?php } ?>
					<?php foreach($this->items as $item) {
						if($item['category_id'] != $category_id)
						{
					?>
						<tr class="checklist-categories">
							<td colspan="<?php if($this->position == "Spv") echo "5"; else echo "4"; ?>"><?php echo $item['category_name']; ?></td>
						</tr>
					<?php $category_id = $item['category_id']; } ?>
					<?php if($item['subcategory_id'] != $subcategory_id)
						{
					?>
						<tr class="checklist-subcategories">
							<td colspan="<?php if($this->position == "Spv") echo "5"; else echo "4"; ?>"><?php echo $item['subcategory_name']; ?></td>
						</tr>
					<?php $subcategory_id = $item['subcategory_id']; } ?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" name="item_id[<?php echo $item['item_id']; ?>]" value="<?php echo $item['item_id']; ?>"></td>
							<td><?php echo $item['item_name']; ?><input type="hidden" name="item_name[<?php echo $item['item_id']; ?>]" value="<?php echo $item['item_name']; ?>"></td>
							<?php if($this->position == "Spv") { ?>
								<td align="center"><?php if($item['condition_staff'.$this->posOff]== 1) echo "&#10004;"; else if($item['condition_staff'.$this->posOff]== 2) echo "&#10060;"; ?></td>
							<?php } ?>
							<td align="right" width="70"><input type="radio" id="ok<?php echo $item['item_id']; ?>" name="item_condition[<?php echo $item['item_id']; ?>]" value="1" <?php if($item['condition']== 1) echo "checked"; elseif($item['condition']== 2) echo "disabled"; ?>> <label for="ok<?php echo $item['item_id']; ?>">Ok</label></td>
							<td align="center" width="180"><a <?php if($item['condition']!= 2) { ?>href="#kaizen-form"<?php } ?> class="not_ok_rb" data-id="<?php echo $item['item_id']; ?>"  data-cat="<?php echo $item['category_name']; ?>"  data-itemname="<?php echo $item['item_name']; ?>" data-subcat="<?php echo $item['subcategory_name']; ?>" ><input type="radio" id="not_ok<?php echo $item['item_id']; ?>" name="item_condition[<?php echo $item['item_id']; ?>]" value="2" <?php if($item['condition']== 2) echo "checked"; ?>> <label for="not_ok<?php echo $item['item_id']; ?>">Need to be repaired</label></a></td>
						</tr>
					<?php $i++; } ?>
				</table>
				</div>
				<?php } ?>
			
			<div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:250px;">Save</button>
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

<form id="kaizen-form" action=""  method="post" enctype="multipart/form-data" style="padding: 20px; border-radius: 10px;" class="mfp-hide white-popup-block">	
	<h1 class="page-title" style="margin-left: 0px !important; border-bottom: 1px solid; margin-bottom: 20px;">Submit Kaizen</h1>
	<div id="kaizen-field" class="col-md-12 col-sm-12 col-xs-12">									
		<label for="picture-kaizen" id="picture-kaizen-label">
			<div><img width="30" src="/images/camera.png" /><br/>Upload Picture</div>	
		</label>
		<input id="picture-kaizen" name="picture" type="file" accept="image/*" capture="capture" />	
	</div>
	<div id="kaizen-image-holder"></div>
	<input name="type_id" type="hidden" value="<?php echo $this->type_id; ?>" />	
	<input id="checklist_item_id" name="checklist_item_id" type="hidden" value="" />	
	<input id="kaizen-location" name="location" type="hidden" value="" />	
	<?php if(!empty($this->kaizenCategories)) { ?>
		<div id="kaizen-category-field">
			Department:<br/>
			<select id="kaizen-category-select" name="category" required>
			<option value="" disabled selected hidden>Select Department</option>
			<?php foreach($this->kaizenCategories as $category) { ?>
			  <option value="<?php echo $category['category_id']; ?>" <?php if($category['category_id'] == $this->defaultCategory) echo "selected"; ?>><?php echo $category['category_name']; ?></option>
			 <?php } ?>
			</select><br/>
		</div>
	<?php } ?>
	<div id="kaizen-area-field">
			Area:<br/>
			<select id="kaizen-area-select" name="area">
				<option value="" disabled selected hidden>Select Area</option>
				<?php if(!empty($this->area)) {
					foreach($this->area as $area) { ?>
						<option value="<?php echo $area['area_id']; ?>" <?php if($area['area_id'] == $this->area_id) echo "selected"; ?>><?php echo $area['area_name']; ?></option>
				<?php } } ?>
			</select>
		</div>
	<div id="kaizen-floor-field">
		Floor:<br/>
		<select id="kaizen-floor-select" name="floor_id">
			<option value="" disabled selected hidden>Select Floor</option>
			<?php if(!empty($this->curFloor)) {
				foreach($this->curFloor as $floor) { ?>
					<option value="<?php echo $floor['floor_id']; ?>" <?php if($floor['floor_id'] == $this->floor_id) echo "selected"; ?>><?php echo $floor['floor']; ?></option>
			<?php } } ?>
		</select>
	</div>
	<?php /*<div id="kaizen-location-field">
		Location:<br/>
		<textarea rows="2" cols="50" id="kaizen-location-txtarea" name="location" required></textarea><br/>
	</div> */ ?>
	<div id="kaizen-incident-field">
		Incident:<br/>
		<select id="kaizen-incident-select" name="incident_id">
			<option value="" disabled selected hidden>Select Incident</option>
			<?php if(!empty($this->incident)) {
				foreach($this->incident as $incident) { ?>
					<option value="<?php echo $incident['kejadian_id']; ?>" <?php if($incident['kejadian_id'] == $this->kejadian_id) echo "selected"; ?>><?php echo $incident['kejadian']; ?></option>
			<?php } } ?>
		</select>
	</div>
	<div id="kaizen-modus-field">
		Modus:<br/>
		<select id="kaizen-modus-select" name="modus_id">
			<option value="" disabled selected hidden>Select Modus</option>
		</select>
	</div>
	<div id="kaizen-discussion-field">
		Detail:<br/>
		<textarea rows="4" cols="50"  id="kaizen-discussion-txtarea" name="description" required></textarea><br/>
	</div>	
	<div id="kaizen-button-field">
		<input type="button" id="kaizen-cancel-issue" name="cancel-issue" value="Cancel" class="form-btn" /> <input type="submit" id="kaizen-issue-submit" name="issue-submit" value="Submit" class="form-btn">
	</div>
</form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#digital-checklist-menu").addClass("active");
	$("#digital-checklist-menu .child_menu").show();
	
	var curItemId = 0;
	var kejadian_id = 0;
	var modus_id = 0;

	setInterval(function(){ 
		$.ajax({
			url: '/default/checklist/savechecklistitems',
			type: 'POST',
			data: $('#checklist-form').serialize(),
			success: function(id) {
			
			}
		});
	}, 60000);
	
	$('.not_ok_rb').click(function() {
		var item_id = curItemId = $(this)[0].dataset.id;
		$('#checklist_item_id').val(item_id);
		if($(this)[0].dataset.subcat != "") { var cat =  $(this)[0].dataset.subcat; }
		else { var cat = $(this)[0].dataset.cat; }
		var item_name = $(this)[0].dataset.itemname;
		$.ajax({
			url: "/default/issue/getkejadianbyname",
			data: { kejadian_name : cat, category_id: <?php echo $this->defaultCategory; ?>, template_id: <?php echo $this->checklist['template_id']; ?>  }
		}).done(function(response) {
			$('#kaizen-incident-select').val(response);
			if(response > 0)
			{
				kejadian_id = response;
				$.ajax({
					url: "/default/issue/getmodusbykejadianid",
					data: { kejadian_id : response, category_id: <?php echo $this->defaultCategory; ?>  }
				}).done(function(response2) {
					$("#kaizen-modus-select").empty();
					var object = $.parseJSON(response2);
					$("#kaizen-modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
					$.each(object, function (item, value) {
						$("#kaizen-modus-select").append(new Option(value.modus, value.modus_id));
					});
					$('#kaizen-modus-select').val(item_name);
					
					$.ajax({
						url: "/default/issue/getmodusbyname",
						data: { modus_name : item_name, category_id: <?php echo $this->defaultCategory; ?>, kejadian_id: response  }
					}).done(function(response3) {
						modus_id = response3;
						$('#kaizen-modus-select').val(response3);
					});
				});
			}
		});
	});
	
	$('.not_ok_rb').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#modus',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#issue-form").hide();
				$("#kaizen-field").show();
				$("#kaizen-form")[0].reset();
				$("#kaizen-image-holder").html("");
				function filePreview2(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.addEventListener('load', function() {
							$("#kaizen-image-holder").html("");
							$("<img />", {
								"src": reader.result,
										"class": "thumb-image"
								}).appendTo("#kaizen-image-holder");
						});
						reader.readAsDataURL(input.files[0]);
					}
				}

				$( "#picture-kaizen" ).change(function() {
					$("#kaizen-field").hide();
					filePreview2(this);
				});
				
				$( "#kaizen-cancel-issue" ).click(function() {
					$( "#issue-form").show();
					$.magnificPopup.close();
				});
				
				$("#kaizen-category-select").change(function() {
					var cat_id = $( this ).val();
					$('body').mLoading();
					$("#kaizen-area-field").show();
					$.ajax({
						url: "/default/issue/getlocationbyareaid",
						data: { area_id :  $("#kaizen-area-select").val(), cat_id : cat_id  }
					}).done(function(response) {
						var object = $.parseJSON(response);
						$("#kaizen-floor-select").empty();
						$("#kaizen-floor-select").append('<option value=""  disabled selected hidden>Select Location</option>');
						$.each(object, function (item, value) {
							if(value.disable == 1) $("#kaizen-floor-select").append('<option value="'+value.floor_id+'"  disabled class="select-option-disable">'+value.floor+'</option>');
							else $("#kaizen-floor-select").append(new Option(value.floor, value.floor_id));
						});			
						$("#kaizen-floor-select").val(<?php echo $this->floor_id; ?>);
						$("#kaizen-floor-field").show();
						$("body").mLoading('hide');
					});
					
					$.ajax({
						url: "/default/issue/getincidentbyissuetypeid",
						data: { issue_type : <?php echo $this->type_id; ?>, category_id: cat_id  }
					}).done(function(response) {
						curIncidents = [];
						if(response == "[]")
						{
							$("#kaizen-incident-field").hide();
							$("#kaizen-modus-field").hide();
							$("#kaizen-incident-select").prop('required',false);
							$("#kaizen-modus-select").prop('required',false);
						} else {
							$("#kaizen-incident-field").show();
							$("#kaizen-incident-select").prop('required',true);
							$("#kaizen-incident-select").empty();
							var object = $.parseJSON(response);
							$("#kaizen-incident-select").append('<option value=""  disabled selected hidden>Select Incident</option>');
							$.each(object, function (item, value) {
								$("#kaizen-incident-select").append(new Option(value.kejadian, value.kejadian_id));
								curIncidents[value.kejadian_id] = value.show_pelaku_checkbox;
							});
							$("#kaizen-incident-select").val(kejadian_id);
							
							$.ajax({
								url: "/default/issue/getmodusbykejadianid",
								data: { kejadian_id : kejadian_id, category_id: cat_id  }
							}).done(function(response) {
								$("#kaizen-modus-select").empty();
								var object = $.parseJSON(response);
								$("#kaizen-modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
								$.each(object, function (item, value) {
									$("#kaizen-modus-select").append(new Option(value.modus, value.modus_id));
								});
								$("#kaizen-modus-select").val(modus_id);
								$("#kaizen-modus-field").show();
								$("body").mLoading('hide');
							});
						}
						$("body").mLoading('hide');
					});
				});
				
				$("#kaizen-area-select").change(function() {
					var area_id = $( this ).val();
					
					$('body').mLoading();
					$.ajax({
						url: "/default/issue/getlocationbyareaid",
						data: { area_id :  area_id, cat_id : $("#kaizen-category-select").val() <?php /*echo $this->defaultCategory; */ ?>  }
					}).done(function(response) {
						var object = $.parseJSON(response);
						$("#kaizen-floor-select").empty();
						$("#kaizen-floor-select").append('<option value=""  disabled selected hidden>Select Location</option>');
						$.each(object, function (item, value) {
							if(value.disable == 1) $("#kaizen-floor-select").append('<option value="'+value.floor_id+'"  disabled class="select-option-disable">'+value.floor+'</option>');
							else $("#kaizen-floor-select").append(new Option(value.floor, value.floor_id));
						});			
						$("#kaizen-floor-field").show();
						$("body").mLoading('hide');
					});
					
				});
				
				$("#kaizen-incident-select").change(function() {
					if($( this ).val() > 0)
					{			
						$('body').mLoading();
						$("#kaizen-modus-select").prop('required',true);
						$.ajax({
							url: "/default/issue/getmodusbykejadianid",
							data: { kejadian_id : $( this ).val(), category_id: $("#kaizen-category-select").val() <?php /*echo $this->defaultCategory;*/ ?>  }
						}).done(function(response) {
							$("#kaizen-modus-select").empty();
							var object = $.parseJSON(response);
							$("#kaizen-modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
							$.each(object, function (item, value) {
								$("#kaizen-modus-select").append(new Option(value.modus, value.modus_id));
							});
							$("#kaizen-modus-field").show();
							$("body").mLoading('hide');
						});
					}
					else
					{
						$("#kaizen-modus-select").prop('required',false);
					}
				});
			},
			close: function() {	
				$( "#issue-form").show();
				$("#kaizen-image-holder").html("");
				kejadian_id = 0;
				modus_id = 0;
			}
		}
	});
	
	$('#kaizen-form').on('submit', function(event){
		event.preventDefault();
		$("body").mLoading();
		$.ajax({
			url: '/default/issue/submitissue',
			type: 'POST',
			data: new FormData($('#kaizen-form')[0]),
			cache: false,
			contentType: false,
			processData: false,

			xhr: function () {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('progress', function (e) {
					if (e.lengthComputable) {
						$('progress').attr({
						value: e.loaded,
						max: e.total
						});
					}
					}, false);
				}
				return myXhr;
			},
			success: function(response) {				
				if(response > 0)
				{
					$.ajax({
						url: "/default/checklist/updateissue",
						data: { item_id : curItemId, issue_id : response  }
					}).done(function(response) {
						$("#not_ok"+$('#checklist_item_id').val()).prop("checked", true);
						$("#not_ok"+$('#checklist_item_id').val()).parent('a').attr('href','');
						$("#ok"+$('#checklist_item_id').val()).prop("disabled", true);
						$.magnificPopup.close();		
						$("#kaizen-form")[0].reset();
						$("#kaizen-image-holder").html("");
						curItemId = 0;			
						kejadian_id = 0;
						modus_id = 0;						
						$("body").mLoading('hide');
					});
				}
				else {
					curItemId = 0;
					$("body").mLoading('hide');
					alert("Submitting kaizen failed, please try again");
				}
			}
		});
	});
	
	$('#checklist-form').on('submit', function(event){
		event.preventDefault();
		$("body").mLoading();
		$.ajax({
			url: '/default/checklist/savechecklistitems',
			type: 'POST',
			data: $('#checklist-form').serialize(),
			success: function(id) {
				location.href="/default/checklist/view";
			}
		});
		
	});
});	
</script>