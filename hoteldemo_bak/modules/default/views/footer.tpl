	</div>
	<!-- end of content_wrapper -->
		
	<?php if($this->showIssueFinding == 1) { ?>		
		<form id="issue-form" action=""  method="post" enctype="multipart/form-data">
			<div id="issue-finding-field" class="col-md-12 col-sm-12 col-xs-12">	
				<div class="menu-icon">								
					<label for="picture-issue">
						<div class="icon-title"><img width="30" src="/images/isort_logo_white.png" /></div>	
					</label>
				</div>
				<?php if($this->kaizenNoPhoto != 1) { ?><input id="picture-issue" name="picture" type="file" accept="image/*" <?php if($this->site_id == 1) { ?>capture="capture"<?php } ?> /><?php } ?>	
			</div>
			<div id="other-info" style="display:none;">
				<h1 class="page-title">Submit Kaizen</h1>
				<div id="image-holder"></div>
				<?php if(!empty($this->kaizenCategories)) { ?>
				<div id="category-field">
					Department:<br/>
					<select id="category-select" name="category" required>
					<option value="" disabled selected hidden>Select Department</option>
					<?php foreach($this->kaizenCategories as $category) { ?>
					  <option value="<?php echo $category['category_id']; ?>"><?php echo $category['category_name']; ?></option>
					 <?php } ?>
					</select><br/>
					<?php /*<input type="button" id="cancel-issue" name="cancel-issue" value="Cancel" /> <input type="button" id="category-next" name="category-next" value="Next" /> */ ?>
				</div>
				<?php } ?>
				<div id="area-field">
						Area:<br/>
						<select id="area-select" name="area">
							<option value="" disabled selected hidden>Select Area</option>
							<?php if(!empty($this->area)) {
								foreach($this->area as $area) { ?>
									<option value="<?php echo $area['area_id']; ?>"><?php echo $area['area_name']; ?></option>
							<?php } } ?>
						</select>
					</div>
				<div id="floor-field">
					Floor:<br/>
					<select id="floor-select" name="floor_id">
						<option value="" disabled selected hidden>Select Floor</option>
					</select>
				</div>
				<div id="type-field">
					Type:<br/>
					<select id="type-select" name="type_id" required>
						<option value="" disabled selected hidden>Select Type</option>
					</select>
				</div>
				<div id="incident-field">
					Incident:<br/>
					<select id="incident-select" name="incident_id">
						<option value="" disabled selected hidden>Select Incident</option>
					</select>
				</div>
				<div id="modus-field">
					Modus:<br/>
					<select id="modus-select" name="modus_id">
						<option value="" disabled selected hidden>Select Modus</option>
					</select>
				</div>
				<div id="manpower-field">
					Man Power:<br/>
					<input id="manpower-text" name="manpower_id" type="text" autocomplete="off" required>
				</div>
				<div id="discussion-field">
					Detail:<br/>
					<textarea rows="4" cols="50" id="discussion-txtarea" name="description" required></textarea><br/>
					<!--<input type="radio" name="sendwa" value="4" checked> Send Anonymous Notification<br>-->
					<!--<input type="radio" name="sendwa" value="1" checked> Send WhatsApp to Chief/Manager<br>-->
					<!--<input type="radio" name="sendwa" value="2"> Send WhatsApp to Group/Contact List<br>-->
					<!--<input type="radio" name="sendwa" value="3"> Do not send Notification<br/><br/>-->
				</div>	
				<div id="pelaku-tertangkap-field" style="display:none;">
					<input type="checkbox" name="pelaku_tertangkap" value="1"> Pelaku Tertangkap
				</div>
				<div id="button-field">
					<input type="button" id="cancel-issue" name="cancel-issue" value="Cancel" class="form-btn" /> <input type="submit" id="issue-submit" name="issue-submit" value="Submit" class="form-btn">
				</div>
				<br/><br/>
			</div>
		</form>
	<?php } ?>
	
		<!-- footer content -->
        <footer>
          <div class="pull-right">
            Powered by <a href="http://isort.id">isort</a> Copyright &copy;<?php echo date("Y"); ?>. All Rights Reserved.
          </div>
          <div class="clearfix"></div>
        </footer>
        <!-- /footer content -->
	  </div>
    </div>

	
	<div class="loading-mask">  
	  <p></p>
	</div>
	

    <!-- NProgress -->
	<script src="/js/custom.js"></script>	


<script type="text/javascript">
	$(document).ready(function() {
		<?php if($this->showSiteSelection == 1) { ?>
			$('#site-menu').on('change', function(event){
				location.href="/default/user/setsiteid/id/"+$(this).val();
			});
			
			$(document).bind("contextmenu",function(e){
			  return false;
		   });
		<?php } ?>
		$("#issue-form")[0].reset();
		$("#other-info").hide();
		
		<?php if($this->kaizenNoPhoto == 1) { ?>
			$( ".menu-icon .icon-title" ).click(function() {
				$(".msg").hide();
				$("#content_wrapper").hide();
				$("#issue-finding-field").hide();
				$("#other-info").show();
			});
			
		<?php } else { ?>
			function filePreview(input) {
				if (input.files && input.files[0]) {
					var reader = new FileReader();
					reader.addEventListener('load', function() {
						$("#other-info").show();
						$("<img />", {
							"src": reader.result,
									"class": "thumb-image"
							}).appendTo("#image-holder");
					});
					reader.readAsDataURL(input.files[0]);
				}
			}
			
			$( "#picture-issue" ).change(function() {
				$(".msg").hide();
				$("#content_wrapper").hide();
				$("#issue-finding-field").hide();
				filePreview(this);
			});
		<?php } ?>

		$( "#cancel-issue" ).click(function() {
			$(".msg").show();
			$("#content_wrapper").show();
			$("#issue-finding-field").show();
			$("#other-info").hide();
		});

		/*** ISSUE FINDING FORM ***/

	$("#type-field").hide();
	$("#incident-field").hide();
	$("#modus-field").hide();
	$("#manpower-field").hide();
	$("#area-field").hide();
	$("#floor-field").hide();
	$("#manpower-text").prop('required',false);

	$("#category-select").change(function() {
		var cat_id = $( this ).val();
		$('body').mLoading();
		$("#area-field").show();
		$.ajax({
			url: "/default/issue/getissuetypeandfloorbycatid",
			data: { category_id :  cat_id }
		}).done(function(response) {
			var object = $.parseJSON(response);
			var issue_type_id = 0;
			$("#type-select").empty();
			$("#type-select").append('<option value=""  disabled selected hidden>Select Issue Type</option>');
			$.each(object.issue_type, function (item, value) {
				$("#type-select").append(new Option(value.issue_type, value.issue_type_id));
				issue_type_id = value.issue_type_id;
			});
			<?php if($this->kaizenNoPhoto == 1) { ?>
				$("#type-select").val(issue_type_id);
				$.ajax({
					url: "/default/issue/getincidentbyissuetypeid",
					data: { issue_type : issue_type_id, category_id: cat_id  }
				}).done(function(response) {
					curIncidents = [];
					var kejadian_id = 0;
					if(response == "[]")
					{
						$("#incident-field").hide();
						$("#modus-field").hide();
						$("#incident-select").prop('required',false);
						$("#modus-select").prop('required',false);
					} else {
						$("#incident-field").show();
						$("#incident-select").prop('required',true);
						$("#incident-select").empty();
						var object = $.parseJSON(response);
						$("#incident-select").append('<option value=""  disabled selected hidden>Select Incident</option>');
						$.each(object, function (item, value) {
							$("#incident-select").append(new Option(value.kejadian, value.kejadian_id));
							curIncidents[value.kejadian_id] = value.show_pelaku_checkbox;							
							$("#incident-select").val(value.kejadian_id);
							kejadian_id = value.kejadian_id;
						});
						$("#modus-select").prop('required',true);
						$.ajax({
							url: "/default/issue/getmodusbykejadianid",
							data: { kejadian_id : kejadian_id, category_id: cat_id  }
						}).done(function(response) {
							$("#modus-select").empty();
							var object = $.parseJSON(response);
							$("#modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
							$.each(object, function (item, value) {
								$("#modus-select").append(new Option(value.modus, value.modus_id));
							});							
							$("#modus-field").show();
						});
					}
				});
			<?php } ?>
			$("#type-field").show();

			$("#floor-select").empty();
			$("#floor-select").append('<option value=""  disabled selected hidden>Select Floor</option>');
			$.each(object.floor, function (id, val) {
				if(val.disable == 1) $("#floor-select").append('<option value="'+val.floor_id+'"  disabled class="select-option-disable">'+val.floor+'</option>');
				else $("#floor-select").append(new Option(val.floor, val.floor_id));
			});
			$("#floor-select").prop('required',true);
			$("#incident-field").hide();
			$("#modus-field").hide();
			$("#incident-select").prop('required',false);
			$("#modus-select").prop('required',false);
			$("body").mLoading('hide');
		});
		$("#pelaku-tertangkap-field").hide();
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	var curIncidents = [];
	$("#type-select").change(function() {
		var cat_id = $("#category-select").val();
		if($( this ).val() > 0)
		{
			$('body').mLoading();
			$.ajax({
				url: "/default/issue/getincidentbyissuetypeid",
				data: { issue_type : $( this ).val(), category_id: cat_id  }
			}).done(function(response) {
				curIncidents = [];
				if(response == "[]")
				{
					$("#incident-field").hide();
					$("#modus-field").hide();
					$("#incident-select").prop('required',false);
					$("#modus-select").prop('required',false);
				} else {
					$("#incident-field").show();
					$("#incident-select").prop('required',true);
					$("#incident-select").empty();
					var object = $.parseJSON(response);
					$("#incident-select").append('<option value=""  disabled selected hidden>Select Incident</option>');
					$.each(object, function (item, value) {
						$("#incident-select").append(new Option(value.kejadian, value.kejadian_id));
						curIncidents[value.kejadian_id] = value.show_pelaku_checkbox;
					});
				}
				$("body").mLoading('hide');
			});
		}
		$("#pelaku-tertangkap-field").hide();
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	$("#incident-select").change(function() {
		if($( this ).val() > 0)
		{			
			$('body').mLoading();
			if(curIncidents[$( this ).val()] == "1")
			{
				$("#pelaku-tertangkap-field").show();
			}
			else
			{
				$("#pelaku-tertangkap-field").hide();
			}
			$("#modus-field").show();
			$("#modus-select").prop('required',true);
			$.ajax({
				url: "/default/issue/getmodusbykejadianid",
				data: { kejadian_id : $( this ).val(), category_id: $("#category-select").val()  }
			}).done(function(response) {
				$("#modus-select").empty();
				var object = $.parseJSON(response);
				$("#modus-select").append('<option value=""  disabled selected hidden>Select Modus</option>');
				$.each(object, function (item, value) {
					$("#modus-select").append(new Option(value.modus, value.modus_id));
				});
				$("body").mLoading('hide');
			});
		}
		else
		{
			$("#modus-select").prop('required',false);
		}
		$("#manpower-field").hide();		
		$('#manpower-text').val('');
		$("#manpower-text").prop('required',false);
	});

	/*$("#modus-select").change(function() {
		if(($("#category-select").val() == 1 &&  $("#incident-select").val() >= 50 && $("#incident-select").val() <= 57) || ($("#category-select").val() == 2 &&  ($("#incident-select").val() == 5 || ($("#incident-select").val() >= 13 && $("#incident-select").val() <= 19))) ||  ($("#category-select").val() == 3 &&  ($("#incident-select").val() == 112 || ($("#incident-select").val() >= 136 && $("#incident-select").val() <= 142))) || ($("#category-select").val() == 5 &&  $("#incident-select").val() >= 44 && $("#incident-select").val() <= 51))
		{
			var modusid = $( this ).val();
			var categoryid = $("#category-select").val();
			$("#manpower-field").show();
			$('#manpower-text').val('');
			$("#manpower-text").prop('required',true);
			$( "#manpower-text" ).autocomplete({
				source: function( request, response ) {
					$.ajax({
						url: "/default/manpower/getmanpowerbykeyword",
						dataType: "json",
						data: {
							q: request.term, 
							m: modusid,
							c: categoryid
						},
						success: function( data ) {
							response( data );
						}
					});
				}
			});
		}
		else{
			$("#manpower-field").hide();			
			$('#manpower-text').val('');
			$("#manpower-text").prop('required',false);
		}
	});*/
	
	$("#area-select").change(function() {
		var area_id = $( this ).val();
		
		$('body').mLoading();
		$.ajax({
			url: "/default/issue/getlocationbyareaid",
			data: { area_id :  area_id, cat_id : $("#category-select").val() }
		}).done(function(response) {
			var object = $.parseJSON(response);
			$("#floor-select").empty();
			$("#floor-select").append('<option value=""  disabled selected hidden>Select Location</option>');
			$.each(object, function (item, value) {
				if(value.disable == 1) $("#floor-select").append('<option value="'+value.floor_id+'"  disabled class="select-option-disable">'+value.floor+'</option>');
				else $("#floor-select").append(new Option(value.floor, value.floor_id));
			});			
			$("#floor-field").show();
			$("body").mLoading('hide');
		});
		
	});

	$('#issue-form').on('submit', function(event){
		event.preventDefault();
		$("body").mLoading();
		if($("#manpower-field").is(":visible") === true)
		{
			var c = $("#category-select").val();
			var name =  $("#manpower-text").val();
			var m =  $("#modus-select").val();
			$.ajax({
				url: "/default/manpower/getmanpowerbyname",
				dataType: "json",
				data: {
					name: name, 
					c: c,
					m: m
				},
				success: function( data ) {
					if(data === false)
					{
						$("body").mLoading('hide');
						alert("Data Man Power tidak terdapat di list, mohon di perbaiki");
					}
					else
					{
						$.ajax({
							url: '/default/issue/submitissue',
							type: 'POST',
							data: new FormData($('#issue-form')[0]),
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
								if(response > 0)
								{
									location.href="/default/issue/listissues/id/"+response;
								}
								else {
									location.href="/default/index/index/err/1";
								}
							}
						});
					}
				}
			});
		}
		else
		{
			$.ajax({
				url: '/default/issue/submitissue',
				type: 'POST',
				data: new FormData($('#issue-form')[0]),
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
					if(response > 0)
					{
						location.href="/default/issue/listissues/id/"+response;
					}
					else {
						location.href="/default/index/index/err/1";
					}
				}
			});
		}
	});

	});		
</script>



	<!-- Global site tag (gtag.js) - Google Analytics -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=UA-142474125-1"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());

	gtag('config', 'UA-142474125-1');
	</script>

  </body>
</html>
