
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('employee_salary_edit');?>
            	</div>
            </div>
			<div class="panel-body">
                    <?php echo form_open(base_url() . 'index.php?admin/employee_salary/do_update/'.$row['id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top', 'enctype' => 'multipart/form-data'));?>
                          
                            <div class="form-group">
                                <label for="field-1" class="col-sm-2"><?php echo get_phrase('salary');?></label>

                                <label for="field-1" class="col-sm-2"><?php echo get_phrase('applicable_from');?></label>

                                <label for="field-1" class="col-sm-2"><?php echo get_phrase('applicable_till');?></label>
                                <label for="field-1" class="col-sm-4"><?php echo get_phrase('description');?></label>

                            </div>
                            <br><br>
                            <input type="hidden" name="user_id" value="<?php echo $salary_data[0][user_id]; ?>">
                            <?php foreach ($salary_data as $row): ?>
                                <div>
                                    <input type="hidden" name="salary_id[]" value="<?php echo $row['id']; ?>">
                                    <div class="col-sm-2">
                                        <input id="salary" type="text" class="form-control" name="salary[]"  
                                            value="<?php echo $row['salary']; ?>">
                                    </div>

                                    
                                    <div class="col-sm-2">
                                        <input type="text" data-format="dd-M-yyyy" class="form-control datepicker" name="applicableFrom[]"  
                                            value="<?php echo $row['applicableFrom']; ?>">
                                    </div>

                                    
                                    <div class="col-sm-2">
                                        <input type="text" data-format="dd-M-yyyy" class="form-control datepicker" name="applicableTill[]"  
                                            value="<?php echo $row['applicableTill']; ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="textarea" class="form-control" name="description[]"  
                                            value="<?php echo $row['description']; ?>">
                                    </div>
                                    <br><br><br>
                                </div>

                            <?php endforeach ?>

                            


                            <div id="blankform">
                                <input type="hidden" name="salary_id[]" value="">
                                <div class="col-sm-2">
                                    <input id="salary" type="text" class="form-control" name="salary[]"  
                                        value="">
                                </div>

                                
                                <div class="col-sm-2">
                                    <input type="text" data-format="dd-M-yyyy" class="form-control datepicker" name="applicableFrom[]"  
                                        value="">
                                </div>

                                
                                <div class="col-sm-2">
                                    <input type="text" data-format="dd-M-yyyy" class="form-control datepicker" name="applicableTill[]"  
                                        value="">
                                </div>
                                <div class="col-sm-6">
                                    <input type="textarea" class="form-control" name="description[]"  
                                        value="">
                                </div>
                                <br><br><br>
                            </div>

                            <div id="salary_entry_add">
                                
                            </div>

                            

                            

                            <div class="row">
                
                                <button style="margin-top: 2%; margin-left: 3%" type="button" class="btn btn-default" id="add_a_row">
                                    <i class="entypo-plus"></i> <?php echo get_phrase('add_a_row');?>
                                </button>
                
                            </div>

                            <div class="col-md-3" style="margin-top: 20px;">
                                <button type="submit" class="btn btn-info"><?php echo get_phrase('save');?></button>
                            </div>

                            
                        </div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('#add_a_row').on('click', function(){
        var blankform = $('#blankform').html();
        //alert(blankform);
        $('#salary_entry_add').append(blankform).find(".datepicker").datepicker();;
    })


</script>