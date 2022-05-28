<?php echo form_open('user_role_wise_privileges/'.(isset($row->id)?"edit":"add")); ?>
<?php echo validation_errors(); ?>
<?php 	echo form_hidden('role_id', $role_id);?>
	<table class="addForm" border="0" cellspacing="0px" cellpadding="0px" width="100%">
		<tr>
			<td class="formTitleBar">
			<?php $class_name = 'class="formTitleBar_edit"'; ?>
				<div <?php echo $class_name?>>
					<div style="float:left;margin: 0 0 0 20px;">
				</div>
			</td>

		</tr>
    </table>
<style>
.bluetableborder{
    border-left: 1px solid #CDCDCD;
    border-right: 1px solid #CDCDCD;
    border-top: 1px solid #CDCDCD;
    margin: 10px 0px;
    width: 100%;
}
.bluetableborder tr:hover td { /*background: #ecf2f6;*/ color:#000; background: #FFFF95;}

.bluetablehead{padding-left:5px;padding-top:3px;padding-bottom:3px;border:1px solid #D7DEEE;padding-right:3px;}
.bluetablehead05 {   
	background-color: #CDCDCD;
    color: #2E2E2E;
    padding-bottom: 4px;
    padding-left: 10px;
    padding-top: 3px;}
.bluetablehead06 {   
	text-transform: uppercase;
	text-align:left;
	background-color: #555;
    color: #f1f1f1;
    padding-bottom: 4px;
    padding-left: 10px;
    padding-top: 3px;
    font-size: 14px;
    }

 .bluetablehead07 {   
	background-color: #CDCDCD;
    color: #2E2E2E;
    padding-bottom: 4px;
    padding-left: 10px;
    padding-top: 3px;
    font-size: 13px;
    }
.paddingleft05BottomBorder {
    border-bottom: 1px solid #CDCDCD;
    font-family: Arial;
    padding-left: 5px;
    padding-top: 3px;
}
.check_w {
	width:10px;
}
</style>
<script type="text/javascript">
function jqCheck(id,sl1,sl2,sl3,sl4){	
				if ($("#edit_group_" + sl1+ "_" + sl2+ "_" + sl3+ "_" + sl4).is(':checked') || $("#add_group_" + sl1+ "_" + sl2+ "_" + sl3+ "_" + sl4).is(':checked') || $("#delete_group_" + sl1+ "_" + sl2+ "_" + sl3+ "_" + sl4).is(':checked') || $("#execute_group_" + sl1+ "_" + sl2+ "_" + sl3+ "_" + sl4).is(':checked')) {					
					
					$("#view_group_" + sl1+ "_" + sl2+ "_" + sl3+ "_" + sl4).attr("checked",true);	
						
				}	
}
function jqCheckSubGroup(type,id,sl1,sl2){	
		
				if (!id.checked) {
					$("input[class^='" + type +"_group[" + sl1+ "]']").attr("checked",false);		
				}	else {
					$("input[class^='" + type +"_group[" + sl1+ "]']").attr("checked",true);	
				}	

}
function jqCheckAll(type,id){		
				//alert(id.checked);		
				if (!id.checked) {
					$("input[class^=" + type +"_group]").attr("checked",false);		
				}	else {
					$("input[class^=" + type +"_group]").attr("checked",true);	
				}	

}
$(document).ready(function()
		{
			$("#all_view").click(function()				
			{
				jqCheckAll ('view',this);					
			});		
			$("#all_add").click(function()				
			{
				jqCheckAll ('add',this);					
			});
			$("#all_edit").click(function()				
			{
				jqCheckAll ('edit',this);					
			});
			$("#all_delete").click(function()				
			{
				jqCheckAll ('delete',this);					
			});			
			
			$("#all_execute").click(function()				
			{
				jqCheckAll ('execute',this);					
			});	
			
	
		});
</script>
<table cellspacing="0" cellpadding="2" border="0" width="95%" class="bluetableborder">
<tr class='bluetablehead05'>
	<th colspan="4">Description</th>
	<th width="50">View</th>
	<th width="50">Add</th>
	<th width="50">Edit</th>
	<th width="50">Delete</th>
</tr>
<tr>
	<td class='paddingleft05BottomBorder' colspan="4"></td>
	<td class='paddingleft05BottomBorder'><div align="center"><input type="checkbox" class="check_w" value="all_view" id="all_view" name="all_view"></div></td>	
	<td class='paddingleft05BottomBorder'><div align="center"><input type="checkbox"  class="check_w" value="all_add" id="all_add" name="all_add"></div></td>	
	<td class='paddingleft05BottomBorder'><div align="center"><input type="checkbox"  class="check_w" value="all_edit" id="all_edit" name="all_edit"></div></td>	
	<td class='paddingleft05BottomBorder'><div align="center"><input type="checkbox"  class="check_w" value="all_delete" id="all_delete" name="all_delete"></div></td>
</tr>
<?php
//echo '<pre>';
//print_r($user_resources_array);
$grp = 0;
$sub_grp_class = 0;
foreach($user_resources_array as $key=>$rows){	?>
	<tr class='bluetablehead06'>
		<th class='paddingleft05BottomBorder' style="color: #f1f1f1;" colspan="9"><?php echo $key?></th>
		</tr>
	<?php
	$sub_grp = 0;
	$odd_even = 0;
	foreach($rows as $key1=>$rows1){
		?>
		<tr class='bluetablehead07'>
			<th class='paddingleft05BottomBorder'></th>
			<th class='paddingleft05BottomBorder' align="left" colspan="3"><?php echo $key1?></th>
		<th class='paddingleft05BottomBorder' > <div align="center"><input type="checkbox" id="view_group_<?php echo "{$grp}_{$sub_grp}" ?>" onclick="jqCheckSubGroup ('view',this,<?php echo $sub_grp_class ?>,<?php echo $sub_grp ?>)" class="view_group check_w"  name="view_group[<?php echo $grp ?>][<?php echo $sub_grp ?>]"></div></th>
		<th class='paddingleft05BottomBorder' > <div align="center"><input type="checkbox" id="add_group_<?php echo "{$grp}_{$sub_grp}" ?>" onclick="jqCheckSubGroup ('add',this,<?php echo $sub_grp_class ?>,<?php echo $sub_grp ?>)"  class="add_group check_w"  name="add_group[<?php echo $grp ?>][<?php echo $sub_grp ?>]"></div></th>	
		<th  class='paddingleft05BottomBorder' > <div align="center"><input type="checkbox" id="edit_group_<?php echo "{$grp}_{$sub_grp}" ?>"  onclick="jqCheckSubGroup ('edit',this,<?php echo $sub_grp_class ?>,<?php echo $sub_grp ?>)"  class="edit_group check_w" name="edit_group[<?php echo $grp ?>][<?php echo $sub_grp ?>]"></div></th>	
		<th  class='paddingleft05BottomBorder' > <div align="center"><input type="checkbox" id="delete_group_<?php echo"{$grp}_{$sub_grp}" ?>"  onclick="jqCheckSubGroup ('delete',this,<?php echo $sub_grp_class ?>,<?php echo $sub_grp ?>)"  class="delete_group check_w" name="delete_group[<?php echo $grp ?>][<?php echo $sub_grp ?>]"></div></th>
		</tr>
		<?php
		$entity = 0;
		foreach($rows1 as $key2=>$rows2){
			$oddrow = '';
			if($odd_even++ % 2 == 1) {
				$oddrow = "oddrow";
			}
			?><tr class="<?php echo $oddrow?>"><?php
			//$evenrow = "";
			$id=0;
			
			foreach($rows2 as $key3=>$rows3){
				$view_checked = "";
				$add_checked = "";
				$edit_checked = "";
				$delete_checked = "";
				$execute_checked = "";
				$controllers_actions = "";
				foreach($role_privilege_resources as $resource){
					if(isset($rows3['View'])) {
						foreach($rows3['View'] as $rows5){
							if($resource['controller'] == $key3 AND $resource['action'] == $rows5['name']){
								$view_checked = "checked = 'checked'";								
							}
						}
					}
					if(isset($rows3['Add'])) {
						foreach($rows3['Add'] as $rows5){
							if($resource['controller'] == $key3 AND $resource['action'] == $rows5['name']){
								$add_checked = "checked = 'checked'";								
							}
						}
					}
					if(isset($rows3['Edit'])) {
						foreach($rows3['Edit'] as $rows5){
							if( $resource['controller'] == $key3 AND $resource['action'] == $rows5['name']){
								$edit_checked = "checked = 'checked'";								
							}
						}
					}
					if(isset($rows3['Delete'])) {
						foreach($rows3['Delete'] as $rows5){
							if($resource['controller'] == $key3 AND $resource['action'] == $rows5['name']){
								$delete_checked = "checked = 'checked'";								
							}
						}
					}

				}
				
//				if(DEBUG) {
//					foreach($rows3 as $rows4){
//						foreach($rows4 as $rows5){
//							$controllers_actions[] = $rows5['name'];
//						}
//					}
//				}
				if(is_array($controllers_actions)){
							$controllers_actions = implode(", ",$controllers_actions);
							$controllers_actions = "( $key3 -> $controllers_actions )";
				}
				
				
			?>
				<td class='paddingleft05BottomBorder'></td>
				<td class='paddingleft05BottomBorder'></td>
				<td class='paddingleft05BottomBorder'></td>
				<td  class='paddingleft05BottomBorder' align="left" ><?php echo "$key2 $controllers_actions"; ?></td>
				<?php if(isset($rows3['View'])) { ?>
					<td class='paddingleft05BottomBorder' ><div align="center"><input <?php echo $view_checked?> type="checkbox" id="view_group_<?php echo "{$grp}_{$sub_grp}_{$entity}_{$id}" ?>" name="data[<?php echo $entity ?>][<?php echo $key3 ?>][View]" class="view_group[<?php echo $sub_grp_class ?>][<?php echo $id ?>] check_w"></div></td>
				<?php	} else { echo "<td align='center' class='paddingleft05BottomBorder'>-</td>";}	?>
				<?php if(isset($rows3['Add'])) { ?>
					<td class='paddingleft05BottomBorder' ><div align="center"> <input <?php echo $add_checked?> onchange="jqCheck(this,<?php echo $grp ?>,<?php echo $sub_grp ?>,<?php echo $entity ?>,<?php echo $id ?>)"  type="checkbox" id="add_group_<?php echo "{$grp}_{$sub_grp}_{$entity}_{$id}" ?>" name="data[<?php echo $entity ?>][<?php echo $key3 ?>][Add]"  class="add_group[<?php echo $sub_grp_class ?>][<?php echo $id ?>] check_w"></div></td>
				<?php	} else { echo "<td align='center' class='paddingleft05BottomBorder'>-</td>";}	?>
				<?php if(isset($rows3['Edit'])) { ?>
					<td class='paddingleft05BottomBorder' ><div align="center"> <input <?php echo $edit_checked?> onchange="jqCheck(this,<?php echo $grp ?>,<?php echo $sub_grp ?>,<?php echo $entity ?>,<?php echo $id ?>)"   type="checkbox" id="edit_group_<?php echo "{$grp}_{$sub_grp}_{$entity}_{$id}" ?>" name="data[<?php echo $entity ?>][<?php echo $key3 ?>][Edit]"  class="edit_group[<?php echo $sub_grp_class ?>][<?php echo $id ?>] check_w"></div></td>
				<?php	} else { echo "<td align='center' class='paddingleft05BottomBorder'>-</td>";}	?>
				<?php if(isset($rows3['Delete'])) { ?>
					<td class='paddingleft05BottomBorder' ><div align="center"> <input <?php echo $delete_checked?> onchange="jqCheck(this,<?php echo $grp ?>,<?php echo $sub_grp ?>,<?php echo $entity ?>,<?php echo $id ?>)"   type="checkbox" id="delete_group_<?php echo "{$grp}_{$sub_grp}_{$entity}_{$id}" ?>" name="data[<?php echo $entity ?>][<?php echo $key3 ?>][Delete]"  class="delete_group[<?php echo $sub_grp_class ?>][<?php echo $id ?>] check_w"></div></td>
				<?php	} else { echo "<td align='center' class='paddingleft05BottomBorder'>-</td>";}	?>

			</tr>
			<?php
			$id++;
			}
			$entity++;
		}
		$sub_grp++;
		$sub_grp_class++;
	}
	$grp++;
}
?>
</table>


<div class="buttons" style="margin:0px 0px 0px 20px;">
    <?php echo form_submit(array('name'=>'submit','id'=>'submit','class'=>'btn btn-default'),'Save');?>
    <?php echo form_button(array('name'=>'button','id'=>'button','value'=>'true','type'=>'reset','content'=>'Reset','class'=>'btn btn-default'));?>
    <?php echo form_button(array('name'=>'button','id'=>'button','value'=>'true','type'=>'reset','content'=>'Cancel','class'=>'btn btn-default','onclick'=>"window.location.href='".site_url('user_roles')."'"));?>
</div>

<?php echo form_close(); ?>
