<!-- Magnific Popup core CSS file -->
<link rel="stylesheet" href="/css/magnific-popup.css">
<link rel="stylesheet" href="/css/jquery-ui.min.css">

<style>
fieldset {
  background-color: #eeeeee;
}

legend {
  background-color: gray;
  color: white;
  padding: 5px 10px;
}

input {
  margin: 5px;
} 


h1 {
margin: 0 0 30px 0;
text-align: center;
}

input[type="text"],
input[type="password"],
input[type="date"],
input[type="datetime"],
input[type="email"],
input[type="number"],
input[type="search"],
input[type="tel"],
input[type="time"],
input[type="url"],
textarea,
select {
background: rgba(255,255,255,0.1);
border: none;
font-size: 16px;
height: auto;
margin: 0;
outline: 0;
padding: 15px;
width: 100%;
background-color: white;
color: #8a97a0;
box-shadow: 0 1px 0 rgba(0,0,0,0.03) inset;
margin-bottom: 5px;
}

input[type="radio"],
input[type="checkbox"] {
margin: 0 4px 8px 0;
}

select {
padding: 6px;
height: 32px;
border-radius: 2px;
}

button {
padding: 19px 39px 18px 39px;
color: #FFF;
background-color: #4bc970;
font-size: 18px;
text-align: center;
font-style: normal;
border-radius: 5px;
width: 100%;
border: 1px solid #3ac162;
border-width: 1px 1px 3px;
box-shadow: 0 -1px 0 rgba(255,255,255,0.1) inset;
margin-bottom: 10px;
}

fieldset {
padding-top : 30px;
padding-left : 30px;
padding-right : 30px;
border: none;
}

legend {
font-size: 1.4em;
margin-bottom: 10px;
}

label {
display: block;
margin-bottom: 8px;
}

label.light {
font-weight: 300;
display: inline;
}

.number {
background-color: #5fcf80;
color: #fff;
height: 30px;
width: 30px;
display: inline-block;
font-size: 0.8em;
margin-right: 4px;
line-height: 30px;
text-align: center;
text-shadow: 0 1px 0 rgba(255,255,255,0.2);
border-radius: 100%;
}
 
</style>

  <div class="">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
		<div class="">
			<?php if(!empty($this->message)) { ?><div class="err-msg"><?php echo $this->message; ?></div><?php } ?>
		  
		<div >
		<div class="x_title">
				<h2 class="page-title">Update Watertank Checklist</h2>
				<div class="clearfix"></div>
			</div>
		<form action="/default/checklistwatertank/updatechecklistwatertank" method="POST" onsubmit="$('body').mLoading();">
		<fieldset>
			<legend>Pump Room:</legend>
			<label for="fname">Shift:</label>
			<select type="select" id="shift" name="shift" >
			<option value=<?php echo $this->checklist['shift'];?>>Shift <?php echo $this->checklist['shift'];?></option>
				<option > Pilih Shift</option>
				<option value="1"> Shift 1</option>
				<option value="2"> Shift 2</option>
				<option value="3"> Shift 3</option>
			</select><br><br>
			<label for="tempcool">Temp Cool (&#8451;):</label>
			<input type="text" id="tempcool" name="tempcool" value="<?php echo $this->checklist['temper_cool']; ?>" >
			<input type="text" style="display:none;" id="checklistwater" name="checklistwater" value="<?php echo $this->checklist['checklist_watertank_id']; ?>" ><br><br>
			<label for="temphot">Temp Hot (&#8451;):</label>
			<input type="text" id="temphot" name="temphot"  value="<?php echo $this->checklist['temper_hot']; ?>" ><br><br>
			<label for="Vol1">Volume 1 (%):</label>
			<input type="text" id="vol1" name="vol1" value="<?php echo $this->checklist['vol_1']; ?>" ><br><br>
			<label for="Vol2">Volume 2 (%):</label>
			<input type="text" id="vol2" name="vol2" value="<?php echo $this->checklist['vol_2']; ?>" ><br><br>
		</fieldset>
		<fieldset>
				<legend>Swimming Pool:</legend>
			<label for="PH">PH:</label>
			<input type="text" id="ph" name="ph" value="<?php echo $this->checklist['ph']; ?>" ><br><br>
			<label for="CL">CL:</label>
			<input type="text" id="cl" name="cl" value="<?php echo $this->checklist['cl']; ?>" ><br><br>
			<label for="Vol1">P Sampit Utara:</label>
			<select type="text" id="psu" name="psu" >
			<option value=<?php echo $this->checklist['sampit_utara'];?>><?php echo $this->checklist['sampit_utara'];?></option>
			<option> Pilih Opsi</option>
			<option value="auto"> Auto</option>
			<option value="on"> On </option>
			<option value="off"> Off </option>
			</select>
			<br><br>
			<label for="Vol2">P Sampit Selatan 1:</label>
			<select  id="pss1" name="pss1" >
			<option value=<?php echo $this->checklist['sampit_utara'];?>><?php echo $this->checklist['sampit_sel1'];?></option>
			<option> Pilih Opsi</option>
			<option value="auto"> Auto</option>
			<option value="on"> On </option>
			<option value="off"> Off </option>
			</select>
			<br><br>
			<label for="Vol2">P Sampit Selatan 2:</label>
			<select  id="pss2" name="pss2" >
			<option value=<?php echo $this->checklist['sampit_utara'];?>><?php echo $this->checklist['sampit_sel2'];?></option>
			<option> Pilih Opsi</option>
			<option value="auto"> Auto</option>
			<option value="on"> On </option>
			<option value="off"> Off </option>
			</select>
			<br><br>
			<label for="Vol2">P Sampit Selatan 3:</label>
			<select  id="pss3" name="pss3" >
			<option value=<?php echo $this->checklist['sampit_utara'];?>><?php echo $this->checklist['sampit_sel3'];?></option>
			<option> Pilih Opsi</option>
			<option value="auto"> Auto</option>
			<option value="on"> On </option>
			<option value="off"> Off </option>
			</select>
			<br><br>
			<label for="Vol2">P Sampit Kitchen:</label>
			<select  id="psk" name="psk" >
			<option value=<?php echo $this->checklist['sampit_kitchen'];?>><?php echo $this->checklist['sampit_sel1'];?></option>
			<option> Pilih Opsi</option>
			<option value="auto"> Auto</option>
			<option value="on"> On </option>
			<option value="off"> Off </option>
			</select>
			<br><br>
			<label for="Vol2">Genzet 1:</label>
			<input type="text" id="genzet1" name="genzet1" value="<?php echo $this->checklist['genzet_1'];?>" ><br><br>
			<label for="Vol2">Genzet 2:</label>
			<input type="text" id="genzet2" name="genzet2" value="<?php echo $this->checklist['genzet_2'];?>" ><br><br>
			<label for="Vol2">Fuel 1 (%):</label>
			<input type="text" id="fuel1" name="fuel1" value="<?php echo $this->checklist['fuel_1'];?>" >
			<br><br>
			<label for="Vol2">Fuel 2 (%):</label>
			<input type="text" id="fuel2" name="fuel2" value="<?php echo $this->checklist['fuel_2'];?>" >
			<br><br>
			<label for="Vol2">Remarks:</label>
			<textarea type="text" id="remark" name="remark" value="<?php echo $this->checklist['remarks'];?>" ><?php echo $this->checklist['remarks'];?></textarea>
			<br><br>
			<center><input style="margin-bottom:30px;" type="submit" value="Submit"></center>
		</fieldset>
		</form>
		</div> 
		</div>
	  </div>
	</div>
 </div> 
<!-- /page content -->

<!-- Magnific Popup core JS file -->
<script src="/js/jquery.magnific-popup.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$("#digital-checklist-menu").addClass("active");
	$("#digital-checklist-menu .child_menu").show();
	
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	
	<?php if($this->err == 1) { ?>
	alert("Checklist template is already exist, please use the existing one.");
	<?php } ?>
	
	<?php if($this->err == 2) { ?>
	alert("Room Number does not exist. Please type the correct room number.");
	<?php } ?>
	
	$( "#template_id" ).change(function() {
		$.ajax({
			url: "/checklist/getroomsbytemplateid/id/"+$(this).val(),
			success: function(response){
				var resp = jQuery.parseJSON(response);
				$('#room_no').empty();
				$.each(resp, function(key, val) {
					$('#room_no').append($("<option value='"+ val.floor +"'>"+val.floor+"</option>"));
				}); 	
			}
		});	
		
	});
	
});	
</script>