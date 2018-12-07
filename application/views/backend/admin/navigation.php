<?php 
    $groupClass = $this->db->query("select * from codes where key_name='class' or key_name = 'group' order by key_name")->result_array();

 ?>

<div class="sidebar-menu">
    <header class="logo-env" >

        <!-- logo -->
        <div class="logo" style="">
            <a href="<?php echo base_url(); ?>">
                <img src="uploads/logo.png"  style="max-height:60px;"/>
            </a>
        </div>

        <!-- logo collapse icon -->
        <div class="sidebar-collapse" style="">
            <a href="#" class="sidebar-collapse-icon with-animation">

                <i class="entypo-menu"></i>
            </a>
        </div>

        <!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
        <div class="sidebar-mobile-menu visible-xs">
            <a href="#" class="with-animation">
                <i class="entypo-menu"></i>
            </a>
        </div>
    </header>

    <div style=""></div>
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dashboard">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
            </a>
        </li>

        <!-- STUDENT -->
        <li class="<?php if ($page_name == 'student_add' ||
                                $page_name == 'student_bulk_add' ||
                                    $page_name == 'student_information' ||
                                      //  $page_name == 'student_marksheet' ||
        								$page_name == 'marksheet' ||
                                            $page_name == 'student_promotion')
                                                echo 'opened active has-sub';
        ?> ">
            <a href="#">
                <i class="fa fa-group"></i>
                <span><?php echo get_phrase('student'); ?></span>
            </a>
            <ul>
                <!-- STUDENT ADMISSION -->
                <li class="<?php if ($page_name == 'student_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_add">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_student'); ?></span>
                    </a>
                </li>

                <!-- STUDENT BULK ADMISSION
                <li class="<?php if ($page_name == 'student_bulk_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_bulk_add">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('admit_bulk_student'); ?></span>
                    </a>
                </li>
				-->
                <!-- STUDENT INFORMATION -->
                <li class="<?php if ($page_name == 'student_information' || $page_name == 'student_marksheet') echo 'opened active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_information'); ?></span>
                    </a>
                    <ul>
                    	<?php $campus = $this->db->get('campus')->result_array(); foreach ($campus as $campusinfo):?>

                        <li>
                        	<a href="<?php echo base_url(); ?>index.php?admin/student_information/<?php echo $campusinfo['id']; ?>"><?php echo $campusinfo['campus_name']?></a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!-- STUDENT PROMOTION -->
                <li class="<?php if ($page_name == 'student_promotion') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_promotion">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_promotion'); ?></span>
                    </a>
                </li>

            </ul>
        </li>

        <!-- TEACHER -->
        <li class="<?php if ($page_name == 'teacher') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/teacher">
                <i class="entypo-users"></i>
                <span><?php echo get_phrase('teacher'); ?></span>
            </a>
        </li>

        <!-- PARENTS -->
        <li class="<?php if ($page_name == 'parent') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/parent">
                <i class="entypo-user"></i>
                <span><?php echo get_phrase('parents'); ?></span>
            </a>
        </li>

		<li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/academic_syllabus">
                        <span><i class="entypo-docs"></i> <?php echo get_phrase('academic_syllabus'); ?></span>
                    </a>
          </li>

        <!-- SUBJECT -->
        <!-- <li class="<?php if ($page_name == 'subject') echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-docs"></i>
                <span><?php echo get_phrase('subject'); ?></span>
            </a>
            <ul>
                <?php
                $classes = $this->db->get('class')->result_array();
                foreach ($classes as $row):
                    ?>
                    <li class="<?php if ($page_name == 'subject' && $class_id == $row['class_id']) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/subject/<?php echo $row['class_id']; ?>">
                            <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li> -->

        <!-- CLASS ROUTINE -->
        <li class="<?php if ($page_name == 'class_routine_view' ||
                                $page_name == 'class_routine_add')
                                    echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-target"></i>
                <span><?php echo get_phrase('class_routine'); ?></span>
            </a>
            <ul>
                <?php
                $classes = $this->db->get('class')->result_array();
                foreach ($classes as $row):
                    ?>
                    <li class="<?php if ($page_name == 'class_routine_view' && $class_id == $row['class_id']) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/class_routine_view/<?php echo $row['class_id']; ?>">
                            <span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php 
                            if( $groupClass[0][value] == 'Class'){
                                echo $groupClass[0][value]." ";
                            }
                            echo $row['name']; ?></span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </li>
       <li class="<?php if ($page_name == 'view_notice') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_academic_calendar">
                        <span><i class="entypo-calendar"></i> <?php echo get_phrase('view_academic_calendar'); ?></span>
                    </a>
         </li>
		<!-- DAILY ATTENDANCE -->
        <li class="<?php
			if ($page_name == 'manage_attendance' ||
                 $page_name == 'manage_attendance_view' || $page_name == 'attendance_report' || $page_name == 'attendance_report_view'|| $page_name == 'coursewise_attendance_report' || $page_name == 'coursewise_attendance_report_view')
                 echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-chart-area"></i>
                <span><?php echo get_phrase('daily_attendance'); ?></span>
            </a>
            <ul>

                    <li class="<?php if (($page_name == 'manage_attendance' || $page_name == 'manage_attendance_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('daily_atendance'); ?></span>
                        </a>
                    </li>

            </ul>
            <ul>

                    <li class="<?php if (( $page_name == 'attendance_report' || $page_name == 'attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('attendance_report'); ?></span>
                        </a>
                    </li>

            </ul>
            <ul>

                    <li class="<?php if (($page_name == 'coursewise_attendance_report' || $page_name == 'coursewise_attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/coursewise_attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('coursewise_report'); ?></span>
                        </a>
                    </li>

            </ul>
        </li>
		<li class="<?php if ($page_name == 'exam_marks') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam_marks">
                        <span><i class="entypo-graduation-cap"></i> <?php echo get_phrase('exam_marks'); ?></span>
                    </a>
        </li>
        <li class="<?php if ($page_name == 'examtype_marks') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/examtype_marks">
                        <span><i class="entypo-graduation-cap"></i> <?php echo get_phrase('examtype_marks'); ?></span>
                    </a>
        </li>
        <!-- <li>
                    <a href="<?php //echo base_url(); ?>index.php?admin/result">
                        <span><i class="entypo-graduation-cap"></i> <?php //echo get_phrase('prepare_result'); ?></span>
                    </a>
        </li> -->
		 <li class="<?php if ($page_name == 'tabulation_sheet') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/tabulation_sheet">
                        <span><i class="entypo-doc-text-inv"></i> <?php echo get_phrase('tabulation_sheet'); ?></span>
                    </a>
         </li>

        <!-- PAYMENT -->
        <!-- <li class="<?php //if ($page_name == 'invoice') echo 'active'; ?> ">
            <a href="<?php //echo base_url(); ?>index.php?admin/invoice">
                <i class="entypo-credit-card"></i>
                <span><?php //echo get_phrase('payment'); ?></span>
            </a>
        </li> -->

        <!-- ACCOUNTING -->
        <li class="<?php
        if ($page_name == 'income' ||
        		$page_name == 'account' ||
        			$page_name == 'customer' ||
        			$page_name == 'item' ||
		                $page_name == 'expense' ||
        					$page_name == 'journal' ||
		                    $page_name == 'expense_category' ||
		                        $page_name == 'payment' ||
        		                    $page_name == 'feeconf' ||
        								$page_name == 'fee_collection' ||
        									$page_name == 'student_all_payments' ||
        										$page_name == 'receipt_and_payment' ||
        											$page_name == 'ledger' ||
        												$page_name == 'profitloss')
		                            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('accounting'); ?></span>
            </a>
            <ul>
            	<li class="<?php if ($page_name == 'fee_collection') echo 'opened active'; ?> ">
                    <a href="#">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('fee_collection'); ?></span>
                    </a>
                    <ul>
                    	<?php $campus = $this->db->get('campus')->result_array();
                      foreach ($campus as $campusinfo):?>

                        <li>

                          	<a href="<?php echo base_url(); ?>index.php?admin/feecollection/<?php echo $campusinfo['id']; ?>"><?php echo $campusinfo['campus_name']?></a>
                          	<!-- <ul>
                            	<?php
                                $classes = $this->db->get_where('class', array('campus_id' => $campusinfo['id']))->result_array();
                                foreach ($classes as $row):
                                    ?>
                                    <li class="<?php if ($page_name == 'student_information' && $page_name == 'student_marksheet' && $campus_id == $row['campus_id'] && $class_id == $row['class_id']) echo 'active'; ?>">
                                        <a href="<?php echo base_url(); ?>index.php?admin/feecollection/<?php echo $row['class_id']; ?>">
                                            <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                          	</ul> -->

                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?admin/student_payable">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_payable'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?admin/salary_payable">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('salary_payable'); ?></span>
                    </a>
                </li>
                <li>
                    <a href="<?php echo base_url(); ?>index.php?admin/employee_salary">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('employee_salary'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'student_all_payments') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/fee_invoice">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('fee_invoice'); ?></span>
                    </a>
                </li>
				<li class="<?php if ($page_name == 'fee_dues') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/feedues">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('fee_Dues'); ?></span>
                    </a>
                </li>
                <!-- <li class="<?php if ($page_name == 'payment') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/payment">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('payment'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'income') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/income">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_payments'); ?></span>
                    </a>
                </li> -->
                <li class="<?php if ($page_name == 'expense') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense'); ?></span>
                    </a>
                </li>
                <!--<li class="<?php if ($page_name == 'expense_category') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense_category">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('expense_category'); ?></span>
                    </a>
                </li>  -->
                <li class="<?php if ($page_name == 'journal') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/journal">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('journal'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'receipt_and_payment') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/receipt_and_payment">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('receipt_and_payment'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'ledger') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/ledger">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('ledger'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'profitloss') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/profitloss">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('profit_&_loss'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

        <!-- LIBRARY -->
        <!--
        <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/book">
                <i class="entypo-book"></i>
                <span><?php echo get_phrase('library'); ?></span>
            </a>
        </li>

        <li class="<?php if ($page_name == 'transport') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/transport">
                <i class="entypo-location"></i>
                <span><?php echo get_phrase('transport'); ?></span>
            </a>
        </li>


        <li class="<?php if ($page_name == 'dormitory') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/dormitory">
                <i class="entypo-home"></i>
                <span><?php echo get_phrase('dormitory'); ?></span>
            </a>
        </li>
 -->

        <!-- NoticeBoard added by Nishan-->
        <li class="<?php
        if ($page_name == 'view_notice' ||
                    $page_name == 'noticeboard')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-flow-tree"></i>
                <span><?php echo get_phrase('Noticeboard'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'view_notice') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/view_noticeboard">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('view_noticeboard'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
	            	<a href="<?php echo base_url(); ?>index.php?admin/noticeboard">
	                <i class="entypo-doc-text-inv"></i>
	                <span><?php echo get_phrase('manage_notice'); ?></span>
	            	</a>
        		</li>
            </ul>
        </li>


        <!-- MESSAGE -->
        <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/message">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('message'); ?></span>
            </a>
        </li>

        <!-- SETTINGS -->

        <li class="<?php
        if ($page_name == 'system_settings' ||
                $page_name == 'manage_language' ||
                    $page_name == 'sms_settings')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-lifebuoy"></i>
                <span><?php echo get_phrase('settings'); ?></span>
            </a>
            <ul>
				<li class="<?php if ($page_name == 'session') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/session">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('session'); ?></span>
                    </a>
         		</li>
                <li class="<?php if ($page_name == 'system_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/system_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('general_settings'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'sms_settings') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/sms_settings">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('sms_settings'); ?></span>
                    </a>
                </li>
                 <!-- Campus added by Nishan-->
        <li class="<?php
        if ($page_name == 'campus_add' ||
                    $page_name == 'campus_manage')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <span><i class="entypo-dot"></i><?php echo get_phrase('campus'); ?></span>
            </a>

            <ul>
                <li class="<?php if ($page_name == 'campus_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_campus">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('add_campus'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'campus_manage') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_campus">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_campus'); ?></span>
                    </a>
                </li>
          	  </ul>
        	</li>

			 <!-- Group added by Nishan-->
        <li class="<?php
        if ($page_name == 'group_add' ||
                    $page_name == 'group_manage')
                        echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-dot"></i>
                <span><?php echo $groupClass[1][value]; ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'group_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/add_group">
                        <span><i class="entypo-dot"></i> <?php echo "Add ".$groupClass[1][value]; ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'group_manage') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_group">
                        <span><i class="entypo-dot"></i> <?php echo "Manage ".$groupClass[1][value]; ?></span>
                    </a>
                </li>
            </ul>
        </li>
		 <!-- CLASS -->
        <li class="<?php
        if ($page_name == 'class' ||
                $page_name == 'section' ||
                    $page_name == 'academic_syllabus')
            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-dot"></i>
                <span><?php echo $groupClass[0][value]; ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'class') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/classes">
                        <span><i class="entypo-dot"></i> <?php echo "Manage ".$groupClass[0][value]; ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'section') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/section">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_sections'); ?></span>
                    </a>
                </li>

            </ul>
        </li>

		<!-- Course added by Nishan-->
        <li class="<?php
        if ($page_name == 'course' ||
                    $page_name == 'course_assigned' ||
        				$page_name == 'teacher_assigned')
                        echo 'opened active';
        ?> ">
          <a href="#">
                <i class="entypo-dot"></i>
                <span><?php echo get_phrase('course'); ?></span>
            </a>
            <ul>
                <li class="<?php if ($page_name == 'course_add') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/course">
                        <span><i class="entypo-docs"></i> <?php echo get_phrase('manage_course'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'group_manage') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/course_assigned">
                        <span><i class="entypo-docs"></i> <?php echo get_phrase('assigned_course'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'teacher_assigned') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/course_teacher">
                        <span><i class="entypo-docs"></i> <?php echo get_phrase('assigned_course_teacher'); ?></span>
                    </a>
                </li>
            </ul>
        </li>

		<!-- EXAMS -->
        <li class="<?php
        if ($page_name == 'exam' ||
        		//$page_name == 'courseconfig' ||
                $page_name == 'grade' ||
                $page_name == 'marks_manage' ||
        		$page_name == 'exam_marks' ||
        		$page_name == 'examtypes' ||
                    $page_name == 'exam_marks_sms' ||
                        $page_name == 'tabulation_sheet' ||
        					$page_name == 'session' ||
                            $page_name == 'marks_manage_view')
                                echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-dot"></i>
                <span><?php echo get_phrase('exam'); ?></span>
            </a>

            <ul>
            	<!-- <li class="<?php if ($page_name == 'courseconfig') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/courseconfig">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('course_config'); ?></span>
                    </a>
                </li> -->
                <li class="<?php if ($page_name == 'exam') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/exam">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_list'); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'examtypes') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/examtypes">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_types'); ?></span>
                    </a>
                </li>
                
                <li class="<?php if ($page_name == 'courseexamtypes') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/courseexamtypes">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('course_examtypes'); ?></span>
                    </a>
                </li>

                <li class="<?php if ($page_name == 'grade') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/grade">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('exam_grades'); ?></span>
                    </a>
                </li>
                <!--
                <li class="<?php if ($page_name == 'marks_manage' || $page_name == 'marks_manage_view') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/marks_manage">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('manage_marks'); ?></span>
                    </a>
                </li>
                 -->


            </ul>
        </li>
		 <!-- ACADEDIC CALENDAR -->

		<li
			class="<?php if ( $page_name == 'view_academic_calendar' ||
					  $page_name == 'academic_calendar') echo 'active'; ?> ">
			<a href="#">
                <i class="entypo-dot"></i>
                <span><?php echo get_phrase('academic_calendar'); ?></span>
            </a>
			<ul>

                <li class="<?php if ($page_name == 'manage_academic_calendar') echo 'active'; ?> ">
	            	<a href="<?php echo base_url(); ?>index.php?admin/academic_calendar">
	                <i class="entypo-dot"></i>
	                <span><?php echo get_phrase('manage_academic_calendar'); ?></span>
	            	</a>
        		</li>
            </ul>
		</li>


		<li class="<?php if ($page_name == 'code_element') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/code_element">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('code_element'); ?></span>
                    </a>
         </li>
		 <!-- ACCOUNTING -->
        <li class="<?php
        if ($page_name == 'income' ||
        		$page_name == 'account' ||
        			$page_name == 'customer' ||
        			$page_name == 'item' ||
		                $page_name == 'expense' ||
        					$page_name == 'journal' ||
		                    $page_name == 'expense_category' ||
		                        $page_name == 'payment' ||
        		                    $page_name == 'feeconf' ||
        								$page_name == 'fee_collection' ||
        									$page_name == 'student_all_payments' ||
        										$page_name == 'receipt_and_payment' ||
        											$page_name == 'ledger' ||
        												$page_name == 'profitloss')
		                            echo 'opened active';
        ?> ">
            <a href="#">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('accounting'); ?></span>
            </a>
            <ul>

            	<li class="<?php if ($page_name == 'account') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/account">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('account_management'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'item') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/item">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('item_management'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'customer') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/customer">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('customer'); ?></span>
                    </a>
                </li>
                 <li class="<?php if ($page_name == 'feeconf') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/feeconf">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('feeconf'); ?></span>
                    </a>
                </li>
                <li class="<?php if ($page_name == 'student_feeconf') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/student_feeConfig">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('student_fee_configuraton'); ?></span>
                    </a>
                </li>



            </ul>
        </li>

         <li class="<?php if ($page_name == 'manage_language') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/manage_language">
                        <span><i class="entypo-dot"></i> <?php echo get_phrase('language_settings'); ?></span>
                    </a>
                </li>
            </ul>
        </li>
        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?admin/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>
    </ul>

</div>
