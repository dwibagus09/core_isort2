<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div class="right_col" role="main">
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

			<form class="form-horizontal form-label-left" action="/default/housekeeping/savereport" method="POST" enctype="multipart/form-data">
				<input type="hidden" id="housekeeping_report_id" name="housekeeping_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['housekeeping_report_id']; ?>">
				<input type="hidden" id="report_date" name="report_date" class="form-control col-md-7 col-xs-12" value="<?php if(!empty($this->housekeeping['report_date'])) echo $this->housekeeping['report_date']; else echo date("l, F j, Y"); ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->housekeeping['report_date'])) echo $this->housekeeping['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['housekeeping_reporting_time']; ?>
				</div>
			  </div>
			  
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
								<td><input type="text" id="inhouse_chief_housekeeping_shift1" name="inhouse_chief_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_chief_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" id="inhouse_chief_housekeeping_shift2" name="inhouse_chief_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_chief_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" id="inhouse_chief_housekeeping_shift3" name="inhouse_chief_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_chief_housekeeping_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Supervisor</td>
								<td><input type="text" id="inhouse_supervisor_shift1" name="inhouse_supervisor_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_supervisor_shift1']; ?>" required></td>
								<td><input type="text" id="inhouse_supervisor_shift2" name="inhouse_supervisor_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_supervisor_shift2']; ?>" required></td>
								<td><input type="text" id="inhouse_supervisor_shift3" name="inhouse_supervisor_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_supervisor_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Staff</td>
								<td><input type="text" id="inhouse_staff_shift1" name="inhouse_staff_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_staff_shift1']; ?>" required></td>
								<td><input type="text" id="inhouse_staff_shift2" name="inhouse_staff_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_staff_shift2']; ?>" required></td>
								<td><input type="text" id="inhouse_staff_shift3" name="inhouse_staff_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_staff_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Administrasi</td>
								<td><input type="text" id="inhouse_admin_shift1" name="inhouse_admin_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_admin_shift1']; ?>" required></td>
								<td><input type="text" id="inhouse_admin_shift2" name="inhouse_admin_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_admin_shift2']; ?>" required></td>
								<td><input type="text" id="inhouse_administrasi_shift3" name="inhouse_admin_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['inhouse_admin_shift3']; ?>" required></td>
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
								<td><input type="text" id="outsource_chief_housekeeping_shift1" name="outsource_chief_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_chief_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_chief_housekeeping_shift2" name="outsource_chief_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_chief_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_chief_housekeeping_shift3" name="outsource_chief_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_chief_housekeeping_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Supervisor</td>
								<td><input type="text" id="outsource_supervisor_shift1" name="outsource_supervisor_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_supervisor_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_supervisor_shift2" name="outsource_supervisor_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_supervisor_shift2']; ?>" required></td>
								<td><input type="text" id="inhouse_supervisor_shift3" name="outsource_supervisor_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_supervisor_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Leader</td>
								<td><input type="text" id="outsource_leader_shift1" name="outsource_leader_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_leader_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_leader_shift2" name="outsource_leader_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_leader_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_leader_shift3" name="outsource_leader_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_leader_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Crew</td>
								<td><input type="text" id="outsource_crew_shift1" name="outsource_crew_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_crew_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_crew_shift2" name="outsource_crew_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_crew_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_crew_shift3" name="outsource_crew_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_crew_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Toilet Crew</td>
								<td><input type="text" id="outsource_toilet_crew_shift1" name="outsource_toilet_crew_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_toilet_crew_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_toilet_crew_shift2" name="outsource_toilet_crew_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_toilet_crew_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_toilet_crew_shift3" name="outsource_toilet_crew_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_toilet_crew_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Gondola</td>
								<td><input type="text" id="outsource_gondola_shift1" name="outsource_gondola_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_gondola_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_gondola_shift2" name="outsource_gondola_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_gondola_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_gondola_shift3" name="outsource_gondola_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_gondola_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Admin</td>
								<td><input type="text" id="outsource_admin_shift1" name="outsource_admin_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_admin_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_admin_shift2" name="outsource_admin_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_admin_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_admin_shift3" name="outsource_admin_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_admin_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Total</td>
								<td><input type="text" id="outsource_total_shift1" name="outsource_total_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_total_shift1']; ?>" required></td>
								<td><input type="text" id="outsource_total_shift2" name="outsource_total_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_total_shift2']; ?>" required></td>
								<td><input type="text" id="outsource_total_shift3" name="outsource_total_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['outsource_total_shift3']; ?>" required></td>
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
								<td><input type="text" id="pest_control_koordinator_shift1" name="pest_control_koordinator_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_koordinator_shift1']; ?>" required></td>
								<td><input type="text" id="pest_control_koordinator_shift2" name="pest_control_koordinator_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_koordinator_shift2']; ?>" required></td>
								<td><input type="text" id="pest_control_koordinator_shift3" name="pest_control_koordinator_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_koordinator_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Leader</td>
								<td><input type="text" id="pest_control_leader_shift1" name="pest_control_leader_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_leader_shift1']; ?>" required></td>
								<td><input type="text" id="pest_control_leader_shift2" name="pest_control_leader_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_leader_shift2']; ?>" required></td>
								<td><input type="text" id="pest_control_leader_shift3" name="pest_control_leader_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_leader_shift3']; ?>" required></td>
							</tr>
							<tr>
								<td>Crew</td>
								<td><input type="text" id="pest_control_crew_shift1" name="pest_control_crew_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_crew_shift1']; ?>" required></td>
								<td><input type="text" id="pest_control_crew_shift2" name="pest_control_crew_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_crew_shift2']; ?>" required></td>
								<td><input type="text" id="pest_control_crew_shift3" name="pest_control_crew_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->housekeeping['pest_control_crew_shift3']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">TARGET PEKERJAAN <a class="add-target-pekerjaan" data-typeid="2" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></span>	
				 <table id="target-pekerjaan-table" class="table">
					  <thead>
						<tr>
						  <th class="id-hidden"></th>
						  <th>Target Perkerjaan</th>
						  <th width="250">Shift 1</th>
						  <th width="250">Shift 2</th>
						  <th width="250">Shift 3</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->work_target)) {
						foreach($this->work_target as $work_target) { ?>
						<tr>
							<td class="id-hidden"><input type="hidden" name="work_target_id[]" value="<?php echo $work_target['work_target_id']; ?>"></td>
							<td><input type="text" name="work_target[]" class="form-control col-md-7 col-xs-12" value="<?php echo $work_target['work_target']; ?>" required></td>
							<td><input type="text" name="work_target_shift1[]" class="form-control col-md-7 col-xs-12" value="<?php echo $work_target['shift1']; ?>" required></td>
							<td><input type="text" name="work_target_shift2[]" class="form-control col-md-7 col-xs-12" value="<?php echo $work_target['shift2']; ?>" required></td>
							<td><input type="text" name="work_target_shift3[]" class="form-control col-md-7 col-xs-12" value="<?php echo $work_target['shift3']; ?>" required></td>
							<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest('tr').remove();"></i></td>
						</tr>
						<?php } } ?>
					</tbody>
				 </table>
			</div>
			
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
						<?php if(!empty($this->hasilTangkapan)) { 
							$i = 0;
							foreach($this->hasilTangkapan as $hasilTangkapan) {
						?>
						<tr>
							<td><?php echo $hasilTangkapan['hewan_tangkapan']; ?><input type="hidden" name="tangkapan_id[<?php echo $i; ?>]" value="<?php echo $hasilTangkapan['tangkapan_id']; ?>"></td>
							<td><input type="text" name="hasil_tangkapan_shift1[<?php echo $i; ?>]" class="form-control col-md-7 col-xs-12" value="<?php echo $hasilTangkapan['shift1']; ?>" required></td>
							<td><input type="text" name="hasil_tangkapan_shift2[<?php echo $i; ?>]" class="form-control col-md-7 col-xs-12" value="<?php echo $hasilTangkapan['shift2']; ?>" required></td>
							<td><input type="text" name="hasil_tangkapan_shift3[<?php echo $i; ?>]" class="form-control col-md-7 col-xs-12" value="<?php echo $hasilTangkapan['shift3']; ?>" required></td>
						</tr>
						<?php $i++; } } ?>
					</tbody>
				 </table>
			</div>
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">TRAINING <a class="add-training" data-typeid="2" style="cursor:pointer;"><i class="fa fa-plus-square"></i></a></span>	
				 <table id="training-table" class="table">
					  <thead>
						<tr>
						  <th class="id-hidden"></th>
						  <th rowspan="2">Training</th>
						  <th width="250" rowspan="2">Shift 1</th>
						  <th width="250" rowspan="2">Shift 2</th>
						  <th width="250" rowspan="2">Shift 3</th>
						  <th></th>
						</tr>
					  </thead>
					  <tbody>
						<?php if(!empty($this->training)) {
						foreach($this->training as $training) { ?>
						<tr>
							<td class="id-hidden"><input type="hidden" name="training_id[]" value="<?php echo $training['training_id']; ?>"></td>
							<td><input type="text" name="training_name[]" class="form-control col-md-7 col-xs-12" value="<?php echo $training['training_name']; ?>" required></td>
							<td><input type="text" name="training_shift1[]" class="form-control col-md-7 col-xs-12" value="<?php echo $training['shift1']; ?>" required></td>
							<td><input type="text" name="training_shift2[]" class="form-control col-md-7 col-xs-12" value="<?php echo $training['shift2']; ?>" required></td>
							<td><input type="text" name="training_shift3[]" class="form-control col-md-7 col-xs-12" value="<?php echo $training['shift3']; ?>" required></td>
							<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest('tr').remove();"></i></td>
						</tr>
						<?php } } ?>
					</tbody>
				 </table>
			</div>
				
			
			<div class="col-md-12 col-sm-12 col-xs-12">			
			  <span class="section">LAPORAN KEJADIAN</span>				
			  <table id="briefing-table" class="table">
				<tr id="briefing">
				  <td style="border-top:none;">
					<div class="col-md-4 col-xs-12"><textarea id="briefing1" name="briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing1']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing2" name="briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing2']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing3" name="briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->housekeeping['briefing3']); ?></textarea></div>
				  </td>
				</tr>
			</table>
			</div>
			
			<div class="col-md-12 col-sm-12 col-xs-12">			  
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:200px;">Selanjutnya</button>
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
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});
	
	$(".add-target-pekerjaan").click(function() {
		var type_id = this.dataset.typeid;
		var row;
		var table_name;
		
		row = '<tr>
			<td class="id-hidden"><input type="hidden" name="work_target_id[]" value="<?php echo $work_target['work_target_id']; ?>"></td>
			<td><input type="text" name="work_target[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="work_target_shift1[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="work_target_shift2[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="work_target_shift3[]" class="form-control col-md-7 col-xs-12" required></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#target-pekerjaan-table").append(row);
	});
	
	$(".add-training").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td class="id-hidden"><input type="hidden" name="training_id[]"></td>
			<td><input type="text" name="training_name[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="training_shift1[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="training_shift2[]" class="form-control col-md-7 col-xs-12" required></td>
			<td><input type="text" name="training_shift3[]" class="form-control col-md-7 col-xs-12" required></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#training-table").append(row);
	});
	
	$("#add-progress-shift12").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><textarea name="area_progress_shift12[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><input type="file" name="img_before_shift12[]"></td>
			<td align="center"><input type="file" name="img_progress_shift12[]"></td>
			<td align="center"><input type="file" name="img_after_shift12[]"></td>
			<td align="center"><textarea name="status_progress_shift12[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#progress-report-shift12-table").append(row);
	});
	
	$("#add-progress-shift3").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><textarea name="area_progress_shift3[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><input type="file" name="img_before_shift3[]"></td>
			<td align="center"><input type="file" name="img_progress_shift3[]"></td>
			<td align="center"><input type="file" name="img_after_shift3[]"></td>
			<td align="center"><textarea name="status_progress_shift3[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#progress-report-shift3-table").append(row);
	});
	
	$("#add-other-info").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
			<td><textarea name="other_info_area[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center"><input type="file" name="other_info_progress[]"></td>
			<td><textarea name="other_info_status[]" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
			<td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
		</tr>';		
		
		$( "#other-info-table").append(row);
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
});	
</script>