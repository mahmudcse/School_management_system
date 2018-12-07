<br>
<div class="container">
<div class="row">
<?php echo form_open(base_url() . 'index.php?admin/ledger', array('class' => 'form-inline validate'));?>

	<div class="form-group">
      <label for="account">Account</label>
      <select name="accountId" class="form-control" required style="width: 100px;">
                       <option value=""><?php echo get_phrase('select_account');?></option>
                          <?php 
                             $categories = $this->db->get_where('account')->result_array();
                             foreach ($categories as $row):
                             ?>
                       <option value="<?php echo $row['componentId'];?>"><?php echo $row['description'];?></option>
		<?php endforeach;?>
	 </select>
    </div>
    <div class="form-group">
      <label for="user">User</label>
      <select name="userId" class="form-control" required style="width: 100px;">
                       <option value=""><?php echo get_phrase('select_user');?></option>
                          <?php 
                             $customers = $this->db->get_where('user')->result_array();
                             foreach ($customers as $row):
                             ?>
                       <option value="<?php echo $row['user_id'];?>"><?php echo $row['user_name'];?></option>
                       <?php endforeach;?>
	  </select>
    </div>
    <div class="form-group">
      <label for="startdate">From:</label>
      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" name="start_date" value="<?php echo  date('d-m-Y', strtotime($start_date)); ?>" placeholder="">
    </div>
    <div class="form-group">
      <label for="enddate">To:</label>
      <input type="text" data-format="dd-mm-yyyy" class="form-control  datepicker" name="end_date" value="<?php echo  date('d-m-Y', strtotime($end_date)); ?>" placeholder="">
    </div>
    <input type="submit" class="btn btn-info" value="Show">

  <?php echo form_close() ?>
  </div>
</div>
<br/>
<div class="col-md-10" id="print"> 
<div>Account : <strong><?php echo $this->db->get_where('account', ['componentId' => $accountId])->row()->description;?></strong></div>
<div>User : <strong><?php echo $this->db->get_where('user', ['user_id' => $userId])->row()->user_name;?></strong></div>
<div>Period : <strong><?php echo date('d-m-Y', strtotime($start_date));?></strong> to <strong><?php echo date('d-m-Y', strtotime($end_date));?></strong></div>
<br />

              
                
               <table class="table table-bordered">
               
                   <thead>
                       <tr>
                        <th><div><?php echo get_phrase('date')?></div>
                        <th><div><?php echo get_phrase('particulars');?></div></th>
                        <th><div><?php echo get_phrase('dr');?></div></th>
                        <th><div><?php echo get_phrase('cr');?></div></th>
                        <th><div><?php echo get_phrase('balance');?></div></th>                          
                       </tr>
                   </thead>
                   <tbody>
                   	<tr>
                   		<td colspan="4" style="text-align: right; border:1px solid #CCCCCC">Balance</td>
                   		<td align="center" style="border:1px solid #CCCCCC; font-weight:bold"><?php echo $cb;?></td>
                   	</tr>
                   	<?php foreach ( $searchData as $row ): ?>
                   	<tr>                   		
                   		<td><?php echo date('d-m-Y',strtotime($row->tdate));?></td>
                   		<td><?php echo $row->description;?></td>
                   		<td><?php echo $row->dr;?></td>
                   		<td><?php echo $row->cr;?></td>
						<td><?php echo $row->amt;?></td>
                   	</tr>
                   	<?php endforeach; ?>
                   	<tr>
                   		<td colspan="4" style="text-align: right; border:1px solid #CCCCCC">Opeing Balance</td>
                   		<td align="center" style="border:1px solid #CCCCCC; font-weight:bold"><?php echo $ob;?></td>
                   	</tr>
                   </tbody>
                </table>

</div>
<div class="row">
	<div class="col-md-12">
	<input type="button" class="btn btn-primary" value="print" onclick="printDiv()">
	</div>
</div>				


<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">
function printDiv() {    
    var printContents = document.getElementById('print').innerHTML;
    var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();
     document.body.innerHTML = originalContents;
    }
$(document).ready(function() {
    $('#rec').DataTable();
    $('#pay').DataTable();
} );
	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_export").dataTable({
			"sPaginationType": "bootstrap",
			"sDom": "<'row'<'col-xs-3 col-left'l><'col-xs-9 col-right'<'export-data'T>f>r>t<'row'<'col-xs-3 col-left'i><'col-xs-9 col-right'p>>",
			"oTableTools": {
				"aButtons": [
					
					{
						"sExtends": "print",
						"fnSetText"	   : "Press 'esc' to return",
						"fnClick": function (nButton, oConfig) {
							datatable.fnSetColumnVis(0, false);
							datatable.fnSetColumnVis(3, false);
							
							this.fnPrint( true, oConfig );
							
							window.print();
							
							$(window).keyup(function(e) {
								  if (e.which == 27) {
									  datatable.fnSetColumnVis(0, true);
									  datatable.fnSetColumnVis(3, true);
								  }
							});
						},
						
					},
				]
			},
			
		});
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>

