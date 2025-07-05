
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
			<form id="bs-form" class="form-label-left" action="" method="POST">
				<input id="bs_meeting_id" class="form-control col-md-7 col-xs-12" name="bs_meeting_id" type="hidden" value="<?php echo $this->bsMeeting['bs_meeting_id']; ?>">
				<div class="x_title">
					<h2>BS Meeting MoM</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Judul</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->bsMeeting['meeting_title']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Tanggal / Jam</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->bsMeeting['tanggal_jam']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Peserta</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php foreach($this->attendance as $attendance) { ?>
								<div class="attendance-name"><?php echo $attendance['attendance_name']; ?></div>
							<?php } ?>
						</div>
					</div>
					<br clear="all" />
					<br/>

					
					<div id="bs_issue_finding"></div>

					<div id="bs_fitout_ongoing"></div>

					<span class="section">Projects / Issues</span>
					<div class="meeting-table">
					<table class="table table-striped">
					<thead>
						<tr>
						<th width="100" rowspan="2">Site / PIC</th>
						<th rowspan="2">Projects / Issues</th>					  
						<th colspan="3">Date</th>
						<th width="350">Follow Up</th>
						<th rowspan="2" width="50">Done</th>	
						<th rowspan="2" width="50">Done by PIC</th>	
						</tr>
						<tr>
						<th width="80">Start</th>
						<th width="90">Target</th>
						<th width="80">Finish</th>
						<th width="175"></th>						  
						</tr>
					</thead>
					<?php
						if(!empty($this->topic))
						{
					?>
						<tbody>
						<?php
							$i = 0;
							foreach($this->topic as $topic) { 
						?>
						<tr <?php if($topic['done'] == 1 && $topic['done_by_pic'] == 1) echo 'style="background-color:#fffdc2;"'; ?>>
						<td><?php echo $topic['initial'].$this->pic[$topic['site_id']]; ?></td>
						<td><?php echo $topic['topic']; ?>
						 <?php if(!empty($topic['images'])) {
							echo "<br/>";
							foreach($topic['images'] as $image)
							{
								echo '<a class="image-popup-vertical-fit" href="/images/bs_meeting'.$image['filename'].'"><img src="/images/bs_meeting'.str_replace(".", "_thumb.", $image['filename']).'" class="bs_thumb"></a> ';
							}
						} ?></td>
						<td align="center"><div id="start-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['startdate']; ?></div><a class="add-start-date" href="#start-date-form" data-id="<?php echo $topic['topic_id']; ?>"><button id="addStartDate" type="button" class="btn-success" style="width:100px; padding:2px 0px;">Add Start Date <i class="fa fa-plus-square"></i></button><a></td>
						<td align="center"><div id="target-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['targetdate']; ?></div><a class="add-target-date" href="#target-date-form" data-id="<?php echo $topic['topic_id']; ?>"><button id="addTargetDate" type="button" class="btn-success" style="width:120px; padding:2px 0px;">Add Target Date <i class="fa fa-plus-square"></i></button></a></td>
						<td align="center"><input class="form-control col-md-7 col-xs-12 datepicker" name="finish_date[]" type="text" value="<?php echo $topic['finishdate']; ?>" autocomplete="off" style="width:80px;"></td>
						<td><div id="progress<?php echo $topic['topic_id']; ?>"><?php echo $this->curFollowUpTopic[$topic['topic_id']]; ?></div><?php echo $this->prevFollowUpTopic[$topic['topic_id']]; ?> <?php if(empty($this->curFollowUpTopic[$topic['topic_id']])) { ?><a id="add-progress-<?php echo $topic['topic_id']; ?>" class="add-progress" href="#progress-form" data-id="<?php echo $topic['topic_id']; ?>"><button id="addProgress" type="button" class="btn-success" style="width:100px; padding:2px 0px;">Add Progress <i class="fa fa-plus-square"></i></button><a><?php } ?></td>
						<?php /*<td><textarea name="followup[]" class="form-control col-md-7 col-xs-12 followup-txtarea" style="height:70px;" placeholder="jangan lupa mengisi status Digital Office"><?php echo str_replace("<br>","&#13;",$topic['follow_up']); ?></textarea><input class="form-control col-md-7 col-xs-12" name="followup_id[]" type="hidden" value="<?php echo $topic['followup_id']; ?>"></td>*/ ?>
						<td align="center"><input type="checkbox" id="done_checkbox" name="done[<?php echo $i; ?>]" value="1" <?php if($topic['done'] == '1') echo "checked"; ?>><input class="form-control col-md-7 col-xs-12" name="topic_id[]" type="hidden" value="<?php echo $topic['topic_id']; ?>"></td>
						<td align="center"><input type="checkbox" id="done_checkbox2" name="done2[]" value="1" <?php if($topic['done_by_pic'] == '1') echo "checked"; ?> disabled></td>
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
				
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-12" style="text-align:center;">
						<button id="send" type="submit" class="btn btn-success" style="width:250px;">Simpan</button>
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

<!-- comment form -->
<form id="comment-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
	<input type="hidden" name="issue_id" id="comment_issue_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit">
</form>

<!-- Target Date form -->
<form action="" id="target-date-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="bs_meeting_id" value="<?php echo $this->bsMeeting['bs_meeting_id']; ?>" />
	<input type="hidden" name="topic_id" id="target_topic_id" />
	<label for="name">Target Date</label><br/>
	<input id="target_date" class="form-control col-md-7 col-xs-12 datepicker" name="target_date" type="text" required autocomplete="off">
	<br/>
	<input type="submit" id="add-target-date-submit" name="add-target-date-submit" value="Submit">
</form>

<!-- Start Date form -->
<form action="" id="start-date-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="bs_meeting_id" value="<?php echo $this->bsMeeting['bs_meeting_id']; ?>" />
	<input type="hidden" name="topic_id" id="start_topic_id" />
	<label for="name">Start Date</label><br/>
	<input id="start_date" class="form-control col-md-7 col-xs-12 datepicker" name="start_date" type="text" required autocomplete="off">
	<br/>
	<input type="submit" id="add-start-date-submit" name="add-start-date-submit" value="Submit">
</form>

<!-- Progress form -->
<form id="progress-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
	<input type="hidden" name="topic_id" id="progress_topic_id" />
	<input type="hidden" name="bs_meeting_id" value="<?php echo $this->bsMeeting['bs_meeting_id']; ?>" />
	<input type="hidden" name="followup_id" id="followup_id" />
	<label for="name">Progress</label><br/>
	<textarea rows="4" style="width:100%" name="followup" id="progress" placeholder="jangan lupa mengisi status Digital Office"></textarea><br/><br/>
	<label for="name">Images  <a id="add-image"><i class="fa fa-plus-square"></i></a></label><br/>
	<div id="list-images"></div>
	<ul class="bs_image">
		<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
	</ul>
	<input type="submit" id="add-progress-submit" name="add-progress-submit" value="Submit" style="margin-left:100px;">
</form>

<!-- View Follow Up -->
<form action="" id="followup-form" class="mfp-hide white-popup-block">
	<div id="follow-up-detail"></div>
</form>


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("body").mLoading();

	$.ajax({
		url: "/default/bs/getbsissues",
	}).done(function(response) { 
		$( "#bs_issue_finding" ).html(response);
		$("body").mLoading('hide');
	});

	<?php if($this->loadFitOutOnGoing) { ?>
	$.ajax({
		url: "/default/bs/getfitoutongoing",
		data: { cur_meeting_date : '<?php echo $this->currentMeetingDate; ?>' }
	}).done(function(response) { 
		$( "#bs_fitout_ongoing" ).html(response);
		/*$("body").mLoading('hide');*/
	});
	<?php } ?>

	/*var id;
	$('.add-comment').click(function() {
		id = this.dataset.id;
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
			  $.ajax({
					url: "/default/issue/getcommentsbyissueid",
					data: { id : id }
				}).done(function(response) {
					$("#comment_issue_id").val(id);
					$( "#comments-content" ).html(response);
				});
			},
			close: function() {	
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/issue/updatecomments",
                    data: { 
                        start: 0,
						category: '0',
						issue_id: id,
						solved: '0'
                     }
                }).done(function(response) {
                    var resp = jQuery.parseJSON(response);
                    $.each( resp, function( idx, val ) {
                        $( "#comment-"+val['id'] ).html(val['comment']);
                    });
                });
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/issue/addcomment',
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
				$.ajax({
					url: "/default/issue/getcommentsbyissueid",
					data: { id : id }
				}).done(function(response) { 
					$("#id").val(id);
					$( "#comments-content" ).html(response);
					$('#comment-form')[0].reset();
				});
				$("body").mLoading('hide');
			}
		});
	});*/

	var target_topic_id;
	$('.add-target-date').click(function() {
		target_topic_id = this.dataset.id;
	});

	$('.add-target-date').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#target_date',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#target_topic_id").val(target_topic_id);
			},
			close: function() {	
				
			}
		}
	});

	$('#target-date-form').on('submit', function(event){
		event.preventDefault(); 
		$('body').mLoading();
		var topicid = $("#target_topic_id").val();
		$.ajax({
			url: '/default/bs/addtargetdate',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				$("body").mLoading('hide');	
				$.magnificPopup.close();
				$("#target-date"+topicid).html(response);
				$('#target-date-form')[0].reset();
				<?php if(!$this->allowDeleteBSMeeting) { ?>
					$('.remove-start-date').hide();
					$('.remove-target-date').hide();
				<?php } else { ?>
					$('.remove-target-date').click(function() {
						var res = confirm("Are you sure you want to remove this target date?");
						if(res == true)
						{
							var topicid = this.dataset.topicid;
							$('body').mLoading();
							$.ajax({
								url: "/default/bs/deletetargetdate",
								data: { id : this.dataset.id, topic_id : topicid }
							}).done(function(response) {
								$("#target-date"+topicid).html(response);
								$("body").mLoading('hide');	
							});
						}
					});
				<?php } ?>
			}
		});
	});

	$('#start-date-form').on('submit', function(event){
		event.preventDefault(); 
		$('body').mLoading();
		var topicid = $("#start_topic_id").val();
		$.ajax({
			url: '/default/bs/addstartdate',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				$("body").mLoading('hide');	
				$.magnificPopup.close();
				$("#start-date"+topicid).html(response);
				$('#start-date-form')[0].reset();
				<?php if(!$this->allowDeleteBSMeeting) { ?>
					$('.remove-start-date').hide();
					$('.remove-target-date').hide();
				<?php } else { ?>
					$('.remove-start-date').click(function() {
						var res = confirm("Are you sure you want to remove this start date?");
						if(res == true)
						{
							var topicid = this.dataset.topicid;
							$('body').mLoading();
							$.ajax({
								url: "/default/bs/deletestartdate",
								data: { id : this.dataset.id, topic_id : topicid }
							}).done(function(response) {
								$("#start-date"+topicid).html(response);
								$("body").mLoading('hide');	
							});
						}
					});
				<?php } ?>
			}
		});
	});

	var start_topic_id;
	$('.add-start-date').click(function() {
		start_topic_id = this.dataset.id;
	});

	$('.add-start-date').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#start_date',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#start_topic_id").val(start_topic_id);
			},
			close: function() {	
				
			}
		}
	});

	var progress_topic_id;
	$('.add-progress').click(function() {
		progress_topic_id = this.dataset.id;
	});

	$('.add-progress').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#progress',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#followup_id").val("");
				$("#progress_topic_id").val(progress_topic_id);
			},
			close: function() {	
				
			}
		}
	});

	$('.edit-progress').click(function() {
		var followup_id = this.dataset.id;
		$("#followup_id").val(followup_id);
		$.ajax({
			url: "/default/bs/getfollowupbyid",
			data: { id : followup_id }
		}).done(function(response) { 
			var resp = jQuery.parseJSON(response);
			$("#followup_id").val(resp.followup_id);
			$("#progress_topic_id").val(resp.topic_id);
			$( "#progress" ).html(resp.follow_up);
			$( "#list-images" ).html(resp.imagelist);

			$('.remove-image-db').on('click', function(event){
				var res = confirm("Are you sure you want to remove this image?");
				if(res == true)
				{
					var imageid = this.dataset.id;
					var thisButton = $(this);
					$.ajax({
						url: "/default/bs/deletefollowupimage",
						data: { id : imageid }
					}).done(function(response) {
						thisButton.closest('li').remove();
					});
				}
			});
		});
	});

	$('.edit-progress').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#progress',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	

			}
		}
	});

	$('#progress-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		var topicid = $("#progress_topic_id").val();
		$.ajax({
			url: '/default/bs/addprogress',
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
				$("body").mLoading('hide');	
				$("#progress"+topicid).html(response);
				$("#add-progress-"+topicid).hide();
				$('#progress-form')[0].reset();
				$.magnificPopup.close();
			}
		});
	});

	$('#bs-form').on('submit', function(event){
		event.preventDefault(); 
		$('body').mLoading();
		$.ajax({
			url: '/default/bs/savedetailadmin',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				$("body").mLoading('hide');	
				alert("Saved Successfully");
			}
		});
	});

	var followup_topic_id;
	$('.view-more-link').click(function() {
		followup_topic_id = this.dataset.id;
	});

	$('.view-more-link').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
			  $.ajax({
					url: "/default/bs/getfollowup",
					data: { id : followup_topic_id }
				}).done(function(response) {
					$( "#follow-up-detail" ).html(response);
				});
			},
			close: function() {	
				
			}
		}
	});

	<?php if($this->allowDeleteBSMeeting) { ?>

	$('.remove-start-date').click(function() {
		var res = confirm("Are you sure you want to remove this start date?");
		if(res == true)
		{
			var topicid = this.dataset.topicid;
			$('body').mLoading();
			$.ajax({
				url: "/default/bs/deletestartdate",
				data: { id : this.dataset.id, topic_id : topicid }
			}).done(function(response) {
				$("#start-date"+topicid).html(response);
				$("body").mLoading('hide');	
			});
		}
	});

	$('.remove-target-date').click(function() {
		var res = confirm("Are you sure you want to remove this target date?");
		if(res == true)
		{
			var topicid = this.dataset.topicid;
			$('body').mLoading();
			$.ajax({
				url: "/default/bs/deletetargetdate",
				data: { id : this.dataset.id, topic_id : topicid }
			}).done(function(response) {
				$("#target-date"+topicid).html(response);
				$("body").mLoading('hide');	
			});
		}
	});

	<?php } else { ?>
		$('.remove-start-date').hide();
		$('.remove-target-date').hide();
	<?php } ?>

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	$('#add-image').on('click', function(event){
		$( ".bs_image").append('<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
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