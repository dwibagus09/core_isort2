<?php
	if(!empty($this->fitOutOnGoing))
	{
?>
	<span class="section">Fit Out On Going</span>
	<div class="meeting-table">
	<table class="table table-striped">
	<thead>
		<tr>
		<th>Shop Name</th>
		<th>Floor</th>
		<th>Unit No</th>						  
		<th>Unit Type</th>
		<th>Actual HO Date</th>
		<th>Opening Date</th>
		<th>Period</th>
		<th>FO PIC</th>
		<th>Progress</th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 1;
		foreach($this->fitOutOnGoing as $fitOutOnGoing) { 
	?>
	<tr>
	<td><?php echo $fitOutOnGoing['6']; ?></td>
	<td align="center"><?php echo $fitOutOnGoing['3']; ?></td>
	<td align="center"><?php echo $fitOutOnGoing['4']; ?></td>
	<td align="center"><?php echo $fitOutOnGoing['5']; ?></td>
	<td align="center"><?php echo $fitOutOnGoing['7']; ?></td>
	<td align="center"><?php echo $fitOutOnGoing['57']; ?></td>
	<td align="center"><?php echo intval($fitOutOnGoing['8']); ?> Week(s)</td>
	<td align="center"><?php echo $fitOutOnGoing['10']; ?></td>
	<td align="center"><?php
		if(!empty($fitOutOnGoing['27']) || !empty($fitOutOnGoing['28']) || !empty($fitOutOnGoing['29']) || !empty($fitOutOnGoing['30']) || !empty($fitOutOnGoing['31']) || !empty($fitOutOnGoing['32']) || !empty($fitOutOnGoing['33'])  || !empty($fitOutOnGoing['34'])  || !empty($fitOutOnGoing['35'])  || !empty($fitOutOnGoing['36'])  || !empty($fitOutOnGoing['37'])  || !empty($fitOutOnGoing['38'])  || !empty($fitOutOnGoing['39'])  || !empty($fitOutOnGoing['40'])  || !empty($fitOutOnGoing['41']))
		{
			$progress = '<h4 style="border-bottom:1px solid;">'.$fitOutOnGoing['6'].'</h4><div class="fo-progress-detail">';
			if(!empty($fitOutOnGoing['27'])) $progress .= "<p><strong>Floor: ".$fitOutOnGoing['27']."% - ".$fitOutOnGoing['42'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['12']).'"><img src="'.$fitOutOnGoing['12'].'" /></a></p>';
			if(!empty($fitOutOnGoing['28'])) $progress .= "<p><strong>Wall: ".$fitOutOnGoing['28']."% - ".$fitOutOnGoing['43'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['13']).'"><img src="'.$fitOutOnGoing['13'].'" /></a></p>';
			if(!empty($fitOutOnGoing['29'])) $progress .= "<p><strong>Ceiling: ".$fitOutOnGoing['29']."% - ".$fitOutOnGoing['44'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['14']).'"><img src="'.$fitOutOnGoing['14'].'" /></a></p>';
			if(!empty($fitOutOnGoing['30'])) $progress .= "<p><strong>Shopfront: ".$fitOutOnGoing['30']."% - ".$fitOutOnGoing['45'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['15']).'"><img src="'.$fitOutOnGoing['15'].'" /></a></p>';
			if(!empty($fitOutOnGoing['31'])) $progress .= "<p><strong>Fixture: ".$fitOutOnGoing['31']."% - ".$fitOutOnGoing['46'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['16']).'"><img src="'.$fitOutOnGoing['16'].'" /></a></p>';
			if(!empty($fitOutOnGoing['32'])) $progress .= "<p><strong>Electrical: ".$fitOutOnGoing['32']."% - ".$fitOutOnGoing['47'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['17']).'"><img src="'.$fitOutOnGoing['17'].'" /></a></p>';
			if(!empty($fitOutOnGoing['33'])) $progress .= "<p><strong>Air Conditioning: ".$fitOutOnGoing['33']."% - ".$fitOutOnGoing['48'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['18']).'"><img src="'.$fitOutOnGoing['18'].'" /></a></p>';
			if(!empty($fitOutOnGoing['34'])) $progress .= "<p><strong>Exhaust: ".$fitOutOnGoing['34']."% - ".$fitOutOnGoing['49'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['19']).'"><img src="'.$fitOutOnGoing['19'].'" /></a></p>';
			if(!empty($fitOutOnGoing['35'])) $progress .= "<p><strong>Fresh Air: ".$fitOutOnGoing['35']."% - ".$fitOutOnGoing['50'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['20']).'"><img src="'.$fitOutOnGoing['20'].'" /></a></p>';
			if(!empty($fitOutOnGoing['36'])) $progress .= "<p><strong>Clean Water: ".$fitOutOnGoing['36']."% - ".$fitOutOnGoing['51'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['21']).'"><img src="'.$fitOutOnGoing['21'].'" /></a></p>';
			if(!empty($fitOutOnGoing['37'])) $progress .= "<p><strong>Waste Water: ".$fitOutOnGoing['37']."% - ".$fitOutOnGoing['52'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['22']).'"><img src="'.$fitOutOnGoing['22'].'" /></a></p>';
			if(!empty($fitOutOnGoing['38'])) $progress .= "<p><strong>Gas: ".$fitOutOnGoing['38']."% - ".$fitOutOnGoing['53'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['23']).'"><img src="'.$fitOutOnGoing['23'].'" /></a></p>';
			if(!empty($fitOutOnGoing['39'])) $progress .= "<p><strong>Sprinkler: ".$fitOutOnGoing['39']."% - ".$fitOutOnGoing['54'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['24']).'"><img src="'.$fitOutOnGoing['24'].'" /></a></p>';
			if(!empty($fitOutOnGoing['40'])) $progress .= "<p><strong>Fire Alarm: ".$fitOutOnGoing['40']."% - ".$fitOutOnGoing['55'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['25']).'"><img src="'.$fitOutOnGoing['25'].'" /></a></p>';
			if(!empty($fitOutOnGoing['41'])) $progress .= "<p><strong>Fire Suppression: ".$fitOutOnGoing['41']."% - ".$fitOutOnGoing['56'].'</strong><br/><a target="_blank" href="'.str_replace("/dr_small/","/dr/",$fitOutOnGoing['26']).'"><img src="'.$fitOutOnGoing['26'].'" /></a></p></div>';
			$progress = str_replace('+','%20',urlencode($progress));
			echo '<a class="view-fo-progress" href="#fo-progress" data-detail="'.$progress.'" style="cursor:pointer; color:red;">view progress</a>';
		}
	?></td>
	</tr>
	<?php
			$i++;
		}
	?>				
</tbody>
</table>
</div>
<br/>
<form action="" id="fo-progress" class="mfp-hide white-popup-block">
	<div id="fo-progress-detail"></div>
</form>

<script type="text/javascript">
$(document).ready(function() {

	var fo_detail;
	$('.view-fo-progress').click(function() {
		fo_detail = this.dataset.detail;
	});

	$('.view-fo-progress').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				$( "#fo-progress-detail" ).html(decodeURIComponent(fo_detail));
			},
			close: function() {	
				
			}
		}
	});
});
</script>

<?php
	}
?>


	