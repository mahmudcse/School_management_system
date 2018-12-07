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

    <div style="border-top:1px solid rgba(69, 74, 84, 0.7);"></div>	
    <ul id="main-menu" class="">
        <!-- add class "multiple-expanded" to allow multiple submenus to open -->
        <!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->


        <!-- DASHBOARD -->
        <li class="<?php if ($page_name == 'dashboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/dashboard">
                <i class="entypo-suitcase"></i>
                <span><?php echo get_phrase('dashboard'); ?></span>
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
                        <a href="<?php echo base_url(); ?>index.php?teacher/manage_attendance">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('daily_atendance'); ?></span>
                        </a>
                    </li>

            </ul>
            <ul>

                    <li class="<?php if (( $page_name == 'attendance_report' || $page_name == 'attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?teacher/attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('attendance_report'); ?></span>
                        </a>
                    </li>

            </ul>
            <ul>

                    <li class="<?php if (($page_name == 'coursewise_attendance_report' || $page_name == 'coursewise_attendance_report_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?teacher/coursewise_attendance_report">
                            <span><i class="entypo-dot"></i><?php echo get_phrase('coursewise_report'); ?></span>
                        </a>
                    </li>

            </ul>
        </li>
		
        <!-- EXAMS -->
        <li class="<?php if ($page_name == 'exam_marks') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?teacher/exam_marks">
                        <span><i class="entypo-graduation-cap"></i> <?php echo get_phrase('exam_marks'); ?></span>
                    </a>
        </li>
         <li class="<?php if ($page_name == 'examtype_marks') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?teacher/examtype_marks">
                        <span>
                        <i class="entypo-graduation-cap"></i> <?php echo get_phrase('examtype_marks'); ?>
                        </span>
                    </a>
        </li>
       
       <li class="<?php if ($page_name == 'noticeboard') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?teacher/view_noticeboard">
                <i class="entypo-doc-text-inv"></i>
                <span><?php echo get_phrase('noticeboard'); ?></span>
            </a>
        </li>

        <!-- MESSAGE -->
        <li class="<?php if ($page_name == 'message') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/message">
                <i class="entypo-mail"></i>
                <span><?php echo get_phrase('message'); ?></span>
            </a>
        </li>
        <!-- CLASS ROUTINE -->
        <li class="<?php if ($page_name == 'class_routine' ||
                                $page_name == 'class_routine_print_view') 
                                    echo 'opened active'; ?> ">
            <a href="#">
                <i class="entypo-target"></i>
                <span><?php echo get_phrase('class_routine'); ?></span>
            </a>
            <ul>
                    	<?php $campus = $this->db->get('campus')->result_array(); foreach ($campus as $campusinfo):?>
                    	
                        <li>
                        	<a href=""><?php echo $campusinfo['campus_name']?></a>
                        	<ul>
                        		<?php
                        $classes = $this->db->get_where('class', array('campus_id' => $campusinfo['id']))->result_array();
                        foreach ($classes as $row):
                            ?>
                            <li class="<?php if ($page_name == 'class_routine' && $class_id == $row['class_id']) echo 'active'; ?>">
	                        <a href="<?php echo base_url(); ?>index.php?teacher/class_routine/<?php echo $row['class_id']; ?>">
	                            <span><?php echo get_phrase('class'); ?> <?php echo $row['name']; ?></span>
	                        </a>
                    		</li>
                        <?php endforeach; ?>
                        	</ul>
                        	
                        </li>
                        <?php endforeach; ?>
                    </ul>
        </li>
        <!-- ACADEDIC CALENDAR -->
		<li
			class="<?php if ($page_name == 'academic_calendar') echo 'active'; ?> ">
			<a href="<?php echo base_url(); ?>index.php?teacher/academic_calendar">
				<span><i class="entypo-calendar"></i> <?php echo get_phrase('academic_calendar'); ?></span>
			</a>
		</li>
		

        <!-- ACADEMIC SYLLABUS -->
        <li class="<?php if ($page_name == 'academic_syllabus') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?teacher/academic_syllabus">
                <i class="entypo-doc"></i>
                <span><?php echo get_phrase('academic_syllabus'); ?></span>
            </a>
        </li>
		<!-- STUDY MATERIAL -->
        <li class="<?php if ($page_name == 'study_material') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/study_material">
                <i class="entypo-book-open"></i>
                <span><?php echo get_phrase('study_material'); ?></span>
            </a>
        </li>
       


        <!-- LIBRARY -->
       <!--  <li class="<?php if ($page_name == 'book') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/book">
                <i class="entypo-book"></i>
                <span><?php echo get_phrase('library'); ?></span>
            </a>
        </li> -->

        <!-- TRANSPORT -->
       <!-- <li class="<?php if ($page_name == 'transport') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/transport">
                <i class="entypo-location"></i>
                <span><?php echo get_phrase('transport'); ?></span>
            </a>
        </li> -->
			 
       

        <!-- ACCOUNT -->
        <li class="<?php if ($page_name == 'manage_profile') echo 'active'; ?> ">
            <a href="<?php echo base_url(); ?>index.php?<?php echo $account_type; ?>/manage_profile">
                <i class="entypo-lock"></i>
                <span><?php echo get_phrase('account'); ?></span>
            </a>
        </li>

    </ul>

</div>