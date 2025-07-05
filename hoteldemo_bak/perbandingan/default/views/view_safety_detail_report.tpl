<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>DAILY SAFETY REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/safety/downloadsafetyreport/id/<?php echo $this->safety['report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			   <table class="table">
				<tbody>
					<tr>
						<td>Day / Date</td>
						<td colspan="3"><?php echo $this->safety['report_date'] ?></td>
					</tr>
					<tr>
						<td>Reporting Time</td>
						<td colspan="2" align="center"><?php echo $this->safety['yesterday_date'] ?></td>
						<td align="center"><?php echo $this->safety['today_date'] ?></td>
					</tr>
					<tr>
						<td>Reporting Time</td>
						<td><?php echo $this->setting['safety_afternoon_reporting_time'] ?></td>
						<td><?php echo $this->setting['safety_night_reporting_time'] ?></td>
						<td><?php echo $this->setting['safety_morning_reporting_time'] ?></td>
					</tr>
				</tbody>
				</table>
			
			  <br/>
			  <span class="section">MAN POWER</span>
				<table class="table">
					<thead>
						<tr>
							<th><?php echo $this->setting['safety_afternoon_reporting_time']; ?></th>
							<th><?php echo $this->setting['safety_night_reporting_time']; ?></th>
							<th><?php echo $this->setting['safety_morning_reporting_time']; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $this->safety['man_power_afternoon']; ?></td>
							<td><?php echo $this->safety['man_power_night']; ?></td>
							<td><?php echo $this->safety['man_power_morning']; ?></td>
						</tr>
					</tbody>
				</table>

				<br/>
				<?php if(!empty($this->equipments_ab)) { ?>
			  	<span class="section">PERLENGKAPAN</span>
				<table id="perlengkapan-table" class="table">
					<thead>
						<tr>
							<th>No</th>
							<th>Equipment Name</th>
							<th>Item</th>
							<th>Status Normal</th>
							<th>Shift 3<br/>23:00</th>
							<th>Shift 1<br/>07:00</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->equipments_ab as $equipment) {	?>
						<tr>
							<td><?php echo $equipment['no']; ?></td>
							<td><?php echo $equipment['equipment_name']; ?></td>
							<td><?php echo $equipment['item_name']; ?></td>
							<td><?php echo $equipment['status']; ?></td>
							<td><?php echo $equipment['shift2']; ?></td>
							<td><?php echo $equipment['shift3']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>

			<?php if(!empty($this->equipments_c1)) { ?>
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">No</th>
				  <th rowspan="2">Equipment Name</th>
				  <th rowspan="2">Item</th>
				  <th colspan="2">Status Pressure<br/>(bar or PSI or Kgf / cm2)</th>
				  <th colspan="2">Actual Pressure<br/>(bar or PSI or Kgf / cm2)</th>
				</tr>
				<tr>
					<th>Cut In</th>
					<th>Cut Off</th>
					<th>Shift 3<br/>23:00</th>
					<th>Shift 1<br/>07:00</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
					foreach($this->equipments_c1 as $equipmentc1) {
				?>
				<tr>
					<td><?php echo $equipmentc1['no']; ?></td>
					<td><?php echo $equipmentc1['equipment_name']; ?></td>
					<td><?php echo $equipmentc1['item_name']; ?></td>
					<td><?php if(!empty($equipmentc1['status_cut_in'])) echo $equipmentc1['status_cut_in']; else echo $equipmentc1['status_pressure_cut_in'];  ?></td>
					<td><?php if(!empty($equipmentc1['status_cut_off'])) echo $equipmentc1['status_cut_off']; else echo $equipmentc1['status_pressure_cut_off'];  ?></td>
					<td><?php echo $equipmentc1['shift2']; ?></td>
					<td><?php echo $equipmentc1['shift3']; ?></td>
				</tr>
				<?php $i++; } ?>
			  </tbody>
			</table>
			</div>
			<?php } ?>
			
			<?php if(!empty($this->equipments_c2)) { ?>
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th>No</th>
				  <th>Tank Condition</th>
				  <th>Status Normal</th>
				  <th>Shift 3<br/>23:00</th>
				  <th>Shift 1<br/>07:00</th>
				</tr>
			  </thead>
			  <tbody>
				<?php 
					foreach($this->equipments_c2 as $equipmentc2) {
				?>
				<tr>
					<td><?php echo $equipmentc2['no']; ?></td>
					<td><?php echo $equipmentc2['item_name']; ?></td>
					<td><?php echo $equipmentc2['status']; ?></td>
					<td><?php echo $equipmentc2['shift2']; ?></td>
					<td><?php echo $equipmentc2['shift3']; ?></td>
				</tr>
				<?php $i++; } ?>
			  </tbody>
			</table>
			<?php } ?>

			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">BRIEFING</span>	
			  	<?php if(!empty($this->safety['briefing1'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->safety['briefing1']); ?></div>
				<?php } ?>
				<?php if(!empty($this->safety['briefing2'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->safety['briefing2']); ?></div>
				<?php } ?>
				<?php if(!empty($this->safety['briefing3'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->safety['briefing3']); ?></div>
				<?php } ?>
			</div>

			<?php if(!empty($this->outsourceTraining) || !empty($this->inHouseTraining)) { ?>
			<span class="section">TRAINING</span>	
				<table id="defect-list-table" class="table">
					<thead>
						<tr>
							<th colspan="2">Outsource</th>
						</tr>
						<tr>
							<th>Activity</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->outsourceTraining as $outsourceTraining) { ?>
						<tr>
							<td><?php echo $outsourceTraining['activity']; ?></td>
							<td><?php echo $outsourceTraining['description']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>

				<table id="defect-list-table" class="table">
					<thead>
						<tr>
							<th colspan="2">In House</th>
						</tr>
						<tr>
							<th>Activity</th>
							<th>Description</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->inHouseTraining as $inHouseTraining) { ?>
						<tr>
							<td><?php echo $inHouseTraining['activity']; ?></td>
							<td><?php echo $inHouseTraining['description']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>		  
			  
			<?php if(!empty($this->safety['sop1']) || !empty($this->safety['sop2']) || !empty($this->safety['sop3'])) { ?>
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
				<?php if(!empty($this->safety['sop1'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->safety['sop1']; ?></div>
				<?php } ?>
				<?php if(!empty($this->safety['sop2'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->safety['sop2']; ?></div>
				<?php } ?>
				<?php if(!empty($this->safety['sop3'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->safety['sop3']; ?></div>
				<?php } ?>
			<br clear="all"/><br/>
			<?php } ?>
			
			<?php if(!empty($this->specific_reports)) { ?>
				<span class="section" style="clear:both; padding-top:20px;">SPECIFIC REPORT</span>
				<table id="defect-list-table" class="table">
					<tbody>
						<?php foreach($this->specific_reports as $specific_report)
							{
								$timeField = "Time";
								if($specific_report['issue_type_id'] < 4)
								{
									$specific_report['detail'] = $specific_report['description'];
								}
								if($specific_report['issue_type_id'] == 4)
								{
									$specific_report['time'] =  $specific_report['area'];
									$specific_report['issue_type_name'] = "Defect List";
									$timeField = "Area";
								}
								$issue = '<span style="font-size:14px; font-weight:bold;">'.$specific_report['issue_type_name']."</span><br/><strong>Detail : </strong>".nl2br($specific_report['detail']);
					
						?>
							<tr>
								<td><?php echo $issue; ?></td>
								<td width="200"><?php echo "<strong>Status :</strong><br/>".$specific_report['status']; ?></td>
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
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/3/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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
			myFormData.append('report_id', '<?php echo $this->safety['report_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/safety/addcomment",
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