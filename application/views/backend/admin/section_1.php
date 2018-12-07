<hr />
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/section_add/');" 
	class="btn btn-primary pull-right">
    	<i class="entypo-plus-circled"></i>
			<?php echo get_phrase('add_new_section');?>
</a>
<?php echo form_open(base_url() . 'index.php?/admin/section/');?>
<div class="form-group">
	<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
	<div class="col-sm-5">
		<select name="class_id" id="class" onchange="this.form.submit();" class="form-control selectboxit" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
             <option value=""><?php echo get_phrase('select');?></option>
                   <?php 
					foreach($classes as $row): ?>
                    <option value="<?php echo $row['class_id'];?>" 
                    <?php if ($row['class_id'] == $class_id) echo 'selected';?>>
						<?php echo $row['name'];?>
                    </option>
                    <?php endforeach; ?>
         </select>
	</div> 
</div> 
<?php echo form_close() ?>		
<br><br><br>

<div class="row">
	<div class="col-md-12">
	
		<div class="tabs-vertical-env">
			
			<div class="tab-content">

				<div class="tab-pane active">
					<table class="table table-bordered responsive">
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo get_phrase('section_name');?></th>
								<th><?php echo get_phrase('group_name');?></th>
								<th><?php echo get_phrase('teacher');?></th>
								<th><?php echo get_phrase('options');?></th>
							</tr>
						</thead>
						<tbody>

						<?php
							$count    = 1;

// 							$sections = $this->db->get()->result_arrry();
							
// 							print_r($sections);
// 							exit();
							
							

							foreach ($sections as $row):
						?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo $row['name'];?></td>
								<td><?php echo $row['group_name'];?></td>
								<td>
									<?php if ($row['teacher_id'] != '' || $row['teacher_id'] != 0)
											echo $this->db->get_where('teacher' , array('teacher_id' => $row['teacher_id']))->row()->name;
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
		                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/section_edit/<?php echo $row['section_id'];?>');">
		                                            <i class="entypo-pencil"></i>
		                                                <?php echo get_phrase('edit');?>
		                                            </a>
		                                                    </li>
		                                    <li class="divider"></li>
		                                    
		                                    <!-- DELETION LINK -->
		                                    <li>
		                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/sections/delete/<?php echo $row['section_id'];?>');">
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

			</div>
			
		</div>	
	
	</div>
</div>