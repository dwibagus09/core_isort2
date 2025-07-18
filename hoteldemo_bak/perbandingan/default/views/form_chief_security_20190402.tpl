<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<?php 
if(empty($this->security['report_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->security['report_date']);
	$cur_date = $cur_report_date[0];
}
?>

<!-- page content -->
<div class="right_col" role="main">
  <div class="">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2><?php echo $this->title; ?></h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">

			<form class="form-horizontal form-label-left" action="/default/security/savechiefreport" method="POST" enctype="multipart/form-data" onSubmit="$('body').mLoading();">
				<input type="hidden" id="chief_security_report_id" name="chief_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_security_report_id']; ?>">
				<input type="hidden" id="morning_security_report_id" name="morning_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['morning']['security_id']; ?>">
				<input type="hidden" id="afternoon_security_report_id" name="afternoon_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['afternoon']['security_id']; ?>">
				<input type="hidden" id="night_security_report_id" name="night_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['night']['security_id']; ?>">
				<input type="hidden" name="report_date" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['report_date']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->security['created_date'])) echo $this->security['created_date']; ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['chief_security_reporting_time']; ?>
				</div>
			  </div>
			  
			  <span class="section">MAN POWER</span>
			  <fieldset>
				<legend>In House</legend>
				<h5>Supervisor</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_inhouse_malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="supervisor_inhouse_malam" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_malam" required="required" type="text" value="<?php echo $this->security['night']['supervisor']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="supervisor_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['morning']['supervisor']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="supervisor_inhouse_siang" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_siang" required="required" type="text" value="<?php echo $this->security['afternoon']['supervisor']; ?>">
					</div>
				  </div>
				  
				  <h5>Staff Posko</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff-posko-inhouse-malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_malam" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_malam" required="required" type="text" value="<?php echo $this->security['night']['staff_posko']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_posko_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['morning']['staff_posko']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_posko_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_siang" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_siang" required="required" type="text" value="<?php echo $this->security['afternoon']['staff_posko']; ?>">
					</div>
				  </div>
				  
				  <h5>Staff CCTV</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_malam" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_malam" required="required" type="text" value="<?php echo $this->security['night']['staff_cctv']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['morning']['staff_cctv']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_siang" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_siang" required="required" type="text" value="<?php echo $this->security['afternoon']['staff_cctv']; ?>">
					</div>
				  </div>
				  
				  <h5>Safety</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_malam" class="form-control col-md-7 col-xs-12" name="safety_inhouse_malam" required="required" type="text" value="<?php echo $this->security['night']['safety']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="safety_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['morning']['safety']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_siang" class="form-control col-md-7 col-xs-12" name="safety_inhouse_siang" required="required" type="text" value="<?php echo $this->security['afternoon']['safety']; ?>">
					</div>
				  </div>
			</fieldset>
			<fieldset>
				<legend>Vendor</legend>
					<h5>CHIEF/WAKA</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chief_spd"><?php echo $this->vendor[0]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="chief_spd" class="form-control col-md-7 col-xs-12" name="chief_spd" required="required" type="text" value="<?php echo $this->security['chief_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chief_army"><?php echo $this->vendor[1]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="chief_army" name="chief_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_army']; ?>">
					</div>
				  </div>
				  
				  <h5>PANWAS</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="panwas_spd"><?php echo $this->vendor[0]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="panwas_spd" class="form-control col-md-7 col-xs-12" name="panwas_spd" required="required" type="text" value="<?php echo $this->security['panwas_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="panwas_army"><?php echo $this->vendor[1]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="panwas_army" name="panwas_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['panwas_army']; ?>">
					</div>
				  </div>
				  
				  <h5>DANTON/DANRU PAGI</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="danton_pagi_spd"><?php echo $this->vendor[0]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="danton_pagi_spd" class="form-control col-md-7 col-xs-12" name="danton_pagi_spd" required="required" type="text" value="<?php echo $this->security['danton_pagi_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="danton_pagi_army"><?php echo $this->vendor[1]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="danton_pagi_army" name="danton_pagi_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['danton_pagi_army']; ?>">
					</div>
				  </div>
				  
				  <h5>KEKUATAN</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kekuatan_spd"><?php echo $this->vendor[0]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="kekuatan_spd" class="form-control col-md-7 col-xs-12" name="kekuatan_spd" required="required" type="text" value="<?php echo $this->security['kekuatan_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kekuatan_army"><?php echo $this->vendor[1]['vendor_name']; ?> <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="kekuatan_army" name="kekuatan_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['kekuatan_army']; ?>">
					</div>
				  </div>
			</fieldset>
			
			<span class="section">PERLENGKAPAN</span>
			<div class="table-dv">
			  <table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th rowspan="2" class="id-hidden"></th>
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
					<td class="id-hidden"><input type="hidden" name="id_equipment_list[<?php echo $i; ?>]" value="<?php echo $equipment['security_equipment_list_id']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="equipment_name[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['equipment_name']; ?>" disabled></td>
					<td><input class="form-control col-md-7 col-xs-12" name="equipment_vendor[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['vendor_name']; ?>" disabled></td>
					<td><input class="form-control col-md-7 col-xs-12" name="total_equipment[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['total_equipment']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="ok_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['ok_condition']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="bad_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['bad_condition']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="description[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['description']; ?>"></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			</div> 
			  <span class="section">BRIEFING</span>				
			  <table id="briefing-table" class="table">
				<tr id="night-briefing">
				  <td>
					<h4>Night Briefing</h4>
					<div class="col-md-4 col-xs-12"><textarea id="night_briefing1" name="night_briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['night']['briefing']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="night_briefing2" name="night_briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['night']['briefing2']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="night_briefing3" name="night_briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['night']['briefing3']); ?></textarea></div>
				  </td>
				</tr>
				<tr id="morning-briefing">
				  <td style="border-top:none;">
					<h4>Morning Briefing</h4>
					<div class="col-md-4 col-xs-12"><textarea id="morning_briefing1" name="morning_briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['morning']['briefing']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="morning_briefing2" name="morning_briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['morning']['briefing2']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="morning_briefing3" name="morning_briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['morning']['briefing3']); ?></textarea></div>
				  </td>
				</tr>
				<tr id="afternoon-briefing">
				  <td>
					<h4>Afternoon Briefing</h4>
					<div class="col-md-4 col-xs-12"><textarea id="afternoon_briefing1" name="afternoon_briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;", $this->security['afternoon']['briefing']); ?></textarea></div> 
					<div class="col-md-4 col-xs-12"><textarea id="afternoon_briefing2" name="afternoon_briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['afternoon']['briefing2']); ?></textarea></div> 
					<div class="col-md-4 col-xs-12"><textarea id="afternoon_briefing3" name="afternoon_briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;"><?php echo str_replace("<br>","&#13;",$this->security['afternoon']['briefing3']); ?></textarea></div>
				  </td>
				</tr>
			</table>
			  
			<span class="section">TRAINING</span>		
			  <div class="col-md-6 col-xs-12">
				<h4>Outsource <a class="add-training" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></h4>
				<table id="outsource-training-table" class="table">
					<?php if(!empty($this->outdoorTraining)) {
						foreach($this->outdoorTraining as $outdoorTraining) { ?>
						<tr><td class="id-hidden"><input type="hidden" name="training_type[]" value="<?php echo $outdoorTraining['training_type']; ?>"></td><td><select name="training_activity[]" class="form-control" required>
							<?php if(!empty($this->training_activity)) { 
								foreach($this->training_activity as $training_activity) {
							?>
							<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $outdoorTraining['training_activity_id']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
							<?php } } ?>		
						</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;"><?php echo $outdoorTraining['description']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>
					<?php } } ?>
				</table>
			  </div>
			  <div class="col-md-6 col-xs-12">
				<h4>In House <a class="add-training" data-typeid="2" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></h4>
				<table id="inhouse-training-table" class="table">
					<?php if(!empty($this->inHouseTraining)) {
						foreach($this->inHouseTraining as $inHouseTraining) { ?>
						<tr><td class="id-hidden"><input type="hidden" name="training_type[]" value="<?php echo $inHouseTraining['training_type']; ?>"></td><td><select name="training_activity[]" class="form-control" required>
							<?php if(!empty($this->training_activity)) { 
								foreach($this->training_activity as $training_activity) {
							?>
							<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $inHouseTraining['training_activity_id']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
							<?php } } ?>		
						</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;"><?php echo $inHouseTraining['description']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>
					<?php } } ?>
				</table>
			  </div>
			  
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
			<input id="sosialisasi_sop_a" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_a" required="required" type="text" value="<?php echo $this->security['sosialisasi_sop_a']; ?>">
			<input id="sosialisasi_sop_b" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_b" required="required" type="text" value="<?php echo $this->security['sosialisasi_sop_b']; ?>">
			<input id="sosialisasi_sop_c" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_c" required="required" type="text" value="<?php echo $this->security['sosialisasi_sop_c']; ?>">  
			<br/><br/>
			<span class="section" style="clear:both; padding-top:20px;">SPECIFIC REPORT &nbsp;<a id="add-specific-report" href="#specific-report-form"><i class="fa fa-plus-square"></i></a></span>
			<table id="specific-report-table" class="table">
			<?php if(!empty($this->specific_report)) { 
				foreach($this->specific_report as $specific_report) {
					if($specific_report['issue_type_id'] < 4)
					{
						$issueDate = explode(" ",$specific_report['issue_date']);
						$specific_report['time'] = $issueDate[1];
			?>
					<tr id="<?php echo $specific_report['issue_type'].$specific_report['issue_id']; ?>">
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="<?php echo $specific_report['issue_id']; ?>"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type_id']; ?>"><input type="hidden" id="securityid" name="security-id-sr[]" value="<?php echo intval($specific_report['security_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['time']; ?>" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['time']; ?>" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['description']; ?>" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['description']; ?>" /></td>
						<td><br/>Status<br/><textarea id="status-<?php echo $specific_report['issue_type']; ?>" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"><?php echo $specific_report['status']; ?></textarea></td>
						<td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue" data-id="<?php echo $specific_report['issue_type'].$specific_report['issue_id']; ?>"></i></td>
					</tr>
				<?php } else { 
					if($specific_report['issue_type_id'] == 4)
					{
						$specific_report['time'] =  $specific_report['area'];
						/*$specific_report['status'] = $specific_report['follow_up'];*/
						$specific_report['issue_type_name'] = "Defect List";
					}
				?>
					<tr>
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type']; ?>"><input type="hidden" id="securityid" name="security-id-sr[]" value="<?php echo intval($specific_report['security_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/><?php if($specific_report['issue_type'] == 4) echo "Area"; else echo "Time"; ?> : <input type="text" id="time-<?php echo $specific_report['issue_type']; ?>" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['time']; ?>" /><br/>Detail : <input type="text" id="description-<?php echo $specific_report['issue_type']; ?>" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['detail']; ?>" /></td>
						<td><br/>Status<br/><textarea id="status-<?php echo $specific_report['issue_type']; ?>" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"><?php echo $specific_report['status']; ?></textarea></td>
						<td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td>
					</tr>
			<?php } } } ?>
			</table>
			
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:200px;">Simpan</button>
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

<!-- Specific Report form -->
  <form action="" id="specific-report-form" class="mfp-hide white-popup-block" >
	<div class="item form-group">
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="issue_type">Issue Type <span class="required">*</span>
		</label> 
		<div class="col-md-6 col-sm-6 col-xs-12">
			<select id="issue_type" name="issue_type" class="form-control" required>
				<option disabled selected value style="display:none"> -- select an option -- </option>
				<?php if(!empty($this->issue_type)) { 
					foreach($this->issue_type as $type) {
				?>
				<option value="<?php echo $type['issue_type_id']; ?>"><?php echo $type['issue_type']; ?></option>
				<?php } } ?>
			</select>
		</div>
	</div>
	<div id="list-issue"  class="col-md-6 col-sm-6 col-xs-12"></div>	  
	<div class="add-btn"><input type="submit" id="add-issue-submit" name="add-issue-submit" value="Add"></div>
  </form>
<!-- End of Specific Report form --> 

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
 
<script type="text/javascript">
$(document).ready(function() {	
	$(".add-training").click(function() {
		var type_id = this.dataset.typeid;
		var row;
		var table_name;

		row = '<tr><td class="id-hidden"><input type="hidden" name="training_type[]" value="'+type_id+'"></td><td><select name="training_activity[]" class="form-control" required>';
		
		<?php if(!empty($this->training_activity)) { 
			foreach($this->training_activity as $training_activity) {
		?>
		row = row + '<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $this->security['morning']['outsource_training_activity']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>';
		<?php } } ?>
		
		row = row + '</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;"><?php echo $this->security['morning']['description_training']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>';
		
		if(type_id == "1") table_name = "outsource";
		else if(type_id == "2") table_name = "inhouse";
		
		$( "#"+table_name+"-training-table").append(row);
	});
	
	$('#training-form').on('submit', function(event){
		event.preventDefault(); 		
		var data = $( this ).serializeArray();

		data = '<tr><td class="id-hidden"><input type="hidden" id="training-type" name="training-type[]" value="0"></td><td><strong>'+issue_type+'</strong><br/>'+timeField+' : <input type="text" id="time-'+ issue_type+'" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+timeValue+'" /><br/>Detail : <input type="text" id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+detailValue+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+statusValue+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
		$( "#specific-report-table").append(data);
		$(".remove-issue").click(function() {
			$(this).closest('tr').remove();
		});
		
		$.magnificPopup.close();
	});
	
	$('#add-specific-report').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				$("#issue_type").change(function() {
					if($( "#issue_type" ).val() > 0 && $( "#issue_type" ).val() < 4)
					{
						$.ajax({
							url: "/default/issue/getissuebytype",
							data: { id : $( "#issue_type" ).val(), show_shift:'1', cat_id:1, report_date:'<?php echo $cur_date; ?>' }
						}).done(function(response) {
							$( "#list-issue" ).html(response);
						});
					}
					else if($( "#issue_type" ).val() == 4)
					{
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_malam">Shift <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><select id="shift_id" name="shift_id" class="form-control" required>';
						<?php if(!empty($this->shift)) { 
							foreach($this->shift as $shift) { ?>
								content = content + '<option value="<?php echo $shift['shift_id']; ?>"><?php echo $shift['shift_name']; ?></option>';
						<?php } 
						} ?>
						content = content+'</select></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Area <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Follow Up <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
						$( "#list-issue" ).html(content);
						
					}
					else if($( "#issue_type" ).val() > 4) {
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Time <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Status <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
						$( "#list-issue" ).html(content);
					}
				});
			},
			close: function() {	
				$('#specific-report-form')[0].reset();
				$( "#list-issue" ).html("");
			}
		}
	});
	
	$('#specific-report-form').on('submit', function(event){
		event.preventDefault(); 
		var data;
		var issue_type;
		
		var data = $( this ).serializeArray();
		if(data[0].value > 0 && data[0].value < 4 )
		{
			var shift_id = 1;
			$.each( $( this ).serializeArray(), function( i, field ) {
				if(field.name == "shift_id") shift_id = field.value;
				if(field.name == 'chk_issue_id')
				{
					$.ajax({
						url: "/default/issue/getissuebyid",
						data: { id : field.value, shift_id : shift_id, report_date: '<?php echo $this->security['report_date']; ?>' }
					}).done(function(response) {
						var issue = $.parseJSON(response);
						var issuedate = issue.issue_date;
						var issuetime = issuedate.substring(11);
						data = '<tr id="sr-'+issue.issue_id+'"><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="'+issue.issue_id+'"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="securityid" name="security-id-sr[]" value="'+issue.security_id+'"></td><td><strong>'+issue.issue_type+'</strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issuetime+'" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issuetime+'" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issue.description+'" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.description+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue.issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue" data-id="sr-'+issue.issue_id+'"></i></td></tr>';					
						$( "#specific-report-table").append(data);
						$(".remove-issue").click(function() {
							$(this).closest('tr').remove();
						});
					});
				}
			});
		}
		else if(data[0].value > '3')
		{
			var timeField = "Time";
			var security_id = "";
			if(data[0].value == '4') {
				issue_type = 'Defect List';
				timeField = "Area";
				if(data[1].value == '1') security_id = '<?php echo $this->security['morning']['security_id']; ?>';
				else if(data[1].value == '2') security_id = '<?php echo $this->security['afternoon']['security_id']; ?>';
				else if(data[1].value == '3') security_id = '<?php echo $this->security['night']['security_id']; ?>';
				var timeValue = data[2].value;
				var detailValue = data[3].value;
				var statusValue = data[4].value;
			}
			else
			{
				if(data[0].value == '5') issue_type = 'Safety';
				if(data[0].value == '6') issue_type = 'Traffic Report';
				var timeValue = data[1].value;
				var detailValue = data[2].value;
				var statusValue = data[3].value;
			}
			data = '<tr><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="securityid" name="security-id-sr[]" value="'+security_id+'"></td><td><strong>'+issue_type+'</strong><br/>'+timeField+' : <input type="text" id="time-'+ issue_type+'" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+timeValue+'" /><br/>Detail : <input type="text" id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+detailValue+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+statusValue+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
			$( "#specific-report-table").append(data);
			$(".remove-issue").click(function() {
				$(this).closest('tr').remove();
			});
		}
		$.magnificPopup.close();
	});
	
	$(".remove-issue").click(function() {
		$(this).closest('tr').remove();
	});
	
	/*$('#chief_spd').on('change', function(event){
		$('#kekuatan_spd')[0].value = +$('#chief_spd')[0].value + +$('#panwas_spd')[0].value + +$('#danton_pagi_spd')[0].value;
	});
	$('#chief_army').on('change', function(event){
		$('#kekuatan_army')[0].value = +$('#chief_army')[0].value + +$('#panwas_army')[0].value + +$('#danton_pagi_army')[0].value;
	});
	$('#panwas_spd').on('change', function(event){
		$('#kekuatan_spd')[0].value = +$('#chief_spd')[0].value + +$('#panwas_spd')[0].value + +$('#danton_pagi_spd')[0].value;
	});
	$('#panwas_army').on('change', function(event){
		$('#kekuatan_army')[0].value = +$('#chief_army')[0].value + +$('#panwas_army')[0].value + +$('#danton_pagi_army')[0].value;
	});
	$('#danton_pagi_spd').on('change', function(event){
		$('#kekuatan_spd')[0].value = +$('#chief_spd')[0].value + +$('#panwas_spd')[0].value + +$('#danton_pagi_spd')[0].value;
	});
	$('#danton_pagi_army').on('change', function(event){
		$('#kekuatan_army')[0].value = +$('#chief_army')[0].value + +$('#panwas_army')[0].value + +$('#danton_pagi_army')[0].value;
	});*/
	
});	
</script>