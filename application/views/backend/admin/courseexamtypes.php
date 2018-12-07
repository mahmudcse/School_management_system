<hr />
<div class="row">
	<div class="col-md-12">
            	<?php echo form_open(base_url() . 'index.php?admin/courseexamtypes');?>
                 <?php echo form_hidden('operation', 'show');?>
                 		<div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('select_term');?></label>
                                 <?php echo form_dropdown('term_id', $terms, $term_id, "class=\"form-control selectboxit\"");?>
                            </div>
						        </div>
                  <div class="col-md-3">
                    <div class="form-group">
                      <label class="control-label"><?php echo $groupClass[1][value];?></label>
                      <?php echo form_dropdown('group_id', $groups, $group_id,"class=\"form-control selectboxit\"");?>
                    </div>
                  </div>

						<div class="col-md-3">
							 <div class="form-group">
                                <label class="control-label"><?php echo $groupClass[0][value];?></label>
                                 <?php echo form_dropdown('class_id', $allclasses, $class_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
                </div>
            </div>

						<div class="col-md-3">
				<div class="form-group">
				<label class="control-label"><?php echo get_phrase('section');?></label>
				<?php echo form_dropdown('section_id', $allsections, $section_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
				</div>
			</div> 
				 <div class="col-md-3">
							  <div class="form-group">
                                <label class="control-label"><?php echo get_phrase('course');?></label>
                                   <?php echo form_dropdown('course_id', $courses, $course_id, "onchange=\"this.form.submit();\" class=\"form-control selectboxit\"");?>
                </div>
            </div>
							
				
			<table class="table table-bordered datatable" id="table_export">
				<tbody>
                	<tr>
                		<th>Exam type</th>
                		<th>On Report</th>
                		<th>Order</th>
                		
                	</tr>
				<?php 
                	foreach ($examtypes as $type):
                ?>
                
                	<tr>
                		<td>
                 			<input type="checkbox"  <?php if(in_array($type['examtype_id'], $selectedtypes)){echo "checked=\"checked\"";} ?> name="examtypes[]" value="<?php echo $type['examtype_id'];?>" /> <?php echo $type['name'];?>
                 		</td>
                 		<td>
                 			<input type="checkbox" <?php if(1==$examcourse[$type['examtype_id']]['report_card']){ echo "checked=\"checked\"";}?> name="report_card_<?php echo $type['examtype_id'];?>" value="1">
                 		</td>
                 		<td>
                 			<select name="order_index_<?php echo $type['examtype_id'];?>" class="form-control selectboxit">
                 				<?php for($i=0 ; $i<count($examtypes) ; $i++){?>
                 				<option <?php if($i==$examcourse[$type['examtype_id']]['order_index']){ echo "selected=\"selected\"";}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
                 				<?php }?>
                 			</select>
                 		</td>
                 		
                 	</tr>
               
                <?php endforeach; ?>
				</tbody>
                  </table>
				<input type="button" value=" Update " onclick="this.form.operation.value='update'; this.form.submit();"/>
               	<?php echo form_close();?>
				
			</div>
        </div>
	
<script type="text/javascript">
  function show_examtypes(course_id)
  {
      for(i=0;i<=100;i++)
      {

          try
          {
              document.getElementById('examtype_id_'+i).style.display = 'none' ;
	  		  document.getElementById('examtype_id_'+i).setAttribute("name" , "temp");
          }
          catch(err){}
      }
      document.getElementById('examtype_id_'+course_id).style.display = 'block' ;
	  document.getElementById('examtype_id_'+course_id).setAttribute("name" , "examtype_id");
  }

</script> 