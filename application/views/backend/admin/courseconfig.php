<div class="box">
	<div class="box-header">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo get_phrase('course_config');?>
              	</a>
          	</li>
		</ul>
    	<!------CONTROL TABS END------->
        
	</div>
	<div class="box-content padded">
		<div class="tab-content">            
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box active" id="list">
          		<div class="box-content">
            	<?php echo form_open(base_url() . 'index.php?admin/courseconfig' , array('class' => 'form-horizontal validatable','target'=>'_top'));?>
                 <?php echo form_hidden('operation', 'show');?>
                 <div class="padded" style="padding-top:20px">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('Class');?></label>
                                <div class="col-sm-5">
                                    <?php echo form_dropdown('class_id', $allclasses, $class_id, "class=\"form-control selectboxit\"");?>
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
               	<?php echo form_open(base_url() . 'index.php?admin/courseconfig' , array('class' => 'form-horizontal validatable','target'=>'_top'));?>
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
            <!----TABLE LISTING ENDS--->
            
		</div>
	</div>
</div>