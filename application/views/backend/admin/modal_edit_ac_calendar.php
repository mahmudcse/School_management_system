<?php 
$edit_data = $this->db->get_where('academic_calendar', array('ac_calendar_id' => $param2))->row_array();

?>
<div class="">

<?php echo form_open(base_url() . 'index.php?admin/academic_calendar/update' , array('class' => 'form-horizontal form-groups-bordered validate','id' => 'editform','target'=>'_top', ));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('event');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="event" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="<?php echo $edit_data['event']?>"/>
                                	<input type="hidden" name="event_id" value="<?php echo $edit_data['ac_calendar_id']?>">
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('event_type');?></label>
                        
								<div class="col-sm-5">
							    <select name="event_type" class="form-control selectboxit">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php 
                                $event_types = $this->db->get_where('codes', array('key_name' => 'event.type'))->result_array();
                                foreach($event_types as $row): ?>
                                		<option value="<?php echo $row['value']; ?>" <?php if($edit_data['event_type'] == $row['value']) echo 'selected';?>>
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
                                $recurrings = $this->db->get_where('codes', array('key_name' => 'recurring.type'))->result_array();
									foreach($recurrings as $row):
								?>
                                		<option value="<?php echo $row['value']; ?>" <?php if($edit_data['recurring'] == $row['value']) echo 'selected';?>>
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
                                    <input type="text" name="start_date" value="<?php echo $edit_data['start_date']?>" class="form-control datepicker"/>
                                </div>
                            </div>
                            
							<div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('end_date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" name="end_date" value="<?php echo $edit_data['start_date'];?>" class="form-control datepicker"/>
                                </div>
                            </div>
                            <div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class_off');?></label>
                        
								<div class="col-sm-5">
							    <select name="class_off" class="form-control selectboxit">
							    <?php if($edit_data['class_off'] == 0) {?>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                <?php } else { ?>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                <?php } ?>
                                
                                </select>
						   		</div> 
							</div>
							<div class="form-group">
								<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('school_off');?></label>
                        
								<div class="col-sm-5">
							    <select name="school_off" class="form-control selectboxit">
                                <?php if($edit_data['school_off'] == 0) {?>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                                <?php } else { ?>
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                                <?php } ?>
                                </select>
						   		</div> 
							</div>
                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('update_event');?></button>
                              </div>
							</div>
                    <?php echo form_close()?>
</div>
<script type="text/javascript">
document.forms['editform'].elements['class_off'].value = <?php echo $edit_data['class_off']?>;
</script>