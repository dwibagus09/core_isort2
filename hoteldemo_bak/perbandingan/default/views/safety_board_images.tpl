<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">


	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle">Safety Board Uploader</h2>

		<div style="margin-bottom:10px;">
			<a class="add-image" href="#popup-form"><input type="button" value="Add Image" style="width:100px;"></a>
		</div>
		
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="200">Image</th>
				  <th>Description</th>
				  <th>Publish Date</th>
                  <th width="150">Enable</th>
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->safetyBoard))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->safetyBoard as $safetyBoard) { 
				?>
				<tr>
				  <td class="date-column"><a class="image-popup-vertical-fit" href="<?php echo "/safety_board/large/".$safetyBoard['img']; ?>"><img src="/safety_board/thumb/<?php echo $safetyBoard['img']; ?>" width="100" /></a></td>
				  <td class="date-column"><?php echo $safetyBoard['description']; ?></td>
				  <td class="date-column"><?php echo date("F Y",strtotime($safetyBoard['year']."-".$safetyBoard['month']."-01")); ?></td>
				  <td class="date-column"><?php echo $safetyBoard['enable']?"Yes":"No"; ?></td>
                  <td class="action-column">
					<a class="add-image" href="#popup-form" data-id="<?php echo $safetyBoard['safety_board_id']; ?>" style="cursor:pointer;"><i class="fa fa-edit" style="font-size:20px;" ></i></a>&nbsp;&nbsp;
					<i class="fa fa-trash remove-image" data-id="<?php echo $safetyBoard['safety_board_id']; ?>" style="font-size:20px; cursor:pointer;" ></i>
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

<!-- Add image form -->
  <form action="" id="popup-form" class="mfp-hide white-popup-block" ><br/>
	<h2 id="form-title"></h2>
	<input type="hidden" name="safety_board_id" id="safety_board_id" /><br/>
	<label for="name">Image</label><br/>
	<div id="img-thumb"></div>
	<input type="file" name="filename" id="filename" class="attachment-uploader" style="margin:7px 0px;">
	<label for="name">Description</label><br/>
    <textarea rows="3" name="description" id="description" style="width:100%;"></textarea><br/><br/>
	<label for="issue_type">Publish Date</label><br/>
	<select name="month" id="month" style="width:200px;">
		<option value="1">January</option>
		<option value="2">February</option>
		<option value="3">March</option>
		<option value="4">April</option>
		<option value="5">May</option>
		<option value="6">June</option>
		<option value="7">July</option>
		<option value="8">August</option>
		<option value="9">September</option>
		<option value="10">October</option>
		<option value="11">November</option>
		<option value="12">December</option>
	</select>
	<select name="year" id="year" style="width:100px;">
	<?php for($y=2020;  $y<=date("Y"); $y++) { ?>
		<option value="<?php echo $y; ?>"><?php echo $y; ?></option>
	<?php }  ?>
	</select><br/><br/>
    <input type="checkbox" name="enable" id="enable"> Enable<br/><br/>
	<input type="submit" class="submit-btn" id="add-image-submit" name="add-image-submit" value="Save">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	$('.add-image').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#schedule_date',
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#popup-form')[0].reset();
				$("#safety_board_id").val("");
			}
		}
	});
	
	$(".add-image").click(function() {
		var id = this.dataset.id;
		if(id > 0)
		{
			$( "#form-title" ).html("Edit Safety Board Image");
			$.ajax({
				url: "/default/safety/getsafetyboardbyid",
				data: { id : id }
			}).done(function(response) {
				var obj = jQuery.parseJSON(response);
				$("#safety_board_id").val(obj.safety_board_id);
				$("#description").val(obj.description);
				$("#month").val(obj.month);
				$("#year").val(obj.year);
				if(obj.img != null)
				{
					$( "#img-thumb" ).html('<img src="/safety_board/thumb/'+obj.img+'" width="100" />');
				}
				else
				{
					$( "#img-thumb" ).html('');
				}
				if(obj.enable == "1")
				{
					$('#enable').prop('checked', true);
				}
				else
				{
					$('#enable').prop('checked', false);
				}
			});	
		}
		else
		{
			$( "#form-title" ).html("Add Safety Board Image");
		}
	});
	
	$('#popup-form').on('submit', function(event){
        event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/safety/savesafetyboard',
			type: 'POST',
			data: new FormData($('form')[0]),
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
				location.href="/default/safety/viewsafetyboardimages";
			}
		});
	});
	
	$(".remove-image").click(function() {
		var res = confirm("Are you sure you want to delete this image?");
		if(res == true)
		{
			location.href="/default/safety/deletesafetyboardbyid/id/"+this.dataset.id;
		}
	});
	
});
</script>