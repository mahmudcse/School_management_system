<hr />

<br>

<div class="row">
    <div class="col-md-12">
    <?php echo form_open(base_url() . 'index.php?admin/feecollection');?>
        <div class="col-md-4">
            <div class="form-group">
                <input type="hidden" name="campus" value="<?php echo $campus_id; ?>" >
                <label for="startdate"><?php echo $groupClass[1][value]; ?></label>
              <select name="group" id="group_id" class="form-control" onchange="this.form.submit()">
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
                <select name="class" class="form-control" onchange="this.form.submit()">
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
            <?php echo form_close();?>
    </div>
</div>

<hr />
<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>
        <?php 
            $query = $this->db->get_where('section' , array('class_id' => $class_id , 'group_id' => $group_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
        ?>
            <li>
                <a href="#<?php echo $row['section_id'];?>" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-user"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('section');?> <?php echo $row['name'];?> </span>
                </a>
            </li>
        <?php endforeach;?>
        <?php endif;?>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
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
                                // $this->db->select('enroll.*, student.name');
                                // $this->db->from('enroll');
                                // $this->db->join('student', 'enroll.student_id = student.student_id');
                                // $this->db->where('enroll.class_id', $class_id);
                                // $students = $this->db->get()->result_array();
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
                                 <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_feecollection/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('fee_collection');?>
                                 </a>
                                <!--
								<div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                        
                                        
                                        <li>
                                       <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_feecollection/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('fee_collection');?>
                                                </a>
                                        </li>
                                    </ul>
                                </div>
                                -->
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        <?php 
            $query = $this->db->get_where('section' , array('class_id' => $class_id));
            if ($query->num_rows() > 0):
                $sections = $query->result_array();
                foreach ($sections as $row):
        ?>
            <div class="tab-pane" id="<?php echo $row['section_id'];?>">
                
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th width="80"><div><?php echo get_phrase('roll');?></div></th>
                            <th width="80"><div><?php echo get_phrase('code');?></div></th>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                                $students   =   $this->db->get_where('enroll' , array(
                                    'class_id'=>$class_id , 'section_id' => $row['section_id'] , 'session_id' => $running_year
                                ))->result_array();
                                foreach($students as $row):?>
                        <tr>
                            <td><?php echo $row['roll'];?></td>
                            <td><?php echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->student_code;?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td>
                                <?php 
                                    echo $this->db->get_where('student' , array(
                                        'student_id' => $row['student_id']
                                    ))->row()->name;
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
                                        
                                        <!-- STUDENT PROFILE LINK -->
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_feecollection/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('fee_collection');?>
                                                </a>
                                        </li>
                                        
                                        <!-- STUDENT EDITING LINK -->
                                        
                                    </ul>
                                </div>
                                
                            </td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        <?php endforeach;?>
        <?php endif;?>

        </div>
        
        
    </div>
</div>



<!---  DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
    
</script>