<!--
/**
 * Created by PhpStorm.
 * User: sara
 * Date: 5/28/17
 *Time: 3:00 PM
 */-->

<?php echo form_open("project_indicators/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Activity:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_projects'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('id_projects', ['' => '--Select--'] + $projects, set_value('id_projects',isset($row->id_projects) ? $row->id_projects : ''), ['id' => 'id_projects', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_code_by_id()']); ?>
                <?php echo(form_error('id_projects')); ?>

            </div>
        </div>


        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Indicator:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('id_indicators'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('id_indicators', ['' => '--Select--'] + $indicators, set_value('id_indicators',isset($row->id_indicators) ? $row->id_indicators : ''), ['id' => 'id_indicators', 'class' => 'input_textbox form-control', 'onchange' => 'ajax_get_code_by_id()']); ?>
                <?php echo(form_error('id_indicators')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Reporting Period:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('reporting_period'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('reporting_period', ['' => '--Select--', 'Year' => 'Year', 'Half Yearly' => 'Half Yearly', 'Quarterly' => 'Quarterly'],set_value('reporting_period',isset($row->reporting_period) ? $row->reporting_period : ''), ['class' => 'input_textbox form-control', 'id' => 'reporting_period', 'onchange' => 'ajax_get_code_by_id()']); ?>
                <?php echo(form_error('reporting_period')); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label required_field">Indicator Type:</label>
            <div class="input-group col-sm-6 <?php if ((form_error('indicator_type'))) { ?> has-error <?php } ?>">
                <?php echo form_dropdown('indicator_type', ['' => '--Select--', 'Custom' => 'Custom', 'Standard' => 'Standard'], set_value('indicator_type',isset($row->indicator_type) ? $row->indicator_type : ''), ['class' => 'input_textbox form-control', 'id' => 'indicator_type', 'onchange' => 'ajax_get_code_by_id()']); ?>
                <?php echo(form_error('indicator_type')); ?>

            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-2 control-label">Code:</label>
            <div class="input-group col-sm-6 <?php if (form_error('code')) { ?> has-error <?php } ?>">
                <?php echo form_input(['name' => 'code', 'id' => 'code', 'class' => 'input_textbox form-control', 'maxlength' => '100'], set_value('code', (isset($row->code) ? $row->code : "")), "readonly"); ?>
                <?php echo(form_error('code')); ?>

            </div>
        </div>


        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-4 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('project_indicators') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>


<script>
    function ajax_get_code_by_id() {
        var id_projects = $("#id_projects").val();
        var id_indicators = $("#id_indicators").val();
        var reporting_text = $("#reporting_period option:selected").text();
        var indicator_type_text = $("#indicator_type option:selected").text();
        if (id_projects != '' && id_indicators != '' && reporting_text != '--Select--' && indicator_type_text != '--Select--') {
            $.ajax({
                url: "<?php echo base_url('project_indicators/ajax_get_code_by_id'); ?>",
                type: "post",
                data: {
                    id_projects: id_projects,
                    id_indicators: id_indicators,
                    reporting_text: reporting_text,
                    indicator_type_text: indicator_type_text
                },
                success: function (response) {
                    $('#code').val(response);
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