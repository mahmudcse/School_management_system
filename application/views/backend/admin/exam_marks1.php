<div class="box">
	<div class="box-header">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo get_phrase('manage_marks');?>
                    	</a></li>
						<li>
     
						
		</ul>
    	<!------CONTROL TABS END------->
	</div>
	<div class="box-content padded">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane  <?php if(!isset($edit_data) && !isset($personal_profile) && !isset($academic_result) )echo 'active';?>" id="list">
				 <?php echo form_open(base_url() . 'index.php?'.$account_type.'/exam_marks');?>
				<table>
					<tr>
						<td>
							<?php echo get_phrase('select_term');?> <br/>
							<?php echo form_dropdown('termId', $terms, $termId);?>
						</td>
						<td>
							<?php echo get_phrase('select_class');?> <br/>
							<?php echo form_dropdown('classId', $allclasses, $classId, "onchange=\"this.form.submit();\"");?>
						</td>
						<td>
							<?php echo get_phrase('select_subject');?> <br/>
							<?php echo form_dropdown('courseId', $courses, $courseId, "onchange=\"this.form.submit();\"");?>
						</td>
						<td>
							<input type="hidden" name="operation" value="selection" />
							<input type="hidden" name="examtype_id" value="-1" />
                    		<input type="submit" value="<?php echo get_phrase('manage_marks');?>" class="btn btn-normal btn-gray" />
                    	</td>
                    </tr>
				</table>
				
               
            	<table>
            		<tr>
            			<th>Student</th>
            			<?php foreach($examtypes as $examtype): ?>
	            		<th><?php echo $examtype['name'];?></th>
	            		<?php endforeach;?>
            		</tr>
            		<?php foreach($students as $student): ?>
	            	<tr>
	            		<td><?php echo $student['name'];?></td>
	            		<?php foreach($examtypes as $examtype): ?>
	            		<td><input class="datacell" id="<?php echo $examtype['examtype_id'].'/'.$student['student_id'];?>" style="width: 80px;" value="<?php echo $marks[$termId][$courseId][$examtype['examtype_id']][$student['student_id']];?>"/></td>
	            		<?php endforeach;?>
	            	</tr>
	            	<?php endforeach;?>
	            	<tr>
            			<th>&nbsp;</th>
            			<?php foreach($examtypes as $examtype): 
            			?>
            			<th>
            			<?php 
            				if($examtype['type']=="composite"){
            			?>
	            		 <input type = "button" value="Process" onclick="this.form.examtype_id.value=<?php echo $examtype['examtype_id'];?>;this.form.operation.value='PROCESS';this.form.submit();"/> <br/>
	            		<?php }?>
	            		<input type = "button" value="Publish" onclick="this.form.examtype_id.value=<?php echo $examtype['examtype_id'];?>;this.form.operation.value='PUBLISH';this.form.submit();"/></th>
	            		<?php endforeach;?>
            		</tr>
	            	
	            </table>
      			<?php echo form_close();?>
			</div>
            <!----TABLE LISTING ENDS--->
            
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

