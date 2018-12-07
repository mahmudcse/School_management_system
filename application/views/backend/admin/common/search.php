
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
<table class="table table-bordered datatable" id="table_export">	<thead>    	<tr>         	<th><div>#</div></th>                        <?php            foreach($propertyArr as $prop=>$value)			{			?>			<th><div><?php echo get_phrase($value);?></div></th>			<?php			}    			?>                    				</tr>	</thead>	<tbody>    	<?php     	$count = 1;    	if($searchData != null && count($searchData) > 0){    		foreach ($searchData as $value ){    			    	?>    	<tr>    		<td><?php echo $count++;?></td>    		<?php     		foreach($propertyArr as $prop=>$name){    			$displayText = $value->{$prop};    		?>    		<td><?php echo Applicationconst::checkAndConv($displayText);?></td>    		<?php     		}    		?>    	</tr>    	<?php    		}    	}    	?>    	  	</tbody></table>

