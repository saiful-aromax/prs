<?php echo form_open("users/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Full Name:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('full_name'))) { ?> has-error <?php } ?>">
                <?php echo form_input(array('name' => 'full_name', 'id' => 'full_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'), set_value('full_name', isset($row->full_name) ? $row->full_name : '')); ?>
                <?php echo(form_error('full_name')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Login:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('login'))) { ?> has-error <?php } ?>">
                <?php
                $login_attribute = $action == 'add' ? ['name' => 'login', 'class' => 'input_textbox form-control', 'maxlength' => '40'] : ['name' => 'login', 'class' => 'input_textbox form-control', 'maxlength' => '40', 'readonly' => 'readonly'];
                echo form_input($login_attribute, set_value('login', isset($row->login) ? $row->login : '')); ?>
                <?php echo form_error('login'); ?>
            </div>
        </div>
        <?php if ($action == 'add') { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Password:</label>
                <div class="input-group col-sm-6 <?php if ((form_error('password'))) { ?> has-error <?php } ?>">
                    <?php echo form_password(array('name' => 'password', 'class' => 'input_textbox form-control', 'maxlength' => '40'), set_value('password')); ?>
                    <?php echo(form_error('password')); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Confirm Password:</label>
                <div class="input-group col-sm-6 <?php if ((form_error('confirm_password'))) { ?> has-error <?php } ?>">
                    <?php echo form_password(array('name' => 'confirm_password', 'class' => 'input_textbox form-control', 'maxlength' => '40'), set_value('confirm_password')); ?>
                    <?php echo(form_error('confirm_password')); ?>
                </div>
            </div>
        <?php } ?>


        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Email:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('email'))) { ?> has-error <?php } ?>">
                <?php echo form_input('email', set_value('email', isset($row->email) ? $row->email : ''), ['type' => "email", 'name' => 'email', 'class' => 'input_textbox form-control', 'maxlength' => '40']); ?>
                <?php echo(form_error('email')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">User Role:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('role_id'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('role_id', ['' => '--Select--'] + $user_roles, set_value('role_id', isset($row->role_id) ? $row->role_id : ''), ['class' => 'input_textbox form-control', 'onchange' => 'show_hide_projects(this.value)', 'id' => 'role_id']); ?>
                <?php echo(form_error('role_id')); ?>
            </div>
        </div>

        <div class="form-group" id="project_html" <?php if ($action == 'add' ||($action != 'add' && $row->role_id !='2')){ ?>style="display: none"<?php } ?> >
            <label class="col-sm-2 control-label required_field">Project:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('project_id'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('project_id', ['' => '--Select--'] + $projects, set_value('project_id', isset($row->project_id) ? $row->project_id : ''), ['class' => 'input_textbox form-control', 'id' => 'project_id']); ?>
                <?php echo(form_error('project_id')); ?>
            </div>
        </div>

        <?php if ($action != 'add') { ?>
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Status:</label>
                <div class="input-group col-sm-6 <?php if ((form_error('status'))) { ?> has-error <?php } ?>">
                    <?php echo form_dropdown('status', ['A' => 'Active', 'I' => 'Inactive'], set_value('status', isset($row->status) ? $row->status : ''), ['class' => 'input_textbox form-control']); ?>
                    <?php echo(form_error('status')); ?>
                </div>
            </div>
        <?php } ?>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('users') . "'"]); ?>
            </div>
        </div>
    </div>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">

    function show_hide_projects(role_id) {
        if (role_id && role_id != '1') {
            $("#project_html").show();
        } else {
            $("#project_html").hide();
        }

    }
</script>