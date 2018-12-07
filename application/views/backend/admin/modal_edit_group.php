<?php
$edit_data = $this->db->get_where('class_group' , array('id' => $param2) )->result_array();
foreach ( $edit_data as $group):
$classinfo = $this->db->get('class')->result_array();
?>
 
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_campus');?>
            	</div>
            </div>
			<div class="panel-body">
                    <?php echo form_open(base_url() . 'index.php?admin/manage_group/update/'.$group['id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            
                        <!-- <div class="form-group">
							<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
							<div class="col-sm-5">
							<select name="class" class="form-control selectboxit">
								
                              <?php 
                              foreach ($classinfo as $class):?>                           
                              <option value="<?php echo $class['class_id']?>" <?php if($class['class_id']==$group['class_id']) echo 'selected'?> ><?php echo $class['name'];?></option>
                              <?php endforeach ?>
                            </select>
							</div> 
						</div>  -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('group name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="group_name" value="<?php echo $group['group_name'];?>"/>
                                </div>
                            </div>
       
                            
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-5">
                                <button type="submit" class="btn btn-info"><?php echo get_phrase('update_group');?></button>
                            </div>
                        </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>
