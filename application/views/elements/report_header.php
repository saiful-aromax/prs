<?php
    $CI =& get_instance();
    $data = $CI->get_general_configuration();
    /*----- report header information address for branch ---------------*/
    //echo "<pre>"; print_r($data['branch_address_on_report']); echo "</pre>";
    $CI->load->model('Po_branch','',TRUE);
    $branch_id = $CI->get_branch_id();
    $bdata = $CI->Po_branch->read($branch_id);
    $bdata=$CI->__object_to_Array($bdata);
    $f=0;
    $class='';
    if(isset($data['organization_logo_on_report']) && $data['organization_logo_on_report']==1) {
        $f=1;
        $class='report_header_logo';
    }
    /*---- report header information address for branch -----------------*/    
    ?>
<div align="center" width="100%" class="<?php echo $class; ?>">
<table border="0" cellspacing="0"> 
        <?php  if(isset($data['branch_address_on_report']) && $data['branch_address_on_report']==1 && $CI->is_head_office() == 0) { ?>
            <tr>
                <?php
                    clearstatcache();
                    if($f==1 && file_exists("media/images/".SITE_NAME."_picture/".$data['po_logo'])){
                        echo '<td align="right" rowspan="3" width="40%">';
                        echo "<img src='".base_url()."media/images/".SITE_NAME."_picture/".$data['po_logo']."' alt='logo' width= '45px'/>&nbsp;</td>";
                    }
                ?>                
                <td align="center" width="60%"><?php if(!empty($data['report_header_line_1'])) echo '<b><FONT size=3>'.$data['report_header_line_1'].'</FONT></b>';?></td></tr>
            <tr><td align="center" width="60%"><?php if(!empty($bdata['address'])) echo '<b><FONT size=2>'.$bdata['address'].'</FONT></b>';?></td></tr>		
        <?php } else {  ?>            
            <tr>
                <?php 
                    //echo base_url()."media/images/".SITE_NAME."_picture/".$data['po_logo'];  
                    clearstatcache();
                    if($f==1 && file_exists("media/images/".SITE_NAME."_picture/".$data['po_logo'])) {
                        echo '<td align="right" rowspan="3" width="40%">';
                        echo "<img src='".base_url()."media/images/".SITE_NAME."_picture/".$data['po_logo']."' alt='logo' width= '45px'/>&nbsp;</td>";
                    }
                ?>
                <td align="center"><?php
                if(!empty($data['report_header_line_1'])) echo '<b><FONT size=3>'.$data['report_header_line_1'].'</FONT></b>';?></td></tr>
            <tr><td align="center"><?php if(!empty($data['report_header_line_2'])) echo '<b><FONT size=2>'.$data['report_header_line_2'].'</FONT></b>';?></td></tr>		
            <tr><td align="center"><?php if(!empty($data['report_header_line_3'])) echo '<b><FONT size=2>'.$data['report_header_line_3'].'</FONT></b>';?></td></tr>
        <?php }   ?>
</table>
</div>