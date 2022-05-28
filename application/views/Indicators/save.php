<?php echo form_open("indicators/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Indicator Name:(max 500 character)</label>
            <div class="input-group col-sm-6 <?php if ((form_error('name'))) { ?> has-error <?php } ?>">
                <?php echo form_textarea(['name' => 'name', 'id' => 'name','rows'=>3, 'class' => 'input_textbox form-control', 'maxlength' => '500'], set_value('name', (isset($row->name) ? $row->name : ""))); ?>
                <?php echo(form_error('name')); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Indicator Code:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('code'))) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'code', 'id' => 'code', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('code', (isset($row->code) ? $row->code : ""))); ?>
                <?php echo(form_error('code')); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label ">Project Type :</label>
            <div class="input-group col-sm-6 <?php if ((form_error('project_type'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('project_type', ['' => '--Select Class--', 'FTF' => 'FTF', 'GCC' => 'GCC'], set_value('project_type',isset($row->project_type) ? $row->project_type : null), ['class' => 'input_textbox form-control']); ?>
                <?php echo(form_error('project_type')); ?>

            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label ">Result Type :</label>
            <div class="input-group col-sm-6 <?php if ((form_error('result_type'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('result_type', ['' => '--Select Type--', 'Impact' => 'Impact', 'Outcome' => 'Outcome', 'Output' => 'Output'], set_value('result_type',isset($row->result_type) ? $row->result_type : null), ['class' => 'input_textbox form-control']); ?>
                <?php echo(form_error('result_type')); ?>

            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['id' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('indicators') . "'"]); ?>
            </div>
        </div>
    </div>

</div>