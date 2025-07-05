<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<?php 
if(empty($this->safety['report_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->safety['report_date']);
	$cur_date = $cur_report_date[0];
}
?>

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle">Safety Monthly Analytics <?php echo $this->monthYear; ?></h2>
			<div class="clearfix"></div>
		  </div>
		  <div class="x_content">
		  <form class="form-horizontal form-label-left" action="/default/safety/savemonthlyanalysis" method="POST" onSubmit="$('body').mLoading();">
			<input type="hidden" value="<?php echo $this->monthly_analysis_id; ?>" name="monthly_analysis_id">
			<span class="section">PERALATAN DAN PERLENGKAPAN</span>
			<h3>Kondisi Peralatan dan Perlengkapan</h3>	
			<h5><strong>Proteksi Aktif Gedung</strong></h5>
			<?php if(!empty($this->buildingActiveProtection)) {
				$i=1;
				$equipment_name = "";
				foreach($this->buildingActiveProtection as $buildingActiveProtection)
				{ 
					if($buildingActiveProtection['equipment_name'] != $equipment_name)
					{	
						$j=1;
						if($i > 1) echo "<table>";
						
						if(strpos(strtolower($equipment_name), "alarm") !== false)
						{
				?>
					<input type="hidden" value="<?php echo $this->false_alarm['false_alarm_id']; ?>" name="false_alarm_id">
					<table width="100%">
						<tr>
							<td rowspan="3"><strong>False Alarm</strong></td>
							<td>Akibat Kerusakan System</td>
							<td style="width:50px;" align="center"><input type="number" id="false_alarm_kerusakan_system" name="false_alarm_kerusakan_system" style="width:60px;" value="<?php echo $this->false_alarm['kerusakan_system']; ?>" required></td>
						</tr>
						<tr>
							<td>Akibat Kerusakan Alat Pendeteksi</td>
							<td style="width:50px;" align="center"><input type="number" id="false_alarm_kerusakan_alat_pendeteksi" name="false_alarm_kerusakan_alat_pendeteksi" style="width:60px;" value="<?php echo $this->false_alarm['kerusakan_alat_pendeteksi']; ?>" required></td>
						</tr>
						<tr>
							<td>Akibat Keteledoran Pekerja-customer</td>
							<td style="width:50px;" align="center"><input type="number" id="false_alarm_keteledoran_pekerja_customer" name="false_alarm_keteledoran_pekerja_customer" style="width:60px;" value="<?php echo $this->false_alarm['keteledoran_pekerja_customer']; ?>" required></td>
						</tr>
						<?php /*<tr>
							<td colspan="2" align="right"><strong>Total</strong></td>
							<td style="width:50px;" align="center"><input type="number" id="false_alarm_total" name="false_alarm_total" style="width:60px;" disabled></td>
						</tr> */ ?>
					</table>
				<?php 
						}
				?>
					<h5><?php echo $buildingActiveProtection['equipment_name']; ?></h5>
					<table id="perlengkapan-table" class="table">
						<?php if($buildingActiveProtection['equipment_name'] == "Pompa Kebakaran") { ?>
							<tr>
								<th width="50">No</th>
								<th width="250"><?php echo $buildingActiveProtection['column_name']; ?></th>
								<th width="250">Deskripsi</th>
								<th width="150">Lokasi</th>
								<th width="100">Jumlah</th>
								<th>Kondisi</th>
							</tr>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $buildingActiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingActiveProtection['item_detail_id']; ?>" name="building_active_protection_id[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]"></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_description[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['description'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_location[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['location'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_total_item[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingActiveProtection['total_item']; ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['item_condition'])); ?></textarea></td>
							</tr>
						<?php } else { ?>
							<tr>
								<th width="50" rowspan="2">No</th>
								<th width="250" rowspan="2"><?php echo $buildingActiveProtection['column_name']; ?></th>
								<th width="200" rowspan="2">Deskripsi</th>
								<th width="150" rowspan="2">Lokasi</th>
								<th width="100" rowspan="2">Jumlah</th>
								<th width="240" colspan="4">Kondisi</th>
								<th rowspan="2">Remark</th>
							</tr>
							<tr>
								<th width="60">Baik</th>
								<th width="60"><?php if($buildingActiveProtection['equipment_name'] == "APAR") { ?>Expired<?php } else { ?>Rusak<?php } ?></th>
								<th width="60"><?php if($buildingActiveProtection['equipment_name'] == "APAR") { ?>Pengisian Ulang<?php } else { ?>Perbaikan<?php } ?></th>
								<th width="60">Baru</th>
							</tr>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $buildingActiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingActiveProtection['item_detail_id']; ?>" name="building_active_protection_id[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]"></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_description[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['description'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_location[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['location'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_total_item[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingActiveProtection['total_item']; ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_good_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['good_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_bad_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['bad_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_repair_refill_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['repair_refill_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_new_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['new_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_remark[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['remark'])); ?></textarea></td>
							</tr>
						<?php } ?>
						
				<?php  $equipment_name = $buildingActiveProtection['equipment_name'];
					   $i++;
					   $j++;
					}
					else { ?>
						<?php if($buildingActiveProtection['equipment_name'] == "Pompa Kebakaran") { ?>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $buildingActiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingActiveProtection['item_detail_id']; ?>" name="building_active_protection_id[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]"></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_description[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['description'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_location[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['location'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_total_item[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingActiveProtection['total_item']; ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['item_condition'])); ?></textarea></td>
							</tr>
						<?php } else { ?>
							<tr>
								<td><?php echo $j; ?></td>
								<td><?php echo $buildingActiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingActiveProtection['item_detail_id']; ?>" name="building_active_protection_id[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]"></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_description[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['description'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_location[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['location'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_total_item[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingActiveProtection['total_item']; ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_good_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['good_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_bad_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['bad_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_repair_refill_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['repair_refill_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_new_condition[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['new_condition'])); ?></textarea></td>
								<td><textarea class="monthly_analysis_field" rows="1" name="building_active_protection_remark[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingActiveProtection['remark'])); ?></textarea></td>
							</tr>
						<?php } ?>
					<?php $j++; 
					}
				}
				echo "<table>";
			} ?>
			<h5><strong>Proteksi Pasif Gedung</strong></h5>
			<?php if(!empty($this->buildingPassiveProtection)) {
				$i=1;
				$equipment_name = "";
				foreach($this->buildingPassiveProtection as $buildingPassiveProtection)
				{ 
					if($buildingPassiveProtection['equipment_name'] != $equipment_name)
					{	
						$j=1;
						if($i > 1) echo "<table>";
				?>
					<h5><?php echo $buildingPassiveProtection['equipment_name']; ?></h5>
					<table id="perlengkapan-table" class="table">
						<tr>
							<th width="50">No</th>
							<th><?php echo $buildingPassiveProtection['column_name']; ?></th>
							<th width="250">Deskripsi</th>
							<th width="150">Lokasi</th>
							<th width="100">Jumlah</th>
							<th width="150">Kondisi</th>
						</tr>
						<tr>
							<td><?php echo $j; ?></td>
							<td><?php echo $buildingPassiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingPassiveProtection['item_detail_id']; ?>" name="building_passive_protection_id[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]"></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_description[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['description'])); ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_location[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['location'])); ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_total_item[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingPassiveProtection['total_item']; ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_condition[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['item_condition'])); ?></textarea></td>
						</tr>
				<?php  $equipment_name = $buildingPassiveProtection['equipment_name'];
					   $i++;
					   $j++;
					}
					else { ?>
						<tr>
							<td><?php echo $j; ?></td>
							<td><?php echo $buildingPassiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingPassiveProtection['item_detail_id']; ?>" name="building_passive_protection_id[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]"></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_description[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['description'])); ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_location[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['location'])); ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_total_item[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo $buildingPassiveProtection['total_item']; ?></textarea></td>
							<td><textarea class="monthly_analysis_field" rows="1" name="building_passive_protection_condition[<?php echo $buildingPassiveProtection['equipment_item_id']; ?>]" required><?php echo str_replace("<br>","&#13;",stripslashes($buildingPassiveProtection['item_condition'])); ?></textarea></td>
						</tr>
					<?php $j++; 
					}
				}
				echo "<table>";
			} ?>
		
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="send" type="submit" class="btn btn-success" style="width:250px;">Simpan & Ke Halaman Berikutnya</button>
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
});
</script>