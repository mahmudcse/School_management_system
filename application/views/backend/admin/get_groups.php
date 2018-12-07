<?php
$class = intval($_GET['value']);
$result = $this->db->get_where('class_group', array('class_id' => $class))->result_array();
foreach ($result as $row){
	echo $row['group_name'];
}
