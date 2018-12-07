<script type="text/javascript">
function prepareAction(formObj, action){
	if(formObj==null){
		alert('Undefined form');
		return;
	}
	if('delete' == action.toLowerCase()){
		if( confirm("Are you sure?")){
			formObj.action = '<?php echo base_url('index.php/'.$component.'/delete');?>';
		}else{
			return;
		}
	}else if('update' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';
	}else  if('save' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/save');?>';
	}else  if('cancel' == action.toLowerCase()){
		formObj.action = '<?php echo base_url('index.php/'.$component.'/search');?>';
	}else{
		alert('Invalid action');
		return;
	}
	formObj.submit();
}
</script>
<div id="userinput">
	<?php echo form_open($component.'/save');?>
		<?php foreach ($inputs as $inp):?>
			<?php if($inp['type'] == 'hidden'){echo form_hidden( $inp['fielddata']['name'],$inp['fielddata']['value']);}?>
		<?php endforeach;?>
		<?php //$this->load->view('templates/buttonbar.php');?>
		<br/>
		<table>
			<?php foreach ($inputs as $inp):?>
			<?php if($inp['type'] != 'hidden'){?>
			<tr>
				<td><label><?php echo $inp['fielddata']['name'];?></label></td>
				<td>
					<?php 
					if($inp['type']=='textfield')
						echo form_input($inp['fielddata']);
					elseif ($inp['type']=='dropdown')
						echo form_dropdown($inp['fielddata']['name'], $inp['fielddata']['options'], $inp['fielddata']['value']);
					else 
						echo 'Type<>field map does not exist for type '.$inp['type'];
					?>
				</td>
			</tr>
			<?php }?>
			<?php endforeach;?>
		</table>

		<br/> 
		<?php $this->load->view('templates/buttonbar.php');?>

	<?php echo form_close();?>
	<br />
	<hr />

</div>