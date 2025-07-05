<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h1 class="pagetitle">Closed Kaizen</h1>
		<div id="list-issue-desktop">
			<div id="issue-tabs" class="tabs">
				<nav>
				    <ul class="nav nav-tabs process-model more-icon-process" role="tablist">
                      <li role="presentation"><a id="securityicon" href="#security" aria-controls="security" role="tab" data-toggle="tab" data-id="1"><img src="/images/security_silver.png" />
                        <p>Security (<?php echo intval($this->totalSecIssues['total']); ?>)</p>
                        </a></li>
                      <li role="presentation"><a id="safetyicon" href="#safety" aria-controls="safety" role="tab" data-toggle="tab" data-id="3"><img src="/images/safety_silver.png" />
                        <p>Safety (<?php echo intval($this->totalSafIssues['total']); ?>)</p>
                        </a></li>
                      <li role="presentation"><a id="parkingicon" href="#parking" aria-controls="parking" role="tab" data-toggle="tab" data-id="5"><img src="/images/parking_silver.png" />
                        <p>Parking &amp; Traffic (<?php echo intval($this->totalParkIssues['total']); ?>)</p>
                        </a></li>
                      <li role="presentation"><a id="housekeepingicon" href="#housekeeping" aria-controls="housekeeping" role="tab" data-toggle="tab" data-id="2"><img src="/images/housekeeping_silver.png" />
                        <p>Housekeeping (<?php echo intval($this->totalHKIssues['total']); ?>)</p>
                        </a></li>
                      <li role="presentation"><a id="engineeringicon" href="#engineering" aria-controls="engineering" role="tab" data-toggle="tab" data-id="6"><img src="/images/engineering_silver.png" />
                        <p>Engineering (<?php echo intval($this->totalEngIssues['total']); ?>)</p>
                        </a></li>
                      <li role="presentation"><a id="bsicon" href="#bs" aria-controls="bs" role="tab" data-toggle="tab" data-id="10"><img src="/images/bs_silver.png" />
                        <p>Building Service (<?php echo intval($this->totalBSIssues['total']); ?>)</p>
                        </a></li>
                    </ul>
				</nav>
		<div class="filter">
			<form id="filter-form" action="/default/issue/solvedissues"  method="post">
				<div class="filter-field">
        					ID : <input type="text" id="issue_id" name="issue_id" value="<?php echo $this->issue_id; ?>" >
				</div>
				<div class="filter-field"> 
        					Start Date : <input type="text" id="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>">
				</div>
				<div class="filter-field"> 
        					End Date :	<input type="text" id="end_date" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>">
				</div>
				<div class="filter-field"><input type="submit" id="filter-issue" name="filter-issue" value="Search" style="width:70px;" class="form-btn"></div>
						<div class="filter-field"><img id="exporttopdf" class="exporttopdf-btn" src="/images/newlogo_pdf.png"></div>
			</form>
		</div>
				<div class="content">
                  <div role="tabpanel" class="tab-pane active" id="security">
                      
                  </div>
                  <div role="tabpanel" class="tab-pane" id="safety">

                  </div>
                  <div role="tabpanel" class="tab-pane" id="parking">
   
                  </div>
                  <div role="tabpanel" class="tab-pane" id="housekeeping">
     
                  </div>
                  <div role="tabpanel" class="tab-pane" id="engineering">
  
                  </div>
                  <div role="tabpanel" class="tab-pane" id="bs">
                    
                  </div>
				</div><!-- /content -->
			</div><!-- /tabs -->
		</div>
		</div>		
	</div>
  </div>
</div>
<!-- /page content -->

<!-- comment form -->
<form action="" id="comment-form"  class="mfp-hide white-popup-block" >
	<input type="hidden" name="issue_id" id="comment_issue_id" />
	<div id="comments-content"></div>
	<label for="name">Comment</label><br/>
	<textarea rows="4" cols="25" name="comment" id="comment"></textarea>
	<input type="file" name="attachment" id="attachment" class="attachment-uploader" style="margin:7px 0px;">
	<input type="submit" id="add-comment-submit" name="add-comment-submit" value="Submit" class="form-btn">
</form>
<!-- End of comment form -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	var selectedID;
	var addCommentIntervalId;
	var curHref = "";

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
					end_date : '<?php echo $this->end_date; ?>',
					solved: 1
			}
		}).done(function(response) {
		$("#security").addClass("");
		$("#security").html(response);
		var href="";
		if(selCatId == 1)
		    href = "#security";
		else if(selCatId == 2)
		    href = "#housekeeping";
		else if(selCatId == 3)
		    href = "#safety";
		else if(selCatId == 5)
		    href = "#parking";
		else if(selCatId == 6)
		    href = "#engineering";
		else if(selCatId == 10)
		    href = "#bs";
		$(href).html(response);
		$('.nav-tabs #securityicon img').attr("src", "/images/security_silver.png");
		$( "#security" ).removeClass( "active" );
		$(".process-model  a[href='#security']").parent().removeClass( "active" );
		$('.nav-tabs '+href+'icon img').attr("src", "/images/"+href.replace("#","")+"_gold.png");
		$curr = $(".process-model  a[href='" + href + "']").parent();
		$curr.addClass("active");
		$(href).addClass( "active" );
			$("body").mLoading('hide');
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
		$('.nav-tabs #bsicon img').attr("src", "/images/bs_silver.png");
	    $('img', $(this)).attr("src", "/images/"+href.replace("#","")+"_gold.png");
		$.ajax({
			async : true,
			url: "/default/issue/showissuesbycategory",
			data: { category : cat_id,
					issue_id : '<?php echo $this->issue_id; ?>',
					start_date : '<?php echo $this->start_date; ?>',
					end_date : '<?php echo $this->end_date; ?>',
					solved: 1
			}
		}).done(function(response) {
		    $( "#security" ).removeClass( "active" );
		    $( "#safety" ).removeClass( "active" );
		    $( "#parking" ).removeClass( "active" );
		    $( "#housekeeping" ).removeClass( "active" );
		    $( "#engineering" ).removeClass( "active" );
		    $( "#bs" ).removeClass( "active" );
			$(href).html(response);
			$( href ).addClass( "active" );
			$("body").mLoading('hide');
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
	
	$('.add-comment').click(function() {
		selectedID = this.dataset.id;
	});
	
	$('.add-comment').magnificPopup({
		type: 'inline',
		preloader: false,
		focus: '#comment',
		callbacks: {
			open: function() {
			  var id = selectedID;
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
	
	/*setInterval(function(){ 
		$.ajax({
			url: "/default/issue/getupdatedsolvedcomments",
		}).done(function(response) {
			var resp = jQuery.parseJSON(response);
			$.each( resp, function( idx, val ) {
				$( "#comment-"+val['id'] ).html(val['comment']);
			});
		});
	}, 10000);*/
	
	$("#exporttopdf").click(function() {
		var url = '/default/issue/exportissuestopdf/solved/1';
		if($('#issue_id').val() > 0)
		{
			url = url + "/id/" + $('#issue_id').val();
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