<div class="row">
	<div class="col-md-8">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('addmission_form');?>
            	</div>
            </div>

			<div class="panel-body">
                <?php echo form_open(base_url() . 'index.php?admin/student/create/' , array('class' => 'form-horizontal form-groups-bordered validate', 'enctype' => 'multipart/form-data'));?>
					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Student Code');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" name="student_code" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>

					</div>

					

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>

					

					<div class="form-group">

						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Father&rsquo;s Name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" name="fathername" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>

						</div>

					</div>

					

					<div class="form-group">

						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Father&rsquo;s Profession');?></label>

                        

						<div class="col-sm-5">

							<select name="fprofession" class="form-control selectboxit">

                              <option value=""><?php echo get_phrase('select');?></option>

                              <option value="Service"><?php echo get_phrase('Service');?></option>

                              <option value="Business"><?php echo get_phrase('Business');?></option>

                          </select>

						</div> 

					</div>

					

					<div class="form-group">

						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Mobile(F)');?></label>

                        

						<div class="col-sm-5">

							<input type="text" class="form-control" name="fcontactno" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>

						</div>

					</div>

					

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Mother&rsquo;s Name');?></label>

						<div class="col-sm-5">
							<input type="text" class="form-control" name="mothername" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Mother&rsquo;s Profession');?></label>

						<div class="col-sm-5">
							<select name="mprofession" class="form-control selectboxit">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <option value="Service"><?php echo get_phrase('Service');?></option>
                              <option value="Business"><?php echo get_phrase('Business');?></option>
                          </select>
						</div> 
					</div>

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('Mobile(M)');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="mcontactno"  data-message-required="<?php echo get_phrase('value_required');?>" value="" autofocus>
						</div>
					</div>

					<div class="form-group">

						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
						<div class="col-sm-5">
							<select name="class_id" class="form-control" data-validate="required" id="class_id" 
								data-message-required="<?php echo get_phrase('value_required');?>"
									onchange="return get_class_sections(this.value)">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
								$classes = $this->db->get('class')->result_array();
								foreach($classes as $row):
									?>
                            		<option value="<?php echo $row['class_id'];?>">
											<?php echo $row['name'];?>
                                            </option>
                                <?php
								endforeach;
							  ?>
                          </select>
						</div> 
					</div>



					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('section');?></label>
		                    <div class="col-sm-5">
		                        <select name="section_id" class="form-control" id="section_selector_holder">
		                            <option value=""><?php echo get_phrase('select_class_first');?></option>
			                    </select>
			                </div>
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('roll');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="roll" value="" >
						</div> 
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('birthday');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control datepicker" name="birthday" value="" data-start-view="2">
						</div> 
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('gender');?></label>
						<div class="col-sm-5">
							<select name="sex" class="form-control selectboxit">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <option value="male"><?php echo get_phrase('male');?></option>
                              <option value="female"><?php echo get_phrase('female');?></option>
                          </select>
						</div> 
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Present Address');?></label>
						<div class="col-sm-5">
							<textarea name="address" id="ttt" rows="3" class="form-control"></textarea>
						</div> 
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('Permanent Address');?></label>
						<div class="col-sm-5">
							<textarea name="paddress" id="ttt" rows="3" class="form-control"></textarea>
						</div> 
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('phone');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="phone" value="">
						</div> 
					</div>

                    

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('email');?></label>
						<div class="col-sm-5">
							<input type="text" class="form-control" name="email"  data-message-required="<?php echo get_phrase('value_required');?>" value="">
						</div>
					</div>

					

					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('password');?></label>
						<div class="col-sm-5">
							<input type="password" class="form-control" name="password" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>" value="" >
						</div> 
					</div>



					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('dormitory');?></label>
						<div class="col-sm-5">
							<select name="dormitory_id" class="form-control selectboxit">
                              <option value=""><?php echo get_phrase('select');?></option>
	                              <?php 
	                              	$dormitories = $this->db->get('dormitory')->result_array();
	                              	foreach($dormitories as $row):
	                              ?>
                              		<option value="<?php echo $row['dormitory_id'];?>"><?php echo $row['name'];?></option>
                          		<?php endforeach;?>
                          </select>
						</div> 
					</div>



					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('transport_route');?></label>
						<div class="col-sm-5">
							<select name="transport_id" class="form-control selectboxit">
                              <option value=""><?php echo get_phrase('select');?></option>
	                              <?php 
	                              	$transports = $this->db->get('transport')->result_array();
	                              	foreach($transports as $row):
	                              ?>
                              		<option value="<?php echo $row['transport_id'];?>"><?php echo $row['route_name'];?></option>
                          		<?php endforeach;?>
                          </select>
						</div> 
					</div>

	

					<div class="form-group">
						<label for="field-1" class="col-sm-3 control-label"><?php echo get_phrase('photo');?></label>
						<div class="col-sm-5">
							<div class="fileinput fileinput-new" data-provides="fileinput">
								<div class="fileinput-new thumbnail" style="width: 100px; height: 100px;" data-trigger="fileinput">
									<img src="http://placehold.it/200x200" alt="...">
								</div>

								<div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px"></div>
								<div>
									<span class="btn btn-white btn-file">
										<span class="fileinput-new">Select image</span>
										<span class="fileinput-exists">Change</span>
										<input type="file" name="userfile" accept="image/*">
									</span>
									<a href="#" class="btn btn-orange fileinput-exists" data-dismiss="fileinput">Remove</a>
								</div>
							</div>
						</div>
					</div>

                    

                    <div class="form-group">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('add_student');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
		<blockquote class="blockquote-blue">
			<p>
				<strong>Student Admission Notes</strong>
			</p>
			<p>
				Admitting new students will automatically create an enrollment to the selected class in the running session.

				Please check and recheck the informations you have inserted because once you admit new student, you won't be able

				to edit his/her class,roll,section without promoting to the next session.
			</p>
		</blockquote>
	</div>
</div>
<script type="text/javascript">
	function get_class_sections(class_id) {
    	$.ajax({
            url: '<?php echo base_url();?>index.php?admin/get_class_section/' + class_id ,
            success: function(response)
            {
                jQuery('#section_selector_holder').html(response);
            }
        });
    }
</script>