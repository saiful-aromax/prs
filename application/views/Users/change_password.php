<?php echo form_open("users/change_password/"); ?>
    <div class="box box-info">
        <div class="box-body col-sm-offset-2">
            <div class="form-group">
                <label class="col-sm-2 control-label">User Login</label>
                <div class="input-group col-sm-6">
                    <?php echo form_input(['name' => 'login', 'class' => 'input_textbox form-control', 'disabled' => 'disabled'], $row->login); ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">User Full Name</label>
                <div class="input-group col-sm-6">
                    <?php echo form_input(['name' => 'full_name', 'class' => 'input_textbox form-control', 'disabled' => 'disabled'], $row->full_name); ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Old Password</label>
                <div class="input-group col-sm-6 <?php if (form_error('old_password')) { ?> has-error <?php } ?>">
                    <?php echo form_input(['name' => 'old_password', 'id' => 'old_password', 'type' => 'password', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('old_password')); ?>
                    <?php if (form_error('old_password')) {
                        echo(form_error('old_password'));
                    } ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label required_field">New Password</label>
                <div class="input-group col-sm-6 <?php if (form_error('password')) { ?> has-error <?php } ?>">
                    <?php echo form_input(['name' => 'password', 'off', 'type' => 'password', 'id' => 'password', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('password')); ?>
                    <?php if (form_error('password')) {
                        echo(form_error('password'));
                    } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label required_field">Verify New Password</label>
                <div class="input-group col-sm-6 <?php if (form_error('verify_password')) { ?> has-error <?php } ?>">
                    <?php echo form_input(['name' => 'verify_password', 'id' => 'verify_password', 'type' => 'password', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('verify_password')); ?>
                    <?php if (form_error('verify_password')) {
                        echo(form_error('verify_password'));
                    } ?>
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <div class="input-group col-sm-offset-4 col-sm-4">
                    <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                    <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('/') . "'"]); ?>
                </div>
            </div>


        </div>
    </div>
<?php echo form_close(); ?>