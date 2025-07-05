<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle">Housekeeping Monthly Analytics <?php echo $this->monthYear; ?> - <?php echo $this->ident['initial']; ?></h2>
			<div class="clearfix"></div>			
			<a href="/default/housekeeping/downloadhousekeepingmonthlyanalysis/id/<?php echo $this->monthly_analysis_id; ?>" target="_blank" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			<span class="section">PERFORMANCE</span>
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

			<span class="section">DETAIL KEJADIAN <?php echo strtoupper($this->monthYear); ?></span>
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

			<span class="section">DETAIL ANALISA</span>
			<h3>Urutan Hari Dengan Jumlah Kejadian Tertinggi</h3>	
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

			<h3>Periode Jam Dengan Jumlah Kejadian Tertinggi</h3>	
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

			<?php /*<h3>Area Tenant yang rawan kejadian</h3>	
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

			<h3>Area Publik yang rawan kejadian</h3>	
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
				<?php if(!empty($this->list_tangkapan)) {
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
				<?php $i++; } } ?>
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
				<?php if(!empty($this->pelaku_tertangkap_detail)) {
					foreach($this->pelaku_tertangkap_detail as $pelaku) {
						if($pelaku['issue_date'] > "2019-10-23 14:30:00")
						{
							$issuedate = explode("-",$pelaku['issue_date']);
							$imageURL = "/storage/images/issues/".$issuedate[0]."/";
						}
						else
							$imageURL = "/storage/images/issues/";
				?>
				<tr>
					<td align="center"><a class="image-popup-vertical-fit" href="<?php echo $imageURL.str_replace(".","_large.",$pelaku['picture']); ?>"><img src="<?php echo $imageURL.str_replace(".","_thumb.",$pelaku['picture']); ?>"></a></td>
					<td><?php echo nl2br($pelaku['description']); ?></td>
					<td><?php echo $pelaku['date']; ?></td>
				</tr>
				<?php } } ?>
			  </tbody>
			</table>
			</div>			
		
			<span class="section" style="clear:both; padding-top:20px;">KESIMPULAN UMUM</span>
			<div class="table-dv">
			<table id="kesimpulan-table" class="table">
			  <thead>
				<tr>
				  <th>No</th>
				  <th width="200">Jenis Kejadian</th>
				  <th>Jumlah</th>
				  <th>Analisa</th>
				  <th>Rencana &amp; Tindakan</th>
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
					<td><?php echo $incident['analisa']; ?></td>
					<td><?php echo $incident['tindakan']; ?></td>
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