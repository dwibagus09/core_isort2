<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  <div class="x_title">
			<h2><?php echo $this->title; ?></h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">

			<form id="mod-form" class="form-horizontal form-label-left" action="/default/mod/savereport" method="POST" enctype="multipart/form-data" onsubmit="$('body').mLoading();">
				<input type="hidden" id="mod_report_id" name="mod_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['mod_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->mod['report_date'])) echo $this->mod['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  
			  <span class="section">ISSUE</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. BUILDING SERVICE</legend>
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
								if(!empty($utilitySpecificReport['solved_date']) && $utilitySpecificReport['solved_date'] != "0000-00-00 00:00:00")
									$completion_date = explode(" ", $utilitySpecificReport['solved_date']);
								else
									$completion_date[0] = "";

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
								<td><?php if(intval($utilitySpecificReport['solved']) == 1) { ?><input type="hidden" name="utility_specific_report_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $utilitySpecificReport['issue_id']; ?>"><?php } ?><input type="text" name="utility_specific_report_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $completion_date[0]; ?>" <?php if(intval($utilitySpecificReport['solved']) < 1) echo "disabled"; ?>></td>
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
								if(!empty($safetySpecificReport['completion_date']) && $security_comp_date!= "0000-00-00 00:00:00") $safety_comp_date = $safetySpecificReport['completion_date'];
								else $safety_comp_date = $safetySpecificReport['solved_date'];
								
								if(!empty($safety_comp_date) && $safety_comp_date!="0000-00-00 00:00:00")
									$safety_completion_date = explode(" ", $safety_comp_date);
								else
									$safety_completion_date[0] = "";

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
								<td><?php if(($safetySpecificReport['solved'] == 1 && !empty($safetySpecificReport['issue_id'])) || empty($safetySpecificReport['issue_id'])) { ?><input type="hidden" name="safety_specific_report_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $safetySpecificReport['specific_report_id']; ?>"><?php } ?><input type="text" name="safety_specific_report_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $safety_completion_date[0]; ?>" <?php if($safetySpecificReport['solved'] != 1 && !empty($safetySpecificReport['issue_id'])) echo "disabled"; ?>></td>
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
								if(!empty($securitySpecificReport['completion_date']) && $securitySpecificReport['completion_date']!= "0000-00-00 00:00:00") $security_comp_date = $securitySpecificReport['completion_date'];
								else $security_comp_date = $securitySpecificReport['solved_date'];
								
								if(!empty($security_comp_date) && $security_comp_date!= "0000-00-00 00:00:00")
									$security_completion_date = explode(" ", $security_comp_date);
								else
									$security_completion_date[0] = "";
								
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
								<td><?php if(($securitySpecificReport['solved'] == 1 && !empty($securitySpecificReport['issue_id'])) || empty($securitySpecificReport['issue_id'])) { ?><input type="hidden" name="security_specific_report_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $securitySpecificReport['specific_report_id']; ?>"><?php } ?><input type="text" name="security_specific_report_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $security_completion_date[0]; ?>" <?php if(!empty($securitySpecificReport['issue_id'])) echo "disabled"; ?>></td>
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
								if(!empty($hk_progress_report_shift['completion_date']) && $hk_progress_report_shift['completion_date']!="0000-00-00 00:00:00")
									$hk_progress_report_shift_completion_date = explode(" ", $hk_progress_report_shift['completion_date']);
								else
									$hk_progress_report_shift_completion_date[0] = "";
									
								if($hk_progress_report_shift['upload_date'] > "2019-10-23 23:59:59")
									$imageURL = "/images/progress_report_root/".date("Ym")."/";
								else
									$imageURL = "/images/progress_report/";	
							?>
							<tr>
								<td>
									<?php if(!empty($hk_progress_report_shift['img_before'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_before'].'"><img src="/images/progress_report/'.$hk_progress_report_shift['img_before'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
										if(!empty($hk_progress_report_shift['img_progress'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_progress'].'"><img src="/images/progress_report/'.$hk_progress_report_shift['img_progress'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
										if(!empty($hk_progress_report_shift['img_after'])) echo '<a class="image-popup-vertical-fit" href="'.$imageURL.$hk_progress_report_shift['img_after'].'"><img src="/images/progress_report/'.$hk_progress_report_shift['img_after'].'" height="50" style="margin-right:5px; margin-bottom:5px;" /></a>'; 
									?>
								</td>
								<td><?php echo $hk_progress_report_shift['area']; ?></td>
								<td><?php echo $hk_progress_report_shift['status']; ?></td>
								<td><input type="hidden" name="hk_progress_report_shift_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $hk_progress_report_shift['progress_report_id']; ?>"><input type="text" name="hk_progress_report_shift_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $hk_progress_report_shift_completion_date[0]; ?>"></td>
							</tr>
							<?php }  } ?>
							<?php if(!empty($this->hk_other_info)) { 
							foreach($this->hk_other_info as $hk_other_info) { 
								if(!empty($hk_other_info['completion_date']) && $hk_other_info['completion_date']!="0000-00-00 00:00:00")
									$hk_other_info_completion_date = explode(" ", $hk_other_info['completion_date']);
								else
									$hk_other_info_completion_date[0] = "";
									
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
								<td><input type="hidden" name="hk_other_info_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $hk_other_info['other_info_id']; ?>"><input type="text" name="hk_other_info_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $hk_other_info_completion_date[0]; ?>"></td>
							</tr>
							<?php }  } ?>
							<?php if(!empty($this->hk_issues)) { 
							foreach($this->hk_issues as $hk_issue) { 
								if(!empty($hk_issue['solved_date']) && $hk_issue['solved_date']!="0000-00-00 00:00:00")
									$hk_issue_completion_date = explode(" ", $hk_issue['solved_date']);
								else
									$hk_issue_completion_date[0] = "";
									
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
								<td><input type="text" name="hk_issue_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $hk_issue_completion_date[0]; ?>"  <?php if(!empty($hk_issue['issue_id'])) echo "disabled"; ?>></td>
							</tr>
							<?php }  } ?>
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
								if(!empty($parkingSpecificReport['completion_date']) && $parkingSpecificReport['completion_date'] != "0000-00-00 00:00:00") $parking_comp_date = $parkingSpecificReport['completion_date'];
								else $parking_comp_date = $parkingSpecificReport['solved_date'];
								
								if(!empty($parking_comp_date) && $parking_comp_date!= "0000-00-00 00:00:00")
									$parking_completion_date = explode(" ", $parking_comp_date);
								else
									$parking_completion_date[0] = "";
								
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
								<td><?php if(($parkingSpecificReport['solved'] == 1 && !empty($parkingSpecificReport['issue_id'])) || empty($parkingSpecificReport['issue_id'])) { ?><input type="hidden" name="parking_specific_report_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $parkingSpecificReport['specific_report_id']; ?>"><?php } ?><input type="text" name="parking_specific_report_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker" value="<?php echo $parking_completion_date[0]; ?>" <?php if(!empty($parkingSpecificReport['issue_id'])) echo "disabled"; ?>></td>
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
								<td><input type="text" name="inhouse_engineering_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_engineering_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_engineering_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_engineering_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_engineering_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_engineering_absent']; ?>" required></td>
								<td><textarea name="inhouse_engineering_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_engineering_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>BS</td>
								<td><input type="text" name="inhouse_bs_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_bs_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_bs_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_bs_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_bs_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_bs_absent']; ?>" required></td>
								<td><textarea name="inhouse_bs_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_bs_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Tenant Relation</td>
								<td><input type="text" name="inhouse_tr_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_tr_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_tr_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_tr_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_tr_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_tr_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_tr_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_tr_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_tr_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_tr_absent']; ?>" required></td>
								<td><textarea name="inhouse_tr_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_tr_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Security</td>
								<td><input type="text" name="inhouse_security_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_security_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_security_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_security_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_security_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_security_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_security_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_security_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_security_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_security_absent']; ?>" required></td>
								<td><textarea name="inhouse_security_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_security_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Safety</td>
								<td><input type="text" name="inhouse_safety_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_safety_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_safety_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_safety_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_safety_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_safety_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_safety_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_safety_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_safety_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_safety_absent']; ?>" required></td>
								<td><textarea name="inhouse_safety_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_safety_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="inhouse_parking_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_parking_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_parking_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_parking_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_parking_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_parking_absent']; ?>" required></td>
								<td><textarea name="inhouse_parking_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_parking_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="inhouse_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_housekeeping_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_housekeeping_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_housekeeping_absent']; ?>" required></td>
								<td><textarea name="inhouse_housekeeping_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_housekeeping_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Customer Service</td>
								<td><input type="text" name="inhouse_reception_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_reception_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_reception_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_reception_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_reception_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_reception_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_reception_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_reception_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_reception_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['inhouse_reception_absent']; ?>" required></td>
								<td><textarea name="inhouse_reception_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['inhouse_reception_keterangan']; ?></textarea></td>
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
								<td><input type="text" name="outsource_security_safety_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_security_safety_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_security_safety_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_security_safety_middle']; ?>" required></td>
								<td><input type="text" name="outsource_security_safety_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_security_safety_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_security_safety_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_security_safety_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_security_safety_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_security_safety_absent']; ?>" required></td>
								<td><textarea name="outsource_security_safety_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_security_safety_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Safety</td>
								<td><input type="text" name="outsource_safety_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_safety_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_safety_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_safety_middle']; ?>" required></td>
								<td><input type="text" name="outsource_safety_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_safety_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_safety_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_safety_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_safety_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_safety_absent']; ?>" required></td>
								<td><textarea name="outsource_safety_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_safety_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="outsource_parking_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_parking_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_parking_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_parking_middle']; ?>" required></td>
								<td><input type="text" name="outsource_parking_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_parking_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_parking_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_parking_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_parking_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_parking_absent']; ?>" required></td>
								<td><textarea name="outsource_parking_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_parking_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Valet</td>
								<td><input type="text" name="outsource_valet_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_valet_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_valet_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_valet_middle']; ?>" required></td>
								<td><input type="text" name="outsource_valet_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_valet_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_valet_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_valet_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_valet_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_valet_absent']; ?>" required></td>
								<td><textarea name="outsource_valet_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_valet_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="outsource_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_housekeeping_middle']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_housekeeping_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_housekeeping_absent']; ?>" required></td>
								<td><textarea name="outsource_housekeeping_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_housekeeping_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Pest Control</td>
								<td><input type="text" name="outsource_pest_control_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_pest_control_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_pest_control_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_pest_control_middle']; ?>" required></td>
								<td><input type="text" name="outsource_pest_control_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_pest_control_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_pest_control_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_pest_control_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_pest_control_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['outsource_pest_control_absent']; ?>" required></td>
								<td><textarea name="outsource_pest_control_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['outsource_pest_control_keterangan']; ?></textarea></td>
							</tr>
						</tbody>
					 </table>
					 </div>
				</fieldset>
				
				<div class="table-dv">
				<table id="mod-table" class="table" style="background-color:#EEE;">
						<tr>
							<td width="120"><strong>TOTAL</strong></td>
							<td width="80"><input type="text" name="total_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['total_shift1']; ?>" required></td>
							<td width="80"><input type="text" name="total_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['total_middle']; ?>" required></td>
							<td width="80"><input type="text" name="total_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['total_shift2']; ?>" required></td>
							<td width="80"><input type="text" name="total_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['total_shift3']; ?>" required></td>
							<td width="80"><input type="text" name="total_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->mod['total_absent']; ?>" required></td>
							<td><textarea name="total_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->mod['total_keterangan']; ?></textarea></td>
						</tr>
					 </table>
				</div>
			</div>
			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:200px;">Halaman Berikutnya</button>
				</div>
			  </div>
			</form>
		  </div>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 <script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {		
	<?php if($this->editMode == 1) { ?>
		$(".edit-mod").css("display", "block");
		$(".edit-mod").addClass('current-page');
		$(".edit-mod").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	<?php } ?>
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	$(".add-event").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><input type="hidden" name="event_id[]" class="form-control col-md-7 col-xs-12" required></td>	
			<td><textarea name="event_name[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>									
			<td align="center"><input type="file" name="event_image[]"></td>
			<td><textarea name="event_location[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><textarea name="event_status[]" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required></textarea></td>
			<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#event-table").append(row);
	});
	
	$("#add-attachment").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td><input type="hidden" name="attachment_id[]" class="form-control col-md-7 col-xs-12" required></td>									
				<td align="center"><input type="file" name="attachment_file[]"></td>
				<td><input type="text" name="attachment-description[]" class="form-control col-md-7 col-xs-12" required /></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#attachment-table").append(row);
	});
	
	$('#mod-form').on('submit', function(event){
		$("body").mLoading();
	});
});	
</script>