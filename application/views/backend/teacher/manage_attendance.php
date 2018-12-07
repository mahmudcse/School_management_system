<hr />

<?php 

echo form_open(base_url() . 'index.php?teacher/attendance_selector/');?>
<div class="row">


	<div class="col-md-3">
		<div class="form-group">
			<!-- <input type="hidden" name="campus" value="<?php //echo $campus_id; ?>" > -->
			<label class="control-label"><?php echo $groupClass[1][value]; ?></label>
			
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

	<div class="col-md-2">
		<div class="form-group">
		<label class="control-label" style="margin-bottom: 5px;"><?php echo $groupClass[0][value];?></label>
			<select name="class_id" class="form-control selectboxit" onchange="select_section(this.value)">
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

	
    <!-- <div id="section_holder"> -->
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

    </div>
	
    
	<input type="hidden" name="year" value="<?php echo $running_year;?>">

	<div class="col-md-3" style="margin-top: 20px;">
		<button type="submit" class="btn btn-info"><?php echo get_phrase('manage_attendance');?></button>
	</div>

</div>
<?php echo form_close();?>

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