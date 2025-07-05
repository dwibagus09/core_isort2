<link rel="stylesheet" href="/css/jquery-ui.min.css">

<div id="user-statistic">
	<div class="row">
	  <div class="col-md-12 col-sm-12 col-xs-12">
	  	<h2 class="pagetitle">Corporate User Statistic</h2>
		<div class="statistic-filter">
			<form id="statistic-filter-form" action="/default/statistic/corporate"  method="post">
				<div class="statistic-filter-field">Start Date : <input type="text" name="start_date" name="start_date" class="datepicker" value="<?php echo $this->start_date; ?>"></div>
				<div class="statistic-filter-field">End Date :	<input type="text" name="end_date" class="datepicker" value="<?php echo $this->end_date; ?>"></div>
				<div class="statistic-filter-field"><input type="submit" id="view-corporate-stat" name="view-corporate-stat" value="Go" style="width:50px; margin-top:0px;" class="form-btn"> <input type="button" id="export-corporate-stat" name="export-corporate-stat" value="Export to PDF" style="width:110px; margin-top:0px;" class="form-btn"></div>
			</form>
		</div>
		
		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Security</h4>
			<div class="total-people-corp-stat">Total Security: 45 people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Security A</td>
					<td align="center">Bali</td>
					<td align="center">377</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 14:25</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Security B</td>
					<td align="center">Sumatra</td>
					<td align="center">186</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 15:05</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Security C</td>
					<td align="center">East Java</td>
					<td align="center">146</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 14:35</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Security D</td>
					<td align="center">Jakarta</td>
					<td align="center">137</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 12:58</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Security E</td>
					<td align="center">Central Java</td>
					<td align="center">121</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 10:54</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Security F</td>
					<td align="center">Sumatra</td>
					<td align="center">119</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 17:40</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Security G</td>
					<td align="center">Jakarta</td>
					<td align="center">110</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 12:39</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Security H</td>
					<td align="center">Bali</td>
					<td align="center">106</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:27</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Security I</td>
					<td align="center">Central Java</td>
					<td align="center">93</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 09:54</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Security J</td>
					<td align="center">Jakarta</td>
					<td align="center">81</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 07:24</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Safety</h4>
			<div class="total-people-corp-stat">Total Safety: 37 people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Safety A</td>
					<td align="center">Jakarta</td>
					<td align="center">177</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 20:25</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Safety B</td>
					<td align="center">Sumatra</td>
					<td align="center">126</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 07:39</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Safety C</td>
					<td align="center">East Java</td>
					<td align="center">110</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 12:39</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Safety D</td>
					<td align="center">Jakarta</td>
					<td align="center">75</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 07:25</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Safety E</td>
					<td align="center">Bali</td>
					<td align="center">71</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:03</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Safety F</td>
					<td align="center">Central Java</td>
					<td align="center">55</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 19:21</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Safety G</td>
					<td align="center">Jakarta</td>
					<td align="center">51</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 01:23:</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Safety H</td>
					<td align="center">Bali</td>
					<td align="center">48</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 15:53</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Safety I</td>
					<td align="center">East Java</td>
					<td align="center">45</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 09:07</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Safety J</td>
					<td align="center">Central Java</td>
					<td align="center">41</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 18:57</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Parking &amp; Traffic</h4>
			<div class="total-people-corp-stat">Total Parking &amp; Traffic: 25 people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Parking Traffic A</td>
					<td align="center">Sumatra</td>
					<td align="center">201</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 15:06</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Parking Traffic B</td>
					<td align="center">Bali</td>
					<td align="center">139</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:40</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Parking Traffic C</td>
					<td align="center">Jakarta</td>
					<td align="center">112</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 15:15</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Parking Traffic D</td>
					<td align="center">East Java</td>
					<td align="center">100</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 23:39</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Parking Traffic E</td>
					<td align="center">Central Java</td>
					<td align="center">93</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 11:30</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Parking Traffic F</td>
					<td align="center">Bali</td>
					<td align="center">85</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 20:44</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Parking Traffic G</td>
					<td align="center">Bali</td>
					<td align="center">65</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:51</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Parking Traffic H</td>
					<td align="center">Jakarta</td>
					<td align="center">61</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 10:06</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Parking Traffic I</td>
					<td align="center">Central Java</td>
					<td align="center">59</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 18:54</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Parking Traffic J</td>
					<td align="center">East Java</td>
					<td align="center">42</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 07:31</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-6 col-xs-12">
			<h4>Housekeeping</h4>
			<div class="total-people-corp-stat">Total Housekeeping: 41 people</div>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th> 
					<th width="155">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Housekeeping A</td>
					<td align="center">East Java</td>					
					<td align="center">228</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 17:33</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Housekeeping B</td>
					<td align="center">Sumatra</td>
					<td align="center">211</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:32</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Housekeeping C</td>
					<td align="center">Jakarta</td>
					<td align="center">190</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:19</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Housekeeping D</td>
					<td align="center">Bali</td>
					<td align="center">155</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 14:16</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Housekeeping E</td>
					<td align="center">Bali</td>
					<td align="center">150</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 07:38</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Housekeeping F</td>
					<td align="center">Central Java</td>
					<td align="center">141</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 11:40</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Housekeeping G</td>
					<td align="center">East Java</td>
					<td align="center">133</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 14:47</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Housekeeping H</td>
					<td align="center">East Java</td>
					<td align="center">127</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 13:36</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Housekeeping I</td>
					<td align="center">Central Java</td>
					<td align="center">104</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 10:01</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Housekeeping J</td>
					<td align="center">Bali</td>
					<td align="center">81</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 12:33</td>
				</tr>
			</table>
		</div>	

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4>Site Managers By Login</h4>
			<table>
				<tr>
					<th width="35">No</th>
					<th>Name</th>
					<th width="70">Site</th>
					<th width="100">Total Login</th>
					<th width="155">Last Login</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Site Manager A</td>
					<td align="center">East Java</td>					
					<td align="center">77</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 16:27</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Site Manager B</td>
					<td align="center">Sumatra</td>
					<td align="center">51</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 21:13</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Site Manager C</td>
					<td align="center">Jakarta</td>
					<td align="center">47</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 15:07</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Site Manager D</td>
					<td align="center">Bali</td>
					<td align="center">42</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 02:20</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Site Manager E</td>
					<td align="center">Bali</td>
					<td align="center">39</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 18:32</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Site Manager F</td>
					<td align="center">Central Java</td>
					<td align="center">34</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 19:26</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Site Manager G</td>
					<td align="center">East Java</td>
					<td align="center">21</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 12:24</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Site Manager H</td>
					<td align="center">East Java</td>
					<td align="center">20</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 09:35</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Site Manager I</td>
					<td align="center">Central Java</td>
					<td align="center">4</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m")  , date("d")-1, date("Y"))); ?> 18:46</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Site Manager J</td>
					<td align="center">Bali</td>
					<td align="center">3</td>
					<td align="center"><?php echo date("j M Y", mktime(0, 0, 0, date("m"), date("d")-1, date("Y"))); ?> 17:59</td>
				</tr>
			</table>
		</div>

		<div class="user-stat col-md-5 col-sm-5 col-xs-12">
			<h4>Site Managers By Comments</h4>
			<table>
				<tr>
					<th width="35" rowspan="2">No</th>
					<th rowspan="2">Name</th>
					<th width="100" rowspan="2">Site</th>
					<th width="200" colspan="2">Total Comments</th>
				</tr>
				<tr>
					<th width="100">Kaizen</th>
					<th width="100">Daily Report</th>
				</tr>
				<tr>
					<td>1</td>
					<td>Site Manager A</td>
					<td align="center">East Java</td>					
					<td align="center">67</td>
					<td align="center">24</td>
				</tr>
				<tr>
					<td>2</td>
					<td>Site Manager B</td>
					<td align="center">Sumatra</td>
					<td align="center">51</td>
					<td align="center">31</td>
				</tr>
				<tr>
					<td>3</td>
					<td>Site Manager C</td>
					<td align="center">Jakarta</td>
					<td align="center">27</td>
					<td align="center">45</td>
				</tr>
				<tr>
					<td>4</td>
					<td>Site Manager D</td>
					<td align="center">Bali</td>
					<td align="center">32</td>
					<td align="center">33</td>
				</tr>
				<tr>
					<td>5</td>
					<td>Site Manager E</td>
					<td align="center">Bali</td>
					<td align="center">29</td>
					<td align="center">32</td>
				</tr>
				<tr>
					<td>6</td>
					<td>Site Manager F</td>
					<td align="center">Central Java</td>
					<td align="center">24</td>
					<td align="center">22</td>
				</tr>
				<tr>
					<td>7</td>
					<td>Site Manager G</td>
					<td align="center">East Java</td>
					<td align="center">25</td>
					<td align="center">21</td>
				</tr>
				<tr>
					<td>8</td>
					<td>Site Manager H</td>
					<td align="center">East Java</td>
					<td align="center">7</td>
					<td align="center">5</td>
				</tr>
				<tr>
					<td>9</td>
					<td>Site Manager I</td>
					<td align="center">Central Java</td>
					<td align="center">2</td>
					<td align="center">2</td>
				</tr>
				<tr>
					<td>10</td>
					<td>Site Manager J</td>
					<td align="center">Bali</td>
					<td align="center">3</td>
					<td align="center">1</td>
				</tr>
			</table>
		</div>
		
		<div class="user-stat col-md-5 col-sm-5 col-xs-12" style="min-height:auto;">
			<h4>Users Statistic for All Sites</h4>
			<?php /*<div id="total-login" class="graph">Loading User Graph...</div>*/ ?>
			<table style="margin-bottom:8px;">
				<tr>
					<th width="70" height="33">Sites</th>
					<th>By Login</th>
					<th>By Submitting Kaizen</th>
					<th>By Comments</th>
				</tr>
				<tr>
					<th height="33">Jakarta</th>
					<td align="center">580</td>
					<td align="center">370</td>
					<td align="center">277</td>
				</tr>
				<tr>
					<th height="33">Bali</th>
					<td align="center">384</td>
					<td align="center">319</td>
					<td align="center">256</td>
				</tr>
				<tr>
					<th height="33">Sumatra</th>
					<td align="center">465</td>
					<td align="center">298</td>
					<td align="center">105</td>
				</tr>
				<tr>
					<th height="33">East Java</th>
					<td align="center">370</td>
					<td align="center">207</td>
					<td align="center">77</td>
				</tr>
				<tr>
					<th height="33">Central Java</th>
					<td align="center">189</td>
					<td align="center">84</td>
					<td align="center">47</td>
				</tr>
			</table>
		</div>	

		<div id="graph" style="clear:both;">
			<?php /*<div id="user-stat-login" class="graph">Loading User Statistic By Login...</div>
			<div id="user-stat-issues" class="graph">Loading User Statistic By Submitting Kaizen...</div>
			<div id="user-stat-comments" class="graph">Loading User Statistic By Comments...</div>
			<div id="security-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>
			<div id="safety-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>
			<div id="parking-outstanding-ap" class="graph">Loading Outstanding Action Plan Statistic...</div>*/ ?>

			<div class="corporate-user-stat">
				<canvas id="userStatLogin"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="userStatSubmitIssue"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="userStatComment"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="securityReschedule"></canvas>
			</div>
			<div class="corporate-user-stat">
				<canvas id="prevMaintReschedule"></canvas>
			</div>
		</div>
		
	  </div>
	</div>
  </div>
</div>
</div>
<!-- /page content -->

<script type="text/javascript" src="/js/Chart.js2.9.3/dist/Chart.min.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/utils.js"></script>
<script type="text/javascript" src="/js/Chart.js2.9.3/plugin/chartjs-plugin-labels.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });

	$("#export-corporate-stat").click(function() {
		$("body").mLoading();
		var login = document.getElementById("userStatLogin");
		var issue = document.getElementById("userStatSubmitIssue");
		var comment = document.getElementById("userStatComment");
		var apreschedulesec = document.getElementById("securityReschedule");
		var prevmaintreschedule = document.getElementById("prevMaintReschedule");
		$.ajax({
			method: 'POST',
			url: '/default/statistic/saveusergraph',
			data: {
				login: login.toDataURL("image/png"),
				issue: issue.toDataURL("image/png"),
				comment: comment.toDataURL("image/png"),
				apreschedulesec: apreschedulesec.toDataURL("image/png"),
				prevmaintreschedule: prevmaintreschedule.toDataURL("image/png")
			},
			success: function(data) {
				if(window.innerWidth <= 800 && window.innerHeight <= 600) {
					location.href = '/default/statistic/exportcorporatestatistictopdf/cd/'+data+'/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>';
				} else {
					window.open("/default/statistic/exportcorporatestatistictopdf/cd/"+data+"/sd/<?php echo str_replace("-","",$this->start_date); ?>/ed/<?php echo str_replace("-","",$this->end_date); ?>");
				}	
				$("body").mLoading('hide');	
			}
		});
	});	

	/*** Master User Statistic 
	var masterUserStat = new Array(['By Login', 236, 177, 137, 	98, 75], ['By Submitting Issue', 168, 109, 83, 66, 42], ['By Comments', 103, 92, 61, 47, 34]);
	var masterUserStatChart = new JSChart('user-statistic-all-sites', 'bar');
	masterUserStatChart.setDataArray(masterUserStat);
	masterUserStatChart.setTitle('User Statistic for All Sites');
	masterUserStatChart.setTitleColor('#8E8E8E');
	masterUserStatChart.setAxisNameX('');
	masterUserStatChart.setAxisNameY('');
	masterUserStatChart.setAxisNameFontSize(6);
	masterUserStatChart.setAxisValuesFontSize(6);
	masterUserStatChart.setAxisNameColor('#999');
	masterUserStatChart.setAxisValuesColor('#777');
	masterUserStatChart.setAxisColor('#B5B5B5');
	masterUserStatChart.setAxisWidth(1);
	masterUserStatChart.setBarValuesColor('#2F6D99');
	masterUserStatChart.setAxisPaddingTop(50);
	masterUserStatChart.setAxisPaddingBottom(40);
	masterUserStatChart.setAxisPaddingLeft(40);
	masterUserStatChart.setTitleFontSize(10);
	masterUserStatChart.setBarColor('#2D6B96', 1);
	masterUserStatChart.setBarColor('#04da18', 2);
	masterUserStatChart.setBarColor('#f1a81c', 3);
	masterUserStatChart.setBarColor('#9CCEF0', 4);
	masterUserStatChart.setBarColor('#03d1de', 5);
	masterUserStatChart.setBarColor('#c3ec1f', 6);
	masterUserStatChart.setBarColor('#bb0606', 7);
	masterUserStatChart.setBarColor('#a688bf', 8);
	masterUserStatChart.setBarBorderWidth(0);
	masterUserStatChart.setBarSpacingRatio(18);
	masterUserStatChart.setBarOpacity(0.9);
	masterUserStatChart.setBarValuesFontSize(6);
	masterUserStatChart.setFlagRadius(6);
	masterUserStatChart.setLegendShow(true);
	masterUserStatChart.setLegendPosition('bottom');
	masterUserStatChart.setLegendPadding(10);
	masterUserStatChart.setLegendFontSize(7);
	masterUserStatChart.setLegendForBar(1, 'Jakarta');
	masterUserStatChart.setLegendForBar(2, 'East Java');
	masterUserStatChart.setLegendForBar(3, 'Bali');
	masterUserStatChart.setLegendForBar(4, 'Central Java');
	masterUserStatChart.setLegendForBar(5, 'Sumatra');
	masterUserStatChart.setSize(652, 307);
	masterUserStatChart.setGridColor('#F7F7F7');
	masterUserStatChart.draw(); ***/

	/*** USER STAT BY LOGIN ***/
	var userStatLoginLabel = new Array();
	var userStatLoginData = new Array();
	userStatLoginLabel[0] = "East Java";
	userStatLoginData[0] = 1170;
	userStatLoginLabel[1] = "Bali";
	userStatLoginData[1] = 1111;
	userStatLoginLabel[2] = "Central Java";
	userStatLoginData[2] = 1016;
	userStatLoginLabel[3] = "Jakarta";
	userStatLoginData[3] = 710;
	userStatLoginLabel[4] = "Sumatra";
	userStatLoginData[4] = 623;
	
	var color = Chart.helpers.color;
	var userStatLoginChartData = {
		labels: userStatLoginLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatLoginData
		}]
	};

	var userStatLoginChart = document.getElementById('userStatLogin').getContext('2d');
	window.userStatLoginBar = new Chart(userStatLoginChart, {
		type: 'bar',
		data: userStatLoginChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY LOGIN',
				padding: 25
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** USER STAT BY ISSUES ***/

	var userStatSubmitIssueLabel = new Array();
	var userStatSubmitIssueData = new Array();
	userStatSubmitIssueLabel[0] = "Jakarta";
	userStatSubmitIssueData[0] = 298;
	userStatSubmitIssueLabel[1] = "Bali";
	userStatSubmitIssueData[1] = 207;
	userStatSubmitIssueLabel[2] = "Central Java";
	userStatSubmitIssueData[2] = 84;
	userStatSubmitIssueLabel[3] = "Sumatra";
	userStatSubmitIssueData[3] = 77;
	userStatSubmitIssueLabel[4] = "East Java";
	userStatSubmitIssueData[4] = 47;
	
	var color = Chart.helpers.color;
	var userStatSubmitIssueChartData = {
		labels: userStatSubmitIssueLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatSubmitIssueData
		}]
	};

	var userStatSubmitIssueChart = document.getElementById('userStatSubmitIssue').getContext('2d');
	window.userStatSubmitIssueBar = new Chart(userStatSubmitIssueChart, {
		type: 'bar',
		data: userStatSubmitIssueChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY SUBMITTING KAIZEN',
				padding: 25
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** USER STAT BY COMMENTS ***/

	var userStatCommentsLabel = new Array();
	var userStatCommentsData = new Array();
	userStatCommentsLabel[0] = "Jakarta";
	userStatCommentsData[0] = 291;
	userStatCommentsLabel[1] = "Bali";
	userStatCommentsData[1] = 126;
	userStatCommentsLabel[2] = "East Java";
	userStatCommentsData[2] = 96;
	userStatCommentsLabel[3] = "Central Java";
	userStatCommentsData[3] = 45;
	userStatCommentsLabel[4] = "Sumatra";
	userStatCommentsData[4] = 2;
	
	<?php if(!empty($this->userStatisticSummary) && !empty($this->totalCommentsStat)) {
		$i = 0;
	 	foreach($this->totalCommentsStat as $key=>$val) { 
			echo 'userStatCommentsLabel['.$i.'] = "'.$this->userStatisticSummary[$key]['initial'].'";';
			echo 'userStatCommentsData['.$i.'] = '.$val.';';
			$i++;
	 	}
	} ?>
	
	var color = Chart.helpers.color;
	var userStatCommentsChartData = {
		labels: userStatCommentsLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  userStatCommentsData
		}]
	};

	var userStatCommentsChart = document.getElementById('userStatComment').getContext('2d');
	window.userStatCommentsBar = new Chart(userStatCommentsChart, {
		type: 'bar',
		data: userStatCommentsChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'USER STATISTIC BY COMMENTS',
				padding: 25
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** SECURITY RESCHEDULE ACTION PLAN ***/
	var securityRescheduleLabel = new Array();
	var securityRescheduleData = new Array();
	securityRescheduleLabel[0] = "Bali";
	securityRescheduleData[0] = 25;
	securityRescheduleLabel[1] = "Jakarta";
	securityRescheduleData[1] = 22;
	securityRescheduleLabel[2] = "East Java";
	securityRescheduleData[2] = 17;
	securityRescheduleLabel[3] = "Sumatra";
	securityRescheduleData[3] = 11;
	securityRescheduleLabel[4] = "Central Java";
	securityRescheduleData[4] = 8;
	
	var color = Chart.helpers.color;
	var securityRescheduleChartData = {
		labels: securityRescheduleLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  securityRescheduleData
		}]
	};

	var securityRescheduleChart = document.getElementById('securityReschedule').getContext('2d');
	window.securityRescheduleBar = new Chart(securityRescheduleChart, {
		type: 'bar',
		data: securityRescheduleChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'SECURITY RESCHEDULE ACTION PLAN <?php echo date("Y"); ?>',
				padding: 25
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9
					}
				}]
			}
		}
	});

	/*** PREVENTIVE MAINTENANCE RESCHEDULE ***/
	var prevMaintRescheduleLabel = new Array();
	var prevMaintRescheduleData = new Array();
	prevMaintRescheduleLabel[0] = "East Java";
	prevMaintRescheduleData[0] = 16;
	prevMaintRescheduleLabel[1] = "Bali";
	prevMaintRescheduleData[1] = 12;
	prevMaintRescheduleLabel[2] = "Central Java";
	prevMaintRescheduleData[2] = 9;
	prevMaintRescheduleLabel[3] = "Sumatra";
	prevMaintRescheduleData[3] = 5;
	prevMaintRescheduleLabel[4] = "Jakarta";
	prevMaintRescheduleData[4] = 1;

	
	var color = Chart.helpers.color;
	var prevMaintRescheduleChartData = {
		labels: prevMaintRescheduleLabel,
		datasets: [{
			label: 'User Statistic By Login',
			backgroundColor: '#9e824b',
			borderColor: '#9e824b',
			borderWidth: 1,
			data:  prevMaintRescheduleData
		}]
	};

	var prevMaintRescheduleChart = document.getElementById('prevMaintReschedule').getContext('2d');
	window.prevMaintRescheduleBar = new Chart(prevMaintRescheduleChart, {
		type: 'bar',
		data: prevMaintRescheduleChartData,
		options: {
			responsive: true,
			legend: {
				display: false,
			},
			title: {
				display: true,
				text: 'PREVENTIVE MAINTENANCE RESCHEDULE <?php echo date("Y"); ?>',
				padding: 25
			},
			plugins: {
				labels: {
					render: 'value',
					fontColor: '#000'
				}
			},
			scales: {
				xAxes: [{
					ticks: {
						fontSize: 9
					}
				}]
			}
		}
	});

});	
</script>