<?php
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title">
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_group');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/manage_group/create' , array('class' => 'form-horizontal form-groups-bordered validate'));?>
	
					<!-- <div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
						<div class="col-sm-5">
							<select name="class" class="form-control selectboxit">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php foreach ($classinfo as $row): ?>
                              <option value="<?php echo $row['class_id']?>"><?php echo $row['name'];?></option>
                              <?php endforeach ?>
                              
                          </select>
						</div> 
					</div>-->
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo $groupClass[1][value]." Name";?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="group_name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" 
                            	value="">
						</div>
					</div>                   
                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('add');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>
		