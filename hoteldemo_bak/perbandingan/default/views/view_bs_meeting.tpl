<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">BS Meeting MoM</h2>
			<div class="paging">
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
			</div>
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="170">Day / Date</th>
				  <th>Title</th>			
				  <th class="comment-column">Comments</th>	  
				  <th width="150">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->bsMeeting))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->bsMeeting as $bsMeeting) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $bsMeeting['day_date']; ?></th>
				  <td class="date-column"><?php echo $bsMeeting['meeting_title']; ?></td>
				  <td>
						<div class="three-newest-comments" id="comment-<?php echo $bsMeeting['bs_meeting_id']; ?>">
						<?php if(!empty($bsMeeting['comments'])) { 
							foreach($bsMeeting['comments'] as $comment)
							{
								$cdate = explode("-", $comment['comment_date']);
								echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong> '.$comment['comment'].'<br/>';
								if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl.'/images/bs_meeting/comments_'.$cdate[0].$cdate[1].'/'.$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
								echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
							}
						} ?>
						</div>
				  </td>
				  <td class="action-column">
					<?php if($bsMeeting['approved'] != 1 && $this->showBSMeetingAdmin == 1) { ?><a href="/default/bs/edit/id/<?php echo $bsMeeting['bs_meeting_id']; ?>"><i class="fa fa-edit" ></i></a><?php } ?>
					<a href="/default/bs/viewdetail/id/<?php echo $bsMeeting['bs_meeting_id']; ?>" ><img src="/images/view_report2.jpg" width="24" /></a>
					<a href="/default/bs/exporttopdf/id/<?php echo $bsMeeting['bs_meeting_id']; ?>" target="_blank"><img src="/images/newlogo_pdf.png" width="24"></a>
				  	<a class="add-comment" href="#comment-form" data-id="<?php echo $bsMeeting['bs_meeting_id']; ?>" style="float:none; padding-top:10px;"><img src="/images/comment_24x24.png" /></a>
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
					
		  <div class="paging">
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
			</div>
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- comment form -->
  <form action="" id="comment-form" class="mfp-hide white-popup-block" >
	<input type="hidden" name="bs_meeting_id" id="bs_meeting_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit">
  </form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
  
<script type="text/javascript">
$(document).ready(function() {
	var id;
	
	$('.add-comment').click(function() {
		id = this.dataset.id;
		console.log(id);
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		callbacks: {
			open: function() {
			  $.ajax({
					url: "/default/bs/getcommentsbymeetingid",
					data: { id : id }
				}).done(function(response) {
					$("#bs_meeting_id").val(id);
					$( "#comments-content" ).html(response);
				});
			},
			close: function() {	
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/bs/updatecomments",
                    data: { 
                        start:'<?php echo $this->start; ?>'
                     }
                }).done(function(response) {
                    var resp = jQuery.parseJSON(response);
                    $.each( resp, function( idx, val ) {
                        $( "#comment-"+val['bs_meeting_id'] ).html(val['comment']);
                    });
                });
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/bs/addcomment',
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
					url: "/default/bs/getcommentsbymeetingid",
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
});
</script>