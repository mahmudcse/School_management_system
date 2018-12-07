
            
               <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            
                            <th><div><?php echo get_phrase('StudentId');?></div></th>
							 <th><div><?php echo get_phrase('Class');?></div></th>
							 <th><div><?php echo get_phrase('Section');?></div></th>
                            <th><div><?php echo get_phrase('Name');?></div></th>
							 <th><div><?php echo get_phrase('Father');?></div></th>
							  <th><div><?php echo get_phrase('Phone');?></div></th>
                            <th><div><?php echo get_phrase('Due Amount');?></div></th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$totaldueAmount = 0.0;$student_id="";$name="";$fathername="";$phone="";$classId="";$className="";$dueAmount=0.0;
		
						foreach ( $feeDuesData as $value ) {
	               		 $student_id = $value->student_id;
						 
						 $classId = $this->db->get_where('enroll', array('student_id' => $student_id))->row()->class_id;
						 $className = $this->crud_model->get_class_name($classId);
						 
						 $section_id = $this->db->get_where('enroll', array('student_id' => $student_id))->row()->section_id;
						 $sectionName = $this->crud_model->get_section_name($section_id);
						 
						 $name = $value->name;
						 $fathername = $value->fathername;
						 $phone = $value->phone;
						 $dueAmount = $value->dueAmount;
						$totaldueAmount += $dueAmount;
						?>
                        <tr>
                            <td><?php echo $student_id;?></td>
							<td><?php echo $className;?></td>
							<td><?php echo $sectionName;?></td>
                            <td><?php echo $name;?></td>
							<td><?php echo $fathername;?></td>
							<td><?php echo $phone;?></td>
                            <td><?php echo $dueAmount;?></td>
                          </tr>
                     	<?php }?>
						
                    </tbody>
                </table>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "xls",
						"mColumns": [1,2]
					},
					{
						"sExtends": "pdf",
						"mColumns": [1,2]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(0, false);
							datatable.fnSetColumnVis(3, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(3, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>

