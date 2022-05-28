<?php
$CI =& get_instance();
$data = $CI->get_general_configuration();
//if(!empty($data['report_header_line_1'])) echo '<h2>'.$data['report_header_line_1'].'</h2>';
//if(!empty($data['report_header_line_2'])) echo '<h3>'.$data['report_header_line_2'].'</h3>';
//if(!empty($data['report_header_line_3'])) echo '<h3>'.$data['report_header_line_3'].'</h3>';
?>
<table width="100%" border="0" cellspacing="0"> 
	<tr><td align="center"><?php if(!empty($data['report_header_line_1'])) echo '<b><FONT size=3>'.$data['report_header_line_1'].'</FONT></b>';?></td></tr>		
</table>
