<?php
$system = $this->db->get('settings')->result_array();
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

$this->db->select('enroll.*, student.name, class.name as class, section.name as section, cg.group_name');
$this->db->from('enroll');
$this->db->join('student', 'enroll.student_id = student.student_id');
$this->db->join('class', 'enroll.class_id = class.class_id');
$this->db->join('section', 'enroll.section_id = section.section_id');
$this->db->join('class_group cg', 'cg.id = section.group_id', 'inner');
$this->db->where('enroll.student_id', $param2);
$studentinfo = $this->db->get()->row_array();

$student_id = $studentinfo['student_id'];
$session_id = $studentinfo['session_id'];
$group_name = $studentinfo['group_name'];

?>
<div id="print">
				<div style="text-align: center; font-size: 9px; font-weight: 50">
					<table width="100%" style="font-size: 10px;">
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>



						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%" style="font-size: 10px;">Receipt: <?php echo $receiptNo; ?></td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Date: <?php echo date('d-m-Y', strtotime($tdate)); ?></td>

							<td width="10%">&nbsp;</td>

							<td width="16%" style="font-size: 10px;">Receipt: <?php echo $receiptNo; ?></td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Date: <?php echo date('d-m-Y', strtotime($tdate)); ?></td>
							<td width="10%">&nbsp;</td>	
						</tr>

						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%">Name: <?php echo $studentinfo['name']?></td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Section: <?php echo $studentinfo['section']?></td>

							<td width="10%">&nbsp;</td>

							<td width="16%">Name: <?php echo $studentinfo['name']?></td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Section: <?php echo $studentinfo['section']?>
								
							</td>
							<td width="10%">&nbsp;</td>
							
						</tr>

						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%">
							Class: <?php echo $studentinfo['class']?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Roll: <?php echo $studentinfo['roll']?></td>

							<td width="10%">&nbsp;</td>

							<td width="16%">
							Class: <?php echo $studentinfo['class']?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Roll: <?php echo $studentinfo['roll']?></td>
							<td width="10%">&nbsp;</td>
							
						</tr>
<?php if($studentinfo['class'] == 'NINE' || $studentinfo['class'] == 'TEN'): ?>
						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%">
								<?php echo $group_name; ?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%">&nbsp;</td>

							<td width="10%">&nbsp;</td>

							<td width="16%">
							<?php echo $group_name; ?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%">&nbsp;</td>
							<td width="10%">&nbsp;</td>
							
						</tr>
<?php endif; ?>
						
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="8">&nbsp;</td>
						</tr>

						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%">Description</td>
							<td width="5%">&nbsp;</td>

							<td width="14%">Tk.</td>

							<td width="10%">&nbsp;</td>

							<td width="16%">Description</td>
							<td width="5%">&nbsp;</td>
							<td width="14%">Tk.</td>
							<td width="10%">&nbsp;</td>
							
						</tr>
						<?php 

					  	$count = 0;
					  	$total = 0;
						foreach ( $transaction_info as $row):
						$total += $row['unitPrice'];
						?>

						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%" style="font-size: 9px;">
								<?php 
									echo $row['itemName'].'-'.date('M', mktime(0,0,0,$row['month'], 10));			
								  ?>
							</td>
							<td width="5%">&nbsp;</td>

							<td width="14%">
								<input type="text" readonly value="<?php echo number_format((float)$row['unitPrice'], 2, '.', '') ?>" style="text-align: left; border: 0px solid; color: #000; padding-right: 10px; ">
							</td>

							<td width="10%">&nbsp;</td>

							<td width="16%" style="font-size: 9px;">
								<?php 
									echo $row['itemName'].'-'.date('M', mktime(0,0,0,$row['month'], 10));			
								  ?>
							</td>
							<td width="5%">&nbsp;</td>

							<td width="14%">
								<input type="text" readonly value="<?php echo number_format((float)$row['unitPrice'], 2, '.', '') ?>" style="text-align: left; border: 0px solid; color: #000; padding-right: 10px; ">
							</td>
							<td width="10%">&nbsp;</td>
							
						</tr>

						<?php
						    $count++;
						endforeach;
						?>


						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%">Total Tk.</td>
							<td width="5%">&nbsp;</td>
							<td width="14%"><?php echo $total;?></td>

							<td width="10%">&nbsp;</td>

							<td width="16%">Total Tk.</td>
							<td width="5%">&nbsp;</td>
							<td width="14%"><?php echo $total;?></td>
							<td width="10%">&nbsp;</td>
							
						</tr>

						<tr>
							<td width="10%">&nbsp;</td>
							<td colspan="3">
								TK. In Words :
								<?php 	
									$result = convert_number_to_words($total);
									echo get_phrase($result);
							 	?>
							</td>
							<td colspan="1">&nbsp;</td>
							<td colspan="3">
								TK. In Words :
								<?php 	
									$result = convert_number_to_words($total);
									echo get_phrase($result);
							 	?>
							</td>
							<td width="10%">&nbsp;</td>
						</tr>

						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
							<td colspan="2">&nbsp;</td>
							<td colspan="3">&nbsp;</td>
						</tr>


						<tr>
							<td width="10%">&nbsp;</td>
							<td width="16%" align="center" style="border-top: 1px solid #ddd;">
								<?php 
				                    $teacher = $this->db->get_where('codes', array('key_name' => 'signature.reciept.teacher'))->row()->value;
				                    echo $teacher;
				                 ?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%" align="center" style="border-top: 1px solid #ddd;">
								<?php 
				                    $head_master = $this->db->get_where('codes', array('key_name' => 'signature.reciept.headmaster'))->row()->value;
				                    echo $head_master;

				                 ?>
							</td>

							<td width="10%">&nbsp;</td>

							<td width="16%" align="center" style="border-top: 1px solid #ddd;">
								<?php 
				                    $teacher = $this->db->get_where('codes', array('key_name' => 'signature.reciept.teacher'))->row()->value;
				                    echo $teacher;

				                 ?>
							</td>
							<td width="5%">&nbsp;</td>
							<td width="14%" align="center" style="border-top: 1px solid #ddd;">
								<?php 
				                    $head_master = $this->db->get_where('codes', array('key_name' => 'signature.reciept.headmaster'))->row()->value;
				                    echo $head_master;

				                 ?>
							</td>
							<td width="10%">&nbsp;</td>
							
						</tr>


					</table>
				</div>
			</div>






		<div align="center">
			<input class="btn btn-primary"type="button" name="submit" onclick="printDiv();" value="Print" id="printInvoice">
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

