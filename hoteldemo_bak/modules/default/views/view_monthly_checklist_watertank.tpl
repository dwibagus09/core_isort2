<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<style>
 
	.menu_hide-menu {
		display:none;
	}
	.menu_show-menu {
		
	}
 
 </style>

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle">View Monthly Checklist Watertank</h2>
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
					<th >Date</th>
					<th ></th>
					<th >Option</th>
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
					
				  <td class="date-column"><?php echo $checklist['created_2'];  ?></th>
				 <td></td>
				  <td class="action-column">
					<?php 
					$currentTime = new \DateTime();
					$currentTimeString = $currentTime->format('Y-m-d');
					$yesterday = new \DateTime('yesterday');
					$yesterdayString = $checklist['created'];

					if ($yesterdayString < $currentTimeString ) {
						$menuClass = 'hide-menu';
					} else {
						$menuClass = 'show-menu';
					}
					
					?>
					<!--<?php echo $yesterdayString; ?>-->
					
					<a href="/default/checklistwatertank/viewdetailmonthly/id/<?php echo $checklist['created_3']; ?>" class="action-btn"><img src="/images/view_report.png" width="24" /></a>
					<a href="/default/checklistwatertank/export/id/<?php echo $checklist['created_3']; ?>" class="action-btn"><img src="/images/arrowdownload.png" width="24" /></a>
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
					url: "/default/checklistwatertank/getcommentsbychecklistid",
					data: { id : id }
				}).done(function(response) {
					$("#checklist_id").val(id);
					$( "#comments-content" ).html(response);
				});
			},
			close: function() {	
				$( "#comments-content").html("");
				$.ajax({
                    url: "/default/checklistwatertank/updatecomments",
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
			url: '/default/checklistwatertank/addcomment',
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
					url: "/default/checklistwatertank/getcommentsbychecklistid",
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