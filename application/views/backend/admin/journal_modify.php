
<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>

	<div class="tab-pane box" id="add" style="padding: 5px">
			<?php echo form_open(base_url() . "index.php?admin/journal/do_update/$id" , array('class' => 'form-inline form-groups validate', 'enctype' => 'multipart/form-data', 'style' => 'text-align:center;'));?>
<?php 
	foreach ($transaction as $row) {?>
		
	
				<div class="row" style="margin-bottom: 10px">
					<div class="col-md-3"></div>
					<div class="col-md-3">
							<div class="form-group">
							<label><?php echo get_phrase('particulars');?></label>
							<input type="text" class="form-control" name="particular" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row->description; ?>"/>
							</div>
					</div>
					<div class="col-md-3">
							<div class="form-group">
							<label><?php echo get_phrase('select_date');?></label>
							<input type="text" class=" form-control datepicker" name="timestamp" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $row->tdate; ?>"/>
							</div>
					</div>	
					<div class="col-md-3"></div>
				</div>
<?php
}
?>

				<div class="row">
				<table style="background-color:#ccffcc;width:50%; float:left;" id="d">
	
					<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA; text-align: center;">Debits</th></tr>
			
					<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
<?php 
//foreach ($debitrow as $debitrow) {
	foreach ($debitDatas as $debitDatas) {
		
	
	?>



					<tr id="student_entry1">
						<td>
							
							<select name="userd" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_user');?></option>
		                          <?php 
		                             $customers = $this->db->get_where('user')->result_array();
		                             foreach ($customers as $row):
		                             ?>
		                       <option value="<?php echo $row['user_id'];?> " <?php if($row['user_id'] == $debitDatas['userId'])
		                       echo 'selected'; ?>>


		                       <?php echo $row['user_name'];?>
		                       	
		                       </option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select name="accountd[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_account');?></option>
		                          <?php 
		                             $categories = $this->db->get_where('account')->result_array();
		                             foreach ($categories as $row):
		                             ?>
		                       <option value="<?php echo $row['componentId'];?>"
		                       <?php if($row['componentId'] == $debitDatas['accountId'])
		                       echo 'selected'; ?>>
		                       <?php echo $row['description'];?>
		                       	
		                       </option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select id="item_selector_holder" name="itemd[]" class="form-control" style="width: 100px; margin-left: 0px;">
							<option value="">
							<?php echo get_phrase('select_item');?>
								
							</option>
							<?php $categories = $this->db->get('item')->result_array();
			    				foreach ($categories as $row):
			    			?>		
			    				<option value="<?php echo $row['componentId'];?>" <?php if($row['componentId'] == $debitDatas['itemId'])
			    				echo 'selected'; ?>>
			    				<?php echo $row['itemName'];?>
			    					
			    				</option>
			                <?php endforeach;?>
							</select>
						</td>
						<td>
						<input type="text" name="unitPriced[]" class="unitPriced"style="width: 60px;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $debitDatas['unitPrice']; ?>"/>
						</td>
						<td>
						<input type="text" name="quantityd[]" style="width: 50px;" onchange="calcSum1();" class="quantityd"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $debitDatas['quantity']; ?>"/>
						</td>
						<td>
						<input type="text" name="amountd[]" value="<?php echo $debitDatas['quantity']*$debitDatas['unitPrice'] ?>" style="width: 60px;" readonly>			
						<input onclick="deleteParentElement(this);" type="button" style="width: 3px; text-align: left; font-weight: bold;font-size: large;" value="-"/>						
						</td>
					</tr>
<?php
	}
//}
 ?>
					<tr><th> <input type="button"  onclick="append_debit_table_row();" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="total">0.00</th></tr>
			
	
				</table>

					<table style="background-color:#ffe6e6;width:50%;" id="c">
				
						<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA; text-align: center;">Credits</th></tr>
				
						<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
<?php 
//foreach ($creditrow as $creditrow) {
	foreach ($creditDatas as $creditDatas) {
		
	
	?>
						<tr id="student_entry2">
						<td>
							<select name="userc" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_user');?></option>
		                          <?php 
		                             $customers = $this->db->get_where('user')->result_array();
		                             foreach ($customers as $row):
		                             ?>
		                       <option value="<?php echo $row['user_id'];?>" <?php if($row['user_id'] == $creditDatas['userId']) echo 'selected'; ?>>
		                       <?php echo $row['user_name'];?>
		                       	
		                       </option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select name="accountc[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_account');?>
		                       	
		                       </option>
		                          <?php 
		                             $categories = $this->db->get_where('account')->result_array();
		                             foreach ($categories as $row):
		                             ?>
		                       <option value="<?php echo $row['componentId'];?>" <?php if($row['componentId'] == $creditDatas['accountId']) echo 'selected'; ?>>
		                       <?php echo $row['description'];?>
		                       	
		                       </option>
		                       <?php endforeach;?>
		        			</select>
		
						</td>
						<td>
							<select id="item_selector_holder" name="itemc[]" class="form-control" style="width: 100px; margin-left: 0px;">
							<option value="">
							<?php echo get_phrase('select_item');?>
								
							</option>
							<?php $categories = $this->db->get('item')->result_array();
			    				foreach ($categories as $row):
			    			?>		
			    				<option value="<?php echo $row['componentId'];?>" <?php if($row['componentId'] == $creditDatas['itemId']) echo 'selected'; ?>>
			    				<?php echo $row['itemName'];?>
			    					
			    				</option>
			                <?php endforeach;?>
							</select>
						</td>
						<td>
						<input type="text"  style="width: 60px;" name="unitPricec[]" class="unitPricec" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $creditDatas['unitPrice']; ?>"/>
						</td>
						<td>
						<input type="text" style="width: 50px;"  name="quantityc[]" class="quantityc" onchange="calcSum2()"; data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $creditDatas['quantity']; ?>"/>
						</td>
						<td>
							<input type="text" name="amountc[]" value="<?php echo $creditDatas['quantity']*$creditDatas['unitPrice']; ?>" style="width: 60px;" readonly>
							<input onclick="deleteParentElement(this);" type="button" style="width: 3px; text-align: left; font-weight: bold;font-size: large;" value="-"/>	
					
						</td>
						
					</tr>
<?php
	}
//}
 ?>
						<tr><th> <input onclick="append_credit_table_row();" type="button" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="ttlcr">0.00</th></tr>

				</table>
			</div>
<br>


<br><br>

				<div class="row">
					<div style="text-align: center;">
						<button type="submit" class="btn btn-success" id="submit_button">
							<i class="entypo-check"></i> <?php echo get_phrase('Edit');?>
						</button>
					</div>
				</div>


<?php echo form_close();?>
								
			</div>
</body>
</html>

                  

<script type="text/javascript">
	var blank_student_entry1 ='';
	$(document).ready(function() {
		//$('#bulk_add_form').hide(); 
		blank_student_entry1 = $('#student_entry1').html();
		for ($i = 1; $i<1;$i++) {
			$("#student_entry1").append(blank_student_entry1);
		}
		
	});
	var blank_student_entry2 ='';
	$(document).ready(function() {
		//$('#bulk_add_form').hide(); 
		blank_student_entry2 = $('#student_entry2').html();
		for ($i = 1; $i<1;$i++) {
			$("#student_entry2").append(blank_student_entry2);
		}
		
	});
	function append_debit_table_row()
	{
		$("#d tr:last").before('<tr>' +blank_student_entry1 +'</tr>');
	}
	function append_credit_table_row()
	{
		$("#c tr:last").before('<tr>' +blank_student_entry2 +'</tr>');
	}

	// REMOVING INVOICE ENTRY
	function deleteParentElement(item)
	{
		//n.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		$("tr").has($(item)).remove();

	}

	function get_expense_item() {
		
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_expense_item/',
            success: function(response)
            {				
                jQuery('#item_selector_holder').html(response);
            }
        });

	}
	function calcSum1(){
		var sum = 0.0;
		var total = 0.0;
		$("#d tr").each(function(){            
		       var $this = $(this);
		       var quantity = parseInt($this.find('.quantityd').val(),10);
		       var unitprice = parseInt($this.find('.unitPriced').val(),10);		       		       
		       $this.find('[name="amountd[]"]').val(quantity * unitprice );	
		       sum = Number(quantity) * Number(unitprice); 
		       total = total + sum;     	       		       
		 });
		$('#total').val(sum);		
	}
	function calcSum2(){
		var sum = 0.0;
		var total = 0.0;
		$("#c tr").each(function(){            
		       var $this = $(this);
		       var quantity = parseInt($this.find('.quantityc').val(),10);
		       var unitprice = parseInt($this.find('.unitPricec').val(),10);		       		       
		       $this.find('[name="amountc[]"]').val(quantity * unitprice );	
		       sum = Number(quantity) * Number(unitprice); 
		       total = total + sum;     	       		       
		 });
		$('#total').val(sum);		
	}

	function loadTransaction(ajxurl){
		$.ajax({
            url: ajxurl,
            dataType: 'json',
            success: function(data)
            {		
				//var componentId = data.transaction.componentId[0];
				//alert(componentId);
				jump(add);
            }
        });
}
    
</script>


