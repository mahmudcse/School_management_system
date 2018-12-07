<?php
$this->db->select ( 'feeConfig.*, codes.*' );
$this->db->from ( 'feeConfig' );
$this->db->join ( 'codes', 'feeConfig.fee_type = codes.id' );
$edit_data = $this->get_where ( 'feeConfig.fee_id', $param2 )->row ();

// $edit_data = $this->db->get_where('codes', array('id' => $param2))->row();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
			<div class="panel-heading">
				<div class="panel-title">
					<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_element');?>
            	</div>
			</div>
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/manage_code_element/update/'.$edit_data->id , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
     
                    <div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('fee_name');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" name="fee_name"
							data-validate="required"
							data-message-required="<?php echo get_phrase('value_required');?>"
							value="" autofocus>
					</div>
				</div>

				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo get_phrase('type');?></label>
					<div class="col-sm-5">
						<select name="fee_type" id="class"
							onchange="return get_section_group(this.value)"
							class="form-control selectboxit" data-validate="required"
							data-message-required="<?php echo get_phrase('value_required');?>">
							<option><?php echo get_phrase('select')?></option>
                              <?php
																														foreach ( $feeTypes as $row ) :
																															?>
                                		<option
								value="<?php echo $row['id'];?>">
												<?php echo $row['value'];?>
                                        </option>
                                    <?php endforeach; ?>
                           </select>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label"><?php echo get_phrase('category');?></label>
					<div class="col-sm-5">
						<select name="fee_category" id="class"
							onchange="return get_section_group(this.value)"
							class="form-control selectboxit" data-validate="required"
							data-message-required="<?php echo get_phrase('value_required');?>">
							<option><?php echo get_phrase('select')?></option>
                              <?php
																														foreach ( $feeCategories as $row ) :
																															?>
                                		<option
								value="<?php echo $row['id'];?>">
												<?php echo $row['value'];?>
                                        </option>
                                    <?php endforeach; ?>
                           </select>
					</div>
				</div>
				<div class="form-group">
					<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>

					<div class="col-sm-5">
						<input type="text" class="form-control" name="fee_amount"
							data-validate="required"
							data-message-required="<?php echo get_phrase('value_required');?>"
							value="" autofocus>
					</div>
				</div>

				<div class="form-group">
					<div class="col-sm-offset-3 col-sm-5">
						<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_element');?></button>
					</div>
				</div>
        		<?php form_close()?>
            </div>
		</div>
	</div>
</div>

<?php

?>



