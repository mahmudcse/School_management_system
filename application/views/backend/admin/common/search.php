
<?php echo form_open($searchAction);?>	
<?php foreach ($inputs as $inp):?>		
<?php if($inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>	<?php endforeach;?>		
<table width="60%" style="margin:0px auto;">
	<tr>
	<?php foreach ($inputs as $inp):?>	<?php if($inp['type'] != 'hidden'){?>
	<th><?php echo $inp['label'];?>
	<?php if($inp['type']=='textfield')	echo form_input($inp['fielddata']);			
	elseif ($inp['type']=='dropdown')	echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value']);
	else echo 'Type<>field map does not exist for type '.$inp['type'];?>	
	</th>
	<?php }?>
	<?php endforeach;?>
		
</tr>
<tr>
	<td align="center" colspan="2"><input type="submit" value="Search"></td>
</tr>
</table>

<div style="clear: both;"></div>
<?php echo form_close();?>
<table class="table table-bordered datatable" id="table_export">
