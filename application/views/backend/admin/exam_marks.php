<hr />
<div class="row">
	<div class="col-md-12">
		<?php 
		echo form_open(base_url() . 'index.php?'.$account_type.'/exam_marks');?>
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo get_phrase('term');?></label>
					<?php echo form_dropdown('termId', $terms, $termId,"class=\"form-control selectboxit\"");?>
				</div>
			</div>
			
			<div class="col-md-3">
				<div class="form-group">
					<label class="control-label"><?php echo $groupClass[1][value];?></label>
					<?php echo form_dropdown('groupId', $groups, $groupId,"class=\"form-control selectboxit\"");?>
				</div>
			</div>

			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo $groupClass[0][value];?></label>
				<?php echo form_dropdown('classId', $allclasses, $classId, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('section');?></label>
				<?php echo form_dropdown('sectionId', $allsections, $sectionId, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div>
			<div class="col-md-2">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('subject');?></label>
				<?php echo form_dropdown('courseId', $courses, $courseId, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				<input type="hidden" name="operation" value="selection" />
				<input type="hidden" name="examtype_id" value="-1" />
				</div>
			</div>
			
		
			
			
			<table class="table table-bordered datatable" id="">
			      <thead>
				   <tr>
            			<th>Roll</th>
            			<th>Name</th>
            			<?php foreach($examtypes as $examtype): ?>
	            		<th><?php echo $examtype['name'];?></th>
	            		<?php endforeach;?>
            		</tr>
            	</thead>
				<tbody>	
					<?php foreach($students as $student): ?>
	            	<tr>
	            		<td><?php echo $student['roll'];?></td>
	            		<td><?php echo $student['name'];?></td>
	            			<?php foreach($examtypes as $examtype): ?>
	            		<td>
	            			<input 
							<?php if($examtype['type'] == 'composite') echo "readonly"; ?>
	            			class="datacell" id="<?php echo $examtype['examtype_id'].'/'.$student['student_id'];?>" style="width: 80px;" value="<?php echo $marks[$termId][$courseId][$examtype['examtype_id']][$student['student_id']];?>"/>
	            		</td>
	            		<?php endforeach;?>
	            	</tr>
	            	<?php endforeach;?>
	            	<tr>
            			<th>&nbsp;</th>
            			<th>&nbsp;</th>
            			<?php foreach($examtypes as $examtype): 
            			?>
            			<th>
            			<?php 
            				if($examtype['type']=="composite"){
            			?>
	            		 <input type = "button" value="Process" onclick="this.form.examtype_id.value=<?php echo $examtype['examtype_id'];?>;this.form.operation.value='PROCESS';this.form.submit();"/><?php }?>
	            		<input type = "button" value="Publish" onclick="this.form.examtype_id.value=<?php echo $examtype['examtype_id'];?>;this.form.operation.value='PUBLISH';this.form.submit();"/>
						</th>
	            		
						<?php endforeach;?>
						
            		</tr>
					
	            	</tbody>
	            </table>			
						
		<?php echo form_close();?>
	</div>
</div>

<script type="text/javascript">
	termId = <?php echo $termId;?>;
	//groupId = <?php //echo $groupId;?>;
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

