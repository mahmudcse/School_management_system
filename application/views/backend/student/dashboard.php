<?php
$student_id = $this->session->userdata('student_id');
$class_id = $this->db->get_where('enroll', array('student_id' => $student_id))->row()->class_id;
$cours_by_class = $this->db->get_where('course', array('class_id' => $class_id))->result_array(); 
?>
<div class="row">
	<div class="col-md-8">
    	<div class="row">
    		
    		<hr />

<br>

<div class="row">
    <div class="col-md-12">
    
        <div class="tabs-vertical-env">
        
            <ul class="nav tabs-vertical">
            
                <?php foreach ($cours_by_class as $value): ?>
                    
                
                <li class="<?php if ($value['course_id'] == $course_id) echo 'active';?>">

                    <a href="<?php echo base_url();?>index.php?student/get_students_mark_dashboard/<?php echo $value['course_id'];?>">
                                            
                      <?php echo $value['tittle'];?>  

                    </a>

                </li>
            <?php endforeach ?>
            </ul>
            
            <div class="tab-content">

                <div class="tab-pane active">
                    <table class="table table-bordered responsive">
                        <thead>
                            <tr>
                                    <th>Exam</th>
                                    <th style="text-align: center;"><?php echo get_phrase('mark_obtained')?></th>
                                    <th>Out of</th>
                                    <th style="text-align: center;">Grade</th>
                                    <th style="text-align: center;">Highest Mark</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php foreach ($results as $rslt):?>
                                    <tr>
                                        <td><?php echo $rslt['exam_name'];?></td>
                                        <td style="text-align: center;"><?php echo $rslt['mark_obtained'];?></td>
                                        <td><?php echo $rslt['total_mark'];?></td>
                                        <td style="text-align: center;"><?php echo $rslt['grade'];?></td>
                                        <td style="text-align: center;"><?php echo $rslt['highestMark'];?></td>
                                    </tr> 

                            <?php endforeach;?>

                        </tbody>
                    </table>
                </div>

            </div>
            
        </div>  
    
    </div>
</div>
    		<!-- <div class="box-content">
						<table style="width:100%;">
							<thead>
								<tr>
									<th>Course</th>
									<th>Exam</th>
									<th>Mark</th>
									<th>Out of</th>
									<th>Grade</th>
									<th>Highest Mark</th>
								</tr>
							</thead>
							<tbody>
							<?php 
							foreach ($results as $rslt):?>
								<tr>
									<td><?php echo $rslt['course_name'];?></td>
									<td><?php echo $rslt['exam_name'];?></td>
									<td><?php echo $rslt['mark_obtained'];?></td>
									<td><?php echo $rslt['total_mark'];?></td>
									<td><?php echo $rslt['grade'];?></td>
									<td><?php echo $rslt['highest_mark'];?></td>
								</tr>
							<?php endforeach;?>
								<tr>
									<th colspan="4">  
										<a  data-toggle="modal" href="#modal-form" onclick="modal('student_academic_result',<?php echo $this->session->userdata('student_id');?>)" class="btn btn-default btn-small">
                                            <i class="icon-file-alt"></i> <?php echo get_phrase('marksheet');?>
                                        </a>
                                    </th>
								</tr>
							</tbody>
						</table>
						
						

					</div>  --> 
    		
    		
            <!-- CALENDAR-->
            <div class="col-md-12 col-xs-12">    
                <div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_phrase('event_schedule');?>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:0px;">
                        <div class="calendar-env">
                            <div class="calendar-body">
                                <div id="notice_calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	<div class="col-md-4">
		<div class="row">
			<div class="col-md-12">
				<div style="text-align: center; font-size: 18px; color: #fff; padding: 8px; margin-bottom: 5px; background-color: grey;">NoticeBoard</div>
                <div class="tile-stats tile-white">
                    <marquee behavior="scroll" direction="up" scrollamount="3" style="height:200px;">
                    <?php $noticeinfo = $this->db->get('noticeboard')->result_array(); foreach ($noticeinfo as $row):?>
                    <a href="<?php echo base_url();?>index.php?student/view_noticeboard"><h3><?php echo $row['notice_title'];?></h3>
                    <?php echo 'Published: '.date('d-m-y')?>
                    </a>
                    <hr>
                    <?php endforeach;?>
                    </marquee>                   
                   
                </div>
                
            </div>
            <div class="col-md-12">
            
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-group"></i></div>
                    <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('student');?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('student');?></h3>
                   <p>Total students</p>
                </div>
                
            </div>
            <div class="col-md-12">
            
                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-users"></i></div>
                    <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('teacher');?>" 
                    		data-postfix="" data-duration="800" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('teacher');?></h3>
                   <p>Total teachers</p>
                </div>
                
            </div>
            <div class="col-md-12">
            
                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="entypo-user"></i></div>
                    <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('parent');?>" 
                    		data-postfix="" data-duration="500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('parent');?></h3>
                   <p>Total parents</p>
                </div>
                
            </div>
            <div class="col-md-12">
            
                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <?php 
						$check   =   array(  'timestamp' => strtotime(date('Y-m-d')) , 'status' => '1' );
                        $query = $this->db->get_where('attendance' , $check);
                        $present_today      =   $query->num_rows();
						?>
                    <div class="num" data-start="0" data-end="<?php echo $present_today;?>" 
                    		data-postfix="" data-duration="500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('attendance');?></h3>
                   <p>Total present student today</p>
                </div>
                
            </div>
    	</div>
    </div>
	
</div>



    <script>
  $(document).ready(function() {
	  
	  var calendar = $('#notice_calendar');
				
				$('#notice_calendar').fullCalendar({
					header: {
						left: 'title',
						right: 'today prev,next'
					},
					
					//defaultView: 'basicWeek',
					
					editable: false,
					firstDay: 1,
					height: 530,
					droppable: false,
					
					events: [
						<?php 
						$ac_calendar_event	=	$this->db->get('academic_calendar')->result_array();
						foreach($ac_calendar_event as $row):
						?>
						{
							title: "<?php echo $row['event'];?>",
							start: new Date(<?php echo date('Y',strtotime($row['start_date']));?>, <?php echo date('m',strtotime($row['start_date']))-1;?>, <?php echo date('d',strtotime($row['start_date']));?>),
							end:	new Date(<?php echo date('Y',strtotime($row['end_date']));?>, <?php echo date('m',strtotime($row['end_date']))-1;?>, <?php echo date('d',strtotime($row['end_date']));?>) 
						},
						<?php 
						endforeach
						?>
						
					]
				});
	});
  </script>

  
