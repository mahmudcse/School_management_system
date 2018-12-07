<div class="box">
	<div class="box-header">
    
    	<!------CONTROL TABS START------->
		<ul class="nav nav-tabs nav-tabs-left">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="icon-align-justify"></i> 
					<?php echo get_phrase('document_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="icon-plus"></i>
					<?php echo get_phrase('upload_document');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------->
        
	</div>
	<div class="box-content padded">
		<div class="tab-content">
            <!----TABLE LISTING STARTS--->
            <div class="tab-pane box <?php if(!isset($edit_data))echo 'active';?>" id="list">
				
                <table cellpadding="0" cellspacing="0" border="0" class="table table-advance dTable">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo get_phrase('unique_id');?></div></th>
                    		<th><div><?php echo get_phrase('name');?></div></th>
                    		<th><div><?php echo get_phrase('description');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php $count = 1;foreach($documents as $document):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $document['uniqueCode'];?></td>
							<td><a href="<?php echo base_url();?>index.php?teacher/downloadDocument/<?php echo $document['documentId'];?>"><?php echo $document['name'];?></a></td>
							<td><?php echo $document['description'];;?></td>
							<td align="center">
                            	<a data-toggle="modal" href="#modal-delete" onclick="modal_delete('<?php echo base_url();?>index.php?teacher/document/delete/<?php echo $document['documentId'];?>')" class="btn btn-red btn-small">
                                		<i class="icon-trash"></i> <?php echo get_phrase('delete');?>
                                </a>
                                <a href="<?php echo base_url();?>index.php?teacher/shareDocument/<?php echo $document['documentId'];?>" class="btn btn-red btn-small">
                                	<i class="icon-share"></i> <?php echo get_phrase('share');?>
                                </a>
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                    <?php echo form_open(base_url(). 'index.php?teacher/document/upload' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                      
                        <div class="padded">
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('document');?></label>
                                <div class="controls">
                                    <input type="file" class="validate[required]" name="userfile"/>
                                </div>
                            </div>
                            
                            <div class="control-group">
                                <label class="control-label"><?php echo get_phrase('descripption');?></label>
                                <div class="controls">
                                    <input type="text" class="validate[required]" name="description"/>
                                </div>
                            </div>
                           
                            
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="btn btn-gray"><?php echo get_phrase('upload');?></button>
                        </div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS--->
            
		</div>
	</div>
</div>