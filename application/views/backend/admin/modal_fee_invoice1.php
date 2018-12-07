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
//     		echo '<pre>';
//     		print_r($transaction_info);
//     		echo '</pre>';
//     		exit();
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
			foreach ($system as $row) {
				if($row['type'] == 'system_name')
					echo $row['description'];	
			}
			?>
			<p>
			<?php 
			foreach ( $system as $row ) {
				if ($row ['type'] == 'address')
					echo $row ['description'];
			}
			?>	
			</p>
		</div>
		<table class="table">
			
			<thead>
				
			</thead>
			<tbody>
				<tr>
					<td style="text-align: left">Receipt No: <?php echo $receiptNo; ?></td>
					<td></td>
					<td>Date: <?php echo date('d-m-Y', strtotime($tdate)); ?></td>
				</tr>
				<tr>
					<td style="text-align: left">Name: <?php echo $studentinfo['name']?></td>
					<td></td>
					<td></td>			
				</tr>
				<tr>
					<td style="text-align: left">Class: <?php echo $studentinfo['class']?></td>
					<td style="text-align: left">Section: <?php echo $studentinfo['section']?></td>
					<td>Roll: <?php echo $studentinfo['roll']?></td>
				</tr>
				<tr>
					<td style="text-align: left">Month: <?php echo date('F')?></td>
					<td></td>
					<td>Year: <?php echo date('Y')?></td>
				</tr>
			</tbody>
		</table>
		
	</div>
	
<div class="col-md-10">
	
	<table class="table table-bordered">
		<thead>
    <tr>
    
      <th>Fee Name</th>
      <th style="text-align: center; padding-right: 0px">Amount</th>
      
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
     
      <th>
      
      <?php 
		echo $row['itemName'].'-'.date('F', mktime(0,0,0,$row['month'], 10));			
	  ?>
      
      </th>
      <th style="text-align: right; padding-right: 10px"><input type="text" readonly value="<?php echo number_format((float)$row['unitPrice'], 2, '.', '') ?>" style="text-align: right; border: 0px solid; color: #000; padding-right: 10px; ">TK.
      
      </th>
      
    </tr>
    <?php
    $count++;
endforeach;
?>
	<tr>
		<td style="text-align: right; padding-right: 10px; font-weight: blod; font-size: 14px; color: #000;">Total Tk.</td>
		<td id="totalfee" style="text-align: right; padding-right: 20px; color: #000;"><?php echo $total; ?>Tk.</td>
	</tr>
  	</tbody>
	</table>
</div>
</div>
<div class="container">
<div class="form-group">
		<div class="col-sm-offset-3 col-sm-5">
			<input class="btn btn-primary"type="button" name="submit" value="Print" onclick="printDiv();">
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
	
// 	jQuery(document).ready(function($)
// 			{
// 				var elem = $('#print');
// 				PrintElem(elem);
// 				Popup(data);

// 			});

// 		    function PrintElem(elem)
// 		    {
// 		        Popup($(elem).html());
// 		    }

// 		    function Popup(data) 
// 		    {
// 		        var mywindow = window.open('', 'my div', 'height=400,width=600');
// 		        mywindow.document.write('<html><head><title></title>');
// 		        //mywindow.document.write('<link rel="stylesheet" href="assets/css/print.css" type="text/css" />');
// 		        mywindow.document.write('</head><body >');
// 		        //mywindow.document.write('<style>.print{border : 1px;}</style>');
// 		        mywindow.document.write(data);
// 		        mywindow.document.write('</body></html>');

// 		        mywindow.document.close(); // necessary for IE >= 10
// 		        mywindow.focus(); // necessary for IE >= 10

// 		        mywindow.print();
// 		        mywindow.close();

// 		        return true;
// 		    }
	
</script>

