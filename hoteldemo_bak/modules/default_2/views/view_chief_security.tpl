<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">View Chief Daily Report</h2>
			<?php if($this->totalPage > 0) { ?>
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
			<?php } ?>
		<div class="table-auto-scroll">
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th>Day / Date</th>
				  <th>Submitted By</th>	
				  <th class="comment-column">Comments</th>						  
				  <th>Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->security))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->security as $security) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $security['day_date']; ?></th>
				  <td class="date-column"><?php echo $security['name']; ?></th>
				  <td>
						<div class="three-newest-comments" id="comment-<?php echo $security['report_date']; ?>">
						<?php if(!empty($security['comments'])) { 
							foreach($security['comments'] as $comment)
							{
								echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong> '.$comment['comment'].'<br/>';
								if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
								echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
							}
						} ?>
						</div>
				  </td>
				  <td class="action-column">
					<?php if($this->showAddChiefSecurity == 1 && $security['allowEdit'] == 1) {  ?><a href="/default/security/editchiefreport/dt/<?php echo $security['report_date']; ?>" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<?php /*<a class="action-btn delete"><i class="fa fa-trash" data-id="<?php echo $security['chief_security_report_id']; ?>" ></i></a> */ ?>
					<a href="/default/security/viewchiefdetailreport/dt/<?php echo $security['report_date']; ?>" class="action-btn"><img src="/images/view_report.png" width="24" /></a>
					<a class="add-comment" href="#comment-form" data-dt="<?php echo $security['report_date']; ?>"><img src="/images/comment1.png" /></a>
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
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
			</div>
		<?php } ?>
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- comment form -->
  <form action="" id="comment-form" class="mfp-hide white-popup-block" >
	<input type="hidden" name="report_date" id="report_date" />
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
	var report_date;
	$(".fa-trash").click(function() {
		var res = confirm("Are you sure you want to delete this report?");
		if(res == true)
		{
			location.href="/default/security/deletechiefreport/id/"+this.dataset.id;
		}
	});
	
	$('.add-comment').click(function() {
		report_date = this.dataset.dt;
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		callbacks: {
			open: function() {
			  var dt = report_date;
			  $.ajax({
					url: "/default/security/getsecuritycommentsbyreportdate",
					data: { report_date : dt }
				}).done(function(response) {
					$("#report_date").val(dt);
					$( "#comments-content" ).html(response);
				});
				/*addCommentIntervalId = setInterval(function(){ 
					$.ajax({
						url: "/default/security/getsecuritycommentsbyreportdate",
						data: { report_date : dt }
					}).done(function(response) {
						$("#report_date").val(dt);
						$( "#comments-content" ).html(response);
					});
				}, 15000);	*/	
			},
			close: function() {	
				/*clearInterval(addCommentIntervalId);*/
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/security/updatedchiefcomments",
                    data: { 
                        start:'<?php echo $this->start; ?>'
                     }
                }).done(function(response) {
                    var resp = jQuery.parseJSON(response);
                    $.each( resp, function( idx, val ) {
                        $( "#comment-"+val['report_date'] ).html(val['comment']);
                    });
                });
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/security/addcomment',
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
					url: "/default/security/getsecuritycommentsbyreportdate",
					data: { report_date : report_date }
				}).done(function(response) { 
					$("#report_date").val(report_date);
				$( "#comments-content" ).html(response);
					$('#comment-form')[0].reset();
				});
				$("body").mLoading('hide');
			}
		});
	});
	
	/*setInterval(function(){ 
		$.ajax({
			url: "/default/security/getupdatedchiefcomments",
			data: { start : <?php echo intval($this->start); ?> }
		}).done(function(response) {
			var resp = jQuery.parseJSON(response);
			$.each( resp, function( idx, val ) {
				$( "#comment-"+val['report_date'] ).html(val['comment']);
			});
		});
	}, 5000);*/
});
</script>