<hr />

<?php 
echo form_open(base_url() . 'index.php?teacher/attendance_report_selector/'); ?>
<div class="row">
    
<!--     <div class="col-md-3">
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
    </div> -->


<!--     <?php
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
    <?php endif; ?> -->

<!--     <div class="col-md-2">
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
     </div> -->

<!-- <div class="col-md-3 col-md-offset-2">
          <div class="form-group">
              <label class="control-label"><?php echo get_phrase(courses); ?></label>
              <select name="course_id" id="course_holder" class="form-control">
                 <?php 
                     $courses = $this->db->get('course')->result_array();
 
                     foreach ($courses as $course) {?>
                         <option value="<?php echo $course['course_id']; ?>" <?php if($course['course_id'] == $course_id) echo "selected"; ?>>
                             <?php echo $course['tittle']; ?>
                         </option>
                     <?php 
                     }
                  ?>
                  
              </select>
          </div>
      </div> -->

     <div class="col-md-2 col-md-offset-4">
         <div class="form-group">
             <label class="control-label"><?php echo get_phrase(date); ?></label>
             <input type="text" name="timestamp" class="form-control datepicker" data-format="dd-mm-yyyy" value="<?php echo date("d-m-Y", $select_time); ?>">
         </div>
     </div>

    <input type="hidden" name="year" value="<?php echo $running_year; ?>">

    <div class="col-md-3" style="margin-top: 20px;">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('show_report'); ?></button>
    </div>

</div>
<div id="attendance_report">
    
<br/>
    <div class="col-md-3 col-md-offset-5">
        <h4 style="color: #696969;">
            <?php echo date("d M Y", $select_time); ?>
        </h4>
    </div>
<br/>
            
<hr/>
        <div class="row">
            <div class="container">
                <div class="col-md-10">
                    <table class="table table-bordered" style="text-align: center;">
                    <thead>
                        <tr>
                            <td><?php echo $groupClass[1][value]; ?></td>
                            <td><?php echo $groupClass[0][value]; ?></td>
                            <td>Section</td>
                            <td>Total</td>
                            <td>Present</td>
                            <td>Absent</td>
                            <td>Absent notification</td>
                            <td>Total List</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            foreach ($attendance as $row) { 
                                $group_id = $row['group_id'];
                                $class_id = $row['class_id'];
                                $section_id = $row['section_id'];
                                $course_id  = $row['course_id'];
                                if($course_id == ''){
                                    $course_id = $this->db->get_where('course', array('group_id' => $group_id, 'class_id' => $class_id))->row()->course_id;
                                }
                                $total = $row['total'];
                                $timestamp = $row['timestamp'];
                                    ?>
                            <tr>
                                <td><?php echo $row['group_name']; ?></td>
                                <td><?php echo $row['class_name']; ?></td>
                                <td><?php echo $row['section_name']; ?></td>
                                <td><?php echo  $row['total']; ?></td>

                                <?php 
                                $timestamp = strtotime($timestamp);
                                if($select_time == $timestamp){
                                    if($row['present'] == 0 && $row['absent'] == 0){ ?>
                                    <td colspan="3" style="color: #007D3D;">Attendace is not recorded yet
                                    </td>

                                    <?php
                                    }else{?>
                                        
                                        <td><?php echo $row['present']; ?></td>
                                        <td><?php echo $row['absent']; ?></td>
                                        <td>

                                        <?php
                                            $time = date('Y-m-d', $select_time);
                                            $query = "SELECT st.name, e.roll, p.phone FROM enroll e
                                            JOIN student st ON (st.student_id = e.student_id and e.class_id = $class_id AND e.section_id = $section_id)
                                            JOIN section sec ON (e.section_id = sec.section_id and sec.group_id = $group_id)
                                            JOIN attendance a ON (a.student_id = e.student_id and a.course_id = $course_id AND a.timestamp = '$time' AND a.`status` = 0)
                                            JOIN parent p ON (st.parent_id = p.parent_id)";
                                            $details = $this->db->query($query)->result_array();
                                            $reciever = array_column($details, 'phone');
                                            
                                            $notificationQuery = "select * from notification where noticedate = '$time'";
                                            $notificationRcvd = $this->db->query($notificationQuery)->result_array();
                                            $recieved = array_column($notificationRcvd, 'recipient');

                                            $notificationNotRcvd = array_diff($reciever, $recieved);
                                            if(count($notificationNotRcvd) == 0 && count($reciever) != ''){?>
                                                
                                                <span class="btn btn-primary">Notification sent</span>

                                            <?php 
                                            }elseif($row['absent'] != 0){ ?>
                                                    <a href="<?php echo base_url(); ?>index.php?teacher/attendance_report/<?php echo $select_time; ?>/<?php echo $group_id; ?>/<?php echo $class_id; ?>/<?php echo $section_id; ?>/<?php echo $course_id; ?>/absent_sms"
                                                   class="btn btn-success">
                                                    Send
                                                    </a>
                                                <?php
                                                }

                                        ?>
                                            
                                        </td>
                                    <?php 
                                    }
                                }else{ ?>
                                    <td colspan="3" style="color: #007D3D;">Attendace is not recorded yet
                                    </td>
                                <?php
                                }
                                ?>
                                
                                <td>
                                    <a href="<?php echo base_url(); ?>index.php?teacher/manage_attendance_view/<?php echo $group_id; ?>/<?php echo $class_id; ?>/<?php echo $section_id; ?>/<?php echo $course_id; ?>/<?php echo $select_time; ?>" 
                                   class="btn btn-primary" target="_blank">
                                    <?php echo get_phrase('show'); ?>
                                    </a>
                                </td>
                            </tr>   



                        <?php 
                            }
                         ?>
                        
                        
                    </tbody>
                </table>
                </div>
            </div>
        </div>
</div>

<center>
    <div class="col-sm-offset-3 col-sm-5">
    <input class="btn btn-primary"type="button" name="submit" value="Print" onclick="print_report()">
    </div>
</center>


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

    function print_report() {    
            var printContents = document.getElementById('attendance_report').innerHTML;
            var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
        }

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