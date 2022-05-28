<?php
//echo $this->validation->error_string;
echo form_open('user_roles/add');
//print_r($parent_list);
$parent_options[''] = '---Select---';
if (isset($parent_list) && is_array($parent_list)) {
    foreach ($parent_list as $row) {
        $parent_options[$row['id']] = $row['role_name'];
    }
}
?>
    <div class="box box-info">
        <div class="box-body col-sm-offset-2">
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Parent:</label>
                <div class="input-group col-sm-6 <?php if (form_error('name')) { ?> has-error <?php } ?>">
                    <?php echo form_dropdown('cbo_parent', $parent_options, null, ['id' => 'cbo_parent', 'class' => 'input_textbox form-control']); ?>
                    <?php echo form_error('cbo_parent'); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Role Name</label>
                <div class="input-group col-sm-6 <?php if (form_error('txt_role_name')) { ?> has-error <?php } ?>">
                    <?php echo form_input(['name' => 'txt_role_name', 'id' => 'txt_role_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('txt_role_name')); ?>
                    <?php if (form_error('txt_role_name')) {
                        echo(form_error('txt_role_name'));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Role Description</label>
                <div class="input-group col-sm-6 <?php if (form_error('txt_role_description')) { ?> has-error <?php } ?>">
                    <?php echo form_input(['name' => 'txt_role_description', 'id' => 'txt_role_description', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('txt_role_description')); ?>
                    <?php if (form_error('txt_role_description')) {
                        echo(form_error('txt_role_description'));
                    } ?>
                </div>
            </div>
            <div class="form-group" style="margin-bottom: 15px;">
                <div class="input-group col-sm-offset-4 col-sm-4">
                    <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], $this->lang->line('label_save')); ?>
                    <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('user_roles') . "'"]); ?>
                </div>
            </div>
        </div>
    </div>
<?php echo form_close(); ?>