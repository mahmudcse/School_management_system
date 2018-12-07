<div class="box">
	<div class="box-header">
    	<!-- CONTROL TABS START -->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo get_phrase('exam_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo get_phrase('add_examtype');?>
                    	</a></li>
		</ul>
    	<!-- CONTROL TABS END -->
        
	</div>
	<div class="box-content padded">
		<div class="tab-content">
            <!-- TABLE LISTING STARTS -->
            <div class="tab-pane box <?php if(!isset($edit_data))echo 'active';?>" id="list">
				
                <table cellpadding="0" cellspacing="0" border="0" class="table table-advance dTable">
                	<thead>
					
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('display_name');?></div></th>
							<th><div><?php echo get_phrase('type');?></div></th>
                    		<th><div><?php echo get_phrase('total_mark');?></div></th>
                    		<th><div><?php echo get_phrase('rule');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($examtypes as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['name'];?></td>
							<td><?php echo $row['displayname'];?></td>
							<td><?php echo $row['type'];?></td>
							<td><?php echo $row['total_mark'];?></td>
							<td><?php echo $row['rule'];?></td>
							<!--<td><?php echo $this->crud_model->get_type_name_by_id('teacher',$row['teacher_id']);?></td>-->
							
							
						<td align="center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_examtype/<?php echo $row['examtype_id'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/examtypes/delete/<?php echo $row['examtype_id'];?>');">
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
                	<?php echo form_open(base_url() . 'index.php?admin/examtypes/create' , array('class' => 'form-horizontal validatable','target'=>'_top'));?>
                    <div class="padded">
                           
                      <div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        <div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>
							
							
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('display_name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="displayname"/>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('type');?></label>
                                <div class="col-sm-5">
                                    <select name="type" class="form-control">
                                    	<option value="single"><?php echo get_phrase('single');?></option>
                                    	<option value="composite"><?php echo get_phrase('composite');?></option>
                                    </select>
                                </div>
                            </div>
							
							 <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('total_mark');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="total_mark"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('rule');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="rule"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('passing_check');?></label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="passing_check">
                                        <option value="0">No</option>
                                        <option value="1">Yes</option>
                                    </select>
                                </div>
                            </div>
							
                           <!-- <div class="control-group">
                                <label class="control-label"><?php //echo get_phrase('total_mark');?></label>
                                <div class="controls">
                                    <select name="teacher_id" class="uniform">
                                    	<?php 
										//$teachers = $this->db->get('teacher')->result_array();
										//foreach($teachers as $row):
										?>
                                    		<option value="<?php //echo $row['teacher_id'];?>"><?php //echo $row['name'];?></option>
                                        <?php
										//endforeach;
										?>
                                    </select>
                                </div>
                            </div>-->
                        </div>
                       
						 <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_Exam_Type');?></button>
                              </div>
						</div>
                    </form>                
                </div>                
			</div>
			<!-- CREATION FORM ENDS -->
            
		</div>
	</div>
</div>