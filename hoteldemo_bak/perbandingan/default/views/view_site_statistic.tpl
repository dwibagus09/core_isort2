<link rel="stylesheet" href="/css/jquery-ui.min.css">

<!-- page content -->
<div id="user-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle"><?php echo $this->ident['initial']; ?> - Site User Statistic By Login</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/site"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-site-stat" name="view-user-stat" value="Go" style="width:50px;" class="form-btn"> <input type="button" id="export-site-stat" name="export-site-stat" value="Export to PDF" style="width:110px;" class="form-btn"></div>
			</form>
		</div>
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Security</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="80">Total Login</th>
					<th width="150">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Security A</td>
					<td align="center">368</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 18:50</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Security B</td>
					<td align="center">189</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 20:17</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Security C</td>
					<td align="center">145</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 16:02</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Security D</td>
					<td align="center">127</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 22:59</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Security E</td>
					<td align="center">123</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 18:56</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Security F</td>
					<td align="center">121</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 22:03</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Security G</td>
					<td align="center">103</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:00</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Security H</td>
					<td align="center">99</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 10:16</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Security I</td>
					<td align="center">96</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 20:54</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Security J</td>
					<td align="center">81</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 21:53</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Safety</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="80">Total Login</th>
					<th width="150">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Safety A</td>
					<td align="center">368</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 18:50</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Safety B</td>
					<td align="center">130</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 13:46</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Safety C</td>
					<td align="center">103</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:00</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Safety D</td>
					<td align="center">67</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 21:04</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Safety E</td>
					<td align="center">60</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 07:25</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Safety F</td>
					<td align="center">50</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 07:35</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Safety G</td>
					<td align="center">49</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 07:59</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Safety H</td>
					<td align="center">44</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 22:32</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Safety I</td>
					<td align="center">42</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:04</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Safety J</td>
					<td align="center">41</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 16:56</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Parking &amp; Traffic</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="80">Total Login</th>
					<th width="150">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Parking Traffic A</td>
					<td align="center">192</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:23</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Parking Traffic B</td>
					<td align="center">140</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 18:29</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Parking Traffic C</td>
					<td align="center">99</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 11:32</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Parking Traffic D</td>
					<td align="center">95</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 20:56</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Parking Traffic E</td>
					<td align="center">92</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 14:11</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Parking Traffic F</td>
					<td align="center">85</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:02</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Parking Traffic G</td>
					<td align="center">67</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:30</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Parking Traffic H</td>
					<td align="center">62</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:30</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Parking Traffic I</td>
					<td align="center">56</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:22</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Parking Traffic J</td>
					<td align="center">44</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:23</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Housekeeping</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="80">Total Login</th>
					<th width="150">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Housekeeping A</td>				
					<td align="center">210</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:33</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Housekeeping B</td>
					<td align="center">196</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 20:49</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Housekeeping C</td>
					<td align="center">187</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 23:46</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Housekeeping D</td>
					<td align="center">165</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 14:16</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Housekeeping E</td>
					<td align="center">151</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 00:04</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Housekeeping F</td>
					<td align="center">140</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 00:25</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Housekeeping G</td>
					<td align="center">134</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 14:47</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Housekeeping H</td>
					<td align="center">128</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 01:00</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Housekeeping I</td>
					<td align="center">104</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 10:01</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Housekeeping J</td>
					<td align="center">83</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 18:35</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Top Ten User Statistic By Login</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="100">Total Login</th>
					<th width="145">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Housekeeping A</td>		
					<td>Housekeeping</td>					
					<td align="center">210</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:33</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Security B</td>
					<td>Security</td>	
					<td align="center">165</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 00:10</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Security C</td>
					<td>Security</td>
					<td align="center">103</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 19:00</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Safety D</td>
					<td>Safety</td>
					<td align="center">81</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 10:13</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Housekeeping E</td>
					<td>Housekeeping</td>
					<td align="center">75</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 15:49</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Parking & Traffic F</td>
					<td>Parking & Traffic</td>
					<td align="center">71</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 02:23</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Housekeeping G</td>
					<td>Housekeeping</td>
					<td align="center">70</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 09:02</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Parking & Traffic H</td>
					<td>Parking & Traffic</td>
					<td align="center">68</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 21:27</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Safety I</td>
					<td>Safety</td>
					<td align="center">66</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 07:51</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Security J</td>
					<td>Security</td>
					<td align="center">54</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 01:32</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4>Top Ten User Statistic By Submitting Kaizen</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="100">Total Issues</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Security A</td>		
					<td>Security</td>					
					<td align="center">36</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Safety B</td>
					<td>Safety</td>	
					<td align="center">28</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Housekeeping C</td>
					<td>Housekeeping</td>
					<td align="center">27</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Parking & Traffic D</td>
					<td>Parking & Traffic</td>
					<td align="center">17</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Housekeeping E</td>
					<td>Housekeeping</td>
					<td align="center">16</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Parking & Traffic F</td>
					<td>Parking & Traffic</td>
					<td align="center">14</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Security G</td>
					<td>Security</td>
					<td align="center">11</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Parking & Traffic H</td>
					<td>Parking & Traffic</td>
					<td align="center">10</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Safety I</td>
					<td>Safety</td>
					<td align="center">8</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Housekeeping J</td>
					<td>Housekeeping</td>
					<td align="center">7</td>
				</tr>
			</table>
		</div>


		<div class="user-stat col-md-5 col-sm-5 col-xs-12" style="clear:both;">
			<h4>Top Ten User Statistic By Comments</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th>Department</th>
					<th width="110">Total Comments</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Safety A</td>		
					<td>Safety</td>					
					<td align="center">57</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Housekeeping B</td>
					<td>Housekeeping</td>	
					<td align="center">37</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Housekeeping C</td>
					<td>Housekeeping</td>
					<td align="center">31</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Safety D</td>
					<td>Safety</td>
					<td align="center">21</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Housekeeping E</td>
					<td>Housekeeping</td>
					<td align="center">20</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Parking & Traffic F</td>
					<td>Parking & Traffic</td>
					<td align="center">19</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Security G</td>
					<td>Security</td>
					<td align="center">16</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Housekeeping H</td>
					<td>Housekeeping</td>
					<td align="center">15</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Security I</td>
					<td>Security</td>
					<td align="center">12</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Parking & Traffic J</td>
					<td>Parking & Traffic</td>
					<td align="center">10</td>
				</tr>
			</table>
		</div>
		
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->


<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#export-site-stat").click(function() {
		if(window.innerWidth <= 800 && window.innerHeight <= 600) {
			location.href = '/default/statistic/exportsitestatistictopdf/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
		} else {
			window.open("/default/statistic/exportsitestatistictopdf/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
		}		
	});
});	
</script>