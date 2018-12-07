<?php 
	$edit_data	=	$this->db->get_where('expense' , array(
		'expense_id' => $param2
	))->result_array();
	foreach ($edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_expense');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/expense/edit/' . $row['expense_id'] , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
	

					<div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category');?></label>
                        <div class="col-sm-6">
                            <select name="expense_category_id" class="form-control selectboxit" required>
                                <option value=""><?php echo get_phrase('select_expense_category');?></option>
                                <?php 
                                	$categories = $this->db->get_where('account', array('category1' => 'EXPENSE'))->result_array();
                                	foreach ($categories as $row2):
                                ?>
                                <option value="<?php echo $row2['componentId_id'];?>"
                                	<?php if ($row['account_id'] == $row2['componentId'])
                                		echo 'selected';?>>
                                			<?php echo $row2['description'];?>
                                				</option>
                            <?php endforeach;?>
                            </select>
                        </div>
                    </div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('item');?></label>
                        
						<div class="col-sm-6">
							<input type="text" class="form-control" name="item" value="<?php echo $row['item'];?>" >
						</div> 
					</div>
					
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>
                        
						<div class="col-sm-6">
							<input type="text" class="form-control" name="amount" value="<?php echo $row['amount'];?>" 
                                data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
						</div> 
					</div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                        <div class="col-sm-6">
                            <input type="text" class="datepicker form-control" name="timestamp"
                            value="<?php echo date('d M,Y', $row['timestamp']);?>"
                                data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" />
                        </div>
                    </div>
                    
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('update');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>