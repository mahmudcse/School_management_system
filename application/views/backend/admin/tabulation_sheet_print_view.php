<?php 
	$group  		= 	$this->db->get_where('class_group' , array('id' => $group_id))->row()->group_name;
	$class_name		 	= 	$this->db->get_where('class' , array('class_id' => $class_id))->row()->name;
	$exam_name  		= 	$this->db->get_where('exam' , array('exam_id' => $exam_id))->row()->name;
	$system_name        =	$this->db->get_where('settings' , array('type'=>'system_name'))->row()->description;
	$running_year       =	$this->db->get_where('settings' , array('type'=>'running_year'))->row()->description;
?>
<div id="print">
	<script src="assets/js/jquery-1.11.0.min.js"></script>
	<style type="text/css">
		td {
			padding: 5px;
		}
	</style>

	<center>
		<h3 style="font-weight: 100;"><?php echo $system_name;?></h3>
		 <img src="uploads/logo.png" style="max-height : 60px;"><br> 
		<?php echo get_phrase('tabulation_sheet');?><br>
		<?php echo 'Class ' . $class_name;?><br>
		<?php echo $exam_name;?>

		
	</center>

	
	<?php if ($exam_id != '' && $class_id != '' && $group_id != '' && $section_id != ''):?>
<br>
<div class="row" align="center">
	<div class="col-md-12">
		<table class="table table-bordered" border="1" style="width:100%; font-size:10px;border-collapse:collapse; border:1px solid #CCCCCC; text-align:center; margin:15px 0px 20px 0px">
			<thead>
				<tr bgcolor="#F3F3F3">
				<td style="text-align: center;">
					<?php echo get_phrase('students');?> <i class="entypo-down-thin"></i> | <?php echo get_phrase('subjects');?> <i class="entypo-right-thin"></i>
				</td>
				<?php 
					$subjects = $this->db->get_where('course' , array('class_id' => $class_id , 'group_id'=> $group_id))->result_array();
					foreach($subjects as $row):
				?>
					<td style="text-align: center;"><?php echo $row['tittle'];?></td>
				<?php endforeach;?>
				<td style="text-align: center;"><?php echo "GPA";?></td>
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
					$examtype_id = 0;  

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
								
								}
						
					$course_id = $row2['course_id']." ";
					
					$student_id = $row['student_id']."<br>";

					$grade = $this->db->get_where('exammark', array('exam_id' => $exam_id, 'course_id' => $course_id, 'examtype_id' => $examtype_id, 'student_id' => $student_id, 'session_id' => $running_year))->row()->lg;

					echo $grade;

						?>
					</td>
				<?php endforeach;?>

				<td style="text-align: center;"><?php 

					$gpa = $this->db->get_where('result', array('student_id' => $student_id))->row()->gpa;

					$lg = $this->crud_model->get_grade_with_point($gpa);

					echo "$lg ($gpa)";
				    
					
					?></td>
				</tr>

			<?php endforeach;?>

			</tbody>
		</table>
		<table style="margin:100px 0px 20px 0px;width:100%;">
			<tr>
				<td width="25%" align="center" style="border-top: 1px solid #ddd;">Teacher</td>
				<td width="50%">&nbsp;</td>
				<td width="25%" align="center" style="border-top: 1px solid #ddd;">Head Master</td>
			</tr>
		</table>
		<center>
			<a href="<?php echo base_url();?>index.php?admin/tabulation_sheet_print_view/<?php echo $exam_id;?>/<?php echo $class_id;?>/<?php echo $group_id; ?>/<?php echo $section_id; ?>" 
				class="btn btn-primary" target="_blank">
				
			</a>
		</center>
	</div>
</div>
<?php endif;?>