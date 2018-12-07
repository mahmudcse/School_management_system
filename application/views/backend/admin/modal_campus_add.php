<?php
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_form');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/manage_campus/create' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
	
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('campus name');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="campus_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" 
                            	value="">
						</div>
					</div>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('institute name');?></label>
                        
						<div class="col-sm-5">
							<?php $institute = $this->db->get('settings')->row()->description; ?>
							<input type="text" class="form-control" name="institute_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" 
                            	value="<?php echo $institute; ?>" readonly>
						</div>
					</div>                   
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-default"><?php echo get_phrase('add_campus');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
		