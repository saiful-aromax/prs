<!--/**
 * Created by PhpStorm.
 * User: nur
 * Date: 5/31/17
 * Time: 10:23 AM
 */-->
<style type="text/css">
    .multiselect_check {
        width: 100%;
        height: 10em;
        border: solid 1px #c0c0c0;
        overflow: auto;
    }

    .multiselect label {
        font-weight: normal;
        display: block;
    }

    .multiselect-on {
        color: #ffffff;
        background-color: #7487b8;
    }
</style>
<?php echo form_open("project_indicator_disaggregate_sets/" . $action); ?>
<div class="box box-info">
    <div class="box-body col-sm-offset-2">
        <div class="form-group">
            <label class="col-sm-3 control-label required_field"> Project Indicator Code</label>
            <div class="input-group col-sm-6 <?php if (form_error('id_project_indicators')) { ?> has-error <?php } ?>">
                <?php ($action != 'add') ? $disabled = 'disabled' : $disabled = '';
                echo form_dropdown('id_project_indicators', ['' => '--Select--'] + $project_indicator, set_value('id_project_indicators', isset($row->id_project_indicators) ? $row->id_project_indicators : ''), ['id' => 'id_project_indicators', 'class' => 'input_textbox form-control', $disabled => $disabled, 'onchange' => 'ajax_get_disaggregate_list_by_project_indicator_id(this.value)']); ?>
                <?php echo(form_error('id_project_indicators')); ?>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label required_field">Commodity Code</label>
            <div class="input-group col-sm-6 <?php if (form_error('id_commodity')) { ?> has-error <?php } ?>">
                <div class="multiselect_check">

                    <?php foreach ($commodities as $id => $value) {
                        $is_check = false;
                        if ($action != 'add') {
                            if (in_array($value, $selected_commodity)) {
                                $is_check = true;
                            }
                        }
                        ?>
                        <label style="font-weight: normal; width: 100%; margin: 0%;" <?php if ($is_check) { ?> class="multiselect-on" <?php } ?>><input
                                    class="checkbox_class input_textbox"
                                    type="checkbox"
                                    name="id_commodity[]"
                                    id="id_commodity"
                                    value="<?php echo $id; ?>" <?php if ($is_check) { ?> checked <?php } ?>/><?php echo $value; ?>
                        </label><br>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label required_field">Disaggregate Set Code</label>
            <div class="input-group col-sm-6 <?php if (form_error('id_disaggregate_sets')) { ?> has-error <?php } ?>">
                <div class="multiselect_check">
                    <?php foreach ($disaggregate_sets as $id => $value) {
                        $is_check = false;
                        if ($action != 'add') {
                            if (in_array($value, $selected_disaggregate_set)) {
                                $is_check = true;
                            }
                        }
                        ?>
                        <label style="font-weight: normal; width: 100%; margin: 0%" <?php if ($is_check) { ?> class="multiselect-on" <?php } ?>><input
                                    class="checkbox_class input_textbox"
                                    type="checkbox"
                                    name="id_disaggregate_sets[]"
                                    id="id_disaggregate_sets"
                                    value="<?php echo $id; ?>" <?php if ($is_check) { ?> checked <?php } ?>/><?php echo $value; ?>
                        </label><br>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="form-group" style="margin-bottom: 15px;">
            <div class="input-group col-sm-offset-5 col-sm-4">
                <?php echo form_submit(['value' => 'submit', 'class' => 'btn btn-success pull-right submit_buttons positive'], 'Save'); ?>
                <?php echo form_button(['name' => 'button', 'id' => 'button', 'value' => 'true', 'type' => 'reset', 'content' => 'Cancel', 'class' => 'btn btn-danger pull-right cancel_buttons', 'onclick' => "window.location.href='" . site_url('project_indicator_disaggregate_sets') . "'"]); ?>
            </div>
        </div>
        <?php echo form_close(); ?>
    </div>
</div>

<script src="<?php echo base_url('assets/plugins/jQuery/jquery-2.2.3.min.js'); ?>"></script>
<script>

    $(".checkbox_class").click(function () {
        if ($(this).is(':checked')) {
            $(this).parent().addClass("multiselect-on");
        } else {
            $(this).parent().removeClass("multiselect-on");
        }
    });

    function ajax_get_disaggregate_list_by_project_indicator_id(project_indicator_id) {
        if (project_indicator_id != '') {
            $.ajax({
                url: "<?php echo base_url('Project_indicator_disaggregate_sets/ajax_get_disaggregate_list_by_project_indicator_id'); ?>",
                type: "post",
                data: {project_indicator_id: project_indicator_id},
                success: function (response) {
                    $('#disaggregate_sets').html();
                    $('#disaggregate_sets').html(response);
                },
                error: function (xhr) {
                    alert("Something went wrong!");
                }
            });
        }
    }

</script>