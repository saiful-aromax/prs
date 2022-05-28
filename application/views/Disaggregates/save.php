<?php echo form_open("disaggregates/" . $action); ?>
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
            <label class="col-sm-2 control-label required_field">Tier:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregate_tiers'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('id_disaggregate_tiers', ['' => '--Select--'] + $tiers, set_value('id_disaggregate_tiers', isset($row->id_disaggregate_tiers) ? $row->id_disaggregate_tiers : ''), ['id' => 'id_disaggregate_tiers', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_disaggregate_group_by_tier_id(this.value)']); ?>
                <?php echo(form_error('id_disaggregate_tiers')); ?>

            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Disaggregate Group:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregate_groups'))) { ?> has-error <?php } ?>">
                <span id="disaggregate_group"><?php echo form_dropdown('id_disaggregate_groups', ['' => '--Select--'] + $disaggregate_groups, set_value('id_disaggregate_groups', isset($row->id_disaggregate_groups) ? $row->id_disaggregate_groups : ''), ['id' => 'id_disaggregate_groups', 'class' => 'input_textbox form-control']); ?></span>
                <?php echo(form_error('id_disaggregate_groups')); ?>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('disaggregates') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>


<script>
    function ajax_get_disaggregate_group_by_tier_id(id_disaggregate_tiers) {
        if (id_disaggregate_tiers != '') {
            $.ajax({
                url: "<?php echo base_url('disaggregate_sets/ajax_get_disaggregate_group_by_tier_id'); ?>",
                type: "post",
                data: {id_disaggregate_tiers: id_disaggregate_tiers},
                success: function (response) {
                    $('#disaggregate_group').html(response);
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#disaggregate_group').html("<select name=\"id_disaggregate_groups\" class=\"input_textbox form-control\"><option value=\"\" selected=\"selected\">--Select--</option></select>");
        }
    }
</script>