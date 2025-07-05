<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>DAILY HOUSEKEEPING REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/housekeeping/downloadhkreport/id/<?php echo $this->housekeeping['housekeeping_report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php echo $this->housekeeping['report_date']; ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['housekeeping_reporting_time']; ?>
				</div>
			  </div>
			  <br/>
			  <span class="section">MAN POWER</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. In House</legend>
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Description</th>
							  <th width="250" rowspan="2">Shift 1</th>
							  <th width="250" rowspan="2">Shift 2</th>
							  <th width="250" rowspan="2">Shift 3</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Chief Housekeeping</td>
								<td><?php echo $this->housekeeping['inhouse_chief_housekeeping_shift1']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_chief_housekeeping_shift2']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_chief_housekeeping_shift3']; ?></td>
							</tr>
							<tr>
								<td>Supervisor</td>
								<td><?php echo $this->housekeeping['inhouse_supervisor_shift1']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_supervisor_shift2']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_supervisor_shift3']; ?></td>
							</tr>
							<tr>
								<td>Staff</td>
								<td><?php echo $this->housekeeping['inhouse_staff_shift1']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_staff_shift2']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_staff_shift3']; ?></td>
							</tr>
							<tr>
								<td>Administrasi</td>
								<td><?php echo $this->housekeeping['inhouse_admin_shift1']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_admin_shift2']; ?></td>
								<td><?php echo $this->housekeeping['inhouse_admin_shift3']; ?></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>B. Outsourcing</legend>
					<table id="cleaning-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Cleaning Area</th>
							  <th width="250" rowspan="2">Shift 1</th>
							  <th width="250" rowspan="2">Shift 2</th>
							  <th width="250" rowspan="2">Shift 3</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Chief Housekeeping</td>
								<td><?php echo $this->housekeeping['outsource_chief_housekeeping_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_chief_housekeeping_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_chief_housekeeping_shift3']; ?></td>
							</tr>
							<tr>
								<td>Supervisor</td>
								<td><?php echo $this->housekeeping['outsource_supervisor_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_supervisor_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_supervisor_shift3']; ?></td>
							</tr>
							<tr>
								<td>Leader</td>
								<td><?php echo $this->housekeeping['outsource_leader_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_leader_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_leader_shift3']; ?></td>
							</tr>
							<tr>
								<td>Crew</td>
								<td><?php echo $this->housekeeping['outsource_crew_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_crew_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_crew_shift3']; ?></td>
							</tr>
							<tr>
								<td>Toilet Crew</td>
								<td><?php echo $this->housekeeping['outsource_toilet_crew_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_toilet_crew_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_toilet_crew_shift3']; ?></td>
							</tr>
							<tr>
								<td>Gondola</td>
								<td><?php echo $this->housekeeping['outsource_gondola_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_gondola_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_gondola_shift3']; ?></td>
							</tr>
							<tr>
								<td>Admin</td>
								<td><?php echo $this->housekeeping['outsource_admin_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_admin_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_admin_shift3']; ?></td>
							</tr>
							<tr>
								<td>Total</td>
								<td><?php echo $this->housekeeping['outsource_total_shift1']; ?></td>
								<td><?php echo $this->housekeeping['outsource_total_shift2']; ?></td>
								<td><?php echo $this->housekeeping['outsource_total_shift3']; ?></td>
							</tr>
						</tbody>
					 </table>
					 <table id="pest-control-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Pest Control</th>
							  <th width="250" rowspan="2">Shift 1</th>
							  <th width="250" rowspan="2">Shift 2</th>
							  <th width="250" rowspan="2">Shift 3</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Koordinator</td>
								<td><?php echo $this->housekeeping['pest_control_koordinator_shift1']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_koordinator_shift2']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_koordinator_shift3']; ?></td>
							</tr>
							<tr>
								<td>Leader</td>
								<td><?php echo $this->housekeeping['pest_control_leader_shift1']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_leader_shift2']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_leader_shift3']; ?></td>
							</tr>
							<tr>
								<td>Crew</td>
								<td><?php echo $this->housekeeping['pest_control_crew_shift1']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_crew_shift2']; ?></td>
								<td><?php echo $this->housekeeping['pest_control_crew_shift3']; ?></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>
			
			<?php if(!empty($this->work_target)) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">			
				<span class="section">TARGET PEKERJAAN</span>
					<table id="target-pekerjaan-table" class="table">
						<thead>
							<tr>
							<th>Target Perkerjaan</th>
							<th width="250">Shift 1</th>
							<th width="250">Shift 2</th>
							<th width="250">Shift 3</th>
							</tr>
						</thead>
						<tbody>						
							<?php foreach($this->work_target as $work_target) { ?>
							<tr>
								<td><?php echo $work_target['work_target']; ?></td>
								<td><?php echo $work_target['shift1']; ?></td>
								<td><?php echo $work_target['shift2']; ?></td>
								<td><?php echo $work_target['shift3']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>

			
			<?php if(!empty($this->hasilTangkapan)) { 
				$i = 0;	?>
				<div class="col-md-12 col-sm-12 col-xs-12">			
				<span class="section">HASIL TANGKAPAN</span>	
					<table id="hasil-tangkapan-table" class="table">
						<thead>
							<tr>
							<th rowspan="2">Hasil Tangkapan</th>
							<th width="250" rowspan="2">Shift 1</th>
							<th width="250" rowspan="2">Shift 2</th>
							<th width="250" rowspan="2">Shift 3</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->hasilTangkapan as $hasilTangkapan) { ?>
							<tr>
								<td><?php echo $hasilTangkapan['hewan_tangkapan']; ?></td>
								<td><?php echo $hasilTangkapan['shift1']; ?></td>
								<td><?php echo $hasilTangkapan['shift2']; ?></td>
								<td><?php echo $hasilTangkapan['shift3']; ?></td>
							</tr>
							<?php $i++; } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
				
			<?php if(!empty($this->training)) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">			
				<span class="section">TRAINING</span>
					<table id="training-table" class="table">
						<thead>
							<tr>
							<th rowspan="2">Training</th>
							<th width="250" rowspan="2">Shift 1</th>
							<th width="250" rowspan="2">Shift 2</th>
							<th width="250" rowspan="2">Shift 3</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->training as $training) { ?>
							<tr>
								<td><?php echo $training['training_name']; ?></td>
								<td><?php echo $training['shift1']; ?></td>
								<td><?php echo $training['shift2']; ?></td>
								<td><?php echo $training['shift3']; ?></td>
							</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">LAPORAN KEJADIAN</span>		
				<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing1']); ?></div>
				<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing2']); ?></div>
				<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing3']); ?></div>
			</div>

			<span class="section" style="clear:both; padding-top:20px;">PROGRESS REPORT</span>	

			<?php if(!empty($this->progress_report_shift12)) { ?>
				<fieldset>
					<legend>Progress Report Shift 1&2</legend>
					<div class="table-dv">
					<table id="progress-report-shift12-table" class="table">
						<thead>
							<tr>
							<th rowspan="2">Area</th>
							<th width="175" rowspan="2">Before</th>
							<th width="175" rowspan="2">Progress</th>
							<th width="175" rowspan="2">After</th>
							<th rowspan="2">Status</th>
							</tr>
						</thead>
						<tbody>
								<?php foreach($this->progress_report_shift12 as $progress_report_shift12) { 
									if (!empty($progress_report_shift12['img_before'])) {
										$progress_report_shift12['img_before'] = str_replace(".","_thumb.",$progress_report_shift12['img_before']);
									}	
									if (!empty($progress_report_shift12['img_progress'])) {
										$progress_report_shift12['img_progress'] = str_replace(".","_thumb.",$progress_report_shift12['img_progress']);
									}
									if (!empty($progress_report_shift12['img_after'])) {
										$progress_report_shift12['img_after'] = str_replace(".","_thumb.",$progress_report_shift12['img_after']);
									}
								?>
								<tr>
									<td><?php echo str_replace("<br>","&#13;",$progress_report_shift12['area']); ?></td>									
									<td align="center"><?php if(!empty($progress_report_shift12['img_before'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_before']; ?>"><img src="<?php echo $progress_report_shift12['img_before']; ?>" class="thumb-img" /></a><?php } ?></td>									
									<td align="center"><?php if(!empty($progress_report_shift12['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_progress']; ?>"><img src="<?php echo $progress_report_shift12['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>									
									<td align="center"><?php if(!empty($progress_report_shift12['img_after'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift12['img_after']; ?>"><img src="<?php echo $progress_report_shift12['img_after']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td align="center"><?php echo str_replace("<br>","&#13;",$progress_report_shift12['status']); ?></td>
								</tr>	
							<?php } ?>	
						</tbody>
					</table>
					</div>
				</fieldset>
			<?php } ?>
			
			
			<?php if(!empty($this->progress_report_shift3)) { ?>
				<fieldset>
					<legend>Progress Report Shift 3</legend>
					<div class="table-dv">
					<table id="progress-report-shift3-table" class="table">
						<thead>
							<tr>
							<th rowspan="2">Area</th>
							<th width="175" rowspan="2">Before</th>
							<th width="175" rowspan="2">Progress</th>
							<th width="175" rowspan="2">After</th>
							<th rowspan="2">Status</th>
							</tr>
						</thead>
						<tbody>
								<?php foreach($this->progress_report_shift3 as $progress_report_shift3) { 
										if (!empty($progress_report_shift3['img_before'])) {
											$progress_report_shift3['img_before'] = str_replace(".","_thumb.",$progress_report_shift3['img_before']);
										}	
										if (!empty($progress_report_shift3['img_progress'])) {
											$progress_report_shift3['img_progress'] = str_replace(".","_thumb.",$progress_report_shift3['img_progress']);
										}
										if (!empty($progress_report_shift3['img_after'])) {
											$progress_report_shift3['img_after'] = str_replace(".","_thumb.",$progress_report_shift3['img_after']);
										}	
								?>
									<tr>
										<td><?php echo str_replace("<br>","&#13;",$progress_report_shift3['area']); ?></td>
										<td align="center"><?php if(!empty($progress_report_shift3['img_before'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_before']; ?>"><img src="<?php echo $progress_report_shift3['img_before']; ?>" class="thumb-img" /></a><?php } ?></td>
										<td align="center"><?php if(!empty($progress_report_shift3['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_progress']; ?>"><img src="<?php echo $progress_report_shift3['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>
										<td align="center"><?php if(!empty($progress_report_shift3['img_after'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $progress_report_shift3['img_after']; ?>"><img src="<?php echo $progress_report_shift3['img_after']; ?>" class="thumb-img" /></a><?php } ?></td>
										<td><?php echo str_replace("<br>","&#13;",$progress_report_shift3['status']); ?></td>
										</tr>	
							<?php } ?>		
						</tbody>
					</table>
					</div>
				</fieldset>
			<?php } ?>
			
			<?php if(!empty($this->other_info)) { ?>
				<fieldset>
					<legend>Pest Control dan Informasi Lainnya  &nbsp;</legend>
					<div class="table-dv">
					<table id="other-info-table" class="table">
						<thead>
							<tr>
							<th rowspan="2">Area</th>
							<th rowspan="2">Progress</th>
							<th rowspan="2">Status</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->other_info as $other_info) { 
									if (!empty($other_info['img_progress'])) {
										$other_info['img_progress'] = str_replace(".","_thumb.",$other_info['img_progress']);
									}	
							?>
									<tr>
										<td><?php echo str_replace("<br>","&#13;",$other_info['area']); ?></td>
										<td align="center"><?php if(!empty($other_info['img_progress'])) { ?><a class="image-popup-vertical-fit" href="<?php echo $other_info['img_progress']; ?>"><img src="<?php echo $other_info['img_progress']; ?>" class="thumb-img" /></a><?php } ?></td>
										<td><?php echo str_replace("<br>","&#13;",$other_info['status']); ?></td>
									</tr>	
							<?php } ?>		
						</tbody>
					</table>
					</div>
				</fieldset>
			<?php } ?>
			
			<?php if(!empty($this->attachment)) { ?>
				<span class="section">Attachment</span>
				<ul>
					<?php
						foreach($this->attachment as $attachment) {
					?>
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/2/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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
		title : '<img src="/images/comment_24x24.png" /> Comment',
		messageSent : function(id, user, msg) {
			$(".ui-chatbox-content").mLoading();
			var myFormData = new FormData();
			var file_data = $("#filename").prop("files")[0];
			myFormData.append('attachment', file_data);
			myFormData.append('report_id', '<?php echo $this->housekeeping['housekeeping_report_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/housekeeping/addcomment",
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
					this.elem.uiChatboxTitle.html('<img src="/images/comment_24x24.png" width="30"/>');
					this.elem.uiChatboxTitlebar.width("30px");
					this.elem.uiChatbox.addClass('ui-chatbox-minimize-icon-only');
					this.elem.uiChatboxTitlebar.removeClass('ui-widget-header');	
					<?php if(!empty($this->comments)) { 
							foreach($this->comments as $comment) {
					?>
							var comment<?php echo $comment['comment_id']; ?> = "<?php echo $comment['comment']; ?>";
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
						msg = msg + '<br><a href="<?php echo $this->baseUrl; ?>/comments/'+filename+'" target="_blank"><i class="fa fa-paperclip"></i> ' + filename + '</a>';
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