<table style="width:100%;">
	<tr>
		<td align="center">
			 <img src="<?php echo base_url();?>uploads/oisd.png" width="150" height="147" /> 
		</td>
	</tr>
</table>

<div align="center"><H3>REPORT CARD</H3></div>

<div style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px; margin-top:0px; margin-bottom:0px;" align="left">
	<table style="width:100%;">
		<tr>
			<td width="18%"> <strong>Student Name</strong></td>
			<td width="1%"> : </td>
			<td width="58%"><?php echo $student['name'];?></td>
			<td width="10%"><strong>Class</strong></td>
			<td width="1%"> : </td>
			<td width="12%"> <?php echo $class['name'];?> </td>
		</tr>
		<tr>
			<td width="18%"><strong>Father's Name</strong></td>
			<td width="1%"> : </td>
			<td width="58%"><?php echo $student['father_name'];?></td>
			<td width="10%"><strong>Roll No</strong> </td>
			<td width="1%"> : </td>
			<td width="12%"><?php echo $student['roll'];?></td>
		</tr>
	</table>							
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
?>
	<div class="accordion-group">
		<div class="accordion-heading">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapse<?php echo $row0['exam_id'];?>" style="text-decoration:none">
				<i class="icon-rss icon-1x"></i>
				<div style="margin-bottom:10px; margin-top:10px; padding:10px;background-color:#F3F3F3; font-size:16px;font-weight:bold">
				<?php echo $exm['name'];?>
				</div>
         	</a>
     	</div>
	   	<div id="collapse<?php echo $exm['exam_id'];?>" class="accordion-body collapse <?php if($toggle){echo 'in';$toggle=false;}?>" >
	    	<div class="accordion-inner">
			    <table border="1" style="width:100%; border-collapse:collapse; border:1px solid #CCCCCC; text-align:center; margin:15px 0px 20px 0px"> 
			    	<thead>
			        	<tr bgcolor="#F3F3F3">
							<th>Subjects</th>
							<?php foreach ($header[$exm['exam_id']] as $head): ?>
							<th><?php echo $head['name'];?></th>
							<?php endforeach;?>
							<th>Grade</th>
							<th>Highest Marks</th>
			         	</tr>
			   		</thead>
			        <tbody>
			        	<?php foreach ($courses as $course): 
			        	$grade = '';
			        	$highestMark = '';
			        	
			        	?>
			        	<tr>
			           		<td><?php echo $course['tittle'];?></td>
							<?php foreach ($header[$exm['exam_id']] as $head): 
							
								if($head['report_card']==1){
									$total_examMark += $head['total_mark'];
									$total_grade_point += $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['gradePoint'];
									$highest_total_grade_point += $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['highestGradePoint'];
									$grade = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['grade'];
									$highestMark = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['highestMark'];
									$highest_total_marks += $highestMark;
									$total_marks += $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'];
								}
							?>
							<td><?php echo $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'];?></td>
							<?php endforeach;?>
							<td><?php echo $grade;?></td>
							<td><?php echo $highestMark;?></td>
			          	</tr>
			          	<?php endforeach;?>
						<tr>
							<td colspan="<?php echo count($header[$exm['exam_id']]);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">						
								<strong>Grand Total</strong>								
							</td>
							<td colspan="2">
								<strong><?php	echo $total_marks;?></strong>	
							</td>
							<td>	
								<div align="right">
									<?php	echo $highest_total_marks;?>
								</div>
																
							</td>							
						</tr>
						<tr>
							<td colspan="<?php echo count($header[$exm['exam_id']]);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
								<strong>Percentage</strong>
							</td>	
							<td colspan="2">
								<strong><?php	echo round($total_marks*100/$total_examMark , 2);?></strong>	
							</td>	
							<td>	
								<div align="right">
									<?php echo round($highest_total_marks*100/$total_examMark , 2);?>
								</div>
							</td>							
						</tr>
						<tr>
							<td colspan="<?php echo count($header[$exm['exam_id']]);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
								<strong>Average Grade</strong>
							</td>	
							<td colspan="2">
								<strong><?php 
									//$gp =  round($total_grade_point/$total_subjects , 2);
									$avmrks = round($total_marks*100/$total_examMark);
									$lg = "";
									foreach ($grades as $grade):
										if($avmrks>=$grade['mark_from'] && $avmrks<=$grade['mark_upto']){
											$lg = $grade['name'];
											$comment = $grade['comment'];
											break;
										}
									endforeach;
									echo $lg;
								?></strong>	
							</td>	
							<td>	
								<div align="right">
									<?php //$gp = round($highest_total_grade_point/$total_subjects , 2);
									$avmrks = round($highest_total_marks*100/$total_examMark);
									$lg = "";
									foreach ($grades as $grade):
					
									if($avmrks>=$grade['mark_from'] && $avmrks<=$grade['mark_upto']){
											$lg = $grade['name'];
											break;
									}
									endforeach;
									echo $lg;
									?>
									
								</div>
							</td>
						</tr>
			 		</tbody>
			 	</table>
           	</div>
         </div>
     </div>
     <div><strong>Comments</strong></div>
	<div style="border:1px solid #F3F3F3; height:50px; margin-top:10px">
	<?php echo $comment;?>
	</div>
    <?php endforeach;?>
  </div>
  
	<table style="margin:100px 0px 20px 0px;width:100%;">
		<tr>
			<td width="25%" align="center" style="border-top: 1px solid #ddd;">Class Teacher</td>
			<td width="50%">&nbsp;</td>
			<td width="25%" align="center" style="border-top: 1px solid #ddd;">Principal</td>
		</tr>
	</table>	
	<div><strong>Grading</strong></div>
	<div style="border:1px solid #F3F3F3;padding:20px; margin-top:10px">
		A+ - Excellent , A - Very Good, B - Good , C - Satisfactory , D - Needs  Improvement , E - Unclassified	</div>
	<div align="center" style="margin-top:30px">
		<small>eReport Card,Powered by NetSoft Ltd.</small>
	</div>
						