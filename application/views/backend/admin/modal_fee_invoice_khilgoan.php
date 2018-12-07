
<?php
$system = $this->db->get('settings')->result_array();
// $this->db->select('transaction.uniqueCode');
// $this->db->from('transaction');
// $this->db->join('fee_record', 'fee_record.transaction_id = transaction.componentId');
// $this->db->where('fee_record.fee_record_id', $param3);
$receiptNo =$this->db->get_where('fee_record', array('fee_record_id' => $param3))->row()->receipt_no;

$this->db->select('transaction.tdate');
$this->db->from('transaction');
$this->db->join('fee_record', 'fee_record.transaction_id = transaction.componentId');
$this->db->where('fee_record.fee_record_id', $param3);
$tdate =$this->db->get()->row()->tdate;

$condition = array('fee_record.fee_record_id' => $param3, 'transaction_detail.type' => -1);
$this->db->select('fee_record.*, transaction_detail.*, item.itemName');
$this->db->from('fee_record');
$this->db->join('transaction_detail', 'transaction_detail.transactionId = fee_record.transaction_id');
$this->db->join('item', 'item.componentId = transaction_detail.itemId');
$this->db->where($condition);
$this->db->order_by('transaction_detail.month');

$transaction_info = $this->db->get()->result_array();

$this->db->select('enroll.*, student.name, class.name as class, section.name as section');
$this->db->from('enroll');
$this->db->join('student', 'enroll.student_id = student.student_id');
$this->db->join('class', 'enroll.class_id = class.class_id');
$this->db->join('section', 'enroll.section_id = section.section_id');
$this->db->where('enroll.student_id', $param2);
$studentinfo = $this->db->get()->row_array();

$student_id = $studentinfo['student_id'];
$session_id = $studentinfo['session_id'];

?>
<div class="row">
<div id="print">
	
	<div class="col-md-10" style="text-align: center;padding: 20px">
		<div class="col-md-offset-2 col-md-8 col-md-offset-2" style="color: #000; font-size: 18px; font-weight: bold;">
			<?php 
			// foreach ($system as $row) {
			// 	if($row['type'] == 'system_name')
			// 		echo $row['description'];	
			// }
			?>
			<p>
			<?php 
			// foreach ( $system as $row ) {
			// 	if ($row ['type'] == 'address')
			// 		echo $row ['description'];
			// }
			?>	
			</p>
		</div>
		<table class="table" width="100%">
			
			<thead>
				
			</thead>
			<tbody>
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="20%">Receipt No: <?php echo $receiptNo; ?></td>
					<td width="20%"></td>
					<td width="20%">Date: <?php echo date('d-m-Y', strtotime($tdate)); ?></td>
				</tr>
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="20%">Name: <?php echo $studentinfo['name']?></td>
					<td width="20%">&nbsp;</td>
					<td width="20%">&nbsp;</td>			
				</tr>
				<tr>
					<td width="10%">&nbsp;</td>
					<td width="20%">Class: <?php echo $studentinfo['class']?></td>
					<td width="20%">Section: <?php echo $studentinfo['section']?></td>
					<td width="20%">Roll: <?php echo $studentinfo['roll']?></td>
				</tr>
			</tbody>
		</table>
		
	</div>
	
<div>

<br><br><br>
	
	<table width="100%">
		<thead>
		    <tr>
		      <th width="20%">&nbsp;</th>
		      <th width="20%" style="text-align: left;">Description</th>
		      <th width="20%">&nbsp;</th>
		      <th width="20%" style="text-align: center;">TK.</th>
		      <th width="20%">&nbsp;</th>
		    </tr>
  		</thead>
  	<tbody>
	  	<?php 

	  	$count = 0;
	  	$total = 0;
		foreach ( $transaction_info as $row):
		$total += $row['unitPrice'];
		?>
	    <tr>
	      <th width="20%">&nbsp;</th>
	     
	      <th width="20%" style="text-align: left;">
	      
	      <?php 
			echo $row['itemName'].'-'.date('F', mktime(0,0,0,$row['month'], 10));			
		  ?>
	      
	      </th>
	      <th width="20%">&nbsp;</th>
	      <th width="20%" style="text-align: right; padding-right: 10px">

	      	<input type="text" readonly value="<?php echo number_format((float)$row['unitPrice'], 2, '.', '') ?>" style="text-align: center; border: 0px solid; color: #000; padding-right: 10px; ">
	      
	      </th>
	      <th width="20%">&nbsp;</th>
	      
	    </tr>
	    <?php
	    $count++;
	endforeach;
	?>
		<tr>
			<th width="20%">&nbsp;</th>
			<th width="20%" style="text-align: left;">Total Tk.</th>
			<th width="20%">&nbsp;</th>
			<th width="20%" id="totalfee" style="text-align: center;"><?php echo $total;?>
				
			</th>
			<th width="20%">&nbsp;</th>
		</tr>

		<tr>
			<td width="20%">&nbsp;</td>
			<td colspan="4">TK. In Words :
				<?php 	
					$result = convert_number_to_words($total);
					echo get_phrase($result);
			 	?>
			</td>
		</tr>

  	</tbody>
	</table>

	<div>
		<table style="margin:100px 0px 20px 0px;width:100%;">
	        <tr>
	        	<td width="20%">&nbsp;</td>
	            <td width="20%" align="center" style="border-top: 1px solid #ddd;">
	                <?php 
	                    $teacher = $this->db->get_where('codes', array('key_name' => 'signature.reciept.student'))->row()->value;
	                    echo $teacher;

	                 ?>
	            </td>
	            <td width="20%">&nbsp;</td>
	            <td width="20%" align="center" style="border-top: 1px solid #ddd;">
	                <?php 
	                    $head_master = $this->db->get_where('codes', array('key_name' => 'signature.bank'))->row()->value;
	                    echo $head_master;

	                 ?>

	            </td>
	            <td width="20%">&nbsp;</td>
	        </tr>
    	</table>  
	</div>
</div>
</div>

		<div align="center">
			<input class="btn btn-primary"type="button" name="submit" onclick="printDiv();" value="Print" id="printInvoice">
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

