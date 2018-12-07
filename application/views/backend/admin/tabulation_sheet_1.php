<hr />
<div class="row">
	<div class="col-md-12">
		<?php echo form_open(base_url() . 'index.php?admin/tabulation_sheet');?>
			<div class="col-md-3">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('exam');?></label>
					<?php echo form_dropdown('exam_id', $terms, $exam_id,"class=\"form-control selectboxit\"");?>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<!-- <input type="hidden" name="campus" value="<?php //echo $campus_id; ?>" > -->
					<label class="control-label"><?php echo $groupClass[1][value]; ?></label>
					
			      <select name="group_id" id="group_id" class="form-control selectboxit">
			      	<?php
				      	$groupInfo = $this->db->get('class_group')->result_array();
				      	foreach ($groupInfo as $group):
			      	?>
			      	<option value="<?php echo $group['id'];?>"
			      	<?php if($group['id'] == $group_id) echo "selected"; ?>
			      	>


			      		<?php echo $group['group_name'];?>
			      		
			      	</option>
			      	<?php endforeach;?>
			      </select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
					<label for="classid"><?php echo $groupClass[0][value];?></label>
					<select name="class_id" class="form-control selectboxit" id="classid" onchange="get_section(this.value);">
						<option value=""><?php echo get_phrase('select')?></option>
						<?php 
				      	$classInfo = $this->db->get('class')->result_array();

				      	foreach ($classInfo as $class):
				      	?>			    
				      	<option value="<?php echo $class['class_id'];?>"
						<?php if($class['class_id'] == $class_id) echo "selected"; ?>
				      	>

				      		<?php echo $class['name'];?>
				      		
				      	</option>
				      	<?php endforeach;?>
					</select>
				</div>
			</div>

			<div class="col-md-3">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('section')?></label>
					<select name="section_id" id="section_holder" class="form-control" >
					</select>
				</div>
			</div>

			<input type="hidden" name="operation" value="selection">
			<div class="col-md-4" style="margin-top: 20px;">
				<button type="submit" class="btn btn-info"><?php echo get_phrase('view_tabulation_sheet');?></button>
			</div>
		<?php echo form_close();?>
	</div>
</div>

<?php if ($exam_id != '' && $class_id != '' && $group_id != '' && $section_id != ''):?>
<br>
<div class="row">
	<div class="col-md-4"></div>
	<div class="col-md-4" style="text-align: center;">
		<div class="tile-stats tile-gray">
		<div class="icon"><i class="entypo-docs"></i></div>
			<h3 style="color: #696969;">
				<?php
					$exam_name  = $this->db->get_where('exam' , array('exam_id' => $exam_id))->row()->name; 

					$class_name = $this->db->get_where('class' , array('class_id' => $class_id))->row()->name;

					echo get_phrase('tabulation_sheet');


				?>
			</h3>
			<h4 style="color: #696969;">
				<?php echo $groupClass[0][value] . ' ' . $class_name;?> : <?php echo $exam_name;?>
			</h4>
		</div>
	</div>
	<div class="col-md-4"></div>
</div>


<hr />

<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered">
			<thead>
				<tr>
				<td style="text-align: left;">
					<?php echo get_phrase('students');?> <i class="entypo-down-thin"></i> | <?php echo get_phrase('subjects');?> <i class="entypo-right-thin"></i>
				</td>
				<?php 
					$subjects = $this->db->get_where('course' , array('class_id' => $class_id , 'group_id'=> $group_id))->result_array();
					foreach($subjects as $row):
				?>
					<td style="text-align: center;"><?php echo $row['tittle'];?></td>
				<?php endforeach;?>
				<td style="text-align: center;">GRADE</td>
				</tr>
			</thead>
			<tbody>
			<?php

				$this->db->select('*');
				$this->db->from('enroll');
				$this->db->join('section', 'section.section_id = enroll.section_id');
				$this->db->where('section.group_id', $group_id);
				$this->db->where('section.class_id', $class_id);
				$this->db->where('section.section_id', $section_id);
				$this->db->where('enroll.session_id', $running_year);
				$students = $this->db->get()->result_array();	

				foreach($students as $row):
			?>
				<tr>
					<td style="text-align: left;">
						<?php 
								$studentId = $row['student_id'];
								$this->db->select('e.roll, s.name');
								$this->db->from('enroll e');
								$this->db->join('student s', 'e.student_id = s.student_id', 'inner');
								$this->db->where('e.student_id', "$studentId");
								$this->db->group_by('e.student_id');
								$student = $this->db->get()->result_array();

								echo $student[0][roll]." - ".$student[0][name];


						 ?>
					</td>
				<?php
					$total_marks = 0;
					$total_grade_point = 0;
					$examtype_id = 0;  
					$total_percentage = 0;
					$subjectQuantity = 0;
					$failed = 0;

					foreach($subjects as $row2):
				?>
					<td style="text-align: center;">
						<?php 
						
							
							$examtype_id_query = 	$this->db->get_where('examcourse' , array(
													'exam_id' => $exam_id , 
															'course_id' => $row2['course_id'] , 
																'report_card' => 1
												));
							if ( $examtype_id_query->num_rows() > 0){
								$examtype_id = $examtype_id_query->row()->examtype_id;
								$total_course_mark = $this->db->get_where('examtype', array('examtype_id' => $examtype_id))->row()->total_mark;
								}
						

					
					$course_id = $row2['course_id']." ";
					
					$student_id = $row['student_id']."<br>";


					$marks = $this->db->get_where('vstudentcoursemark', array('exam_id' => $exam_id, 'course_id' => $course_id, 'examtype_id' => $examtype_id, 'student_id' => $student_id))->result_array();

					$is_optional = $this->db->get_where('course', array('course_id' => $course_id))->row()->is_optional;
					if($is_optional == 1){
						if($marks[0]['gradePoint']-2 < 0){
							$total_grade_point += 0;
						}else{
							$total_grade_point += $marks[0]['gradePoint']-2;
						}
					}else{
						$total_grade_point += $marks[0]['gradePoint'];
						$subjectQuantity++;
						if($marks[0]['gradePoint'] == 0){
							$failed = 1;
						}
					}

					echo $marks[0]['grade'];
					

						?>
					</td>
				<?php endforeach;?>

				<td style="text-align: center;"><?php 
				    if($failed == 1){
				    	echo "F";
				    }else{
				    	$sgpa = round($total_grade_point/$subjectQuantity, 2);
						$sgpa = $this->crud_model->get_grade_with_point($sgpa);
						echo $sgpa;
				    }
					
					?></td>
				</tr>

			<?php endforeach;?>

			</tbody>
		</table>
		<center>
			<a href="<?php echo base_url();?>index.php?admin/tabulation_sheet_print_view/<?php echo $exam_id;?>/<?php echo $class_id;?>/<?php echo $group_id; ?>/<?php echo $section_id; ?>" 
				class="btn btn-primary" target="_blank">
				<?php echo get_phrase('print_tabulation_sheet');?>
			</a>
		</center>
	</div>
</div>
<?php endif;?>


<script>
	$(window).load(function(){
		var classId = $('#classid').val();
		get_section(classId);
	});




	function get_section(class_id) {
		group_id = $('#group_id').val();
		$.ajax({
	        url: '<?php echo base_url();?>index.php?admin/get_section_with_cls_group/' + class_id + '/' + group_id,
	        success: function(response)
	        {				
	         	jQuery("#section_holder").html(response);
	        }
	    });
	}
</script>