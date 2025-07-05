<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<h2 class="pagetitle"><?php echo $this->ident['initial']." - ".$this->category['category_name']; ?> CQC Email Notification Periode: 1 Juli - 31 Desember 2020</h2>
		</div>
		
		<div class="cqc-email-header">
			<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">To:</label>
				<div class="col-md-6 col-sm-6 col-xs-12"><?php echo $this->to; ?></div>
			</div>
			<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">Cc:</label>
				<div class="col-md-6 col-sm-6 col-xs-12"><?php echo $this->cc; ?></div>
			</div>
			<div class="item form-group">
				<label class="control-label col-md-3 col-sm-3 col-xs-12" for="shift">Subject:</label>
				<div class="col-md-6 col-sm-6 col-xs-12"><?php echo $this->ident['initial']." - ".$this->category['category_name']; ?> CQC Periode: 1 Juli - 31 Desember 2020</div>
			</div>
		</div>

		<div class="cqc-email-body">
			Dear all,<br/>
			Dengan ini, Technical Service sampaikan hasil CQC dari tanggal 1 Juli - 31 Desember 2020.<br/>
			Secara keseluruhan, pemenuhan pelaksanaan action plan sudah terlaksana dengan baik, namun masih terdapat beberapa temuan yang harus <strong>diperbaiki dan diunggah kembali</strong> sesuai dengan catatan di bawah ini.<br/>
			<span style="color:red">Note: Batas waktu mengunggah dokumen pada tanggal <?php echo date("j F Y",mktime(0, 0, 0, date("m")  , date("d")+14, date("Y"))); ?></span>
		</div>

		<div id="cqc-table">
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="250">Module</th>
				  <th width="250">Target</th>
				  <th width="250">Activity</th>
				  <th width="250">Date</th>
				  <th width="250">Remarks</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->ap))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->ap as $a) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $a['module_name']; ?></th>
				  <td class="date-column"><?php echo $a['target_name']; ?></td>
				  <td class="date-column"><?php echo $a['activity_name']; ?></td>
				  <td class="date-column"><?php echo $a['date']; ?></td>
				  <td class="date-column" id="cqc-remarks-<?php echo $a['schedule_id']; ?>"><?php
				  if(!empty($a['cqc'])) {
					  foreach($a['cqc'] as $cqc) { ?>
					  <div class="cqc">
						<?php echo $cqc['remarks']; ?>
						<?php if(!empty($cqc['attachment'])) { ?><br/><i class="fa fa-paperclip"></i> <a href="<?php echo $this->baseUrl.'/actionplan/cqc/'.strtolower(str_replace(" & ", "", $this->category['category_name'])).'/'.$cqc['attachment']; ?>" target="_blank"><?php echo $cqc['attachment']; ?></a><?php } ?>
					  </div>						  
					  <?php }
				  } ?>
				</tr>
				<?php
						$i++;
					}
				?>				
			  </tbody>
			<?php
				}
			?>
			</table>
		</div>			

		<div class="ln_solid"></div>
		<div class="form-group">
			<div class="col-md-12" style="text-align:center;">
			<?php if($this->showCQCEmail == 1) { ?><button id="sendemail" type="button" class="btn btn-success" style="width:250px;">Send</button><?php } ?>
			</div>
		</div>
		<br/><br/>
	  </div>
	</div>
</div>
<!-- /page content -->


<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script type="text/javascript">
$(document).ready(function() {
	$("#sendemail").click(function() {
		$.ajax({
			url: "/default/actionplan/sendcqcemail",
			data: { c : '<?php echo $this->category['category_id']; ?>' }
		}).done(function(response) {
			alert(response);
		});
	});

});
</script>