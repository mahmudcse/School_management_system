
<div class="mail-compose">

    <?php echo form_open(base_url() . 'index.php?admin/notification/send_new/', array('class' => 'form', 'enctype' => 'multipart/form-data')); ?>
		<div>
		<input type="checkbox" id="sms" class="validate[required]" checked="checked" name="sms" value="SMS"/>
		<label style="display: inline;" for="sms">Mobile SMS</label> 
       	<!-- <input type="checkbox" id = "email" class="validate[required]" name="email" value="email"/>
		<label style="display: inline;"  for="email">Email</label> -->
		</div>
		<?php 
		$this->db->select("concat('CAM',campus.id) as id,campus.campus_name as title, 'true' as hasChildren", FALSE);
		$this->db->from('campus');
		$campus_info = $this->db->get()->result_array();

		
		$hierarchy = array();
		$hierarchy['tree'] = array();
		$hierarchy['tree']['id']='root';
		$hierarchy['tree']['nodes']=$campus_info;
		

		$json_data = json_encode($hierarchy, JSON_PRETTY_PRINT);
		// echo '<pre>';
		// print_r($json_data);
		// echo '</pre>';
		?>
	<br />
	<div id="treeContainer">
	</div>
	
	<br />
    <div class="compose-message-editor">
        <textarea row ="2" class="form-control wysihtml5" data-stylesheet-url="assets/css/wysihtml5-color.css" 
            name="message" placeholder="<?php echo get_phrase('write_your_message'); ?>" 
            id="sample_wysiwyg"></textarea>
    </div>
	<hr>
	
    <button type="submit" class="btn btn-success btn-icon pull-right entypo-mail">
        <?php echo get_phrase('send'); ?>

    </button>
<?php echo form_close(); ?>
<br />
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
	//checkedClass: "checked",
	displayCheckbox: true

});

</script>
