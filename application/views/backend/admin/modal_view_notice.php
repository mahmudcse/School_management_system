<?php $notice_info = $this->db->get_where('noticeboard', array('notice_id' => $param2))->row_array(); 
// echo '<pre>';
// print_r($notice_info);
// echo '</pre>';
// exit();
?>
<div class="row">
    <div class="col-md-12">

        <div class="panel panel-primary" data-collapsed="0">

            <div class="panel-heading">
                <div class="panel-title">
                    <?php echo get_phrase('notice'); ?>
                </div>
            </div>

            <div class="panel-body">
            	<div class="">Date: <?php echo date('d M,Y', strtotime($notice_info['create_timestamp']));?> </div>
				<div><h3 style="text-align: center; text-decoration: underline"><?php echo $notice_info['notice_title']?></h3> </div>
				<br>
                <div style="border: 1px solid">
                	
                		<?php echo $notice_info['notice'];?>
                	
                </div>
                <br>
                <?php if($notice_info['file_name']) { ?>
                	<div>Attachment: <a href="<?php echo base_url().'uploads/notice/'.$notice_info['file_name']; ?>" class="btn btn-blue btn-icon icon-left">
                        <i class="entypo-download"></i>
                        <?php echo get_phrase('download');?>
                    </a></div>
				<?php } ?>
            </div>

        </div>

    </div>
</div>
