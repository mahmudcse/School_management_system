<div class="mail-header" style="padding-bottom: 27px ;">
    <!-- title -->
    <h3 class="mail-title">
        <?php echo get_phrase('write_new_message'); ?>
    </h3>
</div>

<div class="mail-compose">

    <?php echo form_open(base_url() . 'index.php?'.$account_type.'/message/send_new/', array('class' => 'form', 'enctype' => 'multipart/form-data')); ?>
		<?php 
		$this->db->select("concat('CAM',campus.id) as id,campus.campus_name as title, 'true' as hasChildren", FALSE);
		$this->db->from('campus');
		$campus_info = $this->db->get()->result_array();
		
		$hierarchy = array();
		$hierarchy['tree'] = array();
		$hierarchy['tree']['id']='root';
		$hierarchy['tree']['nodes']=$campus_info;
		

		$json_data = json_encode($hierarchy, JSON_PRETTY_PRINT);
// 		echo '<pre>';
// 		print_r($campus_info);
// 		echo '</pre>';
		?>

	<div id="treeContainer">
	</div>

 <!--  <div class="form-group">
        <label for="subject"><?php echo get_phrase('recipient'); ?>:</label>
        <br><br>
        <select class="form-control select2" name="reciever" required>

            <option value=""><?php echo get_phrase('select_a_user'); ?></option>
            <optgroup label="<?php echo get_phrase('student'); ?>">
                <?php
                $students = $this->db->get('student')->result_array();
                foreach ($students as $row):
                    ?>

                    <option value="student-<?php echo $row['student_id']; ?>">
                        - <?php echo $row['name']; ?></option>

                <?php endforeach; ?>
            </optgroup>
            <optgroup label="<?php echo get_phrase('teacher'); ?>">
                <?php
                $teachers = $this->db->get('teacher')->result_array();
                foreach ($teachers as $row):
                    ?>

                    <option value="teacher-<?php echo $row['teacher_id']; ?>">
                        - <?php echo $row['name']; ?></option>

                <?php endforeach; ?>
            </optgroup>
            <optgroup label="<?php echo get_phrase('parent'); ?>">
                <?php
                $parents = $this->db->get('parent')->result_array();
                foreach ($parents as $row):
                    ?>

                    <option value="parent-<?php echo $row['parent_id']; ?>">
                        - <?php echo $row['name']; ?></option>

                <?php endforeach; ?>
            </optgroup>
        </select>
    </div> -->


    <div class="compose-message-editor">
        <textarea row ="2" class="form-control wysihtml5" data-stylesheet-url="assets/css/wysihtml5-color.css" 
            name="message" placeholder="<?php echo get_phrase('write_your_message'); ?>" 
            id="sample_wysiwyg"></textarea>
    </div>

    <hr>

    <button type="submit" class="btn btn-success btn-icon pull-right">
        <?php echo get_phrase('send'); ?>
        <i class="entypo-mail"></i>

    </button>
<?php echo form_close(); ?>

</div>

<script type="text/javascript">

var jsonSource = <?php echo $json_data; ?>;

//initialisation
var tree = Vtree.create({
	container: jQuery("#treeContainer"),
	dataSource: jsonSource,
	ajaxUrl: "<?php echo base_url();?>index.php?admin/get_class/",
	plugins: ["ajax_loading", "checkbox"],
	checkBehaviour: "checkParents",
	checkedClass: "checked",
	checkedClass: "checked",
	displayCheckbox: true

});

</script>
