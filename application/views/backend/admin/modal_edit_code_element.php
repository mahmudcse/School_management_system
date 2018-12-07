<?php 

$edit_data = $this->db->get_where('codes', array('id' => $param2))->row();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_element');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/manage_code_element/update/'.$edit_data->id , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
     
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('key');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="key_name" value="<?php echo $edit_data->key_name;?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('value');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="value" value="<?php echo $edit_data->value;?>"/>
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



