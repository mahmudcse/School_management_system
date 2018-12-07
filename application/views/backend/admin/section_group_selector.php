<?php
$query = $this->db->get_where('class_group' , array('class_id' => $class_id));
if($query->num_rows() > 0):
$groups = $query->result_array();
?>

<div class="form-group">
    <label class="col-sm-3 control-label"><?php echo get_phrase('group');?></label>
    <div class="col-sm-5">
        <select name="group_id" class="form-control selectboxit" style="width:100%;">
        <option value="">Select</option>
        <?php
        	foreach($groups as $row):
        ?>
    	<option value="<?php echo $row['id'];?>"><?php echo $row['group_name'];?></option>
    	<?php endforeach;?>
        </select>
    </div>
</div>
	
<?php endif;?>


<script type="text/javascript">
    $(document).ready(function() {
        if($.isFunction($.fn.selectBoxIt))
        {
            $("select.selectboxit").each(function(i, el)
            {
                var $this = $(el),
                    opts = {
                        showFirstOption: attrDefault($this, 'first-option', true),
                        'native': attrDefault($this, 'native', false),
                        defaultText: attrDefault($this, 'text', ''),
                    };
                    
                $this.addClass('visible');
                $this.selectBoxIt(opts);
            });
        }
    });
    
</script>