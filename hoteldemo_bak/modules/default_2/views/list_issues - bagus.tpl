<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<?php if($this->showBuildingServiceIssues == 1) { 
if($this->site_id == 1) { ?>
<style>
	.process-model li { width: 32%; }
	.process-model { max-width: 500px; }
</style>
<?php } else { ?>
	<style>
		.process-model li { width: 16%; }
		.process-model { max-width: 100%; }
		@media (max-width:500px){
			.process-model li { width: 30%; }
		}
	</style>
<?php }
} else { 
	if($this->site_id == 1) {
?>
	<style>
		.process-model li { width: 49%; }
		.process-model { max-width: 500px; }
		@media (max-width:500px){
			.process-model li { width: 45%; }
			.process-model { max-width: 100%; }
		}
	</style>
<?php } else { ?>
	<style>
		.process-model li { width: 19%; }
		.process-model { max-width: 800px; }
		@media (max-width:500px){
			.process-model li { width: 30%; }
			.process-model { max-width: 100%; }
		}
	</style>
<?php } } ?>

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h1 class="pagetitle">Opened Kaizen</h1>
		
		<div id="list-issue-desktop">
			<div id="issue-tabs" class="tabs">
				<nav>
				    <ul class="nav nav-tabs process-model more-icon-process" role="tablist">
						<?php if($this->showHousekeepingIssues == 1) { ?>
						  <li role="presentation"><a id="housekeepingicon" href="#housekeeping" aria-controls="housekeeping" role="tab" data-toggle="tab" data-id="2"><img src="/images/housekeeping_silver.png" />
							<p>Housekeeping (<?php echo intval($this->totalHKIssues['total']); ?>)</p>
							</a></li>
						<?php } ?>
						<?php if($this->showEngineeringIssues == 1) { ?>
						  <li role="presentation"><a id="engineeringicon" href="#engineering" aria-controls="engineering" role="tab" data-toggle="tab" data-id="6"><img src="/images/engineering_silver.png" />
							<p>Engineering (<?php echo intval($this->totalEngIssues['total']); ?>)</p>
							</a></li>
						<?php } ?>
						<?php if($this->showEngineeringIssues == 1) { ?>
						  <li role="presentation"><a class="wo" id="engineeringwoicon" href="#engineering" aria-controls="engineeringwo" role="tab" data-toggle="tab" data-id="6"><img src="/images/engineering_silver.png" />
							<p>Engineering WO (<?php echo intval($this->totalEngIssues['total']); ?>)</p>
							</a></li>
						<?php } ?>
						
						<?php if($this->site_id == 2) { ?>
							<?php if($this->showSecurityIssues == 1) { ?>
							  <li role="presentation"><a id="securityicon" href="#security" aria-controls="security" role="tab" data-toggle="tab" data-id="1"><img src="/images/security_silver.png" />
								<p>Security, Safety, Parking (<?php echo intval($this->totalSecIssues['total']); ?>)</p>
								</a></li>
							<?php } ?>
							<?php if($this->showParkingIssues == 1) { ?>
							  <li role="presentation"><a id="safetyicon" href="#safety" aria-controls="safety" role="tab" data-toggle="tab" data-id="5"><img src="/images/safety_silver.png" />
								<p>Fit Out (<?php echo intval($this->totalParkIssues['total']); ?>)</p>
								</a></li>
							<?php } ?>
							<?php if($this->showTenantRelationIssues == 1) { ?>
							  <li role="presentation"><a id="bsicon" href="#bs" aria-controls="bs" role="tab" data-toggle="tab" data-id="11"><img src="/images/bs_silver.png" />
								<p>Tenant Relation (<?php echo intval($this->totalTRIssues['total']); ?>)</p>
								</a></li>
							<?php } ?>
						<?php } ?>
						<?php if($this->showBuildingServiceIssues == 1) { ?>
						  <li role="presentation"><a id="usericon" href="#user" aria-controls="user" role="tab" data-toggle="tab" data-id="10"><img src="/images/user_silver.png" />
							<p>Human Operations (<?php echo intval($this->totalBSIssues['total']); ?>)</p>
							</a></li>
						<?php } ?>
                    </ul>
				</nav>
				<div class="filter">
        			<form id="filter-form" action="/default/issue/listissues"  method="post">
        				<div class="filter-field"> 
        					ID : <input type="text" id="filter_issue_id" name="issue_id" value="<?php echo $this->issue_id; ?>" >
        				</div>
        				<div class="filter-field"> 
        					Start Date : <input type="text" id="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>">
        				</div>
        				<div class="filter-field"> 
        					End Date :	<input type="text" id="end_date" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>">
        				</div>
        				<div class="filter-field"> <input type="submit" id="filter-issue" name="filter-issue" value="Search" style="width:70px;" class="form-btn"></div>
						<div class="filter-field"><img id="exporttopdf" class="exporttopdf-btn" src="/images/newlogo_pdf.png"></div>
        			</form>
        		</div>
				<div class="content">
                  <div role="tabpanel" class="tab-pane" id="housekeeping">
     
                  </div>
                  <div role="tabpanel" class="tab-pane" id="engineering">
  
                  </div>
				
				  <div role="tabpanel" class="tab-pane active" id="security">
                      
                  </div>
                  <div role="tabpanel" class="tab-pane" id="safety">
   
                  </div>
				  <div role="tabpanel" class="tab-pane" id="parking">
   
                  </div>
				  <div role="tabpanel" class="tab-pane" id="bs">
   
                  </div>
				  <div role="tabpanel" class="tab-pane" id="user">
  
                  </div>
				</div><!-- /content -->
			</div><!-- /tabs -->
		</div>
	  </div>

		
		  <form id="close-issue-form" action="/default/issue/submitsolveissue"  method="post" enctype="multipart/form-data">
		    <h2 style="font-weight: bold; text-align: center; border-bottom: 1px solid; padding-bottom: 5px;">Close Kaizen</h2>
			<input id="issue_id" name="issue_id" type="hidden" />	
			<input id="solved-picture" name="solved-picture" type="file" accept="image/jpeg" />	
			<div id="image-holder-close"></div>
			<div id="issue-detail-solve"></div>
			<div id="lost-found-option">
				<?php if(!empty($this->lostFoundOptions)) { ?>
						<select id="lostfound-select" name="lost_found_option_id">
					<?php foreach($this->lostFoundOptions as $option) {	?>
								<option value="<?php echo $option['option_id']; ?>"><?php echo $option['options']; ?></option>
						<?php } ?>
					</select>
				<?php } ?>
			</div>
			<div id="comment-field">
				Comment:<br/>
				<textarea rows="4" cols="50" id="comment-txtarea" name="comment" required></textarea><br/>
			</div>
			<div style="text-align:center;">
				<input type="button" id="cancel-solved-issue" name="cancel-solved-issue" value="Cancel" class="form-btn" /> <input type="submit" id="solve-issue-submit" name="solve-issue-submit" value="Submit" class="form-btn">
			</div>
		</form>
		
		<form id="issue-progress-form" action="/default/issue/submitprogressissue"  method="post" enctype="multipart/form-data">
			<h2 style="font-weight: bold; text-align: center; border-bottom: 1px solid; padding-bottom: 5px;">Add Progress Image</h2>
			<input id="progress_issue_id" name="progress_issue_id" type="hidden" />	
			<input id="progress-picture" name="progress-picture" type="file" accept="image/jpeg" <?php if($this->site_id == 1) { ?>capture="capture"<?php } ?> />	
			<div id="image-holder-progress"></div>
			<div id="issue-detail-progress"></div>
			<div style="text-align:center;">
				<input type="button" id="cancel-progress-issue" name="cancel-progress-issue" value="Cancel" class="form-btn" /> <input type="submit" id="progress-issue-submit" name="progress-issue-submit" value="Submit" class="form-btn">
			</div>
		</form>
		
		<!-- comment form -->
		  <form id="comment-form" class="mfp-hide white-popup-block"  enctype="multipart/form-data">
			<input type="hidden" name="issue_id" id="comment_issue_id" />
			<div id="comments-content"></div>
			<label for="name">Comment</label><br/>
			<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
			<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
			<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit" class="form-btn">
		  </form>
		
		<!-- WO Form -->		
		<form id="wo-form" class="mfp-hide white-popup-block">
			<input type="hidden" name="issue_id" id="wo_issue_id" />
			<label for="startdate">Start Date</label><br/>
			<input type="text" name="wo_startdate" name="startdate" class="datepicker" value="" placeholder="yyyy-mm-dd" autocomplete="off" required> 
			<?php /*<input type="number" name="wo_starthour" value="" style="width:40px" placeholder="hh" required> : <input type="number" name="wo_startmin" value="" style="width:40px" placeholder="mm" required>*/ ?><br/>
			<label for="enddate">End Date</label><br/>
			<input type="text" name="wo_enddate" class="datepicker" placeholder="yyyy-mm-dd" autocomplete="off" required>
			<?php /*<input type="number" name="wo_endhour" value="" style="width:40px" placeholder="hh" required> : <input type="number" name="wo_endmin" value="" style="width:40px" placeholder="mm" required> */ ?><br/>
			<label for="expected_work_time">Expected Work Time</label><br/>
			<input type="number" name="expected_work_time" value="" style="width:40px" required>
			Hour(s)<br/>
			<label for="worker">Worker</label><br/>
			<select id="workers-select" name="worker_id[]" multiple required>
			<?php if(!empty($this->workers)) { ?>
				<?php foreach($this->workers as $worker) {	?>
							<option value="<?php echo $worker['user_id']; ?>"><?php echo $worker['name']; ?></option>
					<?php } ?>
			<?php } ?>
			</select><br/>
			<label for="assigned_comment">Comment</label><br/>
			<textarea rows="4" cols="25" name="assigned_comment" id="assigned_comment" required></textarea>
			<input type="submit" id="add-wo-submit" name="add-wo-submit" value="Submit" class="form-btn">
		  </form>  
		  
</div>
<!-- /page content -->


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
	var selectedID;
	var addCommentIntervalId;
	var curHref = "";

	$("#close-issue-form")[0].reset();
	$("#issue-progress-form")[0].reset();

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	var selCatId = '<?php echo $this->selectedCategory; ?>';
	$('.nav-tabs #securityicon img').attr("src", "/images/security_gold.png");
	$("body").mLoading();
	$.ajax({
		async : true,
		url: "/default/issue/showissuesbycategory",
		data: { category : selCatId,
				issue_id : '<?php echo $this->issue_id; ?>',
				start_date : '<?php echo $this->start_date; ?>',
				end_date : '<?php echo $this->end_date; ?>'
		}
	}).done(function(response) {
		$("#security").addClass("");
		/*$("#security").html(response);*/
		var href="";
		if(selCatId == 1)
		    href = "#security";
		else if(selCatId == 2)
		    href = "#housekeeping";
		else if(selCatId == 3)
		    href = "#safety";
		else if(selCatId == 5)
		    href = "#safety";
		else if(selCatId == 6)
		    href = "#engineering";
		else if(selCatId == 10)
		    href = "#user";
		else if(selCatId == 11)
		    href = "#bs";
			
		$(href).html(response);		
		$("body").mLoading('hide');
		$('.nav-tabs #securityicon img').attr("src", "/images/security_silver.png");
		$( "#security" ).removeClass( "active" );
		$(".process-model  a[href='#security']").parent().removeClass( "active" );
		$('.nav-tabs '+href+'icon img').attr("src", "/images/"+href.replace("#","")+"_gold.png");
		$curr = $(".process-model  a[href='" + href + "']").parent();
		$curr.addClass("active");
		$(href).addClass( "active" );
	});
	
	$( ".nav-tabs li a" ).mouseover(function() {
	    var href = $(this).attr('href');
        $('img', $(this)).attr("src", "/images/"+href.replace("#","")+"_gold.png");
    });

    $( ".nav-tabs li a" ).mouseleave(function() {
	    var href = $(this).attr('href');
	    if(href != curHref)
	    {
            $('img', $(this)).attr("src", "/images/"+href.replace("#","")+"_silver.png");
	    }
    });

	$('.nav-tabs li a').click(function() {
		$("body").mLoading();
		var cat_id = this.dataset.id;
		var href = $(this).attr('href');
		curHref = href;
		$('.nav-tabs #securityicon img').attr("src", "/images/security_silver.png");
		$('.nav-tabs #safetyicon img').attr("src", "/images/safety_silver.png");
		$('.nav-tabs #parkingicon img').attr("src", "/images/parking_silver.png");
		$('.nav-tabs #housekeepingicon img').attr("src", "/images/housekeeping_silver.png");
		$('.nav-tabs #engineeringicon img').attr("src", "/images/engineering_silver.png");
		$('.nav-tabs #usericon img').attr("src", "/images/user_silver.png");
		$('.nav-tabs #bsicon img').attr("src", "/images/bs_silver.png");
	    $('img', $(this)).attr("src", "/images/"+href.replace("#","")+"_gold.png");
		
		$.ajax({
			async : true,
			url: "/default/issue/showissuesbycategory",
			data: { category : cat_id,
					issue_id : '<?php echo $this->issue_id; ?>',
					start_date : '<?php echo $this->start_date; ?>',
					end_date : '<?php echo $this->end_date; ?>'
			}
		}).done(function(response) {
		    $( "#security" ).removeClass( "active" );
		    $( "#safety" ).removeClass( "active" );
		    $( "#parking" ).removeClass( "active" );
		    $( "#housekeeping" ).removeClass( "active" );
		    $( "#engineering" ).removeClass( "active" );
		    $( "#user" ).removeClass( "active" );
		    $( "#bs" ).removeClass( "active" );
			$(href).html(response);
			$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
			$( href ).addClass( "active" );
			$("body").mLoading('hide');
		});
	});

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
	
	function filePreviewClose(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.addEventListener('load', function() {
				$(".filter").hide();
				$(".pagetitle").hide();
				$("#image-holder-close").html("");
				$("<img />", {
					"src": reader.result,
					"class": "thumb-image"
				}).appendTo("#image-holder-close");
			});
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#solved-picture").change(function() {
		$('#list-issues').hide();
		$('.paging').hide();
		$('#close-issue-form').show();
		$(window).scrollTop(0);
		filePreviewClose(this);
	});
	
	$(".sp-label").click(function() {
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
	
	function filePreviewProgress(input) {
	    $("#image-holder-progress").html("");
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.addEventListener('load', function() {
				$(".filter").hide();
				$(".pagetitle").hide();
				$("#image-holder").html("");
				$("<img />", {
					"src": reader.result,
					"class": "thumb-image"
				}).appendTo("#image-holder-progress");
			});
			reader.readAsDataURL(input.files[0]);
		}
	}
	
	$("#progress-picture").change(function() {
		$('#list-issues').hide();
		$('.paging').hide();
		$('#issue-progress-form').show();
		$(window).scrollTop(0);
		filePreviewProgress(this);
	});
	
	/*$(".sp-label-progress").click(function() {
	    console.log(this.dataset);
		$('#issue-progress-form')[0].reset();
		$("#progress_issue_id").val(this.dataset.id);		
		var issueDetail = $("#issue-detail-"+this.dataset.id).html();
		$("#issue-detail-progress").html(issueDetail);
	});*/
	
	$( "#cancel-solved-issue").click(function() {
		$('#list-issues').show();
		$('.paging').show();
		$('#close-issue-form').hide();
		$(".filter").show();
		$(".pagetitle").show();
	});

	$( "#cancel-progress-issue").click(function() {
		$('#list-issues').show();
		$('.paging').show();
		$('#issue-progress-form').hide();
		$(".filter").show();
		$(".pagetitle").show();
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
				}, 15000);	*/	
			},
			close: function() {	
				clearInterval(addCommentIntervalId);
				$( "#comments-content").html("");
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
			}
		}
	});
	
	$('#comment-form').on('submit', function(event){
		event.preventDefault(); 
		$("body").mLoading();
		$.ajax({
			url: '/default/issue/addcomment',
			type: 'POST',
			data: new FormData($('#comment-form')[0]),
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
			success: function(id) {
				$.ajax({
					url: "/default/issue/getcommentsbyissueid",
					data: { id : id }
				}).done(function(response) { 
					$("#comment_issue_id").val(id);
				$( "#comments-content" ).html(response);
					$('#comment-form')[0].reset();
				});
				$("body").mLoading('hide');
			}
		});
	});
	
	$('#close-issue-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	
	$(".create-report").click(function() {
		var arrCB = {};
		var i = 0;
		$('.chk-issue-id:checkbox:checked').each(
			function(){
				arrCB[i] = this.value;
				i++;
			}
		);
		var ids = JSON.stringify(arrCB);
		location.href="/default/report/createreportfromissue/ids/"+ids;
	});
	
	$("#exporttopdf").click(function() {
		var url = '/default/issue/exportissuestopdf';
		if($('#filter_issue_id').val() > 0)
		{
			url = url + "/id/" + $('#filter_issue_id').val();
		}
		if($('#start_date').val() != "")
		{
			url = url + "/start_date/" + $('#start_date').val();
		}
		if($('#end_date').val() != "")
		{
			url = url + "/end_date/" + $('#end_date').val();
		}
		window.open(url);
	});	
	
});	
</script>