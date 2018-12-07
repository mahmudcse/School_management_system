<?php
$edit_data = $this->db->get_where('student_feeconfig' , array('studentId' => $param2) )->result_array();
$userId = $this->db->get_where('user' , array('reference_id' => $param2) )->row()->user_id;
$transactionInfo = $this->db->get_where('transaction_detail' , array('userId' => $userId, 'type' => -1) )->result_array();


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
<?php echo form_open(base_url() . 'index.php?admin/save_student_fee/create' , array('id' => 'myform'));?>	
	<div class="col-md-12" style="text-align: center;padding: 20px">
		
		<table class="table">
			<thead>
				
			</thead>
			<tbody>
				<tr>
					<td style="text-align: left"></td>
					<td></td>
					<td>Date: <input type="text" class="" name="timestamp" value="<?php echo date('d-m-Y');?>" style="padding-left: 15px" required></td>
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
	
	<div class="col-md-12">
	<table class="table table-bordered">
		<thead>
    <tr>
      <th></th>
      <th>Fee Name</th>
      <th style="text-align: center; padding-right: 0px">Amount</th>
      
    </tr>
  	</thead>
  	<tbody>
  	<?php 
  	$count = 0;
  	foreach ( $edit_data as $row):
  		$found = false;
  		
 		foreach($transactionInfo as $transaction):
		if($transaction['itemId'] == $row['itemId'] && $transaction['month'] == date('n', strtotime($row['month'])) && $transaction['year'] == $row['year'] ) {
			$found = true;
		}		
		endforeach;
?>
    <tr>
      <th>
      	<?php if($found==false) {?>
      	<input type="checkbox" name="fee[]" value="<?php echo $count; ?>" onChange="calculate(this)" >
      	<input type="hidden" name="<?php echo month_.$count ;?>" value="<?php echo $row['month'] ?>">
	    <input type="hidden" name="<?php echo year_.$count;?>" value="<?php echo $row['year'] ?>">
	    <input type="hidden" name="<?php echo item_.$count;?>" value="<?php echo $row['itemId'] ?>">
	    <input type="hidden" name="<?php echo amount_.$count;?>" id="amount" value="<?php echo $row['amount'] ?>">
	    <input type="hidden" name="student_id" value="<?php echo $student_id; ?>">
	    <input type="hidden" name="session_id" value="<?php echo $session_id; ?>">
	    <input type="hidden" name="<?php echo fee_id_.$count;?>" value="<?php echo $row['id']; ?>">
      	<?php } ?>
      </th>
      
      <th>
      <?php echo $row['studentFeeName'] ?>
      
      </th>
      
      <th style="text-align: right; padding-right: 10px"><input type="text" readonly value="<?php echo number_format((float)$row['amount'], 2, '.', '') ?>" style="text-align: right; padding-right: 10px; ">TK.
      
      </th>
      
    </tr>
    <?php
    $count++;
endforeach;
?>
	<tr>
		<td></td>
		<td style="text-align: right; padding-right: 10px;">Total Tk.</td>
		<td id="totalfee" style="text-align: right; padding-right: 20px; color: #000;">Tk.</td>
	</tr>
  	</tbody>
	</table>
	<input type="hidden" name="student_id" value="<?php echo $student_id ?>">
	<input type="hidden" name="item_id" value="<?php echo $edit_data['itemId'] ?>">
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-5">
			<input type="submit" name="submit" value="Save">
		</div>
	</div>

</div>

<?php echo form_close()?>
</div>						
 <script type="text/javascript">

    var total = 0;

    function calculate(item){
        if(item.checked){
           total+= $("#amount").val();
        }else{
           total-= $("#amount").val();
        }
        //alert(total);
        //document.getElementById('totalfee').innerHTML = total + " /-";
    }	
	
</script>


