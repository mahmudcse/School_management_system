<hr/>
<div class="row">
<div class="col-md-12">

<!--ONTROL TABS START-->
<ul class="nav nav-tabs bordered">
<li class="active">
<a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
<?php echo get_phrase('course_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('assign_course');?>
                    	</a></li>
		</ul>
    	<!--CONTROL TABS END-->
        
		<div class="tab-content">
        <br>
            <!--TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
				
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('course_name');?></div></th>
                    		<th><div><?php echo get_phrase('student_id');?></div></th>
                    		<th><div><?php echo $groupClass[0][value];?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($assignedCourse as $row):
                    	?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td>
							<?php 
								$course = $this->db->get_where('course', array('course_id' => $row['course_id']))->row()->tittle;
								echo $course;
							?>
							</td>
							<td><?php echo $row['student_id'];?></td>
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
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_assigned_course/<?php echo $row['sca_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/course_assigned/delete/<?php echo $row['sca_id'];?>');">
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
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/course_assigned/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
	                        <div class="padded">
		                        <div class="form-group">
	                                <label class="col-sm-3 control-label"><?php echo get_phrase('student');?></label>
	                                <div class="col-sm-5">
	                                    <select name="student" class="form-control select2" style="width:100%;">
	                                        <option value=""><?php echo get_phrase('select_student');?></option>
	                                    	<?php 
											$teachers = $this->db->get('student')->result_array();
											foreach($teachers as $row):
											?>
	                                    	<option value="<?php echo $row['student_id'];?>"><?php echo $row['name'];?></option>
	                                        <?php
											endforeach;
											?>
	                                    </select>
	                                </div>
                            	</div>
	                        	<div class="form-group">
									<label for="field-2" class="col-sm-3 control-label"><?php echo $groupClass[0][value];?></label>
		                        
									<div class="col-sm-5">
									<select name="class" class="form-control selectboxit" onchange="get_course(this.value)">
									  <option value=""><?php echo get_phrase('select')?></option>
		                              <?php foreach ($classInfo as $class):?>	                                                         
		                              <option value="<?php echo $class['class_id']?>"><?php echo $class['name'];?></option>
		                              <?php endforeach ?>
		                            </select>
									</div> 
								</div>
                            	<div class="form-group">
									<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('course');?></label>
		                        
									<div class="col-sm-5">
									<select name="course" class="form-control" id="course_holder">
		                              
		                            </select>
									</div> 
								</div>
                            	<div class="form-group">
									<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('session');?></label>
		                        
									<div class="col-sm-5">
									<select name="session" class="form-control selectboxit">
									  <option value=""><?php echo get_phrase('select_session')?></option>
		                              <?php foreach ($sessionInfo as $session):?>	                                                         
		                              <option value="<?php echo $session['componentId']?>" <?php if($session['componentId'] == $running_session) echo "selected"; ?>><?php echo $session['uniqueCode'];?></option>
		                              <?php endforeach ?>
		                            </select>
									</div> 
								</div>
                            
                            
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_course');?></button>
                              </div>
							</div>
                    <?php echo form_close(); ?>              
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
	});
</script>	