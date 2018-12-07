<?php
$edit_data		=	$this->db->get_where('course' , array('course_id' => $param2) )->result_array();

foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_course');?>
            	</div>
            </div>
			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/course/do_update/'.$row['course_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                    <div class="col-sm-5 controls">
                        <input type="text" class="form-control" name="name" value="<?php echo $row['tittle'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                    <div class="col-sm-5 controls">
                        <select name="class" class="form-control" id="class_selector_holder">
                            <?php 
                            $classes = $this->db->get('class')->result_array();
                            foreach($classes as $row2):
                            ?>
                                <option value="<?php echo $row2['class_id'];?>"
                                    <?php if($row['class_id'] == $row2['class_id'])echo 'selected';?>>
                                        <?php echo $row2['name'];?>
                                            </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('group');?></label>
                    <div class="col-sm-5 controls">
                        <select name="group" class="form-control">
                            <option value=""></option>
                            <?php 
                            $group = $this->db->get('class_group')->result_array();
                            foreach($group as $row2):
                            ?>
                                <option value="<?php echo $row2['id'];?>"
                                    <?php if($row['group_id'] == $row2['id'])echo 'selected';?>>
                                        <?php echo $row2['group_name'];?>
                                            </option>
                            <?php
                            endforeach;
                            ?>
                        </select>
                    </div>
                </div>

<?php
    if($row['combined'] == 1){
        $combined_group_id = $this->db->get_where('course_group', array('course_id' => $param2))->row()->group_id;

        $combined_with_info = "SELECT
                                        c.course_id,
                                        c.tittle
                                        FROM
                                        course_group cg
                                        INNER JOIN course c ON c.course_id = cg.course_id
                                        WHERE cg.group_id = $combined_group_id AND cg.course_id != $param2";
        $combined_with_info = $this->db->query($combined_with_info)->row_array();

        if(count($combined_with_info) == 0){
            $combined_with_course_id   = $param2;
            $combined_with_course_name = $row['tittle'];
        }else{
            $combined_with_course_id   = $combined_with_info['course_id'];
            $combined_with_course_name = $combined_with_info['tittle'];
        }
    }

    $class_id = $row['class_id'];
    $group_id = $row['group_id'];

    $all_combined_courses = "SELECT
                                c.course_id,
                                c.tittle,
                                cg.group_id course_group_id
                                FROM
                                course c
                                INNER JOIN course_group cg ON c.course_id = cg.course_id
                                WHERE c.class_id = $class_id AND c.group_id = $group_id";
    $all_combined_courses = $this->db->query($all_combined_courses)->result_array();
?>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('combined_with');?></label>
                    <div class="col-sm-5">
                        <select name="combined_with" class="form-control" id="combined_with">
                            <option value="0">No</option>
                            <option value="-1">Create New</option>

                            <?php foreach ($all_combined_courses as $key => $ccourse): ?>
                                <option value="<?php echo $ccourse['course_id'] ?>"
                                    <?php if($ccourse['course_id'] == $combined_with_course_id) echo "selected"; ?>
                                >
                                    <?php echo $ccourse['tittle']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('is_optional');?></label>
                    <div class="col-sm-5 controls">
                        <select name="is_optional" class="form-control">
                            <?php 
                            	if($row['is_optional'] == 1) {
                            ?>
                            <option value="<?php echo $row['is_optional'];?>">Yes</option>
                            <option value="0">No</option>
                            <?php } else { ?>
                            <option value="<?php echo $row['is_optional'];?>">No</option>
                            <option value="1">Yes</option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                        <button type="submit" class="btn btn-info"><?php echo get_phrase('update');?></button>
                    </div>
                 </div>
        		<?php echo form_close()?>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>

<script>
    $(document).on('change', '#class_selector_holder', function(){
        //var cstatus = $('#combined').val();
        var class_id = $('#class_selector_holder').val();
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_combined_curses/' + class_id ,
                success: function(response){
                    $('#combined_with').html(response);
                }
            });
    });
</script>



