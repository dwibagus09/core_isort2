<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/js/FullWidthTabs/css/component.css" />

  <div class="detail-report">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
		  <div class="x_title">
			<h2>PERFORMANCE APPRAISAL <?php echo strtoupper($this->category['category_name']); ?> DEPARTMENT - <?php echo $this->selectedYear; ?></h2>
			<div class="clearfix"></div>			
			<?php /*<a href="/default/safety/downloadsafetyreport/id/<?php echo $this->safety['report_id']; ?>" style="float:right;"><img src="/images/newlogo_pdf.png" width="24"></a> */ ?>
			<h3><?php echo $this->ident['site_fullname']; ?></h3>
		  </div>
		  <div class="x_content">
			<div id="tabs" class="tabs">
				<nav>
					<ul>
						<li id="section1" class="tab tab-current" data-id="1"><a href="#section-1"><span>Chief</span></a></li>
						<li id="section2" class="tab" data-id="2"><a href="#section-2"><span>Spv</span></a></li>
						<li id="section3" class="tab" data-id="3"><a href="#section-3"><span>Staff</span></a></li>
						<li id="section4" class="tab" data-id="4"><a href="#section-4"><span>Admin</span></a></li>
					</ul>
				</nav>
				<div class="content">
					<section id="section-1" class="content-current">
						<table class="table">
							<thead>
								<tr>
									<th colspan="3">TARGET PENCAPAIAN KINERJA</th>
									<th>Indikator Pengukuran Keberhasilan Kinerja</th>
									<th>Bobot (%)</th>
									<th width="125">Rating<br/><span style="font-size:9px; font-weight:normal;">0 = 0% - 0.9%  dari target<br/>1 =  1% - 70% dari target<br/>2 = 71% - 99% dari target<br/>3 = 100% dari target</span></th>
									<th width="100">Nilai<br/><span style="font-size:9px;">(Bobot x Rating)</span></th>
								</tr>
							</thead>
							<tbody>
							<?php if(!empty($this->schedule)) {
								foreach($this->schedule as $schedule) { ?>
									<tr>
										<td colspan="3"><strong><?php echo strtoupper($schedule['module_name']); ?></strong></td>
										<td></td>
										<td></td>
										<td></td>
										<td></td>
									</tr>
									<?php if(!empty($schedule['target'])) {
										$i = 1;
										foreach($schedule['target'] as $target) { ?>
											<tr>
												<td width="40" align="center"><?php echo $i; ?></td>
												<td colspan="2"><strong><?php echo $target['target_name']; ?></strong></td>
												<td></td>
												<td></td>
												<td></td>
												<td></td>
											</tr>
											<?php if(!empty($target['activity'])) {
												$j = 1;
												foreach($target['activity'] as $activity) { ?>
													<tr>
														<td></td>
														<td width="40" align="center"><?php echo $j; ?></td>
														<td><?php echo $activity['activity_name']; ?></td>
														<td></td>
														<td></td>
														<td></td>
														<td></td>
													</tr>

									<?php 	$j++; } } 
											$i++; 
										}
									}
								}
							} ?>
							</tbody>
						</table>
					</section>
					<section id="section-2">
						
					</section>
					<section id="section-3">
						
					</section>
					<section id="section-4">
						
					</section>
				</div><!-- /content -->
			</div><!-- /tabs -->
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
<script src="/js/FullWidthTabs/js/cbpFWTabs.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	new CBPFWTabs( document.getElementById( 'tabs' ) );

	/*$("body").mLoading();
	$( "#section1" ).addClass( "tab" );

	$.ajax({
		async : true,
		url: "/default/issue/showissuesbycategory",
		data: { category : selCatId,
				issue_id : '<?php echo $this->issue_id; ?>',
				start_date : '<?php echo $this->start_date; ?>',
				end_date : '<?php echo $this->end_date; ?>'
		}
	}).done(function(response) {
		$( "#section-"+selCatId ).html(response);
		$( "#section1" ).removeClass( "tab-current" );
		$( "#section-1" ).addClass( "" );
		$( "#section"+selCatId ).addClass( "tab-current" );
		$( "#section-"+selCatId ).addClass( "content-current" );
		$("body").mLoading('hide');
	});*/

	$('.tab').click(function() {
		$("body").mLoading();
		var cat_id = this.dataset.id;
		$.ajax({
			async : true,
			url: "/default/issue/showissuesbycategory",
			data: { category : cat_id,
					issue_id : '<?php echo $this->issue_id; ?>',
					start_date : '<?php echo $this->start_date; ?>',
					end_date : '<?php echo $this->end_date; ?>'
			}
		}).done(function(response) {
			$( "#section"+selCatId ).removeClass( "tab-current" );
			$( "#section-"+selCatId ).removeClass( "content-current" );
			$( "#section-"+cat_id).html(response);
			$( "#section"+cat_id ).addClass( "tab-current" );
			$( "#section-"+cat_id ).addClass( "content-current" );
			$("body").mLoading('hide');
		});
	});
});
</script>