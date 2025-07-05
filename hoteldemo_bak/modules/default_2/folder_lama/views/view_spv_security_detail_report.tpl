<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="page-title">Supervisor <?php if($this->securityRole) echo "SECURITY "; ?> DAILY REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/security/downloadspvreport/id/<?php echo $this->security['security_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			   <table id="defect-list-table" class="table">
				<tbody>
					<tr>
						<td>Day / Date</td>
						<td><?php if(!empty($this->security['report_date'])) echo $this->security['report_date']; else echo date("l, F j, Y"); ?></td>
					</tr>
					<tr>
						<td>Shift</td>
						<td><?php echo $this->security['shift_name']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td><?php echo $this->security['name']; ?></td>
					</tr>
				</tbody>
				</table>
			
			  <br/>
			  <span class="section">MAN POWER</span>
				<table id="defect-list-table" class="table">
					<thead>
						<tr>
							<th rowspan="2">In House</th>
							<th colspan="2">Vendor</th>
						</tr>
						<tr>
							<th><?php echo strtoupper($this->vendor[0]['vendor_name']); ?></th>
							<th><?php echo strtoupper($this->vendor[1]['vendor_name']); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo 'Spv : '.$this->security['supervisor']; ?></td>
							<td><?php echo 'Waka : '.$this->security['chief_spd']; ?></td>
							<td><?php echo 'Chief : '.$this->security['chief_army']; ?></td>
						</tr>
						<tr>
							<td><?php echo 'Posko : '.$this->security['staff_posko']; ?></td>
							<td><?php echo 'Panwas : '.$this->security['panwas_spd']; ?></td>
							<td><?php echo 'Panwas : '.$this->security['panwas_army']; ?></td>
						</tr>
						<tr>
							<td><?php echo 'CCTV : '.$this->security['staff_cctv']; ?></td>
							<td><?php echo 'Danton / Danru : '.$this->security['danton_spd']; ?></td>
							<td><?php echo 'Danton / Danru : '.$this->security['danton_army']; ?></td>
						</tr>
						<tr>
							<td><?php echo 'Safety : '.$this->security['safety']; ?></td>
							<td><?php echo 'Jumlah : '.$this->security['jumlah_spd']; ?></td>
							<td><?php echo 'Jumlah : '.$this->security['jumlah_army']; ?></td>
						</tr>
					</tbody>
				</table>
			</fieldset>

			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">BRIEFING</span>		
				<?php if(!empty($this->security['briefing'])) { ?><div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['briefing'])); ?></div><?php } ?>
				<?php if(!empty($this->security['briefing2'])) { ?><div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['briefing2'])); ?></div><?php } ?>
				<?php if(!empty($this->security['briefing3'])) { ?><div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['briefing3'])); ?></div><?php } ?>
				<br/>
			</div>

			<?php if(!empty($this->security['defect_list'])) { ?> 
			  <span class="section">Defect List</span>
			  <table id="defect-list-table" class="table">
			  <thead>
				<tr>
				  <tr>
				  <th width="80">Image</th>
				  <th>Date &amp; Time</th>
				  <th>Location</th>
				  <th width="30%">Description</th>
				  <th width="30%">Follow up</th>
				</tr>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach($this->security['defect_list'] as $defect_list) {
				?>
				<tr>
			      <td class="id-hidden"><a class="image-popup-vertical-fit" href="<?php echo $defect_list['large_pic']; ?>"><img src="<?php echo $defect_list['thumb_pic']; ?>" data-large="<?php echo $defect_list['large_pic']; ?>" width="50px" /></a></td>
				  <td><?php echo $defect_list['date_time']; ?></td>
				  <td><?php echo $defect_list['location']; ?></td>
				  <td><?php echo $defect_list['description']; ?></td>
				  <td><?php echo $defect_list['status']; ?></td>
				  </tr>
				<?php } ?>
			  </tbody>
			</table>
			<?php } ?>

			<?php if(!empty($this->security['incident'])) { ?>  
			  <span class="section">Incident Report</span>
			  <table id="incident-report-table" class="table">
			  <thead>
				<tr>
				  <th width="80">Image</th>
				  <th>Date &amp; Time</th>
				  <th>Location</th>
				  <th width="30%">Description</th>
				  <th width="30%">Status</th>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach($this->security['incident'] as $incident) {
				?>
			    	<td><a class="image-popup-vertical-fit" href="<?php echo $incident['large_pic']; ?>"><img src="<?php echo $incident['thumb_pic']; ?>" data-large="<?php echo $incident['large_pic']; ?>" width="50px" /></a></td>
				    <td><?php echo $incident['date_time']; ?></td>
			    	<td><?php echo $incident['location']; ?></td>
				    <td><?php echo $incident['description']; ?></td>
				    <td><?php echo $incident['status']; ?></td>
				<?php } ?>
			  </tbody>
			</table>
			<?php } ?>
			  
			<?php if(!empty($this->security['glitch'])) { ?>
			  <span class="section">Glitch</span>
			  <table id="glitch-table" class="table">
			  <thead>
				<tr>
				  <th width="80">Image</th>
				  <th>Date &amp; Time</th>
				  <th>Location</th>
				  <th width="30%">Description</th>
				  <th width="30%">Status</th>
				</tr>
			  </thead>
			  <tbody>
					<?php foreach($this->security['glitch'] as $glitch) {
				?>
				<tr id="glitch<?php echo $glitch['glitch_id']; ?>">
				    <td><a class="image-popup-vertical-fit" href="<?php echo $glitch['large_pic']; ?>"><img src="<?php echo $glitch['thumb_pic']; ?>" data-large="<?php echo $glitch['large_pic']; ?>" width="50px" /></a></td>
				    <td><?php echo $glitch['date_time']; ?></td>
			    	<td><?php echo $glitch['location']; ?></td>
				    <td><?php echo $glitch['description']; ?></td>
				    <td><?php echo $glitch['status']; ?></td>
				  </tr>
				<?php } ?>
			  </tbody>
			</table>
			<?php } ?>

			<?php if(!empty($this->security['lost_found'])) { ?>  
			  <span class="section">Lost &amp; Found</span>
			  <table id="lost-found-table" class="table">
			  <thead>
				<tr>
				  <th width="80">Image</th>
				  <th>Date &amp; Time</th>
				  <th>Location</th>
				  <th width="30%">Description</th>
				  <th width="30%">Status</th>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach($this->security['lost_found'] as $lost_found) {
				?>
				<tr id="lost-found<?php echo $lost_found['lost_found_id']; ?>">
				    <td><a class="image-popup-vertical-fit" href="<?php echo $lost_found['large_pic']; ?>"><img src="<?php echo $lost_found['thumb_pic']; ?>" data-large="<?php echo $lost_found['large_pic']; ?>" width="50px" /></a></td>
				    <td><?php echo $lost_found['date_time']; ?></td>
			    	<td><?php echo $lost_found['location']; ?></td>
				    <td><?php echo $lost_found['description']; ?></td>
				    <td><?php echo $lost_found['status']; ?></td>
				</tr>
				<?php } ?>
			  </tbody>
			</table>
			<?php } ?>

			<?php if(!empty($this->attachment)) { ?>
				<span class="section">Attachment</span>
				<ul>
					<?php
						foreach($this->attachment as $attachment) {
					?>
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/1/s/'.substr($attachment['upload_date'], 0, 4).'/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
					<?php } ?>
				</ul>
			<?php } ?>

		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<div id="chat_div"></div>

<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.chatbox.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	var box = $("#chat_div").chatbox({
		id:"<?php echo $this->ident['name']; ?>", 
		title : '<img src="/images/comment1.png" /> Comment',
		messageSent : function(id, user, msg) {
			$(".ui-chatbox-content").mLoading();
			var myFormData = new FormData();
			var file_data = $("#filename").prop("files")[0];
			myFormData.append('attachment', file_data);
			myFormData.append('report_date', '<?php echo $this->security['date']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/security/addcomment",
				type: 'POST',
				processData: false,
				contentType: false,
				data: myFormData
			}).done(function(response) {
				$("#chat_div").chatbox("option", "boxManager").addMsg(id, msg, '<?php echo date("Y"); ?>', response);	
				$("#filename").val(''); 
				$(".ui-chatbox-content").mLoading('hide');
			});
		},
		boxManager: {
                init: function(elem) {
                    this.elem = elem;
					this.elem.uiChatboxContent.toggle();
					this.elem.uiChatboxTitlebarMinimize.hide();	
					this.elem.uiChatboxTitle.html('<img src="/images/comment1.png" width="30" style="padding:3px;"/>');
					this.elem.uiChatboxTitlebar.width("30px");
					this.elem.uiChatbox.addClass('ui-chatbox-minimize-icon-only');
					this.elem.uiChatboxTitlebar.removeClass('ui-widget-header');	
					<?php if(!empty($this->comments)) { 
							foreach($this->comments as $comment) {
					?>
							var comment<?php echo $comment['comment_id']; ?> = "<?php echo $comment['comment']; ?>";
							var msg<?php echo $comment['comment_id']; ?> = comment<?php echo $comment['comment_id']; ?>.replace("<br>","\n");
							$("#chat_div").chatbox("option", "boxManager").addMsg('<?php echo $comment['name']; ?>', msg<?php echo $comment['comment_id']; ?>, '<?php echo substr($comment['comment_date'],0,4); ?>', '<?php echo $comment['filename']; ?>');	
					<?php } } ?>					
                },
                addMsg: function(peer, msg, subfolder, filename) {
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
						msg = msg + '<br><a href="<?php echo $this->baseUrl; ?>/comments/'+subfolder+'/'+filename+'" target="_blank"><i class="fa fa-paperclip"></i> ' + filename + '</a>';
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
});
</script>
