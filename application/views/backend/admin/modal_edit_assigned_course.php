<?php
$row =	$this->db->get_where('studentcourseassignment' , array('sca_id' => $param2) )->row_array();
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_assigned_course');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/course_assigned/do_update/'.$row['sca_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('teacher');?></label>
                    <div class="col-sm-5 controls">
                        <select name="student" class="form-control">
                            <?php 
                            $student = $this->db->get('student')->result_array();
                            foreach($student as $row2):
                            ?>
                                <option value="<?php echo $row2['student_id'];?>" <?php if($row['student_id'] == $row2['student_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('course');?></label>
                    <div class="col-sm-5 controls">
                        <select name="course" class="form-control">
                            <?php 
                            $course = $this->db->get('course')->result_array();
                            foreach($course as $row2):
                            ?>
                                <option value="<?php echo $row2['course_id'];?>" <?php if($row['course_id'] == $row2['course_id'])echo 'selected';?>>
                                    <?php echo $row2['course_title'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                    <div class="col-sm-5 controls">
                        <select name="class" class="form-control">
                            <?php 
                            $class = $this->db->get('class')->result_array();
                            foreach($class as $row2):
                            ?>
                                <option value="<?php echo $row2['class_id'];?>" <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                    <?php echo $row2['name'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('session');?></label>
                    <div class="col-sm-5 controls">
                        <select name="session" class="form-control">
                            <?php 
                            $session = $this->db->get('session')->result_array();
                            foreach($session as $row2):
                            ?>
                                <option value="<?php echo $row2['componentId'];?>" <?php if($row['session_id'] == $row2['componentId'])echo 'selected';?>>
                                    <?php echo $row2['uniqueCode'];?>
                                </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
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
