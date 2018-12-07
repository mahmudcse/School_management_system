<style type="text/css">
				
            	table#rpt td { 
				    padding: 0 5px;
				    
				    border-bottom: 1px dotted #999999;
				}
				table#rpt tr:nth-child(2n+1) td {
					background-color:#EEEEEE;
				}
				table#rpt tr:nth-child(2n) td {
					background-color:#EEEEEE;
				}
				table#rpt th { 
				    padding: 0 5px;
				    background-color:#DDDDDD;
				    border-bottom: 1px dotted #999999;
				    border-radius:0;
				}
				table#rpt { 
				    border-spacing: 0;
				    border-collapse: separate;
				}
</style>
<div class="box-content padded">
	<div class="tab-content">
		<!----TABLE LISTING STARTS--->
		<div class="tab-pane box active" id="list">
			<div id="print">

				<table id="rpt" style="width: 60%; margin: auto; min-width: 500px;">

					<tr>
						<td colspan="2" align = "center">
							<h1>Income Statement</h1>
	            				Date: <?php echo $start_tdate.' to '.$end_date;?>
	            				<br />
						<br />
						</td>
					</tr>
					<tr>

	            			<th style="text-align: left;padding-left: 20px;">Revenue</th>

	            			<th style="text-align: right;padding-right: 20px;"><?php echo $revenue;?></th>

	            		</tr>



	            		<?php foreach ($salesdata as $row):



	            		if($row->sales > 0){

	            		?>

	            		<tr class="sales">

	            			<td style="text-align: left;padding-left: 40px;"><?php echo $row->itemName;?></td>

	            			<td style="text-align: right;padding-right: 40px;"><?php echo $row->sales;?></td>

	            		</tr>

	            		<?php } endforeach;?>

	           			<tr>

			   	        	<th style="text-align: left;padding-left: 20px;">Operating Expense</th>

			   	            <th style="text-align: right;padding-right: 20px;"><?php echo $opexp;?></th>

			   	        </tr>

			   	           <?php foreach ($opexpdata as $row):

			   	           if($row->opexp > 0){

			   	          ?>

			   	           <tr class="exp">

			   	           	<td style="text-align: left;padding-left: 40px;"><?php echo $row->accountName;?></td>

			   	            <td style="text-align: right;padding-right: 40px;"><?php echo $row->opexp;?></td>

			   	        </tr>

	            		<?php } endforeach;?>
	            		<tr>
			   	        	<th style="text-align: left;">Gross Income</th>
			   	            <th style="text-align: right;"><?php echo $grossProfit;?></th>
			   	        </tr>
			   	        <tr>
			   	        	<th style="text-align: left;padding-left: 20px;">Other Expense</th>
			   	            <th style="text-align: right;padding-right: 20px;"><?php echo $otherexp;?></th>
			   	        </tr>
			   	           <?php foreach ($expdata as $row):
			   	           if($row->exp > 0){
			   	          ?>
			   	           <tr class="opexp">
			   	           	<td style="text-align: left;padding-left: 40px;"><?php echo $row->accountName;?></td>
			   	            <td style="text-align: right;padding-right: 40px;"><?php echo $row->exp;?></td>
			   	        </tr>
	            		<?php } endforeach;?>
						<tr>
			   	        	<th style="text-align: left;"><h4>Net Income</h4></th>
			   	            <th style="text-align: right;"><?php echo "<h4><u>".$netProfit."</h4></u>";?></th>
			   	        </tr>
				</table>
			</div>
			<div class="col-md-offset-6" >
				<br/>
				<input type="button" class="btn btn-primary" value="Print" onclick="printDiv()">
			</div>
		</div>
		
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