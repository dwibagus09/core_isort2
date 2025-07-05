<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<?php if(!empty($this->detailIssues)) { ?>
	<div id="detail-issue-summary">
		<div class="paging">
			<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Issues </div>
			<div class="paging-section">
				<?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
				<?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
				<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
				<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
				<?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
				<?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
			</div>
		</div>
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Date</th>
					<th width="150">Kejadian - Modus</th>
					<th>Detail</th>
					<th width="<?php if($this->cat_id == 3) echo "18%"; else echo "23%"; ?>">Analisa</th>
					<th width="<?php if($this->cat_id == 3) echo "18%"; else echo "23%"; ?>">Rencana &amp; Tindakan</th>
					<?php if($this->cat_id == 3) { ?><th width="18%">Rekomendasi</th><?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php $i = 0; foreach($this->detailIssues as $detailIssue) { 
					$issuedate = explode(" ", $detailIssue['issue_date']);
					$issue_date = date("j M Y", strtotime($issuedate[0]))." ".$issuedate[1];
				?>
				<tr <?php if($i%2 == 1) echo 'style="background-color:#eee;"'; ?> >
					<td><?php echo $issue_date; ?></td>
					<td><?php echo $detailIssue['kejadian']." - ".$detailIssue['modus']; ?></td>
					<td><?php echo nl2br($detailIssue['description']); ?></td>
					<td><?php echo $detailIssue['analisa']; ?></td>
					<td><?php echo $detailIssue['tindakan']; ?></td>
				</tr>
				<?php $i++; } ?>
			</tbody>
		</table>
		<div class="paging">
			<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Issues </div>
			<div class="paging-section">
				<?php if(!empty($this->firstPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->firstPageUrl; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
				<?php if(!empty($this->prevUrl)) { ?><a class="paging-button" data-href="<?php echo $this->prevUrl; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
				<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
				<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
				<?php if(!empty($this->nextUrl)) { ?><a class="paging-button" data-href="<?php echo $this->nextUrl; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
				<?php if(!empty($this->lastPageUrl)) { ?><a class="paging-button" data-href="<?php echo $this->lastPageUrl; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
			</div>
		</div>
	</div>
<?php } ?>
	 
<!-- /page content -->

<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

	$( ".paging-button").click(function() {
        $("body").mLoading();
		var mo = [];
		$('#month option:selected').each(function () {
			if ($(this).val()) {
				mo.push($(this).val());
			}
		});

		var day = [];
		$('#day option:selected').each(function () {
			if ($(this).val()) {
				day.push($(this).val());
			}
		});

		var floor = [];
		$('#floor option:selected').each(function () {
			if ($(this).val()) {
				floor.push($(this).val());
			}
		});

		var tenant_umum = [];
		$('#tenant_umum option:selected').each(function () {
			if ($(this).val()) {
				tenant_umum.push($(this).val());
			}
		});

		var kejadian = [];
		$('#kejadian option:selected').each(function () {
			if ($(this).val()) {
				kejadian.push($(this).val());
			}
		});

		var modus = [];
		$('#modus option:selected').each(function () {
			if ($(this).val()) {
				modus.push($(this).val());
			}
		});

		var time_period = [];
		$('#time_period option:selected').each(function () {
			if ($(this).val()) {
				time_period.push($(this).val());
			}
		});

		$.ajax({
			url: this.dataset.href,
			data: { c : '<?php echo $this->cat_id; ?>',
					year : $('#year option:selected').val(),
					month : mo,
					day : day,
					floor : floor,
					tenant_umum : tenant_umum,
					kejadian : kejadian,
					modus : modus,
					time_period : time_period
			}
		}).done(function(response) {
			$( "#detail-issue-summary").html(response);
            $("body").mLoading('hide');
		});
	});

});	
</script>