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
		<!-- <img src="uploads/logo.png" style="max-height : 60px;"><br> 
		<h3 style="font-weight: 100;"><?php echo $system_name;?></h3>-->
		<?php echo get_phrase('tabulation_sheet');?><br>
		<?php echo $group . ' | ' . $class_name;?><br>
		<?php echo $exam_name;?>

		
	</center>

	
	<?php if ($exam_id != '' && $class_id != '' && $group_id != '' && $section_id != ''):?>
<br>
<div class="row" align="center">
	<div class="col-md-12">
		<table class="table table-bordered" border="1" border="1" style="width:100%; font-size:10px;border-collapse:collapse; border:1px solid #CCCCCC; text-align:center; margin:15px 0px 20px 0px">
			<thead>
				<tr>
				<td style="text-align: center;">
					<?php echo get_phrase('students');?> <i class="entypo-down-thin"></i> | <?php echo get_phrase('subjects');?> <i class="entypo-right-thin"></i>
				</td>
				<?php 
					$subjects = $this->db->get_where('course' , array('class_id' => $class_id , 'group_id'=> $group_id))->result_array();

					// echo "<pre>";
					// print_r($subjects);
					// echo "</pre>";
					// exit();

					foreach($subjects as $row):
				?>
					<td style="text-align: center;"><?php echo $row['tittle'];?></td>
				<?php endforeach;?>
				<td style="text-align: center;"><?php echo get_phrase('total');?></td>
				<!-- <td style="text-align: center;"><?php //echo get_phrase('average_grade_point');?></td> -->
				</tr>
			</thead>
			<tbody>
			<?php
				
				//$students = $this->db->get_where('enroll' , array('class_id' => $class_id , 'session_id' => $running_year))->result_array();

				// $this->db->select('*');
				// $this->db->from('enroll');
				// $this->db->where('class_id', $class_id);
				// $this->db->where('session_id', $running_year);
				// $students = $this->db->get()->result_array();



				// $sql = "select * from enroll where class_id = '".20."' and session_id = '".3."'";
				// $students = $this->db->query()->result_array();

				$this->db->select('*');
				$this->db->from('enroll');
				$this->db->join('section', 'section.section_id = enroll.section_id');
				$this->db->where('section.group_id', $group_id);
				$this->db->where('section.class_id', $class_id);
				$this->db->where('section.section_id', $section_id);
				$this->db->where('enroll.session_id', $running_year);
				$students = $this->db->get()->result_array();

// 				select enroll.student_id from enroll
// join section on section.section_id = enroll.section_id
// where section.group_id = 9 and section.class_id = 20

				

				foreach($students as $row):
			?>
				<tr>
					<td style="text-align: center;">
						<?php echo $this->db->get_where('student' , array('student_id' => $row['student_id']))->row()->name;?>
					</td>
				<?php
					$total_marks = 0;
					$total_grade_point = 0;
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
							
							$obtained_mark_query = 	$this->db->get_where('exammark' , array(
													'exam_id' => $exam_id , 
															'course_id' => $row2['course_id'] ,
															'examtype_id' => $examtype_id , 
															'student_id' => $row['student_id']
												));
							if ( $obtained_mark_query->num_rows() > 0) {
								$obtained_marks = $obtained_mark_query->row()->mark_obtained;
								echo $obtained_marks;
								if ($obtained_marks >= 0 && $obtained_marks != '') {
									$grade = $this->crud_model->get_grade($obtained_marks);
									$total_grade_point += $grade['grade_point'];
								}
								$total_marks += $obtained_marks;
							}
							

						?>
					</td>
				<?php endforeach;?>
				<td style="text-align: center;"><?php echo $total_marks;?></td>
				<!-- <td style="text-align: center;">

					<?php 
						//$this->db->where('class_id' , $class_id);
						//$this->db->where('year' , $running_year);
						//$this->db->from('subject');
						//$number_of_subjects = $this->db->count_all_results();
						//echo ($total_grade_point / $number_of_subjects);
					?>
				</td> -->
				</tr>

			<?php endforeach;?>

			</tbody>
		</table>
	</div>
</div>
<?php endif;?>