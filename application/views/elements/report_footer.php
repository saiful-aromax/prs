<?php
$CI=&get_instance();
$is_signatory_option_enable=$CI->Config_general->read('general');
$is_signatory_option_enable=isset($is_signatory_option_enable->is_signatory_option_enable)?$is_signatory_option_enable->is_signatory_option_enable:0;
$general_configuration=array();

if(isset($is_signatory_option_enable) && $is_signatory_option_enable==1){
	if(!isset($branch_id) ||$branch_id<0) {
		$user = $this->session->userdata('system.user');
		$branch_id = $user['branch_id'];
	}
	$general_configuration=$CI->Po_branch->get_formatted_signatory_data($branch_id);
	//echo "<pre>";print_r($general_configuration);
}
if(isset($is_signatory_option_enable) && $is_signatory_option_enable==1 && ($general_configuration['pomis_footer1_l_1']!='')){
	?>
	<table id="report_footer_table" width="100%" border="0" cellspacing="0">
		<?php
		for($i=1;$i<4;$i++){
			echo "<tr>";
			for($j=1;$j<4;$j++){ ?>
				<td nowrap="nowrap"><strong><?php echo $general_configuration['pomis_footer'.$i.'_l_'.$j]; ?></strong></td>
				<td nowrap="nowrap"><strong><?php echo ($general_configuration['pomis_footer'.$i.'_l_'.$j] != "")?" : ":""; ?></strong></td>
				<td nowrap="nowrap" width="33%"><?php echo $general_configuration['pomis_footer'.$i.'_v_'.$j]; ?></td>
		<?php	}
			echo "</tr>";
	 } ?>

		<tr>

			<td></td>
			<td >&nbsp;</td>
			<td ></td>
			<td >&nbsp;</td>
			<td ></td>
			<td >&nbsp;</td>
		</tr>
	</table>

<?php
	}else {
	if (isset($branch_id) && ($branch_id < 0)) {
		?>
		<table id="report_footer_table" width="100%" border="0" cellspacing="0">
			<tr>

				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
			</tr>

			<tr>

				<td nowrap="nowrap"><strong>Chief&nbsp;Accountant&nbsp;Name</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><strong>Chief&nbsp;Credit&nbsp;Officer&nbsp;name</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><strong>Chief&nbsp;Executive&nbsp;Name</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
			</tr>

			<tr>

				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
			</tr>
			<tr>

				<td></td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php
	} else {
		?>

		<table id="report_footer_table" width="100%" border="0" cellspacing="0">

			<tr>

				<td nowrap="nowrap"><strong>Prepared By </strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><strong>Verified By </strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td nowrap="nowrap"><strong>Approved By</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
			</tr>

			<tr>

				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
				<td><strong>Signature</strong></td>
				<td><strong>:</strong></td>
				<td width="33%">&nbsp;</td>
			</tr>


			<tr>

				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
				<td><strong>Designation</strong></td>
				<td><strong>:</strong></td>
				<td>&nbsp;</td>
			</tr>
			<tr>

				<td></td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
				<td></td>
				<td>&nbsp;</td>
			</tr>
		</table>
		<?php
	}
}
?>
