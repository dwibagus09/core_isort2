<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">Checklist</h2>
			
			<div class="filter" style="width: 405px; padding: 10px 20px;">
				<form id="filter-form" action="/default/checklist/view"  method="post">
					<div class="filter-field">Filter by Room No: 
						<input type="text" name="room_no" name="room_no" value="<?php echo $this->room_no; ?>">
					</div>
					<div class="filter-field"> <input type="submit" id="filter-issue" name="filter-issue" value="Go" style="width:40px;" class="form-btn"></div>
				</form>
			</div>
			
			<?php if($this->totalPage > 0) { ?>
			<div class="paging">
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Checklist </div>
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
			</div>
			<?php } ?>
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th></th>
				  <th width="170">Day / Date</th>
				  <th>Checklist Type</th>
				  <th>Room</th>
				  <th>Status</th>
				  <th class="comment-column">Comments</th>					  
				  <th width="100">Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->checklist))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->checklist as $checklist) { 
				?>
				<tr <?php if($checklist['highlight'] == 1) { echo 'style="background-color: lightyellow;"'; } ?>>
				  <td><a href="/default/checklist/exporttopdf/id/<?php echo $checklist['checklist_id']; ?>" target="_blank"><img src="/images/newlogo_pdf.png" width="24"></a></td>
				  <td class="date-column"><?php echo $checklist['day_date']; ?></th>
				  <td class="date-column"><?php echo $checklist['template_name']; ?></td>
				  <td class="date-column"><?php echo $checklist['room_no']; ?></td>
				  <td class="date-column"><?php echo $checklist['status']; ?></td>
				  <td>
						<div class="three-newest-comments" id="comment-<?php echo $checklist['checklist_id']; ?>">
						<?php if(!empty($checklist['comments'])) { 
							foreach($checklist['comments'] as $comment)
							{
								$cdate = explode("-", $comment['comment_date']);
								echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong> '.stripslashes($comment['comment']).'<br/>';
								if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl.'/images/checklist/comments_'.$cdate[0].$cdate[1].'/'.$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
								echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
							}
						} ?>
						</div>
				  </td>
				  <td class="action-column">
					<?php if($checklist['show_edit_staff'] == 1) { ?><a href="/default/checklist/viewchecklistitems/id/<?php echo $checklist['checklist_id']; ?>" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<?php if($checklist['show_edit_spv'] == 1) { ?><a href="/default/checklist/viewchecklistitems/id/<?php echo $checklist['checklist_id']; ?>/p/spv" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<?php if($checklist['show_edit_hod'] == 1) { ?><a href="/default/checklist/viewchecklistitemshod/id/<?php echo $checklist['checklist_id']; ?>" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<a href="/default/checklist/viewdetail/id/<?php echo $checklist['checklist_id']; ?>" class="action-btn"><img src="/images/view_report.png" width="24" /></a> 
				  	<a class="add-comment" href="#comment-form" data-id="<?php echo $checklist['checklist_id']; ?>" style="float:none; padding-top:10px;"><img src="/images/comment1.png" /></a>
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
		<?php if($this->totalPage > 0) { ?>			
		  <div class="paging">
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Checklist </div>
			</div>
		<?php } ?>
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- comment form -->
  <form action="" id="comment-form" class="mfp-hide white-popup-block" >
	<input type="hidden" name="checklist_id" id="checklist_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit" class="form-btn">
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
					url: "/default/checklist/getcommentsbychecklistid",
					data: { id : id }
				}).done(function(response) {
					$("#checklist_id").val(id);
					$( "#comments-content" ).html(response);
				});
			},
			close: function() {	
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/checklist/updatecomments",
                    data: { 
                        start:'<?php echo $this->start; ?>'
                     }
                }).done(function(response) {
                    var resp = jQuery.parseJSON(response);
                    $.each( resp, function( idx, val ) {
                        $( "#comment-"+val['checklist_id'] ).html(val['comment']);
                    });
                });
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/checklist/addcomment',
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
					url: "/default/checklist/getcommentsbychecklistid",
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