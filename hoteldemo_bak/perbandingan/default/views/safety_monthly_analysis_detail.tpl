<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle">Safety Monthly Analytics <?php echo $this->monthYear; ?> - <?php echo $this->ident['initial']; ?></h2>
			<div class="clearfix"></div>			
			<a href="/default/safety/downloadsafetymonthlyanalysis/id/<?php echo $this->monthly_analysis_id; ?>" target="_blank" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			<span class="section">REKAPITULASI KEJADIAN DAN KECELAKAAN</span>
			<h3>Rekapitulasi Kejadian</h3>	
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Kejadian</th>
				  <th rowspan="2">Modus</th>
				  <th colspan="12"><?php echo $this->year; ?></th>
				  <th rowspan="2">Total</th>
				</tr>
				<tr>
					<th>Jan</th>
					<th>Feb</th>
					<th>Mar</th>
					<th>Apr</th>
					<th>Mei</th>
					<th>Jun</th>
					<th>Jul</th>
					<th>Agt</th>
					<th>Sep</th>
					<th>Okt</th>
					<th>Nov</th>
					<th>Des</th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->rekap)) {
					foreach($this->rekap as $rekapitulasi) { ?>
				<?php 	
					 $j = 0;
					 if(!empty($rekapitulasi['modus'])) {
					 foreach($rekapitulasi['modus'] as $m) { ?>
				<tr>
				<?php if($j == 0 || $j > $rekapitulasi['total_modus'])
					{	$j = 0;
				?>
					<td rowspan="<?php echo $rekapitulasi['total_modus']; ?>"><?php echo $rekapitulasi['kejadian_name']; ?></td>
				<?php } ?>
					<td><?php echo $m['modus_name']; ?></td>
					<td align="center"><?php echo $m['total_modus_jan']; ?></td>
					<td align="center"><?php echo $m['total_modus_feb']; ?></td>
					<td align="center"><?php echo $m['total_modus_mar']; ?></td>
					<td align="center"><?php echo $m['total_modus_apr']; ?></td>
					<td align="center"><?php echo $m['total_modus_may']; ?></td>
					<td align="center"><?php echo $m['total_modus_jun']; ?></td>
					<td align="center"><?php echo $m['total_modus_jul']; ?></td>
					<td align="center"><?php echo $m['total_modus_aug']; ?></td>
					<td align="center"><?php echo $m['total_modus_sep']; ?></td>
					<td align="center"><?php echo $m['total_modus_oct']; ?></td>
					<td align="center"><?php echo $m['total_modus_nov']; ?></td>
					<td align="center"><?php echo $m['total_modus_dec']; ?></td>
					<td align="center"><strong><?php echo $m['total_modus_peryear']; ?></strong></td>
					<?php $j++; } } ?>
				</tr>
				<?php $i++; } } ?>
				<tr>
					<td colspan="2" align="center"><strong>TOTAL KEJADIAN</strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perjan']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perfeb']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_permar']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perapr']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_permay']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perjun']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perjul']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_peraug']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_persep']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_peroct']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_pernov']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_perdec']; ?></strong></td>
					<td align="center"><strong><?php echo $this->rekapTotal['total_modus_all']; ?></strong></td>
				</tr>
			  </tbody>
			</table>
			</div>

			<span class="section">PERALATAN DAN PERLENGKAPAN</span>
			<h3>KONDISI PERALATAN DAN PERLENGKAPAN</h3>	
			<h3>Proteksi Aktif Gedung</h3>
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
					<table width="100%">
						<tr>
							<td rowspan="3"><strong>False Alarm</strong></td>
							<td>Akibat Kerusakan System</td>
							<td style="width:50px;" align="center"><?php echo $this->false_alarm['kerusakan_system']; ?></td>
						</tr>
						<tr>
							<td>Akibat Kerusakan Alat Pendeteksi</td>
							<td style="width:50px;" align="center"><?php echo $this->false_alarm['kerusakan_alat_pendeteksi']; ?></td>
						</tr>
						<tr>
							<td>Akibat Keteledoran Pekerja-customer</td>
							<td style="width:50px;" align="center"><?php echo $this->false_alarm['keteledoran_pekerja_customer']; ?></td>
						</tr>
						<tr>
							<td colspan="2" align="right" style="background-color:#eee;"><strong>Total</strong></td>
							<td style="width:50px; background-color:#eee;" align="center"><strong><?php echo ($this->false_alarm['kerusakan_system']+$this->false_alarm['kerusakan_alat_pendeteksi']+$this->false_alarm['keteledoran_pekerja_customer']); ?></strong></td>
						</tr>
					</table>
				<?php 
						}
				?>
					<h5><?php echo $buildingActiveProtection['equipment_name']; ?></h5>
					<table id="perlengkapan-table" class="table">
						<tr>
							<th width="50">No</th>
							<th><?php echo $buildingActiveProtection['column_name']; ?></th>
							<th width="250">Deskripsi</th>
							<th width="150">Lokasi</th>
							<th width="100">Jumlah</th>
							<th width="150">Kondisi</th>
						</tr>
						<tr>
							<td><?php echo $j; ?></td>
							<td><?php echo $buildingActiveProtection['item_name']; ?></td>
							<td><?php echo $buildingActiveProtection['description']; ?></td>
							<td><?php echo $buildingActiveProtection['location']; ?></td>
							<td><?php echo $buildingActiveProtection['total_item']; ?></td>
							<td><?php echo $buildingActiveProtection['item_condition']; ?></td>
						</tr>
				<?php  $equipment_name = $buildingActiveProtection['equipment_name'];
					   $i++;
					   $j++;
					}
					else { ?>
						<tr>
							<td><?php echo $j; ?></td>
							<td><?php echo $buildingActiveProtection['item_name']; ?><input type="hidden" value="<?php echo $buildingActiveProtection['item_detail_id']; ?>" name="building_active_protection_id[<?php echo $buildingActiveProtection['equipment_item_id']; ?>]"></td>
							<td><?php echo $buildingActiveProtection['description']; ?></td>
							<td><?php echo $buildingActiveProtection['location']; ?></td>
							<td><?php echo $buildingActiveProtection['total_item']; ?></td>
							<td><?php echo $buildingActiveProtection['item_condition']; ?></td>
						</tr>
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
							<td><?php echo $buildingPassiveProtection['item_name']; ?></td>
							<td><?php echo $buildingPassiveProtection['description']; ?></td>
							<td><?php echo $buildingPassiveProtection['location']; ?></td>
							<td><?php echo $buildingPassiveProtection['total_item']; ?></td>
							<td><?php echo $buildingPassiveProtection['item_condition']; ?></td>
						</tr>
				<?php  $equipment_name = $buildingPassiveProtection['equipment_name'];
					   $i++;
					   $j++;
					}
					else { ?>
						<tr>
							<td><?php echo $j; ?></td>
							<td><?php echo $buildingPassiveProtection['item_name']; ?></td>
							<td><?php echo $buildingPassiveProtection['description']; ?></td>
							<td><?php echo $buildingPassiveProtection['location']; ?></td>
							<td><?php echo $buildingPassiveProtection['total_item']; ?></td>
							<td><?php echo $buildingPassiveProtection['item_condition']; ?></td>
						</tr>
					<?php $j++; 
					}
				}
				echo "<table>";
			} ?>

			<h3>Perlengkapan Proteksi Kebakaran Pada Tenant Yang Bermasalah</h3>
			<table id="fire-protection-tenant-table" class="table">
				<tr>
					<th rowspan="2">Tenant</th>
					<th width="150" rowspan="2">LT</th>
					<th colspan="2">Jenis Temuan Di Lapangan</th>
					<th width="150" rowspan="2">Keterangan</th>
				</tr>
				<tr>
					<th width="150">Proteksi Kebakaran</th>
					<th width="150">Potensi Bahaya</th>
				</tr>
				<?php if(!empty($this->fireProtectionTenantEquipment)) {
					foreach($this->fireProtectionTenantEquipment as $fireProtectionTenantEquipment) {
				?>
					<tr>
						<td><?php echo $fireProtectionTenantEquipment['tenant_name']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['floor']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['proteksi_kebakaran']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['potensi_bahaya']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['keterangan']; ?></td>
					</tr>
				<?php } } ?>
			</table>
			
			<h3>Perlengkapan penanggulangan kebakaran dan kecelakaan gedung</h3>
			<table id="perlengkapan-table" class="table">
				<tr>
					<th width="50">No</th>
					<th>Jenis Perlengkapan</th>
					<th width="200">Lokasi Penempatan</th>
					<th width="100">Jumlah</th>
					<th>Kondisi</th>
				</tr>
				<?php if(!empty($this->fire_accident_equipment_detail)) {
					$i = 1;
					foreach($this->fire_accident_equipment_detail as $fire_accident_equipment_detail) 
					{
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $fire_accident_equipment_detail['equipment_name']; ?></td>
					<td><?php echo $fire_accident_equipment_detail['location']; ?></td>
					<td><?php echo $fire_accident_equipment_detail['total']; ?></td>
					<td><?php echo $fire_accident_equipment_detail['item_condition']; ?></td>
				</tr>
				<?php $i++; } } ?>
			</table>

			<?php if(!empty($this->potential_hazard)) { ?>
				<span class="section">DETAIL HASIL DAN PENCAPAIAN</span>			
				<h3>HASIL TEMUAN DI LAPANGAN</h3>	
				<table id="perlengkapan-table" class="table">
				<thead>
					<tr>
						<th width="170">Hari &amp; Tanggal</th>
						<th>Uraian</th>
						<th>Langkah Antisipasi Awal</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->potential_hazard as $potential_hazard) { ?>
						<tr>
							<td><?php echo $potential_hazard['date_time']; ?></td>
							<td><?php echo $potential_hazard['description']; ?></td>
							<td><?php echo $potential_hazard['status']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
				</table>
			<?php } ?>
			<?php if(!empty($this->training_safety_induction)) { ?>
				<h3>PELAKSANAAN PELATIHAN DAN SAFETY INDUCTION</h3>	
				<table id="perlengkapan-table" class="table">
				<thead>
					<tr>
						<th width="170">Hari &amp; Tanggal</th>
						<th>Jenis Pelatihan</th>
						<th>Peserta</th>
						<th>Dokumen</th>
						<th>Keterangan</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($this->training_safety_induction as $training_safety_induction) { ?>
						<tr>
							<td><?php echo $training_safety_induction['training_date']; ?></td>
							<td><?php echo $training_safety_induction['activity']." : ".$training_safety_induction['description']; ?></td>
							<td><?php echo $training_safety_induction['participant']; ?></td>
							<td><?php if(!empty($training_safety_induction['document'])) { ?><a href="/safety_training/<?php echo $this->year."/".$training_safety_induction['document']; ?>" target="_blank" style="margin-right:10px;"><i class="fa fa-paperclip"></i> <?php echo $training_safety_induction['document']; ?></a><?php } ?></td>
							<td><?php echo $training_safety_induction['remark']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
				</table>
			<?php } ?>

			<span class="section">DETAIL REKAPITULASI KEJADIAN K3</span>
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th>Kejadian</th>
				  <th>Modus</th>
				  <th>Uraian Kejadian</th>
				  <th>Total</th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->rekap)) {
					foreach($this->rekap as $rekapitulasi) { ?>
				<?php 	
					 $j = 0;
					 if(!empty($rekapitulasi['modus'])) {
					 foreach($rekapitulasi['modus'] as $m) { ?>
				<tr>
				<?php if($j == 0 || $j > $rekapitulasi['total_modus'])
					{	$j = 0;
				?>
					<td rowspan="<?php echo $rekapitulasi['total_modus']; ?>"><?php echo $rekapitulasi['kejadian_name']; ?></td>
				<?php } ?>
					<td><?php echo $m['modus_name']; ?></td>
					<td><?php echo nl2br($m['uraian_kejadian']); ?></td>
					<td align="center"><?php echo $m['total_modus_cur_month']; ?></td>
					<?php $j++; } } ?>
				</tr>
				<?php $i++; } } ?>
			  </tbody>
			</table>
			</div>

			<span class="section">ANALISA SAFETY</span>
			<h3>Urutan Hari Dengan Jumlah Kejadian dan Kecelakaan K3 Tertinggi</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Hari</th>
				  <th colspan="<?php echo count($this->rekap); ?>">Jenis Kejadian <?php echo $this->monthYear; ?></th>
				  <th rowspan="2">Total</th>
				</tr>
				<tr>
					<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
					?>
				  		<th><?php echo $rekap['kejadian_name']; ?></th>
				  	<?php } } ?>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->urutan_hari_tertinggi)) { 
					$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
					foreach($this->urutan_hari_tertinggi as $urutan_hari_tertinggi)
					{	
				?>
					<tr>
						<td><?php echo $days[$urutan_hari_tertinggi['day']-1]; ?></td>
						<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
						?>
							<td><?php echo ($rekap['analisa_hari'][$urutan_hari_tertinggi['day']] ? $rekap['analisa_hari'][$urutan_hari_tertinggi['day']] : '-'); ?></td>
						<?php } } ?>
						<td><?php echo $urutan_hari_tertinggi['total']; ?></td>
					</tr>
				<?php } } ?>
					<tr>
						<td colspan="<?php echo (count($this->rekap) + 1); ?>" align="center"><strong>TOTAL</strong></td>
						<td><strong><?php echo $this->rekapTotal['total_modus_per'.strtolower(date("M", strtotime($this->year."-".$this->month."-01")))]; ?></strong></td>
					</tr>
			  </tbody>
			</table>
			</div>

			<h3>Periode Jam Dengan Jumlah Kejadian dan Kecelakaan K3 Tertinggi</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Jam</th>
				  <th colspan="<?php echo count($this->rekap); ?>">Jenis Kejadian <?php echo $this->monthYear; ?></th>
				  <th rowspan="2">Total</th>
				</tr>
				<tr>
					<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
					?>
				  		<th><?php echo $rekap['kejadian_name']; ?></th>
				  	<?php } } ?>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->urutan_total_jam)) { 
					$times = array('09:00 - 12:00', '12:00 - 16:00', '16:00 - 19:00', '19:00 - 23:00','23:00 - 09:00');
					foreach($this->urutan_total_jam as $key=>$urutan_total_jam)
					{	
				?>
					<tr>
						<td><?php echo $times[$key]; ?></td>
						<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
						?>
							<td><?php echo ($rekap['analisa_jam'][$key] ? $rekap['analisa_jam'][$key] : '-'); ?></td>
						<?php } } ?>
						<td><?php echo $urutan_total_jam; ?></td>
					</tr>
				<?php } } ?>
					<tr>
						<td colspan="<?php echo (count($this->rekap) + 1); ?>" align="center"><strong>TOTAL</strong></td>
						<td><strong><?php echo $this->rekapTotal['total_modus_per'.strtolower(date("M", strtotime($this->year."-".$this->month."-01")))]; ?></strong></td>
					</tr>
			  </tbody>
			</table>
			</div>

			<?php /*<h3>Area Tenant yang Rawan Kejadian dan Kecelakaan K3</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Tenant</th>
				  <th rowspan="2">Lantai</th>
				  <th colspan="<?php echo count($this->rekap); ?>">Jenis Kejadian <?php echo $this->monthYear; ?></th>
				  <th rowspan="2">Total</th>
				</tr>
				<tr>
					<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
					?>
				  		<th><?php echo $rekap['kejadian_name']; ?></th>
				  	<?php } } ?>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->urutan_total_issue_tenant)) { 
					foreach($this->urutan_total_issue_tenant as $urutan_total_issue_tenant)
					{	
				?>
					<tr>
						<td><?php echo $urutan_total_issue_tenant['location']; ?></td>
						<td><?php echo $urutan_total_issue_tenant['floor']; ?></td>
						<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {
						?>
							<td><?php echo ($urutan_total_issue_tenant[$rekap['kejadian_id']] ? $urutan_total_issue_tenant[$rekap['kejadian_id']] : '-'); ?></td>
						<?php } } ?>
						<td><?php echo $urutan_total_issue_tenant['total']; ?></td>
					</tr>
				<?php } } ?>
					<tr>
						<td colspan="<?php echo (count($this->rekap) + 2); ?>" align="center"><strong>TOTAL</strong></td>
						<td><strong><?php echo $this->urutan_total_all_issue_tenant; ?></strong></td>
					</tr>
			  </tbody>
			</table>
			</div>

			<h3>Area Publik yang Rawan Kejadian dan Kecelakaan K3</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Fasilitas Umum</th>
				  <th rowspan="2">Lantai</th>
				  <th colspan="<?php echo count($this->rekap); ?>">Jenis Kejadian <?php echo $this->monthYear; ?></th>
				  <th rowspan="2">Total</th>
				</tr>
				<tr>
					<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
					?>
				  		<th><?php echo $rekap['kejadian_name']; ?></th>
				  	<?php } } ?>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->urutan_total_issue_publik)) { 
					foreach($this->urutan_total_issue_publik as $key=>$urutan_total_issue_publik)
					{	
				?>
					<tr>
						<td><?php echo $urutan_total_issue_publik['location']; ?></td>
						<td><?php echo $urutan_total_issue_publik['floor']; ?></td>
						<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
						?>
							<td><?php echo ($urutan_total_issue_publik[$rekap['kejadian_id']] ? $urutan_total_issue_publik[$rekap['kejadian_id']] : '-'); ?></td>
						<?php } } ?>
						<td><?php echo $urutan_total_issue_publik['total']; ?></td>
					</tr>
				<?php } } ?>
					<tr>
						<td colspan="<?php echo (count($this->rekap) + 2); ?>" align="center"><strong>TOTAL</strong></td>
						<td><strong><?php echo $this->urutan_total_all_issue_publik; ?></strong></td>
					</tr>
			  </tbody>
			</table>
			</div> */ ?>

			<?php if(!empty($this->list_tangkapan)) { ?>
				<span class="section" style="clear:both; padding-top:20px;">REKAPITULASI HASIL PENANGKAPAN PELAKU KEJAHATAN</span>
				<div class="table-dv">
				<table id="perlengkapan-table" class="table">
				<thead>
					<tr>
					<th rowspan="2">Jenis Tangkapan</th>
					<th colspan="12"><?php echo $this->year; ?></th>
					<th rowspan="2">Total</th>
					</tr>
					<tr>
						<th>Jan</th>
						<th>Feb</th>
						<th>Mar</th>
						<th>Apr</th>
						<th>Mei</th>
						<th>Jun</th>
						<th>Jul</th>
						<th>Agt</th>
						<th>Sep</th>
						<th>Okt</th>
						<th>Nov</th>
						<th>Des</th>
					</tr>
				</thead>
				<tbody>
					<?php 
						foreach($this->list_tangkapan as $list_tangkapan) { ?>
						<tr>
							<td><?php echo $list_tangkapan['modus']; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][1]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][2]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][3]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][4]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][5]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][6]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][7]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][8]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][9]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][10]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][11]; ?></td>
							<td align="center"><?php echo $list_tangkapan['monthly'][12]; ?></td>
							<td align="center"><strong><?php echo $list_tangkapan['total_peryear']; ?></strong></td>
						</tr>
					<?php $i++; } ?>
					<tr>
						<td align="center"><strong>TOTAL Hasil Tangkapan</strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[1]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[2]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[3]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[4]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[5]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[6]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[7]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[8]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[9]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[10]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[11]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_tangkapan_monthly[12]; ?></strong></td>
						<td align="center"><strong><?php echo $this->total_all_tangkapan; ?></strong></td>
					</tr>
				</tbody>
				</table>
				</div>
			<?php } if(!empty($this->pelaku_tertangkap_detail)) { ?>
			<div class="table-dv">
			<table id="pelaku-detail-table" class="table">
			  <thead>
				<tr>
				  <th width="200">Photo</th>
				  <th>Description</th>
				  <th>Date</th>
				</tr>
			  </thead>
			  <tbody>
				<?php
					foreach($this->pelaku_tertangkap_detail as $pelaku) {
						if($pelaku['issue_date'] > "2019-10-23 14:30:00")
						{
							$issuedate = explode("-",$pelaku['issue_date']);
							$imageURL = "/images/issues/".$issuedate[0]."/";
						}
						else
							$imageURL = "/images/issues/";
				?>
				<tr>
					<td align="center"><a class="image-popup-vertical-fit" href="<?php echo $imageURL.str_replace(".","_large.",$pelaku['picture']); ?>"><img src="<?php echo $imageURL.str_replace(".","_thumb.",$pelaku['picture']); ?>"></a></td>
					<td><?php echo nl2br($pelaku['description']); ?></td>
					<td><?php echo $pelaku['date']; ?></td>
				</tr>
				<?php } ?>
			  </tbody>
			</table>
			</div>			
			<?php } ?>

			<span class="section" style="clear:both; padding-top:20px;">JENIS KECELAKAAN TERTINGGI</span>
			<div class="table-dv">
			<table id="kesimpulan-table" class="table">
			  <thead>
				<tr>
				  <th>No</th>
				  <th width="200">Jenis Kejadian</th>
				  <th>Jumlah</th>
				  <th>Data hasil Investigasi</th>
				  <th>Langkah Antisipatif</th>
				  <th>Rekomendasi</th>
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->incidents)) {
					$i = 1;
					foreach($this->incidents as $incident) {
				?>
				<tr>
					<td><?php echo $i; ?></td>
					<td><?php echo $incident['kejadian']; ?></td>
					<td><?php echo $incident['total_kejadian']; ?></td>
					<td><?php echo nl2br($incident['analisa']); ?></td>
					<td><?php echo nl2br($incident['tindakan']); ?></td>
					<td><?php echo nl2br($incident['rekomendasi']); ?></td>
				</tr>
				<?php $i++; } } ?>
			  </tbody>
			</table>
			</div>

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
	
	$("#business-intelligence-menu").addClass('active');
	$("#business-intelligence-menu .child_menu").show();
});
</script>