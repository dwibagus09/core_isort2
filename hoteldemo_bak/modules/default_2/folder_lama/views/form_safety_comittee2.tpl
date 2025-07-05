<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
			<form class="form-label-left" action="/default/safetycomittee/save2" method="POST" onsubmit="$('body').mLoading();">
				<input id="safety_comittee_id" class="form-control col-md-7 col-xs-12" name="safety_comittee_id" type="hidden" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>">
				<div class="x_title">
					<h2 class="pagetitle">Safety Committee MoM</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<?php /*<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Department</label>
						<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
							<?php echo $this->safetyComittee['department_id']; ?>
						</div>
					</div>
					<br/>*/ ?>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Title</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->safetyComittee['meeting_title']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Date / Time</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->safetyComittee['tanggal_jam']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Attendance</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php foreach($this->attendance as $attendance) { ?>
								<div class="attendance-name"><?php echo $attendance['attendance_name']; ?></div>
							<?php } ?>
						</div>
					</div>
					<br/>
					<br/>
					<div style="margin-bottom:10px; clear:both;">
						<a class="add-topic" href="#topic-form"><input type="button" value="Add Project / Issues" style="width:150px;"></a>
					</div>
					<table class="table table-striped">
					<thead>
						<tr>
						<th width="150">Department</th>
						<th>PIC</th>	
						<th>Project / Issues</th>		
						<th></th>
						</tr>
					</thead>
					<?php
						if(!empty($this->topic))
						{
					?>
						<tbody>
						<?php
							$i = 1;
							foreach($this->topic as $topic) { 
						?>
						<tr>
						<td><?php echo $topic['category_name']; ?></td>
						<td><?php echo $topic['pic_name']; ?></td>
						<td><?php echo $topic['topic']; ?>
							<?php if(!empty($topic['images'])) {
								echo "<br/>";
								foreach($topic['images'] as $image)
								{
									echo '<a class="image-popup-vertical-fit" href="/images/safety_comittee/'.$image['filename'].'"><img src="/images/safety_comittee/'.str_replace(".", "_thumb.", $image['filename']).'" class="sc_thumb"></a> ';
								}
							} ?>
						</td>
						<td align="right"><a class="edit-topic" href="#topic-form" data-id="<?php echo $topic['topic_id']; ?>" ><img src="/images/edit_report.png" width="20" style="margin-top:-7px;" /></a> <a href="/default/safetycomittee/deletetopic/scid/<?php echo $this->safety_comittee_id; ?>/id/<?php echo $topic['topic_id']; ?>"><i class="fa fa-trash" style="font-size:25px;"></i></a></td>
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
				
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-12" style="text-align:center;">
							<button id="send" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/safetycomittee/edit/id/<?php echo $this->safetyComittee['safety_comittee_id']; ?>'">Back to previous page</button>
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

<form action="/default/safetycomittee/savetopic" id="topic-form" class="mfp-hide white-popup-block" method="post" enctype="multipart/form-data" >
	<input class="col-md-7 col-xs-12" id="topic_id" name="topic_id" type="hidden">
	<input class="col-md-7 col-xs-12" name="safety_comittee_id" type="hidden" value="<?php echo $this->safety_comittee_id; ?>">
	<table id="add-topic" class="topic-form-table" width="100%">
		<tr>
			<td>Site<td>
			<td align="center">
			    <select id="department-select" name="department_id"  class="form-control col-md-7 col-xs-12" required>
					<option value="" disabled selected hidden>Select Department</option>
					<?php foreach($this->categories as $category) { ?>
						<option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
						<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>PIC<td>
			<td align="center"><input type="text" id="pic" name="pic" style="width:100%" required /></td>
		</tr>
		<tr>
			<td>Project / Issue<td>
			<td align="center"><textarea id="topic" name="topic" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
		<tr>
			<td valign="top">Images <a id="add-image"><i class="fa fa-plus-square"></i></a><td>
			<td align="left" valign="top" id="topic_image_list">
				<div id="list-images"></div>
				<ul class="hod_image">
					<li><input type="file" name="topic_image[]" style="display:inline-block; font-size:14px;"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
				</ul>
			</td>
		</tr>
	</table>
	<div class="add-btn"><input type="submit" id="add-topic-submit" name="add-topic-submit" value="Save"></div>
</form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.add-topic').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#topic-form')[0].reset();
			}
		}
	});

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('#topic-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	var topic_id;
	$('.edit-topic').on('click', function(event){
		topic_id = this.dataset.id;
	});

	$('.edit-topic').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = topic_id;
				$.ajax({
					url: "/default/safetycomittee/gettopicbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					$("#topic_id").val(resp.topic_id);
					$("#department-select").val(resp.department_id);
					$("#topic").val(resp.topic);
					$("#pic").val(resp.pic_name);
					$("#list-images").html(resp.imagelist);

					$('.remove-image-db').on('click', function(event){
						var res = confirm("Are you sure you want to remove this image?");
						if(res == true)
						{
							var imageid = this.dataset.id;
							var thisButton = $(this);
							$.ajax({
								url: "/default/safetycomittee/deletetopicimage",
								data: { id : imageid }
							}).done(function(response) {
								thisButton.closest('li').remove();
							});
						}
					});
				});
			},
			close: function() {	
				$('#topic-form')[0].reset();
			}
		}
	});

	$('#add-image').on('click', function(event){
		$( "#topic_image_list .hod_image").append('<li><input type="file" name="topic_image[]" style="display:inline-block;font-size:14px;"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
	});

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