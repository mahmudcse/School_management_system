<hr />
<div class="row">
	<div class="col-md-12">
		<!----TABLE LISTING STARTS-->
		<div class="tab-pane box active" id="list">
			<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered datatable" id="table_export">
				<thead>
					<tr>
						<th><div>#</div></th>
						<th><div><?php echo get_phrase('date');?></div></th>
						<th><div><?php echo get_phrase('title');?></div></th>

						<th><div><?php echo get_phrase('options');?></div></th>
					</tr>
				</thead>
				<tbody>
                    	<?php $count = 1; foreach($notices as $row):?>
                        <tr>
							<td><?php echo $count++;?></td>
							<td><?php echo date('d M,Y', strtotime($row['create_timestamp']));?></td>
							<td><?php echo $row['notice_title'];?></td>
							<td>
								<div class="btn-group">
									<a href="#"
										onclick="showAjaxModal('<?php echo base_url();?>index.php?modal/popup/modal_view_notice/<?php echo $row['notice_id'];?>');">
										<button class="btn btn-primary btn-sm">View</button>
									</a>
	
								</div>
							</td>
						</tr>
                        <?php endforeach;?>
                 </tbody>
			</table>
		</div>
		<!----TABLE LISTING ENDS--->
	</div>
</div>
