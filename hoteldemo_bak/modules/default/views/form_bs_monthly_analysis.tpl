<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<?php 
if(empty($this->bs['report_date'])) $cur_date = date("Y-m-d");
else {
	$cur_report_date = explode(" ",$this->bs['report_date']);
	$cur_date = $cur_report_date[0];
}
?>


  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2 class="pagetitle">Human Operations Monthly Analytics <?php echo $this->monthYear; ?></h2>
			<div class="clearfix"></div>
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

			<?php if(!empty($this->listIssues)) { ?>
			<span class="section">LIST KAIZEN <?php echo strtoupper($this->monthYear); ?></span>
			<div class="table-dv">
			<table id="perlengkapan-table" class="table">
			  <thead>
				<tr>
				  <?php /*<th>Image</th> */ ?>
				  <th>Type</th>
				  <th>Location</th>
				  <th>Discussion</th>
				  <th>Date</th>
				  <th>Action</th>
				</tr>
			  </thead>
			  <tbody>
				<?php foreach($this->listIssues as $issue) { 
						/*if($issue['issue_date'] > "2019-10-23 14:30:00")
						{
							$issuedate = explode("-",$issue['issue_date']);
							$imageURL = "/images/issues/".$issuedate[0]."/";
						}
						else
							$imageURL = "/images/issues/";	

						if($issue['solved_date'] > "2019-10-23 14:30:00")
						{
							$solvedIssuedate = explode("-",$issue['solved_date']);
							$solvedImageURL = "/images/issues/".$solvedIssuedate[0]."/";
						}
						else
							$solvedImageURL = "/images/issues/";	*/
				?>
				<tr>
					<?php /*
					<td align="center">
						<a class="image-popup-vertical-fit" href="<?php echo $imageURL.str_replace(".","_large.",$issue['picture']); ?>"><img src="<?php echo $imageURL.str_replace(".","_thumb.",$issue['picture']); ?>" class="monthly-analysis-img"></a>
						<a class="image-popup-vertical-fit" href="<?php echo $solvedImageURL.str_replace(".","_large.",$issue['solved_picture']); ?>"><img src="<?php echo $solvedImageURL.str_replace(".","_thumb.",$issue['solved_picture']); ?>" class="monthly-analysis-img"></a>
					</td> */ ?>
					<td align="center"><span class="anchor" id="issue<?php echo $issue['issue_id']; ?>"></span><?php echo $issue['kejadian']." - ".$issue['modus']; ?></td>
					<td align="center"><?php echo $issue['location']; ?></td>
					<td align="center"><?php echo nl2br($issue['description']); ?></td>
					<td align="center"><?php echo $issue['date']; ?></td>
					<td align="center"><a href="#issue-form" class="action-btn" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-edit" ></i></a></td>
				</tr>
				<?php $i++; } ?>
			  </tbody>
			</table>
			</div>
			<?php } ?>

			<form action="" id="issue-form" class="mfp-hide white-popup-block" >
				<input type="hidden" name="issue_id" id="issue_id" />
				<label for="kejadian_id">Incident</label><br/>
				<select name="kejadian_id" id="kejadian_id">
				<?php foreach($this->listKejadian as $listKejadian) { ?>
					<option value="<?php echo $listKejadian['kejadian_id']; ?>"><?php echo $listKejadian['kejadian']; ?></option>
				<?php } ?>
				</select><br/><br/>
				<label for="modus">Modus</label><br/>
				<select name="modus_id" id="modus_id">
				<?php foreach($this->modus as $modus) { ?>
					<option value="<?php echo $modus['modus_id']; ?>"><?php echo $modus['modus']; ?></option>
				<?php } ?>
				</select><br/><br/>				
				<input type="checkbox" name="pelaku_tertangkap" id="pelaku_tertangkap"> Pelaku Tertangkap
				<div id="issue-detail"></div>
				<div class="save-btn" style="text-align:center;"><input type="submit" id="save-issue" name="save-issue" value="Save"></div>
			</form>


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
						<td><?php print_r($urutan_total_jam); ?></td>
					</tr>
				<?php } } ?>
					<tr>
						<td colspan="<?php echo (count($this->rekap) + 1); ?>" align="center"><strong>TOTAL</strong></td>
						<td><strong><?php echo $this->rekapTotal['total_modus_per'.strtolower(date("M", strtotime($this->year."-".$this->month."-01")))]; ?></strong></td>
					</tr>
			  </tbody>
			</table>
			</div>

			<h3>Area Tenant yang rawan kejadian</h3>	
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
			</div>

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

			<?php if(!empty($this->pelaku_tertangkap_detail)) { ?>
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
			<form class="form-horizontal form-label-left" action="/default/bs/savemonthlyanalysis" method="POST" onSubmit="$('body').mLoading();">
			  <input type="hidden" value="<?php echo $this->monthly_analysis_id; ?>" name="monthly_analysis_id">
			  <span class="section">KESIMPULAN UMUM</span>
				<div class="table-dv">
				<table id="kejadian-summary-table" class="table">
				<thead>
					<tr>
					<th>No</th>
					<th>Jenis Kejadian</th>
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
						<td align="center"><?php echo $i; ?><input type="hidden" name="summary_id[]" value="<?php echo $incident['summary_id']; ?>"></td>
						<td><?php echo $incident['kejadian']; ?><input type="hidden" name="kejadian_id[]" value="<?php echo $incident['kejadian_id']; ?>"></td>
						<td align="center"><?php echo $incident['total_kejadian']; ?></td>
						<td><textarea name="analisa[]" class="form-control col-md-7 col-xs-12" style="height:50px;" required><?php echo str_replace("<br>","&#13;",stripslashes($incident['analisa'])); ?></textarea></td>
						<td><textarea name="tindakan[]" class="form-control col-md-7 col-xs-12" style="height:50px;" required><?php echo str_replace("<br>","&#13;",stripslashes($incident['tindakan'])); ?></textarea></td>
					</tr>
					<?php $i++; } } ?>
				</tbody>
				</table>
				</div> 

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

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#business-intelligence-menu").addClass('active');
	$("#business-intelligence-menu .child_menu").show();

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	var issue_id;
	$(".action-btn").click(function() {
		issue_id = this.dataset.id;
	});

	$('.action-btn').magnificPopup({
		type: 'inline',
		preloader: false,
		callbacks: {
			open: function() {
			  $.ajax({
					url: "/default/issue/getIssueById",
					data: { id : issue_id }
				}).done(function(response) {
					var resp = $.parseJSON(response);
					$("#issue_id").val(resp.issue_id);
					$("#kejadian_id").val(resp.kejadian_id);
					if(resp.pelaku_tertangkap == '1')
					{
						$('#pelaku_tertangkap').prop('checked', true);
					}
					else
					{
						$('#pelaku_tertangkap').prop('checked', false);
					}
					$.ajax({
						url: "/default/issue/getmodusbykejadianid",
						data: { kejadian_id : resp.kejadian_id, category_id : '10' }
					}).done(function(response) {
						$("#modus_id").empty();
						var object = $.parseJSON(response);
						$.each(object, function (item, value) {
							$("#modus_id").append(new Option(value.modus, value.modus_id));
						});			
						$("#modus_id").val(resp.modus_id);
					});	
					var detail = "<label>Location</label><br/>"+resp.location+"<br/><br/><label>Discussion</label><br/>"+resp.description+"<br/><br/><label>Issue Date</label><br/>"+resp.date_time+"<br/><br/>";
					$( "#issue-detail" ).html(detail);
				
					$("#kejadian_id").change(function() {
						if($( this ).val() > 0)
						{			
							$("#modus_id").prop('required',true);
							$.ajax({
								url: "/default/issue/getmodusbykejadianid",
								data: { kejadian_id : $( this ).val(), category_id: '10'  }
							}).done(function(response) {
								$("#modus_id").empty();
								var object = $.parseJSON(response);
								$.each(object, function (item, value) {
									$("#modus_id").append(new Option(value.modus, value.modus_id));
								});
							});
						}
						else
						{
							$("#modus_id").prop('required',false);
						}
					});
				});	
			},
			close: function() {	
				$( "#issues-detail").html("");
			}
		}
	});

	$('#issue-form').on('submit', function(event){
		event.preventDefault(); 
		$.ajax({
			url: '/default/issue/updateissue',
			type: 'POST',
			data: $(this).serialize(),
			success: function() {
				var idparam = "";
				<?php if(!empty($this->monthly_analysis_id)) { ?>
					idparam = "/id/"+<?php echo $this->monthly_analysis_id; ?>;
				<?php } ?>
				location.href="/default/bs/addmonthlyanalysis"+idparam+"?"+Math.random()+"#issue"+issue_id;
			}
		});
	});
	

});
</script>