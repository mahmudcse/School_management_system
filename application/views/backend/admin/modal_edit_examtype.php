<?php 
$edit_data		=	$this->db->get_where('examtype' , array('examtype_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
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
				
                <?php echo form_open(base_url() . 'index.php?admin/examtypes/edit/do_update/'.$row['examtype_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="padded">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('display_name');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="displayname" value="<?php echo $row['displayname'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('type');?></label>
                    <div class="col-sm-5">
                      <select name="type" class="form-control selectboxit">
							<?php
								$type = $this->db->get_where('examtype' , array('examtype_id' => $param2))->row()->type;
							?>
                              <option value="single" <?php if($type == 'single')echo 'selected';?>><?php echo get_phrase('single');?></option>
                              <option value="composite"<?php if($type == 'composite')echo 'selected';?>><?php echo get_phrase('composite');?></option>
                          </select>
                    </div>
                </div>
				<div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('total_mark');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="total_mark" value="<?php echo $row['total_mark'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('rule');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="rule" value="<?php echo $row['rule'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('passing_check');?></label>
                    <div class="col-sm-5">
                      <select name="passing_check" class="form-control selectboxit">
                            <?php
                                $type = $this->db->get_where('examtype' , array('examtype_id' => $param2))->row()->passing_check;
                            ?>
                              <option value = 0 <?php if($type == 0)echo 'selected';?>><?php echo get_phrase('No');?></option>
                              <option value = 1 <?php if($type == 1)echo 'selected';?>><?php echo get_phrase('Yes');?></option>
                          </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_examtype');?></button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>





