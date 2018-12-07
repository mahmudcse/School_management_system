<hr />

<?php echo form_open(base_url() . 'index.php?teacher/groupwise_attendance_selector/'); ?>
<div class="row">

    <div class="container">
        <div class="col-md-offset-3 form-inline validate">
            <div class="form-group">
                  <label for="startdate">From:</label>
                  <input type="text" class="form-control  datepicker" data-format="dd-mm-yyyy" value="<?php echo date('d-m-Y', $start_date); ?>" name="start_date" placeholder="">
            </div>
            <div class="form-group">
                  <label for="enddate">To:</label>
                  <input type="text" class="form-control  datepicker" data-format="dd-mm-yyyy" name="end_date" value="<?php echo date('d-m-Y', $end_date); ?>" placeholder="">
            </div>
      </div>
    </div>

    <br/>

    <div class="col-md-3 col-md-offset-2">
        <div class="form-group">
        <label class="control-label" style="margin-bottom: 5px;"><?php echo $groupClass[0][value];?></label>
            <select name="class_id" id="class_id" class="form-control selectboxit">
                <option value=""><?php echo get_phrase('select');?></option>
                <?php
                    foreach($classes as $row):                         
                ?>
                                
                <option value="<?php echo $row['class_id'];?>"
                    ><?php echo $row['name'];?></option>
                                
                <?php endforeach;?>
            </select>
        </div>
    </div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <!-- <input type="hidden" name="campus" value="<?php //echo $campus_id; ?>" > -->
            <label class="control-label"><?php echo $groupClass[1][value];?></label>
            
          <select name="group_id" id="group_id" class="form-control selectboxit" onchange="select_courses(this.value)">
          <option value="">Select</option>
            <?php
                $groupInfo = $this->db->get('class_group')->result_array();
                foreach ($groupInfo as $group):
            ?>

            <option value="<?php echo $group['id'];?>"><?php echo $group['group_name'];?></option>
            <?php endforeach;?>
          </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
        <label class="control-label"><?php echo get_phrase('course')?></label>
            <select name="course_id" id="course_holder" class="form-control" >
                
                <?php
                    $courses = $this->db->get_where('course', array('group_id' => $group_id))->result_array();

                foreach ($courses as $row):
                ?>

                    <option value="<?php echo $row['course_id']; ?>" 
                            <?php if ($course_id == $row['course_id']) echo 'selected'; ?>>
                            <?php echo $row['tittle']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div> 

    <div class="col-md-3" style="margin-top: 20px;">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('show_report');?></button>
    </div>


</div>
<?php echo form_close(); ?>






<hr />
<div class="row" style="text-align: center;">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <div class="tile-stats tile-gray">
            <div class="icon"><i class="entypo-chart-area"></i></div>
            <?php $group_name = $this->db->get_where('class_group', array('id' => $group_id))->row()->group_name ?>
            <h3 style="color: #696969;"><?php echo get_phrase('attendance_for')."<br/>"; ?> </h3>
            <h4 style="color: #696969;">
            <?php 
            echo $group_name."<br/><br/>";
            ?>
            <?php echo date("d M Y", $start_date).' '. ' - '. date("d M Y", $end_date); ?>
            </h4>
        </div>
    </div>
    <div class="col-sm-4"></div>
</div>
<!-- Print from here -->
<div id="coursewise_attendance_report">
    
<div class="row">

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <?php echo form_open(base_url() . 'index.php?admin/attendance_update/' . $group_id . '/' . $class_id . '/' . $section_id. '/' . $course_id .'/' . $timestamp); ?>

        <div id="attendance_update">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo get_phrase('student_id'); ?></th>
                        <th><?php echo get_phrase('name'); ?></th>
                        <th><?php echo get_phrase('total'); ?></th>
                        <th><?php echo get_phrase('present'); ?></th>
                        <th><?php echo get_phrase('absent'); ?></th>
                        <th><?php echo get_phrase('percentage'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $count = 0;

                    foreach ($students as $student) { ?>
                        <tr>
                            <td><?php echo ++$count; ?></td>
                            <td><?php echo $student['student_id']; ?></td>
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['total']; ?></td>
                            <td><?php echo $student['present']; ?></td>
                            <td><?php echo $student['absent']; ?></td>
                            <td><?php echo $student['percentage']; ?></td>
                        </tr>
                    <?php
                    }
                 ?>
                    
                </tbody>
            </table>
        </div>
</div>
        <center>
            <!-- <button type="submit" class="btn btn-success" id="submit_button">
                <i class="entypo-check"></i> <?php echo get_phrase('save_changes'); ?>
            </button> -->
            <div class="col-sm-offset-3 col-sm-5">
            <input class="btn btn-primary"type="button" name="submit" value="Print" onclick="print_report()">
            </div>
        </center>
        <?php echo form_close(); ?>

    </div>



</div>


<script type="text/javascript">
    function select_courses(group_id) {
        var class_id = $('#class_id').val();
        //alert(class_id);

        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_course_with_group_class/' + class_id+'/' +group_id,
            success:function (response)
            {

                jQuery('#course_holder').html(response);
            }
        });
    }

    function print_report() {    
            var printContents = document.getElementById('coursewise_attendance_report').innerHTML;
            var originalContents = document.body.innerHTML;
             document.body.innerHTML = printContents;
             window.print();
             document.body.innerHTML = originalContents;
        }
</script>