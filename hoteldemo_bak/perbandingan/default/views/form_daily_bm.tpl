<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

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

			<form class="form-horizontal form-label-left" action="/default/bm/savereport" method="POST" onsubmit="$('body').mLoading();">
				<input type="hidden" id="report_id" name="report_id" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['report_id']; ?>">
			  <span class="section">DATE / BUILDING</span>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Day / Date
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
				  <?php if(!empty($this->bm['report_date'])) echo $this->bm['report_date']; else echo date("l, F j, Y"); ?>
				</div>
			  </div>
			  <div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="building">Building
				</label>
				<div class="col-md-6 col-sm-6 col-xs-12">
					<select id="building" name="building" class="form-control" required <?php if(!empty($this->bm['building'])) { ?>disabled<?php } ?>>
						<?php if($this->building != '1') { ?><option value="1" <?php if($this->bm['building'] == '1') echo "selected"; ?>>Office Tower</option><?php } ?>
						<?php if($this->building != '2') { ?><option value="2" <?php if($this->bm['building'] == '2') echo "selected"; ?>>Kondominium</option><?php } ?>
					  </select>
					  <?php if(!empty($this->bm['building'])) { ?><input type="hidden" id="building" name="building" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['building']; ?>"><?php } ?>
				</div>
			  </div>
			  
			<span class="section">SUMMARY WO/WR TENANT &amp; INTERNAL</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. WO Internal</legend>
					<table id="bm-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Department</th>
							  <th rowspan="2">No. of Req. WO per Today</th>
							  <th rowspan="2">Completed WO per Today</th>
							  <th rowspan="2">No. of Outstanding WO per Today</th>
							  <th colspan="2">Accumulate</th>
							</tr>
							<tr>
								<th>Previous Outstanding</th>
								<th>Total Outstanding</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Engineering</td>
								<td><input type="text" name="internal_engineering_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_engineering_req_wo']; ?>" required></td>
								<td><input type="text" name="internal_engineering_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_engineering_completed_wo']; ?>" required></td>
								<td><input type="text" name="internal_engineering_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_engineering_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="internal_engineering_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_engineering_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="internal_engineering_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_engineering_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>BS / Civil</td>
								<td><input type="text" name="internal_bs_civil_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_bs_civil_req_wo']; ?>" required></td>
								<td><input type="text" name="internal_bs_civil_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_bs_civil_completed_wo']; ?>" required></td>
								<td><input type="text" name="internal_bs_civil_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_bs_civil_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="internal_bs_civil_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_bs_civil_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="internal_bs_civil_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_bs_civil_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="internal_housekeeping_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_housekeeping_req_wo']; ?>" required></td>
								<td><input type="text" name="internal_housekeeping_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_housekeeping_completed_wo']; ?>" required></td>
								<td><input type="text" name="internal_housekeeping_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_housekeeping_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="internal_housekeeping_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_housekeeping_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="internal_housekeeping_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_housekeeping_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="internal_parking_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_parking_req_wo']; ?>" required></td>
								<td><input type="text" name="internal_parking_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_parking_completed_wo']; ?>" required></td>
								<td><input type="text" name="internal_parking_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_parking_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="internal_parking_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_parking_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="internal_parking_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_parking_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Other</td>
								<td><input type="text" name="internal_other_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_other_req_wo']; ?>" required></td>
								<td><input type="text" name="internal_other_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_other_completed_wo']; ?>" required></td>
								<td><input type="text" name="internal_other_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_other_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="internal_other_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_other_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="internal_other_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['internal_other_total_outstanding']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
				
				<fieldset>
					<legend>B. WO / WR Tenants</legend>
					<table id="bm-table" class="table">
						  <thead>
							<tr>
							  <th rowspan="2">Department</th>
							  <th rowspan="2">No. of Req. WO per Today</th>
							  <th rowspan="2">Completed WO per Today</th>
							  <th rowspan="2">No. of Outstanding WO per Today</th>
							  <th colspan="2">Accumulate</th>
							</tr>
							<tr>
								<th>Previous Outstanding</th>
								<th>Total Outstanding</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Engineering</td>
								<td><input type="text" name="tenant_engineering_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_engineering_req_wo']; ?>" required></td>
								<td><input type="text" name="tenant_engineering_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_engineering_completed_wo']; ?>" required></td>
								<td><input type="text" name="tenant_engineering_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_engineering_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="tenant_engineering_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_engineering_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="tenant_engineering_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_engineering_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>BS / Civil</td>
								<td><input type="text" name="tenant_bs_civil_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_bs_civil_req_wo']; ?>" required></td>
								<td><input type="text" name="tenant_bs_civil_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_bs_civil_completed_wo']; ?>" required></td>
								<td><input type="text" name="tenant_bs_civil_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_bs_civil_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="tenant_bs_civil_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_bs_civil_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="tenant_bs_civil_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_bs_civil_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="tenant_housekeeping_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_housekeeping_req_wo']; ?>" required></td>
								<td><input type="text" name="tenant_housekeeping_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_housekeeping_completed_wo']; ?>" required></td>
								<td><input type="text" name="tenant_housekeeping_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_housekeeping_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="tenant_housekeeping_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_housekeeping_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="tenant_housekeeping_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_housekeeping_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="tenant_parking_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_parking_req_wo']; ?>" required></td>
								<td><input type="text" name="tenant_parking_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_parking_completed_wo']; ?>" required></td>
								<td><input type="text" name="tenant_parking_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_parking_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="tenant_parking_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_parking_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="tenant_parking_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_parking_total_outstanding']; ?>" required></td>
							</tr>
							<tr>
								<td>Other</td>
								<td><input type="text" name="tenant_other_req_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_other_req_wo']; ?>" required></td>
								<td><input type="text" name="tenant_other_completed_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_other_completed_wo']; ?>" required></td>
								<td><input type="text" name="tenant_other_outstanding_wo" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_other_outstanding_wo']; ?>" required></td>
								<td><input type="text" name="tenant_other_prev_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_other_prev_outstanding']; ?>" required></td>
								<td><input type="text" name="tenant_other_total_outstanding" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['tenant_other_total_outstanding']; ?>" required></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
			</div>
			
			<span class="section">JUMLAH PETUGAS</span>
			  <div class="col-md-12 col-xs-12">
				  <fieldset>
					<legend>A. In House</legend>
					<table id="bm-table" class="table">
						  <thead>
							<tr>
							  <th width="120" rowspan="2">Divisi</th>
							  <th colspan="6">Jumlah</th>
							  <th rowspan="2">Keterangan</th>
							</tr>
							<tr>
								<th width="80">Shift 1</th>
								<th width="80">Middle</th>
								<th width="80">Shift 2</th>
								<th width="80">Shift 3</th>
								<th width="80">Off</th>
								<th width="80">Absent</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Engineering</td>
								<td><input type="text" name="inhouse_engineering_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_off']; ?>" required></td>
								<td><input type="text" name="inhouse_engineering_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_engineering_absent']; ?>" required></td>
								<td><textarea name="inhouse_engineering_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['inhouse_engineering_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>BS / Civil</td>
								<td><input type="text" name="inhouse_bs_civil_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_civil_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_civil_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_civil_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_civil_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_off']; ?>" required></td>
								<td><input type="text" name="inhouse_bs_civil_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_bs_civil_absent']; ?>" required></td>
								<td><textarea name="inhouse_bs_civil_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['inhouse_bs_civil_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="inhouse_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_off']; ?>" required></td>
								<td><input type="text" name="inhouse_housekeeping_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_housekeeping_absent']; ?>" required></td>
								<td><textarea name="inhouse_housekeeping_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['inhouse_housekeeping_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="inhouse_parking_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_off']; ?>" required></td>
								<td><input type="text" name="inhouse_parking_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_parking_absent']; ?>" required></td>
								<td><textarea name="inhouse_parking_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['inhouse_parking_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Other</td>
								<td><input type="text" name="inhouse_other_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_shift1']; ?>" required></td>
								<td><input type="text" name="inhouse_other_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_middle']; ?>" required></td>
								<td><input type="text" name="inhouse_other_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_shift2']; ?>" required></td>
								<td><input type="text" name="inhouse_other_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_shift3']; ?>" required></td>
								<td><input type="text" name="inhouse_other_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_off']; ?>" required></td>
								<td><input type="text" name="inhouse_other_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['inhouse_other_absent']; ?>" required></td>
								<td><textarea name="inhouse_other_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['inhouse_other_keterangan']; ?></textarea></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
		
				<fieldset>
					<legend>B. Outsource</legend>
					<table id="bm-table" class="table">
						  <thead>
							<tr>
							  <th width="120" rowspan="2">Divisi</th>
							  <th colspan="6">Jumlah</th>
							  <th rowspan="2">Keterangan</th>
							</tr>
							<tr>
								<th width="80">Shift 1</th>
								<th width="80">Middle</th>
								<th width="80">Shift 2</th>
								<th width="80">Shift 3</th>
								<th width="80">Off</th>
								<th width="80">Absent</th>
							</tr>
						  </thead>
						  <tbody>
							<tr>
								<td>Engineering</td>
								<td><input type="text" name="outsource_engineering_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_engineering_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_middle']; ?>" required></td>
								<td><input type="text" name="outsource_engineering_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_engineering_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_engineering_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_off']; ?>" required></td>
								<td><input type="text" name="outsource_engineering_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_engineering_absent']; ?>" required></td>
								<td><textarea name="outsource_engineering_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['outsource_engineering_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>BS / Civil</td>
								<td><input type="text" name="outsource_bs_civil_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_bs_civil_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_middle']; ?>" required></td>
								<td><input type="text" name="outsource_bs_civil_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_bs_civil_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_bs_civil_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_off']; ?>" required></td>
								<td><input type="text" name="outsource_bs_civil_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_bs_civil_absent']; ?>" required></td>
								<td><textarea name="outsource_bs_civil_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['outsource_bs_civil_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Housekeeping</td>
								<td><input type="text" name="outsource_housekeeping_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_middle']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_off']; ?>" required></td>
								<td><input type="text" name="outsource_housekeeping_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_housekeeping_absent']; ?>" required></td>
								<td><textarea name="outsource_housekeeping_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['outsource_housekeeping_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Parking</td>
								<td><input type="text" name="outsource_parking_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_parking_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_middle']; ?>" required></td>
								<td><input type="text" name="outsource_parking_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_parking_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_parking_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_off']; ?>" required></td>
								<td><input type="text" name="outsource_parking_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_parking_absent']; ?>" required></td>
								<td><textarea name="outsource_parking_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['outsource_parking_keterangan']; ?></textarea></td>
							</tr>
							<tr>
								<td>Other</td>
								<td><input type="text" name="outsource_other_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_shift1']; ?>" required></td>
								<td><input type="text" name="outsource_other_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_middle']; ?>" required></td>
								<td><input type="text" name="outsource_other_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_shift2']; ?>" required></td>
								<td><input type="text" name="outsource_other_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_shift3']; ?>" required></td>
								<td><input type="text" name="outsource_other_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_off']; ?>" required></td>
								<td><input type="text" name="outsource_other_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['outsource_other_absent']; ?>" required></td>
								<td><textarea name="outsource_other_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['outsource_other_keterangan']; ?></textarea></td>
							</tr>
						</tbody>
					 </table>
				</fieldset>
				
				<table id="bm-table" class="table" style="background-color:#EEE;">
						<tr>
							<td width="120"><strong>TOTAL</strong></td>
							<td width="80"><input type="text" name="total_shift1" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_shift1']; ?>" required></td>
							<td width="80"><input type="text" name="total_middle" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_middle']; ?>" required></td>
							<td width="80"><input type="text" name="total_shift2" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_shift2']; ?>" required></td>
							<td width="80"><input type="text" name="total_shift3" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_shift3']; ?>" required></td>
							<td width="80"><input type="text" name="total_off" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_off']; ?>" required></td>
							<td width="80"><input type="text" name="total_absent" class="form-control col-md-7 col-xs-12" value="<?php echo $this->bm['total_absent']; ?>" required></td>
							<td><textarea name="total_keterangan" class="form-control col-md-7 col-xs-12 event-txtarea" style="height:50px;" required><?php echo $this->bm['total_keterangan']; ?></textarea></td>
						</tr>
					 </table>
			</div>
		
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

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	<?php if($this->editMode == 1) { ?>
		$(".edit-bm").css("display", "block");
		$(".edit-bm").addClass('current-page');
		$(".edit-bm").addClass('current-page').parents('ul').slideDown().parent().addClass('active');
	<?php } ?>

	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
		}
	});

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$(".add-utility-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="utility_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $utility['issue_id']; ?>">
					<input type="file" name="utility_img[]">
				</td>
				<td><textarea name="utility_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="utility_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="utility_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="utility_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#utility-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-safety-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="safety_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $safety['issue_id']; ?>">
					<input type="file" name="safety_img[]">
				</td>
				<td><textarea name="safety_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="safety_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="safety_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="safety_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#safety-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-security-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="security_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $security['issue_id']; ?>">
					<input type="file" name="security_img[]">
				</td>
				<td><textarea name="security_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="security_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="security_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="security_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#security-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-housekeeping-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="housekeeping_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $housekeeping['issue_id']; ?>">
					<input type="file" name="housekeeping_img[]">
				</td>
				<td><textarea name="housekeeping_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="housekeeping_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="housekeeping_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="housekeeping_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#housekeeping-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-parking-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="parking_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $parking['issue_id']; ?>">
					<input type="file" name="parking_img[]">
				</td>
				<td><textarea name="parking_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="parking_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="parking_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="parking_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#parking-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-resident-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="resident_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $resident['issue_id']; ?>">
					<input type="file" name="resident_img[]">
				</td>
				<td><textarea name="resident_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="resident_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="resident_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="resident_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#resident-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	
	$(".add-building-service-issue").click(function() {
		var row;
		var table_name;
		
		row = '<tr>
				<td>
					<input type="hidden" name="building_service_issue_id[]" class="form-control col-md-7 col-xs-12" value="<?php echo $building_service['issue_id']; ?>">
					<input type="file" name="building_service_img[]">
				</td>
				<td><textarea name="building_service_location[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="building_service_description[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><textarea name="building_service_status[]" class="form-control col-md-7 col-xs-12 issue-txtarea" style="height:50px;" required></textarea></td>
				<td><input type="text" name="building_service_completion_date[]" class="form-control col-md-7 col-xs-12 datepicker"></td>
				<td><i class="fa fa-trash remove-issue" onclick="$(this).closest(\'tr\').remove();"></i></td>
			</tr>';		
		
		$( "#building-service-table").append(row);
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
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