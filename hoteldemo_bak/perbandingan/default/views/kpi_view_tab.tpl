<style>
.mytooltip {
  color: red !important;
  padding: 5px 20px;
  text-align: center;
  font: bold 14px ;
  text-decoration: none;
}
</style>

<table class="table" style="margin-bottom:0px;">
	<thead>
		<tr>
			<th colspan="3">TARGET PENCAPAIAN KINERJA</th>
			<th width="200">Indikator Pengukuran Keberhasilan Kinerja</th>
			<th width="90">Bobot (%)</th>
			<th width="125" style="line-height:11px;">Rating<br/><span style="font-size:9px; font-weight:normal;">0 = 0% - 0.9%  dari target<br/>1 =  1% - 70% dari target<br/>2 = 71% - 99% dari target<br/>3 = 100% dari target</span></th>
			<th width="100">Nilai<br/><span style="font-size:9px;">(Bobot x Rating)</span></th>
		</tr>
	</thead>
</table>
<div id="data-kpi" <?php if(!empty($this->height)) { echo 'style="height:'.$this->height.'px;"'; } ?>>
<table class="table">
	<tbody>
		<?php if(!empty($this->kpi)) { 
			$module_id = 0;
			$target_id = 0;
			$skipTotal = 0;
			$totalBobotPresentase = 0;
			$summaryTotalNilai = 0;
			$summaryTotalCapaian = 0;
			$s = 0;
			$k=0;
			$i = 1;
			$mdl_ctr = "B";
			foreach($this->kpi as $kpi) { 
				if($this->curTab != "chief" && $k == 0)
				{
				?>
					<tr>
					<td colspan="3"><strong><?php echo $mdl_ctr.". ". strtoupper($this->moduleName); ?></strong></td>
					<td width="200"></td>
					<td width="90"></td>
					<td width="125"></td>
					<td width="83"></td>
					</tr>
				<?php $mdl_ctr++;
				}
				if($module_id != $kpi['action_plan_module_id'] && $this->curTab == "chief")	{ 
					if($module_id > 0)
					{
						$totalBobotPresentase += $this->kpi[$k-1][$this->curTab."_bobot"]; 
						$totalNilaiCapaian += $totalNilai;
						$totalNilaiPresentase = (($totalBobotPresentase*3)/100);
						$totalBobotCapaian = (($totalNilaiCapaian/$totalNilaiPresentase)*$totalBobotPresentase);
					
						if(!empty($this->achievementCategoryModule[$module_id]))
						{
							foreach($this->achievementCategoryModule[$module_id] as $acm) {
								if($acm['start_range'] <= $totalNilaiCapaian)
								{
									$kategoriCapaianKinerjaModul = $acm['description'];
									break;
								}
							}	
						}
					?>
					<tr style="background-color:#ffd03f; color:black;">
						<td colspan="4" align="right"><strong>TOTAL</strong></td>
						<td align="center"><strong><?php echo $this->kpi[$k-1][$this->curTab."_bobot"]; ?>%</strong></td>
						<td></td>
						<td align="center"><strong><?php echo $totalNilai; ?></strong></td>
					</tr>
					<tr style="color:black; font-size:14px; font-weight:bold;">
						<td colspan="4" align="right"><strong>Total Bobot presentase</strong></td>
						<td align="center"><strong><?php echo $totalBobotPresentase; ?>%</strong></td>
						<td></td>
						<td align="center"><strong><?php echo $totalNilaiPresentase; ?></strong></td>
					</tr>
					<tr style="background-color:#3dcbff; color:black; font-size:14px; font-weight:bold;">
						<td colspan="4" align="right"><strong>Hasil Capaian</strong></td>
						<td align="center"><strong><?php echo round($totalBobotCapaian,2); ?>%</strong></td>
						<td><strong><?php echo $kategoriCapaianKinerjaModul; ?></strong></td>
						<td align="center"><strong><?php echo $totalNilaiCapaian; ?></strong></td>
					</tr>
					<tr>
						<td colspan="7" style="height:30px; border-left:none; border-right:none;"></td>
					</tr>
					<?php 
						$summary[$s]['total'] = $totalNilaiCapaian;
						$summaryTotalNilai += $totalNilaiCapaian;
						$summaryTotalCapaian += $totalBobotCapaian;
						$totalBobot = 0;
						$totalNilai = 0;
						$skipTotal = 1;
						$totalBobotPresentase = 0;
						$totalNilaiCapaian = 0;
						$s++;
					}
					$totalBobot = 0;
					$totalNilai = 0;
					$module_id = $kpi['action_plan_module_id'];
					$i = 1; 

					$summary[$s]['module_name'] = $kpi['module_name'];
				if($this->curTab == "chief") {
			?>
				<tr>
					<td colspan="3"><strong><?php echo strtoupper($kpi['module_name']); ?></strong></td>
					<td width="200"></td>
					<td width="90"></td>
					<td width="125"></td>
					<td width="83"></td>
				</tr>
			<?php } $mdl_ctr++;
			 }  
				if($target_id != $kpi['action_plan_target_id']) {
					if($target_id > 0 && empty($skipTotal))
					{ ?>
					<tr style="background-color:#ffd03f; color:black; font-weight:bold;">
						<td colspan="4" align="right">TOTAL</td>
						<td align="center"><?php echo $this->kpi[$k-1][$this->curTab."_bobot"]; ?>%</td>
						<td></td>
						<td align="center" <?php if($this->kpi[$k-1]['kpi_only'] == "1") { echo 'id="'.$this->curTab.'total_b1"'; } ?>><?php echo $totalNilai; ?></td>
					</tr>
					<?php
						$totalBobotPresentase += $this->kpi[$k-1][$this->curTab."_bobot"];
						$totalNilaiCapaian += $totalNilai;
					}
					$totalBobot = 0;
					$totalNilai = 0;
					$skipTotal = 0;
					$target_id = $kpi['action_plan_target_id'];
					$j = 1; 
					if($this->kpi[$k-1]["target_sort_order"] == $this->kpi[$k]["target_sort_order"]) $target_no = "";
					else $target_no = $i;
					?>
						<tr>
							<td width="40" align="center"><?php echo $target_no; ?></td>
							<td colspan="2"><strong><?php echo $kpi['target_name']; ?></strong></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr>
			<?php if($this->kpi[$k-1]["target_sort_order"] != $this->kpi[$k]["target_sort_order"]) $i++;  } 
				if(empty($kpi['use_activity_bobot'])) $bobot = $this->activityBobot[$kpi['action_plan_target_id']];
				else $bobot = $kpi['activity_'.$this->curTab.'_bobot'];

				$nilai = round((($bobot*$kpi['rating'])/100),2);

				$tooltip = "Delegated to: ";
				if($kpi['chief'] == 1) $tooltip .= "Chief, "; 
				if($kpi['spv'] == 1) $tooltip .= "Spv, "; 
				if($kpi['staff'] == 1) $tooltip .= "Staff, "; 
				if($kpi['admin'] == 1) $tooltip .= "Admin, "; 
				$tooltip = substr($tooltip, 0, -2);
			?>
					<tr>
						<td></td>
						<td width="40" align="center"><?php echo $j; ?></td>
						<td><div title="<?php echo $tooltip; ?>"><?php echo $kpi['activity_name']; ?></div></td>
						<td><?php echo $kpi['document_as_approve']; ?></td>
						<td align="center"><?php echo $bobot; ?>%</td>
						<td align="center"><?php if($kpi['kpi_only'] == '1') { ?><input type="text" name="<?php echo $this->curTab.'_'.$kpi['action_plan_activity_id']; ?>" id="<?php echo $this->curTab.'_'.$kpi['action_plan_activity_id']; ?>" class="b1" data-id="<?php echo $kpi['action_plan_activity_id']; ?>" data-bobot="<?php echo $bobot; ?>" style="width:100px; text-align:center;" value="<?php echo $kpi['rating']; ?>" <?php if(!$this->allowFillSpvStaffAdmin) { echo "disabled"; } ?>><?php } else echo $kpi['rating']; ?></td>
						<td align="center" <?php if($kpi['kpi_only'] == '1') { ?>id="<?php echo $this->curTab; ?>nilai<?php echo $kpi['action_plan_activity_id']; ?>" class="<?php echo $this->curTab; ?>nilaib1"<?php } ?>><?php echo $nilai; ?></td>
					</tr>
			<?php 	$totalBobot += $kpi['bobot']; 
					$totalNilai += $nilai; 
					$j++;
					$k++; 
				}
			} 
			$totalBobotPresentase += $kpi[$this->curTab.'_bobot']; 
			$totalNilaiCapaian += $totalNilai;
			$totalNilaiPresentase = (($totalBobotPresentase*3)/100);
			$totalBobotCapaian = (($totalNilaiCapaian/$totalNilaiPresentase)*$totalBobotPresentase);
			$summary[$s]['total'] = $totalNilaiCapaian;
			$summaryTotalNilai += $totalNilaiCapaian;
			$summaryTotalCapaian += $totalBobotCapaian;

			if(2.4 == $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target terpenuhi';
			elseif(1.7 <= $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target hampir terpenuhi';
			elseif(0.1 <= $totalNilaiCapaian)	$kategoriCapaianKinerjaModul = 'Target kurang terpenuhi';
			else $kategoriCapaianKinerjaModul = 'Target tidak terpenuhi';

			$nilaic13 =  round(($this->kpi_c_section['c13']*9/100),2);
			$nilaic21 =  round(($this->kpi_c_section['c21']*1/100),2);
			$nilaic22 =  round(($this->kpi_c_section['c22']*1/100),2);								
			$nilaic23 =  round(($this->kpi_c_section['c23']*1/100),2);
			$nilaiFirst6Month = round(($this->first6MonthScore*4/100),2);
			$nilaiSecond6Month = round(($this->second6MonthScore*4/100),2);
			$totalNilaiC1 = $nilaiFirst6Month + $nilaiSecond6Month + $nilaic13;
			$totalNilaiC2 = $nilaic21 +  $nilaic22 + $nilaic23;
			$totalNilaiCapaianC = $totalNilaiC1 + $totalNilaiC2;
			$totalBobotCapaianC = ($totalNilaiCapaianC/0.6)*20;
			if(0.6 == $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target terpenuhi';
			elseif(0.43 <= $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target hampir terpenuhi';
			elseif(0.1 <= $totalNilaiCapaianC)	$kategoriCapaianKinerjaModulC = 'Target kurang terpenuhi';
			else $kategoriCapaianKinerjaModulC = 'Target tidak terpenuhi';
			?>
			<tr style="background-color:#ffd03f; color:black;">
				<td colspan="4" align="right"><strong>TOTAL</strong></td>
				<td align="center"><strong><?php echo $kpi[$this->curTab.'_bobot']; ?>%</strong></td>
				<td></td>
				<td align="center"><strong><?php echo $totalNilai; ?></strong></td>
			</tr>
			<tr style="color:black; font-size:14px; font-weight:bold;">
				<td colspan="4" align="right"><strong>Total Bobot presentase</strong></td>
				<td align="center"><strong><?php echo $totalBobotPresentase; ?>%</strong></td>
				<td></td>
				<td align="center"><strong><?php echo $totalNilaiPresentase; ?></strong></td>
			</tr>
			<tr style="background-color:#3dcbff; color:black; font-size:14px; font-weight:bold;">
				<td colspan="4" align="right"><strong>Hasil Capaian</strong></td>
				<td align="center"><strong><?php echo round($totalBobotCapaian,2); ?>%</strong></td>
				<td><strong><?php echo $kategoriCapaianKinerjaModul; ?></strong></td>
				<td align="center"><strong><?php echo $totalNilaiCapaian; ?></strong></td>
			</tr>

			<tr>
				<td colspan="7" style="height:30px; border-left:none; border-right:none;"></td>
			</tr>

			<tr>
				<td colspan="3"><strong><?php echo $mdl_ctr. ". KEPATUHAN PADA STANDAR KERJA DAN KEPRIBADIAN"; ?></strong></td>
				<td width="200"></td>
				<td width="90"></td>
				<td width="125"></td>
				<td width="83"></td>
			</tr>
			<tr>
				<td width="40" align="center">1</td>
				<td colspan="2"><strong>Kepatuhan dan konsistensi terhadap Rencana kerja dan perusahaan serta kualitas Kerja</strong></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">1</td>
				<td>Kualitas Implementasi Action Plan Enam Bulan pertama</td>
				<td></td>
				<td align="center">4%</td>
				<td align="center"><?php echo $this->first6MonthScore; ?></td>
									<td align="center" id="nilaiFirst6Month"><?php echo $nilaiFirst6Month; ?></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">2</td>
				<td>Kualitas Implementasi Action Plan Enam Bulan kedua</td>
				<td></td>
				<td align="center">4%</td>
				<td align="center"><?php echo $this->second6MonthScore; ?></td>
									<td align="center" id="nilaiSecond6Month"><?php echo $nilaiSecond6Month; ?></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">3</td>
				<td>Hasil Audit Security tahunan</td>
				<td></td>
				<td align="center">9%</td>
						<td align="center"><input type="text" name="<?php echo $this->curTab; ?>c13" id="<?php echo $this->curTab; ?>c13" style="width:100px; text-align:center;" value="<?php echo $this->kpi_c_section['c13']; ?>" <?php if(!$this->allowFillSpvStaffAdmin) { echo "disabled"; } ?>></td>
						<td align="center" id="<?php echo $this->curTab; ?>nilaic13"><?php echo $nilaic13; ?></td>
				</tr>
					<tr style="background-color:#ffd03f; color:black; font-weight:bold;">
						<td colspan="4" align="right">TOTAL</td>
				<td align="center"><strong>17%</strong></td>
				<td></td>
									<td align="center" id="<?php echo $this->curTab; ?>totalNilaiC1"><?php echo $totalNilaiC1; ?></td>
			</tr>
			<tr>
				<td width="40" align="center">2</td>
				<td colspan="2"><strong>Kepribadian</strong></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">1</td>
				<td>Integritas (Integrity)</td>
				<td></td>
				<td align="center">1%</td>
									<td align="center"><input type="text" name="<?php echo $this->curTab; ?>c21" id="<?php echo $this->curTab; ?>c21" style="width:100px; text-align:center;" value="<?php echo $this->kpi_c_section['c21']; ?>" <?php if(!$this->allowFillSpvStaffAdmin) { echo "disabled"; } ?>></td>
									<td align="center" id="<?php echo $this->curTab; ?>nilaic21"><?php echo $nilaic21; ?></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">2</td>
				<td>Disipline Kehadiran (Attendance)</td>
				<td></td>
				<td align="center">1%</td>
									<td align="center"><input type="text" name="<?php echo $this->curTab; ?>c22" id="<?php echo $this->curTab; ?>c22" style="width:100px; text-align:center;" value="<?php echo $this->kpi_c_section['c22']; ?>" <?php if(!$this->allowFillSpvStaffAdmin) { echo "disabled"; } ?>></td>
									<td align="center" id="<?php echo $this->curTab; ?>nilaic22"><?php echo $nilaic22; ?></td>
			</tr>
			<tr>
				<td></td>
				<td width="40" align="center">3</td>
				<td>Kerjasama (Team Work)</td>
				<td></td>
				<td align="center">1%</td>
									<td align="center"><input type="text" name="<?php echo $this->curTab; ?>c23" id="<?php echo $this->curTab; ?>c23" style="width:100px; text-align:center;" value="<?php echo $this->kpi_c_section['c23']; ?>" <?php if(!$this->allowFillSpvStaffAdmin) { echo "disabled"; } ?>></td>
									<td align="center" id="<?php echo $this->curTab; ?>nilaic23"><?php echo $nilaic23; ?></td>
			</tr>
								<tr style="background-color:#ffd03f; color:black; font-weight:bold;">
									<td colspan="4" align="right">TOTAL</td>
									<td align="center">3%</td>
				<td></td>
									<td align="center" id="<?php echo $this->curTab; ?>totalNilaiC2"><?php echo $totalNilaiC2; ?></td>
			</tr>
			<tr style="color:black; font-size:14px; font-weight:bold;">
				<td colspan="4" align="right"><strong>Total Bobot presentase</strong></td>
				<td align="center"><strong>20%</strong></td>
				<td></td>
				<td align="center"><strong>0.6</strong></td>
			</tr>
			<tr style="background-color:#3dcbff; color:black; font-size:14px; font-weight:bold;">
				<td colspan="4" align="right">Hasil Capaian</td>
				<td align="center" id="<?php echo $this->curTab; ?>totalBobotCapaianC"><?php echo round($totalBobotCapaianC,2); ?>%</td>
				<td><?php echo $kategoriCapaianKinerjaModulC; ?></td>
				<td align="center" id="<?php echo $this->curTab; ?>totalNilaiCapaianC"><?php echo $totalNilaiCapaianC; ?></td>
			</tr>
	</tbody>
</table>

<table class="table" style="margin-bottom:0px;">
	<thead>
		<tr>
			<th colspan="3">KESIMPULAN AKHIR</th>
			<th width="200">NILAI</th>
			<th width="90">TOTAL NILAI</th>
			<th width="125">TOTAL % CAPAIAN</th>
			<th width="83">KATEGORI CAPAIAN KINERJA</th>
		</tr>
	</thead>
	<tbody>
		<?php if(!empty($summary)) { 
			$z = 0;
			foreach($summary as $sum) {	
		?>
			<tr>
				<td colspan="3"><?php echo strtoupper($this->moduleName); ?></td>
				<td align="center"><?php echo $sum['total']; ?></td>
			<?php if($z == 0) { 
					$summaryTotalCapaianBeforeC = $summaryTotalCapaian;
					$summaryTotalCapaian = $summaryTotalCapaian + round($totalBobotCapaianC,2);
					$summaryTotalNilaiBeforeC = $summaryTotalNilai;
					$summaryTotalNilai = $summaryTotalNilai + $totalNilaiCapaianC;
					$summaryTotalNilai = round($summaryTotalNilai,2);
					foreach($this->achievementCategory as $ac) {
						if($ac['start_range'] <= $summaryTotalNilai)
						{
							$kategoriCapaianKinerja = $ac['description'];
							break;
						}
					}	
			?>
					<td align="center" valign="middle"  id="<?php echo $this->curTab; ?>summaryTotalNilai" rowspan="<?php echo count($summary)+1; ?>" style="font-size:14px; font-weight:bold;"><?php echo $summaryTotalNilai; ?></td>
					<td align="center" valign="middle"  id="<?php echo $this->curTab; ?>summaryTotalCapaian" rowspan="<?php echo count($summary)+1; ?>" style="font-size:14px; font-weight:bold;"><?php echo round($summaryTotalCapaian,2); ?>%</td>
					<td align="center" rowspan="<?php echo count($summary)+1; ?>" style="font-size:14px; font-weight:bold;"><?php echo $kategoriCapaianKinerja; ?></td>
			<?php } ?>
			</tr>
		<?php $z++; } } ?>
		<tr>
			<td colspan="3">Kepatuhan Pada Standar Kerja Dan Kepribadian </td>
			<td align="center" id="<?php echo $this->curTab; ?>summaryTotalNilaiCapaianC"><?php echo $totalNilaiCapaianC; ?></td>
		</tr>
	</tbody>
</table>
</div>

<link rel="stylesheet" href="/css/jquery-ui.min.css">
<script src="/js/jquery-ui.min.js"></script>					
<script type="text/javascript">
$(document).ready(function() {
	$("#data-kpi").height($( window ).height()-312);

	$( document ).tooltip({
		tooltipClass: "mytooltip"
	});

	/*$("#<?php echo $this->curTab; ?>c13").keyup(function() {
		var nilaic13 = (this.value)*9/100;
		$("#<?php echo $this->curTab; ?>nilaic13").html(nilaic13);
		var totalNilaiC1 = parseFloat(nilaic13) + parseFloat(<?php echo $nilaiFirst6Month; ?>) + parseFloat(<?php echo $nilaiSecond6Month; ?>);
		$("#<?php echo $this->curTab; ?>totalNilaiC1").html(totalNilaiC1.toFixed(2));
		var totalNilaiCapaianC = parseFloat(totalNilaiC1) + parseFloat($("#<?php echo $this->curTab; ?>totalNilaiC2").html());
		$("#<?php echo $this->curTab; ?>totalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));	
		var totalBobotCapaianC = (parseFloat(totalNilaiCapaianC)/0.6)*20;	
		$("#<?php echo $this->curTab; ?>totalBobotCapaianC").html(parseFloat(totalBobotCapaianC).toFixed(2)+"%");
		$("#<?php echo $this->curTab; ?>summaryTotalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));
		var summaryTotalNilai = parseFloat(<?php echo $summaryTotalNilaiBeforeC; ?>) + parseFloat(totalNilaiCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalNilai").html(parseFloat(summaryTotalNilai).toFixed(2));
		var summaryTotalCapaian = parseFloat(<?php echo $summaryTotalCapaianBeforeC; ?>) + parseFloat(totalBobotCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalCapaian").html(parseFloat(summaryTotalCapaian).toFixed(2)+"%");
	});

	$("#<?php echo $this->curTab; ?>c21").keyup(function() {
		var nilaic21 = (this.value)*1/100;
		$("#<?php echo $this->curTab; ?>nilaic21").html(nilaic21);
		var totalNilaiC2 =  parseFloat(nilaic21) + parseFloat($("#<?php echo $this->curTab; ?>nilaic22").html()) + parseFloat($("#<?php echo $this->curTab; ?>nilaic23").html());
		$("#<?php echo $this->curTab; ?>totalNilaiC2").html(parseFloat(totalNilaiC2).toFixed(2));
		var totalNilaiCapaianC = parseFloat(totalNilaiC2) + parseFloat($("#<?php echo $this->curTab; ?>totalNilaiC1").html());
		$("#<?php echo $this->curTab; ?>totalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));	
		var totalBobotCapaianC = (parseFloat(totalNilaiCapaianC)/0.6)*20;	
		$("#<?php echo $this->curTab; ?>totalBobotCapaianC").html(parseFloat(totalBobotCapaianC).toFixed(2)+"%");	
		$("#<?php echo $this->curTab; ?>summaryTotalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));		
		var summaryTotalNilai = parseFloat(<?php echo $summaryTotalNilaiBeforeC; ?>) + parseFloat(totalNilaiCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalNilai").html(parseFloat(summaryTotalNilai).toFixed(2));
		var summaryTotalCapaian = parseFloat(<?php echo $summaryTotalCapaianBeforeC; ?>) + parseFloat(totalBobotCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalCapaian").html(parseFloat(summaryTotalCapaian).toFixed(2)+"%");
	});

	$("#<?php echo $this->curTab; ?>c22").keyup(function() {
		var nilaic22 = (this.value)*1/100;
		$("#<?php echo $this->curTab; ?>nilaic22").html(nilaic22);
		var totalNilaiC2 =  parseFloat(nilaic22) + parseFloat($("#<?php echo $this->curTab; ?>nilaic21").html()) + parseFloat($("#<?php echo $this->curTab; ?>nilaic23").html());
		$("#<?php echo $this->curTab; ?>totalNilaiC2").html(parseFloat(totalNilaiC2).toFixed(2));
		var totalNilaiCapaianC = parseFloat(totalNilaiC2) + parseFloat($("#<?php echo $this->curTab; ?>totalNilaiC1").html());
		$("#<?php echo $this->curTab; ?>totalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));	
		var totalBobotCapaianC = (parseFloat(totalNilaiCapaianC)/0.6)*20;	
		$("#<?php echo $this->curTab; ?>totalBobotCapaianC").html(parseFloat(totalBobotCapaianC).toFixed(2)+"%");		
		$("#<?php echo $this->curTab; ?>summaryTotalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));
		var summaryTotalNilai = parseFloat(<?php echo $summaryTotalNilaiBeforeC; ?>) + parseFloat(totalNilaiCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalNilai").html(parseFloat(summaryTotalNilai).toFixed(2));
		var summaryTotalCapaian = parseFloat(<?php echo $summaryTotalCapaianBeforeC; ?>) + parseFloat(totalBobotCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalCapaian").html(parseFloat(summaryTotalCapaian).toFixed(2)+"%");	
	});

	$("#<?php echo $this->curTab; ?>c23").keyup(function() {
		var nilaic23 = (this.value)*1/100;
		$("#<?php echo $this->curTab; ?>nilaic23").html(nilaic23);
		var totalNilaiC2 =  parseFloat(nilaic23) + parseFloat($("#<?php echo $this->curTab; ?>nilaic22").html()) + parseFloat($("#<?php echo $this->curTab; ?>nilaic21").html());
		$("#<?php echo $this->curTab; ?>totalNilaiC2").html(parseFloat(totalNilaiC2).toFixed(2));
		var totalNilaiCapaianC = parseFloat(totalNilaiC2) + parseFloat($("#<?php echo $this->curTab; ?>totalNilaiC1").html());
		$("#<?php echo $this->curTab; ?>totalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));		
		var totalBobotCapaianC = (parseFloat(totalNilaiCapaianC)/0.6)*20;	
		$("#<?php echo $this->curTab; ?>totalBobotCapaianC").html(parseFloat(totalBobotCapaianC).toFixed(2)+"%");	
		$("#<?php echo $this->curTab; ?>summaryTotalNilaiCapaianC").html(parseFloat(totalNilaiCapaianC).toFixed(2));
		var summaryTotalNilai = parseFloat(<?php echo $summaryTotalNilaiBeforeC; ?>) + parseFloat(totalNilaiCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalNilai").html(parseFloat(summaryTotalNilai).toFixed(2));
		var summaryTotalCapaian = parseFloat(<?php echo $summaryTotalCapaianBeforeC; ?>) + parseFloat(totalBobotCapaianC);
		$("#<?php echo $this->curTab; ?>summaryTotalCapaian").html(parseFloat(summaryTotalCapaian).toFixed(2)+"%");	
	});

	$(".b1").keyup(function() {
		var value = parseFloat(this.value);
		var bobot = parseFloat(this.dataset.bobot);
		var nilai = (value*bobot)/100;
		$("#<?php echo $this->curTab; ?>nilai"+this.dataset.id).html(nilai.toFixed(2));
		var totalNilai = 0;
		$(".<?php echo $this->curTab; ?>nilaib1").each(function () {                  
			totalNilai = parseFloat(totalNilai) + parseFloat($(this).html()); 
		});
		$("#<?php echo $this->curTab; ?>total_b1").html(totalNilai.toFixed(2));
	});*/
});
</script>