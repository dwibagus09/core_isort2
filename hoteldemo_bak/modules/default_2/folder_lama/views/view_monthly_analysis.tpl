<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">

	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
			<h2 class="pagetitle"><?php echo $this->category['category_name']; ?> Monthly Analytics</h2>
			<?php if($this->totalPage > 0) { ?>
			<div class="paging">
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
			</div>
			<?php } ?>
		  <table class="table table-striped">
			  <thead>
				<tr>
				  <th width="200">Month</th>
				  <th width="150">Submitted By</th>
				  <?php /*<th>Comments</th> */ ?>						  
				  <th>Action</th>
				</tr>
			  </thead>
			  <?php
				if(!empty($this->monthlyAnalysis))
				{
			?>
				<tbody>
				<?php
					$i = 1;
					foreach($this->monthlyAnalysis as $monthlyAnalysis) { 
				?>
				<tr>
				  <td class="date-column"><?php echo $monthlyAnalysis['monthyear']; ?></th>
				  <td class="date-column"><?php echo $monthlyAnalysis['name']; ?></td>
				  <?php /*<td>
						<div class="three-newest-comments" id="comment-<?php echo $monthlyAnalysis['monthlyAnalysis']; ?>">
						<?php if(!empty($monthlyAnalysis['comments'])) { 
							foreach($monthlyAnalysis['comments'] as $comment)
							{
								echo '<div style="border-bottom:1px solid #ccc; text-align:left; clear:both; margin-bottom:15px; padding-bottom:5px;"><strong>'.$comment['name'].' : </strong> '.$comment['comment'].'<br/>';
								if(!empty($comment['filename'])) echo '<a href="'.$this->baseUrl."/comments/".$comment['filename'].'" target="_blank"><i class="fa fa-paperclip"></i> '.$comment['filename'].'</a>';
								echo '<div style="font-size:10px; color:#aaa; text-align:right;">'.$comment['comment_date']."</div></div>";
							}
						} ?>
						</div>
				  </td>*/ ?>
				  <td class="action-column">
				  	<?php if($monthlyAnalysis['allowEdit'] == 1) { ?><a href="/default/bi/addmonthlyanalysis/id/<?php echo $monthlyAnalysis['monthly_analysis_id']; ?>/c/<?php echo $this->category['category_id']; ?>" class="action-btn"><img src="/images/edit_report.png" width="24" /></a><?php } ?>
					<a href="/default/bi/viewdetailmonthlyanalysis/id/<?php echo $monthlyAnalysis['monthly_analysis_id']; ?>/c/<?php echo $this->category['category_id']; ?>" class="action-btn"><img src="/images/view_report.png" width="24" /></a>
					<?php /*<a class="add-comment" href="#comment-form" data-ym="<?php echo $monthlyAnalysis['yearmonth']; ?>" style="float:none; padding-top:10px; display:block;"><img src="/images/comment_24x24.png" /></a>*/ ?>
				  </td>
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
			
			<?php if($this->totalPage > 0) { ?>			
			<div class="paging">
				<div class="paging-section">
					<?php if(!empty($this->firstPageUrl)) { ?><a href="<?php echo $this->firstPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-left" ></i></a><?php } ?>
					<?php if(!empty($this->prevUrl)) { ?><a href="<?php echo $this->prevUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-left" ></i></a><?php } ?>
					<span class="page-indicator" style="margin-right:10px; margin-left:10px;">Page <?php echo $this->curPage; ?> of <?php echo $this->totalPage; ?></span>
					<?php /*<a class="create-report"><img src="/images/report-icon.png" /></a>*/ ?>
					<?php if(!empty($this->nextUrl)) { ?><a href="<?php echo $this->nextUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-right" ></i></a><?php } ?>
					<?php if(!empty($this->lastPageUrl)) { ?><a href="<?php echo $this->lastPageUrl; ?>" data-id="<?php echo $issue['issue_id']; ?>"><i class="fa fa-angle-double-right"></i></a><?php } ?>
				</div>
				<div class="record-indicator">Showing <?php echo $this->startRec." - ".$this->endRec." of ".$this->totalRec; ?> Reports </div>
			</div>
			<?php } ?>
	  </div>
	</div>
</div>
<!-- /page content -->
