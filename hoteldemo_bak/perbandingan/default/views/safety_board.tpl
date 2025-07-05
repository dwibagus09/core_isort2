<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">


	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle"><?php echo $this->ident['initial']; ?> - Safety Board <?php echo date("Y"); ?></h2>
            <?php
            for($i=0; $i<12; $i++)
            {
			?>
                <div class="safety-board-icon">
                    <?php if((($i+1) == (date("n")+1) && date("j") > 20) || $this->uploadSafetyBoard == 1) { ?><a href="/default/safety/viewsafetyboardimageslist/ym/<?php echo date("Y").($i+1); ?>"><?php } ?>
                        <img class="safety-board-icon-img" src="/images/<?php if((($i+1) == date("n")+1 && date("j") > 20)  || $this->uploadSafetyBoard == 1) echo "safety_board_icon_unlock.jpg"; else echo "safety_board_icon_lock.jpg"; ?>" />
                        <div class="safety-board-month"><?php echo date("F", strtotime(date("Y")."-".($i+1)."-01")); ?></div>
                   <?php if((($i+1) == date("n")+1 && date("j") > 20)  || $this->uploadSafetyBoard == 1) { ?> </a><?php } ?>
                </div>	
			<?php
				}
			?>					
	  </div>
	</div>
</div>
<!-- /page content -->