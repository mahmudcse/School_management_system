<?php 
$edit_data		=	$this->db->get_where('account' , array('componentId' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_account');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/account/do_update/'.$row['componentId'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                    <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="uniqueCode" value="<?php echo $row['uniqueCode'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('description');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="description" value="<?php echo $row['description'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category1');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="category1" value="<?php echo $row['category1'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category2');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="category2" value="<?php echo $row['category2'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('category3');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="category3" value="<?php echo $row['category3'];?>"/>
                        </div>
                    </div>
                    
            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_account');?></button>
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


