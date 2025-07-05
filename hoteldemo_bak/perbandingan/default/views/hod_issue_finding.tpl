<?php
	if(!empty($this->issues))
	{
?>
	<span class="section">Opened Kaizen</span>
	<div class="meeting-table">
		<table class="table table-striped" style="margin-bottom:0px;">
			<thead>
				<tr>
				<th width="120" style="text-align:left;">Site</th>
				<th width="150" style="text-align:left;">Department</th>
				<th style="text-align:left;">Kaizen</th>
				<th width="150" style="text-align:left;">Location</th>						  
				<th width="100" style="text-align:left;">Date</th>
				<th width="250" style="text-align:left;">Comment</th>
				<th width="67" style="text-align:left;"></th>
				</tr>
			</thead>
		</table>
		<div class="scrolling-content">
		<table class="table table-striped">
			<tbody>
				<?php
					$i = 1;
					foreach($this->issues as $issue) { 
						$issue_date_time = explode(" ",$issue['issue_date']);
						$issuedate = explode("-",$issue_date_time[0]);
						$imageURL = "/images/issues/".$issuedate[0]."/";

						$pic = explode(".", $issue['picture']);
						$issue['large_pic'] = $imageURL.$pic[0]."_large.".$pic[1];
						$issue['thumb_pic'] = $imageURL.$pic[0]."_thumb.".$pic[1];
				?>
				<tr>
				<td width="120"><?php echo $issue['site_name'].$this->pic[$issue['site_id']]; ?></td>
				<td width="150"><?php echo $issue['category_name']; ?></td>
				<td><?php echo $issue['description'].'<br/><a class="image-popup-vertical-fit" href="'.$issue['large_pic'].'"><img src="'.$issue['thumb_pic'].'" data-large="'.$issue['large_pic'].'" width="50px" /></a>'; ?></td>
				<td width="150"><?php echo $issue['location']; ?></td>
				<td width="100"><?php echo $issue['date']; ?></td>
				<td width="250"><div class="three-newest-comments" id="comment-<?php echo $issue['issue_id']; ?>">
					<?php if(!empty($issue['comments'])) { 
						foreach($issue['comments'] as $comment)
						{
							echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>';
							if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
							echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
						}
					} ?>
					</div>
				</td>
				<td align="right" width="50"><a class="add-comment" href="#comment-form" data-id="<?php echo $issue['issue_id']; ?>" data-site_id="<?php echo $issue['site_id']; ?>"><img src="/images/comment1.png" /></a></td>
				</tr>
				<?php
						$i++;
					}
				?>				
			</tbody>
		</table>
		</div>
	</div>
	<br/>
<?php
	}
?>

<!-- comment form -->
<form id="comment-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
	<input type="hidden" name="issue_id" id="comment_issue_id" />
	<input type="hidden" name="site_id" id="comment_site_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit" class="form-btn">
</form>					

<script type="text/javascript">
$(document).ready(function() {
	var id;
	var site_id;
	$('.add-comment').click(function() {
		id = this.dataset.id;
		site_id = this.dataset.site_id;
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
					$("#comment_site_id").val(site_id);
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
						solved: '0',
						display_comment: 1,
						site_id: site_id
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