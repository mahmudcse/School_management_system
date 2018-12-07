
<hr />

<?php echo form_open(base_url() . 'index.php?teacher/attendance_selector/'); ?>
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label"><?php echo $groupClass[1][value];?></label>
            
          <select name="group_id" id="group_id" class="form-control selectboxit">
            <?php
                $groupInfo = $this->db->get('class_group')->result_array();
                foreach ($groupInfo as $group):
            ?>
            <option value="<?php echo $group['id'];?>" <?php if($group_id == $group['id']) echo 'selected'; ?>><?php echo $group['group_name'];?></option>
            <?php endforeach;?>
          </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" style="margin-bottom: 5px;"><?php echo $groupClass[0][value]; ?></label>
            <select name="class_id" class="form-control selectboxit" onchange="select_section(this.value)">
                <option value=""><?php echo get_phrase('select_class'); ?></option>
                <?php
                $classes = $this->db->get('class')->result_array();
                foreach ($classes as $row):
                    ?>

                    <option value="<?php echo $row['class_id']; ?>"
                            <?php if ($class_id == $row['class_id']) echo 'selected'; ?>><?php echo $row['name']; ?></option>
                        <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
        <label class="control-label"><?php echo get_phrase('section')?></label>
            <select name="section_id" id="section_holder" class="form-control" >
            <?php
        $sections = $this->db->get_where('section', array(
                    'class_id' => $class_id, 'group_id' => $group_id
                ))->result_array();
        foreach ($sections as $row):
            ?>

                    <option value="<?php echo $row['section_id']; ?>" 
                            <?php if ($section_id == $row['section_id']) echo 'selected'; ?>>
                            <?php echo $row['name']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
        <label class="control-label"><?php echo get_phrase('course')?></label>
            <select name="course_id" id="course_holder" class="form-control" >
                <?php
                    $courses = $this->db->get_where('course', array(
                        'class_id' => $class_id, 'group_id' => $group_id
                                ))->result_array();
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

    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" style="margin-bottom: 5px;"><?php echo get_phrase('date'); ?></label>
            <input type="text" class="form-control datepicker" name="timestamp" data-format="dd-mm-yyyy"
                   value="<?php echo date("d-m-Y", $timestamp); ?>"/>
        </div>
    </div>

    <input type="hidden" name="year" value="<?php echo $running_year; ?>">

    <div class="col-md-3" style="margin-top: 20px;">
        <button type="submit" class="btn btn-info"><?php echo get_phrase('manage_attendance'); ?></button>
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
            echo $group_name."<br/>";
            echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name."&nbsp:&nbsp";
            echo get_phrase('section')."&nbsp";
            echo $this->db->get_where('section', array('section_id' => $section_id))->row()->name; 
            echo "<br>";
            echo $this->db->get_where('course', array('course_id' => $course_id))->row()->tittle; 
            ?> 
            

            </h4>
            <h4 style="color: #696969;">
                <?php echo date("d M Y", $timestamp); ?>
            </h4>
        </div>
    </div>
    <div class="col-sm-4"></div>
</div>

<div class="row">

    <div class="col-md-2"></div>

    <div class="col-md-8">

        <?php echo form_open(base_url() . 'index.php?teacher/attendance_update/' . $group_id . '/' . $class_id . '/' . $section_id. '/' . $course_id .'/' . $timestamp); ?>

        <div id="attendance_update">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?php echo get_phrase('roll'); ?></th>
                        <th><?php echo get_phrase('name'); ?></th>
                        <th><?php echo get_phrase('status'); ?></th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $count = 0;

                    foreach ($students as $student) { ?>
                        <tr>
                            <td><?php echo ++$count; ?></td>
                            <td><?php echo $student['roll']; ?></td>
                            <td><?php echo $student['student_name']; ?></td>
                            <input type="hidden" name="student_id[]" value="<?php echo $student['student_id']; ?>">
                            <td>
                                    <input type="checkbox" name="status_<?php echo $student['student_id']; ?>" value="1" <?php if($student['status'] == 1) echo checked; ?>>
                            </td>
                        </tr>
                    <?php
                    }
                 ?>
                    
                </tbody>
            </table>
        </div>

        <center>
            <button type="submit" class="btn btn-success" id="submit_button">
                <i class="entypo-check"></i> <?php echo get_phrase('save_changes'); ?>
            </button>
        </center>
        <?php echo form_close(); ?>

    </div>



</div>


<script type="text/javascript">
    function select_section(class_id) {
        group_id = $('#group_id').val();
        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_section_with_cls_group/' + class_id + '/' + group_id,
            success:function (response)
            {

                jQuery('#section_holder').html(response);
            }
        });

        $.ajax({
            url: '<?php echo base_url(); ?>index.php?admin/get_course_with_group_class/' + class_id + '/' + group_id,
            success:function (response)
            {

                jQuery('#course_holder').html(response);
            }
        });
    }
</script>