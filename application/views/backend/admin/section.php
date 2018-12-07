<hr />
<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/section_add/<?php echo $class_id ?>');" 
	class="btn btn-primary pull-right">
    	<i class="entypo-plus-circled"></i>
			<?php echo get_phrase('add_new_section');?>
</a> 
<br><br><br>

<div class="row">
	<div class="col-md-12">
	
		<div class="tabs-vertical-env">
		
			<ul class="nav tabs-vertical">
				<?php
				$campus = $this->db->get('campus')->result_array();
				foreach ($campus as $row):
				?>
				<li class="<?php if ($row['id'] == $campus_id) echo 'active';?>">
					<?php echo $row['campus_name'];?>					
					<ul>
						<?php 
						$classes = $this->db->get_where('class', array('campus_id' => $row['id']))->result_array();
						foreach ($classes as $row2):
						?>
						<li class="<?php if ($row2['class_id'] == $class_id) echo 'active';?>">
				
							<a href="<?php echo base_url();?>index.php?admin/section/<?php echo $row2['class_id'];?>">				
								
								<?php echo $groupClass[0][value];?> <?php echo $row2['name'];?>
							</a>
								
						</li>
						<?php endforeach;?>
					</ul>
				</li>
				<?php endforeach; ?>
			</ul>
			
			<div class="tab-content">

				<div class="tab-pane active">
					<table class="table table-bordered responsive">
						 <caption style="text-align: center; color: #000;"><?php echo $this->db->get_where('class', array('class_id' => $class_id))->row()->name;?></caption>
						<thead>
							<tr>
								<th>#</th>
								<th><?php echo get_phrase('section_name');?></th>
								<th><?php echo get_phrase('nick_name');?></th>
								<th><?php echo $groupClass[1][value];?></th>
								<th><?php echo get_phrase('options');?></th>
							</tr>
						</thead>
						<tbody>

						<?php
							$count    = 1;
							foreach ($sections as $row):
						?>
							<tr>
								<td><?php echo $count++;?></td>
								<td><?php echo $row['name'];?></td>
								<td><?php echo $row['nick_name'];?></td>
								<td>
									<?php 
									$group = $this->db->get_where('class_group', array('id' => $row['group_id']))->row()->group_name;
									echo $group;
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