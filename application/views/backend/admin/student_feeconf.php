<?php 

?>
<hr />
<div class="row">
	<div class="col-md-12">
		<?php echo form_open(base_url() . 'index.php?'.$account_type.'/student_feeConfig');?>
			<!-- <div class="col-md-4">
				<div class="form-group">
					<label class="control-label"><?php echo get_phrase('select_campus');?></label>
					<?php echo form_dropdown('campus_id', $campuslist, $id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('select_session');?></label>
				<?php echo form_dropdown('session_id', $sessions, $session_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('select_class');?></label>
				<?php echo form_dropdown('class_id', $allclass, $class_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('select_group');?></label>
				<?php echo form_dropdown('group_id', $groups, $group_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
			
				</div>
			</div> -->
			<div class="col-md-12">
				<div class="col-md-offset-4 col-md-3">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('student_id');?></label>
				<input type="text" class="form-control" name="student_code" onblur="this.form.submit();">
				</div>
				</div>
			</div>
			<!-- <div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('select_section');?></label>
				<?php echo form_dropdown('section_id', $sections, $section_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				
				</div>
			</div> -->
			
			
			<table class="table table-bordered datatable" id="table_export">
			      <thead>
				   <tr>
            			<th>Student</th>
            			<th>Fee Name </th>
            			<th>Amount</th>
	            		<td>Actions</td>
            		</tr>
            	</thead>
				<tbody>	
					<?php foreach($feeInfo as $fee): ?>
	            	<tr>
	            		<td><?php echo $fee['name'];?></td>
	            		<td><?php echo $fee['studentFeeName'];?></td>
	            		<td><?php echo $fee['amount'];?></td>
	            		<td>
							<div class="btn-group">
		                        <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
		                                    Action <span class="caret"></span>
		                        </button>
		                        <ul class="dropdown-menu dropdown-default pull-right" role="menu">
		                                    
		                                    <!-- EDITING LINK -->
		                         <li>
		                         <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/studentfee_edit/<?php echo $fee['id'];?>');">
		                         <i class="entypo-pencil"></i>
		                                                <?php echo get_phrase('edit');?>
		                         </a>
		                         </li>
		                         <li class="divider"></li>
		                                    
		                         <!-- DELETION LINK -->
		                         <li>
		                         <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/sections/delete/<?php echo $fee['id'];?>');">
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
			
			
			
		<?php echo form_close();?>
	</div>
</div>

<script type="text/javascript">
	termId = <?php echo $termId;?>;
	classId = <?php echo $classId;?>;
	courseId = <?php echo $courseId;?>;
    $( ".datacell" ).change(function() {
	  id = $(this).attr('id');
	  val = $(this).val();
		
	  $(this).css("background-color", "#ffb3b3");

	  obj = $(this);
	  
	  url = "<?php echo base_url('index.php?/admin/markupdate');?>/"+termId+"/"+courseId+"/"+id+"/"+val;
	  
	  $.get( url).done(function( data ) {
	    if(data-1==0)
		  obj.css("background-color", "#ffffff");
	    else
	    	obj.css("background-color", "#ff0000");
	   
	  });
		
	});
</script> 

