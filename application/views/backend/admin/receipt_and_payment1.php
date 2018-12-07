<br>
<div class="container">
	<div class="col-md-offset-3">
  <?php echo form_open(base_url() . 'index.php?admin/receipt_and_payment', array('class' => 'form-inline validate'));?>
    <div class="form-group">
      <label for="startdate">From:</label>
      <input type="text" class="form-control  datepicker" name="start_date" placeholder="Start date">
    </div>
    <div class="form-group">
      <label for="enddate">To:</label>
      <input type="text" class="form-control  datepicker" name="end_date" placeholder="End date">
    </div>
    <input type="submit" class="btn btn-info" value="Show">

  <?php echo form_close() ?>
  </div>
</div>
                <hr>
<div class="row">
                <div class="col-md-6">
                	<div id="rec">
		                <div style="text-align: center; font-size: 16px; color: #000;">
		                <h3>Receipts</h3>
		                <p><?php echo $start_date.' - '.$end_date;?></p>
		                </div >
						<table class="table table-bordered">
		               
		                    <thead>
		                        <tr>
		                        	<th><div><?php echo get_phrase('s.l')?></div>
		                            <th><div><?php echo get_phrase('particulars');?></div></th>
		                            <th>Amount</th>
		                            
		                        </tr>
		                    </thead>
		                    <tbody>
		                    <tr><td>1</td><td>Opening Banlace</td><td><?php  echo $obData['ob'];?></td></tr>
		                    <?php 
		                    $totalRec = 0.0;
		                    
		                    $count=2; 
		                    // echo "<pre>";
		                    // print_r($recData);
		                    // echo "</pre>";

		                    
		                    foreach ( $recBalanceData as $value ) {
		                    		
		                    		$totalRec += $value->balance;
									
		                    ?>	
		                        <tr>
		                        	<td><?php echo $count++;?></td>
		                        	<td><?php echo $value->uniqueCode; ?></td>                       	
		                        	<td><?php echo $value->balance; ?> </td> 
		                        </tr>
		
		                    <?php } ?>  
		                        <tr>
		                        	<td></td>
		                        	<td><?php echo get_phrase('total')?></td>
		                        	<td><?php echo $totalRec; ?></td>
		                        </tr>                     
		                    </tbody>
		                </table>
					</div>
					<div class="col-sm-offset-3 col-sm-5">
					<input class="btn btn-primary"type="button" name="submit" value="Print" onclick="printDiv1()">
					</div>
				</div>
				
				<div class="col-md-6">
					<div id="pay">
						<div style="text-align: center; font-size: 16px; color: #000;">
		                <h3>Payments</h3>
		                <p><?php echo $start_date.' - '.$end_date;?></p>
		                </div>
						<table class="table table-bordered">
		                    <thead>
		                        <tr>
		                        	<th><div><?php echo get_phrase('s.l')?></div>
		                            <th><div><?php echo get_phrase('particulars');?></div></th>
		                            <th><?php echo get_phrase('amount')?></th>
		                        </tr>
		                    </thead>
		                    <tbody>
	                       <?php 
		                    $totalPay = 0.0;
		                    $totalCr = 0.0;
							$count=1; 
		                    foreach ( $payBalanceData as $value ) {
		                    		$totalPay += $value->balance;
		                    ?>	
		                        <tr>
		                        	<td><?php echo $count++;?></td>
		                        	<td><?php echo $value->uniqueCode; ?></td>                       	
		                        	<td><?php echo $value->balance; ?> </td>
		                        </tr>
		                        
		                    <?php } ?> 
		                    <tr>
		                    	<td></td>
		                    	<td><?php echo get_phrase('total')?></td>
		                    	<td><?php echo $totalPay; ?></td>
		                    </tr>
		                    </tbody>
		                </table>                
					</div>
					<div class="col-sm-offset-3 col-sm-5">
						<input class="btn btn-primary"type="button" name="submit" value="Print" onclick="printDiv2()">
					</div>
				</div>
</div>
<div class="row">
		
</div>
<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">
function printDiv1() {    
    var printContents = document.getElementById('rec').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }
function printDiv2() {    
    var printContents = document.getElementById('pay').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }    		
</script>

