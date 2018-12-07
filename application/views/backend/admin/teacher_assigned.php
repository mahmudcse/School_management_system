
<hr/>
<div class="row">
<div class="col-md-12">

<!--CONTROL TABS START-->
<ul class="nav nav-tabs bordered">
    <li class="active">
        <a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
            <?php echo get_phrase('teacher_list');?>
        </a>
    </li>
    <li>
        <a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
            <?php echo get_phrase('assign_teacher');?>
        </a>
    </li>
</ul>
    	<!--CONTROL TABS END-->
        
		<div class="tab-content">
        <br>
            <!--TABLE LISTING STARTS-->
            <div class="tab-pane fade in active" id="list">
				
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('teacher');?></div></th>
                    		<th><div><?php echo get_phrase('course_name');?></div></th>
                    		<th><div><?php echo $groupClass[0][value];?></div></th>                   		
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($teacherInfo as $row):
                    	?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td>
							<?php
								$teacher = $this->db->get_where('teacher', array('teacher_id' => $row['teacher_id']))->row()->name;
								echo $teacher;
							?>
							</td>
							<td>
							<?php 
								$course = $this->db->get_where('course', array('course_id' => $row['course_id']))->row()->tittle;
								echo $course;
							?>
							</td>							
							<td>
							<?php 
								$class = $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;
								echo $class;
							?>
							</td>							
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_course_teacher/<?php echo $row['cta_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/course_teacher/delete/<?php echo $row['cta_id'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!--TABLE LISTING ENDS->
            
            
			<!-CREATION FORM STARTS-->
			<div class="tab-pane fade" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/course_teacher' , array('class' => 'form-horizontal validatable','target'=>'_top'));?>
                 <?php echo form_hidden('operation', 'show');?>
                 <div class="padded" style="padding-top:20px">
                 <input type="hidden" id="class_id" name="" value="<?php echo $class_id ?>">
                 <input type="hidden" id="session_id" name="" value="<?php echo $session_id ?>">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $groupClass[0][value];?></label>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('class_id', $allclasses, $class_id, "class=\"form-control selectboxit\"");?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $groupClass[1][value];?></label>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('group_id', $allgroups, $group_id, "class=\"form-control selectboxit\"");?>
                                </div>
                            </div>
							 <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('Session');?></label>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('session_id', $sessions, $session_id, "class=\"form-control selectboxit\"");?>
                                </div>
                            </div>
							<div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('Go');?></button>
                              </div>
						   </div>
				</div>
				
               	<?php echo form_close();?>
				</div>
               	<br/>
               	<?php echo form_open(base_url() . 'index.php?admin/course_teacher' , array('class' => 'form-horizontal validatable','target'=>'_top'));?>
                <?php echo form_hidden('class_id', $class_id);?>
                <?php echo form_hidden('session_id', $session_id);?>
                <?php echo form_hidden('operation', 'update');?>
                <?php echo form_hidden('coursecount', count($courses));?>
               	 
				 <table class="table table-bordered datatable" id="table_export">
                    <tbody>
                    	<tr>
                    		<th><input type="checkbox" name="checkAll"/></th>
                    		<th>Course</th>
                    		<th>Course Teacher</th>
                    	</tr>
                    	<?php 
                    	$cnt = 0;
                    	foreach($courses as $course):
                    	$cnt++;
                    	?>
                        <tr>
							<td>
							<input type="hidden" name="course_<?php echo $cnt;?>" value="<?php echo $course['course_id']?>"/>
							<input type="checkbox" name="selectedCourse_<?php echo $cnt;?>" <?php echo isset($courseTeacher[$course['course_id']])?"checked='checked'":""; ?> value="<?php echo $course['course_id'];?>"/></td>
							<td><?php echo $course['tittle'];?></td>
							<td><?php echo form_dropdown('teacher_id_'.$cnt, $teachers, $courseTeacher[$course['course_id']], "class=\"form-control selectboxit\"");?></td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                        	<td colspan="3"><button type="submit" class="btn btn-info"><?php echo get_phrase('Update');?></button></td>
                        </tr>
                    </tbody>
                </table>
                
                <?php echo form_close();?>       
                </div>                
			</div>
			<!--CREATION FORM ENDS-->
		</div>
	</div>
</div>



<!---  DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">








function get_course(class_id) {
	
	$.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_class_course/' + class_id ,
        success: function(response)
        {				
            jQuery('#course_holder').html(response);
        }
    });

}
	jQuery(document).ready(function($)
	{
		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});


        // var class_id = $('#class_id').val();
        // var session_id = $('#session_id').val();
        // if(class_id > 0 || session_id > 0){
        //     $(".nav-tabs a[href='#add']").tab('show');
        //     return false;
        // }

	});

    
</script>	