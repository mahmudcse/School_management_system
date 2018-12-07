<script src="http://cdnjs.cloudflare.com/ajax/libs/jspdf/0.9.0rc1/jspdf.min.js"></script>

<div class="btn btn-success" id="pdf">Print</div>

<div id="makepdf">
<?php 

    foreach ($students as $student):

        foreach ($exam as $exm):
            $header[$exm['exam_id']] = $this->db->get_where('vgradesheetheader' , array('exam_id'=>$exm['exam_id'], 'class_id'=>$class_id))->result_array();
            $marks = $this->db->get_where('vstudentcoursemark' , array('exam_id'=>$exm['exam_id'], 'student_id'=>$student['student_id']))->result_array();
                
            foreach ($marks as $mark):
                $marks[$exm['exam_id']][$mark['course_id']][$mark['examtype_id']] = array('mark_obtained'=>$mark['mark_obtained'], 'grade'=>$mark['grade'], 'gradePoint'=>$mark['gradePoint'], 'highestGradePoint'=>$mark['highestGradePoint'],'highestMark'=>$mark['highestMark']);
            endforeach;
        endforeach;
 ?>

<div class="eachPage">
    


<div align="center"><H3>REPORT CARD</H3></div>

<div style="font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;font-size:12px; margin-top:0px; margin-bottom:0px;" align="left">
    <table style="width:100%;">
        <tr>
            <td width="18%"> <strong>Student Name</strong></td>
            <td width="1%"> : </td>
            <td width="58%"><?php echo $student['name'];?></td>
            <td width="10%"><strong><?php echo $groupClass[0][value]; ?></strong></td>
            <td width="1%"> : </td>
            <td width="12%"> <?php
                echo $student['class_name'];?> 
            </td>
        </tr>
        <tr>
            <td width="18%"><strong>Father's Name</strong></td>
            <td width="1%"> : </td>
            <td width="58%"><?php echo $student['fathername'];?></td>
            <td width="10%"><strong>Section</strong> </td>
            <td width="1%"> : </td>
            <td width="12%"><?php echo $student['section_name'];?></td>
        </tr>
        <tr>
            <td width="18%"><strong>Session</strong></td>
            <td width="1%"> : </td>
            <td width="58%"><?php echo $student['uniqueCode'];?></td>
            <td width="10%"><strong>Registration</strong> </td>
            <td width="1%"> : </td>
            <td width="12%"><?php echo $student['student_code'];?></td>
        </tr>
    </table>                            
</div>

<div class="accordion" id="accordion2">
<?php

$toggle = true;

foreach ($exam as $exm):
    $total_grade_point  =   0;
    $highest_total_grade_point  =   0;
    $total_marks        =   0;
    $highest_total_marks        =   0;
    $total_subjects     =   count($courses);
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

                        foreach ($courses as $course): 
                        $grade = '';
                        $highestMark = '';
                        $uniqueRow = array();


                        foreach ($header[$exm['exam_id']] as $head): 
                            if(isset($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'])){
                                $uniqueRow[$head['name']] = $marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'];
                            }

                            if($head['report_card']==1){
                                if($marks[$exm['exam_id']][$course['course_id']][$head['examtype_id']]['mark_obtained'] != ''){
                                    $total_examMark += $head['total_mark'];
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
                            <?php foreach ($uniqueHeaders as $idx=>$head): 
                            ?>
                            <td><?php echo $uniqueRow[$idx];?></td>
                            <?php endforeach;?>
                            <td><?php echo $grade;?></td>
                            <?php 
                                $gradePoint = $this->db->get_where('grade', array('name' => $grade))->row()->grade_point;
                             ?>
                            <td><?php echo $gradePoint; ?></td>
                            <td><?php echo $highestMark;?></td>
                        </tr>
                        <?php endforeach;?>
                        <tr>
                            <td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">                      
                                <strong>Grand Total</strong>                                
                            </td>
                            <td colspan="3">
                                <strong><?php   echo $total_marks;?></strong>   
                            </td>
                            <td>    
                                <div align="center">
                                    <?php   echo $highest_total_marks;?>
                                </div>                          
                            </td>                           
                        </tr>
                        <tr>
                            <td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
                                <strong>Percentage</strong>
                            </td>   
                            <td colspan="3">
                                <strong><?php
                                    echo round($total_marks*100/$total_examMark);
                                    ?></strong> 
                            </td>   
                            <td style="text-align: center;">    
                                <div>
                                    <?php echo round($highest_total_marks*100/$total_examMark);?>
                                </div>
                            </td>                           
                        </tr>
                        <tr>
                            <td colspan="<?php echo count($uniqueHeaders);?>" align="right" style="height:30px; font-size:14px; font-weight:bold; padding-right:20px">
                                <strong>Average Grade</strong>
                            </td>   
                            <td colspan="3">
                                <strong><?php 
                                    //$gp =  round($total_grade_point/$total_subjects , 2);
                                    $avmrks = round($total_marks*100/$total_examMark);
                                    $lg = "";
                                    foreach ($grades as $grade):
                                        if($avmrks>=$grade['mark_from'] && $avmrks<=$grade['mark_upto']){
                                            $lg = $grade['name'];

                                            $everagePoint = $this->db->get_where('grade', array('name' => $lg))->row()->grade_point;
                             


                                            $comment = $grade['comment'];
                                            break;
                                        }
                                    endforeach;
                                    echo $lg." ($everagePoint) ";
                                ?></strong> 
                            </td>   
                            <td style="text-align: center;">    
                                <div>
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
    <div style="border:1px solid #F3F3F3; height:50px; margin-top:10px;padding-top: 10px; padding-left: 10px;">
     <?php echo $comment;?>
    </div>
        <table style="margin:100px 0px 20px 0px;width:100%;">
        <tr>
            <td width="25%" align="center" style="border-top: 1px solid #ddd;">
                <?php 
                    $teacher = $this->db->get_where('codes', array('key_name' => 'teacher'))->row()->value;
                    echo $teacher;

                 ?>
            </td>
            <td width="50%">&nbsp;</td>
            <td width="25%" align="center" style="border-top: 1px solid #ddd;">
                <?php 
                    $head_master = $this->db->get_where('codes', array('key_name' => 'head'))->row()->value;
                    echo $head_master;

                 ?>

            </td>
        </tr>
    </table>    
    <div><strong>Grading</strong></div>
    <div style="border:1px solid #F3F3F3;padding:20px; margin-top:10px; font-size: 15px; font-weight: 200">
        <?php foreach ($grades as $grade): ?>
            <span><?php echo " ".$grade[name]." : ".$grade[comment]."," ?></span>
        <?php endforeach ?>
    </div>
    <div align="center" style="margin-top:30px">
        <small>eReport Card,Powered by NetSoft Ltd.</small>
    </div>

</div> <!-- eachPage class ends here -->

    <?php 
        endforeach;
    endforeach;

    ?>
  </div>

  </div> <!-- makepdf id ends here -->

  <script>
      $("#pdf").on("click", function () {
            var divContents = $("#makepdf").html();
            var printWindow = window.open('', '', 'height=400,width=800');
            printWindow.document.write('<html><head><title>DIV Contents</title>');
            printWindow.document.write('</head><body >');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        });



  </script>

