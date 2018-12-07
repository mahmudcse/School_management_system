<hr />
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('course_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_course');?>
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
                    		<th><div><?php echo get_phrase('course_name');?></div></th>
                    		<th><div><?php echo $groupClass[0][value];?></div></th>
                    		<th><div><?php echo $groupClass[1][value];?></div></th>
                    		<th><div><?php echo get_phrase('optioal');?></div></th>
                    		<th><div><?php echo get_phrase('action');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($courses as $row):?>
                        <tr>
                        	<td><?php echo $count++; ?></td>
							<td><?php echo $row['tittle']; ?></td>
							<td><?php 
									$class = $this->db->get_where('class', array('class_id' => $row['class_id']))->row()->name;
							        echo $class;
							        ?>
							</td>
							<td><?php 
									$group = $this->db->get_where('class_group', array('id' => $row['group_id']))->row()->group_name;
									echo $group;
								?>
							</td>
							<td>
								<?php 
								if($row['is_optional'] == 1) {
									echo 'Yes';
								}
								else {
									echo 'No';
								}
								?>
								
							</td>
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_course/<?php echo $row['course_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                        </a>
                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/course/delete/<?php echo $row['course_id'];?>');">
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
            <!--TABLE LISTING ENDS->
            
            
			<!-CREATION FORM STARTS-->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/course/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('course_code');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="course_code" data-validate="required" data-message-required="<?php echo get_phrase('code_required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('name_required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('credit') ?></label>
                                <div class="col-sm-5">
                                    <input type="text" name="credit" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('credit_required'); ?>">
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('campus');?></label>
                                <div class="col-sm-5">
                                    <select name="campus" class="form-control selectboxit" style="width:100%;" onchange="get_campus_class(this.value)" id="campus">
                                    	<option value=""><?php echo get_phrase('select_campus');?></option>
                                    	<?php 
										
										
										foreach($campus as $row):
										?>
                                    		<option value="<?php echo $row['id'];?>" <?php if($row['id'] == $default_campus) echo "selected"; ?>>
                                                    <?php echo $row['campus_name'];?>
                                            </option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo $groupClass[0][value];?></label>
		                    	<div class="col-sm-5">
		                        <select name="class" class="form-control" id="class_selector_holder">
		                            <option value=""><?php echo get_phrase('select');?></option>
			                        
			                    </select>
			                	</div>
							</div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo $groupClass[1][value];?></label>
                                <div class="col-sm-5">
                                    <select name="group" class="form-control selectboxit" style="width:100%;">
                         
                                    	<?php 
										$teachers = $this->db->get('class_group')->result_array();
										foreach($teachers as $row):
										?>
                                    		<option value="<?php echo $row['id'];?>" <?php if($row['group_name'] == 'Common') echo 'selected';?>><?php echo $row['group_name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label"><?php //echo get_phrase('combined');?></label>
                                <div class="col-sm-5">
                                    <select name="combined" class="form-control" id="combined">
                                        <option value="0"><?php //echo get_phrase('no');?></option>
                                        <option value="1"><?php //echo get_phrase('yes');?></option>
                                    </select>
                                </div>
                            </div> -->
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('combined_with');?></label>
                                <div class="col-sm-5">
                                    <select name="combined_with" class="form-control" id="combined_with">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('is_optional');?></label>
                                <div class="col-sm-5">
                                   	<select name="is_optional" class="form-control">
                                   	<option value="0"><?php echo get_phrase('no');?></option>
                                   	<option value="1"><?php echo get_phrase('yes');?></option>
                                   	</select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_course');?></button>
                              </div>
						   </div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
            
		</div>
	</div>
</div>


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">
$(window).load(function(){
    var campus_id = $('#campus').val();
    $.ajax({
        url: '<?php echo base_url();?>index.php?admin/get_campus_class/' + campus_id ,
        success: function(response)
        {               
            jQuery('#class_selector_holder').html(response);
        }
    });

});
    



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

    $(document).on('change', '#class_selector_holder', function(){
        //var cstatus = $('#combined').val();
        var class_id = $('#class_selector_holder').val();
            $.ajax({
                url: '<?php echo base_url();?>index.php?admin/get_combined_curses/' + class_id ,
                success: function(response){
                    $('#combined_with').html(response);
                }
            });
    });
		
</script>