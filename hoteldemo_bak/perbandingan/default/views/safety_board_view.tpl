<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<h2 class="pagetitle">Safety Board <?php echo date("F Y", strtotime($this->year."-".$this->month."-01")); ?></h2>
            <?php
            if(!empty($this->safetyBoard))
            {
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->safetyBoard as $safetyBoard) { 
				?>
                    <div class="thumb-safety-board">
                        <a class="image-popup-vertical-fit" data-source="<?php echo $safetyBoard['img']; ?>" href="<?php echo "/safety_board/large/".$safetyBoard['img']; ?>">
                            <img class="safety-board-thumb-img" src="/safety_board/thumb/<?php echo $safetyBoard['img']; ?>" />
                        </a>
                    </div>
				<?php
						$i++;
					}
				?>				
			  </tbody>
			<?php
				}
			?>
					
	  </div>
	</div>
</div>
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>  
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/jquery.watermark.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.image-popup-vertical-fit').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true,
			titleSrc: function(item) {
              return '<div style="text-align:center; width:500px;"><a id="image-source-link" href="/safety_board/large/'+item.el.attr('data-source')+'" download="'+item.el.attr('data-source')+'"><button>Download</button></a></div>';
            }
		},
        callbacks: {
            open: function() {
                $('.mfp-img').watermark({
                    text: 'For Pakuwon Group. <?php echo date("F Y", strtotime($this->year."-".$this->month."-01"));; ?>',
                    textSize: 50,
                    textWidth: 900,
                    gravity : 'SE',
                    textBg : '#DDDDDD',
					textColor : '#000000',
					done: function (imgURL) {
						this.src=imgURL;						
						$('#image-source-link').attr('href',imgURL);
					}
                });     
				
            }
        }
	});

});
</script>