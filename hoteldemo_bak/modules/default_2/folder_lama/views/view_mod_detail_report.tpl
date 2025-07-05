<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link type="text/css" href="/css/jquery.ui.chatbox.css" rel="stylesheet" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>MANAGER ON DUTY REPORT</h2>
			<div class="clearfix"></div>		
			<h3><?php echo $this->name; ?></h3>	
			<a href="/default/mod/downloadmodreport/id/<?php echo $this->mod['mod_report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php echo $this->mod['report_date']; ?>
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
								<td><?php echo nl2br($utilitySpecificReport['description']); ?></td>
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
									<?php if(!empty($safetySpecificReport['picture'])) echo '<a class="image-popup-vertical-fit" href="/images/issues/'.str_replace(".","_large.",$safetySpecificReport['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$safetySpecificReport['picture']).'" height="50" style="margin-right:5px;" /></a>'; ?>
									<?php if(!empty($safetySpecificReport['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$safetySpecificReport['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$safetySpecificReport['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo $safetySpecificReport['location']; ?></td>
								<td><?php echo nl2br($safetySpecificReport['detail']); ?></td>
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
								<td><?php echo nl2br($securitySpecificReport['detail']); ?></td>
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
								{
									$uploaddate = explode("-",$hk_progress_report_shift['upload_date']); 
									$imageURL = "/images/progress_report_root/".$uploaddate[0].$uploaddate[1]."/";
								}
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
								{
									$uploaddate = explode("-",$hk_other_info['upload_date']); 
									$imageURL = "/images/progress_report_root/".$uploaddate[0].$uploaddate[1]."/";
								}
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
								<td><?php echo nl2br($hk_issue['description']); ?></td>
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
								<td><?php echo nl2br($parkingSpecificReport['detail']); ?></td>
								<td><?php echo $parking_completion_date; ?></td>
							</tr>
						<?php } } ?>
						</tbody>
					 </table>
					 </div>
				</fieldset>
			</div>
			
			<span class="section">JUMLAH PETUGAS</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. In House</legend>
					<div class="table-dv">
					<table id="mod-table" class="table">
						  <thead>
							<tr>
							  <th width="120" rowspan="2">Divisi</th>
							  <th colspan="5">Jumlah</th>
							  <th rowspan="2">Keterangan</th>
							</tr>
							<tr>
								<th width="80">Shift 1</th>
								<th width="80">Middle</th>
								<th width="80">Shift 2</th>
								<th width="80">Shift 3</th>
								<th width="80">Absent</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Engineering</td>
								<td><?php echo $this->mod['inhouse_engineering_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_engineering_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_engineering_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_engineering_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_engineering_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_engineering_keterangan']; ?></td>
							</tr>
							<tr>
								<td>BS</td>
								<td><?php echo $this->mod['inhouse_bs_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_bs_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_bs_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_bs_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_bs_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_bs_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Tenant Relation</td>
								<td><?php echo $this->mod['inhouse_tr_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_tr_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_tr_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_tr_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_tr_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_tr_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Security</td>
								<td><?php echo $this->mod['inhouse_security_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_security_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_security_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_security_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_security_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_security_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Safety</td>
								<td><?php echo $this->mod['inhouse_safety_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_safety_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_safety_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_safety_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_safety_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_safety_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><?php echo $this->mod['inhouse_parking_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_parking_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_parking_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_parking_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_parking_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_parking_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><?php echo $this->mod['inhouse_housekeeping_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_housekeeping_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_housekeeping_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_housekeeping_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_housekeeping_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_housekeeping_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Customer Service</td>
								<td><?php echo $this->mod['inhouse_reception_shift1']; ?></td>
								<td><?php echo $this->mod['inhouse_reception_middle']; ?></td>
								<td><?php echo $this->mod['inhouse_reception_shift2']; ?></td>
								<td><?php echo $this->mod['inhouse_reception_shift3']; ?></td>
								<td><?php echo $this->mod['inhouse_reception_absent']; ?></td>
								<td><?php echo $this->mod['inhouse_reception_keterangan']; ?></td>
							</tr>
						</tbody>
					 </table>
					 </div>
				</fieldset>
		
				<fieldset>
					<legend>B. Outsource</legend>
					<div class="table-dv">
					<table id="mod-table" class="table">
						  <thead>
							<tr>
							  <th width="120" rowspan="2">Divisi</th>
							  <th colspan="5">Jumlah</th>
							  <th rowspan="2">Keterangan</th>
							</tr>
							<tr>
								<th width="80">Shift 1</th>
								<th width="80">Middle</th>
								<th width="80">Shift 2</th>
								<th width="80">Shift 3</th>
								<th width="80">Absent</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Security</td>
								<td><?php echo $this->mod['outsource_security_safety_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_security_safety_middle']; ?></td>
								<td><?php echo $this->mod['outsource_security_safety_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_security_safety_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_security_safety_absent']; ?></td>
								<td><?php echo $this->mod['outsource_security_safety_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Safety</td>
								<td><?php echo $this->mod['outsource_safety_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_safety_middle']; ?></td>
								<td><?php echo $this->mod['outsource_safety_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_safety_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_safety_absent']; ?></td>
								<td><?php echo $this->mod['outsource_safety_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><?php echo $this->mod['outsource_parking_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_parking_middle']; ?></td>
								<td><?php echo $this->mod['outsource_parking_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_parking_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_parking_absent']; ?></td>
								<td><?php echo $this->mod['outsource_parking_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Valet</td>
								<td><?php echo $this->mod['outsource_valet_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_valet_middle']; ?></td>
								<td><?php echo $this->mod['outsource_valet_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_valet_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_valet_absent']; ?></td>
								<td><?php echo $this->mod['outsource_valet_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><?php echo $this->mod['outsource_housekeeping_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_housekeeping_middle']; ?></td>
								<td><?php echo $this->mod['outsource_housekeeping_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_housekeeping_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_housekeeping_absent']; ?></td>
								<td><?php echo $this->mod['outsource_housekeeping_keterangan']; ?></td>
							</tr>
							<tr>
								<td>Pest Control</td>
								<td><?php echo $this->mod['outsource_pest_control_shift1']; ?></td>
								<td><?php echo $this->mod['outsource_pest_control_middle']; ?></td>
								<td><?php echo $this->mod['outsource_pest_control_shift2']; ?></td>
								<td><?php echo $this->mod['outsource_pest_control_shift3']; ?></td>
								<td><?php echo $this->mod['outsource_pest_control_absent']; ?></td>
								<td><?php echo $this->mod['outsource_pest_control_keterangan']; ?></td>
							</tr>
						</tbody>
					 </table>
					 </div>
				</fieldset>
				
				<div class="table-dv">
				<table id="mod-table" class="table" style="background-color:#EEE;">
						<tr>
							<td width="120"><strong>TOTAL</strong></td>
							<td width="80"><?php echo $this->mod['total_shift1']; ?></td>
							<td width="80"><?php echo $this->mod['total_middle']; ?></td>
							<td width="80"><?php echo $this->mod['total_shift2']; ?></td>
							<td width="80"><?php echo $this->mod['total_shift3']; ?></td>
							<td width="80"><?php echo $this->mod['total_absent']; ?></td>
							<td><?php echo $this->mod['total_keterangan']; ?></td>
						</tr>
					 </table>
				</div>
			</div>

			<div class="col-md-12 col-sm-12 col-xs-12" style="padding-top:20px;">			
			  <span class="section">Jumlah Kendaraan Masuk</span>	
				 <table id="head-car-count" class="table">
					<tr>
						<th width="50%">Jenis Kendaraan</th>
						<th width="50%">Jumlah</th>
					</tr>
					<tr>
						<td>Car Count Parking</td>
						<td><?php echo $this->mod['car_parking']; ?></td>
					</tr>
					<tr>
						<td>Car Count Drop Off</td>
						<td><?php echo $this->mod['car_drop_off']; ?></td>
					</tr>
					<tr>
						<td>Box Vehicle</td>
						<td><?php echo $this->mod['box_vehicle']; ?></td>
					</tr>
					<tr>
						<td>Motorbike</td>
						<td><?php echo $this->mod['motorbike']; ?></td>
					</tr>
					<tr>
						<td>Bus</td>
						<td><?php echo $this->mod['bus']; ?></td>
					</tr>
					<tr>
						<td>Valet Service</td>
						<td><?php echo $this->mod['valet_parking']; ?></td>
					</tr>
					
					<tr>
						<td>Taxi Bluebird</td>
						<td><?php echo $this->mod['taxi_bluebird']; ?></td>
					</tr>
					<tr>
						<td>Taxi Non Blue bird</td>
						<td><?php echo $this->mod['taxi_non_bluebird']; ?></td>
					</tr>					
				 </table>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">FASILITAS/PERALATAN</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th width="200">Nama Fasilitas/Peralatan</th>
						  <th width="150">Kondisi (Foto bila ada)</th>
						  <th width="150">Lantai/Area</th>
						  <th>Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->equipments)) {
							foreach($this->equipments as $equipment) { ?>
							<tr>
								<td><?php echo $equipment['equipment_name']; ?></td>									
								<td align="center"><?php if(!empty($equipment['image'])) { ?><a class="image-popup-vertical-fit" href="/images/equipment/<?php echo $equipment['image']; ?>"><img src="/images/equipment/<?php echo $equipment['image']; ?>" class="thumb-img" /></a><?php } ?></td>
								<td><?php echo str_replace("<br>","&#13;",$equipment['area']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$equipment['keterangan']); ?></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				 </div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">INCIDENT</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th>Nama Insiden</th>
						  <th width="150">Kondisi (Foto)</th>
						  <th width="150">Lantai/Area</th>
						  <th width="150">Status</th>
						  <th width="200">Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->incident)) {
							foreach($this->incident as $incident) { 
								if(empty($incident['status'])) $incident['status'] = $incident['comment'];
								if($incident['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($incident['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
						?>
							<tr>
								<td><?php echo nl2br($incident['description']); ?></td>									
								<td align="center">
									<?php if(!empty($incident['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$incident['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$incident['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($incident['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$incident['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$incident['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$incident['location']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$incident['status']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$incident['keterangan']); ?></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">LOST & FOUND</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th>Kejadian</th>
						  <th width="150">Informasi Pelapor (Foto)</th>
						  <th width="150">Lantai/Area</th>
						  <th width="150">Status</th>
						  <th width="200">Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->lostFound)) {
							foreach($this->lostFound as $lostFound) { 
								if(empty($lostFound['status'])) $lostFound['status'] = $lostFound['comment'];	
								if($lostFound['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($lostFound['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";
							?>
							<tr>
								<td><?php echo nl2br($lostFound['description']); ?></td>									
								<td align="center">
									<?php if(!empty($lostFound['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$lostFound['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$lostFound['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($lostFound['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$lostFound['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$lostFound['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$lostFound['location']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$lostFound['status']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$lostFound['keterangan']); ?></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">GLITCH</span>	
				<div class="table-dv">
				 <table id="event-table" class="table">
					  <thead>
						<tr>
						  <th>Pelanggaran</th>
						  <th>Foto Pelanggaran</th>
						  <th>Lantai/Area</th>
						  <th>Tindakan Perbaikan</th>
						  <th>Keterangan</th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->glitch)) {
							foreach($this->glitch as $glitch) { 
								if(empty($glitch['status'])) $glitch['status'] = $glitch['comment'];
								if($glitch['issue_date'] > "2019-10-23 14:30:00")
									$imageURL = "/images/issues/".date("Y")."/";
								else
									$imageURL = "/images/issues/";

								if($glitch['solved_date'] > "2019-10-23 15:35:00")
									$solvedImageURL = "/images/issues/".date("Y")."/";
								else
									$solvedImageURL = "/images/issues/";	
							?>
							<tr>
								<td><?php echo nl2br($glitch['description']); ?></td>									
								<td align="center">
									<?php if(!empty($glitch['picture'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.str_replace(".","_large.",$glitch['picture']).'"><img src="'.$imageURL.str_replace(".","_thumb.",$glitch['picture']).'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; ?>
									<?php if(!empty($glitch['solved_picture'])) echo '<a class="image-popup-vertical-fit" href="'.$solvedImageURL.str_replace(".","_large.",$glitch['solved_picture']).'"><img src="'.$solvedImageURL.str_replace(".","_thumb.",$glitch['solved_picture']).'" height="50" /></a>'; ?>
								</td>
								<td><?php echo str_replace("<br>","&#13;",$glitch['location']); ?></td>
								<td align="center"><?php echo str_replace("<br>","&#13;",$glitch['status']); ?></td>
								<td><?php echo str_replace("<br>","&#13;",$glitch['keterangan']); ?></td>
							</tr>	
						<?php } } ?>
					</tbody>
				 </table>
				</div>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">WAY SYSTEM</span>	
				<?php if(!empty($this->mod['way_system_img'])) { ?><a class="image-popup-vertical-fit" href="/images/way_system/<?php echo $this->mod['way_system_img']; ?>"><img src="/images/way_system/<?php echo $this->mod['way_system_img']; ?>" class="thumb-img" /></a><?php } ?><input type="file" name="way_system_img" accept="image/jpeg">
			</div>
			
			  <div class="col-md-12 col-xs-12" style="padding-top:20px;">
				  <fieldset>
					<legend>SG</legend>
					<table id="inhouse-table" class="table">
						  <thead>
							<tr>
							  <th>Info</th>
							  <th>Jumlah</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Absent</td>
								<td><?php echo $this->mod['sg_absent']; ?></td>
							</tr>
							<tr>
								<td>Subtitute</td>
								<td><?php echo $this->mod['sg_subtitute']; ?></td>
							</tr>
							<tr>
								<td>Subtitute (No Beacon)</td>
								<td><?php echo $this->mod['sg_subtitute_no_beacon']; ?></td>
							</tr>
							<tr>
								<td>Negligence</td>
								<td><?php echo $this->mod['sg_negligence']; ?></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>HK</legend>
					<table id="cleaning-table" class="table">
						  <thead>
							<tr>
							  <th>Info</th>
							  <th>Jumlah</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Absent</td>
								<td><?php echo $this->mod['hk_absent']; ?></td>
							</tr>
							<tr>
								<td>Subtitute</td>
								<td><?php echo $this->mod['hk_subtitute']; ?></td>
							</tr>
							<tr>
								<td>Subtitute (No Beacon)</td>
								<td><?php echo $this->mod['hk_subtitute_no_beacon']; ?></td>
							</tr>
							<tr>
								<td>Negligence</td>
								<td><?php echo $this->mod['hk_negligence']; ?></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>	

			
			<?php if(!empty($this->events)) { ?>
				<div class="col-md-12 col-sm-12 col-xs-12">			
				<span class="section">EVENT</span>	
					<div class="table-dv">
					<table id="event-table" class="table">
						<thead>
							<tr>
							<th width="200">Nama Event</th>
							<th width="150">Kondisi Event (Foto)</th>
							<th width="200">Lantai</th>
							<th>Status Event</th>
							</tr>
						</thead>
						<tbody>
								<?php foreach($this->events as $event) { ?>
								<tr>
									<td><?php echo $event['event_name']; ?></td>									
									<td align="center"><?php if(!empty($event['event_img'])) { ?><a class="image-popup-vertical-fit" href="/images/event/<?php echo $event['event_img']; ?>"><img src="/images/event/<?php echo $event['event_img']; ?>" class="thumb-img" /></a><?php } ?></td>
									<td><?php echo str_replace("<br>","&#13;",$event['event_location']); ?></td>
									<td align="center"><?php echo str_replace("<br>","&#13;",$event['event_status']); ?></td>
								</tr>	
							<?php } ?>
						</tbody>
					</table>
					</div>
				</div>
			<?php } ?>
			
			<?php if(!empty($this->attachment)) { ?>
				<span class="section">Attachment</span>
				<ul>
					<?php
						foreach($this->attachment as $attachment) {
					?>
					<li><?php echo '<a href="'.$this->baseUrl.'/default/attachment/openattachment/c/8/f/'.$attachment['filename'].'" target="_blank" class="attachment-file">'.$attachment['description'].'</a>'; ?></li>
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
			myFormData.append('report_id', '<?php echo $this->mod['mod_report_id']; ?>');
			myFormData.append('comment', msg);

			$.ajax({
				url: "/default/mod/addcomment",
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