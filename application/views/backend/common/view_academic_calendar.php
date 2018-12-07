<style>
table {
    border-collapse: collapse;
    width: 100%;
}

th, td {
    text-align: left;
    padding: 8px;
}
th {
    background-color: #4CAF50;
    color: white;
}
</style>
<hr />
<div class="row">
	<div class="col-md-offset-2 col-md-8" id="print">
		<!----TABLE LISTING STARTS-->
		<div style="text-align: center;">
			<h2><?php echo $system_name;?></h2>
			<h3><?php echo get_phrase('academic_calendar_for ').date('Y')?></h3>
		</div>	
			<table  class="table">
				<caption></caption>
				<thead>
					<tr>
						<th>Event</th>
						<th>Date</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($events as $row):?>
					<tr>
						<td><?php echo $row['event']; ?></td>
						<td><?php if($row['end_date']) {
							echo date('M-d', strtotime($row['start_date'])).' - '.date('M-d',strtotime($row['end_date'])); 
						} else {
							echo date('M-d', strtotime($row['start_date']));
						}
						?></td>
					</tr>
				<?php endforeach; ?>		
				</tbody>
			</table>
		<!----TABLE LISTING ENDS--->
		
	</div> 
	<div class="col-md-offset-2 col-md-8">
		<input type="button" class="btn btn-info" value="Print" onclick="printDiv();">
	</div>
</div>
<script type="text/javascript">     
function printDiv() {    
    var printContents = document.getElementById('print').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }
</script>