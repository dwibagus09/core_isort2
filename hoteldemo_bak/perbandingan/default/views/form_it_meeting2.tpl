<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
			<form class="form-label-left" action="/default/it/save2" method="POST" onsubmit="$('body').mLoading();">
				<input id="it_meeting_id" class="form-control col-md-7 col-xs-12" name="it_meeting_id" type="hidden" value="<?php echo $this->itMeeting['it_meeting_id']; ?>">
				<div class="x_title">
					<h2>IT Meeting MoM</h2>
					<div class="clearfix"></div>
				</div>
				<div class="x_content">
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">Nama Site</label>
						<div class="col-md-6 col-sm-6 col-xs-12 " style="padding-top:4px;">
							<?php echo $this->ident['site_fullname']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Judul</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->itMeeting['meeting_title']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Tanggal / Jam</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php echo $this->itMeeting['tanggal_jam']; ?>
						</div>
					</div>
					<br/>
					<div class="item form-group">
						<label class="control-label col-md-3 col-sm-3 col-xs-12" for="supervisor-inhouse">Peserta</label>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<?php foreach($this->attendance as $attendance) { ?>
								<div class="attendance-name"><?php echo $attendance['attendance_name']; ?></div>
							<?php } ?>
						</div>
					</div>
					<br/>
					<br/>
					<div style="margin-bottom:10px; clear:both;">
						<a class="add-topic" href="#topic-form"><input type="button" value="Add Project / Issues" style="width:150px;"></a>
					</div>
					<table class="table table-striped">
					<thead>
						<tr>
						<th width="150">Name</th>
						<th>Project / Issues</th>		
						<th width="50"></th>
						</tr>
					</thead>
					<?php
						if(!empty($this->topic))
						{
					?>
						<tbody>
						<?php
							$i = 1;
							foreach($this->topic as $topic) { 
						?>
						<tr>
						<td><?php echo $topic['name']; ?></td>
						<td><?php echo $topic['topic']; ?></td>
						<td><a class="edit-topic" href="#topic-form" data-id="<?php echo $topic['topic_id']; ?>" ><i class="fa fa-edit" style="font-size:18px;" ></i></a> <a href="/default/it/deletetopic/itid/<?php echo $this->it_meeting_id; ?>/id/<?php echo $topic['topic_id']; ?>"><i class="fa fa-trash" style="font-size:18px;"></i></a></td>
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
				
					<div class="ln_solid"></div>
					<div class="form-group">
						<div class="col-md-12" style="text-align:center;">
							<button id="send" type="button" class="btn btn-success" style="width:250px;" onclick="javascript:$('body').mLoading();location.href='/default/it/edit/id/<?php echo $this->itMeeting['it_meeting_id']; ?>'">Kembali ke halaman sebelumnya</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	  </div>
	</div>
  </div>
</div>
<!-- /page content -->

<form action="/default/it/savetopic" id="topic-form" class="mfp-hide white-popup-block" >
	<input class="col-md-7 col-xs-12" id="topic_id" name="topic_id" type="hidden">
	<input class="col-md-7 col-xs-12" name="it_meeting_id" type="hidden" value="<?php echo $this->it_meeting_id; ?>">
	<table id="add-topic" class="topic-form-table" width="100%">
		<tr>
			<td>PIC<td>
			<td align="center">
				<select id="pic-select" name="pic_id"  class="form-control col-md-7 col-xs-12" required>
					<option value="" disabled selected hidden>Select PIC</option>
					<?php foreach($this->users as $user) { ?>
						<option value="<?php echo $user['user_id']; ?>"><?php echo $user['name']; ?></option>
						<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td>Project / Issues<td>
			<td align="center"><textarea id="topic" name="topic" class="form-control col-md-7 col-xs-12 briefing-txtarea" style="height:50px;" required></textarea></td>
		</tr>
	</table>
	<div class="add-btn"><input type="submit" id="add-topic-submit" name="add-topic-submit" value="Add"></div>
</form>

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$('.add-topic').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				
			},
			close: function() {	
				$('#topic-form')[0].reset();
			}
		}
	});

	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	$('#topic-form').on('submit', function(event){
		$("body").mLoading();
	});
	
	var topic_id;
	$('.edit-topic').on('click', function(event){
		topic_id = this.dataset.id;
	});

	$('.edit-topic').magnificPopup({
		type: 'inline',
		preloader: false,
		closeOnBgClick: false,
		enableEscapeKey: false,
		callbacks: {
			open: function() {
				var id = topic_id;
				$.ajax({
					url: "/default/it/gettopicbyid",
					data: { id : id }
				}).done(function(response) { 
					var resp = jQuery.parseJSON(response);
					console.log(resp);
					$("#topic_id").val(resp.topic_id);
					$("#pic-select").val(resp.pic_id);
					$("#topic").val(resp.topic);
				});
			},
			close: function() {	
				$('#topic-form')[0].reset();
			}
		}
	});

	

});	
</script>