<hr />

<?php 
echo form_open(base_url() . 'index.php?admin/attendance_report_selector/'); ?>
<div class="row">
    
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo get_phrase(group); ?></label>
            <select name="group_id" id="group_id" class="form-control selectboxit">
            <?php 
                $groups = $this->db->get('class_group')->result_array();
                foreach ($groups as $group) {?>
                     <option value="<?php echo $group['id']; ?>" <?php if($group['id'] == $group_id) echo "selected"; ?>>
                        <?php echo $group['group_name']; ?>
                    </option>
                <?php 
                }
             ?>
               
            </select>
        </div>
    </div>


    <?php
    $query = $this->db->get('class');
    if ($query->num_rows() > 0):
        $class = $query->result_array();
        ?>

        <div class="col-md-2">
            <div class="form-group">
                <label class="control-label" style="margin-bottom: 5px;"><?php echo get_phrase('class'); ?></label>
                <select class="form-control selectboxit" name="class_id" onchange="select_section(this.value)">
                    <option value=""><?php echo get_phrase('select_class'); ?></option>
                    <?php foreach ($class as $row): ?>
                        <option value="<?php echo $row['class_id']; ?>"<?php if ($class_id == $row['class_id']) echo 'selected'; ?> ><?php echo $row['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-md-2">
         <div class="form-group">
             <label class="control-label"><?php echo get_phrase(sections); ?></label>
             <select name="section_id" id="section_holder" class="form-control">
                 <?php 
                     $sections = $this->db->get_where('section', array('group_id' => $group_id, 'class_id' => $class_id))->result_array();
                     foreach ($sections as $section) {?>
                         <option value="<?php echo $section['section_id']; ?>" <?php if($section['section_id'] == $section_id) echo "selected"; ?>>
                            <?php echo $section['name']; ?>
                         </option>
                         <?php 
                     }
                  ?>  
             </select>
         </div>
     </div>

     <div class="col-md-3">
         <div class="form-group">
             <label class="control-label"><?php echo get_phrase(courses); ?></label>
             <select name="course_id" id="course_holder" class="form-control">
                <?php 
                    $courses = $this->db->get_where('course', array('class_id' => $class_id, 'group_id' => $group_id))->result_array();

                    foreach ($courses as $course) {?>
                        <option value="<?php echo $course['course_id']; ?>" <?php if($course['course_id'] == $course_id) echo "selected"; ?>>
                            <?php echo $course['tittle']; ?>
                        </option>
                    <?php 
                    }
                 ?>
                 
             </select>
         </div>
     </div>

     <div class="col-md-2">
         <div class="form-group">
             <label class="control-label"><?php echo get_phrase(date); ?></label>
             <input type="text" name="timestamp" class="form-control datepicker" data-format="dd-mm-yyyy" value="<?php echo date("d-m-Y", $timestamp); ?>">
         </div>
     </div>

    <input type="hidden" name="year" value="<?php echo $running_year; ?>">

    <div class="col-md-3" style="margin-top: 20px;">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('show_report'); ?></button>
    </div>

</div>


<?php if ($group_id != '' && $class_id != '' && $section_id != '' && $timestamp != ''): ?>

    <br>
    <div class="row">
        <div class="col-md-4" style="text-align: center;">
            <div class="tile-stats tile-gray">
                <div class="icon"><i class="entypo-docs"></i></div>
                <h3 style="color: #696969;">
                    <?php
                    $group_name = $this->db->get_where('class_group', array('id' => $group_id))->row()->group_name;
                    $section_name = $this->db->get_where('section', array('section_id' => $section_id))->row()->name;
                    $class_name = $this->db->get_where('class', array('class_id' => $class_id))->row()->name;
                    echo get_phrase('attendance_sheet')."<br>";
                    
                    ?>
                </h3>

                <h4 style="color: #696969;">
    <?php echo get_phrase($group_name)."<br>";
    echo get_phrase('class') . ' ' . $class_name; ?> : <?php echo get_phrase('section');?> <?php echo $section_name; ?><br>
    <?php echo date(F,$timestamp);?>
                </h4>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>


    <?php 
    $query = "SELECT * FROM enroll
            JOIN section ON enroll.section_id = section.section_id
            WHERE 
            enroll.class_id = $class_id AND 
            enroll.session_id = $running_year AND 
            enroll.section_id = $section_id AND
            section.group_id = $group_id";
    $students = $this->db->query($query)->result_array();

     ?>

    <hr />

    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered" id="my_table">
                <thead>
                    <tr>
                        <td style="text-align: center;">
                            <?php echo get_phrase('students'); ?> <i class="entypo-down-thin"></i> | <?php echo get_phrase('date'); ?> <i class="entypo-right-thin"></i>
                        </td>
                            <?php
                            $year = explode('-', $running_year);
                            $month = date(n, $timestamp);
                            $year = date(Y, $timestamp);
                            $days = cal_days_in_month(CAL_GREGORIAN, $month, $year[0]);
                        	
                            for ($i = 1; $i <= $days; $i++) {
                                ?>
                                                    <td style="text-align: center;"><?php echo $i; ?></td>
                                            <?php } ?>

                    </tr>
                </thead>

                <tbody>
                            <?php
                            $data = array();

                            $present = 0;
                            $absent  = 0;
                            $absent_phones = array();
                            
                            foreach ($students as $row):
                                ?>
                        <tr>
                            <td style="text-align: center;">
                            <?php echo $this->db->get_where('student', array('student_id' => $row['student_id']))->row()->name; ?>
                            </td>
                            <?php
                            $status = 0;
                            for ($i = 1; $i <= $days; $i++) {
                                $studentId = $row['student_id'];

                                $attendance = "SELECT * FROM attendance
                                            JOIN section ON attendance.section_id = section.section_id
                                            WHERE section.group_id = $group_id AND
                                            attendance.class_id = $class_id AND
                                            attendance.section_id = $section_id AND
                                            attendance.year = $running_year AND
                                            attendance.student_id = $studentId GROUP BY timestamp";

                                $attendance = $this->db->query($attendance)->result_array();

                              foreach ($attendance as $row1):
                                    $date_dummy = date('j', $row1['timestamp']);
                                    $month_dummy = date(n, $row1['timestamp']);
                                    $year_dummy = date(Y, $row1['timestamp']);
                                    if ($i == $date_dummy && $month == $month_dummy && $year == $year_dummy)
                                    $status = $row1['status'];

                                    if($status == 2){
                                        $absent_student_id = $row1['student_id'];
                                        $query = "SELECT parent.phone FROM parent 
                                        JOIN student ON student.parent_id = parent.parent_id
                                        WHERE student.student_id = $absent_student_id";

                                        $absent_phone = $this->db->query($query)->row()->phone;
                                        array_push($absent_phones, $absent_phone); ?>
                                        <input type="hidden" name="absent_phones[]" value="<?php echo $absent_phone; ?>">
                                        
                                    <?php  }
                                endforeach;
								
                                ?>
                                <td style="text-align: center;">
                                    <?php if ($status == 1) { 
                                            $present++;
                                        ?>
                                        <!--<i class="entypo-record" style="color: #00a651;"></i>-->
										<div style="color: #00a651">P</div>
                                    <?php $status = 0;} else if ($status == 2) {
                                            $absent++;

                                     ?>
                                        <!--<i class="entypo-record" style="color: #ee4749;"></i>-->
										<div style="color: #ee4749">A</div>
                                    <?php $status = 0;} ?>
                                </td>

                            <?php } 
                            endforeach; ?>

                    </tr>
                </tbody>
            </table>
        


        <div class="container">

                <br>
            <div class="row">
                <div class="col-md-4"></div>
                <div class="col-md-4" style="text-align: center;">
                    <div class="tile-stats tile-gray">
                        <div class="icon"><i class="entypo-docs"></i></div>
                        <h4 style="color: #696969;">
                            <?php 
                            echo date("jS \of F Y", $timestamp) . "<br>";
                            ?>
                        </h4>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>


        <div class="col-md-6 col-md-offset-3">
            <table style="text-align: center;" class="table table-bordered">
                <thead>
                    <tr>
                        <td class="col-md-3"><?php echo get_phrase(students); ?></td>
                        <td class="col-md-3"><?php echo get_phrase(number); ?></td>
                        <td class="col-md-3"><?php echo get_phrase(detail_list); ?></td>
                        <td class="col-md-3"><?php echo get_phrase(send_sms); ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    
                        <td><?php echo get_phrase(total); ?></td>
                        <td><?php echo count($students); ?></td>
                        <td>
                            <a href="<?php echo base_url(); ?>index.php?admin/manage_attendance_view/<?php echo $group_id; ?>/<?php echo $class_id; ?>/<?php echo $section_id; ?>/<?php echo $timestamp; ?>" 
                           class="btn btn-primary" target="_blank">
                            <?php echo get_phrase('show'); ?>
                            </a>
                        </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><?php echo get_phrase(present); ?></td>
                        <td><?php echo $present; ?></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td><?php echo get_phrase(absent); ?></td>
                        <td><?php echo $absent; ?></td>
                        <td></td>
                        <!-- <td><a href="javascript:;"><button class="btn btn-danger"><?php echo get_phrase(send_sms) ?></button></a> 
                        </td>-->

                        <td>
                        <?php 
                            $absent_message = "Hello";
                         ?>

                            <a href="<?php echo base_url(); ?>index.php?admin/absent_student_notification/send_new" 
                           class="btn btn-danger" target="_blank">
                            <?php echo get_phrase('send_sms'); ?>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
            <center>
                <a href="<?php echo base_url(); ?>index.php?admin/attendance_report_print_view/<?php echo $class_id; ?>/<?php echo $section_id; ?>/<?php echo $timestamp; ?>" 
                   class="btn btn-primary" target="_blank">
                    <?php echo get_phrase('print_attendance_sheet'); ?>
                </a>
            </center>
        </div>
    </div>
<?php endif; ?>



<script type="text/javascript">

    // ajax form plugin calls at each modal loading,
    $(document).ready(function() {

        // SelectBoxIt Dropdown replacement
        if($.isFunction($.fn.selectBoxIt))
        {
            $("select.selectboxit").each(function(i, el)
            {
                var $this = $(el),
                    opts = {
                        showFirstOption: attrDefault($this, 'first-option', true),
                        'native': attrDefault($this, 'native', false),
                        defaultText: attrDefault($this, 'text', ''),
                    };

                $this.addClass('visible');
                $this.selectBoxIt(opts);
            });
        }
    }); 

</script>

<script type="text/javascript">

    function select_section(class_id){
        group_id = $("#group_id").val();
        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_section_with_cls_group/' + class_id + '/' + group_id,
            success:function(response){
                jQuery("#section_holder").html(response);
            }
        });

        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_course_with_group_class/' + class_id + '/' + group_id,
            success: function(response){
                jQuery("#course_holder").html(response);
            }
        });
    }

</script>