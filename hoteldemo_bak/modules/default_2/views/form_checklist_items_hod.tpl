<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />


  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <form id="checklist-form" class="form-label-left" action="/default/checklist/savechecklistitems" method="POST" onsubmit="$('body').mLoading();">
		  	<input id="checklist_id" name="checklist_id" type="hidden" value="<?php echo $this->checklist['checklist_id']; ?>">
			<div class="x_title">
				<h2 class="page-title"><?php echo $this->title; ?></h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="room_no">Room Number</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
						<?php echo $this->checklist['room_no']; ?>
					</div>
				</div>
				<br/>
				<?php if(!empty($this->items)) { 
					$category_id = 0;
					$subcategory_id = 0;
					$i=1;
				?>
				<div class="item form-group">
				<table id="checklist-items">
					<tr>
						<th colspan="2" style="text-align:left;">Date</th>
						<th colspan="2"><?php echo $this->date1; ?></th>
						<th colspan="2"><?php echo $this->date2; ?></th>
						<th colspan="2"><?php echo $this->date3; ?></th>						
						<th rowspan="2">HOD</th>
					</tr>
					<tr>
						<th style="text-align:left" colspan="2">Checked By</th>
						<th>Staff</th>
						<th>Spv</th>
						<th>Staff</th>
						<th>Spv</th>
						<th>Staff</th>
						<th>Spv</th>
					</tr>
					<?php foreach($this->items as $item) {
						if($item['category_id'] != $category_id)
						{
					?>
						<tr class="checklist-categories">
							<td colspan="9"><?php echo $item['category_name']; ?></td>
						</tr>
					<?php $category_id = $item['category_id']; } ?>
					<?php if($item['subcategory_id'] != $subcategory_id)
						{
					?>
						<tr class="checklist-subcategories">
							<td colspan="9"><?php echo $item['subcategory_name']; ?></td>
						</tr>
					<?php $subcategory_id = $item['subcategory_id']; } ?>
						<tr>
							<td><?php echo $i; ?><input type="hidden" name="item_id[<?php echo $item['item_id']; ?>]" value="<?php echo $item['checklist_item_id']; ?>"></td>
							<td><?php echo $item['item_name']; ?><input type="hidden" name="item_name[<?php echo $item['item_id']; ?>]" value="<?php echo $item['item_name']; ?>"></td>
							<td align="center"><?php if($item['condition_staff']== 1) echo "&#10004;"; else if($item['condition_staff']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv']== 1) echo "&#10004;"; else if($item['condition_spv']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_staff2']== 1) echo "&#10004;"; else if($item['condition_staff2']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv2']== 1) echo "&#10004;"; else if($item['condition_spv2']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_staff3']== 1) echo "&#10004;"; else if($item['condition_staff3']== 2) echo "&#10060;"; ?></td>
							<td align="center"><?php if($item['condition_spv3']== 1) echo "&#10004;"; else if($item['condition_spv3']== 2) echo "&#10060;"; ?></td>
							<td align="center" id="img<?php echo $item['item_id']; ?>"><?php if(empty($item['hod_image_update'])) { ?><a href="#upload-form" class="upload_pu" data-id="<?php echo $item['item_id']; ?>"><img src="/images/progress.png" width="20" /></a><?php } else { ?><img src="<?php echo str_replace(".","_thumb.",$item['hod_image_update']); ?>" height="80" /><?php } ?></td>
						</tr>
					<?php $i++; } ?>
				</table>
				</div>
				<?php } ?>
			
		  </div>
		  </form>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<div id="chat_div"></div>

<form id="upload-form" action=""  method="post" enctype="multipart/form-data" style="padding: 20px; border-radius: 10px;" class="mfp-hide white-popup-block">	
	<h1 class="page-title" style="margin-left: 0px !important; border-bottom: 1px solid; margin-bottom: 20px;">Upload Image</h1>
	<div id="up-field" class="col-md-12 col-sm-12 col-xs-12">									
		<label for="picture-kaizen" id="picture-kaizen-label">
			<div><img width="30" src="/images/camera.png" /><br/>Upload Picture</div>	
		</label>
		<input id="picture-kaizen" name="picture" type="file" accept="image/*" capture="capture" />	
	</div>
	<div id="up-image-holder" style="text-align:center;"></div>
	<input id="checklist_item_id" name="checklist_item_id" type="hidden" value="" />	
	<div id="up-button-field">
		<input type="button" id="up-cancel-issue" name="cancel-issue" value="Cancel" class="form-btn" /> <input type="submit" id="up-issue-submit" name="issue-submit" value="Upload" class="form-btn">
	</div>
</form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.chatbox.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#digital-checklist-menu").addClass("active");
	$("#digital-checklist-menu .child_menu").show();
	
	var box = $("#chat_div").chatbox({
		id:"<?php echo $this->ident['name']; ?>", 
		title : '<img src="/images/comment2.png" /> Comment',
		messageSent : function(id, user, msg) {
			$(".ui-chatbox-content").mLoading();
			var myFormData = new FormData();
			var file_data = $("#filename").prop("files")[0];
			myFormData.append('attachment', file_data);
			myFormData.append('checklist_id', '<?php echo $this->checklist['checklist_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/checklist/addcomment",
				type: 'POST',
				processData: false,
				contentType: false,
				data: myFormData
			}).done(function(response) {
				$("#chat_div").chatbox("option", "boxManager").addMsg(id, msg, response);	
				$("#filename").val(''); 
				$(".ui-chatbox-content").mLoading('hide');
			});
		},
		boxManager: {
                init: function(elem) {
                    this.elem = elem;
					this.elem.uiChatboxContent.toggle();
					this.elem.uiChatboxTitlebarMinimize.hide();	
					this.elem.uiChatboxTitle.html('<img src="/images/comment2.png" width="30"/>');
					this.elem.uiChatboxTitlebar.width("30px");
					this.elem.uiChatbox.addClass('ui-chatbox-minimize-icon-only');
					this.elem.uiChatboxTitlebar.removeClass('ui-widget-header');	
					<?php if(!empty($this->comments)) { 
							foreach($this->comments as $comment) {
					?>
							var comment<?php echo $comment['comment_id']; ?> = "<?php echo str_replace(array("\n","\r","\r\n"),"",$comment['comment']); ?>";
							var msg<?php echo $comment['comment_id']; ?> = comment<?php echo $comment['comment_id']; ?>.replace("<br>","\n");
							$("#chat_div").chatbox("option", "boxManager").addMsg('<?php echo $comment['name']; ?>', msg<?php echo $comment['comment_id']; ?>, '<?php echo $comment['filename']; ?>');	
					<?php } } ?>					
                },
                addMsg: function(peer, msg, filename) {
                    var self = this;
                    var box = self.elem.uiChatboxLog;
                    var e = document.createElement('div');
                    box.append(e);
                    $(e).hide();

                    var systemMessage = false;

                    if (peer) {
                        var peerName = document.createElement("b");
                        $(peerName).text(peer + ": ");
                        e.appendChild(peerName);
                    } else {
                        systemMessage = true;
                    }

                    var msgElement = document.createElement(
                        systemMessage ? "i" : "span");
					
					if(filename !== '')
					{
						msg = msg + '<br><a href="<?php echo $this->baseUrl; ?>/images/checklist/comments_'+filename.substring(14, 20)+'/'+filename+'" target="_blank"><i class="fa fa-paperclip"></i> ' + filename + '</a>';
					}
                    $(msgElement).html(msg);
                    e.appendChild(msgElement);
                    $(e).addClass("ui-chatbox-msg");
                    $(e).css("maxWidth", "100%");
                    $(e).fadeIn();
                    self._scrollToBottom();

                    if (!self.elem.uiChatboxTitlebar.hasClass("ui-state-focus")
                        && !self.highlightLock) {
                        self.highlightLock = true;
                    }
                },
                highlightBox: function() {
                    var self = this;
                    self.elem.uiChatboxTitlebar.effect("highlight", {}, 300);
                    self.elem.uiChatbox.effect("bounce", {times: 3}, 300, function() {
                        self.highlightLock = false;
                        self._scrollToBottom();
                    });
                },
                toggleBox: function() {
                    this.elem.uiChatbox.toggle();
                },
                _scrollToBottom: function() {
                    var box = this.elem.uiChatboxLog;
                    box.scrollTop(box.get(0).scrollHeight);
                }
            }
	});

	$('.upload_pu').click(function() {
		var item_id = curItemId = $(this)[0].dataset.id;
		$('#checklist_item_id').val(item_id);
	});
	
	$('.upload_pu').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$("#up-field").show();
				$("#upload-form")[0].reset();
				$("#up-image-holder").html("");
				function filePreview(input) {
					if (input.files && input.files[0]) {
						var reader = new FileReader();
						reader.addEventListener('load', function() {
							$("#up-image-holder").html("");
							$("<img />", {
								"src": reader.result,
										"class": "thumb-image"
								}).appendTo("#up-image-holder");
						});
						reader.readAsDataURL(input.files[0]);
					}
				}

				$( "#picture-kaizen" ).change(function() {
					$("#up-field").hide();
					filePreview(this);
				});
				
				$( "#up-cancel-issue" ).click(function() {
					$.magnificPopup.close();
				});

			},
			close: function() {	
				$( "#upload-form").show();
				$("#up-image-holder").html("");
			}
		}
	});
	
	$('#upload-form').on('submit', function(event){
		event.preventDefault();
		$("body").mLoading();
		$.ajax({
			url: '/default/checklist/uploadimagehod',
			type: 'POST',
			data: new FormData($('#upload-form')[0]),
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
				if(response != "0")
				{
					$("#img"+$('#checklist_item_id').val()).html('<img src="'+response+'" height="100" />');
					$.magnificPopup.close();		
					$("#upload-form")[0].reset();
					$("#upload-image-holder").html("");
					curItemId = 0;						
					$("body").mLoading('hide');
				}
				else {
					curItemId = 0;
					$("body").mLoading('hide');
					alert("Uploading image failed, please try again");
				}
			}
		});
	});
});	
</script>