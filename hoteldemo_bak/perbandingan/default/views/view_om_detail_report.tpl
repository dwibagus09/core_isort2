<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />


  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>DAILY OPERATIONAL MALL REPORT</h2>
			<div class="clearfix"></div>			
			<a href="/default/operational/downloadomreport/id/<?php echo $this->operational['operation_mall_report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php echo $this->operational['report_date']; ?>
				</div>
			  </div>
			  <br/>
			  <span class="section">ISSUE</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. Building Service</legend>
					<div class="table-dv">
						<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th width="150" rowspan="2">Foto</th>
							  <th width="200" rowspan="2">Lokasi</th>
							  <th rowspan="2">Deskripsi</th>
							  <th width="100" rowspan="2">Completion Date</th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->utilitySpecificReport)) { 
							foreach($this->utilitySpecificReport as $utilitySpecificReport) { 
								$completion_date = explode(" ", $utilitySpecificReport['solved_date']);
								$bs_completion_date = date("j M Y", strtotime($completion_date[0]));

								if($utilitySpecificReport['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($utilitySpecificReport['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td>
									<?php if(!empty($utilitySpecificReport['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$utilitySpecificReport['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$utilitySpecificReport['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($utilitySpecificReport['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$utilitySpecificReport['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$utilitySpecificReport['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo $utilitySpecificReport['location']; ?></td>
								<td><?php echo $utilitySpecificReport['description']; ?></td>
								<td><?php echo $bs_completion_date; ?></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>B. Safety</legend>
					<div class="table-dv">
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th width="150" rowspan="2">Foto</th>
							  <th width="200" rowspan="2">Lokasi</th>
							  <th rowspan="2">Deskripsi</th>
							  <th width="100" rowspan="2">Completion Date</th>
							</tr>
						  </thead>
						  <tbody>
							<?php if(!empty($this->safetySpecificReport)) { 
							foreach($this->safetySpecificReport as $safetySpecificReport) {
								if(!empty($safetySpecificReport['completion_date']) && $safetySpecificReport['completion_date']!="0000-00-00 00:00:00") $safety_comp_date = $safetySpecificReport['completion_date'];
								else $safety_comp_date = $safetySpecificReport['solved_date'];
								$saf_completion_date = explode(" ", $safety_comp_date);
								$safety_completion_date = date("j M Y", strtotime($saf_completion_date[0]));
								if(!empty($safetySpecificReport['issue_id'])) $safetySpecificReport['detail'] = $safetySpecificReport['description'];
							
								if($safetySpecificReport['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($safetySpecificReport['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td>
									<?php if(!empty($safetySpecificReport['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$safetySpecificReport['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$safetySpecificReport['picture']).'" height="50" style="margin-right:5px;" /></a>'; ?>
									<?php if(!empty($safetySpecificReport['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$safetySpecificReport['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$safetySpecificReport['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo $safetySpecificReport['location']; ?></td>
								<td><?php echo $safetySpecificReport['detail']; ?></td>
								<td><?php echo $safety_completion_date; ?></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					 </div>
				</fieldset>

				<fieldset>
					<legend>C. Security</legend>
					<div class="table-dv">
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th width="150" rowspan="2">Foto</th>
							  <th width="200" rowspan="2">Lokasi</th>
							  <th rowspan="2">Deskripsi</th>
							  <th width="100" rowspan="2">Completion Date</th>
							</tr>
						  </thead>
						  <tbody>
							<?php if(!empty($this->securitySpecificReport)) {
							foreach($this->securitySpecificReport as $securitySpecificReport) { 
								if(!empty($securitySpecificReport['completion_date']) && $securitySpecificReport['completion_date']!="0000-00-00 00:00:00") $security_comp_date = $securitySpecificReport['completion_date'];
								else $security_comp_date = $securitySpecificReport['solved_date'];
								$sec_completion_date = explode(" ", $security_comp_date);
								$security_completion_date = date("j M Y", strtotime($sec_completion_date[0]));
								if(!empty($securitySpecificReport['issue_id'])) $securitySpecificReport['detail'] = $securitySpecificReport['description'];
									if($securitySpecificReport['issue_date'] > "2019-10-23 14:30:00")
										$imageURL = "/images/issues/".date("Y")."/";
									else
										$imageURL = "/images/issues/";

									if($securitySpecificReport['solved_date'] > "2019-10-23 15:35:00")
										$solvedImageURL = "/images/issues/".date("Y")."/";
									else
										$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td>
									<?php if(!empty($securitySpecificReport['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$securitySpecificReport['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$securitySpecificReport['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($securitySpecificReport['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$securitySpecificReport['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$securitySpecificReport['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo $securitySpecificReport['location']; ?></td>
								<td><?php echo $securitySpecificReport['detail']; ?></td>
								<td><?php echo $security_completion_date; ?></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					 </div>
				</fieldset>
				
				<fieldset>
					<legend>D. Housekeeping</legend>
					<div class="table-dv">
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th width="150" rowspan="2">Foto</th>
							  <th rowspan="2">Lokasi</th>
							  <th width="250" rowspan="2">Status</th>
							  <th width="100" rowspan="2">Completion Date</th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->hk_progress_report_shift)) { 
							foreach($this->hk_progress_report_shift as $hk_progress_report_shift) {
								$hk_progress_report_shift_completion_date = explode(" ", $hk_progress_report_shift['completion_date']);
								$hk_progress_report_shift_comp_date = date("j M Y", strtotime($hk_progress_report_shift_completion_date[0]));

								if($hk_progress_report_shift['upload_date'] > "2019-10-23 23:59:59")
									$imageURL = "/images/progress_report_root/".date("Ym")."/";
								else
									$imageURL = "/images/progress_report/";			
							?>
							<tr>
								<td>
									<?php if(!empty($hk_progress_report_shift['img_before'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_before'].'"><img src="'.$imageURL.$hk_progress_report_shift['img_before'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
										if(!empty($hk_progress_report_shift['img_progress'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_progress'].'"><img src="'.$imageURL.$hk_progress_report_shift['img_progress'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
										if(!empty($hk_progress_report_shift['img_after'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_after'].'"><img src="'.$imageURL.$hk_progress_report_shift['img_after'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
									?>
								</td>
								<td><?php echo $hk_progress_report_shift['area']; ?></td>
								<td><?php echo $hk_progress_report_shift['status']; ?></td>
								<td><?php echo $hk_progress_report_shift_comp_date; ?></td>
							</tr>
							<?php }  } ?>
							<?php if(!empty($this->hk_other_info)) { 
							foreach($this->hk_other_info as $hk_other_info) { 
								$hk_other_info_completion_date = explode(" ", $hk_other_info['completion_date']);
								$hk_other_info_comp_date = date("j M Y", strtotime($hk_other_info_completion_date[0]));
								if($hk_other_info['upload_date'] > "2019-10-23 23:59:59")
									$imageURL = "/images/progress_report_root/".date("Ym")."/";
								else
									$imageURL = "/images/progress_report/";	
							?>
							<tr>
								<td>
									<?php if(!empty($hk_other_info['img_progress'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_other_info['img_progress'].'"><img src="'.$imageURL.$hk_other_info['img_progress'].'" height="50" style="margin-right:5px;" /></a>';	?>
								</td>
								<td><?php echo $hk_other_info['area']; ?></td>
								<td><?php echo $hk_other_info['status']; ?></td>
								<td><?php echo $hk_other_info_comp_date; ?></td>
							</tr>
							<?php }  } ?>
														
							<?php if(!empty($this->hk_issues)) {
							foreach($this->hk_issues as $hk_issue) {
								$hk_comp_date = $hk_issue['solved_date'];
								$comp_date = explode(" ", $hk_comp_date);
								$hk_completion_date = date("j M Y", strtotime($comp_date[0]));
							
								if($hk_issue['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($hk_issue['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td>
									<?php if(!empty($hk_issue['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$hk_issue['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$hk_issue['picture']).'" height="50" style="margin-right:5px;" /></a>'; ?>
									<?php if(!empty($hk_issue['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$hk_issue['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$hk_issue['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo $hk_issue['location']; ?></td>
								<td><?php echo $hk_issue['description']; ?></td>
								<td><?php echo $hk_completion_date; ?></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					</div>
				</fieldset>
				
				<fieldset>
					<legend>E. Parking &amp; Traffic</legend>
					<div class="table-dv">
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th width="150" rowspan="2">Foto</th>
							  <th width="100" rowspan="2">Time</th>
							  <th width="150" rowspan="2">Lokasi</th>
							  <th rowspan="2">Deskripsi</th>
							  <th width="100" rowspan="2">Completion Date</th>
							</tr>
						  </thead>
						  <tbody>
						  <?php if(!empty($this->parkingSpecificReport)) { 
							foreach($this->parkingSpecificReport as $parkingSpecificReport) { 
								if(!empty($parkingSpecificReport['completion_date'])) $parking_comp_date = $parkingSpecificReport['completion_date'];
								else $parking_comp_date = $parkingSpecificReport['solved_date'];
								$park_completion_date = explode(" ", $parking_comp_date);
								$parking_completion_date = date("j M Y", strtotime($park_completion_date[0]));

								if(!empty($parkingSpecificReport['issue_id'])) $parkingSpecificReport['detail'] = $parkingSpecificReport['description'];
								
								if($parkingSpecificReport['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($parkingSpecificReport['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td>
									<?php if(!empty($parkingSpecificReport['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$parkingSpecificReport['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$parkingSpecificReport['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($parkingSpecificReport['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$parkingSpecificReport['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$parkingSpecificReport['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php if($parkingSpecificReport['issue_type_id'] != 4) echo $parkingSpecificReport['time']; ?></td>
								<td><?php if($parkingSpecificReport['issue_type_id'] != 6) { if($parkingSpecificReport['issue_type_id'] < 4) echo $parkingSpecificReport['location']; else echo $parkingSpecificReport['area']; } ?></td>
								<td><?php echo $parkingSpecificReport['detail']; ?></td>
								<td><?php echo $parking_completion_date; ?>></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					 </div>
				</fieldset>
			</div>
			
			<?php if(!empty($this->marketing_promotion)) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">			
				<span class="section">MARKETING &amp; PROMOTION</span>
					<div class="table-dv">			  
					<table id="marketing-promotion-table" class="table">
						<thead>
							<tr>
							<th>Nama Event</th>
							<th>Foto-foto</th>
							<th>Lokasi</th>
							<th>Kondisi Event</th>
							<th>Periode Event</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($this->marketing_promotion as $marketing_promotion) { ?>
								<tr>
									<td><?php echo $marketing_promotion['event_name']; ?></td>									
									<td align="center"><?php if(!empty($marketing_promotion['event_img'])) { ?><img src="/images/event/<?php echo $marketing_promotion['event_img']; ?>" class="thumb-img" /><?php } ?></td>
									<td><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_location']); ?></td>
									<td align="center"><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_condition']); ?></td>
									<td align="center"><?php echo str_replace("<br>","&#13;",$marketing_promotion['event_period']); ?></td>
								</tr>	
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
			<?php } ?>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">SUMMARY WO/WR TENANT &amp; INTERNAL</span>	
				<div class="table-dv">
				 <table id="summary-wo-wr-tenant-internal" class="table">
					  <thead>
						<tr>
						  <th rowspan="2">Department</th>
						  <th rowspan="2">No. of Req. WO per today</th>
						  <th rowspan="2">Completed WO per today</th>
						  <th rowspan="2">No. of Outstanding WO per today</th>
						  <th colspan="2">Accumulate</th>
						</tr>
						<tr>
						  <th>Previous Outstanding</th>
						  <th>Total Outstanding</th>
						</tr>
					  </thead>
					  <tbody>
						<tr>
							<td>Engineering</td>
							<td><?php echo $this->operational['engineering_no_of_req_wo']; ?></td>
							<td><?php echo $this->operational['engineering_completed_wo']; ?></td>
							<td><?php echo $this->operational['engineering_no_of_outstanding_wo']; ?></td>
							<td><?php echo $this->operational['engineering_previous_outstanding']; ?></td>
							<td><?php echo $this->operational['engineering_next_outstanding']; ?></td>
						</tr>
						<tr>
							<td>BS/Civil</td>
							<td><?php echo $this->operational['bs_no_of_req_wo']; ?></td>
							<td><?php echo $this->operational['bs_completed_wo']; ?></td>
							<td><?php echo $this->operational['bs_no_of_outstanding_wo']; ?></td>
							<td><?php echo $this->operational['bs_previous_outstanding']; ?></td>
							<td><?php echo $this->operational['bs_next_outstanding']; ?></td>
						</tr>
						<tr>
							<td>Housekeeping</td>
							<td><?php echo $this->operational['housekeeping_no_of_req_wo']; ?></td>
							<td><?php echo $this->operational['housekeeping_completed_wo']; ?></td>
							<td><?php echo $this->operational['housekeeping_no_of_outstanding_wo']; ?></td>
							<td><?php echo $this->operational['housekeeping_previous_outstanding']; ?></td>
							<td><?php echo $this->operational['housekeeping_next_outstanding']; ?></td>
						</tr>
						<tr>
							<td>Parking</td>
							<td><?php echo $this->operational['parking_no_of_req_wo']; ?></td>
							<td><?php echo $this->operational['parking_completed_wo']; ?></td>
							<td><?php echo $this->operational['parking_no_of_outstanding_wo']; ?></td>
							<td><?php echo $this->operational['parking_previous_outstanding']; ?></td>
							<td><?php echo $this->operational['parking_next_outstanding']; ?></td>
						</tr>
						<tr>
							<td>Others</td>
							<td><?php echo $this->operational['other_no_of_req_wo']; ?></td>
							<td><?php echo $this->operational['other_completed_wo']; ?></td>
							<td><?php echo $this->operational['other_no_of_outstanding_wo']; ?></td>
							<td><?php echo $this->operational['other_previous_outstanding']; ?></td>
							<td><?php echo $this->operational['other_next_outstanding']; ?></td>
						</tr>
					</tbody>
				 </table>
				 </div>
			</div>
			<br/>
			<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px;">			
			  <span class="section">PERHITUNGAN HEAD COUNT &amp; CAR COUNT</span>	
				 <table id="head-car-count" class="table">
					<tr>
						<td width="150">A. Head Count</td>
						<td></td>
						<td><?php echo $this->operational['head_count']; ?></td>
					</tr>
					<tr>
						<td>B. Total Car Count</td>
						<td></td>
						<td><?php echo $this->operational['total_car_count']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>1. Car Parking</td>
						<td><?php echo $this->operational['car_parking']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>2. Car Drop Off</td>
						<td><?php echo $this->operational['car_drop_off']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>3. Valet Parking</td>
						<td><?php echo $this->operational['valet_parking']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>4. Box Vehicle</td>
						<td><?php echo $this->operational['box_vehicle']; ?></td>
					</tr>
					<tr>
						<td></td>
						<td>5. Taxi</td>
						<td><?php echo $this->operational['taxi_bluebird']; ?></td>
					</tr>
					<tr>
						<td>C. Motorbike</td>
						<td></td>
						<td><?php echo $this->operational['motorbike']; ?></td>
					</tr>
					<tr>
						<td>D. Bus</td>
						<td></td>
						<td><?php echo $this->operational['bus']; ?></td>
					</tr>
				 </table>
			</div>
			
			<?php if(!empty($this->attachment)) { ?>
				<span class="section">Attachment</span>
				<ul>
					<?php
						foreach($this->attachment as $attachment) {
					?>
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/7/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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
			myFormData.append('report_id', '<?php echo $this->operational['operation_mall_report_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/operational/addcomment",
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