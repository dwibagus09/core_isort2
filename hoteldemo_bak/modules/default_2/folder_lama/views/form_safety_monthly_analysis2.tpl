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
		  <form class="form-horizontal form-label-left" action="/default/safety/savemonthlyanalysis2" method="POST" onSubmit="$('body').mLoading();">
			<input type="hidden" value="<?php echo $this->monthly_analysis_id; ?>" name="monthly_analysis_id">
			<span class="section">PERALATAN DAN PERLENGKAPAN</span>
			<h3>Kondisi Peralatan dan Perlengkapan</h3>	
			<?php if(!empty($this->fireProtectionTenantEquipment)) { ?>
			<h5><strong>Perlengkapan Proteksi Kebakaran Pada Tenant Yang Bermasalah</strong></h5>
			<table id="fire-protection-tenant-table" class="table">
				<tr>
					<th rowspan="2">Tenant</th>
					<th rowspan="2">LT</th>
					<th colspan="2" width="50%">Jenis Temuan Di Lapangan</th>
					<th rowspan="2" width="25%">Keterangan</th>
				</tr>
				<tr>
					<th width="25%">Proteksi Kebakaran</th>
					<th width="25%">Potensi Bahaya</th>
				</tr>
				
				<?php foreach($this->fireProtectionTenantEquipment as $fireProtectionTenantEquipment) {	?>
					<tr>
						<td><input type="hidden" value="<?php echo $fireProtectionTenantEquipment['perlengkapan_tenant_id']; ?>" name="fireProtectionId[]"><input type="hidden" value="<?php echo $fireProtectionTenantEquipment['issue_id']; ?>" name="fireProtectionIssueid[]"><?php echo $fireProtectionTenantEquipment['location']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['floor']; ?></td>
						<td><?php echo $fireProtectionTenantEquipment['description']; ?></td>
						<td><textarea rows="1" name="fireProtectionPotensiBahaya[]" class="textarea-column" required><?php echo $fireProtectionTenantEquipment['potensi_bahaya']; ?></textarea></td>
						<td><textarea rows="1" name="fireProtectionKeterangan[]" class="textarea-column" required><?php echo $fireProtectionTenantEquipment['keterangan']; ?></textarea></td>
					</tr>
				<?php } ?>
			</table>
			<?php } ?>
			
			<h5><strong>Perlengkapan penanggulangan kebakaran dan kecelakaan gedung</strong></h5>
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
					<td><?php echo $i; ?><input type="hidden" value="<?php echo $fire_accident_equipment_detail['equipment_id']; ?>" name="fire_accident_equipment_id[]"><input type="hidden" value="<?php echo $fire_accident_equipment_detail['equipment_detail_id']; ?>" name="fire_accident_equipment_detail_id[]"></td>
					<td><?php echo $fire_accident_equipment_detail['equipment_name']; ?></td>
					<td><textarea class="monthly_analysis_field" rows="1" name="fire_accident_equipment_lokasi[]" required><?php echo $fire_accident_equipment_detail['location']; ?></textarea></td>
					<td><textarea class="monthly_analysis_field" rows="1" name="fire_accident_equipment_jumlah[]" required><?php echo $fire_accident_equipment_detail['total']; ?></textarea></td>
					<td><textarea class="monthly_analysis_field" rows="1" name="fire_accident_equipment_kondisi[]" required><?php echo $fire_accident_equipment_detail['item_condition']; ?></textarea></td>
				</tr>
				<?php $i++; } } ?>
			</table>
			
			  <div class="ln_solid"></div>
			  <div class="form-group">
				<div class="col-md-12" style="text-align:center;">
				  <button id="previous" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/safety/addmonthlyanalysis/id/<?php echo $this->monthly_analysis_id; ?>'">Kembali ke halaman sebelumnya</button>
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

	$(".add-tenant").click(function() {
		var row;
		row = '<tr><td><textarea rows="1" name="fireProtectionTenantName[]" class="textarea-column" required></textarea></td><td><textarea rows="1" name="fireProtectionFloor[]" class="textarea-column" required></textarea></td><td><textarea rows="1" name="fireProtectionProteksiKebakaran[]" class="textarea-column" required></textarea></td><td><textarea rows="1" name="fireProtectionPotensiBahaya[]" class="textarea-column" required></textarea></td><td><textarea rows="1" name="fireProtectionKeterangan[]" class="textarea-column" required></textarea></td><td align="center" style="vertical-align:middle;"><i class="fa fa-trash remove-fire-protection-tenant" style="cursor:pointer;" onclick="$(this).closest(\'tr\').remove();"></i></td></tr>';
		
		$( "#fire-protection-tenant-table").append(row);
	});
});
</script>