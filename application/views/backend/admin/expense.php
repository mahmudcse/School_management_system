
<div class="row">
	<div class="col-md-12">

		<!-- CONTROL TABS START -->
		<ul class="nav nav-tabs bordered">
		<li class="active">
		<a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
		<?php echo get_phrase('expense_list');?>
		                    	</a></li>
				<li>
	            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
						<?php echo get_phrase('add_expense');?>
	             	</a>
	            </li>
		</ul>
    	<!--CONTROL TABS END-->
        
		<div class="tab-content">
        <br>
            <!--TABLE LISTING STARTS-->
            <div class="tab-pane active" id="list">
            <div class="container">
					<div class="col-md-offset-3">
				  <?php echo form_open(base_url() . 'index.php?admin/expense/', array('class' => 'form-inline validate'));?>
				    <div class="form-group">
				      <label for="startdate">From:</label>
				      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" value="<?php echo date('d-m-Y',strtotime($start_date)); ?>" name="start_date" placeholder="">
				    </div>
				    <div class="form-group">
				      <label for="enddate">To:</label>
				      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" name="end_date" value="<?php echo date('d-m-Y',strtotime($end_date)); ?>" placeholder="">
				    </div>
				    <input type="submit" class="btn btn-info" value="Show">
				
				  <?php echo form_close() ?>
				  </div>
				</div>
				<br>
                <table class="table table-bordered datatable" id="table_export">
					 <thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('date');?></div></th>
                    		<th><div><?php echo get_phrase('Reference ID');?></div></th>
                    		<th><div><?php echo get_phrase('paritculars');?></div></th>
                    		<th><div align="center"><?php echo get_phrase('Total Amount');?></div></th>
						</tr>
					</thead>
                    <tbody>

                   	<?php 
                   	$count=1; 
                   	$totalAmt = 0.0;
                   	foreach ($expenseinfo as $row):
                   	$totalAmt = $totalAmt + $row->amount;
                   	?>
                   		<tr>
                   			<td><?php echo $count++; ?></td>
                   			<td><?php echo date('d-m-Y', strtotime($row->tdate))?></td>
                   			<td><?php echo $row->uniqueCode?></td>
                   			<td><?php echo $row->description?></td>
                   			<td align="center"><?php echo $row->amount?></td>
                   		</tr>
                   	<?php endforeach;?>
                   	<tr>
                   		<td><?php echo $count++; ?></td>
                   		<td></td>
                   		<td></td>
                   		<td align="right">Grand Total</td>
                   		<td align="center"><?php echo $totalAmt; ?></td>
                   	</tr>
                </table>
                
			</div>
            <!-- Table listing ends -->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                <?php echo form_open(base_url() . 'index.php?admin/expense/create/' , array('class' => 'form-inline form-groups validate', 'enctype' => 'multipart/form-data', 'style' => 'text-align:center;'));?>
				<div class="row">
					<div class="col-md-3">
					<div class="form-group">
							<label><?php echo get_phrase('particulars');?></label>
							<input type="text" class=" form-control" name="particulars" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
							</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							
							<select name="account" class="form-control" required>
				                                <option value=""><?php echo get_phrase('select_account');?></option>
				                                <?php 
				                                	$categories = $this->db->get_where('account', array('category1' => 'EXPENSE'))->result_array();
				                                	foreach ($categories as $row):
				                                ?>
				                                <option value="<?php echo $row['componentId'];?>"><?php echo $row['uniqueCode'];?></option>
				                            <?php endforeach;?>
				         </select>
						</div>
					</div>
					<div class="col-md-3">
							<div class="form-group">
							<label><?php echo get_phrase('select_date');?></label>
							<input type="text" class=" form-control datepicker" name="timestamp" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" placeholder=""/>
							</div>
					</div>	
					<div class="col-md-3"></div>
				</div>
				<br><br>
				
				<div id="bulk_add_form">
				<div id="student_entry">
					<div class="row" style="margin-bottom:10px;">
				
						<div class="form-group">
							<select id="item_selector_holder" name="item[]" class="form-control" style="width: 110px; margin-left: 5px;">
								<option value=""><?php echo get_phrase('select_item');?></option>
								<?php $categories = $this->db->get('item')->result_array();
				    				foreach ($categories as $row):
				    			?>		
				    				<option value="<?php echo $row['componentId'];?>"><?php echo $row['itemName'];?></option>
				                <?php endforeach;?>
							</select>
						</div>
						<div class="form-group">
							<input type="text" name="amount[]" id="amount" class="form-control" style="width: 160px; margin-left: 5px;"
								placeholder="<?php echo get_phrase('amount');?>" required>
						</div>
						<div class="form-group">
							<button type="button" class="btn btn-default " title="<?php echo get_phrase('remove');?>"
									onclick="deleteParentElement(this)" style="margin-left: 10px;">
				        		<i class="entypo-trash" style="color: #696969;"></i>
				        	</button>
						</div>
				
							
					</div>
				
				</div>
				
				
				<div id="student_entry_append"></div>
				<br>
				
				<div class="row">
				
						<button type="button" class="btn btn-default" onclick="append_student_entry()">
							<i class="entypo-plus"></i> <?php echo get_phrase('add_a_row');?>
						</button>
				
				</div>
				
				<br><br>
				
				<div class="row">
				
						<button type="submit" class="btn btn-success" id="submit_button">
							<i class="entypo-check"></i> <?php echo get_phrase('add_expense');?>
						</button>
				
				</div>
				</div>
				
				<?php echo form_close();?>
                </div>                
			</div>

		</div>
	</div>
</div>

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
							datatable.fnSetColumnVis(4, true);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(4, true);
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

<script type="text/javascript">
function printDiv() {    
    var printContents = document.getElementById('print').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }
	var blank_student_entry ='';
	$(document).ready(function() {
		//$('#bulk_add_form').hide(); 
		blank_student_entry = $('#student_entry').html();

		for ($i = 1; $i<1;$i++) {
			$("#student_entry").append(blank_student_entry);
		}
		
	});

	function append_student_entry()
	{
		$("#student_entry_append").append(blank_student_entry);
	}

	// REMOVING INVOICE ENTRY
	function deleteParentElement(n)
	{
		n.parentNode.parentNode.parentNode.removeChild(n.parentNode.parentNode);
	}

	function get_expense_item() {
		
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_expense_item/',
            success: function(response)
            {				
                jQuery('#item_selector_holder').html(response);
            }
        });

	}
</script>