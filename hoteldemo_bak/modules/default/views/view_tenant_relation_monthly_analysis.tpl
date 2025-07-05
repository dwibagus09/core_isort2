<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">Tenant Relation Monthly Analytics</h2>
			<?php if($this->totalPage > 0) { ?>
			<div class="paging">
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/storage/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
			</div>
			<?php } ?>
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="200">Month</th>
				  <th width="150">Submitted By</th>
				  <?php /*<th>Comments</th> */ ?>						  
				  <th>Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->monthlyAnalysis))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->monthlyAnalysis as $monthlyAnalysis) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $monthlyAnalysis['monthyear']; ?></th>
				  <td class="date-column"><?php echo $monthlyAnalysis['name']; ?></td>
				  <?php /*<td>
						<div class="three-newest-comments" id="comment-<?php echo $monthlyAnalysis['monthlyAnalysis']; ?>">
						<?php if(!empty($monthlyAnalysis['comments'])) { 
							foreach($monthlyAnalysis['comments'] as $comment)
							{
								echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong> '.$comment['comment'].'<br/>';
								if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
								echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
							}
						} ?>
						</div>
				  </td>*/ ?>
				  <td class="action-column">
				  	<?php if($monthlyAnalysis['allowEdit'] == 1) { ?><a href="/default/tr/addmonthlyanalysis/id/<?php echo $monthlyAnalysis['monthly_analysis_id']; ?>" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<?php /* <a class="action-btn delete"><i class="fa fa-trash" data-id="<?php echo $mod['mod_report_id']; ?>" ></i></a> */ ?>
					<a href="/default/tr/viewdetailmonthlyanalysis/id/<?php echo $monthlyAnalysis['monthly_analysis_id']; ?>" class="action-btn"><img src="/images/view_report.png" width="24" /></a>
					<?php /*<a class="add-comment" href="#comment-form" data-ym="<?php echo $monthlyAnalysis['yearmonth']; ?>" style="float:none; padding-top:10px; display:block;"><img src="/images/comment1.png" /></a>*/ ?>
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
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
			</div>
		<?php } ?>
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- comment form -->
  <form action="" id="comment-form" class="mfp-hide white-popup-block" >
	<input type="hidden" name="report_id" id="report_id" />
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
	$(".fa-trash").click(function() {
		var res = confirm("Are you sure you want to delete this report?");
		if(res == true)
		{
			location.href="/default/mod/deletereport/id/"+this.dataset.id;
		}
	});
	
	$('.add-comment').click(function() {
		id = this.dataset.id;
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		callbacks: {
			open: function() {
			  $.ajax({
					url: "/default/mod/getcommentsbyreportid",
					data: { id : id }
				}).done(function(response) {
					$("#report_id").val(id);
					$( "#comments-content" ).html(response);
				});
			},
			close: function() {	
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/mod/updatecomments",
                    data: { 
                        start:'<?php echo $this->start; ?>'
                     }
                }).done(function(response) {
                    var resp = jQuery.parseJSON(response);
                    $.each( resp, function( idx, val ) {
                        $( "#comment-"+val['mod_report_id'] ).html(val['comment']);
                    });
                });
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/mod/addcomment',
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
					url: "/default/mod/getcommentsbyreportid",
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
	
	$("#business-intelligence-menu").addClass('active');
	$("#business-intelligence-menu .child_menu").show();
	
});
</script>