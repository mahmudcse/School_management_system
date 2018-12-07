<?php 
$this->db->select('class.*, campus.campus_name');
$this->db->from('class');
$this->db->join('campus', 'class.campus_id = campus.id');
$this->db->where('class.class_id', $param2);
$edit_data = $this->db->get()->row();

$campusinfo = $this->db->get('campus')->result();

// print_r($campusinfo);
// exit();

?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_student');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/classes/do_update/'.$edit_data->class_id , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('campus');?></label>
                        <div class="col-sm-5">
                           <select name="campus" class="form-control">
                                <option value="<?php echo $edit_data->campus_id?>"><?php echo $edit_data->campus_name;?></option>
								 <?php foreach ($campusinfo as $campus): ?> 
                                    <option value="<?php echo $campus->id;?>"><?php echo $campus->campus_name;?></option>
                                 <?php endforeach; ?> 
                           </select> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name" value="<?php echo $edit_data->name;?>"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('numeric_name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="name_numeric" value="<?php echo $edit_data->name_numeric;?>"/>
                        </div>
                    </div>

            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_class');?></button>
						</div>
					</div>
        		</form>
            </div>
        </div>
    </div>
</div>

<?php

?>


