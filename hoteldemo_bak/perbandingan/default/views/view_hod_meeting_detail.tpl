
<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
			<form id="mom-form" class="form-label-left" action="/default/hod/savedetail" method="POST">
				<input id="hod_meeting_id" class="form-control col-md-7 col-xs-12" name="hod_meeting_id" type="hidden" value="<?php echo $this->hodMeeting['hod_meeting_id']; ?>">
				<div class="x_title">
					<h2 class="page-title">Digital MOM</h2>
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
							<?php echo $this->hodMeeting['meeting_title']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Date / Time</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->hodMeeting['tanggal_jam']; ?>
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
					
					
					<div id="hod_issue_finding"></div>

					<div id="hod_fitout_ongoing"></div>

					<span class="section">Projects / Issues</span>
					<div class="meeting-table">
					<table class="table table-striped">
					<thead>
						<tr>
						<th width="150" rowspan="2">Site</th>
						<th rowspan="2">Projects / Issues</th>					  
						<th colspan="3">Date</th>
						<th rowspan="2">Follow Up</th>
						<?php if($this->hodMeeting['approved'] != 1) { ?><th rowspan="2">Done</th><?php } ?>
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
						<td><?php echo $topic['site_name'].$this->pic[$topic['site_id']]; ?><br/><a href="/default/hod/exporttopictopdf/id/<?php echo $topic['topic_id']; ?>" target="_blank"><img src="/images/newlogo_pdf.png" width="24"></a></td>
						<td><?php echo $topic['topic']; ?>
						<?php if(!empty($topic['images'])) {
							echo "<br/>";
							foreach($topic['images'] as $image)
							{
								$filename = explode("/",$image['filename']);
								echo '<a target="_blank" href="/images/hod_meeting'.$image['filename'].'"><i class="fa fa-paperclip"></i> '.$filename[2].'</a><br/>';
							}
						} ?></td>
						<td align="center"><?php echo $topic['startdate']; ?></td>
						<td align="center"><?php echo $topic['targetdate']; ?></td>
						<td align="center"><?php echo $topic['finish_date']; ?></td>
						<td><?php echo $this->prevFollowUpTopic[$topic['topic_id']]; ?></td>
						<?php if($this->hodMeeting['approved'] != 1) { ?><td align="center"><input type="checkbox" id="done_checkbox" name="done_by_pic[<?php echo $i; ?>]" value="1" <?php if($topic['done_by_pic'] == '1') echo "checked"; ?>><input class="form-control col-md-7 col-xs-12" name="topic_id[]" type="hidden" value="<?php echo $topic['topic_id']; ?>"></td><?php } ?>
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
						<?php if($this->hodMeeting['approved'] != 1 && $this->approveHODMeeting != 1 ) { ?><button id="send" type="submit" class="btn btn-success" style="width:250px;">Save</button><?php } ?>
						<?php if($this->approveHODMeeting == 1 && $this->hodMeeting['approved'] != 1) { ?><button id="approve" type="submit" class="btn btn-success" style="width:250px;" >Approve</button><?php } ?>
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

<!-- View Follow Up -->
<form action="" id="followup-form" class="mfp-hide white-popup-block">
	<div id="follow-up-detail"></div>
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
			myFormData.append('hod_meeting_id', '<?php echo $this->hodMeeting['hod_meeting_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/hod/addcomment",
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
						msg = msg + '<br><a href="<?php echo $this->baseUrl; ?>/images/hod_meeting/comments_'+filename.substring(16, 22)+'/'+filename+'" target="_blank"><i class="fa fa-paperclip"></i> ' + filename + '</a>';
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
		url: "/default/hod/gethodissues",
		data: { cur_meeting_date : '<?php echo $this->currentMeetingDate; ?>', id : '<?php echo $this->site_ids; ?>', hod_meeting_id: '<?php echo $this->hodMeeting['hod_meeting_id']; ?>' }
	}).done(function(response) { 
		$( "#hod_issue_finding" ).html(response);
		$("body").mLoading('hide');
	});


	var id;
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
				/*clearInterval(addCommentIntervalId);*/
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

	<?php if($this->approveHODMeeting == 1 && $this->hodMeeting['approved'] != 1) { ?>
		$('#mom-form').on('submit', function(event){
			event.preventDefault();
			var res = confirm("Are you sure you want to approve this MoM?");
			if(res == true)
			{
				$("body").mLoading();
				$.ajax({
					url: '/default/hod/approvemom',
					type: 'POST',
					data: $(this).serialize(),
					success: function(response) {
						$("body").mLoading('hide');	
						alert("Approving MoM successful");
						location.href="/default/hod/viewdetail/id/<?php echo $this->hodMeeting['hod_meeting_id']; ?>";
					}
				});
			}
			else {
				$("body").mLoading('hide');	
			}
		});
	<?php } ?>

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
					url: "/default/hod/getfollowup",
					data: { id : followup_topic_id }
				}).done(function(response) {
					$( "#follow-up-detail" ).html(response);
				});
			},
			close: function() {	
				
			}
		}
	});

	$('.remove-start-date').hide();
	$('.remove-target-date').hide();

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
	$("#bod-meeting-menu").addClass('active');
	$("#bod-meeting-menu .child_menu").show();
});
</script>