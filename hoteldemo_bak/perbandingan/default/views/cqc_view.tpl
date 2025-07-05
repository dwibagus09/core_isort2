<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">
<link rel="stylesheet" type="text/css" href="/js/FullWidthTabs/css/component.css" />

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div style="margin-bottom:10px;">
			<h2 class="pagetitle"><?php echo $this->ident['initial']." - ".$this->category['category_name']; ?> CQC <?php echo $this->selectedYear; ?></h2>
		</div>

		<div id="tabs" class="tabs">
			<nav>
				<ul>
					<li id="period1" class="tab" data-id="1" style="width:130px; text-align:center;"><a href="#period-1"><span>First 6 months</span></a></li>
					<li id="period2" class="tab" data-id="2" style="width:130px; text-align:center;"><a href="#period-3"><span>Second 6 months</span></a></li>
				</ul>
				<?php if($this->allowApproveCQC) { ?>
				<a href="/default/actionplan/viewcqc/c/<?php echo $this->category['category_id']; ?>/y/<?php echo ($this->selectedYear+1); ?>" class="year-paging" ><?php echo ($this->selectedYear+1); ?> &raquo;</a>
				<?php if($this->selectedYear > 2020) { ?><a href="/default/actionplan/viewcqc/c/<?php echo $this->category['category_id']; ?>/y/<?php echo ($this->selectedYear-1); ?>" class="year-paging">&laquo; <?php echo ($this->selectedYear-1); ?></a><?php } ?>
				<?php } ?>
			</nav>
			<div class="content">
				<section id="period-1">
						
				</section>
				<section id="period-2">
					
				</section>
			</div><!-- /content -->
		</div><!-- /tabs -->		

		<div class="ln_solid"></div>
		<div class="form-group">
			<div class="col-md-12" style="text-align:center;">
			<?php if($this->showCQCEmail == 1) { ?><button id="sendemail" type="button" class="btn btn-success" style="width:250px;" onclick="location.href='/default/actionplan/cqcemailconfirmation/c/<?php echo $this->category['category_id']; ?>'">Send Email Notification</button><?php } ?>
			</div>
		</div>
		<br/><br/>
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

	$("body").mLoading();
	$( "#period1" ).addClass( "tab" );
	$.ajax({
		async : true,
		url: "/default/actionplan/showcurrentcqclist",
		data: { 
			c : '<?php echo $this->category['category_id']; ?>',
			y : '<?php echo $this->selectedYear; ?>'
		}
	}).done(function(response) {
		$( "#period-"+<?php echo $this->period; ?>).html(response);
		$( "#period1" ).removeClass( "tab-current" );
		$( "#period-1" ).addClass( "" );
		$( "#period"+<?php echo $this->period; ?>).addClass( "tab-current" );
		$( "#period-"+<?php echo $this->period; ?>).addClass( "content-current" ); 
		$("body").mLoading('hide');
	});
	
	$('.tab').click(function() {
		$("body").mLoading();
		var period = this.dataset.id;
		$.ajax({
			async : true,
			url: "/default/actionplan/showcurrentcqclist",
			data: { c : '<?php echo $this->category['category_id']; ?>',
					period : period,
					y : '<?php echo $this->selectedYear; ?>'
			}
		}).done(function(response) {
			$( "#period-1").html("");
			$( "#period-2").html("");
			$( "#period-"+period).html(response);
			$( "#period1" ).removeClass( "tab-current" );
			$( "#period-1" ).addClass( "" );
			$( "#period2" ).removeClass( "tab-current" );
			$( "#period-2" ).addClass( "" );
			$( "#period"+period).addClass( "tab-current" );
			$( "#period-"+period).addClass( "content-current" ); 
			$("body").mLoading('hide');
			$("body").mLoading('hide');
		});
	});
});
</script>