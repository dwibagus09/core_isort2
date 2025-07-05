<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">Detail Monthly Checklist Watertank</h2>
			
			
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
		  <table class="table">
			  <thead>
			  <tr>
					<th rowspan="2">Date / Day</th>
					<th rowspan="2">Location</th>
					<th colspan="2">Temperature</th>
					<th colspan="2">Volume</th>
					<th rowspan="2">Location</th>
					<th rowspan="2">PH</th>
					<th rowspan="2">CL</th>
					<th rowspan="2">P Sumpit <br> Utara</th>
					<th colspan="3">P Sumpit <br> Selatan</th>
					<th rowspan="2">Sumpit <br> Kitchen</th>
					<th colspan="2">Genzet</th>
					<th colspan="2">Fuel</th>
					<th rowspan="2">Follow Up</th>
					<th rowspan="2">Remarks</th>
					
			  </tr>
				<tr>
				  <th>Hot</th>
				   <th>Cool</th>
				   <th style="item-align:center">1</th>
				   <th>2</th>
				   <th>1</th>
				   <th>2</th>
				   <th>3</th>
				   <th>1</th>
				   <th>2</th>
				   <th>1</th>
				   <th>2</th>
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
					<td class="date-column"><?php echo $checklist['created']; ?></th>
				  <td class="date-column">PUMP ROOM</td>
				  <td class="date-column"><?php echo $checklist['temper_hot']; ?>&#8451;</td>
				  <td class="date-column"><?php echo $checklist['temper_cool']; ?>&#8451;</td>
				  <td class="date-column"><?php echo $checklist['vol_1']; ?>&#37;</th>
				  <td class="date-column"><?php echo $checklist['vol_2']; ?>&#37;</td>
				  <td class="date-column">SWIMMING POOL</td>
				  <td class="date-column"><?php echo $checklist['ph']; ?></td>
				  <td class="date-column"><?php echo $checklist['cl']; ?></td>
				  <td class="date-column"><?php echo $checklist['sampit_utara']; ?></td>
				  <td class="date-column"><?php echo $checklist['sampit_sel1']; ?></td>
				  <td class="date-column"><?php echo $checklist['sampit_sel2']; ?></td>
				  <td class="date-column"><?php echo $checklist['sampit_sel3']; ?></td>
				  <td class="date-column"><?php echo $checklist['sampit_kitchen']; ?></td>
				  <td class="date-column"><?php echo $checklist['genzet_1']; ?></td>
				  <td class="date-column"><?php echo $checklist['genzet_2']; ?></td>
				  <td class="date-column"><?php echo $checklist['fuel_1']; ?>&#37;</td>
				  <td class="date-column"><?php echo $checklist['fuel_2']; ?>&#37;</td>
				  <td class="date-column"><?php echo $checklist['name']; ?></td>
				  <td class="date-column"><?php echo $checklist['remarks']; ?></td>
				 <!-- <td>
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
				  </td>-->
				 
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


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#digital-checklist-menu").addClass("active");
	$("#digital-checklist-menu .child_menu").show();
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	<?php if($this->err == 1) { ?>
	alert("Checklist template is already exist, please use the existing one.");
	<?php } ?>
	
	<?php if($this->err == 2) { ?>
	alert("Room Number does not exist. Please type the correct room number.");
	<?php } ?>
	
	$( "#template_id" ).change(function() {
		$.ajax({
			url: "/checklist/getroomsbytemplateid/id/"+$(this).val(),
			success: function(response){
				var resp = jQuery.parseJSON(response);
				$('#room_no').empty();
				$.each(resp, function(key, val) {
					$('#room_no').append($("<option value='"+ val.floor +"'>"+val.floor+"</option>"));
				}); 	
			}
		});	
		
	});
	
});	
</script>
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