<hr />
<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('fee_list');?>
                    	</a></li>
	
		</ul>
    	<!------CONTROL TABS END------>
        
		<div class="tab-content">
        <br>
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
            
             <?php echo form_open(base_url() . 'index.php?admin/feeconf/filter' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
	             
	             <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('session');?></label>
                     
                    	<div class="col-sm-offset-3 col-sm-5">
                          <select name="session_id" class="form-control selectboxit" onchange="document.getElementById('session_id').value = this.options[this.selectedIndex].value;this.form.submit();">
                         		<option value="">Select session</option>
                          <?php foreach ($sessions as $ses):?>
                              <option <?php if($session_id == $ses['componentId']){ echo "selected=\"selected\"";}?> value="<?php echo $ses['componentId'];?>"><?php echo $ses['uniqueCode'];?> </option>
                          <?php endforeach;?>
                          </select>
                      </div>
                  </div>
                  
                  <div class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('session');?></label>
                     
                    	<div class="col-sm-offset-3 col-sm-5">
                    	<?php echo form_dropdown('category2', $cats, $category2,"class=\"form-control selectboxit\" onchange=\"this.form.submit();\"");?>
                      </div>
                  </div>
                  <?php 
                  	if($category2 == 'CLASS'){
                  ?>
                  <div id="class_select" class="form-group">
                      <label  class="col-sm-3 control-label"><?php echo get_phrase('select_class');?></label>
                     
                    	<div class="col-sm-offset-3 col-sm-5">
                          <select name="class_id" class="form-control selectboxit" onchange="this.form.submit();">
                          	<option value="-1">Select Class</option>
                          <?php foreach ($classes as $cls):?>
                              <option <?php if($class_id == $cls['class_id']){ echo "selected=\"selected\"";}?> value="<?php echo $cls['class_id'];?>"><?php echo $cls['name'];?> </option>
                          <?php endforeach;?>
                          </select>
                      </div>
                  </div>
                  <?php }?>
             	<br/><br/>
            <?php echo form_close()?>   
            
            
            <?php echo form_open(base_url() . 'index.php?admin/feeconf/do_update' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
     			<input type="hidden" id="session_id" name="session_id" value="<?php echo $session_id;?>"/>
     			<input type="hidden" name="category2" value="<?php echo $category2;?>"/>
     			<input type="hidden" name="fees_count" value="<?php echo count($fees);?>">
     			<input type="hidden" id="class_id" name="class_id" value="<?php echo $class_id;?>"/>
	             <table class="table table-bordered">
	                	<thead>
	                		<tr>
		                		<th><div><?php echo get_phrase('fee');?></div></th>
	                    		<th><div><?php echo get_phrase('amount');?></div></th>
                    		</tr>
	                    </thead>
	                     <tbody>
	                    	<?php
	                    	$cnt = 0;
	                    	foreach ($fees as $fee):
	                    	$cnt++;
	                    	$amt = 0;
	                    	if(isset($amts[$fee['componentId']])){
	                    		$amt = $amts[$fee['componentId']];
	                    	}
	                    	?>
	                        <tr>
	                            <td><input type="hidden" name="fee_<?php echo $cnt?>" value="<?php echo $fee['componentId'];?>"/> <?php echo $fee['itemName'];?></td>
								<td><input name="amount_<?php echo $cnt?>" type="text" value="<?php echo $amt;?>" /></td>
							</tr>
							<?php endforeach;?>
						</tbody>
	               </table>
	               
	               <div class="form-group">
                    	<div class="col-sm-offset-3 col-sm-5">
                      		<button type="submit" class="btn btn-info"> <?php echo get_phrase('Save');?> </button>
                   		</div>
					</div>
               <?php echo form_close()?>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			
		</div>
	</div>
</div>
