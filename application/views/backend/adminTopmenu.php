<li class="dropdown language-selector <?php if ($page_name == 'student_information' || $page_name == 'student_marksheet') echo 'opened active'; ?>">
         
		            <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        <i class="entypo-user"></i> <?php echo get_phrase('student_information'); ?></span>
                    </a>
                    <ul class="dropdown-menu <?php if ($text_align == 'right-to-left') echo 'pull-right'; else echo 'pull-left';?>">
                    	<?php $campus = $this->db->get('campus')->result_array(); foreach ($campus as $campusinfo):?>
                    	
                        <li>
                        	<a href="<?php echo base_url(); ?>index.php?admin/student_information/<?php echo $campusinfo['id']; ?>"><?php echo $campusinfo['campus_name']?></a>
                        	
                        	
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
		
		<li class="<?php if (($page_name == 'manage_attendance' || $page_name == 'manage_attendance_view')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance">
                            <span><i class="entypo-chart-area"></i><?php echo get_phrase('daily_attendance'); ?></span>
                        </a>
         </li>
		
		<li class="dropdown language-selector <?php if ($page_name == 'fee_collection') echo 'opened active'; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" data-close-others="true">
                        	<i class="entypo-user"></i> <?php echo get_phrase('fee_collection'); ?>
                    </a>
					
					 <ul class="dropdown-menu <?php if ($text_align == 'right-to-left') echo 'pull-right'; else echo 'pull-left';?>">
                    	<?php $campus = $this->db->get('campus')->result_array(); foreach ($campus as $campusinfo):?>
                    	
                        <li>
                            <a href="<?php echo base_url(); ?>index.php?admin/feecollection/<?php echo $campusinfo['id']; ?>"><?php echo $campusinfo['campus_name']?></a>


                        	<!-- <a href=""><?php echo $campusinfo['campus_name']?></a>
                        	<ul>
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
	
		<li class="<?php if ($page_name == 'exam_marks') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/examtype_marks">
                        <span><i class="entypo-graduation-cap"></i> <?php echo get_phrase('examtype_marks'); ?></span>
                    </a>
        </li>
		 <li class="<?php if ($page_name == 'expense') echo 'active'; ?> ">
                    <a href="<?php echo base_url(); ?>index.php?admin/expense">
                        <span><i class="entypo-docs"></i> <?php echo get_phrase('expense'); ?></span>
                    </a>
                </li> 
		<li class="<?php if (($page_name == 'notification')) echo 'active'; ?>">
                        <a href="<?php echo base_url(); ?>index.php?admin/notification">
                            <span><i class="entypo-chart-area"></i><?php echo get_phrase('notification'); ?></span>
                        </a>
         </li>