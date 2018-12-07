<hr />
<div class="row">
	<div class="col-md-12">
			
			<ul class="nav nav-tabs bordered">
				<li class="active">
					<a href="#unpaid" data-toggle="tab">
						<span class="hidden-xs"><?php echo get_phrase('create_single_invoice');?></span>
					</a>
				</li>
			</ul>
			
			<div class="tab-content">
            <br>
				<div class="tab-pane active" id="unpaid">

				<!-- creation of single invoice -->
				<?php echo form_open(base_url() . 'index.php?admin/payment/filter' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
				<input name="operation" value="" type="hidden"/>
				<div class="row">
					<div class="col-md-6">
	                        <div class="panel panel-default panel-shadow" data-collapsed="0">
	                            <div class="panel-heading">
	                                <div class="panel-title"><?php echo get_phrase('invoice_informations');?></div>
	                            </div>
	                            <div class="panel-body">
	                                
	                                <div class="form-group">
	                                    <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
	                                    <div class="col-sm-9">
	                                        <select name="class_id" class="form-control selectboxit"
	                                        	onchange="this.form.submit()">
	                                        	<option value="-1"><?php echo get_phrase('select_class');?></option>
	                                        	<?php 
	                                        		foreach ($classes as $row):
	                                        	?>
	                                        	<option <?php if($class_id == $row['class_id']){echo "selected=\"selected\"";}?> value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
	                                        	<?php endforeach;?>
	                                            
	                                        </select>
	                                    </div>
	                                </div>

	                                <div class="form-group">
		                                <label class="col-sm-3 control-label"><?php echo get_phrase('student');?></label>
		                                <div class="col-sm-9">
		                                    <select name="student_id" class="form-control" style="width:100%;" onchange="this.form.submit();">
		                                        <option value="-1"><?php echo get_phrase('select_class_first');?></option>
		                                    	<?php  foreach ($students as $row):?>
		                                    	<option <?php if($student_id == $row['student_id']){echo "selected=\"selected\"";}?> value="<?php echo $row['student_id'];?>"><?php echo $row['name'];?></option>
		                                    	<?php endforeach;?>
		                                    </select>
		                                </div>
		                            </div>

	                                <div class="form-group">
	                                    <label class="col-sm-3 control-label"><?php echo get_phrase('description');?></label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control" name="description"/>
	                                    </div>
	                                </div>

	                                <div class="form-group">
	                                    <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="datepicker form-control" name="date"
                                                data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
	                                    </div>
	                                </div>
	                                
	                                <div class="form-group">
	                                    <label class="col-sm-3 control-label"><?php echo get_phrase('total_amount');?></label>
	                                    <div class="col-sm-9">
	                                        <input type="text" class="form-control" name="total" id="total"
                                                data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="0.0"/>
	                                    </div>
	                                </div>
	                                
	                                <div class="form-group">
	                                	<input type="hidden" name="feecnt" id="cnt" value="<?php  echo  count($payments);?>"> 
						              	<table style="width:90%;margin: auto;">
						                    	<thead>
						                    		<tr style="background-color: silver;">
						                    			<th> &nbsp; </th>
						                    			<th> Item </th>
						                    			<th> Month </th>
						                    			<th> Year </th>
						                    			<th> Amount </th>
						                    			<th> Paid </th>
						                    		</tr>
						                    	</thead>
						                    	<tbody>
						                    	<?php
						                    	$cnt = 0;
						                    	$amount = 0.0;
						                    	$paid = 0.0;
						                    	foreach ($payments as $pmt):
						                    	$cnt++;
						                    	
						                    	$amount += $pmt['amount'];
						                    	$paid += $pmt['paid_amount'];
						                    	?>
						                    		<tr>
						                    			<td>
						                    				<?php if($pmt['paid_amount'] == 0.0){?>
						                    				<input onchange="calc();"  name="item_<?php echo $cnt;?>" value="<?php echo $pmt['item_id'];?>" id="item_<?php echo $cnt;?>" type="checkbox">
						                    				<?php }?>
						                    				<input value="<?php  echo $pmt['amount'];?>" name="amount_<?php echo $cnt;?>" id="amount_<?php echo $cnt;?>" type="hidden">
						                    				<input value="<?php  echo $pmt['paid_amount'];?>" name="paid_amount_<?php echo $cnt;?>" type="hidden">
						                    				<input value="<?php  echo $pmt['month'];?>" name="month_<?php echo $cnt;?>" type="hidden">
						                    				<input value="<?php  echo $pmt['year'];?>" name="year_<?php echo $cnt;?>" type="hidden">
						                    			</td>
						                    			<td><?php echo $pmt['itemName'];?></td>
						                    			<td><?php echo $pmt['month'];?></td>
						                    			<td><?php echo $pmt['year'];?></td>
						                    			<td><?php echo $pmt['amount'];?></td>
						                    			<td><?php echo $pmt['paid_amount'];?></td>
						                    		</tr>
						                    	<?php endforeach;?>
						                    		<tr style="background-color: silver;">
						                    			<td colspan="4" style="font-weight: bold;"> Total </td>
						                    			<td style="font-weight: bold;"> <?php echo $amount;?> </td>
						                    			<td style="font-weight: bold;"> <?php echo $paid;?> </td>
						                    		</tr>
						                    	</tbody>
						                    </table>
	                                </div>
	                                
	                                <!-- creation of single invoice -->
									<div class="form-group">
				                  		<div class="col-sm-5">
				                       		<button type="button" onclick="submitIt(this.form);" class="btn btn-info"><?php echo get_phrase('add_invoice');?></button>
				                      	</div>
				                  	</div>
                        
	                            </div>
	                        </div>
	                    </div>
                    </div>
                    
                    


	                </div>
	              
	              	
	              	

				
                        
                      	<?php echo form_close();?>
				</div>
				<div >
				</div>
			
			</div>
		</div>

<script type="text/javascript">
	function calc() {
		var amt = 0.0;
		var cnt = $('#cnt').val();
		for (i = 1; i <= cnt; i++) {
			if($("#item_"+i).prop( "checked")){
				amt -= -$("#amount_"+i).val();
			}
			
				
		}
		$("#total").val(amt);
	}
	function submitIt(frm) {
		frm.operation.value = 'save_payment';

		frm.submit();
	}
</script>