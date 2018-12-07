<?php 
$edit_data		=	$this->db->get_where('session' , array('componentId' => $param2) )->result_array();
foreach ( $edit_data as $row):

$start   = date('m/d/Y',strtotime($row['start']));

$end   = date('m/d/Y',strtotime($row['end']));
?>
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_session');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'index.php?admin/session/do_update/'.$row['componentId'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>               
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('session_name');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="form-control" name="uniqueCode" value="<?php echo $row['uniqueCode'];?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('start');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="start" value="<?php echo $start;?>"/>
                        </div>
                    </div>
                    
                     <div class="form-group">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('end');?></label>
                        <div class="col-sm-5">
                            <input type="text" class="datepicker form-control" name="end" value="<?php echo $end;?>"/>
                        </div>
                    </div>

                    
            		<div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('edit_session');?></button>
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


