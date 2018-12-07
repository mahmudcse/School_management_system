<hr />

<?php echo form_open(base_url() . 'index.php?admin/attendance_report_selector/'); ?>
<div class="row">

    <div class="col-md-3">
        <div class="form-group">
            <!-- <input type="hidden" name="campus" value="<?php //echo $campus_id; ?>" > -->
            <label class="control-label"><?php echo get_phrase('group')?></label>
            
          <select name="group_id" id="group_id" class="form-control selectboxit">
            <?php
                $groupInfo = $this->db->get('class_group')->result_array();
                foreach ($groupInfo as $group):
            ?>
            <option value="<?php echo $group['id'];?>"><?php echo $group['group_name'];?></option>
            <?php endforeach;?>
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
                    <option value="<?php echo $row['class_id']; ?>" ><?php echo $row['name']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    <?php endif; ?>

    <div class="col-md-2">
        <div class="form-group">
        <label class="control-label"><?php echo get_phrase('section')?></label>
            <select name="section_id" id="section_holder" class="form-control" >
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group">
        <label class="control-label"><?php echo get_phrase('course')?></label>
            <select name="course_id" id="course_holder" class="form-control" >
            </select>
        </div>
    </div>

    <div class="col-md-2">
        <div class="form-group">
            <label class="control-label" style="margin-bottom: 5px;"><?php echo get_phrase('date');?>   </label>
            <input type="text" class="form-control datepicker" name="timestamp" data-format="dd-mm-yyyy"
                value="<?php echo date("d-m-Y");?>"/>
        </div>
    </div>

    <input type="hidden" name="operation" value="selection">
    <input type="hidden" name="year" value="<?php echo $running_year;?>">

	<div class="col-md-3" style="margin-top: 20px;">
		<button type="submit" class="btn btn-info"><?php echo get_phrase('show_report');?></button>
	</div>
</div>

<?php echo form_close(); ?>


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