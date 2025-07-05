<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>DAILY PARKING &amp; TRAFFIC REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/parking/downloadparkingtrafficreport/id/<?php echo $this->parking['parking_report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			   <table class="table">
				<tbody>
					<tr>
						<td>Day / Date</td>
						<td colspan="3"><?php echo $this->parking['report_date']; ?></td>
					</tr>
					<tr>
						<td>Time</td>
						<td><?php echo $this->setting['parking_traffic_reporting_time']; ?></td>
					</tr>
				</tbody>
				</table>
			
			  <br/>
			  <span class="section">MAN POWER</span>
				<table class="table">
					<thead>
						<tr>
							<th colspan="4">IN HOUSE</th>
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
							<td><?php echo $this->parking['inhouse_spv_malam']; ?></td>
							<td><?php echo $this->parking['inhouse_spv_pagi']; ?></td>
							<td><?php echo $this->parking['inhouse_spv_siang']; ?></td>
						</tr>
						<tr>
							<td>Admin</td>
							<td><?php echo $this->parking['inhouse_admin_malam']; ?></td>
							<td><?php echo $this->parking['inhouse_admin_pagi']; ?></td>
							<td><?php echo $this->parking['inhouse_admin_siang']; ?></td>
						</tr>
						<tr>
							<td>Kekuatan</td>
							<td><?php echo $this->parking['inhouse_kekuatan_malam']; ?></td>
							<td><?php echo $this->parking['inhouse_kekuatan_pagi']; ?></td>
							<td><?php echo $this->parking['inhouse_kekuatan_siang']; ?></td>
						</tr>
						<tr>
							<td>Car Count</td>
							<td colspan="3"><?php echo "Mobil : ".intval($this->parking['inhouse_carcount_mobil'])."<br/>Motor : ".intval($this->parking['inhouse_carcount_motor'])."<br/>Box : ".intval($this->parking['inhouse_carcount_box'])."<br/>Valet Reg : ".intval($this->parking['inhouse_carcount_valet_reg'])."<br/>Self Valet : ".intval($this->parking['inhouse_carcount_self_valet'])."<br/>Drop Off : ".intval($this->parking['inhouse_carcount_drop_off'])."<br/>Taxi : ".intval($this->parking['inhouse_carcount_taxi'])."<br/>Total : ".intval($this->parking['inhouse_carcount_total']); ?></td>
						</tr>
					</tbody>
				</table>

				<table class="table">
					<thead>
						<tr>
							<th colspan="4">VENDOR</th>
						</tr>
						<tr>
							<th></th>
							<th><?php echo $this->vendor1; ?></th>
							<th>Valet</th>
						</tr>
					</thead> 
					<tbody>
						<tr>
							<td>CPM/ACPM</td>
							<td><?php echo $this->parking['vendor_cpm_acpm_spi']; ?></td>
							<td><?php echo $this->parking['vendor_cpm_acpm_valet']; ?></td>
						</tr>
						<tr>
							<td>PENGAWAS</td>
							<td><?php echo $this->parking['vendor_pengawas_spi']; ?></td>
							<td><?php echo $this->parking['vendor_pengawas_valet']; ?></td>
						</tr>
						<tr>
							<td>ADMIN</td>
							<td><?php echo $this->parking['vendor_admin_spi']; ?></td>
							<td><?php echo $this->parking['vendor_admin_valet']; ?></td>
						</tr>
						<tr>
							<td>KEKUATAN</td>
							<td colspan="3"><?php echo $this->vendor1." Pagi : ".$this->parking['vendor_kekuatan_spi_pagi']."<br/>". $this->vendor1. " Siang : ".$this->parking['vendor_kekuatan_spi_siang']."<br/>".$this->vendor1." Malam : ".$this->parking['vendor_kekuatan_spi_malam']."<br/>Valet Pagi : ".$this->parking['vendor_kekuatan_valet_pagi']."<br/>Valet Siang : ".$this->parking['vendor_kekuatan_valet_siang']."<br/>Valet Malam : ".$this->parking['vendor_kekuatan_valet_malam']."<br/>Taxi Pagi : ".$this->parking['vendor_kekuatan_taxi_pagi']."<br/>Taxi Siang : ".$this->parking['vendor_kekuatan_taxi_siang']."<br/>Taxi Malam : ".$this->parking['vendor_kekuatan_taxi_malam']."<br/>Taxi Online Pagi : ".$this->parking['vendor_kekuatan_taxionline_pagi']."<br/>Taxi Online Siang : ".$this->parking['vendor_kekuatan_taxionline_siang']."<br/>Taxi Online Malam : ".$this->parking['vendor_kekuatan_taxionline_malam']; ?></td>
						</tr>
					</tbody>
				</table>

				<br/>
				<?php if(!empty($this->equipments)) { ?>
			  	<span class="section">PERLENGKAPAN</span>
				<table id="perlengkapan-table" class="table">
					<thead>
						<tr>
							<th rowspan="2">Nama Perlengkapan</th>
							<th rowspan="2">Jumlah</th>
							<th colspan="2">Kondisi</th>
							<th rowspan="2">Keterangan</th>
						</tr>
						<tr>
							<th>Ok</th>
							<th>Tidak Ok</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($this->equipments as $equipment) {	?>
						<tr>
							<td><?php echo $equipment['equipment_name']; ?></td>
							<td><?php echo $equipment['total_equipment']; ?></td>
							<td><?php echo $equipment['ok_condition']; ?></td>
							<td><?php echo $equipment['bad_condition']; ?></td>
							<td><?php echo $equipment['description']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			<?php } ?>

			<?php if(!empty($this->parkingEquipments)) { ?>
			<span class="section">PERALATAN PARKIR</span>
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Nama Peralatan</th>
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
				<?php 
					foreach($this->parkingEquipments as $equipment) {
				?>
				<tr>
					<td><?php echo $equipment['equipment_name']; ?></td>
					<td><?php echo $equipment['total_equipment']; ?></td>
					<td><?php echo $equipment['ok_condition'];  ?></td>
					<td><?php echo $equipment['bad_condition'];  ?></td>
					<td><?php echo $equipment['description']; ?></td>
				</tr>
				<?php $i++; } ?>
			  </tbody>
			</table>
			<?php } ?>
			

			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">BRIEFING</span>	
			  	<?php if(!empty($this->parking['briefing1'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->parking['briefing1']); ?></div>
				<?php } ?>
				<?php if(!empty($this->parking['briefing2'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->parking['briefing2']); ?></div>
				<?php } ?>
				<?php if(!empty($this->parking['briefing3'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo nl2br($this->parking['briefing3']); ?></div>
				<?php } ?>
			</div>
			<br clear="all" />
			<?php if(!empty($this->outdoorTraining) || !empty($this->inHouseTraining)) { ?>
			<span class="section">TRAINING</span>	
				<?php if(!empty($this->outdoorTraining)) { ?>
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
						<?php foreach($this->outdoorTraining as $outsourceTraining) { ?>
						<tr>
							<td><?php echo $outsourceTraining['activity']; ?></td>
							<td><?php echo $outsourceTraining['description']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
				<?php } ?>

				<?php if(!empty($this->inHouseTraining)) { ?>
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
			<?php } } ?>		  
			  
			<?php if(!empty($this->parking['sop1']) || !empty($this->parking['sop2']) || !empty($this->parking['sop3'])) { ?>
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
				<?php if(!empty($this->parking['sop1'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->parking['sop1']; ?></div>
				<?php } ?>
				<?php if(!empty($this->parking['sop2'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->parking['sop2']; ?></div>
				<?php } ?>
				<?php if(!empty($this->parking['sop3'])) { ?>
					<div class="col-md-12 col-xs-12" style="border-bottom:1px solid #ddd; padding:5px 0px;"><?php echo $this->parking['sop3']; ?></div>
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
								$issue = '<span style="font-size:14px; font-weight:bold;">'.$specific_report['issue_type_name']."</span><br/><strong>".$timeField.' : </strong>'.$specific_report['time']."<br/><strong>Detail : </strong>".nl2br($specific_report['detail']);
					
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
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/5/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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
			myFormData.append('report_id', '<?php echo $this->parking['parking_report_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/parking/addcomment",
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