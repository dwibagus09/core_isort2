<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

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

			<form class="form-horizontal form-label-left" novalidate action="/default/security/savechiefreport" method="POST">
				<input type="hidden" id="chief_security_report_id" name="chief_security_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_security_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->security['created_date'])) echo $this->security['created_date']; else echo date("l, F j, Y"); ?>
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
					  <input id="supervisor_inhouse_malam" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_malam" required="required" type="text" value="<?php echo $this->security['supervisor']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="supervisor_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['supervisor']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="supervisor_inhouse_siang" class="form-control col-md-7 col-xs-12" name="supervisor_inhouse_siang" required="required" type="text" value="<?php echo $this->security['supervisor']; ?>">
					</div>
				  </div>
				  
				  <h5>Staff Posko</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff-posko-inhouse-malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_malam" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_malam" required="required" type="text" value="<?php echo $this->security['staff_posko']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_posko_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['staff_posko_inhouse_pagi']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_posko_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_posko_inhouse_siang" class="form-control col-md-7 col-xs-12" name="staff_posko_inhouse_siang" required="required" type="text" value="<?php echo $this->security['staff_posko']; ?>">
					</div>
				  </div>
				  
				  <h5>Staff CCTV</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_malam" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_malam" required="required" type="text" value="<?php echo $this->security['staff_posko']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['staff_cctv_inhouse_pagi']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="staff_cctv_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="staff_cctv_inhouse_siang" class="form-control col-md-7 col-xs-12" name="staff_cctv_inhouse_siang" required="required" type="text" value="<?php echo $this->security['staff_posko']; ?>">
					</div>
				  </div>
				  
				  <h5>Safety</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_malam">Malam <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_malam" class="form-control col-md-7 col-xs-12" name="safety_inhouse_malam" required="required" type="text" value="<?php echo $this->security['safety']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_pagi">Pagi <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_pagi" class="form-control col-md-7 col-xs-12" name="safety_inhouse_pagi" required="required" type="text" value="<?php echo $this->security['safety_inhouse_pagi']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_siang">Siang <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="safety_inhouse_siang" class="form-control col-md-7 col-xs-12" name="safety_inhouse_siang" required="required" type="text" value="<?php echo $this->security['safety']; ?>">
					</div>
				  </div>
			</fieldset>
			<fieldset>
				<legend>Vendor</legend>
					<h5>CHIEF/WAKA</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chief_spd">SPD <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="chief_spd" class="form-control col-md-7 col-xs-12" name="chief_spd" required="required" type="text" value="<?php echo $this->security['chief_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chief_army">Army <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="chief_army" name="chief_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['chief_army']; ?>">
					</div>
				  </div>
				  
				  <h5>PANWAS</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="panwas_spd">SPD <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="panwas_spd" class="form-control col-md-7 col-xs-12" name="panwas_spd" required="required" type="text" value="<?php echo $this->security['panwas_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="panwas_army">Army <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="panwas_army" name="panwas_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['panwas_army']; ?>">
					</div>
				  </div>
				  
				  <h5>DANTON PAGI</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="danton_pagi_spd">SPD <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="danton_pagi_spd" class="form-control col-md-7 col-xs-12" name="danton_pagi_spd" required="required" type="text" value="<?php echo $this->security['danton_pagi_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="danton_pagi_army">Army <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="danton_pagi_army" name="danton_pagi_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['danton_pagi_army']; ?>">
					</div>
				  </div>
				  
				  <h5>KEKUATAN</h5>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kekuatan_spd">SPD <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input id="kekuatan_spd" class="form-control col-md-7 col-xs-12" name="kekuatan_spd" required="required" type="text" value="<?php echo $this->security['kekuatan_spd']; ?>">
					</div>
				  </div>
				  <div class="item form-group">
					<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kekuatan_army">Army <span class="required">*</span>
					</label>
					<div class="col-md-6 col-sm-6 col-xs-12">
					  <input type="text" id="kekuatan_army" name="kekuatan_army" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->security['kekuatan_army']; ?>">
					</div>
				  </div>
			</fieldset>
			
			<span class="section">PERLENGKAPAN &nbsp;<a id="add-perlengkapan"><i class="fa fa-plus-square"></i></a></span>
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
						foreach($this->equipments as $equipment) {
				?>
				<tr>
					<td class="id-hidden"><input type="hidden" name="id_equipment_list[]" value="<?php echo $equipment['security_equipment_list_id']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="equipment_name[]" type="text" value="<?php echo $equipment['equipment_name']; ?>" disabled></td>
					<td><select name="vendor_equipment[]" class="form-control" required>
						<option value="SPD">SPD</option>
						<option value="Army">Army</option>
					</select></td>
					<td><input class="form-control col-md-7 col-xs-12" name="total_equipment[]" type="text"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="ok_condition[]" type="text"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="bad_condition[]" type="text"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="description[]" type="text"></td>
				</tr>
				<?php } 
				} ?>
			  </tbody>
			</table>
			  
			  <span class="section">Briefing &amp; Training</span>
			  <table id="briefing-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Briefing</th>
				  <th width="400" colspan="2">Training - Activity</th>
				</tr>
				<tr>
				  <th width="200">Outsource</th>
				  <th width="200">In House</th>
				</tr>
			  </thead>
			  <tbody>
				<tr id="briefing">
				  <td>
					<textarea id="briefing1" name="briefing1" class="form-control col-md-7 col-xs-12" style="height:50px;"><?php echo $issue_date_time[1]; ?></textarea> 
					<textarea id="briefing2" name="briefing2" class="form-control col-md-7 col-xs-12" style="height:50px;"><?php echo $issue_date_time[1]; ?></textarea> 
					<textarea id="briefing3" name="briefing3" class="form-control col-md-7 col-xs-12" style="height:50px;"><?php echo $issue_date_time[1]; ?></textarea></td>
				  <td>
					<select id="outsource_training_activity" name="outsource_training_activity" class="form-control" required>
						<?php if(!empty($this->training_activity)) { 
								foreach($this->training_activity as $training_activity) {
						?>
							<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $security['outsource_training_activity']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
						<?php } } ?>
					  </select>
					DESKRIPSI TRAINING<textarea id="description_outsource_training" name="description_outsource_training" class="form-control col-md-7 col-xs-12" style="height:150px!important;"><?php echo $incident['description']; ?></textarea>
				</td>
				<td>
					<select id="inhouse_training_activity" name="inhouse_training_activity" class="form-control" required>
						<?php if(!empty($this->training_activity)) { 
								foreach($this->training_activity as $training_activity) {
						?>
							<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $security['inhouse_training_activity']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
						<?php } } ?>
					  </select>
				      DESKRIPSI TRAINING <textarea id="description_inhouse_training" name="description_inhouse_training" class="form-control col-md-7 col-xs-12" style="height:150px!important;"><?php echo $incident['status']; ?></textarea>
				</td>
				</tr>
				<tr>
					<td colspan="3">
						SOSIALISASI SOP
						<input id="sosialisasi_sop_a" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_a" required="required" type="text" value="<?php echo $this->security['chief_army']; ?>">
						<input id="sosialisasi_sop_b" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_b" required="required" type="text" value="<?php echo $this->security['chief_army']; ?>">
						<input id="sosialisasi_sop_c" class="form-control col-md-7 col-xs-12" name="sosialisasi_sop_c" required="required" type="text" value="<?php echo $this->security['chief_army']; ?>">
					</td>
				</tr>
			  </tbody>
			</table>
			  
			<span class="section">SPECIFIC REPORT &nbsp;<a id="add-specific-report" href="#specific-report-form"><i class="fa fa-plus-square"></i></a></span>
			<table id="specific-report-table" class="table">
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="safety_inhouse_malam">Issue Type <span class="required">*</span>
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
	$('#add-specific-report').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
				$( "#list-issue" ).html("");
				$('#specific-report-form')[0].reset();
				$('#issue_type').change(function () {
					var optionSelected = $(this).find("option:selected");
					valueSelected  = optionSelected[0].value;
					if(valueSelected>0 && valueSelected < 4)
					{	
						$.ajax({
							url: "/default/issue/getissuebytype",
							data: { id : valueSelected }
						}).done(function(response) {
							$( "#list-issue" ).html(response);
						});	
					}
					else
					{
						var resp = '<div class="item form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Time <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" id="time" name="time" required="required" class="form-control col-md-7 col-xs-12"></div></div><div class="item form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12" for="detail">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" id="detail" name="detail" required="required" class="form-control col-md-7 col-xs-12"></div></div><div class="item form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status </label><div class="col-md-6 col-sm-6 col-xs-12"><textarea id="status" name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div></div>';
						$( "#list-issue" ).html(resp);
					}
				});
			},
			close: function() {	
				
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
			$.each( $( this ).serializeArray(), function( i, field ) {
				if(field.name == 'chk_issue_id')
				{
					$.ajax({
						url: "/default/issue/getissuebyid",
						data: { id : field.value }
					}).done(function(response) {
						var issue = $.parseJSON(response);
						var issuedate = issue.issue_date;
						var issuetime = issuedate.substring(11);
						data = '<tr id="'+issue.issue_type+issue.issue_id+'"><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="'+issue.issue_id+'"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"></td><td><strong>'+issue.issue_type+'</strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issuetime+'" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issuetime+'" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issue.description+'" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.description+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue.issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue" data-id="'+issue.issue_type+issue.issue_id+'"></i></td></tr>';					
						$( "#specific-report-table").append(data);
						$(".remove-issue").click(function() {
							$("#"+this.dataset.id).remove();
						});
					});
				}
			});
		}
		else if(data[0].value > '3')
		{
			if(data[0].value == '4') issue_type = 'Defect List';
			if(data[0].value == '5') issue_type = 'Safety';
			if(data[0].value == '6') issue_type = 'Traffic Report';
			data = '<tr><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"></td><td><strong>'+issue_type+'</strong><br/>Time : <input type="text" id="time-'+ issue_type+'" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+data[1].value+'" /><br/>Detail : <input type="text" id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+data[2].value+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+data[3].value+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
			$( "#specific-report-table").append(data);
			$(".remove-issue").click(function() {
				$(this).closest('tr').remove();
			});
		}
		$.magnificPopup.close();
	});
});	
</script>