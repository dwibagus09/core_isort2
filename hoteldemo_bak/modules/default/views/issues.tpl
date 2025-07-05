<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<?php if($this->totalPage > 0) { ?>
<div class="paging">
    <div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Kaizen </div>
    <div class="paging-section">
        <?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-step-backward" ></i></a><?php } ?>
        <?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-caret-left" ></i></a><?php } ?>
        <span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
        <?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
        <?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-caret-right" ></i></a><?php } ?>
        <?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-step-forward"></i></a><?php } ?>
    </div>
</div>
<?php } ?>

<div id="list-issues-table">
<table id="list-issues" class="listissue2">
    <tr>
        <?php if( $issue['solved'] == 1) {
		 echo '<th class="bubble">Image</th>';
		 }
		 else{
			echo '<th class="bubble"></th>';
		 }?>
        <th class="bubble">Detail</th>
        <th class="comment-column">Comments</th>
    </tr>
    <?php
        if(!empty($this->issues))
        {
            $i = 1;
            foreach($this->issues as $issue) { 
				if(!empty($issue['kejadian']))	$kejadian = " - ".$issue['kejadian']; 
				else $kejadian = "";

				if(!empty($issue['modus']))	$modus = " - ".$issue['modus']; 
				else $modus = "";

				if(!empty($issue['manpower_name']))	$manpower = " - ".$issue['manpower_name']; 
				else $manpower = "";


				if(!empty($issue['floor']))	
				{
					$floor = $issue['area_name']." - ".$issue['floor']." - "; 
				}
				else
				{ 
					$floor = "";
					$tenant_umum = "";
				}

				if(!empty($issue['lost_found']))
				{
					$lost_found_option = ' <br/> '.$issue['lost_found'];
				}
    ?>

        <!--<tr <?php if($i%2==0) echo 'class="even-row"'; else echo 'class="row-event"' ?>>-->
		<tr>
            <td width="20">
				<?php if($this->category_id == 6 && $issue['solved'] == 0  && $this->addWorkOrder == 1 && $issue['show_add_wo_btn'] == 1 && $this->wo == 1) { ?>
				<br/><a id="wo<?php echo $issue['issue_id']; ?>" class="add-wo" href="#wo-form" data-id="<?php echo $issue['issue_id']; ?>"><img src="/images/wo.png" alt="Create Work Order"/></a></div>
				<!--<label class="sp-label-progress" for="progress-picture" data-id="<?php echo $issue['issue_id']; ?>">
					<img src="/images/progress.png" class="progress-icon" />
				</label>!-->
				<?php }
				
				elseif( $issue['solved'] == 0) {
			?>
				<br/>
                <label class="sp-label-progress" for="progress-picture" data-id="<?php echo $issue['issue_id']; ?>">
					<img src="/images/progress.png" class="progress-icon" />
				</label>
				<label class="sp-label-close" for="solved-picture" data-id="<?php echo $issue['issue_id']; ?>" data-issuetype="<?php echo $issue['issue_type_id']; ?>">
					<img src="/images/camera.png" class="solved-icon" />
				</label>
			<?php } ?>
			</td>

            <td align="left" style="text-align:left;" width="45%">
				<div class="kaizen-img-wrapper">
					<?php if($issue['issue_type_id'] != 19) { ?>
					<div class="kaizen-img">
						<span style="font-size:11px;font-weight:bold;">Opened Image:</span><br/>
						<a class="image-popup-vertical-fit" href="<?php echo $issue['large_pic']; ?>"><img src="<?php echo $issue['thumb_pic']; ?>" data-large="<?php echo $issue['large_pic']; ?>" width="50px" /></a><br/>
					</div>
					<?php } ?>
					<div class="kaizen-img">
						<?php if(!empty($issue['progress_images'])) { ?>
							<span style="font-size:11px;font-weight:bold;">Progress Image(s):</span><br/>
							<?php foreach($issue['progress_images'] as $progress_image) { ?>
								<a class="image-popup-vertical-fit" href="<?php echo $progress_image['large_pic']; ?>"><img src="<?php echo $progress_image['thumb_pic']; ?>" data-large="<?php echo $progress_image['large_pic']; ?>" width="50px" /></a> 
						<?php } } ?>
					</div>
					<div class="kaizen-img">
						<?php if(!empty($issue['solved_picture'])) { ?>
						<span style="font-size:11px;font-weight:bold;">Closed Image:</span><br/>
						<a class="image-popup-vertical-fit" href="<?php echo $issue['large_solved_pic']; ?>"><img src="<?php echo $issue['thumb_solved_pic']; ?>" data-large="<?php echo $issue['large_solved_pic']; ?>" width="50px" /></a>
						<?php } ?>
					</div>
				</div>

				<div class="kaizen-detail-wrapper">
					<?php
						if($issue['solved'] == 1) {
							 echo '<i class="fa fa-calendar-alt"></i>&nbsp; &nbsp;'.$issue['issue_date_time'].'<br/><i class="fas fa-calendar-check"></i>&nbsp; &nbsp;'.$issue['solved_issue_date_time'];
						}
						else {
							echo '<i class="fa fa-calendar-alt"></i>&nbsp; &nbsp;'.$issue['issue_date_time']." (";
							if(!empty($issue['count_years'])) { 
								echo $issue['count_years']." year"; 
								if($issue['count_years'] > 1) echo "s "; 
							} 
							if(!empty($issue['count_months'])) { 
								echo $issue['count_months']." month"; 
								if($issue['count_months'] > 1) echo "s"; 
							} 
							echo " ".$issue['count_days']." day";
							if($issue['count_days']>1) echo "s";
							echo ")";
						}
					?>
					<?php echo '<br/><i class="fas fa-portrait"></i>&nbsp; &nbsp;'.$issue['name']; ?>
					<?php echo '<br/><i class="fas fa-map-marked-alt"></i>&nbsp; &nbsp;'.$floor.$issue['location']; ?>
					<?php echo '<br/><i class="fas fa-exclamation-triangle"></i>&nbsp; &nbsp;'.$issue['issue_type'].$kejadian.$modus.$manpower.$lost_found_option; ?>
					<div id="issue-detail-<?php echo $issue['issue_id']; ?>"><i class="fas fa-info-circle"></i>&nbsp; &nbsp;<?php echo nl2br(trim($issue['description'])); ?></p></div>
				</div>
			</td>
		

            <td align="center">
                <a class="add-comment" href="#comment-form" data-id="<?php echo $issue['issue_id']; ?>"><img src="/images/comment1.png" /></a>
                <div class="three-newest-comments-chat " id="comment-<?php echo $issue['issue_id']; ?>">
                <?php if(!empty($issue['comments'])) { 
					
                    foreach($issue['comments'] as $comment)
                    {	
						if($comment['user_id'] == ucwords(strtolower($this->ident['user_id']))){
                        echo '<div class="msg-chat sent" ><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>'.$comment['comment_date'];
						}else{
							echo '<div class="msg-chat rcvd" ><strong>'.$comment['name'].' : </strong>'.$comment['comment'].'<br/>'.$comment['comment_date'];
						}
						if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl."/comments/".substr($comment['comment_date'],0,4)."/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
                        echo '<div class="">'.$comment['comment_date_']."</div></div>";
                    }
                } ?>
                </div>
            </td>
        </tr>
	
    <?php
                $i++;
            }
        }
    ?>
</table>
</div>
<?php if($this->totalPage > 0) { ?>
<div class="paging">
    <div class="paging-section">
        <?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-step-backward" ></i></a><?php } ?>
        <?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-caret-left" ></i></a><?php } ?>
        <span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
        <?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
        <?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-caret-right" ></i></a><?php } ?>
        <?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-step-forward"></i></a><?php } ?>
    </div>
    <div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Kaizen </div>
</div>
<?php } ?>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">


$(document).ready(function() {
	var selectedID;
	var addCommentIntervalId;

	$("#engineeringwoicon").click(function() {
		$("label").hide();
		//alert('Halaman ini sudah di loading');
	}); 

	$("#engineeringicon").click(function() {
		$(".add-wo").hide();
		//alert('Halaman ini sudah di loading');
	}); 

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$('#close-issue-form').hide();
	$('#issue-progress-form').hide();
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	

	$("#solved-picture").change(function() {
		$('#list-issues').hide();
		$('.listissue2').hide();
		$('.paging').hide();
		$('#close-issue-form').show();
		$(window).scrollTop(0);
	});                       

	$(".sp-label-close").click(function() {
		$('#close-issue-form')[0].reset();
		$("#issue_id").val(this.dataset.id);		
		var issueDetail = $("#issue-detail-"+this.dataset.id).html();
		$("#issue-detail-solve").html(issueDetail);
		if(this.dataset.issuetype == 3)
		{
			$('#lost-found-option').show();
		}
		else
		{
			$('#lost-found-option').hide();			
			$('#lostfound-select').val(0);
		}
	}); 
	
	$("#progress-picture").change(function() {
		$('#list-issues').hide();
		$('.listissue2').hide();
		$('.paging').hide();
		$('#issue-progress-form').show();
		$(window).scrollTop(0);
		/*filePreviewProgress(this);*/
	});
	
	$(".sp-label-progress").click(function() {
		$('#issue-progress-form')[0].reset();
		$("#progress_issue_id").val(this.dataset.id);		
		var issueDetail = $("#issue-detail-"+this.dataset.id).html();
		$("#issue-detail-progress").html(issueDetail);
	});
	

	$('.add-comment').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		closeOnBgClick: false,
		callbacks: {
			open: function() {
			  var id = selectedID; /*this._lastFocusedEl.dataset.id;*/
			  $.ajax({
					url: "/default/issue/getcommentsbyissueid",
					data: { id : id }
				}).done(function(response) {
					$("#comment_issue_id").val(id);
					$( "#comments-content" ).html(response);
				});
				/*addCommentIntervalId = setInterval(function(){ 
					$.ajax({
						url: "/default/issue/getcommentsbyissueid",
						data: { id : id }
					}).done(function(response) {
						$("#comment_issue_id").val(id);
						$( "#comments-content" ).html(response);
					});
				}, 15000);*/		
			},
			close: function() {	
				clearInterval(addCommentIntervalId);
				$( "#comments-content").html("");
                $.ajax({
                    url: "/default/issue/removecommentcache",
                    data: { 
                        start:'<?php echo $this->start; ?>',
                        start_date: '<?php echo $this->start_date; ?>',
                        end_date: '<?php echo $this->end_date; ?>',
                        category: '<?php echo $this->category_id; ?>',
                        issue_id: '<?php echo $this->issue_id; ?>',
                        solved: '<?php echo $this->solved; ?>'
                     }
                }).done(function(response) {
					console.log("cache berhasil dihapus");
                    $.ajax({
						url: "/default/issue/updatecomments",
						data: { 
							start:'<?php echo $this->start; ?>',
							start_date: '<?php echo $this->start_date; ?>',
							end_date: '<?php echo $this->end_date; ?>',
							category: '<?php echo $this->category_id; ?>',
							issue_id: '<?php echo $this->issue_id; ?>',
							solved: '<?php echo $this->solved; ?>'
						}
					}).done(function(response) {
						var resp = jQuery.parseJSON(response);
						$.each( resp, function( idx, val ) {
							$( "#comment-"+val['id'] ).html(val['comment']);
						});
					});
                });
			}
		}
	});

	

    $( ".paging-button").click(function() {
		try {
			$("body").mLoading();
        var cat_id = '<?php echo $this->category_id; ?>';
		var wo = '<?php echo $this->wo; ?>';
		$.ajax({
			url: this.dataset.href,
			data: { category : cat_id,
					issue_id : '<?php echo $this->issue_id; ?>',
					start_date : '<?php echo $this->start_date; ?>',
					end_date : '<?php echo $this->end_date; ?>',
					wo : wo
			}
		}).done(function(response) {
		    var href="";
			if(cat_id == 1)
			    href = "#security";
			else if(cat_id == 2)
			    href = "#housekeeping";
			else if(cat_id == 3)
			    href = "#safety";
			else if(cat_id == 5)
			    href = "#safety";
			else if(cat_id == 6)
			    href = "#engineering";
			else if( cat_id == 6)
			    href = "#engineering_wo";
			else if(cat_id == 11)
			    href = "#bs";
			$(href).html(response);
            $("body").mLoading('hide');
		});
		} catch (error) {
			console.error(error);
		}
        
	});
	

	<?php if($this->category_id == 6) { ?>
	
	$('.add-wo').click(function() {
		selectedID = this.dataset.id;
		$("#wo_issue_id").val(selectedID);
	});
	
	$('.add-wo').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#wo_startdate',
		closeOnBgClick: false,
		callbacks: {
			open: function() {
			  
			  $('#wo-form')[0].reset();
			},
			close: function() {	
			
			}
		}
	});
	
	$('#wo-form').on('submit', function(event){
		event.preventDefault(); 
		$('body').mLoading();
		
		if ($(this).data('submitted') === true) {
			event.preventDefault(); 
		} else {
			$(this).data('submitted', true);
			
			$.ajax({
				url: '/default/workorder/addworkorder',
				type: 'POST',
				data: $(this).serialize(),
				success: function(response) {
					$("body").mLoading('hide');	
					if(response > 0)
					{
						alert("Saved Successfully");
						$.magnificPopup.close();
						$("#wo"+response).hide();
					}
					else
					{
						alert("Creating work order failed, please try again");
					}
				}
			});
		}
	  });
	
	<?php } ?>
});	
</script>