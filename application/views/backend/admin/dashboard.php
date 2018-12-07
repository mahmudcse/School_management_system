
<hr />

<div class="row">
	<div class="col-md-12">
    	<div class="row">
            
            <div class="col-md-3">
                <div class="tile-stats tile-red">
                    <div class="icon"><i class="fa fa-group"></i></div>
					
                    <div class="num" data-start="0" data-end="<?php echo $this->db->count_all('student');?>" 
                    		data-postfix="" data-duration="1500" data-delay="0">0</div>
                   
					
                    <h3><?php echo get_phrase('student');?></h3>
                   <p>Total students</p>
                </div>
                
            </div>
			
			<div class="col-md-3">
                <div class="tile-stats tile-green">
                    <div class="icon"><i class="entypo-users"></i></div>
					<?php
					 $start_date = date ( 'Y-m-d', strtotime ( "0 days" ) );
		  			 $end_date = date ( 'Y-m-d', strtotime ( "0 days" ) );     
					 
					 $payBalance = "SELECT uniqueCode, ROUND(SUM(quantity*unitPrice),2) AS balance FROM(
                		SELECT DISTINCT td.componentId, td.accountId, td.itemId, td.transactionId, a.uniqueCode, td.quantity, td.unitPrice
                		FROM transaction_detail td
                		INNER JOIN transaction t ON (td.transactionId = t.componentId)
                		INNER JOIN account a ON (td.accountId = a.componentId)
                		INNER JOIN transaction_detail tdc ON (td.transactionId = tdc.transactionId AND tdc.`type` = -1)
                		INNER JOIN account ac ON (tdc.accountId = ac.componentId)
                		WHERE td.`type` = 1 AND ac.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND ac.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "'
                		AND t.tdate BETWEEN '".$start_date."' AND '".$end_date."'
                		) a
                		GROUP BY a.uniqueCode";

						//echo $recSql;
						//exit();
				
						$payBalancequery = $this->db->query($payBalance);
						$payBalanceData = $payBalancequery->result ();
		
		                $totalPay = 0.0;
		               	foreach ( $payBalanceData as $value ) {
	               		 $totalPay += $value->balance;
						 }
					?>
					
                    <div class="num" data-start="0" data-end="<?php echo $totalPay;?>" 
                    		data-postfix="" data-duration="800" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('Taka');?></h3>
                   <p>Today Payment</p>
                </div>
                
            </div>
            <div class="col-md-3">
                <div class="tile-stats tile-aqua">
                    <div class="icon"><i class="entypo-user"></i></div>
					<?php
					$recBalanceSql = "SELECT uniqueCode, ROUND(SUM(quantity*unitPrice),2) AS balance FROM(
                		SELECT DISTINCT td.componentId, td.accountId, td.itemId, td.transactionId, a.uniqueCode, td.quantity, td.unitPrice
                		FROM transaction_detail td
                		INNER JOIN transaction t ON (td.transactionId = t.componentId)
                		INNER JOIN account a ON (td.accountId = a.componentId)
                		INNER JOIN transaction_detail tdc ON (td.transactionId = tdc.transactionId AND tdc.`type` = 1)
                		INNER JOIN account ac ON (tdc.accountId = ac.componentId)
                		WHERE td.`type` = -1 AND ac.category1 = '" . Applicationconst::ACCOUNT_CAT1_ASSET . "' AND ac.category2 = '" . Applicationconst::ACCOUNT_CAT2_CURRENT_ASSET . "'
                		AND t.tdate BETWEEN '".$start_date."' AND '".$end_date."'
                		) a
                		GROUP BY a.uniqueCode";
              //  echo $recBalanceSql;                

                    $recBalancequery = $this->db->query($recBalanceSql);
                    $recBalanceData = $recBalancequery->result ();
					 $totalRec = 0.0;	
					foreach ( $recBalanceData as $value ) { $totalRec += $value->balance; } 
					
					?>
                    <div class="num" data-start="0" data-end="<?php echo $totalRec;?>" 
                    		data-postfix="" data-duration="500" data-delay="0">0</div>
                    
                    <h3><?php echo 'Taka' ?></h3>
                   <p>Today Receipt</p>
                </div>
                
            </div>
            <div class="col-md-3">
                <div class="tile-stats tile-blue">
                    <div class="icon"><i class="entypo-chart-bar"></i></div>
                    <?php 
						//$check	=	array(	'timestamp' => strtotime(date('Y-m-d')) , 'status' => '2' );
						//$query = $this->db->get_where('attendance' , $check);
						//$present_today		=	$query->num_rows();
						?>
					<?php
				$feeDues = "SELECT s.student_id, s.name, SUM(sfc.amount) AS dueAmount 
							 FROM student_feeconfig sfc
							 INNER JOIN student s ON (sfc.studentId = s.student_id)
							 INNER JOIN user u ON (u.reference_id = sfc.studentId AND u.user_type = 'STUDENT')
							 LEFT JOIN transaction_detail td ON (u.user_id = td.userId AND sfc.itemId = td.itemId AND month(str_to_date(sfc.month,'%M')) = td.month AND sfc.year = td.year)
							 WHERE DATEDIFF(NOW(), STR_TO_DATE(CONCAT(sfc.year,'/',sfc.month,'/01'),'%Y/%M/%d')) > 30
							  AND td.componentId IS NULL
							 GROUP BY s.student_id
							 ORDER BY dueAmount DESC";
					
					$feeDuesquery = $this->db->query($feeDues);
						$feeDuesData = $feeDuesquery->result ();
		
		                $totaldueAmount = 0.0;
		               	foreach ( $feeDuesData as $value ) {
	               		 $totaldueAmount += $value->dueAmount;
						 }
					
					?>	
                    <div class="num" data-start="0" data-end="<?php echo $totaldueAmount;?>" 
                    		data-postfix="" data-duration="500" data-delay="0">0</div>
                    
                    <h3><?php echo get_phrase('Taka');?></h3>
                   <p>Total fee dues up today</p>
                </div>
                
            </div>
			
			
			
			
			
		</div>
	</div>
</div>			
<div class="row">
	
    
	<div class="col-md-4">
    	<div class="row">
            <!-- CALENDAR-->
            <div class="col-md-12 col-xs-12">    
                <div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_phrase('Message');?>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:0px;">
                        <br />
						<a href="<?php echo base_url(); ?>index.php?admin/message/message_new" class="btn btn-success btn-icon btn-block">
								<?php echo get_phrase('Compose'); ?>
								<i class="entypo-pencil"></i>
							</a>
					   <br />
		    <?php
            $current_user = $this->session->userdata('login_type') . '-' . $this->session->userdata('login_user_id');

            $this->db->where('sender', $current_user);
            $this->db->or_where('reciever', $current_user);
            $message_threads = $this->db->get('message_thread')->result_array();
            		
            foreach ($message_threads as $row):
//             echo '<pre>';
//             print_r($row);
//             echo '</pre>';
                // defining the user to show
                if ($row['sender'] == $current_user)
                    $user_to_show = explode('-', $row['reciever']);
                if ($row['reciever'] == $current_user)
                    $user_to_show = explode('-', $row['sender']);
                

                $user_to_show_type = $user_to_show[0];
                $user_to_show_id = $user_to_show[1];
                $unread_message_number = $this->crud_model->count_unread_message_of_thread($row['message_thread_code']);
                ?>
                <div class="<?php if (isset($current_message_thread_code) && $current_message_thread_code == $row['message_thread_code']) echo 'active'; ?>">
                    <a href="<?php echo base_url(); ?>index.php?admin/message/message_read/<?php echo $row['message_thread_code']; ?>" style="padding:12px;">
                        <i class="entypo-dot"></i>

                        <?php echo $this->db->get_where($user_to_show_type, array($user_to_show_type . '_id' => $user_to_show_id))->row()->name; ?>

                        <span class="badge badge-default pull-right" style="color:#fff;"><?php echo $user_to_show_type; ?></span>

                        <?php if ($unread_message_number > 0): ?>
                            <span class="badge badge-secondary pull-right">
                                <?php echo $unread_message_number; ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endforeach; ?>
        
                    </div>
                </div>
            </div>
        </div>
    </div>


<div class="col-md-4">
    	<div class="row">
            <!-- CALENDAR-->
            <div class="col-md-12 col-xs-12">    
                <div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_phrase('Noticeboard');?>
                        </div>
                    </div>
                    <div class="tile-stats tile-white panel-body">
                    <marquee id='scroll_news' behavior="scroll" direction="up" scrollamount="3" style="height:300px;" onMouseOver="document.getElementById('scroll_news').stop();" onMouseOut="document.getElementById('scroll_news').start();">
                    <?php
                    	$this->db->select('*');
                    	$this->db->from('noticeboard');
                    	//$this->db->where('create_timestamp >', 'DATE_SUB(NOW(), INTERVAL 1 MONTH)');
                    	$noticeinfo = $this->db->get()->result_array();
                    foreach ($noticeinfo as $row):?>
                    <a href="<?php echo base_url();?>index.php?admin/view_noticeboard"><h3><?php echo $row['notice_title'];?></h3>
                    <?php echo 'Published: '.date("d/m/Y", strtotime($row['create_timestamp']));?>
                    </a>
                    <hr>
                    <?php endforeach;?>
                    </marquee>                   
                   	
                </div>
                
            </div>
            
            
    	</div>
    </div>
</div>
<div class="col-md-4">
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

  
