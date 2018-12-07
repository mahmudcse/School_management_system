<hr />
<div class="row">
	<div class="col-md-12">
    
    	<!--CONTROL TABS START-->
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('item_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_item');?>
                    	</a></li>
		</ul>
    	<!--CONTROL TABS END-->
        
		<div class="tab-content">
        <br>
            <!--TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
				
                <table class="table table-bordered datatable" id="table_export">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('description');?></div></th>
                    		<th><div><?php echo get_phrase('category 1');?></div></th>
                    		<th><div><?php echo get_phrase('category 2');?></div></th>
                    		<th><div><?php echo get_phrase('category 3');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($items as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['itemName'];?></td>
							<td><?php echo $row['uniqueCode'];?></td>
							<td><?php echo $row['category1'];?></td>
							<td><?php echo $row['category2'];?></td>
							<td><?php echo $row['category3'];?></td>
							<td>
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                    Action <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                    
                                    <!-- EDITING LINK -->
                                    <li>
                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_edit_item/<?php echo $row['componentId'];?>');">
                                            <i class="entypo-pencil"></i>
                                                <?php echo get_phrase('edit');?>
                                            </a>
                                                    </li>
                                    <li class="divider"></li>
                                    
                                    <!-- DELETION LINK -->
                                    <li>
                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>index.php?admin/item/delete/<?php echo $row['componentId'];?>');">
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
            <!--TABLE LISTING ENDS->
            
            
			<!CREATION FORM STARTS-->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'index.php?admin/item/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        <div class="padded">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="itemName" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            
                             <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('category1');?></label>
                                <div class="col-sm-5">
                                     <select name="category1" class="form-control">
										  <option value=""><?php echo get_phrase('Select');?></option>
										  <option value="<?php echo Applicationconst::ITEM_TYPE_FEE;?>"><?php echo Applicationconst::ITEM_TYPE_FEE;?></option>
										  <option value="<?php echo Applicationconst::ITEM_TYPE_INVENTORY;?>"><?php echo Applicationconst::ITEM_TYPE_INVENTORY;?></option>
										  <option value="<?php echo Applicationconst::ITEM_TYPE_OTHERS;?>"><?php echo Applicationconst::ITEM_TYPE_OTHERS;?></option>
								   </select>
								 </div>
                            </div>
                            
                             <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('category2');?></label>
                                <div class="col-sm-5">
                                    <select name="category2" class="form-control">
                                    <option value=""><?php echo get_phrase('select');?></option>
	                                <?php foreach($item_category2 as $row): ?>
	                                		<option value="<?php echo $row['value'];?>">
													<?php echo $row['value'];?>
	                                        </option>
	                                <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                             <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('category3');?></label>
                                <div class="col-sm-5">
                                    <select name="category3" class="form-control">
                                    <option value=""><?php echo get_phrase('select');?></option>
	                                <?php foreach($item_category3 as $row): ?>
	                                		<option value="<?php echo $row['value'];?>">
													<?php echo $row['value'];?>
	                                        </option>
	                                <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('price');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="salePrice"/>
                                </div>
                            </div> -->

                        </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_item');?></button>
                              </div>
							</div>
                    </form>                
                </div>                
			</div>
			<!--CREATION FORM ENDS-->
		</div>
	</div>
</div>



<!---  DATA TABLE EXPORT CONFIGURATIONS -->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>