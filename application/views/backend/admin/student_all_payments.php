<hr />
<?php 

 if($student_id) {
	$student_info = $this->db->get_where('student', array('student_id' => $student_id) )->row_array();
	$transaction_info = $this->db->get_where('transaction_detail', array('transactionId' => $transaction_id))->result_array();
}
?>

<div class="row">
	<div class="col-md-12">
	<?php echo form_open(base_url() . 'index.php?admin/fee_invoice/' , array('class' => 'form-vertical form-groups-bordered validate'));?>
		<div class="col-md-4">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('search_by_student_code');?></label>
				<input type="text" class="form-control" name="student_code" onblur="this.form.submit();">
			
				</div>
			</div>
			<div>   </div>
		<!-- <div class="col-md-4">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('search_by_receipt_number');?></label>
				<input type="text" class="form-control" name="receipt_number" onblur="this.form.submit();">
			
				</div>  
		</div>-->
	<?php echo form_close()?>	
	</div>
	<div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_records');?></span>
                </a>
            </li>

        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            
							<th width="80"><div><?php echo get_phrase('#');?></div></th>
							<th><div><?php echo get_phrase('date');?></div></th>
                            <th><div><?php echo get_phrase('student_code');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                 		<?php $count=1; foreach($transaction_info as $row): 
                 		?>
                        <tr>
                            <td><?php echo $count++;?></td>
                            <td>
								<?php echo date('d-m-Y', strtotime($row['tdate']))?>
                            </td>
							<td><?php echo $row['student_code'];?></td>
							<td><?php echo $row['name']?></td>
							
                            
                            <td>
                                <!-- <a href="javascript:;" onclick="showAjaxModal('<?php //echo base_url();?>index.php?modal/popup/modal_fee_invoice/<?php //echo $row['student_id'];?>/<?php //echo $row['fee_record_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php //echo get_phrase('print_invoice');?>
                                
								  </a> -->
								  <a target="_blank" href="<?php echo base_url();?>index.php?modal/popup/modal_fee_invoice/<?php echo $row['student_id'];?>/<?php echo $row['fee_record_id'];?>">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('print_invoice');?>
                                
								  </a>
                                
                            </td>
                        </tr>
<?php endforeach;?>
                    </tbody>
                </table>
                    
            </div>
        
                    
            </div>
   

        </div>
      </div>  
        
 



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
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "pdf",
						"mColumns": [0, 2, 3, 4]
					},
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(1, false);
							datatable.fnSetColumnVis(5, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(1, true);
									  datatable.fnSetColumnVis(5, true);
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