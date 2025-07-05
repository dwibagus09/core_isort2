
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
			<form id="safety-comittee-form" class="form-label-left" action="" method="POST">
				<input id="safety_comittee_id" class="form-control col-md-7 col-xs-12" name="safety_comittee_id" type="hidden" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>">
				<div class="x_title">
					<h2 class="pagetitle">Safety Committee MoM</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<?php /*<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Site</label>
						<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
							<?php echo $this->ident['site_fullname']; ?>
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
					<br clear="all" />
					<br/>

					
					<div id="safety_comittee_issue"></div>

					<span class="section">Projects / Issues</span>
					<div class="meeting-table">
					<table class="table table-striped">
					<thead>
						<tr>
						<th width="100" rowspan="2">Department<br/>PIC</th>
						<th rowspan="2">Projects / Issues</th>					  
						<th colspan="3">Date</th>
						<th rowspan="2">Follow Up</th>
						<th rowspan="2">Accident Review</th>
						<th rowspan="2">Recommendation</th>
						<th rowspan="2" width="50">Done</th>	
						<th rowspan="2" width="50">Done by PIC</th>	
						</tr>
						<tr>
						<th width="80">Start</th>
						<th width="90">Target</th>
						<th width="80">Finish</th>					  
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
						<td><?php echo $topic['category_name']."<br/>".$topic['pic_name']; ?><br/><a href="/default/safetycomittee/exporttopictopdf/id/<?php echo $topic['topic_id']; ?>" target="_blank"><img src="/images/newlogo_pdf.png" width="24"></a></td>
						<td><?php echo $topic['topic']; ?>
						 <?php if(!empty($topic['images'])) {
							echo "<br/>";
							foreach($topic['images'] as $image)
							{
								echo '<a class="image-popup-vertical-fit" href="/images/safety_comittee'.$image['filename'].'"><img src="/images/safety_comittee'.str_replace(".", "_thumb.", $image['filename']).'" class="sc_thumb"></a> ';
							}
						} ?></td>
						<td align="center"><div id="start-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['startdate']; ?></div><a class="add-start-date" href="#start-date-form" data-id="<?php echo $topic['topic_id']; ?>"><button class="addStartDate" type="button" class="btn-success" style="width:100px; padding:2px 0px;">Add Start Date <i class="fa fa-plus-square"></i></button><a></td>
						<td align="center"><div id="target-date<?php echo $topic['topic_id']; ?>"><?php echo $topic['targetdate']; ?></div><a class="add-target-date" href="#target-date-form" data-id="<?php echo $topic['topic_id']; ?>"><button class="addTargetDate" type="button" class="btn-success" style="width:120px; padding:2px 0px;">Add Target Date <i class="fa fa-plus-square"></i></button></a></td>
						<td align="center"><input class="form-control col-md-7 col-xs-12 datepicker" name="finish_date[]" type="text" value="<?php echo $topic['finishdate']; ?>" autocomplete="off" style="width:80px;"></td>
						<td><div id="progress<?php echo $topic['topic_id']; ?>"><?php echo $this->curFollowUpTopic[$topic['topic_id']]; ?></div><?php echo $this->prevFollowUpTopic[$topic['topic_id']]; ?> <?php if(empty($this->curFollowUpTopic[$topic['topic_id']])) { ?><a id="add-progress-<?php echo $topic['topic_id']; ?>" class="add-progress" href="#progress-form" data-id="<?php echo $topic['topic_id']; ?>"><button class="addProgress" type="button" class="btn-success" style="width:100px; padding:2px 0px;">Add Progress <i class="fa fa-plus-square"></i></button><a><?php } ?></td>
						<td><div id="accidentreview<?php echo $topic['topic_id']; ?>"><?php echo $this->curAccidentReviewTopic[$topic['topic_id']]; ?></div><?php echo $this->prevAccidentReviewTopic[$topic['topic_id']]; ?> <?php if(empty($this->curAccidentReviewTopic[$topic['topic_id']])) { ?><a id="add-accident-review-<?php echo $topic['topic_id']; ?>" class="add-accident-review" href="#accident-review-form" data-id="<?php echo $topic['topic_id']; ?>"><button class="addProgress" type="button" class="btn-success" style="width:130px; padding:2px 0px;">Add Accident Review <i class="fa fa-plus-square"></i></button><a><?php } ?></td>
						<td><div id="recommendation<?php echo $topic['topic_id']; ?>"><?php echo $this->curRecommendationTopic[$topic['topic_id']]; ?></div><?php echo $this->prevRecommendationTopic[$topic['topic_id']]; ?> <?php if(empty($this->curRecommendationTopic[$topic['topic_id']])) { ?><a id="add-recommendation-<?php echo $topic['topic_id']; ?>" class="add-recommendation" href="#recommendation-form" data-id="<?php echo $topic['topic_id']; ?>"><button class="addProgress" type="button" class="btn-success" style="width:135px; padding:2px 0px;">Add Recommendation <i class="fa fa-plus-square"></i></button><a><?php } ?></td>
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
						<button id="send" type="submit" class="form-btn" style="width:250px;">Save</button>
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

<div id="chat_div"></div>

<!-- comment form -->
<form id="comment-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
	<input type="hidden" name="issue_id" id="comment_issue_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit" class="form-btn">
</form>

<!-- Target Date form -->
<form action="" id="target-date-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="safety_comittee_id" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>" />
	<input type="hidden" name="topic_id" id="target_topic_id" />
	<label for="name">Target Date</label><br/>
	<input id="target_date" class="form-control col-md-7 col-xs-12 datepicker" name="target_date" type="text" required autocomplete="off">
	<br/>
	<input type="submit" id="add-target-date-submit" name="add-target-date-submit" value="Submit" class="form-btn">
</form>

<!-- Start Date form -->
<form action="" id="start-date-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="safety_comittee_id" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>" />
	<input type="hidden" name="topic_id" id="start_topic_id" />
	<label for="name">Start Date</label><br/>
	<input id="start_date" class="form-control col-md-7 col-xs-12 datepicker" name="start_date" type="text" required autocomplete="off">
	<br/>
	<input type="submit" id="add-start-date-submit" name="add-start-date-submit" value="Submit" class="form-btn">
</form>

<!-- Progress form -->
<form id="progress-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
	<input type="hidden" name="topic_id" id="progress_topic_id" />
	<input type="hidden" name="safety_comittee_id" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>" />
	<input type="hidden" name="followup_id" id="followup_id" />
	<label for="name">Progress</label><br/>
	<textarea rows="4" style="width:100%" name="followup" id="progress" placeholder=""></textarea><br/><br/>
	<label for="name">Images  <a id="add-image"><i class="fa fa-plus-square"></i></a></label><br/>
	<div id="list-images"></div>
	<ul class="sc_image">
		<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
	</ul>
	<input type="submit" id="add-progress-submit" name="add-progress-submit" value="Submit" style="margin-left:100px;" class="form-btn">
</form>

<!-- View Follow Up -->
<form action="" id="followup-form" class="mfp-hide white-popup-block">
	<div id="follow-up-detail"></div>
</form>

<!-- Accident Review form -->
<form id="accident-review-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="topic_id" id="accident_review_topic_id" />
	<input type="hidden" name="safety_comittee_id" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>" />
	<input type="hidden" name="accident_review_id" id="accident_review_id" />
	<label for="name">Accident Review</label><br/>
	<textarea rows="4" style="width:100%" name="accident_review" id="accident_review" placeholder=""></textarea><br/><br/>
	<label for="name">Images  <a id="ar-add-image"><i class="fa fa-plus-square"></i></a></label><br/>
	<div id="ar-list-images"></div>
	<ul class="ar_image">
		<li><input type="file" name="accident_review_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
	</ul>
	<input type="submit" id="accident-review-submit" name="accident-review-submit" value="Submit" style="margin-left:100px;" class="form-btn">
</form>

<!-- View Accident Review -->
<form action="" id="view-accident-review-form" class="mfp-hide white-popup-block">
	<div id="accident-review-detail"></div>
</form>

<!--Recommendation -->
<form id="recommendation-form" class="mfp-hide white-popup-block">
	<input type="hidden" name="topic_id" id="recommendation_topic_id" />
	<input type="hidden" name="safety_comittee_id" value="<?php echo $this->safetyComittee['safety_comittee_id']; ?>" />
	<input type="hidden" name="recommendation_id" id="recommendation_id" />
	<label for="name">Recommendation</label><br/>
	<textarea rows="4" style="width:100%" name="recommendation" id="recommendation" placeholder=""></textarea><br/><br/>
	<label for="name">Images  <a id="rec-add-image"><i class="fa fa-plus-square"></i></a></label><br/>
	<div id="rec-list-images"></div>
	<ul class="rec_image">
		<li><input type="file" name="recommendation_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest('li').remove();"></i></ul>
	</ul>
	<input type="submit" id="recommendation-submit" name="recommendation-submit" value="Submit" style="margin-left:100px;" class="form-btn">
</form>

<!-- View Recommendation -->
<form action="" id="view-recommendation-form" class="mfp-hide white-popup-block">
	<div id="recommendation-detail"></div>
</form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.chatbox.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	var box = $("#chat_div").chatbox({
		id:"<?php echo $this->ident['name']; ?>", 
		title : '<img src="/images/comment2.png" /> Comment',
		messageSent : function(id, user, msg) {
			$(".ui-chatbox-content").mLoading();
			var myFormData = new FormData();
			var file_data = $("#filename").prop("files")[0];
			myFormData.append('attachment', file_data);
			myFormData.append('safety_comittee_id', '<?php echo $this->safetyComittee['safety_comittee_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/safetycomittee/addcomment",
				type: 'POST',
				processData: false,
				contentType: false,
				data: myFormData
			}).done(function(response) {
				$("#chat_div").chatbox("option", "boxManager").addMsg(id, msg, response);	
				$("#filename").val(''); 
				$(".ui-chatbox-content").mLoading('hide');
			});
		},
		boxManager: {
                init: function(elem) {
                    this.elem = elem;
					this.elem.uiChatboxContent.toggle();
					this.elem.uiChatboxTitlebarMinimize.hide();	
					this.elem.uiChatboxTitle.html('<img src="/images/comment2.png" width="30"/>');
					this.elem.uiChatboxTitlebar.width("30px");
					this.elem.uiChatbox.addClass('ui-chatbox-minimize-icon-only');
					this.elem.uiChatboxTitlebar.removeClass('ui-widget-header');	
					<?php if(!empty($this->comments)) { 
							foreach($this->comments as $comment) {
					?>
							var comment<?php echo $comment['comment_id']; ?> = "<?php echo str_replace(array("\n","\r","\r\n"),"",$comment['comment']); ?>";
							var msg<?php echo $comment['comment_id']; ?> = comment<?php echo $comment['comment_id']; ?>.replace("<br>","\n");
							$("#chat_div").chatbox("option", "boxManager").addMsg('<?php echo $comment['name']; ?>', msg<?php echo $comment['comment_id']; ?>, '<?php echo $comment['filename']; ?>');	
					<?php } } ?>					
                },
                addMsg: function(peer, msg, filename) {
                    var self = this;
                    var box = self.elem.uiChatboxLog;
                    var e = document.createElement('div');
                    box.append(e);
                    $(e).hide();

                    var systemMessage = false;

                    if (peer) {
                        var peerName = document.createElement("b");
                        $(peerName).text(peer + ": ");
                        e.appendChild(peerName);
                    } else {
                        systemMessage = true;
                    }

                    var msgElement = document.createElement(
                        systemMessage ? "i" : "span");
					
					if(filename !== '')
					{
						msg = msg + '<br><a href="<?php echo $this->baseUrl; ?>/images/safety_comittee/comments_'+filename.substring(20, 26)+'/'+filename+'" target="_blank"><i class="fa fa-paperclip"></i> ' + filename + '</a>';
					}
                    $(msgElement).html(msg);
                    e.appendChild(msgElement);
                    $(e).addClass("ui-chatbox-msg");
                    $(e).css("maxWidth", "100%");
                    $(e).fadeIn();
                    self._scrollToBottom();

                    if (!self.elem.uiChatboxTitlebar.hasClass("ui-state-focus")
                        && !self.highlightLock) {
                        self.highlightLock = true;
                    }
                },
                highlightBox: function() {
                    var self = this;
                    self.elem.uiChatboxTitlebar.effect("highlight", {}, 300);
                    self.elem.uiChatbox.effect("bounce", {times: 3}, 300, function() {
                        self.highlightLock = false;
                        self._scrollToBottom();
                    });
                },
                toggleBox: function() {
                    this.elem.uiChatbox.toggle();
                },
                _scrollToBottom: function() {
                    var box = this.elem.uiChatboxLog;
                    box.scrollTop(box.get(0).scrollHeight);
                }
            }
	});

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("body").mLoading();

	$.ajax({
		url: "/default/safetycomittee/getsafetycomitteeissues",
		data: { id : '<?php echo $this->site_ids; ?>', safety_comittee_id: '<?php echo $this->safetyComittee['safety_comittee_id']; ?>' }
	}).done(function(response) { 
		$( "#safety_comittee_issue" ).html(response);
		$("body").mLoading('hide');
	});

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
			url: '/default/safetycomittee/addtargetdate',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				$("body").mLoading('hide');	
				$.magnificPopup.close();
				$("#target-date"+topicid).html(response);
				$('#target-date-form')[0].reset();
				<?php if(!$this->allowDeleteSafetyComittee) { ?>
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
								url: "/default/safetycomittee/deletetargetdate",
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
			url: '/default/safetycomittee/addstartdate',
			type: 'POST',
			data: $(this).serialize(),
			success: function(response) {
				$("body").mLoading('hide');	
				$.magnificPopup.close();
				$("#start-date"+topicid).html(response);
				$('#start-date-form')[0].reset();
				<?php if(!$this->allowDeleteSafetyComittee) { ?>
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
								url: "/default/safetycomittee/deletestartdate",
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
				$('#progress').val("");
				$( "#list-images" ).html("");
        		$( ".sc_image").html('');
                $( ".sc_image").append('<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			},
			close: function() {	
				
			}
		}
	});

	$('.edit-progress').click(function() {
		var followup_id = this.dataset.id;
		$("#followup_id").val(followup_id);
		$.ajax({
			url: "/default/safetycomittee/getfollowupbyid",
			data: { id : followup_id }
		}).done(function(response) { 
			var resp = jQuery.parseJSON(response);
			$("#followup_id").val(resp.followup_id);
			$("#progress_topic_id").val(resp.topic_id);
			$( "#progress" ).val(resp.follow_up);
			$( "#list-images" ).html(resp.imagelist);
            $( ".sc_image").html('');
            $( ".sc_image").append('<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			$('.remove-image-db').on('click', function(event){
				var res = confirm("Are you sure you want to remove this image?");
				if(res == true)
				{
					var imageid = this.dataset.id;
					var thisButton = $(this);
					$.ajax({
						url: "/default/safetycomittee/deletefollowupimage",
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
			url: '/default/safetycomittee/addprogress',
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
				
				$('.edit-progress').click(function() {
            		var followup_id = this.dataset.id;
            		$("#followup_id").val(followup_id);
            		$.ajax({
            			url: "/default/safetycomittee/getfollowupbyid",
            			data: { id : followup_id }
            		}).done(function(response) { 
            			var resp = jQuery.parseJSON(response);
            			$("#followup_id").val(resp.followup_id);
            			$("#progress_topic_id").val(resp.topic_id);
            			$( "#progress" ).val(resp.follow_up);
            			$( "#list-images" ).html(resp.imagelist);
                        $( ".sc_image").html('');
                        $( ".sc_image").append('<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
            			$('.remove-image-db').on('click', function(event){
            				var res = confirm("Are you sure you want to remove this image?");
            				if(res == true)
            				{
            					var imageid = this.dataset.id;
            					var thisButton = $(this);
            					$.ajax({
            						url: "/default/safetycomittee/deletefollowupimage",
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
			}
		});
	});

	$('#safety-comittee-form').on('submit', function(event){
		event.preventDefault(); 
		$('body').mLoading();
		$.ajax({
			url: '/default/safetycomittee/savedetailadmin',
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
					url: "/default/safetycomittee/getfollowup",
					data: { id : followup_topic_id }
				}).done(function(response) {
					$( "#follow-up-detail" ).html(response);
				});
			},
			close: function() {	
				
			}
		}
	});

	<?php if($this->allowDeleteSafetyComittee) { ?>

	$('.remove-start-date').click(function() {
		var res = confirm("Are you sure you want to remove this start date?");
		if(res == true)
		{
			var topicid = this.dataset.topicid;
			$('body').mLoading();
			$.ajax({
				url: "/default/safetycomittee/deletestartdate",
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
				url: "/default/safetycomittee/deletetargetdate",
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
		$( ".sc_image").append('<li><input type="file" name="followup_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
	});

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
	
	/*** Accident Review ***/
	
	var accident_review_topic_id;
	$('.add-accident-review').click(function() {
		accident_review_topic_id = this.dataset.id;
			
	});

	$('.add-accident-review').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#progress',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#accident_review_id").val("");
				$("#accident_review_topic_id").val(accident_review_topic_id);
				$('#accident_review').val("");
				$( "#ar-list-images" ).html("");
        		$( ".ar_image").html('');
                $( ".ar_image").append('<li><input type="file" name="accident_review_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			},
			close: function() {	
				
			}
		}
	});

	$('.edit-accident-review').click(function() {
		var accident_review_id = this.dataset.id;
		$("#accident_review_id").val(accident_review_id);
		$.ajax({
			url: "/default/safetycomittee/getaccidentreviewbyid",
			data: { id : accident_review_id }
		}).done(function(response) { 
			var resp = jQuery.parseJSON(response);
			$("#accident_review_id").val(resp.accident_review_id);
			$("#accident_review_topic_id").val(resp.topic_id);
			$( "#accident_review" ).val(resp.accident_review);
			$( "#ar-list-images" ).html(resp.imagelist);
            $( ".ar_image").html('');
            $( ".ar_image").append('<li><input type="file" name="accident_review_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			$('.remove-image-db').on('click', function(event){
				var res = confirm("Are you sure you want to remove this image?");
				if(res == true)
				{
					var imageid = this.dataset.id;
					var thisButton = $(this);
					$.ajax({
						url: "/default/safetycomittee/deleteaccidentreviewimage",
						data: { id : imageid }
					}).done(function(response) {
						thisButton.closest('li').remove();
					});
				}
			});
		});
	});

	$('.edit-accident-review').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#accident_review',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() { 
			    
			},
			close: function() {	

			}
		}
	});

	$('#accident-review-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		var topicid = $("#accident_review_topic_id").val();
		$.ajax({
			url: '/default/safetycomittee/addaccidentreview',
			type: 'POST',
			data: new FormData($('form')[0]),
			cache: false,
			contentType: false,
			processData: false,

			xhr: function () {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('accident_review', function (e) {
					if (e.lengthComputable) {
						$('accident_review').attr({
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
				$("#accidentreview"+topicid).html(response);
				$("#add-accident-review-"+topicid).hide();
				$('#accident-review-form')[0].reset();
				$.magnificPopup.close();
				
				$('.edit-accident-review').click(function() {
            		var accident_review_id = this.dataset.id;
            		$("#accident_review_id").val(accident_review_id);
            		$.ajax({
            			url: "/default/safetycomittee/getaccidentreviewbyid",
            			data: { id : accident_review_id }
            		}).done(function(response) { 
            			var resp = jQuery.parseJSON(response);
            			$("#accident_review_id").val(resp.accident_review_id);
            			$("#accident_review_topic_id").val(resp.topic_id);
            			$( "#accident_review" ).val(resp.accident_review);
            			$( "#ar-list-images" ).html(resp.imagelist);
                        $( ".ar_image").html('');
                        $( ".ar_image").append('<li><input type="file" name="accident_review_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
            			$('.remove-image-db').on('click', function(event){
            				var res = confirm("Are you sure you want to remove this image?");
            				if(res == true)
            				{
            					var imageid = this.dataset.id;
            					var thisButton = $(this);
            					$.ajax({
            						url: "/default/safetycomittee/deleteaccidentreviewimage",
            						data: { id : imageid }
            					}).done(function(response) {
            						thisButton.closest('li').remove();
            					});
            				}
            			});
            		});
            	});
            
            	$('.edit-accident-review').magnificPopup({
            		type: 'inline',
            		preloader: false,
            		focus: '#accident_review',
            		closeOnBgClick: false,
            		enableEscapeKey: false,
            		callbacks: {
            			open: function() { 
            			    
            			},
            			close: function() {	
            
            			}
            		}
            	});
			}
		});
	});
	
	$('#ar-add-image').on('click', function(event){
		$( ".ar_image").append('<li><input type="file" name="accident_review_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
	});
	
	/*** Recommendation ***/
	
	var recommendation_topic_id;
	$('.add-recommendation').click(function() {
		recommendation_topic_id = this.dataset.id;
			
	});

	$('.add-recommendation').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#recommendation',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#recommendation_id").val("");
				$("#recommendation_topic_id").val(recommendation_topic_id);
				$('#recommendation').val("");
				$( "#rec-list-images" ).html("");
        		$( ".rec_image").html('');
                $( ".rec_image").append('<li><input type="file" name="recommendation_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			},
			close: function() {	
				
			}
		}
	});

	$('.edit-recommendation').click(function() {
		var recommendation_id = this.dataset.id;
		$("#recommendation_id").val(recommendation_id);
		$.ajax({
			url: "/default/safetycomittee/getrecommendationbyid",
			data: { id : recommendation_id }
		}).done(function(response) { 
			var resp = jQuery.parseJSON(response);
			$("#recommendation_id").val(resp.recommendation_id);
			$("#recommendation_topic_id").val(resp.topic_id);
			$( "#recommendation" ).val(resp.recommendation);
			$( "#rec-list-images" ).html(resp.imagelist);
            $( ".rec_image").html('');
            $( ".rec_image").append('<li><input type="file" name="recommendation_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
			$('.remove-image-db').on('click', function(event){
				var res = confirm("Are you sure you want to remove this image?");
				if(res == true)
				{
					var imageid = this.dataset.id;
					var thisButton = $(this);
					$.ajax({
						url: "/default/safetycomittee/deleterecommendationimage",
						data: { id : imageid }
					}).done(function(response) {
						thisButton.closest('li').remove();
					});
				}
			});
		});
	});

	$('.edit-recommendation').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#recommendation',
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() { 
				
			},
			close: function() {	

			}
		}
	});

	$('#recommendation-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		var topicid = $("#recommendation_topic_id").val();
		$.ajax({
			url: '/default/safetycomittee/addrecommendation',
			type: 'POST',
			data: new FormData($('form')[0]),
			cache: false,
			contentType: false,
			processData: false,

			xhr: function () {
				var myXhr = $.ajaxSettings.xhr();
				if (myXhr.upload) {
					myXhr.upload.addEventListener('recommendation', function (e) {
					if (e.lengthComputable) {
						$('recommendation').attr({
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
				$("#recommendation"+topicid).html(response);
				$("#add-recommendation-"+topicid).hide();
				$('#accident-review-form')[0].reset();
				$.magnificPopup.close();
				
				$('.edit-recommendation').click(function() {
            		var recommendation_id = this.dataset.id;
            		$("#recommendation_id").val(recommendation_id);
            		$.ajax({
            			url: "/default/safetycomittee/getrecommendationbyid",
            			data: { id : recommendation_id }
            		}).done(function(response) { 
            			var resp = jQuery.parseJSON(response);
            			$("#recommendation_id").val(resp.recommendation_id);
            			$("#recommendation_topic_id").val(resp.topic_id);
            			$( "#recommendation" ).val(resp.recommendation);
            			$( "#rec-list-images" ).html(resp.imagelist);
                        $( ".rec_image").html('');
                        $( ".rec_image").append('<li><input type="file" name="recommendation_image[]" style="display:inline-block"> <i class="fa fa-trash remove-image" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
            			$('.remove-image-db').on('click', function(event){
            				var res = confirm("Are you sure you want to remove this image?");
            				if(res == true)
            				{
            					var imageid = this.dataset.id;
            					var thisButton = $(this);
            					$.ajax({
            						url: "/default/safetycomittee/deleterecommendationimage",
            						data: { id : imageid }
            					}).done(function(response) {
            						thisButton.closest('li').remove();
            					});
            				}
            			});
            		});
            	});
				
				$('.edit-recommendation').magnificPopup({
            		type: 'inline',
            		preloader: false,
            		focus: '#recommendation',
            		closeOnBgClick: false,
            		enableEscapeKey: false,
            		callbacks: {
            			open: function() { 
            				
            			},
            			close: function() {	
            
            			}
            		}
            	});
			}
		});
	});
	
	$('#rec-add-image').on('click', function(event){
		$( ".rec_image").append('<li><input type="file" name="recommendation_image[]" style="display:inline-block"> <i class="fa fa-trash remove-attendance" style="cursor:pointer;" onclick="$(this).closest(\'li\').remove();"></i></li>');
	});
	
});
</script>