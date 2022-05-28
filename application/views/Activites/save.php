<?php echo form_open("Activites/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Activity Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'name', 'id' => 'name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('name', (isset($row->name) ? $row->name : ""))); ?>
                <?php echo(form_error('name')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Activity Code:</label>
            <div class="input-group col-sm-6 <?php if (form_error('code')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'code', 'id' => 'code', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('code', (isset($row->code) ? $row->code : ""))); ?>
                <?php echo(form_error('code')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Organization Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('organization_name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'organization_name', 'id' => 'organization_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('organization_name', (isset($row->organization_name) ? $row->organization_name : ""))); ?>
                <?php echo(form_error('organization_name')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">Activity Duration:</label>
            <div class="input-group col-sm-6 <?php if (form_error('duration')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'duration', 'id' => 'duration', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('duration', (isset($row->duration) ? $row->duration : ""))); ?>
                <?php echo(form_error('duration')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">Budget:</label>
            <div class="input-group col-sm-6 <?php if (form_error('budget')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'budget', 'id' => 'budget', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('budget', (isset($row->budget) ? $row->budget : ""))); ?>
                <?php echo(form_error('budget')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">Web Address:</label>
            <div class="input-group col-sm-6 <?php if (form_error('web_address')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'web_address', 'id' => 'web_address', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('web_address', (isset($row->web_address) ? $row->web_address : ""))); ?>
                <?php echo(form_error('web_address')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">Address:</label>
            <div class="input-group col-sm-6 <?php if (form_error('address')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'address', 'id' => 'address', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('address', (isset($row->address) ? $row->address : ""))); ?>
                <?php echo(form_error('address')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">M&E Person:</label>
            <div class="input-group col-sm-6 <?php if (form_error('me_person')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'me_person', 'id' => 'me_person', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('me_person', (isset($row->me_person) ? $row->me_person : ""))); ?>
                <?php echo(form_error('me_person')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">M&E Phone Number:</label>
            <div class="input-group col-sm-6 <?php if (form_error('me_phone_no')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'me_phone_no', 'id' => 'me_phone_no', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('me_phone_no', (isset($row->me_phone_no) ? $row->me_phone_no : ""))); ?>
                <?php echo(form_error('me_phone_no')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">M&E Email:</label>
            <div class="input-group col-sm-6 <?php if (form_error('me_email')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'me_email', 'id' => 'me_email', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('me_email', (isset($row->me_email) ? $row->me_email : ""))); ?>
                <?php echo(form_error('me_email')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">CoP Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cop_name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cop_name', 'id' => 'cop_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cop_name', (isset($row->cop_name) ? $row->cop_name : ""))); ?>
                <?php echo(form_error('cop_name')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">CoP Phone Number:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cop_phone_no')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cop_phone_no', 'id' => 'cop_phone_no', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cop_phone_no', (isset($row->cop_phone_no) ? $row->cop_phone_no : ""))); ?>
                <?php echo(form_error('cop_phone_no')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">CoP Email:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cop_email')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cop_email', 'id' => 'cop_email', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cop_email', (isset($row->cop_email) ? $row->cop_email : ""))); ?>
                <?php echo(form_error('cop_email')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">CoR Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cor_name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cor_name', 'id' => 'cor_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cor_name', (isset($row->cor_name) ? $row->cor_name : ""))); ?>
                <?php echo(form_error('cor_name')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">CoR Phone Number:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cor_phone_no')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cor_phone_no', 'id' => 'cor_phone_no', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cor_phone_no', (isset($row->cor_phone_no) ? $row->cor_phone_no : ""))); ?>
                <?php echo(form_error('cor_phone_no')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label required_field">CoR Email:</label>
            <div class="input-group col-sm-6 <?php if (form_error('cor_email')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'cor_email', 'id' => 'cor_email', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('cor_email', (isset($row->cor_email) ? $row->cor_email : ""))); ?>
                <?php echo(form_error('cor_email')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">AoR Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('aor_name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'aor_name', 'id' => 'aor_name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('aor_name', (isset($row->aor_name) ? $row->aor_name : ""))); ?>
                <?php echo(form_error('aor_name')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label ">AoR Phone Number:</label>
            <div class="input-group col-sm-6 <?php if (form_error('aor_phone_no')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'aor_phone_no', 'id' => 'aor_phone_no', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('aor_phone_no', (isset($row->aor_phone_no) ? $row->aor_phone_no : ""))); ?>
                <?php echo(form_error('aor_phone_no')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">AoR Email:</label>
            <div class="input-group col-sm-6 <?php if (form_error('aor_email')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'aor_email', 'id' => 'aor_email', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('aor_email', (isset($row->aor_email) ? $row->aor_email : ""))); ?>
                <?php echo(form_error('aor_email')); ?>

            </div>
        </div><div class="form-group">
            <label class="col-sm-2 control-label required_field">Acme Email:</label>
            <div class="input-group col-sm-6 <?php if (form_error('acme_email')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'acme_email', 'id' => 'acme_email', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('acme_email', (isset($row->acme_email) ? $row->acme_email : ""))); ?>
                <?php echo(form_error('acme_email')); ?>

            </div>
        </div>
        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('Activites') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>