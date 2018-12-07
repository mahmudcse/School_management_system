<?php

?>
<hr />
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('customer_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_customer');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
		<div class="tab-content">
        <br>            
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
				
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                			<td>S.l</td>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('city');?></div></th>
                    		<th><div><?php echo get_phrase('phone');?></div></th>
                    		<th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($customerInfo as $row):?>
                        <tr>
                        	<td><?php echo $count++; ?></td>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo $row['city'] ?></td>
							<td><?php echo $row['phone'] ?></td>
							
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_customer_edit/<?php echo $row['componentId'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/customer/delete/<?php echo $row['componentId'];?>');">
                                            <i class="entypo-trash"></i>
                                                <?php echo get_phrase('delete');?>
                                        </a>
                                   </li>
                                </ul>
                            </div>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/customer/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('address');?></label>
		                    	<div class="col-sm-5">
		                        <input type="text" class="form-control" name="address" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
			                	</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('city');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="city" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
                                <div class="col-sm-5">
                                   	<input type="text" class="form-control" name="phone" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
                                <div class="col-sm-5">
                                   	<input type="email" class="form-control" name="email" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_customer');?></button>
                              </div>
						   </div>
                    <?php echo form_close(); ?>                
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
            
		</div>
	</div>
</div>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

function get_campus_class(campus_id) {
	
	$.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_campus_class/' + campus_id ,
        success: function(response)
        {				
            jQuery('#class_selector_holder').html(response);
        }
    });

}
	
	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>