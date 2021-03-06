<div class="row">
	<div class="col-md-8">
    	<div class="row">
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
                    <a href="<?php echo base_url();?>index.php?admin/view_noticeboard"><h3><?php echo $row['notice_title'];?></h3>
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

  
