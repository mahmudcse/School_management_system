<hr />
<div class="row">
<div class="col-md-12">

<!------CONTROL TABS START------>
<ul class="nav nav-tabs bordered">
<li class="active">
<a href="#list" data-toggle="tab"><i class="entypo-menu"></i>
<?php echo get_phrase('event_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_event');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
        
		<div class="tab-content">
        <br>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
				
                <table class="table" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('Event');?></div></th>
                    		<th><div><?php echo get_phrase('start');?></div></th>
                    		<th><div><?php echo get_phrase('end');?></div></th>
                    		<th><div><?php echo get_phrase('actions');?></div></th>
                    		
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($eventinfo as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['event'];?></td>
							<td><?php echo $row['start_date'];?></td>
							<td><?php echo $row['end_date'];?></td>
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_ac_calendar/<?php echo $row['ac_calendar_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/academic_calendar/delete/<?php echo $row['ac_calendar_id'];?>');">
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
                	<?php echo form_open(base_url() . 'index.php?admin/academic_calendar/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('event');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="event" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('event_type');?></label>
                        
								<div class="col-sm-5">
							    <select name="event_type" class="form-control selectboxit">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php foreach($event_types as $row): ?>
                                		<option value="<?php echo $row['value'];?>">
												<?php echo $row['value'];?>
                                        </option>
                                <?php endforeach; ?>
                                </select>
						   		</div> 
							</div>
							<div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('recurring');?></label>
                        
								<div class="col-sm-5">
							    <select name="recurring" class="form-control selectboxit">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php 
									
									foreach($recurrings as $row):
								?>
                                		<option value="<?php echo $row['value'];?>">
												<?php echo $row['value'];?>
                                                </option>
                                    <?php
									endforeach;
								?>
                                </select>
						   		</div> 
							</div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('start_date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" name="start_date" class="form-control datepicker"/>
                                </div>
                            </div>
                            
							<div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('end_date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" name="end_date" class="form-control datepicker"/>
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class_off');?></label>
                        
								<div class="col-sm-5">
							    <select name="class_off" class="form-control selectboxit">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                </select>
						   		</div> 
							</div>
							<div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('school_off');?></label>
                        
								<div class="col-sm-5">
							    <select name="school_off" class="form-control selectboxit">
                                <option value="">Select</option>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                </select>
						   		</div> 
							</div>
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_event');?></button>
                              </div>
							</div>
                    <?php echo form_close()?>               
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
		</div>
	</div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>