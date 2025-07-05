<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<?php 
if(empty($this->safety['created_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->safety['created_date']);
	$cur_date = $cur_report_date[0];
}
?>

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

			<form class="form-horizontal form-label-left" action="/default/safety/savereport" method="POST" onsubmit="$('body').mLoading();" enctype="multipart/form-data">
				<input type="hidden" id="safety_report_id" name="safety_report_id" required="required" class="form-control col-md-7 col-xs-12" value="<?php echo $this->safety['report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->safety['report_date'])) echo $this->safety['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Date</label>
				<div class="col-md-6 col-sm-6 col-xs-6" style="padding-top:4px; text-align:center;">
					<?php if(!empty($this->safety['yesterday_date'])) echo $this->safety['yesterday_date']; else echo date("d", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3" style="padding-top:4px; text-align:center;">
					<?php if(!empty($this->safety['today_date'])) echo $this->safety['today_date']; else echo date("d"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-3 col-sm-3 col-xs-3" style="padding-top:4px; text-align:center;">
					<?php echo $this->setting['safety_afternoon_reporting_time']; ?>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3" style="padding-top:4px; text-align:center;">
					<?php echo $this->setting['safety_night_reporting_time']; ?>
				</div>
				<div class="col-md-3 col-sm-3 col-xs-3" style="padding-top:4px; text-align:center;">
					<?php echo $this->setting['safety_morning_reporting_time']; ?>
				</div>
			  </div>
			  
			  <span class="section">MAN POWER</span>
			  <div class="item form-group">
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px;  text-align:center; width:30%;">
					<?php echo $this->setting['safety_afternoon_reporting_time']; ?>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px; text-align:center; width:30%;">
					<?php echo $this->setting['safety_night_reporting_time']; ?>
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px; text-align:center; width:30%;">
					<?php echo $this->setting['safety_morning_reporting_time']; ?>	
				</div>
			  </div>
			  <div class="item form-group">
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px; width:30%;">
					<input id="man_power_afternoon" class="form-control col-md-7 col-xs-12" name="man_power_afternoon" required="required" type="text" value="<?php echo $this->safety['man_power_afternoon']; ?>">
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px; width:30%;">
					<input id="man_power_night" class="form-control col-md-7 col-xs-12" name="man_power_night" required="required" type="text" value="<?php echo $this->safety['man_power_night']; ?>">	
				</div>
				<div class="col-md-4 col-sm-4 col-xs-4" style="padding-top:4px; width:30%;">
					<input id="man_power_morning" class="form-control col-md-7 col-xs-12" name="man_power_morning" required="required" type="text" value="<?php echo $this->safety['man_power_morning']; ?>">	
				</div>
			  </div>
			
			<span class="section">PERLENGKAPAN </span>
			  <table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th class="id-hidden"></th>
				  <th>No</th>
				  <th>Equipment Name</th>
				  <th>Item</th>
				  <th>Status Normal</th>
				  <th>Shift 3<br/>23:00</th>
				  <th>Shift 1<br/>07:00</th>
				</tr>
			  </thead>
			  <tbody>
				<?php $i = 0; 
					if(!empty($this->equipments_ab)) {
						foreach($this->equipments_ab as $equipment) {
				?>
				<tr>
					<td class="id-hidden">
						<input type="hidden" name="equipment_item_id[<?php echo $i; ?>]" value="<?php echo $equipment['equipment_item_id']; ?>">
						<input name="status_cut_in[<?php echo $i; ?>]" type="hidden" value="">
						<input name="status_cut_off[<?php echo $i; ?>]" type="hidden" value="">
					</td>
					<td><?php echo $equipment['no']; ?></td>
					<td><?php echo $equipment['equipment_name']; ?></td>
					<td><?php echo $equipment['item_name']; ?></td>
					<td><?php echo $equipment['status']; ?></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift2[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['shift2']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift3[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['shift3']; ?>" required></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			
			
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th rowspan="2" class="id-hidden"></th>
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
				<?php if(!empty($this->equipments_c1)) {
						foreach($this->equipments_c1 as $equipmentc1) {
				?>
				<tr>
					<td class="id-hidden">
						<input type="hidden" name="equipment_item_id[<?php echo $i; ?>]" value="<?php echo $equipmentc1['equipment_item_id']; ?>">
					</td>
					<td><?php echo $equipmentc1['no']; ?></td>
					<td><?php echo $equipmentc1['equipment_name']; ?></td>
					<td><?php echo $equipmentc1['item_name']; ?></td>
					<td><?php if(!empty($equipmentc1['status_cut_in'])) echo $equipmentc1['status_cut_in'].'<input name="status_cut_in['.$i.']" type="hidden" value="'.$equipmentc1['status_pressure_cut_in'].'">'; else echo '<input class="form-control col-md-7 col-xs-12" name="status_cut_in['.$i.']" type="text" value="'.$equipmentc1['status_pressure_cut_in'].'" required>';  ?></td>
					<td><?php if(!empty($equipmentc1['status_cut_off'])) echo $equipmentc1['status_cut_off'].'<input name="status_cut_off['.$i.']" type="hidden" value="'.$equipmentc1['status_pressure_cut_off'].'">'; else echo '<input class="form-control col-md-7 col-xs-12" name="status_cut_off['.$i.']" type="text" value="'.$equipmentc1['status_pressure_cut_off'].'" required>';  ?></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift2[<?php echo $i; ?>]" type="text" value="<?php echo $equipmentc1['shift2']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift3[<?php echo $i; ?>]" type="text" value="<?php echo $equipmentc1['shift3']; ?>" required></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			</div>
			
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th class="id-hidden"></th>
				  <th>No</th>
				  <th>Tank Condition</th>
				  <th>Status Normal</th>
				  <th>Shift 3<br/>23:00</th>
				  <th>Shift 1<br/>07:00</th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->equipments_c2)) {
						foreach($this->equipments_c2 as $equipmentc2) {
				?>
				<tr>
					<td class="id-hidden">
						<input type="hidden" name="equipment_item_id[<?php echo $i; ?>]" value="<?php echo $equipmentc2['equipment_item_id']; ?>">
						<input name="status_cut_in[<?php echo $i; ?>]" type="hidden" value="">
						<input name="status_cut_off[<?php echo $i; ?>]" type="hidden" value="">
					</td>
					<td><?php echo $equipmentc2['no']; ?></td>
					<td><?php echo $equipmentc2['item_name']; ?></td>
					<td><?php echo $equipmentc2['status']; ?></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift2[<?php echo $i; ?>]" type="text" value="<?php echo $equipmentc2['shift2']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="shift3[<?php echo $i; ?>]" type="text" value="<?php echo $equipmentc2['shift3']; ?>" required></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			
			<span class="section">SAFETY TALK / INDUCTION</span>	
			  <h4>BRIEFING</h4>				
			  <table id="briefing-table" class="table">
				<tr id="morning-briefing">
				  <td style="border-top:none;">
					<div class="col-md-4 col-xs-12"><textarea id="briefing1" name="briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->safety['briefing1']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing2" name="briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->safety['briefing2']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing3" name="briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->safety['briefing3']); ?></textarea></div>
				  </td>
				</tr>
			</table>
			  
			 <h4>TRAINING ACTIVITY</h4>		
			  <div class="col-md-6 col-xs-12">
				<h4>Outsource <a class="add-training" data-typeid="1" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></h4>
				<table id="outsource-training-table" class="table">
					<?php if(!empty($this->outdoorTraining)) {
						foreach($this->outdoorTraining as $outdoorTraining) { ?>
						<tr>
							<td class="id-hidden"><input type="hidden" name="training_id[]" value="<?php echo $outdoorTraining['safety_training_id']; ?>"><input type="hidden" name="training_type[]" value="<?php echo $outdoorTraining['training_type']; ?>"><input type="hidden" name="dokumen_training2[]" value="<?php echo $outdoorTraining['document']; ?>"></td>
							<td><select name="training_activity[]" class="form-control" required>
								<?php if(!empty($this->training_activity)) { 
									foreach($this->training_activity as $training_activity) {
								?>
								<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $outdoorTraining['training_activity_id']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
								<?php } } ?>		
								</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;" required><?php echo $outdoorTraining['description']; ?></textarea><br/>
								PESERTA TRAINING<textarea name="participant_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required><?php echo $outdoorTraining['participant']; ?></textarea><br/>
								KETERANGAN<textarea name="remark_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required><?php echo $outdoorTraining['remark']; ?></textarea><br/>
								DOKUMEN<br/><?php if(!empty($outdoorTraining['document'])) { ?><a href="/safety_training/<?php echo $this->y."/".$outdoorTraining['document']; ?>" target="_blank" style="margin-right:10px;"><i class="fa fa-paperclip"></i> <?php echo $outdoorTraining['document']; ?></a><?php } ?><input type="file" name="dokumen_training[]" style="display:inline;">
							</td>
							<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
						</tr>
					<?php } } ?>
				</table>
			  </div>
			  <div class="col-md-6 col-xs-12">
				<h4>In House <a class="add-training" data-typeid="2" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></h4>
				<table id="inhouse-training-table" class="table">
					<?php if(!empty($this->inHouseTraining)) {
						foreach($this->inHouseTraining as $inHouseTraining) { ?>
						<tr>
							<td class="id-hidden"><input type="hidden" name="training_id[]" value="<?php echo $inHouseTraining['safety_training_id']; ?>"><input type="hidden" name="training_type[]" value="<?php echo $inHouseTraining['training_type']; ?>"><input type="hidden" name="dokumen_training2[]" value="<?php echo $inHouseTraining['document']; ?>"></td>
							<td>
								<select name="training_activity[]" class="form-control" required>
									<?php if(!empty($this->training_activity)) { 
										foreach($this->training_activity as $training_activity) {
									?>
									<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $inHouseTraining['training_activity_id']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>
									<?php } } ?>		
								</select>
								DESKRIPSI TRAINING <textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;" required><?php echo $inHouseTraining['description']; ?></textarea><br/>
								PESERTA TRAINING <textarea name="participant_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required><?php echo $inHouseTraining['participant']; ?></textarea><br/>
								KETERANGAN <textarea name="remark_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required><?php echo $inHouseTraining['remark']; ?></textarea><br/>
								DOKUMEN<br/><?php if(!empty($inHouseTraining['document'])) { ?><a href="/safety_training/<?php echo $this->y."/".$inHouseTraining['document']; ?>" target="_blank" style="margin-right:10px;"><i class="fa fa-paperclip"></i> <?php echo $inHouseTraining['document']; ?></a><?php } ?><input type="file" name="dokumen_training[]" style="display:inline;">
							</td>
							<td align="center" style="vertical-align:middle;">
								<i class="fa fa-trash remove-issue" onclick="$(this).closest('tr').remove();"></i>
							</td>
						</tr>
					<?php } } ?>
				</table>
			  </div>
			  
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
			<input id="sop1" class="form-control col-md-7 col-xs-12" name="sop1" required="required" type="text" value="<?php echo $this->safety['sop1']; ?>" style="margin-bottom:5px;">
			<input id="sop2" class="form-control col-md-7 col-xs-12" name="sop2" required="required" type="text" value="<?php echo $this->safety['sop2']; ?>" style="margin-bottom:5px;">
			<input id="sop3" class="form-control col-md-7 col-xs-12" name="sop3" required="required" type="text" value="<?php echo $this->safety['sop3']; ?>" style="margin-bottom:5px;">  
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
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="<?php echo $specific_report['issue_id']; ?>"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type_id']; ?>"><input type="hidden" id="safetyid" name="safety-id-sr[]" value="<?php echo intval($specific_report['safety_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['time']; ?>" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['time']; ?>" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['description']; ?>" required /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['description']; ?>" required /></td>
						<td><br/>Status<br/><textarea id="status-<?php echo $specific_report['issue_type']; ?>" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" required><?php echo $specific_report['status']; ?></textarea></td>
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
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type']; ?>"><input type="hidden" id="safetyid" name="safety-id-sr[]" value="<?php echo intval($specific_report['safety_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/>Detail : 
						<textarea id="description-<?php echo $specific_report['issue_type']; ?>" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" required><?php echo $specific_report['detail']; ?></textarea></td>
						<td><br/>Status<br/><textarea id="status-<?php echo $specific_report['issue_type']; ?>" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" required><?php echo $specific_report['status']; ?></textarea></td>
						<td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td>
					</tr>
			<?php } } } ?>
			</table>
			
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
	<?php if($this->editMode == 1) { ?>
		$(".edit-safety").css("display", "block");
		$(".edit-safety").addClass('current-page');
		$(".edit-safety").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	<?php } ?>

	$(".add-training").click(function() {
		var type_id = this.dataset.typeid;
		var row;
		var table_name;

		row = '<tr><td class="id-hidden"><input type="hidden" name="training_type[]" value="'+type_id+'"></td><td><select name="training_activity[]" class="form-control" required>';
		
		<?php if(!empty($this->training_activity)) { 
			foreach($this->training_activity as $training_activity) {
		?>
		row = row + '<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $this->safety['morning']['outsource_training_activity']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>';
		<?php } } ?>
		
		row = row + '</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;" required></textarea><br/>PESERTA<textarea name="participant_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required></textarea><br/>KETERANGAN<textarea name="remark_training[]" class="form-control col-md-7 col-xs-12" style="height:50px!important;" required></textarea><br/>DOKUMEN <input type="file" name="dokumen_training[]"></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>';
		
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
							data: { id : $( "#issue_type" ).val(), show_shift:0, cat_id:3, report_date:'<?php echo $cur_date; ?>' }
						}).done(function(response) {
							$( "#list-issue" ).html(response);
						});
					}
					else if($( "#issue_type" ).val() == 4)
					{
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift_id">Shift <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><select id="shift_id" name="shift_id" class="form-control" required>';
						<?php if(!empty($this->shift)) { 
							foreach($this->shift as $shift) { ?>
								content = content + '<option value="<?php echo $shift['shift_id']; ?>"><?php echo $shift['shift_name']; ?></option>';
						<?php } 
						} ?>
						content = content+'</select></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Area <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Follow Up <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
						$( "#list-issue" ).html(content);
						
					}
					else if($( "#issue_type" ).val() > 4 && $( "#issue_type" ).val() < 7) {
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Time <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Status <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
						$( "#list-issue" ).html(content);
					}
					else if($( "#issue_type" ).val() > 6) {
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Status <span class="required">*</span></label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
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
						data: { id : field.value, shift_id : shift_id, report_date: '<?php echo $this->safety['report_date']; ?>' }
					}).done(function(response) {
						var issue = $.parseJSON(response);
						var issuedate = issue.issue_date;
						var issuetime = issuedate.substring(11);
						data = '<tr id="sr-'+issue.issue_id+'"><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="'+issue.issue_id+'"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="safetyid" name="safety-id-sr[]" value="'+issue.safety_id+'"></td><td><strong>'+issue.issue_type+'</strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issuetime+'" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issuetime+'" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issue.description+'" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.description+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue.issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue" data-id="sr-'+issue.issue_id+'"></i></td></tr>';					
						$( "#specific-report-table").append(data);
						$(".remove-issue").click(function() {
							$(this).closest('tr').remove();
						});
					});
				}
			});
		}
		else if(data[0].value > 3)
		{
			var timeField = "Time";
			var safety_id = "";
			if(data[0].value == 4) {
				issue_type = 'Defect List';
				timeField = "Area";
				if(data[1].value == '1') safety_id = '<?php echo $this->safety['morning']['safety_id']; ?>';
				else if(data[1].value == '2') safety_id = '<?php echo $this->safety['afternoon']['safety_id']; ?>';
				else if(data[1].value == '3') safety_id = '<?php echo $this->safety['night']['safety_id']; ?>';
				var timeValue = data[2].value;
				var detailValue = data[3].value;
				var statusValue = data[4].value;
				data = '<tr><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="safetyid" name="safety-id-sr[]" value="'+safety_id+'"></td><td><strong>'+issue_type+'</strong><br/>Detail : <input type="text" id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+detailValue+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+statusValue+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
				$( "#specific-report-table").append(data);
				$(".remove-issue").click(function() {
					$(this).closest('tr').remove();
				});
			}
			else
			{
				$.ajax({
					url: "/default/issue/getissuetypebyid",
					data: { id : data[0].value }
				}).done(function(response) {
					var resp = $.parseJSON(response);
					issue_type = resp.issue_type;
					var detailValue = data[1].value;
					var statusValue = data[2].value;
					data = '<tr><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="safetyid" name="safety-id-sr[]" value="'+safety_id+'"></td><td><strong>'+issue_type+'</strong><br/>Detail : <textarea id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+detailValue+'</textarea></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+statusValue+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
					$( "#specific-report-table").append(data);
					$(".remove-issue").click(function() {
						$(this).closest('tr').remove();
					});
				});
			}
			
		}
		$.magnificPopup.close();
	});
	
	$(".remove-issue").click(function() {
		$(this).closest('tr').remove();
	});
	
	$('#chief_spd').on('change', function(event){
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
	});
	
});	
</script>