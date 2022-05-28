<?php echo form_open("disaggregate_groups/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Name:</label>
            <div class="input-group col-sm-6 <?php if (form_error('name')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'name', 'id' => 'name', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('name', (isset($row->name) ? $row->name : ""))); ?>
                <?php echo(form_error('name')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Code:</label>
            <div class="input-group col-sm-6 <?php if (form_error('code')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'code', 'id' => 'code', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('code', (isset($row->code) ? $row->code : ""))); ?>
                <?php echo(form_error('code')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Select Tier:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregate_tiers'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('id_disaggregate_tiers', ['' => '--Select--'] + $tiers, set_value('id_disaggregate_tiers',isset($row->id_disaggregate_tiers) ? $row->id_disaggregate_tiers : ''), ['class' => 'input_textbox form-control']); ?><?php echo(form_error('id_disaggregate_tiers')); ?>

            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('disaggregate_groups') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>