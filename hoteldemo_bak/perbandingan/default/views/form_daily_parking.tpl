<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

<?php 
if(empty($this->parking['created_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->parking['created_date']);
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

			<form class="form-horizontal form-label-left" action="/default/parking/savereport" method="POST" onsubmit="$('body').mLoading();">
				<input type="hidden" id="parking_report_id" name="parking_report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['parking_report_id']; ?>">
			  <span class="section">DAY / DATE</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->parking['report_date'])) echo $this->parking['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="reporting_time">Reporting Time
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12" style="padding-top:4px;">
					<?php echo $this->setting['parking_traffic_reporting_time']; ?>
				</div>
			  </div>
			  
			  <span class="section">MAN POWER</span>
			  <div class="col-md-6 col-xs-12">
				  <fieldset>
					<legend>In House</legend>
					<h5>Supervisor</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_spv_malam">Malam 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_spv_malam" class="form-control col-md-7 col-xs-12" name="inhouse_spv_malam" type="text" value="<?php echo $this->parking['inhouse_spv_malam']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_spv_pagi">Pagi 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_spv_pagi" class="form-control col-md-7 col-xs-12" name="inhouse_spv_pagi" type="text" value="<?php echo $this->parking['inhouse_spv_pagi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_spv_siang">Siang 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_spv_siang" class="form-control col-md-7 col-xs-12" name="inhouse_spv_siang" type="text" value="<?php echo $this->parking['inhouse_spv_siang']; ?>" required>
						</div>
					  </div>
					  
					  <h5>Admin</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_admin_malam">Malam 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_admin_malam" class="form-control col-md-7 col-xs-12" name="inhouse_admin_malam" type="text" value="<?php echo $this->parking['inhouse_admin_malam']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_admin_pagi">Pagi 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_admin_pagi" class="form-control col-md-7 col-xs-12" name="inhouse_admin_pagi" type="text" value="<?php echo $this->parking['inhouse_admin_pagi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_admin_siang">Siang 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_admin_siang" class="form-control col-md-7 col-xs-12" name="inhouse_admin_siang" type="text" value="<?php echo $this->parking['inhouse_admin_siang']; ?>" required>
						</div>
					  </div>
					  
					  <h5>Kekuatan</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_kekuatan_malam">Malam 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_kekuatan_malam" class="form-control col-md-7 col-xs-12" name="inhouse_kekuatan_malam" type="text" value="<?php echo $this->parking['inhouse_kekuatan_malam']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_kekuatan_pagi">Pagi 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_kekuatan_pagi" class="form-control col-md-7 col-xs-12" name="inhouse_kekuatan_pagi" type="text" value="<?php echo $this->parking['inhouse_kekuatan_pagi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_kekuatan_siang">Siang 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_kekuatan_siang" class="form-control col-md-7 col-xs-12" name="inhouse_kekuatan_siang" type="text" value="<?php echo $this->parking['inhouse_kekuatan_siang']; ?>" required>
						</div>
					  </div>
					  
					  <h5>Car Count</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_mobil">Mobil 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_mobil" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_mobil" type="text" value="<?php echo $this->parking['inhouse_carcount_mobil']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_motor">Motor 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_motor" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_motor" type="text" value="<?php echo $this->parking['inhouse_carcount_motor']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_box">Box 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_box" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_box" type="text" value="<?php echo $this->parking['inhouse_carcount_box']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_valet_reg">Valet Reg 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_valet_reg" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_valet_reg" type="text" value="<?php echo $this->parking['inhouse_carcount_valet_reg']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_self_valet">Self Valet 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_self_valet" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_self_valet" type="text" value="<?php echo $this->parking['inhouse_carcount_self_valet']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_drop_off">Drop Off 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_drop_off" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_drop_off" type="text" value="<?php echo $this->parking['inhouse_carcount_drop_off']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_taxi">Taxi 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_taxi" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_taxi" type="text" value="<?php echo $this->parking['inhouse_carcount_taxi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="inhouse_carcount_total">Total 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="inhouse_carcount_total" class="form-control col-md-7 col-xs-12" name="inhouse_carcount_total" type="text" value="<?php echo $this->parking['inhouse_carcount_total']; ?>" required>
						</div>
					  </div>
				</fieldset>
			</div>
			<div class="col-md-6 col-xs-12">
				<fieldset>
					<legend>Vendor</legend>
						<h5>CPM/ACPM</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_cpm_acpm_spi"><?php if($this->site_id == 3) echo "CP"; else echo "SPI"; ?> 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="vendor_cpm_acpm_spi" class="form-control col-md-7 col-xs-12" name="vendor_cpm_acpm_spi" type="text" value="<?php echo $this->parking['vendor_cpm_acpm_spi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_cpm_acpm_valet">Valet 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input type="text" id="vendor_cpm_acpm_valet" name="vendor_cpm_acpm_valet" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_cpm_acpm_valet']; ?>" required>
						</div>
					  </div>
					  
					  <h5>Pengawas</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_pengawas_spi"><?php if($this->site_id == 3) echo "CP"; else echo "SPI"; ?>  
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="vendor_pengawas_spi" class="form-control col-md-7 col-xs-12" name="vendor_pengawas_spi" type="text" value="<?php echo $this->parking['vendor_pengawas_spi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_pengawas_valet">Valet 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input type="text" id="vendor_pengawas_valet" name="vendor_pengawas_valet" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_pengawas_valet']; ?>" required>
						</div>
					  </div>
					  
					  <h5>Admin</h5>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_admin_spi"><?php if($this->site_id == 3) echo "CP"; else echo "SPI"; ?> 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input id="vendor_admin_spi" class="form-control col-md-7 col-xs-12" name="vendor_admin_spi" type="text" value="<?php echo $this->parking['vendor_admin_spi']; ?>" required>
						</div>
					  </div>
					  <div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="vendor_admin_valetv">Valet 
						</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
						  <input type="text" id="vendor_admin_valet" name="vendor_admin_valet" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_admin_valet']; ?>" required>
						</div>
					  </div>
					  
					  <h5>KEKUATAN</h5>
					  <table id="kekuatan-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2"></th>
							  <th rowspan="2">Pagi</th>
							  <th rowspan="2">Siang</th>
							  <th rowspan="2">Malam</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td><?php if($this->site_id == 3) echo "CP"; else echo "SPI"; ?> </td>
								<td><input type="text" id="vendor_kekuatan_spi_pagi" name="vendor_kekuatan_spi_pagi" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_spi_pagi']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_spi_siang" name="vendor_kekuatan_spi_siang" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_spi_siang']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_spi_malam" name="vendor_kekuatan_spi_malam" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_spi_malam']; ?>" required></td>
							</tr>
							<tr>
								<td>Valet</td>
								<td><input type="text" id="vendor_kekuatan_valet_pagi" name="vendor_kekuatan_valet_pagi" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_valet_pagi']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_valet_siang" name="vendor_kekuatan_valet_siang" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_valet_siang']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_valet_malam" name="vendor_kekuatan_valet_malam" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_valet_malam']; ?>" required></td>
							</tr>
							<tr>
								<td>Taxi</td>
								<td><input type="text" id="vendor_kekuatan_taxi_pagi" name="vendor_kekuatan_taxi_pagi" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxi_pagi']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_taxi_siang" name="vendor_kekuatan_taxi_siang" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxi_siang']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_taxi_malam" name="vendor_kekuatan_taxi_malam" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxi_malam']; ?>" required></td>
							</tr>
							<tr>
								<td>Taxi Online</td>
								<td><input type="text" id="vendor_kekuatan_taxionline_pagi" name="vendor_kekuatan_taxionline_pagi" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxionline_pagi']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_taxionline_siang" name="vendor_kekuatan_taxionline_siang" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxionline_siang']; ?>" required></td>
								<td><input type="text" id="vendor_kekuatan_taxionline_malam" name="vendor_kekuatan_taxionline_malam" class="form-control col-md-7 col-xs-12" value="<?php echo $this->parking['vendor_kekuatan_taxionline_malam']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>
			
			<span class="section" style="clear:both;">PERLENGKAPAN</span>
			  <table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th rowspan="2" class="id-hidden"></th>
				  <th rowspan="2">Nama Perlengkapan</th>
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
					<td class="id-hidden"><input type="hidden" name="id_equipment_list[<?php echo $i; ?>]" value="<?php echo $equipment['parking_equipment_list_id']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="equipment_name[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['equipment_name']; ?>" disabled></td>
					<td><input class="form-control col-md-7 col-xs-12" name="total_equipment[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['total_equipment']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="ok_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['ok_condition']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="bad_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['bad_condition']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="description[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['description']; ?>" required></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			
			<span class="section">PERALATAN PARKIR</span>
			  <table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
			      <th rowspan="2" class="id-hidden"></th>
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
				<?php if(!empty($this->parkingEquipments)) {
						foreach($this->parkingEquipments as $equipment) {
				?>
				<tr>
					<td class="id-hidden"><input type="hidden" name="id_equipment_list[<?php echo $i; ?>]" value="<?php echo $equipment['parking_equipment_list_id']; ?>"><input type="hidden" name="parking_equipment_type[<?php echo $i; ?>]" value="<?php echo $equipment['parking_equipment_type']; ?>"></td>
					<td><input class="form-control col-md-7 col-xs-12" name="equipment_name[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['equipment_name']; ?>" disabled></td>
					<td><input class="form-control col-md-7 col-xs-12" name="total_equipment[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['total_equipment']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="ok_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['ok_condition']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="bad_condition[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['bad_condition']; ?>" required></td>
					<td><input class="form-control col-md-7 col-xs-12" name="description[<?php echo $i; ?>]" type="text" value="<?php echo $equipment['description']; ?>" required></td>
				</tr>
				<?php $i++; } 
				} ?>
			  </tbody>
			</table>
			  
			  <span class="section">BRIEFING</span>				
			  <table id="briefing-table" class="table">
				<tr id="briefing">
				  <td style="border-top:none;">
					<div class="col-md-4 col-xs-12"><textarea id="briefing1" name="briefing1" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->parking['briefing1']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing2" name="briefing2" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->parking['briefing2']); ?></textarea></div>
					<div class="col-md-4 col-xs-12"><textarea id="briefing3" name="briefing3" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required><?php echo str_replace("<br>","&#13;",$this->parking['briefing3']); ?></textarea></div>
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
						</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;" required><?php echo $outdoorTraining['description']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>
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
						</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;" required><?php echo $inHouseTraining['description']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>
					<?php } } ?>
				</table>
			  </div>
			  
			<span class="section" style="clear:both;">SOSIALISASI SOP</span>	  
			<input id="sop1" class="form-control col-md-7 col-xs-12" name="sop1" type="text" value="<?php echo $this->parking['sop1']; ?>" required>
			<input id="sop2" class="form-control col-md-7 col-xs-12" name="sop2" type="text" value="<?php echo $this->parking['sop2']; ?>" style="margin-top:5px;" required>
			<input id="sop3" class="form-control col-md-7 col-xs-12" name="sop3" type="text" value="<?php echo $this->parking['sop3']; ?>" style="margin-top:5px;" required>  
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
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="<?php echo $specific_report['issue_id']; ?>"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type_id']; ?>"><input type="hidden" id="parkingid" name="parking-id-sr[]" value="<?php echo intval($specific_report['parking_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['time']; ?>" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['time']; ?>" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="<?php echo $specific_report['description']; ?>" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['description']; ?>" /></td>
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
						<td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="<?php echo $specific_report['issue_type']; ?>"><input type="hidden" id="parkingid" name="parking-id-sr[]" value="<?php echo intval($specific_report['parking_id']); ?>"></td>
						<td><strong><?php echo $specific_report['issue_type_name']; ?></strong><br/><?php if($specific_report['issue_type'] == 4) echo "Area"; else echo "Time"; ?> : <input type="text" id="time-<?php echo $specific_report['issue_type']; ?>" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['time']; ?>" required /><br/>Detail : <input type="text" id="description-<?php echo $specific_report['issue_type']; ?>" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="<?php echo $specific_report['detail']; ?>"  required /></td>
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
		<label class="control-label col-md-3 col-sm-3 col-xs-12" for="issue_type">Issue Type 
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
		$(".edit-parking").css("display", "block");
		$(".edit-parking").addClass('current-page');
		$(".edit-parking").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	<?php } ?>

	$(".add-training").click(function() {
		var type_id = this.dataset.typeid;
		var row;
		var table_name;

		row = '<tr><td class="id-hidden"><input type="hidden" name="training_type[]" value="'+type_id+'"></td><td><select name="training_activity[]" class="form-control" required>';
		
		<?php if(!empty($this->training_activity)) { 
			foreach($this->training_activity as $training_activity) {
		?>
		row = row + '<option value="<?php echo $training_activity['training_activity_id']; ?>" <?php if($training_activity['training_activity_id'] == $this->parking['morning']['outsource_training_activity']) echo "selected"; ?>><?php echo $training_activity['activity']; ?></option>';
		<?php } } ?>
		
		row = row + '</select>DESKRIPSI TRAINING<textarea name="description_training[]" class="form-control col-md-7 col-xs-12" style="height:100px!important;"><?php echo $this->parking['morning']['description_training']; ?></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>';
		
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
					if($( "#issue_type" ).val() > 0 && $( "#issue_type" ).val() < 5)
					{
						$.ajax({
							url: "/default/issue/getissuebytype",
							data: { id : $( "#issue_type" ).val(), show_shift:'0', cat_id:5, report_date:'<?php echo $cur_date; ?>' }
						}).done(function(response) {
							$( "#list-issue" ).html(response);
						});
					}
					/*else if($( "#issue_type" ).val() == 4)
					{
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Area </label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail </label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Follow Up </label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
						$( "#list-issue" ).html(content);
						
					}*/
					else if($( "#issue_type" ).val() > 4) {
						var content = '<label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Time </label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="time" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Detail </label><div class="col-md-6 col-sm-6 col-xs-12"><input type="text" name="detail" class="form-control col-md-7 col-xs-12" style="height:50px;" /></div><br/><br/><label class="control-label col-md-3 col-sm-3 col-xs-12" for="time">Status </label><div class="col-md-6 col-sm-6 col-xs-12"><textarea name="status" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></div>';
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
		if(data[0].value > 0 && data[0].value < 5 )
		{
			$.each( $( this ).serializeArray(), function( i, field ) {
				if(field.name == 'chk_issue_id')
				{
					$.ajax({
						url: "/default/issue/getissuebyid",
						data: { id : field.value, report_date: '<?php echo $cur_date; ?>' }
					}).done(function(response) {
						var issue = $.parseJSON(response);
						var issuedate = issue.issue_date;
						var issuetime = issuedate.substring(11);
						if(data[0].value == 4)
						{
								data = '<tr id="sr-'+issue.issue_id+'"><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="'+issue.issue_id+'"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="parkingid" name="parking-id-sr[]" value="'+issue.parking_id+'"></td><td><strong>'+issue.issue_type+'</strong><br/>Area : <input type="text" id="time-dl" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.location+'" /><br/>Detail : <input type="text" id="description-dl" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.description+'" /></td><td><br/>Status<br/><textarea id="status-dl" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';	
						}
						else
						{
							data = '<tr id="sr-'+issue.issue_id+'"><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="'+issue.issue_id+'"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="parkingid" name="parking-id-sr[]" value="'+issue.parking_id+'"></td><td><strong>'+issue.issue_type+'</strong><br/>Time : <input type="text" name="time-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issuetime+'" /><input type="hidden" id="time-sr" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issuetime+'" /><br/>Detail : <input type="text" name="description-sr" class="form-control col-md-7 col-xs-12" style="height:50px;" disabled value="'+issue.description+'" /><input type="hidden" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+issue.description+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue.issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;"></textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue" data-id="sr-'+issue.issue_id+'"></i></td></tr>';					
						}										
						$( "#specific-report-table").append(data);
						$(".remove-issue").click(function() {
							$(this).closest('tr').remove();
						});
					});
				}
			});
		}
		else if(data[0].value > '4')
		{
			var timeField = "Time";
			var parking_id = "";
			/*if(data[0].value == '4') {
				console.log(data);
				issue_type = 'Defect List';
				timeField = "Area";
				var timeValue = data[1].value;
				var detailValue = data[2].value;
				var statusValue = data[3].value;
			}
			else
			{*/
				if(data[0].value == '5') issue_type = 'Safety';
				if(data[0].value == '6') issue_type = 'Traffic Report';
				var timeValue = data[1].value;
				var detailValue = data[2].value;
				var statusValue = data[3].value;
			/*}*/
			data = '<tr><td class="id-hidden"><input type="hidden" id="id-issue" name="id-issue-sr[]" value="0"><input type="hidden" id="issue_type" name="issue_type[]" value="'+data[0].value+'"><input type="hidden" id="parkingid" name="parking-id-sr[]" value="'+parking_id+'"></td><td><strong>'+issue_type+'</strong><br/>'+timeField+' : <input type="text" id="time-'+ issue_type+'" name="time-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+timeValue+'" /><br/>Detail : <input type="text" id="description-'+ issue_type+'" name="description-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;" value="'+detailValue+'" /></td><td><br/>Status<br/><textarea id="status-'+ issue_type+'" name="status-sr[]" class="form-control col-md-7 col-xs-12" style="height:50px;">'+statusValue+'</textarea></td><td align="center"  style="vertical-align:middle;"><i class="fa fa-trash remove-issue""></i></td></tr>';					
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