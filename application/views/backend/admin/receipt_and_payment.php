<br>
<div class="container">
	<div class="col-md-offset-3">
  <?php echo form_open(base_url() . 'index.php?admin/receipt_and_payment', array('class' => 'form-inline validate'));?>
    <div class="form-group">
      <label for="startdate">From:</label>
      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" value="<?php echo date('d-m-Y', strtotime($start_date));  ?>" name="start_date" placeholder="Start date">
    </div>
    <div class="form-group">
      <label for="enddate">To:</label>
      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" value="<?php echo date('d-m-Y', strtotime($end_date));  ?>" name="end_date" placeholder="End date">
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
		                <p><?php echo date('d-m-Y', strtotime($start_date)).' --- '.date('d-m-Y', strtotime($end_date));?></p>
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
		                    <tr><td>1</td><td>Opening Banlace</td><td style="text-align: right;"><?php  echo $obData['ob'];?></td></tr>
		                    <?php 
		                    $totalRec = 0.0;
		                    $obalance = $obData['ob'];
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
		                        	<td style="text-align: right;"><?php echo $value->balance; ?> </td> 
		                        </tr>
		
		                    <?php } ?>  
		                        <tr>
		                        	<td></td>
		                        	<td><?php echo get_phrase('total')?></td>
		                        	<td style="text-align: right;"><?php echo number_format ($totalRec,2); ?></td>
		                        </tr> 
		                        <tr>
		                        	<td colspan="3">&nbsp;</td>
		                        </tr>
		                        <tr>
		                        	<td></td>
		                        	<td><?php echo get_phrase('Grand Total ( Opening Banlace + Total )')?></td>
		                        	<td style="text-align: right;"><?php $totalRec = $obalance+$totalRec; echo number_format ($totalRec,2); ?></td>
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
		                <p><?php echo date('d-m-Y', strtotime($start_date)).' --- '.date('d-m-Y', strtotime($end_date));?></p>
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
		                        	<td style="text-align: right;"><?php echo $value->balance; ?> </td>
		                        </tr>
		                        
		                    <?php } ?> 
		                    <tr>
		                    	<td></td>
		                    	<td><?php echo get_phrase('total')?></td>
		                    	<td style="text-align: right;"><?php echo number_format ($totalPay,2); ?></td>
		                    </tr>
							<tr>
		                        <td></td>
		                        	<td><?php echo get_phrase('final_balance ( Cash + Bank )')?></td>
		                        	<td style="text-align: right;"><?php echo number_format ($fbData['fb'],2); ?></td>
		                    </tr>
							 <tr>
		                        	<td colspan="3">&nbsp;</td>
		                        </tr>
							<tr>
		                        	<td></td>
		                        	<td><?php echo get_phrase('Grand Total ( total Pay + Final balance )')?></td>
		                        	<td style="text-align: right;"><?php $totalPay = $totalPay+$fbData['fb']; echo number_format ($totalPay,2); ?></td>
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
<!---  DATA TABLE EXPORT CONFIGURATIONS -->                      
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

