<!--
/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/28/17
 * Time: 11:17 AM
 */-->
<?php echo form_open("disaggregate_sets/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Tier:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregate_tiers'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('id_disaggregate_tiers', ['' => '--Select--'] + $tiers, (isset($row->id_disaggregate_tiers) ? $row->id_disaggregate_tiers : ''), ['id' => 'id_disaggregate_tiers', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_disaggregate_group_by_tier_id(this.value);']); ?>
                <?php echo(form_error('id_disaggregate_tiers')); ?>

            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Disaggregate Group:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregate_groups'))) { ?> has-error <?php } ?>">
                <span id="disaggregate_group"><?php echo form_dropdown('id_disaggregate_groups', ['' => '--Select--'] + $disaggregate_groups, set_value('id_disaggregate_groups', isset($row->id_disaggregate_groups) ? $row->id_disaggregate_groups : ''), ['id' => 'id_disaggregate_groups', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_disaggregate_by_disaggregate_group_id(this.value);']); ?></span>
                <?php echo(form_error('id_disaggregate_groups')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Disaggregate:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_disaggregates'))) { ?> has-error <?php } ?>">
                <span id="disaggregate"><?php echo form_dropdown('id_disaggregates', ['' => '--Select--'] + $disaggregates, set_value('id_disaggregates', isset($row->id_disaggregates) ? $row->id_disaggregates : ''), ['class' => 'input_textbox form-control', 'id' => 'id_disaggregates', 'onchange' => 'ajax_get_code_by_disaggregate_id()']); ?></span>
                <?php echo(form_error('id_disaggregates')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Unit:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('unit_id'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('unit_id', ['' => '--Select--'] + $units, set_value('unit_id', isset($row->unit_id) ? $row->unit_id : ''), ['class' => 'input_textbox form-control', 'id' => 'unit_id', 'onchange' => 'ajax_get_code_by_disaggregate_id()']); ?>
                <?php echo(form_error('unit_id')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Code</label>
            <div class="input-group col-sm-6 <?php if (form_error('code')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'code', 'id' => 'code', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('code', (isset($row->code) ? $row->code : "")), "readonly"); ?>
                <?php echo(form_error('code')); ?>

            </div>
        </div>


        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('disaggregate_sets') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script type="text/javascript">

    function ajax_get_disaggregate_group_by_tier_id(id_disaggregate_tiers) {
        if (id_disaggregate_tiers != '') {
            $.ajax({
                url: "<?php echo base_url('disaggregate_sets/ajax_get_disaggregate_group_by_tier_id'); ?>",
                type: "post",
                data: {id_disaggregate_tiers: id_disaggregate_tiers},
                success: function (response) {
                    $('#disaggregate_group').html(response);
                    ajax_get_code_by_disaggregate_id();
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#disaggregate_group').html("<select name=\"id_disaggregate_groups\" class=\"input_textbox form-control\"><option value=\"\" selected=\"selected\">--Select--</option></select>");
            ajax_get_code_by_disaggregate_id();
        }
    }

    function ajax_get_disaggregate_by_disaggregate_group_id(id_disaggregate_groups) {
//        alert(id_disaggregate_groups);
        if (id_disaggregate_groups != '') {
            $.ajax({
                url: "<?php echo base_url('disaggregate_sets/ajax_get_disaggregate_by_disaggregate_group_id'); ?>",
                type: "post",
                data: {id_disaggregate_groups: id_disaggregate_groups},
                success: function (response) {
                    $('#disaggregate').html(response);
                    ajax_get_code_by_disaggregate_id();
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#disaggregate').html("<select name=\"id_disaggregates\" class=\"input_textbox form-control\"><option value=\"\" selected=\"selected\">--Select--</option></select>");
            ajax_get_code_by_disaggregate_id();
        }
    }

    function ajax_get_code_by_disaggregate_id() {
        var id_disaggregate_tiers = $("#id_disaggregate_tiers").val();
        var id_disaggregate_groups = $("#id_disaggregate_groups").val();
        var id_disaggregates = $("#id_disaggregates").val();
        var unit_text = $("#unit_id option:selected").text();
        if (id_disaggregate_tiers && id_disaggregate_groups && id_disaggregates && unit_text != '--Select--') {
            $.ajax({
                url: "<?php echo base_url('disaggregate_sets/ajax_get_code_by_disaggregate_id'); ?>",
                type: "post",
                data: {id_disaggregates: id_disaggregates},
                success: function (response) {
                    $('#code').val(response + '-' + unit_text);
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        } else {
            $('#code').val('');
        }
    }
</script>
