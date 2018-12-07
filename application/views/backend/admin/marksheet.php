<style>
	#gradeTable tr td{
		border: 1px solid black;
		text-align: center;
	}
</style>


<div align="center"><H3>Academic Transcript</H3></div>
<div class="container-fluid">
	<div class="row" style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px; margin-top:0px; margin-bottom:0px;">
		<div class="col-md-12">
			<div class="col-md-5" align="left">
				<table style="width:100%;">
				<tr>
					<td width="18%"> <strong>Student Name</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $student['name'];?></td>
					<!-- <td width="10%"><strong><?php //echo $groupClass[0][value]; ?></strong></td>
					<td width="1%"> : </td>
					<td width="12%"> <?php //echo $class['name'];?> </td> -->
				</tr>
				<tr>
					<td width="18%"> <strong><?php echo $groupClass[0][value]; ?></strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $class['name'];?></td>
				</tr>
<?php if($class['name'] == 'NINE' || $class['name'] == 'TEN'): ?>
				<tr>
					<td width="18%"> <strong><?php echo $groupClass[1][value]; ?></strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $group_name;?></td>
				</tr>
<?php endif; ?>
				<tr>
					<td width="18%"> <strong>Roll No</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $student_code;?></td>
				</tr>
				<tr>
					<td width="18%"> <strong>Section</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $section_name;?></td>
				</tr>
				<tr>
					<td width="18%"><strong>Father's Name</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $student['fathername'];?></td>
				</tr>
				<tr>
				    <td width="18%"><strong>Mother's Name</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $student['mothername'];?></td>
				</tr>
				<tr>
				    <td width="18%"><strong>Session</strong></td>
					<td width="1%"> : </td>
					<td width="58%"><?php echo $session_name;?></td>
				</tr>
				
			</table>
			</div>

<?php 

$grades = $this->db->get('grade')->result_array();

?>

			<div class="col-md-5" align="right" id="gradeTable">
				<table style="width:70%; height: 50%; font-size: 10px;">
					<thead>
						<tr>
							<td colspan="4" style="text-align: center;">Grading System</td>
						</tr>
						<tr>
							<td>Marks Range</td>
							<td>Letter Grade</td>
							<td>Grade Point</td>
							<td>Remarks</td>
						</tr>
					</thead>
					<tbody>
					<?php foreach ($grades as $key => $grade): ?>
						<tr>
							<td>
								<?php echo $grade['mark_from']."-".$grade['mark_upto']; ?>
							</td>
							<td>
								<?php echo $grade['name']; ?>
							</td>
							<td>
								<?php echo $grade['grade_point']; ?>
							</td>
							<td>
								<?php echo $grade['comment']; ?>
							</td>
						</tr>
					<?php endforeach; ?>
					</tbody>
					
					
				</table>
			</div>
		</div>
		
	</div>
</div>

<div class="accordion" id="accordion2">
<?php

$toggle = true;
foreach ($exam as $exm):
	$total_grade_point	=	0;
    $highest_total_grade_point	=	0;
	$total_marks		=	0;
	$highest_total_marks		=	0;
	$total_subjects		=	count($courses);
	$total_examMark = 0;
	$comment = '';
	$uniqueHeaders=array();
	foreach ($header[$exm['exam_id']] as $head):
		$uniqueHeaders[$head['name']] = $head['name'];
	endforeach;
?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $exm['exam_id'];?>" style="text-decoration:none">
				<i class="icon-rss icon-1x"></i>
				<div style="margin-bottom:10px; margin-top:10px; padding:10px;background-color:#F3F3F3; font-size:16px;font-weight:bold; text-align: center;">
				<?php echo $exm['name'];?>
				</div>
         	</a>
     	</div>
	   	<div id="collapse<?php echo $exm['exam_id'];?>" class="accordion-body collapse <?php if($toggle){echo 'in';$toggle=false;}?>" >
	    	<div class="accordion-inner">
			    <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #CCCCCC; text-align:center; margin:15px 0px 20px 0px"> 
			    	<thead>
						<tr bgcolor="#F3F3F3">
							<th style="text-align: left; padding: 10px">Subjects</th>
							
							<?php 
							$uniqueHeaders = array('Full Marks' => 'Full Marks') + $uniqueHeaders;
							 ?>

							<?php foreach ($uniqueHeaders as $idx=>$head): ?>
							<th style="text-align:center"><?php echo $idx;?></th>
							<?php endforeach;?>
							<th style="text-align:center">Grade</th>
							<th style="text-align:center">Grade Point</th>
							<th style="text-align:center">Highest Marks</th>
			         	</tr>
			   		</thead>
			        <tbody>
						


			        	<?php 

			        	$failed = 0;
			        	$courseQuantity = 0;



			        	foreach ($courses as $course): 
			        	$grade = '';
			        	$highestMark = '';
			        	$uniqueRow = array();


						foreach ($header[$exm['exam_id']] as $head): 
							if(isset($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'])){
								$uniqueRow[$head['name']] = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'];

								$eachExmTypeId = $head['examtype_id'];

							}

							if($head['report_card']==1){
								if($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'] != ''){
									$total_examMark += $head['total_mark'];
									$is_optional = $this->db->get_where('course', array('course_id' => $course['course_id']))->row()->is_optional;
									if($is_optional == 0){
									 	$courseQuantity++;
									 }
									
								}

								if($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['grade'] != ''){
									$grade = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['grade'];
								}


								if($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['highestMark'] != ''){
									$highestMark = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['highestMark'];
									$highest_total_marks += $highestMark;
									
								}
								$total_marks += $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'];
							}
						endforeach;			
			        	?>

						<tr>
			           		<td style="text-align: left; padding-left: 20px"><?php echo $course['tittle'];?></td>
			           		<td>
			           			<?php 
									echo course_id_to_total_mark($course['course_id']);
			           			 ?>
			           		</td>
							<?php 
							$first = true;
							foreach ($uniqueHeaders as $idx=>$head): 
							?>
							
							<?php 
								if($first === true){
								$first = false;
								continue;
							} ?>

							<td><?php echo $uniqueRow[$idx];?></td>
							<?php endforeach;?>
							


							<td>
								<?php 

								$condition = array(
									'exam_id'    => $exm['exam_id'],
									'course_id'  => $course['course_id'],
									'report_card'=> 1
								);
									$report_card_examtype_id = $this->db->get_where('examcourse', $condition)->row()->examtype_id;
									$gradeCondition = array(
										'student_id' => $student['student_id'],
										'course_id'  => $course['course_id'],
										'exam_id'    => $exm['exam_id'],
										'examtype_id'=> $report_card_examtype_id
									);

									$letterGrade = $this->db->get_where('exammark', $gradeCondition)->row()->lg;
									echo $letterGrade;


								?>
								
							</td>
							<td>
								<?php 
								$is_optional = $this->db->get_where('course', array('course_id' => $course['course_id']))->row()->is_optional;


									$gradePoint = $this->db->get_where('exammark', $gradeCondition)->row()->gp;
									echo $gradePoint;

									if($gradePoint == 0 && $is_optional != 1){
										$failed = 1;
									}
								?>
								
							</td>
							<td><?php echo $highestMark;?></td>
			          	</tr>
			          	<?php endforeach;?>
						<tr>
							<td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">						
								<strong>Grand Total</strong>								
							</td>
							<td colspan="3">
								<strong><?php	echo $total_marks;?></strong>	
							</td>
							<td>	
								<div align="center">
									<?php	echo $highest_total;?>
								</div>							
							</td>							
						</tr>
						<tr>
							<td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
								<strong>Percentage</strong>
							</td>	
							<td colspan="3">
								<strong><?php
									if($failed == 0){
										echo round($total_marks*100/$total_examMark);
									}
									?></strong>	
							</td>	
							<td style="text-align: center;">	
								<div>
									<?php echo round($highest_total*100/$total_examMark);?>
								</div>
							</td>							
						</tr>
						<tr>
							<td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
								<strong>Average Grade</strong>
							</td>	
							<td colspan="3">
								<strong><?php 
										$sgpaCondition = array(
											'student_id' => $student['student_id'],
											'term_id'    => $exm['exam_id']
										);

										$gpa = $this->db->get_where('result', $sgpaCondition)->row()->gpa;

										$lg = $this->crud_model->get_grade_with_point($gpa);
										echo $lg." (".$gpa.")";

										$comment = $this->db->get_where('grade', array('name' => $lg))->row()->comment;
								?></strong>	
							</td>	
							<td style="text-align: center;">	
								<div>
									<?php 
									//$avmrks = round($highest_total_marks*100/$total_examMark);

									$highestGrade = "";

									//$highestGrade = $this->crud_model->get_grade_with_everage($avmrks, 'lg');
									$highestGrade = $this->crud_model->get_grade_with_point($highest_gpa);
									echo $highestGrade." (".$highest_gpa.")";
									?>
									
								</div>
							</td>
						</tr>
			 		</tbody>
			 	</table>
           	</div>
         </div>
     </div>
	
	<div class="row">
		<div class="col-md-12">
			<table class="table table-bordered">
				<thead>
					<tr>
						<td>Exam Type</td>
						<td>Total Students</td>
						<td>Schooling Day</td>
						<td>Presence</td>
						<td>Absence</td>
						<td>Homework</td>
						<td>Behaviour</td>
						<td>Attention</td>
						<td>Position In Class</td>
						<td>Total</td>
						<td>GRADE</td>
					</tr>
				</thead>
				<tbody>
<?php 

foreach ($exam as $key => $row): 

	$exam_id      = $row['exam_id'];
	$student_id   = $student_id;
	$running_year = $this->db->get_where('settings', array('type' => 'running_year'))->row()->description;

	$resultData = $this->db->get_where('result', array('term_id' => $exam_id, 'student_id' => $student_id, 'session_id' => $running_year))->result_array();



	?>

					<tr>
						<td><?php echo $row['name']; ?></td>
						<td><?php echo $total_students_in_class; ?></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td></td>
						<td><?php echo $resultData[0]['group_position']; ?></td>
						<td><?php echo $total_marks; ?></td>
						<td>


						<?php 
							if($failed == 1){
								echo "0.00";
							}else{
								echo $lg;
							}
							
						?>
							
						</td>
					</tr>
<?php 
endforeach; 
?>
				</tbody>
			</table>
			
		</div>
	</div>


     <div><strong>Comments</strong></div>
    <div style="border:1px solid #F3F3F3; height:50px; margin-top:10px;padding-top: 10px; padding-left: 10px;">
	 <?php echo $comment;?>
	</div>
     	<table style="margin:100px 0px 20px 0px;width:100%;">
		<tr>
			<td width="14.28%" align="center" style="border-top: 1px solid #ddd;">
				<?php 
					$guardian = $this->db->get_where('codes', array('key_name' => 'guardian'))->row()->value;
					echo $guardian;

				 ?>
			</td>
			<td width="14.28%">&nbsp;</td>
			<td width="14.28%" align="center" style="border-top: 1px solid #ddd;">
				<?php 
					$teacher = $this->db->get_where('codes', array('key_name' => 'teacher'))->row()->value;
					echo $teacher;

				 ?>
			</td>
			<td width="14.28%">&nbsp;</td>
			<td width="14.28%" align="center" style="border-top: 1px solid #ddd;">
				<?php 
					$head_master = $this->db->get_where('codes', array('key_name' => 'head'))->row()->value;
					echo $head_master;

				 ?>

			</td>
			<td width="14.28%">&nbsp;</td>
			<td width="14.28%" align="center">
				<?php 
					echo "Comments: ".$comment;
				 ?>

			</td>
		</tr>
	</table>	
	<div align="center" style="margin-top:30px">
		<small>eReport Card,Powered by NetSoft Ltd.</small>
	</div>
	<center>
	<a href="<?php echo base_url(); ?>index.php?admin/student_marksheet_print_view/<?php echo $student['student_id']; ?>/<?php echo $exm['exam_id']; ?>" 
			class="btn btn-primary" target="_blank">
			<?php echo get_phrase('print_report card'); ?>
		</a>
	</center>
    <?php endforeach;?>
  </div>