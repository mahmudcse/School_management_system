
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/expense_add/');" 
class="btn btn-primary pull-right">
<i class="entypo-plus-circled"></i>
<?php echo get_phrase('');?>
</a> 
<br><br>

	<?php echo form_open(base_url() . 'index.php?admin/expense/create/' , array('class' => 'form-inline form-groups validate', 'enctype' => 'multipart/form-data'));?>
	
					<div class="form-group">
                        <label ><?php echo get_phrase('account');?></label>
                            <select name="account" class="form-control" required>
                                <option value=""><?php echo get_phrase('select_account');?></option>
                                <?php 
                                	$categories = $this->db->get_where('account', array('category1' => 'EXPENSE'))->result_array();
                                	foreach ($categories as $row):
                                ?>
                                <option value="<?php echo $row['componentId'];?>"><?php echo $row['description'];?></option>
                            <?php endforeach;?>
                         	</select>
                    </div>
					
					<div class="form-group">
                        <label><?php echo get_phrase('item');?></label>

                            <select name="item" class="form-control" required>
                                <option value=""><?php echo get_phrase('select_item');?></option>
                                <?php 
                                	$categories = $this->db->get_where('item', array('category1' => 'STATIONARY'))->result_array();
                                	foreach ($categories as $row):
                                ?>
                                <option value="<?php echo $row['componentId'];?>"><?php echo $row['itemName'];?></option>
                            <?php endforeach;?>
                            </select>

                    </div>
					
					<div class="form-group">
						<label><?php echo get_phrase('amount');?></label>
                        <input type="text" class="form-control" name="amount" value="" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
					</div>

                    <div class="form-group">
                        <label ><?php echo get_phrase('date');?></label>
                            <input type="text" class="datepicker form-control" name="timestamp" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                    </div>
                    
					<button type="button" class="btn btn-primary" id="addMore" onclick="add_fields();"><?php echo get_phrase('+');?></button>
					<div class="form-group">
					<button type="submit" class="btn btn-info"><?php echo get_phrase('add_expense');?></button>
					</div>

                <?php echo form_close();?>




<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">
$(document).ready(function() {
    var max_fields      = 10; //maximum input boxes allowed
    var wrapper         = $(".input_fields_wrap"); //Fields wrapper
    var add_button      = $(".add_field_button"); //Add button ID
   
    var x = 1; //initlal text box count
    $(add_button).click(function(e){ //on add input button click
        e.preventDefault();
        if(x < max_fields){ //max input box allowed
            x++; //text box increment
            $(wrapper).append('<div><input type="text" name="mytext[]"/><a href="#" class="remove_field">Remove</a></div>'); //add input box
        }
    });
   
    $(wrapper).on("click",".remove_field", function(e){ //user click on remove text
        e.preventDefault(); $(this).parent('div').remove(); x--;
    })
});
$(function() {
	  $("#addMore").click(function(e) {
	    e.preventDefault();
	    $("#fieldList").append("<input type='text, name='name[]' class='form-control'>");

	  });
	});
// 	jQuery(document).ready(function($)
// 	{
		

// 		var datatable = $("#table_export").dataTable({
// 			"sPaginationType": "bootstrap",
// 			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
// 			"oTableTools": {
// 				"aButtons": [
					
// 					{
// 						"sExtends": "xls",
// 						"mColumns": [1,2,3,4,5]
// 					},
// 					{
// 						"sExtends": "pdf",
// 						"mColumns": [1,2,3,4,5]
// 					},
// 					{
// 						"sExtends": "print",
// 						"fnSetText"	   : "Press 'esc' to return",
// 						"fnClick": function (nButton, oConfig) {
// 							datatable.fnSetColumnVis(0, false);
// 							datatable.fnSetColumnVis(6, false);
							
// 							this.fnPrint( true, oConfig );
							
// 							window.print();
							
// 							$(window).keyup(function(e) {
// 								  if (e.which == 27) {
// 									  datatable.fnSetColumnVis(0, true);
// 									  datatable.fnSetColumnVis(6, true);
// 								  }
// 							});
// 						},
						
// 					},
// 				]
// 			},
			
// 		});
		
// 		$(".dataTables_wrapper select").select2({
// 			minimumResultsForSearch: -1
// 		});
// 	});
		
</script>

