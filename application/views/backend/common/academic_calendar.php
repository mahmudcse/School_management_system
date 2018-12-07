<hr />
<div class="row">
	<div class="col-md-12">
		<div class="tab-content">
			<br>
			<!----TABLE LISTING STARTS-->
			<div class="tab-content">
        <br>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
				
                <table class="table table-bordered datatable" id="table_export">
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
                    	<?php $count = 1;foreach($events as $row):?>
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
		</div>

</div>
</div>
</div>