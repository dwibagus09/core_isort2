<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle">CHIEF <?php if($this->securityRole) echo "SECURITY "; ?> DAILY REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/security/downloadchiefreport/dt/<?php echo $this->security['report_date']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			   <table id="defect-list-table" class="table">
				<tbody>
					<tr>
						<td>Day / Date</td>
						<td><?php if(!empty($this->security['created_date'])) echo $this->security['created_date']; else echo date("l, F j, Y"); ?></td>
					</tr>
					<tr>
						<td>Reporting Time</td>
						<td><?php echo $this->setting['chief_security_reporting_time']; ?></td>
					</tr>
				</tbody>
				</table>
			
			  <br/>
			  <span class="section">MAN POWER</span>
			  <div class="table-auto-scroll">
				<table id="defect-list-table" class="table">
					<thead>
						<tr>
							<th colspan="4">In House</th>
						</tr>
						<tr>
							<th></th>
							<th>Malam</th>
							<th>Pagi</th>
							<th>Siang</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>Supervisor</td>
							<td><?php echo $this->security['night']['supervisor']; ?></td>
							<td><?php echo $this->security['morning']['supervisor']; ?></td>
							<td><?php echo $this->security['afternoon']['supervisor']; ?></td>
						</tr>
						<tr>
							<td>Staff Posko</td>
							<td><?php echo $this->security['night']['staff_posko']; ?></td>
							<td><?php echo $this->security['morning']['staff_posko']; ?></td>
							<td><?php echo $this->security['afternoon']['staff_posko']; ?></td>
						</tr>
						<tr>
							<td>Staff CCTV</td>
							<td><?php echo $this->security['night']['staff_cctv']; ?></td>
							<td><?php echo $this->security['morning']['staff_cctv']; ?></td>
							<td><?php echo $this->security['afternoon']['staff_cctv']; ?></td>
						</tr>
						<tr>
							<td>Safety</td>
							<td><?php echo $this->security['night']['safety']; ?></td>
							<td><?php echo $this->security['morning']['safety']; ?></td>
							<td><?php echo $this->security['afternoon']['safety']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			
			<div class="table-auto-scroll">
				<table id="defect-list-table" class="table">
					<thead>
						<tr>
							<th colspan="3">Vendor</th>
						</tr>
						<tr>
							<th></th>
							<th><?php echo $this->vendor[0]['vendor_name']; ?></th>
							<th><?php echo $this->vendor[1]['vendor_name']; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>CHIEF/WAKA</td>
							<td><?php echo $this->security['chief_spd']; ?></td>
							<td><?php echo $this->security['chief_army']; ?></td>
						</tr>
						<tr>
							<td>PANWAS</td>
							<td><?php echo $this->security['panwas_spd']; ?></td>
							<td><?php echo $this->security['panwas_army']; ?></td>
						</tr>
						<tr>
							<td>DANTON/DANRU PAGI</td>
							<td><?php echo $this->security['danton_pagi_spd']; ?></td>
							<td><?php echo $this->security['danton_pagi_army']; ?></td>
						</tr>
						<tr>
							<td>KEKUATAN</td>
							<td><?php echo $this->security['kekuatan_spd']; ?></td>
							<td><?php echo $this->security['kekuatan_army']; ?></td>
						</tr>
					</tbody>
				</table>
			</div>
			</fieldset>

			<span class="section">PERLENGKAPAN</span>
			<div class="table-dv">
			  <table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Nama Perlengkapan</th>
				  <th rowspan="2">Vendor</th>
				  <th rowspan="2">Jumlah</th>
				  <th width="150" colspan="2">Kondisi</th>
				  <th rowspan="2">Keterangan</th>
				</tr>
				<tr>
				  <th width="75">Ok</th>
				  <th width="75">Tidak Ok</th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->equipments)) {
						$i = 0;
						foreach($this->equipments as $equipment) {
				?>
				<tr>
					<td><?php echo $equipment['equipment_name']; ?></td>
					<td><?php echo $equipment['vendor_name']; ?></td>
					<td><?php echo $equipment['total_equipment']; ?></td>
					<td><?php echo $equipment['ok_condition']; ?></td>
					<td><?php echo $equipment['bad_condition']; ?></td>
					<td><?php echo $equipment['description']; ?></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			</div> 

			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">BRIEFING</span>	
			  	<?php if(!empty($this->security['night']['briefing']) || !empty($this->security['night']['briefing']) || !empty($this->security['night']['briefing'])) { ?>
			  		<h4>Night Briefing</h4>	
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['night']['briefing']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['night']['briefing2']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['night']['briefing3'])); ?></div>
				<?php } ?>
				<?php if(!empty($this->security['morning']['briefing']) || !empty($this->security['morning']['briefing']) || !empty($this->security['morning']['briefing'])) { ?>
			  		<h4>Morning Briefing</h4>	
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['morning']['briefing']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['morning']['briefing2']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['morning']['briefing3'])); ?></div>
				<?php } ?>
				<?php if(!empty($this->security['afternoon']['briefing']) || !empty($this->security['afternoon']['briefing']) || !empty($this->security['afternoon']['briefing'])) { ?>
			  		<h4>Afternoon Briefing</h4>	
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br(str_replace("<br>","&#13;",$this->security['afternoon']['briefing']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['afternoon']['briefing2']))."<br/>".nl2br(str_replace("<br>","&#13;",$this->security['afternoon']['briefing3'])); ?></div>
				<?php } ?>
			</div>

			<?php if(!empty($this->outsourceTraining) || !empty($this->inHouseTraining)) { ?>
			<span class="section">TRAINING</span>
			<div class="table-auto-scroll">
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
			</div>
			<?php } ?>			  
			  
			<?php if(!empty($this->security['sosialisasi_sop_a']) || !empty($this->security['sosialisasi_sop_b']) || !empty($this->security['sosialisasi_sop_c'])) { ?>
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
				<?php if(!empty($this->security['sosialisasi_sop_a'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->security['sosialisasi_sop_a']; ?></div>
				<?php } ?>
				<?php if(!empty($this->security['sosialisasi_sop_b'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->security['sosialisasi_sop_b']; ?></div>
				<?php } ?>
				<?php if(!empty($this->security['sosialisasi_sop_c'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->security['sosialisasi_sop_c']; ?></div>
				<?php } ?>
			<br clear="all"/><br/>
			<?php } ?>
			
			<?php if(!empty($this->specific_reports)) { ?>
				<span class="section" style="clear:both; padding-top:20px;">SPECIFIC REPORT</span>
				<div class="table-auto-scroll">
					<table id="defect-list-table" class="table">
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
							<?php foreach($this->specific_reports as $specific_report)
								{
							?>
								<tr>
									<td><a class="image-popup-vertical-fit" href="<?php echo $specific_report['large_pic']; ?>"><img src="<?php echo $specific_report['thumb_pic']; ?>" data-large="<?php echo $specific_report['large_pic']; ?>" width="50px" /></a></td>
									<td><?php echo $specific_report['date_time']; ?></td>
									<td><?php echo $specific_report['location']; ?></td>
									<td><?php echo $specific_report['description']; ?></td>
									<td><?php echo $specific_report['status']; ?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			<?php } ?>

			<?php if(!empty($this->attachment)) { ?>
				<span class="section">Attachment</span>
				<ul>
					<?php
						foreach($this->attachment as $attachment) {
					?>
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/1/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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

<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="/js/jquery.ui.chatbox.js"></script>
<script type="text/javascript">
$(document).ready(function() {
    $('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	var box = $("#chat_div").chatbox({
		id:"<?php echo $this->ident['name']; ?>", 
		title : '<img src="/images/comment1.png" /> Comment',
		messageSent : function(id, user, msg) {
			$(".ui-chatbox-content").mLoading();
			var myFormData = new FormData();
			var file_data = $("#filename").prop("files")[0];
			myFormData.append('attachment', file_data);
			myFormData.append('report_date', '<?php echo $this->security['report_date']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/security/addcomment",
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
					this.elem.uiChatboxTitle.html('<img src="/images/comment1.png" width="30" style="padding:3px;" />');
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