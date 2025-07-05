<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle"><?php echo $this->category['category_name']; ?> Monthly Analytics <?php echo $this->monthYear; ?> - <?php echo $this->ident['initial']; ?></h2>
			<div class="clearfix"></div>			
			<a href="/default/bi/downloadmonthlyanalysis/id/<?php echo $this->monthly_analysis_id; ?>/c/<?php echo $this->category['category_id']; ?>" target="_blank" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			<span class="section">INCIDENT RECAPITULATION</span>
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Incident</th>
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
					<td colspan="2" align="center"><strong>TOTAL INCIDENTS</strong></td>
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

			<span class="section">INCIDENT DETAILS</span>
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <th>Incidents</th>
				  <th>Modus</th>
				  <th>Detail</th>
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

			<span class="section">ANALYSIS DETAILS</span>
			<h3>Sequence of Days with the Highest Number of Incidents</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Type of Incidents</th>
				  <th colspan="<?php echo count($this->urutan_hari_tertinggi); ?>">Days</th>
				</tr>
				<tr>
					<?php if(!empty($this->urutan_hari_tertinggi)) { 
						$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday','Thursday','Friday', 'Saturday');
						foreach($this->urutan_hari_tertinggi as $urutan_hari_tertinggi)
						{	
					?>
						<th><?php echo $days[$urutan_hari_tertinggi['day']-1]; ?></th>
					<?php } } ?>	
				</tr>
			  </thead>
			  <tbody>
				<?php if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {
				?>
					<tr>
						<td><?php echo $rekap['kejadian_name']; ?></td>
						<?php if(!empty($this->urutan_hari_tertinggi)) { 
						$z = 0;
						foreach($this->urutan_hari_tertinggi as $urutan_hari_tertinggi) {
						?>
							<td><?php echo ($rekap['analisa_hari'][$urutan_hari_tertinggi['day']] ? $rekap['analisa_hari'][$urutan_hari_tertinggi['day']] : '-'); ?></td>
						<?php $totalUht[$z] = $totalUht[$z] + intval($rekap['analisa_hari'][$urutan_hari_tertinggi['day']]); $z++; } } ?>
					</tr>
				<?php } } ?>
					<tr>
						<td align="center"><strong>TOTAL</strong></td>
						<?php if(!empty($totalUht)) {
							foreach($totalUht as $tuht)
							{
						?>
							<td><strong><?php echo $tuht; ?></strong></td>
						<?php } } ?>
					</tr>
			  </tbody>
			</table>
			</div>

			<h3>Time Period With Highest Number of Incidents</h3>	
			<div class="table-dv">
			<table class="table">
			  <thead>
				<tr>
				  <th rowspan="2">Type of Incidents</th>
				  <th colspan="5">Time Period</th>
				</tr>
				<tr>
					<?php 
						if(!empty($this->urutan_total_jam)) { 
							$times = array('09:00 - 12:00', '12:00 - 16:00', '16:00 - 19:00', '19:00 - 23:00','23:00 - 09:00');
							foreach($this->urutan_total_jam as $key=>$urutan_total_jam)
							{	
					?>
				  		<th><?php echo $times[$key]; ?></th>
				  	<?php } } ?>
				</tr>
			  </thead>
			  <tbody>
				<?php 
					if(!empty($this->rekap)) { 
						foreach($this->rekap as $rekap) {	
					
				?>
					<tr>
						<td><?php echo $rekap['kejadian_name']; ?></td>
						<?php if(!empty($this->urutan_total_jam)) { 
							$z = 0;
							foreach($this->urutan_total_jam as $key=>$urutan_total_jam)
							{	
								?>
									<td><?php echo ($rekap['analisa_jam'][$key] ? $rekap['analisa_jam'][$key] : '-'); ?></td>
								<?php $totalUtj[$z] = $totalUtj[$z] + intval($rekap['analisa_jam'][$key]); $z++; } } ?>
							</tr>
						<?php } } ?>
					<tr>
						<td align="center"><strong>TOTAL</strong></td>
						<?php if(!empty($totalUtj)) {
							foreach($totalUtj as $tutj)
							{
						?>
							<td><strong><?php echo $tutj; ?></strong></td>
						<?php } } ?>
					</tr>
			  </tbody>
			</table>
			</div>

		
			<span class="section" style="clear:both; padding-top:20px;">CONCLUSION</span>
			<div class="table-dv">
			<table id="kesimpulan-table" class="table">
			  <thead>
				<tr>
				  <th>No</th>
				  <th width="200">Type of Incident</th>
				  <th>Total</th>
				  <th>Analysis</th>
				  <th>Plan &amp; Action</th>
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