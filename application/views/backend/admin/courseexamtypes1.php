<div class="box">
	<div class="box-header">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo get_phrase('manage');?>
                    	</a></li>
						<li>
     
						
		</ul>
    	<!------CONTROL TABS END------->
        
	</div>
	<div class="box-content padded">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane  <?php if(!isset($edit_data) && !isset($personal_profile) && !isset($academic_result) )echo 'active';?>" id="list">
	
                <?php echo form_open(base_url() . 'index.php?admin/courseexamtypes');?>
                <?php echo form_hidden("operation", "");?>
                <div> <?php echo get_phrase('select_term');?> </div>
                <div> <?php echo form_dropdown('term_id', $terms, $term_id);?> </div>
                <br/>
                <div> <?php echo get_phrase('select_class');?> </div>
                <div> <?php echo form_dropdown('class_id', $allclasses, $class_id, "onchange=\"this.form.submit();\"");?> </div>
                <br/>
                <div> <?php echo get_phrase('select_course');?> </div>
                <div> <?php echo form_dropdown('course_id', $courses, $course_id, "onchange=\"this.form.submit();\"");?> </div>
                <br/>
                <table style="border-spacing: 10px; border-collapse: separate;">
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
                 			<select name="order_index_<?php echo $type['examtype_id'];?>">
                 				<?php for($i=0;$i<count($examtypes);$i++){?>
                 				<option <?php if($i==$examcourse[$type['examtype_id']]['order_index']){ echo "selected=\"selected\"";}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
                 				<?php }?>
                 			</select>
                 		</td>
                 		
                 	</tr>
               
                <?php endforeach; ?>
                  </table>
                 <br/>
                 <input type="button" value=" Update " onclick="this.form.operation.value='update'; this.form.submit();"/>
                <?php echo form_close();?>
   
			</div>

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