<?php 
$edit_data		=	$this->db->get_where('item' , array('componentId' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_item');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/item/do_update/'.$row['componentId'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>               
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('item_name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="itemName" value="<?php echo $row['itemName'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category1');?></label>
                        <div class="col-sm-5">
                            <!-- <input type="text" class="form-control" name="category1" value="<?php echo $row['category1'];?>"/> -->
                            <select name="category1" class="form-control">
                                  <option value=""><?php echo get_phrase('Select');?></option>
                                  <option value="<?php echo Applicationconst::ITEM_TYPE_FEE;?>" <?php if(Applicationconst::ITEM_TYPE_FEE == $row['category1']) echo "selected"; ?>><?php echo Applicationconst::ITEM_TYPE_FEE;?></option>
                                  <option value="<?php echo Applicationconst::ITEM_TYPE_INVENTORY;?>" <?php if(Applicationconst::ITEM_TYPE_INVENTORY == $row['category1']) echo "selected"; ?>><?php echo Applicationconst::ITEM_TYPE_INVENTORY; ?></option>
                                  <option value="<?php echo Applicationconst::ITEM_TYPE_OTHERS;?>" <?php if(Applicationconst::ITEM_TYPE_OTHERS == $row['category1']) echo "selected"; ?>><?php echo Applicationconst::ITEM_TYPE_OTHERS;?></option>
                           </select>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category2');?></label>
                        <div class="col-sm-5">
                            <!-- <input type="text" class="form-control" name="category2" value="<?php echo $row['category2'];?>"/> -->
                            <select name="category2" class="form-control">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php 
                                    $query = "select * from codes where key_name = 'item.category2'";
                                    $item_category2 = $this->db->query($query)->result_array();
                                 ?>
                                <?php foreach($item_category2 as $category2): ?>
                                        <option value="<?php echo $category2['value'];?>" <?php if($category2['value'] == $row['category2']) echo "selected";?>>
                                                <?php echo $category2['value'];?>
                                        </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category3');?></label>
                        <div class="col-sm-5">
                            <!-- <input type="text" class="form-control" name="category3" value="<?php echo $row['category3'];?>"/> -->

                            <select name="category3" class="form-control">
                                <option value=""><?php echo get_phrase('select');?></option>
                                <?php 
                                    $query = "select * from codes where key_name = 'item.category3'";
                                    $item_category3 = $this->db->query($query)->result_array();
                                 ?>
                                <?php foreach($item_category3 as $category3): ?>
                                        <option value="<?php echo $category3['value'];?>" <?php if($category3['value'] == $row['category3']) echo "selected";?>>
                                                <?php echo $category3['value'];?>
                                        </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <!-- <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('amount');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="salePrice" value="<?php echo $row['salePrice'];?>"/>
                        </div>
                    </div> -->
                    
            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_item');?></button>
						</div>
					</div>
        		</form>
            </div>
        </div>
    </div>
</div>

<?php
endforeach;
?>


