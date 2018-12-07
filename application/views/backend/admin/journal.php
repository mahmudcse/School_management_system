<hr />
<div class="row">
	<div class="col-md-12">

    	<!--CONTROL TABS START-->
		<ul class="nav nav-tabs bordered" id="mytabs">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
					<?php echo get_phrase('transaction_list');?>
                </a>
            </li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_journal');?>
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
				  <?php echo form_open(base_url() . 'index.php?admin/journal/', array('class' => 'form-inline validate'));?>
				    <div class="form-group">
				      <label for="startdate">From:</label>
				      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" value="<?php echo date('d-m-Y', strtotime($start_date));  ?>" name="start_date" placeholder="">
				    </div>
				    <div class="form-group">
				      <label for="enddate">To:</label>
				      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" name="end_date" value="<?php echo date('d-m-Y', strtotime($end_date));  ?>" placeholder="">
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
                    		<th><div align="center"><?php echo get_phrase('option');?></div></th>


					</tr>
				 </thead>

				 <tbody>
				 <?php
                   	$count=1;
                   	$totalAmt = 0.0;

                   	foreach ($searchData as $row):
                   	$totalAmt = $totalAmt + $row->amount;


                   	?>
                   		<tr>

                   			<td><?php echo $count++; ?></td>
                   			<td><?php echo date('d-m-Y', strtotime($row->tdate))?></td>

                   			<td><?php echo $row->uniqueCode ;?></td>
                   			<td><?php echo $row->description?></td>
                   			<td align="center"><?php echo $row->amount?></td>
                   			<td align="center">

                                <div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">

                                         <!-- journal EDITING LINK -->
                                        <li>
                                        	<a href="javascript:;" id="editbutton" onclick="loadTransaction('<?php echo base_url();?>index.php?modal/transaction/<?php echo $row->componentId;?>')">
                                            	&nbsp;&nbsp;&nbsp;&nbsp;<i class="entypo-pencil"></i>
													<?php echo get_phrase('edit');?>
											</a>
                                        </li>

                                        <li class="divider"></li>

                                        <!-- journal DELETION LINK -->
                                        <li>
                                        	<a href="javascript:;" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/journal/delete/<?php echo $row->componentId;?>');">
                                            	&nbsp;&nbsp;&nbsp;&nbsp;<i class="entypo-trash"></i>
													<?php echo get_phrase('delete');?>
											</a>
                                        </li>
                                    </ul>
                                </div>

                            </td>



                   		</tr>
                   	<?php endforeach;?>
                   		<tr>
                   		<td><?php echo $count++; ?></td>
                   		<td></td>
                   		<td></td>
                   		<td align="right">Grand Total</td>
                   		<td align="center"><?php echo $totalAmt; ?></td>
                   		<td></td>
                   	</tr>
				 </tbody>
				</table>
            </div>




            <!--TABLE LISTING ENDS->
			<!CREATION FORM STARTS-->




			<div class="tab-pane box" id="add" style="padding: 5px">

			<form id="updateForm" method="post" action="<?php echo base_url() ?>index.php?admin/journal/create" class="form-inline form-groups validate" enctype="multipart/form-data" style="text-align:center;">

				<div class="row" style="margin-bottom: 10px">
				<input type="hidden" name="trComponentId">

					<div class="col-md-3"></div>
					<div class="col-md-3">
							<div class="form-group">
							<label><?php echo get_phrase('particulars');?></label>
							<input type="text" class=" form-control" name="particular" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
							</div>
					</div>
					<div class="col-md-3">
							<div class="form-group">
							<label><?php echo get_phrase('select_date');?></label>
							<input type="text" class=" form-control datepicker" name="timestamp" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
							</div>
					</div>
					<div class="col-md-3"></div>
				</div>
				<div class="row">
				<table style="background-color:#ccffcc;width:50%; float:left;" id="d">
				<thead>
					<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA; text-align: center;">Debits</th></tr>

					<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
				</thead>
				<tbody>
					<tr id="student_entry1">

						<input type="hidden" name="detailComponentIdd[]">
						<td>

							<select name="userd[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_user');?></option>
		                          <?php
		                             foreach ($users as $row):
		                             ?>
		                       <option value="<?php echo $row['user_id'];?>"><?php echo $row['user_name'];?></option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select name="accountd[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_account');?></option>
		                          <?php
		                             foreach ($accounts as $row):
		                             ?>
		                       <option value="<?php echo $row['componentId'];?>"><?php echo $row['description'];?></option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select id="item_selector_holder" name="itemd[]" class="form-control" style="width: 100px; margin-left: 0px;">
							<option value=""><?php echo get_phrase('select_item');?></option>
							<?php
			    				foreach ($items as $row):
			    			?>
			    				<option value="<?php echo $row['componentId'];?>"><?php echo $row['itemName'];?></option>
			                <?php endforeach;?>
							</select>
						</td>
						<td>
						<input type="text" id="unitPriced" name="unitPriced[]" class="unitPriced"style="width: 60px;" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
						</td>
						<td>
						<input type="text" id="quantityd" name="quantityd[]" style="width: 50px;" onchange="calcSum1();" class="quantityd"  data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
						</td>
						<td>
						<input type="text" name="amountd[]" style="width: 60px;" readonly>
						<input onclick="deleteParentElement(this);" type="button" style="width: 3px; text-align: left; font-weight: bold;font-size: large;" value="-"/>
						</td>
					</tr>
				</tbody>
					<tr><th> <input type="button"  onclick="append_debit_table_row();" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="total">0.00</th></tr>

				</table>

					<table style="background-color:#ffe6e6;width:50%;" id="c">
					<thead>
						<tr><th colspan="6" style="border-bottom:1px solid #AAAAAA; text-align: center;">Credits</th></tr>

						<tr><th>User</th><th>Account</th><th>Item</th><th>Unit Price</th> <th>Quantity</th><th>Amount</th></tr>
					</thead>
					<tbody>
						<tr id="student_entry2">
						<input type="hidden" name="detailComponentIdc[]">

						<td>
							<select name="userc[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_user');?></option>
		                          <?php
		                             foreach ($users as $row):
		                             ?>
		                       <option value="<?php echo $row['user_id'];?>"><?php echo $row['user_name'];?></option>
		                       <?php endforeach;?>
		        			</select>
						</td>
						<td>
							<select name="accountc[]" class="form-control" required style="width: 100px;">
		                       <option value=""><?php echo get_phrase('select_account');?></option>
		                          <?php
		                             foreach ($accounts as $row):
		                             ?>
		                       <option value="<?php echo $row['componentId'];?>"><?php echo $row['description'];?></option>
		                       <?php endforeach;?>
		        			</select>

						</td>
						<td>
							<select id="item_selector_holder" name="itemc[]" class="form-control" style="width: 100px; margin-left: 0px;">
							<option value=""><?php echo get_phrase('select_item');?></option>
							<?php
			    				foreach ($items as $row):
			    			?>
			    				<option value="<?php echo $row['componentId'];?>"><?php echo $row['itemName'];?></option>
			                <?php endforeach;?>
							</select>
						</td>
						<td>
						<input type="text" id="unitPricec"  style="width: 60px;" name="unitPricec[]" class="unitPricec" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
						</td>
						<td>
						<input type="text" id="quantityc" style="width: 50px;"  name="quantityc[]" class="quantityc" onchange="calcSum2()"; data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
						</td>
						<td>
							<input type="text" name="amountc[]" style="width: 60px;" readonly>
							<input onclick="deleteParentElement(this);" type="button" style="width: 3px; text-align: left; font-weight: bold;font-size: large;" value="-"/>

						</td>

					</tr>
				</tbody>
						<tr><th> <input onclick="append_credit_table_row();" type="button" style="padding: 3px 8px;font-weight: bold;font-size: large;" value=" + "/> </th><th colspan="3">Total</th><th id="ttlcr">0.00</th></tr>

				</table>
			</div>
<br>


<br><br>

				<div class="row">
					<div style="text-align: center;">
						<button type="submit" class="btn btn-success" id="submit_button">
							<i class="entypo-check"></i><?php echo get_phrase('save'); ?>
						</button>
					</div>
				</div>
</form>

			</div>
			<!-- CREATION FORM ENDS -->
		</div>
	</div>
</div>





<!---  DATA TABLE EXPORT CONFIGURATIONS -->
<script type="text/javascript">

$('document').ready(function(){
	$("#editbutton").click(function(){
		alert("Some code");
	});
});


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

	jQuery(document).ready(function($)
	{


		var datatable = $("#table_export").dataTable();

		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});

</script>
<script type="text/javascript">
	var blank_student_entry1 ='';
	$(document).ready(function() {
		//$('#bulk_add_form').hide();
		blank_student_entry1 = $('#student_entry1').html();
		// for ($i = 1; $i<1;$i++) {
		// 	$("#student_entry1").append(blank_student_entry1);
		// }

	});
	var blank_student_entry2 ='';
	$(document).ready(function() {
		//$('#bulk_add_form').hide();
		blank_student_entry2 = $('#student_entry2').html();
		// for ($i = 1; $i<1;$i++) {
		// 	$("#student_entry2").append(blank_student_entry2);
		// }

	});
	function append_debit_table_row()
	{
		$("#d tr:last").before('<tr>' +blank_student_entry1 +'</tr>');
	}
	function append_credit_table_row()
	{
		$("#c tr:last").before('<tr>' +blank_student_entry2 +'</tr>');
	}

	// REMOVING INVOICE ENTRY
	function deleteParentElement(item)
	{
		//n.parentNode.parentNode.removeChild(n.parentNode.parentNode);
		$("tr").has($(item)).remove();

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
	function calcSum1(){
		var sum = 0.0;
		var total = 0.0;
		$("#d tr").each(function(){
		       var $this = $(this);
		       var quantity = parseInt($this.find('.quantityd').val(),10);
		       var unitprice = parseInt($this.find('.unitPriced').val(),10);
		       $this.find('[name="amountd[]"]').val(quantity * unitprice );
		       sum = Number(quantity) * Number(unitprice);
		       total = total + sum;
		 });
		$('#total').val(sum);
	}
	function calcSum2(){
		var sum = 0.0;
		var total = 0.0;
		$("#c tr").each(function(){
		       var $this = $(this);
		       var quantity = parseInt($this.find('.quantityc').val(),10);
		       var unitprice = parseInt($this.find('.unitPricec').val(),10);
		       $this.find('[name="amountc[]"]').val(quantity * unitprice );
		       sum = Number(quantity) * Number(unitprice);
		       total = total + sum;
		 });
		$('#total').val(sum);
	}

	function loadTransaction(ajxurl){
		$('#mytabs a[href="#add"]').tab('show');
		$('#student_entry1').hide();
		$('#student_entry2').hide();
		$("#d").find("tr:gt(2):not(:last)").remove();
		$("#c").find("tr:gt(2):not(:last)").remove();



		$('#updateForm').attr('action','<?php echo base_url() ?>index.php?admin/journal/do_update');
		$('#submit_button').html('Update');

		$.ajax({
            url: ajxurl,
            dataType: 'json',
            async: false,
            success: function(data)
            {
            	$('input[name = trComponentId]').val(data.transaction[0].componentId);
				$('input[name = particular]').val(data.transaction[0].description);
				$('input[name = timestamp]').val(data.transaction[0].tdate);

				for(i = 0; i < data.details.length; i++){

					if(data.details[i].type == 1){
						append_debit_table_row();

						$("#d tr:last").prev().find('[name="detailComponentIdd[]"]').val(data.details[i].componentId);
						$("#d tr:last").prev().find('[name="userd[]"]').val(data.details[i].userId);
						$("#d tr:last").prev().find('[name="accountd[]"]').val(data.details[i].accountId);
						$("#d tr:last").prev().find('[name="itemd[]"]').val(data.details[i].itemId);

						$("#d tr:last").prev().find('[name="unitPriced[]"]').val(data.details[i].unitPrice);
						$("#d tr:last").prev().find('[name="quantityd[]"]').val(data.details[i].quantity);
						$("#d tr:last").prev().find('[name="amountd[]"]').val(data.details[i].unitPrice*data.details[i].quantity);



					} else if (data.details[i].type == -1){
						append_credit_table_row();

						$("#c tr:last").prev().find('[name="detailComponentIdc[]"]').val(data.details[i].componentId);
						$("#c tr:last").prev().find('[name="userc[]"]').val(data.details[i].userId);
						$("#c tr:last").prev().find('[name="accountc[]"]').val(data.details[i].accountId);
						$("#c tr:last").prev().find('[name="itemc[]"]').val(data.details[i].itemId);


						$("#c tr:last").prev().find('[name="unitPricec[]"]').val(data.details[i].unitPrice);
						$("#c tr:last").prev().find('[name="quantityc[]"]').val(data.details[i].quantity);
						$("#c tr:last").prev().find('[name="amountc[]"]').val(data.details[i].unitPrice*data.details[i].quantity);
					}

				}

            }
        });


	}

</script>
