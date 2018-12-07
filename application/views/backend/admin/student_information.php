<hr>
<div class="row">
	<div class="col-md-12">
		<?php echo form_open(base_url() . 'index.php?'.$account_type.'/student_information');?>
			<div class="col-md-4">
				<div class="form-group">
					<input type="hidden" name="campus" value="<?php echo $campus_id; ?>" >
					<label for="startdate"><?php echo $groupClass[1][value];?></label>
					
			      <select name="group" id="group_id" class="form-control">
			      	<?php 
			      	$groupInfo = $this->db->get('class_group')->result_array();
			      	foreach ($groupInfo as $group):
			      	?>
			      	<option value="<?php echo $group['id'];?>" <?php if($group['id'] == $group_id) echo "selected"; ?>><?php echo $group['group_name'];?></option>
			      	<?php endforeach;?>
			      </select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				<label for="startdate"><?php echo $groupClass[0][value];?></label>
				<select name="class" class="form-control" onchange="get_section(this.value);" id="class_holder">
					<option value=""><?php echo get_phrase('select')?></option>
					<?php 
			      	$classInfo = $this->db->get_where('class', array('campus_id' => $campus_id))->result_array();
			      	foreach ($classInfo as $class):
			      	?>			    
			      	<option value="<?php echo $class['class_id'];?>" <?php if($class['class_id'] == $class_id) echo "selected"; ?>><?php echo $class['name'];?></option>
			      	<?php endforeach;?>
				</select>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
				<label for="startdate"><?php echo get_phrase('section')?></label>
					<select name="section" id="section_holder" class="form-control" onchange="this.form.submit()">
					
					</select>
				</div>
			</div>
			
		
			
			
			<table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            
							<th width="80"><div><?php echo get_phrase('roll');?></div></th>
                            <th width="80"><div><?php echo get_phrase('Code');?></div></th>
							<th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                
                                foreach($students as $row):?>
                        <tr>
                            
							<td><?php echo $row['roll'];?></td>
							<td><?php echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->student_code;?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td>
                                <?php 
                                    echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $this->db->get_where('student' , array(
                                        'student_id' => $row['student_id']
                                    ))->row()->address;
                                ?>
                            </td>
                            <td>
                                <?php 
                                    echo $this->db->get_where('student' , array(
                                        'student_id' => $row['student_id']
                                    ))->row()->email;
                                ?>
                            </td>
                            <td>
                                
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                        <!-- STUDENT MARKSHEET LINK  -->
                                        <li>
                                            <a href="<?php echo base_url();?>index.php?admin/marksheet/<?php echo $row['student_id'];?>">
                                                <i class="entypo-chart-bar"></i>
                                                    <?php echo get_phrase('mark_sheet');?>
                                                </a>
                                        </li>

                                        
                                        <!-- STUDENT PROFILE LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('profile');?>
                                                </a>
                                        </li>
                                        
                                        <!-- STUDENT EDITING LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
						
		<?php echo form_close();?>
	</div>

    <center>
        <a href="<?php echo base_url(); ?>index.php?admin/sectionwise_marksheet_print/<?php echo $group_id; ?>/<?php echo $class_id; ?>/<?php echo $section_id; ?>" class="btn btn-primary" target="_blank">
            <?php echo get_phrase("print_section_marksheet") ?>
        </a>
    </center>
</div>


<script>

    $(window).on('load', function(){
        var classId = $('#class_holder').val();
        get_section(classId);
    });

	function get_section(class_id) {
        group_id = $('#group_id').val();
		$.ajax({
	        url: '<?php echo base_url();?>index.php?admin/get_section_with_cls_group/' + class_id + '/' + group_id,
	        success: function(response)
	        {				
	            jQuery('#section_holder').html(response);
	        }
	    });
	}
</script>